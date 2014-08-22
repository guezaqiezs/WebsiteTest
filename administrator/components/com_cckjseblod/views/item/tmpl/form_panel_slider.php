<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optCloserMode		=	array();
$optCloserMode[] 	=	JHTML::_( 'select.option', 0, JText::_( 'NONE' ) );
$optCloserMode[] 	=	JHTML::_( 'select.option', 1, JText::_( 'AS PANEL CLOSER' ) );
$selectCloserMode	=	( ! $this->isNew ) ? $this->item->bool2 : 0;
$lists['forceEnd'] 	=	JHTML::_( 'select.genericlist', $optCloserMode, 'bool2', 'size="1" class="inputbox"', 'value', 'text', $selectCloserMode );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PANEL SLIDER' ); ?>::<?php echo JText::_( 'DESCRIPTION PANEL SLIDER' ); ?>">
		<?php echo JText::_( 'PANEL SLIDER' ); ?>
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
        <?php } ?>
        <tr>
        	<td colspan="3">
			</td>
        </tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
   	        	<?php echo JText::_( 'END PANEL CLOSER' ); ?>&nbsp;&nbsp;<span id="force-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[Toggle]</span>
			</td>
        </tr>
		<tr id="as-force-1" class="<?php echo ( @$this->item->bool2 ) ? '' : 'display-no' ?>" >
            <td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CLOSER MODE' ); ?>::<?php echo JText::_( 'CLOSER MODE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CLOSER MODE' ); ?>::<?php echo JText::_( 'CHOOSE CLOSER MODE OR NOT' ); ?>">
					<?php echo JText::_( 'CLOSER MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['forceEnd']; ?>
			</td>
		</tr>
	</table>
   	<table class="admintable header_jseblod">
		<tr>
			<td>
				<?php echo JText::_( 'NOTE FORM CONTENT').' :: '.JText::_( 'STYLE' ); ?>
			</td>
		</tr>
	</table>
	<table class="admintable">
		<tr>
			<td colspan="3">
				<?php echo JText::_( 'PANEL EXPLANATION' ); ?>
			</td>
		</tr>
    </table>
</fieldset>

<script type="text/javascript">
	window.addEvent( "domready",function(){
					
	$("force-toggle").addEvent("click", function(f) {
			f = new Event(f).stop();
			
			if ( $("as-force-1").hasClass("display-no") ) {
				$("as-force-1").removeClass("display-no");
			} else {
				$("as-force-1").addClass("display-no");
			}
		});
	 });
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="panel_slider" />
<?php } ?>