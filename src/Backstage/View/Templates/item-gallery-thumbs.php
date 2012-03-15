<script type="text/template" id="item-gallery-thumbs">
<% _.each(pics, function(pic){%>
<div class="singlePic span2">
	<a href="#" data-controller="common" data-action="edit" data-id="<%=pic.id%>">
		<img class="thumbnail" src="<%=basePath + pic.src %>" alt=""/>
	</a>
	<p>
		<input type="checkbox" name="list-single[]" data-id="<%=pic.id%>"/>
		<%=pic.label%>
	</p>
</div>
<%})%>
</script>