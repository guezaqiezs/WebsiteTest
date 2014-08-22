<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optStyle[] 		=	JHTML::_( 'select.option', '', JText::_( 'DEFAULT' ) );
$optStyle[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'ITEMS' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'calendar', JText::_( 'CALENDAR' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'checkbox', JText::_( 'CHECKBOX' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'select_simple', JText::_( 'SELECT SIMPLE DROPDOWN' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'radio', JText::_( 'RADIO' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'save', JText::_( 'SAVE JOOMLA CATEGORIES' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'text', JText::_( 'TEXT' ) );
$optStyle[]			=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
$optStyle[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'SEARCH TYPES CONFIG' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'search_stage', JText::_( 'STAGE RESULTS ARTICLE' ) );
$optStyle[]			=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
$selectStyle		=	( ! @$this->isNew ) ? @$this->item->stylextd : '';
$lists['style']	=	JHTML::_( 'select.genericlist', $optStyle, 'stylextd', 'size="1" class="inputbox"', 'value', 'text', $selectStyle );


$optParam[] 		=	JHTML::_( 'select.option', 0, JText::_( 'INHERIT' ) );
if ( ! @$this->item->boolxtd || @$this->doCopy ) {
	$optParam[] 	=	JHTML::_( 'select.option', 2, JText::_( 'CUSTOM' ) );
} else {
	$optParam[] 	=	JHTML::_( 'select.option', 1, JText::_( 'CUSTOM' ) );
	$optParam[] 	=	JHTML::_( 'select.option', 2, JText::_( 'CUSTOM RESET' ) );
}
if ( @$this->doCopy ) {
	$selectParam		=	( @$this->item->boolxtd == 1 ) ? 2 : 0;
} else {
	$selectParam		=	( ! @$this->isNew ) ? @$this->item->boolxtd : 0;
}
$lists['param']		=	JHTML::_( 'select.genericlist', $optParam, 'boolxtd', 'size="1" class="inputbox"', 'value', 'text', $selectParam );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ALIAS CUSTOM' ); ?>::<?php echo JText::_( 'DESCRIPTION ALIAS CUSTOM' ); ?>">
		<?php echo JText::_( 'ALIAS CUSTOM' ); ?>
    </span>
</legend>
	<table class="admintable">
		<tr>
        	<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DESCRIPTION LIGHT BULB' ); ?>::<?php echo JText::_( 'CHOOSE DISPLAY DESCRIPTION LIGHT BULB OR NOT' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>:
				</span>
			</td>
			<td colspan="2">
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
			<td colspan="2">
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CONTENT ITEM' ); ?>::<?php echo JText::_( 'CONTENT ITEM BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CONTENT ITEM' ); ?>::<?php echo JText::_( 'SELECT CONTENT ITEM' ); ?>">
					<?php echo JText::_( 'CONTENT ITEM' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-disabled" type="text" id="extended_title" name="extended_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$this->item->extended ) ? $this->item->extendedTitle : ''; ?>" />
				<input type="hidden" id="extended" name="extended" value="<?php echo @$this->item->extended; ?>" />
                <input type="hidden" id="extended_id" name="extended_id" value="<?php echo @$this->item->extendedId; ?>" />
			</td>
			<td>
				<?php echo $this->modals['selectItem']; ?>
                <?php if ( ! @$this->isNew ) { ?>
                    <span style="float: left;">&nbsp;&nbsp;&nbsp;</span>
                    <div class="button2-left">
                        <div class="blank">
                            <a onclick="javascript: fieldExtended('extended_id');" alt="Extended"><?php echo JText::_( 'EDIT' ); ?></a>
                        </div>
                    </div>
                <?php } ?>
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'STYLE VARIATION' ); ?>::<?php echo JText::_( 'STYLE VARIATION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'STYLE VARIATION' ); ?>::<?php echo JText::_( 'SELECT STYLE VARIATION' ); ?>">
					<?php echo JText::_( 'STYLE VARIATION' ); ?>:
				</span>
			</td>
			<td colspan="2">
				<?php echo $lists['style']; ?>                
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PARAM VARIATION' ); ?>::<?php echo JText::_( 'PARAM VARIATION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PARAM VARIATION' ); ?>::<?php echo JText::_( 'SELECT PARAM VARIATION' ); ?>">
					<?php echo JText::_( 'PARAM VARIATION' ); ?>:
				</span>
			</td>
			<td colspan="2">
				<?php echo $lists['param']; ?>             
			</td>
		</tr>
	</table>
</fieldset>

<?php
if ( @$this->item->boolxtd ) {
	$tmpl	=	( $this->item->stylextd ) ? $this->item->stylextd : CCK_DB_Result( 'SELECT cc.name FROM #__jseblod_cck_items AS s LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type WHERE s.id = '.(int)$this->item->extendedId );
	if ( JFile::exists( dirname(__FILE__).DS.'form_'.$tmpl.'.php' ) ) {
		include_once( dirname(__FILE__).DS.'form_'.$tmpl.'.php' );
	}
}
?>

<input type="hidden" name="type" value="alias_custom" />