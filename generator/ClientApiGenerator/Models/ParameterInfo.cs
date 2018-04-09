using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using static ClientApiGenerator.TemplateBase;

namespace ClientApiGenerator.Models
{
    public class ParameterInfo
    {
        public string ParamName { get; set; }
        public string CleanParamName
        {
            get
            {
                return ParamName.Replace("$", "");
            }
        }

        public string StrippedPackageParamName
        {
            get
            {
                var cleanedParam = CleanParamName;
                var index = cleanedParam.LastIndexOf(".") + 1;
                return cleanedParam.Substring(index, cleanedParam.Length - index);
            }
        }
        public string Type { get; set; }
        public string TypeName { get; set; }
        public string ApexTypeName
        {
            get
            {
                if(TypeName == "Int32" || TypeName == "Int32?")
                {
                    return TypeName.Replace("Int32", "Integer");
                } else
                {
                    return TypeName;
                }
            }
        }
        public string Comment { get; set; }
        public ParameterLocationType ParameterLocation { get; set; }
        public bool IsArrayType { get; set; }
        public string ArrayElementType { get; set; }
        public bool Required { get; set; }
        public bool ReadOnly { get; set; }
        public int? MaxLength { get; set; }
        public int? MinLength { get; set; }
        public string Example { get; set; }
    }
}
