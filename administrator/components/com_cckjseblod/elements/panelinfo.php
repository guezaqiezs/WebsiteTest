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

/**
 * CCKjSeblod 		Element Class
 **/
class JElementPanelInfo extends JElement
{
	/**
	 * Element name
	 **/
	var	$_name = 'PanelInfo';
	
	function fetchElement( $name, $value, &$node, $control_name )
	{
		global $mainframe;
		
		$db					=&	JFactory::getDBO();
		$doc 				=&	JFactory::getDocument();
		$template 			=	$mainframe->getTemplate();
		$fieldName			=	$control_name.'['.$name.']';
		
		$html				=	'<strong><font color="#6CC634">'.JText::_( 'MENU PANEL INFO' ).'</font></strong>';
		
		return $html;
	}
}
