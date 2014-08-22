<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
?>
<div id="samisideBar2">
<?php if($type == 'logout') : ?>
<form action="index.php" method="post" name="login" id="form-login">
<a id="sideBarTab2" onclick="samislide();"><img src="<?php echo JURI::base().'modules/mod_samilogin2/'; ?>images/slide-button-o.gif" alt="sideBar" title="sideBar" /></a>
	
	<div id="sideBarContents" style="width:0px;">
		<div id="sideBarContentsInner">
	<div style="color:#fff;padding: 20px;text-align: center;font-size:16px;"><br><br>
		<span style="font-weight: bold;"><?php echo $user->get('name');	?>,</span> <br>are you sure  <br><br>
	
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'Logout'); ?>" />
	</div>

	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
</form>
<?php else : ?>

	
	<a id="sideBarTab2" onclick="samislide();"><img src="<?php echo JURI::base().'modules/mod_samilogin2/'; ?>images/slide-button.gif" alt="sideBar" title="sideBar" /></a>
	
	<div id="sideBarContents" style="width:0px;">
		<div id="sideBarContentsInner">

			
		
<form id="samiform" action="<?php echo JRoute::_( 'index.php', true); ?>" method="post" name="login" id="form-login" >
	<fieldset >
	<p id="form-login-username">
		<label for="modlgn_username"><?php echo JText::_('Username') ?></label><br />
		<input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" />
	</p>
	<p id="form-login-password">
		<label for="modlgn_passwd"><?php echo JText::_('Password') ?></label><br />
		<input id="modlgn_passwd" type="password" name="passwd" class="inputbox" size="18" alt="password" />
	</p>
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	<p id="form-login-remember">
		<label for="modlgn_remember"><?php echo JText::_('Remember me') ?></label>
		<input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" />
	</p>
	<?php endif; ?>
	<input type="submit" name="Submit" class="button" value="<?php echo JText::_('Login') ?>" />
	</fieldset>
	<ul>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>">
			<?php echo JText::_('Forgot Your Password ?'); ?></a>
		</li>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
			<?php echo JText::_('Forgot Your Username'); ?></a>
		</li>
		<?php
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>">
				<?php echo JText::_('Register'); ?></a>
		</li>
		<?php endif; ?>
	</ul>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php endif; ?>
</div></div></div>