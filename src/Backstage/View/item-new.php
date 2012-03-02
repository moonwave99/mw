<script type="text/template" id="item-new">

<div class="modal-header">
	<a class="close" data-dismiss="modal">×</a>
	<h3>Add New <?php echo $this -> entity ?></h3>
</div>

<div class="modal-body">
<?php pre($this -> fields);?>
</div>

<div class="modal-footer">
	<a href="#" class="btn btn-primary">Save changes</a>
	<a href="#" class="btn" data-dismiss="modal">Cancel</a>
</div>
	
</script>