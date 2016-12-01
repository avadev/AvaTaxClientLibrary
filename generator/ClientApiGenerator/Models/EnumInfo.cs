using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Models
{
    public class EnumInfo
    {
        public string EnumDataType { get; set; }
        public string Comment { get; set; }
        public List<EnumItem> Items { get; set; }

        /// <summary>
        /// Convert this object to a JSON string of itself
        /// </summary>
        /// <returns></returns>
        public override string ToString()
        {
            StringBuilder sb = new StringBuilder();
            foreach (var i in Items) {
                sb.AppendLine(Resource1.csharp_enum_value
                    .Replace("@@COMMENT@@", Fixups.Comment(i.Comment))
                    .Replace("@@VALUE@@", i.Value));
            }

            return Resource1.csharp_enum_class
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
