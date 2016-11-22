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
    public enum JurisdictionType
    {
        /// <summary>
        /// 
        /// </summary>
        Country,

        /// <summary>
        /// 
        /// </summary>
        Composite,

        /// <summary>
        /// 
        /// </summary>
        State,

        /// <summary>
        /// 
        /// </summary>
        County,

        /// <summary>
        /// 
        /// </summary>
        City,

        /// <summary>
        /// 
        /// </summary>
        Special,


    }
}
