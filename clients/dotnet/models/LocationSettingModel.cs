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
    /// Represents the answer to one local jurisdiction question for a location.
    /// </summary>
    public class LocationSettingModel
    {
        /// <summary>
        /// The unique ID number of the location question answered.
        /// </summary>
        public Int32? questionId { get; set; }

        /// <summary>
        /// The answer the user provided.
        /// </summary>
        public String value { get; set; }


    }
}
