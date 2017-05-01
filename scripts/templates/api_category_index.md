---
layout: page
title: AvaTax API - Method Categories 
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
			<th>Category</th>
		</tr>
	</thead>
	<tbody>
@foreach(var c in SwaggerModel.Categories) {
    WriteLine("<tr>");
    WriteLine("<td><a href=\"" + c + "\">" + c + "</a></td>");
    WriteLine("</tr>");
}
    </tbody>
</table>

<br/>
