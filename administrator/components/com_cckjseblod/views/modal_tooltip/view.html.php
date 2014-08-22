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
 * Modal Tooltip	View Class
 **/
class CCKjSeblodViewModal_Tooltip extends JView
{
	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$controller	=	JRequest::getWord( 'controller' );
		$document	=&	JFactory::getDocument();
		//
		$array 		=	JRequest::getVar( 'cid',  0, '', 'array' );
		$boolId 	=	(int)$array[0];
		$from 		=	JRequest::getVar( 'from' );
		$into		=	JRequest::getVar( 'into' );
		$legend		=	JRequest::getVar( 'legend' );
		
		// Get Data from Model
		if ( $boolId != -1 ) {
			$tooltip	=	HelperjSeblod_Helper::getWysiwygContent( $into, $from, $boolId );
		}
		
		if ( ! $tooltip ) {
			$tooltip	=	JText::_( 'EMPTY' );
		}
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		//
		$this->assignRef( 'legend', $legend );
		//
		$this->assignRef( 'tooltip', $tooltip );
		
		parent::display( $tpl );
	}
	
}
?>