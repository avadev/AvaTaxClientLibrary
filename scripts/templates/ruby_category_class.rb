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
        paramcomments.Add("\r\n      # @param " + PhpTypeName(p.TypeName) + " $" + p.CleanParamName + " " + PhpTypeComment(SwaggerModel, p));
        if (p.ParameterLocation == ParameterLocationType.QueryString) {
            guzzleparamlist.Append("'" + p.ParamName + "' => $" + p.CleanParamName + ", ");
        }
        if (p.ParameterLocation == ParameterLocationType.RequestBody) {
            payload = "json_encode($" + p.CleanParamName + ")";
        }
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