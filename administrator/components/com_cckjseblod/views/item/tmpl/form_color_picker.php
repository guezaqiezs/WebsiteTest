<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes

?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'COLOR PICKER' ); ?>::<?php echo JText::_( 'DESCRIPTION COLOR PICKER' ); ?>">
		<?php echo JText::_( 'COLOR PICKER' ); ?>
    </span>
</legend>
	<table class="admintable">
		<tr>
			<td>
				<?php echo '<font color="gray">(*)&nbsp;' . JText::_( 'Not Available' ) . '</font>'; ?>
			</td>
		</tr>
	</table>
</fieldset>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="color_picker" />
<?php } ?>