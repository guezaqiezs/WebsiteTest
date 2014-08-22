<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

/**
 * Interface_Content		Model Class
 **/
class CCKjSeblodModelInterface_Content extends JModel
{
	/**
	 * Vars
	 **/
	var $_data			= null;
	var $_artId			= null;
	var $_catId			= null;
	var $_article		= null;
	var $_item			= null;
	
	/**
	 * Constructor | Get ID from Request
	 **/
	function __construct()
	{
		parent::__construct();
		
		$array = JRequest::getVar( 'cid',  0, '', 'array' );
		$this->setValues( (int)$array[0] );
	}

	/**
	 * Set Values
	 **/
	function setValues( $id )
	{
		// Set Values
		$this->_id		= $id;
		$this->_data	= null;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getData( $contentType )
	{
		if ( empty( $this->_data ) )
		{
			if ( $contentType ) {
				$query	= 'SELECT s.*'
						. ' FROM #__jseblod_cck_types AS s'
						. ' LEFT JOIN #__jseblod_cck_types_categories AS cc ON cc.id = s.category'
						. ' WHERE s.name = "'.$contentType.'" AND s.published = 1'
						;
				$this->_db->setQuery( $query );
				$this->_data	=	$this->_db->loadObject();
			}
		}
		
		return $this->_data;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getArticle( $artId )
	{
		if ( empty( $this->_article ) )
		{
			if ( $artId )
			{
				$query = ' SELECT * FROM #__content'
						.' WHERE id = '.$artId;
				$this->_db->setQuery( $query );
				$this->_article = $this->_db->loadObject();
			}
		}
		
		return $this->_article;
	}

	/**
	 * Get Data from Database
	 **/
	function getCategory( $cckId )
	{
		if ( empty( $this->_category ) )
		{
			if ( $cckId )
			{
				$query = ' SELECT * FROM #__categories'
						.' WHERE id = '.$cckId;
				$this->_db->setQuery( $query );
				$this->_category = $this->_db->loadObject();
			}
		}
		
		return $this->_category;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getContentItem( $itemId )
	{
		$where = ' WHERE s.id = '.$itemId;
		
		$query = ' SELECT sc.name AS typename, s.*'
				.' FROM #__jseblod_cck_items AS s'
				.' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
				. $where
				;
		$this->_db->setQuery( $query );
		$this->_item = $this->_db->loadObject();
		
		return $this->_item;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getContentType( $itemId )
	{
		$where = ' WHERE ccc.id = '.$itemId;
		$orderby = ' ORDER BY cc.ordering';
		
		$query = ' SELECT sc.name AS typename, s.*'
				.' FROM #__jseblod_cck_items AS s'
				.' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
				.' LEFT JOIN #__jseblod_cck_type_item AS cc ON cc.itemid = s.id'
				.' LEFT JOIN #__jseblod_cck_types AS ccc ON ccc.id = cc.typeid'
				. $where
				. $orderby
				;
		$this->_db->setQuery( $query );
		$this->_contentTypeList = $this->_db->loadObjectList();
		
		return $this->_contentTypeList;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getContentTypeData( $contentType )
	{
		if ( empty( $this->_contentTypeTitle ) )
		{
			$query = 'SELECT id, title'
					.' FROM #__jseblod_cck_types'
					.' WHERE name = "'.$contentType.'" AND s.published = 1'
					;
			$this->_db->setQuery( $query );
			$this->_contentTypeData = $this->_db->loadObject();
		}
		
		return $this->_contentTypeData;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getContentTypeTitle( $contentTypeId )
	{
		$query = 'SELECT title'
				.' FROM #__jseblod_cck_types'
				.' WHERE id = '.$contentTypeId
				;
		$this->_db->setQuery( $query );
		$contentType = $this->_db->loadObject();
		
		return $contentType;
	}
	
	function &doStore( $actionMode )
	{
		global $mainframe;
		$lang   =& JFactory::getLanguage();
		$userC 	=&	JFactory::getUser();
		
		$data = JRequest::get( 'post' );
		
		$cck			=	0;
		$cckId			=	( @$data['id'] ) ? $data['id'] : 0;
		
		$nUploads		=	0;
		$batchUploads	=	array();
		
		define( '_LANG_SEPARATOR',	( $lang->getTag() == 'fr-FR' ) ? ' : ' : ': ' );
		$contentType	=	CCKjSeblodItem_Form::getContentTypeName( $data['contenttype'] );
		$items 			=	CCKjSeblodItem_Form::getItems( $data['contenttype'], 'admin', '', true );
		$items2nd		=	array();
		
		$jcontent		=	JRequest::getVar( 'jcontent', array(), 'post', 'array');
		if ( @$jcontent['title'] ) {
			if( empty( $jcontent['alias'] ) ) {
				$jcontent['alias'] = $jcontent['title'];
			}
			$jcontent['alias'] = JFilterOutput::stringURLSafe( $jcontent['alias'] );
			
			if(trim(str_replace('-','',$jcontent['alias'])) == '') {
				$datenow =& JFactory::getDate();
				$jcontent['alias'] = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
			}
		}
		if ( $actionMode != 1 ) {
			$jcontentdetails	=	JRequest::getVar( 'jcontentdetails', array(), 'post', 'array');
			$jcontentparams		=	JRequest::getVar( 'jcontentparams', array(), 'post', 'array');
			$jcontentmeta		=	JRequest::getVar( 'jcontentmeta', array(), 'post', 'array');
		}
		
		// TODO si cckid get author de larticle et seter sinon new user si exist sinon user courrant
		define( '_USER_CURRENT',	( @$newUser['userid'] ) ? @$newUser['userid'] : @$userC->id );
		$textObj		=	CCKjSeblodItem_Store::getData( $contentType, $items, $data, $cckId, $cck, $actionMode, 'admin', $items2nd );
		
		$content				=	new stdClass();
		$content->text			=	$textObj->text;
		
		$content->common_key	=	implode( '::', array_keys( $jcontent ) );
		$content->common_val	=	implode( '::', array_values( $jcontent ) );
		if ( $actionMode != 1 ) {
			$content->details_key	=	implode( '::', array_keys( $jcontentdetails ) );
			$content->details_val	=	implode( '::', array_values( $jcontentdetails ) );
			$content->params_key	=	implode( '::', array_keys( $jcontentparams ) );
			$content->params_val	=	implode( '::', array_values( $jcontentparams ) );
			$content->meta_key		=	implode( '::', array_keys( $jcontentmeta ) );
			$content->meta_val		=	implode( '::', array_values( $jcontentmeta ) );
		}
		
		// Menu??
		// Email?? TODO
		// Specific (Cat?) TODO
		// SendEmails TODO
		if ( $textObj->nUploads && sizeof( $textObj->batchUploads ) ) {
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_upload.php' );
		}
		
		return $content;
	}
	
	/**
	 * Store
	 **/
	function store( $actionMode )
	{
		global $mainframe;
		$lang   =& JFactory::getLanguage();
		$userC 	=&	JFactory::getUser();
		
		$data			=	JRequest::get( 'post' );
		$form			=	JRequest::getVar( 'jcontentform', array(), 'post', 'array');
		$exclusion		=	JRequest::getVar( 'jcontentexcluded' );
		$lang_id		=	JRequest::getVar( 'lang_id' );
		
		$cck			=	1;
		$cckId			=	( @$data['id'] ) ? $data['id'] : 0;
		$client			=	'admin';
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
		$contentType	=	CCKjSeblodItem_Form::getContentTypeName( $data['contenttype'] );
		$items 			=	CCKjSeblodItem_Form::getItems( $data['contenttype'], $client, '', true );
		$items2nd		=	CCKjSeblodItem_Form::getItems( $data['contenttype'], 'site', $exclusion, true );		
		JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
		
		if ( $actionMode == 2 ) {
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_registration.php' );
			if ( ! $newUser['userid'] ) {
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
			if ( ! $actionMode && $lang_id ) {
				//JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'tables' );
				require( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'tables'.DS.'JFContent.php' );
				include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_translation.php' );
			} else {
				include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_submission.php' );
				if ( ! $actionMode && CCK_LANG_Enable() ) {
					$langs	=	JRequest::getVar( 'jseblod_jfarttranslations' );
					//JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'tables' );
					require( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'tables'.DS.'JFContent.php' );
					include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_translation.php' );
				}
			}
		}
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
		// Indexed		
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