<script type="text/template" id="item-gallery-single">
<div class="singlePic span2">
	<input type="checkbox" name="pictureList[]" data-id="<%=pic.id%>"/>
	<%=pic.label%>
	<a href="#" data-id="<%=pic.id%>" data-controller="common" data-action="edit">
		<img class="thumbnail" src="<%=basePath + pic.src %>" alt=""/>
	</a>
	<p>
		<p>tags: <% if(typeof pic.tagList == 'object'){
			_.each(pic.tagList,function(tag, i){ print(tag.label + (i != pic.tagList.length - 1 ?", ":"")) })}
			else print(pic.tagList)%>
		</p>		
		<p><%=pic.width%> x <%=pic.height%> <%=pic.type%></p>		
	</p>
</div>
</script>