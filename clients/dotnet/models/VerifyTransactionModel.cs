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
    /// Verify this transaction by matching it to values in your accounting system.
    /// </summary>
    public class VerifyTransactionModel
    {
        /// <summary>
        /// Transaction Date - The date on the invoice, purchase order, etc.
        /// </summary>
        public DateTime? verifyTransactionDate { get; set; }

        /// <summary>
        /// Total Amount - The total amount (not including tax) for the document.
        /// </summary>
        public Decimal? verifyTotalAmount { get; set; }

        /// <summary>
        /// Total Tax - The total tax for the document.
        /// </summary>
        public Decimal? verifyTotalTax { get; set; }


    }
}
