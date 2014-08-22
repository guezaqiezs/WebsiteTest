<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<script type="text/javascript">
	window.addEvent("domready",function(){
		$("import_mode0").addEvent("click", function(){
			if ( $("import_pack").value ) {
				submitbutton('importXml');
			}
		});
		$("import_mode1").addEvent("click", function(){
			if ( $("import_pack").value ) {
				submitbutton('importXml');
			}
		});

	});
</script>

<fieldset class="adminform">
    <legend class="legend-border"><?php echo JText::_( 'CONTENT PACKS IMPORT' ); ?></legend>
    <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-bottom: 10px;">
    <table class="admintable" >
        <tr>
            <td>
                <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                <?php echo JText::_( 'DESCRIPTION IMPORT CONTENT PACKS' ); ?>
            </td>
        </tr>
    </table>
    </span>
    <table class="admintable">
        <tr>
            <td width="25" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'IMPORT' ); ?>::<?php echo JText::_( 'SELECT IMPORT MODE' ); ?>">
                    <?php echo JText::_( 'IMPORT' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['import_selection']; ?>
            </td>
        </tr>
        <tr>
            <td width="25" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'IMPORT MODE' ); ?>::<?php echo JText::_( 'SELECT IMPORT MODE' ); ?>">
                    <?php echo JText::_( 'IMPORT MODE' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['import_mode']; ?>
            </td>
        </tr>
        <tr>
        	<td colspan="3">
            </td>
        </tr>
        <tr>
            <td width="25" class="key_jseblod">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'IMPORT PACK' ); ?>::<?php echo JText::_( 'IMPORT CONTENT PACK BALLOON' ); ?>">
                    <?php echo _IMG_IMPORT; ?>
                </span>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT PACK' ); ?>::<?php echo JText::_( 'BROWSE CONTENT PACK' ); ?>">
                    <?php echo JText::_( 'CONTENT PACK' ); ?>:
                </span>
            </td>
            <td>
                <input class="input_box" type="file" id="import_pack" name="import_pack" size="32" />
            </td>
        </tr>
    </table>
</fieldset>