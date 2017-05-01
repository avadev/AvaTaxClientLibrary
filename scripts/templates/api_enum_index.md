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
@foreach(var e in SwaggerModel.Enums) {
    WriteLine("<tr>");
    WriteLine("<td><a href=\"" + e.EnumDataType + "\">" + e.EnumDataType + "</a></td>");
    WriteLine("<td>" + e.Comment + "</td>");
    WriteLine("</tr>");
}
    </tbody>
</table>

<br/>
