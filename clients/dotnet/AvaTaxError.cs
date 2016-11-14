using System;
using Avalara.AvaTax.RestClient.Model;

namespace Avalara.AvaTax.RestClient
{
    public class AvaTaxError : Exception
    {
        /// <summary>
        /// The raw error message from the client
        /// </summary>
        public ErrorResult error { get; set; }

        /// <summary>
        /// Constructs an error object for an API call
        /// </summary>
        /// <param name="err"></param>
        public AvaTaxError(ErrorResult err)
        {
            this.error = err;
        }
    }
}