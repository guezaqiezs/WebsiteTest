<?php
/**
* @version 			1.6.0
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			Site Forms - jSeblod CCK ( Content Construction Kit )
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require Helper
jimport( 'joomla.filesystem.file' );

require_once( dirname(__FILE__).DS.'helper.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'jseblod_display.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'jseblod_helper.php' );
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_form.php' );
JHTML::_( 'stylesheet', 'site.css', 'components/com_cckjseblod/assets/css/' );

// Get Parameters
$user 		=&	JFactory::getUser();
$document	=&	JFactory::getDocument();
$itemId		=	JRequest::getCmd( 'Itemid' );
$typeid		=	$params->get( 'typeid', '' );
$templateid	=	$params->get( 'templateid', '' );
$style		=	$params->get( 'style', 'default' );

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

if ( ! $typeid ) {
	$error	=	1;
}

$contentType	=	modCCKjSeblod_SiteFormsHelper::getData( $typeid );
if ( ! $contentType ) {
	$mainframe->enqueueMessage( JText::_( 'SITE FORMS MODULE CONTENT TYPE NOT FOUND' ), "error" );
	return true;
}

$contentTemplate	=	modCCKjSeblod_SiteFormsHelper::getTemplate( $templateid );
if ( ! $contentTemplate ) {
	$contentTemplate	=	CCKjSeblodItem_Form::getTemplate( $contentType->sitetemplate, 1 );
}
if ( ! $contentTemplate ) {
	$mainframe->enqueueMessage( JText::_( 'SITE FORMS MODULE CONTENT TEMPLATE NOT FOUND' ), "error" );
	return true;
}
$template	=	$contentTemplate->name;
$templateid	=	$contentTemplate->id;
$path		=	JPATH_THEMES;
$auto		=	$contentTemplate->mode;

if ( ! defined( '_ERROR_REFRESH_ITEMID' ) ) {
	define( '_ERROR_REFRESH_ITEMID', $itemId );
}

$client		=	'site';
$items		=	CCKjSeblodItem_Form::getItems( $contentType->id, $client, '', false, true );
$countItems	=	count( $items );

if ( ! $countItems ) {
	$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE EMPTY' ), "error" );
	return true;
}

if ( $items[0]->typename == 'form_action' ) {
	$actionMode	=	$items[0]->bool2;
	$access		=	$items[0]->display;
	$catLocate	=	$items[0]->location;
	$maxC		=	$items[0]->maxlength;
	$maxCU		=	$items[0]->size;
	if ( $access == -1 ) {
		$default_author	=	( @$items[0]->content ) ? $items[0]->content : 0;
		if ( $user->id != $default_author ) {
			$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE NOTAUTH' ), "error" );
			return true;
		}				
	} else if ( $access == 17 ) {
	} else {
		if ( $user->gid < $access ) {
			$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE NOTAUTH' ), "error" );
			return true;
		}
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
$captchaEnable	=	null;
$artId		=	null;
$ran		=	null;
$ran		=	rand( 1, 100000 );
//
$inheritId			=	JRequest::getInt( 'id' );
if ( $params->get( 'importer_hidden', '' ) ) {
	$importer_hidden	=	explode( '<br />', strtr( $params->get( 'importer_hidden', '' ), array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />') ) );
}
if ( $params->get( 'importer_text', '' ) ) {
	$importer_text	 	=	explode( '<br />', strtr( $params->get( 'importer_text', '' ), array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />') ) );
}
$importedItems		=	array();
$importedFrom		=	array();
if ( count( @$importer_hidden ) ) {
	foreach( $importer_hidden as $importer ) {
		if ( $importer  ) {
			$tab	=	explode( '=', $importer );
			$importedItems[$tab[0]]				=	$tab[1];
			$importedItems[$tab[0].'_sabatype']	=	'hidden';
			$importedFrom[]						=	$tab[1];
		}
	}
}
if ( count( @$importer_text ) ) {
	foreach( $importer_text as $importer ) {
		if ( $importer  ) {
			$tab	=	explode( '=', $importer );
			$importedItems[$tab[0]]				=	$tab[1];
			$importedItems[$tab[0].'_sabatype']	=	'text';
			$importedFrom[]						=	$tab[1];
		}
	}
}
$importedValues	=	CCK_GET_Value( $inheritId, $importedFrom, true );
//
if ( sizeof( $items ) ) {
	foreach ( $items as $item ) {
		$itemName	=	$item->name;
		$itemValue	=	null;
		
		// --- Inherit Value from Article or else..
		if ( @$importedItems[$itemName] ) {
			if ( @$importedItems[$itemName.'_sabatype'] == 'hidden' ) {
				$itemValue 			=	@$importedValues[@$importedItems[$itemName]];
				$item->typename		=	'hidden';
				$item->defaultvalue	=	'';
				$item->displayfield	=	-1;				
			} else {
				$itemValue 			=	@$importedValues[@$importedItems[$itemName]];
				$item->typename		=	'text';
				$item->gEACL		=	-3;
			}
		}
		// ---
		// Live
		if ( $item->live == 'url' ) {
			$itemValue	=	JRequest::getString( $item->prevalue, '', 'GET' );
		} else if ( $item->live == 'url_int' ) {
			$itemValue	=	JRequest::getInt( $item->prevalue, '', 'GET' );
		}
		// Display ?
		$doc2->$itemName	= CCKjSeblodItem_Form::getData( $item, $itemValue, $client, $artId, null, $actionMode, $content2, $rowU2, 0, 0, $importedValues, null, $ran );
		
		if ( $item->typename == 'form_action' ) {
			if ( $auto != 1 ) {
				$buffer	=	JFile::read( JPATH_THEMES.DS.$template.DS.$file.'.php' );
				if ( JString::strpos( $buffer, $item->name.'->form' ) === false ) {
					$mainframe->enqueueMessage( "ERROR FORM NOT FOUND", "error" );
					return true;
				}
			}
			$formName	=	$item->name;
			$userId		=	( $user->id ) ? $user->id : $doc2->$itemName->content;
			if ( $actionMode == 1 ) {
				$formHidden = 	'<input type="hidden" id="jcontentformpublished" name="jcontentform[published]" value="'.$doc2->$itemName->bool.'" />'
							.	'<input type="hidden" id="jcontentformaccess" name="jcontentform[access]" value="'.$doc2->$itemName->bool5.'" />'
							.	'<input type="hidden" id="jcontentformparent_id" name="jcontentform[parent_id]" value="'.$doc2->$itemName->location.'" />'
							.	'<input type="hidden" id="jcontentformcreated_user_id" name="jcontentform[created_user_id]" value="'.$userId.'" />';				
			} else {
				$formHidden = 	'<input type="hidden" id="jcontentformstate" name="jcontentform[state]" value="'.$doc2->$itemName->bool.'" />'
							.	'<input type="hidden" id="jcontentformaccess" name="jcontentform[access]" value="'.$doc2->$itemName->bool5.'" />'
							.	'<input type="hidden" id="jcontentformcatid" name="jcontentform[catid]" value="'.$doc2->$itemName->location.'" />'
							.	'<input type="hidden" id="jcontentformcreated_by" name="jcontentform[created_by]" value="'.$userId.'" />'
							.	'<input type="hidden" id="jcontentformusertype" name="jcontentform[usertype]" value="'.$doc2->$itemName->format.'" />'
							.	'<input type="hidden" id="jcontentformuseractivation" name="jcontentform[useractivation]" value="'.$doc2->$itemName->bool4.'" />';
			}
			if ( $auto == 1 ) {
				$cckForm	=	$itemName;
			}
		} else {
			if ( $item->typename == 'captcha_image' && ( $item->gEACL == -1 || ( $item->gEACL == 1 && $client == 'site' ) || ( $item->gEACL == 0 ) ) ) {
				$captchaEnable	=	1;
			}
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

$doc2->menu->title	=	$contentType->title;
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

require( JModuleHelper::getLayoutPath( 'mod_cckjseblod_siteforms' ) );