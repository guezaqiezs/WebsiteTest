<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
global	$mainframe;
$dbpref	=	$mainframe->getCfg('dbprefix');
$db		=&	JFactory::getDBO();
		
if ( @$this->item->id && @$this->item->options && JString::strpos( @$this->item->options, '||' ) !== false ) {
	$request			=	explode( '||', $this->item->options );
	$selectTables		=	$request[0];
	$selectFields		=	$request[1];
	$selectSecondFields	=	$request[2];
	$selectThirdFields	=	$this->item->location;
	$where				=	explode( '||', $this->item->content );
	$orderby			=	explode( '||', $this->item->extra );
		
	$fields		=	$db->getTableFields( $selectTables, true );
	$optFields	=	array();
	if ( sizeof( $fields[$selectTables] ) ) {
		foreach ( $fields[$selectTables] as $field => $type ) {
			$optFields[]	=	JHTML::_( 'select.option', $field, $field );
		}
		$list['fields']			=&	JHTML::_( 'select.genericlist', $optFields, 'db_fields', 'class="required required-enabled"', 'value', 'text', $selectFields, 'db_fields' );
		$list['secondfields']	=&	JHTML::_( 'select.genericlist', $optFields, 'db_secondfields', 'class="required required-enabled"', 'value', 'text', $selectSecondFields, 'db_secondfields' );
		$list['thirdfields']	=&	JHTML::_( 'select.genericlist', $optFields, 'db_thirdfields', 'class="required required-enabled"', 'value', 'text', $selectThirdFields, 'db_thirdfields' );
		$optSFields		=	array();
		$optSFields[]	=	JHTML::_( 'select.option', '', JText::_( 'SELECT A FIELD' ) );
		$optSFields		=	array_merge( $optSFields, $optFields );
		$list['fourthfields']	=&	JHTML::_( 'select.genericlist', $optSFields, 'db_fourthfields', '', 'value', 'text', @$where[0], 'db_fourthfields' );
		$list['fifthfields']	=&	JHTML::_( 'select.genericlist', $optSFields, 'db_fifthfields', '', 'value', 'text', $orderby[0], 'db_fifthfields' );
	}
} else {
	$selectTables	=	'';
}

$optTables		=	array();
$optTables[]	=	JHTML::_( 'select.option', '', JText::_( 'SELECT A TABLE' ) );
$tables 		=	$db->getTableList();
$cleanTable		=	str_replace( '#__', $dbpref, $selectTables );
if ( sizeof( $tables ) ) {
	foreach ( $tables as $table ) {
	   	$optTables[]	=	JHTML::_( 'select.option', $table, $table );
	}
	$list['tables'] = JHTML::_( 'select.genericlist', $optTables, 'db_tables', 'class="required required-enabled"', 'value', 'text', $cleanTable, 'db_tables' );
}

// Where Clause
$optWhere			=	array();
$selectWhere		=	( ! $this->isNew ) ? @$where[1] : '';
$optWhere[]			=	JHTML::_( 'select.option',  '', JText::_( 'SELECT AN OPERATOR' ), 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  '=', JText::_( '=' ), 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  '!=', JText::_( '!=' ), 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  'SUP', JText::_( '>' ), 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  'INF', JText::_( '<' ), 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  'IN', 'IN', 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  'NOTIN', 'NOT IN', 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  'LIKE%', JText::_( 'LIKE %...' ), 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  '%LIKE', JText::_( 'LIKE ...%' ), 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  '%LIKE%', JText::_( 'LIKE %...%' ), 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  'NOTLIKE%', JText::_( 'NOT LIKE %...' ), 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  '%NOTLIKE', JText::_( 'NOT LIKE ...%' ), 'value', 'text' );
$optWhere[]			=	JHTML::_( 'select.option',  '%NOTLIKE%', JText::_( 'NOT LIKE %...%' ), 'value', 'text' );
$lists['where']		=	JHTML::_( 'select.genericlist', $optWhere, 'db_fourth', 'size="1"', 'value', 'text', $selectWhere, 'db_fourth' );

// Order By
$optOrderBy			=	array();
$selectOrderBy		=	( ! $this->isNew ) ? @$orderby[1] : 'ASC';
$optOrderBy[]		=	JHTML::_( 'select.option',  'ASC', JText::_( 'ASCENDANT' ), 'value', 'text' );
$optOrderBy[]		=	JHTML::_( 'select.option',  'DESC', JText::_( 'DESCENDANT' ), 'value', 'text' );
$lists['orderby']	=	JHTML::_( 'select.genericlist', $optOrderBy, 'db_fifth', 'size="1"', 'value', 'text', $selectOrderBy, 'db_fifth' );

// Set Ajax Mode List
$optAjaxMode		=	array();
$selectAjaxMode	=	( @$this->item->bool ) ? $this->item->bool : 0;
$optAjaxMode[]	=	JHTML::_( 'select.option',  '0', JText::_( 'NONE' ), 'value', 'text' );
$optAjaxMode[]	=	JHTML::_( 'select.option',  '1', JText::_( 'AS CHILD' ), 'value', 'text' );
$optAjaxMode[]	=	JHTML::_( 'select.option',  '2', JText::_( 'AS PARENT' ), 'value', 'text' );

$lists['ajaxMode']	=	JHTML::_( 'select.genericlist', $optAjaxMode, 'bool', 'class="inputbox" size="1"', 'value', 'text', $selectAjaxMode, 'bool' );

// Set Custom
$optQuery		=	array();
$selectQuery	=	( @$this->item->bool2 ) ? $this->item->bool2 : 0;
$optQuery[]		=	JHTML::_( 'select.option',  0, JText::_( 'CONSTRUCTION' ), 'value', 'text' );
$optQuery[]		=	JHTML::_( 'select.option',  1, JText::_( 'FREE' ), 'value', 'text' );

$lists['query']	=	JHTML::_( 'select.genericlist', $optQuery, 'bool2', 'class="inputbox" size="1"', 'value', 'text', $selectQuery, 'bool2' );

$optStore				=	array();
$optStore[] 			=	JHTML::_( 'select.option', 0, JText::_( 'DEFAULT OPTION VALUE' ) );
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
  	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SELECT DYNAMIC' ); ?>::<?php echo JText::_( 'DESCRIPTION SELECT DYNAMIC' ); ?>">
		<?php echo JText::_( 'SELECT DYNAMIC' ); ?>
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
			<td colspan"2">
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
			<td colspan="2">
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
			<td colspan="2">
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
			<td colspan="2">
				<?php echo $this->lists['required']; ?>
			</td>
		</tr>
		<tr>
			<td colspan="4">
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'QUERY' ); ?>::<?php echo JText::_( 'SELECT QUERY MODE' ); ?>">
					<?php echo JText::_( 'QUERY' ); ?>:
				</span>
			</td>
			<td colspan="2">
				<?php echo $lists['query']; ?>
			</td>
		</tr>
		<tr id="as-construct1" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'TABLE' ); ?>::<?php echo JText::_( 'TABLE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'TABLE' ); ?>::<?php echo JText::_( 'SELECT SQL TABLE' ); ?>">
					<?php echo JText::_( 'TABLE' ); ?>:
				</span>
			</td>
			<td align="left" id="tables-container" colspan="2">
				<?php echo $list['tables']; ?>
			</td>
		</tr>
		<tr id="as-construct2" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'TEXT OPTIONS' ); ?>::<?php echo JText::_( 'TEXT OPTIONS BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'TEXT OPTIONS' ); ?>::<?php echo JText::_( 'SELECT TEXT OPTIONS' ); ?>">
					<?php echo JText::_( 'TEXT OPTIONS' ); ?>:
				</span>
			</td>
			<td align="left" id="fields-container" colspan="2">
				<?php echo @$list['fields']; ?>
			</td>
		</tr>
		<tr id="as-construct3" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'VALUE OPTIONS' ); ?>::<?php echo JText::_( 'VALUE OPTIONS BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'VALUE OPTIONS' ); ?>::<?php echo JText::_( 'SELECT VALUE OPTIONS' ); ?>">
					<?php echo JText::_( 'VALUE OPTIONS' ); ?>:
				</span>
			</td>
			<td align="left" id="secondfields-container" colspan="2">
				<?php echo @$list['secondfields']; ?>
			</td>
		</tr>
        <tr id="as-construct4" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WHERE CLAUSE' ); ?>::<?php echo JText::_( 'CONSTRUCT WHERE CLAUSE' ); ?>">
					<?php echo JText::_( 'WHERE CLAUSE' ); ?>:
				</span>
			</td>
			<td align="left" id="fourthfields-container">
				<?php echo @$list['fourthfields']; ?>
			</td>
            <td>
            <?php echo $lists['where'] .'&nbsp;'; ?>
           <input class="inputbox" type="text" id="db_fourth_content" name="db_fourth_content" maxlength="250" size="40" value="<?php echo @$where[2]; ?>" />
			</td>
		</tr>
       	<tr id="as-construct5" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WHERE CLAUSE' ); ?>::<?php echo JText::_( 'EDIT WHERE CLAUSE' ); ?>">
					<?php echo JText::_( 'WHERE CLAUSE' ); ?>:
				</span>
			</td>
            <td colspan="2">
				<input class="inputbox" type="text" id="options2" name="options2" maxlength="250" size="48" value='<?php echo @$this->item->options2; ?>' />
			</td>
		</tr>
        <tr id="as-construct6" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ORDER BY' ); ?>::<?php echo JText::_( 'CONSTRUCT ORDER BY' ); ?>">
					<?php echo JText::_( 'ORDER BY' ); ?>:
				</span>
			</td>
			<td align="left" id="fifthfields-container">
				<?php echo @$list['fifthfields']; ?>
			</td>
            <td valign="top">
	            <?php echo $lists['orderby']; ?>
            </td>
		</tr>
        <tr id="as-free" class="<?php echo ( @$this->item->bool2 == 1 ) ? '' : 'display-no' ?>">
            <td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FREE QUERY' ); ?>::<?php echo JText::_( 'FREE QUERY BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FREE QUERY' ); ?>::<?php echo JText::_( 'EDIT FREE QUERY' ); ?>">
					<?php echo JText::_( 'FREE QUERY' ); ?>:
				</span>
			</td>
			<td>
				<textarea class="inputbox" id="options" name="options" cols="30" rows="5"><?php echo @$this->item->options; ?></textarea>
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
			<td colspan="2">
				<input class="inputbox" type="text" id="selectlabel" name="selectlabel" maxlength="50" size="32" value="<?php echo ( @$this->item->id ) ? $this->item->selectlabel : 'Select an Option'; ?>" />
			</td>
		</tr>
        <tr>
			<td colspan="4">
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
			<td colspan="2">
				<input class="inputbox" type="text" id="defaultvalue" name="defaultvalue" maxlength="250" size="32" value="<?php echo @$this->item->defaultvalue; ?>" />
			</td>
		</tr>
		<tr id="as-indexedkey-1" class="<?php echo ( ($cleanTable == $dbpref.'content' || $cleanTable == $dbpref.'categories') && @$selectSecondFields == 'id' ) ? '' : 'display-no'; ?>">
			<td colspan="3">
			</td>
		</tr>
        <tr id="as-indexedkey-2" class="<?php echo ( ($cleanTable == $dbpref.'content' || $cleanTable == $dbpref.'categories') && @$selectSecondFields == 'id' ) ? '' : 'display-no'; ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MODE' ); ?>::<?php echo JText::_( 'INDEXED MODE ON SELECT DYNAMIC BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
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
		<tr id="as-indexedkey" class="<?php echo ( ($cleanTable == $dbpref.'content' || $cleanTable == $dbpref.'categories') && @$selectSecondFields == 'id' && @$this->item->bool4 == 1 ) ? ''
																																													: 'display-no'; ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'INDEX AS KEY' ); ?>::<?php echo JText::_( 'SELECT INDEX' ); ?>">
					<?php echo JText::_( 'INDEX AS KEY' ); ?>:
				</span>
			</td>
			<td colspan="2">
            	<?php echo $lists['indexedKey']; ?>
			</td>
		</tr>
		<tr id="as-construct7" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : '' ?>">
			<td colspan="4">
			</td>
		</tr>
        <tr id="as-construct8" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : '' ?>">
        	<td colspan="4" class="keytext_jseblod">
            <?php echo JText::_( 'AJAX CASCADE'); ?>
			</td>
        </tr>
		<tr id="as-construct9" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'AJAX MODE' ); ?>::<?php echo JText::_( 'AJAX MODE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'AJAX MODE' ); ?>::<?php echo JText::_( 'SELECT AJAX MODE' ); ?>">
					<?php echo JText::_( 'AJAX MODE' ); ?>:
				</span>
			</td>
			<td colspan="2">
				<?php echo $lists['ajaxMode']; ?>
			</td>
		</tr>
		<tr id="as-parent" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : ( ( @$this->item->bool == 1 || @$this->item->bool == 3 ) ? '' : 'display-no' ) ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'AJAX PARENT FIELD' ); ?>::<?php echo JText::_( 'AJAX PARENT FIELD BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'AJAX PARENT FIELD' ); ?>::<?php echo JText::_( 'SELECT AJAX PARENT FIELD' ); ?>">
					<?php echo JText::_( 'AJAX PARENT FIELD' ); ?>:
				</span>
			</td>
			<td id="thirdfields-container" colspan="2">
				<?php echo @$list['thirdfields']; ?>
			</td>
		</tr>
		<tr id="as-child" class="<?php echo ( @$this->item->bool2 == 1 ) ? 'display-no' : ( ( @$this->item->bool == 2 || @$this->item->bool == 3 ) ? '' : 'display-no' ) ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'AJAX CHILD ITEM' ); ?>::<?php echo JText::_( 'AJAX CHILD ITEM BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'AJAX CHILD ITEM' ); ?>::<?php echo JText::_( 'SELECT AJAX CHILD ITEM' ); ?>">
					<?php echo JText::_( 'AJAX CHILD ITEM' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-disabled" type="text" id="extended_title" name="extended_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$this->item->extended ) ? $this->item->extendedTitle : ''; ?>" />
				<input type="hidden" id="extended" name="extended" value="<?php echo @$this->item->extended; ?>" />
				<input type="hidden" id="extended_id" name="extended_id" value="<?php echo @$this->item->extendedId; ?>" />
			</td>
			<td>
				<?php echo $this->modals['selectItem']; ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'GROUP ACCESS' ); ?>::<?php echo JText::_( 'GROUP ACCESS BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'GROUP ACCESS' ); ?>::<?php echo JText::_( 'SELECT GROUP ACCESS' ); ?>">
					<?php echo JText::_( 'GROUP ACCESS' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['gACL']; ?>
			</td>
		</tr>
    </table>
</fieldset>

<script type="text/javascript">
$("bool").addEvent("change", function(i) {
		i = new Event(i).stop();
		
		var layout = $("bool").value;
		
		switch( layout )
		{
			case "1":
				if ( $("as-parent").hasClass("display-no") ) {
					$("as-parent").removeClass("display-no");
				}
				if ( ! $("as-child").hasClass("display-no") ) {
					$("as-child").addClass("display-no");
				}
				break;
			case "2":
				if ( $("as-child").hasClass("display-no") ) {
					$("as-child").removeClass("display-no");
				}
				if ( ! $("as-parent").hasClass("display-no") ) {
					$("as-parent").addClass("display-no");
				}
				break;
			default:
				if ( ! $("as-child").hasClass("display-no") ) {
					$("as-child").addClass("display-no");
				}
				if ( ! $("as-parent").hasClass("display-no") ) {
					$("as-parent").addClass("display-no");
				}
			break;
		}
		
	});
$("bool2").addEvent("change", function(q) {
		q = new Event(q).stop();
		
		var layout = $("bool2").value;
		
		if( layout == 1 ) {
			if ( ! $("as-construct1").hasClass("display-no") ) {
				$("as-construct1").addClass("display-no");
				$("as-construct2").addClass("display-no");
				$("as-construct3").addClass("display-no");
				$("as-construct4").addClass("display-no");
				$("as-construct5").addClass("display-no");
				$("as-construct6").addClass("display-no");
				$("as-construct7").addClass("display-no");
				$("as-construct8").addClass("display-no");
				$("as-construct9").addClass("display-no");
			}			
			if ( ! $("as-child").hasClass("display-no") ) {
				$("as-child").addClass("display-no");
			}
			if ( ! $("as-parent").hasClass("display-no") ) {
				$("as-parent").addClass("display-no");
			}
			if ( $("as-free").hasClass("display-no") ) {
				$("as-free").removeClass("display-no");
			}
		} else {
			if ( $("as-construct1").hasClass("display-no") ) {
				$("as-construct1").removeClass("display-no");
				$("as-construct2").removeClass("display-no");
				$("as-construct3").removeClass("display-no");
				$("as-construct4").removeClass("display-no");
				$("as-construct5").removeClass("display-no");
				$("as-construct6").removeClass("display-no");
				$("as-construct7").removeClass("display-no");
				$("as-construct8").removeClass("display-no");
				$("as-construct9").removeClass("display-no");
			}
			if ( $("bool").value == 2 && $("as-child").hasClass("display-no") ) {
				$("as-child").removeClass("display-no");
			}
			if ( $("bool").value == 1 && $("as-parent").hasClass("display-no") ) {
				$("as-parent").removeClass("display-no");
			}	
			if ( ! $("as-free").hasClass("display-no") ) {
				$("as-free").addClass("display-no");
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
<input type="hidden" name="type" value="select_dynamic" />
<?php } ?>