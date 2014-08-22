<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<script src="<?php echo JURI::root()._PATH_MOORAINBOW; ?>moorainbow.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo JURI::root()._PATH_MOORAINBOW; ?>moorainbow.css" type="text/css" />

<?php
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$buttons = array( 'Update'	=> array( 'Update', 'forward', "javascript: mediaProcess();", 'onclick' ),
   				  'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				  'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
				 
$javascript = '

	window.addEvent( "domready",function(){
		$("location").value		=	"images/"+parent.$("folderbase").value
	});

	// Save Button
	function mediaProcess() {
		if ( $("location") ) {
			$("location").disabled=false;
		}
		document.adminForm.submit();
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-process" style="float: left">
		<?php echo JText::_( 'MEDIA PROCESS' ); ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
		<legend class="legend-border"><?php echo JText::_( 'BATCH PROCESS' ); ?></legend>
		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-bottom: 10px;">
		<table class="admintable" >
			<tr>
				<td>
					<strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
					<?php echo JText::_( 'DESCRIPTION MEDIA PROCESS' ); ?>
				</td>
			</tr>
		</table>
		</span>
		<table class="admintable">
			<tr>
				<td width="25" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FOLDER' ); ?>::<?php echo JText::_( 'FOLDER' ); ?>">
						<?php echo JText::_( 'FOLDER' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox" type="text" id="location" name="location" maxlength="250" disabled="disabled" size="64" value="" />
				</td>
			</tr>
			<tr>
				<td width="25" class="key_jseblod">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'TITLE' ); ?>::<?php echo JText::_( 'MEDIA PROCESS TITLE BALLOON' ); ?>">
                        <?php echo _IMG_BALLOON_LEFT; ?>
                    </span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TITLE' ); ?>::<?php echo JText::_( 'EDIT TITLE' ); ?>">
						<?php echo JText::_( 'TITLE' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox" type="text" id="rename" name="rename" maxlength="250" size="32" value="" />
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
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'IMAGE' ); ?>::<?php echo JText::_( 'SELECT IMAGE PROCESSING' ); ?>">
                        <?php echo JText::_( 'IMAGE' ); ?>:
                    </span>
                </td>
                <td>
                    <?php echo $this->lists['original']; ?>
                </td>
            </tr>
			<tr>
                <td width="25" align="right" class="key_jseblod">
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
                        <?php echo JText::_( 'WIDTH' ); ?>:
                    </span>
                </td>
                <td>
                    <input class="inputbox validate-number" type="text" id="width" name="width" maxlength="50" size="16" value="<?php echo ( @$this->item->width ) ? $this->item->width : 0; ?>" />
                </td>
            </tr>
            <tr>
                <td width="25" align="right" class="key_jseblod">
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'HEIGHT' ); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
                        <?php echo JText::_( 'HEIGHT' ); ?>:
                    </span>
                </td>
                <td>
                    <input class="inputbox validate-number" type="text" id="height" name="height" maxlength="50" size="16" value="<?php echo ( @$this->item->height ) ? $this->item->height : 100; ?>" />
                </td>
            </tr>
            <tr>
                <td width="25" align="right" class="key_jseblod">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'WATERMARK' ); ?>::<?php echo JText::_( 'WATERMARK BALLOON' ); ?>">
                        <?php echo _IMG_BALLOON_LEFT; ?>
                    </span>
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'WATERMARK' ); ?>::<?php echo JText::_( 'EDIT WATERMARK' ); ?>">
                        <?php echo JText::_( 'WATERMARK' ); ?>:
                    </span>
                </td>
                <td>
                    <input class="inputbox" type="text" id="options2" name="options2" maxlength="50" size="32" value="<?php echo ( @$this->item->options2 ) ? $this->item->options2 : ''; ?>" />
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
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'ADD COLOR' ); ?>::<?php echo JText::_( 'PICK ADD COLOR' ); ?>">
                        <?php echo JText::_( 'ADD COLOR' ); ?>:
                    </span>
                </td>
                <td>
                    <input class="inputbox" type="text" id="extra" name="extra" maxlength="7" size="16" value="#FFFFFF" />&nbsp;&nbsp;
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'ADD COLOR' ); ?>::<?php echo JText::_( 'PICK ADD COLOR' ); ?>">
                        <?php echo _IMG_COLOR_GRID; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                </td>
            </tr>
		    <tr>
                <td colspan="3" class="keytext_jseblod">
                    <?php echo JText::_( 'THUMB').' 1'; ?>
                </td>
            </tr>
            <tr>
                <td width="25" align="right" class="key_jseblod">
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'THUMB').' 1'; ?>::<?php echo JText::_( 'SELECT THUMB PROCESSING' ); ?>">
                        <?php echo JText::_( 'THUMB').' 1'; ?>:
                    </span>
                </td>
                <td>
                    <?php echo $this->lists['thumb1']; ?>
                </td>
            </tr>
            <tr>
                <td width="25" align="right" class="key_jseblod">
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
                        <?php echo JText::_( 'WIDTH' ); ?>:
                    </span>
                </td>
                <td>
                    <input class="inputbox validate-number" type="text" id="width1" name="width1" maxlength="50" size="16" value="<?php echo ( @$this->item->options && $opts[0][1] ) ? $opts[0][1] : 150; ?>" />
                </td>
            </tr>
            <tr>
                <td width="25" align="right" class="key_jseblod">
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'HEIGHT' ); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
                        <?php echo JText::_( 'HEIGHT' ); ?>:
                    </span>
                </td>
                <td>
                    <input class="inputbox validate-number" type="text" id="height1" name="height1" maxlength="50" size="16" value="<?php echo ( @$this->item->options && $opts[0][2] ) ? $opts[0][2] : 0; ?>" />
                </td>
            </tr>
            <tr>
                <td colspan="3">
                </td>
            </tr>
            <tr>
                <td colspan="3" class="keytext_jseblod">
                    <?php echo JText::_( 'THUMB').' 2'; ?>&nbsp;&nbsp;<span id="thumb2-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[Toggle]</span>
                </td>
            </tr>
            <tr id="as-thumb2-1" class="display-no" >
                <td width="25" align="right" class="key_jseblod">
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'THUMB').' 2'; ?>::<?php echo JText::_( 'SELECT THUMB PROCESSING' ); ?>">
                        <?php echo JText::_( 'THUMB').' 2'; ?>:
                    </span>
                </td>
                <td>
                    <?php echo $this->lists['thumb2']; ?>
                </td>
            </tr>
            <tr id="as-thumb2-2" class="display-no" >
                <td width="25" align="right" class="key_jseblod">
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
                        <?php echo JText::_( 'WIDTH' ); ?>:
                    </span>
                </td>
                <td>
                    <input class="inputbox validate-number" type="text" id="width2" name="width2" maxlength="50" size="16" value="<?php echo ( @$this->item->options && $opts[1][1] ) ? $opts[1][1] : 0; ?>" />
                </td>
            </tr>
            <tr id="as-thumb2-3" class="display-no" >
                <td width="25" align="right" class="key_jseblod">
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'HEIGHT'); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
                        <?php echo JText::_( 'HEIGHT').' 2'; ?>:
                    </span>
                </td>
                <td>
                    <input class="inputbox validate-number" type="text" id="height2" name="height2" maxlength="50" size="16" value="<?php echo ( @$this->item->options && $opts[1][2] ) ? $opts[1][2] : 0; ?>" />
                </td>
            </tr>
            <tr id="as-thumb2-4" class="<?php echo ( @$opts[1][1] ) ? '' : 'display-no' ?>" >
                <td colspan="3">
                </td>
            </tr>
            <tr>
                <td colspan="3" class="keytext_jseblod">
                    <?php echo JText::_( 'THUMB').' 3'; ?>&nbsp;&nbsp;<span id="thumb3-toggle" style="font-weight:normal; font-style:italic; cursor:pointer;">[Toggle]</span>
                </td>
            </tr>
            <tr id="as-thumb3-1" class="display-no" >
                <td width="25" align="right" class="key_jseblod">
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'THUMB').' 3'; ?>::<?php echo JText::_( 'SELECT THUMB PROCESSING' ); ?>">
                        <?php echo JText::_( 'THUMB').' 3'; ?>:
                    </span>
                </td>
                <td>
                    <?php echo $this->lists['thumb3']; ?>
                </td>
            </tr>
            <tr id="as-thumb3-2" class="display-no" >
                <td width="25" align="right" class="key_jseblod">
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
                        <?php echo JText::_( 'WIDTH' ); ?>:
                    </span>
                </td>
                <td>
                    <input class="inputbox validate-number" type="text" id="width3" name="width3" maxlength="50" size="16" value="<?php echo ( @$this->item->options && $opts[2][1] ) ? $opts[2][1] : 0; ?>" />
                </td>
            </tr>
            <tr id="as-thumb3-3" class="display-no" >
                <td width="25" align="right" class="key_jseblod">
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'HEIGHT' ); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
                        <?php echo JText::_( 'HEIGHT' ); ?>:
                    </span>
                </td>
                <td>
                    <input class="inputbox validate-number" type="text" id="height3" name="height3" maxlength="50" size="16" value="<?php echo ( @$this->item->options && $opts[2][2] ) ? $opts[2][2] : 0; ?>" />
                </td>
            </tr>
		</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_cckjseblod" />
<input type="hidden" name="controller" value="" />
<input type="hidden" name="task" value="mediaProcess" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>

<script type="text/javascript">
	window.addEvent( "domready",function(){
					
		var init3 = '#FFFFFF';
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
	
	$("thumb2-toggle").addEvent("click", function(t2) {
			t2 = new Event(t2).stop();
			
			if ( $("as-thumb2-1").hasClass("display-no") ) {
				$("as-thumb2-1").removeClass("display-no");
			} else {
				$("as-thumb2-1").addClass("display-no");
			}
			if ( $("as-thumb2-2").hasClass("display-no") ) {
				$("as-thumb2-2").removeClass("display-no");
			} else {
				$("as-thumb2-2").addClass("display-no");
			}
			if ( $("as-thumb2-3").hasClass("display-no") ) {
				$("as-thumb2-3").removeClass("display-no");
			} else {
				$("as-thumb2-3").addClass("display-no");
			}
			if ( $("as-thumb2-4").hasClass("display-no") ) {
				$("as-thumb2-4").removeClass("display-no");
			} else {
				$("as-thumb2-4").addClass("display-no");
			}
		});
	$("thumb3-toggle").addEvent("click", function(t3) {
			t3 = new Event(t3).stop();
			
			if ( $("as-thumb3-1").hasClass("display-no") ) {
				$("as-thumb3-1").removeClass("display-no");
			} else {
				$("as-thumb3-1").addClass("display-no");
			}
			if ( $("as-thumb3-2").hasClass("display-no") ) {
				$("as-thumb3-2").removeClass("display-no");
			} else {
				$("as-thumb3-2").addClass("display-no");
			}
			if ( $("as-thumb3-3").hasClass("display-no") ) {
				$("as-thumb3-3").removeClass("display-no");
			} else {
				$("as-thumb3-3").addClass("display-no");
			}
		});
</script>