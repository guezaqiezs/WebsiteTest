<?php
/**
 * jeFAQ package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Added style sheet
$doc = & JFactory::getDocument();
$css    = JURI::base().'components/com_jefaq/assets/css/style.css';
$doc->addStyleSheet($css);

// check if the controller name is something other than the default, if so, render the submenu appropriately
// note: the second argument in the addEntry helper function is weather or not the sub menu item is active

$controllerName = JRequest::getCmd( 'c', 'faq' );
$control = '';
switch($controllerName) {
	default:
		JSubMenuHelper::addEntry(JText::_('JE_FAQ_CONTROLLER'), 'index.php?option=com_jefaq', true);
		$control = 'faq';
		break;
}

require_once( JPATH_COMPONENT.DS.'controllers'.DS.$control.'.php' );
$controllerName = 'jefaqController'.$control;

// Create the controller
$controller = new $controllerName();

// Perform the Request task
$controller->execute( JRequest::getCmd('task') );

// Redirect if set by the controller
$controller->redirect();
?>