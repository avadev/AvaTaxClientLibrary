using System.Collections.Generic;

namespace ClientApiGenerator.Models
{
    /// <summary>
    /// Enum documentation
    /// </summary>
    public class EnumInfo
    {
        /// <summary>
        /// The enum name.
        /// </summary>
        public string Name { get; set; }

        /// <summary>
        /// The enum summary.
        /// </summary>
        public string Summary { get; set; }

        /// <summary>
        /// A list of documentation for this enum's values.
        /// </summary>
        public List<EnumItem> Values { get; set; }
    }
}
