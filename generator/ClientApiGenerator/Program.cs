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
                                TypeName = ResolveBaseType(parameter.type)
                            });

                        // URL Path parameters
                        } else if (parameter.paramIn == "path") {
                            api.Params.Add(new ParameterInfo()
                            {
                                Comment = parameter.description ?? "",
                                ParamName = parameter.name,
                                TypeName = ResolveBaseType(parameter.type)
                            });

                        // Body parameters
                        } else if (parameter.paramIn == "body") {
                            api.BodyParameterType = ResolveType(parameter.schema);
                        }
                    }

                    // Now figure out the response type
                    SwaggerResult ok = null;
                    if (verb.Value.responses.TryGetValue("200", out ok)) {
                        api.TypeName = ResolveType(ok.schema);
                    }

                    // Done with this API
                    apis.Add(api);
                }
            }

            // Now spit out a coherent API structure
            StringBuilder sb = new StringBuilder();
            sb.Append(@"using Avalara.AvaTax.RestClient.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using Newtonsoft.Json;

namespace Avalara.AvaTax.RestClient
{
    public partial class AvaTaxClient
    {
");
            string currentRegion = null;
            foreach (var api in apis) {
                if (currentRegion != api.Category) {
                    if (currentRegion != null) {
                        sb.AppendLine("        #endregion\r\n");
                    }
                    sb.AppendLine("        #region " + api.Category);
                    currentRegion = api.Category;
                }
                sb.Append(api.ToString());
            }
            if (currentRegion != null) {
                sb.Append("        #endregion");
            }
            sb.Append(@"    
    }
}
");
            File.WriteAllText(args[1], sb.ToString());
            Console.WriteLine(sb.ToString());
        }

        #region Type Helpers
        private static string ResolveBaseType(string rawtype)
        {
            return rawtype
                .Replace("integer", "Int32")
                .Replace("number", "Decimal");
        }

        private static string ResolveType(SwaggerSchemaRef schema)
        {
            string basetype = schema.schemaName;
            string responsetype = schema.type ?? "";

            // If this is recursion
            if (responsetype == "array") {
                basetype = ResolveType(schema.items);
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
        #endregion
    }
}
