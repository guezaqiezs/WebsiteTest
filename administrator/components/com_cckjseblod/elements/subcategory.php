<?php
/**
* @version		$Id: category.php 10707 2008-08-21 09:52:47Z eddieajau $
* @package		Joomla.Framework
* @subpackage	Parameter
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Renders a category element with subcategories support
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementSubcategory extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Category';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();

		$section	= $node->attributes('section');
		$class		= $node->attributes('class');
		if (!$class) {
			$class = "inputbox";
		}

		if (!isset ($section)) {
			// alias for section
			$section = $node->attributes('scope');
			if (!isset ($section)) {
				$section = 'content';
			}
		}

		if ($section == 'content') {
            $query = 'SELECT s.id, s.title' .
                ' FROM #__sections AS s' .
                ' WHERE s.published = 1' .
                ' AND s.scope = '.$db->Quote($section).
                ' ORDER BY s.title';
            
            $db->setQuery($query);
            $section_options = $db->loadObjectList();
            
            $section_list = array();
            foreach($section_options as $section_option)
            {
                $section_list[] = $section_option->id;    
            }
            $section_list = implode('\', \'', $section_list);
            
            $query = 'SELECT id, parent_id, title, section' .
                    ' FROM #__categories' .
                    ' WHERE section IN ( \''.$section_list.'\' )' .
                    ' ORDER BY title';
            $db->setQuery($query);
            $cat_list = $db->loadObjectList();

            /**
             * BEGIN: Pulled from admin.categories.php
             */
            // establish the hierarchy of the menu
            $children = array();
            // first pass - collect children
            if ( sizeof( $cat_list ) ) {
              foreach ($cat_list as $cat )
              {
                  $pt = $cat->parent_id;
                  $list = @$children[$pt] ? $children[$pt] : array();
                  array_push( $list, $cat );
                  $children[$pt] = $list;
              }
            }
            // second pass - get an indent list of the items
            $cat_list = JHTML::_('menu.categorytreerecurse', 0, '', array(), $children, 9999, 0, 0 );
            /**
             * END: Pulled from admin.categories.php
             */
             
             $category_options = array();
             foreach($cat_list as $cat_item)
             {   
                 if (!isset($category_options[$cat_item->section]) || !is_array($category_options[$cat_item->section]))
                 {
                    $category_options[$cat_item->section] = array();
                 }
                 $category_options[$cat_item->section][$cat_item->id] = new stdClass();
                 $category_options[$cat_item->section][$cat_item->id]->id = $cat_item->id;
                 $category_options[$cat_item->section][$cat_item->id]->title = $cat_item->treename;
             }
            
            $options = array();
            $options[] = JHTML::_('select.option', '0', '- '.JText::_('Select Category').' -', 'id', 'title'); 
            foreach ($section_options as $section_option)
            {
                $options[] = JHTML::_('select.optgroup', $section_option->title, 'id', 'title');
				if ( sizeof ( @$category_options[$section_option->id] ) ) {
					foreach($category_options[$section_option->id] as $category_option)
					{
						$options[] = JHTML::_('select.option', $category_option->id, $category_option->title, 'id', 'title');
					}
				}
            }

            return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="'.$class.'"', 'id', 'title', $value, $control_name.$name );
		} else {
			$query = 'SELECT c.id, c.parent_id, c.title' .
				' FROM #__categories AS c' .
				' WHERE c.published = 1' .
				' AND c.section = '.$db->Quote($section).
				' ORDER BY c.title';
            
            $db->setQuery($query);
            $cat_list = $db->loadObjectList();
            
            /**
             * BEGIN: Pulled from admin.categories.php
             */
            // establish the hierarchy of the menu
            $children = array();
            // first pass - collect children
            if ( sizeof( $cat_list ) ) {
            foreach ($cat_list as $cat )
              {
                  $pt = $cat->parent_id;
                  $list = @$children[$pt] ? $children[$pt] : array();
                  array_push( $list, $cat );
                  $children[$pt] = $list;
              }
            }
            // second pass - get an indent list of the items
            $cat_list = JHTML::_('menu.categorytreerecurse', 0, '', array(), $children, 9999, 0, 0 );
            /**
             * END: Pulled from admin.categories.php
             */
             
             $category_options = array();
             foreach($cat_list as $cat_item)
             {
                 $category_options[$cat_item->id] = new stdClass();
                 $category_options[$cat_item->id]->id = $cat_item->id;
                 $category_options[$cat_item->id]->title = $cat_item->treename;
             }
            
            $options = array();
            $options[] = JHTML::_('select.option', '0', '- '.JText::_('Select Category').' -', 'id', 'title'); 

            foreach($category_options as $category_option)
            {
                $options[] = JHTML::_('select.option', $category_option->id, $category_option->title, 'id', 'title');
            }

            return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="'.$class.'"', 'id', 'title', $value, $control_name.$name );
		}
	}
}