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

	<div class="thumbnails" id="item-gallery" data-entity="<?php echo strtolower($this -> entity) ?>" data-controller="gallery" data-action="fetch" data-startup="true" data-source="<?php $this -> path_to(sprintf('backstage/%s/list', strtolower($this -> entity))) ?>">
	</div>
	<hr/>
	<div id="actions-common">
		<a class="btn btn-inverse" href="#" data-controller="common" data-entity="<?php echo strtolower($this -> entity)?>" data-action="new"><i class="icon-white icon-plus"></i> Add New <?php echo ucwords($this -> entity)?></a>
		<a class="btn btn-inverse" href="#" data-controller="common" data-entity="<?php echo strtolower($this -> entity)?>" data-action="deleteCecked" data-content="You should select an item at least." title="Hey you!"><i class="icon-white icon-trash"></i> Delete Selected</a>
	</div>	
<?php endblock() ?>

<?php startblock('templates') ?>
	<?php superBlock() ?>

	<?php $this -> requestView('Backstage\View\Templates\item-gallery-thumbs') ?>	
	<?php $this -> requestView('Backstage\View\Templates\item-new') ?>	
	<?php $this -> requestView('Backstage\View\Templates\item-edit') ?>	
	<?php $this -> requestView('Backstage\View\Templates\item-delete') ?>

<?php endblock() ?>