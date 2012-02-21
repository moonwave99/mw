<?php requestView('App\View\base', $data) ?>

<?php startblock('css') ?>
	<?php superBlock() ?>

	<!-- require page specific CSS files here -->

<?php endblock() ?>

<?php startblock('container') ?>

<section id="container" class="mwAlertBox cornered">
	<header id="header">
		<h1><?php startblock('title') ?>Made with MW.<?php endblock() ?></h1>
	</header> <!-- /#main -->

	<section id="content" role="main">
<?php startblock('content') ?>

<?php endblock() ?>
	</section> <!-- /#main -->

	<footer id="footer">
		<strong>&copy; 2012 - <a href="http://www.diegocaponera.com/projects/mw">Made in MW</a>.</strong>
	</footer> <!-- /#footer -->

</section>

<?php endblock() ?>

<?php startblock('js') ?>
	<?php superBlock() ?>

	<!-- require page specific JS files here -->

<?php endblock() ?>