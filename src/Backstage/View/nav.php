<div class="span2">
  <div class="well sidebar-nav">
    <ul class="nav nav-list">

<?php foreach($this -> nav as $navSection):?>

      <li class="nav-header"><?php echo $navSection['label'] ?></li>

<?php foreach($navSection['entries'] as $entry):?>
	
      <li><a href="<?php $this -> path_to('backstage/' . $entry -> pathName)?>"><?php echo $entry -> label ?></a></li>

<?php endforeach; ?>

<?php endforeach; ?>
    </ul>
  </div><!--/.well -->
</div><!--/span-->