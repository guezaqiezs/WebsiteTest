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
 * Types_Categories		View Class
 **/
class CCKjSeblodViewTypes_Categories extends JView
{
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isAuth ) 
	{
		JToolBarHelper::title(   JText::_( 'TYPE CATEGORY MANAGER' ), 'category.png' );
		JToolBarHelper::custom( 'back', 'back', 'back', JText::_( 'Back' ), false );
		JToolBarHelper::divider();
		if ( $isAuth ) {
			JToolBarHelper::custom( 'publish', 'publish_jseblod', 'publish_jseblod', JText::_( 'Publish' ), true ); //JToolBarHelper::publishList();
			JToolBarHelper::custom( 'unpublish', 'unpublish_jseblod', 'unpublish_jseblod', JText::_( 'Unpublish' ), true ); //JToolBarHelper::unpublishList();
			switch( _TYPE_CATEGORY_DELETE_MODE ) {
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
		HelperjSeblod_Display::help( 'types_categories' );
	}
	
	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$controller		=	JRequest::getWord( 'controller' );
		$document		=&	JFactory::getDocument();
		$model 			=&	$this->getModel();
			$task				=	JRequest::getVar( 'layout' );
		$user 			=&	JFactory::getUser();
		$filterin		=	JRequest::getInt( 'filterin' );
		$categoryFilter	=	JRequest::getInt( 'categoryfilter' );
		
		// Get Data from Model
		$pagination		=&	$this->get( 'Pagination' );
		$categoriesItems	=&	$this->get( 'Data' );
		
		// Set Flags
		$isAuth = ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
		
		// Get User State
		$filter_order		=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order',		'filter_order',		's.lft',	'cmd' );
		$filter_order_Dir	=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order_Dir',	'filter_order_Dir',	'asc',		'cmd' );
		$filter_category	=	( $categoryFilter ) ? $categoryFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_category', 'filter_category', 0, 'int' );
		$filter_state		=	$mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_state',		'filter_state',		'',			'word' );
		$filter_search		=	( $filterin ) ? 3 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_search',		'filter_search',	0,			'int' );
		$search				=	( $filterin ) ? $filterin : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.search',				'search',			'',			'string' );
		$search				=	JString::strtolower( $search );
		
		// Set Table Ordering
		$lists['order']		=	$filter_order;
		$lists['order_Dir']	=	$filter_order_Dir;
		
		// Set Search Filter
		$optSearch[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'TYPE CATEGORIES' ) );
		$optSearch[] 		=	JHTML::_( 'select.option', '0', JText::_( 'Title' ) );
		$optSearch[] 		=	JHTML::_( 'select.option', '1', JText::_( 'Name' ) );
		$optSearch[] 		=	JHTML::_( 'select.option', '2', JText::_( 'Description' ) );
		$optSearch[] 		= 	JHTML::_( 'select.option', '3', JText::_( 'MINUS ID' ) . '&nbsp;(*)' );
		$optSearch[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$lists['filter_search'] =	JHTML::_( 'select.genericlist', $optSearch, 'filter_search', 'size="1" class="inputbox"', 'value', 'text', $filter_search );
		
		// Set Search Box
		$lists['search']	=	$search;
		
		// Set State Filter
		$lists['filter_state']	=	JHTML::_( 'grid.state',  $filter_state );
		
		// Set Category Filter
		$javascript 		=	'onchange="document.adminForm.submit();"';
		$optCategories		=	array();
		$optCategories[]	=	JHTML::_( 'select.option',  '0', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
		$optCategories[]	=	JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
		$optCategories[]	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
		$optCategories		=	array_merge( $optCategories, HelperjSeblod_Helper::getTypeCategories( false, false ) );
		$optCategories[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$lists['filter_category']	=	JHTML::_('select.genericlist', $optCategories, 'filter_category', $javascript, 'value', 'text', $filter_category );
		
		$parentId	=	( ! $filter_category || $filter_category < 3 ) ? 2 : $model->getParent( $filter_category );
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		//
		$this->assignRef( 'isAuth', $isAuth );
		$this->assignRef( 'categoriesItems', $categoriesItems );
		$this->assignRef( 'pagination', $pagination );
		$this->assignRef( 'parentId', $parentId );
		//
		$this->assignRef( 'lists', $lists );

		$this->_displayToolbar( $isAuth );
		
		parent::display( $tpl );
	}
	
}
?>