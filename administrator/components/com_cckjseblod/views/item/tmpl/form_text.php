<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
// DO NOT USE:: ->content
// DO NOT USE:: ->bool8
$optSubstituteMode		=	array();
$optSubstituteMode[] 	=	JHTML::_( 'select.option', 0, JText::_( 'NONE' ) );
//$optSubstituteMode[] 	=	JHTML::_( 'select.option', 1, JText::_( 'AS TITLE' ) );
//$optSubstituteMode[] 	=	JHTML::_( 'select.option', 2, JText::_( 'AS TITLE AND TEXT' ) );
$optSubstituteMode[] 	=	JHTML::_( 'select.option', 2, JText::_( 'AS TITLE VALUE' ) );
$selectSubstituteMode	=	( ! $this->isNew ) ? $this->item->substitute : 0;
$lists['substitute'] 	=	JHTML::_( 'select.genericlist', $optSubstituteMode, 'substitute', 'size="1" class="inputbox"', 'value', 'text', $selectSubstituteMode );

$optKeyMode				=	array();
$optKeyMode[] 			=	JHTML::_( 'select.option', 0, JText::_( 'No' ) );
$optKeyMode[] 			=	JHTML::_( 'select.option', 1, JText::_( 'Yes' ) );
$selectKeyMode			=	( ! $this->isNew ) ? $this->item->indexedkey : 0;
$lists['indexed_key'] 	=	JHTML::_( 'select.radiolist', $optKeyMode, 'indexedkey', 'size="1" class="inputbox"', 'value', 'text', $selectKeyMode );

$optIndexed			=	array();
$optIndexed[] 		=	JHTML::_( 'select.option', 0, JText::_( 'No' ) );
$optIndexed[] 		=	JHTML::_( 'select.option', 1, JText::_( 'Yes' ) );
$selectIndexed		=	( ! $this->isNew ) ? $this->item->indexed : 0;
$lists['indexed'] 	=	JHTML::_( 'select.radiolist', $optIndexed, 'indexed', 'size="1" class="inputbox"', 'value', 'text', $selectIndexed );

$optIncrement		=	array();
$optIncrement[] 	=	JHTML::_( 'select.option', 0, JText::_( 'No' ) );
$optIncrement[] 	=	JHTML::_( 'select.option', 1, JText::_( 'Yes' ) );
$selectIncrement	=	( ! $this->isNew ) ? $this->item->bool8 : 0;
$lists['increment']	=	JHTML::_( 'select.radiolist', $optIncrement, 'bool8', 'size="1" class="inputbox"', 'value', 'text', $selectIncrement );

$opt2				=	( @$this->item->options2 && strpos( @$this->item->options2, '||' ) !== false ) ? explode( '||', $this->item->options2 ) : '';
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'TEXT' ); ?>::<?php echo JText::_( 'DESCRIPTION TEXT' ); ?>">
		<?php echo JText::_( 'TEXT' ); ?>
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
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'VALIDATION' ); ?>::<?php echo JText::_( 'SELECT VALIDATION' ); ?>">
					<?php echo JText::_( 'VALIDATION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['validation']; ?>
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MAXLENGTH' ); ?>::<?php echo JText::_( 'EDIT MAXLENGTH' ); ?>">
					<?php echo JText::_( 'MAXLENGTH' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:3}" type="text" id="maxlength" name="maxlength" maxlength="50" size="16" value="<?php echo ( @$this->item->maxlength ) ? $this->item->maxlength : 50; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEFAULT VALUE' ); ?>::<?php echo JText::_( 'EDIT DEFAULT VALUE' ); ?>">
					<?php echo JText::_( 'DEFAULT VALUE' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="defaultvalue" name="defaultvalue" maxlength="250" size="32" value="<?php echo @$this->item->defaultvalue; ?>" />
			</td>
		</tr>
        <tr>
			<td colspan="3">
			</td>
		</tr>
        <tr height="23">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'INCREMENTER' ); ?>::<?php echo JText::_( 'CHOOSE INCREMENTER MODE OR NOT' ); ?>">
					<?php echo JText::_( 'INCREMENTER' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['increment']; ?>&nbsp;
                <span id="as-incrementer" class="<?php echo ( @$this->item->bool8 ) ? '' : 'display-no' ?>">
                <?php echo JText::_( 'MIN' ); ?>:
  				<input class="inputbox required validate-number required-enabled" type="text" id="options2_1" name="options2_1" maxlength="50" size="6" style="text-align: center;" value="<?php echo ( @$opt2[0] != '' ) ? $opt2[0] : 1; ?>" />
                <?php echo JText::_( 'MAX' ); ?>:
  				<input class="inputbox required validate-number required-enabled" type="text" id="options2_2" name="options2_2" maxlength="50" size="6" style="text-align: center;" value="<?php echo ( @$opt2[1] != '' ) ? $opt2[1] : 99; ?>" />
                </span>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'INDEXED AS KEY' ); ?>::<?php echo JText::_( 'CHOOSE INDEXED AS KEY OR NOT' ); ?>">
					<?php echo JText::_( 'INDEXED AS KEY' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['indexed_key']; ?>
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
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:2}" type="text" id="size" name="size" maxlength="50" size="16" value="<?php echo ( @$this->item->size ) ? $this->item->size : 32; ?>" />
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
<input type="hidden" name="type" value="text" />
<?php } ?>

<script type="text/javascript">
	window.addEvent( "domready",function(){
	
	$("bool80").addEvent("change", function(i0) {
		i0 = new Event(i0).stop();
		
		if ( ! $("as-incrementer").hasClass("display-no") ) {
			$("as-incrementer").addClass("display-no");
		} else {
			$("as-incrementer").removeClass("display-no");
		}
	});
	$("bool81").addEvent("change", function(i1) {
		i1 = new Event(i1).stop();
		
		if ( $("as-incrementer").hasClass("display-no") ) {
			$("as-incrementer").removeClass("display-no");
		} else {
			$("as-incrementer").addClass("display-no");
		}
	});
	});
</script>
