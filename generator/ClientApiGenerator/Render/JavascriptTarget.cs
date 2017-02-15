using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using ClientApiGenerator.Models;
using System.IO;

namespace ClientApiGenerator.Render
{
    public class JavascriptTarget : BaseRenderTarget
    {
        public override void Render(SwaggerInfo model, string rootPath)
        {
            string php = Path.Combine(rootPath, "javascript");
            Directory.CreateDirectory(php);

            // Set up the razor scripts
            var apiTask = this.MakeRazorTemplate(Resource1.javascript_api_class);

            // Now spit out a coherent single file for the PHP API
            File.WriteAllText(Path.Combine(rootPath, "AvaTax-REST-V2-JS-SDK\\lib\\AvaTaxClient.js"),
                apiTask.ExecuteTemplate(model, null, null));
        }
    }
}
