namespace ClientApiGenerator.Models
{
    /// <summary>
    /// Documentation for an enum value
    /// </summary>
    public class EnumItem
    {
        /// <summary>
        /// The name of the enum value.
        /// </summary>
        public string Name { get; set; }

        /// <summary>
        /// The enum value
        /// </summary>
        public object Value { get; set; }

        /// <summary>
        /// A description of the enum value.
        /// </summary>
        public string Summary { get; set; }

        /// <summary>
        /// Deprecation information.
        /// </summary>
        public DeprecationStatus DeprecationStatus { get; set; }
    }
}
