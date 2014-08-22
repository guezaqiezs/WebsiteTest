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
 * Content Templates	Model Class
 **/
class CCKjSeblodModelTemplates extends JModel
{
	/**
	 * Vars
	 **/
	var $_data 			= null;
	var $_total 		= null;
	var $_pagination	= null;
	var $_branch		= null;
	
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
        //$limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		
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
			
			$query	= 'SELECT s.templateid, COUNT( s.templateid ) AS num'
					. ' FROM #__jseblod_cck_template_cat AS s'
					. ' LEFT JOIN #__categories AS cc ON cc.id = s.catid '
					. ' WHERE cc.id'
					. ' GROUP BY s.templateid';
			$this->_db->setQuery( $query );
			$catAssignments = $this->_db->loadObjectList( 'templateid' );
			
			$query	= 'SELECT s.templateid, COUNT( s.templateid ) AS num'
					. ' FROM #__jseblod_cck_template_menu AS s'
					. ' LEFT JOIN #__menu AS cc ON cc.id = s.menuid '
					. ' WHERE cc.id AND cc.published >= 0'
					. ' GROUP BY s.templateid';
			$this->_db->setQuery( $query );
			$menuAssignments = $this->_db->loadObjectList( 'templateid' );
			
			$query	= 'SELECT s.templateid, COUNT( s.templateid ) AS num'
					. ' FROM #__jseblod_cck_template_menu AS s'
					. ' WHERE s.menuid = 0'
					. ' GROUP BY s.templateid';
			$this->_db->setQuery( $query );
			$allMenuAssignments = $this->_db->loadObjectList( 'templateid' );
			
			$query	= 'SELECT s.templateid, COUNT( s.templateid ) AS num'
			   	. ' FROM #__jseblod_cck_template_url AS s'
			   	. ' GROUP BY s.templateid';
			$this->_db->setQuery( $query );
			$urlAssignments = $this->_db->loadObjectList( 'templateid' );
			
			$query	= 'SELECT s.admintemplate, COUNT( s.admintemplate ) AS num'
			   	. ' FROM #__jseblod_cck_types AS s'
			   	. ' GROUP BY s.admintemplate';
			$this->_db->setQuery( $query );
			$defaultTemplateAT = $this->_db->loadObjectList( 'admintemplate' );
			
			$query	= 'SELECT s.sitetemplate, COUNT( s.sitetemplate ) AS num'
			   	. ' FROM #__jseblod_cck_types AS s'
			   	. ' GROUP BY s.sitetemplate';
			$this->_db->setQuery( $query );
			$defaultTemplateST = $this->_db->loadObjectList( 'sitetemplate' );
			
			$query	= 'SELECT s.contenttemplate, COUNT( s.contenttemplate ) AS num'
			   	. ' FROM #__jseblod_cck_types AS s'
			   	. ' GROUP BY s.contenttemplate';
			$this->_db->setQuery( $query );
			$defaultTemplateCT = $this->_db->loadObjectList( 'contenttemplate' );
			
			if ( sizeof( $this->_data ) ) {
				foreach ( $this->_data as $item ) {
					$item->catAssignments	=	@$catAssignments[$item->id]->num ? 	$catAssignments[$item->id]->num : '-';
					$item->menuAssignments	=	( @$allMenuAssignments[$item->id]->num ) ? 'all' : ( @$menuAssignments[$item->id]->num ? 	$menuAssignments[$item->id]->num : '-' );
					$item->urlAssignments	=	@$urlAssignments[$item->id]->num ? 	$urlAssignments[$item->id]->num : '-';
					$item->views			=	@$catAssignments[$item->id]->num + @$menuAssignments[$item->id]->num + @$urlAssignments[$item->id]->num;
					$item->default			=	( @$defaultTemplateAT[$item->id]->num + @$defaultTemplateST[$item->id]->num + @$defaultTemplateCT[$item->id]->num ) ? 1 : 0;
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
        }
		
        return $this->_pagination;
	}
	
	/**
	 * Return Database Query
	 **/
	function _buildQuery()
	{
		global $mainframe;
		
		$where = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();
		
		$query = 'SELECT s.*, cc.title AS categorytitle, cc.color AS categorycolor, cc.introchar AS categoryintrochar, cc.colorchar AS categorycolorchar, u.name AS editor'
				.' FROM #__jseblod_cck_templates AS s'
				.' LEFT JOIN #__jseblod_cck_templates_categories AS cc ON cc.id = s.category'
				.' LEFT JOIN #__users AS u ON u.id = s.checked_out'
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
		$db	=& JFactory::getDBO();
		$controller			=	JRequest::getWord( 'controller' );
		$task				=	JRequest::getVar( 'layout' );
		$categoryFilter 	=	JRequest::getInt( 'categoryfilter' );
		$selectCat 			=	JRequest::getInt( 'selectcat' );
				
		$filter_category	= ( $categoryFilter ) ? 0 : ( ( $selectCat ) ? $selectCat : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_category',	'filter_category',	0,		'int' ) );
		$filter_assignment	= ( $categoryFilter ) ? 0 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_assignment',	'filter_assignment',	0,		'int' );
		$filter_state		= ( $task == 'element' ) ? 'P' : ( ( $categoryFilter ) ? '' : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_state',		'filter_state',		'',			'word' ) );
		$filter_type		=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_type',	'filter_type',	'',	'string' );
		$filter_mode		=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_mode',	'filter_mode',	'',	'string' );
		$filter_search		= ( $categoryFilter ) ? 5 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_search',	'filter_search',	0,		'int' );
		$search				= ( $categoryFilter ) ? $categoryFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.search',			'search',			'',		'string' );
		$search				= JString::strtolower( $search );
		
		$where = '';
		
		if ( $filter_category ) {
			$categories = $this->_getBranch( $filter_category );
			if ( $categories ) {
				$where .= ( $where ) ? ' AND s.category IN ('.$categories.')' : ' WHERE s.category IN ('.$categories.')';
			}
		}
		
		if ( $filter_assignment ) {
			$views = $this->_getTypeWithViews();
			if ( $filter_assignment == -1 ) {
				$where .= ( $where ) ? ' AND s.id NOT IN ('.$views.')' : ' WHERE s.id NOT IN ('.$views.')';
			} else if ( $filter_assignment == 1 ) {
				$where .= ( $where ) ? ' AND s.id IN ('.$views.')' : ' WHERE s.id IN ('.$views.')';
			}
		}
		
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where .= ($where) ? ' AND s.published = 1' : ' WHERE s.published = 1';
			} else if ($filter_state == 'U' ) {
				$where .= ($where) ? ' AND s.published = 0' : ' WHERE s.published = 0';
			}
		}
		
		if ( $task == 'select' || $task == 'element' ) {
			$tpl_type	=	JRequest::getVar( 'tpl_type' );
			if ( $tpl_type != '' ) {
				$where .= ($where) ? ' AND s.type = '.$tpl_type : ' WHERE s.type = '.$tpl_type;
			}
			if ( $filter_mode != '' ) {
				$where .= ($where) ? ' AND s.mode = '.$filter_mode : ' WHERE s.mode = '.$filter_mode;
			}
		} else {
			if ( $filter_type != '' ) {
				$where .= ($where) ? ' AND s.type = '.$filter_type : ' WHERE s.type = '.$filter_type;
			}
		}
		
		if ( $search ) {
			if ( $filter_search == 5 ) {
				$where .= ( $where ) ? ' AND s.category = '.(int)$search : ' WHERE s.category = '.(int)$search;
			} else if ( $filter_search == 4 ) {
				$where .= ( $where ) ? ' AND LOWER(cc.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(cc.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else if ( $filter_search == 3 ) {
				$where .= ( $where ) ? ' AND s.id = '.(int)$search : ' WHERE s.id = '.(int)$search;
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
	function _buildContentOrderBy() 
	{	
		global $mainframe, $option;
		$controller	=	JRequest::getWord( 'controller' );
		$task		=	JRequest::getVar( 'layout' );
		
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order',		'filter_order',		's.title',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order_Dir',	'filter_order_Dir',	'asc',		'cmd' );
		
		$orderby = ' ORDER BY '.$filter_order .' '. $filter_order_Dir;
		
		return $orderby;
	}
	
	function _getBranch( $currentId )
	{
		if ( empty( $this->_branch ) ) {
			$query = 'SELECT s.id, (COUNT(parent.id) - (branch.depth + 1)) AS depth'
					.' FROM #__jseblod_cck_templates_categories AS s,'
					.' #__jseblod_cck_templates_categories AS parent,'
					.' #__jseblod_cck_templates_categories AS subparent,'
					.' ('
						.' SELECT s.id, (COUNT(parent.id) - 1) AS depth'
						.' FROM #__jseblod_cck_templates_categories AS s,'
						.' #__jseblod_cck_templates_categories AS parent'
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
	
	function _getTypeWithViews()
	{
		$whereC = ' WHERE cc.id';
		$whereM = ' WHERE cm.id AND cm.published >= 0';
		$whereU = '';
		if ( empty( $this->_views ) ) {
			$query	= 'SELECT s.templateid'
					. ' FROM #__jseblod_cck_template_cat AS s'
					. ' LEFT JOIN #__categories AS cc ON cc.id = s.catid '
					. $whereC
					. ' UNION'
					. ' SELECT s.templateid'
					. ' FROM #__jseblod_cck_template_menu AS s'
					. ' LEFT JOIN #__menu AS cm ON cm.id = s.menuid '
					. $whereM
					. ' UNION'
					. ' SELECT s.templateid'
					. ' FROM #__jseblod_cck_template_url AS s'
					. $whereU
					;
			$this->_db->setQuery( $query );
			$this->_views = $this->_db->loadResultArray();
			if ( is_array( $this->_views ) ) {
				$this->_views = implode( ',', $this->_views );
			}
		}
		
		return ( $this->_views );
	}
}
?>