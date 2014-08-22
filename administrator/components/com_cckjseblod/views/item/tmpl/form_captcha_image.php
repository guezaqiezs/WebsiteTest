<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes

$optCaptchaMode			=	array();
$optCaptchaMode[] 		=	JHTML::_( 'select.option', 0, JText::_( 'MATH' ) );
$optCaptchaMode[] 		=	JHTML::_( 'select.option', 1, JText::_( 'WORD' ) );
$selectCaptchaMode		=	( ! $this->isNew ) ? $this->item->bool : 0;
$lists['captchaMode'] 	=	JHTML::_( 'select.radiolist', $optCaptchaMode, 'bool', 'size="1" class="inputbox"', 'value', 'text', $selectCaptchaMode );

$optFunction[]		=	JHTML::_( 'select.option', '1', JText::_( 'MATH SUM' ) );
$optFunction[]		=	JHTML::_( 'select.option', '2', JText::_( 'MATH PRODUCT' ) );
$optFunction[]		=	JHTML::_( 'select.option', '4', JText::_( 'MATH DIFFERENCE' ) );
$selectFunction		=	array();
if ( ! $this->isNew || @$this->item->bool2 ) {
	if ( @$this->item->bool2 == 1 || @$this->item->bool2 == 2 || @$this->item->bool2 == 4 ) {
		$selectFunction[]	=	$this->item->bool2;
	} else if ( @$this->item->bool2 == 3 ) {
		$selectFunction[]	=	'1';
		$selectFunction[]	=	'2';
	} else if ( @$this->item->bool2 == 5 ) {
		$selectFunction[]	=	'1';
		$selectFunction[]	=	'4';
	} else if ( @$this->item->bool2 == 6 ) {
		$selectFunction[]	=	'2';
		$selectFunction[]	=	'4';
	} else if ( @$this->item->bool2 == 7 ) {
		$selectFunction[]	=	'1';
		$selectFunction[]	=	'2';
		$selectFunction[]	=	'4';
	}
} else {
	$selectFunction[]	=	'1';
	$selectFunction[]	=	'2';
	$selectFunction[]	=	'4';
}
$lists['function']	=	JHTML::_( 'select.genericlist', $optFunction, 'bool2_math[]', 'size="3" class="inputbox required required-enabled" multiple="multiple" style="width: 147px"', 'value', 'text', $selectFunction );

$optLength[]		=	JHTML::_( 'select.option', '3', '3 ' . JText::_( 'CHARS' ) );
$optLength[]		=	JHTML::_( 'select.option', '4', '4 ' . JText::_( 'CHARS' ) );
$optLength[]		=	JHTML::_( 'select.option', '5', '5 ' . JText::_( 'CHARS' ) );
$optLength[]		=	JHTML::_( 'select.option', '6', '6 ' . JText::_( 'CHARS' ) );
$optLength[]		=	JHTML::_( 'select.option', '7', '7 ' . JText::_( 'CHARS' ) );
$optLength[]		=	JHTML::_( 'select.option', '8', '8 ' . JText::_( 'CHARS' ) );
$selectLength		=	( @$this->item->bool2 ? $this->item->bool2 : 4 );
$lists['length']	=	JHTML::_( 'select.genericlist', $optLength, 'bool2_word', 'size="1" class="inputbox"', 'value', 'text', $selectLength );

$textColor	=	( @$this->item->content ) ? $this->item->content : '#000000';
$backColor	=	( @$this->item->location ) ? $this->item->location : '#FFFFFF';
$gridColor	=	( @$this->item->extra ) ? $this->item->extra : '#d7d7d7';

$optCase		=	array();
$optCase[] 		=	JHTML::_( 'select.option', 0, JText::_( 'LOWERCASE' ) );
$optCase[] 		=	JHTML::_( 'select.option', 1, JText::_( 'UPPERCASE' ) );
$selectCase		=	( ! $this->isNew ) ? $this->item->bool3 : 0;
$lists['case'] 	=	JHTML::_( 'select.genericlist', $optCase, 'bool3', 'size="1" class="inputbox"', 'value', 'text', $selectCase );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CAPTCHA IMAGE' ); ?>::<?php echo JText::_( 'DESCRIPTION CAPTCHA IMAGE' ); ?>">
		<?php echo JText::_( 'CAPTCHA IMAGE' ); ?>
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
        <tr>
        	<td colspan="3">
            </td>
        </tr>
        <?php } ?>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ALLOW EDITION' ); ?>::<?php echo JText::_( 'ALLOW EDITION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ALLOW EDITION' ); ?>::<?php echo JText::_( 'SELECT ALLOW EDITION MODE' ); ?>">
					<?php echo JText::_( 'ALLOW EDITION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['allowEdition']; ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CAPTCHA MODE' ); ?>::<?php echo JText::_( 'CAPTCHA MODE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CAPTCHA MODE' ); ?>::<?php echo JText::_( 'CHOOSE CAPTCHA MODE' ); ?>">
					<?php echo JText::_( 'CAPTCHA MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['captchaMode']; ?>
			</td>
		</tr>
        <tr id="as-word" class="<?php echo ( @$this->item->bool ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'LENGTH' ); ?>::<?php echo JText::_( 'SELECT LENGTH' ); ?>">
					<?php echo JText::_( 'LENGTH' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['length']; ?>
			</td>
		</tr>
        <tr id="as-word2" class="<?php echo ( @$this->item->bool ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CASE' ); ?>::<?php echo JText::_( 'SELECT CASE' ); ?>">
					<?php echo JText::_( 'CASE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['case']; ?>
			</td>
		</tr>
        <tr id="as-math" class="<?php echo ( ! @$this->item->bool ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FUNCTION' ); ?>::<?php echo JText::_( 'SELECT FUNCTION' ); ?>">
					<?php echo JText::_( 'FUNCTION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['function']; ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
					<?php echo JText::_( 'WIDTH' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled validate-number" type="text" id="width" name="width" maxlength="3" size="32" value="<?php echo ( @$this->item->width ) ? $this->item->width : '150'; ?>" />
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'HEIGHT' ); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
					<?php echo JText::_( 'HEIGHT' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled validate-number" type="text" id="height" name="height" maxlength="3" size="32" value="<?php echo ( @$this->item->height ) ? $this->item->height : '50'; ?>" />
			</td>
		</tr>
       	<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'TEXT COLOR' ); ?>::<?php echo JText::_( 'PICK TEXT COLOR' ); ?>">
					<?php echo JText::_( 'TEXT COLOR' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="content" name="content" maxlength="7" size="32" value="<?php echo $textColor; ?>" />&nbsp;&nbsp;
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'COLOR' ); ?>::<?php echo JText::_( 'PICK COLOR' ); ?>">
					<?php echo _IMG_COLOR_TEXT; ?>
				</span>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'BACKGROUND COLOR' ); ?>::<?php echo JText::_( 'PICK BACKGROUND COLOR' ); ?>">
					<?php echo JText::_( 'BACKGROUND COLOR' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="location" name="location" maxlength="7" size="32" value="<?php echo $backColor; ?>" />&nbsp;&nbsp;
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'COLOR' ); ?>::<?php echo JText::_( 'PICK COLOR' ); ?>">
					<?php echo _IMG_COLOR_BACK; ?>
				</span>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'GRID COLOR' ); ?>::<?php echo JText::_( 'PICK GRID COLOR' ); ?>">
					<?php echo JText::_( 'GRID COLOR' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="extra" name="extra" maxlength="7" size="32" value="<?php echo $gridColor; ?>" />&nbsp;&nbsp;
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'COLOR' ); ?>::<?php echo JText::_( 'PICK COLOR' ); ?>">
					<?php echo _IMG_COLOR_GRID; ?>
				</span>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SIZE' ); ?>::<?php echo JText::_( 'EDIT SIZE' ); ?>">
					<?php echo JText::_( 'SIZE' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:2}" type="text" id="size" name="size" maxlength="50" size="32" value="<?php echo ( @$this->item->size ) ? $this->item->size : 32; ?>" />
			</td>
		</tr>
	</table>
</fieldset>

<script type="text/javascript">
	window.addEvent( "domready",function(){
				
		var init = '<?php echo $textColor; ?>';
		if ( !init ) { init = "#FFFFFF"; }
		R = HexToRGB( init, 0, 2 );
		G = HexToRGB( init, 2, 4);
		B = HexToRGB( init, 4, 6);
		
		var c1 = new MooRainbow( "contentRainbow", {
			id: "contentRainbow",
			wheel: false, 
      		"startColor": [R, G, B],
      		"onChange": function( color ) { $("content").value = color.hex; }
      	});
		
		var init2 = '<?php echo $backColor; ?>';
		if ( !init2 ) { init2 = "#FFFFFF"; }
		R2 = HexToRGB( init2, 0, 2 );
		G2 = HexToRGB( init2, 2, 4);
		B2 = HexToRGB( init2, 4, 6);
		
		var c2 = new MooRainbow( "locationRainbow", {
			id: "locationRainbow",
			wheel: false, 
		"startColor": [R2, G2, B2],
      		"onChange": function( color ) { $("location").value = color.hex; }
      	});
		
		var init3 = '<?php echo $gridColor; ?>';
		if ( !init3 ) { init3 = "#FFFFFF"; }
		R2 = HexToRGB( init3, 0, 2 );
		G2 = HexToRGB( init3, 2, 4);
		B2 = HexToRGB( init3, 4, 6);
		
		var c2 = new MooRainbow( "extraRainbow", {
			id: "extraRainbow",
			wheel: false, 
		"startColor": [R2, G2, B2],
      		"onChange": function( color ) { $("extra").value = color.hex; }
      	});
	});
	
	function HexToRGB(hexa,left,right) {return parseInt((cutHex(hexa)).substring(left,right),16)}
	function cutHex(hexa) {return (hexa.charAt(0)=="#") ? hexa.substring(1,7):hexa}

	$("bool0").addEvent("change", function(m) {
			m = new Event(m).stop();
			
			if ( $("as-math").hasClass("display-no") ) {
				$("as-math").removeClass("display-no");
			}
			if ( ! $("as-word").hasClass("display-no") ) {
				$("as-word").addClass("display-no");
			}
			if ( ! $("as-word2").hasClass("display-no") ) {
				$("as-word2").addClass("display-no");
			}
		});
	$("bool1").addEvent("change", function(i) {
			i = new Event(i).stop();
			
			if ( $("as-word").hasClass("display-no") ) {
				$("as-word").removeClass("display-no");
			}
			if ( $("as-word2").hasClass("display-no") ) {
				$("as-word2").removeClass("display-no");
			}
			if ( ! $("as-math").hasClass("display-no") ) {
				$("as-math").addClass("display-no");
			}
		});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="captcha_image" />
<?php } ?>