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
 * Configuration	Controller Class
 **/
class CCKjSeblodControllerConfiguration extends CCKjSeblodController
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
		
		// Register Extra Tasks
		$this->registerTask( 'apply', 'save' );
		
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
			$mainframe->redirect( _LINK_CCKJSEBLOD, JText::_( 'Alertnotauth' ), 'error' );
		}
		
		switch( $this->getTask() ) {
			case 'operations':
				JRequest::setVar( 'view', 'configuration' );
				JRequest::setVar( 'layout', 'operations' );
				break;
			default:
				// Set Default View
				$view = JRequest::getCmd( 'view' );
				if ( empty( $view ) ) {
					//JRequest::setVar( 'hidemainmenu', 1 );		// No checkin cause buggy 'hidemainmenu' ? ( also hide submenu .. )
					JRequest::setVar( 'view', 'configuration' );
					JRequest::setVar( 'layout', 'form' );
				}
				break;
		}
		
		parent::display();
	}

	/**	FROM 1ST ( View = Configuration ) **/
	/**
	 * Save && Redirect
	 **/
	function save()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'configuration' );

		if ( $model->store() ) {
			$msg = JText::_( 'Successfully Saved' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		// Checkin!
		//$model->checkin();		// No checkin cause buggy 'hidemainmenu' ? ( also hide submenu .. )
		
		switch( $this->getTask() ) {
			case 'apply':
				$link = _LINK_CCKJSEBLOD_CONFIGURATION;
				break;
			case 'save':
				$link = _LINK_CCKJSEBLOD;
				break;
			default:
				break;
		}
		
		$this->setRedirect( $link, $msg, $msgType );
	}

	/**
	 * Back
	 **/
	function back()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link = _LINK_CBJSEBLOD;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Cancel && Redirect
	 **/
	function cancel()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'configuration' );
		
		// Checkin!
		//$model->checkin();		// No checkin cause buggy 'hidemainmenu' ? ( also hide submenu .. )
		
		$link = _LINK_CCKJSEBLOD;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Reset CCK && Redirect
	 **/
	function reset()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'configuration' );

		if ( $model->reset() ) {
			$msg = JText::_( 'RESET SUCCESS' );
			$msgType = 'notice';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD;
		
		$this->setRedirect( $link, $msg, $msgType );
	}

	/**
	 * Update SqeezeBox Style
	 **/
	function squeezebox()
	{
		$cssFile	=	JPATH_SITE.DS.'media'.DS.'system'.DS.'css'.DS.'modal.css';
		$oldCss		=	JPATH_SITE.DS.'media'.DS.'system'.DS.'css'.DS.'modal.css.jseblod.old';
		$newCss		=	JPATH_SITE.DS.'media'.DS.'system'.DS.'css'.DS.'modal.css.jseblod.new';
		if ( JFile::exists( $oldCss ) ) {
			JFile::move( $cssFile, $newCss );
			JFile::move( $oldCss, $cssFile );
			$msg = JText::_( 'UPDATE SUCCESS' );
			$msgType = 'notice';
		} else if ( JFile::exists( $newCss ) ) {
			JFile::move( $cssFile, $oldCss );
			JFile::move( $newCss, $cssFile );
			$msg = JText::_( 'UPDATE SUCCESS' );
			$msgType = 'notice';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}		
		$link = _LINK_CCKJSEBLOD;
		
		$this->setRedirect( $link, $msg, $msgType );
	}

	/**
	 * Clean Template Folders && Redirect
	 **/
	function clean()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		if ( HelperjSeblod_helper::clean() ) {
			$msg = JText::_( 'CLEAN SUCCESS' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Update Version
	 **/
	function version_update()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'configuration' );
		
		if ( $model->version_update() ) {
			$msg = JText::_( 'UPDATE SUCCESS' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Update Quick Category Title
	 **/
	function update_quick_title()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$title	=	JRequest::getVar( 'quick_title' );
		$model	=	$this->getModel( 'configuration' );

		if ( $model->update_quick_category( $title, '' ) ) {
			$msg = JText::_( 'QUICK CATEGORIES UPDATED' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Update Quick Category Color
	 **/
	function update_quick_color()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$color	=	JRequest::getVar( 'quick_color' );
		$model	=	$this->getModel( 'configuration' );

		if ( $model->update_quick_category( '', $color ) ) {
			$msg = JText::_( 'QUICK CATEGORIES UPDATED' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Update Quick Category Title/Color
	 **/
	function update_quick_title_color()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$title	=	JRequest::getVar( 'quick_title' );
		$color	=	JRequest::getVar( 'quick_color' );
		$model	=	$this->getModel( 'configuration' );

		if ( $model->update_quick_category( $title, $color ) ) {
			$msg = JText::_( 'QUICK CATEGORIES UPDATED' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
}
?>