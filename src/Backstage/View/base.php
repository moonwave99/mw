<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <title><?php echo $this -> pageTitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="<?php startblock('author') ?>Diego Caponera<?php endblock() ?>"/>	
	<meta name="description" content="<?php startblock('description') ?>Made with MW.<?php endblock() ?>"/>	
	<meta name="csrf" content="<?php echo $this -> token ?>"/>

	<link rel="author" href="humans.txt" />

<?php startblock('css') ?>		

    <link href="<?php $this -> asset('css/bootstrap.css')?>" rel="stylesheet"/>
	<?php if(DEBUG === true):?>
	<link rel="stylesheet" href="<?php $this -> asset('css/debug.css') ?>"/>
	<?php endif;?>
	
<?php endblock() ?>	

	<script src="<?php $this -> asset('js/libs/modernizr-2.0.6.min.js') ?>"></script>
	<script>var mwBasepath = "<?php echo BASE_PATH ?>"</script>

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico"/>
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png"/>
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png"/>
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png"/>
  </head>

  <body>

<?php startblock('container') ?>

<?php endblock() ?>	

<?php startblock('js') ?>
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->	

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php $this -> asset('js/libs/jquery-1.7.0.min.js') ?>"><\/script>')</script>

	<script src="<?php $this -> asset('js/libs/underscore-min.js') ?>"></script>
	<script src="<?php $this -> asset('js/plugins.js') ?>"></script>
	
  <!--[if lt IE 7 ]>
    <script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->			
	
<?php endblock() ?>

<?php if( DEBUG === true ):?>
	<?php $this -> requestView('MWCore\View\debug'); ?>
<?endif;?>

  </body>
</html>
