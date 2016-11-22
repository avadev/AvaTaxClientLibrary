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
    public enum DocumentType
    {
        /// <summary>
        /// 
        /// </summary>
        SalesOrder,

        /// <summary>
        /// 
        /// </summary>
        SalesInvoice,

        /// <summary>
        /// 
        /// </summary>
        PurchaseOrder,

        /// <summary>
        /// 
        /// </summary>
        PurchaseInvoice,

        /// <summary>
        /// 
        /// </summary>
        ReturnOrder,

        /// <summary>
        /// 
        /// </summary>
        ReturnInvoice,

        /// <summary>
        /// 
        /// </summary>
        InventoryTransferOrder,

        /// <summary>
        /// 
        /// </summary>
        InventoryTransferInvoice,

        /// <summary>
        /// 
        /// </summary>
        ReverseChargeOrder,

        /// <summary>
        /// 
        /// </summary>
        ReverseChargeInvoice,

        /// <summary>
        /// 
        /// </summary>
        Any,


    }
}
