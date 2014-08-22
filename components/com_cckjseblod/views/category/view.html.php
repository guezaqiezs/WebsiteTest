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
 * Category			View Class
 **/
class CCKjSeblodViewCategory extends JView
{
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
		$model 		=&	$this->getModel();
		
		$itemId		=	JRequest::getInt( 'Itemid' );
		
		$menus		=	&JSite::getMenu();
		$menu 		=	$menus->getActive();
		if ( is_object( $menu ) ) {
			$menu_params	=	new JParameter( $menu->params );
		}
		
		$categoryItems	=	$model->getData();
		if ( @$menu_params && $menu_params->get( 'auto_redirect', 0 ) ) {
			if ( count( $categoryItems ) == 1 && @$categoryItems[0]->id ) {
				$this->setLayout( 'form' );
				require_once( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'views'.DS.'type'.DS.'view.html.php' );
				CCKjSeblodViewType::display( null, @$categoryItems[0]->id, @$categoryItems[0]->content_typeid );
				return;
			}
		}
		if ( @$menu_params && $menu_params->get( 'page_title' ) ) {
			$page_title	=	$menu_params->get( 'page_title' );
			$document->setTitle( $page_title );	
		} else {
			$page_title	=	JText::_( 'SUBMITTED CATEGORIES' );
		}
		
		//$date_format	=	( $menu_params->get( 'date_format' ) ) ? $menu_params->get( 'date_format' ) : JText::_('DATE_FORMAT_LC4');

		
		// Push Data to Template
		$this->assignRef( 'option', $option);
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		//
		$this->assignRef( 'itemId', $itemId );
		$this->assignRef( 'menu_params', $menu_params );
		$this->assignRef( 'page_title', $page_title );
		$this->assignRef( 'date_format', $date_format );
		$this->assignRef( 'categoryItems', $categoryItems );
				
		parent::display( $tpl );
	}
	
}
?>