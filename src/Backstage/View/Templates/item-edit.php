<script type="text/template" id="item-edit">

<div class="modal-header">
	<a class="close" data-dismiss="modal">×</a>
	<h3>Edit <?php echo ucwords($this -> entity) ?></h3>
</div>

<?php $this -> editForm -> render() ?>

</script>