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
    /// Tax Rate Model
    /// </summary>
    public class TaxRateModel
    {
        /// <summary>
        /// Total Rate
        /// </summary>
        public Decimal? totalRate { get; set; }

        /// <summary>
        /// Rates
        /// </summary>
        public RateModel[] rates { get; set; }


    }
}
