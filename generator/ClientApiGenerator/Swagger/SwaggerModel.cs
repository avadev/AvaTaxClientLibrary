using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Swagger
{
    class SwaggerModel
    {
        public string swagger { get; set; }
        public Dictionary<string, object> info { get; set; }
        public string basePath { get; set; }
        public Dictionary<string, Dictionary<string, SwaggerMethod>> paths { get; set; }
        public Dictionary<string, SwaggerDefinition> definitions { get; set; }
        public Dictionary<string, object> securityDefinitions { get; set; }

        [JsonProperty("x-avalara-version")]
        public string ApiVersion { get; set; }
        
        [JsonExtensionData]
        public IDictionary<string, object> Extended { get; set; }
    }
}
