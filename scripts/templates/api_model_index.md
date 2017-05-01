---
layout: page
title: AvaTax API - Models 
categories: [AvaTax API Reference]
product: avaTax
doctype: restapi
nav: apis
disqus: 1
---

## Models

<table class="styled-table">
	<thead>
		<tr>
			<th>Model</th>
			<th>Summary</th>
		</tr>
	</thead>
	<tbody>
@foreach(var m in SwaggerModel.Models) {
    WriteLine("<tr>");
    WriteLine("<td><a href=\"" + m.SchemaName + "\">" + m.SchemaName + "</a></td>");
    WriteLine("<td>" + m.Comment + "</td>");
    WriteLine("</tr>");
}
    </tbody>
</table>

<br/>
