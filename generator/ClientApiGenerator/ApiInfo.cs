using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator
{
    public class ApiInfo
    {
        public string URI { get; set; }
        public string Comment { get; set; }
        public string Category { get; set; }
        public string TypeName { get; set; }
        public string HttpVerb { get; set; }
        public string OperationId { get; set; }
        public ParameterInfo BodyParam { get; set; }
        public List<ParameterInfo> Params { get; set; }
        public List<ParameterInfo> QueryParams { get; set; }

        public override string ToString()
        {
            // Assemble parameter comments and list
            StringBuilder paramcomments = new StringBuilder();
            StringBuilder paramlist = new StringBuilder();
            StringBuilder parambuilder = new StringBuilder();
            foreach (var p in Params) {
                paramcomments.AppendFormat("        /// <param name=\"{0}\">{1}</param>\r\n", p.CSharpParamName, p.Comment);
                paramlist.AppendFormat("{0} {1}, ", p.TypeName, p.ParamName.Replace("$", ""));
                parambuilder.AppendFormat("\r\n            path.ApplyField(\"{0}\", {1});", p.ParamName, p.CSharpParamName);
            }
            foreach (var p in QueryParams) {
                paramcomments.AppendFormat("        /// <param name=\"{0}\">{1}</param>\r\n", p.CSharpParamName, p.Comment);
                paramlist.AppendFormat("{0} {1}, ", p.TypeName, p.ParamName.Replace("$", ""));
                parambuilder.AppendFormat("\r\n            path.AddQuery(\"{0}\", {1});", p.ParamName, p.CSharpParamName);
            }
            if (BodyParam != null) {
                paramcomments.AppendFormat("        /// <param name=\"{0}\">{1}</param>\r\n", BodyParam.CSharpParamName, BodyParam.Comment);
                paramlist.AppendFormat("{0} {1}, ", BodyParam.TypeName, BodyParam.ParamName.Replace("$", ""));
                parambuilder.AppendFormat("\r\n            path.AddQuery(\"{0}\", {1});", BodyParam.ParamName, BodyParam.CSharpParamName);
            }
            paramcomments.Append("        /// <returns></returns>");
            if (paramlist.Length > 0) paramlist.Length -= 2;

            // Here's your template
            return Resource1.api_template_csharp
                .Replace("@@CATEGORY@@", Category)
                .Replace("@@COMMENT@@", Fixups.Comment(Comment))
                .Replace("@@TYPENAME@@", TypeName)
                .Replace("@@APINAME@@", OperationId)
                .Replace("@@HTTPVERB@@", HttpVerb)
                .Replace("@@PARAMCOMMENTS@@", paramcomments.ToString())
                .Replace("@@PARAMBUILDER@@", parambuilder.ToString())
                .Replace("@@PARAMS@@", paramlist.ToString())
                .Replace("@@URI@@", URI)
                .Replace("@@PAYLOAD@@", BodyParam == null ? "null" : "model");
        }
    }
}
