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
    /// Represents an address to resolve.  
        ///             Note that there are no data validations on this model since malformed addresses will get "best-guess" resolution.
    /// </summary>
    public class AddressInfo
    {
        /// <summary>
        /// Line1
        /// </summary>
        public String line1 { get; set; }

        /// <summary>
        /// Line2
        /// </summary>
        public String line2 { get; set; }

        /// <summary>
        /// Line3
        /// </summary>
        public String line3 { get; set; }

        /// <summary>
        /// City
        /// </summary>
        public String city { get; set; }

        /// <summary>
        /// State / Province / Region
        /// </summary>
        public String region { get; set; }

        /// <summary>
        /// Two character ISO 3166 Country Code
        /// </summary>
        public String country { get; set; }

        /// <summary>
        /// Postal Code / Zip Code
        /// </summary>
        public String postalCode { get; set; }

        /// <summary>
        /// Geospatial latitude measurement
        /// </summary>
        public Decimal? latitude { get; set; }

        /// <summary>
        /// Geospatial longitude measurement
        /// </summary>
        public Decimal? longitude { get; set; }



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