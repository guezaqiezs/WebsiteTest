<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optLocation		=	array();
$optLocation[]	 	=	JHTML::_( 'select.option', 0, JText::_( 'BOX' ) );
$optLocation[] 		=	JHTML::_( 'select.option', 1, JText::_( 'DEFAULT ON FORM' ) );
$selectLocation		=	( ! $this->isNew ) ? $this->item->bool : 0;
$lists['location'] 	=	JHTML::_( 'select.radiolist', $optLocation, 'bool', 'size="1" class="inputbox required required-enabled"', 'value', 'text', $selectLocation );

$modals['defaultvalue']			=	HelperjSeblod_Display::quickModalWysiwyg( 'EDITOR', $this->controller, 'defaultvalue', 'pagebreak', 0, @$this->item->id, false );
$tooltips['link_defaultvalue']	=	HelperjSeblod_Display::quickTooltipAjaxLink( JText::_( 'VALUE' ), $this->controller, 'defaultvalue', @$this->item->id );

// Set Delete Mode
$optEditor		=	array();
$optEditor[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'JOOMLA DEFAULT EDITOR' ) );
$optEditor[] 	=	JHTML::_( 'select.option', '', JText::_('JOOMLA DEFAULT EDITOR') );
$optEditor[] 	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
$optEditor[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'OTHER JOOMLA EDITORS' ) );
$joomlaEditors	=	HelperjSeblod_Helper::getJoomlaEditors();
$optEditor		=	array_merge( $optEditor, $joomlaEditors );
$optEditor[] 	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
$optEditor[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'PRESET TINYMCE EDITORS' ) );
$optEditor[] 	=	JHTML::_( 'select.option', 'tinypreset_simple', 'TinyMCE 3 (Simple)' );
$optEditor[] 	=	JHTML::_( 'select.option', 'tinypreset_advanced', 'TinyMCE 3 (Advanced)' );
$optEditor[] 	=	JHTML::_( 'select.option', 'tinypreset_extended', 'TinyMCE 3 (Extended)' );
//$optEditor[] 	=	JHTML::_( 'select.option', 'tinypreset_extended', 'TinyMCE 3 (Extended) + TinyBrowser' );
$optEditor[] 	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
$selectEditor		=	( ! $this->isNew ) ? $this->item->format : '';
$lists['editor']	=	JHTML::_( 'select.genericlist', $optEditor, 'format', 'size="1" class="inputbox"', 'value', 'text', $selectEditor );

$optImporterMode		=	array();
$optImporterMode[] 		=	JHTML::_( 'select.option', 0, JText::_( 'NONE' ) );
//$optImporterMode[] 		=	JHTML::_( 'select.option', 1, JText::_( 'AS DESCRIPTION' ) );
$optImporterMode[] 		=	JHTML::_( 'select.option', 1, JText::_( 'AS INTROTEXT' ) );
$optImporterMode[] 		=	JHTML::_( 'select.option', 2, JText::_( 'AS FULLTEXT OR DESCRIPTION' ) );
$selectImporterMode		=	( ! $this->isNew ) ? $this->item->importer : 0;
$lists['importerMode'] 	=	JHTML::_( 'select.genericlist', $optImporterMode, 'importer', 'size="1" class="inputbox"', 'value', 'text', $selectImporterMode );
?>

<fieldset class="adminform">
<legend class="legend-border">
   	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WYSIWYG EDITOR' ); ?>::<?php echo JText::_( 'DESCRIPTION WYSIWYG EDITOR' ); ?>">
		<?php echo JText::_( 'WYSIWYG EDITOR' ); ?>
    </span>
</legend>
	<table class="admintable">
   		<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DESCRIPTION LIGHT BULB' ); ?>::<?php echo JText::_( 'CHOOSE DISPLAY DESCRIPTION LIGHT BULB OR NOT' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['light']; ?>
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'LABEL' ); ?>::<?php echo JText::_( 'LABEL BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'LABEL' ); ?>::<?php echo JText::_( 'EDIT LABEL' ); ?>">
					<?php echo JText::_( 'LABEL' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="label" name="label" maxlength="50" size="32" value="<?php echo @$this->item->label; ?>" />
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY LABEL' ); ?>::<?php echo JText::_( 'DISPLAY LABEL BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY LABEL' ); ?>::<?php echo JText::_( 'SELECT DISPLAY LABEL MODE' ); ?>">
					<?php echo JText::_( 'DISPLAY LABEL' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['display']; ?>
			</td>
		</tr>
   	    <?php } ?>
   	</table>
	<table class="admintable header_jseblod">
		<tr>
			<td>
				<?php echo JText::_( 'NOTE FORM').' :: '.JText::_( 'CONSTRUCTION' ); ?>
			</td>
		</tr>
	</table>
	<table class="admintable">
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'Required' ); ?>::<?php echo JText::_( 'CHOOSE REQUIRED OR NOT' ); ?>">
					<?php echo JText::_( 'Required' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['required']; ?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key"> 
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'EDITOR' ); ?>::<?php echo JText::_( 'CHOOSE EDITOR' ); ?>">
					<?php echo JText::_( 'EDITOR' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['editor']; ?>
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key"> 
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEFAULT  VALUE' ); ?>::<?php echo JText::_( 'EDIT DEFAULT VALUE' ); ?>">
					<?php echo JText::_( 'DEFAULT VALUE' ); ?>:
				</span>
			</td>
			<td>
				<span class="ajaxTip2" title="<?php echo $tooltips['link_defaultvalue']; ?>">
					<?php echo $modals['defaultvalue']; ?>
				</span>
			</td>
		</tr>
        <tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
            <?php echo JText::_( 'IMPORTER DESC TEXT'); ?>
			</td>
        </tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'IMPORTER MODE' ); ?>::<?php echo JText::_( 'IMPORTER MODE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'IMPORTER MODE' ); ?>::<?php echo JText::_( 'SELECT IMPORTER MODE' ); ?>">
					<?php echo JText::_( 'IMPORTER MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['importerMode']; ?>
			</td>
		</tr>
   	</table>
	<table class="admintable header_jseblod">
		<tr>
			<td>
				<?php echo JText::_( 'NOTE FORM').' :: '.JText::_( 'STYLE' ); ?>
			</td>
		</tr>
	</table>
	<table class="admintable">
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'HEIGHT' ); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
					<?php echo JText::_( 'HEIGHT' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number required required-enabled" type="text" id="height" name="height" maxlength="50" size="16" value="<?php echo ( @$this->item->height ) ? $this->item->height : 200; ?>" />
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key"> 
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'POSITION' ); ?>::<?php echo JText::_( 'CHOOSE POSITION' ); ?>">
					<?php echo JText::_( 'POSITION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['location']; ?>
			</td>
		</tr>
        <tr>
			<td colspan="3">
				<?php echo JText::_( 'DEFAULT ON FORM EXPLANATION' ); ?>
			</td>
		</tr>
	</table>
   	<table class="admintable header_jseblod" >
		<tr>
			<td>
				<?php echo JText::_( 'NOTE CONTENT').' :: '.JText::_( 'DISPLAY' ); ?>
			</td>
		</tr>
	</table>
    <table class="admintable">
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'GROUP ACCESS' ); ?>::<?php echo JText::_( 'GROUP ACCESS BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'GROUP ACCESS' ); ?>::<?php echo JText::_( 'SELECT GROUP ACCESS' ); ?>">
					<?php echo JText::_( 'GROUP ACCESS' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['gACL']; ?>
			</td>
		</tr>
    </table>
</fieldset>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="wysiwyg_editor" />
<?php } ?>