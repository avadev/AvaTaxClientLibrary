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
    /// Set Password Model
    /// </summary>
    public class SetPasswordModel
    {
        /// <summary>
        /// New Password
        /// </summary>
        public String newPassword { get; set; }


    }
}
