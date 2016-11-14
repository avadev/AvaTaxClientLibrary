using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Swagger
{
    public class SwaggerParam
    {
        public string name { get; set; }
        public string description { get; set; }
        public bool required { get; set; }
        public string type { get; set; }
        public string format { get; set; }
        public SwaggerSchemaRef schema { get; set; }

        [JsonProperty("in")]
        public string paramIn { get; set; }

        [JsonProperty("default")]
        public string defaultValue { get; set; }

        [JsonExtensionData]
        public IDictionary<string, object> Extended { get; set; }

    }
}
