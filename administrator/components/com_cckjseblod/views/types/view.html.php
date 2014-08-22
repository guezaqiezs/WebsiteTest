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
 * Content Types	View Class
 **/
class CCKjSeblodViewTypes extends JView
{
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isAuth, $categoryFilter, $contentItemFilter ) 
	{
		JToolBarHelper::title(   JText::_( 'CONTENT TYPE MANAGER' ), 'types.png' );
		if ( $categoryFilter ) {
			JToolBarHelper::custom( 'categories', 'back', 'back', JText::_( 'Back' ), false );
			JToolBarHelper::divider();
		}
		if ( $contentItemFilter ) {
			JToolBarHelper::custom( 'back', 'back', 'back', JText::_( 'Back' ), false );
			JToolBarHelper::divider();
		}
		JToolBarHelper::custom( 'addcategory', 'new-category', 'new-category', JText::_( 'New' ), false );
		JToolBarHelper::custom( 'categories', 'categories', 'categories', JText::_( 'Categories' ), false );
		JToolBarHelper::divider();
		JToolBarHelper::custom( 'createHtml', 'html_jseblod', 'html_jseblod', JText::_( 'GENERATE' ), true );
		if ( $isAuth ) {
			JToolBarHelper::custom( 'publish', 'publish_jseblod', 'publish_jseblod', JText::_( 'Publish' ), true ); //JToolBarHelper::publishList();
			JToolBarHelper::custom( 'unpublish', 'unpublish_jseblod', 'unpublish_jseblod', JText::_( 'Unpublish' ), true ); //JToolBarHelper::unpublishList();
			switch( _TYPE_DELETE_MODE ) {
				case '1':
					JToolBarHelper::custom( 'remove', 'delete_jseblod', 'delete_jseblod', JText::_( 'Delete' ), true ); //JToolBarHelper::deleteList();
					break;
				case '0':
					JToolBarHelper::custom( 'delete', 'delete_jseblod', 'delete_jseblod', JText::_( 'Delete' ), true );
					break;
				case '-1':
					JToolBarHelper::custom( 'removeAll', 'delete_jseblod', 'delete_jseblod', JText::_( 'Delete' ), true );
					break;	
				default:
					break;
			}
			JToolBarHelper::custom( 'copy', 'copy2edit', 'copy', JText::_( 'Copy' ), true );
			JToolBarHelper::editListX();
			JToolBarHelper::custom( 'add', 'new_jseblod', 'new_jseblod', JText::_( 'New' ), false ); //JToolBarHelper::addNewX();
		} else {
			JToolBarHelper::editListX( 'edit', JText::_( 'View' ) );
		}
		JToolBarHelper::divider();
		JToolBarHelper::custom( 'cpanel', 'jseblod', 'cpanel', JText::_( 'CPANEL' ), false );
		HelperjSeblod_Display::help( 'types' );
	}
	
	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$user 				=& JFactory::getUser();
		$controller 		= JRequest::getWord( 'controller' );
		$model 				=&	$this->getModel();
		$document			=& JFactory::getDocument();
		$object				=	JRequest::getVar( 'object' );
		$task				=	JRequest::getVar( 'layout' );
		$contentItemFilter 	= JRequest::getInt( 'contentfilter' );
		$categoryFilter 	= JRequest::getInt( 'categoryfilter' );
		$templateFilter		=	JRequest::getInt( 'templatefilter' );
		
		$from				= JRequest::getVar( 'from' );
		$into				= JRequest::getVar( 'into' );
		
		$layout		=	$this->getLayout();
		if ( $layout == 'element' ) {
			$action		=	JRequest::getVar( 'action' );
		} else {
			$action		=	0;
		}
		
		// Get Data from Model
		$pagination			=	$model->getPagination( $layout, $action );
		$types_items		=	$model->getData( $layout, $action );
    
		// Set Flags
		$isAuth = ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
		
		// Get User State
		$filter_restricted	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_restricted', 'filter_restricted', _RESTRICTION_TYPE,		'int' );
		$filter_category	= ( $categoryFilter || $contentItemFilter ) ? 0 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_category',		'filter_category',		0,			'int' );
		$filter_assignment	= ( $categoryFilter || $contentItemFilter ) ? 0 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_assignment',	'filter_assignment',	0,			'int' );
		$filter_state		= ( $task == 'element' ) ? 'P' : ( ( $categoryFilter || $contentItemFilter ) ? '' : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_state',			'filter_state',			'',			'word' ) );
		$filter_search		= ( $templateFilter ) ? 9 : ( ( $categoryFilter || $contentItemFilter ) ? ( ( $contentItemFilter ) ? 8 : 5 ) : ( $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_search',	'filter_search', 0, 'int' ) ) );
		$search				= ( $templateFilter ) ? $templateFilter : ( ( $categoryFilter || $contentItemFilter ) ? ( ( $contentItemFilter ) ? $contentItemFilter : $categoryFilter ) : ( $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.search', 'search', '', 'string' ) ) );
		$search				= JString::strtolower( $search );
		//
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order',			'filter_order',			's.title',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order_Dir',		'filter_order_Dir',		'asc',		'cmd' );
		
		// Set Table Ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		// Set Search Filter
		$options_search[] 		= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'CONTENT TYPES' ) );
		$options_search[] 		= JHTML::_( 'select.option', '0', JText::_( 'Title' ) );
		$options_search[] 		= JHTML::_( 'select.option', '1', JText::_( 'Name' ) );
		$options_search[] 		= JHTML::_( 'select.option', '2', JText::_( 'Description' ) );
		$options_search[] 		= JHTML::_('select.option', '3', JText::_( 'MINUS ID' ) . '&nbsp;(*)' );
		$options_search[]		= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$options_search[] 		= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'TYPE CATEGORIES' ) );
		$options_search[] 		= JHTML::_( 'select.option', '4', JText::_( 'CATEGORY TITLE' ) );
		$options_search[] 		= JHTML::_( 'select.option', '5', JText::_( 'CATEGORY ID' ) . '&nbsp;(*)' );
		$options_search[]		= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$options_search[] 		= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'CONTENT ITEM' ) );
		$options_search[] 		= JHTML::_('select.option', '6', JText::_( 'CONTENT ITEM TITLE' ) );
		$options_search[] 		= JHTML::_('select.option', '7', JText::_( 'CONTENT ITEM NAME' ) );
		$options_search[] 		= JHTML::_('select.option', '8', JText::_( 'CONTENT ITEM ID' ) . '&nbsp;(*)' );
		$options_search[]		= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$options_search[] 		= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'TEMPLATE' ) );
		$options_search[] 		= JHTML::_('select.option', '9', JText::_( 'TEMPLATE ID' ) . '&nbsp;(*)' );
		$options_search[]		= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$lists['filter_search'] = JHTML::_( 'select.genericlist', $options_search, 'filter_search', 'size="1" class="inputbox"', 'value', 'text', $filter_search );

		// Set Search Box
		$lists['search']	= $search;

		// Set State Filter
		$lists['state']		= JHTML::_( 'grid.state',  $filter_state );
		
		// Set Category Filter
		$javascript 	= 'onchange="document.adminForm.submit();"';
		$optionCategories	= array();
		$optionCategories[]	= JHTML::_( 'select.option',  '0', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
		$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
		$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
		$optionCategories	= array_merge( $optionCategories, HelperjSeblod_Helper::getTypeCategories( false, true ) );
		$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$lists['category']	= JHTML::_('select.genericlist', $optionCategories, 'filter_category', $javascript.'class="filter-margin"', 'value', 'text', $filter_category );

		// Set Category List ( Select List )
		$optionCategories	= array();
		$optionCategories[]	= JHTML::_( 'select.option',  '0', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
		$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
		$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
		$optionCategories	= array_merge( $optionCategories, HelperjSeblod_Helper::getTypeCategories( true, true ) );
		$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$lists['batch_category']	=	JHTML::_( 'select.genericlist', $optionCategories, 'category', 'class="inputbox" size="1"', 'value', 'text', '' );
		
		// Set Search Filter
		$optionAssignments[]	= JHTML::_( 'select.option',  '0', JText::_( 'SELECT ADMIN VIEWS STATE' ), 'value', 'text' );
		$optionAssignments[] 	= JHTML::_( 'select.option', '1', JText::_( 'ASSIGNED' ) );
		$optionAssignments[]	= JHTML::_( 'select.option', '-1', JText::_( 'UNASSIGNED' ) );
		$lists['assignment'] 	= JHTML::_( 'select.genericlist', $optionAssignments, 'filter_assignment', $javascript, 'value', 'text', $filter_assignment );
		// Set Restriction Filter
		$optRestricted[]		= JHTML::_( 'select.option',  3, JText::_( 'HIGHER' ), 'value', 'text' );
		$optRestricted[]		= JHTML::_( 'select.option',  2, JText::_( 'HIGH' ), 'value', 'text' );
		$optRestricted[]		= JHTML::_( 'select.option',  1, JText::_( 'MEDIUM' ), 'value', 'text' );
		$optRestricted[]		= JHTML::_( 'select.option',  0, JText::_( 'LOW' ), 'value', 'text' );
		$lists['restricted']	= JHTML::_( 'select.genericlist', $optRestricted, 'filter_restricted', $javascript, 'value', 'text', $filter_restricted );
		
		// Set Process List ( Select List )
		$optExportPackTemplates		=	array();
		$optExportPackTemplates[]	=	JHTML::_( 'select.option',  '3', JText::_( 'WITH ITEMS AND TEMPLATES' ), 'value', 'text' );
		$optExportPackTemplates[]	=	JHTML::_( 'select.option',  '2', JText::_( 'WITH ITEMS' ), 'value', 'text' );
		$optExportPackTemplates[]	=	JHTML::_( 'select.option',  '1', JText::_( 'WITH TEMPLATES' ), 'value', 'text' );
		$optExportPackTemplates[]	=	JHTML::_( 'select.option',  '0', JText::_( 'WITH NOTHING' ), 'value', 'text' );
		$lists['export_mode']		=	JHTML::_( 'select.genericlist', $optExportPackTemplates, 'export_mode', 'class="inputbox" size="1"', 'value', 'text', '3' );
		
		$lists['add_mode']			=	JHTML::_( 'select.genericlist', $optExportPackTemplates, 'add_mode', 'class="inputbox" size="1"', 'value', 'text', '3' );
		
		// Set Process List ( Select List )
		$optionImportProcess	= array();
		$optionImportProcess[]	= JHTML::_( 'select.option',  '0', JText::_( 'IGNORE EXISTING' ), 'value', 'text' );
		$optionImportProcess[]	= JHTML::_( 'select.option',  '1', JText::_( 'UPDATE EXISTING' ), 'value', 'text' );
		$lists['import_mode']	=	JHTML::_( 'select.genericlist', $optionImportProcess, 'import_mode', 'class="inputbox" size="1"', 'value', 'text', '0' );
		
		// Create Go to Pack Checkbox
		$optGoToPack[] 		= JHTML::_( 'select.option',  '1', JText::_( 'GO TO PACK' ) );
		$lists['goToPack'] 	= HelperjSeblod_Helper::checkBoxList( $optGoToPack, 'add_redirection', '', 'value', 'text', 1 );
		
		// Set Process List ( Select List )
		$optHtmlMode			=	array();
		$optHtmlMode[]			=	JHTML::_( 'select.option',  '0', JText::_( 'CONTENT' ), 'value', 'text' );
		$optHtmlMode[]			=	JHTML::_( 'select.option',  '1', JText::_( 'ADMIN FORM TAB' ), 'value', 'text' );
		$optHtmlMode[]			=	JHTML::_( 'select.option',  '2', JText::_( 'SITE FORM TAB' ), 'value', 'text' );
		$lists['html_mode']		=	JHTML::_( 'select.genericlist', $optHtmlMode, 'html_mode', 'class="inputbox" size="1"', 'value', 'text', '0' );
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document',	$document );
		$this->assignRef( 'object',	$object );
		$this->assignRef( 'from', $from );
		$this->assignRef( 'into', $into );
		$this->assignRef( 'types_items', $types_items );
		$this->assignRef( 'pagination', $pagination );
		$this->assignRef( 'isAuth', $isAuth );
		$this->assignRef( 'lists', $lists );
		
		$this->assignRef( 'action', $action );
		
		$this->_displayToolbar( $isAuth, $categoryFilter, $contentItemFilter );
		
		parent::display( $tpl );
	}
	
}
?>