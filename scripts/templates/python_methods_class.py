import requests
from _str_version import str_type


class Mixin:
    """Mixin class contain methods attached to Client class."""
@foreach(var m in SwaggerModel.Methods){
    var paramlist = new System.Text.StringBuilder();
    var paramcomments = new System.Collections.Generic.List<string>();
    string querystringoptions = "";
    string callwithquerystring = "";
	string payload = "None";
	paramlist.Append("self, ");
    foreach (var p in m.Params) {
        if (p.CleanParamName == "X-Avalara-Client") continue;
        if (p.ParameterLocation == ParameterLocationType.UriPath || p.ParameterLocation == ParameterLocationType.RequestBody) {
            paramlist.Append(p.CleanParamName);
            paramlist.Append(", ");
        }
        paramcomments.Add("      :param " + p.CleanParamName + " [" + PythonTypeName(p.TypeName) + "] " + PhpTypeComment(SwaggerModel, p) + "\r\n");
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

<text>
    r"""
    @PythonComment(m.Summary, 6)
	
    @PythonComment(m.Description, 6)
	
    @foreach (var pc in paramcomments) { Write(pc);}
      :return @PythonTypeName(m.ResponseTypeName)
    """
    def @{Write(SnakeCase(m.Name) + "(" + paramlist.ToString() + "):");}
        return requests.@{Write(m.HttpVerb.ToLower());}(f"{self.base_url}@m.URI", 
@{Write("								auth=self.auth, headers=self.client_header,");}
@if(callwithquerystring.Length > 0 && payload != "None"){
Write("								params=" + callwithquerystring + ", json=" + payload);
}else if(callwithquerystring.Length > 0){
Write("								params=" + callwithquerystring);
} else if(payload != "None") {
Write("								json=" + payload);
} else{
Write("								params=None");	
})
</text>}
 