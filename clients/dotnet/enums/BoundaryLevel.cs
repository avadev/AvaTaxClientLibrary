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
    public enum BoundaryLevel
    {
        /// <summary>
        /// 
        /// </summary>
        Address,

        /// <summary>
        /// 
        /// </summary>
        Zip9,

        /// <summary>
        /// 
        /// </summary>
        Zip5,


    }
}
