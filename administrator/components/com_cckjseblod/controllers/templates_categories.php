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
 * Templates_Categories		Controller Class
 **/
class CCKjSeblodControllerTemplates_Categories extends CCKjSeblodController
{
	/**
	 * Vars
	 **/
	var $_isAuth	=	null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
		
		// Register Extra Tasks
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'copy', 'edit' );
		$this->registerTask( 'delete', 'edit' );
				
		// Check User Auth
		$user	=&	JFactory::getUser();
		$isAuth	=	( $user->get( 'gid' ) < _VIEW_ACCESS ) ? 0 : 1;
		$this->_setValues( $isAuth );
	}
	
	/**
	 * Set Values 
	 **/
	function _setValues( $isAuth )
	{
		// Set Values
		$this->_isAuth	=	$isAuth;
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
		$view	=	JRequest::getCmd( 'view' );
		if ( empty( $view ) ) {
			JRequest::setVar( 'view', 'templates_categories' );
			JRequest::setVar( 'layout', 'default' );
		}
		
		parent::display();
	}
	
	/**
	 * 1. View = Templates_Categories
	 **/
	
	/**
	 * Display Edit Form
	 **/
	function edit()
	{	
		global $mainframe;
		
		// Check User Authorization;
		if ( ! $this->_isAuth ) {
			$mainframe->redirect( _LINK_CCKJSEBLOD, JText::_( 'NOT AUTH' ), 'error' );
		}
		
		switch( $this->getTask() ) {
			case 'add':
			case 'edit':
				JRequest::setVar( 'view', 'templates_category' );
				JRequest::setVar( 'layout', 'form' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', false );
				break;
			case 'copy':
				JRequest::setVar( 'view', 'templates_category' );
				JRequest::setVar( 'layout', 'form' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', true );
				JRequest::setVar( 'doDelete', false );
				break;
			case 'delete':
				JRequest::setVar( 'view', 'templates_category' );
				JRequest::setVar( 'layout', 'delete' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', true );
				break;
			default:
				break;
		}
		
		parent::display();
	}

	/**
	 * Remove && Redirect
	 **/
	function remove()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$deleteMode	=	1;
		$model 		=	$this->getModel( 'templates_category' );
		
		if ( $model->cannotDelete() ) {
			$msg		=	JText::_( 'CANNOT REMOVE CATEGORY' );
			$msgType	=	'notice';
		} else {
			if ( $total	= $model->delete( $deleteMode ) ) {
				$msg		=	JText::sprintf( 'Items Removed', $total );
				$msgType	=	'message';
			} else {
				$msg		=	JText::_( 'An error has occurred' );
				$msgType	=	'error';
			}
		}
		
		$link	=	_LINK_CCKJSEBLOD_TEMPLATES_CATEGORIES;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	/**
	 * Remove && Redirect
	 **/
	function removeAll()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$deleteMode	=	-1;
		$model		=	$this->getModel( 'templates_category' );
		
		if ( $model->cannotDelete() ) {
			$msg		=	JText::_( 'CANNOT REMOVE CATEGORY' );
			$msgType	=	'notice';
		} else {
			if ( $total	= $model->delete( $deleteMode ) ) {
				$msg		=	JText::sprintf( 'Items Removed', $total );
				$msgType	=	'message';
			} else {
				$msg		=	JText::_( 'An error has occurred' );
				$msgType	=	'error';
			}
		}
		
		$link	=	_LINK_CCKJSEBLOD_TEMPLATES_CATEGORIES;
		
		$this->setRedirect( $link, $msg, $msgType );
	}

	/**
	 * Publish && Redirect
	 **/
	function publish()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model	=	$this->getModel( 'templates_category' );
		
		$cid	=	JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		if ( count( $cid ) < 1 ) {
			JError::raiseError( 500, JText::_( 'Select an Item to publish' ) );
		}
		
		if ( ! $model->publish( $cid, 1 ) ) {
			echo "<script> alert('".$model->getError( true )."'); window.history.go(-1); </script>\n";
		}
		
		$link	=	_LINK_CCKJSEBLOD_TEMPLATES_CATEGORIES;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Unpublish && Redirect
	 **/
	function unpublish()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model	=	$this->getModel( 'templates_category' );
		
		$cid	=	JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		if ( count( $cid ) < 1 ) {
			JError::raiseError( 500, JText::_( 'Select an Item to unpublish' ) );
		}
		
		if ( ! $model->publish( $cid, 0 ) ) {
			echo "<script> alert('".$model->getError( true )."'); window.history.go(-1); </script>\n";
		}
		
		$link	=	_LINK_CCKJSEBLOD_TEMPLATES_CATEGORIES;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Back
	 **/
	function back()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link	=	_LINK_CCKJSEBLOD_TEMPLATES;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * 2. View = Templates_Category
	 **/
	 
	/**
	 * Save && Redirect
	 **/
	function save()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model	=	$this->getModel( 'templates_category' );
		
		if ( $rowId = $model->store() ) {
			$msg		=	JText::_( 'Item Saved' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		// Checkin!
		$model->checkin();
		
		switch( $this->getTask() ) {
			case 'apply':
				$link	=	( $rowId ) ? _LINK_CCKJSEBLOD_TEMPLATES_CATEGORIES.'&task=edit&cid[]='.$rowId : _LINK_CCKJSEBLOD_TEMPLATES;
				break;
			case 'save':
				$link	=	_LINK_CCKJSEBLOD_TEMPLATES_CATEGORIES;
				break;
			default:
				break;
		}
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Live Save && Redirect
	 **/
	function liveSave()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'templates_category' );
		
		if ( $rowId = $model->liveStore() ) {
			//$msg = JText::_( 'Item Saved' );
			//$msgType = 'message';
		} else {
			//$msg = JText::_( 'An error has occurred' );
			//$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_TEMPLATES_CATEGORIES;
		
		$this->setRedirect( $link );	//$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Cancel && Redirect
	 **/
	function cancel()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model	=	$this->getModel( 'templates_category' );
		
		// Checkin!
		$model->checkin();
		
		$link	=	_LINK_CCKJSEBLOD_TEMPLATES_CATEGORIES;
		
		$this->setRedirect( $link );
	}

	/**
	 * Check Availability [ Ajax ]
	 **/
	function checkAvailability()
	{
		$available	=	0;
		$total		=	0;
		
		if ( $available = JRequest::getVar( 'available', '', 'get', 'string' ) ) {
			
			// Check Name
			$available =	HelperjSeblod_Helper::stringURLSafe( $available );
			if( trim( str_replace( '_', '', $available ) ) == '' ) {
				$datenow	=&	JFactory::getDate();
				$available =	$datenow->toFormat( "%Y_%m_%d_%H_%M_%S" );
			}
			
			$db	=& JFactory::getDBO();
			$where 		= ' WHERE s.name = "'.$available.'"';
			
			$query = ' SELECT COUNT( s.id )'
				   . ' FROM #__jseblod_cck_templates_categories AS s'
				   . $where
				   ;
			$db->setQuery( $query );
			$total	=	$db->loadResult();
		} 
		
		echo $total;
	}
	
}
?>