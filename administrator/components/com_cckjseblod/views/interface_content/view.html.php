<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 20098 jSeblod. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Interface_Content	View Class
 **/
class CCKjSeblodViewInterface_Content extends JView
{
	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$user 		=& JFactory::getUser();
		$controller = JRequest::getWord( 'controller' );
		$document	=& JFactory::getDocument();
		$model 		=&	$this->getModel();
		
		$cat_id		=	JRequest::getString( 'cat_id' );
		$u_opt		=	JRequest::getString( 'u_opt' );
		$u_task		=	JRequest::getString( 'u_task' );
		$contentTypeId	=	JRequest::getInt( 'contenttype' );
		$contentType	=	$model->getContentTypeTitle( $contentTypeId );
		
		$doApply    = JRequest::getVar( 'doApply', false );
		$actionMode = 	JRequest::getInt( 'actionmode' );
		$task		=	( $doApply ) ? 'apply' : 'save';
		$content	=	$model->doStore( $actionMode );
		
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		
		$this->assignRef( 'task', $task );
		$this->assignRef( 'actionMode', $actionMode );
		$this->assignRef( 'contentType', $contentType );
		$this->assignRef( 'content', $content );
		$this->assignRef( 'cat_id', $cat_id );
		$this->assignRef( 'u_opt', $u_opt );
		$this->assignRef( 'u_task', $u_task );
		
		parent::display( $tpl );
	}
	
}
?>