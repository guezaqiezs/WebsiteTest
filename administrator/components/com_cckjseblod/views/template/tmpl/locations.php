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
	$buttons = array('Save'		=> array( 'Save', 'save_jseblod', "javascript: saveParams( 'savelocations' );", 'onclick' ),
					 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
					 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
}
$javascript ='
	function addToListAndSelect( frmName, tgtListName ) {
		var form = eval( "document." + frmName );
		var tgtList = eval( "form." + tgtListName );
		
		var value = $("new_reserved").value;
		if ( value != "" ) {
			value = value.toUpperCase();
			var tgtLen = tgtList.length;
			
			opt = new Option( value, value );
			var res=0;
			for (var i=0; i < tgtLen; i++) {
				if ( tgtList.options[i].value == value ) {
					res = 1;
					break;
				}
			}
			if ( res == 0 ) {
				tgtList.options[tgtList.length] = opt;	
				tgtList.options[tgtList.length-1].selected = true;
			}
			
			$("new_reserved").value = "";
		}
	}
	function allSelected(element){for(var i=0;i<element.options.length;i++){var o=element.options[i];o.selected=true}}
	function saveParams( task ) {
		allSelected(document.adminForm["locations[]"]);
		$("task").value	=	task;
		submitbutton(task);
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php?option=com_cckjseblod&controller=templates&task=savelocations&tmpl=component" method="post" name="adminForm">


<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-templates" style="float: left">
		<?php echo JText::_( 'TEMPLATE' ) . ': <small><small>[ '.JText::_( 'LOCATIONS' ).' ( Xml ) ]</small></small>'; ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo @$this->template->title; ?></legend>
		<table class="adminlist">
        	<tr>
            	<td width="145" align="center">
	                <?php echo JText::_( 'ADD OR DEL LOCATION' ); ?>
					<input class="inputbox" type="text" id="new_reserved" name="new_reserved" style="text-transform: uppercase; margin-top: 2px;" maxlength="50" size="24" value="" /><br />
					<input class="button_blank" style="margin-top: 5px;" type="button" id="pushtorightfield" name="pushtorightfield" value="&nbsp;&larr;" onclick="delSelectedFromList('adminForm', 'locations');" />
					<input class="button_blank" style="margin-top: 5px;" type="button" id="pushtorightfield" name="pushtorightfield" value="&nbsp;&rarr;" onclick="addToListAndSelect('adminForm', 'locations');" />
				</td>
				<td align="left">
					<?php echo $this->locations; ?>
				</td>
            </tr>
		</table>
	</fieldset>
	<fieldset class="adminform" style="border: none;">
        <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 2px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'LOCATIONS' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'LOCATIONS DESCRIPTION' ); ?>
                    <?php echo '<br /><br />&nbsp;&nbsp;' . JText::_( 'BY NAME' ) .':&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. JText::_( 'BY NAME EXAMPLE' ); ?>
                    <?php echo '<br />&nbsp;&nbsp;' . JText::_( 'BY LOCATION' ) .': '. JText::_( 'BY LOCATION EXAMPLE' ); ?>
                    <?php echo '<br /><br />' . JText::_( 'LOCATIONS DESCRIPTION2' ); ?>
				</td>
			</tr>
		</table>
		</span>
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