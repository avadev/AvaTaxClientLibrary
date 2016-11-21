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
    /// Tells you whether this location object has been correctly set up to the local jurisdiction's standards
    /// </summary>
    public class LocationValidationModel
    {
        /// <summary>
        /// True if the location has a value for each jurisdiction-required setting.
        ///             The user is required to ensure that the values are correct according to the jurisdiction; this flag
        ///             does not indicate whether the taxing jurisdiction has accepted the data you have provided.
        /// </summary>
        public Boolean? settingsValidated { get; set; }

        /// <summary>
        /// A list of settings that must be defined for this location
        /// </summary>
        public LocationQuestionModel[] requiredSettings { get; set; }


    }
}
