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
