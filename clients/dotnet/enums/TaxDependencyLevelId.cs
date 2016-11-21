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
    public enum TaxDependencyLevelId
    {
        /// <summary>
        /// 
        /// </summary>
        Document,

        /// <summary>
        /// 
        /// </summary>
        State,

        /// <summary>
        /// 
        /// </summary>
        TaxRegion,

        /// <summary>
        /// 
        /// </summary>
        Address,


    }
}
