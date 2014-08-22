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

jimport( 'joomla.application.component.model' );

/**
 * Content Items	Model Class
 **/
class CCKjSeblodModelItems extends JModel
{
	/**
	 * Vars
	 **/
	var $_data 			= null;
	var $_total 		= null;
	var $_pagination 	= null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
        parent::__construct();
		
        global $mainframe, $option;
		$controller = JRequest::getWord('controller');
		$task		=	JRequest::getVar( 'layout' );
		
        // Get Pagination Request Variables
        $limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg( 'list_limit' ), 'int' );
        $limitstart = $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.limitstart', 'limitstart', 0, 'int' );
		//$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
        // In case Limit has been Changed, Adjust it
        $limitstart = ( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );
		
        $this->setState( 'limit', $limit );
        $this->setState( 'limitstart', $limitstart );
	}
	
	/**
	 * Get Data from Database
	 **/
	function getData()
	{
		if ( empty( $this->_data ) )
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->_pagination->limitstart, $this->_pagination->limit );
			
			$query = 'SELECT s.itemid, s.client'
				   . ' FROM #__jseblod_cck_type_item AS s'
				   . ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
				   . ' GROUP BY s.itemid';
			$this->_db->setQuery( $query );
			$clients = $this->_db->loadObjectList( 'itemid' );
			
			if ( sizeof( $this->_data ) ) {
				foreach ( $this->_data as $item ) {
					$item->client		=	@$clients[$item->id]->client ? 1 : 0;
					if ( $item->extended && $item->elemxtd ) {
						if ( $item->typetitle == 'JOOMLA CONTENT' || $item->typetitle == 'SELECT DYNAMIC' || $item->typetitle == 'ALIAS' || $item->typetitle == 'ALIAS CUSTOM' || $item->typetitle == 'ECOMMERCE CART'
							|| $item->typetitle == 'FIELDX ARRAY' || $item->typetitle == 'JOOMLA USER' ) {
							$extendedInfos			=	$this->_getExtendedInfos( $item->extended, 'items' );
							$item->extendedId		=	$extendedInfos->id;
							$item->extendedTitle	=	$extendedInfos->title;
						} else if ( $item->typetitle == 'GROUP CONTENT TYPE' ) {
							$extendedInfos			=	$this->_getExtendedInfos( $item->extended, 'types' );
							if ( $extendedInfos ) {
								$item->extendedId		=	$extendedInfos->id;
								$item->extendedTitle	=	$extendedInfos->title;
							} else {
								$item->extendedId		=	null;
								$item->extendedTitle	=	null;
							}
						} else {}
					}
				}
			}
		}

		return $this->_data;
	}
	
	/**
	 * Get Total
	 **/
	function getTotal()
	{
        if ( empty( $this->_total ) ) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount( $query );
        }
		
        return $this->_total;
	}
	
	/**
	 * Get Pagination Object
	 **/
	function getPagination()
	{
       if ( empty( $this->_pagination ) ) {
            jimport( 'joomla.html.pagination' );
            $this->_pagination = new JPagination( $this->getTotal(), $this->getState( 'limitstart' ), $this->getState( 'limit' ) );
			// Rectify Buggy Pagination :: Begin
			if ( $this->_pagination->limitstart > $this->_pagination->total ) {
				$this->_pagination->limitstart = $this->_pagination->total;
				$page = ( floor( $this->_pagination->limitstart / $this->_pagination->limit ) < $this->_pagination->limitstart / $this->_pagination->limit ) ? floor( $this->_pagination->limitstart / $this->_pagination->limit ) + 1 : floor( $this->_pagination->limitstart / $this->_pagination->limit );
				$this->_pagination->limitstart = ( $this->_pagination->limit != 0 ? ( floor( $this->_pagination->limitstart / $this->_pagination->limit ) * $this->_pagination->limit ) : 0 );
				$tab = array();
				$tab['pages.current'] = $page;
				$this->_pagination->setProperties( $tab );
			}
			if ( $this->_pagination->limitstart && ( $this->_pagination->limitstart == $this->_pagination->total ) ) {
				$this->_pagination->limitstart = $this->_pagination->limitstart - $this->_pagination->limit;
			}
			// Rectify Buggy Pagination :: End
        }
		
        return $this->_pagination;
	}
	
	/**
	 * Return Database Query
	 **/
	function _buildQuery()
	{
		global $mainframe, $option;
		$controller	= JRequest::getWord( 'controller' );
		
		$task		=	JRequest::getVar( 'layout' );
		$contentTypeFilter = JRequest::getInt( 'contentfilter' );
		$searchTypeFilter = JRequest::getInt( 'searchfilter' );
		$clientFilter = JRequest::getWord( 'clientfilter' );
		
		$where = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();
		
		$filter_content_type	= ( $contentTypeFilter ) ? $contentTypeFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_content_type',	'filter_content_type',	0,	'int' );
		$filter_search_type	= ( $searchTypeFilter ) ? $searchTypeFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_search_type',	'filter_search_type',	0,	'int' );
		$filter_client			= ( $clientFilter ) 	? $clientFilter : 	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_client',	'filter_client',	'',	'word' );
		$selectOrdering			= ( $filter_content_type && $filter_client ) ? ', ccc.ordering' : '';
		$selectOrdering			= ( $filter_search_type ) ? ', tcc.ordering' : '';
		
		$query	= 'SELECT DISTINCT s.*, sc.title AS categorytitle, sc.color AS categorycolor, sc.introchar AS categoryintrochar, sc.colorchar AS categorycolorchar, cc.title AS typetitle, u.name AS editor'
				. $selectOrdering
				. ' FROM #__jseblod_cck_items AS s'
				. ' LEFT JOIN #__jseblod_cck_items_categories AS sc ON sc.id = s.category'
				. ' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
				. ' LEFT JOIN #__jseblod_cck_type_item AS ccc ON ccc.itemid = s.id'
				. ' LEFT JOIN #__jseblod_cck_types AS cccc ON cccc.id = ccc.typeid'
				. ' LEFT JOIN #__jseblod_cck_search_item AS tcc ON tcc.itemid = s.id'
				. ' LEFT JOIN #__jseblod_cck_searchs AS tccc ON tccc.id = tcc.searchid'
				. ' LEFT JOIN #__users AS u ON u.id = s.checked_out'
				. $where
				. $orderby
				;

		return $query;
	}
	
	/**
	 * Return Where into Query 
	 **/
	function _buildContentWhere()
	{
		global $mainframe, $option;
		$controller	= JRequest::getWord( 'controller' );
		$task		=	JRequest::getVar( 'layout' );
		$categoryFilter 	= JRequest::getInt( 'categoryfilter' );
		$contentTypeFilter = JRequest::getInt( 'contentfilter' );
		$searchTypeFilter	=	JRequest::getInt( 'searchfilter' );
		$clientFilter = JRequest::getWord( 'clientfilter' );
		$db	=& JFactory::getDBO();
		
		$filter_restricted		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_restricted', 'filter_restricted', _RESTRICTION_FIELD,	'int' );
		$filter_category		= ( $categoryFilter ) ? 0 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_category',	'filter_category',	0,		'int' );
		$filter_type			= ( $categoryFilter ) ? 0 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_type',		'filter_type',		0,		'int' );		
		$filter_title			=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_title',		'filter_title',		-1,			'int' );
		$filter_index			=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_index',		'filter_index',		-1,			'int' );
		$filter_content_type	= ( $categoryFilter ) ? 0 : ( ( $contentTypeFilter ) ? $contentTypeFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_content_type',	'filter_content_type',	0,	'int' ) );
		$filter_search_type		= ( $searchTypeFilter ) ? $searchTypeFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_search_type',	'filter_search_type',	0,	'int' );
		$filter_client			= ( $categoryFilter ) ? 0 : ( ( $clientFilter ) 	? $clientFilter : 	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_client',	'filter_client',	'',	'word' ) );
		$filter_search			= ( $categoryFilter ) ? 6 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_search',	'filter_search', 0, 'int' );
		$search					= ( $categoryFilter ) ? $categoryFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.search', 'search', '', 'string' );
		$search					= JString::strtolower( $search );
		
		$where = null;
		if ( $filter_restricted ) {
				$where = ' WHERE (sc.display = '.$filter_restricted.' OR sc.display > '.$filter_restricted.') ';
		}
		
		if ( $filter_category ) {
			$categories = $this->_getBranch( $filter_category );
			if ( $categories ) {
				$where .= ( $where ) ? ' AND s.category IN ('.$categories.')' : ' WHERE s.category IN ('.$categories.')';
			}
		}

		if ( $filter_title != -1 ) {
			if ( $filter_title == 0 ) {
				$where .= ( $where ) ? ' AND s.substitute = 0' : ' WHERE s.substitute = 0';
			} else {
				$where .= ( $where ) ? ' AND s.substitute' : ' WHERE s.substitute';
			}
		}

		if ( $filter_index != -1 ) {
			$where .= ( $where ) ? ' AND ( s.indexedkey = '.$filter_index.' OR s.indexed = '.$filter_index.' )' : ' WHERE ( s.indexedkey = '.$filter_index.' OR s.indexed = '.$filter_index.' )';
		}

		if ( $filter_type ) {
			$where .= ( $where ) ? ' AND s.type = '.$filter_type : ' WHERE s.type = '.$filter_type;
		}
		
		if ( $filter_content_type ) {
			$where .= ( $where ) ? ' AND cccc.id = '.$filter_content_type : ' WHERE cccc.id = '.$filter_content_type;
		}
		
		if ( $filter_search_type ) {
			$where .= ( $where ) ? ' AND tccc.id = '.$filter_search_type : ' WHERE tccc.id = '.$filter_search_type;
		}
		
		if ( $filter_client ) {
			if ( $filter_client == 'admin' || $filter_client == 'site' ) {	
				$where .= ( $where ) ? ' AND ccc.client = "'.$filter_client.'"' : ' WHERE ccc.client = "'.$filter_client.'"';
			} else if ( $filter_client == 'both' ) {
				$itemsWithClient = $this->_getItemsWithClient();
				$where .= ( $where ) ? ' AND s.id IN ('.$itemsWithClient.')' : ' WHERE s.id IN ('.$itemsWithClient.')';
			} else if ( $filter_client == 'none' ) {
				$itemsWithClient = $this->_getItemsWithClient();
				$where .= ( $where ) ? ' AND s.id NOT IN ('.$itemsWithClient.')' : ' WHERE s.id IN ('.$itemsWithClient.')';
			}
		}
			
		if ( $search ) {
			if ( $filter_search == 9 ) {
				$where .= ( $where ) ? ' AND cccc.id = '.(int)$search : ' WHERE cccc.id = '.(int)$search;
			} else if ( $filter_search == 8 ) {
				$where .= ( $where ) ? ' AND LOWER(cccc.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(cccc.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else if ( $filter_search == 7 ) {
				$where .= ( $where ) ? ' AND LOWER(cccc.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(cccc.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else if ( $filter_search == 6 ) {
				$where .= ( $where ) ? ' AND s.category = ' . (int)$search : ' WHERE s.category = ' . (int)$search;
			} else if ( $filter_search == 5 ) {
				$where .= ( $where ) ? ' AND LOWER(sc.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(sc.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else if ( $filter_search == 4 ) {
				$where .= ( $where ) ? ' AND s.id = ' . (int)$search : ' WHERE s.id = ' . (int)$search;
			} else if ( $filter_search == 3 ) {
				$where .= ( $where ) ? ' AND LOWER(typetitle) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(typetitle) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else if ( $filter_search == 2 ) {
				$where .= ( $where ) ? ' AND LOWER(s.description) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(s.description) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else if ( $filter_search == 1 ) {
				$where .= ( $where ) ? ' AND LOWER(s.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(s.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else {
				$where .= ( $where ) ? ' AND LOWER(s.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(s.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );				
			}
		}
		
		return $where;
	}
	
	/**
	 * Return OrderBy into Query 
	 **/
	function _buildContentOrderBy() {
		
		global $mainframe, $option;
		$controller			= JRequest::getWord( 'controller' );
		$task				=	JRequest::getVar( 'layout' );
		$contentTypeFilter	=	JRequest::getInt( 'contentfilter' );
		$clientFilter 		=	JRequest::getWord( 'clientfilter' );
		$searchTypeFilter	=	JRequest::getInt( 'searchfilter' );
		
		$filter_content_type	= ( $contentTypeFilter ) ? $contentTypeFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_content_type',	'filter_content_type',	0,	'int' );
		$filter_search_type		= ( $searchTypeFilter ) ? $searchTypeFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_search_type',	'filter_search_type',	0,	'int' );
		$filter_client			= ( $clientFilter ) 	? $clientFilter : 	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_client',	'filter_client',	'',	'word' );
		//		
		$call_ordering			=	( $contentTypeFilter && $clientFilter ) ? 1 : JRequest::getInt( 'call_ordering' );
		$filter_order			=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order',		'filter_order',		's.title',	'cmd' );
		$filter_order_Dir		=	( $filter_content_type && $filter_client && $call_ordering ) ? 'asc' : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order_Dir',	'filter_order_Dir',	'asc',		'cmd' );
		$filter_order			=	( $filter_content_type && $filter_client && $call_ordering ) ? 'ccc.ordering' : $filter_order;
		$filter_order_Dir		=	( $filter_order == 'ccc.ordering' && ( ! $filter_content_type || ! $filter_client ) ) ? 'asc' : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order_Dir',	'filter_order_Dir',	'asc',		'cmd' );
		$filter_order			=	( $filter_order == 'ccc.ordering' && ( ! $filter_content_type || ! $filter_client ) ) ? 's.title' : $filter_order;
		
		$orderby = ' ORDER BY '.$filter_order .' '. $filter_order_Dir;
		
		return $orderby;
	}

	function _getBranch( $currentId )
	{
		if ( empty( $this->_branch ) ) {
			$query = 'SELECT s.id, (COUNT(parent.id) - (branch.depth + 1)) AS depth'
					.' FROM #__jseblod_cck_items_categories AS s,'
					.' #__jseblod_cck_items_categories AS parent,'
					.' #__jseblod_cck_items_categories AS subparent,'
					.' ('
						.' SELECT s.id, (COUNT(parent.id) - 1) AS depth'
						.' FROM #__jseblod_cck_items_categories AS s,'
						.' #__jseblod_cck_items_categories AS parent'
						.' WHERE s.lft BETWEEN parent.lft AND parent.rgt'
						.' AND s.id ='.$currentId
						.' GROUP BY s.id'
						.' ORDER BY s.lft'
						.' ) AS branch'
					.' WHERE s.lft BETWEEN parent.lft AND parent.rgt'
					.' AND s.lft BETWEEN subparent.lft AND subparent.rgt'
					.' AND subparent.id = branch.id'
					.' GROUP BY s.id'
					.' ORDER BY s.lft';
			
			$this->_db->setQuery( $query );
			$this->_branch = $this->_db->loadResultArray();
			if ( is_array( $this->_branch ) ) {
				$this->_branch = implode( ',', $this->_branch );
			}
		}
		
		return( $this->_branch );
	}
	
	function _getItemsWithClient()
	{
		$query	= 'SELECT s.itemid '
				. ' FROM #__jseblod_cck_type_item AS s'
				. ' ORDER BY s.itemid';
				;
		$this->_db->setQuery( $query );
		$itemsWithClient = $this->_db->loadResultArray();
		if ( is_array( $itemsWithClient ) ) {
			$itemsWithClient = implode( ',', $itemsWithClient );
		}
		
		return( $itemsWithClient );
	}
	
	/**
	 * Get Data from Database
	 **/
	function getReservedData()
	{
		if ( empty( $this->_reservedData ) )
		{		
			$query = 'SELECT s.name as text, s.name as value'
				   . ' FROM #__jseblod_cck_items_reserved AS s'
				   . ' ORDER BY s.name';
			$this->_db->setQuery( $query );
			$this->_reservedData = $this->_db->loadObjectList();
		}

		return $this->_reservedData;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getExtendedInfos( $extended, $extendedType )
	{
		$where = ' WHERE s.name = "'.$extended.'"';
		
  		$query = ' SELECT s.id, s.title'
  			. ' FROM #__jseblod_cck_'.$extendedType.' AS s'
  			. $where
  		;
    	$this->_db->setQuery( $query );
  		$this->_extendedInfos = $this->_db->loadObject();
		
		return $this->_extendedInfos;
	}
}
?>