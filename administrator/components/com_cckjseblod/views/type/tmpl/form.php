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
	window.addEvent( "domready",function(){
		var adminFormValidator=new FormValidator($("adminForm"));$("title").addEvent("keyup",function(k){checkavailable(this.getValue())});$("title").addEvent("change",function(c){checkavailable(this.getValue())});var AjaxTooltips=new MooTips($$(".ajaxTip"),{className:"ajaxTool",fixed:true});var selectedJCat=document.getElementById("selected_categories");var nbJCat=selectedJCat.options.length;
		if ( nbJCat ) {
			textJCat = "'.$assignedJCat.'";
			$("joomla_categories_nb").value = nbJCat+" "+textJCat;
		}
		
		$("dblclick_admin0").checked = true;
		$("dblclick_site0").checked = true;
		$("dblclick_content0").checked = true;
		$("dblclick_email0").checked = true;
		
		$("adminfield_types").addEvent("change",function(ai){ai=new Event(ai).stop();selectAndHideAdmin()});$("adminfield_categories").addEvent("change",function(ac){ac=new Event(ac).stop();selectAndHideAdmin()});$("sitefield_types").addEvent("change",function(si){si=new Event(si).stop();selectAndHideSite()});$("sitefield_categories").addEvent("change",function(sc){sc=new Event(sc).stop();selectAndHideSite()});
		$("contentfield_types").addEvent("change",function(ci){ci=new Event(ci).stop();selectAndHideContent()});$("contentfield_categories").addEvent("change",function(cc){cc=new Event(cc).stop();selectAndHideContent()});$("emailfield_types").addEvent("change",function(ei){ei=new Event(ei).stop();selectAndHideEmail()});$("emailfield_categories").addEvent("change",function(ec){ec=new Event(ec).stop();selectAndHideEmail()});
	});

	var setAdminFormActionRequired = function(){if(!$("adminaction_item").hasClass("required")){$("adminaction_item").addClass("required");$("adminaction_item").addClass("required-enabled")}}

	var setAdminFormActionNotRequired = function(){if($("selected_adminfields").length==0){if($("adminaction_item").hasClass("required")){$("adminaction_item").removeClass("required")}if($("adminaction_item").hasClass("required-enabled")){$("adminaction_item").removeClass("required-enabled")}}}
	
	var setSiteFormActionRequired = function(){if(!$("siteaction_item").hasClass("required")){$("siteaction_item").addClass("required");$("siteaction_item").addClass("required-enabled")}}

	var setSiteFormActionNotRequired = function(){if($("selected_sitefields").length==0){if($("siteaction_item").hasClass("required")){$("siteaction_item").removeClass("required")}if($("siteaction_item").hasClass("required-enabled")){$("siteaction_item").removeClass("required-enabled")}}}
		
	var selectAndHideAdmin=function(){var itemsToSelect=document.getElementById("available_adminfields");var itemsToHide=document.getElementById("hidden_adminfields");myItemType=$("adminfield_types").value;myItemCat=$("adminfield_categories").value;var myOptions=[];for(var loop=0;loop<itemsToSelect.options.length;loop++){myOptions[loop]={optText:itemsToSelect.options[loop].text,optValue:itemsToSelect.options[loop].value}}var myHidden=[];for(var loop=0;loop<itemsToHide.options.length;loop++){myHidden[loop]={optText:itemsToHide.options[loop].text,optValue:itemsToHide.options[loop].value}}if(!myItemType&&!myItemCat&&myHidden.length){for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemsToSelect.options[itemsToSelect.length]=optObj}itemsToHide.options.length=0}else{if(!myHidden.length){itemsToSelect.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}else{itemsToSelect.options.length=0;itemsToHide.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemInfos=myHidden[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}}};var selectAndHideSite=function(){var itemsToSelect=document.getElementById("available_sitefields");var itemsToHide=document.getElementById("hidden_sitefields");myItemType=$("sitefield_types").value;myItemCat=$("sitefield_categories").value;var myOptions=[];for(var loop=0;loop<itemsToSelect.options.length;loop++){myOptions[loop]={optText:itemsToSelect.options[loop].text,optValue:itemsToSelect.options[loop].value}}var myHidden=[];for(var loop=0;loop<itemsToHide.options.length;loop++){myHidden[loop]={optText:itemsToHide.options[loop].text,optValue:itemsToHide.options[loop].value}}if(!myItemType&&!myItemCat&&myHidden.length){for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemsToSelect.options[itemsToSelect.length]=optObj}itemsToHide.options.length=0}else{if(!myHidden.length){itemsToSelect.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}else{itemsToSelect.options.length=0;itemsToHide.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemInfos=myHidden[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}}};function allSelected(element){for(var i=0;i<element.options.length;i++){var o=element.options[i];o.selected=true}}function sortFuncAsc(record1,record2){var value1=record1.optText.toLowerCase();var value2=record2.optText.toLowerCase();if(value1>value2)return(1);if(value1<value2)return(-1);return(0)}function sortFuncDesc(record1,record2){var value1=record1.optText.toLowerCase();var value2=record2.optText.toLowerCase();if(value1>value2)return(-1);if(value1<value2)return(1);return(0)}function sortSelect(selectToSort,ascendingOrder){if(arguments.length==1)ascendingOrder=true;var myOptions=[];for(var loop=0;loop<selectToSort.options.length;loop++){myOptions[loop]={optText:selectToSort.options[loop].text,optValue:selectToSort.options[loop].value}}if(ascendingOrder){myOptions.sort(sortFuncAsc)}else{myOptions.sort(sortFuncDesc)}selectToSort.options.length=0;for(var loop=0;loop<myOptions.length;loop++){var optObj=document.createElement("option");optObj.text=myOptions[loop].optText;optObj.value=myOptions[loop].optValue;selectToSort.options.add(optObj)}}function array_search(needle,haystack){var key="";for(key in haystack){if((haystack[key]==needle)){return key}}return false}function addSelectedToListAndSelect(frmName,srcListName,tgtListName,delListName,chkListName){var form=eval("document."+frmName);var srcList=eval("form."+srcListName);var tgtList=eval("form."+tgtListName);var srcLen=srcList.length;var tgtLen=tgtList.length;var tgt="x";for(var i=tgtLen-1;i>-1;i--){tgt+=","+tgtList.options[i].value+","}var k=0;var values=new Array;for(var i=0;i<srcLen;i++){if(srcList.options[i].selected&&tgt.indexOf(","+srcList.options[i].value+",")==-1){opt=new Option(srcList.options[i].text,srcList.options[i].value);tgtList.options[tgtList.length]=opt;values[k]=srcList.options[i].value;k++}}setSelectedValues(frmName,tgtListName,values);if(delListName){if(chkListName){setSelectedValuesIf(frmName,delListName,values,chkListName)}else{setSelectedValues(frmName,delListName,values)}}}function setSelectedValues(frmName,srcListName,values){var form=eval("document."+frmName);var srcList=eval("form."+srcListName);var srcLen=srcList.length;for(var i=0;i<srcLen;i++){srcList.options[i].selected=false;if(array_search(srcList.options[i].value,values)){srcList.options[i].selected=true}}}function moveToTop(frmName,srcListName,index,to){var form=eval("document."+frmName);var srcList=eval("form."+srcListName);var topOpt=[];topOpt={optText:srcList.options[index].text,optValue:srcList.options[index].value};var myOptions=[];for(var loop=0;loop<srcList.options.length;loop++){myOptions[loop]={optText:srcList.options[loop].text,optValue:srcList.options[loop].value}}srcList.options.length=0;var optObj=document.createElement("option");optObj.text=topOpt.optText;optObj.value=topOpt.optValue;srcList.options.add(optObj);for(var loop=0;loop<myOptions.length;loop++){if(loop!=index){var optObj=document.createElement("option");optObj.text=myOptions[loop].optText;optObj.value=myOptions[loop].optValue;srcList.options.add(optObj)}}srcList.options[0].selected=true}function moveToBottom(frmName,srcListName,index,to){var form=eval("document."+frmName);var srcList=eval("form."+srcListName);var topOpt=[];topOpt={optText:srcList.options[index].text,optValue:srcList.options[index].value};var myOptions=[];for(var loop=0;loop<srcList.options.length;loop++){myOptions[loop]={optText:srcList.options[loop].text,optValue:srcList.options[loop].value}}srcList.options.length=0;for(var loop=0;loop<myOptions.length;loop++){if(loop!=index){var optObj=document.createElement("option");optObj.text=myOptions[loop].optText;optObj.value=myOptions[loop].optValue;srcList.options.add(optObj)}}var optObj=document.createElement("option");optObj.text=topOpt.optText;optObj.value=topOpt.optValue;srcList.options.add(optObj);srcList.options[srcList.options.length-1].selected=true}var checkavailable=function(available){var url="index.php?option=com_cckjseblod&controller=types&task=checkAvailability&format=raw&available="+available;var a=new Ajax(url,{method:"get",update:"",onComplete:function(response){if($("available").hasClass("available-enabled")){$("available").removeClass("available-enabled")}if(response&&response>0){if(!$("available").hasClass("available-failed")){if($("available").hasClass("available-passed")){$("available").removeClass("available-passed")}else if($("available").hasClass("available-enabled")){$("available").removeClass("available-enabled")}$("available").addClass("available-failed")}}else{if(!$("available").hasClass("available-passed")){if($("available").hasClass("available-failed")){$("available").removeClass("available-failed")}$("available").addClass("available-passed")}}}}).request()};function submitbutton(pressbutton){var form=document.adminForm;if(pressbutton=="cancel"){submitform(pressbutton);return}var adminFormValidator=new FormValidator($("adminForm"));if(adminFormValidator.validate()&&!$("available").hasClass("available-failed")){allSelected(document.adminForm["selected_categories[]"]);allSelected(document.adminForm["selected_adminfields[]"]);allSelected(document.adminForm["selected_sitefields[]"]);allSelected(document.adminForm["selected_contentfields[]"]);allSelected(document.adminForm["selected_emailfields[]"]);submitform(pressbutton);return}}

	var selectAndHideEmail=function(){var itemsToSelect=document.getElementById("available_emailfields");var itemsToHide=document.getElementById("hidden_emailfields");myItemType=$("emailfield_types").value;myItemCat=$("emailfield_categories").value;var myOptions=[];for(var loop=0;loop<itemsToSelect.options.length;loop++){myOptions[loop]={optText:itemsToSelect.options[loop].text,optValue:itemsToSelect.options[loop].value}}var myHidden=[];for(var loop=0;loop<itemsToHide.options.length;loop++){myHidden[loop]={optText:itemsToHide.options[loop].text,optValue:itemsToHide.options[loop].value}}if(!myItemType&&!myItemCat&&myHidden.length){for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemsToSelect.options[itemsToSelect.length]=optObj}itemsToHide.options.length=0}else{if(!myHidden.length){itemsToSelect.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}else{itemsToSelect.options.length=0;itemsToHide.options.length=0;if(!myItemType){var typeOk=1}if(!myItemCat){var catOk=1}for(var loop=0;loop<myOptions.length;loop++){optObj=new Option(myOptions[loop].optText,myOptions[loop].optValue);itemInfos=myOptions[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}for(var loop=0;loop<myHidden.length;loop++){optObj=new Option(myHidden[loop].optText,myHidden[loop].optValue);itemInfos=myHidden[loop].optValue.split("-");if(((itemInfos[1]==myItemType)||typeOk)&&((itemInfos[2]==myItemCat)||catOk)){itemsToSelect.options[itemsToSelect.length]=optObj}else{itemsToHide.options[itemsToHide.length]=optObj}}}}};
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

var addToContentIf = function( form, srcfield ) {
	addSelectedToListAndSelect(form, srcfield, "selected_contentfields", "", "");
}
var delToContentIf = function( form, srcfield ) {
	delSelectedFromList(form, srcfield);
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
				case "admin":
					trytounassigntoadmin();
					break;
				case "site":
					trytounassigntosite();
					break;
				case "content":
					trytounassigntocontent();
					break;
				case "email":
					trytounassigntoemail();
					break;
				default:
					break;
			}
			break;
	}
}

var trytounassigntoadmin = function() {
addSelectedToListAndSelect("adminForm","selected_adminfields","available_adminfields", "selected_contentfields", "selected_sitefields");
delToContentIf("adminForm","selected_contentfields");
delSelectedFromList("adminForm","selected_adminfields");
setAdminFormActionNotRequired();	
}
var trytounassigntosite = function() {
addSelectedToListAndSelect("adminForm","selected_sitefields","available_sitefields", "selected_contentfields", "selected_adminfields");
delToContentIf("adminForm","selected_contentfields");
delSelectedFromList("adminForm","selected_sitefields");
setSiteFormActionNotRequired();
}
var trytoassigntoadmin = function() {	addSelectedToListAndSelect("adminForm","available_adminfields","selected_adminfields","", "");addToContentIf("adminForm","available_adminfields");delSelectedFromList("adminForm","available_adminfields");setAdminFormActionRequired();}
var trytoassigntosite = function() {	
addSelectedToListAndSelect("adminForm","available_sitefields","selected_sitefields","", "");addToContentIf("adminForm","available_sitefields");delSelectedFromList("adminForm","available_sitefields");setSiteFormActionRequired();}
var trytoassigntocontent = function() {	
addSelectedToListAndSelect("adminForm","available_contentfields","selected_contentfields","","");delSelectedFromList("adminForm","available_contentfields");}
var trytounassigntocontent = function() {
addSelectedToListAndSelect("adminForm","selected_contentfields","available_contentfields","","");delSelectedFromList("adminForm","selected_contentfields");}
var trytoassigntoemail = function() {	
addSelectedToListAndSelect("adminForm","available_emailfields","selected_emailfields","","");delSelectedFromList("adminForm","available_emailfields");}
var trytounassigntoemail = function() {	
addSelectedToListAndSelect("adminForm","selected_emailfields","available_emailfields","","");delSelectedFromList("adminForm","selected_emailfields");}
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
var createField = function(client) {	
	window.addEvent("domready",function(){
		var url="index.php?option=com_cckjseblod&controller=items&task=create&new_f=1&assign="+client+"&tmpl=component";
		SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '._MODAL_WIDTH.', y: '._MODAL_HEIGHT.'}});
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

var typeInterface = function(type) {

	var srcList=document.getElementById("selected_"+type+"fields");
	var typefields = "";
	for(var loop=0;loop<srcList.options.length;loop++){
		var value =	srcList.options[loop].value.split("-");
		typefields += value[0]+",";
	}
	var typefields = typefields.substr(0,typefields.length-1);

	if ( type == "content" ) {
		if ( $("contentdisplay") ) {
			var typevalues = $("contentdisplay").value;
			typevalues = typevalues.replace( /</g, "[[" );
			typevalues = typevalues.replace( />/g, "]]" );
			typevalues = typevalues.replace( /&/g, "@@" );
			typevalues = typevalues.replace( /#/g, "^^" );
		}
	} else {
		if ( $(type+"form") ) {
			var typevalues = $(type+"form").value;
		}
	}
	
	window.addEvent("domready",function(){
		if ( typefields ) {
			var url="index.php?option=com_cckjseblod&controller=types&task="+type+"&tmpl=component&typeitems="+typefields+"&typevalues="+typevalues;
			SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '._MODAL_WIDTH.', y: '._MODAL_HEIGHT.'},closeWithOverlay:false});
		} else {
			alert("'.$errorEmpty.'");
		}
	});
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
					<input class="inputbox required minLength required-enabled" validatorProps="{minLength:3}" type="text" id="title" name="title" maxlength="50" size="32" value="<?php echo ( $this->doCopy ) ? JText::_( 'COPYOF' ) . $this->type->title : @$this->type->title; ?>" />
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
                    <?php echo JText::_( 'NOTE CONTENT').' :: '.JText::_( 'TEMPLATES' ); ?>
                </td>
            </tr>
        </table>
        <table class="admintable" style="margin-bottom: 1px;">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT TEMPLATE' ); ?>::<?php echo JText::_( 'SITE CONTENT TEMPLATE BALLOON' ); ?>">
						<?php echo _IMG_DEFAULT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT TEMPLATE' ); ?>::<?php echo JText::_( 'ASSIGN SITE CONTENT DEFAULT TEMPLATE' ); ?>">
						<?php echo JText::_( 'CONTENT TEMPLATE' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required required-disabled" type="text" id="contenttemplate_title" name="contenttemplate_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$this->type->contenttemplate ) ? $this->type->contenttemplateTitle : $this->defaultContent; ?>" />
					<input type="hidden" id="contenttemplate" name="contenttemplate" value="<?php echo ( @$this->type->contenttemplate ) ? $this->type->contenttemplate : 3; ?>" />
				</td>
				<td>
					<?php echo $this->modals['selectContentTemplate']; ?>
                    <span style="float: left;">&nbsp;&nbsp;&nbsp;</span>
					<div class="button2-left">
                        <div class="blank">
                            <a onclick="javascript: templateParams('contenttemplate');" alt="Params"><?php echo JText::_( 'PARAMS' ); ?></a>
                        </div>
					</div>
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
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ADMIN TEMPLATE' ); ?>::<?php echo JText::_( 'ADMIN FORM TEMPLATE BALLOON' ); ?>">
						<?php echo _IMG_DEFAULT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ADMIN TEMPLATE' ); ?>::<?php echo JText::_( 'ASSIGN ADMIN FORM DEFAULT TEMPLATE' ); ?>">
						<?php echo JText::_( 'ADMIN TEMPLATE' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required required-disabled" type="text" id="admintemplate_title" name="admintemplate_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$this->type->admintemplate ) ? $this->type->admintemplateTitle : $this->defaultSubmission; ?>" />
					<input type="hidden" id="admintemplate" name="admintemplate" value="<?php echo ( @$this->type->admintemplate ) ? $this->type->admintemplate : 1; ?>" />
				</td>
				<td>
					<?php echo $this->modals['selectAdminTemplate']; ?>
                    <span style="float: left;">&nbsp;&nbsp;&nbsp;</span>
					<div class="button2-left">
                        <div class="blank">
                            <a onclick="javascript: templateParams('admintemplate');" alt="Params"><?php echo JText::_( 'PARAMS' ); ?></a>
                        </div>
					</div>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SITE TEMPLATE' ); ?>::<?php echo JText::_( 'SITE FORM TEMPLATE BALLOON' ); ?>">
						<?php echo _IMG_DEFAULT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SITE TEMPLATE' ); ?>::<?php echo JText::_( 'ASSIGN SITE FORM DEFAULT TEMPLATE' ); ?>">
						<?php echo JText::_( 'SITE TEMPLATE' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required required-disabled" type="text" id="sitetemplate_title" name="sitetemplate_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$this->type->sitetemplate ) ? $this->type->sitetemplateTitle : $this->defaultSubmission; ?>" />
					<input type="hidden" id="sitetemplate" name="sitetemplate" value="<?php echo ( @$this->type->sitetemplate ) ? $this->type->sitetemplate : 1; ?>" />
				</td>
				<td>
					<?php echo $this->modals['selectSiteTemplate']; ?>
                    <span style="float: left;">&nbsp;&nbsp;&nbsp;</span>
					<div class="button2-left">
                        <div class="blank">
                            <a onclick="javascript: templateParams('sitetemplate');" alt="Params"><?php echo JText::_( 'PARAMS' ); ?></a>
                        </div>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'ADMIN FORM VIEWS' ); ?></legend>

		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 8px; margin-bottom: 8px; margin-left: 6px; margin-right: 6px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
					<?php echo JText::_( 'DESCRIPTION ADMIN FORM VIEWS' ); ?>
				</td>
			</tr>
		</table>
		</span>
		<table class="admintable" style="margin-bottom: 1px;">
            <tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ADMIN URL' ); ?>::<?php echo JText::_( 'ADMIN URL BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ADMIN URL' ); ?>::<?php echo JText::_( 'ASSIGN ADMIN URL' ); ?>">
						<?php echo JText::_( 'ADMIN URL' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox validate-alphanum-lower-under" type="text" id="admin_url" name="admin_url" maxlength="50" size="32"
                    value="<?php echo @$this->type->admin_url; ?>" onblur="if(this.value=='com_') this.value='';" onfocus="if(this.value=='') this.value='com_';" />
				</td>
                <td id="as-url" class="<?php echo ( @$this->type->admin_url ) ? '' : 'display-no' ?>">
                	&nbsp;&nbsp;<?php echo $this->lists['viewComponent']; ?>
                </td>
			</tr>
            <tr>
            	<td colspan="4">
	            </td>
            </tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'JOOMLA CATEGORIES' ); ?>::<?php echo JText::_( 'JOOMLA CATEGORIES BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'JOOMLA CATEGORIES' ); ?>::<?php echo JText::_( 'ASSIGN JOOMLA CATEGORIES' ); ?>">
						<?php echo $this->lists['hiddenAssignedCategories']; ?>
						<?php echo $this->lists['hiddenAvailableCategories']; ?>
						<?php echo JText::_( 'JOOMLA CATEGORIES' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox notrequired-disabled" type="text" id="joomla_categories_nb" name="joomla_categories_nb" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$this->type->joomla_categories ) ? $this->type->nbJoomlaCategories : JText::_( 'ASSIGN SOME JOOMLA CATEGORIES BY MODAL' ); ?>" />
				</td>
				<td>
					<?php echo $this->modals['selectJoomlaCategories']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="col width-50">
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'ITEMS' ); ?></legend>
	
	<?php
	$tab_state_cookie_name = 'cck_content_type';
	$tab_state = JRequest::getInt($tab_state_cookie_name, 0, 'cookie');
	$tab_params = array('startOffset'=>$tab_state, 'onActive'=>"function(title, description){ description.setStyle('display', 'block'); title.addClass('open').removeClass('closed'); for (var i = 0, l = this.titles.length; i < l; i++) { if (this.titles[i].id == title.id) Cookie.set('$tab_state_cookie_name', i); } }");
	
	$pane =& JPane::getInstance( 'tabs', $tab_params );
	echo $pane->startPane( 'pane' );
	echo $pane->startPanel( _IMG_ADMINITEMS .'&nbsp;&nbsp;&nbsp;'. JText::_( 'ADMIN FORM TAB' ), 'panel1' );
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
					<?php echo $this->lists['hiddenAdminFields']; ?>
					<?php echo JText::_( 'AVAILABLE' ); ?>:
				</span>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<input style="margin-right: 36px;" type="radio" name="dblclick_admin" id="dblclick_admin1" value="1" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'TOP' ); ?>" onclick="moveToTop('adminForm','selected_adminfields',adminForm.selected_adminfields.selectedIndex,-1)" />
				<input class="button_jseblod" type="button" value="<?php echo JText::_( 'BOT' ); ?>" onclick="moveToBottom('adminForm','selected_adminfields',adminForm.selected_adminfields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_admin" id="dblclick_admin2" value="2" />
			</td>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<?php echo $this->lists['adminFieldCategories']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<input style="margin-right: 36px;" type="radio" name="dblclick_admin" id="dblclick_admin3" value="3" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'UP' ); ?>" onclick="moveInList('adminForm','selected_adminfields',adminForm.selected_adminfields.selectedIndex,-1)" />
				<input class="button_jseblod" type="button" value="<?php echo JText::_( 'DOWN' ); ?>" onclick="moveInList('adminForm','selected_adminfields',adminForm.selected_adminfields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_admin" id="dblclick_admin4" value="4" />
			</td>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<?php echo $this->lists['adminFieldTypes']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" valign="top">
				<?php echo $this->lists['adminActionItems']; ?>
				<?php echo $this->lists['assignedAdminFields']; ?>
			</td>
			<td width="190" valign="top">
				<?php echo $this->lists['availableAdminFields']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" class="key_jseblod">
				<input style="margin-left: 54px;" class="button_blank" type="button" id="selectallleftfield" name="selectallleftfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['selected_adminfields[]']);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" id="pushtorightfield" name="pushtorightfield" value="&nbsp;&rarr;" onclick="addSelectedToListAndSelect('adminForm','selected_adminfields','available_adminfields','selected_contentfields', 'selected_sitefields');delToContentIf('adminForm','selected_contentfields');delSelectedFromList('adminForm','selected_adminfields');setAdminFormActionNotRequired();" /><input style="margin-left: 36px;" type="radio" name="dblclick_admin" id="dblclick_admin0" value="0" />
			</td>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" id="pushtoleftfield" name="pushtoleftfield" value="&nbsp;&larr;" onClick="trytoassigntoadmin();" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button"  id="selectallrightfield" name="selectallrightfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['available_adminfields']);" />
			</td>
		</tr>
		<tr>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" value="<?php echo '&nbsp;&rarr;'.JText::_( 'SITE' ); ?>" onclick="addSelectedToListAndSelect('adminForm','selected_adminfields','selected_sitefields','available_sitefields','');delSelectedFromList('adminForm','available_sitefields');setSiteFormActionRequired();" />&nbsp;&nbsp;&nbsp;
                <input class="button_blank" type="button" value="<?php echo '&nbsp;&rarr;'.JText::_( 'EMAIL' ); ?>" onclick="addSelectedToListAndSelect('adminForm','selected_adminfields','selected_emailfields','available_emailfields','');delSelectedFromList('adminForm','available_emailfields');" />
			</td>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" value="<?php echo '&nbsp;A.z'; ?>" onclick="sortSelect(adminForm.available_adminfields, true);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" value="<?php echo '&nbsp;Z.a'; ?>" onclick="sortSelect(adminForm.available_adminfields, false);" />
			</td>
		</tr>
		<tr>
        	<td width="190" class="key_jseblod">
                <input class="button_blank" type="button" value="<?php echo JText::_( 'EDIT ITEM' ); ?>" onclick="javascript: editContentField('admin');" />
			</td>
			<td width="190" class="key_jseblod">
                <input class="button_blank" type="button" value="<?php echo JText::_( 'NEW ITEM' ); ?>" onclick="javascript: createField('admin');" />
			</td>
		</tr>
        <tr>
        	<td width="190" class="key_jseblod">
                <input class="button_blank" type="button" value="<?php echo JText::_( 'EDIT ACTION ITEM' ); ?>" onclick="javascript: editActionField('admin');" />
			</td>
            <td width="190" class="key_jseblod">
			</td>
		</tr>
	</table>
	<?php
	echo $pane->endPanel();
	echo $pane->startPanel( _IMG_SITEITEMS .'&nbsp;&nbsp;&nbsp;' . JText::_( 'SITE FORM TAB' ), 'panel2' );
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
					<?php echo $this->lists['hiddenSiteFields']; ?>
					<?php echo JText::_( 'AVAILABLE' ); ?>:
				</span>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<input style="margin-right: 36px;" type="radio" name="dblclick_site" id="dblclick_site1" value="1" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'TOP' ); ?>" onclick="moveToTop('adminForm','selected_sitefields',adminForm.selected_sitefields.selectedIndex,-1)" />
				<input class="button_jseblod" type="button" value="<?php echo JText::_( 'BOT' ); ?>" onclick="moveToBottom('adminForm','selected_sitefields',adminForm.selected_sitefields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_site" id="dblclick_site2" value="2" />
			</td>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<?php echo $this->lists['siteFieldCategories']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<input style="margin-right: 36px;" type="radio" name="dblclick_site" id="dblclick_site3" value="3" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'UP' ); ?>" onclick="moveInList('adminForm','selected_sitefields',adminForm.selected_sitefields.selectedIndex,-1)" />
				<input class="button_jseblod" type="button" value="<?php echo JText::_( 'DOWN' ); ?>" onclick="moveInList('adminForm','selected_sitefields',adminForm.selected_sitefields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_site" id="dblclick_site4" value="4" />
			</td>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<?php echo $this->lists['siteFieldTypes']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" valign="top">
				<?php echo $this->lists['siteActionItems']; ?>
				<?php echo $this->lists['assignedSiteFields']; ?>
			</td>
			<td width="190" valign="top">
				<?php echo $this->lists['availableSiteFields']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" class="key_jseblod">
				<input style="margin-left: 54px;" class="button_blank" type="button" id="selectallleftfield" name="selectallleftfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['selected_sitefields[]']);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" id="pushtorightfield" name="pushtorightfield" value="&nbsp;&rarr;" onclick="addSelectedToListAndSelect('adminForm','selected_sitefields','available_sitefields','selected_contentfields', 'selected_adminfields');delToContentIf('adminForm','selected_contentfields');delSelectedFromList('adminForm','selected_sitefields');setSiteFormActionNotRequired();" /><input style="margin-left: 36px;" type="radio" name="dblclick_site" id="dblclick_site0" value="0" />
			</td>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" id="pushtoleftfield" name="pushtoleftfield" value="&nbsp;&larr;" onClick="trytoassigntosite();" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button"  id="selectallrightfield" name="selectallrightfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['available_sitefields']);" />
			</td>
		</tr>
		<tr>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" value="<?php echo '&rarr;'.JText::_( 'ADMIN' ); ?>" onclick="addSelectedToListAndSelect('adminForm','selected_sitefields','selected_adminfields','available_adminfields','');delSelectedFromList('adminForm','available_adminfields');setAdminFormActionRequired();" />&nbsp;&nbsp;&nbsp;
                <input class="button_blank" type="button" value="<?php echo '&rarr;'.JText::_( 'EMAIL' ); ?>" onclick="addSelectedToListAndSelect('adminForm','selected_sitefields','selected_emailfields','available_emailfields','');delSelectedFromList('adminForm','available_emailfields');" />
			</td>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" value="<?php echo '&nbsp;A.z'; ?>" onclick="sortSelect(adminForm.available_sitefields, true);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" value="<?php echo '&nbsp;Z.a'; ?>" onclick="sortSelect(adminForm.available_sitefields, false);" />
			</td>
		</tr>
		<tr>
			<td width="190" class="key_jseblod">
                <input class="button_blank" type="button" value="<?php echo JText::_( 'EDIT ITEM' ); ?>" onclick="javascript: editContentField('site');" />
			</td>
            <td width="190" class="key_jseblod">
                <input class="button_blank" type="button" value="<?php echo JText::_( 'NEW ITEM' ); ?>" onclick="javascript: createField('site');" />
			</td>
		</tr>
        <tr>
			<td width="190" class="key_jseblod">
	            <input class="button_blank" type="button" value="<?php echo JText::_( 'EDIT ACTION ITEM' ); ?>" onclick="javascript: editActionField('site');" />
			</td>
            <td width="190" class="key_jseblod">
			</td>
		</tr>
	</table>

	<?php
	echo $pane->endPanel();
	echo $pane->startPanel( JText::_( 'CONTENT TAB' ).'&nbsp;&nbsp;&nbsp;'._IMG_CONTENTITEMS, 'panel3' );
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
                            <?php echo JText::_( 'DESCRIPTION CONTENT FIELDS' ); ?>
                        </td>
                    </tr>
            	</table>
            	</span>
            </td>
        </tr>
	</table>

	<?php
	echo $pane->endPanel();
	echo $pane->startPanel( JText::_( 'EMAIL TAB' ).'&nbsp;&nbsp;&nbsp;'._IMG_EMAILITEMS, 'panel4' );
	?>
	
	<table class="admintable" >
		<tr>
			<td width="190" class="key_jseblod">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'ASSIGNED ITEMS' ); ?>::<?php echo JText::_( 'SELECT ASSIGNED ITEMS' ); ?>">
					<?php echo JText::_( 'ASSIGNED' ); ?>:
				</span>
			</td>
			<td width="190" class="key_jseblod">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AVAILABLE ITEMS' ); ?>::<?php echo JText::_( 'SELECT AVAILABLE ITEMS' ); ?>">
					<?php echo $this->lists['hiddenEmailFields']; ?>
					<?php echo JText::_( 'AVAILABLE' ); ?>:
				</span>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<input style="margin-right: 36px;" type="radio" name="dblclick_email" id="dblclick_email1" value="1" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'TOP' ); ?>" onclick="moveToTop('adminForm','selected_emailfields',adminForm.selected_emailfields.selectedIndex,-1)" />
				<input class="button_jseblod" type="button" value="<?php echo JText::_( 'BOT' ); ?>" onclick="moveToBottom('adminForm','selected_emailfields',adminForm.selected_emailfields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_email" id="dblclick_email2" value="2" />
			</td>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
				<?php echo $this->lists['emailFieldCategories']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" height="27px" class="key_jseblod" valign="bottom">
            <input style="margin-right: 36px;" type="radio" name="dblclick_email" id="dblclick_email3" value="3" /><input class="button_jseblod" type="button" value="<?php echo JText::_( 'UP' ); ?>" onclick="moveInList('adminForm','selected_emailfields',adminForm.selected_emailfields.selectedIndex,-1)" />
				<input class="button_jseblod" type="button" value="<?php echo JText::_( 'DOWN' ); ?>" onclick="moveInList('adminForm','selected_emailfields',adminForm.selected_emailfields.selectedIndex,+1)" /><input style="margin-left: 36px;" type="radio" name="dblclick_email" id="dblclick_email4" value="4" />
			</td>
			<td width="190" height="27px" class="key_jseblod" align="center" valign="bottom">
				<?php echo $this->lists['emailFieldTypes']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" valign="top">
				<?php echo $this->lists['assignedEmailFields']; ?>
			</td>
			<td width="190" valign="top">
				<?php echo $this->lists['availableEmailFields']; ?>
			</td>
		</tr>
		<tr>
			<td width="190" class="key_jseblod">
				<input style="margin-left: 54px;" class="button_blank" type="button" id="selectallleftfield" name="selectallleftfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['selected_emailfields[]']);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" id="pushtorightfield" name="pushtorightfield" value="&nbsp;&rarr;" onclick="trytounassigntoemail();" /><input style="margin-left: 36px;" type="radio" name="dblclick_email" id="dblclick_email0" value="0" />
			</td>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" id="pushtoleftfield" name="pushtoleftfield" value="&nbsp;&larr;" onClick="trytoassigntoemail();" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button"  id="selectallrightfield" name="selectallrightfield" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['available_emailfields']);" />
			</td>
		</tr>
		<tr>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" value="<?php echo '&nbsp;A.z'; ?>" onclick="sortSelect(adminForm.selected_emailfields, true);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" value="<?php echo '&nbsp;Z.a'; ?>" onclick="sortSelect(adminForm.selected_emailfields, false);" />
			</td>
			<td width="190" class="key_jseblod">
				<input class="button_blank" type="button" value="<?php echo '&nbsp;A.z'; ?>" onclick="sortSelect(adminForm.available_emailfields, true);" />&nbsp;&nbsp;&nbsp;
				<input class="button_blank" type="button" value="<?php echo '&nbsp;Z.a'; ?>" onclick="sortSelect(adminForm.available_emailfields, false);" />
			</td>
		</tr>
        <tr>
        	<td width="190" colspan="2">
        		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 8px; margin-left: 2px; margin-right: 2px;">
            	<table class="admintable">
                    <tr>
                        <td>
                            <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                            <?php echo JText::_( 'DESCRIPTION EMAIL FIELDS' ); ?>
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
<input type="hidden" name="id" value="<?php echo ( $this->doCopy ) ? '' : @$this->type->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo @$this->type->id; ?>" />
<input type="hidden" name="name" value="<?php echo ( $this->doCopy ) ? '' : @$this->type->name?>" />
<input type="hidden" name="adminform" value="<?php echo $this->adminform; ?>" id="adminform" />
<input type="hidden" name="siteform" value="<?php echo $this->siteform; ?>" id="siteform" />
<textarea class="inputbox" style="display: none;" id="contentdisplay" name="contentdisplay" cols="1" rows="1" style="overflow:hidden;"><?php echo $this->contentdisplay; ?></textarea>
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>

<script type="text/javascript">
$("admin_url").addEvent("change", function(u) {
		u = new Event(u).stop();
		
		var layout = $("admin_url").value;
		var field  = "as-url";
		if ( layout ) {
			if ( $(field).hasClass("display-no") ) {
				$(field).removeClass("display-no");
			}
		} else {
			if ( ! $(field).hasClass("display-no") ) {
				$(field).addClass("display-no");
			}
		}		
	});
</script>