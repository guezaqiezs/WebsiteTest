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
class CCKjSeblodModelType extends JModel
{
	/**
	 * Vars
	 **/
	var $_data		=	null;
	var $_template	=	null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
		
		$this->setValues();
	}

	/**
	 * Set Values
	 **/
	function setValues()
	{
		// Set Values
		$this->_data	=	null;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getData()
	{
		if ( empty( $this->_data ) )
		{
			$where		=	$this->_buildContentWhere();
			$orderby	=	$this->_buildContentOrderBy();
				
			$query	= 'SELECT s.*, cc.title AS categorytitle, '
					. 'CASE WHEN scc.bool2 = 0 THEN "'.JText::_( 'ARTICLE SUBMISSION' ).'"'
						. ' WHEN scc.bool2 = 1 THEN "'.JText::_( 'CATEGORY SUBMISSION' ).'"'
						. ' WHEN scc.bool2 = 2 THEN "'.JText::_( 'USER REGISTRATION' ).'"'
						. ' WHEN scc.bool2 = 4 THEN "'.JText::_( 'USER CONTENT' ).'"'
						. ' ELSE "Unknown" END as action_mode'
					. ' FROM #__jseblod_cck_types AS s'
					. ' LEFT JOIN #__jseblod_cck_types_categories AS cc ON cc.id = s.category'
					. ' LEFT JOIN #__jseblod_cck_type_item AS sc ON sc.typeid = s.id'
					. ' LEFT JOIN #__jseblod_cck_items AS scc ON scc.id = sc.itemid'
					. $where
					. $orderby
					;
			$this->_db->setQuery( $query );
			$this->_data	=	$this->_db->loadObjectList();
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

		$where	=	( $params->get( 'show_unpublished', 0 ) ) ? ' WHERE s.published >= 0' : ' WHERE s.published = 1';

		$where	.=	' AND sc.client = "site" AND scc.type = 25';
	
		if ( $params->get( 'action_mode', '' ) != '' ) {
			$action	=	$params->get( 'action_mode', '' );
			$where	.=	' AND scc.bool2 = '.$action;
		} else {
			$where	.=	' AND scc.bool2 != 3';
		}

		if ( $params->get( 'category_id', 0 ) ) {
			$catid	=	$params->get( 'category_id', 0 );
			$where	.=	' AND s.category = '.(int)$catid;
		}
		
		return $where;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _buildContentOrderBy()
	{
		global $mainframe;
		$params	=&	$mainframe->getParams();
		
		$orderby	= ' ORDER BY ';
		$order		=	$params->get( 'orderby_sec', 'alpha' );
		
		if ( $order == 'ralpha' ) {
			$orderby	.=	's.title DESC';
		} else {
			$orderby	.=	's.title ASC';
		}
			
		return $orderby;
	}
	
	function checkIfUserOnAdmin( $userId )
	{
		if ( $userId ) {
			$query	= 'SELECT COUNT(s.session_id)'
					. ' FROM #__session AS s'
					. ' WHERE s.client_id = 1 AND s.userid = '.$userId
					;
			$this->_db->setQuery( $query );
			$onAdmin	=	$this->_db->loadResult();
		}
		
		return $onAdmin;
	}
	
	function getActionAttribs( $formName )
	{
		$msg	=	null;
		$cckId	=	JRequest::getInt( 'id', 0, 'POST' );
		
		if ( $formName ) {
			$query	= 'SELECT s.message, s.message2, s.style, s.bool3, s.url'
					. ' FROM #__jseblod_cck_items AS s'
					. ' WHERE s.name = "'.$formName.'"'
					;
			$this->_db->setQuery( $query );
			$actionObj	=	$this->_db->loadObject();
			if ( ! $actionObj->message2 ) {
				$actionObj->message2	=	$actionObj->message;
			}
			if ( $cckId ) {
				$actionObj->message	=	$actionObj->message2;
			}
		}
		
		return $actionObj;
	}
	
	/**
	 * Store
	 **/
	function store( $actionMode )
	{
		global $mainframe;
		$lang   =& JFactory::getLanguage();
		$userC 	=&	JFactory::getUser();
		
		$data	=	JRequest::get( 'post' );
		$form	=	JRequest::getVar( 'jcontentform', array(), 'post', 'array');
		$exclusion		=	JRequest::getVar( 'jcontentexcluded' );

		$cck			=	1;
		$cckId			=	( @$data['id'] ) ? $data['id'] : 0;
		$client			=	'site';
		$nEmails		=	0;
		$batchEmails	=	array();
		$nMenus			=	0;
		$batchMenus		=	array();
		$nPlugins		=	0;
		$batchPlugins	=	array();
		$nUploads		=	0;
		$batchUploads	=	array();
		$activationCode	=	'';
		define( '_LANG_SEPARATOR',	( $lang->getTag() == 'fr-FR' ) ? ' : ' : ': ' );
		$contentType	=	CCKjSeblodItem_Form::getContentTypeName( $data['typeid'] );
		$items			=	CCKjSeblodItem_Form::getItems( $data['typeid'], $client, '', true );
		$items2nd		=	CCKjSeblodItem_Form::getItems( $data['typeid'], 'admin', $exclusion, true );
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'tables' );
		JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );

		if ( $actionMode == 2 ) {
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_registration.php' );
			if ( ! @$newUser['userid'] ) {
				return false;
			}
		}
		
		if ( $cckId ) {
			$query	=	( $actionMode == 1 ) ? 'SELECT created_user_id FROM #__categories WHERE id='.$cckId : 'SELECT created_by FROM #__content WHERE id='.$cckId;
			$author	=	CCK_DB_Result( $query );
		}
		define( '_USER_CURRENT',	( $author ) ? ( $author ) : ( ( @$newUser['userid'] ) ? @$newUser['userid'] : @$userC->id ) );
		$textObj		=	CCKjSeblodItem_Store::getData( $contentType, $items, $data, $cckId, $cck, $actionMode, $client, $items2nd );
		
		// Groups[$i][fieldname]->value
		if ( sizeof( $textObj->objGroups ) ) {
			foreach ( $textObj->objGroups as $objGroup_key => $objGroup_val ) {
				$items[$objGroup_key]	=	$objGroup_val;
			}
		}
		// Free Code
		if ( $textObj->nCodes && sizeof( $textObj->batchCodes ) ) {
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_free_code.php' );
		}
		//
		if ( $textObj->nPlugins && sizeof( $textObj->batchPlugins ) ) {
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_contentplugin.php' );
		}
		if ( $actionMode == 1 ) {
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_submission_cat.php' );			
		} else {
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_submission.php' );
		}
		/*if ( CCK_LANG_Enable() && ! $cckId && $row->id ) { //a voir !
			CCK_DB_Delete( 'DELETE s.* FROM #__jf_content AS s WHERE s.reference_table="content" AND s.reference_id='.(int)$row->id );
			if ( @$newUser['userid'] ) {
				CCK_DB_Delete( 'DELETE s.* FROM #__jf_content AS s WHERE s.reference_table="users" AND s.reference_id='.(int)$newUser['userid'] );
			}
		}*/
		// Indexed
		if ( $textObj->indexedkey ) {
			$query	= 'INSERT IGNORE INTO #__jseblod_cck_extra_index_key_'.$textObj->indexedkey['name'].' ( id, keyid )'
				   	. ' VALUES ('.$textObj->item_id.', "'.$textObj->indexedkey['id'].'")'
            		. ' ON DUPLICATE KEY UPDATE id = '.$textObj->item_id.', keyid = "'.$textObj->indexedkey['id'].'"'
					 ;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				//return false;
			}
		}
		// External Idx
		if ( $textObj->nIndexed && sizeof( $textObj->batchIndexed ) ) {
			$deleted	=	array();
			for ( $i = 0; $i < $textObj->nIndexed; $i++ ) {
				if ( ! @$deleted[$textObj->batchIndexed[$i]['name']] ) {
					$query	= 'DELETE s.* FROM #__jseblod_cck_extra_index_'.$textObj->batchIndexed[$i]['name'].' AS s WHERE s.id = '.$textObj->item_id;
					$this->_db->setQuery( $query );
					$this->_db->query();
					$deleted[$textObj->batchIndexed[$i]['name']]	=	true;
				}
				$query	= 'INSERT IGNORE INTO #__jseblod_cck_extra_index_'.$textObj->batchIndexed[$i]['name'].' ( id, indexid )'
					   	. ' VALUES ('.$textObj->item_id.', "'.$textObj->batchIndexed[$i]['id'].'")'
            			. ' ON DUPLICATE KEY UPDATE id = '.$textObj->item_id.', indexid = "'.$textObj->batchIndexed[$i]['id'].'"'
						 ;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					//return false;
				}
			}
		}
		//
		if ( $actionMode == 2 || $actionMode == 4 ) {
			$newUser['contentid']		=	$row->id;
			$newUser['userid']			=	( $newUser['userid'] != 0 ) ? $newUser['userid'] : $form['created_by'];
			$newUser['type']			=	$contentType;
			$newUser['registration']	=	( $actionMode == 2 ) ? 1 : 0;
			$CCKUser	=&	JTable::getInstance( 'users', 'Table' );
			$CCKUser->bind( $newUser );
			$CCKUser->setType( $newUser['contentid'], $newUser['type'] );
			$CCKUser->store();
		}
		
		if ( $textObj->nUploads && sizeof( $textObj->batchUploads ) ) {
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_upload.php' );
		}
		if ( $textObj->nMenus && sizeof( $textObj->batchMenus ) ) {
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_createmenu.php' );
		}
		if ( $textObj->nEmails && sizeof( $textObj->batchEmails ) ) {
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_sendemail.php' );
		}
		
		return $row->id;
	}
}
?>