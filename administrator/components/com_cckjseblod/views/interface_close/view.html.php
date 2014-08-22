<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

/**
 * Interface_Close	View Class
 **/
class CCKjSeblodViewInterface_Close extends JView
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
		$lang_id	=&	JRequest::getInt( 'lang_id' );
		
		$u_lang		=	( $lang_id ) ? '&lang='.CCK_LANG_ShortCode( $lang_id ) : '';
		$action		=	JRequest::getVar( 'action' );
		switch ( $action ) {
			case '4':
			case '2':
				$ajaxUrl	=	'index.php?option=com_users&tmpl=component';
				break;
			case '1':
				$ajaxUrl	=	'index.php?option=com_categories&section=com_content&tmpl=component';
				break;
			case '0':
			default:
				$ajaxUrl	=	'index.php?option=com_content'.$u_lang.'&tmpl=component';
				break;
		}
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		$this->assignRef( 'ajaxUrl', $ajaxUrl );
		
		parent::display( $tpl );
	}
	
}
?>