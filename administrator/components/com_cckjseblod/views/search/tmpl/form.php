<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
jimport( 'joomla.html.pane' );
JHTML::_( 'behavior.modal' );

$editAlert	=	JText::_( 'SELECT FIELD TO EDIT' );
$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );
$rel 	= "{handler: 'iframe', size: {x: "._MODAL_WIDTH.", y: "._MODAL_HEIGHT."}}";

$assignedJCat	=	JText::_( 'ASSIGNED' );
$errorEmpty		=	JText::_( 'NO SEARCH FIELD' );

$javascript ='
	window.addEvent( "domready",function(){	var adminFormValidator=new FormValidator($("adminForm"));
		$("title").addEvent("keyup",function(k){checkavailable(this.getValue())});$("title").addEvent("change",function(c){checkavailable(this.getValue())});
		var AjaxTooltips=new MooTips($$(".ajaxTip"),{className:"ajaxTool",fixed:true});
		
		$("dblclick_list0").checked = true;
		$("dblclick_search0").checked = true;
		$("dblclick_content0").checked = true;
		
		$("searchfield_types").addEvent("change",function(ai){ai=new Event(ai).stop();selectAndHideSearch()});$("searchfield_categories").addEvent("change",function(ac){ac=new Event(ac).stop();selectAndHideSearch()});
		$("listfield_types").addEvent("change",function(si){si=new Event(si).stop();selectAndHideList()});$("listfield_categories").addEvent("change",function(sc){sc=new Event(sc).stop();selectAndHideList()});
		$("contentfield_types").addEvent("change",function(si){si=new Event(si).stop();selectAndHideContent()});$("contentfield_categories").addEvent("change",function(sc){sc=new Event(sc).stop();selectAndHideContent()});
		
		if ( $("content").value != 2 ) {
			if ( ! $("panel4").hasClass("display-no") ) {
				$("panel4").addClass("display-no");
			}			
		}
		$("content").addEvent("change", function(c) {
			c = new Event(c).stop();
			
			var layout = $("content").value;
			
			if ( layout == 2 || layout == 3 || layout == 4 ) {
				if ( $("as-special").hasClass("display-no") ) {
					$("as-special").removeClass("display-no");
				}
				if ( layout == 2 ) {
					if ( $("panel4").hasClass("display-no") ) {
						$("panel4").removeClass("display-no");
					}
				} else {
					if ( ! $("panel4").hasClass("display-no") ) {
						$("panel4").addClass("display-no");
					}
					if ( $("panel4").hasClass("open") ) {
						$("panel4").removeClass("open");
						$("panel4").addClass("closed");
					}					
				}
			} else {
				if ( ! $("as-special").hasClass("display-no") ) {
					$("as-special").addClass("display-no");
				}
				if ( ! $("panel4").hasClass("display-no") ) {
					$("panel4").addClass("display-no");
				}
				if ( $("panel4").hasClass("open") ) {
					$("panel4").removeClass("open");
					$("panel4").addClass("closed");
				}
			}
		});
	});

	var selectAndHideSearch=function(){var itemsToSelect=document.getElementById("available_searchfields");var itemsToHide=document.getElementById("hidden_searchfields");myItemType=$("searchfield_types").value;myItemCat=$("searchfield_categories").value;var myOptions=[];for(var loop=0;loop<itemsToSelect.options.length;loop++){myOptions[loop]={optText:itemsToSelect.options[loop].text,optValue:itemsToSelect.options[loop].value}}var myHidden=[];for(var loop=0;loop<itemsToHide.options.length;loop++){myHidden[loop]={optText:itemsToHide.options[loop].text,optValue:itemsToHide.options[loop].value}}if(!myItemType&&!myItemCat&&myHidden.length){for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemsToSelect.options[itemsToSelect.length]=optObj}itemsToHide.options.length=0}else{if(!myHidden.length){itemsToSelect.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}else{itemsToSelect.options.length=0;itemsToHide.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemInfos=myHidden[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}}};var selectAndHideList=function(){var itemsToSelect=document.getElementById("available_listfields");var itemsToHide=document.getElementById("hidden_listfields");myItemType=$("listfield_types").value;myItemCat=$("listfield_categories").value;var myOptions=[];for(var loop=0;loop<itemsToSelect.options.length;loop++){myOptions[loop]={optText:itemsToSelect.options[loop].text,optValue:itemsToSelect.options[loop].value}}var myHidden=[];for(var loop=0;loop<itemsToHide.options.length;loop++){myHidden[loop]={optText:itemsToHide.options[loop].text,optValue:itemsToHide.options[loop].value}}if(!myItemType&&!myItemCat&&myHidden.length){for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemsToSelect.options[itemsToSelect.length]=optObj}itemsToHide.options.length=0}else{if(!myHidden.length){itemsToSelect.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}else{itemsToSelect.options.length=0;itemsToHide.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemInfos=myHidden[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}}};function allSelected(element){for(var i=0;i<element.options.length;i++){var o=element.options[i];o.selected=true}}function sortFuncAsc(record1,record2){var value1=record1.optText.toLowerCase();var value2=record2.optText.toLowerCase();if(value1>value2)return(1);if(value1<value2)return(-1);return(0)}function sortFuncDesc(record1,record2){var value1=record1.optText.toLowerCase();var value2=record2.optText.toLowerCase();if(value1>value2)return(-1);if(value1<value2)return(1);return(0)}function sortSelect(selectToSort,ascendingOrder){if(arguments.length==1)ascendingOrder=true;var myOptions=[];for(var loop=0;loop<selectToSort.options.length;loop++){myOptions[loop]={optText:selectToSort.options[loop].text,optValue:selectToSort.options[loop].value}}if(ascendingOrder){myOptions.sort(sortFuncAsc)}else{myOptions.sort(sortFuncDesc)}selectToSort.options.length=0;for(var loop=0;loop<myOptions.length;loop++){var optObj=document.createElement("option");optObj.text=myOptions[loop].optText;optObj.value=myOptions[loop].optValue;selectToSort.options.add(optObj)}}function array_search(needle,haystack){var key="";for(key in haystack){if((haystack[key]==needle)){return key}}return false}function addSelectedToListAndSelect(frmName,srcListName,tgtListName,delListName,chkListName){var form=eval("document."+frmName);var srcList=eval("form."+srcListName);var tgtList=eval("form."+tgtListName);var srcLen=srcList.length;var tgtLen=tgtList.length;var tgt="x";for(var i=tgtLen-1;i>-1;i--){tgt+=","+tgtList.options[i].value+","}var k=0;var values=new Array;for(var i=0;i<srcLen;i++){if(srcList.options[i].selected&&tgt.indexOf(","+srcList.options[i].value+",")==-1){opt=new Option(srcList.options[i].text,srcList.options[i].value);tgtList.options[tgtList.length]=opt;values[k]=srcList.options[i].value;k++}}setSelectedValues(frmName,tgtListName,values);if(delListName){if(chkListName){setSelectedValuesIf(frmName,delListName,values,chkListName)}else{setSelectedValues(frmName,delListName,values)}}}function setSelectedValues(frmName,srcListName,values){var form=eval("document."+frmName);var srcList=eval("form."+srcListName);var srcLen=srcList.length;for(var i=0;i<srcLen;i++){srcList.options[i].selected=false;if(array_search(srcList.options[i].value,values)){srcList.options[i].selected=true}}}function moveToTop(frmName,srcListName,index,to){var form=eval("document."+frmName);var srcList=eval("form."+srcListName);var topOpt=[];topOpt={optText:srcList.options[index].text,optValue:srcList.options[index].value};var myOptions=[];for(var loop=0;loop<srcList.options.length;loop++){myOptions[loop]={optText:srcList.options[loop].text,optValue:srcList.options[loop].value}}srcList.options.length=0;var optObj=document.createElement("option");optObj.text=topOpt.optText;optObj.value=topOpt.optValue;srcList.options.add(optObj);for(var loop=0;loop<myOptions.length;loop++){if(loop!=index){var optObj=document.createElement("option");optObj.text=myOptions[loop].optText;optObj.value=myOptions[loop].optValue;srcList.options.add(optObj)}}srcList.options[0].selected=true}function moveToBottom(frmName,srcListName,index,to){var form=eval("document."+frmName);var srcList=eval("form."+srcListName);var topOpt=[];topOpt={optText:srcList.options[index].text,optValue:srcList.options[index].value};var myOptions=[];for(var loop=0;loop<srcList.options.length;loop++){myOptions[loop]={optText:srcList.options[loop].text,optValue:srcList.options[loop].value}}srcList.options.length=0;for(var loop=0;loop<myOptions.length;loop++){if(loop!=index){var optObj=document.createElement("option");optObj.text=myOptions[loop].optText;optObj.value=myOptions[loop].optValue;srcList.options.add(optObj)}}var optObj=document.createElement("option");optObj.text=topOpt.optText;optObj.value=topOpt.optValue;srcList.options.add(optObj);srcList.options[srcList.options.length-1].selected=true}

		var checkavailable=function(available){var url="index.php?option=com_cckjseblod&controller=searchs&task=checkAvailability&format=raw&available="+available;var a=new Ajax(url,{method:"get",update:"",onComplete:function(response){if($("available").hasClass("available-enabled")){$("available").removeClass("available-enabled")}if(response&&response>0){if(!$("available").hasClass("available-failed")){if($("available").hasClass("available-passed")){$("available").removeClass("available-passed")}else if($("available").hasClass("available-enabled")){$("available").removeClass("available-enabled")}$("available").addClass("available-failed")}}else{if(!$("available").hasClass("available-passed")){if($("available").hasClass("available-failed")){$("available").removeClass("available-failed")}$("available").addClass("available-passed")}}}}).request()};function submitbutton(pressbutton){var form=document.adminForm;if(pressbutton=="cancel"){submitform(pressbutton);return}var adminFormValidator=new FormValidator($("adminForm"));if(adminFormValidator.validate()&&!$("available").hasClass("available-failed")){allSelected(document.adminForm["selected_searchfields[]"]);allSelected(document.adminForm["selected_listfields[]"]);allSelected(document.adminForm["selected_contentfields[]"]);submitform(pressbutton);return}}
	function allSelected(element){for(var i=0;i<element.options.length;i++){var o=element.options[i];o.selected=true}}

var selectAndHideContent=function(){var itemsToSelect=document.getElementById("available_contentfields");var itemsToHide=document.getElementById("hidden_contentfields");myItemType=$("contentfield_types").value;myItemCat=$("contentfield_categories").value;var myOptions=[];for(var loop=0;loop<itemsToSelect.options.length;loop++){myOptions[loop]={optText:itemsToSelect.options[loop].text,optValue:itemsToSelect.options[loop].value}}var myHidden=[];for(var loop=0;loop<itemsToHide.options.length;loop++){myHidden[loop]={optText:itemsToHide.options[loop].text,optValue:itemsToHide.options[loop].value}}if(!myItemType&&!myItemCat&&myHidden.length){for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemsToSelect.options[itemsToSelect.length]=optObj}itemsToHide.options.length=0}else{if(!myHidden.length){itemsToSelect.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}else{itemsToSelect.options.length=0;itemsToHide.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemInfos=myHidden[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}}};

function setSelectedValuesIf( frmName, srcListName, values, chkListName ) {
	var form = eval( "document." + frmName );
	var srcList = eval( "form." + srcListName );
	var chkList = eval( "form." + chkListName );

	var srcLen = srcList.length;
	var chkLen = chkList.length;

	var chkvalues = new Array;
	for (var i=0; i < chkLen; i++) {
		chkvalues[i] = chkList.options[i].value;
	}

	for (var i=0; i < srcLen; i++) {
		srcList.options[i].selected = false;
		if ( array_search( srcList.options[i].value, chkvalues )) {
		} else {
			if ( array_search( srcList.options[i].value, values )) {
				srcList.options[i].selected = true;
			}
		}
	}
}

var dblclick_do = function( form, field, option, type ) {
	for (var i=1; i<5; i++) {
		var opt = "dblclick_"+type+i;
		if ( $(opt).checked == true ) {
			var fct = i;
			break;
		}
	}
	switch (fct) {
		case 1:
		    moveToTop(form, field, option, 1);
			break;
		case 2:
			moveToBottom(form, field, option, -1)		
			break;
		case 3:
		    moveInList(form, field, option , -1)
			break;
		case 4:
		    moveInList(form, field, option , 1)
			break;
		case 0:
		default:
			switch (type) {
				case "list":
					trytounassigntolist();
					break;
				case "search":
					trytounassigntosearch();
					break;
				case "content":
					trytounassigntocontent();
					break;
				default:
					break;
			}
			break;
	}
}

var trytounassigntolist = function() {
addSelectedToListAndSelect("adminForm","selected_listfields","available_listfields", "", "");delSelectedFromList("adminForm","selected_listfields");
}
var trytoassigntolist = function() {
addSelectedToListAndSelect("adminForm","available_listfields","selected_listfields", "", "");delSelectedFromList("adminForm","available_listfields");}

var trytounassigntosearch = function() {
addSelectedToListAndSelect("adminForm","selected_searchfields","available_searchfields", "", "");delSelectedFromList("adminForm","selected_searchfields");
}
var trytoassigntosearch = function() {
addSelectedToListAndSelect("adminForm","available_searchfields","selected_searchfields", "", "");delSelectedFromList("adminForm","available_searchfields");}
var trytoassigntocontent = function() {	
addSelectedToListAndSelect("adminForm","available_contentfields","selected_contentfields","","");delSelectedFromList("adminForm","available_contentfields");}
var trytounassigntocontent = function() {
addSelectedToListAndSelect("adminForm","selected_contentfields","available_contentfields","","");delSelectedFromList("adminForm","selected_contentfields");}
var editContentField = function(client) {
	var itemToEdit=document.getElementById("selected_"+client+"fields");
	if (!itemToEdit.value) {
		var editalert	=	"'.$editAlert.'";
		alert(editalert);
	} else {
	var tabField	=	itemToEdit.value.split("-");
	window.addEvent("domready",function(){
		var url="index.php?option=com_cckjseblod&controller=items&task=create&assign="+client+"&tmpl=component&cid[]="+tabField[0];
		SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '._MODAL_WIDTH.', y: '._MODAL_HEIGHT.'}});
	});
	}
}
var editActionField = function(client) {
	var itemToEdit=document.getElementById(client+"action_item");
	if (!itemToEdit.value) {
		var editalert	=	"'.$editAlert.'";
		alert(editalert);
	} else {
	window.addEvent("domready",function(){
		var url="index.php?option=com_cckjseblod&controller=items&task=create&assign="+client+"&tmpl=component&cid[]="+itemToEdit.value;
		SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '._MODAL_WIDTH.', y: '._MODAL_HEIGHT.'}});
	});
	}
}
var searchInterface = function(type) {
	var srcList=document.getElementById("selected_"+type+"fields");
	var searchfields = "";
	for(var loop=0;loop<srcList.options.length;loop++){
		var value =	srcList.options[loop].value.split("-");
		searchfields += value[0]+",";
	}
	var searchfields = searchfields.substr(0,searchfields.length-1);
	
	var liststage = "";
	if ( type == "list" ) {
		if ( $("liststage") ) {
			liststage = $("liststage").value;
		}
	}
	if ( type == "content" ) {
		if ( $("contentdisplay") ) {
			var searchvalues = $("contentdisplay").value;
			searchvalues = searchvalues.replace( /</g, "[[" );
			searchvalues = searchvalues.replace( />/g, "]]" );
			searchvalues = searchvalues.replace( /&/g, "@@" );
			searchvalues = searchvalues.replace( /#/g, "^^" );
		}
	} else {
		if ( $(type+"match") ) {
			var searchvalues = $(type+"match").value;
		}
	}
	window.addEvent("domready",function(){
		if ( searchfields ) {
			var template_type = type+"template";
			var template_id	= "";
			if ( $(template_type) ) {
				template_id	= $(template_type).value;
			}
			var url="index.php?option=com_cckjseblod&controller=searchs&task="+type+"&tmpl=component&tmpl_id="+template_id+"&searchitems="+searchfields+"&searchvalues="+searchvalues+"&liststage="+liststage;
			SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '._MODAL_WIDTH.', y: '._MODAL_HEIGHT.'},closeWithOverlay:false});
		} else {
			alert("'.$errorEmpty.'");
		}
	});
}

var templateParams = function(type) {
	if ( $(type) ) {
		var template_id	=	$(type).value;
		if ( template_id ) {
			window.addEvent("domready",function(){
				var url="index.php?option=com_cckjseblod&controller=templates&task=params&tmpl=component&cid[]="+template_id;
				SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '._MODAL_WIDTH.', y: '._MODAL_HEIGHT.'}});
			});
		}
	}
}
var templateLocations = function(type) {
	if ( $(type) ) {
		var template_id	=	$(type).value;
		if ( template_id ) {
			window.addEvent("domready",function(){
				var url="index.php?option=com_cckjseblod&controller=templates&task=locations&tmpl=component&cid[]="+template_id;
				SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '._MODAL_WIDTH.', y: '._MODAL_HEIGHT.'}});
			});
		}
	}
}
';
	
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div class="col width-50">
	<fieldset class="adminform">
		<legend class="legend-border"><?php echo JText::_( 'Details' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Title' ); ?>::<?php echo JText::_( 'TITLE BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="25" align="center" valign="middle" class="key_jseblod">
					<input class="inputbox available-enabled" type="text"  id="available" name="available" maxlength="0"  size="1" value="" disabled="disabled" style="width: 14px; height: 13px; text-align: center; cursor: default; vertical-align: middle;" />
				</td>
				<td width="100" align="right" class="keyy_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Title' ); ?>::<?php echo JText::_( 'EDIT TITLE' ); ?>">
						<?php echo JText::_( 'Title' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required minLength required-enabled" validatorProps="{minLength:3}" type="text" id="title" name="title" maxlength="50" size="32" value="<?php echo ( $this->doCopy ) ? JText::_( 'COPYOF' ) . $this->search->title : @$this->search->title; ?>" />
				</td>
			</tr>
			<tr>
            	<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key" colspan="2">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Published' ); ?>::<?php echo JText::_( 'CHOOSE PUBLISHED OR NOT' ); ?>">
						<?php echo JText::_( 'Published' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['published']; ?>
				</td>
			</tr>
			<tr>
            	<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key" colspan="2">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY' ); ?>::<?php echo JText::_( 'SELECT CATEGORY' ); ?>">
						<?php echo JText::_( 'CATEGORY' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['category']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="4">
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Description' ); ?>::<?php echo JText::_( 'DESCRIPTION BALLOON TYPE' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key" colspan="2">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Description' ); ?>::<?php echo JText::_( 'VIEW EDIT DESCRIPTION' ); ?>">
						<?php echo JText::_( 'Description' ); ?>:
					</span>
				</td>
				<td>
					<span class="ajaxTip" title="<?php echo $this->tooltips['link_description']; ?>">
						<?php echo $this->modals['description']; ?>
					</span>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'DEFAULT TEMPLATES' ); ?></legend>
		<table class="admintable header_jseblod">
            <tr>
                <td>
                    <?php echo JText::_( 'NOTE CONTENT AND LIST').' :: '.JText::_( 'TEMPLATES' ); ?>
                    <span style="background: #FBFBFB; padding: 2px; font-weight: normal; color: #666; border: 1px dashed #DDDDDD; margin-top: 8px; margin-bottom: 6px; margin-left: 6px; margin-right: 6px;">
                    	<?php echo '<b>'.JText::_( 'SUGGESTION LIST TEMPLATE CUSTOM TEXT' ).'</b>'; ?>
                    </span>
                </td>
            </tr>
        </table>
        <table class="admintable" style="margin-bottom: 1px;">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT' ); ?>::<?php echo JText::_( 'SEARCH CONTENT BALLOON' ); ?>">
						<?php echo _IMG_DEFAULT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT' ); ?>::<?php echo JText::_( 'ASSIGN RESULT DEFAULT TEMPLATE' ); ?>">
						<?php echo JText::_( 'CONTENT' ); ?>:
					</span>
				</td>
				<td colspan="2">
					<?php echo $this->lists['content']; ?>&nbsp;
				</td>
			</tr>
			<tr id="as-special" class="admintable <?php echo ( @$this->search->content == 2 || @$this->search->content == 3 || @$this->search->content == 4 ) ? '' : 'display-no' ?>">
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'LIST TEMPLATE' ); ?>::<?php echo JText::_( 'ASSIGN LIST TEMPLATE' ); ?>">
						<?php echo JText::_( 'LIST TEMPLATE' ); ?>:
					</span>
				</td>
				<td>
                	<table cellpadding="0" cellspacing="0" border="0">
                    	<tr>
                        	<td>
								<input class="inputbox required required-disabled" type="text" id="contenttemplate_title" name="contenttemplate_title" maxlength="50" size="32" disabled="disabled"
                    					value="<?php echo ( @$this->search->contenttemplate ) ? $this->search->contenttemplateTitle : $this->defaultContent; ?>" />
								<input type="hidden" id="contenttemplate" name="contenttemplate" value="<?php echo ( @$this->search->contenttemplate ) ? $this->search->contenttemplate
																																					   : $this->defaultContentId; ?>" />
                            </td>
                            <td>
								<?php echo $this->modals['selectContentTemplate']; ?>
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="2">
								<!--<div class="button2-left">
                                    <div class="blank">
                                        <a onclick="alert('hi!')" alt="Params"><?php //echo JText::_( 'INDEX PHP' ); ?></a>
                                    </div>
                                </div>
                                <span style="float: left;">&nbsp;&nbsp;&nbsp;</span>-->
                                <div class="button2-left">
                                    <div class="blank">
                                        <a onclick="javascript: templateLocations('contenttemplate');" alt="Params"><?php echo JText::_( 'LOCATIONS' ); ?></a>
                                    </div>
                                </div>
                                <span style="float: left;">&nbsp;&nbsp;&nbsp;</span>
								<div class="button2-left">
                                    <div class="blank">
                                        <a onclick="javascript: templateParams('contenttemplate');" alt="Params"><?php echo JText::_( 'PARAMS' ); ?></a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
				</td>
			</tr>
		</table>
        <table class="admintable header_jseblod">
            <tr>
                <td>
                    <?php echo JText::_( 'NOTE FORM').' :: '.JText::_( 'TEMPLATES' ); ?>
                </td>
            </tr>
        </table>
		<table class="admintable" style="margin-bottom: 1px;">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEARCH TEMPLATE' ); ?>::<?php echo JText::_( 'SEARCH FORM TEMPLATE BALLOON' ); ?>">
						<?php echo _IMG_DEFAULT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEARCH TEMPLATE' ); ?>::<?php echo JText::_( 'ASSIGN SEARCH FORM DEFAULT TEMPLATE' ); ?>">
						<?php echo JText::_( 'SEARCH TEMPLATE' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required required-disabled" type="text" id="searchtemplate_title" name="searchtemplate_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$this->search->searchtemplate ) ? $this->search->searchtemplateTitle : $this->defaultSubmission; ?>" />
					<input type="hidden" id="searchtemplate" name="searchtemplate" value="<?php echo ( @$this->search->searchtemplate ) ? $this->search->searchtemplate : 1; ?>" />
				</td>
				<td>
					<?php echo $this->modals['selectSearchTemplate']; ?>
					<span style="float: left;">&nbsp;&nbsp;&nbsp;</span>
					<div class="button2-left">
                        <div class="blank">
                            <a onclick="javascript: templateParams('searchtemplate');" alt="Params"><?php echo JText::_( 'PARAMS' ); ?></a>
                        </div>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'SORT' ); ?></legend>
        <?php 
		$panesort =& JPane::getInstance( 'tabs', array( 'startOffset' => 0 ) ); //PB ONLY ONE INSTANCE.....
		
		echo $panesort->startPane( 'panesort' );
		echo $panesort->startPanel( JText::_( 'SORT COMMON' ), 'panelsort1' );
		?>
		<table class="admintable" style="margin-bottom: 1px;">
			<tr height="22">
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SORT' ).' 1'; ?>::<?php echo JText::_( 'SELECT SORT' ); ?>">
						<?php echo JText::_( 'SORT' ).' 1'; ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['sort1']; ?>
				</td>
				<td>
					<?php echo $this->lists['sort1_type']; ?>
				</td>
				<td id="as-sort1_mode" class="<?php echo ( @$this->selectSortType1 != 'CUSTOM' && @$this->selectSortType1 != 'CUSTOM_STAGE' ) ? '' : 'display-no' ?>">
					<?php echo $this->lists['sort1_mode']; ?>
				</td>
				<td id="as-sort1_helper" class="<?php echo ( @$this->selectSortType1 == 'CUSTOM' || @$this->selectSortType1 == 'CUSTOM_STAGE' ) ? '' : 'display-no' ?>">
					<?php echo $this->lists['sort1_helper']; ?>
				</td>
			</tr>
			<tr height="22">
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SORT' ).' 2'; ?>::<?php echo JText::_( 'SELECT SORT' ); ?>">
						<?php echo JText::_( 'SORT' ).' 2'; ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['sort2']; ?>
				</td>
				<td id="as-sort2_type" class="<?php echo ( @$this->selectSort2 ) ? '' : 'display-no' ?>">
					<?php echo $this->lists['sort2_type']; ?>
				</td>
                <td id="as-sort2_mode" class="<?php echo ( @$this->selectSort2 ) ? ( ( @$this->selectSortType2 != 'CUSTOM' && @$this->selectSortType2 != 'CUSTOM_STAGE' ) ? '' : 'display-no' ) : 'display-no' ?>">
					<?php echo $this->lists['sort2_mode']; ?>
				</td>
				<td id="as-sort2_helper" class="<?php echo ( @$this->selectSort2 ) ? ( ( @$this->selectSortType2 == 'CUSTOM' || @$this->selectSortType2 == 'CUSTOM_STAGE' ) ? '' : 'display-no' ) : 'display-no' ?>">
					<?php echo $this->lists['sort2_helper']; ?>
				</td>
			</tr>
			<tr height="22">
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SORT' ).' 3'; ?>::<?php echo JText::_( 'SELECT SORT' ); ?>">
						<?php echo JText::_( 'SORT' ).' 3'; ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['sort3']; ?>
				</td>
				<td id="as-sort3_type" class="<?php echo ( @$this->selectSort3 ) ? '' : 'display-no' ?>">
					<?php echo $this->lists['sort3_type']; ?>
				</td>
                <td id="as-sort3_mode" class="<?php echo ( @$this->selectSort3 ) ? ( ( @$this->selectSortType3 != 'CUSTOM' && @$this->selectSortType3 != 'CUSTOM_STAGE' ) ? '' : 'display-no' ) : 'display-no' ?>">
					<?php echo $this->lists['sort3_mode']; ?>
				</td>
				<td id="as-sort3_helper" class="<?php echo ( @$this->selectSort3 ) ? ( ( @$this->selectSortType3 == 'CUSTOM' || @$this->selectSortType3 == 'CUSTOM_STAGE' ) ? '' : 'display-no' ) : 'display-no' ?>">
					<?php echo $this->lists['sort3_helper']; ?>
				</td>
			</tr>
			<tr height="22">
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SORT' ).' 4'; ?>::<?php echo JText::_( 'SELECT SORT' ); ?>">
						<?php echo JText::_( 'SORT' ).' 4'; ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['sort4']; ?>
				</td>
				<td id="as-sort4_type" class="<?php echo ( @$this->selectSort4 ) ? '' : 'display-no' ?>">
					<?php echo $this->lists['sort4_type']; ?>
				</td>
                <td id="as-sort4_mode" class="<?php echo ( @$this->selectSort4 ) ? ( ( @$this->selectSortType4 != 'CUSTOM' && @$this->selectSortType4 != 'CUSTOM_STAGE' ) ? '' : 'display-no' ) : 'display-no' ?>">
					<?php echo $this->lists['sort4_mode']; ?>
				</td>
				<td id="as-sort4_helper" class="<?php echo ( @$this->selectSort4 ) ? ( ( @$this->selectSortType4 == 'CUSTOM' || @$this->selectSortType4 == 'CUSTOM_STAGE' ) ? '' : 'display-no' ) : 'display-no' ?>">
					<?php echo $this->lists['sort4_helper']; ?>
				</td>
			</tr>
		</table>
		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 8px; margin-bottom: 6px; margin-left: 6px; margin-right: 6px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
					<?php echo JText::_( 'DESCRIPTION SORT COMMON' ); ?>
				</td>
			</tr>
		</table>
		</span>
		<?php
		echo $panesort->endPanel();
		echo $panesort->startPanel( JText::_( 'SORT ADVANCED' ), 'panelsort2' );
		?>
		<table class="admintable" style="margin-bottom: 1px;">
			<tr height="22">
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SORT' ).' 1'; ?>::<?php echo JText::_( 'SELECT SORT' ); ?>">
						<?php echo JText::_( 'SORT' ).' 1'; ?>:
					</span>
				</td>
                <td>
                	<?php echo $this->lists['sort1_bot'].'&nbsp;'.$this->lists['sort1_eot']; ?>
                </td>
                <td>
                	<?php echo $this->lists['sort1_stage']; ?>
                </td>
			</tr>
			<tr height="22">
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SORT' ).' 2'; ?>::<?php echo JText::_( 'SELECT SORT' ); ?>">
						<?php echo JText::_( 'SORT' ).' 2'; ?>:
					</span>
				</td>
                <td id="as-sort2_target" class="<?php echo ( @$this->selectSort2 ) ? '' : 'display-no' ?>">
	                <?php echo $this->lists['sort2_bot'].'&nbsp;'.$this->lists['sort2_eot']; ?>
                </td>
                <td id="as-sort2_stage" class="<?php echo ( @$this->selectSort2 ) ? '' : 'display-no' ?>">
                	<?php echo $this->lists['sort2_stage']; ?>
                </td>
			</tr>
			<tr height="22">
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SORT' ).' 3'; ?>::<?php echo JText::_( 'SELECT SORT' ); ?>">
						<?php echo JText::_( 'SORT' ).' 3'; ?>:
					</span>
				</td>
                <td id="as-sort3_target" class="<?php echo ( @$this->selectSort3 ) ? '' : 'display-no' ?>">
					<?php echo $this->lists['sort3_bot'].'&nbsp;'.$this->lists['sort3_eot']; ?>
                </td>
                <td id="as-sort3_stage" class="<?php echo ( @$this->selectSort3 ) ? '' : 'display-no' ?>">
                	<?php echo $this->lists['sort3_stage']; ?>
                </td>
			</tr>
			<tr height="22">
				<td width="25" height="20" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SORT' ).' 4'; ?>::<?php echo JText::_( 'SELECT SORT' ); ?>">
						<?php echo JText::_( 'SORT' ).' 4'; ?>:
					</span>
				</td>
                <td id="as-sort4_target" class="<?php echo ( @$this->selectSort4 ) ? '' : 'display-no' ?>">
					<?php echo $this->lists['sort4_bot'].'&nbsp;'.$this->lists['sort4_eot']; ?>
                </td>
                <td id="as-sort4_stage" class="<?php echo ( @$this->selectSort4 ) ? '' : 'display-no' ?>">
                	<?php echo $this->lists['sort4_stage']; ?>
                </td>
			</tr>
		</table>
		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 8px; margin-bottom: 6px; margin-left: 6px; margin-right: 6px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
					<?php echo JText::_( 'DESCRIPTION SORT ADVANCED' ); ?>
				</td>
			</tr>
		</table>
		</span>
		<?php
		echo $panesort->endPanel();
		echo $panesort->endPane();
		?>
	</fieldset>
</div>

<div class="col width-50">
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'ITEMS' ); ?></legend>
	
	<?php
	$tab_state_cookie_name = 'cck_search_type';
	$tab_state = JRequest::getInt($tab_state_cookie_name, 1, 'cookie');
	$tab_params = array('startOffset'=>$tab_state, 'onActive'=>"function(title, description){ description.setStyle('display', 'block'); title.addClass('open').removeClass('closed'); for (var i = 0, l = this.titles.length; i < l; i++) { if (this.titles[i].id == title.id) Cookie.set('$tab_state_cookie_name', i); } }");
	$tab_params	=	array(); //PB ONLY ONE INSTANCE.....
	$pane =& JPane::getInstance( 'tabs', $tab_params );
	echo $pane->startPane( 'pane' );
	echo $pane->startPanel( _IMG_LISTITEMS .'&nbsp;&nbsp;&nbsp;'. JText::_( 'LIST TAB' ), 'panel1' );
	?>
    
 	<table class="admintable">
		<tr>
			<td width="190" class="key_jseblod">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'ASSIGNED ITEMS' ); ?>::<?php echo JText::_( 'SELECT ASSIGNED ITEMS' ); ?>">
					<?php echo JText::_( 'ASSIGNED' ); ?>:
				</span>
			</td>
			<td width="190" class="key_jseblod">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AVAILABLE ITEMS' ); ?>::<?php echo JText::_( 'SELECT AVAILABLE ITEMS' ); ?>">
					<?php echo $this->lists['hiddenListFields']; ?>
					<?php echo JText::_( 'AVAILABLE' ); ?>:
				</span>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<input style="margin-right: 36px;" type="radio" name="dblclick_list" id="dblclick_list1" value="1" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'TOP' ); ?>" onclick="moveToTop('adminForm','selected_listfields',adminForm.selected_listfields.selectedIndex,-1)" />
				<input class="button_jseblod" type="button" value="<?php echo JText::_( 'BOT' ); ?>" onclick="moveToBottom('adminForm','selected_listfields',adminForm.selected_listfields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_list" id="dblclick_list2" value="2" />
			</td>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<?php echo $this->lists['listFieldCategories']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<input style="margin-right: 36px;" type="radio" name="dblclick_list" id="dblclick_list3" value="3" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'UP' ); ?>" onclick="moveInList('adminForm','selected_listfields',adminForm.selected_listfields.selectedIndex,-1)" />
				<input class="button_jseblod" type="button" value="<?php echo JText::_( 'DOWN' ); ?>" onclick="moveInList('adminForm','selected_listfields',adminForm.selected_listfields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_list" id="dblclick_list4" value="4" />
			</td>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<?php echo $this->lists['listFieldTypes']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" valign="top">
	            <?php echo $this->lists['listActionItems']; ?>
				<?php echo $this->lists['assignedListFields']; ?>
			</td>
			<td width="190" valign="top">
				<?php echo $this->lists['availableListFields']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" class="key_jseblod">
				<input style="margin-left: 54px;" class="button_blank" type="button" id="selectallleftfield" name="selectallleftfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['selected_listfields[]']);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" id="pushtorightfield" name="pushtorightfield" value="&nbsp;&rarr;" onclick="addSelectedToListAndSelect('adminForm','selected_listfields','available_listfields','', '');delSelectedFromList('adminForm','selected_listfields');" /><input style="margin-left: 36px;" type="radio" name="dblclick_list" id="dblclick_list0" value="0" />
			</td>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" id="pushtoleftfield" name="pushtoleftfield" value="&nbsp;&larr;" onClick="trytoassigntolist();" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button"  id="selectallrightfield" name="selectallrightfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['available_listfields']);" />
			</td>
		</tr>
		<tr>
        	<td width="190" class="key_jseblod" style="line-height:16px;">
				<input class="button_blank" type="button" value="<?php echo JText::_( 'EDIT ITEM' ); ?>" onclick="javascript: editContentField('list');" />
			</td>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" value="<?php echo '&nbsp;A.z'; ?>" onclick="sortSelect(adminForm.available_listfields, true);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" value="<?php echo '&nbsp;Z.a'; ?>" onclick="sortSelect(adminForm.available_listfields, false);" />
			</td>
		</tr>
		<tr>
        	<td width="190" class="key_jseblod" style="line-height:16px;">
				<input class="button_blank" type="button" value="<?php echo JText::_( 'EDIT ACTION ITEM' ); ?>" onclick="javascript: editActionField('list');" />
			</td>
			<td width="190" class="key_jseblod">
			</td>
		</tr>
		<tr>
        	<td width="190" colspan="2">
        		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 8px; margin-left: 2px; margin-right: 2px;">
            	<table class="admintable">
                    <tr>
                        <td>
                            <strong><?php echo JText::_( 'NOTICE' ); ?>:</strong><br />
                            <?php echo JText::_( 'DESCRIPTION SAME SEARCH ACTION' ); ?>
                        </td>
                    </tr>
            	</table>
            	</span>
            </td>
		</tr>
	</table>
    
	<?php
	echo $pane->endPanel();
	echo $pane->startPanel( _IMG_SEARCHITEMS .'&nbsp;&nbsp;&nbsp;' . JText::_( 'SEARCH TAB' ), 'panel2' );
	?>
	
    <table class="admintable">
		<tr>
			<td width="190" class="key_jseblod">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'ASSIGNED ITEMS' ); ?>::<?php echo JText::_( 'SELECT ASSIGNED ITEMS' ); ?>">
					<?php echo JText::_( 'ASSIGNED' ); ?>:
				</span>
			</td>
			<td width="190" class="key_jseblod">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AVAILABLE ITEMS' ); ?>::<?php echo JText::_( 'SELECT AVAILABLE ITEMS' ); ?>">
					<?php echo $this->lists['hiddenSearchFields']; ?>
					<?php echo JText::_( 'AVAILABLE' ); ?>:
				</span>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<input style="margin-right: 36px;" type="radio" name="dblclick_search" id="dblclick_search1" value="1" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'TOP' ); ?>" onclick="moveToTop('adminForm','selected_searchfields',adminForm.selected_searchfields.selectedIndex,-1)" />
				<input class="button_jseblod" type="button" value="<?php echo JText::_( 'BOT' ); ?>" onclick="moveToBottom('adminForm','selected_searchfields',adminForm.selected_searchfields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_search" id="dblclick_search2" value="2" />
			</td>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<?php echo $this->lists['searchFieldCategories']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<input style="margin-right: 36px;" type="radio" name="dblclick_search" id="dblclick_search3" value="3" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'UP' ); ?>" onclick="moveInList('adminForm','selected_searchfields',adminForm.selected_searchfields.selectedIndex,-1)" />
				<input class="button_jseblod" type="button" value="<?php echo JText::_( 'DOWN' ); ?>" onclick="moveInList('adminForm','selected_searchfields',adminForm.selected_searchfields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_search" id="dblclick_search4" value="4" />
			</td>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<?php echo $this->lists['searchFieldTypes']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" valign="top">
				<?php echo $this->lists['searchActionItems']; ?>
				<?php echo $this->lists['assignedSearchFields']; ?>
			</td>
			<td width="190" valign="top">
				<?php echo $this->lists['availableSearchFields']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" class="key_jseblod">
				<input style="margin-left: 54px;" class="button_blank" type="button" id="selectallleftfield" name="selectallleftfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['selected_searchfields[]']);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" id="pushtorightfield" name="pushtorightfield" value="&nbsp;&rarr;" onclick="addSelectedToListAndSelect('adminForm','selected_searchfields','available_searchfields','', '');delSelectedFromList('adminForm','selected_searchfields');" /><input style="margin-left: 36px;" type="radio" name="dblclick_search" id="dblclick_search0" value="0" />
			</td>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" id="pushtoleftfield" name="pushtoleftfield" value="&nbsp;&larr;" onClick="trytoassigntosearch();" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button"  id="selectallrightfield" name="selectallrightfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['available_searchfields']);" />
			</td>
		</tr>
		<tr>
        	<td width="190" class="key_jseblod" style="line-height:16px;">
				<input class="button_blank" type="button" value="<?php echo JText::_( 'EDIT ITEM' ); ?>" onclick="javascript: editContentField('search');" />
			</td>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" value="<?php echo '&nbsp;A.z'; ?>" onclick="sortSelect(adminForm.available_searchfields, true);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" value="<?php echo '&nbsp;Z.a'; ?>" onclick="sortSelect(adminForm.available_searchfields, false);" />
			</td>
		</tr>
		<tr>
        	<td width="190" class="key_jseblod" style="line-height:16px;">
				<input class="button_blank" type="button" value="<?php echo JText::_( 'EDIT ACTION ITEM' ); ?>" onclick="javascript: editActionField('search');" />
			</td>
			<td width="190" class="key_jseblod">
			</td>
		</tr>
		<tr>
        	<td width="190" colspan="2">
        		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 8px; margin-left: 2px; margin-right: 2px;">
            	<table class="admintable">
                    <tr>
                        <td>
                            <strong><?php echo JText::_( 'NOTICE' ); ?>:</strong><br />
                            <?php echo JText::_( 'DESCRIPTION SAME SEARCH ACTION' ); ?>
                        </td>
                    </tr>
            	</table>
            	</span>
            </td>
		</tr>
	</table>
     
	<?php
	echo $pane->endPanel();
	echo $pane->startPanel( JText::_( 'CONTENT TAB' ).'&nbsp;&nbsp;&nbsp;'._IMG_CONTENTITEMS, 'panel4' );
	?>

	<table class="admintable">
		<tr>
			<td width="190" class="key_jseblod">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'ASSIGNED ITEMS' ); ?>::<?php echo JText::_( 'SELECT ASSIGNED ITEMS' ); ?>">
					<?php echo JText::_( 'ASSIGNED' ); ?>:
				</span>
			</td>
            <td width="190" class="key_jseblod">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AVAILABLE ITEMS' ); ?>::<?php echo JText::_( 'SELECT AVAILABLE ITEMS' ); ?>">
					<?php echo $this->lists['hiddenContentFields']; ?>
					<?php echo JText::_( 'AVAILABLE' ); ?>:
				</span>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
                <input style="margin-right: 36px;" type="radio" name="dblclick_content" id="dblclick_content1" value="1" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'TOP' ); ?>" onclick="moveToTop('adminForm','selected_contentfields',adminForm.selected_contentfields.selectedIndex,-1)" />
                <input class="button_jseblod" type="button" value="<?php echo JText::_( 'BOT' ); ?>" onclick= "moveToBottom('adminForm','selected_contentfields',adminForm.selected_contentfields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_content" id="dblclick_content2" value="2" />
			</td>
            <td width="190" height="27px" class="key_jseblod" valign="bottom">
				<?php echo $this->lists['contentFieldCategories']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
                <input style="margin-right: 36px;" type="radio" name="dblclick_content" id="dblclick_content3" value="3" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'UP' ); ?>" onclick="moveInList('adminForm','selected_contentfields',adminForm.selected_contentfields.selectedIndex,-1)" />
				<input class="button_jseblod" type="button" value="<?php echo JText::_( 'DOWN' ); ?>" onclick="moveInList('adminForm','selected_contentfields',adminForm.selected_contentfields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_content" id="dblclick_content4" value="4" />
			</td>
            <td width="190" height="27px" class="key_jseblod" align="center" valign="bottom">
				<?php echo $this->lists['contentFieldTypes']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" valign="top">
				<?php echo $this->lists['assignedContentFields']; ?>
			</td>
            <td width="190" valign="top">
				<?php echo $this->lists['availableContentFields']; ?>
			</td>
		</tr>
       	<tr>
			<td width="190" class="key_jseblod">
				<input style="margin-left: 54px;" class="button_blank" type="button" id="selectallleftfield" name="selectallleftfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['selected_contentfields[]']);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" id="pushtorightfield" name="pushtorightfield" value="&nbsp;&rarr;" onclick="trytounassigntocontent();" /><input style="margin-left: 36px;" type="radio" name="dblclick_content" id="dblclick_content0" value="0" />
			</td>
            <td width="190" class="key_jseblod">
				<input class="button_blank" type="button" id="pushtoleftfield" name="pushtoleftfield" value="&nbsp;&larr;" onClick="trytoassigntocontent();" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button"  id="selectallrightfield" name="selectallrightfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['available_contentfields']);" />
			</td>
		</tr>
		<tr>
			<td width="190" class="key_jseblod">
  				<!--<input class="button_jseblod" type="button" value="<?php /* echo 'Admin'; */ ?>" onclick="alert('Soon, Admin first');" />-->
				<input class="button_blank" type="button" value="<?php echo '&nbsp;A.z'; ?>" onclick="sortSelect(adminForm.selected_contentfields, true);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" value="<?php echo '&nbsp;Z.a'; ?>" onclick="sortSelect(adminForm.selected_contentfields, false);" />
   				<!--<input class="button_jseblod" type="button" value="<?php /* echo 'Site'; */ ?>" onclick="alert('Soon, Site first');" />-->
			</td>
            <td width="190" class="key_jseblod">
				<input class="button_blank" type="button" value="<?php echo '&nbsp;A.z'; ?>" onclick="sortSelect(adminForm.available_contentfields, true);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" value="<?php echo '&nbsp;Z.a'; ?>" onclick="sortSelect(adminForm.available_contentfields, false);" />
			</td>
		</tr>
        <tr>
        	<td width="190" colspan="2">
        		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 8px; margin-left: 2px; margin-right: 2px;">
            	<table class="admintable">
                    <tr>
                        <td>
                            <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                            <?php echo JText::_( 'DESCRIPTION SEARCH CONTENT FIELDS' ); ?>
                        </td>
                    </tr>
            	</table>
            	</span>
            </td>
        </tr>
	</table>
   
	<?php
	echo $pane->endPanel();
	echo $pane->endPane();
	?>

	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo ( $this->doCopy ) ? '' : @$this->search->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo @$this->search->id; ?>" />
<input type="hidden" name="name" value="<?php echo ( $this->doCopy ) ? '' : @$this->search->name?>" />
<input type="hidden" name="searchmatch" value="<?php echo $this->searchmatch; ?>" id="searchmatch" />
<input type="hidden" name="listmatch" value="<?php echo $this->listmatch; ?>" id="listmatch" />
<input type="hidden" name="autotype_id" value="" id="autotype_id" />
<input type="hidden" name="autotype_list" value="" id="autotype_list" />
<input type="hidden" name="autotype_content" value="" id="autotype_content" />
<textarea class="inputbox" style="display: none;" id="contentdisplay" name="contentdisplay" cols="1" rows="1" style="overflow:hidden;"><?php echo $this->contentdisplay; ?></textarea>
<input type="hidden" name="liststage" value="<?php echo $this->liststage; ?>" id="liststage" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>

<script type="text/javascript">
$("sort1").addEvent("change", function(s1) {
		s1 = new Event(s1).stop();
		var layout = $("sort1").value;
		if ( layout ) {
			if ( layout == "--") {
				window.addEvent("domready",function(){
					var url="index.php?option=com_cckjseblod&controller=items&task=select&tmpl=component&into=sort1&extra=injectsort1";
					SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '<?php echo _MODAL_WIDTH; ?>', y: '<?php echo _MODAL_HEIGHT; ?>'}});
				});
			}
		}
	});
$("sort2").addEvent("change", function(s2) {
		s2 = new Event(s2).stop();
		var layout = $("sort2").value;
		if ( layout != 0 ) {
			if ( layout == "--") {
				window.addEvent("domready",function(){
					var url="index.php?option=com_cckjseblod&controller=items&task=select&tmpl=component&into=sort2&extra=injectsort2";
					SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '<?php echo _MODAL_WIDTH; ?>', y: '<?php echo _MODAL_HEIGHT; ?>'}});
				});
			} else {
				if ( $("as-sort2_mode").hasClass("display-no") ) {
					$("as-sort2_mode").removeClass("display-no");
				}
				if ( $("as-sort2_type").hasClass("display-no") ) {
					$("as-sort2_type").removeClass("display-no");
				}
				if ( $("as-sort2_target").hasClass("display-no") ) {
					$("as-sort2_target").removeClass("display-no");
				}
				if ( $("as-sort2_stage").hasClass("display-no") ) {
					$("as-sort2_stage").removeClass("display-no");
				}
			}
		} else {
			if ( ! $("as-sort2_mode").hasClass("display-no") ) {
				$("as-sort2_mode").addClass("display-no");
			}
			if ( ! $("as-sort2_helper").hasClass("display-no") ) {
				$("as-sort2_helper").addClass("display-no");
			}
			if ( ! $("as-sort2_type").hasClass("display-no") ) {
				$("as-sort2_type").addClass("display-no");
			}
			if ( ! $("as-sort2_target").hasClass("display-no") ) {
				$("as-sort2_target").addClass("display-no");
			}
			if ( ! $("as-sort2_stage").hasClass("display-no") ) {
				$("as-sort2_stage").addClass("display-no");
			}
		}
	});
$("sort3").addEvent("change", function(s3) {
		s3 = new Event(s3).stop();
		var layout = $("sort3").value;
		if ( layout != 0 ) {
			if ( layout == "--") {
				window.addEvent("domready",function(){
					var url="index.php?option=com_cckjseblod&controller=items&task=select&tmpl=component&into=sort3&extra=injectsort3";
					SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '<?php echo _MODAL_WIDTH; ?>', y: '<?php echo _MODAL_HEIGHT; ?>'}});
				});
			} else {
				if ( $("as-sort3_mode").hasClass("display-no") ) {
					$("as-sort3_mode").removeClass("display-no");
				}
				if ( $("as-sort3_type").hasClass("display-no") ) {
					$("as-sort3_type").removeClass("display-no");
				}
				if ( $("as-sort3_target").hasClass("display-no") ) {
					$("as-sort3_target").removeClass("display-no");
				}
				if ( $("as-sort3_stage").hasClass("display-no") ) {
					$("as-sort3_stage").removeClass("display-no");
				}
			}
		} else {
			if ( ! $("as-sort3_mode").hasClass("display-no") ) {
				$("as-sort3_mode").addClass("display-no");
			}
			if ( ! $("as-sort3_helper").hasClass("display-no") ) {
				$("as-sort3_helper").addClass("display-no");
			}
			if ( ! $("as-sort3_type").hasClass("display-no") ) {
				$("as-sort3_type").addClass("display-no");
			}
			if ( ! $("as-sort3_target").hasClass("display-no") ) {
				$("as-sort3_target").addClass("display-no");
			}
			if ( ! $("as-sort3_stage").hasClass("display-no") ) {
				$("as-sort3_stage").addClass("display-no");
			}
		}
	});
$("sort4").addEvent("change", function(s4) {
		s4 = new Event(s4).stop();
		var layout = $("sort4").value;
		if ( layout != 0 ) {
			if ( layout == "--") {
				window.addEvent("domready",function(){
					var url="index.php?option=com_cckjseblod&controller=items&task=select&tmpl=component&into=sort4&extra=injectsort4";
					SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '<?php echo _MODAL_WIDTH; ?>', y: '<?php echo _MODAL_HEIGHT; ?>'}});
				});
			} else {
				if ( $("as-sort4_mode").hasClass("display-no") ) {
					$("as-sort4_mode").removeClass("display-no");
				}
				if ( $("as-sort4_type").hasClass("display-no") ) {
					$("as-sort4_type").removeClass("display-no");
				}
				if ( $("as-sort4_target").hasClass("display-no") ) {
					$("as-sort4_target").removeClass("display-no");
				}
				if ( $("as-sort4_stage").hasClass("display-no") ) {
					$("as-sort4_stage").removeClass("display-no");
				}
			}
		} else {
			if ( ! $("as-sort4_mode").hasClass("display-no") ) {
				$("as-sort4_mode").addClass("display-no");
			}
			if ( ! $("as-sort4_helper").hasClass("display-no") ) {
				$("as-sort4_helper").addClass("display-no");
			}
			if ( ! $("as-sort4_type").hasClass("display-no") ) {
				$("as-sort4_type").addClass("display-no");
			}
			if ( ! $("as-sort4_target").hasClass("display-no") ) {
				$("as-sort4_target").addClass("display-no");
			}
			if ( ! $("as-sort4_stage").hasClass("display-no") ) {
				$("as-sort4_stage").addClass("display-no");
			}
		}
	});
$("sorttype1").addEvent("change", function(st1) {
		st1 = new Event(st1).stop();
		var layout = $("sorttype1").value;
		if ( layout == 'CUSTOM' || layout == 'CUSTOM_STAGE' ) {
			if ( $("as-sort1_helper").hasClass("display-no") ) {
				$("as-sort1_helper").removeClass("display-no");
			}
			if ( ! $("as-sort1_mode").hasClass("display-no") ) {
				$("as-sort1_mode").addClass("display-no");
			}
		} else {
			if ( $("as-sort1_mode").hasClass("display-no") ) {
				$("as-sort1_mode").removeClass("display-no");
			}
			if ( ! $("as-sort1_helper").hasClass("display-no") ) {
				$("as-sort1_helper").addClass("display-no");
			}
		}
	});
$("sorttype2").addEvent("change", function(st2) {
		st2 = new Event(st2).stop();
		var layout = $("sorttype2").value;
		if ( layout == 'CUSTOM' || layout == 'CUSTOM_STAGE' ) {
			if ( $("as-sort2_helper").hasClass("display-no") ) {
				$("as-sort2_helper").removeClass("display-no");
			}
			if ( ! $("as-sort2_mode").hasClass("display-no") ) {
				$("as-sort2_mode").addClass("display-no");
			}
		} else {
			if ( $("as-sort2_mode").hasClass("display-no") ) {
				$("as-sort2_mode").removeClass("display-no");
			}
			if ( ! $("as-sort2_helper").hasClass("display-no") ) {
				$("as-sort2_helper").addClass("display-no");
			}
		}
	});
$("sorttype3").addEvent("change", function(st3) {
		st3 = new Event(st3).stop();
		var layout = $("sorttype3").value;
		if ( layout == 'CUSTOM' || layout == 'CUSTOM_STAGE' ) {
			if ( $("as-sort3_helper").hasClass("display-no") ) {
				$("as-sort3_helper").removeClass("display-no");
			}
			if ( ! $("as-sort3_mode").hasClass("display-no") ) {
				$("as-sort3_mode").addClass("display-no");
			}
		} else {
			if ( $("as-sort3_mode").hasClass("display-no") ) {
				$("as-sort3_mode").removeClass("display-no");
			}
			if ( ! $("as-sort3_helper").hasClass("display-no") ) {
				$("as-sort3_helper").addClass("display-no");
			}
		}
	});
$("sorttype4").addEvent("change", function(st4) {
		st4 = new Event(st4).stop();
		var layout = $("sorttype4").value;
		if ( layout == 'CUSTOM' || layout == 'CUSTOM_STAGE' ) {
			if ( $("as-sort4_helper").hasClass("display-no") ) {
				$("as-sort4_helper").removeClass("display-no");
			}
			if ( ! $("as-sort4_mode").hasClass("display-no") ) {
				$("as-sort4_mode").addClass("display-no");
			}
		} else {
			if ( $("as-sort4_mode").hasClass("display-no") ) {
				$("as-sort4_mode").removeClass("display-no");
			}
			if ( ! $("as-sort4_helper").hasClass("display-no") ) {
				$("as-sort4_helper").addClass("display-no");
			}
		}
	});
</script>