---
layout: page
title: @MethodModel.Name API
date: @{ Write(DateTime.UtcNow.ToString("yyyy-MM-dd")); }
comments: true
categories: [avatax, api]
product: avatax
doctype: api-references
api-category: @MethodModel.Category
api: @MethodModel.Name
disqus: 1
---

<table class="styled-table">
    <tr>
        <th>PURPOSE</th>
        <td>@MethodModel.Summary</td>
    </tr>
    <tr>
        <th>HTTP VERB</th>
        <td><code class="highlight-rouge">@MethodModel.HttpVerb.ToUpper()</code></td>
    </tr>
    <tr>
        <th>URL (SANDBOX)</th>
@{
WriteLine("        <td>https://sandbox-rest.avatax.com" + MethodModel.URI + "</td>");
}
    </tr>
    <tr>
        <th>URL (PRODUCTION)</th>
@{
WriteLine("        <td>https://rest.avatax.com" + MethodModel.URI + "</td>");
}
    </tr>
    <tr>
        <th>CONTENT-TYPE</th>
        <td><code class="highlight-rouge">application/json</code></td>
    </tr>
    <tr>
        <th>RESPONSE BODY</th>
        <td>@FriendlyModelLink("https://developer.avalara.com/api-reference/avatax/rest/v2/models/", MethodModel.ResponseTypeName)</td>
    </tr>
</table>

## Description

@{
    var html = MarkdownToHtml(MethodModel.Description);
    if (!String.IsNullOrEmpty(html)) {
        WriteLine(html.Replace("<ul>","<ul class=\"normal\">"));
    }
}

## Relevant Blog Posts

<ul class="normal">
{% for post in site.posts %}
   {% if post.relevantapimethods contains '@MethodModel.Name' %}
       <li><a href="{{ post.url }}">{{ post.title }}</a></li>
   {% endif %}
{% endfor %}
</ul>

## Parameters

<table class="styled-table">
    <thead>
        <tr>
            <th>LOCATION</th>
            <th>PARAMETER</th>
            <th>ATTRIBUTES</th>
            <th>SUMMARY</th>
        </tr>
    </thead>
    <tbody>
@{
    foreach (var p in MethodModel.Params) {
        WriteLine("<tr>");
        WriteLine("<td>" + p.ParameterLocation + "</td>");
        WriteLine("<td><code class=\"highlight-rouge\">" + p.CleanParamName + "</code></td>");
        
        // Write parameter options
        Write("<td>");
        Write(p.TypeName);
        if (p.Required) {
            Write(", Required");
        } else {
            Write(", Optional");
        }
        if (p.MinLength != null) {
            Write(", Min Length " + p.MinLength.Value.ToString());
        }
        if (p.MaxLength != null) {
            Write(", Max Length " + p.MaxLength.Value.ToString());
        }
        WriteLine("</td>");

        // Final bits
        WriteLine("<td>" + MarkdownToHtml(p.Comment) + "</td>");
        WriteLine("</tr>");
    }
}
    </tbody>
</table>


<div>
    <div class="try-it-now-header" data-target="#try-it-now" data-toggle="collapse" OnClick="$('#try-it-now-icon').toggleClass('rotate');">
        <div class="documentation-expand-icon rotate" id="try-it-now-icon" style="display: inline-block; margin-right: 5px;">
            <svg id="Layer_1" version="1.1" viewBox="0 0 512 512" width="24px" x="0px" xml:space="preserve" y="0px" style="display: block; margin: auto;"><g transform="rotate(0 256 256)"><g><path d="M254.8,5.9c-139,0-252,113.1-252,252s113.1,252,252,252s252-113.1,252-252S393.8,5.9,254.8,5.9z M254.8,454 c-108.1,0-196-88-196-196s87.9-196,196-196s196,88,196,196S362.9,454,254.8,454z"></path><polygon points="254.8,269.4 172.5,187.1 132.9,226.7 254.8,348.6 376.8,226.7 337.2,187.1"></polygon></g></g></svg>
        </div>
        <h3 class="clickable" style="display: inline-block;">Try It Now</h3>
    </div>
    <div class="collapse" id="try-it-now">

        <div class="api-console-output">
            <h5 class="console-output-header">API Endpoint</h5>
            <div class="row" style="margin: 10px;">
                <div class="code-snippet-plaintext" style="display: inline;" id="console-method">@MethodModel.HttpVerb.ToUpper()</div>
                <div class="code-snippet-plaintext" style="display: inline;" id="console-server">https://sandbox-rest.avatax.com</div>
                <div class="code-snippet-plaintext" style="display: inline;" id="console-path">@MethodModel.URI</div>
            </div>
            <h5 class="console-output-header">
                Headers
                <i class="glyphicon glyphicon-pencil"></i>
            </h5>
            <div class="code-snippet reqScroll">
                <textarea style="height: 50px;" id="console-headers" >Authorization: (use Developer website demo credentials)
X-Avalara-Client: Avalara Developer Website; @SwaggerModel.ApiVersionThreeSegmentsOnly; AvaTax SDK; @SwaggerModel.ApiVersionThreeSegmentsOnly; developer-console</textarea>
            </div>
            <div class="row" style="margin-bottom: 8px;">
                <div class="col-md-6 console-req-container">
                    <h5 class="console-output-header">
                        Request
                        <i class="glyphicon glyphicon-pencil"></i>
                    </h5>
                    <textarea id="console-input-sample" style="display: none;">@{ if (MethodModel.BodyParam != null) { Write(GetExample(MethodModel.BodyParam.TypeName)); } }</textarea>
                    <div class="code-snippet reqScroll">
                        <textarea id="console-input">{ }</textarea>
                    </div>
                </div>
                <div class="col-md-6 console-res-container">
                     <h5 class="console-output-header">Response</h5>
                     <div class="code-snippet respScroll">
                         <pre id="console-output"> </pre>
                     </div>
                 </div>
             </div>
             <div>
                 <button class="btn btn-secondary" style="color: #000000;" type="button" onClick="$('#console-input').empty().val($('#console-input-sample').val());">Fill with Sample Data</button>
                 <button class="btn btn-secondary" style="color: #000000;" type="button" onClick="$('#console-input').empty().val('{ }');">Reset</button>
                 <button class="btn btn-primary" type="button" onClick="ApiRequest();">Submit</button>
             </div>
        </div>
    </div>
</div>

<div>
    <div class="try-it-now-header" data-target="#example-request" data-toggle="collapse" OnClick="$('#example-request-icon').toggleClass('rotate');">
        <div class="documentation-expand-icon rotate" id="example-request-icon" style="display: inline-block; margin-right: 5px;">
            <svg id="Layer_1" version="1.1" viewBox="0 0 512 512" width="24px" x="0px" xml:space="preserve" y="0px" style="display: block; margin: auto;"><g transform="rotate(0 256 256)"><g><path d="M254.8,5.9c-139,0-252,113.1-252,252s113.1,252,252,252s252-113.1,252-252S393.8,5.9,254.8,5.9z M254.8,454 c-108.1,0-196-88-196-196s87.9-196,196-196s196,88,196,196S362.9,454,254.8,454z"></path><polygon points="254.8,269.4 172.5,187.1 132.9,226.7 254.8,348.6 376.8,226.7 337.2,187.1"></polygon></g></g></svg>
        </div>
        <h3 class="clickable" style="display: inline-block;">Example Request</h3>
    </div>
    <div class="collapse" id="example-request">

    <h4>Request Path</h4>
    
@{
    WriteLine("{% highlight markdown %}");
    WriteLine(MethodModel.HttpVerb.ToUpper() + " https://sandbox-rest.avatax.com" + MethodModel.URI);
    WriteLine("{% endhighlight %}");

    if (MethodModel.BodyParam != null) {
        WriteLine("<h4>Request Body</h4>");
        WriteLine("<p>Complete documentation: " + FriendlyModelLink("https://developer.avalara.com/api-reference/avatax/rest/v2/models/", MethodModel.BodyParam.TypeName) + "</p>");
        WriteLine("{% highlight json %}");
        WriteLine(GetExample(MethodModel.BodyParam.TypeName));
        WriteLine("{% endhighlight %}");
    }
}

    </div>
</div>

<div>
    <div class="try-it-now-header" data-target="#example-response" data-toggle="collapse" OnClick="$('#example-response-icon').toggleClass('rotate');">
        <div class="documentation-expand-icon rotate" id="example-response-icon" style="display: inline-block; margin-right: 5px;">
            <svg id="Layer_1" version="1.1" viewBox="0 0 512 512" width="24px" x="0px" xml:space="preserve" y="0px" style="display: block; margin: auto;"><g transform="rotate(0 256 256)"><g><path d="M254.8,5.9c-139,0-252,113.1-252,252s113.1,252,252,252s252-113.1,252-252S393.8,5.9,254.8,5.9z M254.8,454 c-108.1,0-196-88-196-196s87.9-196,196-196s196,88,196,196S362.9,454,254.8,454z"></path><polygon points="254.8,269.4 172.5,187.1 132.9,226.7 254.8,348.6 376.8,226.7 337.2,187.1"></polygon></g></g></svg>
        </div>
        <h3 class="clickable" style="display: inline-block;">Example Response</h3>
    </div>
    <div class="collapse" id="example-response">
    <h4>Response Body</h4>
@{
WriteLine("<p>Complete documentation: " + FriendlyModelLink("https://developer.avalara.com/api-reference/avatax/rest/v2/models/", MethodModel.ResponseTypeName) + "</p>");
}

{% highlight json %}
@GetExample(MethodModel.ResponseTypeName)
{% endhighlight %}

    </div>
</div>

<div>
    <div class="try-it-now-header" data-target="#curl-example" data-toggle="collapse" OnClick="$('#curl-example-icon').toggleClass('rotate');">
        <div class="documentation-expand-icon rotate" id="curl-example-icon" style="display: inline-block; margin-right: 5px;">
            <svg id="Layer_1" version="1.1" viewBox="0 0 512 512" width="24px" x="0px" xml:space="preserve" y="0px" style="display: block; margin: auto;"><g transform="rotate(0 256 256)"><g><path d="M254.8,5.9c-139,0-252,113.1-252,252s113.1,252,252,252s252-113.1,252-252S393.8,5.9,254.8,5.9z M254.8,454 c-108.1,0-196-88-196-196s87.9-196,196-196s196,88,196,196S362.9,454,254.8,454z"></path><polygon points="254.8,269.4 172.5,187.1 132.9,226.7 254.8,348.6 376.8,226.7 337.2,187.1"></polygon></g></g></svg>
        </div>
        <h3 class="clickable" style="display: inline-block;">Example Using CURL</h3>
    </div>
    <div class="collapse" id="curl-example">

{% highlight shell %}
curl
    -X @MethodModel.HttpVerb.ToUpper()
    -H 'Accept: application/json'
    -H 'Authorization: Basic aHR0cHdhdGNoOmY='
@{
    if (MethodModel.BodyParam != null) {
        WriteLine(GetExample(MethodModel.BodyParam.TypeName));
    }
    WriteLine("    https://sandbox-rest.avatax.com" + MethodModel.URI);
}
{% endhighlight %}

    </div>
</div>
