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
 * Content Type		Model Class
 **/
class CCKjSeblodModelUser extends JModel
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
		
		$this->setValues( $userId );
	}

	/**
	 * Set Values
	 **/
	function setValues( $userId )
	{
		// Set Values
		$this->_data		=	null;
		$this->_dataForm	=	null;
		$this->_dataUser	=	null;
		$this->_dataDefault	=	null;
		
		$this->_userId		=	$userId;
	}
	
	/********************************************************************
	 *************************** USER'S FORM ****************************
	 ********************************************************************/
	
	function &getDataForm()
	{
		if ( empty( $this->_dataForm ) )
		{
			$where		=	$this->_buildQueryWhereDefault();
			$orderby	=	$this->_buildQueryOrderByDefault();
			
			$query	= 'SELECT s.contentid, cc.id AS typeid'
					. ' FROM #__jseblod_cck_users AS s'
					. ' LEFT JOIN #__jseblod_cck_types AS cc ON cc.name = s.type'
					. ' WHERE s.userid ='.$this->_userId.' AND s.registration = 1'
					;
				$this->_db->setQuery( $query );
				$this->_dataForm	=	$this->_db->loadObject();
		}	
		
		return $this->_dataForm;
	}
	
	/********************************************************************
	 ************************* USER'S HOMEPAGE **************************
	 ********************************************************************/
		 
	/********************************************************************
	 ************************** USER'S CONTENT **************************
	 ********************************************************************/
	 
	 /**
	 * Get Data from Database
	 **/
	function &getDataUser()
	{
		if ( empty( $this->_dataUser ) )
		{
			if ( $this->_userId ) {
				global $mainframe;
				
				$where		=	$this->_buildQueryWhereUser();
				$orderby	=	$this->_buildQueryOrderByUser();
				
				$query	= 'SELECT cc.title AS category, a.id, a.title, a.alias, a.sectionid, a.state, a.catid, a.introtext, a.created, a.created_by, a.hits, a.checked_out, a.checked_out_time, u.name AS author, u.name AS editor, cc.published AS cat_state, scc.id AS content_typeid, '
						. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'
						. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'
						. ' FROM #__content AS a'
						. ' LEFT JOIN #__categories AS cc ON cc.id = a.catid'
						. ' LEFT JOIN #__users AS u ON u.id = a.created_by'
						. ' LEFT JOIN #__jseblod_cck_users AS sc ON sc.contentid = a.id'
						. ' LEFT JOIN #__jseblod_cck_types AS scc ON scc.name = sc.type'
						. $where
						. $orderby
						;
				$this->_db->setQuery( $query );
				$this->_dataUser	=	$this->_db->loadObjectList();
			}
		}
		
		return $this->_dataUser;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _buildQueryWhereUser()
	{
		global $mainframe;
		$params	=&	$mainframe->getParams();
		
		$typeid	=	$params->get( 'typeid', 0 );
		if ( $params->get( 'category_id', 0 ) ) {
			$catid	=	$params->get( 'category_id', 0 );
			$where_category	=	' AND a.catid = '.(int)$catid;
		} else {
			$where_category	=	'';
		}
		
		$where_state	=	( $params->get( 'show_unpublished', 0 ) ) ? 'a.state >= 0' : 'a.state = 1';
		$where			=	' WHERE '.$where_state.$where_category.' AND sc.userid = '.$this->_userId.' AND sc.registration != 1';
		if ( $typeid && $typeid != 0 ) {
			$where		.=	' AND scc.id ='.$typeid;
		}
		
		return $where;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _buildQueryOrderByUser()
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
				$query	= 'DELETE s.* FROM #__jseblod_cck_users AS s WHERE s.contentid ='.(int)$cid;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					return false;
				}
			}						
		}
		
		return $n;
	}
	 
	 /********************************************************************
	 **************************** USER LIST ******************************
	 ********************************************************************/
	 
 	/**
	 * Get Data from Database
	 **/
	function &getDataDefault()
	{
		if ( empty( $this->_dataDefault ) )
		{
			$where		=	$this->_buildQueryWhereDefault();
			$orderby	=	$this->_buildQueryOrderByDefault();
			
			$query	= 'SELECT u.id, u.name, u.username, u.usertype, u.email, u.block, cckt.id as content_typeid, cc.title AS category, a.id as contentid, a.alias, a.sectionid, a.state, a.catid, a.introtext, a.created, a.created_by, a.hits, a.checked_out, a.checked_out_time, u.name AS author, u.name AS editor, cc.published AS cat_state, '
					. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'
					. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'
					. ' FROM #__users AS u'
					. ' LEFT JOIN #__jseblod_cck_users AS ccku ON ccku.userid = u.id'
					. ' LEFT JOIN #__content AS a ON a.id = ccku.contentid'
					. ' LEFT JOIN #__jseblod_cck_types AS cckt ON cckt.name = ccku.type'
					. ' LEFT JOIN #__categories AS cc ON cc.id = a.catid'
					. $where
					. $orderby
					;
				$this->_db->setQuery( $query );
				$this->_dataDefault	=	$this->_db->loadObjectList();
		}
		
		return $this->_dataDefault;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _buildQueryWhereDefault()
	{
		global $mainframe;
		$params	=&	$mainframe->getParams();

		$where	=	( $params->get( 'show_blocked', 0 ) ) ? ' WHERE u.block >= 0' : ' WHERE u.block = 0';
		
		$where	.=	' AND ccku.registration = 1';
	
		if ( $params->get( 'usergroup', 29 ) ) {
			$userGroup	=	$params->get( 'usergroup', 29 );
			if ( $userGroup == 29 ) {
				$where	.=	' AND u.gid <= 21';
			} else if ( $userGroup == 30 ) {
				$where	.=	' AND u.gid >= 23 AND u.gid <= 25';
			} else {
				$where	.=	' AND u.gid = '.(int)$userGroup;
			}
		}
		
		if ( $params->get( 'category_id', 0 ) ) {
			$catid	=	$params->get( 'category_id', 0 );
			$where	.=	' AND a.catid = '.(int)$catid;
		}
		
		return $where;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _buildQueryOrderByDefault()
	{
		global $mainframe;
		$params	=&	$mainframe->getParams();
		
		$orderby	= ' ORDER BY ';
		$order		=	$params->get( 'orderby_sec', 'alpha' );
		
		if ( $order == 'ralpha' ) {
			$orderby	.=	'u.name DESC';
		} else {
			$orderby	.=	'u.name ASC';
		}
			
		return $orderby;
	}
	
	/**
	 * Enable / Block
	 **/
	function enable( $cid = array(), $state = 0 )
	{
		global $mainframe;
		
		if ( count( $cid ) ) {
			JArrayHelper::toInteger( $cid );
			$cids	=	implode( ',', $cid );
				
			$query	= 'UPDATE #__users'
					. ' SET block = '.(int)$state
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
	function remove()
	{
		global $mainframe;
		JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
				
		$cids	=	JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row	=&	JTable::getInstance( 'user', 'JTable' );
		
		if ( $n = count( $cids ) )
		{
			foreach($cids as $cid) {
				if ( ! $row->delete( $cid ) ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
				$query	= 'DELETE s.* FROM #__jseblod_cck_users AS s WHERE s.userid ='.(int)$cid;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					return false;
				}
			}						
		}
		
		return $n;
	}
	
	 /********************************************************************
	 *************************** ACTIONVATION ****************************
	 ********************************************************************/
	
	/**
	 * User Activate
	 **/
	function activate( $code )
	{
		if ( $code ) {
			$query	= 'SELECT id'
					. ' FROM #__users'
					. ' WHERE activation = '.$this->_db->Quote( $code )
					. ' AND block = 1'
					. ' AND lastvisitDate = '.$this->_db->Quote( '0000-00-00 00:00:00' );
					;
			$this->_db->setQuery( $query );
			$id	=	intval( $this->_db->loadResult() );
	
			if ( $id ) {
				JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
				$user	=&	JUser::getInstance( (int)$id );
				$user->set( 'block', '0' );
				if ( ! $user->save() ) {
					JError::raiseWarning( "An error has occurred", $user->getError() );
					return false;
				}
				return true;
			} else {
				return false;
			}
		}
		
		return false;
	}
}
?>