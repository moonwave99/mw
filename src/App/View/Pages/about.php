<?php requestView('App\View\layout', $data) ?>

<?php startblock('pageTitle') ?>
	<?php echo $data['pageTitle'] ?>
<?php endblock() ?>

<?php startblock('title') ?>
	<?php echo $data['title'] ?>
<?php endblock() ?>

<?php startblock('content') ?>

	<p>This is the about page.</p>

<?php endblock() ?>

<?php startblock('js') ?>
	<?php superBlock() ?>

	<!-- require page specific JS files here -->

<?php endblock() ?>