<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php 
// Specific Attributes
$optMethod			=	array();
$optMethod[] 		=	JHTML::_( 'select.option', 0, JText::_( 'GET' ) );
$optMethod[] 		=	JHTML::_( 'select.option', 1, JText::_( 'POST' ) );
$selectMethod		=	( ! $this->isNew ) ? $this->item->bool : 0;
$lists['boolean'] 	=	JHTML::_( 'select.radiolist', $optMethod, 'bool', 'size="1" class="inputbox"', 'value', 'text', $selectMethod );

$optMode			=	array();
$optMode[] 			=	JHTML::_( 'select.option', 'all', JText::_( 'DEFAULT PHRASE' ) );
$optMode[] 			=	JHTML::_( 'select.option', 'exact', JText::_( 'EXACT PHRASE' ) );
$selectMode			=	( ! $this->isNew ) ? $this->item->format : 'exact';
$lists['mode'] 		=	JHTML::_( 'select.genericlist', $optMode, 'format', 'size="1" class="inputbox"', 'value', 'text', $selectMode );

$optMode			=	array();
$optMode[] 			=	JHTML::_( 'select.option', '5', '5' );
$optMode[] 			=	JHTML::_( 'select.option', '10', '10' );
$optMode[] 			=	JHTML::_( 'select.option', '15', '15' );
$optMode[] 			=	JHTML::_( 'select.option', '20', '20' );
$optMode[] 			=	JHTML::_( 'select.option', '25', '25' );
$optMode[] 			=	JHTML::_( 'select.option', '30', '30' );
$optMode[] 			=	JHTML::_( 'select.option', '50', '50' );
$optMode[] 			=	JHTML::_( 'select.option', '100', '100' );
$optMode[] 			=	JHTML::_( 'select.option', '0', JText::_( 'All' ) );
$optMode[] 			=	JHTML::_( 'select.option', '-1', JText::_( 'DEFAULT' ) );
$selectMode			=	( ! $this->isNew ) ? $this->item->size : '25';
$lists['limit']		=	JHTML::_( 'select.genericlist', $optMode, 'size', 'size="1" class="inputbox"', 'value', 'text', $selectMode );

$optActionMode			=	array();
$optActionMode[] 		=	JHTML::_( 'select.option', 0, JText::_( 'ARTICLES' ) );
$selectActionMode		=	0;
$lists['actionMode']	=	JHTML::_('select.genericlist', $optActionMode, 'location', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectActionMode );

$optText		=	array();
$optText[] 		=	JHTML::_( 'select.option', 0, JText::_( 'INTROTEXT' ) );
$optText[] 		=	JHTML::_( 'select.option', 1, JText::_( 'FULLTEXT' ) );
$selectText		=	( ! $this->isNew ) ? $this->item->bool2 : 0;
$lists['text'] 	=	JHTML::_( 'select.genericlist', $optText, 'bool2', 'size="1" class="inputbox"', 'value', 'text', $selectText );

$modals['message']			=	HelperjSeblod_Display::quickModalWysiwyg( 'EDITOR', $this->controller, 'message', 'pagebreak', 0, @$this->item->id, false );
$tooltips['link_message']	=	HelperjSeblod_Display::quickTooltipAjaxLink( JText::_( 'MESSAGE' ), $this->controller, 'message', @$this->item->id );

$optMessageStyle		=	array();
$optMessageStyle[] 		=	JHTML::_( 'select.option', '', JText::_( 'NONE' ) );
$optMessageStyle[] 		=	JHTML::_( 'select.option', 'message', JText::_( 'MESSAGE' ) );
$optMessageStyle[] 		=	JHTML::_( 'select.option', 'notice', JText::_( 'NOTICE' ) );
$optMessageStyle[] 		=	JHTML::_( 'select.option', 'text', JText::_( 'TEXT' ) );
$selectMessageStyle		=	( ! $this->isNew ) ? $this->item->style : '';
$lists['messageStyle'] 	=	JHTML::_( 'select.genericlist', $optMessageStyle, 'style', 'size="1" class="inputbox"', 'value', 'text', $selectMessageStyle );

$optTrash		=	array();
$optTrash[] 	=	JHTML::_( 'select.option', 0, JText::_( 'NONE' ) );
$optTrash[] 	=	JHTML::_( 'select.option', 1, JText::_( '1' ) );
$optTrash[] 	=	JHTML::_( 'select.option', 2, JText::_( '2' ) );
$optTrash[] 	=	JHTML::_( 'select.option', 3, JText::_( '3' ) );
$optTrash[] 	=	JHTML::_( 'select.option', 4, JText::_( '4' ) );
$optTrash[] 	=	JHTML::_( 'select.option', 5, JText::_( '5' ) );
$selectTrash	=	( ! $this->isNew ) ? $this->item->bool4 : 0;
$lists['trash']	=	JHTML::_( 'select.genericlist', $optTrash, 'bool4', 'size="1" class="inputbox"', 'value', 'text', $selectTrash );

$lists['cache']	=	JHTML::_( 'select.booleanlist', 'bool5', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool5 : 0 );

$optCache		=	array();
$optCache[] 	=	JHTML::_( 'select.option', 0, JText::_( 'No' ) );
$optCache[] 	=	JHTML::_( 'select.option', 1, JText::_( 'Yes' ) );
$optCache[] 	=	JHTML::_( 'select.option', 2, JText::_( 'YES BUT USER' ) );
$selectCache	=	( ! $this->isNew ) ? $this->item->bool5 : 0;
$lists['cache'] =	JHTML::_( 'select.genericlist', $optCache, 'bool5', 'size="1" class="inputbox"', 'value', 'text', $selectCache );

$optCache2			=	array();
$optCache2[] 		=	JHTML::_( 'select.option', 0, JText::_( 'No' ) );
$optCache2[] 		=	JHTML::_( 'select.option', 1, JText::_( 'Yes' ) );
$selectCache2		=	( ! $this->isNew ) ? $this->item->bool8 : 0;
$lists['cache2']	=	JHTML::_( 'select.genericlist', $optCache2, 'bool8', 'size="1" class="inputbox"', 'value', 'text', $selectCache2 );

$optDebug		=	array();
$optDebug[] 	=	JHTML::_( 'select.option', 0, JText::_( 'No' ) );
$optDebug[] 	=	JHTML::_( 'select.option', 1, JText::_( 'SIMPLE' ) );
$optDebug[] 	=	JHTML::_( 'select.option', 2, JText::_( 'ADVANCED' ) );
$selectDebug	=	( ! $this->isNew ) ? $this->item->bool6 : 0;
$lists['debug'] =	JHTML::_( 'select.genericlist', $optDebug, 'bool6', 'size="1" class="inputbox"', 'value', 'text', $selectDebug );

$optSEF			=	array();
$optSEF[] 		=	JHTML::_( 'select.option', -1, JText::_( 'OFF' ) );
$optSEF[] 		=	JHTML::_( 'select.option', 0, JText::_( 'OPTIMIZED' ) );
$optSEF[] 		=	JHTML::_( 'select.option', 1, JText::_( 'SHORT' ) );
$selectSEF		=	( ! $this->isNew ) ? $this->item->bool3 : 0;
$lists['sef']	=	JHTML::_( 'select.genericlist', $optSEF, 'bool3', 'size="1" class="inputbox"', 'value', 'text', $selectSEF );

$optTarget				=	array();
$optTarget[] 			=	JHTML::_( 'select.option', 0, JText::_( 'TARGET SELF' ) );
$optTarget[] 			=	JHTML::_( 'select.option', 1, JText::_( 'TARGET BLANK' ) );
$selectTarget			=	( ! $this->isNew ) ? $this->item->bool7 : 0;
$lists['target'] 		=	JHTML::_( 'select.genericlist', $optTarget, 'bool7', 'size="1" class="inputbox"', 'value', 'text', $selectTarget );

$optcacheGroup			=	array();
$optcacheGroup[] 		=	JHTML::_( 'select.option', 18, _NBSP.'-&nbsp;'.JText::_( 'Registered' ) );
$optcacheGroup[] 		=	JHTML::_( 'select.option', 19, _NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Author' ) );
$optcacheGroup[] 		=	JHTML::_( 'select.option', 20, _NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Editor' ) );
$optcacheGroup[] 		=	JHTML::_( 'select.option', 21, _NBSP._NBSP._NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Publisher' ) );
$optcacheGroup[] 		=	JHTML::_( 'select.option', 23, _NBSP.'-&nbsp;'.JText::_( 'Manager' ) );
$optcacheGroup[] 		=	JHTML::_( 'select.option', 24, _NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Administrator' ) );
$optcacheGroup[] 		=	JHTML::_( 'select.option', 25, _NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Super Administrator' ) );
$selectcacheGroup		=	( @$this->item->extra ) ? explode( ',', @$this->item->extra ) : array( 18, 19, 20, 21, 23, 24, 25 );
$lists['cacheGroup']	=	JHTML::_( 'select.genericlist', $optcacheGroup, 'extra[]', 'size="7" class="inputbox" multiple="multiple"', 'value', 'text', $selectcacheGroup );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH ACTION' ); ?>::<?php echo JText::_( 'DESCRIPTION SEARCH ACTION' ); ?>">
		<?php echo JText::_( 'SEARCH ACTION' ); ?>
    </span>
</legend>
	<table class="admintable">
		<tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ACTION MODE' ); ?>::<?php echo JText::_( 'SELECT ACTION MODE' ); ?>">
					<?php echo JText::_( 'ACTION MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['actionMode']; ?>
			</td>
		</tr>
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
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'METHOD' ); ?>::<?php echo JText::_( 'CHOOSE METHOD GET OR POST' ); ?>">
					<?php echo JText::_( 'METHOD' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['boolean']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'TARGET' ); ?>::<?php echo JText::_( 'SELECT TARGET' ); ?>">
					<?php echo JText::_( 'TARGET' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['target']; ?>
			</td>
		</tr>
        <tr>
            <td colspan="3">
            </td>
	    </tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PAGINATION LIMIT' ); ?>::<?php echo JText::_( 'PAGINATION LIMIT BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PAGINATION LIMIT' ); ?>::<?php echo JText::_( 'EDIT PAGINATION LIMIT' ); ?>">
					<?php echo JText::_( 'PAGINATION LIMIT' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['limit']; ?>
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH LIMIT' ); ?>::<?php echo JText::_( 'SEARCH LIMIT BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH LIMIT' ); ?>::<?php echo JText::_( 'EDIT SEARCH LIMIT' ); ?>">
					<?php echo JText::_( 'SEARCH LIMIT' ); ?>:
				</span>
			</td>
			<td>
	            <input class="inputbox" type="text" id="maxlength" name="maxlength" maxlength="50" size="16" value="<?php echo ( $this->isNew ) ? 500 : $this->item->maxlength; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH IN' ); ?>::<?php echo JText::_( 'EDIT SEARCH IN' ); ?>">
					<?php echo JText::_( 'SEARCH IN' ); ?>:
				</span>
			</td>
			<td>
	            <?php echo $lists['text']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH MATCH' ); ?>::<?php echo JText::_( 'SEARCH MATCH BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH MATCH' ); ?>::<?php echo JText::_( 'SELECT SEARCH MATCH' ); ?>">
					<?php echo JText::_( 'SEARCH MATCH' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['mode']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH LENGTH' ); ?>::<?php echo JText::_( 'SEARCH LENGTH BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH LENGTH' ); ?>::<?php echo JText::_( 'SELECT SEARCH LENGTH' ); ?>">
					<?php echo JText::_( 'SEARCH LENGTH' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['trash']; ?>
			</td>
		</tr>
		<tr>
        	<td colspan="3">
			</td>
        </tr>
		<tr>
        	<td colspan="3" class="keytext_jseblod">
            <?php echo JText::_( 'CACHING' ); ?>
			</td>
        </tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CACHE SEARCH' ); ?>::<?php echo JText::_( 'CACHE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CACHE SEARCH' ); ?>::<?php echo JText::_( 'CHOOSE ENABLED OR NOT' ); ?>">
					<?php echo JText::_( 'CACHE SEARCH' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['cache']; ?>
			</td>
		</tr>
		<tr id="as-cache-group" class="<?php echo ( @$this->item->bool5 == 2 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CACHE PER USER' ); ?>::<?php echo JText::_( 'CACHE PER USER BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CACHE PER USER' ); ?>::<?php echo JText::_( 'SELECT USER GROUP' ); ?>">
					<?php echo JText::_( 'CACHE PER USER' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['cacheGroup']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CACHE RENDER' ); ?>::<?php echo JText::_( 'CACHE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CACHE RENDER' ); ?>::<?php echo JText::_( 'CHOOSE ENABLED OR NOT' ); ?>">
					<?php echo JText::_( 'CACHE RENDER' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['cache2']; ?>
			</td>
		</tr>
		<tr>
        	<td colspan="3">
			</td>
        </tr>
		<tr>
        	<td colspan="3" class="keytext_jseblod">
            <?php echo JText::_( 'DEBUG' ); ?>
			</td>
        </tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEBUG' ); ?>::<?php echo JText::_( 'CHOOSE ENABLED OR NOT' ); ?>">
					<?php echo JText::_( 'DEBUG' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['debug']; ?>
			</td>
		</tr>
		<tr>
        	<td colspan="3">
			</td>
        </tr>
		<tr>
        	<td colspan="3" class="keytext_jseblod">
	            <?php echo JText::_( 'SEO' ); ?>
			</td>
        </tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEF URLS' ); ?>::<?php echo JText::_( 'SEF URLS BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEF URLS' ); ?>::<?php echo JText::_( 'SELECT SEF URLS' ); ?>">
					<?php echo JText::_( 'SEF URLS' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['sef']; ?>
			</td>
		</tr>
	</table>
	<table class="admintable header_jseblod">
		<tr>
			<td>
				<?php echo JText::_( 'NOTE NO RESULT ACTION').' :: '.JText::_( 'MESSAGE' ); ?>
			</td>
		</tr>
	</table>
	<table class="admintable">
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MESSAGE' ); ?>::<?php echo JText::_( 'MESSAGE SEARCH BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MESSAGE' ); ?>::<?php echo JText::_( 'EDIT MESSAGE' ); ?>">
					<?php echo JText::_( 'MESSAGE' ); ?>:
				</span>
			</td>
			<td>
                <span class="ajaxTip2" title="<?php echo $tooltips['link_message']; ?>">
					<?php echo $modals['message']; ?>
				</span>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MESSAGE STYLE' ); ?>::<?php echo JText::_( 'SELECT MESSAGE STYLE' ); ?>">
					<?php echo JText::_( 'MESSAGE STYLE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['messageStyle']; ?>
			</td>
		</tr>
	</table>
</fieldset>

<script type="text/javascript">
$("bool5").addEvent("change", function(c) {
	c = new Event(c).stop();
	
	var layout = $("bool5").value;
	
	if ( layout == 2 ) {
		if ( $("as-cache-group").hasClass("display-no") ) {
			$("as-cache-group").removeClass("display-no");
		}
	} else {
		if ( ! $("as-cache-group").hasClass("display-no") ) {
			$("as-cache-group").addClass("display-no");
		}			
	}
});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="search_action" />
<?php } ?>