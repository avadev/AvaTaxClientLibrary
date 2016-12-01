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
        public static string CSharpComment(string c)
        {
            if (String.IsNullOrEmpty(c)) return "";
            return c.Replace("\r\n", "\r\n        /// ");
        }

        public static string CleanParameterName(string p)
        {
            if (String.IsNullOrEmpty(p)) return "";
            return p.Replace("$", "");
        }
        #endregion
    }
}
