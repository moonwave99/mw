<?php $this -> requestView('Backstage\View\layout'); ?>

<?php startblock('pageTitle') ?><?php echo $this -> pageTitle ?><?php endblock() ?>

<?php startblock('title') ?><?php echo $this -> title ?><?php endblock() ?>

<?php startblock('css') ?>
	<?php superBlock() ?>
	<!-- require page specific CSS files here -->

<?php endblock() ?>

<?php startblock('content') ?>

	<p>Here you can manage your <strong><?php echo ucwords($this -> entity)?></strong> list.</p>
	<hr/>
	<table id="dataTable" class="table table-striped table-bordered" data-entity="<?php echo strtolower($this -> entity) ?>" data-startup="true" data-controller="table" data-action="fetch" data-source="<?php $this -> path_to(sprintf('backstage/%s/list', strtolower($this -> entity))) ?>">
	    <thead>
	        <tr>
				<th data-field="id"></th>
				
			<?php foreach($this -> fields as $field): ?>
				<?php if($field -> target !== 'table' && $field -> target !== 'both') continue;?>
				<th data-field="<?php echo $field -> name ?>"><?php echo $field -> label ?></th>

			<?php endforeach; ?>

				<th style="width:1%">Actions</th>			
	        </tr>
	    </thead>
	    <tbody>
	    </tbody>
	</table>
	<hr/>	
	<div id="actions-common">
		<a class="btn btn-inverse" href="#" data-controller="common" data-entity="<?php echo strtolower($this -> entity)?>" data-action="new"><i class="icon-white icon-plus"></i> Add New <?php echo ucwords($this -> entity)?></a>
		<a class="btn btn-inverse" href="#" data-controller="common" data-entity="<?php echo strtolower($this -> entity)?>" data-action="deleteCecked" data-content="You should select an item at least." title="Hey you!"><i class="icon-white icon-trash"></i> Delete Selected</a>
	</div>	
<?php endblock() ?>

<?php startblock('templates') ?>
	<?php superBlock() ?>

	<?php $this -> requestView('Backstage\View\Templates\item-table-row') ?>	
	<?php $this -> requestView('Backstage\View\Templates\item-new') ?>	
	<?php $this -> requestView('Backstage\View\Templates\item-edit') ?>	
	<?php $this -> requestView('Backstage\View\Templates\item-delete') ?>
	<?php $this -> requestView('Backstage\View\Templates\item-gallery') ?>	

<?php endblock() ?>