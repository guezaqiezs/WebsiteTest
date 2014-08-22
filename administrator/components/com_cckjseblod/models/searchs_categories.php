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
 * Searchs_Categories		Model Class
 **/
class CCKjSeblodModelSearchs_Categories extends JModel
{
	/**
	 * Vars
	 **/
	var $_data 			=	null;
	var $_total 		=	null;
	var $_pagination	=	null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
        parent::__construct();
		
        global $mainframe, $option;
		$controller =	JRequest::getWord( 'controller' );
		$task		=	JRequest::getVar( 'layout' );
		
        // Get Pagination Request Variables
        $limit		=	$mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg( 'list_limit' ), 'int' );
        $limitstart = $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.limitstart', 'limitstart', 0, 'int' );
        //$limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		
        // In case Limit has been Changed, Adjust it
        $limitstart	=	( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );
		
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
			$query 			=	$this->_buildQuery();
			$this->_data	=	$this->_getList( $query, $this->_pagination->limitstart, $this->_pagination->limit );
			
			$query	= 'SELECT s.category, COUNT( s.category ) AS num'
					. ' FROM #__jseblod_cck_searchs AS s'
					. ' GROUP BY s.category';
			$this->_db->setQuery( $query );
			$searchs	=	$this->_db->loadObjectList( 'category' );
			if ( sizeof( $this->_data ) ) {
				foreach ( $this->_data as $item ) {
					$item->displaySearchs	=	@$searchs[$item->id]->num ? $searchs[$item->id]->num : 0;
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
            $query			=	$this->_buildQuery();
            $this->_total	=	$this->_getListCount( $query );
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
			$this->_pagination	=	new JPagination( $this->getTotal(), $this->getState( 'limitstart' ), $this->getState( 'limit' ) );
			if ( $this->_pagination->limitstart && ( $this->_pagination->limitstart == $this->_pagination->total ) ) {
				$this->_pagination->limitstart = $this->_pagination->limitstart - $this->_pagination->limit;
			}
		}
		
		return $this->_pagination;
	}
	
	/**
	 * Return Database Query
	 **/
	function _buildQuery()
	{
		global $mainframe;
		
		$where		=	$this->_buildContentWhere();
		$orderby 	=	$this->_buildContentOrderBy();
		$groupby	=	' GROUP BY s.title ';
		
		$query	= ' SELECT s.title, s.name, s.color, s.introchar, s.colorchar, ( COUNT( parent.title ) - 1 ) AS depth, s.published, s.lft, s.rgt, s.id, s.checked_out, s.checked_out_time, s.checked_out AS editor'
				. ' FROM #__jseblod_cck_searchs_categories AS s, #__jseblod_cck_searchs_categories AS parent'
				. $where
				. $groupby
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
		$controller		=	JRequest::getWord( 'controller' );
		$db				=&	JFactory::getDBO();
		$filterin		=	JRequest::getInt( 'filterin' );
		$categoryFilter	=	JRequest::getInt( 'categoryfilter' );
		
		$filter_category	=	( $categoryFilter ) ? $categoryFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_category', 'filter_category', 0, 'int' );
		$filter_state		=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_state',		'filter_state',		'',		'word' );
		$filter_search		=	( $filterin ) ? 3 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_search',	'filter_search',	0,		'int' );
		$search				=	( $filterin ) ? $filterin : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.search',			'search',			'',		'string' );
		$search				=	JString::strtolower( $search );
		
		$where	=	' WHERE ( s.lft BETWEEN parent.lft AND parent.rgt )';
		
		if ( $filter_category ) {
			$categories	=	$this->_getBranch( $filter_category );
			if ( $categories ) {
				$where	.=	' AND s.id IN ('.$categories.')';
			}
		}
		
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where	.=	' AND s.published = 1';
			} else if ($filter_state == 'U' ) {
				$where	.=	' AND s.published = 0';
			}
		}
		
		if ( $search ) {
			if ( $filter_search == 3 ) {
				$where	.=	( $where ) ? ' AND s.id = ' . (int)$search : ' WHERE s.id = ' . (int)$search;
			} else if ( $filter_search == 2 ) {
				$where	.=	( $where ) ? ' AND LOWER(s.description) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(s.description) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else if ( $filter_search == 1 ) {
				$where	.=	( $where ) ? ' AND LOWER(s.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(s.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else {
				$where	.=	( $where ) ? ' AND LOWER(s.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(s.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			}
		}
		
		return $where;
	}
	
	/**
	 * Return OrderBy into Query 
	 **/
	function _buildContentOrderBy() {
		
		global $mainframe, $option;
		$controller	=	JRequest::getWord( 'controller' );
		
		$filter_order		=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order',		'filter_order',		's.lft',	'cmd' );
		$filter_order_Dir	=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order_Dir',	'filter_order_Dir',	'asc',		'cmd' );
		
		$orderby	=	( $filter_order == 'depth' ) ? ' ORDER BY '.$filter_order .' '. $filter_order_Dir . ', s.name '. $filter_order_Dir : ' ORDER BY '.$filter_order .' '. $filter_order_Dir;
		
		return $orderby;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getParent( $currentId )
	{
		$where		=	' WHERE ( s.lft BETWEEN parent.lft AND parent.rgt ) AND s.id != parent.id AND s.id ='.$currentId;
		$orderby 	=	' ORDER BY parent.lft DESC';
    		
		$query 	= 'SELECT parent.id'
				. ' FROM #__jseblod_cck_searchs_categories AS s, #__jseblod_cck_searchs_categories AS parent'
				. $where
				. $orderby
				;
		$this->_db->setQuery( $query );
		$parent	=	$this->_db->loadResult();
		
		return $parent;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getBranch( $currentId )
	{	
		if ( empty( $this->_pagination ) ) {
			$query 	= 'SELECT s.id, (COUNT(parent.id) - (branch.depth + 1)) AS depth'
					. ' FROM #__jseblod_cck_searchs_categories AS s,'
					. ' #__jseblod_cck_searchs_categories AS parent,'
					. ' #__jseblod_cck_searchs_categories AS subparent,'
					. ' ('
						. ' SELECT s.id, (COUNT(parent.id) - 1) AS depth'
						. ' FROM #__jseblod_cck_searchs_categories AS s,'
						. ' #__jseblod_cck_searchs_categories AS parent'
						. ' WHERE s.lft BETWEEN parent.lft AND parent.rgt'
						. ' AND s.id ='.$currentId
						. ' GROUP BY s.id'
						. ' ORDER BY s.lft'
						. ' ) AS branch'
					. ' WHERE s.lft BETWEEN parent.lft AND parent.rgt'
					. ' AND s.lft BETWEEN subparent.lft AND subparent.rgt'
					. ' AND subparent.id = branch.id'
					. ' GROUP BY s.id'
					. ' ORDER BY s.lft';
			
			$this->_db->setQuery( $query );
			$this->_branch	=	$this->_db->loadResultArray();
			
			if ( is_array( $this->_branch ) ) {
				$this->_branch	=	implode( ',', $this->_branch );
			}
		}
		
		return( $this->_branch );
	}
}
?>