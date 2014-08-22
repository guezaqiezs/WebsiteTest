<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Site jSeblod CCK
 **/
jimport( 'joomla.filesystem.file' );

$lang	=&	JFactory::getLanguage();
$lang->load( 'com_cckjseblod_more' );

// Include CSS
JHTML::_( 'stylesheet', 'site.css', 'components/com_cckjseblod/assets/css/' );
JHTML::_( 'stylesheet', 'cck.css', 'components/com_cckjseblod/assets/css/' );

// Require Helpers
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'jseblod_helper.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'jseblod_define.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'jseblod_display.php' );
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_form.php' ); //TODO REFACT
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_search.php' ); //TODO REFACT
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_store.php' );

// Require Base Controller
require_once( JPATH_COMPONENT.DS.'controller.php' );

// Create Controller
$classname	=	'CCKjSeblodController';
$controller =	new $classname( );

// Perform Request task
$controller->execute( JRequest::getVar('task', null, 'default', 'cmd') );

// Redirect
$controller->redirect();
?>