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
    public enum NexusTypeId
    {
        /// <summary>
        /// 
        /// </summary>
        None,

        /// <summary>
        /// 
        /// </summary>
        SalesOrSellersUseTax,

        /// <summary>
        /// 
        /// </summary>
        SalesTax,

        /// <summary>
        /// 
        /// </summary>
        SSTVolunteer,

        /// <summary>
        /// 
        /// </summary>
        SSTNonVolunteer,


    }
}
