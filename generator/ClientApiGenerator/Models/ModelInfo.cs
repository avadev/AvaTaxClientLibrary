using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Models
{
    public class ModelInfo
    {
        public string Description { get; set; }
        public List<string> Required { get; set; }
        public string Type { get; set; }
        public object Example { get; set; }
        public string Comment { get; set; }
        public string SchemaName { get; set; }
        public List<ParameterInfo> Properties { get; set; }
    }
}
