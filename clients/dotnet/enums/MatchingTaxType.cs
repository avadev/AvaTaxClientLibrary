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
    public enum MatchingTaxType
    {
        /// <summary>
        /// 
        /// </summary>
        All,

        /// <summary>
        /// 
        /// </summary>
        BothSalesAndUseTax,

        /// <summary>
        /// 
        /// </summary>
        ConsumerUseTax,

        /// <summary>
        /// 
        /// </summary>
        MedicalExcise,

        /// <summary>
        /// 
        /// </summary>
        Fee,

        /// <summary>
        /// 
        /// </summary>
        VATInputTax,

        /// <summary>
        /// 
        /// </summary>
        VATNonrecoverableInputTax,

        /// <summary>
        /// 
        /// </summary>
        VATOutputTax,

        /// <summary>
        /// 
        /// </summary>
        Rental,

        /// <summary>
        /// 
        /// </summary>
        SalesTax,

        /// <summary>
        /// 
        /// </summary>
        UseTax,


    }
}
