using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator
{
    public class ModelInfo
    {
        public string Comment { get; set; }
        public string SchemaName { get; set; }
        public List<ParameterInfo> Properties { get; set; }

        /// <summary>
        /// Serialize this as a file string
        /// </summary>
        /// <returns></returns>
        public override string ToString()
        {
            StringBuilder sb = new StringBuilder();
            foreach (var prop in Properties) {
                sb.AppendLine(Resource1.model_property_template_csharp
                    .Replace("@@PROPERTYNAME@@", prop.CSharpParamName)
                    .Replace("@@COMMENT@@", prop.Comment)
                    .Replace("@@PROPERTYTYPE@@", prop.TypeName));
            }

            // Produce the full file
            return Resource1.model_class_template_csharp
                .Replace("@@PROPERTYLIST@@", sb.ToString())
                .Replace("@@COMMENT@@", Comment)
                .Replace("@@MODELCLASS@@", SchemaName);
        }
    }
}
