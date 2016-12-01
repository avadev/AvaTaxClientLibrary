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

        /// <summary>
        /// Serialize this as a file string
        /// </summary>
        /// <returns></returns>
        public override string ToString()
        {
            StringBuilder sb = new StringBuilder();
            foreach (var prop in Properties) {
                sb.AppendLine(Resource1.csharp_model_property
                    .Replace("@@PROPERTYNAME@@", prop.CleanParamName)
                    .Replace("@@COMMENT@@", Fixups.Comment(prop.Comment))
                    .Replace("@@PROPERTYTYPE@@", prop.TypeName));
            }

            // Produce the full file
            return Resource1.csharp_model_class
                .Replace("@@PROPERTYLIST@@", sb.ToString())
                .Replace("@@COMMENT@@", Fixups.Comment(Comment))
                .Replace("@@MODELCLASS@@", SchemaName);
        }
    }
}
