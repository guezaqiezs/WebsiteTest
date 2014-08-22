<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.modal' );
JHTML::_( 'behavior.mootools' );
$user	=&	JFactory::getUser();
if ( $this->mode ) {
	 echo '<script type="text/javascript" src="'.JURI::root().'/plugins/editors/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>';
	 echo HelperjSeblod_Display::quickWysiwyg( $this->e_editor );
} else {
	$editor	=& JFactory::getEditor( $this->e_editor );
}

$this->document->addStyleSheet( 'administrator/templates/khepri/css/icon.css' );
$this->document->addStyleSheet( 'administrator/components/com_cckjseblod/assets/css/icon.css' );
$this->document->addStyleSheet( 'templates/system/css/general.css' );

$buttons = array('Apply'	=> array( 'Apply', 'apply_jseblod', "javascript: applyWysiwyg();", 'onclick' ),
				 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
				 
$into		= $this->into;
$boolId		= $this->boolId;
$error		= JText::_( 'WYSIWYG EDITOR NOT SUPPORTED' );

$javascript ='
	window.addEvent("domready",function(){
		var into = "'.$into.'";
		var into_updated = into+"_updated";
		var updated = parent.$(into_updated).value;
		if ( updated == 1 ) {
			var content = parent.$(into).value;
			$(into).value = content;
		}
	});
	
	var getCurrentEditor = function() {
		if (this.JContentEditor) {
			editor = "jce";
        } else if (this.CKEDITOR) { 
            editor = "ck"; 
		} else if (this.FCKeditorAPI) {
			editor = "fck";
		} else if (this.tinyMCE) {
			editor = "tiny";
		} else {
			editor = null;
		}
		return editor;
	}
	
	var mode = "'.$this->mode.'";
	var applyWysiwyg = function() {
		var editor = getCurrentEditor();
		var into = "'.$into.'";
		var into_updated = into+"_updated";
		var error = "'.$error.'";
		var content = null;
		
		switch( editor )
		{
        case "ck": 
            content = this.CKEDITOR.instances[into].getData();
            break;
		case "fck":
			content = this.FCKeditorAPI.GetInstance(into).GetHTML()
			break;
		case "jce":
			content = this.JContentEditor.getContent(into);
			break;
		case "tiny":
			if ( mode ) {
				content = this.tinyMCE.activeEditor.getContent();
			} else {
				content = this.tinyMCE.activeEditor.getContent();
			}
			break;
		default:
			content = $(into).value;
			break;
		}
		
		parent.document.getElementById(into).value = content;
		var into_hidden = into+"_hidden";
		if ( parent.document.getElementById(into_hidden) ) {
			parent.document.getElementById(into_hidden).value = content;
		}
		parent.document.getElementById(into_updated).value = "1";
		var into_required = into+"_required";
		if ( parent.document.getElementById(into_required) ) {
			if ( content != "" ) {
				parent.document.getElementById(into_required).value = " ";
			} else {
				parent.document.getElementById(into_required).value = "";
			}
		}
		window.parent.document.getElementById("sbox-window").close();	
	}
	';
$this->document->addScriptDeclaration( $javascript );

?>
<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform reducepadding modal-bg-toolbar">
	<div class="header icon-48-mediamanager" style="float: left">
		<?php echo JText::_( 'WYSIWYG EDITOR' ); ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	<?php if ( ! $user->get('id') ) { ?>
    	<fieldset class="adminform_error">
	<table class="admintable" align="left">
		<tr>
			<td align="left" style="text-indent:30px;" >
				<?php echo JText::_( 'ALERT VIEW NOT AUTH LOGIN' ); ?>
			</td>
		</tr>
	</table>
	</fieldset>
	<?php } else { ?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>
		<table class="admintable" align="center" width="100%">
			<tr>
				<td valign="top" align="center" width="100%">
				<?php
                if ( $boolId != -1 ) {
					
                    if ( $this->mode ) {
						echo '<textarea class="mce_editable" id="'.$into.'" name="'.$into.'" cols="60" rows="20" style="width:100%; height:280">'.$this->wysiwyg.'</textarea>';
                    } else {
				        echo $editor->display( $into, $this->wysiwyg, '100%', '280', '60', '20', array('pagebreak', 'readmore', 'cckjseblod') );
					}
					
        		} else {
        
                    if ( $this->mode ) {
						echo '<textarea class="mce_editable" id="'.$into.'" name="'.$into.'" cols="60" rows="20" style="width:100%; height:280"></textarea>';
                    } else { 
                        echo $editor->display( $into, '', '100%', '280', '60', '20', array('pagebreak', 'readmore', 'cckjseblod') );
					}
        
 				} ?>        
				</td>
			</tr>
		</table>
	</fieldset>
	<?php } ?>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="cid[]" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>