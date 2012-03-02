<?php $this -> requestView('App\View\base') ?>

<?php startblock('css') ?>
	<?php superBlock() ?>
	<link rel="stylesheet" href="<?php $this -> asset('css/mw.css') ?>"/>
	<!-- require page specific CSS files here -->

<?php endblock() ?>

<?php startblock('container') ?>

<section id="container" class="mwAlertBox cornered">
	<header id="header">
		<h1><?php startblock('title') ?>Made with MW.<?php endblock() ?></h1>
	</header> <!-- /#header -->

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