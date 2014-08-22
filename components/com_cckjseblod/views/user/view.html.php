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
jimport( 'joomla.filesystem.file' );

/**
 * User		View Class
 **/
class CCKjSeblodViewUser extends JView
{
	/**
	 * Redirect (NOTAUTH)
	 **/
	function redirectNotAuth( $userId, $url, $message, $type )
	{
		global $mainframe;
		
		if ( $userId ) {
			$mainframe->redirect( 'index.php', JText::_( 'CONTENT TYPE NOTAUTH' ), "error" );
		} else {
			$url	=	( $url ) ? $url : 'index.php?option=com_user&view=login';
			$mainframe->redirect( $url, JText::_( 'CONTENT TYPE NOTAUTH' ), "error" );
		}
	}
	
	function display( $tpl = null )
	{
		global $mainframe, $option;
		$controller	=	JRequest::getWord( 'controller' );
		$document	=&	JFactory::getDocument();
		$layout		=	$this->getLayout();
		$itemId		=	JRequest::getInt( 'Itemid' );
		$model 		=&	$this->getModel();
		$user 		=&	JFactory::getUser();

		$menus		=	&JSite::getMenu();
		$menu 		=	$menus->getActive();
		if ( is_object( $menu ) ) {
			$menu_params	=	new JParameter( $menu->params );
		}
		
		$articleItems	=	$model->getDataUser();
		
		if ( @$menu_params && $menu_params->get( 'auto_redirect', 0 ) ) {
			if ( count( $articleItems ) < 2 ) {
				if ( @$articleItems[0]->id ) {
					$aid	=	@$articleItems[0]->id;
					$typeid	=	@$articleItems[0]->content_typeid;
				} else {
					$aid	=	0;
					$typeid	=	$menu_params->get( 'add_typeid' );
				}
				$this->setLayout( 'form' );
				require_once( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'views'.DS.'type'.DS.'view.html.php' );
				CCKjSeblodViewType::display( null, $aid, $typeid );
				return;
			}
		}
		if ( @$menu_params && $menu_params->get( 'page_title' ) ) {
			$page_title	=	$menu_params->get( 'page_title' );
			$document->setTitle( $page_title );	
		}
					
		if ( $layout == 'default' ) {
			$page_title		=	( @$page_title ) ? $page_title : JText::_( 'USERS' );

			//auto redirect TODO
			$userItems	=	$model->getDataDefault();
			
			// Push Data to Template
			$this->assignRef( 'option', $option);
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document', $document );
			//
			$this->assignRef( 'itemId', $itemId );
			$this->assignRef( 'menu_params', $menu_params );
			$this->assignRef( 'page_title', $page_title );
			$this->assignRef( 'userItems', $userItems );
			
			parent::display( $tpl );
			return;			
		} else if ( $layout == 'form' ) {
			if ( $user->id ) {
				$userForm	=	$model->getDataForm();
				if ( ! @$userForm->contentid || ! @$userForm->typeid ) {
					return;
				}
				require_once( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'views'.DS.'type'.DS.'view.html.php' );
				CCKjSeblodViewType::display( null, $userForm->contentid, $userForm->typeid );
			}
			return;
		} else if ( $layout == 'user' ) {
			$page_title		=	( @$page_title ) ? $page_title : JText::_( 'SUBMISSIONS' );

			//auto redirect TODO
			$articleItems	=	$model->getDataUser();
			
			$date_format	=	( @$menu_params && $menu_params->get( 'date_format' ) ) ? $menu_params->get( 'date_format' ) : JText::_('DATE_FORMAT_LC4');
			
			// Push Data to Template
			$this->assignRef( 'option', $option);
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document', $document );
			//
			$this->assignRef( 'itemId', $itemId );
			$this->assignRef( 'menu_params', $menu_params );
			$this->assignRef( 'page_title', $page_title );
			$this->assignRef( 'date_format', $date_format );
			$this->assignRef( 'articleItems', $articleItems );
			
			parent::display( $tpl );
			return;
		}
		
		/********************************************************************
		**************************** HOME PAGE ******************************
	 	********************************************************************/
	 
		$data		=	'My Homepage <I>Coming Soon!</I>';
		
		// Push Data into Template
		$this->assignRef( 'option', $option);
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		//
		$this->assignRef( 'data', $data );
		//	
		$this->assignRef( 'itemId', $itemId );
		
		parent::display( $tpl );
	}
	
}
?>