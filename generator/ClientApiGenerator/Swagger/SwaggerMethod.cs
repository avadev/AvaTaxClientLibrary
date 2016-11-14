using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Swagger
{
    public class SwaggerMethod
    {
        public List<string> tags { get; set; }
        public string summary { get; set; }
        public string operationId { get; set; }
        public List<string> consumes { get; set; }
        public List<string> produces { get; set; }
        public List<SwaggerParam> parameters { get; set; }
        public Dictionary<string, SwaggerResult> responses { get; set; }
        public bool deprecated { get; set; }
        public List<Dictionary<string, object>> security { get; set; }

        [JsonExtensionData]
        public IDictionary<string, object> Extended { get; set; }
    }
}
