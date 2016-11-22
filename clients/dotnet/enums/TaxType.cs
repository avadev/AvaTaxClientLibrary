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
    public enum TaxType
    {
        /// <summary>
        /// 
        /// </summary>
        ConsumerUse,

        /// <summary>
        /// 
        /// </summary>
        Excise,

        /// <summary>
        /// 
        /// </summary>
        Fee,

        /// <summary>
        /// 
        /// </summary>
        Input,

        /// <summary>
        /// 
        /// </summary>
        Nonrecoverable,

        /// <summary>
        /// 
        /// </summary>
        Output,

        /// <summary>
        /// 
        /// </summary>
        Rental,

        /// <summary>
        /// 
        /// </summary>
        Sales,

        /// <summary>
        /// 
        /// </summary>
        Use,


    }
}
