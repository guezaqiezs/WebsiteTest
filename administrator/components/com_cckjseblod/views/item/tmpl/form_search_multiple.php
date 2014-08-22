<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optStyle[]			=	JHTML::_( 'select.option', '', JText::_( 'SELECT A VARIATION' ), 'value', 'text' );
$optStyle[] 		=	JHTML::_( 'select.option', 'radio', JText::_( 'RADIO' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'select_numeric', JText::_( 'SELECT NUMERIC' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'select_simple', JText::_( 'SELECT SIMPLE DROPDOWN' ) );
$optStyle[] 		=	JHTML::_( 'select.option', 'text', JText::_( 'TEXT' ) );
$selectStyle		=	( ! @$this->isNew ) ? @$this->item->stylextd : '';
$lists['style']	=	JHTML::_( 'select.genericlist', $optStyle, 'stylextd', 'size="1" class="inputbox required required-enabled"', 'value', 'text', $selectStyle );

//$optParam[] 		=	JHTML::_( 'select.option', 0, JText::_( 'INHERIT' ) );
//if ( ! @$this->item->boolxtd ) {
//	$optParam[] 	=	JHTML::_( 'select.option', 2, JText::_( 'CUSTOM' ) );
//} else {
	$optParam[] 	=	JHTML::_( 'select.option', 1, JText::_( 'CUSTOM' ) );
//	$optParam[] 	=	JHTML::_( 'select.option', 2, JText::_( 'CUSTOM RESET' ) );
//}
$selectParam		=	( ! @$this->isNew ) ? @$this->item->boolxtd : 0;
$lists['param']		=	JHTML::_( 'select.genericlist', $optParam, 'boolxtd', 'size="1" class="inputbox"', 'value', 'text', $selectParam );

$lists['match']		=	JHTML::_('select.booleanlist', 'bool8', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool8 : 0 );

$optMatch[] 		=	JHTML::_( 'select.option', 0, JText::_( 'SEARCH AND' ) );
$optMatch[] 		=	JHTML::_( 'select.option', 1, JText::_( 'SEARCH OR' ) );
$selectMatch		=	( ! @$this->isNew ) ? @$this->item->bool8 : 0;
$lists['match']		=	JHTML::_( 'select.genericlist', $optMatch, 'bool8', 'size="1" class="inputbox required required-enabled"', 'value', 'text', $selectMatch );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH MULTIPLE' ); ?>::<?php echo JText::_( 'DESCRIPTION SEARCH MULTIPLE' ); ?>">
		<?php echo JText::_( 'SEARCH MULTIPLE' ); ?>
    </span>
</legend>
	<table class="admintable">
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH IN' ); ?>::<?php echo JText::_( 'SEARCH IN MULTIPLE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH IN' ); ?>::<?php echo JText::_( 'SEARCH IN MULTIPLE' ); ?>">
					<?php echo JText::_( 'SEARCH IN' ); ?>:
				</span>
			</td>
			<td>
				<textarea class="inputbox" id="content" name="content" cols="30" rows="5"><?php echo @$this->item->content; ?></textarea>
			</td>
		</tr>
		<tr id="as-multiple" class="<?php echo ( ! @$this->item->content || strpos( @$this->item->content, ',' ) === false  ) ? 'display-no' : '' ?>">
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEARCH AND OR' ); ?>::<?php echo JText::_( 'SEARCH AND OR' ); ?>">
					<?php echo JText::_( 'SEARCH AND OR' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['match']; ?>
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
			<td colspan="2">
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
			<td colspan="2">
				<?php echo $lists['param']; ?>             
			</td>
		</tr>
	</table>
</fieldset>

<?php
if ( @$this->item->boolxtd && $this->item->stylextd ) {
	if ( JFile::exists( dirname(__FILE__).DS.'form_'.$this->item->stylextd.'.php' ) ) {
		include_once( dirname(__FILE__).DS.'form_'.$this->item->stylextd.'.php' );
	}
}
?>

<script type="text/javascript">
$("content").addEvent("keyup", function(c) {
		c = new Event(c).stop();
		
		var layout = $("content").value;
		if ( layout.indexOf( "," ) != -1 ) {
			if ( $("as-multiple").hasClass("display-no") ) {
				$("as-multiple").removeClass("display-no");
			}
		} else {
			if ( ! $("as-multiple").hasClass("display-no") ) {
				$("as-multiple").addClass("display-no");
			}
		}
	});
</script>

<input type="hidden" name="type" value="search_multiple" />