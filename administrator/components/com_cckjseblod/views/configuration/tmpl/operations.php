<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$buttons = array( 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
				 
//$javascript ='';
//$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-configuration" style="float: left">
		<?php echo JText::_( 'OPERATIONS' ); ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
    <fieldset class="adminform">
		<legend class="legend-border"><?php echo JText::_( 'JOOMLA STYLE' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SQUEEZEBOX CSS' ); ?>::<?php echo JText::_( 'SQUEEZEBOX CSS BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SQUEEZEBOX CSS' ); ?>::<?php echo JText::_( 'UPDATE SQUEEZEBOX CSS' ); ?>">
						<?php echo JText::_( 'SQUEEZEBOX CSS' ); ?>:
					</span>
				</td>
				<td>
					<div class="button2-left">
						<div class="next">
							<a onclick="parent.submitbutton('squeezebox');" alt="Reset"><?php echo JText::_( 'JOOMLA 15 16' ); ?></a>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>
	
    <fieldset class="adminform">
		<legend class="legend-border"><?php echo JText::_( 'JSEBLOD CCK QUICK CATEGORY' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TITLE' ); ?>::<?php echo JText::_( 'EDIT TITLE' ); ?>">
						<?php echo JText::_( 'TITLE' ); ?>:
					</span>
				</td>
				<td>
	                <input class="inputbox" type="text" id="quick_title" name="quick_title" maxlength="50" size="32" value="Quick Category" />&nbsp;
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COLOR' ); ?>::<?php echo JText::_( 'EDIT COLOR' ); ?>">
						<?php echo JText::_( 'COLOR' ); ?>:
					</span>
				</td>
   				<td>
	                <input class="inputbox" type="text" id="quick_color" name="quick_color" maxlength="50" size="32" value="#ffd700" />&nbsp;
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'UPDATE' ); ?>::<?php echo JText::_( 'QUICK CATEGORIES UPDATE BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'UPDATE' ); ?>::<?php echo JText::_( 'SELECT UPDATE' ); ?>">
						<?php echo JText::_( 'UPDATE' ); ?>:
					</span>
				</td>
				<td>
					<div class="button2-left">
						<div class="next">
							<a onclick="parent.$('quick_title').value=$('quick_title').value;parent.submitbutton('update_quick_title');" alt="Reset"><?php echo JText::_( 'TITLE' ); ?></a>
						</div>
					</div>
					<div class="button2-left">
						<div class="next">
							<a onclick="parent.$('quick_color').value=$('quick_color').value;parent.submitbutton('update_quick_color');" alt="Reset"><?php echo JText::_( 'COLOR' ); ?></a>
						</div>
					</div>
					<div class="button2-left">
						<div class="next">
                        <a onclick="parent.$('quick_title').value=$('quick_title').value;parent.$('quick_color').value=$('quick_color').value; 
                        		parent.submitbutton('update_quick_title_color');" alt="Reset">
								<?php echo JText::_( 'TITLE AND COLOR' ); ?>
                            </a>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'JSEBLOD CCK TEMPLATES' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CLEAN TEMPLATE FOLDERS' ); ?>::<?php echo JText::_( 'CLEAN TEMPLATE FOLDERS BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CLEAN TEMPLATE FOLDERS' ); ?>::<?php echo JText::_( 'CLICK TO CLEAN TEMPLATE FOLDERS' ); ?>">
						<?php echo JText::_( 'CLEAN TEMPLATE FOLDERS' ); ?>:
					</span>
				</td>
				<td>
					<div class="button2-left">
						<div class="next">
							<a onclick="parent.submitbutton('clean');" alt="Clean"><?php echo JText::_( 'CLEAN' ); ?></a>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend class="legend-border"><?php echo JText::_( 'JSEBLOD CCK RESET COMPONENT' ); ?></legend>
		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-bottom: 10px;">
		<table class="admintable" >
			<tr>
				<td>
					<strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
					<?php echo JText::_( 'DESCRIPTION RESET COMPONENT' ); ?>
				</td>
			</tr>
		</table>
		</span>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'RESET COMPONENT' ); ?>::<?php echo JText::_( 'RESET COMPONENT WARNING' ); ?>">
						<?php echo _IMG_WARNING; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'RESET COMPONENT' ); ?>::<?php echo JText::_( 'RESET COMPONENT DATABASE TABLES' ); ?>">
						<?php echo JText::_( 'RESET COMPONENT' ); ?>:
					</span>
				</td>
				<td>
					<div class="button2-left">
						<div class="next">
							<a onclick="parent.submitbutton('reset');" alt="Reset"><?php echo JText::_( 'Reset' ); ?></a>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="" />
<input type="hidden" name="cid[]" value="" />
<input type="hidden" name="name" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>
<br />
<?php
HelperjSeblod_Display::quickCopyright();
?><br />