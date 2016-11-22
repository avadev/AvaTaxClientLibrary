using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Models
{
    public class ApiModel
    {
        public List<ApiInfo> Methods { get; set; }
        public List<EnumInfo> Enums { get; set; }
        public List<ModelInfo> Models { get; set; }

        public ApiModel()
        {
            Methods = new List<ApiInfo>();
            Enums = new List<EnumInfo>();
            Models = new List<ModelInfo>();
        }
    }
}
