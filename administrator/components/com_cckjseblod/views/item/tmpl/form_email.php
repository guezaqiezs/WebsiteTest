<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes

$optValidation[]		=	JHTML::_( 'select.option', 'validate-email', JText::_( 'EMAIL' ) );
$selectValidation		=	'validate-email';
$lists['validation']	=	JHTML::_( 'select.genericlist', $optValidation, 'validation', 'size="1" class="inputbox"', 'value', 'text', $selectValidation );

$optDisplayField[] 		=	JHTML::_( 'select.option', -1, JText::_( 'No' ) );
$optDisplayField[] 		=	JHTML::_( 'select.option', 0, JText::_( 'Yes' ) );
$selectOptDisplayField	=	( @$this->item->id ) ? @$this->item->displayfield : 0;
$lists['displayField']	=	JHTML::_( 'select.radiolist', $optDisplayField, 'displayfield', 'size="1" class="inputbox"', 'value', 'text', $selectOptDisplayField );

//$optDisplayValue		=	array();
//$optDisplayValue[]		=	JHTML::_( 'select.option', -1, JText::_( 'No' ) );
//$optDisplayValue[]		=	JHTML::_( 'select.option', 1, JText::_( 'Yes' ) );
//$optDisplayValue[]		=	JHTML::_( 'select.option', 2, JText::_( 'NO SPAM' ) );
//$selectOptDisplayValue	=	( @$this->item->id ) ? @$this->item->displayvalue : 1;
//$lists['displayValue']	=	JHTML::_( 'select.radiolist', $optDisplayValue, 'displayvalue', 'size="1" class="inputbox"', 'value', 'text', $selectOptDisplayValue );

if ( @$this->item->message ) {
	$modals['message']			=	HelperjSeblod_Display::quickModalWysiwyg( 'EDITOR', $this->controller, 'message', 'pagebreak', 2, @$this->item->id, false );
} else {
	$modals['message']			=	HelperjSeblod_Display::quickModalWysiwyg( 'EDITOR', $this->controller, 'message', 'pagebreak', 1, @$this->item->id, false );
}
$tooltips['link_message']	=	HelperjSeblod_Display::quickTooltipAjaxLink( JText::_( 'MESSAGE' ), $this->controller, 'message', @$this->item->id );

$optMode	=	array();
$optMode[] 	=	JHTML::_( 'select.option', 0, JText::_( 'NEVER' ) );

$optMode[]	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'ARTICLE CATEGORY' ) );
$optMode[] 	=	JHTML::_( 'select.option', 1, JText::_( 'SUBMISSION' ) );
$optMode[] 	=	JHTML::_( 'select.option', 2, JText::_( 'EDITION' ) );
$optMode[] 	=	JHTML::_( 'select.option', 6, JText::_( 'ALWAYS SUBMISSION EDITION' ) );
$optMode[] 	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
$optMode[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'USER' ) );
$optMode[] 	=	JHTML::_( 'select.option', 3, JText::_( 'REGISTRATION' ) );
$optMode[] 	=	JHTML::_( 'select.option', 4, JText::_( 'EDITION' ) );
$optMode[] 	=	JHTML::_( 'select.option', 8, JText::_( 'ALWAYS REGISTRATION EDITION' ) );
$optMode[] 	=	JHTML::_( 'select.option', 5, JText::_( 'VALIDATION' ) );
$optMode[] 	=	JHTML::_( 'select.option', 7, JText::_( 'VALIDATION USER' ) );
$optMode[] 	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
$selectMode	=	( ! $this->isNew ) ? $this->item->bool : 0;
$lists['mode'] 	=	JHTML::_( 'select.genericlist', $optMode, 'bool', 'size="1" class="inputbox required required-enabled"', 'value', 'text', $selectMode );

$optFields			=	array();
$optFields[] 		=	JHTML::_( 'select.option', '', JText::_( 'NONE' ) );
$optFields[] 		=	JHTML::_( 'select.option', 'all', JText::_( 'ALL ITEMS' ) );
$optFields[] 		=	JHTML::_( 'select.option', 'email', JText::_( 'EMAIL ITEMS' ) );
$selectFields		=	( ! $this->isNew ) ? $this->item->location : '';
$lists['fields'] 	=	JHTML::_( 'select.genericlist', $optFields, 'location', 'size="1" class="inputbox"', 'value', 'text', $selectFields );

$optUsers		=	array();
$optUsers		=	array_merge( $optUsers, HelperjSeblod_Helper::getJoomlaAuthors( 24 ) );
$selectUsers	= 	explode( ',', @$this->item->toadmin ); ;
$lists['users']	=  JHTML::_('select.genericlist', $optUsers, 'toadmin[]', 'class="inputbox" style="width: 196px;" size="5" multiple="multiple"', 'value', 'text', $selectUsers );

$optFrom		=	array();
$optFrom[] 		=	JHTML::_( 'select.option', 0, JText::_( 'DEFAULT' ) );
$optFrom[] 		=	JHTML::_( 'select.option', 1, JText::_( 'EMAIL' ) );
$optFrom[] 		=	JHTML::_( 'select.option', 2, JText::_( 'FIELD ARTICLE' ) );
$optFrom[] 		=	JHTML::_( 'select.option', 3, JText::_( 'FIELD FORM' ) );
$selectFrom		=	( ! $this->isNew ) ? $this->item->bool2 : 0;
$lists['from'] 	=	JHTML::_( 'select.genericlist', $optFrom, 'bool2', 'size="1" class="inputbox"', 'value', 'text', $selectFrom );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'Email' ); ?>::<?php echo JText::_( 'DESCRIPTION EMAIL' ); ?>">
		<?php echo JText::_( 'Email' ); ?>
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
            	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DISPLAY FIELD' ); ?>::<?php echo JText::_( 'DISPLAY EMAIL FIELD BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'Required' ); ?>::<?php echo JText::_( 'CHOOSE REQUIRED OR NOT' ); ?>">
					<?php echo JText::_( 'Required' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['required']; ?>
			</td>
		</tr>
		<tr class="display-no">
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'VALIDATION' ); ?>::<?php echo JText::_( 'SELECT VALIDATION' ); ?>">
					<?php echo JText::_( 'VALIDATION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['validation']; ?>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'EMAIL MODE' ); ?>::<?php echo JText::_( 'SELECT EMAIL MODE' ); ?>">
					<?php echo JText::_( 'EMAIL MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['mode']; ?>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'EMAIL FROM' ); ?>::<?php echo JText::_( 'EMAIL FROM BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'EMAIL FROM' ); ?>::<?php echo JText::_( 'SELECT EMAIL FROM' ); ?>">
					<?php echo JText::_( 'EMAIL FROM' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['from']; ?>
			</td>
		</tr>
        <tr id="as-from" class="<?php echo ( @$this->item->bool2 ) ? '' : 'display-no' ?>">
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'EMAIL FROM' ); ?>::<?php echo JText::_( 'EDIT EMAIL FROM' ); ?>">
					<?php echo JText::_( 'EMAIL FROM PARAM' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="extra" name="extra" maxlength="250" size="32" value="<?php echo @$this->item->extra; ?>" />
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEST TO' ); ?>::<?php echo JText::_( 'SEPARATED BY LINEBREAK' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEST TO' ); ?>::<?php echo JText::_( 'EDIT DEST TO' ); ?>">
					<?php echo JText::_( 'DEST TO' ); ?>:
				</span>
			</td>
			<td>
                <textarea class="inputbox" id="mailto" name="mailto" cols="25" rows="2"><?php echo @$this->item->mailto; ?></textarea>
			</td>
		</tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEST TO FIELD B' ); ?>::<?php echo JText::_( 'DEST TO FIELD BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEST TO FIELD B' ); ?>::<?php echo JText::_( 'EDIT DEST TO FIELD' ); ?>">
					<?php echo JText::_( 'DEST TO FIELD' ); ?>:
				</span>
			</td>
			<td>
                <textarea class="inputbox" id="options2" name="options2" cols="25" rows="2"><?php echo @$this->item->options2; ?></textarea>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEST TO ADMINISTRATOR' ); ?>::<?php echo JText::_( 'SELECT DEST TO ADMINISTRATOR' ); ?>">
					<?php echo JText::_( 'DEST TO ADMINISTRATOR' ); ?>
				</span>
			</td>
			<td>
                <?php echo $lists['users']; ?>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SUBJECT' ); ?>::<?php echo JText::_( 'SUBJECT EMAIL BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'SUBJECT' ); ?>::<?php echo JText::_( 'EDIT SUBJECT' ); ?>">
					<?php echo JText::_( 'SUBJECT' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="content" name="content" maxlength="250" size="44" value="<?php echo @$this->item->content; ?>" />
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MESSAGE' ); ?>::<?php echo JText::_( 'MESSAGE EMAIL BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MESSAGE' ); ?>::<?php echo JText::_( 'EDIT MESSAGE' ); ?>">
					<?php echo JText::_( 'MESSAGE' ); ?>:
				</span>
			</td>
			<td>
				<span class="ajaxTip2" title="<?php echo $tooltips['link_message']; ?>">
					<?php echo $modals['message']; ?>
				</span>
			</td>
		</tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ITEMS' ); ?>::<?php echo JText::_( 'SELECT ITEMS TO SEND' ); ?>">
					<?php echo JText::_( 'ITEMS' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['fields']; ?>
			</td>
		</tr>
        <tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
	            <?php echo JText::_( 'MORE RECIPIENT'); ?>&nbsp;&nbsp;<span id="dest-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[Toggle]</span>
			</td>
        </tr>
        <tr id="as-dest-cc" class="<?php echo ( ! ( @$this->item->cc == '' && @$this->item->bcc == '' && @$this->item->options == '' ) ) ? '' : 'display-no' ?>">
            <td width="25" align="right" class="key_jseblod">
            	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEST CC' ); ?>::<?php echo JText::_( 'SEPARATED BY LINEBREAK' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEST CC' ); ?>::<?php echo JText::_( 'EDIT DEST CC' ); ?>">
					<?php echo JText::_( 'DEST CC' ); ?>:
				</span>
			</td>
			<td>
                <textarea class="inputbox" id="cc" name="cc" cols="25" rows="2"><?php echo @$this->item->cc; ?></textarea>
			</td>
		</tr>
        <tr id="as-dest-bcc" class="<?php echo ( ! ( @$this->item->cc == '' && @$this->item->bcc == '' && @$this->item->options == '' ) ) ? '' : 'display-no' ?>">
            <td width="25" align="right" class="key_jseblod">
            	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEST BCC' ); ?>::<?php echo JText::_( 'SEPARATED BY LINEBREAK' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEST BCC' ); ?>::<?php echo JText::_( 'EDIT DEST BCC' ); ?>">
					<?php echo JText::_( 'DEST BCC' ); ?>:
				</span>
			</td>
			<td>
                <textarea class="inputbox" id="bcc" name="bcc" cols="25" rows="2"><?php echo @$this->item->bcc; ?></textarea>
			</td>
		</tr>
		<tr id="as-dest-bcc2" class="<?php echo ( ! ( @$this->item->cc == '' && @$this->item->bcc == '' && @$this->item->options == '' ) ) ? '' : 'display-no' ?>">
            <td width="25" align="right" class="key_jseblod">
            	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEST BCC FIELD B' ); ?>::<?php echo JText::_( 'DEST BCC FIELD BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEST BCC FIELD B' ); ?>::<?php echo JText::_( 'EDIT DEST BCC FIELD' ); ?>">
					<?php echo JText::_( 'DEST BCC FIELD' ); ?>:
				</span>
			</td>
			<td>
                <textarea class="inputbox" id="options" name="options" cols="25" rows="2"><?php echo @$this->item->options; ?></textarea>
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
				<input class="inputbox required validate-number maxLength required-enabled" validatorProps="{maxLength:2}" type="text" id="size" name="size" maxlength="50" size="32" value="<?php echo ( @$this->item->size ) ? $this->item->size : 32; ?>" />
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
        <tr>
			<td colspan="3">
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'NO SPAM' ); ?>::<?php echo JText::_( 'EDIT NO SPAM' ); ?>">
					<?php echo JText::_( 'NO SPAM' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="format" name="format" maxlength="50" size="32" value="<?php echo ( ! @$this->isNew ) ? @$this->item->format : ' [AT] '; ?>" />
			</td>
		</tr>
    </table>
</fieldset>

<script type="text/javascript">
$("dest-toggle").addEvent("click", function(t) {
		t = new Event(t).stop();
		
		if ( $("as-dest-cc").hasClass("display-no") ) {
			$("as-dest-cc").removeClass("display-no");
		} else {
			$("as-dest-cc").addClass("display-no");
		}
		if ( $("as-dest-bcc").hasClass("display-no") ) {
			$("as-dest-bcc").removeClass("display-no");
		} else {
			$("as-dest-bcc").addClass("display-no");
		}
		if ( $("as-dest-bcc2").hasClass("display-no") ) {
			$("as-dest-bcc2").removeClass("display-no");
		} else {
			$("as-dest-bcc2").addClass("display-no");
		}
	});

$("bool2").addEvent("change", function(f) {
		f = new Event(f).stop();
		
		var layout = $("bool2").value;
		
		if ( layout > 0) {
			if ( $("as-from").hasClass("display-no") ) {
				$("as-from").removeClass("display-no");
			}
		} else {
			if ( ! $("as-from").hasClass("display-no") ) {
				$("as-from").addClass("display-no");
			}			
		}
	});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="email" />
<?php } ?>