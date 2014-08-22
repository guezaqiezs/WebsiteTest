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

jimport( 'joomla.filesystem.archive' );
jimport( 'joomla.utilities.simplexml' );
jimport( 'joomla.installer.helper' );

/**
 * CCKjSeblod		Share_Export Class
 **/
class CCKjSeblodShare_Import
{
	/**
	 * Import Xml Process Packs
	 **/
	function importContent_Pack( $installPack, $mode, $selection = 0 ) {

	    require_once( JPATH_COMPONENT.DS.'helpers'.DS.'jseblod_share.php' );
    
    	$config		=&	JFactory::getConfig();
    	$tempFolder	=	$config->getValue( 'config.tmp_path' );
   
   		$fileName 	=	JFile::makeSafe( $installPack['name'] );
    
    	$src	=	$installPack['tmp_name'];
    	$dest	=	$tempFolder.DS.$fileName;
    	if ( strtolower( JFile::getExt( $fileName ) ) != 'zip' ) {
    		return false;
    	}
    	if ( ! JFile::upload( $src, $dest ) ) {
    		return false;
    	}
    	if ( ! extension_loaded( 'zlib' ) ) {
    		JError::raiseWarning( 'SOME_ERROR_CODE', JText::_( 'WARNINSTALLZLIB' ) );
    		return false;
    	}
    
    	$fileUnpack	=	HelperjSeblod_Share::unpack( $dest );
    	if ( ! $fileUnpack ) {
    		return false;
    	}
		$path	=	$fileUnpack['extractdir'];
    	$files	=	JFolder::files( $path, '\.xml$', false, false );
		
		// EXTRAITE TOUS LES DOSSIERS ET TOUS LES FICHIERS A LA RACINE DU DOSSIER PRINCIPAL AVANT CETTE BOUCLE !!
		// SI DEJA PRESENT NE PAS LES COPIER !! 
											
		$n = count($files);
		if ( $n > 0 )
		{
			$ignored	=	null;
			$memory		=	array();
			for ( $i = $n-1; $i >= 0; $i-- )
			{
				$file_name	=	$files[$i];
				
				$file_type	=	HelperjSeblod_Share::getType( $files[$i] );
				$file		=	$path.DS.$file_name;
				
				if ( $file_type == 'field' ) {			
					if ( array_search( $file, $memory ) === FALSE ) {
						CCKjSeblodShare_Import::importXmlProcess_Items( $file, $mode, $path, null );
					}
				} else if ( $file_type == 'tmpl' ) {
					if ( array_search( $file, $memory ) === FALSE ) {
						CCKjSeblodShare_Import::importXmlProcess_Templates( $file, $mode, $path, null );
					}
				} else if ( $file_type == 'type' && ! $selection ) {
				//--
					$xml	=	new JSimpleXML;
					if ( ! $xml->loadFile( $file ) ) {
						return false;
					}
					
					if ( $xml->document->attributes( 'type' ) != 'Content_Types' ) {
						return false;
					}

					foreach ( $xml->document->content_type as $type ) {
						$type_childs		=	get_object_vars( $type );
						$k					=	0;
						$x					=	0;
						$new        		=	1;
						$assignItem			=	array();
						$assignItemC		=	array();
						if ( array_key_exists( 'title', $type_childs ) ) {
							$title				=	( $type->title[0]->data() ) ? $type->title[0]->data() : '';
						}
						if ( array_key_exists( 'name', $type_childs ) ) {
							$name				=	( $type->name[0]->data() ) ? $type->name[0]->data() : '';
						}
						if ( array_key_exists( 'category', $type_childs ) ) {
							$category			=	( $type->category[0]->data() ) ? $type->category[0]->data() : ( $name ? $name : 3 );
						}
						if ( array_key_exists( 'admintemplate', $type_childs ) ) {
							$admintemplate		=	( $type->admintemplate[0]->data() ) ? $type->admintemplate[0]->data() : 'default_form';
						}
						if ( array_key_exists( 'sitetemplate', $type_childs ) ) {
							$sitetemplate		=	( $type->sitetemplate[0]->data() ) ? $type->sitetemplate[0]->data() : 'default_form';
						}
						if ( array_key_exists( 'contenttemplate', $type_childs ) ) {
							$contenttemplate	=	( $type->contenttemplate[0]->data() ) ? $type->contenttemplate[0]->data() : 'default_content';
						}
						if ( array_key_exists( 'description', $type_childs ) ) {
							$description		=	( $type->description[0]->data() ) ? htmlspecialchars(htmlspecialchars_decode($type->description[0]->data()), ENT_QUOTES) : '';
						}
						if ( array_key_exists( 'published', $type_childs ) ) {
							$published			=	( $type->published[0]->data() ) ? $type->published[0]->data() : 0;
						}
						
						if ( array_key_exists( 'fields', $type_childs ) ) {
							foreach ( $type->fields[0]->children() as $child ) {
								$item_name			=	$child->data();
								if ( $item_name ) {	
									$item_path			=	$path.DS.'field_'.$item_name.'.xml';								
									$assignItem[$k][0]	=	CCKjSeblodShare_Import::importXmlProcess_Items( $item_path, $mode, $path, $name );
									$assignItem[$k][1]	=	$child->attributes( 'client' );
									//
									$assignItem[$k][2]	=	( $child->attributes( 'typography' ) ) ? $child->attributes( 'typography' ) : '';
									$assignItem[$k][3]	=	( $child->attributes( 'submissiondisplay' ) ) ? $child->attributes( 'submissiondisplay' ) : '';
									$assignItem[$k][4]	=	( $child->attributes( 'editiondisplay' ) ) ? $child->attributes( 'editiondisplay' ) : '';
									$assignItem[$k][5]	=	( $child->attributes( 'value' ) ) ? $child->attributes( 'value' ) : '';
									$assignItem[$k][6]	=	( $child->attributes( 'helper' ) ) ? $child->attributes( 'helper' ) : '';
									$assignItem[$k][7]	=	( $child->attributes( 'live' ) ) ? $child->attributes( 'live' ) : '';
									$assignItem[$k][8]	=	( $child->attributes( 'acl' ) ) ? $child->attributes( 'acl' ) : '';
									//
									$memory[]			=	$item_path;
									$k++;
								}
							}
						}
						if ( array_key_exists( 'fields_content', $type_childs ) ) {
							foreach ( $type->fields_content[0]->children() as $child ) {
								$item_name			=	$child->data();
								if ( $item_name ) {	
									$item_path			=	$path.DS.'field_'.$item_name.'.xml';								
									$assignItemC[$x][0]	=	CCKjSeblodShare_Import::importXmlProcess_Items( $item_path, $mode, $path, $name );
									$assignItemC[$x][1]	=	$child->attributes( 'client' );
									//
									$assignItemC[$x][2]	=	( $child->attributes( 'contentdisplay' ) ) ? $child->attributes( 'contentdisplay' ) : '';
									$assignItemC[$x][3]	=	( $child->attributes( 'bool' ) ) ? $child->attributes( 'bool' ) : 0;
									$assignItemC[$x][4]	=	( $child->attributes( 'helper' ) ) ? $child->attributes( 'helper' ) : '';
									$assignItemC[$x][5]	=	( $child->attributes( 'link' ) ) ? $child->attributes( 'link' ) : '';
									$assignItemC[$x][6]	=	( $child->attributes( 'link_helper' ) ) ? $child->attributes( 'link_helper' ) : '';
									$assignItemC[$x][7]	=	( $child->attributes( 'acl' ) ) ? $child->attributes( 'acl' ) : '';
									//
									$memory[]			=	$item_path;
									$x++;
								}
							}
						}
						if ( ! ( $title && $name ) ) {
							return false;
						}
						if ( $category != 3 ) {
							$query 	= 'SELECT s.id'
									. ' FROM #__jseblod_cck_types_categories AS s'
									. ' WHERE s.name = "'.$category.'"'
									;
							$this->_db->setQuery( $query );
							$categoryid	=	$this->_db->loadResult();
							if ( ! $categoryid ) {
								//
								$c_title		=	( $type->category[0]->attributes( 'title' ) ) ? $type->category[0]->attributes( 'title' ) : $category;
								$c_color		=	( $type->category[0]->attributes( 'color' ) ) ? $type->category[0]->attributes( 'color' ) : '';
								$c_introchar	=	( $type->category[0]->attributes( 'introchar' ) ) ? $type->category[0]->attributes( 'introchar' ) : '';
								$c_colorchar	=	( $type->category[0]->attributes( 'colorchar' ) ) ? $type->category[0]->attributes( 'colorchar' ) : '';
								$c_display		=	( $type->category[0]->attributes( 'display' ) ) ? $type->category[0]->attributes( 'display' ) : 3;
								$c_description	=	( $type->category[0]->attributes( 'description' ) ) ? htmlspecialchars(htmlspecialchars_decode($type->category[0]->attributes( 'description' )), ENT_QUOTES) : '';
								$c_parent		=	( $type->category[0]->attributes( 'parent' ) ) ? $type->category[0]->attributes( 'parent' ) : 'top';
								$c_parents		=	explode( '/', $c_parent );
								if ( sizeof( $c_parents ) ) {
									$t						=	0;
									$c_parentid				=	2;
									$c_parent_title			=	( $type->category[0]->attributes( 'parent_title' ) ) ? $type->category[0]->attributes( 'parent_title' ) : '';
									$c_parent_titles		=	explode( '/', $c_parent_title );
									$c_parent_color			=	( $type->category[0]->attributes( 'parent_color' ) ) ? $type->category[0]->attributes( 'parent_color' ) : '';
									$c_parent_colors		=	explode( '/', $c_parent_color );
									$c_parent_introchar		=	( $type->category[0]->attributes( 'parent_introchar' ) ) ? $type->category[0]->attributes( 'parent_introchar' ) : '';
									$c_parent_introchars	=	explode( '/', $c_parent_introchar );
									$c_parent_colorchar		=	( $type->category[0]->attributes( 'parent_colorchar' ) ) ? $type->category[0]->attributes( 'parent_colorchar' ) : '';
									$c_parent_colorchars	=	explode( '/', $c_parent_colorchar );
									$c_parent_display		=	( $type->category[0]->attributes( 'parent_display' ) ) ? $type->category[0]->attributes( 'parent_display' ) : '';
									$c_parent_displays		=	explode( '/', $c_parent_display );
									foreach ( $c_parents as $c_parent ) {	
										$query 	= 'SELECT s.id'
											. ' FROM #__jseblod_cck_types_categories AS s'
											. ' WHERE s.name = "'.$c_parent.'"'
											;
										$this->_db->setQuery( $query );
										$existing_parentid	=	$this->_db->loadResult();
										if ( ! $existing_parentid ) {
											$c_parent_title		=	( $c_parent_titles[$t] ) ? $c_parent_titles[$t] : $c_parent;
											$c_parent_color		=	( $c_parent_colors[$t] ) ? $c_parent_colors[$t] : '';
											$c_parent_introchar	=	( $c_parent_introchars[$t] ) ? $c_parent_introchars[$t] : '';
											$c_parent_colorchar	=	( $c_parent_colorchars[$t] ) ? $c_parent_colorchars[$t] : '';
											$c_parent_display	=	( $c_parent_displays[$t] ) ? $c_parent_displays[$t] : 2;
											$c_limits			=	CCKjSeblodShare_Import::addCatintoTree( $category, 'types', $c_parentid );
											$c_limit			=	explode( '||', $c_limits );
											$query = 'INSERT INTO #__jseblod_cck_types_categories ( title, name, color, introchar, colorchar, lft, rgt, display, description, published )'
													.' VALUES ( "'.$c_parent_title.'", "'.$c_parent.'", "'.$c_parent_color.'", "'.$c_parent_introchar.'", "'.$c_parent_colorchar.'", '
																.  $c_limit[0].', '.$c_limit[1].', '.$c_parent_display.', "", 1 )';
											$this->_db->setQuery( $query );
											$this->_db->query();
											$c_parentid	=	$this->_db->insertid();
										} else {
											$c_parentid	=	$existing_parentid;
										}
										$t++;
									}
								}
								//
								$c_limits		=	CCKjSeblodShare_Import::addCatintoTree( $category, 'types', $c_parentid );
								$c_limit		=	explode( '||', $c_limits );
								$query = 'INSERT INTO #__jseblod_cck_types_categories ( title, name, color, introchar, colorchar, lft, rgt, display, description, published )'
										.' VALUES ( "'.$c_title.'", "'.$category.'", "'.$c_color.'", "'.$c_introchar.'", "'.$c_colorchar.'", '.$c_limit[0].', '.$c_limit[1].', '.$c_display.', "'.$c_description.'", 1 )'
										;
								$this->_db->setQuery( $query );
								$this->_db->query();
								$categoryid	=	$this->_db->insertid();
								if ( ! $categoryid ) {
									$categoryid	=	3;
								}
							}
						}
						// Admin Form
						$tmpl_path			=	$path.DS.'tmpl_'.$admintemplate.'.xml';
						if ( JFile::exists( $tmpl_path ) ) {
							$memory[]			=	$tmpl_path;
							$admintemplateid	=	CCKjSeblodShare_Import::importXmlProcess_Templates( $tmpl_path, $mode, $path, $name );
						} else {
							if ( JFolder::exists( JPATH_SITE.DS.'templates'.DS.$admintemplate ) ) {
								$admintemplateid	=	CCKjSeblodShare_Import::getResultFromDatabase( 'SELECT id FROM #__jseblod_cck_templates WHERE name="'.$admintemplate.'"' );
							} else {
								$admintemplateid	=	1;
							}
						}
						// Site Form
						$tmpl_path			=	$path.DS.'tmpl_'.$sitetemplate.'.xml';
						if ( JFile::exists( $tmpl_path ) ) {
							$memory[]			=	$tmpl_path;
							$sitetemplateid		=	CCKjSeblodShare_Import::importXmlProcess_Templates( $tmpl_path, $mode, $path, $name );
						} else {
							if ( JFolder::exists( JPATH_SITE.DS.'templates'.DS.$sitetemplate ) ) {
								$sitetemplateid	=	CCKjSeblodShare_Import::getResultFromDatabase( 'SELECT id FROM #__jseblod_cck_templates WHERE name="'.$sitetemplate.'"' );
							} else {
								$sitetemplateid	=	1;
							}
						}						
						// Content
						$tmpl_path			=	$path.DS.'tmpl_'.$contenttemplate.'.xml';
						if ( JFile::exists( $tmpl_path ) ) {
							$memory[]			=	$tmpl_path;
							$contenttemplateid	=	CCKjSeblodShare_Import::importXmlProcess_Templates( $tmpl_path, $mode, $path, $name );
						} else {
							if ( JFolder::exists( JPATH_SITE.DS.'templates'.DS.$contenttemplate ) ) {
								$contenttemplateid	=	CCKjSeblodShare_Import::getResultFromDatabase( 'SELECT id FROM #__jseblod_cck_templates WHERE name="'.$contenttemplate.'"' );
							} else {
								$contenttemplateid	=	3;
							}
						}
						if ( ! $mode ) {
							$query	= ' INSERT IGNORE INTO #__jseblod_cck_types ( title, name, category, admintemplate, sitetemplate, contenttemplate, description, published )'
									. ' VALUES ( "'.$title.'", "'.$name.'", '.$categoryid.', '.$admintemplateid.', '.$sitetemplateid.', '.$contenttemplateid.', "'.$description.'", '
									. $published.' )'
									;
						} else {
							$query	= ' INSERT INTO #__jseblod_cck_types ( title, name, category, admintemplate, sitetemplate, contenttemplate, description, published )'
									. ' VALUES ( "'.$title.'", "'.$name.'", '.$categoryid.', '.$admintemplateid.', '.$sitetemplateid.', '.$contenttemplateid.', "'.$description.'", '
									. $published.' )'
									. ' ON DUPLICATE KEY UPDATE title = "'.$title.'", category = '.$categoryid.', admintemplate = '.$admintemplateid.', sitetemplate = '.$sitetemplateid.','
									. ' contenttemplate = '.$contenttemplateid.', description = "'.$description.'", published = '.$published
									;
						}
						$this->_db->setQuery( $query );
						if ( ! $this->_db->query() ) {
							$this->setError( $this->_db->getErrorMsg() );
							return false;
						}
						
						$insertid	=	$this->_db->insertid();
						if ( ! $insertid ) {
						  $new  = 1;
							$query 	= 'SELECT s.id'
									. ' FROM #__jseblod_cck_types AS s'
									. ' WHERE s.name = "'.$name.'"'
									;
							$this->_db->setQuery( $query );
							$insertid	=	$this->_db->loadResult();
						}
						//
						if ( $new == 1 || ( ! $new && $mode ) ) {
							$itemValues	=	null;
							for ( $k = 0, $m = 0, $l = count($assignItem); $k < $l; $k++ )	{
								if ( $m == 0 ) {
									$itemValues = ' ( '.$insertid.', '.$assignItem[$k][0].', "'.$assignItem[$k][1].'", 1, "'.$assignItem[$k][2].'", "'.$assignItem[$k][3].'", "'.$assignItem[$k][4].'", "'.$assignItem[$k][5].'", "'.$assignItem[$k][6].'", "'.$assignItem[$k][7].'", "'.$assignItem[$k][8].'" ) ';
									$m++;
								} else {
									$order	=	$m + 1;
									$itemValues .= ', ( '.$insertid.', '.$assignItem[$k][0].', "'.$assignItem[$k][1].'", '.$order.', "'.$assignItem[$k][2].'", "'.$assignItem[$k][3].'", "'.$assignItem[$k][4].'", "'.$assignItem[$k][5].'", "'.$assignItem[$k][6].'", "'.$assignItem[$k][7].'", "'.$assignItem[$k][8].'" ) ';
									$m++;
								}
							}
							
							if ( $m > 0 ) {
								$query = 'DELETE FROM #__jseblod_cck_type_item WHERE typeid = '.$insertid;
								$this->_db->setQuery( $query );
								if ( ! $this->_db->query() ) {
									$this->setError( $this->_db->getErrorMsg() );
									return false;
								}
								$query = 'INSERT INTO #__jseblod_cck_type_item ( `typeid`, `itemid`, `client`, `ordering`, `typography`, `submissiondisplay`, `editiondisplay`, `value`, `helper`, `live`, `acl` ) VALUES ' . $itemValues;
								$this->_db->setQuery( $query );
								if ( ! $this->_db->query() ) {
									$this->setError( $this->_db->getErrorMsg() );
									return false;
								}
							}
						}
						if ( $new == 1 || ( ! $new && $mode ) ) {
							$itemValuesC	=	null;
							for ( $k = 0, $m = 0, $l = count($assignItemC); $k < $l; $k++ )	{
								if ( $m == 0 ) {
									$itemValuesC = ' ( '.$insertid.', '.$assignItemC[$k][0].', "'.$assignItemC[$k][1].'", 1, "'.$assignItemC[$k][2].'", '.$assignItemC[$k][3].', "'.$assignItemC[$k][4].'", "'.$assignItemC[$k][5].'", "'.$assignItemC[$k][6].'", "'.$assignItemC[$k][7].'" ) ';
									$m++;
								} else {
									$order	=	$m + 1;
									$itemValuesC .= ', ( '.$insertid.', '.$assignItemC[$k][0].', "'.$assignItemC[$k][1].'", '.$order.', "'.$assignItemC[$k][2].'", '.$assignItemC[$k][3].', "'.$assignItemC[$k][4].'", "'.$assignItemC[$k][5].'", "'.$assignItemC[$k][6].'", "'.$assignItemC[$k][7].'" ) ';
									$m++;
								}
							}
							
							if ( $m > 0 ) {
								$query = 'DELETE FROM #__jseblod_cck_type_item_email WHERE typeid = '.$insertid;
								$this->_db->setQuery( $query );
								if ( ! $this->_db->query() ) {
									$this->setError( $this->_db->getErrorMsg() );
									return false;
								}
								$query = 'INSERT INTO #__jseblod_cck_type_item_email ( `typeid`, `itemid`, `client`, `ordering`, `contentdisplay`, `bool`, `helper`, `link`, `link_helper`, `acl` ) VALUES ' . $itemValuesC;
								$this->_db->setQuery( $query );
								if ( ! $this->_db->query() ) {
									$this->setError( $this->_db->getErrorMsg() );
									return false;
								}
							}
						}
					}
				//--
				} else {}
			}
		}
		//$ignored = ( $ignored ) ? '1||' . substr( $ignored, 0, -2 ) : true;
		
		if ( JFile::exists( $fileUnpack['packagefile'] ) ) {
			JFile::delete( $fileUnpack['packagefile'] );
		}
		if ( JFolder::exists( $fileUnpack['dir'] ) ) {
			JFolder::delete( $fileUnpack['dir'] );
		}
		
		return true; //return $ignored;
	}
	
	/**
	 * Import Xml Process Templates
	 **/
	function importXmlProcess_Templates( $file, $mode, $path, $content_type )
	{
		$res	=	null;
		$xml	=	new JSimpleXML;
		if ( ! $xml->loadFile( $file ) ) {
			return false;
		}
		if ( $xml->document->attributes( 'type' ) != 'jSeblod_Templates' ) {
			return false;
		}
		
		
		foreach ( $xml->document->jseblod_template as $template ) {
			$template_childs	=	get_object_vars( $template );
			if ( array_key_exists( 'title', $template_childs ) ) {
				$title			=	( $template->title[0]->data() ) ? $template->title[0]->data() : '';
			}
			if ( array_key_exists( 'name', $template_childs ) ) {
				$name			=	( $template->name[0]->data() ) ? $template->name[0]->data() : '';
			}
			if ( array_key_exists( 'category', $template_childs ) ) {
				$category		=	( $template->category[0]->data() ) ? $template->category[0]->data() : ( $content_type ? $content_type : 3 );
			}
			if ( array_key_exists( 'type', $template_childs ) ) {
				$t_type			=	( $template->type[0]->data() ) ? $template->type[0]->data() : 0;
			} else {
				$t_type			=	0;
			}
			if ( array_key_exists( 'mode', $template_childs ) ) {
				$t_mode			=	( $template->mode[0]->data() ) ? $template->mode[0]->data() : 0;
			} else {
				$t_mode			=	0;
			}
			if ( array_key_exists( 'description', $template_childs ) ) {
				$description	=	( $template->description[0]->data() ) ? htmlspecialchars(htmlspecialchars_decode($template->description[0]->data()), ENT_QUOTES) : '';
			}
			if ( array_key_exists( 'published', $template_childs ) ) {
				$published		=	( $template->published[0]->data() ) ? $template->published[0]->data() : 0;
			}
			
			if ( ! ( $title && $name ) ) {
				return false;
			}
			if ( $category != 3 ) {
				$query 	= 'SELECT s.id'
						. ' FROM #__jseblod_cck_templates_categories AS s'
						. ' WHERE s.name = "'.$category.'"'
						;
				$this->_db->setQuery( $query );
				$categoryid	=	$this->_db->loadResult();
				if ( ! $categoryid ) {
					$c_title		=	( $template->category[0]->attributes( 'title' ) ) ? $template->category[0]->attributes( 'title' ) : $category;
					$c_color		=	( $template->category[0]->attributes( 'color' ) ) ? $template->category[0]->attributes( 'color' ) : '';
					$c_introchar	=	( $template->category[0]->attributes( 'introchar' ) ) ? $template->category[0]->attributes( 'introchar' ) : '';
					$c_colorchar	=	( $template->category[0]->attributes( 'colorchar' ) ) ? $template->category[0]->attributes( 'colorchar' ) : '';
					$c_description	=	( $template->category[0]->attributes( 'description' ) ) ? htmlspecialchars(htmlspecialchars_decode($template->category[0]->attributes( 'description' )), ENT_QUOTES) : '';					
					//
					$c_parent		=	( $template->category[0]->attributes( 'parent' ) ) ? $template->category[0]->attributes( 'parent' ) : 'top';
					$c_parents		=	explode( '/', $c_parent );
					if ( sizeof( $c_parents ) ) {
						$t						=	0;
						$c_parentid				=	2;
						$c_parent_title			=	( $template->category[0]->attributes( 'parent_title' ) ) ? $template->category[0]->attributes( 'parent_title' ) : '';
						$c_parent_titles		=	explode( '/', $c_parent_title );
						$c_parent_color			=	( $template->category[0]->attributes( 'parent_color' ) ) ? $template->category[0]->attributes( 'parent_color' ) : '';
						$c_parent_colors		=	explode( '/', $c_parent_color );
						$c_parent_introchar		=	( $template->category[0]->attributes( 'parent_introchar' ) ) ? $template->category[0]->attributes( 'parent_introchar' ) : '';
						$c_parent_introchars	=	explode( '/', $c_parent_introchar );
						$c_parent_colorchar		=	( $template->category[0]->attributes( 'parent_colorchar' ) ) ? $template->category[0]->attributes( 'parent_colorchar' ) : '';
						$c_parent_colorchars	=	explode( '/', $c_parent_colorchar );
						foreach ( $c_parents as $c_parent ) {							
							$query 	= 'SELECT s.id'
								. ' FROM #__jseblod_cck_templates_categories AS s'
								. ' WHERE s.name = "'.$c_parent.'"'
								;
							$this->_db->setQuery( $query );
							$existing_parentid	=	$this->_db->loadResult();
							if ( ! $existing_parentid ) {
								$c_parent_title		=	( $c_parent_titles[$t] ) ? $c_parent_titles[$t] : $c_parent;
								$c_parent_color		=	( $c_parent_colors[$t] ) ? $c_parent_colors[$t] : '';
								$c_parent_introchar	=	( $c_parent_introchars[$t] ) ? $c_parent_introchars[$t] : '';
								$c_parent_colorchar	=	( $c_parent_colorchars[$t] ) ? $c_parent_colorchars[$t] : '';
								$c_limits			=	CCKjSeblodShare_Import::addCatintoTree( $category, 'templates', $c_parentid );
								$c_limit			=	explode( '||', $c_limits );
								$query = 'INSERT INTO #__jseblod_cck_templates_categories ( title, name, color, introchar, colorchar, lft, rgt, description, published )'
										.' VALUES ( "'.$c_parent_title.'", "'.$c_parent.'", "'.$c_parent_color.'", "'.$c_parent_introchar.'", "'.$c_parent_colorchar.'", '
													.  $c_limit[0].', '.$c_limit[1].', "", 1 )';
								$this->_db->setQuery( $query );
								$this->_db->query();
								$c_parentid	=	$this->_db->insertid();
							} else {
								$c_parentid	=	$existing_parentid;
							}
							$t++;
						}
					}
					//
					$c_limits		=	CCKjSeblodShare_Import::addCatintoTree( $category, 'templates', $c_parentid );
					$c_limit		=	explode( '||', $c_limits );
					$query = 'INSERT INTO #__jseblod_cck_templates_categories ( title, name, color, introchar, colorchar, lft, rgt, description, published )'
							.' VALUES ( "'.$c_title.'", "'.$category.'", "'.$c_color.'", "'.$c_introchar.'", "'.$c_colorchar.'", '.$c_limit[0].', '.$c_limit[1].', "'.$c_description.'", 1 )'
							;
					$this->_db->setQuery( $query );
					$this->_db->query();
					$categoryid	=	$this->_db->insertid();
					if ( ! $categoryid ) {
						$categoryid	=	3;
					}
				}
			}
			if ( ! $mode ) {
				$query	= ' INSERT IGNORE INTO #__jseblod_cck_templates ( title, name, category, type, mode, description, published )'
						. ' VALUES ( "'.$title.'", "'.$name.'", '.$categoryid.', '.$t_type.', '.$t_mode.', "'.$description.'", '.$published.' )'
						;
			} else {
				$query	= ' INSERT INTO #__jseblod_cck_templates ( title, name, category, type, mode, description, published )'
						. ' VALUES ( "'.$title.'", "'.$name.'", '.$categoryid.', '.$t_type.', '.$t_mode.', "'.$description.'", '.$published.' )'
						. ' ON DUPLICATE KEY UPDATE title = "'.$title.'", category = '.$categoryid.', type = '.$t_type.', mode = '.$t_mode.', description = "'.$description.'", published = '.$published
						;
			}
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			$insertid	=	$this->_db->insertid();
			if ( ! $insertid ) {
				$query 	= 'SELECT s.id'
						. ' FROM #__jseblod_cck_templates AS s'
						. ' WHERE s.name = "'.$name.'"'
						;
				$this->_db->setQuery( $query );
				$insertid	=	$this->_db->loadResult();
			}
			
			if ( JFolder::exists( $path.DS.$name ) ) {
				if ( ! $mode ) {
					if ( ! JFolder::exists( JPATH_SITE.DS.'templates'.DS.$name ) ) {
						if ( $path.DS.$name ) {	
							JFolder::copy( $path.DS.$name, JPATH_SITE.DS.'templates'.DS.$name );
						}
					}
				} else {
					if ( JFolder::exists( JPATH_SITE.DS.'templates'.DS.$name ) ) {
						JFolder::delete( JPATH_SITE.DS.'templates'.DS.$name );
					}	
					if ( $path.DS.$name ) {	
						JFolder::copy( $path.DS.$name, JPATH_SITE.DS.'templates'.DS.$name );
					}
				}
			}
		}
		
		return $insertid;
	}
	
	/**
	 * Import Xml Process Items
	 **/
	function importXmlProcess_Items( $file, $mode, $path, $content_type )
	{
		$xml	=	new JSimpleXML;
		if ( ! $xml->loadFile( $file ) ) {
			return false;
		}
		
		if ( $xml->document->attributes( 'type' ) != 'Content_Fields' ) {
			return false;
		}
		foreach ( $xml->document->content_field as $item ) {
			$item_childs		=	get_object_vars( $item );
			if ( array_key_exists( 'title', $item_childs ) ) {
				$title			=	( $item->title[0]->data() ) ? $item->title[0]->data() : '';
			}
			if ( array_key_exists( 'name', $item_childs ) ) {
				$name			=	( $item->name[0]->data() ) ? $item->name[0]->data() : '';
			}
			if ( array_key_exists( 'category', $item_childs ) ) {
				$category		=	( $item->category[0]->data() ) ? $item->category[0]->data() : ( $content_type ? $content_type : 3 );
			}
			if ( array_key_exists( 'type', $item_childs ) ) {
				$type			=	( $item->type[0]->data() ) ? $item->type[0]->data() : '';
			}
			if ( array_key_exists( 'description', $item_childs ) ) {
				$description	=	( $item->description[0]->data() ) ? htmlspecialchars(htmlspecialchars_decode($item->description[0]->data()), ENT_QUOTES) : '';
			}
			if ( array_key_exists( 'light', $item_childs ) ) {
				$light			=	( $item->light[0]->data() ) ? $item->light[0]->data() : 0;				//
			}
			if ( array_key_exists( 'label', $item_childs ) ) {
				$label			=	( $item->label[0]->data() ) ? $item->label[0]->data() : '';
			}
			if ( array_key_exists( 'selectlabel', $item_childs ) ) {
				$selectlabel	=	( $item->selectlabel[0]->data() ) ? $item->selectlabel[0]->data() : '';
			}
			if ( array_key_exists( 'display', $item_childs ) ) {
				$display		=	( $item->display[0]->data() ) ? $item->display[0]->data() : 0;
			}
			if ( array_key_exists( 'required', $item_childs ) ) {
				$required		=	( $item->required[0]->data() ) ? $item->required[0]->data() : 0;
			}
			if ( array_key_exists( 'validation', $item_childs ) ) {
				$validation		=	( $item->validation[0]->data() ) ? $item->validation[0]->data() : '';
			}
			if ( array_key_exists( 'defaultvalue', $item_childs ) ) {
				//$defaultvalue	=	( $item->defaultvalue[0]->data() ) ? $item->defaultvalue[0]->data() : '';
				$defaultvalue	=	( $item->defaultvalue[0]->data() ) ? htmlspecialchars(htmlspecialchars_decode($item->defaultvalue[0]->data()), ENT_QUOTES) : '';
			}
			if ( array_key_exists( 'options', $item_childs ) ) {
				//TODO: multi cat
				$options		=	( $item->options[0]->data() ) ? $item->options[0]->data() : '';
				if ( $type == 7 ) {
					$options			=	( $options ) ? $options : 'default-submission';
					$jc_title			=	( $item->options[0]->attributes( 'categorytitle' ) ) ? $item->options[0]->attributes( 'categorytitle' ) : 'Default Submission';
					$jc_sectiontitle	=	( $item->options[0]->attributes( 'sectiontitle' ) ) ? $item->options[0]->attributes( 'sectiontitle' ) : 'jSeblod CCK';
					$jc_sectionalias	=	( $item->options[0]->attributes( 'sectionalias' ) ) ? $item->options[0]->attributes( 'sectionalias' ) : 'jseblod-cck';
					$jc_query 	= 'SELECT s.id FROM #__categories AS s WHERE s.alias = "'.$options.'"';
					$this->_db->setQuery( $jc_query );
					$jc_catid	=	$this->_db->loadResult();
					if ( ! $jc_catid ) {
						$jc_query 	= 'SELECT s.id FROM #__sections AS s WHERE s.alias = "'.$jc_sectionalias.'"';
						$this->_db->setQuery( $jc_query );
						$jc_sectionid	=	$this->_db->loadResult();
						if ( ! $jc_sectionid ) {
							$jc_query = 'INSERT INTO #__sections ( title, alias, scope, published )'
										.' VALUES ( "'.$jc_sectiontitle.'", "'.$jc_sectionalias.'", "content", 1 )'
										;
							$this->_db->setQuery( $jc_query );
							$this->_db->query();				
							$js_sectionid	=	$this->_db->insertid();	
						}
						$jc_query = 'INSERT INTO #__categories ( parent_id, title, alias, section, published )'
								.' VALUES ( 0, "'.$jc_title.'", "'.$options.'", "'.$jc_sectionid.'", 1 )'
								;
						$this->_db->setQuery( $jc_query );
						$this->_db->query();				
						$js_catid	=	$this->_db->insertid();
					}
					$options	=	$jc_catid;
				}
			}
			if ( array_key_exists( 'maxlength', $item_childs ) ) {
				$maxlength		=	( $item->maxlength[0]->data() ) ? $item->maxlength[0]->data() : 0;
			}
			if ( array_key_exists( 'size', $item_childs ) ) {
				$size			=	( $item->size[0]->data() ) ? $item->size[0]->data() : 0;
			}
			if ( array_key_exists( 'cols', $item_childs ) ) {
				$cols			=	( $item->cols[0]->data() ) ? $item->cols[0]->data() : 0;
			}
			if ( array_key_exists( 'rows', $item_childs ) ) {
				$rows			=	( $item->rows[0]->data() ) ? $item->rows[0]->data() : 0;
			}
			if ( array_key_exists( 'ordering', $item_childs ) ) {
				$ordering		=	( $item->ordering[0]->data() ) ? $item->ordering[0]->data() : 0;
			}
			if ( array_key_exists( 'divider', $item_childs ) ) {
				$divider		=	( $item->divider[0]->data() ) ? $item->divider[0]->data() : '';
			}
			if ( array_key_exists( 'bool', $item_childs ) ) {
				$bool			=	( $item->bool[0]->data() ) ? $item->bool[0]->data() : 0;
			}
			if ( array_key_exists( 'extra', $item_childs ) ) {
				$extra			=	( $item->extra[0]->data() ) ? $item->extra[0]->data() : '';
			}
			if ( array_key_exists( 'location', $item_childs ) ) {
				$location		=	( $item->location[0]->data() ) ? $item->location[0]->data() : '';
				if ( $type == 25 ) {
					$location			=	( $location ) ? $location : 'default-submission';
					$jc_title			=	( $item->location[0]->attributes( 'categorytitle' ) ) ? $item->location[0]->attributes( 'categorytitle' ) : 'Default Submission';
					$jc_sectiontitle	=	( $item->location[0]->attributes( 'sectiontitle' ) ) ? $item->location[0]->attributes( 'sectiontitle' ) : 'jSeblod CCK';
					$jc_sectionalias	=	( $item->location[0]->attributes( 'sectionalias' ) ) ? $item->location[0]->attributes( 'sectionalias' ) : 'jseblod-cck';
					$jc_query 	= 'SELECT s.id FROM #__categories AS s WHERE s.alias = "'.$location.'"';
					$this->_db->setQuery( $jc_query );
					$jc_catid	=	$this->_db->loadResult();
					if ( ! $jc_catid ) {
						$jc_query 	= 'SELECT s.id FROM #__sections AS s WHERE s.alias = "'.$jc_sectionalias.'"';
						$this->_db->setQuery( $jc_query );
						$jc_sectionid	=	$this->_db->loadResult();
						if ( ! $jc_sectionid ) {
							$jc_query = 'INSERT INTO #__sections ( title, alias, scope, published )'
										.' VALUES ( "'.$jc_sectiontitle.'", "'.$jc_sectionalias.'", "content", 1 )'
										;
							$this->_db->setQuery( $jc_query );
							$this->_db->query();				
							$jc_sectionid	=	$this->_db->insertid();	
						}
						$jc_query = 'INSERT INTO #__categories ( parent_id, title, alias, section, published )'
								.' VALUES ( 0, "'.$jc_title.'", "'.$location.'", "'.$jc_sectionid.'", 1 )'
								;
						$this->_db->setQuery( $jc_query );
						$this->_db->query();				
						$jc_catid	=	$this->_db->insertid();
					}
					$location	=	$jc_catid;
				}
			}
			if ( array_key_exists( 'content', $item_childs ) ) {
				$content		=	( $item->content[0]->data() ) ? $item->content[0]->data() : '';
			}
			if ( array_key_exists( 'extended', $item_childs ) ) {
				$extended		=	( $item->extended[0]->data() ) ? $item->extended[0]->data() : '';
			}
			if ( array_key_exists( 'style', $item_childs ) ) {
				$style			=	( $item->style[0]->data() ) ? $item->style[0]->data() : '';
			}
			if ( array_key_exists( 'message', $item_childs ) ) {
				$message		=	( $item->message[0]->data() ) ? htmlspecialchars($item->message[0]->data()) : '';
			}
			if ( array_key_exists( 'message2', $item_childs ) ) {
				$message2		=	( $item->message2[0]->data() ) ? htmlspecialchars($item->message2[0]->data()) : '';
			}
			if ( array_key_exists( 'format', $item_childs ) ) {
				$format			=	( $item->format[0]->data() ) ? $item->format[0]->data() : '';
			}
			if ( array_key_exists( 'mailto', $item_childs ) ) {
				$mailto			=	( $item->mailto[0]->data() ) ? $item->mailto[0]->data() : '';
			}
			if ( array_key_exists( 'cc', $item_childs ) ) {
				$cc				=	( $item->cc[0]->data() ) ? $item->cc[0]->data() : '';
			}
			if ( array_key_exists( 'bcc', $item_childs ) ) {
				$bcc			=	( $item->bcc[0]->data() ) ? $item->bcc[0]->data() : '';
			}
			if ( array_key_exists( 'elemxtd', $item_childs ) ) {
				$elemxtd		=	( $item->elemxtd[0]->data() ) ? $item->elemxtd[0]->data() : '';
			}
			if ( array_key_exists( 'bool2', $item_childs ) ) {
				$bool2			=	( $item->bool2[0]->data() ) ? $item->bool2[0]->data() : 0;
			} else {
				$bool2			=	0;	
			}
			if ( array_key_exists( 'displayfield', $item_childs ) ) {
				$displayfield	=	( $item->displayfield[0]->data() ) ? $item->displayfield[0]->data() : 0;
			} else {
				$displayfield	=	0;	
			}
			if ( array_key_exists( 'displayvalue', $item_childs ) ) {
				$displayvalue	=	( $item->displayvalue[0]->data() ) ? $item->displayvalue[0]->data() : 0;
			} else {
				$displayvalue	=	0;	
			}
			if ( array_key_exists( 'width', $item_childs ) ) {
				$width			=	( $item->width[0]->data() ) ? $item->width[0]->data() : 0;
			} else {
				$width			=	0;	
			}
			if ( array_key_exists( 'height', $item_childs ) ) {
				$height			=	( $item->height[0]->data() ) ? $item->height[0]->data() : 0;
			} else {
				$height			=	0;	
			}
			if ( array_key_exists( 'codebefore', $item_childs ) ) {
				$codebefore		=	( $item->codebefore[0]->data() ) ? $item->codebefore[0]->data() : '';
			}
			if ( array_key_exists( 'codeafter', $item_childs ) ) {
				$codeafter		=	( $item->codeafter[0]->data() ) ? $item->codeafter[0]->data() : '';
			}
			if ( array_key_exists( 'importer', $item_childs ) ) {
				$importer		=	( $item->importer[0]->data() ) ? $item->importer[0]->data() : 0;
			} else {
				$importer		=	0;	
			}
			if ( array_key_exists( 'substitute', $item_childs ) ) {
				$substitute		=	( $item->substitute[0]->data() ) ? $item->substitute[0]->data() : 0;
			} else {
				$substitute		=	0;	
			}
			if ( array_key_exists( 'indexed', $item_childs ) ) {
			 	$indexed		=	( $item->indexed[0]->data() ) ? $item->indexed[0]->data() : 0;
			} else {
				$indexed		=	0;
			}
			if ( array_key_exists( 'indexedkey', $item_childs ) ) {
				$indexedkey		=	( $item->indexedkey[0]->data() ) ? $item->indexedkey[0]->data() : 0;
			} else {
				$indexedkey		=	0;
			}
			if ( array_key_exists( 'indexedxtd', $item_childs ) ) {
				$indexedxtd	=	( $item->indexedxtd[0]->data() ) ? $item->indexedxtd[0]->data() : '';
			}
			if ( array_key_exists( 'bool3', $item_childs ) ) {
				$bool3			=	( $item->bool3[0]->data() ) ? $item->bool3[0]->data() : 0;
			} else {
				$bool3			=	0;	
			}
			if ( array_key_exists( 'bool4', $item_childs ) ) {
				$bool4			=	( $item->bool4[0]->data() ) ? $item->bool4[0]->data() : 0;
			} else {
				$bool4			=	0;	
			}
			if ( array_key_exists( 'bool5', $item_childs ) ) {
				$bool5			=	( $item->bool5[0]->data() ) ? $item->bool5[0]->data() : 0;
			} else {
				$bool5			=	0;	
			}
			if ( array_key_exists( 'bool6', $item_childs ) ) {
				$bool6			=	( $item->bool6[0]->data() ) ? $item->bool6[0]->data() : 0;
			} else {
				$bool6			=	0;	
			}
			if ( array_key_exists( 'bool7', $item_childs ) ) {
				$bool7			=	( $item->bool7[0]->data() ) ? $item->bool7[0]->data() : 0;
			} else {
				$bool7			=	0;	
			}
			if ( array_key_exists( 'bool8', $item_childs ) ) {
				$bool8			=	( $item->bool8[0]->data() ) ? $item->bool8[0]->data() : 0;
			} else {
				$bool8			=	0;	
			}
			if ( array_key_exists( 'url', $item_childs ) ) {
				$url			=	( $item->url[0]->data() ) ? $item->url[0]->data() : '';
			}
			if ( array_key_exists( 'toadmin', $item_childs ) ) {
				$toadmin		=	( $item->toadmin[0]->data() ) ? $item->toadmin[0]->data() : '';
			}
			if ( array_key_exists( 'css', $item_childs ) ) {
				$css			=	( $item->css[0]->data() ) ? $item->css[0]->data() : '';
			}
			if ( array_key_exists( 'uacl', $item_childs ) ) {
				$uACL			=	( $item->uacl[0]->data() ) ? $item->uacl[0]->data() : 0;
			} else {
				$uACL			=	0;
			}
			if ( array_key_exists( 'gacl', $item_childs ) ) {
				$gACL			=	( $item->gacl[0]->data() ) ? $item->gacl[0]->data() : 0;
			} else {
				$gACL			=	0;
			}
			if ( array_key_exists( 'ueacl', $item_childs ) ) {
				$uEACL			=	( $item->ueacl[0]->data() ) ? $item->ueacl[0]->data() : 0;
			} else {
				$uEACL			=	0;
			}
			if ( array_key_exists( 'geacl', $item_childs ) ) {
				$gEACL			=	( $item->geacl[0]->data() ) ? $item->geacl[0]->data() : 0;
			} else {
				$gEACL			=	0;
			}
			if ( array_key_exists( 'beforesave', $item_childs ) ) {
				$beforesave		=	( $item->beforesave[0]->data() ) ? addslashes($item->beforesave[0]->data()) : '';
			}
			if ( array_key_exists( 'options2', $item_childs ) ) {
				$options2		=	( $item->options2[0]->data() ) ? $item->options2[0]->data() : '';
			}
			if ( array_key_exists( 'stylextd', $item_childs ) ) {
				$stylextd		=	( $item->stylextd[0]->data() ) ? $item->stylextd[0]->data() : '';
			}
			if ( array_key_exists( 'boolxtd', $item_childs ) ) {
				$boolxtd		=	( $item->boolxtd[0]->data() ) ? $item->boolxtd[0]->data() : 0;
			} else {
				$boolxtd		=	0;
			}
			
			if ( ! ( $title && $name ) ) {
				return false;
			}
			if ( $category != 3 ) {
				$query 	= 'SELECT s.id'
						. ' FROM #__jseblod_cck_items_categories AS s'
						. ' WHERE s.name = "'.$category.'"'
						;
				$this->_db->setQuery( $query );
				$categoryid	=	$this->_db->loadResult();
				if ( ! $categoryid ) {
					$c_title		=	( $item->category[0]->attributes( 'title' ) ) ? $item->category[0]->attributes( 'title' ) : $category;
					$c_color		=	( $item->category[0]->attributes( 'color' ) ) ? $item->category[0]->attributes( 'color' ) : '';
					$c_introchar	=	( $item->category[0]->attributes( 'introchar' ) ) ? $item->category[0]->attributes( 'introchar' ) : '';
					$c_colorchar	=	( $item->category[0]->attributes( 'colorchar' ) ) ? $item->category[0]->attributes( 'colorchar' ) : '';
					$c_display		=	( $item->category[0]->attributes( 'display' ) ) ? $item->category[0]->attributes( 'display' ) : 3;
					$c_description	=	( $item->category[0]->attributes( 'description' ) ) ? htmlspecialchars(htmlspecialchars_decode($item->category[0]->attributes( 'description' )), ENT_QUOTES) : '';
					//
					$c_parent		=	( $item->category[0]->attributes( 'parent' ) ) ? $item->category[0]->attributes( 'parent' ) : 'top';
					$c_parents		=	explode( '/', $c_parent );
					if ( sizeof( $c_parents ) ) {
						$t						=	0;
						$c_parentid				=	2;
						$c_parent_title			=	( $item->category[0]->attributes( 'parent_title' ) ) ? $item->category[0]->attributes( 'parent_title' ) : '';
						$c_parent_titles		=	explode( '/', $c_parent_title );
						$c_parent_color			=	( $item->category[0]->attributes( 'parent_color' ) ) ? $item->category[0]->attributes( 'parent_color' ) : '';
						$c_parent_colors		=	explode( '/', $c_parent_color );
						$c_parent_introchar		=	( $item->category[0]->attributes( 'parent_introchar' ) ) ? $item->category[0]->attributes( 'parent_introchar' ) : '';
						$c_parent_introchars	=	explode( '/', $c_parent_introchar );
						$c_parent_colorchar		=	( $item->category[0]->attributes( 'parent_colorchar' ) ) ? $item->category[0]->attributes( 'parent_colorchar' ) : '';
						$c_parent_colorchars	=	explode( '/', $c_parent_colorchar );
						$c_parent_display		=	( $item->category[0]->attributes( 'parent_display' ) ) ? $item->category[0]->attributes( 'parent_display' ) : '';
						$c_parent_displays		=	explode( '/', $c_parent_display );
						foreach ( $c_parents as $c_parent ) {
							$query 	= 'SELECT s.id'
								. ' FROM #__jseblod_cck_items_categories AS s'
								. ' WHERE s.name = "'.$c_parent.'"'
								;
							$this->_db->setQuery( $query );
							$existing_parentid	=	$this->_db->loadResult();
							if ( ! $existing_parentid ) {
								$c_parent_title		=	( $c_parent_titles[$t] ) ? $c_parent_titles[$t] : $c_parent;
								$c_parent_color		=	( $c_parent_colors[$t] ) ? $c_parent_colors[$t] : '';
								$c_parent_introchar	=	( $c_parent_introchars[$t] ) ? $c_parent_introchars[$t] : '';
								$c_parent_colorchar	=	( $c_parent_colorchars[$t] ) ? $c_parent_colorchars[$t] : '';
								$c_parent_display	=	( $c_parent_displays[$t] ) ? $c_parent_displays[$t] : 2;
								$c_limits			=	CCKjSeblodShare_Import::addCatintoTree( $category, 'items', $c_parentid );
								$c_limit			=	explode( '||', $c_limits );
								$query = 'INSERT INTO #__jseblod_cck_items_categories ( title, name, color, introchar, colorchar, lft, rgt, display, description, published )'
										.' VALUES ( "'.$c_parent_title.'", "'.$c_parent.'", "'.$c_parent_color.'", "'.$c_parent_introchar.'", "'.$c_parent_colorchar.'", '
													.  $c_limit[0].', '.$c_limit[1].', '.$c_parent_display.', "", 1 )';
								$this->_db->setQuery( $query );
								$this->_db->query();
								$c_parentid	=	$this->_db->insertid();
							} else {
								$c_parentid	=	$existing_parentid;
							}
							$t++;
						}
					}
					//
					$c_limits		=	CCKjSeblodShare_Import::addCatintoTree( $category, 'items', $c_parentid );
					$c_limit		=	explode( '||', $c_limits );
					$query = 'INSERT INTO #__jseblod_cck_items_categories ( title, name, color, introchar, colorchar, lft, rgt, display, description, published )'
							.' VALUES ( "'.$c_title.'", "'.$category.'", "'.$c_color.'", "'.$c_introchar.'", "'.$c_colorchar.'", '.$c_limit[0].', '.$c_limit[1].', '.$c_display.', "", 1 )'
							;
					$this->_db->setQuery( $query );
					$this->_db->query();
					$categoryid	=	$this->_db->insertid();
					if ( ! $categoryid ) {
						$categoryid	=	3;
					}
				}
			}
			if ( ! $mode ) {
				$query  = ' INSERT IGNORE INTO #__jseblod_cck_items ( title, name, category, type, description, light, label, selectlabel, display,'
						. ' required, validation, defaultvalue, options, maxlength, size, cols, rows, ordering, divider, bool, extra, location, content,'
						. ' extended, style, message, message2, format, mailto, cc, bcc, elemxtd, bool2, displayfield, displayvalue, width, height,'
						. ' codebefore, codeafter, importer, substitute, indexed, indexedkey, indexedxtd, bool3, bool4, bool5, bool6, bool7, bool8, url, toadmin, css, uACL, gACL, uEACL, gEACL, beforesave, options2, '
						. ' stylextd, boolxtd )'
						. ' VALUES ( "'.$title.'", "'.$name.'", '.$categoryid.', '.$type.', "'.$description.'", '.$light.', "'.$label.'", "'.$selectlabel.'", '.$display
						. ', '.$required.', "'.$validation.'", "'.$defaultvalue.'", "'.$options.'", '.$maxlength.', '.$size.', '.$cols.', '.$rows.', '.$ordering.', "'.$divider.'"'						
 						. ', '.$bool.', "'.$extra.'", "'.$location.'", "'.$content.'", "'.$extended.'", "'.$style.'", "'.$message.'", "'.$message2.'", "'.$format.'", "'.$mailto.'", "'.$cc.'", "'.$bcc.'"'
						. ', "'.$elemxtd.'", '.$bool2.', '.$displayfield.', '.$displayvalue.', '.$width.', '.$height
						. ', "'.$codebefore.'", "'.$codeafter.'", '.$importer.', '.$substitute.', '.$indexed.', '.$indexedkey.', "'.$indexedxtd.'", '.$bool3.', '.$bool4.', '.$bool5.', '.$bool6.', '.$bool7.', '.$bool8
						. ', "'.$url.'", "'.$toadmin.'", "'.$css.'", '.$uACL.', '.$gACL.', '.$uEACL.', '.$gEACL.', "'.$beforesave.'", "'.$options2.'", "'.$stylextd.'", '.$boolxtd
						.' )'
						;
			} else {
				$query = ' INSERT INTO #__jseblod_cck_items ( title, name, category, type, description, light, label, selectlabel, display, required, validation, defaultvalue, options'
						 . ', maxlength, size, cols, rows, ordering, divider, bool, extra, location, content, extended, style, message, message2, format, mailto, cc, bcc, elemxtd, bool2'
						 . ', displayfield, displayvalue, width, height, codebefore, codeafter, importer, substitute, indexed, indexedkey, indexedxtd, bool3, bool4, bool5, bool6, bool7, bool8'
						 . ', url, toadmin, css, uACL, gACL, uEACL, gEACL, beforesave, options2, stylextd, boolxtd'
						 . ' )'
						 . ' VALUES ( "'.$title.'", "'.$name.'", '.$categoryid.', '.$type.', "'.$description.'", '.$light.', "'.$label.'", "'.$selectlabel.'", '.$display
						 . ', '.$required.', "'.$validation.'", "'.$defaultvalue.'", "'.$options.'", '.$maxlength.', '.$size.', '.$cols.', '.$rows.', '.$ordering.', "'.$divider.'"'
 						 . ', '.$bool.', "'.$extra.'", "'.$location.'", "'.$content.'", "'.$extended.'", "'.$style.'", "'.$message.'", "'.$message2.'", "'.$format.'", "'.$mailto.'", "'.$cc.'", "'.$bcc.'"'
						 . ', "'.$elemxtd.'", '.$bool2.', '.$displayfield.', '.$displayvalue.', '.$width.', '.$height
						 . ', "'.$codebefore.'", "'.$codeafter.'", '.$importer.', '.$substitute.', '.$indexed.', '.$indexedkey.', "'.$indexedxtd.'", '.$bool3.', '.$bool4.', '.$bool5.', '.$bool6.', '.$bool7.', '.$bool8
						 . ', "'.$url.'", "'.$toadmin.'", "'.$css.'", '.$uACL.', '.$gACL.', '.$uEACL.', '.$gEACL.', "'.$beforesave.'", "'.$options2.'", "'.$stylextd.'", '.$boolxtd
						 . ' )'
			 			 . ' ON DUPLICATE KEY UPDATE title = "'.$title.'", category = '.$categoryid.', type = '.$type.', description = "'.$description.'", light = '.$light
						 . ', label = "'.$label.'", selectlabel = "'.$selectlabel.'", display = '.$display.', required = '.$required.', validation = "'.$validation.'"'
						 . ', defaultvalue = "'.$defaultvalue.'", options = "'.$options.'", maxlength = '.$maxlength.', size = '.$size.', cols = '.$cols.', rows = '.$rows
 						 . ', ordering = '.$ordering.', divider = "'.$divider.'", bool = '.$bool.', extra = "'.$extra.'", location = "'.$location.'", content = "'.$content.'"'
  						 . ', extended = "'.$extended.'", style = "'.$style.'", message = "'.$message.'", message2 = "'.$message2.'", format = "'.$format.'", mailto = "'.$mailto.'", cc = "'.$cc.'", bcc = "'.$bcc.'"'
   						 . ', elemxtd = "'.$elemxtd.'", bool2 = '.$bool2.', displayfield = '.$displayfield.', displayvalue = '.$displayvalue.', width = '.$width.', height = '.$height
  						 . ', codebefore = "'.$codebefore.'", codeafter = "'.$codeafter.'", importer = '.$importer.', substitute = '.$substitute.', indexed = '.$indexed.', indexedkey = '.$indexedkey.', indexedxtd = "'.$indexedxtd.'", bool3 = '.$bool3
						 . ', bool4 = '.$bool4.', bool5 = '.$bool5.', bool6 = '.$bool6.', bool7 = '.$bool7.', bool8 = '.$bool8
  						 . ', url = "'.$url.'", toadmin = "'.$toadmin.'", css = "'.$css.'", uACL = '.$uACL.', gACL = '.$gACL.', uEACL = '.$uEACL.', gEACL = '.$gEACL
  						 . ', beforesave = "'.$beforesave.'", options2 = "'.$options2.'", stylextd = "'.$stylextd.'", gACL = '.$boolxtd
						 ;
			}
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			$insertid	=	$this->_db->insertid();
			if ( ! $insertid ) {
				$query 	= 'SELECT s.id'
						. ' FROM #__jseblod_cck_items AS s'
						. ' WHERE s.name = "'.$name.'"'
						;
				$this->_db->setQuery( $query );
				$insertid	=	$this->_db->loadResult();
			}
		}
		
		return $insertid;
	}
	
	/**
	 * Add Category into Tree
	 **/
	function addCatintoTree( $c_name, $elem, $parentid )
	{
		$query	=	CCKjSeblodShare_Import::_buildQueryCategory( $elem, $parentid );
		$this->_db->setQuery( $query );
		$brothers	=	$this->_db->loadResultArray();
		$parent		=	array_shift( $brothers );
		$brothers[]	=	$c_name;
		sort( $brothers );
		$key	=	array_search( $c_name, $brothers );
		
		if ( $key == 0 ) {
			$query 	= 'SELECT lft FROM #__jseblod_cck_'.$elem.'_categories'
					. ' WHERE name = "'.$parent.'"'
					;
		} else {
			$bigbrother	=	$brothers[$key - 1];
			$query	= 'SELECT rgt FROM #__jseblod_cck_'.$elem.'_categories'
					. ' WHERE name = "'.$bigbrother.'"'
					;
		}
		$this->_db->setQuery( $query );
		$limit	=	$this->_db->loadResult();
		$query 	= 'UPDATE #__jseblod_cck_'.$elem.'_categories'
				. ' SET lft = CASE WHEN lft > '.$limit.' THEN lft + 2 ELSE lft END,'
				. ' rgt = CASE WHEN rgt >= '.$limit.' THEN rgt + 2 ELSE rgt END'
				. ' WHERE rgt > '.$limit
				;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		$lft	=	$limit + 1;
		$rgt	=	$limit + 2;
		
		return $lft.'||'.$rgt;
	}
	
	/**
	 * Return Database Query
	 **/
	function _buildQueryCategory( $elem, $parentid )
	{
		$query  = 'SELECT s.name, (COUNT(parent.name) - (sub_tree.depth + 1)) AS depth'
				. ' FROM #__jseblod_cck_'.$elem.'_categories AS s,'
				. ' #__jseblod_cck_'.$elem.'_categories AS parent,'
				. ' #__jseblod_cck_'.$elem.'_categories AS sub_parent,'
				. ' ('
		            . ' SELECT s.name, (COUNT(parent.name) - 1) AS depth'
		            . ' FROM #__jseblod_cck_'.$elem.'_categories AS s,'
		            . ' #__jseblod_cck_'.$elem.'_categories AS parent'
		            . ' WHERE s.lft BETWEEN parent.lft AND parent.rgt'
		            . ' AND s.id = '.$parentid
		            . ' GROUP BY s.name'
		            . ' ORDER BY s.lft'
					. ' ) AS sub_tree'
				. ' WHERE s.lft BETWEEN parent.lft AND parent.rgt'
				. ' AND s.lft BETWEEN sub_parent.lft AND sub_parent.rgt'
				. ' AND sub_parent.name = sub_tree.name'
				. ' GROUP BY s.name'
				. ' HAVING depth <= 1'
				. ' ORDER BY s.lft'
				;
      
		return $query;
	}
	
	function getResultFromDatabase( $query )
	{
		$db	=&	JFactory::getDBO();
		
		$db->setQuery( $query );
		$result	=	$db->loadResult();
		
		return $result;
	}
}
?>