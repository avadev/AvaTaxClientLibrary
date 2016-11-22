using ClientApiGenerator.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator.Render
{
    public class BaseRenderTarget
    {
        /// <summary>
        /// Render this particular type of client library
        /// </summary>
        /// <param name="model"></param>
        /// <param name="rootPath"></param>
        public virtual void Render(ApiModel model, string rootPath)
        {
        }
    }
}
