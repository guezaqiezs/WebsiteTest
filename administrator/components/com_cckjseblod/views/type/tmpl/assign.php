<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.modal' );

$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$buttons = array('Apply'	=> array( 'Apply', 'apply_jseblod', "javascript: applyAssign();", 'onclick' ),
				 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );

$assignedJCat	=	JText::_( 'ASSIGNED' );
$assignJCat		=	JText::_( 'ASSIGN SOME JOOMLA CATEGORIES BY MODAL' );
				 
$javascript ='
	window.addEvent( "domready",function(){
			
			var selectedCategories = parent.document.getElementById("selected_categories");
			var mySelected = [];
			for (var loop=0; loop<selectedCategories.options.length; loop++) {
				mySelected[loop] = { optText:selectedCategories.options[loop].text, optValue:selectedCategories.options[loop].value };
			}
			
			var selectedInterface = document.getElementById("selected_categories");
			for (var loop=0; loop<mySelected.length; loop++) {
				optObj = new Option( mySelected[loop].optText, mySelected[loop].optValue );
				selectedInterface.options[selectedInterface.length] = optObj;
			}
			
			var availableCategories = parent.document.getElementById("available_categories");
			var myAvailable = [];
			for (var loop=0; loop<availableCategories.options.length; loop++) {
				myAvailable[loop] = { optText:availableCategories.options[loop].text, optValue:availableCategories.options[loop].value };
			}
			
			var availableInterface = document.getElementById("available_categories");
			for (var loop=0; loop<myAvailable.length; loop++) {
				optObj = new Option( myAvailable[loop].optText, myAvailable[loop].optValue );
				availableInterface.options[availableInterface.length] = optObj;
			}
			
	});

	function allSelected(element) {
		for (var i=0; i<element.options.length; i++) {
			var o = element.options[i];
			o.selected = true;
		}
	}
	
	//
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
	
	function array_search( needle, haystack ) {
		var key = "";
	 
		for( key in haystack ){
			if( ( haystack[key] == needle ) ){
				return key;
			}
		}
		
		return false;
	}
	
	function addSelectedToListAndSelect( frmName, srcListName, tgtListName, delListName ) {
		var form = eval( "document." + frmName );
		var srcList = eval( "form." + srcListName );
		var tgtList = eval( "form." + tgtListName );

		var srcLen = srcList.length;
		var tgtLen = tgtList.length;
		var tgt = "x";

		//build array of target items
		for (var i=tgtLen-1; i > -1; i--) {
			tgt += "," + tgtList.options[i].value + ","
		}

		var k=0;
		var values = new Array;
		
		//Pull selected resources and add them to list
		for (var i=0; i < srcLen; i++) {
			if (srcList.options[i].selected && tgt.indexOf( "," + srcList.options[i].value + "," ) == -1) {
				opt = new Option( srcList.options[i].text, srcList.options[i].value );
				tgtList.options[tgtList.length] = opt;
				values[k] = srcList.options[i].value;
				k++;
			}
		}
		setSelectedValues(frmName, tgtListName, values);
		if (delListName) {
			setSelectedValues(frmName, delListName, values);
		}
		
	}
	
	function setSelectedValues( frmName, srcListName, values ) {
		var form = eval( "document." + frmName );
		var srcList = eval( "form." + srcListName );

		var srcLen = srcList.length;

		for (var i=0; i < srcLen; i++) {
			srcList.options[i].selected = false;
			if ( array_search( srcList.options[i].value, values )) {
				srcList.options[i].selected = true;
			}
		}
	}
		
	var applyAssign = function() {
	
		var selectedInterface = document.getElementById("selected_categories");
		var mySelected = [];
		for (var loop=0; loop<selectedInterface.options.length; loop++) {
			mySelected[loop] = { optText:selectedInterface.options[loop].text, optValue:selectedInterface.options[loop].value };
		}
		
		var selectedCategories = parent.document.getElementById("selected_categories");
		selectedCategories.options.length = 0; 
		for (var loop=0; loop<mySelected.length; loop++) {
			optObj = new Option( mySelected[loop].optText, mySelected[loop].optValue );
			selectedCategories.options[selectedCategories.length] = optObj;
		}
		
		var availableInterface = document.getElementById("available_categories");
		var myAvailable = [];
		for (var loop=0; loop<availableInterface.options.length; loop++) {
			myAvailable[loop] = { optText:availableInterface.options[loop].text, optValue:availableInterface.options[loop].value };
		}
		
		var availableCategories = parent.document.getElementById("available_categories");
		availableCategories.options.length = 0; 
		for (var loop=0; loop<myAvailable.length; loop++) {
			optObj = new Option( myAvailable[loop].optText, myAvailable[loop].optValue );
			availableCategories.options[availableCategories.length] = optObj;
		}
		
		var nb = selectedCategories.options.length;
		if ( nb ) {
			text = "'.$assignedJCat.'";
			parent.$("joomla_categories_nb").value = nb+" "+text;
		} else {
			text = "'.$assignJCat.'";
			parent.$("joomla_categories_nb").value = text;
		}
		
		window.parent.document.getElementById("sbox-window").close();	
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-categories" style="float: left">
		<?php echo JText::_( 'JOOMLA CATEGORIES' ) . ': <small><small>[ '.JText::_( 'ASSIGN' ).' ]</small></small>'; ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'JOOMLA CATEGORIES' ); ?></legend>
	
		<table class="admintable" align="center">
			<tr>
				<td width="100" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ASSIGNED JOOMLA CATEGORIES' ); ?>::<?php echo JText::_( 'SELECT ASSIGNED JOOMLA CATEGORIES' ); ?>">
						<?php echo JText::_( 'ASSIGNED' ); ?>:
					</span>
				</td>
				<td width="100" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'AVAILABLE JOOMLA CATEGORIES' ); ?>::<?php echo JText::_( 'SELECT AVAILABLE JOOMLA CATEGORIES' ); ?>">
						<?php echo JText::_( 'AVAILABLE' ); ?>:
					</span>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $this->lists['assignedCategories']; ?>
				</td>
				<td>
					<?php echo $this->lists['availableCategories']; ?>
				</td>
			</tr>
			<tr>
				<td class="key_jseblod">
					<input class="button_jseblod" type="button" id="selectallleftcategory" name="selectallleftcategory" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['selected_categories[]']);" />
					<input class="button_jseblod" type="button" id="pushtorightcategory" name="pushtorightcategory" value="&nbsp;&rarr;" onclick="addSelectedToListAndSelect('adminForm','selected_categories','available_categories','');delSelectedFromList('adminForm','selected_categories');" />
				</td>
				<td class="key_jseblod">
					<input class="button_jseblod" type="button" id="pushtoleftcategory" name="pushtoleftcategory" value="&nbsp;&larr;" onClick="addSelectedToListAndSelect('adminForm','available_categories','selected_categories','');delSelectedFromList('adminForm','available_categories');" />
					<input class="button_jseblod" type="button"  id="selectallrightcategory" name="selectallrightcategory" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['available_categories']);" />
				</td>
			</tr>
			<tr>
				<td class="key_jseblod">
					<input class="button_jseblod" type="button" value="<?php echo '&nbsp;A.z'; ?>" onclick="sortSelect(adminForm.selected_categories, true);" />
					<input class="button_jseblod" type="button" value="<?php echo '&nbsp;Z.a'; ?>" onclick="sortSelect(adminForm.selected_categories, false);" />
				</td>
				<td class="key_jseblod">
					<input class="button_jseblod" type="button" value="<?php echo '&nbsp;A.z'; ?>" onclick="sortSelect(adminForm.available_categories, true);" />
					<input class="button_jseblod" type="button" value="<?php echo '&nbsp;Z.a'; ?>" onclick="sortSelect(adminForm.available_categories, false);" />
				</td>
			</tr>
		</table>
		
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo ( $this->doCopy ) ? '' : @$this->type->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo @$this->type->id; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>