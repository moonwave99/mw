<?php requestView('App\View\base', $data) ?>

<?php startblock('container') ?>

<section id="container" class="mwAlertBox cornered">
	<header id="header">
		<h1>

<?php startblock('title') ?>
	Made with MW.
<?php endblock() ?>				

		</h1>
	</header>

	<section id="content" role="main">
<?php startblock('content') ?>

<?php endblock() ?>
	</section> <!-- /#main -->

	<footer id="site-footer">
		<strong>&copy; 2012 - <a href="http://www.diegocaponera.com/projects/mw">Made in MW</a>.</strong>
	</footer> <!-- /#footer -->

</section>

<?php endblock() ?>