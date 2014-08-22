<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optStyle		=	array();
$optStyle[]		=	JHTML::_( 'select.option', 'brown', JText::_( 'BROWN' ) );
$optStyle[]		=	JHTML::_( 'select.option', 'default', JText::_( 'DEFAULT' ) );
$optStyle[]		=	JHTML::_( 'select.option', 'gold', JText::_( 'GOLD' ) );
$optStyle[]		=	JHTML::_( 'select.option', 'gray', JText::_( 'GRAY' ) );
$selectStyle	=	( @$this->item->id ) ? $this->item->style : 'default';
$lists['calendarStyle']	=	JHTML::_( 'select.genericlist', $optStyle, 'style', 'size="1" class="inputbox"', 'value', 'text', $selectStyle );

$optDirection[]	=	JHTML::_( 'select.option', 0, JText::_( 'ALL DEFAULT' ) );
$optDirection[] =	JHTML::_( 'select.option', -2, JText::_( 'PAST' ) );
$optDirection[] =	JHTML::_( 'select.option', -1, JText::_( 'PAST TODAY' ) );
$optDirection[] =	JHTML::_( 'select.option', 9, JText::_( 'NONE CURRENT MONTH' ) );
$optDirection[] =	JHTML::_( 'select.option', .5, JText::_( 'TODAY FUTURE' ) );
$optDirection[] =	JHTML::_( 'select.option', 1, JText::_( 'FUTURE' ) );
$selectDirection	=	( @$this->item->id ) ? $this->item->content : 0;
$lists['dateDirection']	=	JHTML::_( 'select.genericlist', $optDirection, 'content', 'size="1" class="inputbox"', 'value', 'text', $selectDirection );

$optNavigation[]	=	JHTML::_( 'select.option', 2, JText::_( 'Month & Year' ) );
$optNavigation[]	=	JHTML::_( 'select.option', 1, JText::_( 'Month' ) );
$selectNavigation	=	( @$this->item->id ) ? $this->item->location : 2;
$lists['dateNavigation']	=	JHTML::_( 'select.genericlist', $optNavigation, 'location', 'size="1" class="inputbox"', 'value', 'text', $selectNavigation );

$optMode		=	array();
$selectMode		=	( ! @$this->isNew ) ? $this->item->bool : 0;
$optMode[]		=	JHTML::_( 'select.option',  '0', JText::_( 'DIRECTION' ), 'value', 'text' );
$optMode[]		=	JHTML::_( 'select.option',  '1', JText::_( 'DATE RESTRICTIONS' ), 'value', 'text' );
$lists['mode']	=	JHTML::_( 'select.radiolist', $optMode, 'bool', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectMode, 'bool' );

$optYearFormat			=	array();
$selectYearFormat		=	( @$this->item->bool ) ? $this->item->bool2 : 0;
$optYearFormat[]		=	JHTML::_( 'select.option',  '0', JText::_( 'LEFT' ), 'value', 'text' );
$optYearFormat[]		=	JHTML::_( 'select.option',  '1', JText::_( 'RIGHT' ), 'value', 'text' );
$lists['yearFormat']	=	JHTML::_( 'select.radiolist', $optYearFormat, 'bool2', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectYearFormat, 'bool2' );

if ( @$this->item->options && ( strpos( @$this->item->options, '||' ) !== false ) ) {
	$options	=	explode( '||', $this->item->options );
}
?>

<fieldset class="adminform">
<legend class="legend-border">
  	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CALENDAR' ); ?>::<?php echo JText::_( 'DESCRIPTION CALENDAR' ); ?>">
		<?php echo JText::_( 'CALENDAR' ); ?>
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
				<?php echo JText::_( 'NOTE CONTENT').' :: '.JText::_( 'CONSTRUCTION' ); ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FORMAT' ); ?>::<?php echo JText::_( 'FORMAT BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FORMAT' ); ?>::<?php echo JText::_( 'SELECT FORMAT' ); ?>">
					<?php echo JText::_( 'FORMAT' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="format" name="format" maxlength="50" size="32" value="<?php echo ( @$this->item->format ) ? $this->item->format : 'Y/m/d'; ?>" />
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'NAVIGATION' ); ?>::<?php echo JText::_( 'NAVIGATION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'NAVIGATION' ); ?>::<?php echo JText::_( 'SELECT NAVIGATION' ); ?>">
					<?php echo JText::_( 'NAVIGATION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['dateNavigation']; ?>
			</td>
		</tr>
        <tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
            <?php echo JText::_( 'DIRECTION RESTRICTIONS'); ?>
			</td>
        </tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DATE MODE' ); ?>::<?php echo JText::_( 'CHOOSE DATE MODE' ); ?>">
					<?php echo JText::_( 'DATE MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['mode']; ?>
			</td>
		</tr>
        <tr id="as-direction" class="<?php echo ( @$this->item->bool == 0 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DIRECTION' ); ?>::<?php echo JText::_( 'DIRECTION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DIRECTION' ); ?>::<?php echo JText::_( 'SELECT DIRECTION' ); ?>">
					<?php echo JText::_( 'DIRECTION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['dateDirection']; ?>
			</td>
		</tr>
        <tr id="as-year-start" class="<?php echo ( @$this->item->bool == 1 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'START YEAR' ); ?>::<?php echo JText::_( 'START YEAR BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'START YEAR' ); ?>::<?php echo JText::_( 'EDIT START YEAR' ); ?>">
					<?php echo JText::_( 'START YEAR' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="opt_start" name="opt_start" maxlength="250" size="32" value="<?php echo @$options[0]; ?>" />
			</td>
		</tr>
        <tr id="as-year-end" class="<?php echo ( @$this->item->bool == 1 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'END YEAR' ); ?>::<?php echo JText::_( 'END YEAR BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'END YEAR' ); ?>::<?php echo JText::_( 'EDIT END YEAR' ); ?>">
					<?php echo JText::_( 'END YEAR' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="opt_end" name="opt_end" maxlength="250" size="32" value="<?php echo @$options[1]; ?>" />
			</td>
		</tr>
        <tr id="as-year-format" class="<?php echo ( @$this->item->bool == 1 ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'YEAR POSITION' ); ?>::<?php echo JText::_( 'YEAR POSITION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'YEAR POSITION' ); ?>::<?php echo JText::_( 'CHOOSE YEAR POSITION' ); ?>">
					<?php echo JText::_( 'YEAR POSITION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['yearFormat']; ?>
			</td>
		</tr>
	</table>
   	<table class="admintable header_jseblod" >
		<tr>
			<td>
				<?php echo JText::_( 'NOTE FORM').' :: '.JText::_( 'FORM' ); ?>
			</td>
		</tr>
	</table>
    <table class="admintable">
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'STYLE' ); ?>::<?php echo JText::_( 'SELECT STYLE' ); ?>">
					<?php echo JText::_( 'STYLE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['calendarStyle']; ?>
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
$("bool0").addEvent("change", function(a) {
		a = new Event(a).stop();
		if ( $("as-direction").hasClass("display-no") ) {
			$("as-direction").removeClass("display-no");
		}
		if ( ! $("as-year-start").hasClass("display-no") ) {
			$("as-year-start").addClass("display-no");
		}
		if ( ! $("as-year-end").hasClass("display-no") ) {
			$("as-year-end").addClass("display-no");
		}
		if ( ! $("as-year-format").hasClass("display-no") ) {
			$("as-year-format").addClass("display-no");
		}
	});
$("bool1").addEvent("change", function(b) {
		b = new Event(b).stop();
		if ( $("as-year-start").hasClass("display-no") ) {
			$("as-year-start").removeClass("display-no");
		}
		if ( $("as-year-end").hasClass("display-no") ) {
			$("as-year-end").removeClass("display-no");
		}
		if ( $("as-year-format").hasClass("display-no") ) {
			$("as-year-format").removeClass("display-no");
		}
		if ( ! $("as-direction").hasClass("display-no") ) {
			$("as-direction").addClass("display-no");
		}
	});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="calendar" />
<?php } ?>