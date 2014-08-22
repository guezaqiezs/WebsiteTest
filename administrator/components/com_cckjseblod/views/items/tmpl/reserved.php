<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$buttons = array('Save'		=> array( 'Save', 'save_jseblod', "javascript: saveReservedItems();", 'onclick' ),
				 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) )
				;

$doClose		=	$this->doClose;

$javascript ='
	window.addEvent("domready",function(){
	
		var doclose	= "'.$doClose.'";
		if ( doclose ) {
			window.parent.document.getElementById("sbox-window").close();
		}	
		
	});
	
	// sort function - ascending (case-insensitive)
	function sortFuncAsc(record1, record2) {
		var value1 = record1.optText.toLowerCase();
		var value2 = record2.optText.toLowerCase();
		if (value1 > value2) return(1);
		if (value1 < value2) return(-1);
		return(0);
	}

	// sort function - descending (case-insensitive)
	function sortFuncDesc(record1, record2) {
		var value1 = record1.optText.toLowerCase();
		var value2 = record2.optText.toLowerCase();
		if (value1 > value2) return(-1);
		if (value1 < value2) return(1);
		return(0);
	}

	function sortSelect(selectToSort, ascendingOrder) {
		if (arguments.length == 1) ascendingOrder = true;    // default to ascending sort
		
		// copy options into an array
		var myOptions = [];
		for (var loop=0; loop<selectToSort.options.length; loop++) {
			myOptions[loop] = { optText:selectToSort.options[loop].text, optValue:selectToSort.options[loop].value };
		}
		
		// sort array
		if (ascendingOrder) {
			myOptions.sort(sortFuncAsc);
		} else {
			myOptions.sort(sortFuncDesc);
		}
		
		// copy sorted options from array back to select box
		selectToSort.options.length = 0;
		for (var loop=0; loop<myOptions.length; loop++) {
			var optObj = document.createElement("option");
			optObj.text = myOptions[loop].optText;
			optObj.value = myOptions[loop].optValue;
			selectToSort.options.add(optObj);
		}
	}
	//
		
	function addToListAndSelect( frmName, tgtListName ) {
		var form = eval( "document." + frmName );
		var tgtList = eval( "form." + tgtListName );
		
		var value = $("new_reserved").value;
				
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
	
	function allSelected(element) {
		for (var i=0; i<element.options.length; i++) {
			var o = element.options[i];
			o.selected = true;
		}
	}
	
	// Save Button
	function saveReservedItems() {
		allSelected( document.adminForm["reserved_items[]"] );
		submitform( "reserved_save" );
	}
	
	
	
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>&amp;tmpl=component" method="post" id="adminForm" name="adminForm">

<div>	
	<fieldset class="adminform modal-bg-toolbar">
		<div class="header icon-48-items" style="float: left">
			<?php echo JText::_( 'CONTENT ITEMS' ) . ': <small><small>[ '.JText::_( 'RESERVE' ).' ]</small></small>'; ?>
		</div>
		<div style="float: right">
			<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
		</div>
	</fieldset>
	
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'RESERVED ITEMS' ); ?></legend>
	
			<table class="admintable" align="center">
			<tr>
				<td>
					<?php echo $this->lists['reservedItems']; ?>
				</td>
			</tr>
			<tr>
				<td class="key_jseblod">
					<input class="button_jseblod" type="button" value="<?php echo 'A.z'; ?>" onclick="sortSelect(adminForm.reserved_items, true);" />
					<input class="button_jseblod" type="button" value="<?php echo 'Z.a'; ?>" onclick="sortSelect(adminForm.reserved_items, false);" />
				</td>
			</tr>
			<tr>
				<td class="key_jseblod">
					<input class="inputbox" type="text" id="new_reserved" name="new_reserved" maxlength="50" size="32" value="" />
					<input class="button_jseblod" type="button" value="<?php echo 'Add'; ?>" onclick="addToListAndSelect('adminForm', 'reserved_items');" />
				</td>
			</tr>
		</table>
	
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="select" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?><br />