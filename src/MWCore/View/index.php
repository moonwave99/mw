<?php $this -> requestView('App\View\layout') ?>

<?php startblock('pageTitle') ?>
	<?php echo $this -> pageTitle ?>
<?php endblock() ?>

<?php startblock('title') ?>
	<?php echo $this -> title ?>
<?php endblock() ?>

<?php startblock('content') ?>

	<p>Proudly made in MW.</p>

<?php endblock() ?>

<?php startblock('js') ?>
	<?php superBlock() ?>

	<!-- require page specific JS files here -->

<?php endblock() ?>