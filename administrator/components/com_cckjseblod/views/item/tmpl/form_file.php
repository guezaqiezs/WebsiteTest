<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$lists['displayPath']	=	JHTML::_('select.booleanlist', 'bool2', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool2 : 0 );

$lists['includePath']	=	JHTML::_('select.booleanlist', 'bool3', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool3 : 1 );

$optIncludeExt[] 		=	JHTML::_( 'select.option', 1, JText::_( 'No' ) );
$optIncludeExt[] 		=	JHTML::_( 'select.option', 0, JText::_( 'Yes' ) );
$selectIncludeExt		=	( ! $this->isNew ) ? @$this->item->bool5 : 0;
$lists['includeExt']	=	JHTML::_( 'select.radiolist', $optIncludeExt, 'bool5', 'size="1" class="inputbox"', 'value', 'text', $selectIncludeExt );

$optFormat[] 		=	JHTML::_( 'select.option', 0, JText::_( 'DROPDOWN' ) );
$optFormat[] 		=	JHTML::_( 'select.option', 1, JText::_( 'MULTIPLE' ) );
$selectFormat		=	( ! $this->isNew ) ? @$this->item->bool4 : 0;
$lists['format']	=	JHTML::_( 'select.radiolist', $optFormat, 'bool4', 'size="1" class="inputbox"', 'value', 'text', $selectFormat );

$optPreview 		=	array();
$optPreview[] 		=	JHTML::_( 'select.option', 0, JText::_( 'TITLE' ) );
$optPreview[] 		=	JHTML::_( 'select.option', 1, JText::_( 'ICON' ) );
$optPreview[] 		=	JHTML::_( 'select.option', -1, JText::_( 'NONE' ) );
$selectPreview		=	( ! $this->isNew ) ? @$this->item->bool6 : 0;
$lists['preview']	=	JHTML::_( 'select.genericlist', $optPreview, 'bool6', 'size="1" class="inputbox"', 'value', 'text', $selectPreview );
?>

<fieldset class="adminform">
<legend class="legend-border">
  	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'File' ); ?>::<?php echo JText::_( 'DESCRIPTION FILE' ); ?>">
		<?php echo JText::_( 'File' ); ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SUBFOLDERS' ); ?>::<?php echo JText::_( 'SUBFOLDERS BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SUBFOLDERS' ); ?>::<?php echo JText::_( 'CHOOSE SUBFOLDERS OR NOT' ); ?>">
					<?php echo JText::_( 'SUBFOLDERS' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['boolean']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'INCLUDE PATH IN VALUE' ); ?>::<?php echo JText::_( 'CHOOSE INCLUDE PATH IN VALUE OR NOT' ); ?>">
					<?php echo JText::_( 'INCLUDE PATH IN VALUE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['includePath']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'INCLUDE EXT IN VALUE' ); ?>::<?php echo JText::_( 'CHOOSE INCLUDE EXT IN VALUE OR NOT' ); ?>">
					<?php echo JText::_( 'INCLUDE EXT IN VALUE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['includeExt']; ?>
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
				<input class="inputbox required required-enabled" type="text" id="options" name="options" maxlength="250" size="32" value="<?php echo @$this->item->options; ?>" />
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SELECT LABEL' ); ?>::<?php echo JText::_( 'SELECT LABEL BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SELECT LABEL' ); ?>::<?php echo JText::_( 'EDIT SELECT LABEL' ); ?>">
					<?php echo JText::_( 'SELECT LABEL' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="selectlabel" name="selectlabel" maxlength="50" size="32" value="<?php echo ( @$this->item->id ) ? $this->item->selectlabel : 'Select a File'; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FORMAT' ); ?>::<?php echo JText::_( 'CHOOSE DROPDOWN OR MULTIPLE' ); ?>">
					<?php echo JText::_( 'FORMAT' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['format']; ?>
			</td>
		</tr>
		<tr id="as-divider" class="<?php echo ( @$this->item->bool4 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEPARATOR' ); ?>::<?php echo JText::_( 'SEPARATOR BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEPARATOR' ); ?>::<?php echo JText::_( 'EDIT SEPARATOR' ); ?>">
					<?php echo JText::_( 'SEPARATOR' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="divider" name="divider" maxlength="50" size="16" value="<?php echo ( @$this->item->divider ) ? $this->item->divider : ','; ?>" />
			</td>
		</tr>
		<tr id="as-rows" class="<?php echo ( @$this->item->bool4 ) ? '' : 'display-no' ?>">
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ROWS' ); ?>::<?php echo JText::_( 'EDIT ROWS' ); ?>">
					<?php echo JText::_( 'ROWS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="rows" name="rows" maxlength="50" size="16" value="<?php echo ( @$this->item->rows ) ? $this->item->rows : 10; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
					<?php echo JText::_( 'WIDTH' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox validate-number" type="text" id="width" name="width" maxlength="50" size="16" value="<?php echo @$this->item->width; ?>" />
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
				<input class="inputbox validate-number" type="text" id="height" name="height" maxlength="50" size="16" value="<?php echo @$this->item->height; ?>" />
			</td>
		</tr>
	</table>
</fieldset>

<script type="text/javascript">
	$("bool40").addEvent("change", function(m) {
			m = new Event(m).stop();
			
			if ( ! $("as-divider").hasClass("display-no") ) {
				$("as-divider").addClass("display-no");
			}
			if ( ! $("as-rows").hasClass("display-no") ) {
				$("as-rows").addClass("display-no");
			}
		});
	$("bool41").addEvent("change", function(i) {
			i = new Event(i).stop();
			
			if ( $("as-divider").hasClass("display-no") ) {
				$("as-divider").removeClass("display-no");
			}
			if ( $("as-rows").hasClass("display-no") ) {
				$("as-rows").removeClass("display-no");
			}
		});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="file" />
<?php } ?>