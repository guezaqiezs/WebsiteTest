<?php
/**
 * CustomFlash Joomla! 1.5 Native Component
 * @version 1.2.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class JElementCustomFlash extends JElement
{


	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id, moviename '
		. ' FROM #__customflash '
		. ' ORDER BY moviename'
		;
		$db->setQuery( $query );
		$options = $db->loadObjectList( );
		if(!$options) $options = array();
		
		return JHTML::_('select.genericlist', $options, $control_name.'['.$name.']', 'class="inputbox"', 'id', 'moviename', $value);
	}
}