<?php requestView('Backstage\View\layout', $data) ?>

<?php startblock('pageTitle') ?>
	<?php echo $data['pageTitle'] ?>
<?php endblock() ?>

<?php startblock('title') ?>
	<?php echo $data['title'] ?>
<?php endblock() ?>

<?php startblock('content') ?>

	<p>This is the backstage of your application.</p>

<?php endblock() ?>