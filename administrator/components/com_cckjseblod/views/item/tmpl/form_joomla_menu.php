<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optDisplayField[] 		=	JHTML::_( 'select.option', -1, JText::_( 'No' ) );
$optDisplayField[] 		=	JHTML::_( 'select.option', 0, JText::_( 'Yes' ) );
$selectOptDisplayField	=	( @$this->item->id ) ? @$this->item->displayfield : -1;
$lists['displayField']	=	JHTML::_( 'select.radiolist', $optDisplayField, 'displayfield', 'size="1" class="inputbox"', 'value', 'text', $selectOptDisplayField );

$optLayout[] 		=	JHTML::_( 'select.option', 0, JText::_( 'ARTICLE LAYOUT' ) );
$optLayout[] 		=	JHTML::_( 'select.option', 1, JText::_( 'CATEGORY BLOG LAYOUT' ) );
$optLayout[] 		=	JHTML::_( 'select.option', 2, JText::_( 'CATEGORY LIST LAYOUT' ) );
$selectLayout		=	( @$this->item->id ) ? @$this->item->bool2 : 0;
$lists['layout']	=	JHTML::_( 'select.genericlist', $optLayout, 'bool2', 'size="1" class="inputbox" style="width: 147px;"', 'value', 'text', $selectLayout );

$lists['fullTree']	= JHTML::_('select.booleanlist', 'bool', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool : 1 );

require_once ( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_menus'.DS.'helpers'.DS.'helper.php' );
$menuTypes 			= 	MenusHelper::getMenuTypeList();
$optParentType		=	array();
$optParentType[] 		=	JHTML::_( 'select.option', '', JText::_( 'SELECT MENU PARENT' ) );
if ( sizeof( $menuTypes ) ) {
	foreach ( $menuTypes as $menutype ) {
		$optParentType[] 		=	JHTML::_( 'select.option', 'menutype-'.$menutype->id, $menutype->title );
	}
}

$selectParentType		=	@$this->item->location;
$lists['parentType']	=	JHTML::_( 'select.genericlist', $optParentType, 'location_type', 'class="inputbox" size="1" style="width: 147px;"', 'value', 'text', $selectParentType );

require_once ( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'html'.DS.'menutree.php' );
$optParentItem			=	JHTML::_('menutree.linkoptions');
$selectParentItem		=	@$this->item->location;

$lists['parentItem']	=	JHTML::_( 'select.genericlist', $optParentItem, 'location_item', 'class="inputbox" size="15" style="width: 147px; padding-left: 1px;"', 'value', 'text', $selectParentItem );

$optParams[] 		=	JHTML::_( 'select.option', 0, JText::_( 'DEFAULT' ) );
$optParams[] 		=	JHTML::_( 'select.option', 1, JText::_( 'INHERIT' ) );
$selectParams		=	( ! $this->isNew ) ? @$this->item->bool3 : 0;
$lists['params']	=	JHTML::_( 'select.radiolist', $optParams, 'bool3', 'size="1" class="inputbox" ', 'value', 'text', $selectParams );

$optInherit			=	JHTML::_('menu.linkoptions', false);
$selectInherit		=	@$this->item->extra;
$lists['inherit']	=	JHTML::_( 'select.genericlist', $optInherit, 'extra', 'class="inputbox" size="1"', 'value', 'text', $selectInherit );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'JOOMLA MENU' ); ?>::<?php echo JText::_( 'DESCRIPTION JOOMLA MENU' ); ?>">
		<?php echo JText::_( 'JOOMLA MENU' ); ?>
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
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY FIELD' ); ?>::<?php echo JText::_( 'CHOOSE DISPLAY FIELD OR NOT' ); ?>">
					<?php echo JText::_( 'DISPLAY FIELD' ); ?>:
				</span>
			</td>
        	<td>
		        <?php echo $lists['displayField']; ?>
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
			<td colspan="3">
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FULL TREE' ); ?>::<?php echo JText::_( 'CHOOSE FULL TREE OR NOT' ); ?>">
					<?php echo JText::_( 'FULL TREE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['fullTree']; ?>
			</td>
		</tr>
		<tr id="as-item" class="<?php echo ( @$this->item->bool || $this->isNew ) ? '' : 'display-no' ?>">
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PARENT ITEM' ); ?>::<?php echo JText::_( 'SELECT PARENT ITEM' ); ?>">
					<?php echo JText::_( 'PARENT ITEM' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['parentItem']; ?>
			</td>
		</tr>
		<tr id="as-type" class="<?php echo ( @$this->item->bool == 0 && ! $this->isNew ) ? '' : 'display-no' ?>">
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PARENT TYPE' ); ?>::<?php echo JText::_( 'SELECT PARENT TYPE' ); ?>">
					<?php echo JText::_( 'PARENT TYPE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['parentType']; ?>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MENU ITEM TYPE' ); ?>::<?php echo JText::_( 'CHOOSE MENU ITEM TYPE' ); ?>">
					<?php echo JText::_( 'MENU ITEM TYPE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['layout']; ?>
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
				<input class="inputbox" type="text" id="selectlabel" name="selectlabel" maxlength="50" size="32" value="<?php echo ( @$this->item->selectlabel ) ? $this->item->selectlabel : ''; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PARAMETERS' ); ?>::<?php echo JText::_( 'CHOOSE DEFAULT PARAMETERS OR INHERITED' ); ?>">
					<?php echo JText::_( 'PARAMETERS' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['params']; ?>
			</td>
		</tr>
        <tr id="as-inherit" class="<?php echo ( @$this->item->bool3 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'INHERIT FROM' ); ?>::<?php echo JText::_( 'SELECT INHERIT FROM' ); ?>">
					<?php echo JText::_( 'INHERIT FROM' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['inherit']; ?>
			</td>
		</tr>
        <tr>
			<td colspan="3">
				<?php echo JText::_( 'MENU ITEM TYPE EXPLANATION' ); ?>
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
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:2}" type="text" id="size" name="size" maxlength="50" size="16" value="<?php echo ( @$this->item->size ) ? $this->item->size : 15; ?>" />
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

<script type="text/javascript">

	$("bool0").addEvent("change", function(t) {
			t = new Event(t).stop();
			
			if ( $("as-type").hasClass("display-no") ) {
				$("as-type").removeClass("display-no");
			}
			if ( ! $("as-item").hasClass("display-no") ) {
				$("as-item").addClass("display-no");
			}
			if ( $('size').value == 15 ) {
				$('size').value = 1;
			}
		});
	$("bool1").addEvent("change", function(i) {
			i = new Event(i).stop();
			
			if ( $("as-item").hasClass("display-no") ) {
				$("as-item").removeClass("display-no");
			}
			if ( ! $("as-type").hasClass("display-no") ) {
				$("as-type").addClass("display-no");
			}
			if ( $('size').value == 1 ) {
				$('size').value = 15;
			}
		});
	$("bool30").addEvent("change", function(d) {
			d = new Event(d).stop();
			
			if ( ! $("as-inherit").hasClass("display-no") ) {
				$("as-inherit").addClass("display-no");
			}
		});
	$("bool31").addEvent("change", function(f) {
			f = new Event(f).stop();
			
			if ( $("as-inherit").hasClass("display-no") ) {
				$("as-inherit").removeClass("display-no");
			}
		});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="joomla_menu" />
<?php } ?>