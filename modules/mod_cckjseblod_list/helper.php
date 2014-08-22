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

class modCCKjSeblod_ListHelper {
	
	/**
	 * Get Data
	 **/
	function &getData( $searchTypeId )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $searchTypeId ) {
			$query	= 'SELECT s.*'
					. ' FROM #__jseblod_cck_searchs AS s'
					. ' WHERE s.id = '.$searchTypeId.' AND s.published = 1'
					;
			$db->setQuery( $query );
			$data	=	$db->loadObject();
		}
		
		return $data;
	}
	
	function getDataResult( $searchMode, $ordering, $areas, $searchLimit, $items, $sort, $searchIn, $searchLength, $itemId, $user, $cacheSearch, $stage, $stages, $debug, $cacheGroups )
	{
		JPluginHelper::importPlugin( 'search', 'cckjseblod' );
		$dispatcher	=&	JDispatcher::getInstance();
		
		if ( $debug ) {
			$profiler	=	new JProfiler();
		}
		if ( $cacheSearch ) {
			// Cache [ON]
			$cache	=&	JFactory::getCache();
			$cache->setCaching( 1 );
			$cache->_options['cachebase']	=	JPATH_CACHE.DS.'cck-cache-search'; //Method!
			$user		=	( $cacheSearch == 2 && $user->id > 0 && strpos( ','.$cacheGroups.',', ','.$user->gid.',' ) !== false ) ? $user : null;
			$results	=	$cache->call( array( $dispatcher, 'trigger' ), 'onSearch', array( '', $searchMode, $ordering, $areas['active'], $searchLimit, $items, $sort,
																							  $searchIn, $searchLength, $itemId, $user, $cacheSearch, $stage, $stages, 0 ) );
		} else {
			// Cache [OFF]
			$results	=	$dispatcher->trigger( 'onSearch', array( '', $searchMode, $ordering, $areas['active'], $searchLimit, $items, $sort,
																	 $searchIn, $searchLength, $itemId, $user, $cacheSearch, $stage, $stages, 0 ));
		}
		$list	=	array();
		foreach( $results AS $result ) {
			$list	=	array_merge( (array)$list, (array)$result );
		}
		if ( $debug ) {
			echo $profiler->mark( JText::_( 'SEARCH CACHING STATE'.$cacheSearch ) );
			echo ' ~ <b>' . count( $list ) . '</b>';
			echo ( $stage == 0 ) ? '<br />' : '<br />';
		}
		
		return $list;
	}

	function render( $list, $searchType, $path, $client, $itemId, $sef, $sef_option, $uID, $uGID, $templateid, $more_link )
	{	
		$dataL		=	null;
		$item		=	null;
		$dispatcher	=&	JDispatcher::getInstance();
		require_once ( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' );
		
		$contentTemplate	=	modCCKjSeblod_ListHelper::getTemplate( $templateid );
		$auto				=	$contentTemplate->mode;
		$myList				=	1;
		require( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonbefore_list.php' );
		
		$rows[$i]->id			=	0;
		$rows[$i]->text			=	$dataL;
		$rows[$i]->parameters	=	new JParameter( @$rows[$i]->attribs );
		$rows[$i]->event		=	new stdClass ();
		$results	=	$dispatcher->trigger( 'onPrepareContent', array ( &$rows[$i], & $rows[$i]->parameters, 0 ) );
		$results	=	$dispatcher->trigger( 'onAfterDisplayTitle', array ( $rows[$i], & $rows[$i]->parameters, 0 ) );
		$rows[$i]->event->afterDisplayTitle = trim( implode( "\n", $results ) );
		$results	=	$dispatcher->trigger( 'onBeforeDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, 0 ) );
		$rows[$i]->event->beforeDisplayContent = trim( implode( "\n", $results ) );
		$results	=	$dispatcher->trigger( 'onAfterDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, 0 ) );
		$rows[$i]->event->afterDisplayContent = trim( implode( "\n", $results ) );
		$dataL =	$rows[$i]->text;
		
		if ( $more_link ) {
			$docR->more_link	=	$more_link;
		}
		//$listItem->created	=	( $listItem->created ) ? JHTML::Date( $listItem->created ) : '';
		//$listItem->count		=	$i + 1;
		foreach( $docR as $key => $value ) {
			$docR->key	=	null;
			$docR->value =	null;
		}
		if ( JFile::exists( $fileToRender ) ) {
			JFile::delete( $fileToRender );
		}
		
		return $dataL;
	}
	
	/**
	 * Get Items
	 **/
	function getItemsSearch( $searchId, $client, $exclusion, $prename, $cck = false )
	{
		$db	=&	JFactory::getDBO();		
		
		if ( $client == 'all' )  {
			$where 	=	' WHERE cc.searchid = '.$searchId;
		} else {
			$where 	=	' WHERE cc.client = "'.$client.'" AND cc.searchid = '.$searchId;
		}
		$form 	=	true;
		if ( ! $form ) {
			$where	.=	' AND s.type != 25';
		}
		if ( $exclusion != '' ) {
			$where .= ' AND s.id NOT IN ('.$exclusion.')';
		}
		$orderby	=	' ORDER BY cc.ordering ASC';
		
		$query	= ' SELECT DISTINCT s.*, sc.name AS typename, cc.client, cc.searchmatch, cc.value, cc.helper, cc.helper2, cc.target, cc.groupname, cc.live, cc.stage'
				. ' FROM #__jseblod_cck_items AS s '
				. ' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
				. ' LEFT JOIN #__jseblod_cck_search_item AS cc ON cc.itemid = s.id'
				. $where
				. $orderby
				;
		
		$db->setQuery( $query );
		$items	=	( $prename ) ? $db->loadObjectList( 'name' ) : $db->loadObjectList();
		
		if ( ! sizeof( $items ) ) {
			$items = array();
			return $items;
		}
		
		return $items;
	}
	
	/**
	 * Get Items Content
	 **/
	function getItemsSearchContent( $searchId, $client )
	{
		$db	=&	JFactory::getDBO();		
		
		$items 		=	array();
		$where 		=	' WHERE cc.client = "'.$client.'" AND cc.searchid = '.$searchId;
		$orderby	=	' ORDER BY cc.ordering ASC';
		
		$query	= ' SELECT DISTINCT s.*, sc.name AS typename, cc.client, cc.contentdisplay, cc.width, cc.helper, cc.target, cc.groupname, cc.stage'
				. ' FROM #__jseblod_cck_items AS s '
				. ' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
				. ' LEFT JOIN #__jseblod_cck_search_item_content AS cc ON cc.itemid = s.id'
				. $where
				. $orderby
				;
		
		$db->setQuery( $query );
		$items	=	$db->loadObjectList();
				
		return $items;
	}
	
	/**
	 * Get Title
	 **/
	function getTitle( $title, $start, $end )
	{	
		// TODO: Fix when Both + Negative End
		if ( $start != '' || $end ) {
			// Start
			if ( ! is_numeric( $start ) ) {
				$start	=	strpos( $title, $start ) + 1;
			}
			$bot	=	$start;
			
			// End (Length)
			$eot	=	null;
			if ( $end != '' ) {
				if ( ! is_numeric( $end ) ) {
					$end	=	strpos( $title, $end ) - 1 - $start;
				}
				$eot	=	$end;
			}
			
			$title	=	( $eot ) ? substr( $title, $bot, $eot ) : substr( $title, $bot );
		}
		
		return trim( $title );
	}
	
	/**
	 * Get Template
	 **/
	function &getTemplate( $contentTemplateId )
	{
		$db	=&	JFactory::getDBO();
				
		if ( $contentTemplateId ) {
			$query	= 'SELECT s.id, s.name, s.mode'
					. ' FROM #__jseblod_cck_templates AS s'
					. ' WHERE s.id = '.$contentTemplateId.' AND s.published = 1'
					;
			$db->setQuery( $query );
			$template	=	$db->loadObject();
		}
	
		return $template;
	}	
}