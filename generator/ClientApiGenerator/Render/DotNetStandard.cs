using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using ClientApiGenerator.Models;
using System.IO;
using System.Text.RegularExpressions;

namespace ClientApiGenerator.Render
{
    public class DotNetStandard : BaseRenderTarget
    {
        public override void Render(SwaggerInfo model, string rootPath)
        {
            // Set up the razor scripts
            var apiTask = this.MakeRazorTemplate(Resource1.csharp_api_class);
            var modelTask = this.MakeRazorTemplate(Resource1.csharp_model_class);
            var enumTask = this.MakeRazorTemplate(Resource1.csharp_enum_class);

            // Now spit out a coherent API structure
            File.WriteAllText(Path.Combine(rootPath, "AvaTax-REST-V2-DotNet-SDK\\src\\AvaTaxApi.cs"),
                apiTask.ExecuteTemplate(model, null, null));

            // Next let's assemble the model files
            var modelDir = CleanFolder(rootPath, "AvaTax-REST-V2-DotNet-SDK\\src\\models");
            foreach (var m in model.Models) {
                if (!m.SchemaName.StartsWith("FetchResult")) {
                    File.WriteAllText(Path.Combine(modelDir, m.SchemaName + ".cs"),
                        modelTask.ExecuteTemplate(model, m, null));
                }
            }

            // Finally assemble the enums
            var enumDir = CleanFolder(rootPath, "AvaTax-REST-V2-DotNet-SDK\\src\\enums");
            foreach (var e in model.Enums) {
                File.WriteAllText(Path.Combine(enumDir, e.EnumDataType + ".cs"),
                    enumTask.ExecuteTemplate(model, null, e));
            }

            // Update the version number in the assembly info file
            Fixup(rootPath, model.ApiVersion);
        }

        private void Fixup(string rootPath, string version)
        {
            // Fixup the global assembly
            string path = Path.Combine(rootPath, "AvaTax-REST-V2-DotNet-SDK\\GlobalAssemblyInfo.cs");
            ReplaceStringInFile(path, "\\[assembly: AssemblyVersion\\(\".*\"\\)\\]", "[assembly: AssemblyVersion(\"" + version.Replace("-", ".") + "\")]", System.Text.Encoding.UTF8);
            ReplaceStringInFile(path, "\\[assembly: AssemblyFileVersion\\(\".*\"\\)\\]", "[assembly: AssemblyFileVersion(\"" + version.Replace("-", ".") + "\")]", System.Text.Encoding.UTF8);

            // Fixup the .nuspec file
            path = Path.Combine(rootPath, "AvaTax-REST-V2-DotNet-SDK\\src\\Avalara.AvaTax.RestClient.nuspec");
            ReplaceStringInFile(path, "<version>(.*)</version>", "<version>" + version.Replace("-", ".") + "</version>", System.Text.Encoding.UTF8);
        }

        private string CleanFolder(string rootPath, string relativePath)
        {
            var dir = Path.Combine(rootPath, relativePath);
            if (!Directory.Exists(dir)) {
                Directory.CreateDirectory(dir);
            } else {
                foreach (var f in Directory.GetFiles(dir)) {
                    File.Delete(f);
                }
            }
            return dir;
        }
    }
}
