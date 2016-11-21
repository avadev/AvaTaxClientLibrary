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
    /// Helper function for throwing known error response
    /// </summary>
    public class ErrorResult
    {
        /// <summary>
        /// Information about the error(s)
        /// </summary>
        public ErrorInfo error { get; set; }


    }
}
