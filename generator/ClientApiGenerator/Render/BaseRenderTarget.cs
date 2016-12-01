using ClientApiGenerator.Models;
using Microsoft.CSharp;
using System;
using System.CodeDom.Compiler;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Reflection;
using System.Text;
using System.Threading.Tasks;
using System.Web.Razor;

namespace ClientApiGenerator.Render
{
    public class BaseRenderTarget
    {
        /// <summary>
        /// Render this particular type of client library
        /// </summary>
        /// <param name="model"></param>
        /// <param name="rootPath"></param>
        public virtual void Render(ApiModel model, string rootPath)
        {
        }

        #region Razor Engine Config
        private RazorTemplateEngine _engine = null;
        private RazorTemplateEngine SetupRazorEngine()
        {
            if (_engine != null) return _engine;

            // Set up the hosting environment

            // a. Use the C# language (you could detect this based on the file extension if you want to)
            RazorEngineHost host = new RazorEngineHost(new CSharpRazorCodeLanguage());

            // b. Set the base class
            host.DefaultBaseClass = typeof(TemplateBase).FullName;

            // c. Set the output namespace and type name
            host.DefaultNamespace = "RazorOutput";
            host.DefaultClassName = "Template";

            // d. Add default imports
            host.NamespaceImports.Add("System");

            // Create the template engine using this host
            _engine = new RazorTemplateEngine(host);
            return _engine;
        }

        protected TemplateBase MakeRazorTemplate(string template)
        {
            // Construct a razor templating engine and a compiler
            var engine = SetupRazorEngine();
            var codeProvider = new CSharpCodeProvider();

            // Produce generator results for all templates
            GeneratorResults results = null;
            string code = null;
            using (var r = new StringReader(template)) {

                // Produce analyzed code
                results = engine.GenerateCode(r);

                // Make a code generator
                using (var sw = new StringWriter()) {
                    codeProvider.GenerateCodeFromCompileUnit(results.GeneratedCode, sw, new System.CodeDom.Compiler.CodeGeneratorOptions());
                    code = sw.GetStringBuilder().ToString();
                }
            }

            // Construct a new assembly for this
            string outputAssemblyName = String.Format("Temp_{0}.dll", Guid.NewGuid().ToString("N"));
            var compiled = codeProvider.CompileAssemblyFromDom(
                new CompilerParameters(new string[] {
                    typeof(Program).Assembly.CodeBase.Replace("file:///", "").Replace("/", "\\")
                }, outputAssemblyName),
                results.GeneratedCode);

            // Did the compiler produce an error?
            if (compiled.Errors.HasErrors) {
                CompilerError err = compiled.Errors.OfType<CompilerError>().Where(ce => !ce.IsWarning).First();
                throw new Exception(String.Format("Error Compiling Template: ({0}, {1}) {2}", err.Line, err.Column, err.ErrorText));

            // Load this assembly into the project
            } else {
                var asm = Assembly.LoadFrom(outputAssemblyName);
                if (asm == null) {
                    throw new Exception("Error loading template assembly");

                // Get the template type
                } else {
                    Type typ = asm.GetType("RazorOutput.Template");
                    if (typ == null) {
                        throw new Exception(String.Format("Could not find type RazorOutput.Template in assembly {0}", asm.FullName));
                    } else {
                        TemplateBase newTemplate = Activator.CreateInstance(typ) as TemplateBase;
                        if (newTemplate == null) {
                            throw new Exception("Could not construct RazorOutput.Template or it does not inherit from TemplateBase");
                        } else {
                            return newTemplate;
                        }
                    }
                }
            }
        }
        #endregion
    }
}
