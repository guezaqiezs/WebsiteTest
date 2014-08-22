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

$root	=	JURI::root( true );
define( "_PATH_ROOT",			$root );
define( "_PATH_CALENDAR",		'/media/jseblod/calendar/' );
define( "_PATH_FORMVALIDATOR",	'/media/jseblod/formvalidator/' );
define( "_PATH_MOOTIPS",		'/media/jseblod/mootips/' );

/**
 * NBSP
 **/

define( '_NBSP', str_repeat( '&nbsp;', 3 ) );
define( '_NBSP2', str_repeat( '&nbsp;', 5 ) );

/**
 * CCK Config
 **/

$config 	=&	CCK::CORE_getConfig();
if ( $config ) {
	define( "_MODAL_WIDTH",			$config->modal_width );
	define( "_MODAL_HEIGHT",		$config->modal_height );
	define( "_OPENING",				$config->opening );
	define( "_CLOSING",				$config->closing );	
	define( "_JTEXT_ON_LABEL", 		$config->jtext_on_label );	
	define( "_SITEFORM_ONCLICK",	$config->siteform_tips );
	define( "_WYSIWYG_EDITOR",		$config->wysiwyg_editor );
	define( "_DEFAULT_SECTION",		$config->jseblod_section );
	define( "_VALIDATION_ALERT",	$config->validation_alert );
	define( "_ARTICLE_TYPEID",		$config->article_typeid );
	define( "_ARTICLE_TEMPLATEID",	$config->article_templateid );
	define( "_ARTICLE_ITEMID",		$config->article_itemid );
	define( "_CATEGORY_TYPEID",		$config->category_typeid );
	define( "_CATEGORY_TEMPLATEID",	$config->category_templateid );
	define( "_CATEGORY_ITEMID",		$config->category_itemid );
	define( "_USER_TYPEID",			$config->login_typeid );
	define( "_USER_TEMPLATEID",		$config->login_templateid );
	define( "_USER_ITEMID",			$config->login_itemid );
	define( "_USER_OWN_TYPEID",		$config->user_typeid );
	define( "_USER_OWN_TEMPLATEID",	$config->user_templateid );
	define( "_USER_OWN_ITEMID",		$config->user_itemid );
}

?>