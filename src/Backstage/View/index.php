<?php $this -> requestView('Backstage\View\layout') ?>

<?php startblock('pageTitle') ?>
	<?php echo $this -> pageTitle ?>
<?php endblock() ?>

<?php startblock('title') ?>
	<?php echo $this -> title ?>
<?php endblock() ?>

<?php startblock('content') ?>

	<p>This is the backstage of your application.</p>

<?php endblock() ?>