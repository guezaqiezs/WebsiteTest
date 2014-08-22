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
 * Templates_Views		View Class
 **/
class CCKjSeblodViewTemplates_Views extends JView
{
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isAuth ) 
	{
		JToolBarHelper::title(   JText::_( 'TEMPLATE SITE VIEWS' ), 'views.png' );
		JToolBarHelper::custom( 'back', 'back', 'back', JText::_( 'Back' ), false );
		JToolBarHelper::divider();
		JToolBarHelper::custom( 'cpanel', 'jseblod', 'cpanel', JText::_( 'CPANEL' ), false );
		HelperjSeblod_Display::help( 'templates' );
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
		$task				=	JRequest::getVar( 'layout' );
		$typeFilter 		= JRequest::getWord( 'typefilter' );
		$templateFilter 	= JRequest::getInt( 'templatefilter' );
		
		// Get Data from Model
		$pagination 		=& $this->get( 'Pagination' );
		$assignmentsItems	=& $this->get( 'Data' );
		$allMenuItems		=& $this->get( 'AllMenu' );
		
		
		// Set Flags
		$isAuth = ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
		
		// Get User State
		$filter_type		= ( $templateFilter && ! $typeFilter ) ? 0 : ( ( $typeFilter ) ? $typeFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_type',		'filter_type',	0,		'word' ) );
		$filter_state		= ( $templateFilter ) ? '' : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_state',		'filter_state',		'',					'word' );
		$filter_search		= ( $templateFilter ) ? 3 : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_search',	'filter_search',	0,			'int' );
		$search				= ( $templateFilter ) ? $templateFilter : $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.search',	'search',		'',		'string' );
		$search				= JString::strtolower( $search );
		//
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order',		'filter_order',		'assignmenttitle',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order_Dir',	'filter_order_Dir',	'asc',				'cmd' );
		
		// Set Search Filter
		$options_search[] 		= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'TEMPLATE VIEWS' ) );
		$options_search[] = JHTML::_( 'select.option', 0, JText::_( 'Title' ) );
		$options_search[]		= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$options_search[] 		= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'TEMPLATES' ) );
		$options_search[] = JHTML::_( 'select.option', 1, JText::_( 'TEMPLATE TITLE' ) );
		$options_search[] = JHTML::_( 'select.option', 2, JText::_( 'TEMPLATE NAME' ) );
		$options_search[] = JHTML::_( 'select.option', 3, JText::_( 'TEMPLATE ID' ) . '&nbsp;(*)' );
		$options_search[]		= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$lists['filter_search'] = JHTML::_( 'select.genericlist', $options_search, 'filter_search', 'size="1" class="inputbox"', 'value', 'text', $filter_search );
		
		// Set Search Box
		$lists['search']= $search;
		
		$javascript 	= 'onchange="document.adminForm.submit();"';
		$optState	= array();
		$optState[] = JHTML::_( 'select.option', '', JText::_( 'SELECT TEMPLATE STATE' ) );
		$optState[] = JHTML::_( 'select.option', 'P', JText::_( 'Published' ) );
		$optState[] = JHTML::_( 'select.option', 'U', JText::_( 'Unpublished' ) );
		$lists['filter_state'] 	= JHTML::_( 'select.genericlist', $optState, 'filter_state', $javascript, 'value', 'text', $filter_state );
		
		// Set Type Filter
		$optionTypes[]	= JHTML::_( 'select.option',  '', JText::_( 'SELECT A SITE VIEW TYPE' ), 'value', 'text' );
		$optionTypes[] 	= JHTML::_( 'select.option', 'category', JText::_( 'JOOMLA CATEGORY' ) );
		$optionTypes[]	= JHTML::_( 'select.option', 'menu', JText::_( 'MENU ITEM' ) );
		$optionTypes[]	= JHTML::_( 'select.option', 'url', JText::_( 'SITE URL' ) );
		$lists['type'] 	= JHTML::_( 'select.genericlist', $optionTypes, 'filter_type', $javascript, 'value', 'text', $filter_type );
		
		// Create Table Ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'view', $view );
		$this->assignRef( 'assignmentsItems', $assignmentsItems );
		$this->assignRef( 'allMenuItems', $allMenuItems );
		$this->assignRef( 'pagination', $pagination );
		$this->assignRef( 'lists', $lists );
		
		$this->_displayToolbar( $isAuth );
		
		parent::display( $tpl );
	}
	
}
?>