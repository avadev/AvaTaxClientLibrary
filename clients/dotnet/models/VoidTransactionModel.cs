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
    /// A request to void a previously created transaction
    /// </summary>
    public class VoidTransactionModel
    {
        /// <summary>
        /// Please specify the reason for voiding or cancelling this transaction
        /// </summary>
        public VoidReasonCode code { get; set; }


    }
}
