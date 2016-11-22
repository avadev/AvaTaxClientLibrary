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
    /// Represents a single security role.
    /// </summary>
    public class SecurityRoleModel
    {
        /// <summary>
        /// The unique ID number of this security role.
        /// </summary>
        public String id { get; set; }

        /// <summary>
        /// A description of this security role
        /// </summary>
        public String description { get; set; }



        /// <summary>
        /// Convert this object to a JSON string of itself
        /// </summary>
        /// <returns>A JSON string of this object</returns>
        public override string ToString()
		{
            return JsonConvert.SerializeObject(this, new JsonSerializerSettings() { Formatting = Formatting.Indented });
		}
    }
}
