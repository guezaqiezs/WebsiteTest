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
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'JOOMLA CONTENT' ); ?>::<?php echo JText::_( 'DESCRIPTION JOOMLA CONTENT' ); ?>">
		<?php echo JText::_( 'JOOMLA CONTENT' ); ?>
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
	</table>
</fieldset>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="type" value="joomla_content" />
<?php } ?>