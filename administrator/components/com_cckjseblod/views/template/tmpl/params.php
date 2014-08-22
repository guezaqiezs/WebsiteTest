<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

if ( ! $this->isAuth ) {
	$buttons = array('Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
} else {
	$buttons = array('Save'		=> array( 'Save', 'save_jseblod', "javascript: saveParams( 'saveparams' );", 'onclick' ),
					 'Apply'	=> array( 'Apply', 'apply_jseblod', "javascript: saveParams( 'applyparams' );", 'onclick' ),
					 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
					 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
}
$javascript ='
	function saveParams( task ) {
		$("task").value	=	task;
		submitbutton(task);
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php?option=com_cckjseblod&controller=templates&task=saveparams&tmpl=component" method="post" name="adminForm">


<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-templates" style="float: left">
		<?php echo JText::_( 'TEMPLATE' ) . ': <small><small>[ '.JText::_( 'PARAMETERS INI' ).' ]</small></small>'; ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo @$this->template->title; ?></legend>
		<table class="adminlist">
			<?php if ( ! is_null( $this->params ) ) { ?>
			<tr>	
				<td align="center" colspan="2" style="padding-bottom: 5px; border: none;">
					<?php echo $this->params->render(); ?>
				</td>
			</tr>
			<?php } ?>
		</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" id="task" />
<input type="hidden" name="template" value="<?php echo @$this->template->name; ?>" />
<input type="hidden" name="template_id" value="<?php echo @$this->template->id; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?><br />