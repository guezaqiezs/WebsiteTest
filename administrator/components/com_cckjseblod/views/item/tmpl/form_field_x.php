<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$lists['repeatable']	=	JHTML::_( 'select.booleanlist', 'bool2', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool2 : 1 );
$lists['draggable']		=	JHTML::_( 'select.booleanlist', 'bool3', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool3 : 1 );
$lists['deletable']		=	JHTML::_( 'select.booleanlist', 'bool4', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool4 : 1 );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FIELDX' ); ?>::<?php echo JText::_( 'DESCRIPTION ITEM X ARRAY' ); ?>">
		<?php echo JText::_( 'FIELDX' ); ?>
    </span>
</legend>
	<table class="admintable">
		<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'LABEL' ); ?>::<?php echo JText::_( 'LABEL BALLOON' ); ?>">
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
				<?php echo $this->modals['selectItem']; ?><?php //echo $this->modals['newItem']; ?>
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
		<tr>
        	<td width="25" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEFAULT' ); ?>::<?php echo JText::_( 'EDIT DEFAULT NUMBER' ); ?>">
					<?php echo JText::_( 'DEFAULT' ); ?>:
				</span>
			</td>
			<td colspan="2">
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:2}" type="text" id="rows" name="rows" maxlength="50" size="16" value="<?php echo ( @$this->item->rows ) ? $this->item->rows : 3; ?>" />
			</td>
		</tr>
        <tr>
			<td colspan="3">
			</td>
		</tr>
		<tr>
        	<td colspan="3" class="keytext_jseblod">
	            <?php echo JText::_( 'DELETABLE' ); ?>
			</td>
        </tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
	            <img src="../media/jseblod/_icons/del-default.gif" alt="Del" />
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ENABLE' ); ?>::<?php echo JText::_( 'CHOOSE ENABLE OR NOT' ); ?>">
					<?php echo JText::_( 'ENABLE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['deletable']; ?>
			</td>
		</tr>
        <tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
	            <?php echo JText::_( 'DRAGGABLE' ); ?>
			</td>
        </tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
	            <img src="../media/jseblod/_icons/drag-default.gif" alt="Drag" />
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ENABLE' ); ?>::<?php echo JText::_( 'CHOOSE ENABLE OR NOT' ); ?>">
					<?php echo JText::_( 'ENABLE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['draggable']; ?>
			</td>
		</tr>
        <tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
            <?php echo JText::_( 'REPEATABLE'); ?>
			</td>
        </tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
	            <img src="../media/jseblod/_icons/add-default.gif" alt="Add" />
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ENABLE' ); ?>::<?php echo JText::_( 'CHOOSE ENABLE OR NOT' ); ?>">
					<?php echo JText::_( 'ENABLE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['repeatable']; ?>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MAXIMUM' ); ?>::<?php echo JText::_( 'EDIT MAXIMUM' ); ?>">
					<?php echo JText::_( 'MAXIMUM' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:3}" type="text" id="maxlength" name="maxlength" maxlength="50" size="16" value="<?php echo ( @$this->item->maxlength ) ? $this->item->maxlength : 5; ?>" />
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
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEPARATOR' ); ?>::<?php echo JText::_( 'EDIT SEPARATOR' ); ?>">
					<?php echo JText::_( 'SEPARATOR' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="content" name="content" maxlength="50" size="16" value="<?php echo ( @$this->item->content ) ? $this->item->content : ''; ?>" />
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
			<td colspan="2">
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:2}" type="text" id="cols" name="cols" maxlength="50" size="16" value="<?php echo ( @$this->item->cols != '' ) ? $this->item->cols : 3; ?>" />
			</td>
		</tr>
    </table>
</fieldset>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="type" value="field_x" />
<?php } ?>