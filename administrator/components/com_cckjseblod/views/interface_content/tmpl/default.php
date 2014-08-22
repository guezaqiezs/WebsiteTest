<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
jimport( 'joomla.html.pane' );
JHTML::_( 'behavior.modal' );

$this->document->addScript( JURI::root( true ).'/media/system/js/tabs.js' );
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$contentTypeId	=	@$this->contentType->id;
$validationAlert	=	( _VALIDATION_ALERT ) ? JText::_( 'ONE OR MORE FIELDS' ) : '';
$act			=	$this->act;
$formName		=	$this->formName;
?>

<script language="javascript" type="text/javascript">
	/* Put your Javacript Code here Without Dom-Ready! */
	new SmoothScroll({ duration: 1000 });
	
	function submitbutton(pressbutton) {
		var adminFormValidator = new FormValidator( $("adminForm") );
		
		if ( adminFormValidator.validate() ) {
				submitform(pressbutton);			
		} else {
			if ( ! $('validation-alert-elem') ) {				
				var p = $("validation-alert-container");
				var newElement = document.createElement("div");
				var message = document.createTextNode("<?php echo $validationAlert; ?>");					
				newElement.appendChild(message);
				newElement.setAttribute("id", "validation-alert-elem");
				newElement.className = "validation-advice";
				if(p) { p.adopt(newElement); }
			}
		}
	}
</script>

<form enctype="multipart/form-data" action="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>&amp;tmpl=component" method="post" id="adminForm" name="adminForm">

<?php
$fs	=	'index.php?option=com_cckjseblod&controller=interface&artid='.$this->cckId.'&cck=1&act='.$this->act;
if ( $this->cck ) {
	if ( $this->cck == 2 ) {
		$lk	=	"javascript: window.parent.document.getElementById('sbox-window').close();";
	} else {
		$u_lang	=	( $this->lang_id ) ? '&lang='.CCK_LANG_ShortCode( $this->lang_id ) : '';
		$lk2	=	( $act && $act > 0 ) ? (( $act == 1 ) ? 'index.php?option=com_categories&section=com_content' : 'index.php?option=com_users' ) : 'index.php?option=com_content'.$u_lang;
		$lk	=	( $this->brb ) ? ( ( $this->brb == 2 ) ? 'index.php?option=com_cckjseblod' : 'index.php?option=com_cckjseblod&controller=types' ) : $lk2;
	}
	if ( $this->error ) {
		$buttons = array('Cancel'		=> array( 'Close', 'cancel_jseblod', $lk, 'href' ),
						 'Divider'		=> array( 'Divider', 'divider', "#", '#' ),
						 'Selection'	=> array( 'Selection', 'refresh_jseblod', "setSelectionLayout", 'id' ) );
	} else {
		if ( $this->lang_id ) {
			$buttons = array('Save' 		=> array( 'Save', 'save_jseblod', "javascript: submitbutton('save')", 'onclick' ),
							 'Apply' 		=> array( 'Apply', 'apply_jseblod', "javascript: submitbutton('apply')", 'onclick' ),
							 'Spacer'		=> array( 'Spacer', 'spacer', "#", '#' ),
							 'Cancel'		=> array( 'Close', 'cancel_jseblod', $lk, 'href' ),
							 'Divider'		=> array( 'Divider', 'divider', "#", '#' ),
							 'Selection'	=> array( 'Selection', 'refresh_jseblod', "setSelectionLayout", 'id' ) );
		} else {
			$buttons = array('SaveNew' 		=> array( 'Save & New', 'savenew_jseblod', "javascript: submitbutton('savenew')", 'onclick' ),
							 'Save' 		=> array( 'Save', 'save_jseblod', "javascript: submitbutton('save')", 'onclick' ),
							 'Apply' 		=> array( 'Apply', 'apply_jseblod', "javascript: submitbutton('apply')", 'onclick' ),
							 'Spacer'		=> array( 'Spacer', 'spacer', "#", '#' ),
							 'Cancel'		=> array( 'Close', 'cancel_jseblod', $lk, 'href' ),
							 'Divider'		=> array( 'Divider', 'divider', "#", '#' ),
							 'Selection'	=> array( 'Selection', 'refresh_jseblod', "setSelectionLayout", 'id' ) );
		}
	}
	if ( $this->cck == 2 ) {
		$buttons['Fullscr']	=	array( 'Fullscreen', 'redirect_jseblod', 'javascript: parent.location.href=\''.$fs.'\'', 'onclick' );
	}
} else {
	if ( $this->error ) {
		$buttons = array('Cancel'		=> array( 'Close', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ),
						 'Divider'		=> array( 'Divider', 'divider', "#", '#' ),
						 'Selection' 	=> array( 'Selection', 'refresh', "setSelectionLayout", 'id' ) );
	} else {
		$buttons = array('Save' 		=> array( 'Save', 'save_jseblod', "javascript: submitbutton('save')", 'onclick' ),
						 'Apply' 		=> array( 'Apply', 'apply_jseblod', "javascript: submitbutton('apply')", 'onclick' ),
						 'Spacer'		=> array( 'Spacer', 'spacer', "#", "#" ),
						 'Cancel'		=> array( 'Close', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ),
						 'Divider'		=> array( 'Divider', 'divider', "#", '#' ),
						 'Selection' 	=> array( 'Selection', 'refresh_jseblod', "setSelectionLayout", 'id' ) );
	}
	if (  $act != 3 ) {
		$buttons['Fullscr']	=	array( 'Fullscreen', 'redirect_jseblod', 'javascript: parent.location.href=\''.$fs.'\'', 'onclick' );
	}
}
?>

<div id="modal-top">
	<fieldset class="adminform modal-bg-toolbar">
		<div class="header icon-48-interface" style="float: left; color: brown;">
			<?php
			if ( $this->lang_id ) {
				$shortcode	=	CCK_LANG_Shortcode( $this->lang_id );
				$flag		=	'<img src="../components/com_joomfish/images/flags/'.$shortcode.'.gif" alt="'.$shortcode.'"/> ';
			} else {
				$flag		=	'';
			}
            echo JText::_( 'CONTENT' ) . ': '.$flag.'<small><small>[ '.JText::_( 'FILL IN' ).' ]</small></small>';
			?>
		</div>
		<div style="float: right">
			<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
		</div>
	</fieldset>
    <?php if ( $this->actionMode == 0 && CCK_LANG_Enable() && ( ! $this->lang_id || $this->lang_next != '' )) { ?>
    <fieldset class="adminform modal-bg-toolbar">
    <div align="left">
    <?php
	if ( ! $this->lang_id ) {
	$langs	=	CCK_LANG_List();
	if ( sizeof( $langs ) ) {
		$all	=	array();
		$flag	=	array();
		$flags	=	array();
		$k		=	0;
		foreach( $langs as $elem ) {
			if ( $elem->shortcode == CCK_Lang_Default() ) {
				$checked	=	$elem->id;
				$flag[]		=	JHTML::_( 'select.option', $elem->id, '<img src="../components/com_joomfish/images/flags/'.$elem->shortcode.'.gif" alt="'.$elem->shortcode.'"/>' );
			} else {
				$all[]		=	$elem->id;
				$flags[]	=	JHTML::_( 'select.option', $elem->id, '<img src="../components/com_joomfish/images/flags/'.$elem->shortcode.'.gif" alt="'.$elem->shortcode.'"/>' );
			}
			$k++;
		}
		$checkedAll	=	( _BOOL_CHECK ) ? $all : null;
		$flagBar	=	HelperjSeblod_Helper::checkBoxList( $flag, 'jseblod_jfarttranslation[]', 'class="inputbox" disabled="disabled"', 'value', 'text', $checked );
		$flagsBar	=	HelperjSeblod_Helper::checkBoxList( $flags, 'jseblod_jfarttranslations[]', 'class="inputbox" size="1"', 'value', 'text', $checkedAll, 'jseblod_jfarttranslations' );
		echo $flagBar.$flagsBar;
		echo '<a class="minibutton" onclick="javascript: submitbutton(\'savetranslate\')" href="#">'
			.'<img src="components/com_cckjseblod/assets/images/32/icon-32-next_joomfish.png" alt="" align="right" style="padding-left:14px;padding-right:15px;margin-right:1px;"/></a>';
		//echo '<a class="minibutton" onclick="javascript: alert(\'toggle\')" href="#">'
		//	.'<img src="components/com_cckjseblod/assets/images/32/icon-32-toggle_joomfish.png" alt="" align="right" style="padding-left:14px;padding-right:15px;margin-right:1px;"/></a>';
	}
	} else {
		if ( strpos( $this->lang_next, ',' ) !== false ) {
			$i		=	1;
			$langs	=	explode( ',', $this->lang_next );
			if ( sizeof( $langs ) ) {
				foreach( $langs as $elem ) {
					echo '<input type="hidden" id="jseblod_jfarttranslations'.$i.'" name="jseblod_jfarttranslations[]" value="'.$elem.'">';
					$i++;
				}
			}
		} else {
			if ( $this->lang_next ) {
				echo '<input type="hidden" name="jseblod_jfarttranslations" value="'.$this->lang_next.'">';
			}
		}
		echo '<span style="color: #bbb; line-height: 20px;"><b>'.JText::_( 'CLICK ON NEXT TO CONTINUE TRANSLATIONS' ).'</b></span>';
		echo '<a class="minibutton" onclick="javascript: submitbutton(\'savetranslate\')" href="#">'
			.'<img src="components/com_cckjseblod/assets/images/32/icon-32-next_joomfish.png" alt="" align="right" style="padding-left:14px;padding-right:15px;margin-right:1px;"/></a>';
	}
    ?>
    </div>
    </fieldset>
    <?php } ?>
	<?php
	if ( ! $this->error ) {
	
		echo $this->data;
		echo ( $this->formHidden ) ? $this->formHidden : '';
		
		?>
        <div style="float: left; color: grey; cursor: pointer; padding-bottom:6px;">
            <span class="DescriptionTip" title="AJAX:index.php?option=com_cckjseblod&amp;controller=modal_tooltip&amp;cid[]=<?php echo $contentTypeId; ?>&amp;tmpl=component&amp;from=types&amp;into=description&amp;legend=Description">
	        <?php echo '&nbsp;&nbsp;&nbsp;' . JText::_( 'CONTENT TYPE INFO' ); ?>
			</span>
        </div>
        <?php
		
		if ( ! $this->cck || $this->cck == 2 ) {
			HelperjSeblod_Display::quickBackToTopModal();
		} else {
			HelperjSeblod_Display::quickBackToTop();
		}
		
	} else {
		if ( JFile::exists( dirname(__FILE__) .DS. 'default_error_' . $this->error . '.php' ) ) {
			echo $this->loadTemplate( 'error_'.$this->error );
		}
	}
	?>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="cid[]" value="<?php echo @$this->cckId; ?>" />
<input type="hidden" name="id" value="<?php echo @$this->cckId; ?>" />
<input type="hidden" name="cck" value="<?php echo $this->cck; ?>" />
<input type="hidden" name="brb" value="<?php echo $this->brb; ?>" />
<input type="hidden" name="act" value="<?php echo $this->act; ?>" />
<input type="hidden" name="cat_id" value="<?php echo $this->cat_id; ?>" />
<input type="hidden" name="u_opt" value="<?php echo $this->u_opt; ?>" />
<input type="hidden" name="u_task" value="<?php echo $this->u_task; ?>" />
<input type="hidden" name="lang_id" value="<?php echo $this->lang_id; ?>" />
<input type="hidden" name="contenttype" value="<?php echo @$this->contentType->id; ?>">
<input type="hidden" name="actionmode" value="<?php echo $this->actionMode; ?>" />
<?php if ( $this->act == 4 ) { ?>
<input type="hidden" name="userid" value="<?php echo @$this->userid; ?>">
<?php } ?>
<?php echo JHTML::_('form.token'); ?>
<?php echo '</form>'; ?>
<?php
HelperjSeblod_Display::quickCopyright();
?><?php if ( ! $this->cck ) { echo '<br />'; } ?>