<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			Core - jSeblod CCK ( Content Construction Kit )
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Core Class
 **/
class CCK
{
	public static $_config	=	null;
	public static $_user	=	null;
		
	// ################
	// ##   ARTICLE  ##
	// ################
	
	// ARTICLE_getRow
	function ARTICLE_getRow( $id )
	{
		$row	=	'';
		
		if ( $id ) {
			JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
			$row	=&	JTable::getInstance( 'content' );
			$row->load( $id );
		}
		
		return $row;
	}
	
	// ARTICLE_getRow_Value
	function ARTICLE_getRow_Value( $id, $fieldname )
	{
		$res	=	'';
		
		$row	=	CCK::ARTICLE_getRow( $id );
		
		if ( ! $row ) {
			return false;
		}
		
		$res	=	@$row->$fieldname;
		
		return $res;
	}
	
	// ARTICLE_getText
	function ARTICLE_getText( $id )
	{
		$obj	=	CCK::DB_loadObject( 'SELECT s.id, s.introtext, s.fulltext FROM #__content AS s WHERE s.id='.$id );
		$res	=	$obj->introtext.$obj->fulltext;
		
		return $res;
	}
	
	// ARTICLE_getValue
	function ARTICLE_getValue( $id, $fieldname )
	{
		$text	=	CCK::ARTICLE_getText( $id );
		
		$res	=	CCK::CONTENT_getValue( $text, $fieldname );
		
		return $res;
	}
	
	// ARTICLE_getValues
	function ARTICLE_getValues( $id, $fieldnames = '' )
	{		
		$text	=	CCK::ARTICLE_getText( $id );
		
		$res	=	CCK::CONTENT_getValues( $text, $fieldnames );
		
		return $res;
	}
	
	// ARTICLE_setRow_Value
	function ARTICLE_setRow_Value( $id, $fieldname,	$value )
	{
		$row	=	CCK::ARTICLE_getRow( $id );
		
		if ( ! $row ) {
			return false;
		}
		
		$row->$fieldname	=	$value;
		
		if ( ! $row->store() ) {
			return false;
		}
		
		return true;
	}
	
	// ARTICLE_setValue
	function ARTICLE_setValue( $id, $fieldname, $value, $old_value = '' )
	{
		$row	=	CCK::ARTICLE_getRow( $id );
		
		if ( ! $row ) {
			return false;
		}
		
		$row->introtext	=	CCK::CONTENT_setValue( $row->introtext, $fieldname, $value, $old_value );
		$row->fulltext	=	CCK::CONTENT_setValue( $row->fulltext, $fieldname, $value, $old_value );
	
		if ( ! $row->store() ) {
			return false;
		}
		
		return true;
	}
	
	// ARTICLE_setValues
	function ARTICLE_setValues( $id, $fieldnames, $values, $old_values = '' )
	{
		$row	=	CCK::ARTICLE_getRow( $id );
		
		if ( ! $row ) {
			return false;
		}
		
		$row->introtext	=	CCK::CONTENT_setValues( $row->introtext, $fieldnames, $values, $old_values );
		$row->fulltext	=	CCK::CONTENT_setValues( $row->fulltext, $fieldnames, $values, $old_values );
	
		if ( ! $row->store() ) {
			return false;
		}
		
		return true;
	}
	
	// ################################################################
	// ################################################################
	
	// ################
	// ##  CATEGORY  ##
	// ################

	// CATEGORY_getRow
	function CATEGORY_getRow( $id )
	{
		$row	=	'';
		
		if ( $id ) {
			JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
			$row	=&	JTable::getInstance( 'category' );
			$row->load( $id );
		}
		
		return $row;
	}

	// ################################################################
	// ################################################################

	// ################
	// ##   CONTENT  ##
	// ################
	
	// CONTENT_getRegex
	function CONTENT_getRegex()
	{
		$res	=	'#::(.*?)::(.*?)::/(.*?)::#s';
		
		return $res;
	}
	
	// CONTENT_getRegex_Field
	function CONTENT_getRegex_Field( $fieldname )
	{
		$res	=	'#::'.$fieldname.'::(.*?)::/'.$fieldname.'::#s';
		
		return $res;
	}
	
	// CONTENT_getRegex_Group
	function CONTENT_getRegex_Group( $fieldname, $groupname, $gx = '(.*?)' )
	{
		$res	=	'#::'.$fieldname.'\|'.$gx.'\|'.$groupname.'::(.*?)::/'.$fieldname.'\|'.$gx.'\|'.$groupname.'::#s';
		
		return $res;
	}
	
	// CONTENT_getSyntax
	function CONTENT_getSyntax( $fieldname, $value )
	{
		$res	=	'::'.$fieldname.'::'.$value.'::/'.$fieldname.'::';
		
		return $res;
	}
	
	// CONTENT_getSyntax_Group
	function CONTENT_getSyntax_Group( $fieldname, $groupname, $value, $gx = '(.*?)' )
	{
		$res	=	'#::'.$fieldname.'\|'.$gx.'\|'.$groupname.'::'.$value.'::/'.$fieldname.'\|'.$gx.'\|'.$groupname.'::#s';
		
		return $res;
	}
	
	// CONTENT_getText
	function CONTENT_getText( $text )
	{
		$regex	=	CCK::CONTENT_getRegex();
		preg_match_all( $regex, $text, $res );
		
		return $res;
	}
	
	// CONTENT_getValue
	function CONTENT_getValue( $text, $fieldname )
	{
		$res	=	'';
		
		$regex	=	CCK::CONTENT_getRegex_Field( $fieldname );
		preg_match( $regex, $text, $matches );
		
		if ( sizeof( $matches ) ) {
			$res	=	$matches[1];
		}
		
		return $res;
	}
	
	// CONTENT_getValues
	function CONTENT_getValues( $text, $fieldnames = '' )
	{
		$res	=	array();
		
		//TODO:: if $fieldnames
		
		$regex	=	CCK::CONTENT_getRegex();
		preg_match_all( $regex, $text, $matches );
		
		if ( sizeof( $matches[1] ) ) {
			foreach ( $matches[1] as $key => $val ) {
				$res[$val]	=	$matches[2][$key];
			}
		}
		
		return $res;
	}
	
	// CONTENT_setValue
	function CONTENT_setValue( $text, $fieldname, $value, $old_value = '' )
	{	
		$res	=	$text;
		$search	=	'';
		
		if ( $old_value ) {
			$search	=	CCK::CONTENT_getSyntax( $fieldname, $old_value );
		} else {
			$regex	=	CCK::CONTENT_getRegex_Field( $fieldname );
			preg_match( $regex, $text, $matches );
			if ( sizeof( $matches ) ) {
				$search	=	$matches[0];
			}
		}
		if ( $search ) {
			$replace	=	CCK::CONTENT_getSyntax( $fieldname, $value );
			if ( strpos( $text, $search ) !== false ) {
				$res	=	str_replace( $search, $replace, $text );
			}
		}
		
		return $res;
	}

	// CONTENT_setValues
	function CONTENT_setValues( $text, $fieldnames, $values, $old_values = '' )
	{	
		$res			=	$text;
		$n_fieldnames	=	sizeof( $fieldnames );
		$n_values		=	sizeof( $values );
		$n_old_values	=	sizeof( $old_values );
		
		if ( is_array( $old_values ) ) {
			if ( ( $n_fieldnames == $n_values ) && ( $n_fieldnames == $n_old_values ) ) {
				for ( $i = 0 ; $i < $n_fieldnames; $i++ ) {
					$res	=	CCK::CONTENT_setValue( $res, $fieldnames[$i], $values[$i], $old_values[$i] );
				}
			}
		} else {
			if ( $n_fieldnames == $n_values ) {
				for ( $i = 0; $i < $n_fieldnames; $i++ ) {
					$res	=	CCK::CONTENT_setValue( $res, $fieldnames[$i], $values[$i], $old_values );
				}
			}
		}		
		
		return $res;
	}

	// ################################################################
	// ################################################################
	
	// ################
	// ##    CORE    ##
	// ################

	// CORE_getConfig
	function CORE_getConfig()
	{		
		if ( CCK::$_config ) {
			return CCK::$_config;
		}
	
		$config	=	CCK::DB_loadObject( 'SELECT * FROM #__jseblod_cck_configuration' );
		
		CCK::$_config	=&	$config;
		
		return $config;
	}
	
	// CORE_getConfig_Value
	function CORE_getConfig_Value( $fieldname )
	{		
		if ( ! CCK::$_config ) {
			$config			=	CCK::DB_loadObject( 'SELECT * FROM #__jseblod_cck_configuration' );
			CCK::$_config	=&	$config;
		}
		
		return CCK::$_config->$fieldname;
	}
	
	// ################################################################
	// ################################################################
	
	// ################
	// ##     DB     ##
	// ################

	// DB_delete
	function DB_delete( $query )
	{
		$db		=&	JFactory::getDBO();
		
		$db->setQuery( $query );
		if ( ! $db->query() ) {
			return false;
		}
		
		return true;
	}
	
	// DB_loadResult
	function DB_loadResult( $query )
	{
		$db		=&	JFactory::getDBO();
	
		$db->setQuery( $query );
		$res	=	$db->loadResult();
		
		return $res;
	}
	
	// DB_loadResultArray
	function DB_loadResultArray( $query )
	{
		$db		=&	JFactory::getDBO();
	
		$db->setQuery( $query );
		$res	=	$db->loadResultArray();
		
		return $res;
	}
	
	// DB_loadObject
	function DB_loadObject( $query )
	{
		$db		=&	JFactory::getDBO();
	
		$db->setQuery( $query );
		$res	=	$db->loadObject();
		
		return $res;
	}
	
	// DB_loadObjectList
	function DB_loadObjectList( $query, $key = null )
	{
		$db		=&	JFactory::getDBO();
	
		$db->setQuery( $query );
		$res	=	$db->loadObjectList( $key );
		
		return $res;
	}

	// ################################################################
	// ################################################################

	// ################
	// ##   FIELD    ##
	// ################
	
	// FIELD_cleanExtended
	function FIELD_cleanExtended( $fieldname )
	{
		if ( ( $cut = strpos( $fieldname, '[' ) ) !== false ) {
			$res	=	substr( $fieldname, $cut + 1, -1 );
		} else if ( ( $cut = strpos( $fieldname, '(' ) ) !== false ) {
			$res	=	substr( $fieldname, $cut + 1, -1 );
		} else {
			$res	=	$fieldname;
		}
	
		return $res;
	}

	// FIELD_getAttribute
	function FIELD_getAttribute( $fieldname, $attribute )
	{
		if ( ! $fieldname || ! $attribute ) {
			return false;
		}
		$res	=	CCK::DB_loadResult( 'SELECT s.'.$attribute.' FROM #__jseblod_cck_items AS s'
									   .' WHERE s.name="'.$fieldname.'"' );
		
		return $res;
	}

	// FIELD_getObject
	function FIELD_getObject( $fieldname, $attribute = '' )
	{
		if ( ! $fieldname ) {
			return false;
		}
		if ( $attribute ) {
			if ( is_array( $attribute ) ) {
				$req	=	'';
				foreach ( $attribute as $attrib ) {
					if ( $attrib ) {
						$req	.=	's.'.$attrib.',';
					}
				}
				if ( $req ) {
					$req	=	substr( $req, 0, -1 );
				}
			} else {
				$req	=	's.'.$attribute;
			}
			$join	=	'';
		} else {
			$req	=	'cc.name AS typename, s.*';
			$join	=	' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type';
		}
		$res	=	CCK::DB_loadObject( 'SELECT '.$req.' FROM #__jseblod_cck_items AS s'
									   . $join
									   . ' WHERE s.name="'.$fieldname.'"' );
		
		return $res;
	}
	
	// FIELD_getOption_Text
	function FIELD_getOption_Text( $value, $options, $multiple = 0, $separator = '' ) {
		//TODO:: FIELD_getOption_Text
	}

	// ################################################################
	// ################################################################

	// ################
	// ##    KEY     ##
	// ################
	
	// KEY_getId
	function KEY_getId( $indexed_key, $key )
	{
		$db	=&	JFactory::getDBO();
			
		$query	= 'SELECT s.id'
				. ' FROM #__jseblod_cck_extra_index_key_'.$indexed_key.' AS s'
				. ' WHERE s.keyid="'.$key.'"';
				;
		$db->setQuery( $query );
		$res	=	$db->loadResult();
		
		return $res;
	}
	
	// KEY_getKey
	function KEY_getKey( $indexed_key, $id )
	{
		$db	=&	JFactory::getDBO();
			
		$query	= 'SELECT s.keyid'
				. ' FROM #__jseblod_cck_extra_index_key_'.$indexed_key.' AS s'
				. ' WHERE s.id="'.$id.'"';
				;
		$db->setQuery( $query );
		$res	=	$db->loadResult();
		
		return $res;
	}
	
	// KEY_getMap
	function KEY_getMap( $indexed_key = '' )
	{
		$db		=&	JFactory::getDBO();
		$res	=	array();
		
		if ( $indexed_key ) {
			$indexed_keys[]	=	$indexed_key;
		} else {
			$indexed_keys	=	CCK::DB_loadResultArray( 'SELECT s.name FROM #__jseblod_cck_items AS s WHERE s.indexedkey = 1' );
		}
		
		foreach ( $indexed_keys as $indexed_key ) {
			$query	= 'SELECT s.id, s.keyid'
					. ' FROM #__jseblod_cck_extra_index_key_'.$indexed_key.' AS s'
					;
			$db->setQuery( $query );
			$res[$indexed_key]	=	$db->loadObjectList( 'keyid' );
		}
		
		return $res;
	}
	
	// INDEX_deleteIndexed
	function INDEX_deleteIndexed( $indexed, $ids )
	{
		$db	=&	JFactory::getDBO();
			
		$query	= 'DELETE s.*'
				. ' FROM #__jseblod_cck_extra_index_'.$indexed.' AS s'
				. ' WHERE s.id IN ( '.$ids.' )';
				;
		$db->setQuery( $query );
		if ( ! $db->query() ) {
			return false;
		}
				
		return true;
	}
	
	// INDEX_deleteIndexed_Key
	function INDEX_deleteIndexed_Key( $indexed_key, $ids )
	{
		$db	=&	JFactory::getDBO();
			
		$query	= 'DELETE s.*'
				. ' FROM #__jseblod_cck_extra_index_key_'.$indexed_key.' AS s'
				. ' WHERE s.id IN ( '.$ids.' )';
				;
		$db->setQuery( $query );
		if ( ! $db->query() ) {
			return false;
		}
				
		return true;
	}

	// ################################################################
	// ################################################################

	// ################
	// ##    LANG    ##
	// ################

	// ################################################################
	// ################################################################
	
	// ################
	// ##    USER    ##
	// ################
		
	// USER_getIP
	function USER_getIP()
	{
		$res	=	getenv( 'REMOTE_ADDR' ); //$_SERVER["REMOTE_ADDR"]
	
		return $res;
	}
	
	// USER_getProfile
	function USER_getProfile( $userid )
	{
		$res	=	CCK::DB_loadResult( 'SELECT CONCAT(cc.introtext,cc.fulltext) FROM #__jseblod_cck_users as s'
									.	' LEFT JOIN #__content as cc ON cc.id = s.contentid WHERE s.registration=1 AND s.userid='.$userid );
		
		return $res;
	}
	
	// USER_getProfileId
	function USER_getProfileId( $userid )
	{
		$res	=	CCK::DB_loadResult( 'SELECT contentid FROM #__jseblod_cck_users WHERE registration=1 AND userid='.$userid );
	
		return $res;
	}
	
	// USER_getSession
	function USER_getSession()
	{
		$session	=&	JFactory::getSession();
		$res		=	$session->getId();
	
		return $res;
	}
	
	// USER_getUser
	function &USER_getUser( $userid = 0, $profile = true )
	{
		if ( CCK::$_user && ! $userid ) {
			return CCK::$_user;
		}
			
		if ( ! $userid ) {
			$user	=&	JFactory::getUser();
		} else {
			$user	=&	JFactory::getUser( $userid );
		}
		
		// IP & Host
		$user->ip	=	CCK::USER_getIP();
		//$user->host	=	( $user->ip  ) ? gethostbyaddr( $user->ip ) : '';
		
		// Session
		if ( $user->id ) {
			$user->session_id	=	null;
			$user->where_clause	=	'userid='.$user->id;
		} else {
			$user->session_id	=	CCK::USER_getSession();
			$user->where_clause	=	'session_id="'.$user->session_id.'"';
		}
		
		// Profile
		if ( $user->id && $profile ) {			
			$text	=	CCK::USER_getProfile( $user->id );
			
			if ( $profile ) {
				$regex	=	CCK::CONTENT_getRegex();
				preg_match_all( $regex, $text, $matches );
				if ( sizeof( $matches[1] ) ) {
					foreach ( $matches[1] as $key => $val ) {
						$user->$val	=	$matches[2][$key];
					}
				}
			}
		}
		if ( ! $userid && $profile ) {
			CCK::$_user	=&	$user;
		}
		
		return $user;
	}
	
	// USER_setValue
	function USER_setValue( $id, $fieldname, $value, $old_value = '' )
	{
		$profileId	=	CCK::USER_getProfileId( $id );
		
		if ( ! CCK::ARTICLE_setValue( $profileId, $fieldname, $value, $old_value ) ) {
			return false;
		}
		
		return true;
	}
	
	// USER_setValues
	function USER_setValues( $id, $fieldnames, $values, $old_values = '' )
	{
		$profileId	=	CCK::USER_getProfileId( $id );
		
		if ( ! ARTICLE_setValues( $profileId, $fieldnames, $values, $old_values ) ) {
			return false;
		}
		
		return true;
	}
}
?>