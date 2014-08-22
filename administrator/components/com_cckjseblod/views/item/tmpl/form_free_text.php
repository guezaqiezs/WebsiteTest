<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes

$optDisplayField[] 		= JHTML::_( 'select.option', -1, JText::_( 'No' ) );
$optDisplayField[]		= JHTML::_( 'select.option', 0, JText::_( 'Yes' ) );
$selectOptDisplayField	=	( @$this->item->id ) ? @$this->item->displayfield : 0;
$lists['displayField']	= JHTML::_( 'select.radiolist', $optDisplayField, 'displayfield', 'size="1" class="inputbox"', 'value', 'text', $selectOptDisplayField );

$modals['defaultvalue']		=	HelperjSeblod_Display::quickModalWysiwyg( 'EDITOR', $this->controller, 'defaultvalue', 'pagebreak', 0, @$this->item->id, false );
$tooltips['link_defaultvalue']	=	HelperjSeblod_Display::quickTooltipAjaxLink( JText::_( 'VALUE' ), $this->controller, 'defaultvalue', @$this->item->id );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FREE TEXT' ); ?>::<?php echo JText::_( 'DESCRIPTION FREE TEXT' ); ?>">
		<?php echo JText::_( 'FREE TEXT' ); ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEFAULT VALUE' ); ?>::<?php echo JText::_( 'EDIT DEFAULT VALUE' ); ?>">
					<?php echo JText::_( 'DEFAULT VALUE' ); ?>:
				</span>
			</td>
			<td>
				<span class="ajaxTip2" title="<?php echo $tooltips['link_defaultvalue']; ?>">
					<?php echo $modals['defaultvalue']; ?>
				</span>
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
   	<table class="admintable header_jseblod" >
		<tr>
			<td>
				<?php echo JText::_( 'NOTE FORM CONTENT').' :: '.JText::_( 'CODE' ); ?>&nbsp;&nbsp;<span id="code-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[Toggle]</span>
			</td>
		</tr>
	</table>
    <table id="as-code" class="admintable <?php echo ( ! ( @$this->item->codebefore == '' && @$this->item->codeafter == '' ) ) ? '' : 'display-no' ?>">
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CODE BEFORE FIELD' ); ?>::<?php echo JText::_( 'CODE BEFORE FIELD BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CODE BEFORE FIELD' ); ?>::<?php echo JText::_( 'EDIT CODE BEFORE FIELD' ); ?>">
					<?php echo JText::_( 'CODE BEFORE FIELD' ); ?>:
				</span>
			</td>
			<td>
				<textarea class="inputbox" id="codebefore" name="codebefore" cols="25" rows="2"><?php echo @$this->item->codebefore; ?></textarea>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CODE AFTER FIELD' ); ?>::<?php echo JText::_( 'CODE AFTER FIELD BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CODE AFTER FIELD' ); ?>::<?php echo JText::_( 'EDIT CODE AFTER FIELD' ); ?>">
					<?php echo JText::_( 'CODE AFTER FIELD' ); ?>:
				</span>
			</td>
			<td>
				<textarea class="inputbox" id="codeafter" name="codeafter" cols="25" rows="2"><?php echo @$this->item->codeafter; ?></textarea>
			</td>
		</tr>
	</table>
</fieldset>

<script type="text/javascript">
$("code-toggle").addEvent("click", function(c) {
		c = new Event(c).stop();
		
		if ( $("as-code").hasClass("display-no") ) {
			$("as-code").removeClass("display-no");
		} else {
			$("as-code").addClass("display-no");
		}
	});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="free_text" />
<?php } ?>