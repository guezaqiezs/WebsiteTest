<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$buttons = array( 'Update'	=> array( 'Update', 'forward', "javascript: replaceProcess();", 'onclick' ),
   				  'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				  'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
				 
$javascript = '

	window.addEvent( "domready",function(){	
		var adminFormValidator = new FormValidator( $("adminForm") );
	});

	// Save Button
	function replaceProcess() {
		var adminFormValidator = new FormValidator( $("adminForm") );
		
		if (adminFormValidator.validate()) {
			var search_string	=	document.getElementById("search_string").value;
			var replace_string	=	document.getElementById("replace_string").value;			
			parent.document.getElementById("search_string").value = search_string;
			parent.document.getElementById("replace_string").value = replace_string;
			parent.submitbutton("cpanel_process");
			window.parent.document.getElementById("sbox-window").close();
		}
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-process" style="float: left">
		<?php echo JText::_( 'ARTICLE PROCESS' ); ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
		<legend class="legend-border"><?php echo JText::_( 'REPLACE STRING' ); ?></legend>
		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-bottom: 10px;">
		<table class="admintable" >
			<tr>
				<td>
					<strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
					<?php echo JText::_( 'DESCRIPTION REPLACE STRING IN ARTICLES' ); ?>
				</td>
			</tr>
		</table>
		</span>
		<table class="admintable">
			<tr>
				<td width="25" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'REPLACE STRING' ); ?>::<?php echo JText::_( 'REPLACE STRING IN ARTICLES WARNING' ); ?>">
						<?php echo _IMG_WARNING; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'REPLACE STRING' ); ?>::<?php echo JText::_( 'REPLACE STRING IN ARTICLES' ); ?>">
						<?php echo JText::_( 'REPLACE STRING' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required required-enabled" type="text" id="search_string" name="search_string" maxlength="250" size="32" value="" />
					<?php echo JText::_( 'REPLACE BY' ); ?>
					<input class="inputbox" type="text" id="replace_string" name="replace_string" maxlength="250" size="32" value="" />
					<?php echo JText::_( 'IN ALL ARTICLES' ); ?>
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

<?php
HelperjSeblod_Display::quickCopyright();
?>