using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Swagger
{
    public class SwaggerProperty
    {
        public string name { get; set; }
        public string description { get; set; }
        public bool required { get; set; }
        public string type { get; set; }
        public string format { get; set; }
        public bool readOnly { get; set; }
        public object example { get; set; }

        public SwaggerSchemaRef schema { get; set; }
        public SwaggerSchemaRef items { get; set; }

        [JsonProperty("$ref")]
        public string schemaref { get; set; }

        [JsonProperty("in")]
        public string paramIn { get; set; }

        [JsonProperty("default")]
        public string defaultValue { get; set; }


        [JsonProperty("enum")]
        public List<string> enumValues { get; set; }

        [JsonExtensionData]
        public IDictionary<string, object> Extended { get; set; }
    }
}
