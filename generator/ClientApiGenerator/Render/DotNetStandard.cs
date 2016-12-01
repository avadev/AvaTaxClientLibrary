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
            StringBuilder sb = new StringBuilder();
            string currentRegion = null;
            foreach (var api in (from a in model.Methods orderby a.Category, a.OperationId select a)) {
                if (currentRegion != api.Category) {
                    if (currentRegion != null) {
                        sb.AppendLine("        #endregion\r\n");
                    }
                    sb.AppendLine("        #region " + api.Category);
                    currentRegion = api.Category;
                }
                sb.AppendLine(api.ToString());
            }
            if (currentRegion != null) {
                sb.AppendLine("        #endregion");
            }

            // Next let's assemble the api file
            string filetext = Resource1.csharp_api_class
                .Replace("@@APILIST@@", sb.ToString());
            File.WriteAllText(Path.Combine(rootPath, "dotnet\\AvaTaxApi.cs"), filetext);

            // Next let's assemble the model files
            foreach (var m in model.Models) {
                if (!m.SchemaName.StartsWith("FetchResult")) {
                    File.WriteAllText(Path.Combine(rootPath, "dotnet\\models\\" + m.SchemaName + ".cs"), m.ToString());
                }
            }

            // Finally assemble the enums
            foreach (var e in model.Enums) {
                File.WriteAllText(Path.Combine(rootPath, "dotnet\\enums\\" + e.EnumDataType + ".cs"), e.ToString());
            }
        }
    }
}
