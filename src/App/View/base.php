<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
		<title>
			<?php startblock('pageTitle') ?>
				Made with MW.
			<?php endblock() ?>
		</title>	
		<meta name="description" content="## PUT YOUR CONTENT HERE ##"/>
		<meta name="author" content="## PUT YOUR AUTHOR INFO HERE ##"/>	
		<meta name="viewport" content="width=device-width,initial-scale=1"/>	
		
		<link rel="author" href="humans.txt" />
		<link href="<?php asset('favicon.ico') ?>" rel="icon" type="image/x-icon" />
		<link href="<?php asset('favicon.ico') ?>" rel="shortcut icon" type="image/x-icon" />
		
	<?php startblock('css') ?>		
		<link rel="stylesheet" href="<?php asset('css/style.css') ?>"/>		
		<?php if( DEBUG === true ):?>
		<link rel="stylesheet" href="<?php asset('css/debug.css') ?>"/>
		<?endif;?>		
	<?php endblock() ?>	
		
		<script src="<?php asset('js/libs/modernizr-2.0.6.min.js') ?>"></script>

	</head>
	<body>
		
		<section id="container">
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
				<strong>&copy; 2011 - moonwavelabs</strong>
			</footer> <!-- /#footer -->
	
		</section>			

		<?php startblock('js') ?>
			<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->	

			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
			<script>window.jQuery || document.write('<script src="<?php asset('js/libs/jquery-1.7.0.min.js') ?>"><\/script>')</script>
			
			<script defer src="<?php asset('js/plugins.js') ?>"></script>
			
			<script>
				var _gaq=[['_setAccount','<?php echo $data['settings'] -> GOOGLE_ANALYTICS ?>'],['_trackPageview']];
				(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
				g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
				s.parentNode.insertBefore(g,s)}(document,'script'));
			</script>		
			
		  <!--[if lt IE 7 ]>
		    <script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		    <script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
		  <![endif]-->			
			
		<?php endblock() ?>
		
		<?php if( DEBUG === true ):?>
			<script defer src="<?php asset('js/mylibs/debug.js') ?>"></script>
			<?php require_once(MW_VIEWS.DIRECTORY_SEPARATOR.'debug.php'); ?>
		<?endif;?>
		
	</body>
</html>