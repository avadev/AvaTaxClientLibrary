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
        private static ApiModel ParseSwagger(SwaggerModel obj)
        {
            ApiModel result = new ApiModel();

            // Loop through all paths and spit them out to the console
            foreach (var path in (from p in obj.paths orderby p.Key select p)) {
                foreach (var verb in path.Value) {

                    // Set up our API
                    ApiInfo api = new ApiInfo();
                    api.URI = path.Key;
                    api.HttpVerb = verb.Key;
                    api.Comment = verb.Value.summary;
                    api.Params = new List<ParameterInfo>();
                    api.QueryParams = new List<ParameterInfo>();
                    api.Category = verb.Value.tags.FirstOrDefault();
                    api.OperationId = verb.Value.operationId.Replace("ApiV2", "");

                    // Now figure out all the URL parameters
                    foreach (var parameter in verb.Value.parameters) {

                        // Query String Parameters
                        if (parameter.paramIn == "query") {
                            api.QueryParams.Add(new ParameterInfo()
                            {
                                Comment = parameter.description ?? "",
                                ParamName = parameter.name,
                                TypeName = ResolveType(parameter, parameter.schema)
                            });

                            // URL Path parameters
                        } else if (parameter.paramIn == "path") {
                            api.Params.Add(new ParameterInfo()
                            {
                                Comment = parameter.description ?? "",
                                ParamName = parameter.name,
                                TypeName = ResolveType(parameter, parameter.schema)
                            });

                            // Body parameters
                        } else if (parameter.paramIn == "body") {
                            api.BodyParam = new ParameterInfo()
                            {
                                Comment = parameter.description ?? "",
                                ParamName = "model",
                                TypeName = ResolveType(parameter, parameter.schema)
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
                        api.TypeName = ResolveType(null, ok.schema);
                    } else if (verb.Value.responses.TryGetValue("201", out ok)) {
                        api.TypeName = ResolveType(null, ok.schema);
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
                        TypeName = ResolveType(prop.Value, null)
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
            var tat = new EnumInfo()
            {
                EnumDataType = "TransactionAddressType",
                Items = new List<EnumItem>()
            };
            tat.AddItem("ShipFrom", "This is the location from which the product was shipped");
            tat.AddItem("ShipTo", "This is the location to which the product was shipped");
            tat.AddItem("PointOfOrderAcceptance", "Location where the order was accepted; typically the call center, business office where purchase orders are accepted, server locations where orders are processed and accepted");
            tat.AddItem("PointOfOrderOrigin", "Location from which the order was placed; typically the customer's home or business location");
            tat.AddItem("SingleLocation", "Only used if all addresses for this transaction were identical; e.g. if this was a point-of-sale physical transaction");
            result.Enums.Add(tat);

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

        private static string ResolveType(SwaggerProperty prop, SwaggerSchemaRef schema)
        {
            // First, is this a simple property?
            string responsetype = null;
            string basetype = null;
            if (prop != null) {
                var rawtype = ResolveValueType(prop.type, prop.format, prop.EnumDataType, prop.required);
                if (rawtype != null) return rawtype;
                if (schema == null) schema = prop.items;
                if (schema == null) schema = prop.schema;

                // See if we have a custom schema in the extended properties
                if (schema == null && prop.schemaref != null) schema = new SwaggerSchemaRef()
                {
                     schemaName = prop.schemaref.Substring(prop.schemaref.LastIndexOf('/')+1),
                     type = prop.type
                };

                // Is this an array?
                if (prop.type == "array") {
                    responsetype = prop.type;
                }
            }

            // Okay, this is a complex object
            if (schema != null) {

                // Try to resolve it as a value type
                var rawtype = ResolveValueType(schema.type, null, null, false);
                if (rawtype != null) {
                    return rawtype;
                }

                // Okay, it's not void
                if (responsetype == null) responsetype = schema.type ?? "";
                if (basetype == null) basetype = schema.schemaName;

                // If this is recursion
                if (responsetype == "array") {
                    if (prop != null && prop.items != null) {
                        basetype = ResolveType(null, prop.items);
                    } else {
                        basetype = ResolveType(null, schema.items);
                    }
                }

                // If there's no base type, try resolving it as a value
                if (basetype == null && prop != null) {
                    basetype = ResolveValueType(schema.type, prop.format, prop.EnumDataType, prop.required);
                } else if (basetype == null) {
                    basetype = ResolveValueType(schema.type, null, null, false);
                }

                // Cleanup the type
                if (basetype.IndexOf("/") > 0) {
                    basetype = basetype.Substring(basetype.LastIndexOf('/') + 1);
                }
                if (basetype.StartsWith("FetchResult[")) {
                    basetype = basetype.Replace("[", "<").Replace("]", ">");
                }
                if (basetype.Contains("Enumerable")) {
                    Console.WriteLine("Something");
                }
                if (responsetype == "array") {
                    basetype = "List<" + basetype + ">";
                }
                return basetype;
            }

            // No hope left - just describe it as an anonymous object
            if (prop != null && prop.Extended != null && prop.Extended.Count != 0) {
                if (prop.description == "Default addresses for all lines in this document" ||
                    prop.description == "Specify any differences for addresses between this line and the rest of the document") {
                    return "Dictionary<TransactionAddressType, AddressInfo>";
                }
            }
            return "Dictionary<string, string>";
        }

        private static string ResolveValueType(string type, string format, string enumdatatype, bool required)
        {
            StringBuilder typename = new StringBuilder();
            bool isValueType = false;
            if (type == "integer") {
                typename.Append("Int32");
                isValueType = true;
            } else if (type == "number") {
                typename.Append("Decimal");
                isValueType = true;
            } else if (type == "boolean") {
                typename.Append("Boolean");
                isValueType = true;
            } else if (format == "date-time" && type == "string") {
                typename.Append("DateTime");
                isValueType = true;
            } else if (type == "string") {
                if (enumdatatype == null) {
                    return "String";
                } else {
                    typename.Append(enumdatatype);
                    isValueType = true;
                }
            }

            // Is this a basic value type?
            if (isValueType) {
                if (!required) {
                    typename.Append("?");
                }
                return typename.ToString();
            }

            // No help here
            return null;
        }
        #endregion
    }
}
