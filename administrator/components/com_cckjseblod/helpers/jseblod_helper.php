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
 * HelperjSeblod	Helper Class
 **/
class HelperjSeblod_Helper
{	
	/**
	 * Get Joomla SectionId
	 **/
	function getJoomlaSectionId( $catId )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $catId ) {
			$query 	= 'SELECT s.section'
					. ' FROM #__categories AS s'
					. ' WHERE s.id = '.$catId;
					;
			$db->setQuery( $query );
			$joomlaSectionId	=	$db->loadResult();
		}
		
		return $joomlaSectionId;
	}
	
	/**
     * Get Template Categories
     **/
	function getTemplateCategories( $excluded, $published )
	{
		$db	=&	JFactory::getDBO();
		
		$n			=	( $excluded ) ? 2 : 1;
		$orderby	=	' GROUP BY s.title ORDER BY s.lft';	
		$where		=	( $excluded ) ? ' WHERE s.lft > 1 AND s.lft BETWEEN parent.lft AND parent.rgt' : ' WHERE s.lft > 0 AND s.lft BETWEEN parent.lft AND parent.rgt';
		$where		=	( $published ) ? $where . ' AND s.published = 1' : $where;
		
		$query	= 'SELECT CONCAT( REPEAT("&nbsp;&nbsp;&nbsp;&nbsp;", COUNT(parent.title) - '.$n.'), s.title) AS text, s.id AS value'
				. ' FROM #__jseblod_cck_templates_categories AS s, #__jseblod_cck_templates_categories AS parent'
				. $where
				. $orderby
				;
		
		$db->setQuery( $query );
		$categories	=	$db->loadObjectList();
		
		if ( sizeof( $categories ) ) {
			$categories[0]->text	=	( ! $excluded ) ? JText::_( $categories[0]->text ) : $categories[0]->text;
		}
		
		return $categories;
	}
	
	/**
     * Get Content Templates
     **/
	function getContentTemplates( $tmplIds = null )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $tmplIds != '' ) {
			if ( strpos( $tmplIds, ',' ) !== false ) {
				$where	=	' WHERE s.id IN ('.$tmplIds.')';
			} else {
				$where	=	' WHERE s.id = '.$tmplIds;
			}
		} else {
			$where	=	'';
		}
		
		$query 	= 'SELECT s.id AS value, s.title AS text'
				. ' FROM #__jseblod_cck_templates AS s'
				. $where
				. ' ORDER BY s.title asc'
				;
		$db->setQuery( $query );
		$contentTemplates	=	$db->loadObjectList();
		
		return $contentTemplates;
	}
	
	/**
     * Get Type Categories
     **/
	function getTypeCategories( $excluded, $published )
	{
		$db	=&	JFactory::getDBO();
		
		$n			=	( $excluded ) ? 2 : 1;
		$where 		=	( $excluded ) ? ' WHERE s.lft > 1 AND s.lft BETWEEN parent.lft AND parent.rgt' : ' WHERE s.lft > 0 AND s.lft BETWEEN parent.lft AND parent.rgt';
		$where		=	( $published ) ? $where . ' AND s.published = 1' : $where;
		$orderby	=	' GROUP BY s.title ORDER BY s.lft';	
		
		$query 	= 'SELECT CONCAT( REPEAT("&nbsp;&nbsp;&nbsp;&nbsp;", COUNT(parent.title) - '.$n.'), s.title) AS text, s.id AS value'
				. ' FROM #__jseblod_cck_types_categories AS s, #__jseblod_cck_types_categories AS parent'
				. $where
				. $orderby
				;
		
		$db->setQuery( $query );
		$categories	=	$db->loadObjectList();
		
		if ( sizeof( $categories ) ) {
			$categories[0]->text	=	( ! $excluded ) ? JText::_( $categories[0]->text ) : $categories[0]->text;
		}
		
		return $categories;
	}
	
	/**
	 * Get Item Categories
  	 **/
	
	function getItemCategories( $excluded, $published )
	{
		$db	=&	JFactory::getDBO();
		
		$n			=	( $excluded ) ? 2 : 1;
		$where 		=	( $excluded ) ? ' WHERE s.lft > 1 AND s.lft BETWEEN parent.lft AND parent.rgt' : ' WHERE s.lft > 0 AND s.lft BETWEEN parent.lft AND parent.rgt';
		$where		=	( $published ) ? $where . ' AND s.published = 1' : $where;
		$orderby	=	' GROUP BY s.title ORDER BY s.lft';	
		
		$query 	= 'SELECT CONCAT( REPEAT("&nbsp;&nbsp;&nbsp;&nbsp;", COUNT(parent.title) - '.$n.'), s.title) AS text, s.id AS value'
				. ' FROM #__jseblod_cck_items_categories AS s, #__jseblod_cck_items_categories AS parent'
				. $where
				. $orderby
				;
		
		$db->setQuery( $query );
		$categories	=	$db->loadObjectList();
		
		if ( sizeof( $categories ) ) {
			$categories[0]->text	=	( ! $excluded ) ? JText::_( $categories[0]->text ) : $categories[0]->text;
		}
		
		return $categories;
	}

	/**
	 * Get Item Categories
  	 **/
	
	function getItemCategories2( $excluded, $published )
	{
		$db	=&	JFactory::getDBO();
		
		$n			=	( $excluded ) ? 2 : 1;
		$where 		=	( $excluded ) ? ' WHERE s.lft > 1 AND s.lft BETWEEN parent.lft AND parent.rgt' : ' WHERE s.lft > 0 AND s.lft BETWEEN parent.lft AND parent.rgt';
		$where		=	( $published ) ? $where . ' AND s.published = 1' : $where;
		$where		.=	' AND s.display';
		$orderby	=	' GROUP BY s.title ORDER BY s.lft';	
		
		$query 	= 'SELECT CONCAT( REPEAT("&nbsp;&nbsp;&nbsp;&nbsp;", COUNT(parent.title) - '.$n.'), s.title) AS text, s.id AS value'
				. ' FROM #__jseblod_cck_items_categories AS s, #__jseblod_cck_items_categories AS parent'
				. $where
				. $orderby
				;
		
		$db->setQuery( $query );
		$categories	=	$db->loadObjectList();
		
		if ( sizeof( $categories ) ) {
			$categories[0]->text	=	( ! $excluded ) ? JText::_( $categories[0]->text ) : $categories[0]->text;
		}
		
		return $categories;
	}
	
	/**
	 * Get Item Categories Alpha
   **/
	
	function getItemCategoriesAlpha()
	{
		$db	=&	JFactory::getDBO();
		
		$where		=	' WHERE s.display > 0';
		$orderby	=	' ORDER BY s.lft';	
		
		$query 	= 'SELECT s.title AS text, s.id AS value'
				. ' FROM #__jseblod_cck_items_categories AS s'
				. $where
				. $orderby
				;
		
		$db->setQuery( $query );
		$categories	=	$db->loadObjectList();
		
		return $categories;
	}
	
	/**
     * Get Item Types
     **/
	function getItemTypes( $excluded = false )
	{
		$db	=&	JFactory::getDBO();
	  	
		$query	= 'SELECT s.name AS value, s.title AS text'
				. ' FROM #__jseblod_cck_items_types AS s'
				. ' WHERE s.id != 25'
				. ' ORDER BY s.title asc'
				;
		$db->setQuery( $query );
		$itemTypes	=	$db->loadObjectList();
		if ( sizeof( $itemTypes ) ) {
			foreach ( $itemTypes as $item ) {
				$item->text	=	JText::_( $item->text );
			}
		}
		if ( ! $excluded ) {
			$f_action			=	new stdClass();
			$f_action->value	=	'form_action';
			$f_action->text		=	JText::_( 'ACTION FORM ACTION' );
			array_unshift( $itemTypes, $f_action );
			// TODO: add Search Action
		}
		return $itemTypes;
	}
	
	/**
     * Get Item Types By Id
     **/
	function getItemTypesById()
	{
		$db	=&	JFactory::getDBO();
		
		$query	= 'SELECT s.id AS value, s.title AS text'
				. ' FROM #__jseblod_cck_items_types AS s'
				. ' WHERE s.id != 25 AND s.id != 46'
				. ' ORDER BY s.title asc'
				;
		$db->setQuery( $query );
		$itemTypes	=	$db->loadObjectList();
		if ( sizeof( $itemTypes ) ) {
			foreach ( $itemTypes as $item ) {
				$item->text	=	JText::_( $item->text );
			}
		}
		
		return $itemTypes;
	}

	/**
     * Get Type Categories
     **/
	function getSearchCategories( $excluded, $published )
	{
		$db	=&	JFactory::getDBO();
		
		$n			=	( $excluded ) ? 2 : 1;
		$where 		=	( $excluded ) ? ' WHERE s.lft > 1 AND s.lft BETWEEN parent.lft AND parent.rgt' : ' WHERE s.lft > 0 AND s.lft BETWEEN parent.lft AND parent.rgt';
		$where		=	( $published ) ? $where . ' AND s.published = 1' : $where;
		$orderby	=	' GROUP BY s.title ORDER BY s.lft';	
		
		$query 	= 'SELECT CONCAT( REPEAT("&nbsp;&nbsp;&nbsp;&nbsp;", COUNT(parent.title) - '.$n.'), s.title) AS text, s.id AS value'
				. ' FROM #__jseblod_cck_searchs_categories AS s, #__jseblod_cck_searchs_categories AS parent'
				. $where
				. $orderby
				;
		
		$db->setQuery( $query );
		$categories	=	$db->loadObjectList();
		
		if ( sizeof( $categories ) ) {
			$categories[0]->text	=	( ! $excluded ) ? JText::_( $categories[0]->text ) : $categories[0]->text;
		}
		
		return $categories;
	}
	
	/**
     * Get Content Types
     **/
	function getContentTypes()
	{
		$db	=&	JFactory::getDBO();
		
		$query	= 'SELECT s.id AS value, s.title AS text'
				. ' FROM #__jseblod_cck_types AS s'
				. ' WHERE s.published = 1'
				. ' ORDER BY s.title asc'
				;
		$db->setQuery( $query );
		$contentTypes	=	$db->loadObjectList();
		
		return $contentTypes;
	}
	
	/**
     * Get Content Types
     **/
	function getContentTypesByName( $action = 0 )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $action == -1 ) {
			$whereAction	=	'';
		} else {
			$whereAction	=	' AND cc.client = "admin" AND ccc.type = 25 AND ccc.bool2 = '.$action;
		}
		$query	= 'SELECT DISTINCT s.name AS value, s.title AS text'
				. ' FROM #__jseblod_cck_types AS s'
				. ' LEFT JOIN #__jseblod_cck_type_item AS cc ON cc.typeid = s.id'
				. ' LEFT JOIN #__jseblod_cck_items AS ccc ON ccc.id = cc.itemid'
				. ' WHERE s.published = 1'
				. $whereAction
				. ' ORDER BY s.title asc'
				;
		$db->setQuery( $query );
		$contentTypes	=	$db->loadObjectList();
		
		return $contentTypes;
	}
	
	/**
     * Get Search Types
     **/
	function getSearchTypes()
	{
		$db	=&	JFactory::getDBO();
		
		$query	= 'SELECT s.id AS value, s.title AS text'
				. ' FROM #__jseblod_cck_searchs AS s'
				. ' WHERE s.published = 1'
				. ' ORDER BY s.title asc'
				;
		$db->setQuery( $query );
		$searchTypes	=	$db->loadObjectList();
		
		return $searchTypes;
	}
	
	/**
     * Get Group ContentType
     **/
	function getGroupCTypes()
	{
		$db	=&	JFactory::getDBO();
		
		$query	= 'SELECT s.name AS value, s.title AS text'
				. ' FROM #__jseblod_cck_items AS s'
				. ' WHERE s.type = 20'
				. ' ORDER BY s.title asc'
				;
		$db->setQuery( $query );
		$group	=	$db->loadObjectList();
		
		return $group;
	}
	
	/**
	 * Get Wysiwyg Content
	 **/
	function getWysiwygContent( $field, $table, $itemId )
	{
		$db		=& JFactory::getDBO();
		
		if ( ! $itemId ) {
			return '';
		}
		$where	= ' WHERE s.id = '.$itemId;
		
  		$query 	= ' SELECT s.'.$field
				. ' FROM #__jseblod_cck_'.$table.' AS s'
				. $where
				;
    	$db->setQuery( $query );
  		$wysiwygContent	=	$db->loadResult();
		
		return $wysiwygContent;
	}
	
	/**
     * Get Template Position
     **/
	function getTemplatePositions( $template = '' )
	{
		$db	=&	JFactory::getDBO();
		
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.archive' );
		jimport( 'joomla.utilities.simplexml' );
		
		if ( ! $template ) {
			$query	= 'SELECT template '
					. ' FROM #__templates_menu'
					. ' WHERE client_id = 0 AND menuid = 0';
			$db->setQuery( $query );
			$template = $db->loadResult();
		}
		
		$positions	=	array();
		$path	=	JPATH_SITE.DS.'templates'.DS.$template;
		$xml	=&	JFactory::getXMLParser('Simple');
		if ( $xml->loadFile( $path.DS.'templateDetails.xml' ) )
		{
			$p	=&	$xml->document->getElementByPath( 'positions' );
			if ( is_a( $p, 'JSimpleXMLElement' ) && count( $p->children() ) )
			{
				foreach ( $p->children() as $child )
				{
					if ( ! in_array( $child->data(), $positions ) && $child->data() ) {
						$positions[]	=	$child->data();
					}
				}
			}
		}
		sort( $positions );
		
		return $positions;
	}
	
	/**
     * Get Template Locations
     **/
	function getTemplateLocations( $template = '' )
	{
		$db	=&	JFactory::getDBO();
		
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.archive' );
		jimport( 'joomla.utilities.simplexml' );
		
		if ( ! $template ) {
			$query	= 'SELECT template '
					. ' FROM #__templates_menu'
					. ' WHERE client_id = 0 AND menuid = 0';
			$db->setQuery( $query );
			$template = $db->loadResult();
		}
		
		$locations	=	array();
		$path	=	JPATH_SITE.DS.'templates'.DS.$template;
		$xml	=&	JFactory::getXMLParser('Simple');
		if ( $xml->loadFile( $path.DS.'templateDetails.xml' ) )
		{
			$p	=&	$xml->document->getElementByPath( 'locations' );
			if ( is_a( $p, 'JSimpleXMLElement' ) && count( $p->children() ) )
			{
				foreach ( $p->children() as $child )
				{
					if ( ! in_array( $child->data(), $locations ) && $child->data() ) {
						$locations[]	=	$child->data();
					}
				}
			}
		}
		sort( $locations );
		
		return $locations;
	}
	
	/**
     * Set Template Location/Position
     **/
	function setTemplateLocPos( $type, $elems, $template )
	{
		jimport( 'joomla.filesystem.file' );
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.archive' );
		jimport( 'joomla.utilities.simplexml' );
				
		$path	=	JPATH_SITE.DS.'templates'.DS.$template;
		$xml	=&	JFactory::getXMLParser( 'Simple' );
		if ( $xml->loadFile( $path.DS.'templateDetails.xml' ) )
		{
			$p	=&	$xml->document->getElementByPath( 'locations' );
			if ( is_a( $p, 'JSimpleXMLElement' ) )
			{
				$xml->document->removeChild( $p );
			}
			$xml->document->addChild( 'locations' );
			$p	=&	$xml->document->getElementByPath( 'locations' );
			if ( sizeof( $elems ) ) {
				sort( $elems );
				foreach( $elems as $elem ) {
					$row	=	&$p->addChild( 'location' );
					$row->setData( $elem );
				}
			}
		}

		$name		=	( $xml->document->name() ) ? $xml->document->name() : 'install';
		$root		=&	$xml->document;
		$attrib		=	'';
		foreach ( $root->attributes() as $key => $val ) {
			$attrib	.=	' '.$key.'="'.$val.'"';
		}
		$buffer		=	'<?xml version="1.0" encoding="utf-8"?>'."\n";
		$buffer		.=	'<'.$name.$attrib.'>'."\n";
		foreach ( $root->children() as $child ) {
			if ( @$child->children() ) {
				$buffer	.=	"\n".$child->toString();
			} else {
				$buffer	.=	$child->toString();
			}
		}
		$buffer	=	str_replace( array( 'creationdate>', 'authoremail>', 'authorurl>' ), array( 'creationDate>', 'authorEmail>', 'authorUrl>' ), $buffer );
		$buffer	.=	"\n\n".'</'.$name.'>';
		JFile::write( $path.DS.'templateDetails.xml', $buffer );
		
		return true;
	}
	
	/**
     * Get Plugin Buttons
     **/
	function getPluginsButton()
	{
		$db	=&	JFactory::getDBO();
		
		$query	= 'SELECT name AS text, element AS value'
				. ' FROM #__plugins'
				. ' WHERE folder = "editors-xtd" AND element != "cckjseblod" AND element != "pagebreak" AND element != "readmore" ';
		$db->setQuery( $query );
		$plugins = $db->loadObjectList();
		
		return $plugins;
	}

	function getPluginsButtonName()
	{
		$db	=&	JFactory::getDBO();
		
		$query	= 'SELECT element'
				. ' FROM #__plugins'
				. ' WHERE folder = "editors-xtd"';
		$db->setQuery( $query );
		$plugins = $db->loadResultArray();
		
		return $plugins;
	}


	/**
	 * Get Joomla Authors 
	 **/
	function getJoomlaAuthors( $group = null )
	{
		$db	=&	JFactory::getDBO();
		
		$where	=	'';
		if ( $group ) {
			$where	=	' WHERE gid >= 24';
		}
		
		$query	= 'SELECT name AS text, s.id AS value'
				. ' FROM #__users AS s'
				. $where
				. ' ORDER BY name asc '
				;
		$db->setQuery( $query );
		$joomlaAuthors	=	$db->loadObjectList();
		
		return $joomlaAuthors;
	}
	
	/**
	 * Get Joomla Categories
	 **/
	function getJoomlaCategories()
	{
		$db	=&	JFactory::getDBO();
		
		$where	= ' WHERE s.section NOT LIKE "%com_%" ';
		
		$query	= 'SELECT (CONCAT( s.title,(CONCAT(" ( ", (CONCAT(cc.title, " )")) )) )) AS text, s.id AS value'
				. ' FROM #__categories AS s'
				. ' LEFT JOIN #__sections AS cc ON cc.id = s.section '	
				. $where
				. ' ORDER BY s.title asc ;' ;
		$db->setQuery( $query );
		$joomlaCategories	=	$db->loadObjectList();
		
		return $joomlaCategories;
	}
	
	/**
	 * Get Joomla Editors
	 **/
	function getJoomlaEditors()
	{
		$db	=&	JFactory::getDBO();
		
		$where	= ' WHERE s.folder LIKE "editors" AND s.published AND s.element != "cckjseblod"';
		
		$query	= 'SELECT s.name AS text, s.element AS value'
				. ' FROM #__plugins AS s'
				. $where
				. ' ORDER BY s.name asc'
				;
		$db->setQuery( $query );
		$joomlaEditors	=	$db->loadObjectList();
		
		return $joomlaEditors;
	}
	
	/**
	 * Get Joomla Articles
	 **/
	function getJoomlaArticles( $catIds, $states, $userOnly, $indexedkey = '' )
	{
		$db		=&	JFactory::getDBO();
		$user	=&	JFactory::getUser();
		
		if ( strpos( $states, ',' ) !== false ) {
			$where	=	' WHERE s.state IN ('.$states.')';
		} else {
			$where	=	' WHERE s.state = '.$states;
		}
		
		if ( $catIds != '' ) {
			if ( strpos( $catIds, ',' ) !== false ) {
				$whereCheck	=	' WHERE s.id IN ('.$catIds.')';
				$query 	= 'SELECT COUNT( s.id )'
						. ' FROM #__categories AS s'
						. $whereCheck
						;
				$db->setQuery( $query );
				$joomlaCat	=	$db->loadResult();
				if ( $joomlaCat ) {
					$where	.=	' AND s.catid IN ('.$catIds.')';
				}
			} else {
				$whereCheck	=	' WHERE s.id = '.$catIds;
				$query 	= 'SELECT COUNT( s.id )'
						. ' FROM #__categories AS s'
						. $whereCheck
						;
				$db->setQuery( $query );
				$joomlaCat	=	$db->loadResult();
				if ( $joomlaCat ) {
					$where	.=	' AND s.catid = '.$catIds;
				}
			}
		}
		
		$where2	=	( $userOnly ) ? ' AND s.created_by='.(int)$user->id : '';
		
		if ( $indexedkey ) {
			$key	=	'cc.keyid';
			$join	=	' LEFT JOIN #__jseblod_cck_extra_index_key_'.$indexedkey.' AS cc ON cc.id = s.id';
		} else {
			$key	=	's.id';
			$join	=	'';
		}
		
		$query 	= 'SELECT s.title AS text, '.$key.' AS value'
				. ' FROM #__content AS s'
				. $join
				. $where
				. $where2
				. ' ORDER BY s.title asc ;' ;
		$db->setQuery( $query );
		$joomlaArticles	=	$db->loadObjectList();
		
		return $joomlaArticles;
	}

	/**
	 * Get Joomla Articles
	 **/
	function getNumItems( $location, $actionMode, $user = null )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $actionMode == 1 ) {
			$whereU	= ( $user ) ? ' AND s.created_user_id ='.(int)$user : '';
			
			$query 	= 'SELECT COUNT(s.id)'
					. ' FROM #__categories AS s'
					. ' WHERE s.parent_id ='.(int)$location
					. $whereU
					;
		} else {
			$whereU	= ( $user ) ? ' AND s.created_by ='.(int)$user : '';
			
			$query 	= 'SELECT COUNT(s.id)'
					. ' FROM #__content AS s'
					. ' WHERE s.catid ='.(int)$location
					. $whereU
					;
		}
		$db->setQuery( $query );
		$num	=	$db->loadResult();
		
		return $num;
	}

	/**
	 * Get Next Auto Increment
	 **/
	function getNextAutoIncrement( $table )
	{
		global	$mainframe;
		$db		=&	JFactory::getDBO();

		$dbname	=	$mainframe->getCfg('db');
		$dbpref	=	$mainframe->getCfg('dbprefix');
		$query	=	'SELECT Auto_increment FROM information_schema.tables WHERE table_schema="'.$dbname.'" AND table_name="'.$dbpref.$table.'"';
		$db->setQuery( $query );
		$next	=	$db->loadResult();

		return $next;
	}

	/**
	* Generates an HTML radio list
	*
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @returns string HTML for the select list
	*/
	function radiolist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = null, $idtag = false, $orientation = false, $cols = 0, $table_style = 0, $translate = false )
	{
		reset( $arr );
		$html = '';

		if (is_array($attribs)) {
			$attribs = JArrayHelper::toString($attribs);
		 }

		$id_text = $name;
		if ( $idtag ) {
			$id_text = $idtag;
		}

		for ($i=0, $n=count( $arr ); $i < $n; $i++ )
		{
			$k	= $arr[$i]->$key;
			$t	= $translate ? JText::_( $arr[$i]->$text ) : $arr[$i]->$text;
			$id	= ( isset($arr[$i]->id) ? @$arr[$i]->id : null);

			$extra	= '';
			$extra	.= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array( $selected ))
			{
				foreach ($selected as $val)
				{
					$k2 = is_object( $val ) ? $val->$key : $val;
					if ($k == $k2)
					{
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ((string)$k == (string)$selected ? " checked=\"checked\"" : '');
			}
			// Images!!
			$count	=	strlen( $t );
			if ( $t[0] == '#' && $t[$count-1] == '#' ) {
				$t	=	substr( $t, 1, $count-2 );
				$t	=	'<img src="'.JURI::root().$t.'" alt="cancel" />';
			}
			// !!
			if ( $table_style == 1 ) {
				$html		.=	'<td align="left">';
				$html		.=	"\n\t<input type=\"radio\" name=\"$name\" id=\"$id_text$k\" value=\"".$k."\"$extra $attribs />";
				$html		.=	"\n\t<label for=\"$id_text$k\">$t</label>";
				$html		.=	'</td>';
				if ( $orientation == true && ( ! $cols || ( $cols && ($i+1)%$cols == 0 ) ) && ( $i < $n-1 ) ) {
					$html	.=	'</tr><tr>';
				}
			} else {
				$html		.=	"\n\t<input type=\"radio\" name=\"$name\" id=\"$id_text$k\" value=\"".$k."\"$extra $attribs />";
				$html		.=	"\n\t<label for=\"$id_text$k\">$t</label>";
				if ( $orientation == true && ( ! $cols || ( $cols && ($i+1)%$cols == 0 ) ) && $i < $n-1 ) {
					$html	.=	"<br />";
				}
			}
		}
		if ( $table_style == 1 ) {
			$html = '<table cellpadding="0" cellspacing="0" border="0"><tr>'.$html.'</tr></table>';
		}
		$html .= "\n";
		
		return $html;
	}
	
	/**
	 * Generates a HTML check box or boxes
     * @param array An array of objects
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param mixed The key that is selected. Can be array of keys or just one key
     * @param string The name of the object variable for the option value
     * @param string The name of the object variable for the option text
     * @returns string HTML for the select list
     **/
	function checkBoxList( $arr, $tag_name, $tag_attribs, $key = 'value', $text = 'text', $selected = null, $idtag = false, $orientation = false, $cols = 0, $table_style = 0 )
	{
		reset( $arr );
		$html	=	"";
		for ( $i = 0, $n = count( $arr ); $i < $n; $i++ ) {
			$k	=	$arr[$i]->$key;
			$t	=	$arr[$i]->$text;
			$id	=	@$arr[$i]->id;
			
			$id_text = $tag_name;
		  if ( $idtag ) {
			 $id_text = $idtag;
		  }
			
			$extra	=	'';
			$extra	.=	$id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if ( is_array( $selected ) ) {
				foreach ( $selected as $obj ) {
					$k2	=	$obj;
					if ( $k == $k2 ) {
						$extra	.=	" checked ";
						break;
					}
				}
			} else {
				$extra	.=	($k == $selected ? " checked " : '');
			}
			// Images!!
			$count	=	strlen( $t );
			if ( $t[0] == '#' && $t[$count-1] == '#' ) {
				$t	=	substr( $t, 1, $count-2 );
				$t	=	'<img src="'.JURI::root().$t.'" alt="cancel" />';
			}
			// !!
			if ( $table_style == 1 ) {
				$html		.=	'<td align="left">';
				$html		.=	"\n\t<input type=\"checkbox\" name=\"$tag_name\" id=\"$id_text$k\" value=\"".$k."\"$extra $tag_attribs />";
				$html		.=	"\n\t<label for=\"$id_text$k\">$t</label>";
				$html		.=	'</td>';
				if ( $orientation == true && ( ! $cols || ( $cols && ($i+1)%$cols == 0 ) ) && ( $i < $n-1 ) ) {
					$html	.=	'</tr><tr>';
				}
			} else {
				$html		.=	"\n\t<input type=\"checkbox\" name=\"$tag_name\" id=\"$id_text$k\" value=\"".$k."\"$extra $tag_attribs />";
				$html		.=	"\n\t<label for=\"$id_text$k\">$t</label>";
				if ( $orientation == true && ( ! $cols || ( $cols && ($i+1)%$cols == 0 ) ) && $i < $n-1 ) {
					$html	.=	"<br />";
				}
			}
		}
		if ( $table_style == 1 ) {
			$html = '<table cellpadding="0" cellspacing="0" border="0"><tr>'.$html.'</tr></table>';
		}
		$html	.=	"\n";
		
		return $html;
	}
	
	function stringURLSafe( $string )
	{
		//remove any '-' from the string they will be used as concatonater
		$str = str_replace('_', ' ', $string);

		$lang =& JFactory::getLanguage();
		$str = $lang->transliterate($str);

		// remove any duplicate whitespace, and ensure all characters are alphanumeric
		$str = preg_replace(array('/\s+/','/[^A-Za-z0-9_]/'), array('_',''), $str);

		// lowercase and trim
		$str = trim(strtolower($str));
		return $str;
	}
		
	/**
	 * Clean Template Folders
	 **/
	function clean( $name = null )
	{
		$path	=	( $name ) ? JPATH_SITE.DS.'templates'.DS.$name : JPATH_SITE.DS.'templates';
		$files	=	JFolder::files( $path, 'index_jseblod', true, true );
		if ( sizeof( $files ) ) {
			foreach ( $files as $file ) {
				if ( JFile::exists( $file ) ) {
					JFile::delete( $file );
				}
			}
		}
		return true;
	}
	
	function getCCKUser( $select, $where, $id )
	{
		$db	=&	JFactory::getDBO();
		
		$query	=	'SELECT '.$select.' FROM #__jseblod_cck_users WHERE registration=1 AND '.$where.' ='.$id;
		
		$db->setQuery( $query );
		$result	=	$db->loadResult();
		
		return $result;
	}
	
	/**
	 * Absolute Paths
	 **/
	function absolutePaths( $data )
	{
		$root		=	JURI::root();
		$root_mini	=	JURI::root( true );
		$search		=	'#(href|src)="(.*)"#sU';
		
		preg_match_all( $search, $data, $matches );
		if ( sizeof( $matches[2] ) ) {
			$i		=	0;
			foreach( $matches[2] as $match ) {
				if ( strpos( $match, 'http' ) === false && strpos( $match, 'mailto' ) === false ) {
					if ( strpos( $match, '../' ) !== false ) {
						$match	=	str_replace( '../', '', $match );
					}
					if ( strpos( $match, 'administrator/' ) !== false ) {
						$match	=	str_replace( 'administrator/', '', $match );
					}
					if ( strpos( $match, $root ) !== false ) {
					} else {
						if ( strpos( $match, $root_mini ) !== false ) {
							$match	=	str_replace( $root_mini, '', $match );
						}
						if ( $match[0] == '/' ) {
							$match	=	substr( $match, 1 );
						}
						$match	=	JURI::root().$match;
					}
					$data	=	str_replace( $matches[0][$i], $matches[1][$i].'="'.$match.'"', $data );
				}
				$i++;
			}
		}
		
		return $data;
	}
	
	/**
     * Get Option Text
     **/
	function getOptionText( $value, $options )
	{
		$opts	=	explode( '||', $options );
		$text	=	'';
		
		if ( $value ) {
			if ( sizeof( $opts ) ) {
				foreach ( $opts as $opt ) {
					if ( strpos( $opt.'||', $value.'||' ) !== false ) {
						$text	=	explode( '=', $opt );
						$text	=	$text[0];
						break;
					}
				}
			}
		}
		
		return ( $text );
	}
}
?>