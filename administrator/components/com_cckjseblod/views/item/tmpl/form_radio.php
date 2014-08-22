<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
// DO NOT USE:: ->content
// DO NOT USE:: ->bool8
$optOrientation			=	array();
$optOrientation[] 		=	JHTML::_( 'select.option', 0, JText::_( 'HORIZONTAL' ) );
$optOrientation[] 		=	JHTML::_( 'select.option', 1, JText::_( 'VERTICAL' ) );
$selectOrientation		=	( ! $this->isNew ) ? $this->item->bool : 0;
$lists['orientation'] 	=	JHTML::_( 'select.radiolist', $optOrientation, 'bool', 'size="1" class="inputbox"', 'value', 'text', $selectOrientation );

$optTableStyle			=	array();
$optTableStyle[] 		=	JHTML::_( 'select.option', 0, JText::_( 'No' ) );
$optTableStyle[] 		=	JHTML::_( 'select.option', 1, JText::_( 'Yes' ) );
$selectTableStyle		=	( ! $this->isNew ) ? $this->item->bool2 : 0;
$lists['table'] 		=	JHTML::_( 'select.genericlist', $optTableStyle, 'bool2', 'size="1" class="inputbox"', 'value', 'text', $selectTableStyle );

if ( @$this->item->options ) {
	$options	=	explode( '||', $this->item->options );
	$nOpt	=	count( $options );
} else {
	$nOpt = 0;
}

$optSubstituteMode		=	array();
$optSubstituteMode[] 	=	JHTML::_( 'select.option', 0, JText::_( 'NONE' ) );
$optSubstituteMode[] 	=	JHTML::_( 'select.option', 1, JText::_( 'AS TITLE VALUE' ) );
$optSubstituteMode[] 	=	JHTML::_( 'select.option', 2, JText::_( 'AS TITLE TEXT' ) );
$selectSubstituteMode	=	( ! $this->isNew ) ? $this->item->substitute : 0;
$lists['substitute'] 	=	JHTML::_( 'select.genericlist', $optSubstituteMode, 'substitute', 'size="1" class="inputbox"', 'value', 'text', $selectSubstituteMode );

$optIndexed			=	array();
$optIndexed[] 		=	JHTML::_( 'select.option', 0, JText::_( 'No' ) );
$optIndexed[] 		=	JHTML::_( 'select.option', 1, JText::_( 'Yes' ) );
$selectIndexed		=	( ! $this->isNew ) ? $this->item->indexed : 0;
$lists['indexed'] 	=	JHTML::_( 'select.radiolist', $optIndexed, 'indexed', 'size="1" class="inputbox"', 'value', 'text', $selectIndexed );
?>

<script type="text/javascript">
	function addElement(parentId, elementTag, elementId, html) {
		var p = document.getElementById(parentId);
		var newElement = document.createElement(elementTag);
		newElement.setAttribute('id', elementId);
		newElement.innerHTML = html;
		p.appendChild(newElement);
	}

	function removeElement(elementId) {
		var element = document.getElementById(elementId);
		element.parentNode.removeChild(element);
	}
	
	function addOption() {
		optId++;
		var img_del = '<?php echo _IMG_DEL; ?>'; 
		var html = '<input class="inputbox" type="text" id="options" name="options[]" maxlength="250" size="32" value="" /> ' +
				   '<a href="javascript: removeElement(\'opt-' + optId + '\');">'+img_del+'</a>';
		addElement('options', 'p', 'opt-' + optId, html);
	}
	
	var optId = "<?php echo $nOpt; ?>";
</script>

<fieldset class="adminform">
<legend class="legend-border">
   	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'RADIO' ); ?>::<?php echo JText::_( 'DESCRIPTION RADIO' ); ?>">
		<?php echo JText::_( 'RADIO' ); ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'Ordering' ); ?>::<?php echo JText::_( 'Select Ordering' ); ?>">
					<?php echo JText::_( 'Ordering' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['ordering']; ?>
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ADD OPTION' ); ?>::<?php echo JText::_( 'CLICK TO ADD AN OPTION' ); ?>">
					<?php echo JText::_( 'ADD OPTION' ); ?>:
				</span>
			</td>
			<td>
				<a href="javascript: addOption();"><?php echo _IMG_ADD; ?></a>
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'Options' ); ?>::<?php echo JText::_( 'OPTIONS BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'Options' ); ?>::<?php echo JText::_( 'EDIT OPTIONS' ); ?>">
					<?php echo JText::_( 'Options' ); ?>:
				</span>
			</td>
			<td>
				<div id="options">
				<?php if ( $nOpt ) {
					for ( $i = 0; $i < $nOpt; $i++ ) {
						$j = $i + 1; ?>
						<p id="opt-<?php echo $j; ?>"><input class="inputbox" type="text" id="options" name="options[]" maxlength="250" size="32" value="<?php echo $options[$i]; ?>" />&nbsp;<a href="javascript: removeElement('opt-<?php echo $j; ?>');"><?php echo _IMG_DEL; ?></a></p>
					<?php } } ?>
				</div>
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
				<input class="inputbox" type="text" id="defaultvalue" name="defaultvalue" maxlength="50" size="32" value="<?php echo @$this->item->defaultvalue; ?>" />
			</td>
		</tr>
 		<tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
            <?php echo JText::_( 'TITLE SUBSTITUTE' ); ?>
			</td>
        </tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'TITLE MODE' ); ?>::<?php echo JText::_( 'TITLE MODE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'TITLE MODE' ); ?>::<?php echo JText::_( 'SELECT TITLE MODE' ); ?>">
					<?php echo JText::_( 'TITLE MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['substitute']; ?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
            	<?php echo JText::_( 'INDEX DATABASE' ); ?>
			</td>
        </tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'INDEXED' ); ?>::<?php echo JText::_( 'CHOOSE INDEXED OR NOT' ); ?>">
					<?php echo JText::_( 'INDEXED' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['indexed']; ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ORIENTATION' ); ?>::<?php echo JText::_( 'CHOOSE ORIENTATION' ); ?>">
					<?php echo JText::_( 'ORIENTATION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['orientation']; ?>
			</td>
		</tr>
		<tr>
        	<td width="25" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'COLUMNS' ); ?>::<?php echo JText::_( 'EDIT COLUMNS' ); ?>">
					<?php echo JText::_( 'COLUMNS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:2}" type="text" id="cols" name="cols" maxlength="50" size="16" value="<?php echo ( @$this->item->cols != '' ) ? $this->item->cols : 3; ?>" />
			</td>
		</tr>
		<tr>
        	<td width="25" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'TABLE STYLE' ); ?>::<?php echo JText::_( 'CHOOSE TABLE STYLE OR NOT' ); ?>">
					<?php echo JText::_( 'TABLE STYLE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['table']; ?>
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
<input type="hidden" name="type" value="radio" />
<?php } ?>