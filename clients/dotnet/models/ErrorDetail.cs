using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using Newtonsoft.Json;

namespace Avalara.AvaTax.RestClient
{
    /// <summary>
    /// Message object
    /// </summary>
    public class ErrorDetail
    {
        /// <summary>
        /// Name of the error.
        /// </summary>
        public ErrorCodeId? code { get; set; }

        /// <summary>
        /// Error message identifier
        /// </summary>
        public Int32? number { get; set; }

        /// <summary>
        /// Concise summary of the message, suitable for display in the caption of an alert box.
        /// </summary>
        public String message { get; set; }

        /// <summary>
        /// A more detailed description of the problem referenced by this error message, suitable for display in the contents area of an alert box.
        /// </summary>
        public String description { get; set; }

        /// <summary>
        /// Indicates the SoapFault code
        /// </summary>
        public String faultCode { get; set; }

        /// <summary>
        /// URL to help for this message
        /// </summary>
        public String helpLink { get; set; }

        /// <summary>
        /// Item the message refers to, if applicable.  This is used to indicate a missing or incorrect value.
        /// </summary>
        public String refersTo { get; set; }

        /// <summary>
        /// Severity of the message
        /// </summary>
        public SeverityLevel? severity { get; set; }


    }
}
