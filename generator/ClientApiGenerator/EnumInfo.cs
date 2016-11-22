using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator
{
    public class EnumInfo
    {
        public string EnumDataType { get; set; }
        public string Comment { get; set; }
        public List<EnumItem> Items { get; set; }

        public override string ToString()
        {
            StringBuilder sb = new StringBuilder();
            foreach (var i in Items) {
                sb.AppendLine(Resource1.enum_value_template_csharp
                    .Replace("@@COMMENT@@", Fixups.Comment(i.Comment))
                    .Replace("@@VALUE@@", i.Value));
            }

            return Resource1.enum_class_template_csharp
                .Replace("@@ENUMCLASS@@", EnumDataType)
                .Replace("@@COMMENT@@", Fixups.Comment(Comment))
                .Replace("@@VALUELIST@@", sb.ToString());
        }

        public void AddItem(string value, string comment)
        {
            if (this.Items == null) this.Items = new List<EnumItem>();
            Items.Add(new EnumItem()
            {
                Value = value,
                Comment = comment
            });
        }
    }
}
