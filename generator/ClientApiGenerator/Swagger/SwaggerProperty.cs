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
        public string format { get; set; }
        public string description { get; set; }
        public string type { get; set; }
        public bool readOnly { get; set; }
        public object example { get; set; }
        public SwaggerSchemaRef schema { get; set; }

        [JsonProperty("enum")]
        public List<string> enumValues { get; set; }

        [JsonExtensionData]
        public IDictionary<string, object> Extended { get; set; }
    }
}
