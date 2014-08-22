<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$cck			=	$this->cck;
$brb			=	$this->brb;
$act			=	$this->act;
$u_opt    = $this->u_opt;
$u_task    = $this->u_task;
$lang_id  = $this->lang_id;
?>

<?php
if ( $this->cck ) {
	if ( $this->cck == 2 ) {
		$lk		=	"javascript: window.parent.document.getElementById('sbox-window').close();";
		$action	=	'index.php?option='.$this->option.'&amp;controller='.$this->controller.'&amp;task=importXml&amp;tmpl=component';
	} else {
		$u_lang	=	( $this->lang_id ) ? '&lang='.CCK_LANG_ShortCode( $this->lang_id ) : '';
		$lk2	=	( $act && $act > 0 ) ? (( $act == 1 ) ? 'index.php?option=com_categories&section=com_content' : 'index.php?option=com_users' ) : 'index.php?option=com_content'.$u_lang;
		$lk		=	( $this->brb ) ? ( ( $this->brb == 2 ) ? 'index.php?option=com_cckjseblod' : 'index.php?option=com_cckjseblod&controller=types' ) : $lk2;
		$action	=	'index.php?option='.$this->option.'&amp;controller='.$this->controller.'&amp;task=importXml';
	}
	$buttons = array('Import' 		=> array( 'Import', 'import_jseblod', "javascript: submitbutton('importXml')", 'onclick' ),
					 'Spacer'		=> array( 'Spacer', 'spacer', "#", "#" ),
					 'Cancel'		=> array( 'Close', 'cancel_jseblod', $lk, 'href' ),
					 'Divider'		=> array( 'Divider', 'divider', "#", '#' ),
					 'Selection' 	=> array( 'Selection', 'refresh_jseblod', "setSelectionLayout", 'id' ) );
} else {
	$action		= 'index.php?option='.$this->option.'&amp;controller='.$this->controller.'&amp;task=importXml&amp;tmpl=component';
	$buttons = array('Import' 		=> array( 'Import', 'import_jseblod', "javascript: submitbutton('importXml')", 'onclick' ),
				 'Spacer'		=> array( 'Spacer', 'spacer', "#", "#" ),
				 'Cancel'		=> array( 'Close', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ),
				 'Divider'		=> array( 'Divider', 'divider', "#", '#' ),
				 'Selection' 	=> array( 'Selection', 'refresh_jseblod', "setSelectionLayout", 'id' ) );
}
?>

<form enctype="multipart/form-data" action="<?php echo $action; ?>" method="post" id="adminForm" name="adminForm">

<div id="modal-top">
	<fieldset class="adminform modal-bg-toolbar">
		<div class="header icon-48-interface" style="float: left; color: brown;">
			<?php echo JText::_( 'CONTENT MANAGER' ) . ': <small><small>[ '.JText::_( 'IMPORT CONTENT PACK' ).' ]</small></small>'; ?>
		</div>
		<div style="float: right">
			<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
		</div>
	</fieldset>
	<fieldset class="adminform">
    <legend class="legend-border"><?php echo JText::_( 'CONTENT PACKS IMPORT' ); ?></legend>
    <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-bottom: 10px;">
    <table class="admintable" >
        <tr>
            <td>
                <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                <?php echo JText::_( 'DESCRIPTION IMPORT CONTENT PACKS' ); ?>
            </td>
        </tr>
    </table>
    </span>
    <table class="admintable">
        <tr>
            <td width="25" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'IMPORT' ); ?>::<?php echo JText::_( 'SELECT IMPORT MODE' ); ?>">
                    <?php echo JText::_( 'IMPORT' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['import_selection']; ?>
            </td>
        </tr>
        <tr>
            <td width="25" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'IMPORT MODE' ); ?>::<?php echo JText::_( 'SELECT IMPORT MODE' ); ?>">
                    <?php echo JText::_( 'IMPORT MODE' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['import_mode']; ?>
            </td>
        </tr>
        <tr>
        	<td colspan="3">
            </td>
        </tr>
        <tr>
            <td width="25" class="key_jseblod">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'IMPORT PACK' ); ?>::<?php echo JText::_( 'IMPORT CONTENT PACK BALLOON' ); ?>">
                    <?php echo _IMG_IMPORT; ?>
                </span>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT PACK' ); ?>::<?php echo JText::_( 'BROWSE CONTENT PACK' ); ?>">
                    <?php echo JText::_( 'CONTENT PACK' ); ?>:
                </span>
            </td>
            <td>
                <input class="input_box" type="file" id="import_pack" name="import_pack" size="32" />
            </td>
        </tr>
    </table>
	</fieldset>
    	
	<?php if ( $this->cck ) { ?>
	<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 6px; text-align:center; margin:0 10px 10px;">
		<?php echo JText::_( 'CONTENT EDITION KIT FULLSCREEN FOR ARTICLES ONLY' ); ?>
    </span>
    <?php } ?>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="cid[]" value="<?php echo @$this->artId; ?>" />
<input type="hidden" name="id" value="<?php echo @$this->artId; ?>" />
<input type="hidden" name="cck" value="<?php echo $this->cck; ?>" />
<input type="hidden" name="brb" value="<?php echo $this->brb; ?>" />
<input type="hidden" name="act" value="<?php echo $this->act; ?>" />
<input type="hidden" name="cat_id" value="<?php echo $this->cat_id; ?>" />
<input type="hidden" name="u_opt" value="<?php echo $this->u_opt; ?>" />
<input type="hidden" name="u_task" value="<?php echo $this->u_task; ?>" />
<input type="hidden" name="lang_id" value="<?php echo $this->lang_id; ?>" />
<input type="hidden" name="contenttype" value="<?php echo @$this->contentType->id; ?>">
<?php echo JHTML::_('form.token'); ?>
<?php echo '</form>'; ?>
<?php
HelperjSeblod_Display::quickCopyright();
?><br />