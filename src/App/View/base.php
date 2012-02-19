<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8"/>
		<meta name="author" content="<?php startblock('author') ?>Diego Caponera<?php endblock() ?>"/>	
		<meta name="description" content="<?php startblock('description') ?>Made with MW.<?php endblock() ?>"/>		
		<meta name="viewport" content="width=device-width,initial-scale=1"/>	
		<meta name="csrf" content="<?php echo $data['token'] ?>"/>
	
		<title><?php startblock('pageTitle') ?>Made with MW.<?php endblock() ?></title>		
	
		<link rel="author" href="humans.txt" />
		<link href="<?php asset('favicon.ico') ?>" rel="icon" type="image/x-icon" />
		<link href="<?php asset('favicon.ico') ?>" rel="shortcut icon" type="image/x-icon" />
		
<?php startblock('css') ?>		
		<link rel="stylesheet" href="<?php asset('css/style.css') ?>"/>	
<?php endblock() ?>	
		
		<script src="<?php asset('js/libs/modernizr-2.0.6.min.js') ?>"></script>
		<script>var mwBasepath = "<?php echo BASE_PATH ?>"</script>

	</head>
	<body class="mwlabs">
		
		<?php startblock('container') ?>
		
		<?php endblock() ?>	
		
		<?php startblock('js') ?>
			<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->	

			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
			<script>window.jQuery || document.write('<script src="<?php asset('js/libs/jquery-1.7.0.min.js') ?>"><\/script>')</script>
			
			<script src="<?php asset('js/libs/underscore-min.js') ?>"></script>
			<script src="<?php asset('js/plugins.js') ?>"></script>	
			
		  <!--[if lt IE 7 ]>
		    <script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		    <script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
		  <![endif]-->			
			
		<?php endblock() ?>
		
		<?php if( DEBUG === true ):?>
			<script defer src="<?php asset('js/mylibs/debug.js') ?>"></script>
			<?php require_once(SRC_PATH.'MWCore/View/debug.php'); ?>
		<?endif;?>		
		
	</body>
</html>