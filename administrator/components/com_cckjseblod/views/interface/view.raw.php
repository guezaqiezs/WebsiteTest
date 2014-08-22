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

jimport( 'joomla.application.component.view' );

/**
 * Interface	View Class
 **/
class CCKjSeblodViewInterface extends JView
{
	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$user 		=&	JFactory::getUser();
		$controller	=	JRequest::getWord( 'controller' );
		$document	=&	JFactory::getDocument();
		$model 		=&	$this->getModel();
		
		$cck = JRequest::getInt( 'cck' );
		$brb = JRequest::getInt( 'brb' );
		$act = JRequest::getInt( 'act' );
		$cat_id = JRequest::getString( 'cat_id' );
		$u_opt = JRequest::getString( 'u_opt' );
		$u_task = JRequest::getString( 'u_task' );
		$lang_id = JRequest::getString( 'lang_id' );
		$e_name = JRequest::getVar( 'e_name' );
		$userid = JRequest::getInt( 'userid' );
		
		// Get Data from Model
		$pagination		=	$model->getPagination( $act );
		$typesItems		=	$model->getData( $act );
		// Set Flags
		//$isAuth = ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
				
		// Get User State
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order',			'filter_order',			's.title',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order_Dir',		'filter_order_Dir',		'asc',		'cmd' );
		$filter_category	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_category',		'filter_category',		0,			'int' );
		$filter_search		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_search',		'filter_search',		0,			'int' );
		$search				= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.search',				'search',				'',			'string' );
		$search				= JString::strtolower( $search );

		// Set Table Ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		// Set Search Filter
		$options_search[] 		= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'CONTENT TYPES' ) );
		$options_search[] 		= JHTML::_( 'select.option', '0', JText::_( 'Title' ) );
		$options_search[] 		= JHTML::_( 'select.option', '1', JText::_( 'Name' ) );
		$options_search[] 		= JHTML::_( 'select.option', '2', JText::_( 'Description' ) );
		$options_search[] 		= JHTML::_( 'select.option', '3', JText::_( 'MINUS ID' ) . '&nbsp;(*)' );
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
		$lists['filter_search'] = JHTML::_( 'select.genericlist', $options_search, 'filter_search', 'size="1" class="inputbox"', 'value', 'text', $filter_search );

		// Set Search Box
		$lists['search']	= $search;
		
		// Set Category Filter
		$javascript 	= 'onchange="document.adminForm.submit();"';
		$optionCategories	= array();
		$optionCategories[]	= JHTML::_( 'select.option',  '0', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
		$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
		$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
		$optionCategories	= array_merge( $optionCategories, HelperjSeblod_Helper::getTypeCategories( false, true ) );
		$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$lists['category']	= JHTML::_('select.genericlist', $optionCategories, 'filter_category', $javascript, 'value', 'text', $filter_category );
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document',	$document );
		$this->assignRef( 'typesItems', $typesItems );
		$this->assignRef( 'pagination', $pagination );
		//$this->assignRef( 'isAuth', $isAuth );
		$this->assignRef( 'lists', $lists );
		$this->assignRef( 'cck', $cck );
		$this->assignRef( 'brb', $brb );
		$this->assignRef( 'act', $act );
		$this->assignRef( 'cat_id', $cat_id );
		$this->assignRef( 'u_opt', $u_opt );
		$this->assignRef( 'u_task', $u_task );
		$this->assignRef( 'lang_id', $lang_id );
		$this->assignRef( 'e_name', $e_name );
		if ( $act == 4 ) {
			$this->assignRef( 'userid', $userid );		
		}
		
		parent::display( $tpl );
	}
}
?>