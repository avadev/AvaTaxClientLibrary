using ClientApiGenerator.Models;
using ClientApiGenerator.Render;
using ClientApiGenerator.Swagger;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;

namespace ClientApiGenerator
{
    class Program
    {
        static void Main(string[] args)
        {
            ProcessSwagger(args[0], args[1]);
        }

        public static void ProcessSwagger(string swaggerFile, string clientPath)
        {
            if (!File.Exists(swaggerFile) || !Directory.Exists(clientPath)) {
                Console.WriteLine(@"ClientApiGenerator.exe {swaggerFile} {clientPath}

Arguments:
    {swaggerFile} - The path to the Avalara.AvaTax.RestClient.json file
    {clientPath}  - The path to the clients folder where the structure will be created

");
                return;
            }

            // Read in the swagger object
            Console.WriteLine("***** Parsing file {0}", swaggerFile);
            var json = File.ReadAllText(swaggerFile);
            var settings = new JsonSerializerSettings();
            settings.MetadataPropertyHandling = MetadataPropertyHandling.Ignore;
            var obj = JsonConvert.DeserializeObject<Swagger.SwaggerModel>(json, settings);
            var api = ParseSwagger(obj);

            // Render each target
            foreach (var type in typeof(Program).Assembly.GetTypes()) {
                if (type.IsSubclassOf(typeof(BaseRenderTarget))) {
                    var target = Activator.CreateInstance(type) as BaseRenderTarget;
                    if (target != null) {
                        Console.WriteLine("***** Rendering {0}", type.Name);
                        target.Render(api, clientPath);
                    }
                }
            }

            // Done
            Console.WriteLine("***** Done");
        }

        #region Parsing
        private static SwaggerInfo ParseSwagger(SwaggerModel obj)
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
                    api.Name = verb.Value.operationId.Replace("ApiV2", "");

                    // Now figure out all the URL parameters
                    foreach (var parameter in verb.Value.parameters) {

                        // Query String Parameters
                        if (parameter.paramIn == "query") {
                            api.QueryParams.Add(new ParameterInfo()
                            {
                                Comment = parameter.description ?? "",
                                ParamName = parameter.name,
                                TypeName = ResolveType(parameter)
                            });

                            // URL Path parameters
                        } else if (parameter.paramIn == "path") {
                            api.Params.Add(new ParameterInfo()
                            {
                                Comment = parameter.description ?? "",
                                ParamName = parameter.name,
                                TypeName = ResolveType(parameter)
                            });

                            // Body parameters
                        } else if (parameter.paramIn == "body") {
                            api.BodyParam = new ParameterInfo()
                            {
                                Comment = parameter.description ?? "",
                                ParamName = "model",
                                TypeName = ResolveType(parameter)
                            };
                        }

                        // Is this property an enum?
                        if (parameter.EnumDataType != null) {
                            ExtractEnum(result.Enums, parameter);
                        }
                    }

                    // Now figure out the response type
                    SwaggerResult ok = null;
                    if (verb.Value.responses.TryGetValue("200", out ok)) {
                        api.TypeName = ResolveType(ok.schema);
                    } else if (verb.Value.responses.TryGetValue("201", out ok)) {
                        api.TypeName = ResolveType(ok.schema);
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
                    Properties = new List<ParameterInfo>()
                };
                foreach (var prop in def.Value.properties) {
                    if (!prop.Value.required && def.Value.required != null) {
                        prop.Value.required = def.Value.required.Contains(prop.Key);
                    }
                    m.Properties.Add(new ParameterInfo()
                    {
                        Comment = prop.Value.description,
                        ParamName = prop.Key,
                        TypeName = ResolveType(prop.Value)
                    });

                    // Is this property an enum?
                    if (prop.Value.EnumDataType != null) {
                        ExtractEnum(result.Enums, prop.Value);
                    }
                }

                result.Models.Add(m);
            }

            // Now add the enums we know we need.
            // Because of the complex way this Dictionary<> is rendered in Swagger, it's hard to pick up the correct values.
            var tat = (from e in result.Enums where e.EnumDataType == "TransactionAddressType" select e).FirstOrDefault();
            if (tat == null) {
                tat = new EnumInfo()
                {
                    EnumDataType = "TransactionAddressType",
                    Items = new List<EnumItem>()
                };
                result.Enums.Add(tat);
            }
            tat.AddItem("ShipFrom", "This is the location from which the product was shipped");
            tat.AddItem("ShipTo", "This is the location to which the product was shipped");
            tat.AddItem("PointOfOrderAcceptance", "Location where the order was accepted; typically the call center, business office where purchase orders are accepted, server locations where orders are processed and accepted");
            tat.AddItem("PointOfOrderOrigin", "Location from which the order was placed; typically the customer's home or business location");
            tat.AddItem("SingleLocation", "Only used if all addresses for this transaction were identical; e.g. if this was a point-of-sale physical transaction");

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

        private static string ResolveType(SwaggerProperty prop)
        {
            StringBuilder typename = new StringBuilder();
            bool isValueType = false;

            // If this API produces a file download
            if (prop == null) return "FileResult";

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
                typename.Append(ResolveType(prop.items).Replace("?", ""));
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
                typename.Append(ResolveType(prop.schema));

                // Custom hack for objects that aren't represented correctly in swagger at the moment - still have to fix this in REST v2
            } else if (prop.description == "Default addresses for all lines in this document") {
                typename.Append("Dictionary<TransactionAddressType, AddressInfo>");

                // Custom hack for objects that aren't represented correctly in swagger at the moment - still have to fix this in REST v2
            } else if (prop.description == "Specify any differences for addresses between this line and the rest of the document") {
                typename.Append("Dictionary<TransactionAddressType, AddressInfo>");

            // All else is just a generic object
            } else {
                typename.Append("Dictionary<string, string>");
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
