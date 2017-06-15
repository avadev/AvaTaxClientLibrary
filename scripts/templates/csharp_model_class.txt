using System;
using System.Collections.Generic;
using Newtonsoft.Json;

/*
 * AvaTax API Client Library
 *
 * (c) 2004-2017 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @@author Ted Spence
 * @@author Zhenya Frolov
 */

namespace Avalara.AvaTax.RestClient
{
    /// <summary>
    /// @CSharpComment(ClassModel.Comment, 4)
    /// </summary>
    public class @ClassModel.SchemaName
    {
@foreach(var p in ClassModel.Properties) {
    WriteLine("        /// <summary>");
    WriteLine("        /// " + CSharpComment(p.Comment, 8));
    WriteLine("        /// </summary>");
    if(p.CleanParamName.Contains(".")) {
      WriteLine("        [JsonProperty(PropertyName = \"" + p.CleanParamName + "\")]");
      WriteLine("        public " + p.TypeName + " " + p.StrippedPackageParamName + " { get; set; }");
    } else {
      WriteLine("        public " + p.TypeName + " " + p.CleanParamName + " { get; set; }");
    }
    
    WriteLine("");
}

        /// <summary>
        /// Convert this object to a JSON string of itself
        /// </summary>
        /// <returns>A JSON string of this object</returns>
        public override string ToString()
        {
            return JsonConvert.SerializeObject(this, new JsonSerializerSettings() { Formatting = Formatting.Indented });
        }
    }
}
