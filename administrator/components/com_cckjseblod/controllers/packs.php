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
 * Packs		Controller Class
 **/
class CCKjSeblodControllerPacks extends CCKjSeblodController
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
		$this->registerTask( 'trash', 'remove' );
				
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
			case 'add':
				JRequest::setVar( 'view', 'packs' );
				JRequest::setVar( 'layout', 'import' );
				break;
			default:
				// Set Default View
				$view = JRequest::getCmd( 'view' );
				if ( empty( $view ) ) {
					JRequest::setVar( 'view', 'packs' );
					JRequest::setVar( 'layout', 'default' );
				}
				break;
		}
		
		parent::display();
	}

	/**	FROM 1ST ( View = Packs ) **/
	
	/**
	 * Empty Pack && Redirect
	 **/
	function remove()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'packs' );
		
		if ( $model->remove() ) {
			$msg = JText::_( 'EMPTY SUCCESS' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_PACKS;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Import Xml
	 **/
	function importXml()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'packs' );
		
		if ( $success = $model->importXml() ) {
			if ( JString::strpos( $success, '||' ) !== false ) {
				$ignored = explode( '||', $success );
				$msg = JText::_( 'ITEMS IMPORTED' ) . ' ( ' . $ignored[1] . ' ' . JText::_( 'IGNORED' ) . ' )';
			} else {
				$msg = JText::_( 'ITEMS IMPORTED' );
			}
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_PACKS;
		
		$this->setRedirect( $link, $msg, $msgType );
	}

	/**
	 * Export Xml
	 **/
	function exportXml()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'packs' );
		
		if ( $file = $model->exportXml() ) {
			$this->setRedirect( 'components/com_cckjseblod/download.php?file='.$file );
			$msg = JText::_( 'PACK EXPORTED' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
			$link = _LINK_CCKJSEBLOD_PACKS;
			$this->setRedirect( $link, $msg, $msgType );
		}
		
		//$link = _LINK_CCKJSEBLOD_PACKS;
		
		//$this->setRedirect( $link, $msg, $msgType );
	}
}
?>