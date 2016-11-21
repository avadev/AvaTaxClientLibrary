using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator
{
    public class Fixups
    {
        public static string Comment(string c)
        {
            if (String.IsNullOrEmpty(c)) return "";
            return c.Replace("\r\n", "\r\n        /// ");
        }
    }
}
