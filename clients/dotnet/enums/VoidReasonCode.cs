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
    public enum VoidReasonCode
    {
        /// <summary>
        /// 
        /// </summary>
        Unspecified,

        /// <summary>
        /// 
        /// </summary>
        PostFailed,

        /// <summary>
        /// 
        /// </summary>
        DocDeleted,

        /// <summary>
        /// 
        /// </summary>
        DocVoided,

        /// <summary>
        /// 
        /// </summary>
        AdjustmentCancelled,


    }
}
