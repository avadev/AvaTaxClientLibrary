using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator
{
    public class ParameterInfo
    {
        public string ParamName { get; set; }
        public string CSharpParamName
        {
            get
            {
                return ParamName.Replace("$", "");
            }
        }
        public string TypeName { get; set; }
        public string Comment { get; set; }
    }
}
