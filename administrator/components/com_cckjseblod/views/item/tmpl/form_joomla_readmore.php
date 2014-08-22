<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optMode[] 		=	JHTML::_( 'select.option', 0, JText::_( 'No' ) );
$optMode[] 		=	JHTML::_( 'select.option', 1, JText::_( 'Yes' ) );
//$optMode[] 	=	JHTML::_( 'select.option', 2, JText::_( 'COMPATIBILITY' ) );
$selectMode		=	( @$this->item->id ) ? @$this->item->bool : 1;
$lists['mode']	=	JHTML::_( 'select.radiolist', $optMode, 'bool', 'size="1" class="inputbox"', 'value', 'text', $selectMode );

$optDisplayField[] = JHTML::_( 'select.option', 0, JText::_( 'ALWAYS' ) );
$optDisplayField[] = JHTML::_( 'select.option', 1, JText::_( 'ON ADMIN' ) );
$optDisplayField[] = JHTML::_( 'select.option', 2, JText::_( 'ON SITE' ) );
$optDisplayField[] = JHTML::_( 'select.option', -1, JText::_( 'HIDE' ) );
$selectDisplayField	=	( @$this->item->id ) ? @$this->item->displayfield : 1;
$lists['displayField'] = JHTML::_( 'select.genericlist', $optDisplayField, 'displayfield', 'size="1" class="inputbox"', 'value', 'text', $selectDisplayField );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'JOOMLA READMORE' ); ?>::<?php echo JText::_( 'DESCRIPTION JOOMLA READMORE' ); ?>">
		<?php echo JText::_( 'JOOMLA READMORE' ); ?>
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
        <tr>
            <td width="25" align="right" class="key_jseblod">
            	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY FIELD' ); ?>::<?php echo JText::_( 'DISPLAY READMORE FIELD BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY FIELD' ); ?>::<?php echo JText::_( 'CHOOSE DISPLAY FIELD OR NOT' ); ?>">
					<?php echo JText::_( 'DISPLAY FIELD' ); ?>:
				</span>
			</td>
        	<td>
		        <?php echo $lists['displayField']; ?>
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
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'Enabled' ); ?>::<?php echo JText::_( 'CHOOSE ENABLED OR NOT' ); ?>">
					<?php echo JText::_( 'Enabled' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['mode']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'VALUE' ); ?>::<?php echo JText::_( 'VALUE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'VALUE' ); ?>::<?php echo JText::_( 'EDIT VALUE' ); ?>">
					<?php echo JText::_( 'VALUE' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="dafaultvalue" name="defaultvalue" maxlength="50" size="32" value="<?php echo ( @$this->item->defaultvalue ) ? $this->item->defaultvalue : 'Read More...'; ?>" />
			</td>
		</tr>
	</table>
</fieldset>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="joomla_readmore" />
<?php } ?>