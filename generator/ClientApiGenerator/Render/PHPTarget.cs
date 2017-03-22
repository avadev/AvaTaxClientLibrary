using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using ClientApiGenerator.Models;
using System.IO;

namespace ClientApiGenerator.Render
{
    public class PHPTarget : BaseRenderTarget
    {
        public override void Render(SwaggerInfo model, string rootPath)
        {
            string php = Path.Combine(rootPath, "php");
            Directory.CreateDirectory(php);

            // Set up the razor scripts
            var apiTask = this.MakeRazorTemplate(Resource1.php_api_class);

            // Now spit out a coherent single file for the PHP API
            File.WriteAllText(Path.Combine(rootPath, "AvaTax-REST-V2-PHP-SDK\\src\\AvaTaxClient.php"),
                apiTask.ExecuteTemplate(model, null, null));

            // Fixup the composer.json file
            var fn = Path.Combine(rootPath, "AvaTax-REST-V2-PHP-SDK\\composer.json");
            ReplaceStringInFile(fn, "\"version\": \".*\",", "\"version\": \"" + model.ApiVersion.Replace("-", ".") + "\",");
        }
    }
}
