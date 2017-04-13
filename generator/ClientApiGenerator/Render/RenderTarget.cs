using ClientApiGenerator.Models;
using Microsoft.CSharp;
using System;
using System.CodeDom.Compiler;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Reflection;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading.Tasks;
using System.Web.Razor;

namespace ClientApiGenerator.Render
{
    public class RenderTarget
    {
        /// <summary>
        /// The name of this target
        /// </summary>
        public string name { get; set; }

        /// <summary>
        /// The root folder for all files and tasks under this target
        /// </summary>
        public string rootFolder { get; set; }

        /// <summary>
        /// The list of templates to apply for this target
        /// </summary>
        public List<RenderTemplateTask> templates { get; set; }

        /// <summary>
        /// The list of fixups to apply for this target
        /// </summary>
        public List<RenderFixupTask> fixups { get; set; }

        #region Rendering
        /// <summary>
        /// Render this particular type of client library
        /// </summary>
        /// <param name="api"></param>
        /// <param name="rootPath"></param>
        public virtual void Render(SwaggerInfo api)
        {
            if (templates != null) {

                // Iterate through each template
                foreach (var template in templates) {
                    Console.WriteLine($"     Rendering {name}.{template.file}...");

                    // What type of template are we looking at?
                    switch (template.type) {

                        // A single template file for the entire API
                        case TemplateType.singleFile:
                            RenderSingleFile(api, template);
                            break;

                        // A separate file for each method category in the API
                        case TemplateType.methodCategories:
                            RenderMethodCategories(api, template);
                            break;

                        // One file per category
                        case TemplateType.methods:
                            RenderMethods(api, template);
                            break;

                        // One file per model
                        case TemplateType.models:
                            RenderModels(api, template);
                            break;

                        // One file per model
                        case TemplateType.uniqueModels:
                            RenderUniqueModels(api, template);
                            break;

                        // One file per enum
                        case TemplateType.enums:
                            RenderEnums(api, template);
                            break;
                    }
                }
            }

            // Are there any fixups?
            if (fixups != null) {
                foreach (var fixup in fixups) {
                    FixupOneFile(api, fixup);
                }
            }
        }

        private void FixupOneFile(SwaggerInfo api, RenderFixupTask fixup)
        {
            var fn = Path.Combine(rootFolder, fixup.file);
            Console.Write($"Executing fixup for {fn}... ");
            if (!File.Exists(fn)) {
                Console.WriteLine(" File not found!");
            } else {

                // Figure out what a three-segment version number is
                var sb = new StringBuilder();
                int numPeriods = 0;
                foreach (char c in api.ApiVersion) {
                    if (c == '.') numPeriods++;
                    if (numPeriods > 3 || c == '-') break;
                    sb.Append(c);
                }

                // Determine what the new string is
                var newstring = fixup.replacement
                    .Replace("@@versionNumberWithPeriods@@", api.ApiVersion.Replace("-", "."))
                    .Replace("@@versionNumber@@", api.ApiVersion)
                    .Replace("@@versionNumberThreeSegment@@", sb.ToString());

                // What encoding did they want - basically everyone SHOULD want UTF8, but ascii is possible I guess
                Encoding e = Encoding.UTF8;
                if (fixup.encoding == "ASCII") {
                    e = Encoding.ASCII;
                }

                // Execute the fixup
                ReplaceStringInFile(fn, fixup.regex, newstring, e);
                Console.WriteLine(" Done!");
            }
        }

        private void RenderEnums(SwaggerInfo api, RenderTemplateTask template)
        {
            foreach (var enumDataType in api.Enums) {
                var outputPath = Path.Combine(rootFolder, template.output.Replace("{enumDataType}", enumDataType.EnumDataType.ToLower()));
                Directory.CreateDirectory(Path.GetDirectoryName(outputPath));
                var output = template.razor.ExecuteTemplate(api, null, null, enumDataType);
                File.WriteAllText(outputPath, output);
            }
        }

        private void RenderUniqueModels(SwaggerInfo api, RenderTemplateTask template)
        {
            var oldModels = api.Models;
            api.Models = (from m in api.Models where !m.SchemaName.StartsWith("FetchResult") select m).ToList();
            foreach (var model in api.Models) {
                var outputPath = Path.Combine(rootFolder, template.output.Replace("{model}", model.SchemaName.ToLower()));
                Directory.CreateDirectory(Path.GetDirectoryName(outputPath));
                var output = template.razor.ExecuteTemplate(api, null, model, null);
                File.WriteAllText(outputPath, output);
            }
            api.Models = oldModels;
        }

        private void RenderModels(SwaggerInfo api, RenderTemplateTask template)
        {
            foreach (var model in api.Models) {
                var outputPath = Path.Combine(rootFolder, template.output.Replace("{model}", model.SchemaName.ToLower()));
                Directory.CreateDirectory(Path.GetDirectoryName(outputPath));
                var output = template.razor.ExecuteTemplate(api, null, model, null);
                File.WriteAllText(outputPath, output);
            }
        }

        private void RenderMethods(SwaggerInfo api, RenderTemplateTask template)
        {
            foreach (var method in api.Methods) {
                var outputPath = Path.Combine(rootFolder, template.output.Replace("{method}", method.Name.ToLower()).Replace("{category}", method.Category.ToLower()));
                Directory.CreateDirectory(Path.GetDirectoryName(outputPath));
                var output = template.razor.ExecuteTemplate(api, method, null, null);
                File.WriteAllText(outputPath, output);
            }
        }

        private void RenderMethodCategories(SwaggerInfo api, RenderTemplateTask template)
        {
            var categories = (from m in api.Methods select m.Category).Distinct();
            foreach (var c in categories) {
                var oldMethods = api.Methods;
                api.Methods = (from m in api.Methods where m.Category == c select m).ToList();
                var outputPath = Path.Combine(rootFolder, template.output.Replace("{category}", c.ToLower()));
                Directory.CreateDirectory(Path.GetDirectoryName(outputPath));
                template.razor.Category = c;
                var output = template.razor.ExecuteTemplate(api, null, null, null);
                template.razor.Category = null;
                File.WriteAllText(outputPath, output);
                api.Methods = oldMethods;
            }
        }

        private void RenderSingleFile(SwaggerInfo api, RenderTemplateTask template)
        {
            var outputPath = Path.Combine(rootFolder, template.output);
            Directory.CreateDirectory(Path.GetDirectoryName(outputPath));
            var output = template.razor.ExecuteTemplate(api, null, null, null);
            File.WriteAllText(outputPath, output);
        }
        #endregion

        #region Parsing
        public void ParseRazorTemplates(string renderFilePath)
        {
            // Shortcut
            if (templates == null) return;

            // Parse all razor templates
            string templatePath, contents;
            foreach (var template in templates) {
                try {
                    templatePath = Path.Combine(renderFilePath, template.file);
                    Console.WriteLine($"     Parsing template {templatePath}...");
                    contents = File.ReadAllText(templatePath);
                    template.razor = MakeRazorTemplate(contents);
                } catch (Exception ex) {
                    Console.WriteLine($"Exception parsing {template.file}: {ex.ToString()}");
                    throw ex;
                }
            }
        }
        #endregion

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
                throw new Exception(String.Format("Error Compiling Template (Line {0} Col {1}) Err {2}", err.Line, err.Column, err.ErrorText));

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

        /// <summary>
        /// Replace a regex in a file
        /// </summary>
        /// <param name="path"></param>
        /// <param name="oldRegex"></param>
        /// <param name="newString"></param>
        /// <param name="encoding"></param>
        protected void ReplaceStringInFile(string path, string oldRegex, string newString, Encoding encoding)
        {
            // Read in the global assembly info file
            string contents = File.ReadAllText(path, System.Text.Encoding.UTF8);

            // Replace assembly version and assembly file version
            Regex r = new Regex(oldRegex);
            contents = r.Replace(contents, newString);

            // Write the file back
            File.WriteAllText(path, contents, encoding);
        }
        #endregion
    }
}
