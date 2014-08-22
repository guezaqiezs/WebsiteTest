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

/**
 * Article			View Class
 **/
class CCKjSeblodViewArticle extends JView
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
	
	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$user 		=&	JFactory::getUser();
		$controller =	JRequest::getWord( 'controller' );
		$document	=&	JFactory::getDocument();
		$itemId		=	JRequest::getInt( 'Itemid' );
		$model 		=&	$this->getModel();
		
		$menus		=	&JSite::getMenu();
		$menu 		=	$menus->getActive();
		if ( is_object( $menu ) ) {
			$menu_params	=	new JParameter( $menu->params );
		}
		
		$articleItems	=	$model->getData();
		
		if ( @$menu_params && $menu_params->get( 'auto_redirect', 0 ) ) {
			if ( count( $articleItems ) == 1 && @$articleItems[0]->id ) {
				$this->setLayout( 'form' );
				require_once( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'views'.DS.'type'.DS.'view.html.php' );
				CCKjSeblodViewType::display( null, @$articleItems[0]->id, @$articleItems[0]->content_typeid );
				return;
			}
		}
		
		if ( @$menu_params && $menu_params->get( 'page_title' ) ) {
			$page_title	=	$menu_params->get( 'page_title' );
			$document->setTitle( $page_title );	
		} else {
			$page_title	=	JText::_( 'SUBMITTED ARTICLES' );
		}
		
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
	}
	
}
?>