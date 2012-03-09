<div class="span2">
  <div class="well sidebar-nav">
    <ul class="nav nav-list">

<?php foreach($this -> nav as $navSection):?>

      <li class="nav-header"><?php echo $navSection['label'] ?></li>

<?php foreach($navSection['entries'] as $entry):?>
	
      <li<?php echo $entry -> pathName == $this -> entity ? ' class="active"' : '' ?>>
		<a href="<?php $this -> path_to('backstage/' . $entry -> pathName)?>">
			<i class="icon-<?php echo $entry -> icon ?>"></i> <?php echo $entry -> label ?>
		</a>
	  </li>

<?php endforeach; ?>

<?php endforeach; ?>

<?php if($this -> user -> hasRole(ROLE_ADMIN)):?>
	
	<li<?php echo $this -> entity == 'settings' ? ' class="active"' : '' ?>>
		<a data-controller="settings" data-action="edit" href="<?php $this -> path_to('backstage/settings') ?>"><i class="icon-cog"></i> Settings</a>
	</li>
	
<?php endif;?>

    </ul>
  </div><!--/.well -->
</div><!--/span-->