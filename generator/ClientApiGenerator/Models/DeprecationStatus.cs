namespace ClientApiGenerator.Models
{
    /// <summary>
    /// Deprecation status
    /// </summary>
    public class DeprecationStatus
    {
        /// <summary>
        /// Date we notify user of the deprecation
        /// </summary>
        public string Date { get; set; }

        /// <summary>
        /// Version we notify user of the deprecation
        /// </summary>
        public string Version { get; set; }

        /// <summary>
        /// Additional messages for this deprecation
        /// </summary>
        public string Message { get; set; }
    }
}
