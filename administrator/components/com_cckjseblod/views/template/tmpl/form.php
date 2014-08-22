<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
jimport('joomla.html.pane');
JHTML::_( 'behavior.modal' );
$editor =& JFactory::getEditor();
$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );

$task	=	JRequest::getWord( 'task' );
$rel 	= "{handler: 'iframe', size: {x: "._MODAL_WIDTH.", y: "._MODAL_HEIGHT."}}";
$sourcesMode	=	( ! $this->doCopy && ! @$this->template->id ) ? JText::_( 'Install' ) : JText::_( 'UPDATE' );
$check	=	$this->doCopy;

if ( $this->urlsItems ) {
	$nUrl = count( $this->urlsItems );
} else {
	$nUrl = 0;
}

$javascript ='
	window.addEvent( "domready",function(){
				
		var adminFormValidator = new FormValidator( $("adminForm") );
		
		var check = "'.$check.'";
		if ( check ) {
			$("name").addEvent("keyup",function(k){
				checkavailable(this.getValue());
			});
			
			$("name").addEvent("change",function(c){
				checkavailable(this.getValue());
			});
		}
		
		var AjaxTooltips = new MooTips($$(".ajaxTip"), {
			className: "ajaxTool",
			fixed: true
		});
		
		var pages = "'.$this->template->pages.'";
		if ( pages == "all" ) {
			allselections();
		} else if ( pages == "none" ) {
			disableselections();
		} else {
		}
		var init = "'.$this->doCopy.'";
		var docopy = ( init ) ? init : 0;
		var templateid = "'.@$this->template->id.'";
		var templatename = "'.@$this->template->name.'";
		
		$("install_package").addEvent("change",function(){$("name").value="";var package=$("install_package").value;var packagename=package.slice(0,package.length-4);if(packagename.lastIndexOf("\\\")!=-1){var cut=packagename.lastIndexOf("\\\");packagename=packagename.substr(cut+1); }$("name").value=packagename;if(!$("title").value){$("title").value=packagename}if(!$("category").value){$("category").value=1}if(!$("type").value){$("type").value=-2;$("type_title").value=packagename;$("type_category").value=1}});if(!docopy){$("install_folder").addEvent("change",function(){$("name").value="";var folder=$("install_folder").value;$("name").value=folder})}$("select_install").addEvent("change",function(i){i=new Event(i).stop();var layout=$("select_install").value;switch(layout){case"folder":if(!$("from-upload").hasClass("display-no")){$("from-upload").addClass("display-no")}$("from-folder").removeClass("display-no");$("install_folder").value="";$("name").value="";if(docopy){$("name").disabled=""}break;case"upload":if(!$("from-folder").hasClass("display-no")){$("from-folder").addClass("display-no")}$("from-upload").removeClass("display-no");$("install_package").value="";$("name").value="";if(docopy){$("name").disabled="disabled"}if(check){if(!$("available").hasClass("available-passed")){if($("available").hasClass("available-failed")){$("available").removeClass("available-failed")}else if($("available").hasClass("available-enabled")){$("available").removeClass("available-enabled")}$("available").addClass("available-passed")}}break;default:if(!$("from-folder").hasClass("display-no")){$("from-folder").addClass("display-no")}else if(!$("from-upload").hasClass("display-no")){$("from-upload").addClass("display-no")}else{}$("name").value=templatename;break}});
		
		
	});
	
	function allSelected(element){for(var i=0;i<element.options.length;i++){var o=element.options[i];o.selected=true}}function sortFuncAsc(record1,record2){var value1=record1.optText.toLowerCase();var value2=record2.optText.toLowerCase();if(value1>value2)return(1);if(value1<value2)return(-1);return(0)}function sortFuncDesc(record1,record2){var value1=record1.optText.toLowerCase();var value2=record2.optText.toLowerCase();if(value1>value2)return(-1);if(value1<value2)return(1);return(0)}function sortSelect(selectToSort,ascendingOrder){if(arguments.length==1)ascendingOrder=true;var myOptions=[];for(var loop=0;loop<selectToSort.options.length;loop++){myOptions[loop]={optText:selectToSort.options[loop].text,optValue:selectToSort.options[loop].value}}if(ascendingOrder){myOptions.sort(sortFuncAsc)}else{myOptions.sort(sortFuncDesc)}selectToSort.options.length=0;for(var loop=0;loop<myOptions.length;loop++){var optObj=document.createElement("option");optObj.text=myOptions[loop].optText;optObj.value=myOptions[loop].optValue;selectToSort.options.add(optObj)}}function array_search(needle,haystack){var key="";for(key in haystack){if((haystack[key]==needle)){return key}}return false}function addSelectedToListAndSelect(frmName,srcListName,tgtListName,delListName){var form=eval("document."+frmName);var srcList=eval("form."+srcListName);var tgtList=eval("form."+tgtListName);var srcLen=srcList.length;var tgtLen=tgtList.length;var tgt="x";for(var i=tgtLen-1;i>-1;i--){tgt+=","+tgtList.options[i].value+","}var k=0;var values=new Array;for(var i=0;i<srcLen;i++){if(srcList.options[i].selected&&tgt.indexOf(","+srcList.options[i].value+",")==-1){opt=new Option(srcList.options[i].text,srcList.options[i].value);tgtList.options[tgtList.length]=opt;values[k]=srcList.options[i].value;k++}}setSelectedValues(frmName,tgtListName,values);if(delListName){setSelectedValues(frmName,delListName,values)}}function setSelectedValues(frmName,srcListName,values){var form=eval("document."+frmName);var srcList=eval("form."+srcListName);var srcLen=srcList.length;for(var i=0;i<srcLen;i++){srcList.options[i].selected=false;if(array_search(srcList.options[i].value,values)){srcList.options[i].selected=true}}}function allselections(){var e=document.getElementById("selected_menus");e.disabled=true;if(!e.hasClass("notrequired-disabled")){e.addClass("notrequired-disabled")}var i=0;var n=e.options.length;for(i=0;i<n;i++){e.options[i].disabled=true;e.options[i].selected=true}}function disableselections(){var e=document.getElementById("selected_menus");e.disabled=true;if(!e.hasClass("notrequired-disabled")){e.addClass("notrequired-disabled")}var i=0;var n=e.options.length;for(i=0;i<n;i++){e.options[i].disabled=true;e.options[i].selected=false}}function enableselections(){var e=document.getElementById("selected_menus");e.disabled=false;if(e.hasClass("notrequired-disabled")){e.removeClass("notrequired-disabled")}var i=0;var n=e.options.length;for(i=0;i<n;i++){e.options[i].disabled=false}}
	
	var check = "'.$check.'";
	if(check){var checkavailable=function(available){var url="index.php?option=com_cckjseblod&controller=templates&task=checkAvailability&format=raw&available="+available;var a=new Ajax(url,{method:"get",update:"",onComplete:function(response){if($("available").hasClass("available-enabled")){$("available").removeClass("available-enabled")}if(response&&response>0){if(!$("available").hasClass("available-failed")){if($("available").hasClass("available-passed")){$("available").removeClass("available-passed")}else if($("available").hasClass("available-enabled")){$("available").removeClass("available-enabled")}$("available").addClass("available-failed")}}else{if(!$("available").hasClass("available-passed")){if($("available").hasClass("available-failed")){$("available").removeClass("available-failed")}$("available").addClass("available-passed")}}}}).request()}}


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

	// Submit Button
	function submitbutton( pressbutton ) {
		var form = document.adminForm;
		if ( pressbutton == "cancel" ) {
			submitform( pressbutton );
			return;
		}
		var adminFormValidator = new FormValidator( $("adminForm") );
		
		var check = "'.$check.'";
		if(check){if(adminFormValidator.validate()&&!$("available").hasClass("available-failed")){allSelected(document.adminForm["selected_categories[]"]);$("name").disabled="";$("type_title").disabled="";submitform(pressbutton);return}}else{if(adminFormValidator.validate()){allSelected(document.adminForm["selected_categories[]"]);$("name").disabled="";$("type_title").disabled="";if($("locations")){allSelected(document.adminForm["locations[]"]);}submitform(pressbutton);return}}
	}
	
	';
$this->document->addScriptDeclaration( $javascript );
?>

<script type="text/javascript">
	function addElement(parentId, elementTag, elementId, html) {
		// Adds an element to the document
		var p = document.getElementById(parentId);
		var newElement = document.createElement(elementTag);
		newElement.setAttribute('id', elementId);
		newElement.innerHTML = html;
		p.appendChild(newElement);
	}

	function removeElement(elementId) {
		// Removes an element from the document
		var element = document.getElementById(elementId);
		element.parentNode.removeChild(element);
	}
	
	function addOption() {
		optId++;
		var title_title = "<?php echo JText::_( 'Title' ) .'::'. JText::_( 'EDIT TITLE' ); ?>";
		var title_text = "<?php echo JText::_( 'Title' ); ?>";
		var exact_title = "<?php echo JText::_( 'EXACT' ) .'::'. JText::_( 'SELECT EXACT OR NOT' ); ?>";
		var exact_text = "<?php echo JText::_( 'EXACT' ); ?>";
		var url_title = "<?php echo JText::_( 'URL' ) .'::'. JText::_( 'EDIT URL' ); ?>";
		var url_text = "<?php echo JText::_( 'URL' ); ?>";
		var no = "<?php echo JText::_( 'No' ); ?>";
		var yes = "<?php echo JText::_( 'Yes' ); ?>";
		var img_del = '<?php echo _IMG_DEL; ?>'; 
		var html = 	
					'<tr><td width="135" align="right" class="keyy_jseblod"><span class="editlinktip hasTip" title="'+title_title+'">'+title_text+':</span></td>' +
					'<td><input class="inputbox" type="text" id="urls_title" name="urls_title[]" maxlength="50" size="32" value="" />&nbsp;<a href="javascript: removeElement(\'opt-' + optId + '\');">'+img_del+'</a></td></tr>' +
					'<tr><td width="135" align="right" class="keyy_jseblod"><span class="editlinktip hasTip" title="'+exact_title+'">'+exact_text+':</span></td>' +
					'<td><select id="urls_exact" class="inputbox options_url_jseblod" name="urls_exact[]"><option selected="selected" value="0">'+no+'</option><option value="1">'+yes+'</option></select></td></tr>' +
					'<tr><td width="135" align="right" class="keyy_jseblod"><span class="editlinktip hasTip" title="'+url_title+'">'+url_text+':</span></td>' +
					'<td><input class="inputbox" type="text" id="urls_url" name="urls_url[]" maxlength="250" size="60" value="" /></td></tr>' +
					'<tr><td colspan="2"></td></tr>';
		addElement('options', 'table', 'opt-' + optId, html);
	}
	
	var optId = "<?php echo $nUrl; ?>";
</script>
<form enctype="multipart/form-data" action="index.php" method="post" id="adminForm" name="adminForm">

<div class="col width-50">
	<fieldset class="adminform">
		<legend class="legend-border"><?php echo JText::_( 'Details' ); ?></legend>
		<table class="admintable">
			<tr>
            	<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Title' ); ?>::<?php echo JText::_( 'EDIT TITLE' ); ?>">
						<?php echo JText::_( 'Title' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required minLength required-enabled" validatorProps="{minLength:3}" type="text" id="title" name="title" maxlength="50" size="32" value="<?php echo ( $this->doCopy ) ? JText::_( 'COPYOF' ) . $this->template->title : @$this->template->title; ?>" />
				</td>
			</tr>
			<tr>
            	<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
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
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY' ); ?>::<?php echo JText::_( 'SELECT CATEGORY' ); ?>">
						<?php echo JText::_( 'CATEGORY' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['category']; ?>
				</td>
			</tr>
            <tr>
            	<td colspan="3">
                </td>
            </tr>
            <tr>
            	<td width="25" align="right" class="key_jseblod">
                	<span class="editlinktip hasTip" title="<?php echo JText::_( 'TEMPLATE TYPE' ); ?>::<?php echo JText::_( 'TEMPLATE TYPE BALLOON' ); ?>">
						<?php echo _IMG_WARNING; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TEMPLATE TYPE' ); ?>::<?php echo JText::_( 'SELECT TEMPLATE TYPE' ); ?>">
						<?php echo JText::_( 'TEMPLATE TYPE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['type']; ?>
				</td>
			</tr>
            <tr>
            	<td width="25" align="right" class="key_jseblod">
                	<span class="editlinktip hasTip" title="<?php echo JText::_( 'TEMPLATE MODE' ); ?>::<?php echo JText::_( 'TEMPLATE MODE BALLOON' ); ?>">
						<?php echo _IMG_WARNING; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TEMPLATE MODE' ); ?>::<?php echo JText::_( 'CHOOSE TEMPLATE MODE' ); ?>">
						<?php echo JText::_( 'TEMPLATE MODE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['mode']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="3">
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Description' ); ?>::<?php echo JText::_( 'DESCRIPTION BALLOON TEMPLATE' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
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
		<legend class="legend-border"><?php echo $sourcesMode; ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'INSTALL UPDATE MODE' ); ?>::<?php echo JText::_( 'INSTALL UPDATE MODE BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key" <?php echo ( $this->doCopy ) ? 'colspan="2"' : ''; ?>>
					<span class="editlinktip hasTip" title="<?php echo $sourcesMode . '&nbsp;' . JText::_( 'MODE' ); ?>::<?php echo JText::_( 'SELECT' ) . '&nbsp;' . $sourcesMode . '&nbsp;' . JText::_( 'SOURCES MODE' ); ?>">
						<?php echo JText::_( 'INSTALL UPDATE MODE' ); ?>:
					</span>
				</td>
				<td colspan="3">
					<?php echo $this->lists['install']; ?>
					<?php if ( @$this->template->id && ! $this->doCopy && ! $this->baseDir ) { ?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'MISSING TEMPLATE' ); ?>::<?php echo JText::_( 'MISSING TEMPLATE TOOLTIP' ); ?>">
						<?php echo _NBSP._IMG_WARNING; ?>
					</span>
					<?php } ?>
				</td>
			</tr>
			<tr id="from-folder" class="<?php echo ( $this->doCopy == 1 ) ? '' : 'display-no' ?>" >
            	<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key" class="display-yes" <?php echo ( $this->doCopy ) ? 'colspan="2"' : ''; ?>>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EXISTING FOLDER' ); ?>::<?php echo JText::_( 'SELECT EXISTING FOLDER' ); ?>">
						<?php echo JText::_( 'EXISTING FOLDER' ); ?>:
					</span>
				</td>
				<td colspan="2">
					<?php echo $this->lists['folders']; ?>
				</td>
			</tr>
			<tr id="from-upload" class="<?php echo ( ! $this->doCopy && ! @$this->template->id ) ? '' : 'display-no' ?>">
            	<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key" <?php echo ( $this->doCopy ) ? 'colspan="2"' : ''; ?>>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'UPLOAD PACKAGE' ); ?>::<?php echo JText::_( 'BROWSE UPLOAD PACKAGE' ); ?>">
						<?php echo JText::_( 'UPLOAD PACKAGE' ); ?>:
					</span>
				</td>
				<td colspan="2">
					<input class="input_box" type="file" id="install_package" name="install_package" size="34" style="background-color: white;" /><br />
				</td>
			</tr>
			<tr>
				<td colspan="4">
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Name' ); ?>::<?php echo JText::_( 'TEMPLATE NAME BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<?php if ( $this->doCopy ) { ?>
					<td width="25" align="center" valign="middle" class="key_jseblod">
						<input class="inputbox available-enabled" type="text"  id="available" name="available" maxlength="0"  size="1" value="" disabled="disabled" style="width: 14px; height: 13px; text-align: center; cursor: default; vertical-align: middle;" />
					</td>
				<?php } ?>
				<td width="100" align="right" class="keyy_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NAME' ); ?>::<?php echo ( ! $this->doCopy ) ? JText::_( 'AUTO NAME' ) : JText::_( 'EDIT NAME' ); ?>">
						<?php echo JText::_( 'NAME' ); ?>:
					</span>
				</td>
				<td colspan="2">
					<input class="inputbox required validate-alphanum-lower-under minLength required-disabled" validatorProps="{minLength:3}" type="text" id="name" name="name" <?php echo ( !$this->doCopy ) ? 'disabled="disabled"' : ''; ?> maxlength="50" size="32" value="<?php echo ( @$this->template->id && !$this->doCopy ) ? $this->template->name : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT TYPE' ); ?>::<?php echo JText::_( 'CONTENT TYPE FROM TEMPLATE BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key" <?php echo ( $this->doCopy ) ? 'colspan="2"' : ''; ?>>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT TYPE' ); ?>::<?php echo JText::_( 'SELECT CONTENT TYPE' ); ?>">
						<?php echo JText::_( 'CONTENT TYPE' ); ?>:
					</span>
				</td>
				<td>					
					<input class="inputbox notrequired-disabled" type="text" id="type_title" name="type_title" maxlength="50" size="32" disabled="disabled"  value="<?php echo JText::_( 'SELECT A CONTENT TYPE BY MODAL' ); ?>" />
					<input type="hidden" id="type" name="type" value="" />
					<input type="hidden" id="type_category" name="type_category" value="" />
				</td>
				<td>
					<?php echo $this->modals['selectType']; ?><?php echo $this->modals['newType']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset class="adminform" <?php echo ( @$this->template->id && ( ! $this->doCopy ) ) ? 'style="padding-top: 7px;"' : 'style="padding-top: 8px;"'; ?>>
	<legend class="legend-border"><?php echo JText::_( 'SOURCES' ); ?></legend>
		<?php
		$leftpane =& JPane::getInstance( 'Sliders', array( 'startOffset' => 1, 'startTransition' => 0, 'allowAllClose' => true ) ); 
		echo $leftpane->startPane( 'pane' );
		echo $leftpane->startPanel( JText::_( 'TEMPLATE DETAILS XML' ), 'leftpanel1' );
		if ( @$this->template->id && ( ! $this->doCopy ) ) {
			$baseDir 	= substr( strrchr( $this->baseDir, DS ), 1 );
			$file 		= $this->xmlFile;
			$fileName 	= htmlspecialchars( $file, ENT_COMPAT, 'UTF-8' );
			$fileLink	= JRoute::_( 'index.php?option='.$this->option.'&controller='.$this->controller.'&task=source&cid[]='.$this->template->id.'&dir='.$baseDir.'&file='.$fileName.'&tmpl=component' );
		}
		?>
		<table class="adminlist">
			<tr>
			   <td width="145" align="center">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TEMPLATE DETAILS XML' ); ?>::<?php echo JText::_( 'EDIT TEMPLATE DETAILS XML' ); ?>">
						<?php echo _IMG_XML; ?>
					</span>
				</td>	
				<td colspan="3" align="left">
					<?php echo ( @$this->template->id && ! $this->doCopy && $this->baseDir ) ? _NBSP . '/templates/'.$this->template->name.'/' : _NBSP . '<i>' . JText::_( 'NO TEMPLATE YET' ) . '</i>' ; ?>
				</td>
			</tr>
			<?php if ( @$this->template->id && ( ! $this->doCopy ) ) { ?>
			<tr>
				<td width="145" align="center">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TEMPLATE DETAILS XML' ); ?>::<?php echo JText::_( 'EDIT TEMPLATE DETAILS XML' ); ?>">
						<?php echo 1; ?>
					</span>
				</td>			
				<td align="left">
					<strong><?php echo _NBSP . $fileName; ?></strong>
				</td>
				<?php if ( $this->template->id && ! $this->doCopy && $this->xmlFile ) { ?>
				<td width="25%" align="center">
					<?php echo is_writable( $this->baseDir.DS.$file ) ? '<font color="green"> '. JText::_( 'Writable' ) .'</font>' : '<font color="red"> '. JText::_( 'Unwritable' ) .'</font>' ?>
				</td>
				<td width="40" align="center">
					<a href="<?php echo $fileLink; ?>" class="modal" rel="<?php echo $rel; ?>">
					<img height="18" width="18" border="0" alt="Edit" src="components/com_cckjseblod/assets/images/list/icon-18-edit.png"/>
					</a>
				</td>
				<?php } else { ?>
				<td width="25%">
				</td>
				<td width="40" >
				</td>
				<?php } ?>
			</tr>
			<?php } ?>
		</table>
		<?php
		echo $leftpane->endPanel();
		echo $leftpane->startPanel( JText::_( 'CODE PHP' ), 'leftpanel2' );
		$nPhpFiles = count( $this->phpFiles );
		?>
		<table class="adminlist">
			<tr>
			   <td width="145" align="center">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'PHP DIRECTORY' ); ?>::<?php echo JText::_( 'EDIT PHP FILES' ); ?>">
						<?php echo _IMG_PHP; ?>
					</span>
				</td>	
				<td colspan="3" align="left">
					<?php echo ( @$this->template->id && ! $this->doCopy && $this->baseDir ) ? _NBSP . '/templates/'.$this->template->name.'/' : _NBSP . '<i>' . JText::_( 'NO TEMPLATE YET' ) . '</i>' ; ?>
				</td>
			</tr>
			<?php if ( @$this->template->id && ( ! $this->doCopy ) ) { ?>
				<?php for ( $j = 0; $j < $nPhpFiles; $j++ ) {
					$file 		=& $this->phpFiles[$j];
					$fileName 	= htmlspecialchars( $file, ENT_COMPAT, 'UTF-8' );
					$fileLink	= JRoute::_( 'index.php?option='.$this->option.'&controller='.$this->controller.'&task=source&cid[]='.$this->template->id.'&dir='.$baseDir.'&file='.$fileName.'&tmpl=component' );
				?>
				<tr class="<?php $k=0; echo 'row' . $k; ?>">
					<td width="145" align="center">
						<?php echo $j + 1; ?>
					</td>
					<td>
						<strong><?php echo _NBSP . $fileName; ?></strong>
					</td>
					<td width="25%" align="center">
						<?php echo is_writable( $this->baseDir.DS.$file ) ? '<font color="green"> '. JText::_( 'Writable' ) .'</font>' : '<font color="red"> '. JText::_( 'Unwritable' ) .'</font>' ?>
					</td>
					<td width="40" align="center">
						<a href="<?php echo $fileLink; ?>" class="modal" rel="<?php echo $rel; ?>">
						<img height="18" width="18" border="0" alt="Edit" src="components/com_cckjseblod/assets/images/list/icon-18-edit.png"/>
						</a>
					</td>
				</tr>
			<?php } } ?>
		</table>
		<?php
		echo $leftpane->endPanel();
		echo $leftpane->startPanel( JText::_( 'CODE CSS' ), 'leftpanel3' );
		$nCssFiles = count( $this->cssFiles );
		?>
		<table class="adminlist">
			<tr>
			   <td width="145" align="center">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CSS DIRECTORY' ); ?>::<?php echo JText::_( 'EDIT CSS FILES' ); ?>">
						<?php echo _IMG_CSS; ?>
					</span>
				</td>			
				<td colspan="3" align="left">
					<?php echo ( @$this->template->id && ! $this->doCopy && $this->baseDir ) ? _NBSP . '/templates/'.$this->template->name.'/css/' : _NBSP . '<i>' . JText::_( 'NO TEMPLATE YET' ) . '</i>' ; ?>
				</td>
			</tr>
			<?php if ( @$this->template->id && ( ! $this->doCopy ) ) { ?>
				<?php for ( $j = 0; $j < $nCssFiles; $j++ ) {
					$file 		=& $this->cssFiles[$j];
					$fileName 	= htmlspecialchars( $file, ENT_COMPAT, 'UTF-8' );
					$cssDir 	= substr( strrchr( $this->cssDir, DS ), 1 );
					$fileLink	= JRoute::_( 'index.php?option='.$this->option.'&controller='.$this->controller.'&task=source&cid[]='.$this->template->id.'&dir='.$cssDir.'&file='.$fileName.'&tmpl=component' );
				?>
				<tr class="<?php $k=0; echo 'row' . $k; ?>">
					<td width="145" align="center">
						<?php echo $j + 1; ?>
					</td>
					<td>
						<strong><?php echo _NBSP . $fileName; ?></strong>
					</td>
					<td width="25%" align="center">
						<?php echo is_writable( $this->cssDir.DS.$file ) ? '<font color="green"> '. JText::_( 'Writable' ) .'</font>' : '<font color="red"> '. JText::_( 'Unwritable' ) .'</font>' ?>
					</td>
					<td width="40" align="center">
						<a href="<?php echo $fileLink; ?>" class="modal" rel="<?php echo $rel; ?>">
						<img height="18" width="18" border="0" alt="Edit" src="components/com_cckjseblod/assets/images/list/icon-18-edit.png"/>
						</a>
					</td>
				</tr>
			<?php } } ?>
		</table>
<?php
		echo $leftpane->endPanel();
		echo $leftpane->startPanel( JText::_( 'CODE JS' ), 'leftpanel4' );
		$nJsFiles = count( $this->jsFiles );
		?>
		<table class="adminlist">
			<tr>
			   <td width="145" align="center">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'JS DIRECTORY' ); ?>::<?php echo JText::_( 'EDIT JS FILES' ); ?>">
						<?php echo _IMG_JS; ?>
					</span>
				</td>			
				<td colspan="3" align="left">
					<?php echo ( @$this->template->id && ! $this->doCopy && $this->baseDir ) ? _NBSP . '/templates/'.$this->template->name.'/js/' : _NBSP . '<i>' . JText::_( 'NO TEMPLATE YET' ) . '</i>' ; ?>
				</td>
			</tr>
			<?php if ( @$this->template->id && ( ! $this->doCopy ) ) { ?>
				<?php for ( $j = 0; $j < $nJsFiles; $j++ ) {
					$file 		=& $this->jsFiles[$j];
					$fileName 	= htmlspecialchars( $file, ENT_COMPAT, 'UTF-8' );
					$jsDir 	= substr( strrchr( $this->jsDir, DS ), 1 );
					$fileLink	= JRoute::_( 'index.php?option='.$this->option.'&controller='.$this->controller.'&task=source&cid[]='.$this->template->id.'&dir='.$jsDir.'&file='.$fileName.'&tmpl=component' );
				?>
				<tr class="<?php $k=0; echo 'row' . $k; ?>">
					<td width="145" align="center">
						<?php echo $j + 1; ?>
					</td>
					<td>
						<strong><?php echo _NBSP . $fileName; ?></strong>
					</td>
					<td width="25%" align="center">
						<?php echo is_writable( $this->jsDir.DS.$file ) ? '<font color="green"> '. JText::_( 'Writable' ) .'</font>' : '<font color="red"> '. JText::_( 'Unwritable' ) .'</font>' ?>
					</td>
					<td width="40" align="center">
						<a href="<?php echo $fileLink; ?>" class="modal" rel="<?php echo $rel; ?>">
						<img height="18" width="18" border="0" alt="Edit" src="components/com_cckjseblod/assets/images/list/icon-18-edit.png"/>
						</a>
					</td>
				</tr>
			<?php } } ?>
		</table>
		<?php
		echo $leftpane->endPanel();
		echo $leftpane->startPanel( JText::_( 'LOCATIONS' ).' ( Xml )', 'leftpanel5' );
		?>
		<table class="adminlist">
			<tr>
				<td width="145" align="center">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'LOCATIONS' ).' ( Xml )'; ?>::<?php echo JText::_( 'EDIT LOCATIONS' ); ?>">
						<?php echo _IMG_XML; ?>
					</span>
				</td>
				<td align="left">
					<?php echo ( ! is_null( $this->params ) ) ? _NBSP . '/templates/'.$this->template->name.'/templateDetails.xml' : _NBSP . '<i>' . JText :: _( 'NO TEMPLATE YET' ) . '</i>' ; ?>
				</td>
			</tr>
            <?php if ( @$this->template->id && ( ! $this->doCopy ) ) { ?>
			<tr>
				<td width="145" align="center">
                	<?php echo JText::_( 'ADD OR DEL LOCATION' ); ?>
					<input class="inputbox" type="text" id="new_reserved" name="new_reserved" style="text-transform: uppercase; margin-top: 2px;" maxlength="50" size="24" value="" /><br />
					<input class="button_blank" style="margin-top: 5px;" type="button" id="pushtorightfield" name="pushtorightfield" value="&nbsp;&larr;" onclick="delSelectedFromList('adminForm', 'locations');" />
					<input class="button_blank" style="margin-top: 5px;" type="button" id="pushtorightfield" name="pushtorightfield" value="&nbsp;&rarr;" onclick="addToListAndSelect('adminForm', 'locations');" />
				</td>
				<td align="left">
					<?php echo $this->lists['loc']; ?>
				</td>
			</tr>
            <?php } ?>
		</table>
		<?php
		echo $leftpane->endPanel();
		echo $leftpane->startPanel( JText::_( 'PARAMETERS INI' ), 'leftpanel6' );
		?>
		<table class="adminlist">
			<tr>
				<td width="145" align="center">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'PARAMETERS INI' ); ?>::<?php echo JText::_( 'EDIT PARAMETERS INI' ); ?>">
						<?php echo _IMG_INI; ?>
					</span>
				</td>			
				<td align="left">
					<?php echo ( ! is_null( $this->params ) ) ? _NBSP . '/templates/'.$this->template->name.'/params.ini' : _NBSP . '<i>' . JText :: _( 'NO PARAMETER' ) . '</i>' ; ?>
				</td>
			</tr>
			<?php if ( ! is_null( $this->params ) ) { ?>
			<tr>	
				<td align="center" colspan="2" style="padding-bottom: 5px;">
					<?php echo $this->params->render(); ?>
				</td>
			</tr>
			<?php } ?>
		</table>
		<?php
		echo $leftpane->endPanel();
		echo $leftpane->endPane();
		?>
	</fieldset>
</div>

<div id="as-site-views" class="col width-50 <?php echo ( @$this->template->type || !@$this->template->id ) ? 'display-no' : '' ?>">
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'SITE VIEWS' ); ?></legend>
	<?php
	$tab_state_cookie_name = 'cck_template';
	$tab_state = JRequest::getInt($tab_state_cookie_name, 0, 'cookie');
	$tab_params = array('startOffset'=>$tab_state, 'onActive'=>"function(title, description){ description.setStyle('display', 'block'); title.addClass('open').removeClass('closed'); for (var i = 0, l = this.titles.length; i < l; i++) { if (this.titles[i].id == title.id) Cookie.set('$tab_state_cookie_name', i); } }");
	
	$rightpane =& JPane::getInstance( 'tabs', $tab_params ); 
	echo $rightpane->startPane( 'pane' );
	echo $rightpane->startPanel( _IMG_CATEGORIES .'&nbsp;&nbsp;&nbsp;' . JText::_( 'JOOMLA CATEGORIES' ), 'rightpanel1' );
	?>
	
	<table class="admintable">
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
				<input class="button_jseblod" type="button" id="pushtorightcategory" name="pushtorightcategory" value="&rarr;" onclick="addSelectedToListAndSelect('adminForm','selected_categories','available_categories', '');delSelectedFromList('adminForm','selected_categories');" />
			</td>
			<td class="key_jseblod">
				<input class="button_jseblod" type="button" id="pushtoleftcategory" name="pushtoleftcategory" value="&larr;" onClick="addSelectedToListAndSelect('adminForm','available_categories','selected_categories', '');delSelectedFromList('adminForm','available_categories');" />
				<input class="button_jseblod" type="button"  id="selectallrightcategory" name="selectallrightcategory" value="<?php echo JText::_( 'All' ); ?>" onClick="allSelected(document.adminForm['available_categories']);" />
			</td>
		</tr>
		<tr>
			<td class="key_jseblod">
				<input class="button_jseblod" type="button" value="<?php echo 'A.z'; ?>" onclick="sortSelect(adminForm.selected_categories, true);" />
				<input class="button_jseblod" type="button" value="<?php echo 'Z.a'; ?>" onclick="sortSelect(adminForm.selected_categories, false);" />
			</td>
			<td class="key_jseblod">
				<input class="button_jseblod" type="button" value="<?php echo 'A.z'; ?>" onclick="sortSelect(adminForm.available_categories, true);" />
				<input class="button_jseblod" type="button" value="<?php echo 'Z.a'; ?>" onclick="sortSelect(adminForm.available_categories, false);" />
			</td>
		</tr>
        <tr>
        	<td colspan="2" width="400">
            	<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 8px; margin-bottom: 8px; margin-left: 6px; margin-right: 6px;">
                <table class="admintable">
                    <tr>
                        <td>
                            <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                            <?php echo JText::_( 'DESCRIPTION JOOMLA CATEGORIES' ); ?>
                        </td>
                    </tr>
                </table>
                </span>
            </td>
        </tr>
	</table>

	<?php
		
	echo $rightpane->endPanel();
	echo $rightpane->startPanel( _IMG_MENU .'&nbsp;&nbsp;&nbsp;' . JText::_( 'MENU ITEMS' ), 'rightpanel2' );
	?>
	<table class="admintable">
		<tr>
			<td>
				<input id="menus-all" type="radio" name="menus" value="all" onclick="allselections();" <?php echo ( $this->template->pages == 'all' ) ? 'checked="checked"' : '' ?> /><?php echo JText::_( 'ALL' ); ?>
				<input id="menus-none" type="radio" name="menus" value="none" onclick="disableselections();" <?php echo ( $this->template->pages == 'none' ) ? 'checked="checked"' : '' ?> /><?php echo JText::_( 'None' ); ?>
				<input id="menus-select" type="radio" name="menus" value="select" onclick="enableselections();" <?php echo ( $this->template->pages != 'all' && $this->template->pages != 'none' ) ? 'checked="checked"' : '' ?> /><?php echo JText::_( 'SELECT MENU ITEM FROM LIST' ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo $this->lists['menus']; ?>
			</td>
		</tr>
        <tr>
            <td width="400">
                <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 8px; margin-bottom: 8px; margin-left: 6px; margin-right: 6px;">
                <table class="admintable" >
                    <tr>
                        <td>
                            <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                            <?php echo JText::_( 'DESCRIPTION MENU ITEMS' ); ?>
                        </td>
                    </tr>
                </table>
                </span>
            </td>
	    </tr>
	</table>
	
	<?php
	echo $rightpane->endPanel();
	echo $rightpane->startPanel( _IMG_URL .'&nbsp;&nbsp;&nbsp;' . JText::_( 'SITE URLS' ), 'rightpanel3' );
	?>
	
	<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'ADD URL' ); ?>::<?php echo JText::_( 'CLICK TO ADD AN URL' ); ?>">
					<?php echo JText::_( 'ADD URL' ); ?>:
				</span>
			</td>
			<td width="318">
				<a href="javascript: addOption();"><?php echo _IMG_ADD; ?></a>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div id="options">
					<?php if ( $nUrl ) {
					for ( $i = 0; $i < $nUrl; $i++ ) {
						$row =& $this->urlsItems[$i];
						$j = $i + 1;
						$optionExact = array();
						$optionExact[] = JHTML::_( 'select.option',  '0', JText::_( 'No' ) );
						$optionExact[] = JHTML::_( 'select.option',  '1', JText::_( 'Yes' ) );
						$lists['exact'] = JHTML::_( 'select.genericlist', $optionExact, 'urls_exact[]', 'class="inputbox"', 'value', 'text', $row->exact );
						?>
						<table id="opt-<?php echo $j; ?>">
							<tr>
								<td width="135" align="right" class="keyy_jseblod">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'Title' ); ?>::<?php echo JText::_( 'EDIT TITLE' ); ?>">
										<?php echo JText::_( 'TITLE' ); ?>:
									</span>
								</td>
								<td>
									<input class="inputbox" type="text" id="urls_title" name="urls_title[]" maxlength="50" size="32" value="<?php echo $row->title; ?>" />
									&nbsp;<a href="javascript: removeElement('opt-<?php echo $j; ?>');" onclick=""><?php echo _IMG_DEL; ?></a>
								</td>
							</tr>
							<tr>
								<td width="135" align="right" class="keyy_jseblod">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'EXACT' ); ?>::<?php echo JText::_( 'SELECT EXACT OR NOT' ); ?>">
										<?php echo JText::_( 'EXACT' ); ?>:
									</span>
								</td>
								<td>
									<?php echo $lists['exact']; ?>
								</td>
							</tr>
							<tr>
								<td width="135" align="right" class="keyy_jseblod">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'URL' ); ?>::<?php echo JText::_( 'EDIT URL' ); ?>">
										<?php echo JText::_( 'URL' ); ?>:
									</span>
								</td>
								<td>
									<input class="inputbox" type="text" id="urls_url" name="urls_url[]" maxlength="250" size="60" value="<?php echo $row->url; ?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
								</td>
							</tr>
						</table>
					<?php } } ?>
				</div>
			</td>
		</tr>
		<tr>
        	<td colspan="2" width="400">
                <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 8px; margin-bottom: 8px; margin-left: 6px; margin-right: 6px;">
                <table class="admintable">
                    <tr>
                        <td>
                            <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                            <?php echo JText::_( 'DESCRIPTION SITE URLS' ); ?>
                        </td>
                    </tr>
                </table>
                </span>
            </td>
		</tr>
	</table>
	
	<?php
	echo $rightpane->endPanel();
	echo $rightpane->endPane();
	?>
	
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo ( $this->doCopy ) ? '' : @$this->template->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo @$this->template->id; ?>" />
<input type="hidden" name="install" value="<?php echo ( $this->template->id ) ? '0' : '1'; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>

<script type="text/javascript">
$("tpl_type").addEvent("change", function(t) {
	t = new Event(t).stop();
	
	if ( $("tpl_type").value == 0 ) {
		if ( $("as-site-views").hasClass("display-no") ) {
			$("as-site-views").removeClass("display-no");
		}
	} else {
		if ( ! $("as-site-views").hasClass("display-no") ) {
			$("as-site-views").addClass("display-no");
		}
	}
});
</script>

<?php
HelperjSeblod_Display::quickCopyright();
?>