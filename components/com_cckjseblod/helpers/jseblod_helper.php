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
	 * Get Wysiwyg Content
	 **/
	function getWysiwygContent( $field, $table, $itemId )
	{
		$db		=& JFactory::getDBO();
		
		$where	= ' WHERE s.id = '.$itemId;
		
  		$query 	= ' SELECT s.'.$field
				. ' FROM #__jseblod_cck_'.$table.' AS s'
				. $where
				;
    	$db->setQuery( $query );
  		$wysiwygContent	=	$db->loadResult();
		
		return $wysiwygContent;
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
	 * Get Joomla Content
	 **/
	function getJoomlaContentInfos( $id, $actionMode )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $id ) {
			if ( $actionMode == 1 ) {
				$query 	= 'SELECT s.id, s.description, s.published, s.section'
						. ' FROM #__categories AS s'
						. ' WHERE s.id = '.$id;
						;
			} else {
				$query 	= 'SELECT s.id, s.introtext, s.fulltext, s.state, s.catid, s.created_by'
						. ' FROM #__content AS s'
						. ' WHERE s.id = '.$id;
						;
			}
			$db->setQuery( $query );
			$content	=	$db->loadObject();
		}
		
		return $content;
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
     * Get Plugin Content
     **/
	function getPluginsContent()
	{
		$db	=&	JFactory::getDBO();
		
		$query	= 'SELECT element'
				. ' FROM #__plugins'
				. ' WHERE folder = "content" AND element != "cckjseblod"';
		$db->setQuery( $query );
		$plugins = $db->loadResultArray();
		
		return $plugins;
	}
	
	/**
     * Download Hits
     **/
	function downloadHits( $id, $item, $group, $gx )
	{
		$db	=&	JFactory::getDBO();
		
		$query	= 'SELECT s.hits'
				. ' FROM #__jseblod_cck_downloads AS s'
				. ' WHERE s.item = "'.$item.'" AND s.groupname = "'.$group.'" AND s.gx = '.$gx.' AND s.contentid = '.$id
				;
		$db->setQuery( $query );
		$hits	=	$db->loadResult();

		if ( ! $hits ) {
			$query	= 'INSERT INTO #__jseblod_cck_downloads ( `contentid`, `item`, `groupname`, `gx`, `hits` )'
					. ' VALUES ( '.$id.', "'.$item.'", "'.$group.'", '.$gx.', 1 )';
			$db->setQuery( $query );
			if ( ! $db->query() ) {
				return false;
			}
		} else {
			$hits++;
			$query	= 'UPDATE #__jseblod_cck_downloads AS s'
					. ' SET s.hits = '.(int)$hits
					. ' WHERE s.item = "'.$item.'" AND s.groupname = "'.$group.'" AND s.gx = '.$gx.' AND s.contentid = '.$id;
			$db->setQuery( $query );
			if ( ! $db->query() ) {
				return false;
			}
		}
		
		return true;
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

}
?>