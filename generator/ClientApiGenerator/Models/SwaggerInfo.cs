using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Models
{
    public class SwaggerInfo
    {
        public List<MethodInfo> Methods { get; set; }
        public List<EnumInfo> Enums { get; set; }
        public List<ModelInfo> Models { get; set; }

        public SwaggerInfo()
        {
            Methods = new List<MethodInfo>();
            Enums = new List<EnumInfo>();
            Models = new List<ModelInfo>();
        }
    }
}
