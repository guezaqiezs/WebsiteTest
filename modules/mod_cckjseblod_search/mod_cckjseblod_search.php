<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			Search - jSeblod CCK ( Content Construction Kit )
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require Helper
jimport( 'joomla.filesystem.file' );

require_once( dirname(__FILE__).DS.'helper.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'jseblod_display.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'jseblod_helper.php' );
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_form.php' ); //REFACT TODO
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_search.php' ); //REFACT TODO
JHTML::_( 'stylesheet', 'site.css', 'components/com_cckjseblod/assets/css/' );

// Get Parameters
$user 		=&	JFactory::getUser();
$document	=&	JFactory::getDocument();
$searchid	=	$params->get( 'searchid', '' );
$templateid	=	$params->get( 'templateid', '' );
$style		=	$params->get( 'style', 'default' );

$menu_search 	=	$params->get( 'menu_search', '' );
$itemId			=	( $menu_search ) ? $menu_search : JRequest::getCmd( 'Itemid' );

// Live => Field=Value
$menu_params	=	&$mainframe->getParams();
if ( $menu_params->get( 'list_live', '' ) ) {
	$tempList	=	explode( '<br />', strtr( $menu_params->get( 'list_live', '' ), array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />') ) );
	if ( sizeof( $tempList ) ) {
		foreach ( $tempList as $key => $val ) {
			$tab				=	explode( '=', $val );
			if ( isset( $tab[1] ) ) {
				$liveList[$tab[0]]	=	$tab[1];
			}
		}
	}
}
//

$contentb	=	new stdClass();
$rowUb		=	new stdClass();

$config 	=&	CCK::CORE_getConfig();
if ( $config ) {
	$root	=	JURI::root( true );
	if ( ! defined('_PATH_ROOT') ) {
		define( "_PATH_ROOT", $root );
	}
	if ( ! defined('_PATH_CALENDAR') ) {
		define( "_PATH_CALENDAR", '/media/jseblod/calendar/' );
	}
	if ( ! defined('_PATH_FORMVALIDATOR') ) {
		define( "_PATH_FORMVALIDATOR", '/media/jseblod/formvalidator/' );
	}
	if ( ! defined('_PATH_MOOTIPS') ) {
		define( "_PATH_MOOTIPS", '/media/jseblod/mootips/' );
	}
	if ( ! defined('_MODAL_WIDTH') ) {
		define( "_MODAL_WIDTH",	$config->modal_width );
	}
	if ( ! defined('_MODAL_HEIGHT') ) {
		define( "_MODAL_HEIGHT", $config->modal_height );
	}
	if ( ! defined('_OPENING') ) {
		define( "_OPENING",	$config->opening );
	}
	if ( ! defined('_CLOSING') ) {
		define( "_CLOSING",	$config->closing );	
	}
	if ( ! defined('_JTEXT_ON_LABEL') ) {
		define( "_JTEXT_ON_LABEL", $config->jtext_on_label );
	}
	if ( ! defined('_SITEFORM_ONCLICK') ) {
		define( "_SITEFORM_ONCLICK", $config->siteform_tips );
	}
	if ( ! defined('_WYSIWYG_EDITOR') ) {
		define( "_WYSIWYG_EDITOR", $config->wysiwyg_editor );
	}
}

if ( ! $searchid ) {
	$error	=	1;
}

$searchType	=	modCCKjSeblod_SearchHelper::getData( $searchid );
if ( ! $searchType ) {
	$mainframe->enqueueMessage( JText::_( 'SEARCH MODULE SEARCH TYPE NOT FOUND' ), "error" );
	return true;
}

$contentTemplate	=	modCCKjSeblod_SearchHelper::getTemplate( $templateid );
if ( ! $contentTemplate ) {
	$contentTemplate	=	CCKjSeblodItem_Form::getTemplate( $searchType->searchtemplate, 1 );
}
if ( ! $contentTemplate ) {
	$mainframe->enqueueMessage( JText::_( 'SEARCH MODULE SEARCH TEMPLATE NOT FOUND' ), "error" );
	return true;
}
$template	=	$contentTemplate->name;
$templateid	=	$contentTemplate->id;
$path		=	JPATH_THEMES;
$auto		=	$contentTemplate->mode;

if ( ! defined( '_ERROR_REFRESH_ITEMID' ) ) {
	define( '_ERROR_REFRESH_ITEMID', $itemId );
}

$client		=	'search';
$items		=	CCKjSeblodItem_Search::getItemsSearch( $searchType->id, $client, '', false, true );
$countItems	=	count( $items );

if ( ! $countItems ) {
	$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE EMPTY' ), "error" );
	return true;
}

if ( $items[0]->typename == 'search_action' ) {
	$actionMode		=	0; // TODOO ERASE ACTION MODE DANS CCKITEM_SEARCH.PHP
	$method			=	$items[0]->bool;
	$searchAreas	=	$items[0]->location;
	//$content		=	$items[0]->bool2;
	$limit			=	$items[0]->size;
	$searchLimit	=	$items[0]->maxlength;
	$searchMode		=	$items[0]->format;
}

$task		=	JRequest::getVar( 'task' );
if ( $task == 'search' ) {
	if ( $method ) {
		$post	=	JRequest::get( 'post' );
	} else {
		$post	=	JRequest::get( 'get' );
	}
}

// Initialize Parameters
$random		=	rand( 1, 100000 );
$cache 		=	false;
$file 		=	'index_jseblod'.$random;

// Create File to Render from index.php
$fileToCopy 	=	$path.DS.$template.DS.'index.php';
$fileToRender	=	$path.DS.$template.DS.$file.'.php';
if ( JFile::exists( $fileToCopy ) ) {
	JFile::copy( $fileToCopy, $fileToRender );
}

$rparams	=	array(
	'template' 	=> $template,
	'file'		=> $file.'.php',
	'directory'	=> $path,
);
	
// Create New HTML Document
$doc2	=&	JDocument::getInstance( 'html' );

$cckForm	=	null;
$cckItems	=	null;
$artId		=	null;
$ran		=	null;
$ran		=	rand( 1, 100000 );
if ( sizeof( $items ) ) {
	foreach ( $items as $item ) {
		$itemName			=	$item->name;
		$itemValue			=	null;
		if ( $task == 'search' ) {
			// Live
			//if ( $item->live == 'module' ) {
				$itemValue	=	@$liveList[$itemName];
			//}
			if ( $item->type == 11 ) {
				$itemName	=	$item->extended;
			}
			if ( isset( $post[$itemName] ) ) {
				$itemValue	=	@$post[$itemName];
			}
		}
		// - Security XSS
		if ( ! is_array( $itemValue ) ) {
			$itemValue	=	htmlspecialchars( $itemValue, ENT_QUOTES );
		}
		// -
		$doc2->$itemName	= CCKjSeblodItem_Search::getData( $item, $itemValue, $client, $artId, null, $actionMode, $content2, $rowU2, 0, 0, $ran );
		
		if ( $item->typename == 'search_action' ) {
			if ( $auto != 1 ) {
				$buffer	=	JFile::read( JPATH_THEMES.DS.$template.DS.$file.'.php' );
				if ( JString::strpos( $buffer, $item->name.'->form' ) === false ) {
					$mainframe->enqueueMessage( "ERROR FORM NOT FOUND", "error" );
					return true;
				}
			}
			$formName	=	$item->name;
			$userId		=	( $user->id ) ? $user->id : $doc2->$itemName->content;
			$formHidden = 	'';
			if ( $auto == 1 ) {
				$cckForm	=	$itemName;
			}
		} else {
			if ( $auto == 1 ) {
				$cckItems[]	=	$itemName;
			}
		}
	}
}


if ( ! @$formName ) {
	$mainframe->enqueueMessage( "ERROR FORM NOT FOUND", "error" );
	return true;
}
if ( $auto == 1 ) {
	$doc2->cckform	=	$cckForm;
	$doc2->cckitems	=	$cckItems;
}

$doc2->menu->title	=	$searchType->title;
$doc2->template = $contentTemplate->name;

$data	=	$doc2->render( $cache, $rparams );

// Flush Items && Values
foreach( $doc2 as $key => $value ) {
	$doc2->key	=	null;
	$doc2->value	=	null;
}

// Delete File To Render
if ( JFile::exists( $fileToRender ) ) {
	JFile::delete( $fileToRender );
}

require( JModuleHelper::getLayoutPath( 'mod_cckjseblod_search' ) );