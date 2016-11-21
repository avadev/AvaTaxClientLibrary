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
    /// Tax Authority Info
    /// </summary>
    public class TaxAuthorityInfo
    {
        /// <summary>
        /// Avalara Id
        /// </summary>
        public String avalaraId { get; set; }

        /// <summary>
        /// Jurisdiction Name
        /// </summary>
        public String jurisdictionName { get; set; }

        /// <summary>
        /// Jurisdiction Type
        /// </summary>
        public String jurisdictionType { get; set; }

        /// <summary>
        /// Signature Code
        /// </summary>
        public String signatureCode { get; set; }


    }
}
