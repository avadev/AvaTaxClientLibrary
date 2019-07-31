﻿using ClientApiGenerator.Models;
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
using static ClientApiGenerator.TemplateBase;

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
                Console.WriteLine($"***** Downloading swagger JSON from {task.swaggerUri}");
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

            // If anything blew up, refuse to continue
            } catch (Exception ex) {
                Console.WriteLine($"Exception loading your ApiGenerator file {o.SwaggerRenderPath}: {ex.Message}");
                return null;
            }

            // Create all razor templates
            string baseFolder = Path.GetDirectoryName(o.SwaggerRenderPath);
            foreach (var target in task.targets) {
                target.ParseRazorTemplates(baseFolder);
            }
            return task;
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

            // Parse API version if information is available
            if (result.ApiVersion != null) {

                // Set up alternative version numbers: This one does not permit dashes
                result.ApiVersionPeriodsOnly = result.ApiVersion.Replace("-", ".");

                // Set up alternative version numbers: This one permits only three segments
                var sb = new StringBuilder();
                int numPeriods = 0;
                foreach (char c in obj.ApiVersion) {
                    if (c == '.') numPeriods++;
                    if (numPeriods > 3 || c == '-') break;
                    sb.Append(c);
                }
                result.ApiVersionThreeSegmentsOnly = sb.ToString();
            }

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
                    api.Category = verb.Value.tags.FirstOrDefault();
                    api.Name = verb.Value.operationId;

                    // Now figure out all the URL parameters
                    foreach (var parameter in verb.Value.parameters) {

                        // Construct parameter
                        var pi = ResolveType(parameter);

                        // Query String Parameters
                        if (parameter.paramIn == "query") {
                            pi.ParameterLocation = ParameterLocationType.QueryString;

                            // URL Path parameters
                        } else if (parameter.paramIn == "path") {
                            pi.ParameterLocation = ParameterLocationType.UriPath;

                            // Body parameters
                        } else if (parameter.paramIn == "body") {
                            pi.ParamName = "model";
                            pi.ParameterLocation = ParameterLocationType.RequestBody;
                            api.BodyParam = pi;
                        } else if (parameter.paramIn == "header") {
                            pi.ParameterLocation = ParameterLocationType.Header;
                        } else if (parameter.paramIn == "formData") {
                            pi.ParameterLocation = ParameterLocationType.FormData;
                        } else {
                            throw new Exception("Unrecognized parameter location: " + parameter.paramIn);
                        }
                        api.Params.Add(pi);

                        if (parameter.enumMetadata != null)
                        {
                            if (!result.Enums.Any(e => e.Name == parameter.enumMetadata.Name))
                            {
                                result.Enums.Add(parameter.enumMetadata);
                            }
                        }
                    }

                    // Now figure out the response type
                    SwaggerResult ok = null;
                    if (verb.Value.responses.TryGetValue("200", out ok)
                        || verb.Value.responses.TryGetValue("201", out ok)
                        || verb.Value.responses.TryGetValue("202", out ok)
                        || verb.Value.responses.TryGetValue("204", out ok)) {

                        api.ResponseType = ok.schema?.type;
                        api.ResponseTypeName = ResolveTypeName(ok.schema);
                    }

                    // Ensure that body parameters are always last for consistency
                    if (api.BodyParam != null) {
                        api.Params.Remove(api.BodyParam);
                        api.Params.Add(api.BodyParam);
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

                    if (prop.Value.enumMetadata != null)
                    {
                        if (!result.Enums.Any(e => e.Name == prop.Value.enumMetadata.Name))
                        {
                            result.Enums.Add(prop.Value.enumMetadata);
                        }
                    }
                }

                result.Models.Add(m);
            }

            //// Now add the enums we know we need.
            //// Because of the complex way this Dictionary<> is rendered in Swagger, it's hard to pick up the correct values.
            //var tat = (from e in result.Enums where e.Name == "TransactionAddressType" select e).FirstOrDefault();
            //if (tat == null) {
            //    tat = new EnumInfo()
            //    {
            //        Name = "TransactionAddressType",
            //        Values = new List<EnumItem>()
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

            // Is this an array?
            if (prop.type == "array") {
                pi.IsArrayType = true;
                pi.ArrayElementType = ResolveTypeName(prop.items).Replace("?", "");
            } else if (pi.TypeName.StartsWith("List<")) {
                pi.IsArrayType = true;
                pi.ArrayElementType = pi.TypeName.Substring(5, pi.TypeName.Length - 6);
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
            } else if ((prop.format == "date-time" || prop.format == "date") && prop.type == "string") {
                typename.Append("DateTime");
                isValueType = true;

                // Handle strings, and enums, which are represented as strings
            } else if (prop.type == "string") {

                // Base64 encoded bytes
                if (String.Equals(prop.format, "byte", StringComparison.CurrentCultureIgnoreCase)) {
                    if (prop.description == "Content of the batch file." || prop.description == "This stream contains the bytes of the file being uploaded.")
                    {
                        typename.Append("Byte[]");
                    }
                    else
                    {
                        typename.Append("Byte");
                    }
                } else if (prop.enumMetadata == null) {
                    return "String";
                } else {
                    typename.Append(prop.enumMetadata.Name);
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
            } else if (prop.description == "The list of Avalara-defined tax code types.")
            {
                typename.Append("Dictionary<string, string>");

                // Custom hack for objects that aren't represented correctly in swagger at the moment - still have to fix this in REST v2
            } else if (prop.type == "object") {
                typename.Append("object");

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
