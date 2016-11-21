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
    /// Coordinate Info
    /// </summary>
    public class CoordinateInfo
    {
        /// <summary>
        /// Latitude
        /// </summary>
        public Decimal? latitude { get; set; }

        /// <summary>
        /// Longitude
        /// </summary>
        public Decimal? longitude { get; set; }


    }
}
