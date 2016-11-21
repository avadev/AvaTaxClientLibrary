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
    /// Password Change Model
    /// </summary>
    public class PasswordChangeModel
    {
        /// <summary>
        /// Old Password
        /// </summary>
        public String oldPassword { get; set; }

        /// <summary>
        /// New Password
        /// </summary>
        public String newPassword { get; set; }


    }
}
