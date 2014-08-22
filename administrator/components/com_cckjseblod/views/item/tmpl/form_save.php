<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optMode		=	array();
$optMode[] 		=	JHTML::_( 'select.option', 0, JText::_( 'ARTICLE MANAGER' ).' <I>( #__content )</I>' );
$optMode[] 		=	JHTML::_( 'select.option', 1, '... ... ... ?' );
$selectMode		=	( @$this->item->id ) ? @$this->item->bool : 0;
$lists['mode']	=	JHTML::_( 'select.radiolist', $optMode, 'bool', 'size="1" class="inputbox" disabled="disabled"', 'value', 'text', $selectMode );

$optInCategories		=	array();
$optInCategories[] 		=	JHTML::_( 'select.option', 0, JText::_( 'FREE CATEGORY SELECTION' ) );
$optInCategories[] 		=	JHTML::_( 'select.option', 2, JText::_( 'SUBCATEGORIES' ) );
$optInCategories[] 		=	JHTML::_( 'select.option', 1, JText::_( 'USER CATEGORIES' ) );
$selectInCategories		=	( ! $this->isNew ) ? $this->item->bool2 : 0;
$lists['authorOnly']	=	JHTML::_( 'select.genericlist', $optInCategories, 'bool2', 'size="1" class="inputbox"', 'value', 'text', $selectInCategories );

$optFormat			=	array();
$optFormat[] 		=	JHTML::_( 'select.option', 'generic', JText::_( 'DROPDOWN' ) );
$optFormat[] 		=	JHTML::_( 'select.option', 'radio_h', JText::_( 'RADIO H' ) );
$optFormat[] 		=	JHTML::_( 'select.option', 'radio_v', JText::_( 'RADIO V' ) );
$selectFormat		=	( @$this->item->id ) ? @$this->item->format : 'generic';
$lists['format']	=	JHTML::_( 'select.radiolist', $optFormat, 'format', 'size="1" class="inputbox"', 'value', 'text', $selectFormat );

$optJoomlaCategories		=	HelperjSeblod_Helper::getJoomlaCategories();
$selectJoomlaCategories		=	explode( ',', @$this->item->options );
$lists['joomlaCategories']	=	JHTML::_( 'select.genericlist', $optJoomlaCategories, 'selected_categories[]', 'class="inputbox" size="15" multiple="multiple" style="width: 147px;"', 'value', 'text', $selectJoomlaCategories );

$optSubstituteMode		=	array();
$optSubstituteMode[] 	=	JHTML::_( 'select.option', 0, JText::_( 'NONE' ) );
$optSubstituteMode[] 	=	JHTML::_( 'select.option', 2, JText::_( 'AS TITLE TEXT' ) );
$selectSubstituteMode	=	( ! $this->isNew ) ? $this->item->substitute : 0;
$lists['substitute'] 	=	JHTML::_( 'select.genericlist', $optSubstituteMode, 'substitute', 'size="1" class="inputbox"', 'value', 'text', $selectSubstituteMode );

$optStore				=	array();
$optStore[] 			=	JHTML::_( 'select.option', 0, JText::_( 'DEFAULT CATEGORY ID' ) );
$optStore[] 			=	JHTML::_( 'select.option', 1, JText::_( 'INDEX AS KEY' ) );
$selectStore			=	( ! $this->isNew ) ? $this->item->bool4 : 0;
$lists['store_mode']	=	JHTML::_( 'select.genericlist', $optStore, 'bool4', 'size="1" class="inputbox"', 'value', 'text', $selectStore );

$optIndexedKey[] 		=	JHTML::_( 'select.option', '', JText::_( 'SELECT AN INDEX' ) );
$keys					=	CCK::DB_loadObjectList( 'SELECT title as text, name as value FROM #__jseblod_cck_items WHERE indexedkey' );
if ( $keys ) {
	$optIndexedKey		=	array_merge( $optIndexedKey, $keys );
}
$selectIndexedKey		=	( ! $this->isNew ) ? $this->item->indexedxtd : '';
$lists['indexedKey']	=	JHTML::_( 'select.genericlist', $optIndexedKey, 'indexedxtd', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectIndexedKey );
?>

<fieldset class="adminform">
<legend class="legend-border">
  	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SAVE FORM' ); ?>::<?php echo JText::_( 'DESCRIPTION SAVE FORM' ); ?>">
		<?php echo JText::_( 'SAVE FORM' ); ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SAVE MODE' ); ?>::<?php echo JText::_( 'CHOOSE SAVE MODE' ); ?>">
					<?php echo JText::_( 'SAVE MODE' ); ?>:
				</span>
			</td>
	        <td>
	            <?php echo $lists['mode'] ?>
            </td>
        </tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'IN CATEGORIES' ); ?>::<?php echo JText::_( 'IN CATEGORIES BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'IN CATEGORIES' ); ?>::<?php echo JText::_( 'SELECT CATEGORIES' ); ?>">
					<?php echo JText::_( 'IN CATEGORIES' ); ?>:
				</span>
			</td>
			<td>
   				<?php echo $lists['authorOnly']; ?>
			</td>
		</tr>
		<tr id="as-free-selection" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CATEGORIES' ); ?>::<?php echo JText::_( 'SELECT CATEGORIES' ); ?>">
					<?php echo JText::_( 'CATEGORIES' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['joomlaCategories']; ?>
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
				<input class="inputbox" type="text" id="selectlabel" name="selectlabel" maxlength="50" size="32" value="<?php echo ( @$this->item->id ) ? $this->item->selectlabel : 'Select Category'; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MODE' ); ?>::<?php echo JText::_( 'SELECT MODE' ); ?>">
					<?php echo JText::_( 'MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['store_mode']; ?>
			</td>
		</tr>
		<tr id="as-indexedkey" class="<?php echo ( @$this->item->bool4 == 1 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'INDEX AS KEY' ); ?>::<?php echo JText::_( 'SELECT INDEX' ); ?>">
					<?php echo JText::_( 'INDEX AS KEY' ); ?>:
				</span>
			</td>
			<td>
                <?php echo $lists['indexedKey']; ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'STYLE' ); ?>::<?php echo JText::_( 'CHOOSE STYLE' ); ?>">
					<?php echo JText::_( 'STYLE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['format']; ?>
			</td>
		</tr>
	</table>
</fieldset>

<script type="text/javascript">
$("bool2").addEvent("change", function(c) {
	c = new Event(c).stop();
	
	var layout = $("bool2").value;
	
	if ( layout == 0 || layout == 2 ) {
		if ( $("as-free-selection").hasClass("display-no") ) {
			$("as-free-selection").removeClass("display-no");
		}
	} else {
		if ( ! $("as-free-selection").hasClass("display-no") ) {
			$("as-free-selection").addClass("display-no");
		}			
	}
});
$("bool4").addEvent("change", function(i) {
	i = new Event(i).stop();
	
	if ( $("bool4").value == 1 ) {
		if ( $("as-indexedkey").hasClass("display-no") ) {
			$("as-indexedkey").removeClass("display-no");
		}
	} else {
		if ( ! $("as-indexedkey").hasClass("display-no") ) {
			$("as-indexedkey").addClass("display-no");
		}
	}
});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="save" />
<?php } ?>