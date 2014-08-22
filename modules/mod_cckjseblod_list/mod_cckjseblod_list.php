<?php
/**
* @version 			1.6.0
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			List - jSeblod CCK ( Content Construction Kit )
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require Helper
jimport( 'joomla.filesystem.file' );
jimport( 'joomla.error.profiler' );

require_once( dirname(__FILE__).DS.'helper.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'jseblod_display.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'jseblod_helper.php' );

JHTML::_( 'stylesheet', 'site.css', 'components/com_cckjseblod/assets/css/' );

// Get Parameters
$document	=&	JFactory::getDocument();
$user 		=&	JFactory::getUser();
$uID		=	( $user->id ) ? $user->id : 0;
$uGID		=	( $user->gid ) ? $user->gid : 0;
//
$mode		=	$params->get( 'mode', '' );
$searchid	=	$params->get( 'searchid', '' );
$templateid	=	$params->get( 'templateid', 0 );

$more_link	=	$params->get( 'more_link', '' );
$more_label	=	$params->get( 'more_label', '' );
$itemId		=	( $more_link ) ? $more_link : JRequest::getCmd( 'Itemid' );

$start		=	$params->get( 'title_start', 0 );
$end		=	$params->get( 'title_end', '' );

$config 	=&	CCK::CORE_getConfig();
$path		=	JPATH_THEMES;
if ( $config ) {
	$root	=	JURI::root( true );
	if ( ! defined('_PATH_ROOT') ) {
		define( "_PATH_ROOT", $root );
	}
	if ( ! defined('_JTEXT_ON_LABEL') ) {
		define( "_JTEXT_ON_LABEL", $config->jtext_on_label );
	}
}

// Search Type
if ( ! $searchid ) {
	$mainframe->enqueueMessage( JText::_( 'SEARCH MODULE SEARCH TYPE NOT FOUND' ), "error" );
	return true;
}
$searchType	=	modCCKjSeblod_ListHelper::getData( $searchid );
if ( ! $searchType ) {
	$mainframe->enqueueMessage( JText::_( 'SEARCH MODULE SEARCH TYPE NOT FOUND' ), "error" );
	return true;	
}
if ( ! $templateid ) {
	$templateid	=	$searchType->contenttemplate;
}

// Items
$items		=	modCCKjSeblod_ListHelper::getItemsSearch( $searchType->id, 'list', '', false, true );
$sort		=	modCCKjSeblod_ListHelper::getItemsSearchContent( $searchType->id, 'sort' );
$countItems	=	count( $items );
if ( ! $countItems ) {
	$mainframe->enqueueMessage( JText::_( 'SEARCH MODULE SEARCH TYPE EMPTY' ), "error" );
	return true;
}

// Live => Field=Value
if ( $params->get( 'list_live', '' ) ) {
	$tempList	=	explode( '<br />', strtr( $params->get( 'list_live', '' ), array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />') ) );
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

//--!!
if ( $items[0]->typename == 'search_action' ) {
	$method			=	$items[0]->bool;
	$searchAreas	=	$items[0]->location;
	$limit			=	$items[0]->size;
	$searchLimit	=	$items[0]->maxlength;
	$displayLimit	=	$params->get( 'limit', 0 );
	$searchIn		=	$items[0]->bool2;
	$searchLength	=	$items[0]->bool4;
	$searchMode		=	$items[0]->format;
	//
	//$message		=	$items[0]->message;
	//$style		=	$items[0]->style;
	$cache			=	$items[0]->bool5;
	$cacheGroups	=	$items[0]->extra;
	$cacheRender	=	$items[0]->bool8;
	$debug			=	$items[0]->bool6;
	//
	$sef			=	$items[0]->bool3;
}

//--- Search::begin
$error				=	0;
$total				=	0;
$active				=	array();
$active[0]			=	'cckjseblod';
$areas['active']	=	$active;
$ordering			=	$params->get( 'ordering', '' );
$ordering2			=	$params->get( 'ordering2', '' );
$stage				=	0;
$stages				=	array();
foreach( $items as $item ) {
	$itemValue	=	null;
	if ( $item->type == 11 ) {
		$item->name		=	$item->extended;
		$item->type2	=	CCK_DB_Result( 'SELECT s.type FROM #__jseblod_cck_items AS s WHERE s.name="'.$item->extended.'"' );
	}
	// Live
	//if ( $item->live == 'module' ) {
	if ( @$liveList[$item->name] ) {
		$itemValue	=	$liveList[$item->name];
	}
	//}
	//
	if ( $itemValue != '' ) {
		$item->value	=	$itemValue;
	}
	// Stage
	if ( @$item->stage != 0 ) {
		$stages[$item->stage]	=	'';
	}
}

// Temporary
$countStages	=	count( $stages );
if ( $countStages ) {
	for( $stage =  1; $stage <= $countStages; $stage++ ) {

		$list	=	modCCKjSeblod_ListHelper::getDataResult( $searchMode, $ordering, $areas, $searchLimit, $items, $sort, $searchIn, $searchLength, $itemId, $user, $cache, $stage, $stages, $debug, $cacheGroups );
		
		$stages[$stage]	=	implode( ' ', $list );
		if ( $stages[$stage] == '' ) {
			$error	=	1;
			break;
		}
	}
}
// Final
if ( ! $error ) {
	$stage	=	0;
	$list	=	modCCKjSeblod_ListHelper::getDataResult( $searchMode, $ordering, $areas, $searchLimit, $items, $sort, $searchIn, $searchLength, $itemId, $user, $cache, $stage, $stages, $debug, $cacheGroups );
}
$total	=	count( $list );
// Limit
if( $displayLimit > 0 ) {
	if ( $ordering2 == 'random' || $ordering2 == 'random_shuffle' ) {
		// Random
		$rand_keys	=	array_rand( $list, $displayLimit );
		if ( ! is_array( $rand_keys ) ) { 
			$rand_keys	=	array( $rand_keys );
		}	
		$rand_list = array();
		foreach( $rand_keys as $key ) { 
			array_push( $rand_list, $list[$key] );
		}
		$list	=	array();
		$list	=	array_merge( $list, $rand_list );
	} else {
		// Cut
		$list	=	array_splice( $list, 0, $displayLimit );
	}
}
// Suffle
if ( $ordering2 == 'shuffle' || $ordering2 == 'random_shuffle' ) {
	shuffle( $list );
}
//--- Search::end

// Render (Template)
$client	=	'list';
$dataL	=	null;
// Debug
if ( $debug ) {
	$profiler	=	new JProfiler();
}
if ( $total ) {
	//
	if ( $sef == -1 ) {
		require_once ( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' );
		$sef_option	=	'';
	} else {
		require_once ( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'route.php' );
		if ( CCKjSeblodHelperRoute::isSEF() ) {
			$sef_option		=	'';
		} else {
			$sef_option		=	'option=com_content';
			$sef			    =	0;
		}
	}
	// Render
	if ( ! $mode ) {
		$rows		=	null;

		if ( $searchType->content >= 2 ) {
			// List Template
			if ( $cacheRender ) {
				$cache	=&	JFactory::getCache();
				$cache->setCaching( 1 );
				$cache->_options['cachebase']	=	JPATH_CACHE.DS.'cck-cache-render'; //Method!
				$dataL	=	$cache->call( array( 'modCCKjSeblod_ListHelper', 'render' ), $list, $searchType, $path, $client, $itemId, $sef, $sef_option, $uID, $uGID, $templateid, $more_link );
			} else {
				$dataL	=	modCCKjSeblod_ListHelper::render( $list, $searchType, $path, $client, $itemId, $sef, $sef_option, $uID, $uGID, $templateid, $more_link );
			}
			//
		} else {
			// Content Template
			$dispatcher	=&	JDispatcher::getInstance();
			JPluginHelper::importPlugin( 'content' );
											
			for ( $i = 0; $i < $total; $i++ )
			{
				$listItem		=&	$list[$i];
				if ( $sef == -1 ) {
					
					@$listItem->href	=	ContentHelperRoute::getArticleRoute( $listItem->slug, $listItem->catslug, $listItem->sectionid );
				} else {
					$listItem->href	=	CCKjSeblodHelperRoute::getArticleRoute( $listItem->slug, $listItem->catslug, $sef, $sef_option, $itemId );
				}
				
				$rows[$i]				=	$listItem;
				@$rows[$i]->text		=	( $searchType->content ) ? $listItem->introtext : $listItem->introtext.$listItem->fulltext;
				$rows[$i]->parameters	=	new JParameter( @$rows[$i]->attribs );
				$rows[$i]->event		=	new stdClass ();
				$results	=	$dispatcher->trigger( 'onPrepareContent', array ( &$rows[$i], & $rows[$i]->parameters, 0 ) );
				$results	=	$dispatcher->trigger( 'onAfterDisplayTitle', array ( $rows[$i], & $rows[$i]->parameters, 0 ) );
				$rows[$i]->event->afterDisplayTitle = trim( implode( "\n", $results ) );
				$results	=	$dispatcher->trigger( 'onBeforeDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, 0 ) );
				$rows[$i]->event->beforeDisplayContent = trim( implode( "\n", $results ) );
				$results	=	$dispatcher->trigger( 'onAfterDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, 0 ) );
				$rows[$i]->event->afterDisplayContent = trim( implode( "\n", $results ) );
				$listItem->text =	$rows[$i]->text;
				
				@$listItem->created	=	( $listItem->created ) ? JHTML::Date( $listItem->created ) : '';
				$listItem->count		=	$i + 1;
			}
			//
		}
	}
}

// Debug
if ( $debug ) {
	echo $profiler->mark( JText::_( 'RENDER CACHING STATE'.$cacheRender ) ) . '<br />';
}

require( JModuleHelper::getLayoutPath( 'mod_cckjseblod_list' ) );

?>