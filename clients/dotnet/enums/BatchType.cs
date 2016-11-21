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
    /// 
    /// </summary>
    public enum BatchType
    {
        /// <summary>
        /// 
        /// </summary>
        AvaCertUpdate,

        /// <summary>
        /// 
        /// </summary>
        AvaCertUpdateAll,

        /// <summary>
        /// 
        /// </summary>
        BatchMaintenance,

        /// <summary>
        /// 
        /// </summary>
        CompanyLocationImport,

        /// <summary>
        /// 
        /// </summary>
        DocumentImport,

        /// <summary>
        /// 
        /// </summary>
        ExemptCertImport,

        /// <summary>
        /// 
        /// </summary>
        ItemImport,

        /// <summary>
        /// 
        /// </summary>
        SalesAuditExport,

        /// <summary>
        /// 
        /// </summary>
        SstpTestDeckImport,

        /// <summary>
        /// 
        /// </summary>
        TaxRuleImport,

        /// <summary>
        /// 
        /// </summary>
        TransactionImport,

        /// <summary>
        /// 
        /// </summary>
        UPCBulkImport,

        /// <summary>
        /// 
        /// </summary>
        UPCValidationImport,


    }
}
