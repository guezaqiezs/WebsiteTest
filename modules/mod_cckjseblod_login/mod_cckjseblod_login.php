<?php
/**
* @version		$Id: mod_login.php 10381 2008-06-01 03:35:53Z pasamio $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require Helper
require_once ( dirname(__FILE__).DS.'helper.php' );

$params->def( 'greeting', 1 );

$type	=	modCCKjSeblod_LoginHelper::getType();
$return	=	modCCKjSeblod_LoginHelper::getReturnURL( $params, $type );

$user	=&	JFactory::getUser();

$typeid			=	$params->get('typeid');
$templateid		=	$params->get('templateid');
$menuitemid		=	$params->get('menuitemid');
$registerlabel	=	$params->get('registerlabel');
$lightbox		=	0;

require( JModuleHelper::getLayoutPath( 'mod_cckjseblod_login' ) );