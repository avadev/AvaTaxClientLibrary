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
    /// Represents a service or a subscription type.
    /// </summary>
    public class SubscriptionTypeModel
    {
        /// <summary>
        /// The unique ID number of this subscription type.
        /// </summary>
        public Int32? id { get; set; }

        /// <summary>
        /// The friendly name of the service this subscription type represents.
        /// </summary>
        public String description { get; set; }


    }
}
