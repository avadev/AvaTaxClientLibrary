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
    public enum RateType
    {
        /// <summary>
        /// 
        /// </summary>
        ReducedA,

        /// <summary>
        /// 
        /// </summary>
        ReducedB,

        /// <summary>
        /// 
        /// </summary>
        Food,

        /// <summary>
        /// 
        /// </summary>
        General,

        /// <summary>
        /// 
        /// </summary>
        IncreasedStandard,

        /// <summary>
        /// 
        /// </summary>
        LinenRental,

        /// <summary>
        /// 
        /// </summary>
        Medical,

        /// <summary>
        /// 
        /// </summary>
        Parking,

        /// <summary>
        /// 
        /// </summary>
        SuperReduced,

        /// <summary>
        /// 
        /// </summary>
        ReducedR,

        /// <summary>
        /// 
        /// </summary>
        Standard,

        /// <summary>
        /// 
        /// </summary>
        Zero,


    }
}
