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
    public enum AuthenticationTypeId
    {
        /// <summary>
        /// 
        /// </summary>
        None,

        /// <summary>
        /// 
        /// </summary>
        UsernamePassword,

        /// <summary>
        /// 
        /// </summary>
        AccountIdLicenseKey,

        /// <summary>
        /// 
        /// </summary>
        OpenIdBearerToken,


    }
}
