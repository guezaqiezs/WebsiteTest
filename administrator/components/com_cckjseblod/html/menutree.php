<?php
/**
* @version		$Id: menu.php 10381 2008-06-01 03:35:53Z pasamio $
* @package		Joomla.Framework
* @subpackage		HTML
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Utility class working with menu select lists
 *
 * @static
 * @package 	Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */
class JHTMLMenuTree
{
	/**
	* Build the multiple select list for Menu Links/Pages
	*/
	function linkoptions( $menutype = null )
	{
		$db =& JFactory::getDBO();
		
		$where_menutype	=	( $menutype ) ? ' AND cc.id ="'.$menutype.'"' : '';
		
		// get a list of the menu items
		$query = 'SELECT m.id, m.parent, m.name, m.menutype, cc.id AS menutypeid, cc.title AS menutypetitle'
		. ' FROM #__menu AS m'
		. ' LEFT JOIN #__menu_types AS cc on cc.menutype = m.menutype'
		. ' WHERE m.published = 1'.$where_menutype
		. ' ORDER BY m.menutype, m.parent, m.ordering'
		;
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		$mitems_temp = $mitems;

		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
    if ( sizeof( $mitems ) ) {
  		foreach ( $mitems as $v )
  		{
  			$id = $v->id;
  			$pt = $v->parent;
  			$list = @$children[$pt] ? $children[$pt] : array();
  			array_push( $list, $v );
  			$children[$pt] = $list;
  		}
		}
		// second pass - get an indent list of the items
		$list = JHTMLMenuTree::TreeRecurse( intval( $mitems[0]->parent ), '', array(), $children, 9999, 0, 0 );

		// Code that adds menu name to Display of Page(s)
		$mitems_spacer 	= $mitems_temp[0]->menutype;

		$mitems = array();
		$lastMenuType	= null;
		if ( sizeof( $list ) ) {
  		foreach ($list as $list_a)
  		{
  			if ($list_a->menutype != $lastMenuType)
  			{
  				$mitems[] = JHTML::_('select.option',  'menutype-'.$list_a->menutypeid, $list_a->menutypetitle );
  				$lastMenuType = $list_a->menutype;
  			}
  			
  			$mitems[] = JHTML::_('select.option',  $list_a->id, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$list_a->treename );
  		}
    }
		return $mitems;
	}

	function treerecurse( $id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1 )
	{
		if (@$children[$id] && $level <= $maxlevel)
		{
			foreach ($children[$id] as $v)
			{
				$id = $v->id;

				if ( $type ) {
					$pre 	= '<sup>|_</sup>&nbsp;';
					$spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				} else {
					$pre 	= '- ';
					$spacer = '&nbsp;&nbsp;';
				}

				if ( $v->parent == 0 ) {
					$txt 	= $v->name;
				} else {
					$txt 	= $pre . $v->name;
				}
				$pt = $v->parent;
				$list[$id] = $v;
				$list[$id]->treename = "$indent$txt";
				$list[$id]->children = count( @$children[$id] );
				$list = JHTMLMenuTree::TreeRecurse( $id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type );
			}
		}
		return $list;
	}
}