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
    /// Settle this transaction with your ledger by verifying its amounts.
        ///             If the transaction is not yet committed, you may specify the "commit" value to commit it to the ledger and allow it to be reported.
        ///             You may also optionally change the transaction's code by specifying the "newTransactionCode" value.
    /// </summary>
    public class ChangeTransactionCodeModel
    {
        /// <summary>
        /// To change the transaction code for this transaction, specify the new transaction code here.
        /// </summary>
        public String newCode { get; set; }


    }
}
