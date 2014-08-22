<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
// DO NOT USE:: ->content
// DO NOT USE:: ->bool8
$optFunction[]		=	JHTML::_( 'select.option', '0', JText::_( 'MATH SUM' ) );
$optFunction[]		=	JHTML::_( 'select.option', '1', JText::_( 'MATH PRODUCT' ) );
$optFunction[]		=	JHTML::_( 'select.option', '2', JText::_( 'MATH DIFFERENCE' ) );
$optFunction[]		=	JHTML::_( 'select.option', '3', JText::_( 'MATH QUOTIENT' ) );
$selectFunction		=	( @$this->item->bool ? $this->item->bool : 0 );
$lists['function']	= JHTML::_( 'select.genericlist', $optFunction, 'bool', 'size="1" class="inputbox"', 'value', 'text', $selectFunction );

if ( @$this->item->options && ( strpos( @$this->item->options, '||' ) !== false ) ) {
	$options	=	explode( '||', $this->item->options );
}

$optSubstituteMode		=	array();
$optSubstituteMode[] 	=	JHTML::_( 'select.option', 0, JText::_( 'NONE' ) );
$optSubstituteMode[] 	=	JHTML::_( 'select.option', 1, JText::_( 'AS TITLE VALUE' ) );
$selectSubstituteMode	=	( ! $this->isNew ) ? $this->item->substitute : 0;
$lists['substitute'] 	=	JHTML::_( 'select.genericlist', $optSubstituteMode, 'substitute', 'size="1" class="inputbox"', 'value', 'text', $selectSubstituteMode );
?>

<fieldset class="adminform">
<legend class="legend-border">
  	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SELECT NUMERIC' ); ?>::<?php echo JText::_( 'DESCRIPTION SELECT NUMERIC' ); ?>">
		<?php echo JText::_( 'SELECT NUMERIC' ); ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FUNCTION' ); ?>::<?php echo JText::_( 'SELECT FUNCTION' ); ?>">
					<?php echo JText::_( 'FUNCTION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['function']; ?>
			</td>
		</tr>
       	<tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'OPTION FIRST' ); ?>::<?php echo JText::_( 'EDIT OPTION FIRST' ); ?>">
					<?php echo JText::_( 'OPTION FIRST' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="opt_first" name="opt_first" maxlength="250" size="32" value="<?php echo @$options[0]; ?>" />
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'OPTION START' ); ?>::<?php echo JText::_( 'EDIT OPTION START' ); ?>">
					<?php echo JText::_( 'OPTION START' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="opt_start" name="opt_start" maxlength="250" size="32" value="<?php echo @$options[1]; ?>" />
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'OPTION STEP' ); ?>::<?php echo JText::_( 'EDIT OPTION STEP' ); ?>">
					<?php echo JText::_( 'OPTION STEP' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="opt_step" name="opt_step" maxlength="250" size="32" value="<?php echo @$options[2]; ?>" />
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'OPTION END' ); ?>::<?php echo JText::_( 'EDIT OPTION END' ); ?>">
					<?php echo JText::_( 'OPTION END' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="opt_end" name="opt_end" maxlength="250" size="32" value="<?php echo @$options[3]; ?>" />
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'OPTION LAST' ); ?>::<?php echo JText::_( 'EDIT OPTION LAST' ); ?>">
					<?php echo JText::_( 'OPTION LAST' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="opt_last" name="opt_last" maxlength="250" size="32" value="<?php echo @$options[4]; ?>" />
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
				<input class="inputbox" type="text" id="defaultvalue" name="defaultvalue" maxlength="250" size="32" value="<?php echo @$this->item->defaultvalue; ?>" />
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
				<input class="inputbox" type="text" id="selectlabel" name="selectlabel" maxlength="50" size="32" value="<?php echo ( @$this->item->id ) ? $this->item->selectlabel : 'Select an Option'; ?>" />
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
<input type="hidden" name="type" value="select_numeric" />
<?php } ?>