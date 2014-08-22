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

$optDefault[]		=	JHTML::_('select.option', '_', JText::_( 'IMAGE' ) );
$optDefault[]		=	JHTML::_('select.option', 'thumb1', JText::_( 'THUMB' ).' 1' );
$optDefault[]		=	JHTML::_('select.option', 'thumb2', JText::_( 'THUMB' ).' 2' );
$optDefault[]		=	JHTML::_('select.option', 'thumb3', JText::_( 'THUMB' ).' 3' );
$optDefault[]		=	JHTML::_('select.option', 'thumb4', JText::_( 'THUMB' ).' 4' );
$optDefault[]		=	JHTML::_('select.option', 'thumb5', JText::_( 'THUMB' ).' 5' );
$lists['default']	=	JHTML::_('select.genericlist', $optDefault, 'defaultvalue', 'size="1" class="inputbox"', 'value', 'text', ( ! $this->isNew ) ? $this->item->defaultvalue : '_thumb1' );

$optBox[]			=	JHTML::_('select.option', '', JText::_( 'None' ) );
$optBox[]			=	JHTML::_('select.option', '_', JText::_( 'IMAGE' ) );
$optBox[]			=	JHTML::_('select.option', 'thumb1', JText::_( 'THUMB' ).' 1' );
$optBox[]			=	JHTML::_('select.option', 'thumb2', JText::_( 'THUMB' ).' 2' );
$optBox[]			=	JHTML::_('select.option', 'thumb3', JText::_( 'THUMB' ).' 3' );
$optBox[]			=	JHTML::_('select.option', 'thumb4', JText::_( 'THUMB' ).' 4' );
$optBox[]			=	JHTML::_('select.option', 'thumb5', JText::_( 'THUMB' ).' 5' );
$lists['box']		=	JHTML::_('select.genericlist', $optBox, 'url', 'size="1" class="inputbox"', 'value', 'text', ( ! $this->isNew ) ? $this->item->url : '_' );

$optImageProcess[]	=	JHTML::_('select.option', '', JText::_( 'ORIGINAL' ) );
$optImageProcess[] 	=	JHTML::_('select.option', 'addcolor', JText::_( 'ADD COLOR' ) );
$optImageProcess[] 	=	JHTML::_('select.option', 'crop', JText::_( 'CROP CENTER' ) );
$optImageProcess[] 	=	JHTML::_('select.option', 'maxfit', JText::_( 'MAX FIT' ) );
$optImageProcess[]	=	JHTML::_('select.option', 'stretch', JText::_( 'STRETCH' ) );
$lists['original']	=	JHTML::_('select.genericlist', $optImageProcess, 'format', 'size="1" class="inputbox"', 'value', 'text', ( ! $this->isNew ) ? $this->item->format : '' );

if ( @$this->item->options && strpos( $this->item->options, '||' ) !== false && strpos( $this->item->options, '--' ) !== false ) {
	$opts		=	array();
	$options	=	explode( '||', $this->item->options );
	foreach ( $options as $opt ) {
		$opts[]	=	explode( '--', $opt );
	}
}
$optThumbsProcess[] =	JHTML::_('select.option', '', JText::_( 'NONE' ) );
$optThumbsProcess[] =	JHTML::_('select.option', 'addcolor', JText::_( 'ADD COLOR' ) );
$optThumbsProcess[] =	JHTML::_('select.option', 'crop', JText::_( 'CROP CENTER' ) );
$optThumbsProcess[] =	JHTML::_('select.option', 'maxfit', JText::_( 'MAX FIT' ) );
$optThumbsProcess[] =	JHTML::_('select.option', 'stretch', JText::_( 'STRETCH' ) );
$lists['thumb1']	=	JHTML::_('select.genericlist', $optThumbsProcess, 'thumb1', 'size="1" class="inputbox"', 'value', 'text', ( ! $this->isNew ) ? @$opts[0][0] : 'maxfit' );
$lists['thumb2']	=	JHTML::_('select.genericlist', $optThumbsProcess, 'thumb2', 'size="1" class="inputbox"', 'value', 'text', @$opts[1][0] );
$lists['thumb3']	=	JHTML::_('select.genericlist', $optThumbsProcess, 'thumb3', 'size="1" class="inputbox"', 'value', 'text', @$opts[2][0] );
$lists['thumb4']	=	JHTML::_('select.genericlist', $optThumbsProcess, 'thumb4', 'size="1" class="inputbox"', 'value', 'text', @$opts[3][0] );
$lists['thumb5']	=	JHTML::_('select.genericlist', $optThumbsProcess, 'thumb5', 'size="1" class="inputbox"', 'value', 'text', @$opts[4][0] );

$addColor	=	( @$this->item->extra ) ? $this->item->extra : '#000000';

$optPreview 		=	array();
$optPreview[] 		=	JHTML::_( 'select.option', 0, JText::_( 'TITLE' ) );
$optPreview[] 		=	JHTML::_( 'select.option', 1, JText::_( 'ICON' ) );
$optPreview[] 		=	JHTML::_( 'select.option', 2, JText::_( 'IMAGE' ) );
$optPreview[] 		=	JHTML::_( 'select.option', 3, JText::_( 'THUMB' ).' 1' );
$optPreview[] 		=	JHTML::_( 'select.option', 4, JText::_( 'THUMB' ).' 2' );
$optPreview[] 		=	JHTML::_( 'select.option', 5, JText::_( 'THUMB' ).' 3' );
$optPreview[] 		=	JHTML::_( 'select.option', 6, JText::_( 'THUMB' ).' 4' );
$optPreview[] 		=	JHTML::_( 'select.option', 7, JText::_( 'THUMB' ).' 5' );
$optPreview[] 		=	JHTML::_( 'select.option', -1, JText::_( 'NONE' ) );
$selectPreview		=	( ! $this->isNew ) ? @$this->item->bool6 : 3;
$lists['preview']	=	JHTML::_( 'select.genericlist', $optPreview, 'bool6', 'size="1" class="inputbox"', 'value', 'text', $selectPreview );

$lists['deleteBox']	=	JHTML::_('select.booleanlist', 'bool7', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool7 : 1 );
?>

<fieldset class="adminform">
<legend class="legend-border">
  	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'UPLOAD IMAGE' ); ?>::<?php echo JText::_( 'DESCRIPTION UPLOAD IMAGE' ); ?>">
		<?php echo JText::_( 'UPLOAD IMAGE' ); ?>
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
				<input class="inputbox required required-enabled" type="text" id="location" name="location" maxlength="250" size="32" value="<?php echo ( @$this->item->location ) ? $this->item->location : 'images/'; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'LEGAL EXTENSIONS' ); ?>::<?php echo JText::_( 'LEGAL EXTENSIONS IMAGE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'LEGAL EXTENSIONS' ); ?>::<?php echo JText::_( 'EDIT LEGAL EXTENSIONS' ); ?>">
					<?php echo JText::_( 'LEGAL EXTENSIONS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="options2" name="options2" maxlength="250" size="32" value="<?php echo ( @$this->item->options2 ) ? $this->item->options2 : 'jpg,JPG,png,PNG,gif,GIF'; ?>" />
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
        <tr>
			<td colspan="3">
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEFAULT VALUE' ); ?>::<?php echo JText::_( 'EDIT DEFAULT VALUE' ); ?>">
					<?php echo JText::_( 'DEFAULT VALUE' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="cc" name="cc" maxlength="250" size="32" value="<?php echo @$this->item->cc; ?>" />
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
        <tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'IMAGE' ); ?>::<?php echo JText::_( 'SELECT IMAGE PROCESSING' ); ?>">
					<?php echo JText::_( 'IMAGE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['original']; ?>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
					<?php echo JText::_( 'WIDTH' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="width" name="width" maxlength="50" size="16" value="<?php echo ( @$this->item->width ) ? $this->item->width : 0; ?>" />
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'HEIGHT' ); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
					<?php echo JText::_( 'HEIGHT' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="height" name="height" maxlength="50" size="16" value="<?php echo ( ! @$this->isNew ) ? $this->item->height : 100; ?>" />
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WATERMARK' ); ?>::<?php echo JText::_( 'WATERMARK BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WATERMARK' ); ?>::<?php echo JText::_( 'EDIT WATERMARK' ); ?>">
					<?php echo JText::_( 'WATERMARK' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="content" name="content" maxlength="50" size="32" value="<?php echo ( @$this->item->content ) ? $this->item->content : ''; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ADD COLOR' ); ?>::<?php echo JText::_( 'PICK ADD COLOR' ); ?>">
					<?php echo JText::_( 'ADD COLOR' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="extra" name="extra" maxlength="7" size="16" value="<?php echo $addColor; ?>" />&nbsp;&nbsp;
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'ADD COLOR' ); ?>::<?php echo JText::_( 'PICK ADD COLOR' ); ?>">
					<?php echo _IMG_COLOR_GRID; ?>
				</span>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'AS DEFAULT' ); ?>::<?php echo JText::_( 'SELECT IMAGE/THUMB AS DEFAULT' ); ?>">
					<?php echo JText::_( 'AS DEFAULT' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['default']; ?>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'AS BOX' ); ?>::<?php echo JText::_( 'SELECT IMAGE/THUMB AS BOX' ); ?>">
					<?php echo JText::_( 'AS BOX' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['box']; ?>
			</td>
		</tr>
        <tr>
        	<td colspan="3">
			</td>
        </tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
            	<?php echo JText::_( 'THUMB').' 1'; ?>
			</td>
        </tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'THUMB').' 1'; ?>::<?php echo JText::_( 'SELECT THUMB PROCESSING' ); ?>">
					<?php echo JText::_( 'THUMB').' 1'; ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['thumb1']; ?>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
					<?php echo JText::_( 'WIDTH' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="width1" name="width1" maxlength="50" size="16" value="<?php echo ( @$this->isNew ) ? 150 : @$opts[0][1]; ?>" />
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'HEIGHT' ); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
					<?php echo JText::_( 'HEIGHT' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="height1" name="height1" maxlength="50" size="16" value="<?php echo ( @$this->isNew ) ? 150 : @$opts[0][2]; ?>" />
			</td>
		</tr>
        <tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
   	        	<?php echo JText::_( 'THUMB').' 2'; ?>&nbsp;&nbsp;<span id="thumb2-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[Toggle]</span>
			</td>
        </tr>
        <tr id="as-thumb2-1" class="<?php echo ( @$opts[1][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'THUMB').' 2'; ?>::<?php echo JText::_( 'SELECT THUMB PROCESSING' ); ?>">
					<?php echo JText::_( 'THUMB').' 2'; ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['thumb2']; ?>
			</td>
		</tr>
        <tr id="as-thumb2-2" class="<?php echo ( @$opts[1][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
					<?php echo JText::_( 'WIDTH' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="width2" name="width2" maxlength="50" size="16" value="<?php echo ( @$this->item->options && $opts[1][1] ) ? $opts[1][1] : 0; ?>" />
			</td>
		</tr>
		<tr id="as-thumb2-3" class="<?php echo ( @$opts[1][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'HEIGHT'); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
					<?php echo JText::_( 'HEIGHT').' 2'; ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="height2" name="height2" maxlength="50" size="16" value="<?php echo ( @$this->item->options && $opts[1][2] ) ? $opts[1][2] : 0; ?>" />
			</td>
		</tr>
        <tr id="as-thumb2-4" class="<?php echo ( @$opts[1][1] ) ? '' : 'display-no' ?>" >
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
   	        	<?php echo JText::_( 'THUMB').' 3'; ?>&nbsp;&nbsp;<span id="thumb3-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[Toggle]</span>
			</td>
        </tr>
		<tr id="as-thumb3-1" class="<?php echo ( @$opts[2][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'THUMB').' 3'; ?>::<?php echo JText::_( 'SELECT THUMB PROCESSING' ); ?>">
					<?php echo JText::_( 'THUMB').' 3'; ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['thumb3']; ?>
			</td>
		</tr>
		<tr id="as-thumb3-2" class="<?php echo ( @$opts[2][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
					<?php echo JText::_( 'WIDTH' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="width3" name="width3" maxlength="50" size="16" value="<?php echo ( @$this->item->options && $opts[2][1] ) ? $opts[2][1] : 0; ?>" />
			</td>
		</tr>
		<tr id="as-thumb3-3" class="<?php echo ( @$opts[2][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'HEIGHT' ); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
					<?php echo JText::_( 'HEIGHT' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="height3" name="height3" maxlength="50" size="16" value="<?php echo ( @$this->item->options && $opts[2][2] ) ? $opts[2][2] : 0; ?>" />
			</td>
		</tr>
        
        <tr id="as-thumb2-4" class="<?php echo ( @$opts[2][1] ) ? '' : 'display-no' ?>" >
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
   	        	<?php echo JText::_( 'THUMB').' 4'; ?>&nbsp;&nbsp;<span id="thumb4-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[Toggle]</span>
			</td>
        </tr>
		<tr id="as-thumb4-1" class="<?php echo ( @$opts[3][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'THUMB').' 4'; ?>::<?php echo JText::_( 'SELECT THUMB PROCESSING' ); ?>">
					<?php echo JText::_( 'THUMB').' 4'; ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['thumb4']; ?>
			</td>
		</tr>
		<tr id="as-thumb4-2" class="<?php echo ( @$opts[3][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
					<?php echo JText::_( 'WIDTH' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="width4" name="width4" maxlength="50" size="16" value="<?php echo ( @$this->item->options && $opts[3][1] ) ? @$opts[3][1] : 0; ?>" />
			</td>
		</tr>
		<tr id="as-thumb4-3" class="<?php echo ( @$opts[3][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'HEIGHT' ); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
					<?php echo JText::_( 'HEIGHT' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="height4" name="height4" maxlength="50" size="16" value="<?php echo ( @$this->item->options && @$opts[3][2] ) ? @$opts[3][2] : 0; ?>" />
			</td>
		</tr>
        <tr id="as-thumb4-4" class="<?php echo ( @$opts[3][1] ) ? '' : 'display-no' ?>" >
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
   	        	<?php echo JText::_( 'THUMB').' 5'; ?>&nbsp;&nbsp;<span id="thumb5-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[Toggle]</span>
			</td>
        </tr>
		<tr id="as-thumb5-1" class="<?php echo ( @$opts[4][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'THUMB').' 5'; ?>::<?php echo JText::_( 'SELECT THUMB PROCESSING' ); ?>">
					<?php echo JText::_( 'THUMB').' 5'; ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['thumb5']; ?>
			</td>
		</tr>
		<tr id="as-thumb5-2" class="<?php echo ( @$opts[4][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
					<?php echo JText::_( 'WIDTH' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="width5" name="width5" maxlength="50" size="16" value="<?php echo ( @$this->item->options && @$opts[4][1] ) ? @$opts[4][1] : 0; ?>" />
			</td>
		</tr>
		<tr id="as-thumb5-3" class="<?php echo ( @$opts[4][1] ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'HEIGHT' ); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
					<?php echo JText::_( 'HEIGHT' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="height5" name="height5" maxlength="50" size="16" value="<?php echo ( @$this->item->options && @$opts[4][2] ) ? @$opts[4][2] : 0; ?>" />
			</td>
		</tr>
	</table>
</fieldset>

<script type="text/javascript">
	window.addEvent( "domready",function(){
					
		var init3 = '<?php echo $addColor; ?>';
		if ( !init3 ) { init3 = "#FFFFFF"; }
		R2 = HexToRGB( init3, 0, 2 );
		G2 = HexToRGB( init3, 2, 4);
		B2 = HexToRGB( init3, 4, 6);
		
		var c2 = new MooRainbow( "extraRainbow", {
			id: "extraRainbow",
			wheel: false, 
		"startColor": [R2, G2, B2],
      		"onChange": function( color ) { $("extra").value = color.hex; }
      	});
	});
	
	function HexToRGB(hexa,left,right) {return parseInt((cutHex(hexa)).substring(left,right),16)}
	function cutHex(hexa) {return (hexa.charAt(0)=="#") ? hexa.substring(1,7):hexa}

	$("thumb2-toggle").addEvent("click", function(t2) {
			t2 = new Event(t2).stop();
			
			if ( $("as-thumb2-1").hasClass("display-no") ) {
				$("as-thumb2-1").removeClass("display-no");
			} else {
				$("as-thumb2-1").addClass("display-no");
			}
			if ( $("as-thumb2-2").hasClass("display-no") ) {
				$("as-thumb2-2").removeClass("display-no");
			} else {
				$("as-thumb2-2").addClass("display-no");
			}
			if ( $("as-thumb2-3").hasClass("display-no") ) {
				$("as-thumb2-3").removeClass("display-no");
			} else {
				$("as-thumb2-3").addClass("display-no");
			}
			if ( $("as-thumb2-4").hasClass("display-no") ) {
				$("as-thumb2-4").removeClass("display-no");
			} else {
				$("as-thumb2-4").addClass("display-no");
			}
		});
	$("thumb3-toggle").addEvent("click", function(t3) {
			t3 = new Event(t3).stop();
			
			if ( $("as-thumb3-1").hasClass("display-no") ) {
				$("as-thumb3-1").removeClass("display-no");
			} else {
				$("as-thumb3-1").addClass("display-no");
			}
			if ( $("as-thumb3-2").hasClass("display-no") ) {
				$("as-thumb3-2").removeClass("display-no");
			} else {
				$("as-thumb3-2").addClass("display-no");
			}
			if ( $("as-thumb3-3").hasClass("display-no") ) {
				$("as-thumb3-3").removeClass("display-no");
			} else {
				$("as-thumb3-3").addClass("display-no");
			}
		});
	$("thumb4-toggle").addEvent("click", function(t4) {
			t4 = new Event(t4).stop();
			
			if ( $("as-thumb4-1").hasClass("display-no") ) {
				$("as-thumb4-1").removeClass("display-no");
			} else {
				$("as-thumb4-1").addClass("display-no");
			}
			if ( $("as-thumb4-2").hasClass("display-no") ) {
				$("as-thumb4-2").removeClass("display-no");
			} else {
				$("as-thumb4-2").addClass("display-no");
			}
			if ( $("as-thumb4-3").hasClass("display-no") ) {
				$("as-thumb4-3").removeClass("display-no");
			} else {
				$("as-thumb4-3").addClass("display-no");
			}
		});
	$("thumb5-toggle").addEvent("click", function(t5) {
			t5 = new Event(t5).stop();
			
			if ( $("as-thumb5-1").hasClass("display-no") ) {
				$("as-thumb5-1").removeClass("display-no");
			} else {
				$("as-thumb5-1").addClass("display-no");
			}
			if ( $("as-thumb5-2").hasClass("display-no") ) {
				$("as-thumb5-2").removeClass("display-no");
			} else {
				$("as-thumb5-2").addClass("display-no");
			}
			if ( $("as-thumb5-3").hasClass("display-no") ) {
				$("as-thumb5-3").removeClass("display-no");
			} else {
				$("as-thumb5-3").addClass("display-no");
			}
		});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="upload_image" />
<?php } ?>