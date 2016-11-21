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
            // Read in the swagger object
            var json = File.ReadAllText(args[0]);
            var settings = new JsonSerializerSettings();
            settings.MetadataPropertyHandling = MetadataPropertyHandling.Ignore;
            var obj = JsonConvert.DeserializeObject<Swagger.SwaggerModel>(json, settings);

            // Loop through all paths and spit them out to the console
            List<ApiInfo> apis = new List<ApiInfo>();
            foreach (var path in obj.paths) {
                foreach (var verb in path.Value) {

                    // Set up our API
                    ApiInfo api = new ApiInfo();
                    api.URI = path.Key;
                    api.HttpVerb = verb.Key;
                    api.Comment = verb.Value.summary;
                    api.Params = new List<ParameterInfo>();
                    api.QueryParams = new List<ParameterInfo>();
                    api.Category = verb.Value.tags.FirstOrDefault();
                    api.OperationId = verb.Value.operationId.Replace("ApiV2","");

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
                    }

                    // Now figure out the response type
                    SwaggerResult ok = null;
                    if (verb.Value.responses.TryGetValue("200", out ok)) {
                        api.TypeName = ResolveType(null, ok.schema);
                    } else if (verb.Value.responses.TryGetValue("201", out ok)) {
                        api.TypeName = ResolveType(null, ok.schema);
                    }

                    // Done with this API
                    apis.Add(api);
                }
            }

            // Loop through all the schemas
            List<ModelInfo> models = new List<ModelInfo>();
            List<EnumInfo> enums = new List<EnumInfo>();
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
                        var enumType = (from e in enums where e.EnumDataType == prop.Value.EnumDataType select e).FirstOrDefault();
                        if (enumType == null) {
                            enumType = new EnumInfo()
                            {
                                EnumDataType = prop.Value.EnumDataType,
                                Items = new List<EnumItem>()
                            };
                            enums.Add(enumType);
                        }

                        // Add values if they are known
                        if (prop.Value.enumValues != null) {
                            foreach (var s in prop.Value.enumValues) {
                                enumType.Items.Add(new EnumItem() { Value = s });
                            }
                        }
                    }
                }

                models.Add(m);
            }

            // Now spit out a coherent API structure
            StringBuilder sb = new StringBuilder();
            string currentRegion = null;
            foreach (var api in (from a in apis orderby a.Category, a.OperationId select a)) {
                if (currentRegion != api.Category) {
                    if (currentRegion != null) {
                        sb.AppendLine("        #endregion\r\n");
                    }
                    sb.AppendLine("        #region " + api.Category);
                    currentRegion = api.Category;
                }
                sb.AppendLine(api.ToString());
            }
            if (currentRegion != null) {
                sb.AppendLine("        #endregion");
            }

            // Next let's assemble the api file
            string filetext = Resource1.api_class_template_csharp
                .Replace("@@APILIST@@", sb.ToString());
            File.WriteAllText(Path.Combine(args[1], "AvaTaxApi.cs"), filetext);

            // Next let's assemble the model files
            foreach (var m in models) {
                if (!m.SchemaName.StartsWith("FetchResult")) {
                    File.WriteAllText(Path.Combine(args[1], "models\\" + m.SchemaName + ".cs"), m.ToString());
                }
            }

            // Finally assemble the enums
            foreach (var e in enums) {
                File.WriteAllText(Path.Combine(args[1], "enums\\" + e.EnumDataType + ".cs"), e.ToString());
            }
        }

        #region Type Helpers
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
                    basetype = basetype + "[]";
                }
                return basetype;
            }

            // No hope left - just describe it as an anonymous object
            if (prop != null && prop.Extended.Count != 0) {
                if (prop.description == "Default addresses for all lines in this document" ||
                    prop.description == "Specify any differences for addresses between this line and the rest of the document") {
                    return "Dictionary<string, AddressInfo>";
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
                typename.Append("Bool");
                isValueType = true;
            } else if (format == "date-time" && type == "string") {
                typename.Append("DateTime");
                isValueType = true;
            } else if (type == "string") {
                if (enumdatatype != null) {
                    return enumdatatype;
                } else {
                    return "String";
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
