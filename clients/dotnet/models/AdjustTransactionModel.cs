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
    /// A request to adjust tax for a previously existing transaction
    /// </summary>
    public class AdjustTransactionModel
    {
        /// <summary>
        /// A reason code indicating why this adjustment was made
        /// </summary>
        public String adjustmentReason { get; set; }

        /// <summary>
        /// If the AdjustmentReason is "Other", specify the reason here
        /// </summary>
        public String adjustmentDescription { get; set; }

        /// <summary>
        /// Replace the current transaction with tax data calculated for this new transaction
        /// </summary>
        public CreateTransactionModel newTransaction { get; set; }


    }
}
