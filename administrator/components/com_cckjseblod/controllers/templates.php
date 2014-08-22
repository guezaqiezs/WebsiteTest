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
jimport( 'joomla.installer.installer' );
jimport( 'joomla.installer.helper' );

/**
 * Content Templates 	Controller Class
 **/
class CCKjSeblodControllerTemplates extends CCKjSeblodController
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
		$this->registerTask( 'delete', 'edit' );
		$this->registerTask( 'source', 'edit' );
		$this->registerTask( 'params', 'edit' );
		$this->registerTask( 'locations', 'edit' );
		$this->registerTask( 'savesource', 'savesource' );
		$this->registerTask( 'saveparams', 'saveparams' );
		$this->registerTask( 'savelocations', 'savelocations' );
		$this->registerTask( 'applyparams', 'saveparams' );
		
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
				JRequest::setVar( 'view', 'templates' );
				JRequest::setVar( 'layout', 'element' );
				break;
			case 'select':
				JRequest::setVar( 'view', 'templates' );
				JRequest::setVar( 'layout', 'select' );
				break;
			default:
				// Set Default View
				$view = JRequest::getCmd( 'view' ); ///TODO CHECK THIS!
				if ( empty( $view ) ) {
					JRequest::setVar( 'view', 'templates' );
					JRequest::setVar( 'layout', 'default' );
				}
				break;
		}
			
		parent::display();
	}
	
	/**	FROM 1ST ( View = Templates ) **/
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
				JRequest::setVar( 'view', 'template' );
				JRequest::setVar( 'layout', 'form' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doSource', false );
				JRequest::setVar( 'doPreview', false );
				JRequest::setVar( 'doParams', false );
				JRequest::setVar( 'doLocations', false );
				break;
			case 'copy':
				JRequest::setVar( 'view', 'template' );
				JRequest::setVar( 'layout', 'form' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', true );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doSource', false );
				JRequest::setVar( 'doPreview', false );
				JRequest::setVar( 'doParams', false );
				JRequest::setVar( 'doLocations', false );
				break;
			case 'source':
				JRequest::setVar( 'view', 'template' );
				JRequest::setVar( 'layout', 'source' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doSource', true );
				JRequest::setVar( 'doPreview', false );
				JRequest::setVar( 'doParams', false );
				JRequest::setVar( 'doLocations', false );
				break;
			case 'delete':
				JRequest::setVar( 'view', 'template' );
				JRequest::setVar( 'layout', 'delete' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', true );
				JRequest::setVar( 'doSource', false );
				JRequest::setVar( 'doPreview', false );
				JRequest::setVar( 'doParams', false );
				JRequest::setVar( 'doLocations', false );
				break;
			case 'params':
				JRequest::setVar( 'view', 'template' );
				JRequest::setVar( 'layout', 'params' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doSource', false );
				JRequest::setVar( 'doPreview', false );
				JRequest::setVar( 'doParams', true );
				JRequest::setVar( 'doLocations', false );
				break;
			case 'locations':
				JRequest::setVar( 'view', 'template' );
				JRequest::setVar( 'layout', 'locations' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				JRequest::setVar( 'doDelete', false );
				JRequest::setVar( 'doSource', false );
				JRequest::setVar( 'doPreview', false );
				JRequest::setVar( 'doParams', false );
				JRequest::setVar( 'doLocations', true );
				break;
			default:
				break;
		}
		
		parent::display();
	}
	
	/**
	 * Remove &&  Redirect
	 **/
	function remove()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$deleteMode = 1;
		$model = $this->getModel( 'template' );
		
		if ( $total = $model->delete( $deleteMode ) ) {
			$msg = JText::sprintf( 'Items Removed', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_TEMPLATES;
		
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
		$model = $this->getModel( 'template' );
		
		if ( $total = $model->delete( $deleteMode ) ) {
			$msg = JText::sprintf( 'Items Removed', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		$link = _LINK_CCKJSEBLOD_TEMPLATES;
		
		$this->setRedirect( $link, $msg, $msgType );
	}

	/**
	 * Publish && Redirect
	 **/
	function publish()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'template' );
		
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if ( count( $cid ) < 1 ) {
			JError::raiseError( 500, JText::_( 'Select an Item to publish' ) );
		}

		if ( ! $model->publish( $cid, 1 ) ) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$link = _LINK_CCKJSEBLOD_TEMPLATES;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Unpublish && Redirect
	 **/
	function unpublish()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'template' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );

		if ( count( $cid ) < 1 ) {
			JError::raiseError( 500, JText::_( 'Select an Item to unpublish' ) );
		}

		if( ! $model->publish( $cid, 0 ) ) {
			echo "<script> alert('".$model->getError( true )."'); window.history.go(-1); </script>\n";
		}

		$link = _LINK_CCKJSEBLOD_TEMPLATES;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Assignments Redirection
	 **/
	function views()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link = _LINK_CCKJSEBLOD_TEMPLATES_VIEWS;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Categories Redirection
	 **/
	function categories()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link = _LINK_CCKJSEBLOD_TEMPLATES_CATEGORIES;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Add Category Redirection
	 **/
	function addcategory()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link = _LINK_CCKJSEBLOD_TEMPLATES_CATEGORIES . '&task=add';
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Batch Category Process
	 **/
	function batchCategory()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
				
		$model = $this->getModel( 'template' );

		if ( $total = $model->batchCategory() ) {
			$msg = JText::sprintf( 'ITEMS UPDATED', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_TEMPLATES;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**	FROM 2ND ( View = Template ) **/
	/**
	 * Save && Redirect
	 **/
	function save()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'template' );

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
				$link = ( $rowId ) ? _LINK_CCKJSEBLOD_TEMPLATES.'&task=edit&cid[]='.$rowId : _LINK_CCKJSEBLOD_TEMPLATES;
				break;
			case 'save':
				$link = _LINK_CCKJSEBLOD_TEMPLATES;
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
		
		$model = $this->getModel( 'template' );
		
		if ( $rowId = $model->liveStore() ) {
			//$msg = JText::_( 'Item Saved' );
			//$msgType = 'message';
		} else {
			//$msg = JText::_( 'An error has occurred' );
			//$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_TEMPLATES;
		
		$this->setRedirect( $link );	//$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Cancel && Redirect
	 **/ 
	function cancel()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'template' );
		
		// Checkin!
		$model->checkin();
		
		$link = _LINK_CCKJSEBLOD_TEMPLATES;
		
		$this->setRedirect( $link );
	}
		
	/**	FROM 3RD ( View = Sample || View = Sources ) **/
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
	
	/**	FROM 4TH ( View = Source )  **/
	/**
	 * Save && Redirect
	 **/
	function saveSource()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$cid 			= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$fileContent	= JRequest::getVar( 'filecontent', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$template		= JRequest::getVar( 'template' );
		$dir 			= JRequest::getVar( 'dir' );
		$file 			= JRequest::getVar( 'file' );
		
		$extension = JFile::getExt( $file );
		
		if ( $extension == 'php' || $extension == 'xml' ) {
			if ( JString::strpos( $file, '.css.' ) !== false ) {
				$path = JPATH_SITE.DS.'templates'.DS.$template.DS.'css'.DS.$file;
			} else if ( JString::strpos( $file, '.js.' ) !== false ) {
				$path = JPATH_SITE.DS.'templates'.DS.$template.DS.'js'.DS.$file;
			} else {
				$path = JPATH_SITE.DS.'templates'.DS.$template.DS.$file;
			}
		} else {
			$path = JPATH_SITE.DS.'templates'.DS.$template.DS.$dir.DS.$file;
		}
		
		if ( JFile::exists( $path ) ) {
			$return = JFile::write( $path, $fileContent );
		}

		$link = _LINK_CCKJSEBLOD_TEMPLATES.'&task=source&cid[]='.$cid[0].'&dir='.$dir.'&file='.$file.'&tmpl=component';
		
		$this->setRedirect( $link );
	}

	function saveParams()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$id			=	JRequest::getVar( 'template_id' );
		$params		=	JRequest::getVar('params', array(), 'post', 'array');
		$template	=	JRequest::getVar( 'template' );
		$file		=	JPATH_SITE.DS.'templates'.DS.$template.DS.'params.ini';
		
		if ( JFile::exists( $file ) && count( $params ) )
		{
			$registry	=	new JRegistry();
			$registry->loadArray( $params );
			$txt		=	$registry->toString();
			JFile::write( $file, $txt );
		}
		
		if ( $this->getTask() == 'applyparams' ) {
			$link	=	'index.php?option=com_cckjseblod&controller=templates&task=params&tmpl=component&cid[]='.(int)$id;
		} else {
			$link	=	_LINK_CCKJSEBLOD.'&tmpl=component&task=ccklose';
		}
		
		$this->setRedirect( $link );
	}
	
	function saveLocations()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$locations	=	JRequest::getVar( 'locations' );
		$template	=	JRequest::getVar( 'template' );
		
		HelperjSeblod_Helper::setTemplateLocPos( 'location', $locations, $template );
		
		$link	=	_LINK_CCKJSEBLOD.'&tmpl=component&task=ccklose';
		
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
			$hidden	=	explode( ',', _ITEM_HIDDEN_TEMPLATE );
			if ( array_search( $available, $hidden ) ) {
			  $total = 1;
			} else if ( $available == 'templates' || $available == 'template' ) {
				$total = 1;
			} else {
				$db	=& JFactory::getDBO();
				$where 		= ' WHERE s.name = "'.$available.'"';
				
				$query = ' SELECT COUNT( s.id )'
					   . ' FROM #__jseblod_cck_templates AS s'
					   . $where
					   ;
				$db->setQuery( $query );
				$total	=	$db->loadResult();
			}
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
		
		$model = $this->getModel( 'template' );
		
		if ( $model->addIntoPack() ) {
			$msg = JText::_( 'ELEMENTS ADDED' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$redirect	=	JRequest::getVar( 'add_redirection' );
		$link 		=	$redirect ? _LINK_CCKJSEBLOD_PACKS : _LINK_CCKJSEBLOD_TEMPLATES;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Export Xml
	 **/
	function exportXml()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'template' );
		
		if ( $file = $model->exportXml() ) {
			$this->setRedirect( 'components/com_cckjseblod/download.php?file='.$file );
			$msg = JText::_( 'TEMPLATES EXPORTED' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
			$link = _LINK_CCKJSEBLOD_TEMPLATES;
			$this->setRedirect( $link, $msg, $msgType );
		}
		
		//$link = _LINK_CCKJSEBLOD_TEMPLATES;
		
		//$this->setRedirect( $link, $msg, $msgType );
	}
}
?>