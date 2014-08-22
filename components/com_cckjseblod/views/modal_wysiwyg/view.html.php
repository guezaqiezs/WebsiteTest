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
 * Modal Wysiwyg			View Class
 **/
class CCKjSeblodViewModal_Wysiwyg extends JView
{
	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$controller = JRequest::getWord( 'controller' );
		$document	=& JFactory::getDocument();
		
		$into		= JRequest::getVar( 'into' );
		$from 		= JRequest::getVar( 'from' );
		$array 		= JRequest::getVar( 'cid',  0, '', 'array' );
		$boolId 	= (int)$array[0];
		$e_editor	= JRequest::getVar( 'e_editor' );
		
		if ( strpos( $e_editor, 'tinypreset_' ) === false ) {
			$mode	=	0;
		} else {
			$mode	=	1;
			$e_editor	=	str_replace( 'tinypreset_', '', $e_editor );
		}
		
		// Get Data from Model
		if ( $boolId != -1 ) {
			$wysiwyg = HelperjSeblod_Helper::getWysiwygContent( $into, $from, $boolId );
			
			$this->assignRef( 'wysiwyg', $wysiwyg );
		}
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		$this->assignRef( 'into', $into );
		$this->assignRef( 'boolId', $boolId );
		$this->assignRef( 'e_editor', $e_editor );
		$this->assignRef( 'mode', $mode );
		
		parent::display( $tpl );
	}
	
}
?>