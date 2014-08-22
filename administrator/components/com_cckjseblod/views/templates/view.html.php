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
 * Content Templates	View Class
 **/
class CCKjSeblodViewTemplates extends JView
{
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isAuth, $categoryFilter ) 
	{
		JToolBarHelper::title(   JText::_( 'TEMPLATE MANAGER' ), 'templates.png' );
		if ( $categoryFilter ) {
			JToolBarHelper::custom( 'categories', 'back', 'back', JText::_( 'Back' ), false );
			JToolBarHelper::divider();
		}
		if ( $isAuth ) {
			JToolBarHelper::custom( 'addcategory', 'new-category', 'new-category', JText::_( 'New' ), false );
		}
		JToolBarHelper::custom( 'categories', 'categories', 'categories', JText::_( 'Categories' ), false );
		JToolBarHelper::custom( 'views', 'views', 'views', JText::_( 'VIEWS' ), false );
		JToolBarHelper::divider();
		if ( $isAuth ) {
			JToolBarHelper::custom( 'publish', 'publish_jseblod', 'publish_jseblod', JText::_( 'Publish' ), true ); //JToolBarHelper::publishList();
			JToolBarHelper::custom( 'unpublish', 'unpublish_jseblod', 'unpublish_jseblod', JText::_( 'Unpublish' ), true ); //JToolBarHelper::unpublishList();
			switch( _TEMPLATE_DELETE_MODE ) {
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
		JToolBarHelper::custom( 'cpanel', 'jseblod', 'cpanel', JText::_( 'CPanel' ), false );
		HelperjSeblod_Display::help( 'templates' );
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
		$categoryFilter 	= JRequest::getInt( 'categoryfilter' );
		$into				= JRequest::getVar( 'into' );
		
		$view = JRequest::getWord( 'view' ); //TODO CHECK??
		
		// Get Data from Model
		$pagination 		=& $this->get( 'Pagination' );
		$templates_items	=& $this->get( 'Data' );
		$selectCat 			=	JRequest::getInt( 'selectcat' );
		
		// Set Flags
		$isAuth = ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
		
		// Get User State
		$filter_category	= ( $categoryFilter ) ? 0 : ( ( $selectCat ) ? $selectCat : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_category',	'filter_category',	0,		'int' ) );
		$filter_assignment	= ( $categoryFilter ) ? 0 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_assignment',	'filter_assignment',		0,		'int' );
		$filter_state		= ( $task == 'element' ) ? 'P' : ( ( $categoryFilter ) ? '' : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_state',		'filter_state',		'',			'word' ) );
		$filter_type		=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_type',	'filter_type',	'',	'string' );
		$filter_mode		=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_mode',	'filter_mode',	'',	'string' );
		$filter_search		= ( $categoryFilter ) ? 5 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_search',	'filter_search',	0,		'int' );
		$search				= ( $categoryFilter ) ? $categoryFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.search',			'search',			'',		'string' );
		$search				= JString::strtolower( $search );
		//
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order',		'filter_order',		's.title',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.'.$task.'.filter_order_Dir',	'filter_order_Dir',	'asc',		'cmd' );
		
		// Create Search Filter
		$options_search[] 	= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'TEMPLATES' ) );
		$options_search[] 	= JHTML::_( 'select.option', '0', JText::_( 'Title' ) );
		$options_search[] 	= JHTML::_( 'select.option', '1', JText::_( 'Name' ) );
		$options_search[] 	= JHTML::_( 'select.option', '2', JText::_( 'Description' ) );
		$options_search[] 	= JHTML::_('select.option', '3', JText::_( 'MINUS ID' ) . '&nbsp;(*)' );
		$options_search[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$options_search[] 	= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'TEMPLATE CATEGORIES' ) );
		$options_search[] 	= JHTML::_( 'select.option', '4', JText::_( 'CATEGORY TITLE' ) );
		$options_search[] 	= JHTML::_( 'select.option', '5', JText::_( 'CATEGORY ID' ) . '&nbsp;(*)' );
		$options_search[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$lists['filter_search'] = JHTML::_( 'select.genericlist', $options_search, 'filter_search', 'size="1" class="inputbox"', 'value', 'text', $filter_search );
		
		// Create Search Box
		$lists['search']= $search;
		
		// Create State Filter
		$lists['state']	= JHTML::_( 'grid.state',  $filter_state );
		
		// Set Category Filter
		$javascript 	= 'onchange="document.adminForm.submit();"';
		$optionCategories	= array();
		$optionCategories[]	= JHTML::_( 'select.option',  '0', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
		$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
		$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
		$optionCategories	= array_merge( $optionCategories, HelperjSeblod_Helper::getTemplateCategories( false, true ) );
		$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$class				=	( $task == 'element' ? '' : 'class="filter-margin"' );
		$lists['category']	= JHTML::_('select.genericlist', $optionCategories, 'filter_category', $javascript.$class, 'value', 'text', $filter_category );
		
		// Set Category List ( Select List )
		$optionCategories	= array();
		$optionCategories[]	= JHTML::_( 'select.option',  '0', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
		$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
		$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
		$optionCategories	= array_merge( $optionCategories, HelperjSeblod_Helper::getTemplateCategories( true, true ) );
		$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$lists['batch_category']	=	JHTML::_( 'select.genericlist', $optionCategories, 'category', 'class="inputbox" size="1"', 'value', 'text', '' );
		
		// Set Assignments Filter
		$optionAssignments[]	= JHTML::_( 'select.option',  '0', JText::_( 'SELECT SITE VIEWS STATE' ), 'value', 'text' );
		$optionAssignments[] 	= JHTML::_( 'select.option', '1', JText::_( 'ASSIGNED' ) );
		$optionAssignments[]	= JHTML::_( 'select.option', '-1', JText::_( 'UNASSIGNED' ) );
		$lists['filter_view'] 	= JHTML::_( 'select.genericlist', $optionAssignments, 'filter_assignment', $javascript, 'value', 'text', $filter_assignment );
		
		// Set Assignments Filter
		$optType[]				=	JHTML::_( 'select.option', '', JText::_( 'SELECT A TYPE' ), 'value', 'text' );
		$optType[] 				=	JHTML::_( 'select.option', 0, JText::_( 'CONTENT' ) );
		$optType[] 				=	JHTML::_( 'select.option', 1, JText::_( 'FORM' ) );
		$optType[]				=	JHTML::_( 'select.option', 2, JText::_( 'LIST' ) );
		$optType[]				=	JHTML::_( 'select.option', 5, JText::_( 'GRID' ) );
		$lists['filter_type'] 	=	JHTML::_( 'select.genericlist', $optType, 'filter_type', $javascript, 'value', 'text', $filter_type );
		
		// Set Assignments Filter
		$optMode[]				=	JHTML::_( 'select.option', '', JText::_( 'SELECT A MODE' ), 'value', 'text' );
		$optMode[] 				=	JHTML::_( 'select.option', 1, JText::_( 'AUTO' ) );
		$optMode[] 				=	JHTML::_( 'select.option', 0, JText::_( 'CUSTOM' ) );
		$lists['filter_mode']	=	JHTML::_( 'select.genericlist', $optMode, 'filter_mode', $javascript, 'value', 'text', $filter_mode );
		
		// Create Table Ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		// Create Go to Pack Checkbox
		$optGoToPack[] 		= JHTML::_( 'select.option',  '1', JText::_( 'GO TO PACK' ) );
		$lists['goToPack'] 	= HelperjSeblod_Helper::checkBoxList( $optGoToPack, 'add_redirection', '', 'value', 'text', 1 );
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document',	$document );
		$this->assignRef( 'into', $into );
		$this->assignRef( 'view', $view );  //??TODO::check!
		$this->assignRef( 'templates_items', $templates_items );
		$this->assignRef( 'pagination', $pagination );
		$this->assignRef( 'isAuth', $isAuth );
		$this->assignRef( 'lists', $lists );
		
		$this->_displayToolbar( $isAuth, $categoryFilter );
		
		parent::display( $tpl );
	}
	
}
?>