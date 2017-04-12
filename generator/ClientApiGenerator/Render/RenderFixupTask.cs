using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Render
{
    public class RenderFixupTask
    {
        /// <summary>
        /// The path of the file on which to execute a fixup
        /// </summary>
        public string filePath { get; set; }

        /// <summary>
        /// The file encoding of the file (e.g. UTF8 or ASCII)
        /// </summary>
        public string encoding { get; set; }

        /// <summary>
        /// The regular expression to execute
        /// </summary>
        public string regex { get; set; }

        /// <summary>
        /// The replacement string to apply when the regex is found
        /// </summary>
        public string replacement { get; set; }
    }
}
