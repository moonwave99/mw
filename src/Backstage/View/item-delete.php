<script type="text/template" id="item-delete">

<div class="modal-header">
	<a class="close" data-dismiss="modal">×</a>
	<h3>Deleting <?php echo $this -> entity ?>(s)</h3>
</div>

<div class="modal-body">
<p>Are you sure to delete item(s)?</p>
</div>

<div class="modal-footer">
	<a href="#" class="btn btn-primary" data-controller="common" data-action="deleteConfirm" data-entity="<%=entityName %>">Burn in Hell</a>
	<a href="#" class="btn" data-dismiss="modal">Cancel</a>
</div>
	
</script>