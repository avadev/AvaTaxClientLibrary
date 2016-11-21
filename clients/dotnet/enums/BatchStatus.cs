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
    public enum BatchStatus
    {
        /// <summary>
        /// 
        /// </summary>
        Waiting,

        /// <summary>
        /// 
        /// </summary>
        SystemErrors,

        /// <summary>
        /// 
        /// </summary>
        Cancelled,

        /// <summary>
        /// 
        /// </summary>
        Completed,

        /// <summary>
        /// 
        /// </summary>
        Creating,

        /// <summary>
        /// 
        /// </summary>
        Deleted,

        /// <summary>
        /// 
        /// </summary>
        Errors,

        /// <summary>
        /// 
        /// </summary>
        Paused,

        /// <summary>
        /// 
        /// </summary>
        Processing,


    }
}
