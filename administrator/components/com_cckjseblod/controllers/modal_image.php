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

jimport( 'joomla.application.component.controller' );

/**
 * Modal Image		Controller Class
 **/
class CCKjSeblodControllerModal_Image extends CCKjSeblodController
{
	/**
	 * Display Default View
	 **/
	function display()
	{
		// Set Default View
		$view = JRequest::getCmd( 'view' );
		if ( empty( $view ) ) {
			JRequest::setVar( 'view', 'modal_image' );
			JRequest::setVar( 'layout', 'form' );
		}
		
		parent::display();
	}

}
?>