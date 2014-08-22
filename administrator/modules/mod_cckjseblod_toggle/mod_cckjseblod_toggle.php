<?php
/*
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
*/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

global $option;
$task	=	JRequest::getCmd( 'task' );
JHTML::_( 'stylesheet', 'cckjseblod_toggle.css', 'administrator/modules/mod_cckjseblod_toggle/assets/css/' );

if ( ( $option == 'com_content' || $option == 'com_categories' || $option == 'com_users' ) && ( ! $task || $task == 'view' ) ) {

$title	=	JText::_( 'Toggle CCK' );

$output	= "<span style=\"line-height:0px; padding-top: 1px;\">"
		. "<span style=\"line-height:0px; padding-top: 11px; padding-right: 4px; color: #000000;\"><label for=\"toggle-cck\"><b>$title</b></label></span>"
		. "<input style=\"color: #000000;\" id=\"toggle-cck\" type=\"checkbox\" checked=\"checked\" value=\"1\" name=\"toggle-cck\"/>"
		. "</span>";

echo $output;
}
?>