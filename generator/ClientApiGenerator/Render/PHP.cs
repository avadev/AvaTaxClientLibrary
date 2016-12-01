using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using ClientApiGenerator.Models;
using System.IO;

namespace ClientApiGenerator.Render
{
    public class DotNetStandard : BaseRenderTarget
    {
        public override void Render(ApiModel model, string rootPath)
        {
            // Now spit out a coherent API structure
            File.WriteAllText(Path.Combine(rootPath, "php\\AvaTaxApi.php"), model.FormatTemplate(Resource1.php_api_class, Resource1.php_api_method));

            // Next let's assemble the model files
            foreach (var m in model.Models) {
                if (!m.SchemaName.StartsWith("FetchResult")) {
                    File.WriteAllText(Path.Combine(rootPath, "php\\models\\" + m.SchemaName + ".php"), m.FormatTemplate(Resource1.php_model_class, Resource1.php_model_property));
                }
            }

            // Finally assemble the enums
            foreach (var e in model.Enums) {
                File.WriteAllText(Path.Combine(rootPath, "php\\enums\\" + e.EnumDataType + ".php"), e.FormatTemplate(Resource1.php_enum_class, Resource1.php_enum_value));
            }
            
        }
    }
}
