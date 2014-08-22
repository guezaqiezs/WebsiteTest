<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
// Specific Attributes
$optEvent			=	array();
$optEvent[] 		=	JHTML::_( 'select.option', 0, JText::_( 'onBeforeContentSave' ) );
$optEvent[] 		=	JHTML::_( 'select.option', 1, JText::_( 'onAfterContentSave' ) );
$optEvent[] 		=	JHTML::_( 'select.option', 2, JText::_( 'onBeforeDisplayContent' ) );
$optEvent[] 		=	JHTML::_( 'select.option', 3, JText::_( 'onBeforeDisplayForm' ) );
$num				=	count( $optEvent );
$selectEvent		=	( ! $this->isNew ) ? $this->item->bool : 0;
$lists['event'] 	=	JHTML::_( 'select.genericlist', $optEvent, 'bool', 'class="inputbox" multiple="multiple" size="'.$num.'" disabled="disabled"', 'value', 'text', $selectEvent );

$optMode			=	array();
$optMode[] 			=	JHTML::_( 'select.option', 0, JText::_( 'CODE' ) );
$optMode[] 			=	JHTML::_( 'select.option', 1, JText::_( 'FILE' ) );
$selectMode			=	( ! $this->isNew ) ? $this->item->bool2 : 0;
$lists['mode'] 		=	JHTML::_( 'select.radiolist', $optMode, 'bool2', 'size="1" class="inputbox"', 'value', 'text', $selectMode );

if ( @$this->item->options ) {
	$options	=	explode( '||', $this->item->options );
	$nOpt	=	count( $options );
} else {
	$nOpt	=	0;
}
?>

<script type="text/javascript">
	function addElement(parentId, elementTag, elementId, html) {
		var p = document.getElementById(parentId);
		var newElement = document.createElement(elementTag);
		newElement.setAttribute('id', elementId);
		newElement.innerHTML = html;
		p.appendChild(newElement);
	}

	function removeElement(elementId) {
		var element = document.getElementById(elementId);
		element.parentNode.removeChild(element);
	}
	
	function addOption() {
		optId++;
		var img_del = '<?php echo _IMG_DEL; ?>'; 
		var html = '<input class="inputbox" type="text" id="options" name="options[]" maxlength="250" size="32" value="" /> ' +
				   '<a href="javascript: removeElement(\'opt-' + optId + '\');">'+img_del+'</a>';
		addElement('options', 'p', 'opt-' + optId, html);
	}
	
	var optId = "<?php echo $nOpt; ?>";
</script>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FREE CODE' ); ?>::<?php echo JText::_( 'DESCRIPTION FREE CODE' ); ?>">
		<?php echo JText::_( 'FREE CODE' ); ?>
    </span>
</legend>
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
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'EVENT' ); ?>::<?php echo JText::_( 'SELECT EVENT' ); ?>">
					<?php echo JText::_( 'EVENT' ); ?>:
				</span>
			</td>
			<td>
	            <?php echo $lists['event']; ?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MODE' ); ?>::<?php echo JText::_( 'CHOOSE FREE CODE MODE' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MODE' ); ?>::<?php echo JText::_( 'CHOOSE MODE' ); ?>">
					<?php echo JText::_( 'MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['mode']; ?>
			</td>
		</tr>
		<tr id="as-simple" class="<?php echo ( ! @$this->item->bool2 ) ? '' : 'display-no' ?>">
		  <td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PHP CODE' ); ?>::<?php echo JText::_( 'PHP CODE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PHP CODE' ); ?>::<?php echo JText::_( 'EDIT PHP CODE' ); ?>">
					<?php echo JText::_( 'PHP CODE' ); ?>:
				</span>
			</td>
			<td>
				<textarea class="inputbox required required-enabled" id="defaultvalue" name="defaultvalue" cols="32" rows="20"><?php echo @$this->item->defaultvalue; ?></textarea>
			</td>
		</tr>
		<tr id="as-advanced" class="<?php echo ( @$this->item->bool2 ) ? '' : 'display-no' ?>">
		  <td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PHP FILE' ); ?>::<?php echo JText::_( 'PHP FILE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PHP FILE' ); ?>::<?php echo JText::_( 'EDIT PHP FILE' ); ?>">
					<?php echo JText::_( 'PHP FILE' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox required required-enabled" type="text" id="location" name="location" maxlength="250" size="32" value="<?php echo ( @$this->item->location ) ? $this->item->location : ''; ?>" />
			</td>
		</tr>
        <tr>
        	<td colspan="3">
            </td>
        </tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
	            <?php echo JText::_( 'VARIABLES' ); ?>
			</td>
        </tr>
		<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ADD VARIABLE' ); ?>::<?php echo JText::_( 'CLICK TO ADD A VARIABLE' ); ?>">
					<?php echo JText::_( 'ADD VARIABLE' ); ?>:
				</span>
			</td>
			<td>
				<a href="javascript: addOption();"><?php echo _IMG_ADD; ?></a>
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'VARIABLES' ); ?>::<?php echo JText::_( 'EDIT VARIABLES' ); ?>">
					<?php echo JText::_( 'VARIABLES' ); ?>:
				</span>
			</td>
			<td>
				<div id="options">
				<?php if ( $nOpt ) {
					for ( $i = 0; $i < $nOpt; $i++ ) {
						$j = $i + 1; ?>
						<p id="opt-<?php echo $j; ?>"><input class="inputbox" type="text" id="options" name="options[]" maxlength="250" size="32" value="<?php echo $options[$i]; ?>" />&nbsp;<a href="javascript: removeElement('opt-<?php echo $j; ?>');"><?php echo _IMG_DEL; ?></a></p>
					<?php } } ?>
				</div>
			</td>
		</tr>
	</table>
</fieldset>

<script type="text/javascript">
	window.addEvent( "domready",function(){

	$("bool20").addEvent("change", function(m0) {
			m0 = new Event(m0).stop();
			
			if ( ! $("as-advanced").hasClass("display-no") ) {
				$("as-advanced").addClass("display-no");
			}
			if ( $("as-simple").hasClass("display-no") ) {
				$("as-simple").removeClass("display-no");
			}
		});
	$("bool21").addEvent("change", function(m1) {
			m1 = new Event(m1).stop();
			
			if ( $("as-advanced").hasClass("display-no") ) {
				$("as-advanced").removeClass("display-no");
			}
			if ( ! $("as-simple").hasClass("display-no") ) {
				$("as-simple").addClass("display-no");
			}
		});
	});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="free_code" />
<?php } ?>