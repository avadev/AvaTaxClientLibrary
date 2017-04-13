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

        [Option(shortName: 'c', Required = false, DefaultValue = null, HelpText = "Number of calls to make to AvaTax.  If null, will continue until cancelled.")]
        public int? Calls { get; set; }

        [Option(shortName: 'd', Required = false, DefaultValue = true, HelpText = "Discard first API call.  The first API call includes lots of overhead.")]
        public bool? DiscardFirstCall { get; set; }

        [Option(shortName: 'e', DefaultValue = "https://sandbox-rest.avatax.com", Required = false, HelpText = "URL of the AvaTax environment to call.")]
        public string Environment { get; set; }

        [Option(shortName: 't', DefaultValue = DocumentType.SalesOrder, Required = false, HelpText = "Type of document to create.")]
        public DocumentType DocType { get; set; }

        [Option(shortName: 'l', DefaultValue = 1, Required = false, HelpText = "Number of lines to include in each tax transaction.")]
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
