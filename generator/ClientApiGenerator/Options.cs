using CommandLine;
using System;
using System.IO;

namespace ClientApiGenerator
{
    public class Options
    {
        [Option(shortName: 'g', Required = true, HelpText = "Path of the swagger generation file to use.")]
        public string SwaggerRenderPath { get; set; }

        /// <summary>
        /// Returns true if the options are valid
        /// </summary>
        public bool IsValid()
        {
            // Swagger gen file must exist
            if (!File.Exists(SwaggerRenderPath)) return false;

            return true;
        }
    }
}
