<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.modal' );
$rel	=	"{handler: 'iframe', size: {x: 800, y: 500}}";
?>
<?php
echo $mainframe->getSiteURL();
		global $mainframe;

		$tp = intval($showPositions);
		$url = $client->id ? JURI::base() : $mainframe->getSiteURL();
?>
		<style type="text/css">
		.previewFrame {
			border: none;
			width: 800;
			height: 500px;
		}
		</style>

		<table class="adminform">
			<tr>
				<td width="100%" valign="top" colspan="2">
					<?php echo JHTML::_('iframe', $url.'index.php?option=com_content&view=article&id=73&tmpl=component', 'previewFrame',  array('class' => 'previewFrame')) ?>
				</td>
			</tr>
		</table>