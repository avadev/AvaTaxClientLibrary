using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator
{
    public abstract class TemplateBase
    {
        [Browsable(false)]
        public StringBuilder Buffer { get; set; }

        [Browsable(false)]
        public StringWriter Writer { get; set; }

        [Browsable(false)]
        public dynamic Model { get; set; }

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
            // Don't need to do anything special
            // Razor for ASP.Net does HTML encoding here.
            WriteLiteral(value);
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
        /// <returns></returns>
        public virtual string ExecuteTemplate(dynamic model)
        {
            Model = model;
            Execute();
            return Buffer.ToString();
        }
    }
}
