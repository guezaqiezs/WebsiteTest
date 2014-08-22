<?php
/**
 * CustomFlash Joomla! 1.5 Native Component
 * @version 1.2.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/


// no direct access
defined('_JEXEC') or die('Restricted access');


$controllerName = JRequest::getCmd( 'controller', 'flashmovies' );


switch($controllerName)
{
	
	case 'docs';

		JSubMenuHelper::addEntry(JText::_('Flash Movies'), 'index.php?option=com_customflash&controller=flashmovies', false);
		JSubMenuHelper::addEntry(JText::_('Documentation'), 'index.php?option=com_customflash&controller=docs', true);
		break;
	default:
	
		JSubMenuHelper::addEntry(JText::_('Flash Movies'), 'index.php?option=com_customflash&controller=flashmovies', true);
		JSubMenuHelper::addEntry(JText::_('Documentation'), 'index.php?option=com_customflash&controller=docs', false);
		break;
}
require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );


$controllerName = 'CustomFlashController'.$controllerName;
$controller	= new $controllerName( );


// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
?>