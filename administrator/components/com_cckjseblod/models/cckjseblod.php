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

jimport( 'joomla.application.component.model' );

/**
 * CCKjSeblod		Model Class
 **/
class CCKjSeblodModelCCKjSeblod extends JModel
{
	/**
	 * Vars
	 **/
	var $_data	= null;
	
	/**
	 * Data Import CSV
	 **/
	function dataImportCSV( $actionMode )
	{
		// Get Var
		$default				=	JRequest::getVar( 'import_csv',  0, '', 'array' );
		$default['action_mode']	=	$actionMode;
		if ( $default['action_mode'] == 1 ) {
			$default['action_text']		=	'category';
			$default['action_jf_text']	=	'categories';
		} else {
			$default['action_text']		=	'content'; //Users!
			$default['action_jf_text']	=	'content'; //Users!
		}
		
		// Content Type
		if ( ! $default['content_type'] ) {
			$cType						=	$this->addContentType( JRequest::getVar( 'new_type' ) );
			$default['content_type']	=	@$cType->name;
			$default['content_type_id']	=	@$cType->id;
		}
		
		// #
		// # 1
		// #
		
		// Get File
		$dest	=	$this->_uploadFile();
		
		// Get Data
		$row 		=	0;
		$content	=	array();
		if ( ( $handle = fopen( $dest, "r" ) ) !== FALSE ) {
			while ( ( $data = fgetcsv( $handle, 1000, $default['separator'] ) ) !== FALSE ) {
				if ( $row == 0 ) {
					$fieldnames	=	$data;
				} else {
					$content[]	=	$data;
				}
				$row++;
			}
			fclose( $handle );
		}
		if ( $fieldnames[0] != '' ) {
			$fieldnames[0]	=	preg_replace( '/[^A-Za-z0-9_#\(\)\|]/', '', $fieldnames[0] );
		}
		
		// Get Fields & Groups
		$names		=	'';
		$subnames	=	array();
		if ( sizeof( $fieldnames ) ) {
			$f	=	0;
			foreach ( $fieldnames as $name ) {
				$name	=	preg_replace( '/[^A-Za-z0-9_#\(\)\|]/', '_', $name );
				if ( strpos( $name, '(' ) !== false ) {
					$temp				=	explode( '(', $name );
					$name				=	$temp[0];
					$name				=	strtolower( $name );
					$fieldnames[$f]		=	$name;
					$subnames[$name]	=	substr( $temp[1], 0, -1 );
				} else {
					$name				=	strtolower( $name );
					$fieldnames[$f]		=	$name;
				}
				$names	.=	'"'.$name.'",';
				$f++;
			}
			$names	=	substr( $names, 0, -1 );
		}
		//
		$fields		=	$this->getFields( $fieldnames, $names );
		$subfields	=	array();
		foreach ( $subnames as $subkey => $subval ) {
			$subfields[$subkey]	=	explode( '|F|', $subval );
			if ( sizeof( $subfields[$subkey] ) ) {
				foreach ( $subfields[$subkey] as $subfield_key => $subfield_val ) {
					$query	= ' SELECT s.*, cc.name AS typename FROM #__jseblod_cck_items AS s LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type WHERE s.name = "'.$subfield_val.'"';
					$subfields[$subkey][$subfield_key]	=	CCK::DB_loadObject( $query );
				}
			}
		}
		
		// #
		// # 2
		// #

		// Init
		set_time_limit( 0 );
		$datenow			=&	JFactory::getDate();
		$unixTime			=	$datenow->toUnix();
		$second				=	date( 's' );
		$second				=	$second - 3;
		$default['created']	=	date( 'Y-m-d H:i:'.$second, $unixTime );
		JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
		
		// Init TypeField
		$order				=	1;
		$assignmentsValues	=	( $default['action_mode'] == 2 ) ? ', ( '.$default['content_type_id'].', 121, "admin", '.$order.' ) '
																 : ', ( '.$default['content_type_id'].', 1, "admin", '.$order.' ) ';
		// #
		// # 3
		// #
		
		// Add/Edit Content
		$count	=	count( $content );
		for ( $i = 0, $num = 1; $i < $count; $i++ ) {
			$j			=	0;
			//
			$row		=&	JTable::getInstance( $default['action_text'] );
			$text		=	'';
			$group_text	=	array();
			//
			$default['content_type_up']	=	'';
			$default['index_key_id']	=	'';
			$default['index_key_name']	=	'';
			$default['params']			=	'';
			$default['meta']			=	array();
			$default['save']			=	0;
			$default['substitute']		=	array();
			$default['nIndexed']		=	0;
			$default['batchIndexed']	=	array();
			//
			$x			=	0;
			$id			=	0;
			$isNew 		=	0;
			$memory		=	array();
			$rowU		=	array();
			$isNewU		=	1;
			$rowUser	=	clone( JFactory::getUser( 0 ) );
			//
			
			// #
			// # 4
			// #
			
			// Process Field
			foreach ( $fieldnames as $fieldname ) {
				if ( $fieldname ) {
					// #
					if ( strpos( $fieldname, '#' ) ) {
						$fieldname	=	str_replace( '#', $num, $fieldname );
					}
					
					// Get Fieldtype
					$typename	=	@$fields[$fieldname]->typename;
					
					// Get Value
					if ( @$content[$i][$j] != '' ) {
						$value	=	( $default['force_utf8'] ) ? utf8_encode( @$content[$i][$j] ) : @$content[$i][$j];
					} else {
						$value	=	'';
					}
					
					// Process Field
					if ( $fieldname == 'jseblod' || $fieldname == 'id' || $fieldname == 'userid' ) {
						if ( $fieldname == 'id' ) {					
							$id		=	$value;
							$row->load( $id );
							$isNew 	=	( isset( $row->title ) ) ? 0 : 1;
							if ( @$id && @$id == $idPrev ) {
								$num++;
								$x		=	1;
							} else {
								$num	=	1;
							}
							$idPrev	=	( @$id ) ? $id : 0;
						} else if ( $fieldname == 'jseblod' ) {
							$default['content_type_up']	=	$value;
						} else {
							$userId		=	$value;
							$rowUser	=	clone( JFactory::getUser( $userId ) );
							$isNewU		=	( isset( $rowUser->name ) ) ? 0 : 1;
							if ( ! $isNewU ) {
								$id		=	HelperjSeblod_Helper::getCCKUser( 'contentid', 'userid', $userId );
								if ( $id ) {
									$row->load( $id );
									$isNew	=	0;
								}
							}
						}
					} else {
						// Get Text
						include( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_import.php' );
						
						// Add TypeField
						if ( $i == 0 || $x ) {
							if ( ! $typename ) {
								$itemId	=	$this->addField( $fieldname );
							} else {
								$itemId	=	@$fields[$fieldname]->id;
							}
							// Assignments Type||Field
							if ( $default['content_type_id'] && $itemId ) {
								$order++;
								$assignmentsValues .= ', ( '.$default['content_type_id'].', '.(int)$itemId.', "admin", '.$order.' ) ';
							}
						}
					}
					// Set Memory
					$memory[$fieldname]	=	$value;
					$j++;
				}
			}
			
			// #
			// # 5
			// #
			
			// Import User
			if ( $default['action_mode'] == 2  ) {
				$config			=&	JFactory::getConfig();
				$authorize		=&	JFactory::getACL();
				$usersConfig	=	&JComponentHelper::getParams( 'com_users' );
				
				if ( $isNewU ) {
					$rowU['password2']	=	$rowU['password'];
					if ( ! $rowUser->bind( $rowU, 'usertype' ) ) {
						JError::raiseError( 500, $rowUser->getError() );
					}
					$rowUser->set( 'id', 0 );
					if ( ! $rowUser->get( 'usertype' ) ) {
						$rowUser->set( 'usertype', 'Registered' );
					}
					$rowUser->set( 'gid', $authorize->get_group_id( '', $rowUser->get( 'usertype' ), 'ARO' ) );
					if ( ! $rowUser->get( 'registerDate' ) ) {
						$rowUser->set( 'registerDate', $date->toMySQL() );
					}
					if ( ! $rowUser->save() ) {
						JError::raiseWarning('', JText::_( $rowUser->getError()));
						return false;
					}
					if ( $userId ) {
						$query	=	'UPDATE #__core_acl_aro AS s SET s.value='.(int)$userId.' WHERE s.value='.(int)$rowUser->id;
						$this->_db->setQuery( $query );
						if ( ! $this->_db->query() ) {
							return false;
						}
						$query	=	'UPDATE #__users AS s SET s.id='.(int)$userId.' WHERE s.id='.(int)$rowUser->id;
						$this->_db->setQuery( $query );
						if ( ! $this->_db->query() ) {
							return false;
						}
						$rowUser->id	=	$userId;
					}
				} else {
					if ( $rowU['password'] ) {
						$rowU['password2']	=	$rowU['password'];
					}
					if ( ! $rowUser->bind( $rowU, 'usertype' ) ) {
						JError::raiseError( 500, $rowUser->getError() );
					}
					$rowUser->set( 'gid', $authorize->get_group_id( '', ( $rowUser->usertype ), 'ARO' ) );
					if ( ! $rowUser->save() ) {
						JError::raiseWarning('', JText::_( $rowUser->getError()));
						return false;
					}
				}
				$default['author']	=	$rowUser->id;
			}

			// #
			// # 6
			// #
			
			// Import Content
			if ( $id && ! $isNew && $default['update_mode'] != -1 && $default['action_mode'] != 1 ) {
				$regex			=	"#::(.*?)::(.*?)::/(.*?)::#s";
				preg_match_all( $regex, $row->introtext, $matches );
				if ( sizeof( $matches[1] ) ) {
					$k	=	0;
					foreach ( $matches[1] as $match ) {
						if ( array_key_exists( $match, $memory ) ) {
							$row->introtext	=	str_replace( '::'.$match.'::'.$matches[2][$k].'::/'.$match.'::', '::'.$match.'::'.$memory[$match].'::/'.$match.'::', $row->introtext );
							$text			=	str_replace( '::'.$match.'::'.$memory[$match].'::/'.$match.'::<br />', '', $text );
						}
						$k++;
					}
				}
				$row->introtext	=	str_replace( '::jseblodend::::/jseblodend::', $text.'::jseblodend::::/jseblodend::', $row->introtext );
			} else {
				$default['content_type_up']	=	( $default['content_type_up'] != '' ) ? $default['content_type_up'] : $default['content_type'];
				$text						=	'<br />::jseblod::'.$default['content_type_up'].'::/jseblod::<br />'.$text.'::jseblodend::::/jseblodend::';	
				if ( $default['action_mode'] == 1 ) {
					$row->description	=	$text;					
				} else {
					$row->introtext		=	$text;				
				}

			}
			
			// #
			// # 7
			// #
			
			// Get Groups
			if ( sizeof( $group_text ) ) {
				if ( $default['action_mode'] == 1 ) {
					foreach( $group_text as $group_txt ) {
						$row->description	.=	$group_txt;
					}
				} else {
					foreach( $group_text as $group_txt ) {
						$row->introtext		.=	$group_txt;
					}
				}
			}
			
			// #
			// # 8
			// #
			
			// Title
			if ( sizeof( $default['substitute'] ) ) {
				$title		=	null;
				foreach( $default['substitute'] as $sub ) {			
					$title .=	$sub.' ';
				}
				$row->title	=	trim( $title );
			}
			if ( ! $row->title ) {
				if ( $default['action_mode'] == 2 ) {
					$row->title	=	$rowU['username'];
				} else {
					$row->title	=	$datenow->toFormat( "%Y %m %d %H %M %S" );
				}
			}
			// Alias
			if ( ! $row->alias ) {
				$row->alias	=	JFilterOutput::stringURLSafe( $row->title );
				if( trim( str_replace( '-', '', $row->alias ) ) == '' ) {
					$row->alias	=	$datenow->toFormat( "%Y-%m-%d-%H-%M-%S" );
				}
			}
			
			// Others
			if ( $default['action_mode'] == 1 ) {
				// Category
				if ( ! isset( $row->parent_id ) ) {
					$row->parent_id	=	$default['catid'];
				}
				// Section
				if ( ! isset( $row->section ) ) {
					$row->section	=	CCK_DB_Result( 'SELECT section FROM #__categories WHERE id='.(int)$row->parent_id );
				}
				// State
				if ( ! isset( $row->published ) ) {
					$row->published	=	$default['state'];
				}
				// Author
				//if ( ! $row->created_user_id ) {
				//	$row->created_user_id	=	$default['author'];
				//}
				// Access
				if ( ! isset( $row->access ) ) {
					$row->access	=	$default['access'];
				}
			} else {
				// Category
				if ( ! isset( $row->catid ) ) {
					$row->catid	=	$default['catid'];
				}
				// Save
				if ( $default['save'] ) {
					$row->catid	=	$default['save'];
				}
				// Section
				if ( ! isset( $row->sectionid ) ) {
					$row->sectionid	=	CCK_DB_Result( 'SELECT section FROM #__categories WHERE id='.(int)$row->catid );
				}
				// State
				if ( ! isset( $row->state ) ) {
					$row->state	=	$default['state'];
				}
				// Author
				if ( ! $row->created_by ) {
					$row->created_by	=	$default['author'];
				}
				// Access
				if ( ! isset( $row->access ) ) {
					$row->access	=	$default['access'];
				}
				// Readmore Process
				$row->introtext	=	str_replace( '<br>', '<br />', $row->introtext );
				$pattern		=	'#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
				$tagPos			=	preg_match( $pattern, $row->introtext );
				if ( $tagPos == 0 )	{
					$row->introtext	=	$row->introtext;
					$row->fulltext	=	'';
				} else {
					list( $row->introtext, $row->fulltext ) = preg_split( $pattern, $row->introtext, 2 );
				}
				// Created Date
				if ( ! $row->created ) {
					$row->created	=	$default['created'];
				}
				// Start Publishing
				if ( ! $row->publish_up ) {
					$row->publish_up	=	$default['created'];
				}
				// Params
				if ( $default['params'] ) {
					$row->attribs	=	$default['params'];
				}
				// Meta
				if ( $default['meta'] ) {
					$row->metakey	=	$default['meta']['key'];
					$row->metadesc	=	$default['meta']['desc'];
					$row->metadata	=	$default['meta']['data'];
				}
			}
			
			// #
			// # 9
			// #
			
			if ( $default['lang'] ) {
				// Store||Update Translations
				include( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonprepare_translation_import.php' );
			} else {
				// Store||Update Content
				if ( $id && $isNew ) {
					// Create with specific ids
					$row->id	=	null;
					if ( ! $row->store() ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
					$query	=	'UPDATE #__content AS s SET s.id='.(int)$id.' WHERE s.id='.(int)$row->id;
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						return false;
					}
					$row->id	=	$id;
				} else {
					if ( ! $row->store() ) {
						$this->setError( $this->_db->getErrorMsg() );
						//return false;
					}
				}
				
				// Reorder
				if ( $default['action_mode'] != 1 ) {
					$row->reorder('catid = '.(int) $row->catid.' AND state >= 0');
				}
			}
			
			// #
			// # 10
			// #
			
			// Link ArticleUser
			if ( $default['action_mode'] == 2  ) {
				if ( $row->id && $rowUser->id ) {
					$newUser					=	array();
					$newUser['contentid']		=	$row->id;
					$newUser['userid']			=	$rowUser->id;
					$newUser['type']			=	$default['content_type'];
					$newUser['registration']	=	( $default['action_mode'] == 2 ) ? 1 : 0;
					$CCKUser	=&	JTable::getInstance( 'users', 'Table' );
					$CCKUser->bind( $newUser );
					$CCKUser->setType( $newUser['contentid'], $newUser['type'] );
					$CCKUser->store();
				}
			}

			// #
			// # 11
			// #
		
			// Indexed
			if ( $default['index_key_name'] && $default['index_key_id'] ) {
				$query	= 'INSERT IGNORE INTO #__jseblod_cck_extra_index_key_'.$default['index_key_name'].' ( id, keyid )'
						. ' VALUES ('.$row->id.', "'.$default['index_key_id'].'")';
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					//return false;
				}
			}
			// Indexed
			if ( $default['nIndexed'] && sizeof( $default['batchIndexed'] ) ) {
				for ( $l = 0; $l < $default['nIndexed']; $l++ ) {
					$query	= 'INSERT IGNORE INTO #__jseblod_cck_extra_index_'.$default['batchIndexed'][$l]['name'].' ( id, indexid )'
							. ' VALUES ('.$row->id.', "'.$default['batchIndexed'][$l]['id'].'")';
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						//return false;
					}
				}
			}
			
		}

		// #
		// # 12
		// #
		
		// Add TypeItem
		$this->_addTypeItem( $assignmentsValues, $default );
		
		// End
		if ( JFile::exists( $dest ) ) {
			JFile::delete( $dest );
		}
		set_time_limit( 30 );		
		
		return $i;
	}

	/**
	 * Upload File
	 **/
	function _uploadFile()
	{
		$csv_file 	=	JRequest::getVar( 'import_file', null, 'files', 'array' );
		
		$config		=&	JFactory::getConfig();
    	$tempFolder	=	$config->getValue( 'config.tmp_path' );
   
   		$fileName 	=	JFile::makeSafe( $csv_file['name'] );
    
    	$src		=	$csv_file['tmp_name'];
    	$dest		=	$tempFolder.DS.$fileName;
    	if ( strtolower( JFile::getExt( $fileName ) ) != 'csv' ) {
    		return false;
    	}
    	if ( ! JFile::upload( $src, $dest ) ) {
    		return false;
    	}
		
		return $dest;
	}
	
	/**
     * Add TypeItem
     **/
	function _addTypeItem( $assignmentsValues, $default ) {
		if ( $default['content_type_id'] ) {
			$assignmentsValues	=	substr( $assignmentsValues, 1 );
			$query	= 'INSERT INTO #__jseblod_cck_type_item ( typeid, itemid, client, ordering )'
				   	. ' VALUES ' . $assignmentsValues;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			$assignmentsValues	=	str_replace( 'admin', 'content', $assignmentsValues );
			$query	= 'INSERT IGNORE INTO #__jseblod_cck_type_item_email ( typeid, itemid, client, ordering )'
				   	. ' VALUES ' . $assignmentsValues;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}	
	}
	
	/**
	 * Data Export CSV
	 **/
	function dataExportCSV( $actionMode )
	{
		
		return false;
	}
	
	/**
	 * Data Import XML
	 **/
	function dataImportXML( $actionMode ) {
		
		return true;
	}
	
	/**
	 * Data Export XML
	 **/
	function dataExportXML( $actionMode ) {
		
		return false;
	}
	
	/**
	 * Data Export HTML
	 **/
	function dataExportHTML( $data )
	{
		$name		=	JRequest::getVar( 'filename' );
		$ext		=	JRequest::getVar( 'extension' );

		$config		=&	JFactory::getConfig();
		$tempFolder	=	$config->getValue( 'config.tmp_path' );
		
		if ( $ext == 'html' ) {
$data	=
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>'
.$data
.'</body>
</html>';
		}
		$file	=	$tempFolder.DS.$name.'.'.$ext;
		if ( JFile::exists( $file ) ) {
			JFile::delete( $file );	
		}
		JFile::write( $file, $data );
		if ( JFile::exists( $file ) ) {
			return $file;
		}
		
		return false;
	}
	
	/**
	 * Get Fields
	 **/
	function getFields( $fieldnames, $names )
	{
		$db		=&	JFactory::getDBO();
				
		$query	= ' SELECT DISTINCT s.*, cc.name AS typename'
				. ' FROM #__jseblod_cck_items AS s '
				. ' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
				. ' WHERE s.name IN ( '.$names.' ) '
				. 'ORDER BY FIELD(s.name, '.$names.')'
				;
		
		$db->setQuery( $query );
		$fields	=	$db->loadObjectList( 'name' );
		
		if ( sizeof( $fieldnames ) ) {
			foreach( $fieldnames as $fieldname ) {
				$loop	=	null;
				if ( strpos( $fieldname, '#' ) ) {
					$name	=	substr( $fieldname, 0, -1 );
					$query	= ' SELECT DISTINCT s.*, cc.name AS typename'
							. ' FROM #__jseblod_cck_items AS s '
							. ' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
							. ' WHERE s.name LIKE "'.$name.'%"'
							;					
					$db->setQuery( $query );
					$loop	=	$db->loadObjectList( 'name' );
					if ( sizeof( $loop ) ) {
						$fields	=	array_merge( $fields, $loop );
					}
				}
			}
		}
		
		return $fields;
	}

	/**
	 * Add Field
	 **/
	function addField( $name )
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'tables' );
		
		$row					=&	JTable::getInstance( 'items', 'Table' );
		$row->title				=	ucfirst( str_replace( '_', ' ', $name ) );
		$row->name				=	$name;
		$row->category			=	1;
		$row->type				=	1;
		$row->light				=	1;
		$row->label				=	ucfirst( str_replace( '_', ' ', $name ) );
		$row->display			=	3;
		$row->gACL				=	17;
		
		if ( ! $row->store() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		return $row->id;
	}
	
	/**
	 * Add Content Type
	 **/
	function addContentType( $title )
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'tables' );
		
		$row					=&	JTable::getInstance( 'types', 'Table' );
		$row->title				=	$title;
		$row->name				=	HelperjSeblod_Helper::stringURLSafe( $title );
		if( trim( str_replace( '_', '', $row->name ) ) == '' ) {
    		$datenow	=&	JFactory::getDate();
			$row->name =	$datenow->toFormat( "%Y_%m_%d_%H_%M_%S" );
    	}
		$row->category			=	1;
		$row->admintemplate		=	1;
		$row->sitetemplate		=	1;
		$row->contenttemplate	=	4;
		$row->published			=	1;
		if ( ! $row->store() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
				
		return $row;
	}
	
	/**
	 * Media Process
	 **/
	function mediaProcess()
	{
		$path		=	JRequest::getVar( 'location' );
		$path		=	str_replace( '/', DS, $path );
		$path		=	str_replace( '\\', DS, $path );
		$formatI	=	JRequest::getVar( 'format' );
		$widthI		=	JRequest::getVar( 'width' );
		$heightI	=	JRequest::getVar( 'height' );
		$waterI		=	JRequest::getVar( 'options2' );
		$waterExtI	=	substr( strrchr( $waterI, "." ), 1 );
		$extra		=	JRequest::getVar( 'extra' );
		
		$thumb1		=	JRequest::getVar( 'thumb1' );
		$thumb2		=	JRequest::getVar( 'thumb2' );
		$thumb3		=	JRequest::getVar( 'thumb3' );
		
		$rename		=	JRequest::getVar( 'rename' );
		
		$writable	=	is_writable( JPATH_SITE.DS.$path );
		
		if ( JFolder::exists( JPATH_SITE.DS.$path ) && $writable )
		{
			$files	=	JFolder::files( JPATH_SITE.DS.$path, '.', false, false );
			$num	=	count( $files );
			//$time	=	5*$num;
			//set_time_limit($time);
			set_time_limit( 0 );
			if ( $num ) {
				$k	=	0;
				foreach ( $files as $image ) {
					$location	=	JPATH_SITE.DS.$path.DS.$image;
					
					// -- Image Process
					$newSize	=	getimagesize($location);
					$newWidth	=	$newSize[0];
					$newHeight	=	$newSize[1];
					$newRatio	=	$newWidth / $newHeight;	
					$newExt		=	substr( strrchr( $location, "." ), 1 );
					$resImage	=	null;
					switch( $newExt ) {
						case 'gif':
						case 'GIF':
							$resImage	=	@ImageCreateFromGIF( $location );
							break;
						case 'jpg':
						case 'JPG':
						case 'jpeg': 
						case 'JPEG': 
							$resImage	=	@ImageCreateFromJPEG( $location );
							break;
						case 'png':
						case 'PNG':
							$resImage	=	@ImageCreateFromPNG( $location );
							break;
						default:
							break;
					}
					
					if ( ! $resImage ) {
						//...
					} else {
						//umask(0002);
						$options	=	array();
						// --- !
						$options[]	=	$formatI.'--'.$widthI.'--'.$heightI;
						if ( $thumb1 ) {
							$width1		=	JRequest::getVar( 'width1' );
							$height1	=	JRequest::getVar( 'height1' );
							$options[]	=	$thumb1.'--'.$width1.'--'.$height1;
						}
						if ( $thumb2 ) {
							$width2		=	JRequest::getVar( 'width2' );
							$height2	=	JRequest::getVar( 'height2' );
							$options[]	=	$thumb2.'--'.$width2.'--'.$height2;
						}
						if ( $thumb3 ) {
							$width3		=	JRequest::getVar( 'width3' );
							$height3	=	JRequest::getVar( 'height3' );
							$options[]	=	$thumb3.'--'.$width3.'--'.$height3;
						}
						if ( sizeof( $options ) ) {
							$i	=	0;
							foreach($options as $opts) {
								$opt	=	explode( '--', $opts );
								if ( $opt[0] ) {
									$newX	= 	0;
									$newY	=	0;
									$thumbX	=	0;
									$thumbY =	0;
									if ( ! $opt[1] && ! $opt[1] ) {
										break;
									}							
									$width	=  ( ! $opt[1] && $opt[2] ) ? round( $opt[2] * $newRatio ) : $opt[1];
									$height	=  ( $opt[1] && ! $opt[2] ) ? round( $opt[1] / $newRatio ) : $opt[2];
									$ratio	=	$width / $height;
									switch( $opt[0] )
									{
										case "addcolor":
											$thumbWidth		=	( $ratio > $newRatio ) ? round( $height * $newRatio ) : $width;
											$thumbHeight	=	( $ratio < $newRatio ) ? round( $width / $newRatio ) : $height;
											$thumbX			=	( $width / 2 ) - ( $thumbWidth / 2 );
											$thumbY			=	( $height / 2 ) - ( $thumbHeight / 2 );
											break;
										case "crop":
											$thumbWidth		=	( $ratio < $newRatio ) ? round( $height * $newRatio ) : $width;
											$thumbHeight	=	( $ratio > $newRatio ) ? round( $width / $newRatio ) : $height;
											$thumbX			=	( $width / 2 ) - ( $thumbWidth / 2 );
											$thumbY			=	( $height / 2 ) - ( $thumbHeight / 2 );
											break;
										case "maxfit":
											$width			=	( $width > $newWidth ) ? $newWidth : $width;
								      $height			=	( $height > $newHeight ) ? $newHeight : $height;
											$width			=	( $ratio > $newRatio ) ? round( $height * $newRatio ) : $width;
											$height			=	( $ratio < $newRatio ) ? round( $width / $newRatio ) : $height;
											$thumbWidth		=	$width;
											$thumbHeight	=	$height;
											break;
										case "stretch":
											$thumbWidth		=	$width;
											$thumbHeight	=	$height;
											break;
										default:
											break;
									}
									$thumbImage	=	imageCreateTrueColor( $width, $height );
									if ( $newExt == 'png' || $newExt == 'PNG' ) {
										imagealphablending( $thumbImage, false );
									}
									//add color
									if( $opt[0] == 'addcolor' ) {
										$r		=	hexdec( substr( $extra, 1, 2 ) );
										$g		=	hexdec( substr( $extra, 3, 2 ) );
										$b		=	hexdec( substr( $extra, 5, 2 ) );
										$color	=	imagecolorallocate( $thumbImage, $r, $g, $b );
										imagefill( $thumbImage, 0, 0, $color );
									}
									//
									imagecopyresampled( $thumbImage, $resImage, $thumbX, $thumbY, $newX, $newY, $thumbWidth, $thumbHeight, $newWidth, $newHeight );
									if ( $i == 0 ) {
										//add mask
										if ( $opt[0] == 'maxfit' && $newHeight > $newWidth && JFile::exists( JPATH_SITE.DS.str_replace( '.'.$waterExtI, '2.'.$waterExtI, $waterI ) ) ) {
											$maskImage	=	ImageCreateFromPNG( JPATH_SITE.DS.str_replace( '.'.$waterExtI, '2.'.$waterExtI, $waterI ) );
											imagealphablending( $maskImage, 1 );
											imagecopy( $thumbImage, $maskImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight );										
										} else {										
											if ( JFile::exists( JPATH_SITE.DS.$waterI ) ) {
												$maskImage	=	ImageCreateFromPNG( JPATH_SITE.DS.$waterI );
												imagealphablending( $maskImage, 1 );
												imagecopy( $thumbImage, $maskImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight );
											}
										}
										//
										if ( $rename ) {
											$suffix			=	( $k < 9 ) ? '-0'.($k+1) : '-'.($k+1);
											$thumbLocation	=	JPATH_SITE.DS.$path.DS.$rename.$suffix.'.'.$newExt;
										} else {
											$thumbLocation	=	JPATH_SITE.DS.$path.DS.$image;
										}
										if ( JFile::exists( $location ) ) {
											JFile::delete( $location );
										}
									} else {
										if ( ! JFolder::exists( JPATH_SITE.DS.$path.DS.'_thumb'.$i ) ) {
											JFolder::create( JPATH_SITE.DS.$path.DS.'_thumb'.$i );
											JFile::write( JPATH_SITE.DS.$path.DS.'_thumb'.$i.DS.'index.html', '<html><body bgcolor="#FFFFFF"></body></html>' );
										}
										if ( $rename ) {
											$suffix			=	( $k < 9 ) ? '-0'.($k+1) : '-'.($k+1);
											$thumbLocation	=	JPATH_SITE.DS.$path.DS.'_thumb'.$i.DS.$rename.$suffix.'.'.$newExt;
										} else {
											$thumbLocation	=	JPATH_SITE.DS.$path.DS.'_thumb'.$i.DS.$image;
										}
									}
									switch( $newExt ) {
										case 'gif':
										case 'GIF':
											imagegif( $thumbImage, $thumbLocation );
											break;
										case 'jpg':
										case 'JPG':
										case 'jpeg': 
										case 'JPEG': 
											imagejpeg( $thumbImage, $thumbLocation, 90 );
											break;
										case 'png':
										case 'PNG':
											imagesavealpha($thumbImage, true);
											imagepng( $thumbImage, $thumbLocation, 9 );
											break;
										default:
											break;
									}
									imagedestroy( $thumbImage );
								}
								$i++;
							}
						}
						$k++;
					}
					// -- Image Process End
				}
			}
			set_time_limit( 30 );
			
			return $k;
		}
		
		return 0;
	}
	
	/**
	 * Replace Process
	 **/
	function replaceProcess()
	{
		$cids		=	JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$search		=	JRequest::getVar( 'search_string' );
		$replace	=	JRequest::getVar( 'replace_string' );
		
		$inCids = implode( ',', $cids );
				
		if ( $search != '' )
		{
			//TODO: where content type specifique si coche / tous si aucun coche...
			$where = ' WHERE checked_out = 0';
			$query	= 'UPDATE #__content'
					. ' SET introtext = ( REPLACE( introtext, "'.$search.'", "'.$replace.'" ) )'
					. $where
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			return 1;
		}
		
		return 0;
	}
	
	/**
	 * Lang Publish
	 **/
	function langPublish( $artids, $langId )
	{
		$query	= 'UPDATE #__jf_content AS s SET published=1 WHERE s.reference_table="content" AND language_id='.$langId.' AND s.reference_id IN ( '.$artids.' )';
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Lang Unpublish
	 **/
	function langUnpublish( $artids, $langId )
	{
		$query	= 'UPDATE #__jf_content AS s SET published=0 WHERE s.reference_table="content" AND language_id='.$langId.' AND s.reference_id IN ( '.$artids.' )';
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Lang Delete
	 **/
	function langTrash( $artids, $langId )
	{
		$query	= 'DELETE s.* FROM #__jf_content AS s WHERE s.reference_table="content" AND language_id='.$langId.' AND s.reference_id IN ( '.$artids.' )';
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			return false;
		}
		
		return true;
	}

	/**
	 * Lang Translate
	 **/
	function langTranslate( $artids )
	{
		$datenow	=&	JFactory::getDate();
		$inCids 	=	explode( ',', $artids );	
		$langs		=	CCK_LANG_List();
		$user 		=&	JFactory::getUser();
		
		if ( sizeof( $inCids ) ) {
			JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
			//JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'tables' );
			require( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'tables'.DS.'JFContent.php' );
			foreach( $inCids as $cid ) {
				$row			=&	JTable::getInstance( 'content' );
				$row->load( $cid );
				$query			=	'SELECT s.language_id FROM #__jf_content AS s WHERE s.reference_table="content" AND s.reference_field="title" AND s.reference_id='.(int)$row->id;
				$this->_db->setQuery( $query );
				$translations	=	$this->_db->loadResultArray();
				$langDefault = CCK_LANG_Default();
				foreach( $langs as $elem ) {
					if ( $elem->shortcode != $langDefault && ( ! $translations || ( $translations && ( array_search( $elem->id, $translations ) === false ) ) ) ) {
						$jf						=&	JTable::getInstance( 'jfContent', '' );
						$jf->language_id		=	$elem->id;
						$jf->reference_id		=	$row->id;
						$jf->reference_table	=	'content';
						$jf->reference_field	=	'title';
						$jf->value				=	$row->title;
						$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
						$jf->modified_by		=	$user->id;
						$jf->published			=	0;
						if ( ! $jf->store() ) {
							return false;
						}
						//
						$jf						=&	JTable::getInstance( 'jfContent', '' );
						$jf->language_id		=	$elem->id;
						$jf->reference_id		=	$row->id;
						$jf->reference_table	=	'content';
						$jf->reference_field	=	'alias';
						$jf->value				=	$row->alias;
						$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
						$jf->modified_by		=	$user->id;
						$jf->published			=	0;
						if ( ! $jf->store() ) {
							return false;
						}
						//
						$jf						=&	JTable::getInstance( 'jfContent', '' );
						$jf->language_id		=	$elem->id;
						$jf->reference_id		=	$row->id;
						$jf->reference_table	=	'content';
						$jf->reference_field	=	'introtext';
						$jf->value				=	$row->introtext;
						$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
						$jf->modified_by		=	$user->id;
						$jf->published			=	0;
						if ( ! $jf->store() ) {
							return false;
						}
						//
						$jf						=&	JTable::getInstance( 'jfContent', '' );
						$jf->language_id		=	$elem->id;
						$jf->reference_id		=	$row->id;
						$jf->reference_table	=	'content';
						$jf->reference_field	=	'fulltext';
						$jf->value				=	$row->fulltext;
						$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
						$jf->modified_by		=	$user->id;
						$jf->published			=	0;
						if ( ! $jf->store() ) {
							return false;
						}
						//
						$jf						=&	JTable::getInstance( 'jfContent', '' );
						$jf->language_id		=	$elem->id;
						$jf->reference_id		=	$row->id;
						$jf->reference_table	=	'content';
						$jf->reference_field	=	'attribs';
						$jf->value				=	$row->attribs;
						$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
						$jf->modified_by		=	$user->id;
						$jf->published			=	0;
						if ( ! $jf->store() ) {
							return false;
						}
					}
				}
			}
		}
		
		return true;
	}
	
	/**
	 * Quick Config
	 **/
	function quickConfig()
	{
		$modal_width		=	JRequest::getVar( 'modal_width' );
		$modal_height		=	JRequest::getVar( 'modal_height' );
		$restriction_type	=	JRequest::getVar( 'restriction_type' );
		$restriction_field	=	JRequest::getVar( 'restriction_field' );
		$quick_title		=	JRequest::getVar( 'quick_title' );
		$quick_color		=	JRequest::getVar( 'quick_color' );
		$quick_color		=	str_replace( '*', '#', $quick_color );
											
		$query	= 'UPDATE #__jseblod_cck_configuration'
				. ' SET modal_width = '.(int)$modal_width
				. ' , modal_height = '.(int)$modal_height
				. ' , restriction_type = '.(int)$restriction_type
				. ' , restriction_field = '.(int)$restriction_field
				. ' WHERE id = 1'
				;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		if ( $quick_title && $quick_title != 'Quick Category' ) {
			$quick_title	=	' SET s.title="'.$quick_title.'"';
		} else {
			$quick_title	=	'';
		}
		if ( $quick_color && $quick_color != '#ffd700' ) {
			$quick_color	=	( $quick_title ) ? ' , s.color="'.$quick_color.'"' : ' SET s.color="'.$quick_color.'"';
		} else {
			$quick_color	=	null;
		}
		if ( $quick_title || $quick_color ) {
			$query	= 'UPDATE #__jseblod_cck_templates_categories AS s'
					. $quick_title
					. $quick_color
					. ' WHERE s.name="quick_category"'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			$query	= 'UPDATE #__jseblod_cck_types_categories AS s'
					. $quick_title
					. $quick_color
					. ' WHERE s.name="quick_category"'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			$query	= 'UPDATE #__jseblod_cck_items_categories AS s'
					. $quick_title
					. $quick_color
					. ' WHERE s.name="quick_category"'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			$query	= 'UPDATE #__jseblod_cck_searchs_categories AS s'
					. $quick_title
					. $quick_color
					. ' WHERE s.name="quick_category"'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
			
		return true;
	}
}
?>