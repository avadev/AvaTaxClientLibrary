using ClientApiGenerator.Render;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator
{
    public class SwaggerRenderTask
    {
        /// <summary>
        /// The URL of the overall swagger file we are going to download
        /// </summary>
        public string swaggerUri { get; set; }

        /// <summary>
        /// The list of rendering targets we will use
        /// </summary>
        public List<RenderTarget> targets { get; set; }
    }
}
