<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optStyle[]			=	JHTML::_( 'select.option', '', JText::_( 'SELECT A VARIATION' ), 'value', 'text' );
$optStyle[] 		=	JHTML::_( 'select.option', 'checkbox', JText::_( 'CHECKBOX' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'select_simple', JText::_( 'SELECT SIMPLE' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'select_numeric', JText::_( 'SELECT NUMERIC' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'hidden', JText::_( 'HIDDEN' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'text', JText::_( 'TEXT' ) );
$selectStyle		=	( ! @$this->isNew ) ? @$this->item->stylextd : '';
$lists['style']		=	JHTML::_( 'select.genericlist', $optStyle, 'stylextd', 'size="1" class="inputbox required required-enabled"', 'value', 'text', $selectStyle );

$optParam			=	array();
$optParam[] 		=	JHTML::_( 'select.option', 0, JText::_( 'INHERIT' ) );
$optParam[] 		=	JHTML::_( 'select.option', 1, JText::_( 'CUSTOM' ) );
$selectParam		=	( ! @$this->isNew ) ? @$this->item->boolxtd : 0;
$lists['param']		=	JHTML::_( 'select.genericlist', $optParam, 'boolxtd', 'size="1" class="inputbox"', 'value', 'text', $selectParam );

$optMode			=	array();
$optMode[] 			=	JHTML::_( 'select.option', 0, JText::_( 'ATTRIBUTE' ) );
$optMode[] 			=	JHTML::_( 'select.option', 2, JText::_( 'ATTRIBUTE QUANTITY' ) );
$optMode[] 			=	JHTML::_( 'select.option', 1, JText::_( 'QUANTITY' ) );
$selectMode			=	( ! $this->isNew ) ? $this->item->bool3 : 0;
$lists['mode'] 		=	JHTML::_( 'select.radiolist', $optMode, 'bool3', 'size="1" class="inputbox"', 'value', 'text', $selectMode );

$optItemContent			=	array();
$optItemContent[]		=	JHTML::_( 'select.option', 0, JText::_( 'FORM' ) );
//$optItemContent[]		=	JHTML::_( 'select.option', 1, JText::_( 'VALUE' ) );
$selectItemContent		=	( ! $this->isNew ) ? $this->item->bool5 : 0;
$lists['itemContent']	=	JHTML::_( 'select.genericlist', $optItemContent, 'bool5', 'size="1" class="inputbox"', 'value', 'text', $selectItemContent );

$optItemList		=	array();
$optItemList[] 		=	JHTML::_( 'select.option', 0, JText::_( 'FORM' ) );
//$optItemList[] 		=	JHTML::_( 'select.option', 1, JText::_( 'VALUE' ) );
$selectItemList		=	( ! $this->isNew ) ? $this->item->bool6 : 0;
$lists['itemList']		=	JHTML::_( 'select.genericlist', $optItemList, 'bool6', 'size="1" class="inputbox"', 'value', 'text', $selectItemList );

$optItemCart		=	array();
$optItemCart[] 		=	JHTML::_( 'select.option', 0, JText::_( 'FORM' ) );
$optItemCart[] 		=	JHTML::_( 'select.option', 1, JText::_( 'VALUE' ) );
$selectItemCart	=	( ! $this->isNew ) ? $this->item->bool7 : 0;
$lists['itemCart']	=	JHTML::_( 'select.genericlist', $optItemCart, 'bool7', 'size="1" class="inputbox"', 'value', 'text', $selectItemCart );
?>

<fieldset class="adminform">
    <legend class="legend-border">
        <span class="editlinktip hasTip2" title="<?php echo JText::_( 'ECOMMERCE CART' ); ?>::<?php echo JText::_( 'DESCRIPTION CART' ); ?>">
            <?php echo JText::_( 'ECOMMERCE CART' ); ?>
        </span>
    </legend>
	<table class="admintable">
        <tr>
	        <td>
            	<?php echo '<font color="gray">(*)&nbsp;' . JText::_( 'Not Available' ) . '</font>'; ?>
        	</td>
		</tr>
		<!--
		<tr>
        	<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DESCRIPTION LIGHT BULB' ); ?>::<?php echo JText::_( 'CHOOSE DISPLAY DESCRIPTION LIGHT BULB OR NOT' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>:
				</span>
			</td>
			<td colspan="2">
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
			<td>
				<?php echo $this->lists['display']; ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MODE' ); ?>::<?php echo JText::_( 'CHOOSE MODE' ); ?>">
					<?php echo JText::_( 'MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['mode']; ?>
			</td>
		</tr>
        <tr>
        	<td colspan="3">
            </td>
        </tr>
		<tr id="as-extended" class="<?php echo ( ! @$this->item->bool3 || @$this->item->bool3 == 2 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FIELD' ); ?>::<?php echo JText::_( 'CART ATTRIBUTE FIELD BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FIELD' ); ?>::<?php echo JText::_( 'EDIT FIELD' ); ?>">
					<?php echo JText::_( 'FIELD' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="extended" name="extended" maxlength="50" size="32" value="<?php echo @$this->item->extended; ?>" />
			</td>
		</tr>
        <tr id="as-group" class="<?php echo ( ! @$this->item->extended || strpos( @$this->item->extended, '(' ) === false  ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'GROUPX IDENTIFIER' ); ?>::<?php echo JText::_( 'SELECT GROUPX IDENTIFIER' ); ?>">
					<?php echo JText::_( 'GROUPX IDENTIFIER' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="extra" name="extra" maxlength="50" size="32" value="<?php echo @$this->item->extra; ?>" />
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'STYLE VARIATION' ); ?>::<?php echo JText::_( 'STYLE VARIATION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'STYLE VARIATION' ); ?>::<?php echo JText::_( 'SELECT STYLE VARIATION' ); ?>">
					<?php echo JText::_( 'STYLE VARIATION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['style']; ?>                
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PARAM VARIATION' ); ?>::<?php echo JText::_( 'PARAM VARIATION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PARAM VARIATION' ); ?>::<?php echo JText::_( 'SELECT PARAM VARIATION' ); ?>">
					<?php echo JText::_( 'PARAM VARIATION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['param']; ?>             
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY ON CONTENT' ); ?>::<?php echo JText::_( 'SELECT DISPLAY MODE ON CONTENT' ); ?>">
					<?php echo JText::_( 'DISPLAY ON CONTENT' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['itemContent']; ?>
			</td>
        </tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
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
        <tr>
			<td width="25" align="right" class="key_jseblod">
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
        -->
    </table>
</fieldset>

<?php
if ( @$this->item->boolxtd ) {
	$tmpl	=	( $this->item->stylextd ) ? $this->item->stylextd : CCK_DB_Result( 'SELECT cc.name FROM #__jseblod_cck_items AS s LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type WHERE s.id = '.(int)$this->item->extendedId );
	if ( JFile::exists( dirname(__FILE__).DS.'form_'.$tmpl.'.php' ) ) {
		include_once( dirname(__FILE__).DS.'form_'.$tmpl.'.php' );
	}
}
?>

<script type="text/javascript">
	window.addEvent( "domready",function(){

	$("bool30").addEvent("change", function(m0) {
			m0 = new Event(m0).stop();
			
			if ( $("as-extended").hasClass("display-no") ) {
				$("as-extended").removeClass("display-no");
			}
		});
	$("bool31").addEvent("change", function(m1) {
			m1 = new Event(m1).stop();
			
			if ( ! $("as-extended").hasClass("display-no") ) {
				$("as-extended").addClass("display-no");
			}
		});
	$("bool32").addEvent("change", function(m1) {
			m1 = new Event(m1).stop();
			
			if ( $("as-extended").hasClass("display-no") ) {
				$("as-extended").removeClass("display-no");
			}
		});
	});
	
	$("extended").addEvent("keyup", function(e) {
		e = new Event(e).stop();
		
		var layout = this.value;
		if ( layout.indexOf( "(" ) != -1 ) {
			if ( $("as-group").hasClass("display-no") ) {
				$("as-group").removeClass("display-no");
			}
		} else {
			if ( ! $("as-group").hasClass("display-no") ) {
				$("as-group").addClass("display-no");
			}
		}
	 });
</script>

<input type="hidden" name="type" value="ecommerce_cart" />
<input type="hidden" name="elemxtd" value="" />