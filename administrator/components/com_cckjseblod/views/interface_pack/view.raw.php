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
 * Interface_Pack		View Class
 **/
class CCKjSeblodViewInterface_Pack extends JView
{
	/**
	 * Display Default View
	 **/ 
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$user 		=&	JFactory::getUser();
		$controller =	JRequest::getWord( 'controller' );
		$document	=&	JFactory::getDocument();
		$model 		=&	$this->getModel();
		//
		$cck = JRequest::getInt( 'cck' );
		$brb = JRequest::getInt( 'brb' );
		$act = JRequest::getInt( 'act' );
		$cat_id = JRequest::getInt( 'cat_id' );
		$u_opt = JRequest::getInt( 'u_opt' );
		$u_task = JRequest::getInt( 'u_task' );
		$lang_id = JRequest::getInt( 'lang_id' );
		$artId			=	JRequest::getString( 'artid' );
		$typeName		=	JRequest::getVar( 'content_type' );
		
		// Set Import Pack Mode ( Select List )
		$optImportPack	= array();
		$optImportPack[]	= JHTML::_( 'select.option',  '0', JText::_( 'IGNORE EXISTING' ), 'value', 'text' );
		$optImportPack[]	= JHTML::_( 'select.option',  '1', JText::_( 'UPDATE EXISTING' ), 'value', 'text' );
		$lists['import_mode']	=	JHTML::_( 'select.radiolist', $optImportPack, 'import_mode', 'class="inputbox" size="1"', 'value', 'text', _IMPORT_DEFAULT_MODE );
		
		// Set Process List ( Select List )
		$optionImportSelection	= array();
		$optionImportSelection[]	=	JHTML::_( 'select.option',  '0', JText::_( 'ALL' ), 'value', 'text' );
		$optionImportSelection[]	=	JHTML::_( 'select.option',  '1', JText::_( 'FIELDS ONLY' ), 'value', 'text' );
		$lists['import_selection']	=	JHTML::_( 'select.genericlist', $optionImportSelection, 'import_selection', 'class="inputbox" size="1"', 'value', 'text', 0 );
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		//
		$this->assignRef( 'formName', $formName );
		$this->assignRef( 'formHidden', $formHidden );
		$this->assignRef( 'jsOnSubmit', $jsOnSubmit );
		$this->assignRef( 'data', $data );
		$this->assignRef( 'error', $error );
		//
		$this->assignRef( 'lists', $lists );
		$this->assignRef( 'artId', $artId );
		$this->assignRef( 'cck', $cck );
		$this->assignRef( 'brb', $brb );
		$this->assignRef( 'act', $act );
    $this->assignRef( 'cat_id', $cat_id );
		$this->assignRef( 'u_opt', $u_opt );
		$this->assignRef( 'u_task', $u_task );
    $this->assignRef( 'lang_id', $lang_id );
		$this->assignRef( 'contentType', $contentType );
		
		parent::display( $tpl );
	}
	
}
?>