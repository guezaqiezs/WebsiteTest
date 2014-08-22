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
 * Modal Image		View Class
 **/
class CCKjSeblodViewModal_Image extends JView
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
		
		// Get Data from Model
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		$this->assignRef( 'into', $into );
		
		parent::display( $tpl );
	}
	
}
?>