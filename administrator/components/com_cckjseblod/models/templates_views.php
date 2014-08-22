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
 * Templates_Views		Model Class
 **/
class CCKjSeblodModelTemplates_Views extends JModel
{
	/**
	 * Vars
	 **/
	var $_data 			= null;
	var $_total 		= null;
	var $_pagination	= null;
	var $_allmenu		= null;
	
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
		global $mainframe, $option;
		$db	=& JFactory::getDBO();
		$controller		= JRequest::getWord( 'controller' );
		$templateFilter	= JRequest::getInt( 'templatefilter' );
		
		$where = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();
		
		$filter_search	= ( $templateFilter ) ? 3 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_search', 'filter_search',	0, 'int' );
		$search			= ( $templateFilter ) ? $templateFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.search', 'search',	'',	'string' );
		$search			= JString::strtolower( $search );
		
		if ( $search ) {
			if ( $filter_search == 3 ) {
				$where .= ( $where ) ? ' AND cc.id = '.(int)$search : ' WHERE cc.id = '.(int)$search;
			} else if ( $filter_search == 2 ) {
				$where .= ( $where ) ? ' AND LOWER( cc.name ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER( cc.name ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else if ( $filter_search == 1 ) {
				$where .= ( $where ) ? ' AND LOWER( cc.title ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER( cc.title ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else {
				$whereC = ( $where ) ? ' AND LOWER( scc.title ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER( scc.title ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
				$whereM = ( $where ) ? ' AND LOWER( scm.name ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER( scm.name ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
				$whereU = ( $where ) ? ' AND LOWER( s.title ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER( s.title ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			}
		}
		$requiredWhereC = ( ! $where && ! @$whereC ) ? ' WHERE scc.id ' : ' AND scc.id ';
		$requiredWhereM = ( ! $where && ! @$whereM ) ? ' WHERE scm.id AND scm.published >= 0 ' : ' AND scm.id AND scm.published >= 0 ';
		$requiredWhereU = ( ! $where && ! @$whereU ) ? '' : '';
			
		$query = ' SELECT s.templateid, s.catid as assignmentid, s.type as assignmenttypename, CONCAT( "'.JText::_( "JOOMLA CATEGORY" ).'", "" ) as assignmenttypetitle, cc.title as templatetitle, cc.published as published, scc.title as assignmenttitle, sccc.title as assignmentextra'
			. ' FROM #__jseblod_cck_template_cat AS s'
			. ' LEFT JOIN #__jseblod_cck_templates AS cc ON cc.id = s.templateid'
			. ' LEFT JOIN #__categories AS scc ON scc.id = s.catid'
			. ' LEFT JOIN #__sections AS sccc ON sccc.id = scc.section'
			. $where
			. @$whereC
			. $requiredWhereC
			. ' UNION'
			. ' SELECT s.templateid, s.menuid as assignmentid, s.type as assignmenttypename, CONCAT( "'.JText::_( "MENU ITEM" ).'", "" ) as assignmenttypetitle, cc.title as templatetitle, cc.published as published, scm.name as assignmenttitle, scmc.title as assignmentextra'
			. ' FROM #__jseblod_cck_template_menu AS s'
			. ' LEFT JOIN #__jseblod_cck_templates AS cc ON cc.id = s.templateid'
			. ' LEFT JOIN #__menu AS scm ON scm.id = s.menuid'
			. ' LEFT JOIN #__menu_types AS scmc ON scmc.menutype = scm.menutype'
			. $where
			. @$whereM
			. $requiredWhereM
			. ' UNION'
			. ' SELECT s.templateid, s.urlid as assignmentid, s.type as assignmenttypename, CONCAT( "'.JText::_( "SITE URL" ).'", "" ) as assignmenttypetitle, cc.title as templatetitlem, cc.published as published, s.title as assignmenttitle, s.url as assignmentextra' 
			. ' FROM #__jseblod_cck_template_url AS s'
			. ' LEFT JOIN #__jseblod_cck_templates AS cc ON cc.id = s.templateid'
			. $where
			. @$whereU
			. $requiredWhereU
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
		$controller	= JRequest::getWord( 'controller' );
		$typeFilter = JRequest::getWord( 'typefilter' );
		$templateFilter	= JRequest::getInt( 'templatefilter' );
		
		$filter_type	= ( $templateFilter && ! $typeFilter ) ? 0 : ( ( $typeFilter ) ? $typeFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_type', 'filter_type', 0, 'word' ) );
		$filter_state	= ( $templateFilter ) ? '' : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_state',	'filter_state',	'',	'word' );
		
		$where = '';
		
		if ( $filter_type ) {
			$where = ' WHERE s.type = "'.$filter_type.'"';
		}
		
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where .= ( $where ) ? ' AND cc.published = 1' : ' WHERE cc.published = 1';
			} else if ($filter_state == 'U' ) {
				$where .= ( $where ) ? ' AND cc.published = 0' : ' WHERE cc.published = 0';
			}
		}
		
		return $where;
	}
	
	/**
	 * Return OrderBy into Query 
	 **/
	function _buildContentOrderBy() {
		
		global $mainframe, $option;
		$controller	= JRequest::getWord( 'controller' );
		
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order',		'filter_order',		'assignmenttitle',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order_Dir',	'filter_order_Dir',	'asc',				'cmd' );
		
		$orderby = ' ORDER BY '.$filter_order .' '. $filter_order_Dir;
		return $orderby;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getAllMenu()
	{
		if ( empty( $this->_allmenu ) )
		{
			$query = $this->_buildQueryAllMenu();
			$this->_allmenu = $this->_getList( $query ); 
		}
		
		return $this->_allmenu;
	}
	
	/**
	 * Return Database Query
	 **/
	function _buildQueryAllMenu()
	{
		global $mainframe;
		$db	=& JFactory::getDBO();
		$controller		= JRequest::getWord( 'controller' );
			
		$query	= 'SELECT s.templateid, cc.title AS templatetitle, cc.published, CONCAT( "'.JText::_( "ALL MENU ITEMS" ).'", "" ) as assignmenttitle, CONCAT( "'.JText::_( "ALL MENU ITEMS" ).'", "" ) as assignmenttypetitle'
				. ' FROM #__jseblod_cck_template_menu AS s'
				. ' LEFT JOIN #__jseblod_cck_templates AS cc ON cc.id = s.templateid'
				. ' WHERE s.menuid = 0'
				. ' ORDER BY cc.title'
				;
		
		return $query;
	}

}
?>