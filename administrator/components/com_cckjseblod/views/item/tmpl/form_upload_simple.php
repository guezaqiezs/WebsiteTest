<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$lists['displayPath']	= JHTML::_('select.booleanlist', 'bool2', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool2 : 0 );

$lists['userLocation']	= JHTML::_('select.booleanlist', 'bool3', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool3 : 0 );
$lists['oneLocation']	= JHTML::_('select.booleanlist', 'bool4', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool4 : 1 );

$optPreview 		=	array();
$optPreview[] 		=	JHTML::_( 'select.option', 0, JText::_( 'TITLE' ) );
$optPreview[] 		=	JHTML::_( 'select.option', 1, JText::_( 'ICON' ) );
$optPreview[] 		=	JHTML::_( 'select.option', -1, JText::_( 'NONE' ) );
$selectPreview		=	( ! $this->isNew ) ? @$this->item->bool6 : 0;
$lists['preview']	=	JHTML::_( 'select.genericlist', $optPreview, 'bool6', 'size="1" class="inputbox"', 'value', 'text', $selectPreview );

$lists['deleteBox']	=	JHTML::_('select.booleanlist', 'bool7', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool7 : 1 );
?>

<fieldset class="adminform">
<legend class="legend-border">
  	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'UPLOAD SIMPLE' ); ?>::<?php echo JText::_( 'DESCRIPTION UPLOAD SIMPLE' ); ?>">
		<?php echo JText::_( 'UPLOAD SIMPLE' ); ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FOLDER' ); ?>::<?php echo JText::_( 'FOLDER BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FOLDER' ); ?>::<?php echo JText::_( 'EDIT FOLDER' ); ?>">
					<?php echo JText::_( 'FOLDER' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="location" name="location" maxlength="250" size="32" value="<?php echo @$this->item->location; ?>" />
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'USER SPECIFIC FOLDER' ); ?>::<?php echo JText::_( 'CHOOSE ENABLE USER SPECIFIC FOLDER OR NOT' ); ?>">
					<?php echo JText::_( 'USER SPECIFIC FOLDER' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['userLocation']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CONTENT SPECIFIC FOLDER' ); ?>::<?php echo JText::_( 'CHOOSE ENABLE CONTENT SPECIFIC FOLDER OR NOT' ); ?>">
					<?php echo JText::_( 'CONTENT SPECIFIC FOLDER' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['oneLocation']; ?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'LEGAL EXTENSIONS' ); ?>::<?php echo JText::_( 'LEGAL EXTENSIONS BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'LEGAL EXTENSIONS' ); ?>::<?php echo JText::_( 'EDIT LEGAL EXTENSIONS' ); ?>">
					<?php echo JText::_( 'LEGAL EXTENSIONS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="options2" name="options2" maxlength="250" size="32" value="<?php echo @$this->item->options2; ?>" />
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MAX FILESIZE' ); ?>::<?php echo JText::_( 'MAX FILESIZE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MAX FILESIZE' ); ?>::<?php echo JText::_( 'EDIT MAX FILESIZE' ); ?>">
					<?php echo JText::_( 'MAX FILESIZE' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required validate-number maxLength required-enabled" type="text" id="maxlength" name="maxlength" maxlength="50" size="16" value="<?php echo ( ! @$this->isNew && $this->item->maxlength != 50 ) ? $this->item->maxlength : 10000000; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY PATH' ); ?>::<?php echo JText::_( 'CHOOSE DISPLAY PATH OR NOT' ); ?>">
					<?php echo JText::_( 'DISPLAY PATH' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['displayPath']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DELETE BOX' ); ?>::<?php echo JText::_( 'CHOOSE DELETE BOX OR NOT' ); ?>">
					<?php echo JText::_( 'DELETE BOX' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['deleteBox']; ?>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SIZE' ); ?>::<?php echo JText::_( 'EDIT SIZE' ); ?>">
					<?php echo JText::_( 'SIZE' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:2}" type="text" id="size" name="size" maxlength="50" size="16" value="<?php echo ( @$this->item->size ) ? $this->item->size : 32; ?>" />
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PREVIEW' ); ?>::<?php echo JText::_( 'SELECT PREVIEW' ); ?>">
					<?php echo JText::_( 'PREVIEW' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['preview']; ?>
			</td>
		</tr>
   	</table>
	<table class="admintable header_jseblod">
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
<input type="hidden" name="type" value="upload_simple" />
<?php } ?>