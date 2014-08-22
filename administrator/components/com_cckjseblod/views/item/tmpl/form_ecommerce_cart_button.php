<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
// DO NOT USE:: ->content
// DO NOT USE:: ->bool8
// +
// maxlength, size, style

$optMode			=	array();
$optMode[] 			=	JHTML::_( 'select.option', 0, JText::_( 'SIMPLE' ) );
$optMode[] 			=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'ADVANCED GRID' ) );
$optMode[] 			=	JHTML::_( 'select.option', 1, JText::_( 'BUTTON GRID' ) );
$optMode[] 			=	JHTML::_( 'select.option', 2, JText::_( 'INPUT GRID' ) );
$optMode[]			=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
$selectMode			=	( ! $this->isNew ) ? $this->item->bool3 : 0;
$lists['mode'] 		=	JHTML::_( 'select.genericlist', $optMode, 'bool3', 'size="1" class="inputbox"', 'value', 'text', $selectMode );

$modals['selectColumn']		=	HelperjSeblod_Display::quickModalTask( 'SELECT', '', 'options', 'pagebreak', 'items', 'select' );
$modals['selectColumn_2nd']	=	HelperjSeblod_Display::quickModalTask( 'SELECT', '', 'options_2nd', 'pagebreak', 'items', 'select' );
if ( strpos( @$this->item->options, ',' ) !== false ) {
	$opts			=	explode( ',', @$this->item->options );
	@$options		=	$opts[0];
	@$options_2nd	=	$opts[1];
} else {
	@$options		=	@$this->item->options;
}

$modals['selectRow']		=	HelperjSeblod_Display::quickModalTask( 'SELECT', '', 'options2', 'pagebreak', 'items', 'select' );
$modals['selectRow_2nd']	=	HelperjSeblod_Display::quickModalTask( 'SELECT', '', 'options2_2nd', 'pagebreak', 'items', 'select' );
if ( strpos( @$this->item->options2, ',' ) !== false ) {
	$opts			=	explode( ',', @$this->item->options2 );
	@$options2		=	$opts[0];
	@$options2_2nd	=	$opts[1];
} else {
	@$options2		=	@$this->item->options2;
}

$modals['selectQty']	=	HelperjSeblod_Display::quickModalTask( 'SELECT', '', 'defaultvalue', 'pagebreak', 'items', 'select' );

$modals['selectGrid']	=	HelperjSeblod_Display::quickModalTask( 'SELECT', '', 'style', 'pagebreak', 'templates', 'select', _MODAL_WIDTH, _MODAL_HEIGHT, 'tpl_type=5' );

$optExtra		=	CCK::DB_loadObjectList( 'SELECT title AS text, name AS value FROM #__jseblod_cck_items WHERE type=51 AND bool3!=1' );
$size			=	( count( $optExtra ) ) ? count( $optExtra ) : 3;
$selectExtra	=	( @$this->item->extra ) ? explode( ',', $this->item->extra ) : '';
$lists['extra']	=	JHTML::_( 'select.genericlist', $optExtra, 'selected_extra[]', 'class="inputbox" size="'.$size.'" multiple="multiple" style="width: 147px;"', 'value', 'text', $selectExtra );

$optIndexedKey[] 	=	JHTML::_( 'select.option', '', JText::_( 'SELECT AN INDEX' ) );
$keys				=	CCK::DB_loadObjectList( 'SELECT title as text, name as value FROM #__jseblod_cck_items WHERE indexedkey' );
if ( $keys ) {
	$optIndexedKey	=	array_merge( $optIndexedKey, $keys );
}
$selectIndexedKey	=	( ! $this->isNew ) ? $this->item->extended : '';
$lists['indexedKey']=	JHTML::_( 'select.genericlist', $optIndexedKey, 'extended', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectIndexedKey );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ECOMMERCE CART BUTTON' ); ?>::<?php echo JText::_( 'DESCRIPTION CART BUTTON' ); ?>">
		<?php echo JText::_( 'ECOMMERCE CART BUTTON' ); ?>
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
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY LABEL' ); ?>::<?php echo JText::_( 'DISPLAY LABEL BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY LABEL' ); ?>::<?php echo JText::_( 'SELECT DISPLAY LABEL MODE' ); ?>">
					<?php echo JText::_( 'MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['mode']; ?>
			</td>
		</tr>
		<tr id="as-advanced-5" class="<?php echo ( @$this->item->bool3 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'GRID TEMPLATE' ); ?>::<?php echo JText::_( 'SELECT GRID TEMPLATE' ); ?>">
					<?php echo JText::_( 'GRID TEMPLATE' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-disabled" type="text" id="style_title" name="style_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$this->item->style ) ? $this->item->styleTitle : ''; ?>" />
				<input type="hidden" id="style" name="style" value="<?php echo @$this->item->style; ?>" />
			</td>
			<td>
				<?php echo $modals['selectGrid']; ?>
			</td>
		</tr>
		<tr id="as-advanced-4" class="<?php echo ( @$this->item->bool3 ) ? '' : 'display-no' ?>">
        	<td colspan="4">
        </tr>
		<tr id="as-advanced-1" class="<?php echo ( @$this->item->bool3 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span id="2nd_1-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[+]</span>&nbsp;&nbsp;
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'COLUMN FIELD' ); ?>::<?php echo JText::_( 'SELECT COLUMN FIELD' ); ?>">
					<?php echo JText::_( 'COLUMN FIELD' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="options_title" name="options_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$options ) ? $this->item->optionsTitle : ''; ?>" />
				<input type="hidden" id="options" name="options" value="<?php echo @$options; ?>" />
                <input type="hidden" id="options_id" name="options_id" value="<?php echo @$this->item->optionsId; ?>" />
			</td>
			<td>
				<?php echo $modals['selectColumn']; ?>
                <?php if ( ! @$this->isNew ) { ?>
                    <span style="float: left;">&nbsp;&nbsp;&nbsp;</span>
                    <div class="button2-left">
                        <div class="blank">
                            <a onclick="javascript: fieldExtended('options_id');" alt="Column Field"><?php echo JText::_( 'EDIT' ); ?></a>
                        </div>
                    </div>
                <?php } ?>
			</td>
		</tr>
		<tr id="as-advanced-1-2" class="<?php echo ( @$this->item->bool3 && $options_2nd ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( '2ND' ) .' '. JText::_( 'COLUMN FIELD' ); ?>::<?php echo JText::_( 'SELECT COLUMN FIELD' ); ?>">
					<?php echo JText::_( '2ND' ) .' '. JText::_( 'COLUMN FIELD' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="options_2nd_title" name="options_2nd_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$options_2nd ) ? $this->item->optionsTitle_2nd : ''; ?>" />
				<input type="hidden" id="options_2nd" name="options_2nd" value="<?php echo @$options_2nd; ?>" />
                <input type="hidden" id="options_2nd_id" name="options_2nd_id" value="<?php echo @$this->item->optionsId_2nd; ?>" />
			</td>
			<td>
				<?php echo $modals['selectColumn_2nd']; ?>
                <?php if ( ! @$this->isNew ) { ?>
                    <span style="float: left;">&nbsp;&nbsp;&nbsp;</span>
                    <div class="button2-left">
                        <div class="blank">
                            <a onclick="javascript: fieldExtended('options_2nd_id');" alt="2nd Column Field"><?php echo JText::_( 'EDIT' ); ?></a>
                        </div>
                    </div>
                <?php } ?>
			</td>
		</tr>
		<tr id="as-advanced-2" class="<?php echo ( @$this->item->bool3 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span id="2nd_2-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[+]</span>&nbsp;&nbsp;
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ROW FIELD' ); ?>::<?php echo JText::_( 'SELECT ROW FIELD' ); ?>">
					<?php echo JText::_( 'ROW FIELD' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="options2_title" name="options2_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$options2 ) ? $this->item->options2Title : ''; ?>" />
				<input type="hidden" id="options2" name="options2" value="<?php echo @$options2; ?>" />
                <input type="hidden" id="options2_id" name="options2_id" value="<?php echo @$this->item->options2Id; ?>" />
			</td>
			<td>
				<?php echo $modals['selectRow']; ?>
                <?php if ( ! @$this->isNew ) { ?>
                    <span style="float: left;">&nbsp;&nbsp;&nbsp;</span>
                    <div class="button2-left">
                        <div class="blank">
                            <a onclick="javascript: fieldExtended('options2_id');" alt="Row Field"><?php echo JText::_( 'EDIT' ); ?></a>
                        </div>
                    </div>
                <?php } ?>
			</td>
		</tr>
		<tr id="as-advanced-2-2" class="<?php echo ( @$this->item->bool3 && $options2_2nd ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( '2ND' ) .' '. JText::_( 'ROW FIELD' ); ?>::<?php echo JText::_( 'SELECT ROW FIELD' ); ?>">
					<?php echo JText::_( '2ND' ) .' '. JText::_( 'ROW FIELD' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="options2_2nd_title" name="options2_2nd_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$options2_2nd ) ? $this->item->options2Title_2nd : ''; ?>" />
				<input type="hidden" id="options2_2nd" name="options2_2nd" value="<?php echo @$options2_2nd; ?>" />
                <input type="hidden" id="options2_2nd_id" name="options2_2nd_id" value="<?php echo @$this->item->options2Id_2nd; ?>" />
			</td>
			<td>
				<?php echo $modals['selectRow_2nd']; ?>
                <?php if ( ! @$this->isNew ) { ?>
                    <span style="float: left;">&nbsp;&nbsp;&nbsp;</span>
                    <div class="button2-left">
                        <div class="blank">
                            <a onclick="javascript: fieldExtended('options2_2nd_id');" alt="Row Field"><?php echo JText::_( 'EDIT' ); ?></a>
                        </div>
                    </div>
                <?php } ?>
			</td>
		</tr>
		<tr id="as-advanced-3" class="<?php echo ( @$this->item->bool3 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'QUANTITY FIELD' ); ?>::<?php echo JText::_( 'SELECT QUANTITY FIELD' ); ?>">
					<?php echo JText::_( 'QUANTITY FIELD' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="defaultvalue_title" name="defaultvalue_title" maxlength="50" size="32" disabled="disabled" value="<?php echo ( @$this->item->defaultvalue ) ? $this->item->defaultvalueTitle : ''; ?>" />
				<input type="hidden" id="defaultvalue" name="defaultvalue" value="<?php echo @$this->item->defaultvalue; ?>" />
                <input type="hidden" id="defaultvalue_id" name="defaultvalue_id" value="<?php echo @$this->item->defaultvalueId; ?>" />
			</td>
			<td>
				<?php echo $modals['selectQty']; ?>
                <?php if ( ! @$this->isNew ) { ?>
                    <span style="float: left;">&nbsp;&nbsp;&nbsp;</span>
                    <div class="button2-left">
                        <div class="blank">
                            <a onclick="javascript: fieldExtended('defaultvalue_id');" alt="Quantity Field"><?php echo JText::_( 'EDIT' ); ?></a>
                        </div>
                    </div>
                <?php } ?>
			</td>
		</tr>
		<tr id="as-advanced-7" class="<?php echo ( @$this->item->bool3 ) ? '' : 'display-no' ?>">
			<td colspan="4">
			</td>
		</tr>
		<tr id="as-advanced-6" class="<?php echo ( @$this->item->bool3 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'EXTRA FIELDS' ); ?>::<?php echo JText::_( 'SELECT EXTRA FIELDS' ); ?>">
					<?php echo JText::_( 'EXTRA FIELDS' ); ?>:
				</span>
			</td>
			<td colspan="2">
				<?php echo $lists['extra']; ?>
			</td>
		</tr>
		<tr id="as-advanced-8" class="<?php echo ( @$this->item->bool3 == 2 ) ? '' : 'display-no' ?>">
			<td colspan="4">
			</td>
		</tr>
		<tr id="as-advanced-9" class="<?php echo ( @$this->item->bool3 == 2 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FIELD' ); ?>::<?php echo JText::_( 'EDIT FIELD' ); ?>">
					<?php echo JText::_( 'FIELD' ); ?>:
				</span>
			</td>
			<td>
            	<?php echo $lists['indexedKey']; ?>
			</td>
		</tr>
        <tr id="as-advanced-10" class="<?php echo ( @$this->item->bool3 == 2 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PROFILE PART' ); ?>::<?php echo JText::_( 'EDIT PROFILE PART' ); ?>">
					<?php echo JText::_( 'PROFILE PART' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="location" name="location" maxlength="50" size="32" value="<?php echo @$this->item->location; ?>" />
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
	window.addEvent( "domready",function(){

	$("bool3").addEvent("change", function(m) {
			m = new Event(m).stop();
			
			var layout = this.value;
			if ( layout > 0 ) {
				if ( $("as-advanced-1").hasClass("display-no") ) {
					$("as-advanced-1").removeClass("display-no");
				}
				if ( $("as-advanced-1-2").hasClass("display-no") ) {
					$("as-advanced-1-2").removeClass("display-no");
				}
				if ( $("as-advanced-2").hasClass("display-no") ) {
					$("as-advanced-2").removeClass("display-no");
				}
				if ( $("as-advanced-2-2").hasClass("display-no") ) {
					$("as-advanced-2-2").removeClass("display-no");
				}
				if ( $("as-advanced-3").hasClass("display-no") ) {
					$("as-advanced-3").removeClass("display-no");
				}
				if ( $("as-advanced-4").hasClass("display-no") ) {
					$("as-advanced-4").removeClass("display-no");
				}
				if ( $("as-advanced-5").hasClass("display-no") ) {
					$("as-advanced-5").removeClass("display-no");
				}
				if ( $("as-advanced-6").hasClass("display-no") ) {
					$("as-advanced-6").removeClass("display-no");
				}
				if ( layout == 2 ) {
					if ( $("as-advanced-8").hasClass("display-no") ) {
						$("as-advanced-8").removeClass("display-no");
					}
					if ( $("as-advanced-9").hasClass("display-no") ) {
						$("as-advanced-9").removeClass("display-no");
					}
					if ( $("as-advanced-10").hasClass("display-no") ) {
						$("as-advanced-10").removeClass("display-no");
					}
				}
			} else {
				if ( ! $("as-advanced-1").hasClass("display-no") ) {
					$("as-advanced-1").addClass("display-no");
				}
				if ( ! $("as-advanced-1-1").hasClass("display-no") ) {
					$("as-advanced-1-1").addClass("display-no");
				}
				if ( ! $("as-advanced-2").hasClass("display-no") ) {
					$("as-advanced-2").addClass("display-no");
				}
				if ( ! $("as-advanced-2-2").hasClass("display-no") ) {
					$("as-advanced-2-2").addClass("display-no");
				}
				if ( ! $("as-advanced-3").hasClass("display-no") ) {
					$("as-advanced-3").addClass("display-no");
				}
				if ( ! $("as-advanced-4").hasClass("display-no") ) {
					$("as-advanced-4").addClass("display-no");
				}
				if ( ! $("as-advanced-5").hasClass("display-no") ) {
					$("as-advanced-5").addClass("display-no");
				}
				if ( ! $("as-advanced-6").hasClass("display-no") ) {
					$("as-advanced-6").addClass("display-no");
				}
				if ( ! $("as-advanced-8").hasClass("display-no") ) {
					$("as-advanced-8").addClass("display-no");
				}
				if ( ! $("as-advanced-9").hasClass("display-no") ) {
					$("as-advanced-9").addClass("display-no");
				}
				if ( ! $("as-advanced-10").hasClass("display-no") ) {
					$("as-advanced-10").addClass("display-no");
				}
			}
		});
	});
	
$("2nd_1-toggle").addEvent("click", function(s1) {
	s1 = new Event(s1).stop();
	
	if ( $("as-advanced-1-2").hasClass("display-no") ) {
		$("as-advanced-1-2").removeClass("display-no");
	} else {
		$("as-advanced-1-2").addClass("display-no");
	}
});
$("2nd_2-toggle").addEvent("click", function(s2) {
	s2 = new Event(s2).stop();
	
	if ( $("as-advanced-2-2").hasClass("display-no") ) {
		$("as-advanced-2-2").removeClass("display-no");
	} else {
		$("as-advanced-2-2").addClass("display-no");
	}
});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="type" value="ecommerce_cart_button" />
<?php } ?>