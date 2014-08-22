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

$mainframe->registerEvent( 'onPrepareContent', 'plgContentCCKjSeblod' );

jimport( 'joomla.filesystem.file' );

function plgContentCCKjSeblod( &$row, &$params, $page = 0 ) {
	$plugin	=&	JPluginHelper::getPlugin( 'content', 'cckjseblod' );
	
	// Check Whether Article Got jSeblod Field
	if ( JString::strpos( $row->text, '/jseblod' ) === false ) {
		return true;
	}
	$pluginParams	=	new JParameter( $plugin->params );
	// Check Whether Plugin has been Unpublished
	if ( ! $pluginParams->get( 'enabled', 1 ) ) {
		return true;
	}

	$db		=&	JFactory::getDBO();
	$config =&	CCK::CORE_getConfig();
	if ( ! defined('_JTEXT_ON_LABEL') ) {
		define( "_JTEXT_ON_LABEL",	$config->jtext_on_label );
	}

	$regexContent	=	"#(.*?)".$config->opening."(.*?)".$config->closing."(.*?)".$config->opening."(/.*?)".$config->closing."#s";	
	preg_match_all( $regexContent, $row->text, $contentMatches );
	$contentCount	=	count( $contentMatches[2] );
	
	$topContent		=	'';
	$bottomContent	=	'';
  
 	if ( $contentCount ) {
		$template	=	null;
	  	$doRm		=	null;
		$uri		=	&JFactory::getURI();
		$part_url	=	$uri->getQuery();
		$full_url	=	'index.php?'.$uri->getQuery();		
		if ( $config->views_url || $config->views_menu || $config->views_category ) {
	 		$app		=	&JFactory::getApplication();
		  	$router 	=	$app->getRouter();
		  	$parsed_url	=	$router->parse( $uri );
			$uri->setQuery( $parsed_url );
		}
		
		if ( @$row->cckjseblod_location == 'module' ) {
			if ( ( strpos( $row->text, 'jseblodend::' ) === false ) || ( ( strpos( $row->text, 'jseblodend::' ) !== false ) && ! @$row->fulltext ) ) {
					$doRm	=	1;
			}
		} else {
			if ( array_key_exists( 'text', get_object_vars( $row )) && array_key_exists( 'introtext', get_object_vars( $row )) && array_key_exists( 'fulltext', get_object_vars( $row ))
					&& array_key_exists( 'catid', get_object_vars( $row )) && isset( $row->catid ) ) {
				if ( ( strpos( $row->text, 'jseblodend::' ) === false ) || ( ( strpos( $row->text, 'jseblodend::' ) !== false ) && ! @$row->fulltext ) ) {
						$doRm	=	1;
				}
			}
		}
		
		// ContentType
		if ( $contentMatches[3][0] ) {
   	 		 $contentType	=	$contentMatches[3][0];
   		}
				
		//M
		if ( @$row->jSeblod_template != "" && ! $template ) {
			$query		=	'SELECT name, mode FROM #__jseblod_cck_templates WHERE published = 1 AND name = "'.$row->jSeblod_template.'"';
			$db->setQuery( $query );
			$template	=	$db->loadObject();
		}
		
		//EU
		if ( $config->views_url_e && @$row->cckjseblod_location != 'module' ) {
			if ( ! $template ) {
				$query	= 'SELECT cc.name, cc.mode FROM #__jseblod_cck_template_url AS s'
						. ' LEFT JOIN #__jseblod_cck_templates AS cc ON cc.id = s.templateid'
						. ' WHERE s.exact = 1 AND ( s.url = "'.$part_url.'" OR s.url = "'.$full_url.'" ) AND cc.published = 1'
						. ' ORDER BY s.id DESC'
						;
				$db->setQuery( $query );
				$template	=	$db->loadObject();
			}
		}
		
		//U
		if ( $config->views_url && @$row->cckjseblod_location != 'module' && $parsed_url ) {
			if ( ! $template ) {
				$wheres2	=	array();
				if ( $parsed_url ) {
					foreach ( $parsed_url as $field => $value ) {
						if ( $value ) {					
							$wheres2[]	=	's.url RLIKE ".*'.$value.'.*"';
						}
					}
				}
				$where2	=	'(' . implode( ') OR (', $wheres2 ) . ')';
				$where	=	' ( '.$where2.' ) AND s.exact = 0';
				$query	= 'SELECT s.templateid, s.url, cc.name FROM #__jseblod_cck_template_url AS s'
						. ' LEFT JOIN #__jseblod_cck_templates AS cc ON cc.id = s.templateid'
						. ' WHERE ( '.$where.' ) AND cc.published = 1'
						. ' ORDER BY s.id DESC'
						;
				$db->setQuery( $query );
				$urlAssignments	=	$db->loadObjectList();
				$nu	=	count( $urlAssignments );
				if ( $nu ) {
					if ( $nu > 1 ) {
						for ( $u = 0; $u < $nu; $u++ ) {
							$itemVars	=	explode( '&', $urlAssignments[$u]->url );
							$urlAssignments[$u]->total	=	count( $itemVars );
							$urlAssignments[$u]->match	=	0;
							if ( sizeof( $itemVars ) ) {
								foreach ( $itemVars as $itemVar ) {
									$iVar	=	explode( '=', $itemVar );
									if ( @$parsed_url[$iVar[0]] == $iVar[1] ) {
										$urlAssignments[$u]->match++;
									}
								}
							}
						}
						$urlAssignmentSorted	=	new CCK_objSorter( $urlAssignments, 'match' ); 
						if ( $urlAssignmentSorted->sorted ) {
							$urlAssignments	=	$urlAssignmentSorted->sorted;
						}
						for ( $u = $nu - 1; $u >= 0; $u-- ) {
							if ( $urlAssignments[$u]->match == $urlAssignments[$u]->total ) {
								$templateid	=	$urlAssignments[$u]->templateid;
								break;
							}
						}
					} else {
						$itemVars	=	explode( '&', $urlAssignments[0]->url );
						$total		=	count( $itemVars );
						$match		=	0;
						if ( sizeof( $itemVars ) ) {
							foreach ( $itemVars as $itemVar ) {
								$iVar	=	explode( '=', $itemVar );
								if ( @$parsed_url[$iVar[0]] == $iVar[1] ) {
									$match++;
								}
							}
						}
						if ( $match == $total ) {
							$templateid	=	$urlAssignments[0]->templateid;
						}
					}
					if ( @$templateid ) {
						$query	=	'SELECT name, mode FROM #__jseblod_cck_templates WHERE published = 1 AND id = '.$templateid;
						$db->setQuery( $query );
						$template	=	$db->loadObject();
					}
				}
			}
		}
		
		//MI
		if ( $config->views_menu && @$row->cckjseblod_location != 'module' ) {
			if ( ! $template ) {
				if ( array_key_exists( 'Itemid', $parsed_url ) ) {
				$menuId = $parsed_url['Itemid'];
			}
	
			if ( @$menuId ) {
				$query	= 'SELECT cc.name, cc.mode FROM #__jseblod_cck_template_menu AS s'
						. ' LEFT JOIN #__jseblod_cck_templates AS cc ON cc.id = s.templateid'
						. ' WHERE s.menuid = 0 AND cc.published = 1';
				$db->setQuery( $query ); 
				$template	=	$db->loadObject();
				if ( ! $template ) {
					$query	= 'SELECT cc.name, cc.mode FROM #__jseblod_cck_template_menu AS s'
							. ' LEFT JOIN #__menu AS sc ON sc.id = s.menuid'
							. ' LEFT JOIN #__jseblod_cck_templates AS cc ON cc.id = s.templateid'
							. ' WHERE s.menuid='.$menuId.' AND cc.published = 1 AND sc.published = 1';
					$db->setQuery( $query ); 
					$template	=	$db->loadObject();
					}
				}
			}
		}
		
		//JC
		if ( $config->views_category ) {
			if ( @$parsed_url['option'] == 'com_content' && @$parsed_url['view'] == 'category' ) {
				if ( ! $template ) {
					if ( @$row->catid ) {
						$query	= 'SELECT cc.name, cc.mode FROM #__jseblod_cck_template_cat AS s'
								. ' LEFT JOIN #__categories AS sc ON sc.id = s.catid'
								. ' LEFT JOIN #__jseblod_cck_templates AS cc ON cc.id = s.templateid'
								. ' WHERE s.catid ='.$row->catid.' AND cc.published = 1 AND sc.published = 1';
						$db->setQuery( $query );
						$template	=	$db->loadObject();
					}
				}
			}
		}
		//*/
		//CTD
		if ( ! $template ) {
			if ( @$contentType ) {
				$query 	= 'SELECT cc.name, cc.mode FROM #__jseblod_cck_types AS s'
						. ' LEFT JOIN #__jseblod_cck_templates AS cc ON cc.id = s.contenttemplate'
						. ' WHERE s.name = "'.$contentType.'" AND s.published = 1 AND cc.published = 1';
			}
			$db->setQuery( $query );
			$template = $db->loadObject();
		}
		
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_content.php' );
		if ( ! @$template->name || ! JFile::exists( JPATH_SITE.DS.'templates'.DS.@$template->name.DS.'index.php' ) ) {
			// No Template - No Template Files
		} else {
			$databaseItems	=	null;
			foreach ( $contentMatches[2] as $key => $val) {
				$contentItemsValues[$val]	=	$contentMatches[3][$key];
			}

			if ( $template->mode == 1 ) {
				
				$auto = 1;
				$templateCommon[]	=	'content';
				if ( @$contentType ) {
					$query	= 'SELECT ccc.name AS typename, cc.*, s.contentdisplay AS typography, s.helper AS html, s.link'
							. ' FROM #__jseblod_cck_type_item_email AS s'
							. ' LEFT JOIN #__jseblod_cck_types AS sc ON sc.id = s.typeid'
							. ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
							. ' LEFT JOIN #__jseblod_cck_items_types AS ccc ON ccc.id = cc.type'
							. ' WHERE sc.name = "'.$contentType.'" AND sc.published = 1 AND s.client = "content"'
							. ' ORDER BY s.ordering asc'
							;
				}
				$db->setQuery( $query );			
				$databaseItems	=	$db->loadObjectList( 'name' );		
				plgContentCCKjSeblodPerform( $row, $config, $regexContent, $contentType, $template->name, $topContent, $bottomContent, $databaseItems, $contentItemsValues, $templateMatches, $templateCommon, $doRm, $auto );
				
			} else {
			
				$auto = 0;
				if ( $doRm == 1 && JFile::exists( JPATH_SITE.DS.'templates'.DS.$template->name.DS.'index2.php' ) ) {
					$buffer	=	JFile::read( JPATH_SITE.DS.'templates'.DS.$template->name.DS.'index2.php' );
				} else {
					$buffer	=	JFile::read( JPATH_SITE.DS.'templates'.DS.$template->name.DS.'index.php' );
				}
				// Common Items ( $this-> )
				$regexTemplateCommon	=	"#this->([a-zA-Z0-9]*)->([a-zA-Z0-9_]*)#s";
				preg_match_all( $regexTemplateCommon, $buffer, $templateMatchesCommon );
				$templateCommon	=	array_keys( array_flip( $templateMatchesCommon[1] ) );
				// Template Items ( $jSeblod-> )
				$regexTemplate	=	"#jSeblod->([a-zA-Z0-9_]*)\[?'?([a-zA-Z0-9_\$]*)'?]?\[?'?([a-zA-Z0-9_\$]*)'?]?\[?'?([a-zA-Z0-9_\$]*)'?]?(->([a-zA-Z0-9]*)){0,1}#s";
				preg_match_all( $regexTemplate, $buffer, $templateMatches );
				
				$templateCount	=	count( $templateMatches[1] );
				if ( $templateCount ) {
					$templateItems	=	implode ( '","', $templateMatches[1] );
					$templateItems	=	"\"".$templateItems."\"";
					$query	= 'SELECT ccc.name AS typename, cc.*, s.contentdisplay AS typography, s.helper AS html, s.link'
							. ' FROM #__jseblod_cck_type_item_email AS s'
							. ' LEFT JOIN #__jseblod_cck_types AS sc ON sc.id = s.typeid'
							. ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
							. ' LEFT JOIN #__jseblod_cck_items_types AS ccc ON ccc.id = cc.type'
							. ' WHERE sc.name = "'.$contentType.'" AND sc.published = 1 AND s.client = "content"'
							. ' ORDER BY s.ordering asc'
							;
					$db->setQuery( $query );
					
					$databaseItems	=	$db->loadObjectList( 'name' );
					plgContentCCKjSeblodPerform( $row, $config, $regexContent, $contentType, $template->name, $topContent, $bottomContent, $databaseItems, $contentItemsValues, $templateMatches, $templateCommon, $doRm, $auto );
				} else {
					if ( count($templateCommon ) ) {
						plgContentCCKjSeblodPerform($row, $config, $regexContent, $contentType, $template->name, $topContent, $bottomContent, null, $contentItemsValues, $templateMatches, $templateCommon, $doRm, $auto );
					}
				}
			
			}
			
		}
	}
	
	return true;
}

function plgContentCCKjSeblodPerform( &$row, $config, $regexContent, $contentType, $template,  $topContent, $bottomContent, $databaseItems, &$contentItemsValues, &$templateMatches, &$templateCommon, $doRm, $auto ) {
	$mosConfig_live		=& JURI::base();
	$mosConfig_absolute	= JPATH_ROOT;
	$db					=&	JFactory::getDBO();
	$lang				=&	JFactory::getLanguage();
	$lang->load( 'com_cckjseblod_more' );
	$user				=&	JFactory::getUser();
	$mainframe 			= JFactory::getApplication();
	
	$uID	=	( $user->id ) ? $user->id : 0;
	$uGID	=	( $user->gid ) ? $user->gid : 0;
	
	$random		=	rand( 1, 100000 );
	$cache 		=	false;
	$file 		=	'index_jseblod'.$random;
	require_once ( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' );
	
	JHTML::_( 'stylesheet', 'cck.css', 'components/com_cckjseblod/assets/css/' );
	if ( $doRm == 1 && JFile::exists( JPATH_SITE.DS.'templates'.DS.$template.DS.'index2.php' ) ) {
		$fileToCopy		=	JPATH_SITE.DS.'templates'.DS.$template.DS.'index2.php';		
	} else {
		$fileToCopy		=	JPATH_SITE.DS.'templates'.DS.$template.DS.'index.php';
	}
	$fileToRender	=	JPATH_SITE.DS.'templates'.DS.$template.DS.$file.'.php';
	if ( JFile::exists( $fileToCopy ) ) {
		JFile::copy( $fileToCopy, $fileToRender );
	}
	
	$params = array(
		'template' 	=> $template,
		'file'		=> $file.'.php',
		'directory'	=> JPATH_SITE.DS.'templates',
	);
	
	$doc	=&	JDocument::getInstance( 'html' );	
	
	if ( @$row->cckjseblod_location != 'external' ) {	//TODO: Upside! // Process System Test ?!
		if ( sizeof( $templateCommon ) ) {
			foreach ( $templateCommon as $item ) {
				switch ( $item ) {
					case 'content':
						if ( @$row->catid && @$row->sectionid ) {
							$categoryPlus	=	CCK_DB_Object( 'SELECT title, alias FROM #__categories WHERE id='.$row->catid.' AND section='.$row->sectionid );
							if ( ! @$row->category ) {
								$row->category	=	$categoryPlus->title;
							}
							if ( ! @$row->categoryalias ) {
								$row->categoryalias	=	$categoryPlus->alias;
							}
						}
						$doc->$item	=	$row;
						if ( @$row->alias && @$row->catid && @$row->category && @$row->sectionid ) {
							$doc->$item->art_link = JRoute::_( ContentHelperRoute::getArticleRoute( $row->id.":".$row->alias, $row->catid.":".$row->category, $row->sectionid ) );
						} else {
							$doc->$item->art_link = '';
						}
						if ( @$row->catid && @$row->categoryalias && @$row->sectionid ) {
							$doc->$item->cat_link = JRoute::_( ContentHelperRoute::getCategoryRoute( $row->catid.':'.$row->categoryalias, $row->sectionid ));
						} else {
							$doc->$item->cat_link = '';
						}
						if ( $user->id ) {
							if ( $user->id == @$row->created_by ) {
								$contentTypeId	=	CCKjSeblodItem_Content::getResultFromDatabase( 'SELECT id FROM #__jseblod_cck_types WHERE name = "'.$contentType.'"' );
								$doc->$item->editart_link  = JRoute::_( 'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$contentTypeId.'&cckid='.$row->id );
							}
						}
						break;
					case 'menu':
						$itemId		=	JRequest::getInt( 'Itemid' );
						$menu		=	CCKjSeblodItem_Content::getObjectFromDatabase( 'SELECT name AS title, link, browserNav AS target FROM #__menu WHERE id = '.(int)@$itemId );
						if ( $menu ) {
							$doc->menu->link	=	JRoute::_( $menu->link );
							$doc->menu->target	=	$menu->target;
							$doc->menu->title	=	$menu->title;
						}
						break;
					case 'user':
						$doc->$item	=&	JFactory::getUser();
						$doc->$item->password	=	null;
						break;
					default:
						break;
				}
			}
		}
	}
	
	$groups   		=	array();
	$keys			=	array();
	$js_cart_qty	=	'';
	$js_cart_fields	=	'';
	$cckItems		=	array();
	
  	if ( sizeof( $databaseItems ) ) {
  		foreach ( $databaseItems as $item ) {
			if ( array_key_exists( $item->name, $contentItemsValues ) || $item->typename == 'joomla_content' || $item->typename == 'joomla_user' || $item->typename == 'joomla_readmore' || $item->typename == 'ecommerce_cart' || $item->typename == 'ecommerce_cart_button' || $item->typename == 'ecommerce_price' || $item->typename == 'web_service' ) {
				$itemValue	=	( @$contentItemsValues[$item->name] != '' ) ? $contentItemsValues[$item->name] : null;
				$itemValue 	=	trim( $itemValue );
				$itemName	=	$item->name;
							
				// eCommerce :: Cart Attributes
				if ( $item->type == 51 || $item->type == 53 ) {
					require_once( 'administrator'.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_form.php' );
					require_once( 'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'jseblod_helper.php' );
					$item->acl					=	'';
					$item->editiondisplay		=	'';
					$item->submissiondisplay	=	'';
					$doc->$itemName				=	CCKjSeblodItem_Form::getData( $item, $itemValue, 'content', @$row->id, null, 0, $content, $rowU, 0, 0, @$contentItemsValues, null ); //CHECK IT
					if ( $item->type == 51 ) {
						if ( $item->bool3 == 1 ) {
							$js_cart_qty	=	'quantity';
						} else {
							if ( $doc->$itemName->form != '' ) {
								$js_cart_fields	.=	$doc->$itemName->extended.',';
							}
						}
					}
				} else {
					$doc->$itemName	=	CCKjSeblodItem_Content::getData( $item, $itemValue, $row, $templateMatches, $regexContent, $auto, $uID, $uGID, $contentItemsValues, 'content', $groups, $keys );
				}
				// eCommerce :: Cart Attributes
				
				if ( $auto && $doc->$itemName != null && !is_array( @$doc->$itemName ) && @$doc->$itemName->value == '' && $doc->$itemName->typename != 'panel_slider' && $doc->$itemName->typename != 'sub_panel_tab' && $doc->$itemName->typename != 'ecommerce_cart' && $doc->$itemName->typename != 'ecommerce_cart_button' ) {
            		$doc->$itemName = null;
           		}
				// * 4th dimension *
				// Link
				if ( @$item->link && @$item->link != '' && $doc->$itemName ) {
					if ( $item->link == 'article' ) {
						$doc->$itemName->link	=	@$doc->content->art_link;
					} else if ( is_numeric( $item->link ) ) {
						$doc->$itemName->link	=	'index.php?option=com_cckjseblod&view=search&layout=search&searchid='.$item->link.'&task=search&'.$itemName.'='.$itemValue;
					} else {
						$doc->$itemName->link	=	$item->link;
					}
				}
				// Typography
				if ( ( @$item->typography && @$item->typography != 'none' && @$item->html && @$doc->$itemName->value != '' && $doc->$itemName ) || $item->typename == 'ecommerce_cart_button' ) {
					// JText
					if ( strpos( $item->html, 'J(' ) !== false ) {
						$jtextSearch	=	'#J\((.*)\)#U';
						preg_match_all( $jtextSearch, $item->html, $jtextMatches );
						if ( sizeof( $jtextMatches[0] ) ) {
							foreach( $jtextMatches[0] as $jk => $jv ) {
								$item->html	=	str_replace( $jv, JText::_( $jtextMatches[1][$jk] ), $item->html );										
							}
						}
					}
					// Attributes
					$typoSearch		=	'#\*([a-zA-Z0-9_]*)\*#U';
					preg_match_all( $typoSearch, $item->html, $typoMatches );
					if ( sizeof ( $typoMatches[1] ) ) {
						foreach( $typoMatches[1] as $typo ) {
							$item->html	=	( trim(@$doc->$itemName->$typo) != '' ) ? str_replace( '*'.$typo.'*', $doc->$itemName->$typo, $item->html )
																					: str_replace( '*'.$typo.'*', '456', $item->html );
						}
					}
					$doc->$itemName->html	=	$item->html;
				}			
				// * 4th dimension * //end
				if ( ( @$doc->$itemName && ! ( $item->typename == 'joomla_content' || $item->typename == 'joomla_user' ) )
					|| ( @$doc->$itemName->value != '' && ( $item->typename == 'joomla_content' || $item->typename == 'joomla_user' || $item->typename == 'ecommerce_cart' || $item->typename == 'ecommerce_cart_button' ) ) ) {
					$cckItems[] =	$item->name;
				}
			}
  		}
  	}
		
	if ( $auto == 1 ) {
		$doc->cckitems	=	$cckItems;
	}
	$data		=	$doc->render( $cache, $params );
	$row->text	=	str_replace( $row->text, $data, $row->text );
	
	if ( $user->id ) {
		if ( $user->id == @$row->created_by ) {
			$contentTypeId	=	CCKjSeblodItem_Content::getResultFromDatabase( 'SELECT id FROM #__jseblod_cck_types WHERE name = "'.$contentType.'"' );
			$editart_link 	=	JRoute::_( 'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$contentTypeId.'&cckid='.$row->id );
		}
	}

	foreach( $doc as $key => $value ) {
		$doc->key	=	null;
		$doc->value =	null;
	}
	if ( JFile::exists( $fileToRender ) ) {
		JFile::delete( $fileToRender );
	}
	//$doc	=	null;
	// eCommerce :: Cart Attributes
	if ( $js_cart_qty || $js_cart_fields ) {
		$doc		=&	JFactory::getDocument();
		$js_cart_fields	=	( $js_cart_fields ) ? substr( $js_cart_fields, 0, -1 ) : '';
		$js			=	'  var cck_cart_qty = "'.$js_cart_qty.'";';
		$js			.=	'var cck_cart_fields = "'.$js_cart_fields.'";';
		$doc->addScriptDeclaration( $js );
	}
	// eCommerce :: Cart Attributes
}

?>