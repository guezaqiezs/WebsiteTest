<?php

/**
* Gtalk chatback badge module
*
* @package Gtalk
* @copyright (C) 2006-2008 E. Capoccetti
* @url http://zarateprop.com.ar	/
* @author Esteban Capoccetti <esteban.capoccetti@gmail.com>
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

# Module Parameters loading
$url 	= trim ($params->get('url', ''));
$tk 	= trim ($params->get('tk', ''));
$width 	= trim ($params->get('width', ''));
$height	= trim ($params->get('height', ''));
$allowTransparency = intval ($params->get('allowTransparency', 1));
$frameBorder = intval ($params->get('frameBorder', 1));

?>
<IFRAME src="<?php echo $url?>?tk=<?php echo $tk?>&amp;w=<?php echo $width?>&amp;h=<?php echo $height?>" frameBorder=<?php echo $frameBorder?> width=<?php echo $width?> height=<?php echo $height?><?php echo $allowTransparency ? " allowTransparency" : "";?>></IFRAME>
