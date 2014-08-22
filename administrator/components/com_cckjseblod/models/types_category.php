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
 * Types_Category	Model Class
 **/
class CCKjSeblodModelTypes_Category extends JModel
{
	/**
	 * Vars
	 **/
	var $_id		=	null;
	var $_data		=	null;
	var $_parent	=	null;

	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
		
		$array	=	JRequest::getVar( 'cid',  0, '', 'array' );
		$this->setValues( (int)$array[0] );
	}

	/**
	 * Set Values
	 **/
	function setValues( $id )
	{
		// Set Values
		$this->_id		=	$id;
		$this->_data	=	null;
		$this->_parent	=	null;
	}

	/**
	 * Get Data from Database
	 **/
	function &getData()
	{
		if ( empty( $this->_data ) ) {
			$row	=&	$this->getTable( 'types_categories' );
			
			if ( $this->_id ) {
				$row->load( $this->_id );
				if ( ! $row->checked_out ) {
					$user =& JFactory::getUser();
					// Checkout!
					$row->checkout( $user->get('id') );
				}
			}
			$this->_data	=&	$row;
		}
		
		return $this->_data;
	}

	/**
	 * Get Data from Database
	 **/
	function getParent( $id )
	{
		$where		=	' WHERE ( s.lft BETWEEN parent.lft AND parent.rgt ) AND s.id != parent.id AND s.id ='.$id;
		$orderby	=	' ORDER BY parent.lft DESC';
				
		$query 	= 'SELECT parent.id'
				. ' FROM #__jseblod_cck_types_categories AS s, #__jseblod_cck_types_categories AS parent'
				. $where
				. $orderby
				;
		$this->_db->setQuery( $query );
		$this->_parent	=	$this->_db->loadResult();
		
		return $this->_parent;
	}

	/**
	 * Return Database Query
	 **/
	function _buildQuery( $parentid )
	{
		$query  = 'SELECT s.name, (COUNT(parent.name) - (sub_tree.depth + 1)) AS depth'
				. ' FROM #__jseblod_cck_types_categories AS s,'
				. ' #__jseblod_cck_types_categories AS parent,'
				. ' #__jseblod_cck_types_categories AS sub_parent,'
				. ' ('
		            . ' SELECT s.name, (COUNT(parent.name) - 1) AS depth'
		            . ' FROM #__jseblod_cck_types_categories AS s,'
		            . ' #__jseblod_cck_types_categories AS parent'
		            . ' WHERE s.lft BETWEEN parent.lft AND parent.rgt'
		            . ' AND s.id = '.$parentid
		            . ' GROUP BY s.name'
		            . ' ORDER BY s.lft'
					. ' ) AS sub_tree'
				. ' WHERE s.lft BETWEEN parent.lft AND parent.rgt'
				. ' AND s.lft BETWEEN sub_parent.lft AND sub_parent.rgt'
				. ' AND sub_parent.name = sub_tree.name'
				. ' GROUP BY s.name'
				. ' HAVING depth <= 1'
				. ' ORDER BY s.lft'
				;
      
		return $query;
	}

	/**
	 * Store Record(s)
	 **/
	function store()
	{
		$row	=&	$this->getTable('types_categories');
		$data	=	JRequest::get( 'post' );
		
		$data['title']			=	trim( $data['title'] );
		$data['description']	=	( $data['description_updated'] == 1 ) ? JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW )
		: ( ( ! $data['id'] ) ? HelperjSeblod_Helper::getWysiwygContent( 'description', 'types_categories', $data['cid'][0] ) : HelperjSeblod_Helper::getWysiwygContent( 'description', 'types_categories', $data['id'] ) );
		
		if ( ( $data['parentid'] != $data['parentdb'] )
			&& $data['parentid'] != $data['id']  ) {
			
			$parentid	=	$data['parentid'];
			
			//LOCK TABLE #__jseblod_cck_types_categories WRITE; //todo//lock//
			$query	=	$this->_buildQuery( $parentid );
			$this->_db->setQuery( $query );
			$brothers	=	$this->_db->loadResultArray();
			$parent		=	array_shift( $brothers );
			$brothers[]	=	$data['title'];
			sort( $brothers );
			$key	=	array_search( $data['title'], $brothers );
			
			if ( $key == 0 ) {
				$query	= ' SELECT lft FROM #__jseblod_cck_types_categories'
						. ' WHERE name = "'.$parent.'"'
						;
			} else {
				$bigbrother	=	$brothers[$key - 1];
				$query	= ' SELECT rgt FROM #__jseblod_cck_types_categories'
						. ' WHERE name = "'.$bigbrother.'"'
						;
			}
			
			$this->_db->setQuery( $query );
			$limit	=	$this->_db->loadResult();
			
			$query	= 'UPDATE #__jseblod_cck_types_categories'
					. ' SET lft = CASE WHEN lft > '.$limit.' THEN lft + 2 ELSE lft END,'
					. ' rgt = CASE WHEN rgt >= '.$limit.' THEN rgt + 2 ELSE rgt END'
					. ' WHERE rgt > '.$limit
					;
				
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			$data['lft']	=	$limit + 1;
			$data['rgt']	=	$limit + 2;
		}
		
		// Bind Form Fields to Table
		if ( ! $row->bind( $data ) ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Make Sure Record is Valid
		if ( ! $row->check() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Store Web Link Table to Database
		if ( ! $row->store() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		//UNLOCK TABLES;
		
		return $row->id;
	}

	/**
	 * Live Store Record
	 **/
	function liveStore()
	{
		$liveId		=	JRequest::getInt( 'live_id' );
		$liveTitle	=	JRequest::getVar( 'live_title', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		if ( $liveId ) {
			$query	= 'UPDATE #__jseblod_cck_types_categories'
					. ' SET title = "'.$liveTitle.'"'
					. ' WHERE id = '.(int)$liveId
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return $liveId;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getRemoveData()
	{
		if ( empty( $this->_removeData ) )
		{
			$cids	=	JRequest::getVar( 'cid', array(0), 'post', 'array' );
			$inCids	=	implode( ',', $cids );
			
			$query	= 'SELECT s.id, s.title'
					. ' FROM #__jseblod_cck_types_categories AS s'
					. ' WHERE s.id IN ( '.$inCids.' )'
					;
			$this->_db->setQuery( $query );
			$this->_removeData	=	$this->_db->loadObjectList();
		}
		
		return $this->_removeData;
	}
	
	/**
	 * Cannot Delete Record(s)
	 **/
	function cannotDelete()
	{
		$cannot	=	0;
		$cids	=	JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$inCids	=	implode( ',', $cids );
		
		$query	= 'SELECT COUNT(s.id)'
				. ' FROM #__jseblod_cck_types AS s'
				. ' WHERE s.category IN ( '.$inCids.' )'
				;
			$this->_db->setQuery( $query );
			$cannot	=	$this->_db->loadResult();
		
		return $cannot;
	}
	
	/**
	 * Delete Record(s)
	 **/
	function delete( $deleteMode )
	{
		$cids	=	JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row	=&	$this->getTable( 'types_categories' );
		
		if ( $n = count( $cids ) )
		{
			foreach( $cids as $cid ) {
				
				//LOCK TABLE nested_category WRITE;
				
				$query	= 'SELECT lft, rgt, ( rgt - lft + 1 ) AS width FROM #__jseblod_cck_types_categories'
						. ' WHERE id = '.$cid
						;   
				$this->_db->setQuery( $query );
				$route	=	$this->_db->loadRow();
				
				if ( ! $row->delete( $cid ) ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
				
				if ( $deleteMode == -1 ) {
					if ( ! ( $route[1] - $route[0] == 1 ) ) {				  
						$query	= 'DELETE FROM #__jseblod_cck_types_categories'
								. ' WHERE lft BETWEEN '.$route[0].' AND '.$route[1]
								;
						$this->_db->setQuery( $query );
						if ( ! $this->_db->query() ) {
							$this->setError( $this->_db->getErrorMsg() );
							return false;
						}
					}
					$query	= 'UPDATE #__jseblod_cck_types_categories'
							. ' SET lft = CASE WHEN lft > '.$route[1].' THEN lft - '.$route[2].' ELSE lft END,'
							. ' rgt = CASE WHEN rgt > '.$route[1].' THEN rgt - '.$route[2].'  ELSE rgt END'
							. ' WHERE rgt > '.$route[1]
							;
				} else {
					$query	= 'UPDATE #__jseblod_cck_types_categories'
							. ' SET rgt = CASE WHEN lft BETWEEN '.$route[0].' AND '.$route[1].' THEN rgt - 1 ELSE rgt END,'
							. ' lft = CASE WHEN lft BETWEEN '.$route[0].' AND '.$route[1].' THEN lft - 1 ELSE lft END,'
							. ' lft = CASE WHEN lft > '.$route[1].' THEN lft - 2 ELSE lft END,'
							. ' rgt = CASE WHEN rgt > '.$route[1].' THEN rgt - 2 ELSE rgt END'
							;
				}
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
				
				//UNLOCK TABLES;
			}
		}
		
		return $n;
	}
	
	function publish( $cid = array(), $publish = 1 )
	{
		$user 	=&	JFactory::getUser();
		
		if ( count( $cid ) ) {
			JArrayHelper::toInteger( $cid );
			$cids	=	implode( ',', $cid );
				
			$query	= 'UPDATE #__jseblod_cck_types_categories'
					. ' SET published = '.(int)$publish
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
	 * Checkout Record
	 **/
	function checkout( $uid = null )
	{
		if ( $this->_id ) {
			$row	=&	$this->getTable( 'types_categories' );
			
			// Check User Id
			if ( is_null( $uid ) ) {
				$user	=&	JFactory::getUser();
				$uid	=	$user->get('id');
			}
			
			// Checkout!
			if( ! $row->checkout( $uid, $this->_id ) ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			return true;
		}
		
		return false;
	}
	
	/**
	 * Checkin Record
	 **/
	function checkin()
	{		
		if ( $this->_id ) {
			$row	=&	$this->getTable( 'types_categories' );
			
			// Checkin!
			if( ! $row->checkin( $this->_id ) ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return false;
	}
	
}
?>