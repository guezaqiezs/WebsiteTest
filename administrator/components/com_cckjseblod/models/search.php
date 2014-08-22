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
 * Search			Model Class
 **/
class CCKjSeblodModelSearch extends JModel
{
	/**
	 * Vars
	 **/
	var $_id					= null;
	var $_data					= null;
	var $_removeData			= null;
	var $_assignedFields		= null;
	var $_availableFields		= null;

	/**
	 * Constructor
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
		$this->_id						= $id;
		$this->_data					= null;
		$this->_removeData				= null;
		$this->_assignedCategories		= null;
		$this->_availableCategories		= null;
		$this->_assignedFields			= null;
		$this->_availableFields			= null;
		//$this->_selected_categories		= null;
		$this->_available_categories	= null;

	}

	/**
	 * Get Data from Database
	 **/
	function &getData()
	{
		if ( empty( $this->_data ) ) {
			$row =& $this->getTable( 'searchs' );
			
			if ( $this->_id ) {
				$row->load( $this->_id );
				if ( ! $row->checked_out ) {
					$user =& JFactory::getUser();
					// Checkout!
					$row->checkout( $user->get('id') );
				}
				$this->_data =& $row;
				$this->_data->searchtemplate 		=	( $this->_data->searchtemplate ) ? $this->_data->searchtemplate : 1;
				$this->_data->searchtemplateTitle	=	$this->_getTemplate( $this->_data->searchtemplate );
				$this->_data->contenttemplate 		=	( $this->_data->contenttemplate ) ? $this->_data->contenttemplate : CCK_DB_Result( 'SELECT s.id FROM #__jseblod_cck_templates AS s WHERE s.name = "default_list"' );
				$this->_data->contenttemplateTitle	=	$this->_getTemplate( $this->_data->contenttemplate );
				$this->_data->categorystate			=	$this->_getCategoryState( $this->_data->category );
			}
		}
		
		return $this->_data;
	}

	/**
	 * Get Data from Database
	 **/
	function _getTemplate( $id )
	{
		$where = ' WHERE s.id = '.(int)$id;
			
		$query = ' SELECT s.title'
				.' FROM #__jseblod_cck_templates AS s'
				. $where
				;
		$this->_db->setQuery( $query );
		$template = $this->_db->loadResult();
		
		return $template;
	}
	
	function _getTemplateName( $id )
	{
		$where = ' WHERE s.id = '.(int)$id;
			
		$query = ' SELECT s.name'
				.' FROM #__jseblod_cck_templates AS s'
				. $where
				;
		$this->_db->setQuery( $query );
		$template = $this->_db->loadResult();
		
		return $template;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getDefaultAction()
	{
		$where = ' WHERE s.id = 290';
			
		$query = 'SELECT s.id AS value, s.title AS text'
				.' FROM #__jseblod_cck_items AS s'
				. $where
				;
		$this->_db->setQuery( $query );
		$action = $this->_db->loadObjectList();
		
		return $action;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getSearchAction( $client )
	{
		$where = ' WHERE s.searchid = '.$this->_id.' AND cc.type = 46 AND s.client ="'.$client.'"';
		
		$query 	= 'SELECT s.itemid'
				. ' FROM #__jseblod_cck_search_item AS s'
				. ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
				. $where
				;
		$this->_db->setQuery( $query );
		$this->_searchAction = $this->_db->loadResult();
		
		return $this->_searchAction;
	}
	/**
	 * Get Data from Database
	 **/
	function &getSearchActionItems()
	{
		if ( empty( $this->_searchActionItems ) ) {
			$where = ' WHERE s.type = 46 AND s.id != 290';
			
			$query = 'SELECT s.id AS value, s.title AS text'
				   . ' FROM #__jseblod_cck_items AS s'
				   . $where
				   . ' ORDER BY s.title asc' ;
			$this->_db->setQuery( $query );
			$this->_searchActionItems = $this->_db->loadObjectList();
		}
		
		return $this->_searchActionItems;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getAssignedFields( $client )
	{
		if ( $this->_id ) {
			$where = ' WHERE s.searchid = '.$this->_id.' AND cc.type != 25 AND cc.type != 46 AND s.client = "'.$client.'"';

			$query = 'SELECT (CONCAT( s.itemid,(CONCAT("-", (CONCAT(ccc.name, (CONCAT("-", cccc.id)) )) )) )) AS value, cc.title AS text, cc.name, s.searchmatch, s.value AS prevalue, s.helper, s.helper2, s.target, s.groupname, s.live, s.stage, s.stage_state, s.acl'
				   . ' FROM #__jseblod_cck_search_item AS s'
				   . ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
				   . ' LEFT JOIN #__jseblod_cck_items_types AS ccc ON ccc.id = cc.type'
				   . ' LEFT JOIN #__jseblod_cck_items_categories AS cccc ON cccc.id = cc.category'
				   . $where
				   . ' ORDER BY s.ordering asc' ;
			$this->_db->setQuery( $query );
			$this->_assignedFields = $this->_db->loadObjectList();
		} else { $this->_assignedFields = array() ; }
		
		return $this->_assignedFields;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getAvailableFields( $client )
	{
		$types	= '(1,2,3,4,5,7,9,10,11,14,16,18,26,27,29,30,32,33,39,41,43,44,47,48,49,50,51)'; //TODO SEARCH LIST FIELD TYPE
		$where	= ' WHERE ( sc.display > 0 ) AND s.type IN '.$types;
		$excludedFields = $this->_getExcludedFields( $client );
		if ( $excludedFields ) {
			$where .= ' AND s.id NOT IN ('.$excludedFields.')';
		}
		
		$query	= 'SELECT (CONCAT( s.id,(CONCAT("-", (CONCAT(cc.name, (CONCAT("-", sc.id)))) )) )) AS value, s.title AS text'
				. ' FROM #__jseblod_cck_items AS s'
				. ' LEFT JOIN #__jseblod_cck_items_categories AS sc ON sc.id = s.category'
				. ' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
				. $where
				. ' ORDER BY s.title asc' ;
		$this->_db->setQuery( $query );
		$this->_availableFields = $this->_db->loadObjectList();
		
		return $this->_availableFields;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getExcludedFields( $client )
	{
		$where = ' WHERE searchid = '.$this->_id.' AND client = "'.$client.'"';
		
		$query = ' SELECT itemid FROM #__jseblod_cck_search_item'
				. $where
				;
		$this->_db->setQuery( $query );
		$excludedFields = $this->_db->loadResultArray();
		if ( is_array( $excludedFields ) ) {
			$excludedFields = implode( ',', $excludedFields );
		}
		
		return $excludedFields;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getAvailableFieldsContent( $client )
	{										
		$where = ' WHERE ( sc.display > 0 ) AND s.type != 25 AND s.type != 46';
		$excludedFields = $this->_getExcludedFieldsContent( $client );
		if ( $excludedFields ) {
			$where .= ' AND s.id NOT IN ('.$excludedFields.')';
		}
				
		$query	= 'SELECT (CONCAT( s.id,(CONCAT("-", (CONCAT(cc.name, (CONCAT("-", sc.id)))) )) )) AS value, s.title AS text'
				. ' FROM #__jseblod_cck_items AS s'
				. ' LEFT JOIN #__jseblod_cck_items_categories AS sc ON sc.id = s.category'
				. ' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
				. $where
				. ' ORDER BY s.title asc' ;
		$this->_db->setQuery( $query );
		$this->_availableFieldsC = $this->_db->loadObjectList();
		
		return $this->_availableFieldsC;
	}
	
	/**
	 * Get Data from Database
	 **/	
	function &getAssignedFieldsContent( $client )
	{
		if ( $this->_id ) {
			$where = ' WHERE s.searchid = '.$this->_id.' AND cc.type != 25 AND cc.type != 46 AND s.client = "'.$client.'"';

			$query = 'SELECT cc.id, (CONCAT( s.itemid,(CONCAT("-", (CONCAT(ccc.name, (CONCAT("-", cccc.id)) )) )) )) AS value, cc.title AS text, cc.name, s.contentdisplay, s.width, s.helper, s.link, s.access, s.mode, s.link_helper, s.target, s.groupname, s.stage, s.acl'
				   . ' FROM #__jseblod_cck_search_item_content AS s'
				   . ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
				   . ' LEFT JOIN #__jseblod_cck_items_types AS ccc ON ccc.id = cc.type'
				   . ' LEFT JOIN #__jseblod_cck_items_categories AS cccc ON cccc.id = cc.category'
				   . $where
				   . ' ORDER BY s.ordering asc' ;
			$this->_db->setQuery( $query );
			$this->_assignedFieldsC = $this->_db->loadObjectList();
		} else { $this->_assignedFieldsC = array() ; }
		
		return $this->_assignedFieldsC;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getExcludedFieldsContent( $client )
	{
		$where = ' WHERE searchid = '.$this->_id.' AND client = "'.$client.'"';
		
		$query = ' SELECT itemid FROM #__jseblod_cck_search_item_content'
				. $where
				;
		$this->_db->setQuery( $query );
		$excludedFieldsC = $this->_db->loadResultArray();
		if ( is_array( $excludedFieldsC ) ) {
			$excludedFieldsC = implode( ',', $excludedFieldsC );
		}
		
		return $excludedFieldsC;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getCategoryState( $categoryId )
	{
		$where = ' WHERE s.id = '.$categoryId;
      		
  		$query = ' SELECT s.published'
  			. ' FROM #__jseblod_cck_searchs_categories AS s'
  			. $where
  		;
    	$this->_db->setQuery( $query );
  		$this->_categorystate = $this->_db->loadResult();
		
		return $this->_categorystate;
	}
	
	//
	// LAYOUT 4TH DIMENSION
	//
	
	function &getItemsSearchInterface( $assigned )
	{
		$where = ' WHERE s.id IN ('.$assigned.') AND s.type != 27 AND s.type != 32 AND s.type != 43 AND s.type != 45'; //TODO SEARCH LIST FIELD TYPE
		
		$query	= ' SELECT DISTINCT s.*, sc.name AS typename'
				. ' FROM #__jseblod_cck_items AS s '
				. ' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
				. $where
				. ' ORDER BY FIELD(s.id, '.$assigned.')'
				;
		$this->_db->setQuery( $query );
		$searchItems	=	$this->_db->loadObjectList();
		
		return $searchItems;
	}
	
	/**
	 * Store Record(s)
	 **/
	function store()
	{
		$row =& $this->getTable( 'searchs' );
		$data = JRequest::get( 'post' );
		
		/**
		 * Extra POST Pre-Store
		 **/
		$data['title']			=	trim( $data['title'] );
		$data['description'] 	=	( $data['description_updated'] == 1 ) ? JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW )
		: ( ( ! $data['id'] ) ? HelperjSeblod_Helper::getWysiwygContent( 'description', 'searchs', $data['cid'][0] ) : HelperjSeblod_Helper::getWysiwygContent( 'description', 'searchs', $data['id'] ) );
		
		//$selected_categories = explode( ',', $data['selected_joomla_categories'] );
		//$nCat = count( $selected_categories );
		
		$nCat = count( $data['selected_categories'] );
		
		/**
		 * Store !!
		 **/
		
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
		
		/**
		 * Extra SQL Post-Store
		 **/
		if ( $data['searchmatch'] ) {
			$liveItems		=	explode( '||', $data['searchmatch'] );
			if ( sizeof( $liveItems ) ) {
				$searchItemValues	=	array();
				foreach ($liveItems as $assigned ) {
					$assignedValue	=	explode( '::', $assigned );
					if ( @$assignedValue[1] ) {
						$searchItemValues[$assignedValue[0]] = $assignedValue[1];
						$searchItemValues[$assignedValue[0].'_helper'] = $assignedValue[2];
						$searchItemValues[$assignedValue[0].'_helper2'] = $assignedValue[3];
						$searchItemValues[$assignedValue[0].'_target'] = $assignedValue[4];
						$searchItemValues[$assignedValue[0].'_group'] = $assignedValue[5];
						$searchItemValues[$assignedValue[0].'_stage'] = $assignedValue[6];
						$searchItemValues[$assignedValue[0].'_acl'] = $assignedValue[7];
					}
				}
			}
		}
		
		// Delete Field Assignements
		$query = 'DELETE FROM #__jseblod_cck_search_item WHERE searchid = '.$row->id;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		// Insert Search Field Assignements
		$assignmentsValues = null;
		if ( $data['searchaction_item'] ) {
			$form = $data['searchaction_item'];
			$assignmentsValues = ', ( '.$row->id.', '.$form.', "search", 1, "", "", "", "", "", "", "", "", "", "" ) ';
		}
		$nAField = count( $data['selected_searchfields'] );
		if ( $nAField ) {
			$nOrder = 2;
			foreach ( $data['selected_searchfields'] as $val ) {
				$vals 			=	explode( '-', $val );
				$val 			=	$vals[0];
				$name			=	CCK_DB_Result( 'SELECT s.name FROM #__jseblod_cck_items AS s WHERE s.id='.(int)$val );
				$match			=	( @$searchItemValues[$name] ) ? $searchItemValues[$name] : 'inherit';
				$helper			=	( @$searchItemValues[$name.'_helper'] ) ? $searchItemValues[$name.'_helper'] : '';
				$helper2		=	( @$searchItemValues[$name.'_helper2'] ) ? $searchItemValues[$name.'_helper2'] : '';
				$target			=	( @$searchItemValues[$name.'_target'] ) ? $searchItemValues[$name.'_target'] : '';
				$group			=	( @$searchItemValues[$name.'_group'] ) ? $searchItemValues[$name.'_group'] : '';
				$stage			=	( @$searchItemValues[$name.'_stage'] ) ? $searchItemValues[$name.'_stage'] : '';
				$stage_state	=	0;
				$acl			=	( @$searchItemValues[$name.'_acl'] != '' && @$searchItemValues[$name.'_acl'] != '0,18,19,20,21,23,24,25' ) ? ','.$searchItemValues[$name.'_acl'].',' : '';
				$assignmentsValues .= ', ( '.$row->id.', '.(int)$val.', "search", '.$nOrder.', "'.$match.'", "", "'.$helper.'", "'.$helper2.'", "'.$target.'", "'.$group.'", "", "'.$stage.'", '.$stage_state.', "'.$acl.'" ) ';
				$nOrder++;
			}		
		}
		$assignmentsValues = substr( $assignmentsValues, 1 );
		$query = 'INSERT INTO #__jseblod_cck_search_item ( searchid, itemid, client, ordering, searchmatch, value, helper, helper2, target, groupname, live, stage, stage_state, acl )'
			   . ' VALUES ' . $assignmentsValues;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Liststage
		$liststages	=	explode( '||', $data['liststage'] );
		
		// Insert List Field Assignements
		if ( $data['listmatch'] ) {
			$liveItems		=	explode( '||', $data['listmatch'] );
			if ( sizeof( $liveItems ) ) {
				$listItemValues	=	array();
				foreach ($liveItems as $assigned ) {
					$assignedValue	=	explode( '::', $assigned );
					if ( @$assignedValue[1] ) {
						$listItemValues[$assignedValue[0]] = $assignedValue[1];
						$listItemValues[$assignedValue[0].'_value'] = $assignedValue[2];
						$listItemValues[$assignedValue[0].'_helper'] = $assignedValue[3];
						$listItemValues[$assignedValue[0].'_helper2'] = $assignedValue[4];
						$listItemValues[$assignedValue[0].'_target'] = $assignedValue[5];
						$listItemValues[$assignedValue[0].'_group'] = $assignedValue[6];
						$listItemValues[$assignedValue[0].'_live'] = $assignedValue[7];
						$listItemValues[$assignedValue[0].'_stage'] = $assignedValue[8];
						$listItemValues[$assignedValue[0].'_acl'] = $assignedValue[9];
					}
				}
			}
		}
		$assignmentsValues = null;
		if ( $data['listaction_item'] ) {
			$form = $data['listaction_item'];
			$assignmentsValues = ', ( '.$row->id.', '.$form.', "list", 1, "", "", "", "", "", "", "", "", "", "" ) ';
		}		
		$nSField = count( $data['selected_listfields'] );
		if ( $nSField ) {
			$nOrder = 2;
			foreach ( $data['selected_listfields'] as $val ) {
				$vals 			=	explode( '-', $val );
				$val 			=	$vals[0];
				$name			=	CCK_DB_Result( 'SELECT s.name FROM #__jseblod_cck_items AS s WHERE s.id='.(int)$val );
				$match			=	( @$listItemValues[$name] ) ? $listItemValues[$name] : 'inherit';
				$value			=	( @$listItemValues[$name.'_value'] != '' ) ? $listItemValues[$name.'_value'] : '';
				$helper			=	( @$listItemValues[$name.'_helper'] ) ? $listItemValues[$name.'_helper'] : '';
				$helper2		=	( @$listItemValues[$name.'_helper2'] ) ? $listItemValues[$name.'_helper2'] : '';
				$target			=	( @$listItemValues[$name.'_target'] ) ? $listItemValues[$name.'_target'] : '';
				$group			=	( @$listItemValues[$name.'_group'] ) ? $listItemValues[$name.'_group'] : '';
				$live			=	( @$listItemValues[$name.'_live'] ) ? $listItemValues[$name.'_live'] : '';
				$stage			=	( @$listItemValues[$name.'_stage'] ) ? $listItemValues[$name.'_stage'] : '';
				if ( $stage ) {
					$stage_state	=	( $liststages[$stage - 1] != '' ) ? $liststages[$stage - 1] : 0;
				} else {
					$stage_state	=	0;
				}
				$acl			=	( @$listItemValues[$name.'_acl'] != '' && @$listItemValues[$name.'_acl'] != '0,18,19,20,21,23,24,25' ) ? ','.$listItemValues[$name.'_acl'].',' : '';
				$assignmentsValues .= ', ( '.$row->id.', '.(int)$val.', "list", '.$nOrder.', "'.$match.'", "'.$value.'", "'.$helper.'", "'.$helper2.'", "'.$target.'", "'.$group.'", "'.$live.'", "'.$stage.'", '.$stage_state.', "'.$acl.'" ) ';
				$nOrder++;
			}
		}
		$assignmentsValues = substr( $assignmentsValues, 1 );
		$query = 'INSERT INTO #__jseblod_cck_search_item ( searchid, itemid, client, ordering, searchmatch, value, helper, helper2, target, groupname, live, stage, stage_state, acl )'
			   . ' VALUES ' . $assignmentsValues;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Delete Content Field Assignements
		$query = 'DELETE FROM #__jseblod_cck_search_item_content WHERE searchid = '.$row->id;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Insert Content Field Assignements
		$data['contentdisplay']	=	JRequest::getVar( 'contentdisplay', '', 'post', 'string', JREQUEST_ALLOWRAW );
		if ( $data['contentdisplay'] ) {
			$liveItems		=	explode( '||', $data['contentdisplay'] );
			if ( sizeof( $liveItems ) ) {
				$contentItemValues	=	array();
				foreach ($liveItems as $assigned ) {
					$assignedValue	=	explode( '::', $assigned );
					//if ( @$assignedValue[1] ) {
						$contentItemValues[$assignedValue[0]]					=	$assignedValue[1];
						$contentItemValues[$assignedValue[0].'_width']			=	$assignedValue[2];
						$contentItemValues[$assignedValue[0].'_helper'] 		=	$assignedValue[3];
						$contentItemValues[$assignedValue[0].'_link'] 			=	$assignedValue[4];
						//$contentItemValues[$assignedValue[0].'_link_helper']	=	$assignedValue[5];
						$contentItemValues[$assignedValue[0].'_access']			=	$assignedValue[5];
						$contentItemValues[$assignedValue[0].'_mode']			=	$assignedValue[6];
					//}
				}
			}
		}
		$assignmentsValues = null;
		$nCField = count( $data['selected_contentfields'] );
		if ( $nCField ) {
			$nOrder = 1;
			foreach ( $data['selected_contentfields'] as $val ) {
				$vals = explode( '-', $val );
				$val = $vals[0];
				$name			=	CCK_DB_Result( 'SELECT s.name FROM #__jseblod_cck_items AS s WHERE s.id='.(int)$val );
				$disp			=	( @$contentItemValues[$name] ) ? $contentItemValues[$name] : '';
				$width			=	( @$contentItemValues[$name.'_width'] ) ? $contentItemValues[$name.'_width'] : '';
				switch ( $disp ) {
					case 'bold':
						$helper	=	'<strong>*value*</strong>';
						break;
					case 'italic':
						$helper	=	'<em>*value*</em>';
						break;
					case 'underline':
						$helper	=	'<span style="text-decoration: underline;">*value*</span>';
						break;
					case 'free':
						$helper	=	( @$contentItemValues[$name.'_helper'] ) ? $contentItemValues[$name.'_helper'] : '';
						break;
					default:
						$helper	=	'';
						break;
				}
				$helper			=	addslashes( $helper );
				$link			=	( @$contentItemValues[$name.'_link'] ) ? $contentItemValues[$name.'_link'] : '';
				//$link_helper	=	( @$contentItemValues[$name.'_link_helper'] ) ? $contentItemValues[$name.'_link_helper'] : '';
				$access			=	( @$contentItemValues[$name.'_access'] ) ? $contentItemValues[$name.'_access'] : '';
				$mode			=	( @$contentItemValues[$name.'_mode'] ) ? $contentItemValues[$name.'_mode'] : '';
				$assignmentsValues .= ', ( '.$row->id.', '.(int)$val.', "content", '.$nOrder.', "'.$disp.'", "'.$width.'", "'.$helper.'", "'.$link.'", "", "", "'.$access.'", "'.$mode.'" ) ';
				$nOrder++;
			}
			$assignmentsValues = substr( $assignmentsValues, 1 );
			$query = 'INSERT INTO #__jseblod_cck_search_item_content ( searchid, itemid, client, ordering, contentdisplay, width, helper, link, link_helper, target, access, mode )'
				   . ' VALUES ' . $assignmentsValues;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}			
		}
		
		// Insert Sort Field Assignements
		$assignmentsValues = null;
		$nOrder = 1;
		for ( $s = 0; $s < 4; $s++ ) {
			if ( $data['sort'][$s] && $data['sort'][$s] != '--' ) {
				$val				=	$data['sort'][$s];
				$disp				=	( $data['sort_type'][$s] ) ? $data['sort_type'][$s] : 'ASC';
				$width				=	( $data['sort_mode'][$s] ) ? $data['sort_mode'][$s] : '';
				$helper				=	( $data['sort_helper'][$s] ) ? $data['sort_helper'][$s] : '';
				$target				=	( ( $data['sort_bot'][$s] ) ? $data['sort_bot'][$s] : '' ). '~' . (( $data['sort_eot'][$s] ) ? $data['sort_eot'][$s] : '');
				$target				=	( $target == '~' ) ? '' : $target;
				$stage				=	( $data['sort_stage'][$s] ) ? $data['sort_stage'][$s] : 0;
				$assignmentsValues .=	', ( '.$row->id.', '.(int)$val.', "sort", '.$nOrder.', "'.$disp.'", "'.$width.'", "'.$helper.'", "", "", "'.$target.'", "", "'.$stage.'" ) ';
				$nOrder++;
			}
		}
		$assignmentsValues = substr( $assignmentsValues, 1 );
		$query = 'INSERT INTO #__jseblod_cck_search_item_content ( searchid, itemid, client, ordering, contentdisplay, width, helper, link, link_helper, target, groupname, stage )'
			   . ' VALUES ' . $assignmentsValues;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		//
		
		return $row->id;
	}

	/**
	 * Batch Category Process
	 **/
	function batchCategory()
	{
		$cids		=	JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$categoryId	=	JRequest::getInt( 'category' );
		
		$inCids = implode( ',', $cids );
		
		if ( $categoryId && count( $cids ) && $inCids )
		{
			$n = count( $cids );
			$query = 'UPDATE #__jseblod_cck_searchs'
				   . ' SET category = '.(int)$categoryId
				   . ' WHERE id IN ( '.$inCids.' )'
				   ;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return $n;
	}
	
	/**
	 * Live Store Record
	 **/
	function liveStore()
	{
		$liveId		=	JRequest::getInt( 'live_id' );
		$liveTitle	=	JRequest::getVar( 'live_title', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		if ( $liveId ) {
			$query	= 'UPDATE #__jseblod_cck_searchs'
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
			$cids	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
			$inCids = implode( ',', $cids );
			
			$query = 'SELECT s.id, s.title'
				   . ' FROM #__jseblod_cck_searchs AS s'
				   . ' WHERE s.id IN ( '.$inCids.' )'
				   ;
			$this->_db->setQuery( $query );
			$this->_removeData = $this->_db->loadObjectList();
		}
		
		return $this->_removeData;
	}
	
	/**
	 * Delete Record(s)
	 **/
	function delete( $deleteMode )
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& $this->getTable( 'searchs' );
		
		if ( $n = count( $cids ) )
		{
			foreach( $cids as $cid ) {
				if ( ! $row->delete( $cid ) ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
				
				/**
				 * Extra SQL Post-Delete
				 **/
								
				/*$query = 'DELETE FROM #__jseblod_cck_packs WHERE elemid = '.$cid.' AND type = "type"';
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}*/
				
				if ( $deleteMode == -1 ) {
					// DeleteAll: Assignments Type|Field && Fields
					$query = 'DELETE s, cc FROM #__jseblod_cck_search_item AS s'
					.' LEFT JOIN #__jseblod_cck_items AS cc ON s.itemid = cc.id'					
					.' WHERE s.itemid = cc.id AND s.itemid NOT IN (1,2,3,10,11,12,13,14,15,22,23,24,25,26,27,120,121,274,290)'
					.' AND s.searchid = '.$cid;
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
				}
				// Delete: Assignments Type|Item
				$query = 'DELETE FROM #__jseblod_cck_search_item WHERE searchid = '.$cid;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
				// Delete: Assignments Type|Item Content
				$query = 'DELETE FROM #__jseblod_cck_search_item_content WHERE searchid = '.$cid;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
			}
		}
		
		return $n;
	}
	
	function publish( $cid = array(), $publish = 1 )
	{
		$user 	=& JFactory::getUser();
		
		if ( count( $cid ) ) {
			JArrayHelper::toInteger( $cid );
			$cids = implode( ',', $cid );
				
			$query = 'UPDATE #__jseblod_cck_searchs'
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
			$row =& $this->getTable( 'searchs' );
			
			// Check User Id
			if ( is_null( $uid ) ) {
				$user	=& JFactory::getUser();
				$uid	= $user->get( 'id' );
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
			$row =& $this->getTable( 'searchs' );
			
			// Checkin!
			if ( ! $row->checkin( $this->_id ) ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return false;
	}
	
	/**
	 * Add into Pack
	 **/
	function addIntoPack()
	{
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'cckshare_export.php' );
		
		$cids	=	JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$mode	=	JRequest::getVar( 'add_mode' );
		
		if ( count( $cids ) && $cids[0] )
		{
			CCKjSeblodShare_Export::addIntoPack( $cids, 'search', $mode );
			
			return true;
		}
		
		return false;
	}
	
	function exportXml()
	{
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'cckshare_export.php' );
		
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$fileName	=	JRequest::getVar( 'name_package' );
		$mode		=	JRequest::getVar( 'export_mode' );

		$inCids = implode( ',', $cids );
		
		if ( $n = count( $cids ) && $inCids )
		{
			if ( $file	=	CCKjSeblodShare_Export::exportSearch_Types( $inCids, $fileName, $mode, TRUE ) ) {
				return $file;
			}
			
			return false;
		}
		
		return false;
	}
	
	/**
	 * Get Data from Database
	 **/
	function	getFormFields( $client )
	{
		$where	= ' WHERE s.searchid = '.$this->_id.' AND s.client = "'.$client.'"';

  		$query	= 'SELECT cc.name'
  				. ' FROM #__jseblod_cck_search_item AS s'
				. ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
  				. $where
				. ' ORDER BY s.ordering'
  				;
    	$this->_db->setQuery( $query );
  		$fields	=	$this->_db->loadResultArray();
		
		return $fields;
	}

	/**
	 * Auto Type
	 **/
	function	autoType()
	{
		$typeId			=	JRequest::getVar( 'autotype_id' );
		$items_list		=	JRequest::getInt( 'autotype_list' );
		//$items_search	=	JRequest::getInt( 'autotype_search' );
		$items_content	=	JRequest::getInt( 'autotype_content' );
		if ( $typeId ) {
			$type		=&	JTable::getInstance( 'types', 'Table' );
			$type->load( $typeId );
			
			$search		=&	JTable::getInstance( 'searchs', 'Table' );
			$search->bind( $type );
			//
			if ( $search->category != 1 ) {
				$search->category			=	1;
			}
			$search->content			=	( $items_content ) ? 2 : 0;
			$search->searchtemplate		=	1;
			$search->contenttemplate	=	CCK_DB_Result( 'SELECT s.id FROM #__jseblod_cck_templates AS s WHERE s.name = "default_list"' );
			//
			$search->id	=	null;

			$search->store();
			if ( $search->id ) {
				$items	=	array();
				if ( $items_list ) {
					$query	= 'SELECT s.*'
							. ' FROM #__jseblod_cck_items AS s'
						   	. ' WHERE s.name ="default_search_action"'
							;
					$this->_db->setQuery( $query );
					$items[]=	$this->_db->loadObject();
					$query	= 'SELECT s.*'
							. ' FROM #__jseblod_cck_items AS s'
						   	. ' WHERE s.name ="jseblod"'
							;
					$this->_db->setQuery( $query );
					$items[]=	$this->_db->loadObject();
					if ( sizeof( $items ) == 2 ) {
						$assignmentsValues	=	' ( '.$search->id.', '.(int)$items[0]->id.', "search", 1, "", "", "" ) ';
						$assignmentsValues	.=	', ( '.$search->id.', '.(int)$items[0]->id.', "list", 1, "", "", "" ) ';
						$assignmentsValues	.=	', ( '.$search->id.', '.(int)$items[1]->id.', "list", 2, "exact", "'.$search->name.'", "" ) ';
						$query = 'INSERT INTO #__jseblod_cck_search_item ( searchid, itemid, client, ordering, searchmatch, value, helper )'
							   . ' VALUES ' . $assignmentsValues;
						$this->_db->setQuery( $query );
						if ( ! $this->_db->query() ) {
							$this->setError( $this->_db->getErrorMsg() );
							return false;
						}
					}
				}
				if ( $items_content ) {
					$query	= 'SELECT s.*'
							. ' FROM #__jseblod_cck_type_item_email AS s'
						   	. ' WHERE s.client="content" AND s.typeid ='.(int)$typeId
						   ;
					$this->_db->setQuery( $query );
					$items	=	$this->_db->loadObjectList();
					if ( sizeof( $items ) ) {
						$assignmentsValues	=	null;
						$nOrder	=	1;
						foreach( $items as $item ) {
							$assignmentsValues	.=	', ( '.$search->id.', '.(int)$item->itemid.', "content", '.$nOrder.', "", "", "" ) ';
							$nOrder++;
						}
						$assignmentsValues	=	substr( $assignmentsValues, 1 );
						$query = 'INSERT INTO #__jseblod_cck_search_item_content ( searchid, itemid, client, ordering, contentdisplay, width, helper )'
							   . ' VALUES ' . $assignmentsValues;
						$this->_db->setQuery( $query );
						if ( ! $this->_db->query() ) {
							$this->setError( $this->_db->getErrorMsg() );
							return false;
						}
					}
				}
				return( $search->id );
			}
		}
		
		return false;
	}
}
?>
