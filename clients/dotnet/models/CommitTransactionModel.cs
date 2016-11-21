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
    /// Commit this transaction as permanent
    /// </summary>
    public class CommitTransactionModel
    {
        /// <summary>
        /// Set this value to be true to commit this transaction.
        ///             Committing a transaction allows it to be reported on a tax return.  Uncommitted transactions will not be reported.
        /// </summary>
        public Boolean commit { get; set; }


    }
}
