using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Swagger
{
    public class SwaggerSchemaRef
    {
        public string type { get; set; }

        /// <summary>
        /// Recursion!
        /// </summary>
        public SwaggerSchemaRef items { get; set; }

        [JsonProperty("$ref")]
        public string schemaName { get; set; }

        [JsonExtensionData]
        public IDictionary<string, object> Extended { get; set; }
    }
}
