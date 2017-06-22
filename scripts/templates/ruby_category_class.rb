module AvaTax
  class Client
    module @Category 

@foreach(var m in SwaggerModel.Methods) {
    var paramlist = new System.Text.StringBuilder();
    var paramcomments = new System.Collections.Generic.List<string>();
    string querystringoptions = "";
    string callwithquerystring = "";
    foreach (var p in m.Params) {
        if (p.CleanParamName == "X-Avalara-Client") continue;
        if (p.ParameterLocation == ParameterLocationType.UriPath || p.ParameterLocation == ParameterLocationType.RequestBody) {
            paramlist.Append(p.CleanParamName);
            paramlist.Append(", ");
        }
        paramcomments.Add("      # @param " + p.CleanParamName + " [" + RubyTypeName(p.TypeName) + "] " + PhpTypeComment(SwaggerModel, p) + "\r\n");
        if (p.ParameterLocation == ParameterLocationType.QueryString) {
            querystringoptions = "options={}";
            callwithquerystring = "options";
        }
    }
    if (!String.IsNullOrEmpty(querystringoptions)) {
        paramlist.Append(querystringoptions);
        paramlist.Append(", ");
    }
    if (paramlist.Length > 0) paramlist.Length -= 2;

<text>
      # @RubyComment(m.Summary, 6)
      #
      # @RubyComment(m.Description, 6)
@if (paramcomments.Count > 0) {
    foreach (var pc in paramcomments) { 
        Write(pc);
    }
}
      # @@return [@RubyTypeName(m.ResponseTypeName)]
      def @{Write(SnakeCase(m.Name) + "(" + paramlist.ToString() + ")");}
        path = "@m.URI.Replace("{", "#{")"
@{
Write("        " + m.HttpVerb + "(path");

if (m.BodyParam != null) {
    Write(", model");
}
if (!String.IsNullOrEmpty(callwithquerystring)) {
    Write(", " + callwithquerystring);
}
Write(")");
}
      end
</text>
}
    end
  end
end