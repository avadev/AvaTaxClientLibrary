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
    public enum ResolutionQuality
    {
        /// <summary>
        /// 
        /// </summary>
        NotCoded,

        /// <summary>
        /// 
        /// </summary>
        External,

        /// <summary>
        /// 
        /// </summary>
        CountryCentroid,

        /// <summary>
        /// 
        /// </summary>
        RegionCentroid,

        /// <summary>
        /// 
        /// </summary>
        PartialCentroid,

        /// <summary>
        /// 
        /// </summary>
        PostalCentroidGood,

        /// <summary>
        /// 
        /// </summary>
        PostalCentroidBetter,

        /// <summary>
        /// 
        /// </summary>
        PostalCentroidBest,

        /// <summary>
        /// 
        /// </summary>
        Intersection,

        /// <summary>
        /// 
        /// </summary>
        Interpolated,

        /// <summary>
        /// 
        /// </summary>
        Rooftop,

        /// <summary>
        /// 
        /// </summary>
        Constant,


    }
}
