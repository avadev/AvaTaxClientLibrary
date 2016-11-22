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
    public enum ErrorTargetCode
    {
        /// <summary>
        /// 
        /// </summary>
        Unknown,

        /// <summary>
        /// 
        /// </summary>
        HttpRequest,

        /// <summary>
        /// 
        /// </summary>
        HttpRequestHeaders,

        /// <summary>
        /// 
        /// </summary>
        IncorrectData,

        /// <summary>
        /// 
        /// </summary>
        AvaTaxApiServer,

        /// <summary>
        /// 
        /// </summary>
        AvalaraIdentityServer,

        /// <summary>
        /// 
        /// </summary>
        CustomerAccountSetup,


    }
}
