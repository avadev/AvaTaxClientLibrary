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
    public enum TaxRuleTypeId
    {
        /// <summary>
        /// 
        /// </summary>
        RateRule,

        /// <summary>
        /// 
        /// </summary>
        RateOverrideRule,

        /// <summary>
        /// 
        /// </summary>
        BaseRule,

        /// <summary>
        /// 
        /// </summary>
        ExemptEntityRule,

        /// <summary>
        /// 
        /// </summary>
        ProductTaxabilityRule,

        /// <summary>
        /// 
        /// </summary>
        NexusRule,


    }
}
