<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php 
// Specific Attributes

?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'BUTTON FREE' ); ?>::<?php echo JText::_( 'DESCRIPTION BUTTON FREE' ); ?>">
		<?php echo JText::_( 'BUTTON FREE' ); ?>
    </span>
</legend>
	<table class="admintable">
	    <?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'LABEL' ); ?>::<?php echo JText::_( 'BUTTON LABEL BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'LABEL' ); ?>::<?php echo JText::_( 'EDIT LABEL' ); ?>">
					<?php echo JText::_( 'LABEL' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="label" name="label" maxlength="50" size="32" value="<?php echo @$this->item->label; ?>" />
			</td>
		</tr>
        <?php } ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'JS ONCLICK' ); ?>::<?php echo JText::_( 'EDIT JS ONCLICK' ); ?>">
					<?php echo JText::_( 'JS ONCLICK' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="options" name="options" maxlength="50" size="32" value="<?php echo @$this->item->options; ?>" />
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CSS CLASS' ); ?>::<?php echo JText::_( 'CSS CLASS BUTTON BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CSS CLASS' ); ?>::<?php echo JText::_( 'EDIT CSS CLASS' ); ?>">
					<?php echo JText::_( 'CSS CLASS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="css" name="css" maxlength="50" size="32" value="<?php echo @$this->item->css; ?>" />
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'HTML STYLE' ); ?>::<?php echo JText::_( 'STYLE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'HTML STYLE' ); ?>::<?php echo JText::_( 'EDIT HTML STYLE' ); ?>">
					<?php echo JText::_( 'HTML STYLE' ); ?>:
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
<input type="hidden" name="type" value="button_free" />
<?php } ?>