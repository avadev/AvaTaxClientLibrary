using Avalara.AvaTax.RestClient;
using CommandLine;
using System;

namespace AvaTax_Connect
{
    public class Options
    {
        [Option(shortName: 'u', Required = true, HelpText = "Username for AvaTax.")]
        public string Username { get; set; }

        [Option(shortName: 'p', Required = true, HelpText = "Password for AvaTax.")]
        public string Password { get; set; }

        [Option(shortName: 'c', Required = true, HelpText = "Number of calls to make to AvaTax.  If null, will continue until cancelled.")]
        public int? Calls { get; set; }

        [Option(shortName: 'e', Required = false, HelpText = "AvaTax environment to call.")]
        public AvaTaxEnvironment Environment { get; set; }

        [Option(shortName: 'l', Required = false, HelpText = "Number of lines to include in each tax transaction (Default 1).")]
        public int Lines { get; set; }

        /// <summary>
        /// Returns true if the options are valid
        /// </summary>
        public bool IsValid()
        {
            return (!String.IsNullOrEmpty(Username) && !String.IsNullOrEmpty(Password));
        }
    }
}
