<script type="text/template" id="item-gallery">
<div class="modal-header">
	<a class="close" data-dismiss="modal">×</a>
	<h3>Edit Gallery</h3>
</div>
<div class="modal-body">

	<div class="clearfix item-gallery">

<% if(pics.length == 0){%><span class="noPics">No pics yet.</span><%}%>		
	
<% _.each(pics, function(pic){
	
	print(singlePic({
		pic : pic,
		basePath : basePath
	}));

})%>
	</div>
</div>
<div class="modal-footer" data-id="<%=id%>">
	<a class="btn btn-primary" data-controller="table" data-action="saveGallery">Save</a>	
	<label for="addPicInput" class="btn" data-controller="table" data-action="addPicture">Add New</label>
	<input type="file" id="addPicInput" style="opacity:0"/>
	<a class="btn" data-controller="table" data-action="removePictures">Remove Selected</a>	
</div>
</script>