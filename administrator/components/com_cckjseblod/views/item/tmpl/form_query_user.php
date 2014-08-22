<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
global	$mainframe;
$db		=&	JFactory::getDBO();
$dbpref	=	$mainframe->getCfg('dbprefix');

if ( @$this->item->id && @$this->item->options && JString::strpos( @$this->item->options, '||' ) !== false ) {
	$request			=	explode( '||', $this->item->options );
	$selectTables		=	$request[0];
	$selectFields		=	$request[1];
	$selectSecondFields	=	$request[2];
	
	$fields		=	$db->getTableFields( $selectTables, true );
	$optFields	=	array();
	if ( sizeof( $fields[$selectTables] ) ) {
		foreach ( $fields[$selectTables] as $field => $type ) {
			$optFields[]	=	JHTML::_( 'select.option', $field, $field );
		}
		$list['fields']			=&	JHTML::_( 'select.genericlist', $optFields, 'db_fields', 'class="required required-enabled"', 'value', 'text', $selectFields, 'db_fields' );
		$list['secondfields']	=&	JHTML::_( 'select.genericlist', $optFields, 'db_secondfields', 'class="required required-enabled"', 'value', 'text', $selectSecondFields, 'db_secondfields' );
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
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'QUERY USER' ); ?>::<?php echo JText::_( 'DESCRIPTION QUERY USER' ); ?>">
		<?php echo JText::_( 'QUERY USER' ); ?>
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
   	<table class="admintable header_jseblod" >
		<tr>
			<td>
				<?php echo JText::_( 'NOTE FORM').' :: '.JText::_( 'CONSTRUCTION' ); ?>
			</td>
		</tr>
	</table>
    <table class="admintable">
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SQL QUERY' ); ?>::<?php echo JText::_( 'SQL QUERY BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SQL QUERY' ); ?>::<?php echo JText::_( 'CREATE SQL QUERY' ); ?>">
					<?php echo JText::_( 'SQL QUERY' ); ?>:
				</span>
			</td>
			<td id="tables-container">
				<?php echo $list['tables']; ?>
			</td>
			<td id="fields-container">
				<?php echo @$list['fields']; ?>
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WHERE' ); ?>::<?php echo JText::_( 'WHERE USER BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WHERE' ); ?>::<?php echo JText::_( 'SELECT WHERE FIELD EQUAL USERID' ); ?>">
					<?php echo JText::_( 'WHERE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo JText::_( 'EQUAL USER ID' ); ?>
			</td>
			<td id="secondfields-container">
				<?php echo @$list['secondfields']; ?>
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

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="query_user" />
<?php } ?>