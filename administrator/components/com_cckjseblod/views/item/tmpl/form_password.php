<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optValidation[]	=	JHTML::_( 'select.option', 'validate-password', JText::_( 'PASSWORD' ) );
$selectValidation	=	'validate-password';
$lists['validation'] = JHTML::_( 'select.genericlist', $optValidation, 'validation', 'size="1" class="inputbox"', 'value', 'text', $selectValidation );

$optMinLength[]		=	JHTML::_( 'select.option', '6', '6 ' . JText::_( 'CHARS' ) );
$optMinLength[]		=	JHTML::_( 'select.option', '7', '7 ' . JText::_( 'CHARS' ) );
$optMinLength[]		=	JHTML::_( 'select.option', '8', '8 ' . JText::_( 'CHARS' ) );
$optMinLength[]		=	JHTML::_( 'select.option', '9', '9 ' . JText::_( 'CHARS' ) );
$optMinLength[]		=	JHTML::_( 'select.option', '10', '10 ' . JText::_( 'CHARS' ) );
$optMinLength[]		=	JHTML::_( 'select.option', '11', '11 ' . JText::_( 'CHARS' ) );
$optMinLength[]		=	JHTML::_( 'select.option', '12', '12 ' . JText::_( 'CHARS' ) );
$selectMinLength	=	( @$this->item->bool2 ? $this->item->bool2 : 4 );
$lists['minlength']	=	JHTML::_( 'select.genericlist', $optMinLength, 'bool', 'size="1" class="inputbox"', 'value', 'text', $selectMinLength );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PASSWORD' ); ?>::<?php echo JText::_( 'DESCRIPTION PASSWORD' ); ?>">
		<?php echo JText::_( 'PASSWORD' ); ?>
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
		<tr class="display-no">
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'VALIDATION' ); ?>::<?php echo JText::_( 'SELECT VALIDATION' ); ?>">
					<?php echo JText::_( 'VALIDATION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['validation']; ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MINLENGTH' ); ?>::<?php echo JText::_( 'SELECT MINLENGTH' ); ?>">
					<?php echo JText::_( 'MINLENGTH' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['minlength']; ?>
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MAXLENGTH' ); ?>::<?php echo JText::_( 'EDIT MAXLENGTH' ); ?>">
					<?php echo JText::_( 'MAXLENGTH' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:3}" type="text" id="maxlength" name="maxlength" maxlength="50" size="16" value="<?php echo ( @$this->item->maxlength ) ? $this->item->maxlength : 50; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SIZE' ); ?>::<?php echo JText::_( 'EDIT SIZE' ); ?>">
					<?php echo JText::_( 'SIZE' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:2}" type="text" id="size" name="size" maxlength="50" size="16" value="<?php echo ( @$this->item->size ) ? $this->item->size : 32; ?>" />
			</td>
		</tr>
	</table>
</fieldset>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="password" />
<?php } ?>