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
 * Interface		Model Class
 **/
class CCKjSeblodModelInterface extends JModel
{
	/**
	 * Vars
	 **/
	var $_data 			= null;
	var $_total 		= null;
	var $_pagination	= null;
	
	var $_artId			= null;
	var $_article		= null;
	var $_contentType	= null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
        parent::__construct();
		
        global $mainframe, $option;
		$controller = 	JRequest::getWord( 'controller' );
		$task		=	JRequest::getVar( 'layout' );
		
        // Get Pagination Request Variables
        $limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg( 'list_limit' ), 'int' );
   		$limitstart = $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.limitstart', 'limitstart', 0, 'int' );
        //$limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		
        // In case Limit has been Changed, Adjust it
        $limitstart = ( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );
		
        $this->setState( 'limit', $limit );
        $this->setState( 'limitstart', $limitstart );
		
		$artId = JRequest::getInt( 'artid' );
		$this->setValues( $artId );
	}
	
	/**
	 * Set Values
	 **/
	function setValues( $artId )
	{
		// Set Values
		$this->_artId					= $artId;
		$this->_article					= null;
		$this->_contentType				= null;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getArticle( $artId, $lang_id = 0 )
	{
		if ( empty( $this->_article ) )
		{
			if ( $artId )
			{
				$query	=	'SELECT id, introtext AS content, catid FROM #__content'
						.	' WHERE id = '.$artId;
				$this->_db->setQuery( $query );
				$this->_article	=	$this->_db->loadObject();
				if ( $lang_id ) {
					$introtext	=	CCKjSeblodItem_Form::getIntrotextFromJf( $artId, $lang_id );
					if ( $introtext ) {
						$this->_article->content	=	$introtext;
					}
				}
			}
		}
		
		return $this->_article;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getCategory()
	{
		if ( empty( $this->_category ) )
		{
			if ( $this->_artId )
			{
				$query = ' SELECT id, description AS content FROM #__categories'
						.' WHERE id = '.$this->_artId;
				$this->_db->setQuery( $query );
				$this->_category = $this->_db->loadObject();
			}
		}
		
		return $this->_category;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getContentTypeByCat( $catId )
	{
		if ( empty( $this->_contentType ) )
		{
			if ( $catId )
			{
				$query = ' SELECT s.name FROM #__jseblod_cck_types AS s'
						.' LEFT JOIN #__jseblod_cck_type_cat AS cc ON cc.typeid = s.id'
						.' WHERE s.published = 1 AND cc.catid = '.$catId
						;
				$this->_db->setQuery( $query );
				$this->_contentType = $this->_db->loadResult();
			}
		}
		
		return $this->_contentType;
	}

	/**
	 * Get Data from Database
	 **/
	function getContentTypeByUrl( $u_opt, $cckId, $form_action = false )
	{
		if ( empty( $this->_contentType ) )
		{
			if ( $u_opt )
			{
				$url_type	=	( $cckId ) ? ' AND ( cc.type="url" OR cc.type="url_edit" )' : ' AND ( cc.type="url" OR cc.type="url_add" )';
				if ( $form_action ) {
					$query	= ' SELECT s.name, scc.bool AS state, scc.location AS category, scc.bool5 AS access FROM #__jseblod_cck_types AS s'
							. ' LEFT JOIN #__jseblod_cck_type_url AS cc ON cc.typeid = s.id'
							. ' LEFT JOIN #__jseblod_cck_type_item AS sc ON sc.typeid = s.id'
							. ' LEFT JOIN #__jseblod_cck_items AS scc ON scc.id = sc.itemid'
							. ' WHERE s.published = 1 AND cc.url = "'.$u_opt.'" AND sc.client = "admin" AND scc.type = 25'
							. $url_type
							;					
				} else {
					$query	= ' SELECT s.name FROM #__jseblod_cck_types AS s'
							. ' LEFT JOIN #__jseblod_cck_type_url AS cc ON cc.typeid = s.id'
							. ' WHERE s.published = 1 AND cc.url = "'.$u_opt.'"'
							. $url_type
							;
				}
				$this->_db->setQuery( $query );
				$this->_contentType = $this->_db->loadObject();
			}
		}
		
		return $this->_contentType;
	}

	/**
	 * Get Data from Database
	 **/
	function getData( $act )
	{
		if ( empty( $this->_data ) )
		{
			$query = $this->_buildQuery( $act );
			$this->_data = $this->_getList( $query, $this->_pagination->limitstart, $this->_pagination->limit );
		}
		
		return $this->_data;
	}

	/**
	 * Get Total
	 **/
	function getTotal( $act )
	{
        if ( empty( $this->_total ) ) {
            $query = $this->_buildQuery( $act );
            $this->_total = $this->_getListCount( $query );
        }
		
        return $this->_total;
	}
	
	/**
	 * Get Pagination Object
	 **/
	function getPagination( $act )
	{
		if ( empty( $this->_pagination ) ) {
			jimport( 'joomla.html.pagination' );
			$this->_pagination = new JPagination( $this->getTotal( $act ), $this->getState( 'limitstart' ), $this->getState( 'limit' ) );
		if ( $this->_pagination->limitstart && ( $this->_pagination->limitstart == $this->_pagination->total ) ) {
				$this->_pagination->limitstart = $this->_pagination->limitstart - $this->_pagination->limit;
			}
		}
		
		return $this->_pagination;
	}
	
	/**
	 * Return Database Query
	 **/
	function _buildQuery( $act )
	{
		global $mainframe;
		
		$where = $this->_buildContentWhere( $act );
		$orderby = $this->_buildContentOrderBy();
		
		$query = 'SELECT s.*, cc.title AS categorytitle, cc.color AS categorycolor, cc.introchar AS categoryintrochar, cc.colorchar AS categorycolorchar'
				.' FROM #__jseblod_cck_types AS s'
				.' LEFT JOIN #__jseblod_cck_types_categories AS cc ON cc.id = s.category'
				.' LEFT JOIN #__jseblod_cck_type_item AS ccc ON ccc.typeid = s.id'
				.' LEFT JOIN #__jseblod_cck_items AS cccc ON cccc.id = ccc.itemid'
				. $where
				.' GROUP BY s.id'
				. $orderby
				;
			
		 return $query;
	}
	
	/**
	 * Return Where into Query 
	 **/
	function _buildContentWhere( $act )
	{
		global $mainframe, $option;
		$controller	= JRequest::getWord( 'controller' );
		$db	=& JFactory::getDBO();
		
		$filter_category	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_category',		'filter_category',		0,		'int' );
		$filter_search		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_search',		'filter_search',		0,		'int' );
		$search				= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.search',				'search',				'',		'string' );
		$search				= JString::strtolower( $search );
		$restriction		=	( _RESTRICTION_CONTENT != '' ) ? _RESTRICTION_CONTENT : 0;
		
		$where = ' WHERE s.published = 1 AND cc.display >= '.$restriction.' AND cc.id ';
		
//		if ( $act && $act != 0 ) {
			if ( $act == 3 ) {
				$where	.=	' AND ccc.client = "admin" AND cccc.type = 25 AND ( cccc.bool2 = 3 OR cccc.bool2 = 0 )';
			} else if ( $act == -2 ) {
				$where	.=	' AND ccc.client = "admin" AND cccc.type = 25 AND ( cccc.bool2 = 0 )';
			} else if ( $act == -1 ) {
				$where	.=	' AND ccc.client = "admin" AND cccc.type = 25 AND ( cccc.bool2 = 0 OR cccc.bool2 = 1 OR cccc.bool2 = 2 )';
			} else {
				$where	.=	' AND ccc.client = "admin" AND cccc.type = 25 AND cccc.bool2 = '.$act;
			}
//		}
		
		if ( $filter_category ) {
			$categories = $this->_getBranch( $filter_category );
			if ( $categories ) {
				$where .= ( $where ) ? ' AND s.category IN ('.$categories.')' : ' WHERE s.category IN ('.$categories.')';
			}
		}
		
		if ( $search ) {
			if ( $filter_search == 8 ) {
				$where .= ( $where ) ? ' AND cccc.id = '.(int)$search : ' WHERE cccc.id = '.(int)$search;
			} else if ( $filter_search == 7 ) {
				$where .= ( $where ) ? ' AND LOWER(cccc.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(cccc.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else if ( $filter_search == 6 ) {
				$where .= ( $where ) ? ' AND LOWER(cccc.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) : ' WHERE LOWER(cccc.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			} else if ( $filter_search == 5 ) {
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
	function _buildContentOrderBy() {
		
		global $mainframe, $option;
		$controller	= JRequest::getWord( 'controller' );
		
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order',		'filter_order',		's.title',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order_Dir',	'filter_order_Dir',	'asc',		'cmd' );
		
		$orderby = ' ORDER BY '.$filter_order .' '. $filter_order_Dir;
		
		return $orderby;
	}
	
	function _getBranch( $currentId )
	{
		if ( empty( $this->_branch ) ) {
			$query = 'SELECT s.id, (COUNT(parent.id) - (branch.depth + 1)) AS depth'
					.' FROM #__jseblod_cck_types_categories AS s,'
					.' #__jseblod_cck_types_categories AS parent,'
					.' #__jseblod_cck_types_categories AS subparent,'
					.' ('
						.' SELECT s.id, (COUNT(parent.id) - 1) AS depth'
						.' FROM #__jseblod_cck_types_categories AS s,'
						.' #__jseblod_cck_types_categories AS parent'
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
	
	/**
	 * Delete Record(s)
	 **/
	function delete( $artid )
	{
		global $mainframe;
		
		if ( $artid ) {		
			$query	= 'DELETE c.*, u.* FROM #__content c LEFT JOIN #__jseblod_cck_users u ON c.id=u.contentid WHERE u.contentid ='.(int)$artid;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			
			// Indexed (as Key)
			$fields	=	CCK_DB_ResultArray( 'SELECT name FROM #__jseblod_cck_items WHERE indexedkey = 1' );
			if ( sizeof( $fields ) ) {
				foreach( $fields as $field ) {
					CCK::INDEX_deleteIndexed_Key( $field, $artid );
				}
			}
			// Indexed
			$fields	=	CCK_DB_ResultArray( 'SELECT name FROM #__jseblod_cck_items WHERE indexed = 1' );
			if ( sizeof( $fields ) ) {
				foreach( $fields as $field ) {
					CCK::INDEX_deleteIndexed( $field, $artid );
				}
			}
		}
		
		return true;
	}
	
	/**
	 * Delete Record(s)
	 **/
	function deleteAjax( $artids )
	{
		// Users Link
		$query	=	'DELETE s.* FROM #__jseblod_cck_users AS s WHERE s.contentid IN ( '.$artids.' )';
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			return false;
		}
		// Indexed (as Key)
		$fields	=	CCK_DB_ResultArray( 'SELECT name FROM #__jseblod_cck_items WHERE indexedkey = 1' );
		if ( sizeof( $fields ) ) {
			foreach( $fields as $field ) {
				CCK::INDEX_deleteIndexed_Key( $field, $artids );
			}
		}
		// Indexed
		$fields	=	CCK_DB_ResultArray( 'SELECT name FROM #__jseblod_cck_items WHERE indexed = 1' );
		if ( sizeof( $fields ) ) {
			foreach( $fields as $field ) {
				CCK::INDEX_deleteIndexed( $field, $artids );
			}
		}
		//
		if ( CCK_LANG_Enable() ) {
			$query	=	'DELETE s.* FROM #__jf_content AS s WHERE s.reference_table="content" AND s.reference_id IN ( '.$artids.' )';
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				return false;
			}	
		}
		
		return true;
	}
	
	/**
	 * Delete Record(s)
	 **/
	function deleteAjaxCategory( $catids )
	{
		// Indexed
		$fields	=	CCK_DB_ResultArray( 'SELECT name FROM #__jseblod_cck_items WHERE indexedkey = 1' );
		if ( sizeof( $fields ) ) {
			foreach( $fields as $field ) {
				CCK::INDEX_deleteIndexed_Key( $field, $catids );
			}
		}
		//
		if ( CCK_LANG_Enable() ) {
			$query	=	'DELETE s.* FROM #__jf_content AS s WHERE s.reference_table="categories" AND s.reference_id IN ( '.$catids.' )';
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				return false;
			}	
		}
		
		return true;
	}
}
?>