using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Models
{
    public class ModelInfo
    {
        public string Comment { get; set; }
        public string SchemaName { get; set; }
        public List<ParameterInfo> Properties { get; set; }
    }
}
