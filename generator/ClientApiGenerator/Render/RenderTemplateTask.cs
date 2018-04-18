using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Render
{
    /// <summary>
    /// Define the types of templates recognized
    /// </summary>
    public enum TemplateType { singleFile, methods, methodCategories, models, uniqueModels, enums, listModels };

    /// <summary>
    /// An execution of a render template
    /// </summary>
    public class RenderTemplateTask
    {
        /// <summary>
        /// The file path of the template to use for this target
        /// </summary>
        public string file { get; set; }

        /// <summary>
        /// The type of template to use
        /// </summary>
        public TemplateType type { get; set; }

        /// <summary>
        /// The output path for files produced by this template
        /// </summary>
        public string output { get; set; }

        /// <summary>
        /// The razor template execution for this object
        /// </summary>
        [JsonIgnore]
        public TemplateBase razor { get; set; }
    }
}
