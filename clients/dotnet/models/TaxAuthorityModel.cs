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
    /// Tax Authority Model
    /// </summary>
    public class TaxAuthorityModel
    {
        /// <summary>
        /// The unique ID number of this tax authority.
        /// </summary>
        public Int32 id { get; set; }

        /// <summary>
        /// The friendly name of this tax authority.
        /// </summary>
        public String name { get; set; }

        /// <summary>
        /// The type of this tax authority.
        /// </summary>
        public Int32? taxAuthorityTypeId { get; set; }

        /// <summary>
        /// The unique ID number of the jurisdiction for this tax authority.
        /// </summary>
        public Int32? jurisdictionId { get; set; }


    }
}
