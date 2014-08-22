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
 * Items_Category			View Class
 **/
class CCKjSeblodViewItems_Category extends JView
{
	/**
	 * Display Delete Toolbar
	 **/
	function _displayDeleteToolbar() 
	{
		JToolBarHelper::title(   JText::_( 'ITEM CATEGORY' ).': <small><small>[ '.JText::_( 'Delete' ).' ]</small></small>', 'category.png' );
		JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Close' ), false ); //JToolBarHelper::cancel( 'cancel', 'Close' );
		JToolBarHelper::divider();
		HelperjSeblod_Display::help( 'items_categories' );
	}
	
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isNew, $doCopy, $isAuth )
	{
		if ( $isAuth ) {
			JToolBarHelper::custom( 'save', 'save_jseblod', 'save_jseblod', JText::_( 'Save' ), false ); //JToolBarHelper::save();
			JToolBarHelper::custom( 'apply', 'apply_jseblod', 'apply_jseblod', JText::_( 'Apply' ), false ); //JToolBarHelper::apply();;
			JToolBarHelper::spacer();
		}
		if ( $isNew || $doCopy )  {
			$text	=	$doCopy ? JText::_( 'Copy' ) : JText::_( 'New' );
			JToolBarHelper::title(   JText::_( 'ITEM CATEGORY' ).': <small><small>[ '.$text.' ]</small></small>', 'category.png' );
			JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Cancel' ), false ); //JToolBarHelper::cancel();
		} else {
			$text 	=	$isAuth ? JText::_( 'Edit' ) : JText::_( 'View' );
			JToolBarHelper::title(   JText::_( 'ITEM CATEGORY' ).': <small><small>[ '.$text.' ]</small></small>', 'category.png' );
			JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Close' ), false ); //JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::divider();
		HelperjSeblod_Display::help( 'items_category' );
	}
	
	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$controller	=	JRequest::getWord( 'controller' );
		$document	=&	JFactory::getDocument();
		$model 		=&	$this->getModel();
		$user 		=&	JFactory::getUser();
		
		$doDelete	=	JRequest::getVar( 'doDelete', false );
		if ( $doDelete ) {
			
			// Get Data from Model
			$categoriesItems	=&	$this->get( 'RemoveData' );
			
			// Push Data to Template
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			//
			$this->assignRef( 'categoriesItems', $categoriesItems );
			
			$this->_displayDeleteToolbar();
			
		} else {
			
			// Get Data from Model
			$category	=&	$this->get( 'Data' );
			
			// Set Flags
			$isNew		=	( $category->id > 0 ) ? 0 : 1;
			$doCopy		=	JRequest::getVar( 'doCopy', true );
			$isAuth 	=	( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
			
			// Checking!
			if ( JTable::isCheckedOut( $user->get( 'id' ), $category->checked_out ) ) {
				$msg	=	JText::sprintf( 'DESCBEINGEDITTED', '', $category->title );
				$mainframe->redirect( _LINK_CCKJSEBLOD_ITEMS_CATEGORIES, $msg, 'notice' );
			}
			
			// Set Published List ( Boolean )
			$selectPublished	=	( $isNew ) ? 1 : $category->published;
			$lists['published'] =	JHTML::_( 'select.booleanlist', 'published', 'class="inputbox"', $selectPublished );
			
			// Set Parent List ( Select )
			$optParents				=	array();
			$optParents[]			=	JHTML::_( 'select.option',  '', JText::_( 'SELECT A PARENT' ), 'value', 'text' );
			$optParents				=	array_merge( $optParents, HelperjSeblod_Helper::getItemCategories( false, false ) );
			$selectFilterInParent	=	( JRequest::getInt( 'filter_search' ) == 3 ) ? JRequest::getInt( 'search' ) : '';
			$selectFilterParent		=	( $selectFilterInParent || JRequest::getInt( 'filter_category' ) ) ? ( ( $selectFilterInParent ) ? $selectFilterInParent : JRequest::getInt( 'filter_category' ) ) : '';
			$selectParents			=	( ! $isNew ) ? $model->getParent( $category->id ) : $selectFilterParent;
			$parentdb				=	( ! $isNew ) ? $selectParents : null;
			$lists['parent']		=	JHTML::_( 'select.genericlist', $optParents, 'parentid', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectParents );
			
			// Set Display Items List ( Select )
			$optDisplay		=	array();
			$optDisplay[]	=	JHTML::_( 'select.option', 3, JText::_( 'HIGHER' ) );
			$optDisplay[]	=	JHTML::_( 'select.option', 2, JText::_( 'HIGH' ) );
			$optDisplay[]	=	JHTML::_( 'select.option', 1, JText::_( 'MEDIUM' ) );
			$optDisplay[]	=	JHTML::_( 'select.option', 0, JText::_( 'LOW' ) );
			$selectDisplay	=	( ! $isNew ) ? $category->display : 3;
			$lists['display']	=	JHTML::_( 'select.genericlist', $optDisplay, 'display', 'size="1" class="inputbox required required-enabled"', 'value', 'text', $selectDisplay );
			
			// Set Description Modal ( Wysiwyg )
			$modals['description'] = HelperjSeblod_Display::quickModalWysiwyg( 'Description', $controller, 'description', 'pagebreak', 0, $category->id, false );
			
			// Set Descrition Tooltips
			$tooltips['link_description'] = HelperjSeblod_Display::quickTooltipAjaxLink( 'Description', $controller, 'description', $category->id );
			
			// Push Data to Template
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document', $document );
			//
			$this->assignRef( 'doCopy', $doCopy );
			$this->assignRef( 'category', $category );
			$this->assignRef( 'parentdb', $parentdb );
			//
			$this->assignRef( 'modals', $modals );
			$this->assignRef( 'lists', $lists );
			$this->assignRef( 'tooltips', $tooltips );			
			
			$this->_displayToolbar( $isNew, $doCopy, $isAuth );
		}
		
		parent::display( $tpl );
	}
	
}
?>