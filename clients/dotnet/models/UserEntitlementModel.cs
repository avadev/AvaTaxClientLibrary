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
    /// User Entitlement Model
    /// </summary>
    public class UserEntitlementModel
    {
        /// <summary>
        /// List of API names and categories that this user is permitted to access
        /// </summary>
        public String permissions { get; set; }

        /// <summary>
        /// What access privileges does the current user have to see companies?
        /// </summary>
        public String accessLevel { get; set; }

        /// <summary>
        /// The identities of all companies this user is permitted to access
        /// </summary>
        public Int32? companies { get; set; }


    }
}
