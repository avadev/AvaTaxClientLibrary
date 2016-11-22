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
    /// Information about the error that occurred
    /// </summary>
    public class ErrorInfo
    {
        /// <summary>
        /// Type of error that occurred
        /// </summary>
        public ErrorCodeId? code { get; set; }

        /// <summary>
        /// Short one-line message to summaryize what went wrong
        /// </summary>
        public String message { get; set; }

        /// <summary>
        /// What object or service caused the error?
        /// </summary>
        public ErrorTargetCode? target { get; set; }

        /// <summary>
        /// Array of detailed error messages
        /// </summary>
        public List<ErrorDetail> details { get; set; }


    }
}
