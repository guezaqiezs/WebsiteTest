<?php
/**
* @version		1.0.0
* @package		Sami Login
* @copyright	Copyright (C) 2011 samielkady.com Open Source Matters. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_( 'behavior.mootools' );
$document = &JFactory::getDocument();
$document->addScript(JURI::base().'modules/mod_samilogin2/js/jquery.min.js');
$document->addScript(JURI::base().'modules/mod_samilogin2/js/jquery-ui.min.js');
$document->addScript(JURI::base().'modules/mod_samilogin2/js/side-bar.js');
$document->addStyleSheet(JURI::base().'modules/mod_samilogin2/css/style.css');
// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
$type 	= modSamiLogin2Helper::getType();
$return	= modSamiLogin2Helper::getReturnURL($type);
$user =& JFactory::getUser();
require(JModuleHelper::getLayoutPath('mod_samilogin2'));