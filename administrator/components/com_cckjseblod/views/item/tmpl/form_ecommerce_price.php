<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optPlugins			=	array();
$optPlugins[]		= 	JHTML::_('select.option',  '', JText::_( 'SELECT A PLUGIN' ), 'value', 'text' );
$optPlugins			=	array_merge( $optPlugins, HelperjSeblod_Helper::getPluginsButton() );
$selectPlugins		= 	@$this->item->options;
$lists['plugins']	= 	JHTML::_('select.genericlist', $optPlugins, 'options', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectPlugins );

$optCalc			=	CCK::DB_loadObjectList( 'SELECT title AS text, name AS value FROM #__jseblod_cck_items WHERE type=51 AND bool3=2' );
$size				=	( count( $optCalc ) > 3 ) ? count( $optCalc ) : 3;
$selectCalc			=	( @$this->item->options ) ? explode( ',', $this->item->options ) : '';
$lists['calc']		=	JHTML::_( 'select.genericlist', $optCalc, 'selected_options[]', 'class="inputbox" size="'.$size.'" multiple="multiple" style="width: 147px;"', 'value', 'text', $selectCalc );

//$optMaster		=	CCK::DB_loadObjectList( 'SELECT title AS text, name AS value FROM #__jseblod_cck_items WHERE type=51' );
//$selectMaster		=	( @$this->item->extra ) ? explode( '=', $this->item->extra ) : '';
//$selectMaster1	=	( @$selectMaster[0] ) ? $selectMaster[0] : '';
//$selectMaster2	=	( @$selectMaster[1] ) ? $selectMaster[1] : '';
//$lists['master']	=	JHTML::_( 'select.genericlist', $optMaster, 'extra[]', 'class="inputbox" size="1" style="width: 147px;"', 'value', 'text', $selectMaster2, 'extra1' );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ECOMMERCE PRICE' ); ?>::<?php echo JText::_( 'DESCRIPTION PRICE' ); ?>">
		<?php echo JText::_( 'ECOMMERCE PRICE' ); ?>
    </span>
</legend>
	<table class="admintable">
        <tr>
	        <td>
            	<?php echo '<font color="gray">(*)&nbsp;' . JText::_( 'Not Available' ) . '</font>'; ?>
        	</td>
		</tr>
		<!--
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
		<tr id="as-extended" class="<?php echo ( ! @$this->item->bool3 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FIELD' ); ?>::<?php echo JText::_( 'CART PRICE FIELD BALLOON' ); ?>">
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
		<tr id="as-currency-1" class="<?php echo ( ! @$this->item->extended || strpos( @$this->item->extended, '$' ) === false  ) ? 'display-no' : '' ?>">
			<td colspan="3">
			</td>
		</tr>
        <tr id="as-currency-2" class="<?php echo ( ! @$this->item->extended || strpos( @$this->item->extended, '$' ) === false  ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEFAULT CURRENCY' ); ?>::<?php echo JText::_( 'SELECT DEFAULT CURRENCY' ); ?>">
					<?php echo JText::_( 'DEFAULT CURRENCY' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="format" name="format" maxlength="50" size="32" value="<?php echo @$this->item->format; ?>" />
			</td>
		</tr>
        <tr id="as-currency-3" class="<?php echo ( ! @$this->item->extended || strpos( @$this->item->extended, '$' ) === false  ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PROFILE CURRENCY' ); ?>::<?php echo JText::_( 'EDIT PROFILE CURRENCY' ); ?>">
					<?php echo JText::_( 'PROFILE CURRENCY' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="location" name="location" maxlength="50" size="32" value="<?php echo @$this->item->location; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PRODUCT FIELDS' ); ?>::<?php echo JText::_( 'SELECT PRODUCT FIELDS' ); ?>">
					<?php echo JText::_( 'PRODUCT FIELDS' ); ?>:
				</span>
			</td>
			<td>
	            <input class="inputbox" type="text" id="calc_qty" name="calc_qty" maxlength="50" size="32" disabled="disabled" style="margin-bottom:4px;" value="quantity" /><br />
				<?php echo $lists['calc']; ?>
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
		-->
    </table>
</fieldset>

<script type="text/javascript">
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
		if ( layout.indexOf( "$" ) != -1 ) {
			if ( $("as-currency-1").hasClass("display-no") ) {
				$("as-currency-1").removeClass("display-no");
			}
			if ( $("as-currency-2").hasClass("display-no") ) {
				$("as-currency-2").removeClass("display-no");
			}
			if ( $("as-currency-3").hasClass("display-no") ) {
				$("as-currency-3").removeClass("display-no");
			}
		} else {
			if ( ! $("as-currency-1").hasClass("display-no") ) {
				$("as-groucurrencyp-1").addClass("display-no");
			}
			if ( ! $("as-currency-2").hasClass("display-no") ) {
				$("as-groucurrencyp-2").addClass("display-no");
			}
			if ( ! $("as-currency-3").hasClass("display-no") ) {
				$("as-groucurrencyp-3").addClass("display-no");
			}
		}
	});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="type" value="ecommerce_price" />
<?php } ?>