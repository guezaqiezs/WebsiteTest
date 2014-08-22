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
	$buttons = array(/*'Save' 	=> array( 'Save', 'save_jseblod', "javascript: saveSource( true );", 'onclick' ),*/
					 'Apply'	=> array( 'Apply', 'apply_jseblod', "javascript: saveSource( false );", 'onclick' ),
					 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
					 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
}
$javascript ='
	function saveSource( task ) {
		submitbutton("savesource");
		if ( task ) {
			window.parent.document.getElementById("sbox-window").close();
		}
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php?option=com_cckjseblod&controller=templates&task=savesources&tmpl=component" method="post" name="adminForm">


<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-templates" style="float: left">
		<?php echo JText::_( 'TEMPLATE' ) . ': <small><small>[ '.JText::_( 'Source' ).' ]</small></small>'; ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
		<table>
			<th align="left">
				<font color="#666666"><?php echo $this->path; ?></font>
			</th>
			<tr>
				<td><textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $this->source; ?></textarea></td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="saveSource" />
<input type="hidden" name="template" value="<?php echo @$this->template->name; ?>" />
<input type="hidden" name="cid" value="<?php echo @$this->template->id; ?>" />
<input type="hidden" name="dir" value="<?php echo $this->dir; ?>" />
<input type="hidden" name="file" value="<?php echo $this->file; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?><br />