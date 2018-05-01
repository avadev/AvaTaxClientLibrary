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

                        // One file per model that is used by a CRUD method that returns a list (for Apex use)
                        case TemplateType.listModels:
                            RenderListModels(api, template);
                            break;

                        // One file per model that is used by a CRUD method to fetch a collection of data. i.e FetchResult<SubscriptionModel> ListMySubscriptions() needs be an unique model (for Apex use)
                        case TemplateType.fetchModels:
                            RenderFetchModel(api, template);
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

                // Determine what the new string is
                var newstring = QuickStringMerge(fixup.replacement, api);

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
                // ErrorCodeId is not needed in Apex, all error codes are handled as String
                if ((template.file.Contains("apex_enum_class") || template.file.Contains("apex_meta")) && enumDataType.EnumDataType == "ErrorCodeId") continue;

                var outputPath = Path.Combine(rootFolder, QuickStringMerge(template.output, enumDataType));
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
                var outputPath = Path.Combine(rootFolder, QuickStringMerge(template.output, model));
                Directory.CreateDirectory(Path.GetDirectoryName(outputPath));
                var output = template.razor.ExecuteTemplate(api, null, model, null);
                File.WriteAllText(outputPath, output);
            }
            api.Models = oldModels;
        }

        private void RenderModels(SwaggerInfo api, RenderTemplateTask template)
        {
            foreach (var model in api.Models) {
                var outputPath = Path.Combine(rootFolder, QuickStringMerge(template.output, model));
                Directory.CreateDirectory(Path.GetDirectoryName(outputPath));
                var output = template.razor.ExecuteTemplate(api, null, model, null);
                File.WriteAllText(outputPath, output);
            }
        }

        private void RenderListModels(SwaggerInfo api, RenderTemplateTask template)
        {
            foreach (var method in api.Methods)
            {
                if (method.ResponseType == "array")
                {
                    string modelName = method.parseBracket(method.ResponseTypeName).Substring(4);
                    foreach (var model in api.Models)
                    {
                        if (model.SchemaName.Contains(modelName) && !model.SchemaName.Contains("FetchResult"))
                        {
                            var outputPath = Path.Combine(rootFolder, QuickStringMerge(template.output, model));
                            Directory.CreateDirectory(Path.GetDirectoryName(outputPath));
                            var output = template.razor.ExecuteTemplate(api, null, model, null);
                            File.WriteAllText(outputPath, output);
                            break;
                        }
                    }
                }
            }
        }

        private void RenderFetchModel(SwaggerInfo api, RenderTemplateTask template)
        {
            var oldModels = api.Models;
            api.Models = (from m in api.Models where m.SchemaName.StartsWith("FetchResult") select m).ToList();
            foreach (var model in api.Models) {
                var outputPath = Path.Combine(rootFolder, QuickStringMerge(template.output, model));
                Directory.CreateDirectory(Path.GetDirectoryName(outputPath));
                var output = template.razor.ExecuteTemplate(api, null, model, null);
                File.WriteAllText(outputPath, output);
            }
        }

        private void RenderMethods(SwaggerInfo api, RenderTemplateTask template)
        {
            foreach (var method in api.Methods) {
                var outputPath = Path.Combine(rootFolder, QuickStringMerge(template.output, method));
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
                var outputPath = Path.Combine(rootFolder, QuickStringMerge(template.output, c));
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
                templatePath = Path.Combine(renderFilePath, template.file);
                Console.WriteLine($"     Parsing template {templatePath}...");
                contents = File.ReadAllText(templatePath);
                template.razor = MakeRazorTemplate(contents);
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

                // Print out debug information
                var msg = $"Error Compiling Template (Line {err.Line} Col {err.Column}) Err {err.ErrorText}";
                Console.WriteLine(msg);
                Console.WriteLine("=======================================");
                var codelines = code.Split('\n');
                for (int i = Math.Max(0, err.Line - 5); i < Math.Min(codelines.Length, err.Line + 5); i++) {
                    Console.WriteLine(codelines[i]);
                }
                throw new Exception(msg);

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

        #region String merge function
        private string QuickStringMerge(string template, object mergeSource)
        {
            Regex r = new Regex("{.+?}");
            var matches = r.Matches(template);
            foreach (Match m in matches) {

                // Split into function and field
                string field = m.Value.Substring(1, m.Value.Length - 2);
                string func = null;
                int p = field.IndexOf('.');
                if (p >= 0) {
                    func = field.Substring(p + 1);
                    field = field.Substring(0, p);
                }

                // If we're merging with a plain string, just use that
                string mergeString;
                if (mergeSource is string) {
                    mergeString = mergeSource as string;

                // Find this value in the merge data
                } else {
                    PropertyInfo pi = mergeSource.GetType().GetProperty(field);
                    if (pi == null) {
                        throw new Exception($"Field '{field}' not found when merging filenames.");
                    }
                    object mergeValue = pi.GetValue(mergeSource);
                    mergeString = mergeValue == null ? "" : mergeValue.ToString();
                }

                // Apply function, if any
                switch (func) {
                    case "trim": mergeString = mergeString.Trim(); break;
                    case "lower": mergeString = mergeString.ToLower(); break;
                }

                // Merge this value into the template
                template = template.Replace(m.Value, mergeString);
            }

            // Here's the merged template
            return template;
        }
        #endregion
    }
}