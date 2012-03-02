<?php $this -> requestView('Backstage\View\layout'); ?>

<?php startblock('pageTitle') ?><?php echo $this -> pageTitle ?><?php endblock() ?>

<?php startblock('title') ?><?php echo $this -> title ?><?php endblock() ?>

<?php startblock('css') ?>
	<?php superBlock() ?>
	<!-- require page specific CSS files here -->

<?php endblock() ?>

<?php startblock('crumbs') ?>

	<?php superBlock() ?>
	<li class="cornered-little"><a href="<?php $this -> path_to('backstage/'.strtolower($this -> entity)) ?>"><?php echo ucwords($this -> entity)?></a></li>

<?php endblock() ?>

<?php startblock('content') ?>

	<p>Here you can manage your <strong><?php echo $this -> entity?></strong> list.</p>
	
	<a class="btn btn-inverse" href="#" data-controller="common" data-entity="<?php echo strtolower($this -> entity)?>" data-action="new">Add New <?php echo ucwords($this -> entity)?></a>
	  <a class="btn btn-inverse" href="#" data-controller="common" data-entity="<?php echo strtolower($this -> entity)?>" data-action="deleteCecked" data-content="You should select an item at least." title="Hey you!">Delete Selected</a>	
	
	<table id="dataTable" class="table table-striped table-bordered" data-entity="<?php echo strtolower($this -> entity) ?>" data-startup="true" data-controller="common" data-action="fetch" data-source="<?php $this -> path_to(sprintf('backstage/%s/list', strtolower($this -> entity))) ?>">
	    <thead>
	        <tr>
				<th style="width:1%;" data-field="id"></th>
				
			<?php foreach($this -> fields as $field): ?>

				<th style="width:<?php echo $field['size'] ?>%" data-field="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></th>

			<?php endforeach; ?>

				<th style="width:1%">Actions</th>			
	        </tr>
	    </thead>
	    <tbody>
	    </tbody>
	</table>

	<div class="modal fade" id="box" style="display:none"></div>	

	<?php $this -> requestView('Backstage\View\item-new') ?>	
	<?php $this -> requestView('Backstage\View\item-edit') ?>	
	<?php $this -> requestView('Backstage\View\item-delete') ?>

<?php endblock() ?>