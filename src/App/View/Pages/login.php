<?php requestView('App\View\layout', $data) ?>

<?php startblock('pageTitle') ?>
	<?php echo $data['pageTitle'] ?>
<?php endblock() ?>

<?php startblock('title') ?>
	<?php echo $data['title'] ?>
<?php endblock() ?>

<?php startblock('content') ?>

<p><?echo $data['message']?></p>
<form class="mw-form" method="post" action="<?php path_to('login_check')?>" id="login" data-controller="login" data-action="submit">
	<p class="line clearfix">
		<label for="login_username">Username:</label>
		<input type="text" required="required" id="login_username" name="username" placeholder="Username" size="30"/>			
	</p>
	<p class="line clearfix">
		<label for="login_password">Password:</label>			
		<input type="password" required="required" id="login_password" name="password" placeholder="Password" size="30"/>
	</p>		
	<p class="line clearfix">
		<input type="hidden" id="login_token" name="token" value="<?php echo $data['token'] ?>"/>					
		<input type="submit" name="login_submit" value="Login"/>
	</p>
</form>

<?php endblock() ?>

<?php startblock('js') ?>
	<?php superBlock() ?>

	<!-- require page specific JS files here -->

<?php endblock() ?>