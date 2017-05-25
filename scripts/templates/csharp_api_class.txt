using System;
using System.Collections.Generic;
#if PORTABLE
using System.Net.Http;
using System.Threading.Tasks;
#endif

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
 * @@copyright  2004-2017 Avalara, Inc.
 * @@license    https://www.apache.org/licenses/LICENSE-2.0
 * @@version    @SwaggerModel.ApiVersion
 * @@link       https://github.com/avadev/AvaTax-REST-V2-DotNet-SDK
 */

namespace Avalara.AvaTax.RestClient
{
    public partial class AvaTaxClient
    {
        /// <summary>
        /// Returns the version number of the API used to generate this class
        /// </summary>
        public static string API_VERSION { get { return "@SwaggerModel.ApiVersion"; } }

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

    Write("        public " + m.ResponseTypeName + " " + m.Name + "(");

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
    
    if (m.ResponseTypeName == "String") {
        WriteLine("            return RestCallString(\"" + FirstCharUpper(m.HttpVerb) + "\", path, " + (m.BodyParam == null ? "null" : "model") + ");");
    } else if (m.ResponseTypeName == "FileResult") {
        WriteLine("            return RestCallFile(\"" + FirstCharUpper(m.HttpVerb) + "\", path, " + (m.BodyParam == null ? "null" : "model") + ");");
    } else {
        WriteLine("            return RestCall<" + m.ResponseTypeName + ">(\"" + FirstCharUpper(m.HttpVerb) + "\", path, " + (m.BodyParam == null ? "null" : "model") + ");");
    }

    WriteLine("        }");
    WriteLine("");
}
#endregion

#region Asynchronous
#if PORTABLE
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
    Write("        public async Task<" + m.ResponseTypeName + "> " + m.Name + "Async(");

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
    if (m.ResponseTypeName == "String") {
        WriteLine("            return await RestCallStringAsync(\"" + FirstCharUpper(m.HttpVerb) + "\", path, " + (m.BodyParam == null ? "null" : "model") + ").ConfigureAwait(false);");
    } else {
        WriteLine("            return await RestCallAsync<" + m.ResponseTypeName + ">(\"" + FirstCharUpper(m.HttpVerb) + "\", path, " + (m.BodyParam == null ? "null" : "model") + ").ConfigureAwait(false);");
    }
    WriteLine("        }");
    WriteLine("");
}
#endif
#endregion
    }
}
