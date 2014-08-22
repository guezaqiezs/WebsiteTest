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

jimport( 'joomla.application.component.view' );

/**
 * Content Items	View Class
 **/
class CCKjSeblodViewItems extends JView
{
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isAuth, $categoryFilter, $contentTypeFilter ) 
	{
		JToolBarHelper::title(   JText::_( 'FIELD MANAGER' ), 'items.png' );
		if ( $categoryFilter ) {
			JToolBarHelper::custom( 'categories', 'back', 'back', JText::_( 'Back' ), false );
			JToolBarHelper::divider();
		}
		if ( $contentTypeFilter ) {
			JToolBarHelper::custom( 'back', 'back', 'back', JText::_( 'Back' ), false );
			JToolBarHelper::divider();
		}
		JToolBarHelper::custom( 'addcategory', 'new-category', 'new-category', JText::_( 'New' ), false );
		JToolBarHelper::custom( 'categories', 'categories', 'categories', JText::_( 'Categories' ), false );
		if ( $isAuth ) {
			HelperjSeblod_Display::quickToolbarReserved();
			JToolBarHelper::divider();
			JToolBarHelper::custom( 'remove', 'delete_jseblod', 'delete_jseblod', JText::_( 'Delete' ), true ); //JToolBarHelper::deleteList();
			JToolBarHelper::custom( 'copy', 'copy2edit', 'copy', JText::_( 'Copy' ), true );
			JToolBarHelper::editListX();
			JToolBarHelper::custom( 'add', 'new_jseblod', 'new_jseblod', JText::_( 'New' ), false ); //JToolBarHelper::addNewX();
		} else {
			JToolBarHelper::divider();
			JToolBarHelper::editListX( 'edit', JText::_( 'View' ) );
		}
		JToolBarHelper::divider();
		JToolBarHelper::custom( 'cpanel', 'jseblod', 'cpanel', JText::_( 'CPanel' ), false );
		HelperjSeblod_Display::help( 'items' );
	}
	
	/**
	 * Display Default View
	 **/
	function display($tpl = null)
	{
		// Get Request Vars
		global $mainframe, $option;
		$user 				=& JFactory::getUser();
		$controller 		= JRequest::getWord('controller');
		$document			=& JFactory::getDocument();
		$task				=	JRequest::getVar( 'layout' );
		$clientFilter 		= JRequest::getWord( 'clientfilter' );
		$contentTypeFilter 	= JRequest::getInt( 'contentfilter' );
		$searchTypeFilter 	= JRequest::getInt( 'searchfilter' );
		$categoryFilter 	= JRequest::getInt( 'categoryfilter' );
		$into				= JRequest::getVar( 'into' );
		$extra				= JRequest::getVar( 'extra' );
				
		$doReserved	= JRequest::getVar( 'doReserved', false );
		
		if ( $doReserved ) {
			$doClose	=	JRequest::getVar( 'close', false );
			$reservedData 	=& $this->get( 'reservedData' );
			
			$reservedItems	=	array();
			$reservedItems	=	array_merge( $reservedItems, $reservedData );
			$lists['reservedItems']	=	JHTML::_( 'select.genericlist', $reservedItems, 'reserved_items[]', 'class="inputbox" size="19" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			
			// Push Data into Template
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			//
			$this->assignRef( 'doClose', $doClose );
			$this->assignRef( 'lists', $lists );
			
		} else {
			
			// Get Data from Model
			$pagination 	=& $this->get('Pagination');
			$itemsItems 	=& $this->get( 'Data' );    
			
			// Set Flags
			$isAuth = ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
			
			// Get User State
			$filter_restricted		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_restricted', 'filter_restricted', _RESTRICTION_FIELD,		'int' );
			$filter_category		= ( $categoryFilter ) ? 0 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_category',	'filter_category',	0,			'int' );
			$filter_type			= ( $categoryFilter ) ? 0 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_type',		'filter_type',		0,			'int' );
			$filter_title			=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_title',		'filter_title',		-1,			'int' );
			$filter_index			=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_index',		'filter_index',		-1,			'int' );
			$filter_content_type	= ( $categoryFilter ) ? 0 : ( ( $contentTypeFilter ) ? $contentTypeFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_content_type',	'filter_content_type',	0,	'int' ) );
			$filter_search_type	= ( $categoryFilter ) ? 0 : ( ( $searchTypeFilter ) ? $searchTypeFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_search_type',	'filter_search_type',	0,	'int' ) );
			$filter_client			= ( $categoryFilter ) ? 0 : ( ( $clientFilter ) ? $clientFilter :	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_client',	'filter_client',	'',	'word' ) );
			$filter_search			= ( $categoryFilter ) ? 6 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_search',	'filter_search', 0, 'int' );
			$search					= ( $categoryFilter ) ? $categoryFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.search', 'search', '', 'string' );
			$search					= JString::strtolower( $search );
			//
			$call_ordering			=	( $contentTypeFilter && $clientFilter ) ? 1 : JRequest::getInt( 'call_ordering' );
			$filter_order			=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order',		'filter_order',		's.title',	'cmd' );
			$filter_order_Dir		=	( $filter_content_type && $filter_client && $call_ordering ) ? 'asc' : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order_Dir',	'filter_order_Dir',	'asc',		'cmd' );
			$filter_order			=	( $filter_content_type && $filter_client && $call_ordering ) ? 'ccc.ordering' : $filter_order;
			$filter_order_Dir		=	( $filter_order == 'ccc.ordering' && ( ! $filter_content_type || ! $filter_client ) ) ? 'asc' : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order_Dir',	'filter_order_Dir',	'asc',		'cmd' );
			$filter_order			=	( $filter_order == 'ccc.ordering' && ( ! $filter_content_type || ! $filter_client ) ) ? 's.title' : $filter_order;

			// Set Search Filter
			$options_search[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'CONTENT ITEMS' ) );
			$options_search[] = JHTML::_('select.option', '0', JText::_( 'Title' ) );
			$options_search[] = JHTML::_('select.option', '1', JText::_( 'Name' ) );
			$options_search[] = JHTML::_('select.option', '2', JText::_( 'Description' ) );
			$options_search[] = JHTML::_('select.option', '3', JText::_( 'Type' ) );
			$options_search[] = JHTML::_('select.option', '4', JText::_( 'MINUS ID' ) . '&nbsp;(*)' );
			$options_search[] = JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$options_search[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'ITEM CATEGORIES' ) );
			$options_search[] = JHTML::_( 'select.option', '5', JText::_( 'CATEGORY TITLE' ) );
			$options_search[] = JHTML::_( 'select.option', '6', JText::_( 'CATEGORY ID' ) . '&nbsp;(*)' );
			$options_search[] = JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$options_search[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'CONTENT TYPES' ) );
			$options_search[] = JHTML::_('select.option', '7', JText::_( 'CONTENT TYPE TITLE' ) );
			$options_search[] = JHTML::_('select.option', '8', JText::_( 'CONTENT TYPE NAME' ) );
			$options_search[] = JHTML::_('select.option', '9', JText::_( 'CONTENT TYPE ID' ) . '&nbsp;(*)' );
			$options_search[] = JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$lists['filter_search'] = JHTML::_('select.genericlist', $options_search, 'filter_search', 'size="1" class="inputbox"', 'value', 'text', $filter_search );
			
			// Set Search Box
			$lists['search']= $search;

			// Set Category Filter
			$javascript 			= 'onchange="document.adminForm.submit();"';
			$javascript_ordering 	= 'onchange="document.adminForm.getElementById(\'call_ordering\').value=\'1\';document.adminForm.submit();"';
			$optionCategories	= array();
			$optionCategories[]	= JHTML::_( 'select.option',  '0', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
			$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
			$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
			$optionCategories	= array_merge( $optionCategories, HelperjSeblod_Helper::getItemCategories( false, true ) );
			$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$lists['category']	= JHTML::_('select.genericlist', $optionCategories, 'filter_category', $javascript.'class="filter-margin"', 'value', 'text', $filter_category );
			
			// Set Category List ( Select List )
			$optionCategories	= array();
			$optionCategories[]	= JHTML::_( 'select.option',  '0', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
			$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
			$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
			$optionCategories	= array_merge( $optionCategories, HelperjSeblod_Helper::getItemCategories( true, true ) );
			$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$lists['batch_category']	=	JHTML::_( 'select.genericlist', $optionCategories, 'category', 'class="inputbox" size="1"', 'value', 'text', '' );
			
			// Set Content Type List ( Select List )
			$optionContentTypes		= array();
			$optionContentTypes[]	= JHTML::_( 'select.option',  '0', JText::_( 'SELECT A CONTENT TYPE' ), 'value', 'text' );
			$optionContentTypes		= array_merge( $optionContentTypes, HelperjSeblod_Helper::getContentTypes() );
			$lists['batch_types']	=	JHTML::_( 'select.genericlist', $optionContentTypes, 'type', 'class="inputbox" size="1"', 'value', 'text', '' );

			// Set Content Type List ( Select List )
			$optionSearchTypes		=	array();
			$optionSearchTypes[]	=	JHTML::_( 'select.option',  '0', JText::_( 'SELECT A SEARCH TYPE' ), 'value', 'text' );
			$optionSearchTypes		=	array_merge( $optionSearchTypes, HelperjSeblod_Helper::getSearchTypes() );
			$lists['search_type']	=	JHTML::_( 'select.genericlist', $optionSearchTypes, 'filter_search_type', $javascript.'class="filter-margin"', 'value', 'text', $filter_search_type );

			// Set Content Type Filter
			$lists['content_type']	= JHTML::_('select.genericlist', $optionContentTypes, 'filter_content_type', $javascript_ordering, 'value', 'text', $filter_content_type );
			
			// Set Process List ( Select List )
			$optionTypesProcess		= array();
			$optionTypesProcess[]	= JHTML::_( 'select.option',  '1', JText::_( 'ADMIN ADD TO' ), 'value', 'text' );
			$optionTypesProcess[]	= JHTML::_( 'select.option',  '2', JText::_( 'SITE ADD TO' ), 'value', 'text' );
			$optionTypesProcess[]	= JHTML::_( 'select.option',  '3', JText::_( 'BOTH ADD TO' ), 'value', 'text' );
			$optionTypesProcess[]	= JHTML::_( 'select.option',  '4', JText::_( 'ADMIN DEL FROM' ), 'value', 'text' );
			$optionTypesProcess[]	= JHTML::_( 'select.option',  '5', JText::_( 'SITE DEL FROM' ), 'value', 'text' );
			$optionTypesProcess[]	= JHTML::_( 'select.option',  '6', JText::_( 'BOTH DEL FROM' ), 'value', 'text' );
			$lists['batch_types_process']	=	JHTML::_( 'select.genericlist', $optionTypesProcess, 'type_process', 'class="inputbox" size="1"', 'value', 'text', '1' );
			
			// Set client Filter
			$optionsClient[]	= JHTML::_( 'select.option',  '', JText::_( 'SELECT A CLIENT' ), 'value', 'text' );
			$optionsClient[]	= JHTML::_( 'select.option',  'admin', JText::_( 'ADMIN BACKEND' ), 'value', 'text' );
			$optionsClient[]	= JHTML::_( 'select.option',  'site', JText::_( 'SITE FRONTEND' ), 'value', 'text' );
			$optionsClient[]	= JHTML::_( 'select.option',  'both', JText::_( 'ADMIN AND OR SITE' ), 'value', 'text' );
			$optionsClient[]	= JHTML::_( 'select.option',  'none', JText::_( 'NONE NOT USED' ), 'value', 'text' );
			$lists['client']	= JHTML::_( 'select.genericlist', $optionsClient, 'filter_client', $javascript_ordering, 'value', 'text', $filter_client );

			// Set Restriction Filter
			$optRestricted[]		= JHTML::_( 'select.option',  3, JText::_( 'HIGHER' ), 'value', 'text' );
			$optRestricted[]		= JHTML::_( 'select.option',  2, JText::_( 'HIGH' ), 'value', 'text' );
			$optRestricted[]		= JHTML::_( 'select.option',  1, JText::_( 'MEDIUM' ), 'value', 'text' );
			$optRestricted[]		= JHTML::_( 'select.option',  0, JText::_( 'LOW' ), 'value', 'text' );
			$lists['restricted']	= JHTML::_( 'select.genericlist', $optRestricted, 'filter_restricted', $javascript, 'value', 'text', $filter_restricted );
			
			// Set Item Type Filter
			$optionTypes[]	=	JHTML::_('select.option',  '0', JText::_( 'SELECT A TYPE' ), 'value', 'text' );
			$optionTypes[]	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'ACTION' ) );
			$optionTypes[]	=	JHTML::_('select.option',  "25", JText::_( 'FORM ACTION' ), 'value', 'text' );
			$optionTypes[]	=	JHTML::_('select.option',  "46", JText::_( 'SEARCH ACTION' ), 'value', 'text' );
			$optionTypes[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$optionTypes[]	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'COMMON' ) );
			$optionTypes		= array_merge( $optionTypes, HelperjSeblod_Helper::getItemTypesById() );
			$optionTypes[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$lists['type']	=	JHTML::_('select.genericlist', $optionTypes, 'filter_type', $javascript.' style="padding-right: 8px;"', 'value', 'text', $filter_type );

			// Set Title Filter
			$optTitle[]		=	JHTML::_('select.option', -1, '- '.JText::_( 'TITLE' ).' -', 'value', 'text' );
			$optTitle[]		=	JHTML::_('select.option', 1, JText::_( 'YES' ), 'value', 'text' );
			$optTitle[]		=	JHTML::_('select.option', 0, JText::_( 'NO' ), 'value', 'text' );
			$lists['title']	=	JHTML::_('select.genericlist', $optTitle, 'filter_title', $javascript, 'value', 'text', $filter_title );

			// Set Key Filter
			$optIndex[]		=	JHTML::_('select.option', -1, '- '.JText::_( 'INDEX' ).' -', 'value', 'text' );
			$optIndex[]		=	JHTML::_('select.option', 1, JText::_( 'YES' ), 'value', 'text' );
			$optIndex[]		=	JHTML::_('select.option', 0, JText::_( 'NO' ), 'value', 'text' );
			$lists['index']	=	JHTML::_('select.genericlist', $optIndex, 'filter_index', $javascript, 'value', 'text', $filter_index );
			
			// Create Table Ordering
			$lists['order_Dir']	= $filter_order_Dir;
			$lists['order']		= $filter_order;
			
			// Set Process List ( Select List )
			$optionImportProcess	= array();
			$optionImportProcess[]	= JHTML::_( 'select.option',  '0', JText::_( 'IGNORE EXISTING' ), 'value', 'text' );
			$optionImportProcess[]	= JHTML::_( 'select.option',  '1', JText::_( 'UPDATE EXISTING' ), 'value', 'text' );
			$lists['import_mode']	=	JHTML::_( 'select.genericlist', $optionImportProcess, 'import_mode', 'class="inputbox" size="1"', 'value', 'text', '0' );
			
			// Create Go to Pack Checkbox
			$optGoToPack[] 		= JHTML::_( 'select.option',  '1', JText::_( 'GO TO PACK' ) );
			$lists['goToPack'] 	= HelperjSeblod_Helper::checkBoxList( $optGoToPack, 'add_redirection', '', 'value', 'text', 1 );
			
			// Push Data into Template
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			
			$this->assignRef( 'filter_content_type', $filter_content_type );
			$this->assignRef( 'filter_client', $filter_client );
			
			$this->assignRef( 'into', $into );
			$this->assignRef( 'extra', $extra );
			$this->assignRef( 'itemsItems', $itemsItems );
			$this->assignRef( 'pagination', $pagination);
			$this->assignRef( 'isAuth', $isAuth );
			$this->assignRef( 'lists', $lists );
			
			$this->_displayToolbar( $isAuth, $categoryFilter, $contentTypeFilter );
		
		}
		
		parent::display( $tpl );
	}
	
}
?>