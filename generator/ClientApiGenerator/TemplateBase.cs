using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Microsoft.CSharp;
using System.Dynamic;
using ClientApiGenerator.Models;
using Newtonsoft.Json;
using System.Net;
using System.Text.RegularExpressions;

namespace ClientApiGenerator
{
    public abstract class TemplateBase
    {
        public enum ParameterLocationType
        {
            RequestBody = 0,
            UriPath = 1,
            QueryString = 2,
            Header = 3,
            FormData = 4
        }

        [Browsable(false)]
        public StringBuilder Buffer { get; set; }

        [Browsable(false)]
        public StringWriter Writer { get; set; }

        [Browsable(false)]
        public SwaggerInfo SwaggerModel { get; set; }

        [Browsable(false)]
        public ModelInfo ClassModel { get; set; }

        [Browsable(false)]
        public MethodInfo MethodModel { get; set; }

        [Browsable(false)]
        public EnumInfo EnumModel { get; set; }

        [Browsable(false)]
        public string Category { get; set; }

        public TemplateBase()
        {
            Buffer = new StringBuilder();
            Writer = new StringWriter(Buffer);
        }

        public abstract void Execute();

        #region Writing functions
        /// <summary>
        /// Writes the results of expressions like: "@foo.Bar"
        /// </summary>
        /// <param name="value"></param>
        public virtual void Write(object value)
        {
            WriteLiteral(value);
        }

        public string Emit(string s)
        {
            return s;
        }

        /// <summary>
        /// Writes a CRLF at the end
        /// </summary>
        /// <param name="value"></param>
        /// <param name="paramlist"></param>
        public virtual void WriteLine(string value, params object[] paramlist)
        {
            if (paramlist.Length == 0) {
                WriteLiteral(value + "\r\n");
            } else {
                WriteLiteral(String.Format(value, paramlist) + "\r\n");
            }
        }

        /// <summary>
        /// Writes literals like markup: "<p>Foo</p>"
        /// </summary>
        /// <param name="value"></param>
        public virtual void WriteLiteral(object value)
        {
            Buffer.Append(value);
        }

        /// <summary>
        /// Backtrack a specific number of characters
        /// </summary>
        /// <param name="numChars"></param>
        public virtual void Backtrack(int numChars)
        {
            Buffer.Length -= numChars;
        }
        #endregion

        #region Interface
        /// <summary>
        /// Execute this template against the specified model
        /// </summary>
        /// <param name="model"></param>
        /// <param name="m"></param>
        /// <param name="e"></param>
        /// <returns></returns>
        public virtual string ExecuteTemplate(SwaggerInfo api, MethodInfo method, ModelInfo model, EnumInfo enumDataType)
        {
            Buffer.Clear();
            SwaggerModel = api;
            MethodModel = method;
            ClassModel = model;
            EnumModel = enumDataType;
            Execute();
            return Buffer.ToString();
        }
        #endregion

        #region Fixups
        public string CSharpComment(string c, int indent)
        {
            return CommentLine(c, "/// ", indent);
        }

        private string CommentLine(string originalComment, string commentPrefix, int indent)
        {
            if (String.IsNullOrEmpty(originalComment)) return "";

            // Calculate the correct indent level
            StringBuilder sb = new StringBuilder();
            sb.Append("\r\n");
            for (int i = 0; i < indent; i++) {
                sb.Append(' ');
            }
            sb.Append(commentPrefix);
            var replacement = sb.ToString();

            // Fix whitespace and replace any CR/LF in the stream with new comment lines
            return FixNewlines(FixWhitespace(originalComment))
                .Replace("\r\n", replacement);
        }

        private string FixNewlines(string originalString)
        {
            return Regex.Replace(originalString, @"\r\n|\n\r|\n|\r", "\r\n");
        }

        public string CleanParameterName(string p)
        {
            if (String.IsNullOrEmpty(p)) return "";
            return p.Replace("$", "");
        }

        public string HtmlEncode(string s)
        {
            return WebUtility.HtmlEncode(s);
        }

        public string FirstCharLower(string s)
        {
            return s[0].ToString().ToLower() + s.Substring(1);
        }

        public string FirstCharUpper(string s)
        {
            return s[0].ToString().ToUpper() + s.Substring(1);
        }

        public string SnakeCase(string s)
        {
            StringBuilder sb = new StringBuilder();
            foreach (var c in s) {
                if (Char.IsUpper(c) && sb.Length > 0) {
                    sb.Append('_');
                }
                sb.Append(Char.ToLower(c));
            }
            return sb.ToString();
        }

        public ModelInfo GetModel(string typename)
        {
            return (from m in SwaggerModel.Models where String.Equals(m.SchemaName, typename, StringComparison.CurrentCultureIgnoreCase) select m).FirstOrDefault();
        }

        public string GetExample(string typename)
        {
            // Is this one of our documented types?
            var matchingModel = GetModel(typename);
            if ((matchingModel == null) || (matchingModel.Example == null)) return "";

            // Otherwise here's our example
            var example = JsonConvert.SerializeObject(matchingModel.Example, Formatting.Indented) ?? "";
            return example;
        }

        /// <summary>
        /// Convert a CSharp type name into a PHP type name
        /// </summary>
        /// <param name="typename"></param>
        /// <returns></returns>
        public string PhpTypeName(string typename)
        {
            // Is this an enum?  If so, convert it to a plain object
            if (IsModelType(typename)) {
                return typename.Replace("?", "");
            }

            // Is this an enum?  If so, convert it to a string - we'll add a comment later
            if (IsEnumType(typename)) {
                return "string";
            }

            // Is this a JSON byte array, for example, for a file download or large blob?
            if (typename == "Byte[]") {
                return "string";
            }

            // Is this a fetch result of other objects?
            if (typename.StartsWith("FetchResult<")) {
                return "FetchResult";
            }

            // Is this an array?
            if (typename.StartsWith("List<")) {
                string innertype = typename.Substring(5, typename.Length - 6);
                return PhpTypeName(innertype) + "[]";
            }

            // Map the type as best as possible
            var mapped = GetTypeMap(typename);
            if (mapped == null) return "object";
            return mapped.PHP;
        }


        /// <summary>
        /// Convert a CSharp type name into a Javascript type name
        /// </summary>
        /// <param name="typename"></param>
        /// <returns></returns>
        public string JavascriptTypeName(string typename)
        {
            // Is this an enum?  If so, convert it to a string - we'll add a comment later
            if (IsEnumType(typename)) {
                return "string";
            }

            // Is this a JSON byte array, for example, for a file download or large blob?
            if (typename == "Byte[]") {
                return "string";
            }

            // Is this a fetch result of other objects?
            if (typename.StartsWith("FetchResult<")) {
                return "FetchResult";
            }

            // Is this an array?
            if (typename.StartsWith("List<")) {
                string innertype = typename.Substring(5, typename.Length - 6);
                return JavascriptTypeName(innertype) + "[]";
            }

            // Map the type as best as possible
            var mapped = GetTypeMap(typename);
            if (mapped == null) return "object";

            // Close enough
            return mapped.PHP;
        }

        /// <summary>
        /// Convert a CSharp type name into a Ruby type name
        /// </summary>
        /// <param name="typename"></param>
        /// <returns></returns>
        public string RubyTypeName(string typename)
        {
            // Is this an enum?  If so, convert it to a string - we'll add a comment later
            if (IsEnumType(typename)) {
                return "String";
            }

            // Is this a JSON byte array, for example, for a file download or large blob?
            if (typename == "Byte[]") {
                return "String";
            }

            // Is this a fetch result of other objects?
            if (typename.StartsWith("FetchResult<")) {
                return "FetchResult";
            }

            // Is this an array?
            if (typename.StartsWith("List<")) {
                string innertype = typename.Substring(5, typename.Length - 6);
                return PhpTypeName(innertype) + "[]";
            }

            // Map the type as best as possible
            var mapped = GetTypeMap(typename);
            if (mapped == null) return "Object";
            return mapped.Ruby;
        }

        public string PythonTypeName(string typename)
        {
            // Is this an enum?  If so, convert it to a string - we'll add a comment later
            if (IsEnumType(typename) || IsModelType(typename))
            {
                return typename.Replace("?", "");
            }

            if (typename.StartsWith("FetchResult<"))
            {
                return "FetchResult";
            }

            // Handle arrays
            if (typename.StartsWith("List<"))
            {
                string innertype = typename.Substring(5, typename.Length - 6);
                return PythonTypeName(innertype);
            }

            // Blob arrays are considered strings in Java
            if (typename == "Byte[]")
            {
                return "String";
            }

            // FileResults get returned
            if (typename == "FileResult")
            {
                return "String";
            }

            // Map the type as best as possible
            var mapped = GetTypeMap(typename);
            if (mapped == null)
            {
                return "Python Dictionary";
            }
            return mapped.Python;
        }

        private TypeMap GetTypeMap(string typename)
        {
            var fixedTypeName = typename;
            return (from tm in TypeMap.ALL_TYPES where String.Equals(tm.Csharp, fixedTypeName) select tm).FirstOrDefault();
        }

        public string JavaTypeName(string typename)
        {
            // Is this an enum?  If so, convert it to a string - we'll add a comment later
            if (IsEnumType(typename) || IsModelType(typename)) {
                return typename.Replace("?", "");
            }

            // Fetch results are always named classes
            if (typename.StartsWith("FetchResult<")) {
                string innertype = typename.Substring(12, typename.Length - 13);
                return "FetchResult<" + JavaTypeName(innertype) + ">";
            }

            // Handle arrays
            if (typename.StartsWith("List<")) {
                string innertype = typename.Substring(5, typename.Length - 6);
                return "ArrayList<" + JavaTypeName(innertype) + ">";
            }

            // Blob arrays are considered strings in Java
            if (typename == "Byte[]") {
                return "String";
            }

            // FileResults get returned
            if (typename == "FileResult") {
                return "String";
            }

            // Map the type as best as possible
            var mapped = GetTypeMap(typename);
            if (mapped == null) {
                return "HashMap<String, String>";
            }
            return mapped.Java;
        }

        public bool IsEnumType(string typename)
        {
            if (typename.EndsWith("?")) {
                typename = typename.Substring(0, typename.Length - 1);
            }
            return (SwaggerModel.Enums.Any(e => e.EnumDataType == typename));
        }

        public bool IsModelType(string typename)
        {
            if (typename.EndsWith("?")) {
                typename = typename.Substring(0, typename.Length - 1);
            }
            return (SwaggerModel.Models.Any(m => m.SchemaName == typename));
        }

        public string JavadocComment(MethodInfo method, int indent)
        {
            List<string> lines = new List<string>();
            lines.Add(method.Summary);
            lines.Add("");

            // Break apart description into multiple lines
            var descriptionLines = SplitLines(method.Description);
            if (descriptionLines.Count > 0) {
                lines.AddRange(descriptionLines);
                lines.Add("");
            }

            // Add one line for each parameter
            foreach (var pc in method.Params) {
                if (pc.CleanParamName != "X-Avalara-Client") {
                    lines.Add("@param " + pc.CleanParamName + " " + PhpTypeComment(SwaggerModel, pc));
                }
            }

            // Add the "return" value
            lines.Add("@return " + JavaTypeName(method.ResponseTypeName));

            // Construct an indent
            StringBuilder sb = new StringBuilder();
            for (int i = 0; i < indent; i++) sb.Append(' ');
            var indentString = sb.ToString();

            // Now turn these lines into a JavaDoc
            indentString = indentString + " ";
            sb.AppendLine("/**");
            foreach (var l in lines) {
                sb.Append(indentString);
                sb.Append("* ");
                sb.AppendLine(l);
            }
            sb.Append(indentString);
            sb.AppendLine("*/");
            return sb.ToString();
        }

        public string PhpComment(MethodInfo method, int indent)
        {
            List<string> lines = new List<string>();
            lines.Add(method.Summary);
            lines.Add("");

            // Break apart description into multiple lines
            var descriptionLines = SplitLines(method.Description);
            if (descriptionLines.Count > 0) {
                lines.AddRange(descriptionLines);
                lines.Add("");
            }

            // Add one line for each parameter
            foreach (var pc in method.Params) {
                if (pc.CleanParamName != "X-Avalara-Client") {
                    lines.Add("@param " + PhpTypeName(pc.TypeName) + " " + pc.CleanParamName + " " + PhpTypeComment(SwaggerModel, pc));
                }
            }

            // Add the "return" value
            lines.Add("@return " + PhpTypeName(method.ResponseTypeName));

            // Construct an indent
            StringBuilder sb = new StringBuilder();
            for (int i = 0; i < indent; i++) sb.Append(' ');
            var indentString = sb.ToString();

            // Now turn these lines into a JavaDoc
            indentString = indentString + " ";
            sb.AppendLine("/**");
            foreach (var l in lines) {
                sb.Append(indentString);
                sb.Append("* ");
                sb.AppendLine(l);
            }
            sb.Append(indentString);
            sb.AppendLine("*/");
            return sb.ToString();
        }

        private static List<string> SplitLines(string raw)
        {
            List<string> results = new List<string>();
            if (raw == null) {
                return results;
            }
            StringBuilder sb = new StringBuilder();
            foreach (var c in raw) {
                if (c == '\n') {
                    results.Add(sb.ToString());
                    sb.Length = 0;
                } else if (c != '\r') {
                    sb.Append(c);
                }
            }

            return results;
        }

        public string PhpComment(string c, int indent)
        {
            return CommentLine(c, "* ", indent);
        }

        public string RubyComment(string c, int indent)
        {
            StringBuilder sb = new StringBuilder();
            var allcomments = CommentLine(c, "# ", indent);
            var rubycomments = allcomments.Split(new string[] { "\n" }, 999, StringSplitOptions.RemoveEmptyEntries);
            foreach (var line in rubycomments) {
                var trimmed = line.TrimEnd();
                if (!String.IsNullOrEmpty(trimmed)) {
                    sb.Append(trimmed);
                    sb.Append("\r\n");
                }
            }
            if (sb.Length > 0) sb.Length -= 2;
            return sb.ToString();
        }

        public string PythonComment(string c, int indent)
        {
            StringBuilder sb = new StringBuilder();
            var allcomments = CommentLine(c, "", indent);
            var pycomments = allcomments.Split(new string[] { "\n" }, 999, StringSplitOptions.RemoveEmptyEntries);
            foreach (var line in pycomments)
            {
                var trimmed = line.TrimEnd();
                if (!String.IsNullOrEmpty(trimmed))
                {
                    sb.Append(trimmed);
                    sb.Append("\r\n");
                }
            }
            if (sb.Length > 0) sb.Length -= 2;
            return sb.ToString();
        }

        public string FixWhitespace(string s)
        {
            return String.Join(" ", s.Split(new char[] { ' ' }, StringSplitOptions.RemoveEmptyEntries)).Trim();
        }

        public string PhpTypeComment(SwaggerInfo s, ParameterInfo p)
        {
            string comment = "";
            if (p.Comment != null) {
                comment = NoNewlines(FixWhitespace(p.Comment));
            }

            // Is this an enum?  If so, convert it to a string - we'll add a comment later
            if (IsEnumType(p.TypeName)) {
                return comment + " (See " + p.TypeName.Replace("?", "") + "::* for a list of allowable values)";
            } else if (p.TypeName == "Byte[]") {
                return comment + " (This value is encoded as a Base64 string)";
            }

            return comment;
        }

        public string SanitizeVariableName(string rawName)
        {
            StringBuilder sb = new StringBuilder();
            foreach (var c in rawName) {
                if ((c >= 'a' && c <= 'z')
                    || (c >= 'A' && c <= 'Z')
                    || (c >= '0' && c <= '9')) {
                    sb.Append(c);
                } else {
                    sb.Append("_");
                }
            }
            return sb.ToString();
        }

        private string NoNewlines(string v)
        {
            return v.Replace('\r', ' ').Replace('\n', ' ');
        }
        #endregion
    }
}
