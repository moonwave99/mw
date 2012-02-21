<?php
	
	$log = \MWCore\Kernel\MWLog::getInstance();

?>

<script defer src="<?php $this -> asset('js/mylibs/debug.js') ?>"></script>

<div id="debug" class="clearfix">
	<div id="debug-left">
		<span class="highlight">[MWDebugger]</span>
		| Excecution Time: <span class="highlight"><?php echo $log -> getExectime() ?> sec</span>
		| Query Number: <span class="highlight"><?php echo $log -> getQueryNumber() ?></span>
	<?php if($this -> user !== NULL):?>
		| Logged as: <span class="highlight"><?php echo $this -> user -> username ?></span>
	<?php endif;?>
	</div>
	<div id="debug-right">
		<a href="#" data-controller="debug" data-action="log">[View Log]</a>
		<a href="#" data-controller="debug" data-action="hide">[Hide Bar]</a> | 
	<?php if($this -> user !== NULL):?>
		<a href="<?php $this -> path_to(MW_LOGOUT_PATH) ?>">[Logout]</a>
	<?php else:?>				
		<a href="<?php $this -> path_to(MW_LOGIN_PATH) ?>">[Login]</a>		
	<?php endif;?>		
		
	</div>
</div>
<div id="log">
	<?php if( count($log -> getList()) == 0 ):?>
		<p>Nothing logged man!</p>
	<?php else: ?>

		<ul>
		<?php foreach($log -> getList() as $item):?>
			<li>
				<span class="orange">[<?php echo $item['datetime'] ?>]</span>
				<span class="orange">[<?php echo $item['class'] ?>]</span>
				<span class="orange">[<?php echo $item['function'] ?>]</span>
				<span class="orange"><?php echo $item['file'] ?> - line <?php echo $item['line'] ?></span>				
				<?php pre($item['object'])?>
			</li>
		<?php endforeach;?>
		</ul>
		
	<?php endif;?>

</div>