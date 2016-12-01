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
        public override void Render(ApiModel model, string rootPath)
        {
            // Set up the razor scripts
            var apiTask = this.MakeRazorTemplate(Resource1.php_api_class);
            var modelTask = this.MakeRazorTemplate(Resource1.php_model_class);
            var enumTask = this.MakeRazorTemplate(Resource1.php_enum_class);

            // Now spit out a coherent API structure
            File.WriteAllText(Path.Combine(rootPath, "php\\AvaTaxApi.php"),
                apiTask.ExecuteTemplate(model));

            // Next let's assemble the model files
            foreach (var m in model.Models) {
                if (!m.SchemaName.StartsWith("FetchResult")) {
                    File.WriteAllText(Path.Combine(rootPath, "php\\models\\" + m.SchemaName + ".php"),
                        modelTask.ExecuteTemplate(m));
                }
            }

            // Finally assemble the enums
            foreach (var e in model.Enums) {
                File.WriteAllText(Path.Combine(rootPath, "php\\enums\\" + e.EnumDataType + ".php"),
                    enumTask.ExecuteTemplate(e));
            }

        }
    }
}
