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
 * Templates_Views		Controller Class
 **/
class CCKjSeblodControllerTemplates_Views extends CCKjSeblodController
{
	/**
	 * Vars
	 **/
	var $_isAuth = null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
		
		// Check User Auth
		$user 	=& JFactory::getUser();
		$isAuth = ( $user->get( 'gid' ) < _VIEW_ACCESS ) ? 0 : 1;
		$this->_setValues( $isAuth );
	}
	
	/**
	 * Set Values 
	 **/
	function _setValues( $isAuth )
	{
		// Set Values
		$this->_isAuth	= $isAuth;
	}

	/**
	 * Display Default View
	 **/
	function display()
	{
		global $mainframe;
		
		// Check User Authorization
		if ( ! $this->_isAuth ) {
			$mainframe->redirect( _LINK_CCKJSEBLOD, JText::_( 'NOT AUTH' ), 'error' );
		}
	
		// Set Default View
		$view = JRequest::getCmd( 'view' );
		if ( empty( $view ) ) {
			JRequest::setVar( 'view', 'templates_views' );
			JRequest::setVar( 'layout', 'default' );
		}
		
		parent::display();
	}
	
	/**	FROM 1ST ( View = Assignments ) **/
	/**
	 * Back
	 **/
	function back()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link = _LINK_CCKJSEBLOD_TEMPLATES;
		
		$this->setRedirect( $link );
	}
	
}
?>