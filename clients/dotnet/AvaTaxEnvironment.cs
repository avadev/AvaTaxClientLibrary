using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Avalara.AvaTax.RestClient
{
    /// <summary>
    /// Represents an environment for AvaTax
    /// </summary>
    public enum AvaTaxEnvironment
    {
        /// <summary>
        /// Represents the sandbox environment, https://sandbox-rest.avatax.com
        /// </summary>
        Sandbox = 0,

        /// <summary>
        /// Represents the production environment, https://rest.avatax.com
        /// </summary>
        Production = 1
    }
}
