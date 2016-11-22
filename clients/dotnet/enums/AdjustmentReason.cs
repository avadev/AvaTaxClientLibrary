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
    /// 
    /// </summary>
    public enum AdjustmentReason
    {
        /// <summary>
        /// 
        /// </summary>
        NotAdjusted,

        /// <summary>
        /// 
        /// </summary>
        SourcingIssue,

        /// <summary>
        /// 
        /// </summary>
        ReconciledWithGeneralLedger,

        /// <summary>
        /// 
        /// </summary>
        ExemptCertApplied,

        /// <summary>
        /// 
        /// </summary>
        PriceAdjusted,

        /// <summary>
        /// 
        /// </summary>
        ProductReturned,

        /// <summary>
        /// 
        /// </summary>
        ProductExchanged,

        /// <summary>
        /// 
        /// </summary>
        BadDebt,

        /// <summary>
        /// 
        /// </summary>
        Other,

        /// <summary>
        /// 
        /// </summary>
        Offline,


    }
}
