<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<script type='text/javascript'>
var image_base_path = '<?php $params =& JComponentHelper::getParams('com_media');
echo $params->get('image_path', 'images/stories');?>/';
</script>

<script type='text/javascript'>
ImageManager.onok = function()
{
	var url		= this.fields.url.getValue();
	//var alt		= this.fields.alt.getValue();

	var into	= this.editor;
	var into_hidden	= into+"_hidden";
	window.parent.$(into).value = url;
	window.parent.$(into_hidden).value = url;
	
	return false;
}
</script>

<form enctype="multipart/form-data" action="index.php" method="post" id="imageForm">
	<div id="messages" style="display: none;">
		<span id="message"></span><img src="<?php echo JURI::root() ?>/administrator/components/com_media/images/dots.gif" width="22" height="12" alt="..." />
	</div>
	<fieldset>
		<div style="float: left">
			<label for="folder"><?php echo JText::_('Directory') ?></label>
			<?php echo $this->folderList; ?>
			<button type="button" id="upbutton" title="<?php echo JText::_('Directory Up') ?>"><?php echo JText::_('Up') ?></button>
		</div>
		<div style="float: right">
			<button type="button" onclick="ImageManager.onok();window.parent.document.getElementById('sbox-window').close();"><?php echo JText::_('Insert') ?></button>
			<button type="button" onclick="window.parent.document.getElementById('sbox-window').close();"><?php echo JText::_('Cancel') ?></button>
		</div>
	</fieldset>
	<iframe id="imageframe" name="imageframe" src="index.php?option=com_media&amp;view=imagesList&amp;tmpl=component&amp;folder=<?php echo $this->state->folder?>"></iframe>

	<fieldset>
		<table class="properties">
			<tr>
				<td><label for="f_url"><?php echo JText::_('Image URL') ?></label></td>
				<td><input type="text" id="f_url" value="" /></td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" id="dirPath" name="dirPath" />
	<input type="hidden" id="f_file" name="f_file" />
	<input type="hidden" id="tmpl" name="component" />
</form>
