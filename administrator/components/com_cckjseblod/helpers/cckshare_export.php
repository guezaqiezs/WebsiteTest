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

/**
 * CCKjSeblod		Share_Export Class
 **/
class CCKjSeblodShare_Export
{
	/**
	 * Export Content Templates
	 **/
	function exportContent_Templates( $inCids, $fileName )
	{
		if ( ! $fileName ) {
			$dateNow 	=& JFactory::getDate();
			$fileName	= $dateNow->toFormat( '%Y_%m_%d' );
		}
		
		$config		=&	JFactory::getConfig();
		$tempFolder	=	$config->getValue( 'config.tmp_path' );
		$tmpdir 	=	uniqid('export_');
		$path 		= 	$tempFolder.DS.$tmpdir;
		
		CCKjSeblodShare_Export::exportXmlProcess_Templates( $inCids, $path );

		$pathArchive	=	$tempFolder.DS.$fileName.'.zip';
		$remove_path	=	$path;
		  
		// Delete existing archives by the same name
		if ( JFile::exists($pathArchive) ) {
				if ( !JFile::delete($pathArchive) ) {
				// error;
				return false;
			}
		}  
		
		//You can put many file to zip like $pathFile,$pathFile1,$pathFile2
		require_once( 'pclzip'.DS.'pclzip.lib.php' );
		$archive = new PclZip( $pathArchive );
		if ( $archive->create( $path, PCLZIP_OPT_REMOVE_PATH, $remove_path ) == 0 ) {
			return false; //die( 'Error : ' . $archive->errorInfo( true ) );
		}
		
		JFolder::delete( $path );
		
		return $archive->zipname;
	}
	
	/**
	 * Export Xml Process Templates
	 **/
	function exportXmlProcess_Templates( $inCids, $path ) {
		$query	= ' SELECT s.*, cc.title AS categorytitle, cc.name AS categoryname, cc.color AS categorycolor, cc.introchar AS categoryintrochar'
				. ', cc.colorchar AS categorycolorchar, cc.description AS categorydescription'
				. ' FROM #__jseblod_cck_templates AS s'
				. ' LEFT JOIN #__jseblod_cck_templates_categories AS cc ON cc.id = s.category'
				. ' WHERE s.id IN ( '.$inCids.' )'
				. ' ORDER BY s.id'
				;
		$this->_db->setQuery( $query );
		$templates = $this->_db->loadObjectList();
		
		foreach( $templates as $template ) {
			if ( ! JFile::exists ( $path.DS.'tmpl_'.$template->name.'.xml' ) ) {
				$xml			=	null;
				$buffer			=	null;
				$pathFile		=	null;
				$xml			=	new JSimpleXML();
				$xml->document	=	new JSimpleXMLElement( 'cckjseblod' );
				$xml->document->addAttribute( 'type', 'jSeblod_Templates' );
				// - Copyright
				$info_author	=	&$xml->document->addChild( 'author' );
				$info_author->setData( 'http://www.seblod.com' );
				$info_authorE	=	&$xml->document->addChild( 'authorEmail' );
				$info_authorE->setData( 'contact@seblod.com' );
				$info_authorU	=	&$xml->document->addChild( 'authorUrl' );
				$info_authorU->setData( 'http://www.seblod.com' );
				$info_copyright	=	&$xml->document->addChild( 'copyright' );
				$info_copyright->setData( 'Copyright (C) 2011 SEBLOD. All Rights Reserved.' );
				$info_license	=	&$xml->document->addChild( 'license' );
				$info_license->setData( 'GNU General Public License version 2 or later; see _LICENSE.php' );
				// - Copyright
				$content_template	=	&$xml->document->addChild( 'jseblod_template' );
				$title				=	&$content_template->addChild( 'title' );
				$title->setData( $template->title );
				$name				=	&$content_template->addChild( 'name' );
				$name->setData( $template->name );
				//
				if ( $template->category > 2 ) {
					$c_where	=	' WHERE ( s.lft BETWEEN parent.lft AND parent.rgt ) AND s.id != parent.id AND s.id ='.$template->category;
					$c_orderby	=	' ORDER BY parent.lft DESC';
					$c_query 	= 'SELECT parent.name, parent.title, parent.color, parent.introchar, parent.colorchar'
							. ' FROM #__jseblod_cck_templates_categories AS s, #__jseblod_cck_templates_categories AS parent'
							. $c_where
							. $c_orderby
							;
					$this->_db->setQuery( $c_query );
					$c_parents	=	$this->_db->loadObjectList();
					if ( sizeof( $c_parents ) ) {
						$t	=	0;
						foreach( $c_parents as $c_parent ) {
							if ( $c_parent->name != 'TOP' ) {
								$c_parent_tmp			=	$c_parent->name.'/'.$c_parent_tmp;
								$c_parent_title_tmp		=	$c_parent->title.'/'.$c_parent_title_tmp;
								$c_parent_color_tmp		=	$c_parent->color.'/'.$c_parent_color_tmp;
								$c_parent_introchar_tmp	=	$c_parent->introchar.'/'.$c_parent_introchar_tmp;
								$c_parent_colorchar_tmp	=	$c_parent->colorchar.'/'.$c_parent_colorchar_tmp;
							}
						}
					}
					$c_parent			=	( ! @$c_parent_tmp ) ? 'top' : substr( $c_parent_tmp, 0, -1 );
					$c_parent_title		=	( ! @$c_parent_title_tmp ) ? 'top' : substr( $c_parent_title_tmp, 0, -1 );
					$c_parent_color		=	( ! @$c_parent_color_tmp ) ? '' : substr( $c_parent_color_tmp, 0, -1 );
					$c_parent_introchar	=	( ! @$c_parent_introchar_tmp ) ? '' : substr( $c_parent_introchar_tmp, 0, -1 );
					$c_parent_colorchar	=	( ! @$c_parent_colorchar_tmp ) ? '' : substr( $c_parent_colorchar_tmp, 0, -1 );
				} else {
					$c_parent	=	'top';
				}
				//
				$category			=	&$content_template->addChild( 'category' );
				$category->setData( $template->categoryname );
				$category->addAttribute( 'title', $template->categorytitle );
				$category->addAttribute( 'color', $template->categorycolor );
				$category->addAttribute( 'introchar', $template->categoryintrochar );
				$category->addAttribute( 'colorchar', $template->categorycolorchar );
				$category->addAttribute( 'description', $template->categorydescription );
				$category->addAttribute( 'parent', $c_parent );
				$category->addAttribute( 'parent_title', $c_parent_title );
				$category->addAttribute( 'parent_color', $c_parent_color );
				$category->addAttribute( 'parent_introchar', $c_parent_introchar );
				$category->addAttribute( 'parent_colorchar', $c_parent_colorchar );
				//
				$type				=	&$content_template->addChild( 'type' );
				$type->setData( $template->type );
				$mode				=	&$content_template->addChild( 'mode' );
				$mode->setData( $template->mode );
				$description		=	&$content_template->addChild( 'description' );
				$description->setData( $template->description );
				$published			=	&$content_template->addChild( 'published' );
				$published->setData( $template->published );
				
				$buffer		=	'<?xml version="1.0" encoding="utf-8"?>';
				$buffer		.=	$xml->document->toString();
				$pathFile	=	$path.DS.'tmpl_'.$template->name.'.xml';
				JFile::write( $pathFile, $buffer );
				
				if ( JFolder::exists( JPATH_SITE.DS.'templates'.DS.$template->name ) && ! JFolder::exists( $path.DS.$template->name ) ) {
					JFolder::copy( JPATH_SITE.DS.'templates'.DS.$template->name, $path.DS.$template->name );
				}
			}
		}
		
		return true;
	}
	
	/**
	 * Export Content Types
	 **/
	function exportContent_Types( $inCids, $fileName, $mode, $bool )
	{
		if ( $bool == TRUE ) {
			if ( ! $fileName ) {
				$dateNow 	=& JFactory::getDate();
				$fileName	= $dateNow->toFormat( '%Y_%m_%d' );
			}
				
			$config		=&	JFactory::getConfig();
			$tempFolder	=	$config->getValue( 'config.tmp_path' );
			$tmpdir 	=	uniqid('export_');
			$path 		= 	$tempFolder.DS.$tmpdir;
		} else {
			$path		=	$fileName;
		}
		$query 	= 'SELECT s.*, sc.name AS admintemplatename, scc.name AS sitetemplatename, sccc.name AS contenttemplatename'
				. ', cc.title AS categorytitle, cc.name AS categoryname, cc.color AS categorycolor, cc.introchar AS categoryintrochar'
				. ', cc.colorchar AS categorycolorchar, cc.display AS categorydisplay, cc.description AS categorydescription'
				. ' FROM #__jseblod_cck_types AS s'
				. ' LEFT JOIN #__jseblod_cck_types_categories AS cc ON cc.id = s.category'
				. ' LEFT JOIN #__jseblod_cck_templates AS sc ON sc.id = s.admintemplate'
				. ' LEFT JOIN #__jseblod_cck_templates AS scc ON scc.id = s.sitetemplate'
				. ' LEFT JOIN #__jseblod_cck_templates AS sccc ON sccc.id = s.contenttemplate'
				. ' WHERE s.id IN ( '.$inCids.' )'
				. ' ORDER BY s.id'
				;
		$this->_db->setQuery( $query );
		$types = $this->_db->loadObjectList();
		
		if ( $mode == 3 || $mode == 2 ) {
			$query 	= 'SELECT s.itemid, s.typeid, s.client, cc.name AS itemname, s.typography, s.submissiondisplay, s.editiondisplay, s.value, s.helper, s.live, s.acl'
					. ' FROM #__jseblod_cck_type_item AS s'
					. ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
					. ' WHERE s.typeid IN ( '.$inCids.' )'
					. ' ORDER BY s.typeid, s.client ASC, s.ordering ASC'
					;
			$this->_db->setQuery( $query );
			$items = $this->_db->loadObjectList();
			$query 	= 'SELECT s.itemid, s.typeid, s.client, cc.name AS itemname, s.contentdisplay, s.bool, s.helper, s.link, s.link_helper, s.acl, s.access'
					. ' FROM #__jseblod_cck_type_item_email AS s'
					. ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
					. ' WHERE s.typeid IN ( '.$inCids.' )'
					. ' ORDER BY s.typeid, s.client ASC, s.ordering ASC'
					;
			$this->_db->setQuery( $query );
			$itemsC	= $this->_db->loadObjectList();
		}
		
		foreach ( $types as $type ) {
			$xml			=	null;
			$buffer			=	null;
			$pathFile		=	null;
			$xml			=	new JSimpleXML();
			$xml->document	=	new JSimpleXMLElement( 'cckjseblod' );
			$xml->document->addAttribute( 'type', 'Content_Types' );
			// - Copyright
			$info_author	=	&$xml->document->addChild( 'author' );
			$info_author->setData( 'http://www.seblod.com' );
			$info_authorE	=	&$xml->document->addChild( 'authorEmail' );
			$info_authorE->setData( 'contact@seblod.com' );
			$info_authorU	=	&$xml->document->addChild( 'authorUrl' );
			$info_authorU->setData( 'http://www.seblod.com' );
			$info_copyright	=	&$xml->document->addChild( 'copyright' );
			$info_copyright->setData( 'Copyright (C) 2011 SEBLOD. All Rights Reserved.' );
			$info_license	=	&$xml->document->addChild( 'license' );
			$info_license->setData( 'GNU General Public License version 2 or later; see _LICENSE.php' );
			// - Copyright
			$content_type	=	&$xml->document->addChild( 'content_type' );
			$title			=	&$content_type->addChild( 'title' );
			$title->setData( $type->title );
			$name			=	&$content_type->addChild( 'name' );
			$name->setData( $type->name );
			//
			if ( $type->category > 2 ) {
				$c_where	=	' WHERE ( s.lft BETWEEN parent.lft AND parent.rgt ) AND s.id != parent.id AND s.id ='.$type->category;
				$c_orderby	=	' ORDER BY parent.lft DESC';
				$c_query 	= 'SELECT parent.name, parent.title, parent.color, parent.introchar, parent.colorchar, parent.display'
						. ' FROM #__jseblod_cck_types_categories AS s, #__jseblod_cck_types_categories AS parent'
						. $c_where
						. $c_orderby
						;
				$this->_db->setQuery( $c_query );
				$c_parents	=	$this->_db->loadObjectList();
				if ( sizeof( $c_parents ) ) {
					$t	=	0;
					foreach( $c_parents as $c_parent ) {
						if ( $c_parent->name != 'TOP' ) {
							$c_parent_tmp			=	$c_parent->name.'/'.$c_parent_tmp;
							$c_parent_title_tmp		=	$c_parent->title.'/'.$c_parent_title_tmp;
							$c_parent_color_tmp		=	$c_parent->color.'/'.$c_parent_color_tmp;
							$c_parent_introchar_tmp	=	$c_parent->introchar.'/'.$c_parent_introchar_tmp;
							$c_parent_colorchar_tmp	=	$c_parent->colorchar.'/'.$c_parent_colorchar_tmp;
							$c_parent_display_tmp	=	$c_parent->display.'/'.$c_parent_display_tmp;
						}
					}
				}
				$c_parent	=	( ! @$c_parent_tmp ) ? 'top' : substr( $c_parent_tmp, 0, -1 );
				$c_parent_title		=	( ! @$c_parent_title_tmp ) ? 'top' : substr( $c_parent_title_tmp, 0, -1 );
				$c_parent_color		=	( ! @$c_parent_color_tmp ) ? '' : substr( $c_parent_color_tmp, 0, -1 );
				$c_parent_introchar	=	( ! @$c_parent_introchar_tmp ) ? '' : substr( $c_parent_introchar_tmp, 0, -1 );
				$c_parent_colorchar	=	( ! @$c_parent_colorchar_tmp ) ? '' : substr( $c_parent_colorchar_tmp, 0, -1 );
				$c_parent_display	=	( ! @$c_parent_display_tmp ) ? '2' : substr( $c_parent_display_tmp, 0, -1 );
			} else {
				$c_parent	=	'top';
			}
			//
			$category		=	&$content_type->addChild( 'category' );
			$category->setData( $type->categoryname );
			$category->addAttribute( 'title', $type->categorytitle );
			$category->addAttribute( 'color', $type->categorycolor );
			$category->addAttribute( 'introchar', $type->categoryintrochar );
			$category->addAttribute( 'colorchar', $type->categorycolorchar );
			$category->addAttribute( 'display', $type->categorydisplay );
			$category->addAttribute( 'description', $type->categorydescription );
			$category->addAttribute( 'parent', $c_parent );
			$category->addAttribute( 'parent_title', $c_parent_title );
			$category->addAttribute( 'parent_color', $c_parent_color );
			$category->addAttribute( 'parent_introchar', $c_parent_introchar );
			$category->addAttribute( 'parent_colorchar', $c_parent_colorchar );
			$category->addAttribute( 'parent_display', $c_parent_display );
			//
			$admintemplate	=	&$content_type->addChild( 'admintemplate' );
			$admintemplate->setData( $type->admintemplatename );
			$sitetemplate	=	&$content_type->addChild( 'sitetemplate' );
			$sitetemplate->setData( $type->sitetemplatename );
			$contenttemplate	=	&$content_type->addChild( 'contenttemplate' );
			if ( $mode == 3 || $mode == 1 ) {
				$contenttemplate->setData( $type->contenttemplatename );
			} else {
				$contenttemplate->setData( 0 );
			}
			$description	=	&$content_type->addChild( 'description' );
			$description->setData( $type->description );
			$published		=	&$content_type->addChild( 'published' );
			$published->setData( $type->published );
			
			if ( $mode == 3 || $mode == 1 ) {			
				if ( $type->admintemplate && $type->admintemplatename ) {
					CCKjSeblodShare_Export::exportXmlProcess_Templates( $type->admintemplate, $path );
				}
				if ( $type->sitetemplate && $type->sitetemplatename ) {
					CCKjSeblodShare_Export::exportXmlProcess_Templates( $type->sitetemplate, $path );
				}
				if ( $type->contenttemplate && $type->contenttemplatename ) {
					CCKjSeblodShare_Export::exportXmlProcess_Templates( $type->contenttemplate, $path );
				}
			}
			
			// Admin Form & Site Form
			if ( $mode == 3 || $mode == 2 ) {
				if ( sizeof( $items ) ) {
					$i = 1;
					$content_items	=	&$content_type->addChild( 'fields' );
					foreach ( $items as $item ) {
						if ( $item->typeid == $type->id ) {
							$elem	=	&$content_items->addChild( 'field'.$i );
							$elem->addAttribute( 'client', $item->client );
							//
							$elem->addAttribute( 'typography', $item->typography );
							$elem->addAttribute( 'submissiondisplay', $item->submissiondisplay );
							$elem->addAttribute( 'editiondisplay', $item->editiondisplay );
							$elem->addAttribute( 'value', $item->value );
							$elem->addAttribute( 'helper', $item->helper );
							$elem->addAttribute( 'live', $item->live );
							$elem->addAttribute( 'acl', $item->acl );
							//
							$elem->setData( $item->itemname );
							CCKjSeblodShare_Export::exportXmlProcess_Items( $item->itemid, $path );
							$i++;
						}
					}
				} else {
					$content_items	=	&$content_type->addChild( 'fields' );
					$elem	=	&$content_items->addChild( 'field' );
					$elem->setData( 0 );
				}
			} else {
				$content_items	=	&$content_type->addChild( 'fields' );
				$elem	=	&$content_items->addChild( 'field' );
				$elem->setData( 0 );
			}
			// Content & Email
			if ( $mode == 3 || $mode == 2 ) {
				if ( sizeof( $itemsC ) ) {
					$i = 1;
					$content_itemsC	=	&$content_type->addChild( 'fields_content' );
					foreach ( $itemsC as $itemC ) {
						if ( $itemC->typeid == $type->id ) {
							$elem	=	&$content_itemsC->addChild( 'field_content'.$i );
							$elem->addAttribute( 'client', $itemC->client );
							//
							$elem->addAttribute( 'contentdisplay', $itemC->contentdisplay );
							$elem->addAttribute( 'bool', $itemC->bool );
							$elem->addAttribute( 'helper', $itemC->helper );
							$elem->addAttribute( 'link', $itemC->link );
							$elem->addAttribute( 'link_helper', $itemC->link_helper );
							$elem->addAttribute( 'acl', $itemC->acl );
							$elem->addAttribute( 'access', $itemC->access );
							//
							$elem->setData( $itemC->itemname );
							CCKjSeblodShare_Export::exportXmlProcess_Items( $itemC->itemid, $path );
							$i++;
						}
					}
				} else {
					$content_itemsC	=	&$content_type->addChild( 'fields_content' );
					$elem	=	&$content_itemsC->addChild( 'field_content' );
					$elem->setData( 0 );
				}
			} else {
				$content_itemsC	=	&$content_type->addChild( 'fields_content' );
				$elem	=	&$content_itemsC->addChild( 'field_content' );
				$elem->setData( 0 );
			}
			//
			$buffer		=	'<?xml version="1.0" encoding="utf-8"?>';
			$buffer		.=	$xml->document->toString();
			$pathFile	=	$path.DS.'type_'.$type->name.'.xml';
			JFile::write( $pathFile, $buffer );
		}
 		if ( $bool == TRUE ) { 
			$pathArchive	=	$tempFolder.DS.$fileName.'.zip';
			$remove_path	=	$path;
			  
			// Delete existing archives by the same name
			if ( JFile::exists($pathArchive) ) {
					if ( !JFile::delete($pathArchive) ) {
					// error;
					return false;
				}
			}
			
			//You can put many file to zip like $pathFile,$pathFile1,$pathFile2
			require_once( 'pclzip'.DS.'pclzip.lib.php' );
			$archive = new PclZip( $pathArchive );
			if ( $archive->create( $path, PCLZIP_OPT_REMOVE_PATH, $remove_path ) == 0 ) {
				return false;	//die( 'Error : ' . $archive->errorInfo( true ) );
			}
			
			JFolder::delete( $path );
			
			return $archive->zipname;
		} else {
			return true;
		}
	}

	/**
	 * Export Search Types
	 **/
	function exportSearch_Types( $inCids, $fileName, $mode, $bool )
	{
		return true;
	}
	
	/**
	 * Export Content Items
	 **/
	function exportContent_Items( $inCids, $fileName )
	{
		if ( ! $fileName ) {
			$dateNow 	=& JFactory::getDate();
			$fileName	= $dateNow->toFormat( '%Y_%m_%d' );
		}
			
		$config		=&	JFactory::getConfig();
		$tempFolder	=	$config->getValue( 'config.tmp_path' );
		$tmpdir 	=	uniqid('export_');
		$path 		= 	$tempFolder.DS.$tmpdir;
		
		CCKjSeblodShare_Export::exportXmlProcess_Items( $inCids, $path );
		
		$pathArchive	=	$tempFolder.DS.$fileName.'.zip';
		$remove_path	=	$path;
		  
		// Delete existing archives by the same name
		if ( JFile::exists($pathArchive) ) {
				if ( !JFile::delete($pathArchive) ) {
				// error;
				return false;
			}
		}
		  
		//You can put many file to zip like $pathFile,$pathFile1,$pathFile2
		require_once( 'pclzip'.DS.'pclzip.lib.php' );
		$archive = new PclZip( $pathArchive );
		if ( $archive->create( $path, PCLZIP_OPT_REMOVE_PATH, $remove_path ) == 0 ) {
			return false;	//die( 'Error : ' . $archive->errorInfo( true ) );
		}
		
		JFolder::delete( $path );
		
		return $archive->zipname;
	}
	
	/**
	 * Export Xml Process Items
	 **/
	function exportXmlProcess_Items( $inCids, $path ) {
		$query = ' SELECT s.*, cc.title AS categorytitle, cc.name AS categoryname, cc.color AS categorycolor, cc.introchar AS categoryintrochar'
				. ', cc.colorchar AS categorycolorchar, cc.display AS categorydisplay, cc.description AS categorydescription'
				.' FROM #__jseblod_cck_items AS s'
				. ' LEFT JOIN #__jseblod_cck_items_categories AS cc ON cc.id = s.category'
				. ' WHERE s.id IN ( '.$inCids.' )'
				. ' ORDER BY s.id'
				;
		$this->_db->setQuery( $query );
		$items = $this->_db->loadObjectList();
				
		foreach( $items as $item ) {
			if ( ! JFile::exists ( $path.DS.'item_'.$item->name.'.xml' ) ) {
				$xml			=	null;
				$buffer			=	null;
				$pathFile		=	null;
				$xml			=	new JSimpleXML();
				$xml->document	=	new JSimpleXMLElement( 'cckjseblod' );
				$xml->document->addAttribute( 'type', 'Content_Fields' );
				// - Copyright
				$info_author	=	&$xml->document->addChild( 'author' );
				$info_author->setData( 'http://www.seblod.com' );
				$info_authorE	=	&$xml->document->addChild( 'authorEmail' );
				$info_authorE->setData( 'contact@seblod.com' );
				$info_authorU	=	&$xml->document->addChild( 'authorUrl' );
				$info_authorU->setData( 'http://www.seblod.com' );
				$info_copyright	=	&$xml->document->addChild( 'copyright' );
				$info_copyright->setData( 'Copyright (C) 2011 SEBLOD. All Rights Reserved.' );
				$info_license	=	&$xml->document->addChild( 'license' );
				$info_license->setData( 'GNU General Public License version 2 or later; see _LICENSE.php' );
				// - Copyright
				$content_item	=	&$xml->document->addChild( 'content_field' );
				$title			=	&$content_item->addChild( 'title' );
				$title->setData( $item->title );
				$name			=	&$content_item->addChild( 'name' );
				$name->setData( $item->name );
				//
				if ( $item->category > 2 ) {
					$c_where	=	' WHERE ( s.lft BETWEEN parent.lft AND parent.rgt ) AND s.id != parent.id AND s.id ='.$item->category;
					$c_orderby	=	' ORDER BY parent.lft DESC';
					$c_query 	= 'SELECT parent.name, parent.title, parent.color, parent.introchar, parent.colorchar, parent.display'
							. ' FROM #__jseblod_cck_items_categories AS s, #__jseblod_cck_items_categories AS parent'
							. $c_where
							. $c_orderby
							;
					$this->_db->setQuery( $c_query );
					$c_parents	=	$this->_db->loadObjectList();
					if ( sizeof( $c_parents ) ) {
						$t	=	0;
						foreach( $c_parents as $c_parent ) {
							if ( $c_parent->name != 'TOP' ) {
								$c_parent_tmp			=	$c_parent->name.'/'.$c_parent_tmp;
								$c_parent_title_tmp		=	$c_parent->title.'/'.$c_parent_title_tmp;
								$c_parent_color_tmp		=	$c_parent->color.'/'.$c_parent_color_tmp;
								$c_parent_introchar_tmp	=	$c_parent->introchar.'/'.$c_parent_introchar_tmp;
								$c_parent_colorchar_tmp	=	$c_parent->colorchar.'/'.$c_parent_colorchar_tmp;
								$c_parent_display_tmp	=	$c_parent->display.'/'.$c_parent_display_tmp;
							}
						}
					}
					$c_parent			=	( ! @$c_parent_tmp ) ? 'top' : substr( $c_parent_tmp, 0, -1 );
					$c_parent_title		=	( ! @$c_parent_title_tmp ) ? 'top' : substr( $c_parent_title_tmp, 0, -1 );
					$c_parent_color		=	( ! @$c_parent_color_tmp ) ? '' : substr( $c_parent_color_tmp, 0, -1 );
					$c_parent_introchar	=	( ! @$c_parent_introchar_tmp ) ? '' : substr( $c_parent_introchar_tmp, 0, -1 );
					$c_parent_colorchar	=	( ! @$c_parent_colorchar_tmp ) ? '' : substr( $c_parent_colorchar_tmp, 0, -1 );
					$c_parent_display	=	( ! @$c_parent_display_tmp ) ? '2' : substr( $c_parent_display_tmp, 0, -1 );
				} else {
					$c_parent	=	'top';
				}
				//
				$category		=	&$content_item->addChild( 'category' );
				$category->setData( $item->categoryname );
				$category->addAttribute( 'title', $item->categorytitle );
				$category->addAttribute( 'color', $item->categorycolor );
				$category->addAttribute( 'introchar', $item->categoryintrochar );
				$category->addAttribute( 'colorchar', $item->categorycolorchar );
				$category->addAttribute( 'display', $item->categorydisplay );
				$category->addAttribute( 'description', $item->categorydescription );
				$category->addAttribute( 'parent', $c_parent );
				$category->addAttribute( 'parent_title', $c_parent_title );
				$category->addAttribute( 'parent_color', $c_parent_color );
				$category->addAttribute( 'parent_introchar', $c_parent_introchar );
				$category->addAttribute( 'parent_colorchar', $c_parent_colorchar );
				$category->addAttribute( 'parent_display', $c_parent_display );
				//
				$type			=	&$content_item->addChild( 'type' );
				$type->setData( $item->type );
				$description	=	&$content_item->addChild( 'description' );
				$description->setData( $item->description );
				$light			=	&$content_item->addChild( 'light' );
				$light->setData( $item->light );			
				$label			=	&$content_item->addChild( 'label' );
				$label->setData( $item->label );
				$selectlabel		=	&$content_item->addChild( 'selectlabel' );
				$selectlabel->setData( $item->selectlabel );
				$display		=	&$content_item->addChild( 'display' );
				$display->setData( $item->display );
				$required		=	&$content_item->addChild( 'required' );
				$required->setData( $item->required );
				$validation		=	&$content_item->addChild( 'validation' );
				$validation->setData( $item->validation );
				$defaultvalue	=	&$content_item->addChild( 'defaultvalue' );
				$defaultvalue->setData( $item->defaultvalue );
				//
				$options		=	&$content_item->addChild( 'options' );
				if ( $item->type == 7 && $item->options ) {
					$jc_where	= ' WHERE s.id ='.$item->options;
					$jc_query 	= 'SELECT s.title, s.alias, cc.title AS sectiontitle, cc.alias AS sectionalias'
								. ' FROM #__categories AS s'
								. ' LEFT JOIN #__sections AS cc ON cc.id = s.section'
								. $jc_where
								;
					$this->_db->setQuery( $jc_query );
					$jc_cat		=	$this->_db->loadObject();
					$options->setData( $jc_cat->alias );
					$options->addAttribute( 'categorytitle', $jc_cat->title );
					$options->addAttribute( 'sectiontitle', $jc_cat->sectiontitle );
					$options->addAttribute( 'sectionalias', $jc_cat->sectionalias );
				} else {
					$options->setData( $item->options );
				}
				//
				$maxlength		=	&$content_item->addChild( 'maxlength' );
				$maxlength->setData( $item->maxlength );
				$size			=	&$content_item->addChild( 'size' );
				$size->setData( $item->size );
				$cols			=	&$content_item->addChild( 'cols' );
				$cols->setData( $item->cols );
				$rows			=	&$content_item->addChild( 'rows' );
				$rows->setData( $item->rows );
				$ordering		=	&$content_item->addChild( 'ordering' );
				$ordering->setData( $item->ordering );
				$divider		=	&$content_item->addChild( 'divider' );
				$divider->setData( $item->divider );	
				$bool			=	&$content_item->addChild( 'bool' );
				$bool->setData( $item->bool );
				$extra			=	&$content_item->addChild( 'extra' );
				$extra->setData( $item->extra );
				//
				$location		=	&$content_item->addChild( 'location' );
				if ( $item->type == 25 && $item->location ) {
					$jc_where	= ' WHERE s.id ='.$item->location;
					$jc_query 	= 'SELECT s.title, s.alias, cc.title AS sectiontitle, cc.alias AS sectionalias'
								. ' FROM #__categories AS s'
								. ' LEFT JOIN #__sections AS cc ON cc.id = s.section'
								. $jc_where
								;
					$this->_db->setQuery( $jc_query );
					$jc_cat		=	$this->_db->loadObject();
					$location->setData( $jc_cat->alias );
					$location->addAttribute( 'categorytitle', $jc_cat->title );
					$location->addAttribute( 'sectiontitle', $jc_cat->sectiontitle );
					$location->addAttribute( 'sectionalias', $jc_cat->sectionalias );
				} else {
					$location->setData( $item->location );
				}
				//
				$content		=	&$content_item->addChild( 'content' );
				$content->setData( $item->content );
				$extended		=	&$content_item->addChild( 'extended' );
				$extended->setData( $item->extended );
				$style			=	&$content_item->addChild( 'style' );
				$style->setData( $item->style );
				$message		=	&$content_item->addChild( 'message' );
				$message->setData( $item->message );
				$message2		=	&$content_item->addChild( 'message2' );
				$message2->setData( $item->message2 );
				$format			=	&$content_item->addChild( 'format' );
				$format->setData( $item->format );
				$mailto			=	&$content_item->addChild( 'mailto' );
				$mailto->setData( $item->mailto );
				$cc				=	&$content_item->addChild( 'cc' );
				$cc->setData( $item->cc );
				$bcc			=	&$content_item->addChild( 'bcc' );
				$bcc->setData( $item->bcc );
				$elemxtd		=	&$content_item->addChild( 'elemxtd' );
				$elemxtd->setData( $item->elemxtd );
				$bool2			=	&$content_item->addChild( 'bool2' );
				$bool2->setData( $item->bool2 );
				$displayfield	=	&$content_item->addChild( 'displayfield' );
				$displayfield->setData( $item->displayfield );
				$displayvalue	=	&$content_item->addChild( 'displayvalue' );
				$displayvalue->setData( $item->displayvalue );
				$width			=	&$content_item->addChild( 'width' );
				$width->setData( $item->width );
				$height			=	&$content_item->addChild( 'height' );
				$height->setData( $item->height );
				$codebefore		=	&$content_item->addChild( 'codebefore' );
				$codebefore->setData( $item->codebefore );
				$codeafter		=	&$content_item->addChild( 'codeafter' );
				$codeafter->setData( $item->codeafter );
				$importer		=	&$content_item->addChild( 'importer' );
				$importer->setData( $item->importer );
				$substitute		=	&$content_item->addChild( 'substitute' );
				$substitute->setData( $item->substitute );
				$indexed		=	&$content_item->addChild( 'indexed' );
				$indexed->setData( $item->indexed );
				$indexedkey		=	&$content_item->addChild( 'indexedkey' );
				$indexedkey->setData( $item->indexedkey );
				$indexedxtd	=	&$content_item->addChild( 'indexedxtd' );
				$indexedxtd->setData( $item->indexedxtd );
				$bool3		=	&$content_item->addChild( 'bool3' );
				$bool3->setData( $item->bool3 );
				$bool4		=	&$content_item->addChild( 'bool4' );
				$bool4->setData( $item->bool4 );
				$bool5		=	&$content_item->addChild( 'bool5' );
				$bool5->setData( $item->bool5 );
				$bool6		=	&$content_item->addChild( 'bool6' );
				$bool6->setData( $item->bool6 );
				$bool7		=	&$content_item->addChild( 'bool7' );
				$bool7->setData( $item->bool7 );
				$bool8		=	&$content_item->addChild( 'bool8' );
				$bool8->setData( $item->bool8 );
				$url		=	&$content_item->addChild( 'url' );
				$url->setData( $item->url );
				$toadmin	=	&$content_item->addChild( 'toadmin' );
				$toadmin->setData( $item->toadmin );
				$css		=	&$content_item->addChild( 'css' );
				$css->setData( $item->css );				
				$uACL		=	&$content_item->addChild( 'uacl' );
				$uACL->setData( $item->uACL );
				$gACL		=	&$content_item->addChild( 'gacl' );
				$gACL->setData( $item->gACL );
				$uACL		=	&$content_item->addChild( 'ueacl' );
				$uACL->setData( $item->uEACL );
				$gEACL		=	&$content_item->addChild( 'geacl' );
				$gEACL->setData( $item->gEACL );
				$beforesave	=	&$content_item->addChild( 'beforesave' );
				$beforesave->setData( $item->beforesave );
				$options2	=	&$content_item->addChild( 'options2' );
				$options2->setData( $item->options2 );
				$stylextd	=	&$content_item->addChild( 'stylextd' );
				$stylextd->setData( $item->stylextd );
				$boolxtd	=	&$content_item->addChild( 'boolxtd' );
				$boolxtd->setData( $item->boolxtd );
				
				$buffer		=	'<?xml version="1.0" encoding="utf-8"?>';
				$buffer		.=	$xml->document->toString();
				$pathFile	=	$path.DS.'field_'.$item->name.'.xml';
				JFile::write( $pathFile, $buffer );
			}
		}
		if ( $item->elemxtd == 'item' && $item->extended ) {
			$query = ' SELECT s.id'
					.' FROM #__jseblod_cck_items AS s'
					. ' WHERE s.name = "'.$item->extended.'"'
					;
			$this->_db->setQuery( $query );
			$itemId = $this->_db->loadResult();
			if ( $itemId && $itemId != $item->id ) {
				CCKjSeblodShare_Export::exportXmlProcess_Items( $itemId, $path );
			}
		}
		
		return true;
	}
	
	/**
	 * Add Into Pack
	 **/
	function addIntoPack( $cids, $type, $mode )
	{
		$packElems	=	null;
		
		for( $i = 0, $j = 0, $n = count( $cids ); $i < $n; $i++ ) {
			if ( $j == 0 ) {
				$packElems	=	' ( '.$cids[$i].', "'.$type.'", '.$mode.' ) ';
				$j++;
			} else {
				$packElems	.=	', ( '.$cids[$i].', "'.$type.'", '.$mode.' ) ';
			}
		}
		
		if ( $j > 0 ) {
			$packElems .=';';
			$query = 'INSERT IGNORE INTO #__jseblod_cck_packs ( `elemid`, `type`, `mode` ) VALUES ' . $packElems;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}		
		
		return true;
	}
	
	/**
	 * Export Content Pack
	 **/
	function exportContent_Pack( $packElems, $fileName )
	{
		if ( ! $fileName ) {
			$dateNow 	=& JFactory::getDate();
			$fileName	= $dateNow->toFormat( '%Y_%m_%d' );
		}
			
		$config		=&	JFactory::getConfig();
		$tempFolder	=	$config->getValue( 'config.tmp_path' );
		$tmpdir 	=	uniqid('export_');
		$path 		= 	$tempFolder.DS.$tmpdir;
		
		//
		if ( sizeof( $packElems ) ) {
  		foreach ( $packElems AS $elem ) {
  			if ( $elem->type == 'field' ) {
  				CCKjSeblodShare_Export::exportXmlProcess_Items( $elem->elemid, $path );
  			} else if ( $elem->type == 'tmpl' ) {
  				CCKjSeblodShare_Export::exportXmlProcess_Templates( $elem->elemid, $path );
  			} else if ( $elem->type == 'type' ) {
  				CCKjSeblodShare_Export::exportContent_Types( $elem->elemid, $path, $elem->mode, FALSE );
  			} else {}
  		}
		}
		//
		
		$pathArchive	=	$tempFolder.DS.$fileName.'.zip';
		$remove_path	=	$path;
		  
		// Delete existing archives by the same name
		if ( JFile::exists($pathArchive) ) {
				if ( !JFile::delete($pathArchive) ) {
				// error;
				return false;
			}
		}
		  
		//You can put many file to zip like $pathFile,$pathFile1,$pathFile2
		require_once( 'pclzip'.DS.'pclzip.lib.php' );
		$archive = new PclZip( $pathArchive );
		if ( $archive->create( $path, PCLZIP_OPT_REMOVE_PATH, $remove_path ) == 0 ) {
			return false;	//die( 'Error : ' . $archive->errorInfo( true ) );
		}
		
		JFolder::delete( $path );
		
		return $archive->zipname;
	}
}
?>
