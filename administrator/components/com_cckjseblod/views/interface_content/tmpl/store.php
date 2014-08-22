<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$u_opt			=	$this->u_opt;
$u_task			=	$this->u_task;
$task			=	$this->task;
$content		=	$this->content->text;
$common_key		=	$this->content->common_key;
$common_val		=	$this->content->common_val;
if ( $this->actionMode != 1 ) {
	$details_key	=	$this->content->details_key;
	$details_val	=	$this->content->details_val;
	$params_key		=	$this->content->params_key;
	$params_val		=	$this->content->params_val;
	$meta_key		=	$this->content->meta_key;
	$meta_val		=	$this->content->meta_val;
}

$wysiwyg_editor	=	_WYSIWYG_EDITOR;
$javascript = '
		window.addEvent("domready", function(){
			var actM = "'.$this->actionMode.'";
			 
			commonStore();
			if ( actM != 1 ) {
				detailsStore();
				paramsStore();
				metaStore();
			}
			
			var task = "'.$task.'";
			
			var url_option = "'.$u_opt.'";
			if (url_option && url_option == "com_joomfish") {
				task = "translate."+task;
			} else 	if (url_option && url_option == "com_jevents") {
		        var url_task = "'.$u_task.'";
				if ( url_task == "categories.edit" ) {
					task = "categories.save";	
				} else if ( url_task == "icalevent.edit" ) {
					task = "icalevent."+task;
				} else {}
			} else 	if (url_option && url_option == "com_acajoom") {
				task = "update";
			} else {}
			
			var content = $("store-it").value;
			var editor = getCurrentEditor();
			
			var wysiwyg = "'.$wysiwyg_editor.'";
			switch( editor ) {
				case "ck":
					/* TODO: Get Active CKEditor !!?? */
					if ( parent.CKEDITOR.instances["text"] ) {
						parent.CKEDITOR.instances["text"].setData( content );
					} else if ( parent.CKEDITOR.instances["description"] ) {
						parent.CKEDITOR.instances["description"].setData( content );
					} else {
						/* TODO: Get Active CKEditor !!?? */
						parent.jInsertEditorText( content );
					}
					break;
				case "fck":
					parent.FCKeditorAPI.GetInstance("text").SetHTML( content );
					break;
				case "jce":
					parent.tinyMCE.activeEditor.setContent( content );
					parent.submitbutton(task);
					break;
				case "tiny":
					if ( wysiwyg == 1 ) {
						parent.tinyMCE.setContent( content );
						parent.submitbutton(task);
					} else {
						parent.tinyMCE.activeEditor.setContent( content );
						parent.submitbutton(task);
					}
					break;
				default:
					alert( "Wysiwyg Editor Not Supported. Please Contact Us." )
					break;
			}
			
			window.parent.document.getElementById("sbox-window").close();
			
		});
		
		var getCurrentEditor = function() {
			if (parent.JContentEditor) {
				editor = "jce";
	        } else if (parent.CKEDITOR) { 
    	        editor = "ck"; 
			} else if (parent.FCKeditorAPI) {
				editor = "fck";
			} else if (parent.tinyMCE) {
				editor = "tiny";
			} else {
				editor = null;
			}
			return editor;
		}
		
		function commonStore() {
			var t_common_key = new Array();
			var t_common_key = "'.$common_key.'";
			var t_common_key = t_common_key.split("::");
			var t_common_val = new Array();
			var t_common_val = "'.$common_val.'";
			var t_common_val = t_common_val.split("::");
			
			var common_num = t_common_key.length;
			for (i=0; i<common_num; i++) {
				key = t_common_key[i];
				val = t_common_val[i];
				if ( key == "state" || key == "frontpage" )  {
					var radiokey = key+val;
					if ( parent.$(radiokey) ) {
						parent.$(radiokey).checked = true;
					}
				} else {
					if ( parent.$(key) ) {
						parent.$(key).value = val;
					}
					if (key == "sectionid" && parent.$("catid")) {
						changeParentDynaList( "catid", parent.sectioncategories, parent.document.adminForm.sectionid.options[parent.document.adminForm.sectionid.selectedIndex].value, 0, 0);
					}
				}
			}
		}
		
		function detailsStore() {
			var t_details_key = new Array();
			var t_details_key = "'.@$details_key.'";
			var t_details_key = t_details_key.split("::");
			var t_details_val = new Array();
			var t_details_val = "'.@$details_val.'";
			var t_details_val = t_details_val.split("::");
			
			var details_num = t_details_key.length;
			for (i=0; i<details_num; i++) {
				key = "details"+t_details_key[i];
				val = t_details_val[i];
				if ( parent.$(key) ) {
					parent.$(key).value = val;
				}
			}
		}
		
		function paramsStore() {
			var t_params_key = new Array();
			var t_params_key = "'.@$params_key.'";
			var t_params_key = t_params_key.split("::");
			var t_params_val = new Array();
			var t_params_val = "'.@$params_val.'";
			var t_params_val = t_params_val.split("::");
			
			var params_num = t_params_key.length;
			for (i=0; i<params_num; i++) {
				key = "params"+t_params_key[i];
				val = t_params_val[i];
				if ( parent.$(key) ) {
					parent.$(key).value = val;
				}
			}
		}
		
		function metaStore() {
			var t_meta_key = new Array();
			var t_meta_key = "'.@$meta_key.'";
			var t_meta_key = t_meta_key.split("::");
			var t_meta_val = new Array();
			var t_meta_val = "'.@$meta_val.'";
			var t_meta_val = t_meta_val.split("::");
			
			var meta_num = t_meta_key.length;
			for (i=0; i<meta_num; i++) {
				pre = t_meta_key[i].substr(5);
				if ( pre == "desc" ) {
					pre = "description";
				} else if ( pre == "key" ) {
					pre = "keywords";
				} else {}
				key = "meta"+pre;
				val = t_meta_val[i];
				if ( parent.$(key) ) {
					parent.$(key).value = val;
				}
			}
		}
		
		function changeParentDynaList( listname, source, key, orig_key, orig_val ) {
			var list = eval( "parent.document.adminForm." + listname );
			
			// empty the list
			for (n in list.options.length) {
				list.options[n] = null;
			}
			n = 0;
			for (x in source) {
				if (source[x][0] == key) {
					opt = new Option();
					opt.value = source[x][1];
					opt.text = source[x][2];
					if ((orig_key == key && orig_val == opt.value) || n == 0) {
						opt.selected = true;
					}
					list.options[n++] = opt;
				}
			}
			list.length = n;
		}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<?php
$buttons = array( 'Cancel'		=> array( 'Close', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
?>

<div>
	<fieldset class="adminform modal-bg-toolbar">
		<div class="header icon-48-interface" style="float: left; color: brown;">
			<?php echo JText::_( 'CONTENT INTERFACE' ) . ': <small><small>[ '.JText::_( 'Save' ).' ]</small></small>'; ?>
		</div>
		<div style="float: right">
			<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
		</div>
	</fieldset>

	<fieldset class="adminform">	
	<legend class="legend-border"><?php echo $this->contentType->title; ?></legend>
   		<div class="message-notice-jseblod">
			<?php echo JText::_( 'ARTICLE MUST HAVE A TITLE AND A CATEGORY' ); ?>
		</div>
		<div class="message-message-jseblod">
			<?php echo JText::_( 'CONTENT ADDED' ); ?>
		</div>
	</fieldset>
</div>

<div class="display-no">
   	<textarea id="store-it" name="store-it" style="display: none;"><?php echo $content; ?></textarea>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>
<?php
HelperjSeblod_Display::quickCopyright();
?><br />