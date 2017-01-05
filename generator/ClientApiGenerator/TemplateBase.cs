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

namespace ClientApiGenerator
{
    public abstract class TemplateBase
    {
        [Browsable(false)]
        public StringBuilder Buffer { get; set; }

        [Browsable(false)]
        public StringWriter Writer { get; set; }

        [Browsable(false)]
        public SwaggerInfo SwaggerModel { get; set; }

        [Browsable(false)]
        public ModelInfo ClassModel { get; set; }

        [Browsable(false)]
        public EnumInfo EnumModel { get; set; }

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
        public virtual string ExecuteTemplate(SwaggerInfo api, ModelInfo m, EnumInfo e)
        {
            Buffer.Clear();
            SwaggerModel = api;
            ClassModel = m;
            EnumModel = e;
            Execute();
            return Buffer.ToString();
        }
        #endregion

        #region Fixups
        public string CSharpComment(string c)
        {
            if (String.IsNullOrEmpty(c)) return "";
            return FixWhitespace(c).Replace("\r\n", "\r\n        /// ");
        }

        public string CleanParameterName(string p)
        {
            if (String.IsNullOrEmpty(p)) return "";
            return p.Replace("$", "");
        }

        public string FirstCharLower(string s)
        {
            return s[0].ToString().ToLower() + s.Substring(1);
        }

        public string PhpTypeName(string typename)
        {
            // Is this an enum?  If so, convert it to a string - we'll add a comment later
            if (IsEnumType(typename)) {
                return "string";
            }

            // Otherwise handle it here
            if (String.Equals(typename, "Int32", StringComparison.CurrentCultureIgnoreCase)
                || String.Equals(typename, "Int32?", StringComparison.CurrentCultureIgnoreCase)
                || String.Equals(typename, "Int64", StringComparison.CurrentCultureIgnoreCase)
                || String.Equals(typename, "Int64?", StringComparison.CurrentCultureIgnoreCase)
                || String.Equals(typename, "Byte", StringComparison.CurrentCultureIgnoreCase)
                || String.Equals(typename, "Byte?", StringComparison.CurrentCultureIgnoreCase)
                || String.Equals(typename, "Int16", StringComparison.CurrentCultureIgnoreCase)
                || String.Equals(typename, "Int16?", StringComparison.CurrentCultureIgnoreCase)) {
                return "int";
            } else if (String.Equals(typename, "String", StringComparison.CurrentCultureIgnoreCase)) {
                return "string";
            } else if (String.Equals(typename, "Boolean", StringComparison.CurrentCultureIgnoreCase)
                || String.Equals(typename, "Boolean?", StringComparison.CurrentCultureIgnoreCase)) {
                return "boolean";
            } else if (String.Equals(typename, "DateTime", StringComparison.CurrentCultureIgnoreCase)
                || String.Equals(typename, "DateTime?", StringComparison.CurrentCultureIgnoreCase)) {
                return "string";
            } else if (String.Equals(typename, "Decimal", StringComparison.CurrentCultureIgnoreCase)
                || String.Equals(typename, "Decimal?", StringComparison.CurrentCultureIgnoreCase)) {
                return "float";
            } else if (typename.StartsWith("FetchResult<")) {
                return "FetchResult";
            } else if (typename.StartsWith("List<")) {
                return typename.Substring(5, typename.Length - 6) + "[]";
            } else if (SwaggerModel.Models.Any(m => m.SchemaName == typename)) {
                return typename;

                // Json byte arrays are in Base64 encoded text
            } else if (typename == "Byte[]") {
                return "string";
            } else if (typename.StartsWith("Dictionary<")) {
                return "object";
            }
            return typename;
        }

        private bool IsEnumType(string typename)
        {
            if (typename.EndsWith("?")) {
                typename = typename.Substring(0, typename.Length - 1);
            }
            return (SwaggerModel.Enums.Any(e => e.EnumDataType == typename));
        }

        public string PhpComment(string c)
        {
            if (String.IsNullOrEmpty(c)) return "";
            return FixWhitespace(c).Replace("\r\n", "\r\n *");
        }

        public string FixWhitespace(string s)
        {
            return String.Join(" ", s.Split(new char[] { ' ' }, StringSplitOptions.RemoveEmptyEntries)).Trim();
        }

        public string PhpTypeComment(SwaggerInfo s, ParameterInfo p)
        {
            string comment = "";
            if (p.Comment != null) {
                comment = FixWhitespace(p.Comment).Replace("\r\n", " ");
            }

            // Is this an enum?  If so, convert it to a string - we'll add a comment later
            if (IsEnumType(p.TypeName)) {
                return comment + " (See " + p.TypeName.Replace("?", "") + "::* for a list of allowable values)";
            } else if (p.TypeName == "Byte[]") {
                return comment + " (This value is encoded as a Base64 string)";
            }

            return comment;
        }
        #endregion
    }
}
