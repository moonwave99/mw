<?php $this -> requestView('Backstage\View\base') ?>

<?php startblock('css') ?>
	<?php superBlock() ?>
	
	<link rel="stylesheet" href="<?php $this -> asset('css/datatable_bootstrap.css') ?>"/>	
	<link rel="stylesheet" href="<?php $this -> asset('css/jquery.fancybox.css') ?>"/>	
	<link rel="stylesheet" href="<?php $this -> asset('css/fileuploader.css') ?>"/>		
	<link rel="stylesheet" href="<?php $this -> asset('css/chosen.css') ?>"/>	
	<link rel="stylesheet" href="<?php $this -> asset('css/backstage.css') ?>"/>	

<?php endblock() ?>

<?php startblock('container') ?>

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"/>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="<?php $this -> path_to('backstage')?>">MW | Backstage.</a>
      <div class="nav-collapse">
        <p class="navbar-text pull-right">Logged in as <a href="#"><?php echo $this -> user -> username ?></a></p>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row-fluid">
<?php $this -> requestView('Backstage\View\nav') ?>
    <div class="span10">
      <div class="well">
		<h1><?php startblock('title') ?>This is the backstage.<?php endblock() ?></h1>	
<?php startblock('content') ?>

<?php endblock() ?>
      </div>
    </div><!--/span-->
  </div><!--/row-->

  <hr/>

  <footer>
	<p><strong>&copy; 2012 - <a href="http://www.diegocaponera.com/projects/mw">Made in MW</a>.</strong></p>
  </footer>

</div><!--/.fluid-container-->

<?php endblock() ?>

<?php startblock('js') ?>
	<?php superBlock() ?>

	<script src="<?php $this -> asset('js/libs/jquery.dataTables.min.js')?>"></script>
	<script src="<?php $this -> asset('js/libs/dataTables.bootstrap.js')?>"></script>
	<script src="<?php $this -> asset('js/libs/dataTables.date.js')?>"></script>
	<script src="<?php $this -> asset('js/libs/tiny_mce/jquery.tinymce.js')?>"></script>
	<script src="<?php $this -> asset('js/libs/fileuploader.js')?>"></script>	
	<script src="<?php $this -> asset('js/libs/bootstrap/bootstrap-transition.js')?>"></script>	
	<script src="<?php $this -> asset('js/libs/bootstrap/bootstrap-modal.js')?>"></script>	
	<script src="<?php $this -> asset('js/libs/bootstrap/bootstrap-tooltip.js')?>"></script>	
	<script src="<?php $this -> asset('js/libs/bootstrap/bootstrap-popover.js')?>"></script>			
	<script src="<?php $this -> asset('js/plugins.js')?>"></script>	
	<script src="<?php $this -> asset('js/mylibs/backstage.js')?>"></script>

<?php endblock() ?>