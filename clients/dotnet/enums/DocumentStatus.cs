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
    public enum DocumentStatus
    {
        /// <summary>
        /// 
        /// </summary>
        Temporary,

        /// <summary>
        /// 
        /// </summary>
        Saved,

        /// <summary>
        /// 
        /// </summary>
        Posted,

        /// <summary>
        /// 
        /// </summary>
        Committed,

        /// <summary>
        /// 
        /// </summary>
        Cancelled,

        /// <summary>
        /// 
        /// </summary>
        Adjusted,

        /// <summary>
        /// 
        /// </summary>
        Queued,

        /// <summary>
        /// 
        /// </summary>
        PendingApproval,

        /// <summary>
        /// 
        /// </summary>
        Any,


    }
}
