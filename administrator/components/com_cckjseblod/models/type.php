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
	var $_id					= null;
	var $_data					= null;
	var $_removeData			= null;
	var $_assignedCategories	= null;
	var $_availableCategories	= null;
	var $_assignedFields		= null;
	var $_availableFields		= null;
	var $_selected_categories	= null;
	var $_available_categories	= null;

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
			$row =& $this->getTable( 'types' );
			
			if ( $this->_id ) {
				$row->load( $this->_id );
				if ( ! $row->checked_out ) {
					$user =& JFactory::getUser();
					// Checkout!
					$row->checkout( $user->get('id') );
				}
				$this->_data =& $row;
				$this->_data->admintemplate			=	( $this->_data->admintemplate ) ? $this->_data->admintemplate : 1;
				$this->_data->admintemplateTitle	=	$this->_getTemplate( $this->_data->admintemplate );
				$this->_data->sitetemplate 			=	( $this->_data->sitetemplate ) ? $this->_data->sitetemplate : 1;
				$this->_data->sitetemplateTitle		=	$this->_getTemplate( $this->_data->sitetemplate );
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

	/**
	 * Get Data from Database
	 **/
	function getDefaultAction()
	{
		$where = ' WHERE s.id = 1';
			
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
	function &getAssignedCategories()
	{
		if ( $this->_id ) {
			if ( empty( $this->_assignedCategories ) )
			{
				$where = ' WHERE typeid = '.$this->_id . ' AND cc.id';
				
				$query = 'SELECT s.catid AS value, (CONCAT( cc.title,(CONCAT(" ( ", (CONCAT(ccc.title, " )")) )) )) AS text'
					   . ' FROM #__jseblod_cck_type_cat AS s'
					   . ' LEFT JOIN #__categories AS cc ON cc.id = s.catid '
					   . ' LEFT JOIN #__sections AS ccc ON ccc.id = cc.section '					   
					   . $where
					   . ' ORDER BY cc.title asc' ;
				$this->_db->setQuery( $query );
				$this->_assignedCategories = $this->_db->loadObjectList();
			}
		} else { $this->_assignedCategories = array() ; }
		
		return $this->_assignedCategories;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getAvailableCategories()
	{
		if ( empty( $this->_availableCategories ) )
		{
			$where = ' WHERE s.section NOT LIKE "%com_%" ';
			$excludedCategories = $this->_getExcludedCategories( true );
			if ( $excludedCategories ) {
				$where .= ' AND s.id NOT IN ('.$excludedCategories.')';
			}
			
			$query = 'SELECT s.id AS value, (CONCAT( s.title,(CONCAT(" ( ", (CONCAT(cc.title, " )")) )) )) AS text'
				   . ' FROM #__categories AS s'
				   . ' LEFT JOIN #__sections AS cc ON cc.id = s.section '	
				   . $where
				   . ' ORDER BY s.title asc ;' ;
			$this->_db->setQuery( $query );
			$this->_availableCategories = $this->_db->loadObjectList();
		}
		return $this->_availableCategories;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getExcludedCategories( $all )
	{
		$where = ' WHERE typeid = '.$this->_id;
		if ( $all ) {
			$where = '';
		}
		
		$query = ' SELECT catid FROM #__jseblod_cck_type_cat '
				. $where
				;
		$this->_db->setQuery( $query );
		$excludedCategories = $this->_db->loadResultArray();
		if ( is_array( $excludedCategories ) ) {
			$excludedCategories = implode( ',', $excludedCategories );
		}
		
		return $excludedCategories;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getFormAction( $client )
	{
		$where = ' WHERE s.typeid = '.$this->_id.' AND cc.type = 25 AND s.client ="'.$client.'"';
		
		$query 	= 'SELECT s.itemid'
				. ' FROM #__jseblod_cck_type_item AS s'
				. ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
				. $where
				;
		$this->_db->setQuery( $query );
		$this->_formAction = $this->_db->loadResult();
		
		return $this->_formAction;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getFormActionItems()
	{
		if ( empty( $this->_formActionItems ) ) {
			$where = ' WHERE s.type = 25 AND s.id != 1';
			
			$query = 'SELECT s.id AS value, s.title AS text'
				   . ' FROM #__jseblod_cck_items AS s'
				   . $where
				   . ' ORDER BY s.title asc' ;
			$this->_db->setQuery( $query );
			$this->_formActionItems = $this->_db->loadObjectList();
		}
		
		return $this->_formActionItems;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getAssignedFields( $client )
	{
		if ( $this->_id ) {
			$where = ' WHERE s.typeid = '.$this->_id.' AND cc.type != 25 AND cc.type != 46 AND s.client = "'.$client.'"';

			$query = 'SELECT (CONCAT( s.itemid,(CONCAT("-", (CONCAT(ccc.name, (CONCAT("-", cccc.id)) )) )) )) AS value, cc.title AS text, cc.name, s.typography, s.submissiondisplay, s.editiondisplay, s.value AS prevalue, s.helper, s.live, s.acl'
				   . ' FROM #__jseblod_cck_type_item AS s'
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
		$where = ' WHERE ( sc.display > 0 ) AND s.type != 25 AND s.type != 46 AND s.type != 47 AND s.type != 48 AND s.type != 50 ';
		$excludedFields = $this->_getExcludedFields( $client );
		if ( $excludedFields ) {
			$where .= ' AND s.id NOT IN ('.$excludedFields.')';
		}

		$query = 'SELECT (CONCAT( s.id,(CONCAT("-", (CONCAT(cc.name, (CONCAT("-", sc.id)))) )) )) AS value, s.title AS text'
				.' FROM #__jseblod_cck_items AS s'
				.' LEFT JOIN #__jseblod_cck_items_categories AS sc ON sc.id = s.category'
				.' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
				. $where
				.' ORDER BY s.title asc' ;
		$this->_db->setQuery( $query );
		$this->_availableFields = $this->_db->loadObjectList();
		
		return $this->_availableFields;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getExcludedFields( $client )
	{
		$where = ' WHERE typeid = '.$this->_id.' AND client = "'.$client.'"';
		
		$query = ' SELECT itemid FROM #__jseblod_cck_type_item'
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
	function &getAssignedFieldsRight( $client )
	{
		if ( $this->_id ) {
			if ( $client == 'email' ) {
				$where = ' WHERE s.typeid = '.$this->_id.' AND cc.type != 25 AND cc.type != 27 AND cc.type != 32 AND cc.type != 38 AND cc.type != 46 AND s.client = "email"';
			} else {
				$where = ' WHERE s.typeid = '.$this->_id.' AND cc.type != 25 AND cc.type != 46 AND s.client = "content"';
			}
			$query = 'SELECT (CONCAT( s.itemid,(CONCAT("-", (CONCAT(ccc.name, (CONCAT("-", cccc.id)) )) )) )) AS value, cc.title AS text, cc.name, s.contentdisplay, s.bool, s.helper, s.link'
				   . ' FROM #__jseblod_cck_type_item_email AS s'
				   . ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
				   . ' LEFT JOIN #__jseblod_cck_items_types AS ccc ON ccc.id = cc.type'
				   . ' LEFT JOIN #__jseblod_cck_items_categories AS cccc ON cccc.id = cc.category'
				   . $where
				   . ' ORDER BY s.ordering asc' ;
			$this->_db->setQuery( $query );
			$this->_assignedFieldsE = $this->_db->loadObjectList();
		} else { $this->_assignedFieldsE = array() ; }
		
		return $this->_assignedFieldsE;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getAvailableFieldsRight( $client )
	{
		if ( $client == 'email' ) {
			$where = ' WHERE ( sc.display > 0 ) AND s.type != 25 AND s.type != 27 AND s.type != 32 AND s.type != 38 AND s.type != 46';
			$excludedFieldsRight = $this->_getExcludedFieldsRight( $client );
			if ( $excludedFieldsRight ) {
				$where .= ' AND s.id NOT IN ('.$excludedFieldsRight.')';
			}
		} else {
			$empty	=	array();
											
			$where = ' WHERE ( ( sc.display > 0 ) AND s.type != 25 AND s.type != 46';
			$excludedFieldsRight = $this->_getExcludedFieldsRight( $client );
			$includedFieldsForm = $this->_getExcludedFieldsForm();
			
			if ( sizeof( $includedFieldsForm ) ) {
				if ( sizeof( $excludedFieldsRight ) ) {
					$includedFields	=	array_diff( $includedFieldsForm, $excludedFieldsRight );
					$excludedFields =	implode( ',', $excludedFieldsRight );
					if ( sizeof ( $includedFields ) ) {
						$includedFields = implode( ',', $includedFields );
						$where .= ' AND s.id IN ('.$includedFields.')';
					} else {
						return $empty;
					}
				} else {
					$includedFieldsForm =	implode( ',', $includedFieldsForm );
					$excludedFields		=	0;
					$where .= ' AND s.id IN ('.$includedFieldsForm.')';
				}
				$where .= ' ) OR ( ( s.type = 24 OR s.type = 51 OR s.type = 52 OR s.type = 53 OR s.type = 54 ) AND s.id NOT IN ('.$excludedFields.') )';
			} else {
				$where .= ' ) AND ( s.type = 24 OR s.type = 51 OR s.type = 52 OR s.type = 53 OR s.type = 54 )';
				//return $empty;
			}
		}
		$query = 'SELECT (CONCAT( s.id,(CONCAT("-", (CONCAT(cc.name, (CONCAT("-", sc.id)))) )) )) AS value, s.title AS text'
				.' FROM #__jseblod_cck_items AS s'
				.' LEFT JOIN #__jseblod_cck_items_categories AS sc ON sc.id = s.category'
				.' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
				. $where
				.' ORDER BY s.title asc' ;
		$this->_db->setQuery( $query );
		$this->_availableFieldsE = $this->_db->loadObjectList();
		
		return $this->_availableFieldsE;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getExcludedFieldsRight( $client )
	{
		$where = ' WHERE typeid = '.$this->_id.' AND client = "'.$client.'"';
		
		$query = ' SELECT itemid FROM #__jseblod_cck_type_item_email'
				. $where
				;
		$this->_db->setQuery( $query );
		$excludedFieldsE = $this->_db->loadResultArray();
		if ( $client == 'email' ) {
			if ( is_array( $excludedFieldsE ) ) {
				$excludedFieldsE = implode( ',', $excludedFieldsE );
			}
		}
		
		return $excludedFieldsE;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getExcludedFieldsForm()
	{
		$where = ' WHERE typeid = '.$this->_id.' AND cc.type != 25 AND cc.type != 46';
		
		$query = ' SELECT DISTINCT s.itemid FROM #__jseblod_cck_type_item AS s'
				.' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
				. $where
				;
		$this->_db->setQuery( $query );
		$includedFields = $this->_db->loadResultArray();
		
		return $includedFields;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getCategoryState( $categoryId )
	{
		$where = ' WHERE s.id = '.$categoryId;
      		
  		$query = ' SELECT s.published'
  			. ' FROM #__jseblod_cck_types_categories AS s'
  			. $where
  		;
    	$this->_db->setQuery( $query );
  		$this->_categorystate = $this->_db->loadResult();
		
		return $this->_categorystate;
	}
	
	/**
	 * Get Data from Database
	 **/
	function	getAssignedAdminUrls()
	{
		$where = ' WHERE s.typeid = '.$this->_id;
      		
  		$query = ' SELECT s.url, s.type'
  			. ' FROM #__jseblod_cck_type_url AS s'
  			. $where
  		;
    	$this->_db->setQuery( $query );
  		$admin_urls = $this->_db->loadObject();
		
		return $admin_urls;
	}
	
	//
	// LAYOUT 4TH DIMENSION
	//
	function &getItemsTypeInterface( $assigned )
	{
		$where = ' WHERE s.id IN ('.$assigned.')'; //TODO FORM LIST FIELD TYPE
		
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
		$row =& $this->getTable( 'types' );
		$data = JRequest::get( 'post' );
		
		/**
		 * Extra POST Pre-Store
		 **/	
		$data['title']			=	trim( $data['title'] );
		$data['description']	=	( $data['description_updated'] == 1 ) ? JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW )
		: ( ( ! $data['id'] ) ? HelperjSeblod_Helper::getWysiwygContent( 'description', 'types', $data['cid'][0] ) : HelperjSeblod_Helper::getWysiwygContent( 'description', 'types', $data['id'] ) );
		
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
		
		// Delete Joomla Category Assignements
		$query = 'DELETE FROM #__jseblod_cck_type_cat WHERE typeid = '.$row->id;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Insert Joomla Category Assignments
		if ( $nCat ) {
			$assignmentsValues = null;
			foreach ( $data['selected_categories'] as $val ) {
			//foreach ( $selected_categories as $val ) {
				$assignmentsValues .= ', ( '.$row->id.', '.(int)$val.', "category" ) ';
			}
			$assignmentsValues = substr( $assignmentsValues, 1 );
			$query = 'INSERT INTO #__jseblod_cck_type_cat ( typeid, catid, type )'
				   . ' VALUES ' . $assignmentsValues;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
			
		// Delete Field Assignements
		$query = 'DELETE FROM #__jseblod_cck_type_item WHERE typeid = '.$row->id;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		//
		if ( $data['adminform'] ) {
			$liveItems		=	explode( '||', $data['adminform'] );
			if ( sizeof( $liveItems ) ) {
				$typeItemValues	=	array();
				foreach ($liveItems as $assigned ) {
					$assignedValue	=	explode( '::', $assigned );
					$typeItemValues[$assignedValue[0]]						=	$assignedValue[1];
					$typeItemValues[$assignedValue[0].'_submissiondisplay'] =	$assignedValue[2];
					$typeItemValues[$assignedValue[0].'_editiondisplay']	=	$assignedValue[3];
					$typeItemValues[$assignedValue[0].'_value']				=	$assignedValue[4];
					$typeItemValues[$assignedValue[0].'_helper']			=	"";
					$typeItemValues[$assignedValue[0].'_live']				=	$assignedValue[5];
					$typeItemValues[$assignedValue[0].'_acl']				=	$assignedValue[6];
				}
			}
		}
		
		// Insert Admin Field Assignements
		$assignmentsValues = null;
		if ( $data['adminaction_item'] ) {
			$form = $data['adminaction_item'];
			$assignmentsValues = ', ( '.$row->id.', '.$form.', "admin", 1, typography, submissiondisplay, editiondisplay, value, helper, live, acl ) ';
		}
		$nAField = count( $data['selected_adminfields'] );
		if ( $nAField ) {
			$nOrder = 2;
			foreach ( $data['selected_adminfields'] as $val ) {
				$vals				=	explode( '-', $val );
				$val				=	$vals[0];
				$name				=	CCK_DB_Result( 'SELECT s.name FROM #__jseblod_cck_items AS s WHERE s.id='.(int)$val );
				$typo				=	( @$typeItemValues[$name] ) ? $typeItemValues[$name] : '';
				$submissiondisplay	=	( @$typeItemValues[$name.'_submissiondisplay'] ) ? $typeItemValues[$name.'_submissiondisplay'] : '';
				$editiondisplay		=	( @$typeItemValues[$name.'_editiondisplay'] ) ? $typeItemValues[$name.'_editiondisplay'] : '';
				$value				=	( @$typeItemValues[$name.'_value'] != '' ) ? $typeItemValues[$name.'_value'] : '';
				$helper				=	( @$typeItemValues[$name.'_helper'] ) ? $typeItemValues[$name.'_helper'] : '';
				$live				=	( @$typeItemValues[$name.'_live'] ) ? $typeItemValues[$name.'_live'] : '';
				$acl				=	( @$typeItemValues[$name.'_acl'] ) ? $typeItemValues[$name.'_acl'] : '';
				$acl				=	( @$typeItemValues[$name.'_acl'] != '' && @$typeItemValues[$name.'_acl'] != '23,24,25' ) ? ','.$typeItemValues[$name.'_acl'].',' : '';
				$assignmentsValues .= ', ( '.$row->id.', '.(int)$val.', "admin", '.$nOrder.', "'.$typo.'", "'.$submissiondisplay.'", "'.$editiondisplay.'", "'.$value.'", "'.$helper.'", "'.$live.'", "'.$acl.'" ) ';
				$nOrder++;
			}
			$assignmentsValues = substr( $assignmentsValues, 1 );
			$query = 'INSERT INTO #__jseblod_cck_type_item ( typeid, itemid, client, ordering, typography, submissiondisplay, editiondisplay, value, helper, live, acl )'
				   . ' VALUES ' . $assignmentsValues;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}			
		}
		
		$typeItemValues	=	array();
		if ( $data['siteform'] ) {
			$liveItems		=	explode( '||', $data['siteform'] );
			if ( sizeof( $liveItems ) ) {
				foreach ($liveItems as $assigned ) {
					$assignedValue	=	explode( '::', $assigned );
					$typeItemValues[$assignedValue[0]]						=	$assignedValue[1];
					$typeItemValues[$assignedValue[0].'_submissiondisplay'] =	$assignedValue[2];
					$typeItemValues[$assignedValue[0].'_editiondisplay']	=	$assignedValue[3];
					$typeItemValues[$assignedValue[0].'_value']				=	$assignedValue[4];
					$typeItemValues[$assignedValue[0].'_helper']			=	"";
					$typeItemValues[$assignedValue[0].'_live']				=	$assignedValue[5];
					$typeItemValues[$assignedValue[0].'_acl']				=	$assignedValue[6];
				}
			}
		}
		
		// Insert Site Field Assignements
		$assignmentsValues = null;
		if ( $data['siteaction_item'] ) {
			$form = $data['siteaction_item'];
			$assignmentsValues = ', ( '.$row->id.', '.$form.', "site", 1, typography, submissiondisplay, editiondisplay, value, helper, live, acl ) ';
		}
		$nSField = count( $data['selected_sitefields'] );
		if ( $nSField ) {
			$nOrder = 2;
			foreach ( $data['selected_sitefields'] as $val ) {
				$vals = explode( '-', $val );
				$val = $vals[0];
				$name				=	CCK_DB_Result( 'SELECT s.name FROM #__jseblod_cck_items AS s WHERE s.id='.(int)$val );
				$typo				=	( @$typeItemValues[$name] ) ? $typeItemValues[$name] : '';
				$submissiondisplay	=	( @$typeItemValues[$name.'_submissiondisplay'] ) ? $typeItemValues[$name.'_submissiondisplay'] : '';
				$editiondisplay		=	( @$typeItemValues[$name.'_editiondisplay'] ) ? $typeItemValues[$name.'_editiondisplay'] : '';
				$value				=	( @$typeItemValues[$name.'_value'] != '' ) ? $typeItemValues[$name.'_value'] : '';
				$helper				=	( @$typeItemValues[$name.'_helper'] ) ? $typeItemValues[$name.'_helper'] : '';
				$live				=	( @$typeItemValues[$name.'_live'] ) ? $typeItemValues[$name.'_live'] : '';
				$acl				=	( @$typeItemValues[$name.'_acl'] != '' && @$typeItemValues[$name.'_acl'] != '0,18,19,20,21,23,24,25' ) ? ','.$typeItemValues[$name.'_acl'].',' : '';
				$assignmentsValues .= ', ( '.$row->id.', '.(int)$val.', "site", '.$nOrder.', "'.$typo.'", "'.$submissiondisplay.'", "'.$editiondisplay.'", "'.$value.'", "'.$helper.'", "'.$live.'", "'.$acl.'" ) ';
				$nOrder++;
			}
			$assignmentsValues = substr( $assignmentsValues, 1 );
			$query = 'INSERT INTO #__jseblod_cck_type_item ( typeid, itemid, client, ordering, typography, submissiondisplay, editiondisplay, value, helper, live, acl )'
				   . ' VALUES ' . $assignmentsValues;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		// Delete Field Email Assignements
		$query = 'DELETE FROM #__jseblod_cck_type_item_email WHERE typeid = '.$row->id;
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
					$contentItemValues[$assignedValue[0]]					=	$assignedValue[1];
					$contentItemValues[$assignedValue[0].'_bool']			=	$assignedValue[2];
					$contentItemValues[$assignedValue[0].'_helper'] 		=	$assignedValue[3];
					$contentItemValues[$assignedValue[0].'_link'] 			=	$assignedValue[4];
				}
			}
		}
		$assignmentsValues = null;
		$nCField = count( $data['selected_contentfields'] );
		if ( $nCField ) {
			$nOrder = 1;
			foreach ( $data['selected_contentfields'] as $val ) {
				$vals			=	explode( '-', $val );
				$val 			=	$vals[0];
				$name			=	CCK_DB_Result( 'SELECT s.name FROM #__jseblod_cck_items AS s WHERE s.id='.(int)$val );
				$disp			=	( @$contentItemValues[$name] ) ? $contentItemValues[$name] : '';
				$bool			=	( @$contentItemValues[$name.'_bool'] ) ? $contentItemValues[$name.'_bool'] : 0;
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
				$assignmentsValues .= ', ( '.$row->id.', '.(int)$val.', "content", '.$nOrder.', "'.$disp.'", "'.$bool.'", "'.$helper.'", "'.$link.'", "" ) ';
				$nOrder++;
			}
			$assignmentsValues = substr( $assignmentsValues, 1 );
			$query = 'INSERT INTO #__jseblod_cck_type_item_email ( typeid, itemid, client, ordering, contentdisplay, bool, helper, link, link_helper )'
				   . ' VALUES ' . $assignmentsValues;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}			
		}
		// Insert Email Field Assignements
		$assignmentsValues = null;
		$nEField = count( $data['selected_emailfields'] );
		if ( $nEField ) {
			$nOrder = 1;
			foreach ( $data['selected_emailfields'] as $val ) {
				$vals = explode( '-', $val );
				$val = $vals[0];
				$assignmentsValues .= ', ( '.$row->id.', '.(int)$val.', "email", '.$nOrder.' ) ';
				$nOrder++;
			}
			$assignmentsValues = substr( $assignmentsValues, 1 );
			$query = 'INSERT INTO #__jseblod_cck_type_item_email ( typeid, itemid, client, ordering )'
				   . ' VALUES ' . $assignmentsValues;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}			
		}
		
		// Delete Admin Url Assignements
		$query = 'DELETE FROM #__jseblod_cck_type_url WHERE typeid = '.$row->id;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
				
		// Insert Site Url Assignments
		if ( $data['admin_url'] ) {
			$url_type	=	( $data['admin_url_type'] == 'url_add' || $data['admin_url_type'] == 'url_edit' ) ? ( ( $data['admin_url_type'] == 'url_add' ) ? 'url_add' : 'url_edit' ) : 'url';
			$query	= 'INSERT INTO #__jseblod_cck_type_url ( typeid, urlid, title, url, type )'
				   	. ' VALUES ( '.$row->id.', 1, "'.$data['admin_url'].'", "'.$data['admin_url'].'", "'.$url_type.'" )';
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
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
			$query = 'UPDATE #__jseblod_cck_types'
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
			$query	= 'UPDATE #__jseblod_cck_types'
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
				   . ' FROM #__jseblod_cck_types AS s'
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
		$row =& $this->getTable( 'types' );
		
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
				
				// Delete Assignments From Type|Cat
				$query = 'DELETE FROM #__jseblod_cck_type_cat WHERE typeid = '.$cid;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				
				// Delete Assignments From Type|Url
				$query = 'DELETE FROM #__jseblod_cck_type_url WHERE typeid = '.$cid;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				
				$query = 'DELETE FROM #__jseblod_cck_packs WHERE elemid = '.$cid.' AND type = "type"';
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				
				if ( $deleteMode == -1 ) {
					// DeleteAll: Assignments Type|Field && Fields
					$query = 'DELETE s, cc FROM #__jseblod_cck_type_item AS s'
					.' LEFT JOIN #__jseblod_cck_items AS cc ON s.itemid = cc.id'
					.' WHERE s.itemid = cc.id AND s.itemid NOT IN (1,2,3,10,11,12,13,14,15,22,23,24,25,26,27,120,121,274,290)'
					.' AND s.typeid = '.$cid;
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
					// DeleteAll: Assignments Type|Field Content&Email && Fields
					$query = 'DELETE s, cc FROM #__jseblod_cck_type_item_email AS s'
					.' LEFT JOIN #__jseblod_cck_items AS cc ON s.itemid = cc.id'
					.' WHERE s.itemid = cc.id AND s.itemid NOT IN (1,2,3,10,11,12,13,14,15,22,23,24,25,26,27,120,121,274,290)'
					.' AND s.typeid = '.$cid;
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
				}
				// Delete: Assignments Type|Item
				$query = 'DELETE FROM #__jseblod_cck_type_item WHERE typeid = '.$cid;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
				// Delete: Assignments Type|Item Content&Email
				$query = 'DELETE FROM #__jseblod_cck_type_item_email WHERE typeid = '.$cid;
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
				
			$query = 'UPDATE #__jseblod_cck_types'
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
			$row =& $this->getTable( 'types' );
			
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
			$row =& $this->getTable( 'types' );
			
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
			CCKjSeblodShare_Export::addIntoPack( $cids, 'type', $mode );
			
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
			if ( $file	=	CCKjSeblodShare_Export::exportContent_Types( $inCids, $fileName, $mode, TRUE ) ) {
				return $file;
			}
			
			return false;
		}
		
		return false;
	}
	
	/**
	 * Get Data from Database
	 **/
	function	getContentFields()
	{
		$where	= ' WHERE s.typeid = '.$this->_id.' AND cc.type != 25 AND cc.type != 46 AND s.client = "content"';

  		$query	= 'SELECT cc.name'
  				. ' FROM #__jseblod_cck_type_item_email AS s'
				. ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
  				. $where
				. ' ORDER BY s.ordering'
  				;
    	$this->_db->setQuery( $query );
  		$fields	=	$this->_db->loadResultArray();
		
		return $fields;
	}

	/**
	 * Get Data from Database
	 **/
	function	getFormFields( $client )
	{
		$where	= ' WHERE s.typeid = '.$this->_id.' AND s.client = "'.$client.'"';

  		$query	= 'SELECT cc.name'
  				. ' FROM #__jseblod_cck_type_item AS s'
				. ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
  				. $where
				. ' ORDER BY s.ordering'
  				;
    	$this->_db->setQuery( $query );
  		$fields	=	$this->_db->loadResultArray();
		
		return $fields;
	}

	function createHtml()
	{
		jimport( 'joomla.filesystem.archive' );
		$mode	=	JRequest::getVar( 'html_mode' );

		$row	=&	$this->getTable( 'types' );
		$row->load( $this->_id );
		
		if ( ! $row->name ) {
			return false;
		}
		
		//--begin1
		$html	=	null;
		switch ( $mode) {
			case 1:
				$fields	=&	$this->getFormFields( 'admin' );
				if ( sizeof( $fields ) ) {
					foreach ( $fields as $field ) {
						$html	.=	'<p><?php echo $jSeblod->'.$field.'->form; ?></p>'."\n";
					}
				}
				break;
			case 2:
				$fields	=&	$this->getFormFields( 'site' );
				if ( sizeof( $fields ) ) {
					foreach ( $fields as $field ) {
						$html	.=	'<p><?php echo $jSeblod->'.$field.'->form; ?></p>'."\n";
					}
				}
				break;
			default:
				$fields	=&	$this->getContentFields();
				if ( sizeof( $fields ) ) {
					foreach ( $fields as $field ) {
						$html	.=	'<p><?php echo $jSeblod->'.$field.'->value; ?></p>'."\n";
					}
				}
				break;
		}
		//--end1
		
		$fileName	=	( $mode ) ? $row->name.'_form' : $row->name;
		$config		=&	JFactory::getConfig();
		$tempFolder	=	$config->getValue( 'config.tmp_path' );
		$tmpdir 	=	uniqid('export_');
		$path 		= 	$tempFolder.DS.$tmpdir;

		$title		=	$row->name;
		$titleTab	=	explode( '_', $row->name );
		if ( sizeof( $titleTab ) ) {
			$title	=	null;
			foreach ( $titleTab as $elem ) {
				$title	.=	ucfirst( $elem ).' ';
			}
		}
		$title		=	trim( $title );
		$name		=	( $mode ) ? $row->name.'_form' : $row->name;
		$type		=	( $mode ) ? 'Form' : 'Content';
		$datenow	=&	JFactory::getDate();
		$date		=	$datenow->toFormat( '%B %Y' );
		
		//--begin2
        JFile::write( $path.DS.'index.html', '<html>\n<body bgcolor="#FFFFFF">\n</body>\n</html>' );
		$buffer	=	JFile::read( JPATH_COMPONENT.DS.'helpers'.DS.'tpl'.DS.'index.php', $path.DS.'index.php' );
		$buffer	=	str_replace( '#TITLE#', $title, $buffer );
		$buffer	=	str_replace( '#TYPE#', $type, $buffer );
		$buffer	=	str_replace( '#FIELDS#', $html, $buffer );
		// file_put_contents( $path.DS.'index.php', "\n", FILE_APPEND );
		JFile::write( $path.DS.'index.php', $buffer );
		//
        JFile::write( $path.DS.'params.ini', '' );
		$buffer	=	JFile::read( JPATH_COMPONENT.DS.'helpers'.DS.'tpl'.DS.'params.php', $path.DS.'params.php' );
		$buffer	=	str_replace( '#TITLE#', $title, $buffer );
		$buffer	=	str_replace( '#TYPE#', $type, $buffer );
		JFile::write( $path.DS.'params.php', $buffer );
		$buffer	=	JFile::read( JPATH_COMPONENT.DS.'helpers'.DS.'tpl'.DS.'templateDetails.xml', $path.DS.'templateDetails.xml' );
		$buffer	=	str_replace( '#TITLE#', $title, $buffer );
		$buffer	=	str_replace( '#NAME#', $name, $buffer );
		$buffer	=	str_replace( '#TYPE#', $type, $buffer );
		$buffer	=	str_replace( '#DATE#', $date, $buffer );
		JFile::write( $path.DS.'templateDetails.xml', $buffer );
		JFile::copy( JPATH_COMPONENT.DS.'helpers'.DS.'tpl'.DS.'template_thumbnail.png', $path.DS.'template_thumbnail.png' );
		JFolder::create( $path.DS.'css' );
		JFile::write( $path.DS.'css'.DS.'index.html', '<html>\n<body bgcolor="#FFFFFF">\n</body>\n</html>' );
		JFolder::create( $path.DS.'images' );
        JFile::write( $path.DS.'images'.DS.'index.html', '<html>\n<body bgcolor="#FFFFFF">\n</body>\n</html>' );
		//--end2
		
		$pathArchive	=	$tempFolder.DS.$fileName.'.zip';
		$remove_path	=	$path;
		  
		// Delete existing archives by the same name
		if ( JFile::exists( $pathArchive ) ) {
				if ( !JFile::delete( $pathArchive ) ) {
				return false;
			}
		}
		
		//You can put many file to zip like $pathFile,$pathFile1,$pathFile2
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'pclzip'.DS.'pclzip.lib.php' );
		$archive = new PclZip( $pathArchive );
		if ( $archive->create( $path, PCLZIP_OPT_REMOVE_PATH, $remove_path ) == 0 ) {
			return false; //die( 'Error : ' . $archive->errorInfo( true ) );
		}
		JFolder::delete( $path );
		
		return $archive->zipname;
	}
}
?>
