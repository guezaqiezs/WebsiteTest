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
 * CCKjSeblod		Component Controller
 **/
class CCKjSeblodController extends JController
{
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
		
		// Register Extra Tasks
		$this->registerTask( 'cpanel_process', 'replaceProcess' );
	}
	
	/**
	 * Display Default View
	 **/
	function display()
	{
		global $mainframe;
		
		switch( $this->getTask() ) {
		case 'ccklose':
			JRequest::setVar( 'view', 'cckjseblod' );
			JRequest::setVar( 'layout', 'ccklose' );
			break;
		case 'data':
			JRequest::setVar( 'view', 'cckjseblod' );
			JRequest::setVar( 'layout', 'data' );
			break;
		case 'media':
			JRequest::setVar( 'view', 'cckjseblod' );
			JRequest::setVar( 'layout', 'media' );
			break;
		case 'process':
			JRequest::setVar( 'view', 'cckjseblod' );
			JRequest::setVar( 'layout', 'process' );
			break;
		default:
			// Set Default View
			$view = JRequest::getCmd( 'view' );
			if ( empty( $view ) ) {
				JRequest::setVar( 'view', 'cckjseblod' );
				JRequest::setVar( 'layout', 'default' );
			}
			break;
		}
		
		parent::display();
	}
	
	/**
	 * Display Cpanel View
	 **/
	function cpanel()
	{
		$link = _LINK_CCKJSEBLOD;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Display Help View
	 **/
	function cpanel_help()
	{
		$link = _LINK_CCKJSEBLOD_CONFIGURATION;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Display Interface View
	 **/
	function cpanel_interface()
	{
		$link = _LINK_CCKJSEBLOD_INTERFACE.'&brb=2&act=-1&cck=1';
		
		$this->setRedirect( $link );
	}
		
	/**
	 * Data Import Process
	 **/
	function dataImportProcess()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
				
		$model	=	$this->getModel( 'cckjseblod' );
		$toggle	=	JRequest::getInt( 'toggle' );
		$action	=	JRequest::getInt( 'action_mode' );
		
		if ( $toggle == 3 ) {
			$model->dataImportXML( $action );
		} else {
			$model->dataImportCSV( $action );
		}
		
		if ( $total ) {
			$msgType	=	'message';
			$msg		=	JText::sprintf( 'ITEMS IMPORTED', $total );
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	_LINK_CCKJSEBLOD.'&tmpl=component&task=ccklose';
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Data Export Process
	 **/
	function dataExportProcess()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
				
		$model	=	$this->getModel( 'cckjseblod' );
		$toggle	=	JRequest::getInt( 'toggle' );
		$action	=	JRequest::getInt( 'action_mode' );
		
		if ( $toggle == 4 ) {
			$data	=	$this->dataExportHTML( 1 );
			$file	=	$model->dataExportHTML( $data );
		} else if ( $toggle == 2 ) {
			$file	=	$model->dataExportXML( $action );
		} else {
			$file	=	$model->dataExportCSV( $action );
		}

		if ( $file ) {
			$msgType	=	'message';
			$msg		=	JText::sprintf( 'ITEMS EXPORTED', $total );
			$this->setRedirect( 'components/com_cckjseblod/download.php?file='.$file );
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
			$link = _LINK_CCKJSEBLOD.'&tmpl=component&task=ccklose';
			$this->setRedirect( $link, $msg, $msgType );
		}
		
		//$this->setRedirect( $link, $msg, $msgType );
	}
	
	function dataExportHTML( $ajax = 0 ) {
		$db		=&	JFactory::getDBO();
		
		$select	=	JRequest::getVar( 'selection' );
				
		$ids	=	explode( ',', $select );
		$id		=	$ids[0];
		
		$query	= ' SELECT s.*, cc.title AS category, sc.name AS author, CONCAT(s.introtext, s.fulltext) AS text'
				. ' FROM #__content AS s '
				. ' LEFT JOIN #__categories AS cc ON cc.id = s.catid'
				. ' LEFT JOIN #__users AS sc ON sc.id = s.created_by'
				. ' WHERE s.id='.$id
				;
		
		$db->setQuery( $query );
		$row	=	$db->loadObject();
		
		$dispatcher	=&	JDispatcher::getInstance();
		JPluginHelper::importPlugin( 'content' );
		$limitstart	=	JRequest::getVar( 'limitstart', 0, '', 'int' );

		$i						=	0;
		$rows[$i]				=	$row;
		$rows[$i]->parameters	=	new JParameter( @$rows[$i]->attribs );
		$rows[$i]->event		=	new stdClass ();
		$results	=	$dispatcher->trigger( 'onPrepareContent', array ( &$rows[$i], & $rows[$i]->parameters, $limitstart ) );
		$results	=	$dispatcher->trigger( 'onAfterDisplayTitle', array ( $rows[$i], & $rows[$i]->parameters, $limitstart ) );
		$rows[$i]->event->afterDisplayTitle = trim( implode( "\n", $results ) );
		$results	=	$dispatcher->trigger( 'onBeforeDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, $limitstart ) );
		$rows[$i]->event->beforeDisplayContent = trim( implode( "\n", $results ) );
		$results	=	$dispatcher->trigger( 'onAfterDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, $limitstart ) );
		$rows[$i]->event->afterDisplayContent = trim( implode( "\n", $results ) );
		$data		=	$rows[$i]->text; //JString::transcode( $rows[$i]->text, '', 'UTF-8' );
				
		// Update Paths
		$data	=	HelperjSeblod_Helper::absolutePaths( $data );

		if ( $ajax ) {
			return $data;
		} else {
			echo $data;
		}
	}
	
	/**
	 * Media Process
	 **/
	function mediaProcess()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
				
		$model = $this->getModel( 'cckjseblod' );

		if ( $total = $model->mediaProcess() ) {
			$msg = JText::sprintf( 'ITEMS UPDATED', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD.'&tmpl=component&task=ccklose';
		
		$this->setRedirect( $link, $msg, $msgType );
	}

	/**
	 * Replace Process
	 **/
	function replaceProcess()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
				
		$model = $this->getModel( 'cckjseblod' );

		if ( $total = $model->replaceProcess() ) {
			$msg = JText::sprintf( 'ITEMS UPDATED', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = 'index.php?option=com_content';
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Lang Publish
	 **/
	function langPublish()
	{
		$langC		=	JRequest::getVar( 'lang' );
		$langId		=	CCK_LANG_Id( $langC );
		
		$artids		=	JRequest::getVar( 'artids' );
		$artids		=	substr( $artids, 0, -1 );
		
		if ( $artids ) {
			$model 	=	$this->getModel( 'cckjseblod' );
			if ( $model->langPublish( $artids, $langId ) ) {
				$msg = JText::_( 'Translation(s) Published' );
				$msgType = 'message';
			} else {
				$msg = JText::_( 'An error has occurred' );
				$msgType = 'error';
			}
		}
		
		$link = 'index.php?option=com_content&lang='.$langC;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Lang Unpublish
	 **/
	function langUnpublish()
	{
		$langC		=	JRequest::getVar( 'lang' );
		$langId		=	CCK_LANG_Id( $langC );
		
		$artids		=	JRequest::getVar( 'artids' );
		$artids		=	substr( $artids, 0, -1 );
		
		if ( $artids ) {
			$model 	=	$this->getModel( 'cckjseblod' );
			if ( $model->langUnpublish( $artids, $langId ) ) {
				$msg = JText::_( 'Translation(s) Unpublished' );
				$msgType = 'message';
			} else {
				$msg = JText::_( 'An error has occurred' );
				$msgType = 'error';
			}
		}
		
		$link = 'index.php?option=com_content&lang='.$langC;
		
		$this->setRedirect( $link, $msg, $msgType );
	}

	/**
	 * Lang Trash
	 **/
	function langTrash()
	{
		$langC		=	JRequest::getVar( 'lang' );
		$langId		=	CCK_LANG_Id( $langC );
		
		$artids		=	JRequest::getVar( 'artids' );
		$artids		=	substr( $artids, 0, -1 );
		
		if ( $artids ) {
			$model 	=	$this->getModel( 'cckjseblod' );
			if ( $model->langTrash( $artids, $langId ) ) {
				$msg = JText::_( 'Translation(s) Deleted' );
				$msgType = 'message';
			} else {
				$msg = JText::_( 'An error has occurred' );
				$msgType = 'error';
			}
		}
		
		$link = 'index.php?option=com_content&lang='.$langC;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Lang Translate
	 **/
	function langTranslate()
	{
		$artids		=	JRequest::getVar( 'artids' );
		$artids		=	substr( $artids, 0, -1 );
		
		if ( $artids ) {
			$model 	=	$this->getModel( 'cckjseblod' );
			if ( $model->langTranslate( $artids ) ) {
				$msg = JText::_( 'Translation(s) Saved' );
				$msgType = 'message';
			} else {
				$msg = JText::_( 'An error has occurred' );
				$msgType = 'error';
			}
		}
		
		$link = 'index.php?option=com_content';
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Quick Config
	 **/
	function quickConfig()
	{		
		global $mainframe;
		
		$model	=	$this->getModel( 'cckjseblod' );
		
		if ( $model->quickConfig() ) {
			$msg = JText::sprintf( 'CONFIGURATION SAVED', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		//$link = _LINK_CCKJSEBLOD;		
		
		//$this->setRedirect( $link, $msg, $msgType );
	}
}
?>