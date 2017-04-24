using ClientApiGenerator.Models;
using ClientApiGenerator.Render;
using ClientApiGenerator.Swagger;
using CommandLine;
using CommandLine.Text;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net.Http;
using System.Text;

namespace ClientApiGenerator
{
    class Program
    {
        public static HttpClient _client = new HttpClient();

        static void Main(string[] args)
        {
            // Parse options and show helptext if insufficient
            Options o = new Options();
            Parser.Default.ParseArguments(args, o);
            if (!o.IsValid()) {
                var help = HelpText.AutoBuild(o);
                Console.WriteLine(help.ToString());
                return;
            }

            // First parse the file
            SwaggerRenderTask task = ParseRenderTask(o);
            if (task == null) {
                return;
            }

            // Download the swagger file
            SwaggerInfo api = DownloadSwaggerJson(o, task);
            if (api == null) {
                return;
            }

            // Render output
            Console.WriteLine($"***** Beginning render stage");
            Render(task, api);
        }

        private static SwaggerInfo DownloadSwaggerJson(Options o, SwaggerRenderTask task)
        {
            string swaggerJson = null;
            SwaggerInfo api = null;

            // Download the swagger JSON file from the server
            try {
                Console.WriteLine($"***** Downloading swagger JSON from {o.SwaggerRenderPath}");
                var response = _client.GetAsync(task.swaggerUri).Result;
                swaggerJson = response.Content.ReadAsStringAsync().Result;
            } catch (Exception ex) {
                Console.WriteLine($"Exception downloading swagger JSON file: {ex.ToString()}");
                return null;
            }

            // Parse the swagger JSON file
            try {
                Console.WriteLine($"***** Processing swagger JSON");
                api = ProcessSwagger(swaggerJson);
            } catch (Exception ex) {
                Console.WriteLine($"Exception processing swagger JSON file: {ex.ToString()}");
            }
            return api;
        }

        private static SwaggerRenderTask ParseRenderTask(Options o)
        {
            SwaggerRenderTask task = null;
            try {
                Console.WriteLine($"***** Parsing render file: {o.SwaggerRenderPath}");
                var contents = File.ReadAllText(o.SwaggerRenderPath);
                task = JsonConvert.DeserializeObject<SwaggerRenderTask>(contents);

                // Create all razor templates
                string baseFolder = Path.GetDirectoryName(o.SwaggerRenderPath);
                foreach (var target in task.targets) {
                    target.ParseRazorTemplates(baseFolder);
                }
                return task;

            // If anything blew up, refuse to continue
            } catch (Exception ex) {
                Console.WriteLine($"Exception parsing render task: {ex.Message}");
                return null;
            }
        }

        #region Render targets
        public static void Render(SwaggerRenderTask task, SwaggerInfo api)
        {
            // Render each target
            foreach (var target in task.targets) {
                Console.WriteLine($"***** Rendering {target.name}");
                target.Render(api);
            }

            // Done
            Console.WriteLine("***** Done");
        }
        #endregion

        #region Parse swagger
        public static SwaggerInfo ProcessSwagger(string swagger)
        {
            // Read in the swagger object
            var settings = new JsonSerializerSettings();
            settings.MetadataPropertyHandling = MetadataPropertyHandling.Ignore;
            var obj = JsonConvert.DeserializeObject<Swagger.SwaggerModel>(swagger, settings);
            var api = Cleanup(obj);

            // Sort methods by category name and by name
            api.Methods = (from m in api.Methods orderby m.Category, m.Name select m).ToList();

            // Produce a distinct list of categories to simplify work
            api.Categories = (from m in api.Methods orderby m.Category select m.Category).Distinct().ToList();
            return api;
        }

        private static SwaggerInfo Cleanup(SwaggerModel obj)
        {
            SwaggerInfo result = new SwaggerInfo();
            result.ApiVersion = obj.ApiVersion;

            // Loop through all paths and spit them out to the console
            foreach (var path in (from p in obj.paths orderby p.Key select p)) {
                foreach (var verb in path.Value) {

                    // Set up our API
                    MethodInfo api = new MethodInfo();
                    api.URI = path.Key;
                    api.HttpVerb = verb.Key;
                    api.Summary = verb.Value.summary;
                    api.Description = verb.Value.description;
                    api.Params = new List<ParameterInfo>();
                    api.QueryParams = new List<ParameterInfo>();
                    api.Category = verb.Value.tags.FirstOrDefault();
                    api.Name = verb.Value.operationId;

                    // Now figure out all the URL parameters
                    foreach (var parameter in verb.Value.parameters) {

                        // Construct parameter
                        var pi = ResolveType(parameter);

                        // Query String Parameters
                        if (parameter.paramIn == "query") {
                            api.QueryParams.Add(pi);

                            // URL Path parameters
                        } else if (parameter.paramIn == "path") {
                            api.Params.Add(pi);

                            // Body parameters
                        } else if (parameter.paramIn == "body") {
                            pi.ParamName = "model";
                            api.BodyParam = pi;
                        }

                        // Is this property an enum?
                        if (parameter.EnumDataType != null) {
                            ExtractEnum(result.Enums, parameter);
                        }
                    }

                    // Now figure out the response type
                    SwaggerResult ok = null;
                    if (verb.Value.responses.TryGetValue("200", out ok)) {
                        api.ResponseType = ok.schema == null ? null : ok.schema.type;
                        api.ResponseTypeName = ResolveTypeName(ok.schema);
                    } else if (verb.Value.responses.TryGetValue("201", out ok)) {
                        api.ResponseType = ok.schema == null ? null : ok.schema.type;
                        api.ResponseTypeName = ResolveTypeName(ok.schema);
                    }

                    // Done with this API
                    result.Methods.Add(api);
                }
            }

            // Loop through all the schemas
            foreach (var def in obj.definitions) {
                var m = new ModelInfo()
                {
                    SchemaName = def.Key,
                    Comment = def.Value.description,
                    Example = def.Value.example,
                    Description = def.Value.description,
                    Required = def.Value.required,
                    Type = def.Value.type,
                    Properties = new List<ParameterInfo>()
                };
                foreach (var prop in def.Value.properties) {
                    if (!prop.Value.required && def.Value.required != null) {
                        prop.Value.required = def.Value.required.Contains(prop.Key);
                    }

                    // Construct property
                    var pi = ResolveType(prop.Value);
                    pi.ParamName = prop.Key;
                    m.Properties.Add(pi);

                    // Is this property an enum?
                    if (prop.Value.EnumDataType != null) {
                        ExtractEnum(result.Enums, prop.Value);
                    }
                }

                result.Models.Add(m);
            }

            //// Now add the enums we know we need.
            //// Because of the complex way this Dictionary<> is rendered in Swagger, it's hard to pick up the correct values.
            //var tat = (from e in result.Enums where e.EnumDataType == "TransactionAddressType" select e).FirstOrDefault();
            //if (tat == null) {
            //    tat = new EnumInfo()
            //    {
            //        EnumDataType = "TransactionAddressType",
            //        Items = new List<EnumItem>()
            //    };
            //    result.Enums.Add(tat);
            //}
            //tat.AddItem("ShipFrom", "This is the location from which the product was shipped");
            //tat.AddItem("ShipTo", "This is the location to which the product was shipped");
            //tat.AddItem("PointOfOrderAcceptance", "Location where the order was accepted; typically the call center, business office where purchase orders are accepted, server locations where orders are processed and accepted");
            //tat.AddItem("PointOfOrderOrigin", "Location from which the order was placed; typically the customer's home or business location");
            //tat.AddItem("SingleLocation", "Only used if all addresses for this transaction were identical; e.g. if this was a point-of-sale physical transaction");

            // Here's your processed API
            return result;
        }

        private static void ExtractEnum(List<EnumInfo> enums, SwaggerProperty prop)
        {
            var enumType = (from e in enums where e.EnumDataType == prop.EnumDataType select e).FirstOrDefault();
            if (enumType == null) {
                enumType = new EnumInfo()
                {
                    EnumDataType = prop.EnumDataType,
                    Items = new List<EnumItem>()
                };
                enums.Add(enumType);
            }

            // Add values if they are known
            if (prop.enumValues != null) {
                foreach (var s in prop.enumValues) {
                    if (!enumType.Items.Any(i => i.Value == s)) {
                        enumType.Items.Add(new EnumItem() { Value = s });
                    }
                }
            }
        }

        private static ParameterInfo ResolveType(SwaggerProperty prop)
        {
            var pi = new ParameterInfo()
            {
                Comment = prop.description ?? "",
                ParamName = prop.name,
                Type = prop.type,
                TypeName = ResolveTypeName(prop),
                Required = prop.required,
                ReadOnly = prop.readOnly,
                MaxLength = prop.maxLength,
                MinLength = prop.minLength,
                Example = prop.example == null ? "" : prop.example.ToString()
            };
            if (prop.type == "array") {
                pi.IsArrayType = true;
                pi.ArrayElementType = ResolveTypeName(prop.items).Replace("?", "");
            }
            return pi;
        }

        private static string ResolveTypeName(SwaggerProperty prop)
        {
            StringBuilder typename = new StringBuilder();
            bool isValueType = false;

            // If this API produces a file download
            if (prop == null || prop.type == "file") {
                return "FileResult";
            }

            // Handle integers / int64s
            if (prop.type == "integer") {
                if (String.Equals(prop.format, "int64", StringComparison.CurrentCultureIgnoreCase)) {
                    typename.Append("Int64");
                } else if (String.Equals(prop.format, "byte", StringComparison.CurrentCultureIgnoreCase)) {
                    typename.Append("Byte");
                } else if (String.Equals(prop.format, "int16", StringComparison.CurrentCultureIgnoreCase)) {
                    typename.Append("Int16");
                } else if (prop.format == null || String.Equals(prop.format, "int32", StringComparison.CurrentCultureIgnoreCase)) {
                    typename.Append("Int32");
                } else {
                    Console.WriteLine("Unknown typename");
                }
                isValueType = true;

                // Handle decimals
            } else if (prop.type == "number") {
                typename.Append("Decimal");
                isValueType = true;

                // Handle boolean
            } else if (prop.type == "boolean") {
                typename.Append("Boolean");
                isValueType = true;

                // Handle date-times formatted as strings
            } else if (prop.format == "date-time" && prop.type == "string") {
                typename.Append("DateTime");
                isValueType = true;

                // Handle strings, and enums, which are represented as strings
            } else if (prop.type == "string") {

                // Base64 encoded bytes
                if (String.Equals(prop.format, "byte", StringComparison.CurrentCultureIgnoreCase)) {
                    typename.Append("Byte[]");
                } else if (prop.EnumDataType == null) {
                    return "String";
                } else {
                    typename.Append(prop.EnumDataType);
                    isValueType = true;
                }

                // But, if this is an array, nest it
            } else if (prop.type == "array") {
                typename.Append("List<");
                typename.Append(ResolveTypeName(prop.items).Replace("?", ""));
                typename.Append(">");

                // Is it a custom object?
            } else if (prop.schemaref != null) {
                string schema = prop.schemaref.Substring(prop.schemaref.LastIndexOf("/") + 1);
                if (schema.StartsWith("FetchResult")) {
                    schema = schema.Replace("[", "<");
                    schema = schema.Replace("]", ">");
                }
                typename.Append(schema);

                // Is this a nested swagger element?
            } else if (prop.schema != null) {
                typename.Append(ResolveTypeName(prop.schema));

                // Custom hack for objects that aren't represented correctly in swagger at the moment - still have to fix this in REST v2
            } else if (prop.description == "Default addresses for all lines in this document") {
                typename.Append("Dictionary<TransactionAddressType, AddressInfo>");

                // Custom hack for objects that aren't represented correctly in swagger at the moment - still have to fix this in REST v2
            } else if (prop.description == "Specify any differences for addresses between this line and the rest of the document") {
                typename.Append("Dictionary<TransactionAddressType, AddressInfo>");

                // All else is just a generic object
            } else if (prop.type == "object") {
                typename.Append("Dictionary<string, string>");

            // Catch severe problems or weird/unknown types
            } else {
                throw new NotImplementedException($"Type {prop.type} not implemented");
            }

            // Is this a basic value type that's not required?  Make it nullable
            if (isValueType && !prop.required) {
                typename.Append("?");
            }

            // Here's your type name
            return typename.ToString();
        }
        #endregion
    }
}
