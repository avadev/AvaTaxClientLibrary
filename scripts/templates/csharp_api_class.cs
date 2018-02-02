using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Threading.Tasks;

/*
 * AvaTax Software Development Kit for C#
 *
 * (c) 2004-2017 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @@author     Ted Spence <ted.spence@@avalara.com>
 * @@author     Zhenya Frolov <zhenya.frolov@@avalara.com>
 * @@author     Greg Hester <greg.hester@@avalara.com>
 * @@copyright  2004-2018 Avalara, Inc.
 * @@license    https://www.apache.org/licenses/LICENSE-2.0
 * @@version    @SwaggerModel.ApiVersion
 * @@link       https://github.com/avadev/AvaTax-REST-V2-DotNet-SDK
 */

namespace Avalara.AvaTax.RestClient
{
    /// <summary>
    /// This client uses the `async` features of DotNet and structured error handling.
    /// All API calls are fully asynchronous, and all methods throw AvaTaxError exceptions when the server
    /// responds with a 400 or 500 error code.
    /// </summary>
    public class AvaTaxAsyncClient : AvaTaxClient
    {
#region Methods
@foreach(var m in SwaggerModel.Methods) {<text>
        /// <summary>
        /// @CSharpComment(m.Summary, 8);
        /// </summary>
        /// <remarks>
        /// @CSharpComment(m.Description, 8);
        /// </remarks></text>
    foreach (var p in m.Params) {
        if (p.CleanParamName == "X-Avalara-Client") continue;
        WriteLine("        /// <param name=\"" + p.CleanParamName + "\">" + CSharpComment(p.Comment, 8) + "</param>");
    }
    Write("        public async Task<" + m.ResponseTypeName + "> " + m.Name + "(");

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
	WriteLine("            var result = await RestCallAsync(\"" + FirstCharUpper(m.HttpVerb) + "\", path, " + (m.BodyParam == null ? "null" : "model") + ").ConfigureAwait(false);");
	WriteLine("            result.CheckAndThrow();");
    if (m.ResponseTypeName == "String") {
        WriteLine("            return result.ResponseString;");
    } else if (m.ResponseTypeName == "FileResult") {
        WriteLine("            return new FileResult(result);");
    } else {
        WriteLine("            return result.Deserialize<" + m.ResponseTypeName + ">();");
    }
    WriteLine("        }");
    WriteLine("");
}
#endregion
    }
}
