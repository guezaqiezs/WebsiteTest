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
 * Content Type		Element Class
 **/
class JElementTypeCategories extends JElement
{
	/**
	 * Element name
	 **/
	var	$_name = 'TypeCategories';
	
	function fetchElement( $name, $value, &$node, $control_name )
	{
		global $mainframe;
		$db		=&	JFactory::getDBO();

		$class	=	$node->attributes('class');
		if ( ! $class ) {
			$class	=	"inputbox";
		}

		$query	= 'SELECT s.id, s.title'
				. ' FROM #__jseblod_cck_types_categories AS s'
				. ' WHERE s.published = 1 AND s.name != "TOP"'
				. ' ORDER BY s.title'
				;
		$db->setQuery($query);

		$optCategories	=	$db->loadObjectList();
		array_unshift( $optCategories, JHTML::_('select.option', '0', '- '.JText::_('Select Category').' -', 'id', 'title' ) );

		return JHTML::_('select.genericlist',  $optCategories, ''.$control_name.'['.$name.']', 'class="'.$class.'"', 'id', 'title', $value, $control_name.$name );
	}
}

?>