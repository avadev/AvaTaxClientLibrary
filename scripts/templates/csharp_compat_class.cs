using System;
using System.Collections.Generic;
#if PORTABLE
using System.Net.Http;
using System.Threading.Tasks;
#endif

/*
 * AvaTax Software Development Kit for C#
 *
 * (c) 2004-2018 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @@author     Ted Spence <ted.spence@@avalara.com>
 * @@author     Zhenya Frolov <zhenya.frolov@@avalara.com>
 * @@author     Greg Hester <greg.hester@@avalara.com>
 * @@copyright  2004-2017 Avalara, Inc.
 * @@license    https://www.apache.org/licenses/LICENSE-2.0
 * @@version    @SwaggerModel.ApiVersion
 * @@link       https://github.com/avadev/AvaTax-REST-V2-DotNet-SDK
 */

namespace Avalara.AvaTax.RestClient
{
    /// <summary>
    /// The AvaTax compatible client always returns a predictable response object, the "AvaTaxCallResult".
    /// The result object contains information about whether the call resulted in an error, and if so, what
    /// data was in its response.  The caller is expected to use the object and determine how to handle
    /// errors.
    /// 
    /// This class may be useful for programmers who prefer to use synchronous code or to not use exceptions.
    /// </summary>
    public class AvaTaxCompatibleClient : AvaTaxClient
    {
#region Methods
@foreach(var m in SwaggerModel.Methods) {<text>
        /// <summary>
        /// @CSharpComment(m.Summary, 8)
        /// </summary>
        /// <remarks>
        /// @CSharpComment(m.Description, 8)
        /// </remarks></text>
    foreach (var p in m.Params) {
        if (p.CleanParamName == "X-Avalara-Client") continue;
        WriteLine("        /// <param name=\"" + p.CleanParamName + "\">" + CSharpComment(p.Comment, 8) + "</param>");
    }

    Write("        public AvaTaxCallResult " + m.Name + "(");

    bool any = false;
    foreach (var p in m.Params) {
        if (p.CleanParamName == "X-Avalara-Client") continue;
        Write(p.TypeName + " " + p.CleanParamName + ", ");
        any = true;
    }
    if (any) {
        Backtrack(2);
    }

    WriteLine(")");
    WriteLine("        {");
    WriteLine("            var path = new AvaTaxPath(\"" + m.URI + "\");");
    foreach (var p in m.Params) {
        if (p.ParameterLocation == ParameterLocationType.UriPath) {
            WriteLine("            path.ApplyField(\"{0}\", {1});", p.ParamName, p.CleanParamName);
        } else if (p.ParameterLocation == ParameterLocationType.QueryString) {
            WriteLine("            path.AddQuery(\"{0}\", {1});", p.ParamName, p.CleanParamName);
        }
    }
    
	WriteLine("            return RestCall(\"" + FirstCharUpper(m.HttpVerb) + "\", path, " + (m.BodyParam == null ? "null" : "model") + ");");
    WriteLine("        }");
    WriteLine("");
}
#endregion
    }
}
