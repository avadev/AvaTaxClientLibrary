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
        public virtual void WriteLine(object value)
        {
            WriteLiteral(value.ToString() + "\r\n");
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
    }
}
