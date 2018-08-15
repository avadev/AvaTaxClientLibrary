import requests
from _str_version import str_type


class Mixin:
    """Mixin class contain methods attached to Client class."""
@foreach(var m in SwaggerModel.Methods){
    var paramlist = new System.Text.StringBuilder();
    var paramformat = new System.Text.StringBuilder();
    var paramcomments = new System.Collections.Generic.List<string>();
    string newuri = m.parseURI(m.URI);
    string querystringoptions = "";
    string callwithquerystring = "";
    string payload = "None";
    paramlist.Append("self, ");
    paramformat.Append("self.base_url, ");
    foreach (var p in m.Params) {
        string temp = p.CleanParamName;
        if (p.CleanParamName == "X-Avalara-Client") continue;
        if (p.ParameterLocation == ParameterLocationType.UriPath || p.ParameterLocation == ParameterLocationType.RequestBody) {
            if (p.CleanParamName == "id") temp = "id_";
            paramlist.Append(temp);
            paramlist.Append(", ");
            if (temp != "model"){
                paramformat.Append(temp);
                paramformat.Append(", ");
            }
        }
        paramcomments.Add("      :param " + temp + " [" + PythonTypeName(p.TypeName) + "] " + PhpTypeComment(SwaggerModel, p) + "\r\n");
        if (p.ParameterLocation == ParameterLocationType.QueryString) {
            querystringoptions = "include=None";
            callwithquerystring = "include";
        }

        if (p.ParameterLocation == ParameterLocationType.RequestBody) {
            payload = p.CleanParamName;
        }
    }
    if (!String.IsNullOrEmpty(querystringoptions)) {
        paramlist.Append(querystringoptions);
        paramlist.Append(", ");
    }

    if (paramlist.Length > 0) paramlist.Length -= 2;
    if (paramformat.Length > 0) paramformat.Length -= 2;
    
    if (m.Name.Contains("User") && paramformat.ToString().Contains("id_")) { 
	paramformat.Replace("id_", "holder").Replace("accountId", "id_").Replace("holder", "accountId"); 
    }

<text>
    r"""
    @PythonComment(m.Summary, 6)
    
    @PythonComment(m.Description, 6)
    
    @foreach (var pc in paramcomments) { Write(pc);}
      :return @PythonTypeName(m.ResponseTypeName)
    """
    def @{Write(SnakeCase(m.Name) + "(" + paramlist.ToString() + "):");}
        return requests.@{Write(m.HttpVerb.ToLower());}('{}@newuri'.format(@paramformat.ToString()),
@{Write("                               auth=self.auth, headers=self.client_header, ");
if(callwithquerystring.Length > 0 && payload != "None"){
Write("params=" + callwithquerystring + ", json=" + payload + ", ");
}else if(callwithquerystring.Length > 0){
Write("params=" + callwithquerystring + ", ");
} else if(payload != "None") {
Write("json=" + payload + ", ");
} else{
Write("params=None, ");  
}
WriteLine("");
Write("                               timeout=self.timeout_limit if self.timeout_limit else 10)");
}
</text>}
 