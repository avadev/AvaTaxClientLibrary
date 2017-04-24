module AvaTax
  class Client
    module @Category 

@foreach(var m in SwaggerModel.Methods) {
    var paramlist = new System.Text.StringBuilder();
    var guzzleparamlist = new System.Text.StringBuilder();
    var paramcomments = new System.Collections.Generic.List<string>();
    string payload = "null";
    foreach (var p in m.Params) {
        paramlist.Append("$");
        paramlist.Append(p.CleanParamName);
        paramlist.Append(", ");
    }
    foreach (var p in m.QueryParams) {
        paramlist.Append("$");
        paramlist.Append(p.CleanParamName);
        paramlist.Append(", ");
        guzzleparamlist.Append("'" + p.ParamName + "' => $" + p.CleanParamName + ", ");
        paramcomments.Add("\r\n      # @param " + PhpTypeName(p.TypeName) + " $" + p.CleanParamName + " " + PhpTypeComment(SwaggerModel, p));
    }
    if (m.BodyParam != null) {
        paramlist.Append("$");
        paramlist.Append(m.BodyParam.CleanParamName);
        paramlist.Append(", ");
        payload = "json_encode($" + m.BodyParam.CleanParamName + ")";
        paramcomments.Add("\r\n      # @param " + PhpTypeName(m.BodyParam.TypeName) + " $" + m.BodyParam.CleanParamName + " " + PhpTypeComment(SwaggerModel, m.BodyParam));
    }
    if (paramlist.Length > 0) paramlist.Length -= 2;
    if (guzzleparamlist.Length > 0) guzzleparamlist.Length -= 2;

<text>
      # @m.Summary
      # 
      # @RubyComment(m.Description, 6)
      # </text>@foreach (var pc in paramcomments) { Write(pc);}<text>
      # @@return @PhpTypeName(m.ResponseTypeName)
      def @{Write(FirstCharLower(m.Name) + "(" + paramlist.ToString() + ")");}
        path = '@m.URI.Replace("{", "#{")';
        @m.HttpVerb (path)
      end
</text>
}
    end
  end
end