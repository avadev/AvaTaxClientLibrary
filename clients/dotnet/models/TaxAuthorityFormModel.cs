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
    /// Represents a form that can be filed with a tax authority.
    /// </summary>
    public class TaxAuthorityFormModel
    {
        /// <summary>
        /// The unique ID number of the tax authority.
        /// </summary>
        public Int32 taxAuthorityId { get; set; }

        /// <summary>
        /// The form name of the form for this tax authority.
        /// </summary>
        public String formName { get; set; }


    }
}
