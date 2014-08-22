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
 * Packs			View Class
 **/
class CCKjSeblodViewPacks extends JView
{
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isAuth ) 
	{
		JToolBarHelper::title(   JText::_( 'PACK MANAGER' ), 'packs.png' );
		if ( $isAuth ) {
			JToolBarHelper::custom( 'remove', 'delete_jseblod', 'delete_jseblod', JText::_( 'EMPTY' ), false );
			JToolBarHelper::custom( 'exportXml', 'export_jseblod', 'export_jseblod', JText::_( 'EXPORT' ), false );
			JToolBarHelper::custom( 'importXml', 'import_jseblod', 'import_jseblod', JText::_( 'IMPORT' ), false );
		}
		JToolBarHelper::divider();
		JToolBarHelper::custom( 'cpanel', 'jseblod', 'cpanel', JText::_( 'CPANEL' ), false );
		HelperjSeblod_Display::help( 'packs' );
	}
	
	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$controller 	=	JRequest::getWord( 'controller' );
		$document		=&	JFactory::getDocument();
		$task			=	JRequest::getVar( 'layout' );
		$user 			=&	JFactory::getUser();
		
		$packs->templates = 2;
		$packs->types = 1;
		$packs->items = 9;
		
		// Set Flags
		$isAuth = ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
		
		$packElems		=&	$this->get( 'Data' );
		
		// Set Process List ( Select List )
		$optionImportProcess	= array();
		$optionImportProcess[]	= JHTML::_( 'select.option',  '0', JText::_( 'IGNORE EXISTING' ), 'value', 'text' );
		$optionImportProcess[]	= JHTML::_( 'select.option',  '1', JText::_( 'UPDATE EXISTING' ), 'value', 'text' );
		$lists['import_mode']	=	JHTML::_( 'select.radiolist', $optionImportProcess, 'import_mode', 'class="inputbox" size="1"', 'value', 'text', _IMPORT_DEFAULT_MODE );
		
		// Set Process List ( Select List )
		$optionImportSelection	= array();
		$optionImportSelection[]	=	JHTML::_( 'select.option',  '0', JText::_( 'ALL' ), 'value', 'text' );
		$optionImportSelection[]	=	JHTML::_( 'select.option',  '1', JText::_( 'FIELDS ONLY' ), 'value', 'text' );
		$lists['import_selection']	=	JHTML::_( 'select.genericlist', $optionImportSelection, 'import_selection', 'class="inputbox" size="1"', 'value', 'text', 0 );
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document',	$document );
		//
		$this->assignRef( 'packElems',	$packElems );
		//
		$this->assignRef( 'lists',	$lists );
				
		$this->_displayToolbar( $isAuth );
		
		parent::display( $tpl );
	}
	
}
?>