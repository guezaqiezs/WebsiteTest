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

$lists['authorOnly']		=	JHTML::_('select.booleanlist', 'bool2', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool2 : 0 );

$lists['nextArticles']		=	JHTML::_( 'select.booleanlist', 'bool', 'class="inputbox"', @$this->item->bool );

$lists['itemContent']		=	JHTML::_( 'select.booleanlist', 'bool5', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool5 : 0 );
$lists['itemdList']			=	JHTML::_( 'select.booleanlist', 'bool6', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool6 : 0 );

$optItemContent			=	array();
$optItemContent[] 		=	JHTML::_( 'select.option', -1, JText::_( 'ARTICLE ID' ) );
$optItemContent[] 		=	JHTML::_( 'select.option', 1, JText::_( 'ARTICLE LINK' ) );
$optItemContent[] 		=	JHTML::_( 'select.option', 3, JText::_( 'ARTICLE TITLE' ) );
$optItemContent[] 		=	JHTML::_( 'select.option', 2, JText::_( 'CUSTOM FIELD' ) );
$optItemContent[] 		=	JHTML::_( 'select.option', 0, JText::_( 'FULL CONTENT' ) );
$optItemContent[] 		=	JHTML::_( 'select.option', -2, JText::_( 'STORED VALUE' ) );
$selectItemContent		=	( ! $this->isNew ) ? $this->item->bool5 : 1;
$lists['itemContent'] 	=	JHTML::_( 'select.genericlist', $optItemContent, 'bool5', 'size="1" class="inputbox"', 'value', 'text', $selectItemContent );

$optItemList			=	array();
$optItemList[] 			=	JHTML::_( 'select.option', -1, JText::_( 'ARTICLE ID' ) );
$optItemList[] 			=	JHTML::_( 'select.option', 1, JText::_( 'ARTICLE LINK' ) );
$optItemList[] 			=	JHTML::_( 'select.option', 3, JText::_( 'ARTICLE TITLE' ) );
$optItemList[] 			=	JHTML::_( 'select.option', 2, JText::_( 'CUSTOM FIELD' ) );
$optItemList[] 			=	JHTML::_( 'select.option', 0, JText::_( 'FULL CONTENT' ) );
$optItemList[] 			=	JHTML::_( 'select.option', -2, JText::_( 'STORED VALUE' ) );
$selectItemList			=	( ! $this->isNew ) ? $this->item->bool6 : -1;
$lists['itemList']	 	=	JHTML::_( 'select.genericlist', $optItemList, 'bool6', 'size="1" class="inputbox"', 'value', 'text', $selectItemList );

$optItemCart			=	array();
$optItemCart[] 			=	JHTML::_( 'select.option', -1, JText::_( 'ARTICLE ID' ) );
$optItemCart[] 			=	JHTML::_( 'select.option', 1, JText::_( 'ARTICLE LINK' ) );
$optItemCart[] 			=	JHTML::_( 'select.option', 3, JText::_( 'ARTICLE TITLE' ) );
$optItemCart[] 			=	JHTML::_( 'select.option', 2, JText::_( 'CUSTOM FIELD' ) );
$optItemCart[] 			=	JHTML::_( 'select.option', 0, JText::_( 'FULL CONTENT' ) );
$optItemCart[] 			=	JHTML::_( 'select.option', -2, JText::_( 'STORED VALUE' ) );
$selectItemCart			=	( ! $this->isNew ) ? $this->item->bool7 : -1;
$lists['itemCart']	 	=	JHTML::_( 'select.genericlist', $optItemCart, 'bool7', 'size="1" class="inputbox"', 'value', 'text', $selectItemCart );

$options2	=	explode( '||', @$this->item->options2 );
$opt1		=	@$options2[0];
$opt2		=	@$options2[1];
$opt3		=	@$options2[2];

$optSubstituteMode		=	array();
$optSubstituteMode[] 	=	JHTML::_( 'select.option', 0, JText::_( 'NONE' ) );
$optSubstituteMode[] 	=	JHTML::_( 'select.option', 2, JText::_( 'AS TITLE TEXT' ) );
$selectSubstituteMode	=	( ! $this->isNew ) ? $this->item->substitute : 0;
$lists['substitute'] 	=	JHTML::_( 'select.genericlist', $optSubstituteMode, 'substitute', 'size="1" class="inputbox"', 'value', 'text', $selectSubstituteMode );

$optStore				=	array();
$optStore[] 			=	JHTML::_( 'select.option', 0, JText::_( 'DEFAULT ARTICLE ID' ) );
$optStore[] 			=	JHTML::_( 'select.option', 1, JText::_( 'INDEX AS KEY' ) );
$selectStore			=	( ! $this->isNew ) ? $this->item->bool4 : 0;
$lists['store_mode']	=	JHTML::_( 'select.genericlist', $optStore, 'bool4', 'size="1" class="inputbox"', 'value', 'text', $selectStore );

$optIndexed			=	array();
$optIndexed[] 		=	JHTML::_( 'select.option', 0, JText::_( 'No' ) );
$optIndexed[] 		=	JHTML::_( 'select.option', 1, JText::_( 'Yes' ) );
$selectIndexed		=	( ! $this->isNew ) ? $this->item->indexed : 0;
$lists['indexed'] 	=	JHTML::_( 'select.radiolist', $optIndexed, 'indexed', 'size="1" class="inputbox"', 'value', 'text', $selectIndexed );

$optIndexedKey[] 	=	JHTML::_( 'select.option', '', JText::_( 'SELECT AN INDEX' ) );
$keys				=	CCK::DB_loadObjectList( 'SELECT title as text, name as value FROM #__jseblod_cck_items WHERE indexedkey' );
if ( $keys ) {
	$optIndexedKey	=	array_merge( $optIndexedKey, $keys );
}
$selectIndexedKey	=	( ! $this->isNew ) ? $this->item->indexedxtd : '';
$lists['indexedKey']=	JHTML::_( 'select.genericlist', $optIndexedKey, 'indexedxtd', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectIndexedKey );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'EXTERNAL ARTICLE' ); ?>::<?php echo JText::_( 'DESCRIPTION EXTERNAL ARTICLE' ); ?>">
		<?php echo JText::_( 'EXTERNAL ARTICLE' ); ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'IN ARTICLES' ); ?>::<?php echo JText::_( 'CHOOSE STATES' ); ?>">
					<?php echo JText::_( 'IN ARTICLES' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['content']; ?>
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
				<?php echo $lists['joomlaCategories']; ?>
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
				<input class="inputbox required required-enabled" type="text" id="selectlabel" name="selectlabel" maxlength="50" size="32" value="<?php echo ( @$this->item->selectlabel ) ? $this->item->selectlabel : 'Select an Article'; ?>" />
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FROM AUTHOR ONLY' ); ?>::<?php echo JText::_( 'FROM AUTHOR ONLY BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FROM AUTHOR ONLY' ); ?>::<?php echo JText::_( 'CHOOSE FROM AUTHOR ONLY OR NOT' ); ?>">
					<?php echo JText::_( 'FROM AUTHOR ONLY' ); ?>:
				</span>
			</td>
			<td>
   				<?php echo $lists['authorOnly']; ?>
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
        <tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
	            <?php echo JText::_( 'NEXT ARTICLES'); ?>&nbsp;&nbsp;<span id="next-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[Toggle]</span>
			</td>
        </tr>
        <tr id="as-next" class="<?php echo ( @$this->item->bool ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'NEXT ARTICLES' ); ?>::<?php echo JText::_( 'NEXT ARTICLES BALLOON' ); ?>">
					<?php echo _IMG_WARNING; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'NEXT ARTICLES' ); ?>::<?php echo JText::_( 'CHOOSE ENABLE NEXT ARTICLES OR NOT' ); ?>">
					<?php echo JText::_( 'NEXT ARTICLES' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['nextArticles']; ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY ON CONTENT' ); ?>::<?php echo JText::_( 'DISPLAY ON CONTENT BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY ON CONTENT' ); ?>::<?php echo JText::_( 'SELECT DISPLAY MODE ON CONTENT' ); ?>">
					<?php echo JText::_( 'DISPLAY ON CONTENT' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['itemContent']; ?>
			</td>
		</tr>
		<tr id="as-content" class="<?php echo ( @$this->item->bool5 == 2 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CUSTOM FIELD' ); ?>::<?php echo JText::_( 'EDIT CUSTOM FIELD' ); ?>">
					<?php echo JText::_( 'CUSTOM FIELD' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="options2_content" name="options2_content" maxlength="50" size="32" value="<?php echo ( @$opt1 ) ? $opt1 : ''; ?>" />
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY ON LIST' ); ?>::<?php echo JText::_( 'DISPLAY ON LIST BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY ON LIST' ); ?>::<?php echo JText::_( 'SELECT DISPLAY MODE ON LIST' ); ?>">
					<?php echo JText::_( 'DISPLAY ON LIST' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['itemList']; ?>
			</td>
		</tr>
		<tr id="as-list" class="<?php echo ( @$this->item->bool6 == 2 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CUSTOM FIELD' ); ?>::<?php echo JText::_( 'EDIT CUSTOM FIELD' ); ?>">
					<?php echo JText::_( 'CUSTOM FIELD' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="options2_list" name="options2_list" maxlength="50" size="32" value="<?php echo ( @$opt2 ) ? $opt2 : ''; ?>" />
			</td>
		</tr>
       <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY ON CART' ); ?>::<?php echo JText::_( 'DISPLAY ON CART BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY ON CART' ); ?>::<?php echo JText::_( 'SELECT DISPLAY MODE ON CART' ); ?>">
					<?php echo JText::_( 'DISPLAY ON CART' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['itemCart']; ?>
			</td>
		</tr>
		<tr id="as-cart" class="<?php echo ( @$this->item->bool7 == 2 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CUSTOM FIELD' ); ?>::<?php echo JText::_( 'EDIT CUSTOM FIELD' ); ?>">
					<?php echo JText::_( 'CUSTOM FIELD' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="options2_cart" name="options2_cart" maxlength="50" size="32" value="<?php echo ( @$opt3 ) ? $opt3 : ''; ?>" />
			</td>
		</tr>
    </table>
</fieldset>

<script type="text/javascript">
$("next-toggle").addEvent("click", function(n) {
	n = new Event(n).stop();
	
	if ( $("as-next").hasClass("display-no") ) {
		$("as-next").removeClass("display-no");
	} else {
		$("as-next").addClass("display-no");
	}
});
$("bool5").addEvent("change", function(c) {
	c = new Event(c).stop();
	
	if ( $("bool5").value == 2 ) {
		if ( $("as-content").hasClass("display-no") ) {
			$("as-content").removeClass("display-no");
		}
	} else {
		if ( ! $("as-content").hasClass("display-no") ) {
			$("as-content").addClass("display-no");
		}
	}
});
$("bool6").addEvent("change", function(c2) {
	c2 = new Event(c2).stop();
	
	if ( $("bool6").value == 2 ) {
		if ( $("as-list").hasClass("display-no") ) {
			$("as-list").removeClass("display-no");
		}
	} else {
		if ( ! $("as-list").hasClass("display-no") ) {
			$("as-list").addClass("display-no");
		}
	}
});
$("bool7").addEvent("change", function(c3) {
	c3 = new Event(c3).stop();
	
	if ( $("bool7").value == 2 ) {
		if ( $("as-cart").hasClass("display-no") ) {
			$("as-cart").removeClass("display-no");
		}
	} else {
		if ( ! $("as-cart").hasClass("display-no") ) {
			$("as-cart").addClass("display-no");
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
<input type="hidden" name="type" value="external_article" />
<?php } ?>