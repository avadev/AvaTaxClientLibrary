using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Text.RegularExpressions;

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

        /// <summary>
        /// remove content within curly braces in string, for python use only.
        /// </summary>
        public string parseURI(string sentence)
        {
            Regex rgx = new Regex("\\{.+?\\}");
            return rgx.Replace(sentence, "{}");
        }
    }
}
