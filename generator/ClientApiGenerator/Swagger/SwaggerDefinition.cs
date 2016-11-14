using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Swagger
{
    public class SwaggerDefinition
    {
        public string description { get; set; }
        public List<string> required { get; set; }
        public string type { get; set; }
        public Dictionary<string, SwaggerProperty> properties { get; set; }
        public object example { get; set; }

        [JsonExtensionData]
        public IDictionary<string, object> Extended { get; set; }
    }
}
