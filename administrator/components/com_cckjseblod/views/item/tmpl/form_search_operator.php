<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php 
// Specific Attributes
$optMode			=	array();
$optMode[] 			=	JHTML::_( 'select.option', '((', JText::_( 'OPERATOR (OPEN' ) );
$optMode[] 			=	JHTML::_( 'select.option', '(', JText::_( 'OPERATOR (' ) );
$optMode[] 			=	JHTML::_( 'select.option', 'AND', JText::_( 'OPERATOR AND' ) );
$optMode[] 			=	JHTML::_( 'select.option', 'OR', JText::_( 'OPERATOR OR' ) );
$optMode[] 			=	JHTML::_( 'select.option', ')', JText::_( 'OPERATOR )' ) );
$optMode[] 			=	JHTML::_( 'select.option', '))', JText::_( 'OPERATOR CLOSE)' ) );
$selectMode			=	( ! $this->isNew ) ? $this->item->content : 'OR';
$lists['mode'] 		=	JHTML::_( 'select.genericlist', $optMode, 'content', 'size="1" class="inputbox"', 'value', 'text', $selectMode );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH OPERATOR' ); ?>::<?php echo JText::_( 'DESCRIPTION SEARCH OPERATOR' ); ?>">
		<?php echo JText::_( 'SEARCH OPERATOR' ); ?>
    </span>
</legend>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MODE' ); ?>::<?php echo JText::_( 'SEARCH OPERATOR MODE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MODE' ); ?>::<?php echo JText::_( 'SELECT MODE' ); ?>">
					<?php echo JText::_( 'MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['mode']; ?>
			</td>
		</tr>
	</table>
</fieldset>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="search_operator" />
<?php } ?>