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
 * Article		Model Class
 **/
class CCKjSeblodModelArticle extends JModel
{
	/**
	 * Vars
	 **/
	var $_data		=	null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
		
		$user		=&	JFactory::getUser();
		
		$userId		=	$user->get('id');
		$userGId	=	$user->get('gid');
		$this->setValues( $userId, $userGId );
	}

	/**
	 * Set Values
	 **/
	function setValues( $userId, $userGId )
	{
		// Set Values
		$this->_data	=	null;
		$this->_userId	=	$userId;
		
		$this->_userGId	=	$userGId;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getData()
	{
		if ( empty( $this->_data ) )
		{
			global $mainframe;
			$params	=&	$mainframe->getParams();
				
			//if ( $params->get( 'category_id', 0 ) ) {
			$all_authors		=	$params->get( 'enable_all_authors', 0 );
			$all_authors_access	=	$params->get( 'enable_all_authors_access', 18 );
			if ( $this->_userId && ( ! $all_authors || ( $all_authors && $all_authors_access && $this->_userGId >= $all_authors_access ) ) ) {
				global $mainframe;
				
				$where		=	$this->_buildContentWhere();
				$orderby	=	$this->_buildContentOrderBy();
				
				$query	= 'SELECT cc.title AS category, a.id, a.title, a.alias, a.sectionid, a.state, a.catid, a.introtext, a.created, a.created_by, a.hits, a.checked_out, a.checked_out_time, u.name AS author, u.name AS editor, cc.published AS cat_state, '
						. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'
						. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'
						. ' FROM #__content AS a'
						. ' LEFT JOIN #__categories AS cc ON cc.id = a.catid'
						. ' LEFT JOIN #__users AS u ON u.id = a.created_by'
						. $where
						. $orderby
						;
				$this->_db->setQuery( $query );
				$this->_data	=	$this->_db->loadObjectList();
				
				$contentTypeId	=	null;
				$regex			=	"#"._OPENING."jseblod"._CLOSING."(.*?)"._OPENING."/jseblod"._CLOSING."#s";
				
				if ( sizeof ( $this->_data ) ) {
					foreach ( $this->_data as $item ) {
						preg_match_all( $regex, $item->introtext, $contentMatches );	
						$contentType	=	@$contentMatches[1][0];
						$query	= 'SELECT s.id'
							. ' FROM #__jseblod_cck_types AS s'
							. ' WHERE s.name = "'.$contentType.'"'
							;
						$this->_db->setQuery( $query );
						$contentTypeId	=	$this->_db->loadResult();
						if ( ! $contentTypeId ) {
							$query	= 'SELECT cc.id'
								. ' FROM #__jseblod_cck_type_cat AS s'
								. ' LEFT JOIN #__jseblod_cck_types AS cc ON cc.id = s.typeid'
								. ' WHERE s.catid = '.$item->catid
								;
							$this->_db->setQuery( $query );
							$contentTypeId	=	$this->_db->loadResult();
						}
						$item->content_typeid	=	( @$contentTypeId ) ? $contentTypeId : null;
					}
				}
			}
		}
		
		return $this->_data;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _buildContentWhere()
	{
		global $mainframe;
		$params	=&	$mainframe->getParams();
				
		if ( $params->get( 'category_id', 0 ) ) {
			$catid	=	$params->get( 'category_id', 0 );
			//if ( $params->get( 'include_subcategories', 0 ) ) {
				// TODO check article from every child + selected
			//	$where_category	=	'';
			//} else {
				$where_category	=	' AND a.catid = '.(int)$catid;
			//}
		} else {
			$where_category	=	'';
		}
		
		$where_state	=	'';
		$where_state	=	( $params->get( 'show_published', 1 ) ) ? 'a.state=1' : '';
		$where_state	.=	( $params->get( 'show_unpublished', 0 ) ) ? ( ( $where_state ) ? ' OR a.state=0' : 'a.state=0' ) : '';
		$where_state	.=	( $params->get( 'show_archived', 0 ) ) ? ( ( $where_state ) ? ' OR a.state=-1' : 'a.state=-1' ) : '';
		$where_state	=	( $where_state ) ? '('.$where_state.')' : 'a.state=-2';
		
		$where			=	' WHERE '.$where_state.$where_category;
		if ( ! $params->get( 'enable_all_authors', 0 ) ) {
			$where		.=	' AND a.created_by = '.$this->_userId;
		}
		$where_excluded	=	$this->_buildContentWhereExcluded();
		if ( $where_excluded ) {
			$where .= ' AND a.id NOT IN ('.$where_excluded.')';
		}
		
		return $where;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _buildContentWhereExcluded()
	{
		$query	= 'SELECT contentid FROM #__jseblod_cck_users'
				. ' WHERE registration=1 AND userid = '.(int)$this->_userId
				;
		$this->_db->setQuery( $query );
		$excluded	=	$this->_db->loadResultArray();
		
		if ( is_array( $excluded ) ) {
			$excluded	=	implode( ',', $excluded );
		}
		
		return $excluded;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _buildContentOrderBy()
	{
		global $mainframe;
		$params	=&	$mainframe->getParams();
		require_once ( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'query.php');
		
		$orderby	= ' ORDER BY ';
		$order		=	$params->get( 'orderby_sec', 'alpha' );
		$orderby	.=	ContentHelperQuery::orderbySecondary( $order );
		
		return $orderby;
	}
	
		
	/**
	 * Publish / Unpublish
	 **/
	function publish( $cid = array(), $publish = 1 )
	{
		global $mainframe;
		
		if ( count( $cid ) ) {
			JArrayHelper::toInteger( $cid );
			$cids	=	implode( ',', $cid );
				
			$query	= 'UPDATE #__content'
					. ' SET state = '.(int)$publish
					. ' WHERE id IN ( '.$cids.' )'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Delete
	 **/
	function trash()
	{
		global $mainframe;
		JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
				
		$cids	=	JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row	=&	JTable::getInstance( 'content', 'JTable' );
		
		if ( $n = count( $cids ) )
		{
			foreach($cids as $cid) {
				if ( ! $row->delete( $cid ) ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
			}						
		}
		
		return $n;
	}
}
?>