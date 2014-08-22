<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optJoomlaCategories		=	HelperjSeblod_Helper::getJoomlaCategories();
$selectJoomlaCategories		=	explode( ',', @$this->item->options );
$lists['joomlaCategories']	=	JHTML::_( 'select.genericlist', $optJoomlaCategories, 'selected_categories[]', 'class="inputbox" size="15" multiple="multiple" style="width: 147px;"', 'value', 'text', $selectJoomlaCategories );

$optsearchContent		=	array();
$optsearchContent[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'CORE' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'id', JText::_( 'ID' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'title', JText::_( 'TITLE' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'state', JText::_( 'STATE' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'created_by', JText::_( 'AUTHOR ID' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'catid', JText::_( 'CATEGORY ID' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'parent_id', JText::_( 'CATEGORY PARENT ID' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'created', JText::_( 'CREATED DATE' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'publish_up', JText::_( 'START PUBLISHING' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'publish_down', JText::_( 'FINISH PUBLISHING' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'introtext', JText::_( 'INTROTEXT' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'fulltext', JText::_( 'FULLTEXT' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'metadesc', JText::_( 'METADESC' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'metakey', JText::_( 'METAKEY' ) );
$optsearchContent[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
$optsearchContent[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'ECOMMERCE' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'cart_id', JText::_( 'CART ID' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'cart', JText::_( 'CART OWNER' ) );
$optsearchContent[] 	=	JHTML::_( 'select.option', 'cart_state', JText::_( 'CART STATE' ) );
$optsearchContent[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
$size					=	count( $optsearchContent ) - 2;
$selectsearchContent	=	( ! $this->isNew ) ? explode( ',', @$this->item->content ) : 'title';
$lists['searchContent']	=	JHTML::_( 'select.genericlist', $optsearchContent, 'content[]', 'size="'.$size.'" class="inputbox" multiple="multiple"', 'value', 'text', $selectsearchContent );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH GENERIC' ); ?>::<?php echo JText::_( 'DESCRIPTION SEARCH GENERIC' ); ?>">
		<?php echo JText::_( 'SEARCH GENERIC' ); ?>
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
				<?php echo JText::_( 'NOTE ACTION').' :: '.JText::_( 'SEARCH' ); ?>
			</td>
		</tr>
	</table>
	<table class="admintable">
		<tr>
            <td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH IN' ); ?>::<?php echo JText::_( 'SEARCH IN BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH IN' ); ?>::<?php echo JText::_( 'SELECT SEARCH IN' ); ?>">
					<?php echo JText::_( 'SEARCH IN' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['searchContent']; ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'Required' ); ?>::<?php echo JText::_( 'CHOOSE REQUIRED OR NOT' ); ?>">
					<?php echo JText::_( 'Required' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['required']; ?>
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'VALIDATION' ); ?>::<?php echo JText::_( 'SELECT VALIDATION' ); ?>">
					<?php echo JText::_( 'VALIDATION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['validation']; ?>
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
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'STYLE' ); ?>::<?php echo JText::_( 'STYLE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'STYLE' ); ?>::<?php echo JText::_( 'EDIT STYLE' ); ?>">
					<?php echo JText::_( 'STYLE' ); ?>:
				</span>
			</td>
			<td>
				<textarea class="inputbox" id="style" name="style" cols="25" rows="2"><?php echo @$this->item->style; ?></textarea>
			</td>
		</tr>
	</table>
</fieldset>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="search_generic" />
<?php } ?>