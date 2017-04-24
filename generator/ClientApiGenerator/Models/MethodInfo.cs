using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Models
{
    public class MethodInfo
    {
        public string Name { get; set; }
        public string URI { get; set; }
        public string Summary { get; set; }
        public string Description { get; set; }
        public string Category { get; set; }
        public string ResponseType { get; set; }
        public string ResponseTypeName { get; set; }
        public string HttpVerb { get; set; }
        public ParameterInfo BodyParam { get; set; }
        public List<ParameterInfo> Params { get; set; }
        public List<ParameterInfo> QueryParams { get; set; }
    }
}
