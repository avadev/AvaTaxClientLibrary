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
    /// Rate Model
    /// </summary>
    public class RateModel
    {
        /// <summary>
        /// Rate
        /// </summary>
        public Decimal? rate { get; set; }

        /// <summary>
        /// Name
        /// </summary>
        public String name { get; set; }

        /// <summary>
        /// Type
        /// </summary>
        public String type { get; set; }


    }
}
