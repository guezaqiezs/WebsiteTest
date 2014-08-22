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
 * Content Types	Controller Class
 **/
class CCKjSeblodControllerTypes extends CCKjSeblodController
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
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'copy', 'edit' );
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'create', 'edit' );
		$this->registerTask( 'delete', 'edit' );
		$this->registerTask( 'assign', 'edit' );
		$this->registerTask( 'admin', 'edit' );
		$this->registerTask( 'site', 'edit' );
		$this->registerTask( 'content', 'edit' );
		
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
			case 'element':
				JRequest::setVar( 'view', 'types' );
				JRequest::setVar( 'layout', 'element' );
				break;
			case 'select':
				JRequest::setVar( 'view', 'types' );
				JRequest::setVar( 'layout', 'select' );
				break;
			default:
				// Set Default View
				$view = JRequest::getCmd( 'view' );
				if ( empty( $view ) ) {
					JRequest::setVar( 'view', 'types' );
					JRequest::setVar( 'layout', 'default' );
				}
				break;
		}
		
		parent::display();
	}
	
	/** FROM 1ST ( View = Types ) **/
	/**
	 * Display Edit Form
	 **/
	function edit()
	{	
		global $mainframe;
		
		// Check User Authorization;
		if ( ! $this->_isAuth ) {
			$mainframe->redirect( _LINK_CCKJSEBLOD, JText::_( 'Alertnotauth' ), 'error' );
		}
		
		switch( $this->getTask() ) {
			case 'add':
			case 'edit':
				JRequest::setVar( 'view', 'type' );
				JRequest::setVar( 'layout', 'form' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doAssign', false );
				JRequest::setVar( 'doContent', false );
				break;
			case 'copy':
				JRequest::setVar( 'view', 'type' );
				JRequest::setVar( 'layout', 'form' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', true );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doAssign', false );
				JRequest::setVar( 'doAdmin', false );
				JRequest::setVar( 'doSite', false );
				JRequest::setVar( 'doContent', false );
				break;
			case 'create':
				JRequest::setVar( 'view', 'type' );
				JRequest::setVar( 'layout', 'create' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doAssign', false );
				JRequest::setVar( 'doAdmin', false );
				JRequest::setVar( 'doSite', false );
				JRequest::setVar( 'doContent', false );
				break;
			case 'delete':
				JRequest::setVar( 'view', 'type' );
				JRequest::setVar( 'layout', 'delete' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', true );
				JRequest::setVar( 'doAssign', false );
				JRequest::setVar( 'doAdmin', false );
				JRequest::setVar( 'doSite', false );
				JRequest::setVar( 'doContent', false );
				break;
			case 'assign':
				JRequest::setVar( 'view', 'type' );
				JRequest::setVar( 'layout', 'assign' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doAssign', true );
				JRequest::setVar( 'doAdmin', false );
				JRequest::setVar( 'doSite', false );
				JRequest::setVar( 'doContent', false );
				break;
			case 'admin':
				JRequest::setVar( 'view', 'type' );
				JRequest::setVar( 'layout', 'admin' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doAssign', false );
				JRequest::setVar( 'doAdmin', true );
				JRequest::setVar( 'doSite', false );
				JRequest::setVar( 'doContent', true );
				break;
			case 'site':
				JRequest::setVar( 'view', 'type' );
				JRequest::setVar( 'layout', 'site' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doAssign', false );
				JRequest::setVar( 'doAdmin', false );
				JRequest::setVar( 'doSite', true );
				JRequest::setVar( 'doContent', false );
				break;
			case 'content':
				JRequest::setVar( 'view', 'type' );
				JRequest::setVar( 'layout', 'content' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doAssign', false );
				JRequest::setVar( 'doAdmin', false );
				JRequest::setVar( 'doSite', false );
				JRequest::setVar( 'doContent', true );
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
		
		$deleteMode = 1;
		$model = $this->getModel( 'type' );
		
		if ( $total = $model->delete( $deleteMode ) ) {
			$msg = JText::sprintf( 'Items Removed', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_TYPES;
		
		$this->setRedirect( $link, $msg, $msgType );
	}

	/**
	 * Remove && Redirect
	 **/
	function removeAll()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$deleteMode = -1;
		$model = $this->getModel( 'type' );
		
		if ( $total = $model->delete( $deleteMode ) ) {
			$msg = JText::sprintf( 'Items Removed', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_TYPES;
		
		$this->setRedirect( $link, $msg, $msgType );
	}	
	
	/**
	 * Publish && Redirect
	 **/
	function publish()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'type' );
		
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		if ( count( $cid ) < 1 ) {
			JError::raiseError( 500, JText::_( 'Select an Item to publish' ) );
		}
		
		if ( ! $model->publish( $cid, 1 ) ) {
			echo "<script> alert('".$model->getError( true )."'); window.history.go(-1); </script>\n";
		}
		
		$link = _LINK_CCKJSEBLOD_TYPES;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Unpublish && Redirect
	 **/
	function unpublish()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'type' );
		
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		if ( count( $cid ) < 1 ) {
			JError::raiseError( 500, JText::_( 'Select an Item to unpublish' ) );
		}
		
		if ( ! $model->publish( $cid, 0 ) ) {
			echo "<script> alert('".$model->getError( true )."'); window.history.go(-1); </script>\n";
		}

		$link = _LINK_CCKJSEBLOD_TYPES;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Categories Redirection
	 **/
	function categories()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link = _LINK_CCKJSEBLOD_TYPES_CATEGORIES;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Add Category Redirection
	 **/
	function addcategory()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link = _LINK_CCKJSEBLOD_TYPES_CATEGORIES . '&task=add';
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Batch Category Process
	 **/
	function batchCategory()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
				
		$model = $this->getModel( 'type' );

		if ( $total = $model->batchCategory() ) {
			$msg = JText::sprintf( 'ITEMS UPDATED', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_TYPES;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**	FROM 2ND ( View = Type ) **/
	/**
	 * Save && Redirect
	 **/
	function save()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model	= $this->getModel( 'type' );
		
		if ( $rowId = $model->store() ) {
			$msg = JText::_( 'Item Saved' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		// Checkin!
		$model->checkin();
		
		switch( $this->getTask() ) {
			case 'apply':
				$link = ( $rowId ) ? _LINK_CCKJSEBLOD_TYPES.'&task=edit&cid[]='.$rowId : _LINK_CCKJSEBLOD_TYPES;
				break;
			case 'save':
				$link = _LINK_CCKJSEBLOD_TYPES;
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
		
		$model = $this->getModel( 'type' );
		
		if ( $rowId = $model->liveStore() ) {
			//$msg = JText::_( 'Item Saved' );
			//$msgType = 'message';
		} else {
			//$msg = JText::_( 'An error has occurred' );
			//$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_TYPES;
		
		$this->setRedirect( $link );	//$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Back
	 **/
	function back()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link = _LINK_CCKJSEBLOD_ITEMS;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Cancel && Redirect
	 **/
	function cancel()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'type' );
		
		// Checkin!
		$model->checkin();
		
		$link = _LINK_CCKJSEBLOD_TYPES;

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
				   . ' FROM #__jseblod_cck_types AS s'
				   . $where
				   ;
			$db->setQuery( $query );
			$total	=	$db->loadResult();
		} 
		
		echo $total;
	}
	
	/**
	 * Add into Pack
	 **/
	function addIntoPack()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'type' );
		
		if ( $model->addIntoPack() ) {
			$msg = JText::_( 'ELEMENTS ADDED' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$redirect	=	JRequest::getVar( 'add_redirection' );
		$link 		=	$redirect ? _LINK_CCKJSEBLOD_PACKS : _LINK_CCKJSEBLOD_TYPES;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Export Xml
	 **/
	function exportXml()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'type' );
		
		if ( $file = $model->exportXml() ) {
			$this->setRedirect( 'components/com_cckjseblod/download.php?file='.$file );
		} else {
			$link		=	_LINK_CCKJSEBLOD_TYPES;
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
			$this->setRedirect( $link, $msg, $msgType );
		}
		
		//$link = _LINK_CCKJSEBLOD_TYPES;
			
		//$this->setRedirect( $link, $msg, $msgType );
	}

	/**
	 * Create Html
	 **/
	function createHtml()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'type' );
		
		if ( $file = $model->createHtml() ) {
			$this->setRedirect( 'components/com_cckjseblod/download.php?file='.$file );
		} else {
			$link		=	_LINK_CCKJSEBLOD_TYPES;
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
			$this->setRedirect( $link, $msg, $msgType );
		}
		
		//$link = _LINK_CCKJSEBLOD_TYPES;
			
		//$this->setRedirect( $link, $msg, $msgType );
	}
}
?>