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

jimport( 'joomla.filesystem.file' );

if ( JFile::exists( JPATH_COMPONENT.DS.'_VERSION.php' ) ) {
	$version	=	JFile::read( JPATH_COMPONENT.DS.'_VERSION.php' );
}
define( '_VERSION',			( @$version ) ? $version : '1.6.0' );

// Check New Version
if ( @$version ) {
	//$mainframe->enqueueMessage( JText::_( 'A NEW VERSION IS AVAILABLE' ), "notice" );
}

/**
 * Admin jSeblod CCK
 **/

// Include CSS
JHTML::_( 'stylesheet', 'administrator.css', 'administrator/components/com_cckjseblod/assets/css/' );
JHTML::_( 'stylesheet', 'icon.css', 'administrator/components/com_cckjseblod/assets/css/' );

// Include Tooltip && Mootips && Mootools
JHTML::_( 'script', 'mootips.js', 'media/jseblod/mootips/', true );
JHTML::_( 'stylesheet', 'mootips.css', 'media/jseblod/mootips/' );
JHTML::_( 'behavior.tooltip' );

// Include Tables
JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'tables' );

// Require Helpers
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'jseblod_helper.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'jseblod_define.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'jseblod_display.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'cckitem_content.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'cckitem_form.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'cckitem_store.php' );

// Require Base Controller
require_once( JPATH_COMPONENT.DS.'controller.php' );

// Require Specific Controller
if ( $controller = JRequest::getVar( 'controller' ) ) {
	require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php' );
}

if ( $controller != 'interface' && $controller != 'configuration' ) {
	JSubMenuHelper::addEntry( _IMG_TEMPLATES . '&nbsp;&nbsp;&nbsp;' . JText::_( 'TEMPLATES' ), _LINK_CCKJSEBLOD_TEMPLATES, ( $controller == 'templates' || $controller == 'templates_categories' || $controller == 'templates_views' ) ? true : false );
	JSubMenuHelper::addEntry( _IMG_TYPES . '&nbsp;&nbsp;&nbsp;' . JText::_( 'CONTENT TYPES' ), _LINK_CCKJSEBLOD_TYPES, ( $controller == 'types' || $controller == 'types_categories' ) ? true : false );
	JSubMenuHelper::addEntry( _IMG_ITEMS . '&nbsp;&nbsp;&nbsp;' . JText::_( 'ITEMS' ), _LINK_CCKJSEBLOD_ITEMS, ( $controller == 'items' || $controller == 'items_categories' ) ? true : false );
	JSubMenuHelper::addEntry( _IMG_SEARCHS . '&nbsp;&nbsp;&nbsp;' . JText::_( 'SEARCH TYPES' ), _LINK_CCKJSEBLOD_SEARCHS, ( $controller == 'searchs' || $controller == 'searchs_categories' ) ? true : false );
	JSubMenuHelper::addEntry( _IMG_PACKS . '&nbsp;&nbsp;&nbsp;' . JText::_( 'PACK' ), _LINK_CCKJSEBLOD_PACKS, ( $controller == 'packs' ) ? true : false );
	JSubMenuHelper::addEntry( _IMG_CONFIG . '&nbsp;&nbsp;&nbsp;' . JText::_( 'CONFIG' ) , _LINK_CCKJSEBLOD_CONFIGURATION, ( $controller == 'configuration' ) ? true : false );
}

// Create Controller
$classname	=	'CCKjSeblodController'.$controller;
$controller	=	new $classname( );

// Perform Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect
$controller->redirect();
?>