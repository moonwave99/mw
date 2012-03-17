<script type="text/template" id="item-gallery">
<div class="modal-header">
	<a class="close" data-dismiss="modal">×</a>
	<h3>Edit Gallery</h3>
</div>
<div class="modal-body">
<% if(pics.length == 0){%>
<p>No pics yet.</p>
<%}else{%>
<div class="clearfix item-gallery">
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
</div>
<%}%>
</div>
<div class="modal-footer" data-id="<%=id%>">
	<label class="btn btn-primary clickable" data-controller="table" data-action="addPicture">Add New Picture</label>
	<input type="file" />
	<a class="btn btn-primary" data-controller="table" data-action="removePictures">Delete Selected</a>	
</div>
</script>