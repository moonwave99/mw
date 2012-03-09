<script type="text/template" id="item-new">

<div class="modal-header">
	<a class="close" data-dismiss="modal">×</a>
	<h3>Add New <?php echo ucwords($this -> entity) ?></h3>
</div>

<?php $this -> newForm -> render() ?>

</script>