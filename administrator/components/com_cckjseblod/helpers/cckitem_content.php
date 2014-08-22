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
 * CCKjSeblod		Item_Content Class
 **/
class CCKjSeblodItem_Content
{
	/**
	 * Get Result From Database
	 **/
	function getResultFromDatabase( $query )
	{
		$db	=&	JFactory::getDBO();
		
		$db->setQuery( $query );
		$result	=	$db->loadResult();
		
		return $result;
	}
	
	/**
	 * Get Object From Database
	 **/
	function getObjectFromDatabase( $query )
	{
		$db	=&	JFactory::getDBO();
		
		$db->setQuery( $query );
		$object	=	$db->loadObject();

		return $object;
	}
	
	/**
	 * Get ObjectList From Database
	 **/
	function getObjectListFromDatabase( $query )
	{
		$db	=&	JFactory::getDBO();
		
		$db->setQuery( $query );
		$object	=	$db->loadObjectList();

		return $object;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getContentItem( $itemName )
	{
		$db	=&	JFactory::getDBO();
		
		$where	=	' WHERE s.name = "'.$itemName.'"';
		
		$query	= 'SELECT cc.name AS typename, s.*, sc.title AS categorytitle'
				. ' FROM #__jseblod_cck_items AS s'
				. ' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
				. ' LEFT JOIN #__jseblod_cck_items_categories AS sc ON sc.id = s.category'
				. $where
				;
		$db->setQuery( $query );
		$item	=	$db->loadObject();
		
		return $item;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getContentItemById( $item )
	{
		$db	=&	JFactory::getDBO();
		
		$where	=	' WHERE s.id = '.$item;
		
		$query	= 'SELECT cc.name AS typename, s.*'
				. ' FROM #__jseblod_cck_items AS s'
				. ' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
				. $where
				;
		$db->setQuery( $query );
		$item	=	$db->loadObject();
		
		return $item;
	}
	
	/**
	 * Get External Article Data
	 **/
	function getExternalArticleData( $row )
	{
		$i	=	0;
		$dispatcher	=&	JDispatcher::getInstance();
		JPluginHelper::importPlugin( 'content' );
		$limitstart	=	JRequest::getVar( 'limitstart', 0, '', 'int' );
		
		$rows[$i]				=&	$row;
		$rows[$i]->text			=	$rows[$i]->introtext.$rows[$i]->fulltext;
		$rows[$i]->parameters	=	new JParameter( @$rows[$i]->attribs );
		$rows[$i]->event		=	new stdClass ();
		$rows[$i]->cckjseblod_location	=	'external';
		$results	=	$dispatcher->trigger( 'onPrepareContent', array ( &$rows[$i], & $rows[$i]->parameters, $limitstart ) );
		$results	=	$dispatcher->trigger( 'onAfterDisplayTitle', array ( $rows[$i], & $rows[$i]->parameters, $limitstart ) );
		$rows[$i]->event->afterDisplayTitle = trim( implode( "\n", $results ) );
		$results	=	$dispatcher->trigger( 'onBeforeDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, $limitstart ) );
		$rows[$i]->event->beforeDisplayContent = trim( implode( "\n", $results ) );
		$results	=	$dispatcher->trigger( 'onAfterDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, $limitstart ) );
		$rows[$i]->event->afterDisplayContent = trim( implode( "\n", $results ) );
		
		//$text	=	$row->introtext.$row->fulltext;
		$text	=	$rows[$i]->text;
		
		return $text;
	}

	/**
     * Format Bytes
     **/
	function formatBytes( $bytes, $precision = 2 ) { 
		$units = array('B', 'KB', 'MB', 'GB', 'TB'); 
	   
		$bytes = max($bytes, 0); 
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		$pow = min($pow, count($units) - 1); 
	   
		$bytes /= pow(1024, $pow); 
	   
		return round($bytes, $precision) . ' ' . $units[$pow]; 
	}
	
	/**
     * Get Option Text
     **/
	function getCartAttributeOptions( $value, $options, $separator = ',' )
	{
		if ( $options ) {
			if ( strpos( $options, '=' ) !== false ) {
				$optionsArray	=	explode( '||', $options );
				if ( sizeof( $optionsArray  ) ) {
					$opts	=	'';									
					foreach( $optionsArray as $elem ) {
						$opt	=	explode( '=', $elem );
						if ( strpos( $separator.$value.$separator, $separator.$opt[1].$separator ) !== false ) {
							$opts	.=	$opt[0].'='.$opt[1].'||';
						}
					}
					if ( $opts != '' ) {
						$opts	=	substr( $opts, 0, -2 );
					}
				}
				$options	=	$opts;
			} else {
				$options	=	str_replace( $separator, '||', $value );
			}
		}
		
		return $options;
	}
	
	/**
     * Get Download Hits
     **/
	function getDownloadHits( $id, $item, $group = '', $gx = 0 )
	{
		$db	=&	JFactory::getDBO();
		
		$gx		=	( $gx == -1 ) ? 0 : $gx;
		$query	= 'SELECT s.hits'
				. ' FROM #__jseblod_cck_downloads AS s'
				. ' WHERE s.item = "'.$item.'" AND s.groupname = "'.$group.'" AND s.gx = '.$gx.' AND s.contentid = '.$id
				;
		$db->setQuery( $query );
		$hits	=	$db->loadResult();
		
		return ( $hits ) ? $hits : 0;
	}
	
	/**
     * Get Option Text
     **/
	function getOptionText( $value, $options, $multiple = 0, $separator = '' )
	{
		$opts	=	explode( '||', $options );
		$text	=	'';
		
		if ( $multiple ) {
			$values		=	explode( $separator, $value );
		} else {
			$values		=	array();
			$values[0]	=	$value;
			$separator	=	'';
		}
		
		foreach ( $values as $value ) {
			if ( $value != '' ) {
				if ( sizeof( $opts ) ) {
					foreach ( $opts as $opt ) {
						if ( strpos( '='.$opt.'||', '='.$value.'||' ) !== false ) {
							$texts	=	explode( '=', $opt );
							$text	.=	$texts[0].$separator;
							break;
						}
					}
				}
			}
		}
		if ( $separator ) {
			$text	=	substr( $text, 0, - strlen( $separator ) );
		}
		
		return $text;
	}
	
	/**
	 * Get Items
	 **/
	function getItemsGroup( $contentType, $client, $exclusion, $prename, $cck = false )
	{
		$db	=&	JFactory::getDBO();		
		
		$where 	=	' WHERE cc.client = "'.$client.'" AND ccc.name = "'.$contentType.'"';
		$where	.=	' AND s.type != 25';

		$orderby	=	' ORDER BY cc.ordering ASC';
		
		$query	= ' SELECT DISTINCT s.*, sc.name AS typename, cc.client, cc.contentdisplay AS typography, cc.helper AS html, cc.link'
				. ' FROM #__jseblod_cck_items AS s '
				. ' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
				. ' LEFT JOIN #__jseblod_cck_type_item_email AS cc ON cc.itemid = s.id'
				. ' LEFT JOIN #__jseblod_cck_types AS ccc ON ccc.id = cc.typeid'
				. $where
				. $orderby
				;
		
		$db->setQuery( $query );
		$items	=	( $prename ) ? $db->loadObjectList( 'name' ) : $db->loadObjectList();
		
		if ( ! sizeof( $items ) ) {
			$items = array();
			return $items;
		}
		
		return $items;
	}
	
	/**
     * Get Data Class II
     **/	
	function getDataII( &$item, $itemValue, &$row, $parent, $k = -1 ) {
		$rowUser	=&	JFactory::getUser();
		
		$data			=	$item;
		$data->value	=	'';

		switch ( $item->typename ) {
  				//case 'alias':
					// 1ST-2ND CLASS NOT 3RD!
				  	//break;
  				//case 'alias_custom':
					// 1ST-2ND CLASS NOT 3RD!
				  	//break;
				case 'file':
					$data->value	=	$itemValue;
					// download + downloaded + filesize
					if ( $data->value != '' ) {
						$groupx	=	'';
						if ( $k > -1 ) {
							$groupx	.=	'&group='.$parent;
							$groupx	.=	'&gx='.$k;
						}
						if ( ! @$row->id ) {
							$data->download		=	'index.php?option=com_cckjseblod&task=download&file='.$data->value;
						} else {
							$data->download		=	'index.php?option=com_cckjseblod&task=download&file='.$item->name.$groupx.'&id='.$row->id;
							$data->downloaded	=	CCKjSeblodItem_Content::getDownloadHits( $row->id, $item->name, $parent, $k );
						}
						//
						$data->filesize	=	( JFile::exists( $data->value ) ) ? CCKjSeblodItem_Content::formatBytes( filesize( $data->value ) ) : CCKjSeblodItem_Content::formatBytes( 0 );						
					}
					break;
				case 'folder':
					$data->value	=	$itemValue;
					break;
				//case 'media':
					//$data->value	=	$itemValue;
					//break;
				case 'upload_image':
					$data->value	=	$itemValue;
					// thumb1 + thumb2 + thumb3 + thumb4 + thumb5 + filesize
					if ( $data->value != '' ) {
						$folder			=	substr( $data->value, 0, strrpos( $data->value, '/' ) ).'/';
						$thumb1			=	$folder.'_thumb1'.'/'.substr( strrchr( $data->value, '/' ), 1 );
						$data->thumb1	=	( JFile::exists( $thumb1 ) ) ? $thumb1 : null;
						$thumb2			=	$folder.'_thumb2'.'/'.substr( strrchr( $data->value, '/' ), 1 );
						$data->thumb2	=	( JFile::exists( $thumb2 ) ) ? $thumb2 : null;
						$thumb3			=	$folder.'_thumb3'.'/'.substr( strrchr( $data->value, '/' ), 1 );
						$data->thumb3	=	( JFile::exists( $thumb3 ) ) ? $thumb3 : null;
						$thumb4			=	$folder.'_thumb4'.'/'.substr( strrchr( $data->value, '/' ), 1 );
						$data->thumb4	=	( JFile::exists( $thumb4 ) ) ? $thumb4 : null;
						$thumb5			=	$folder.'_thumb5'.'/'.substr( strrchr( $data->value, '/' ), 1 );
						$data->thumb5	=	( JFile::exists( $thumb5 ) ) ? $thumb5 : null;
						//
						$data->filesize	=	( JFile::exists( $data->value ) ) ? CCKjSeblodItem_Content::formatBytes( filesize( $data->value ) ) : CCKjSeblodItem_Content::formatBytes( 0 );
					}
					break;
				case 'upload_simple':
					$data->value	=	$itemValue;
					// download + downloaded + filesize
					if ( $data->value != '' ) {
						$groupx	=	'';
						if ( $k > -1 ) {
							$groupx	.=	'&group='.$parent;
							$groupx	.=	'&gx='.$k;
						}
						if ( ! @$row->id ) {
							$data->download		=	'index.php?option=com_cckjseblod&task=download&file='.$data->value;
						} else {
							$data->download		=	'index.php?option=com_cckjseblod&task=download&file='.$item->name.$groupx.'&id='.$row->id;
							$data->downloaded	=	CCKjSeblodItem_Content::getDownloadHits( $row->id, $item->name, $parent, $k );
						}
						//
						$data->filesize	=	( JFile::exists( $data->value ) ) ? CCKjSeblodItem_Content::formatBytes( filesize( $data->value ) ) : CCKjSeblodItem_Content::formatBytes( 0 );
					}
					break;
				//case 'form_action':
					//$data->value	=	'';
					//break;
				//case 'captcha_image':
					//$data->value	=	'';
					//break;
				case 'email':
					$data->value	=	( $item->format ) ? str_replace( '@', $item->format, $itemValue ) : $itemValue;
					break;
				//case 'form_save':
					//$data->value	=	'';
					//break;
				//case 'button_free':
					//$data->value	=	'';
					//break;
				//case 'button_reset':
					//$data->value	=	'';
					//break;
				//case 'button_submit':
					//$data->value	=	'';
					//break;
				case 'checkbox':
					$data->value	=	$itemValue;
					// text
					if ( $data->value != '' ) {
						$data->text	=	CCKjSeblodItem_Content::getOptionText( $data->value, $item->options, 1, $item->divider );
					}
					break;
				case 'hidden':
					$data->value	=	( $itemValue ) ? $itemValue : $item->defaultvalue;
					break;
				//case 'password':
					//$data->value	=	'';
				 	//break;  
				case 'radio':
					$data->value	=	$itemValue;
					// text
					if ( $data->value != '' ) {
						$data->text		=	CCKjSeblodItem_Content::getOptionText( $data->value, $item->options );
					}
					break;
				case 'text':
					$data->value	=	$itemValue;
					break;
				case 'calendar':
					$data->value	=	$itemValue;
					break;
				case 'color_picker':
					$data->value	=	$itemValue;
					break;
				case 'select_dynamic':
					$data->value	=	$itemValue;
					// text
					if ( $data->value != '' ) {
						$request	=	explode( '||', $item->options );
						if ( $request && is_array( $request ) ) {
							$query		=	'SELECT '.$request[1].' FROM '.$request[0].' WHERE '.$request[2].' = "'.$data->value.'"';
							$data->text	=	CCK_DB_Result( $query );
						}
					}
					break;
				case 'select_multiple':
					$data->value	=	$itemValue;
					// text
					if ( $data->value != '' ) {
						$data->text	=	CCKjSeblodItem_Content::getOptionText( $data->value, $item->options, 1, $item->divider );
					}
					break;
				case 'select_numeric':
					$data->value	=	$itemValue;
					break;
				case 'select_simple':
					$data->value	=	$itemValue;
					// text
					if ( $data->value != '' ) {
						$data->text	=	CCKjSeblodItem_Content::getOptionText( $data->value, $item->options );
					}
					break;
				case 'textarea':
					$data->value	=	$itemValue;
					break;
				case 'wysiwyg_editor':
					$data->value	=	$itemValue;
					break;
				case 'ecommerce_cart':
					$data->value	=	$itemValue;
					break;
				case 'ecommerce_cart_button':
					$data->value	=	'';
					break;
				case 'ecommerce_price':
					$data->value	=	'';
					break;
				case 'web_service':
					$data->value	=	'';
					break;
				//case 'free_code':
					//$data->value	=	'';
					//break;
				case 'free_text':
					$data->value	=	htmlspecialchars_decode($item->defaultvalue);
					break;
				//case 'field_x':
					// 1ST-2ND CLASS NOT 3RD!
					//break;
				//case 'content_type':
					// 1ST-2ND CLASS NOT 3RD!
					//break;
				//case 'panel_slider':
					//$data->value	=	'';
					//break;
				//case 'sub_panel_tab':
					//$data->value	=	'';
					//break;
				//case 'joomla_content':
					// 1ST-2ND CLASS NOT 3RD!
					//break;	
				//case 'joomla_menu':
					//$data->value	=	'';
					//break;
				case 'joomla_readmore':
					$read_more		=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->defaultvalue ) : $item->defaultvalue;
					$art_link		=	JRoute::_( ContentHelperRoute::getArticleRoute( $row->id.":".$row->alias, $row->catid.":".$row->category, $row->sectionid ) );
					$data->value	=	'<a href="'.$art_link.'">'.$read_more.'</a>';
					break;
				//case 'joomla_user':
					// 1ST-2ND CLASS NOT 3RD!
					//break;				
				case 'joomla_module':
					$data->value	=	'<jdoc:include type="modules" name="'.$item->defaultvalue.'" style="'.$item->style.'" />';
					break;
				case 'joomla_plugin_button':
					$data->value	=	$itemValue;
					break;
				case 'joomla_plugin_content':
					$data->value	=	$itemValue;
					break;
				case 'query_url':
					$urlField	=	$item->location;
					$urlId		=	JRequest::getvar( $urlField, '', 'get' );
					if ( $urlField && $urlId ) {
						$request		=	explode( "||", $item->options );
						$query 			=	'SELECT '.$request[1].' FROM '.$request[0].' WHERE '.$request[2].' = '.$urlId;
						$data->value	=	CCKjSeblodItem_Content::getResultFromDatabase( $query );
					}
					break;
				case 'query_user':
					if ( $userId = $rowUser->get( 'id' ) ) {
						$request		=	explode( "||", $item->options );
						$query 			=	'SELECT '.$request[1].' FROM '.$request[0].' WHERE '.$request[2].' = '.$userId;
						$data->value	=	CCKjSeblodItem_Content::getResultFromDatabase( $query );
					}
					break;
				case 'external_article':
					$data->value	=	$itemValue;
					break;
				//case 'external_subcategories':
					// 1ST-2ND CLASS NOT 3RD!
					//break;
				default:
					break;
			}
			
		return $data;
	}
	
	/**
     * Get Data Class I
     **/	
	function getDataI( &$item, $itemValue, &$row, $valueOnly, &$templateMatches, $regexContent, $bool, $tpl_type, $keys, $plus, $parent = null, $k = 0 ) {
		$rowUser	=&	JFactory::getUser();
		
		$data	=	null;
		
		switch ( $item->typename ) {
			case 'external_article':
				if ( ! $itemValue ) {
					if ( $bool == true ) {
						$item->label	=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
						$data			=	$item;
						return $data;
					}
					return null;
				}
				//Get ArticleId
				if ( $item->bool4 && $item->indexedxtd != '' ) {
					$articleId	=	( $tpl_type == 'list' && @$keys[$item->indexedxtd][$itemValue]->id ) ? $keys[$item->indexedxtd][$itemValue]->id
															: CCK::KEY_getId( $item->indexedxtd, $itemValue );
				} else {
					$articleId	=	$itemValue;
				}
				if ( ! $articleId ) {
					if ( $bool == true ) {
						$item->label	=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
						$data			=	$item;
						return $data;
					}
					return null;
				}
				if ( $plus == -2 ) {
					$index1 = 1;
					$index2 = 4;					
				} else if ( $plus == -1 ) {
					$index1 = 1;
					$index2 = 3;
				} else {
					$index1 = 1 + $plus;
					$index2 = 2 + $plus;
				}
				$search = ( $parent ) ? $parent : $item->name;
				//
				JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
				if ( $bool == true ) {
					if ( $tpl_type == 'cart' ) {
						$display		=	$item->bool7;
						$displayField	=	$item->options2;
						if ( $display == 2 ) {
							$options2		=	explode( '||', $item->options2 );
							$displayField	=	$options2[2];
						}
					} else if ( $tpl_type == 'list' ) {
						$display		=	$item->bool6;
						$displayField	=	$item->options2;
						if ( $display == 2 ) {
							$options2		=	explode( '||', $item->options2 );
							$displayField	=	$options2[1];
						}
					} else {
						$display		=	$item->bool5;
						if ( $display == 2 ) {
							$options2		=	explode( '||', $item->options2 );
							$displayField	=	$options2[0];
						}
					}
					// Load Row		
					if ( !( $display == -1 || $display == -2 ) ) {
						$rowExt	=&	JTable::getInstance( 'content', 'JTable' );
						$rowExt->load( $articleId );
					}
					switch( $display ) {
						case -2:
							$item->value	=	$itemValue;
							break;
						case -1:
							$item->value	=	$articleId;
							break;
						case 1:
							require_once ( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' );
							$categoryAlias	=	CCKjSeblodItem_Content::getResultFromDatabase( 'SELECT alias FROM #__categories WHERE id = '.$rowExt->catid );
							$linkExt		=	JRoute::_(ContentHelperRoute::getArticleRoute($rowExt->id.":".$rowExt->alias, $rowExt->catid.":".$categoryAlias, $rowExt->sectionid));
							$item->value	=	'<a href="'.$linkExt.'">'.$rowExt->title.'</a>';
							break;
						case 2:
							$item->value	=	CCK_GET_ValueFromText( $rowExt->introtext.$rowExt->fulltext, $displayField );
							break;
						case 3:
							$item->value	=	$rowExt->title;
							break;
						case 0:
						default:
							$item->value	=	CCKjSeblodItem_Content::getExternalArticleData( $rowExt );	
							break;
					}
					$item->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
					$item->external		=	$itemValue;
					$item->external_id	=	$articleId;
					$data				=	$item;
				} else {
					// Load Row
					$rowExt	=&	JTable::getInstance( 'content', 'JTable' );
					$rowExt->load( $articleId );
					//Get ArticleFields from TemplateMatches
					$ids = array_keys( $templateMatches[1], $search );
					
					for ( $l = 0, $fields = array(), $nl = count( $ids ); $l < $nl; $l++ ) {
						if ( $templateMatches[$index2][$ids[$l]] ) {
							$fields[$l] = $templateMatches[$index2][$ids[$l]];
						}
					}
					
					if ( ! empty( $fields ) ) {
						$articleFields = array_keys( array_flip( $fields ) );
						preg_match_all( $regexContent, $rowExt->introtext, $articleMatches );
						foreach ( $articleFields as $val ) {
							if ( $val == 'art_link' || $val == 'artlink' ) {
								$itemObj		=	new stdClass();
								$categoryAlias	=	CCKjSeblodItem_Content::getResultFromDatabase( 'SELECT alias FROM #__categories WHERE id = '.$rowExt->catid );
								$itemObj->value	=	JRoute::_(ContentHelperRoute::getArticleRoute($rowExt->id.":".$rowExt->alias, $rowExt->catid.":".$categoryAlias, $rowExt->sectionid));
								$data[$val]		=	$itemObj;
							} else if ( array_key_exists( $val, get_object_vars( $row ) ) ) {
								$itemObj		=	new stdClass();
								$itemObj->value	=	@$rowExt->$val;
								$data[$val]		=	$itemObj;
							} else {
								if  ( ( $aKey = array_search( $val, $articleMatches[2] ) ) !== false ) {
									$external			=	CCKjSeblodItem_Content::getContentItem( $val );
									$data[$val]			=	CCKjSeblodItem_Content::getDataII( $external, trim( $articleMatches[3][$aKey] ), $row, $parent, $k );
									$data[$val]->label	=	( $external->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $external->label ) : $external->label ) : $external->title;
								}
							}
						}
					}
				}
				break;
			case 'alias':
				$extended	=	CCKjSeblodItem_Content::getContentItem( $item->extended );
				$data	 	=	clone CCKjSeblodItem_Content::getDataII( $extended, $itemValue, $row, $parent, $k );
				break;
			//case 'alias_custom':
				//break;
			default:
				$data	=	clone CCKjSeblodItem_Content::getDataII( $item, $itemValue, $row, $parent, $k );
				break;
		}
		
		return $data;
	}
	
	function getData( &$item, $itemValue, &$row, &$templateMatches, $regexContent, $bool, $uID, $uGID, $contentItemsValues, $tpl_type, $groups, $keys )
	{	
		$data		=	null;
		$objVal		=	null;
		$valueOnly	=	null;
		$author		=	( @$row->created_by ) ? $row->created_by : ( ( @$row->created_user_id ) ? $row->created_user_id : 0 );
		
		switch ( $item->typename ) {		
			case 'external_article':
				if ( ! $itemValue ) {
					if ( $bool == true ) {
						$item->label	=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
						$data			=	$item;
						return $data;
					}
					return null;
				}
				//Get ArticleId
				if ( $item->bool4 && $item->indexedxtd != '' ) {
					$articleId	=	( $tpl_type == 'list' ) ? $keys[$item->indexedxtd][$itemValue]->id
															: CCK::KEY_getId( $item->indexedxtd, $itemValue );
				} else {
					$articleId	=	$itemValue;
				}
				if ( ! $articleId ) {
					if ( $bool == true ) {
						$item->label	=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
						$data			=	$item;
						return $data;
					}
					return null;
				}
				//Get Article Infos from Database
				JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
				if ( $bool == true ) {
					if ( $tpl_type == 'cart' ) {
						$display		=	$item->bool7;
						$displayField	=	$item->options2;
						if ( $display == 2 ) {
							$options2		=	explode( '||', $item->options2 );
							$displayField	=	$options2[2];
						}
					} else if ( $tpl_type == 'list' ) {
						$display		=	$item->bool6;
						$displayField	=	$item->options2;
						if ( $display == 2 ) {
							$options2		=	explode( '||', $item->options2 );
							$displayField	=	$options2[1];
						}
					} else {
						$display		=	$item->bool5;
						if ( $display == 2 ) {
							$options2		=	explode( '||', $item->options2 );
							$displayField	=	$options2[0];
						}
					}
					// Load Row
					if ( !( $display == -1 || $display == -2 ) ) {
						$rowExt	=&	JTable::getInstance( 'content', 'JTable' );
						$rowExt->load( $articleId );
					}
					switch( $display ) {
						case -2:
							$item->value	=	$itemValue;
							break;
						case -1:
							$item->value	=	$articleId;
							break;
						case 1:
							require_once ( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' );
							$categoryAlias	=	CCKjSeblodItem_Content::getResultFromDatabase( 'SELECT alias FROM #__categories WHERE id = '.$rowExt->catid );
							$linkExt		=	JRoute::_(ContentHelperRoute::getArticleRoute($rowExt->id.":".$rowExt->alias, $rowExt->catid.":".$categoryAlias, $rowExt->sectionid));
							$item->value	=	'<a href="'.$linkExt.'">'.$rowExt->title.'</a>';
							break;
						case 2:
							$item->value	=	CCK_GET_ValueFromText( $rowExt->introtext.$rowExt->fulltext, $displayField );	
							break;
						case 3:
							$item->value	=	$rowExt->title;
							break;
						case 0:
						default:
							$item->value	=	CCKjSeblodItem_Content::getExternalArticleData( $rowExt );	
							break;
					}
					$item->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
					$item->external		=	$itemValue;
					$item->external_id	=	$articleId;
					$data				=	$item;
				} else {
					// Load Row
					$rowExt	=&	JTable::getInstance( 'content', 'JTable' );
					$rowExt->load( $articleId );
					//Get ArticleFields from TemplateMatches
					$ids = array_keys( $templateMatches[1], $item->name );
					for ( $k = 0, $fields = array(), $n = count( $ids ); $k < $n; $k++ ) {
						if ( $templateMatches[2][$ids[$k]] ) {
							$fields[$k] = $templateMatches[2][$ids[$k]];
						}
					}
					if ( ! empty( $fields ) ) {
						$articleFields = array_keys( array_flip( $fields ) );
						preg_match_all( $regexContent, $rowExt->introtext, $articleMatches );
						foreach ( $articleFields as $val ) {
							if ( $val == 'art_link' || $val == 'artlink' ) {
								$itemObj		=	new stdClass();
								$categoryAlias	=	CCKjSeblodItem_Content::getResultFromDatabase( 'SELECT alias FROM #__categories WHERE id = '.$rowExt->catid );
								$itemObj->value	=	JRoute::_(ContentHelperRoute::getArticleRoute($rowExt->id.":".$rowExt->alias, $rowExt->catid.":".$categoryAlias, $rowExt->sectionid));
								$data[$val]		=	$itemObj;
							} else if ( array_key_exists( $val, get_object_vars( $row ) ) ) {
								$itemObj		=	new stdClass();
								$itemObj->value	=	@$rowExt->$val;
								$data[$val]		=	$itemObj;
							} else {
								if  ( ( $aKey = array_search( $val, $articleMatches[2] ) ) !== false ) {
									$external	=	CCKjSeblodItem_Content::getContentItem( $val );
									$data[$val]	=	CCKjSeblodItem_Content::getDataI( $external, trim( $articleMatches[3][$aKey] ), $row, $valueOnly, $templateMatches, $regexContent, $bool, $tpl_type, $keys, 1 );
									$data[$val]->label	=	( $external->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $external->label ) : $external->label ) : $external->title;
								}
							}
						}
					}
				}
				break;
			case 'joomla_content':
				$extended		=	CCKjSeblodItem_Content::getContentItem( $item->extended );
				if ( ! $extended->gACL || $extended->gACL == 17 || ( $extended->gACL > 0 && $extended->gACL != 17 && $uGID >= $extended->gACL ) || ( $author && $uID == $author ) ) {
					$item->label	=	( $extended->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $extended->label ) : $extended->label ) : $item->title;
					$item->light	=	$extended->light;
					$item->display	=	$extended->display;
					$item->description	=	$extended->description;
					$item->tooltip	=	'AJAX:index.php?option=com_cckjseblod&amp;view=modal_tooltip&amp;cid[]='.$extended->id.
										'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$item->label;
					$field			=	$item->name;
					if ( $field == 'frontpage' ) {
						$res			=	CCKjSeblodItem_Content::getResultFromDatabase( 'SELECT COUNT(content_id) FROM #__content_frontpage WHERE content_id ='.$row->id );
						$item->value	=	( $res ) ? 1 : 0;
					} else {
						if ( array_key_exists( $field, get_object_vars( $row ) ) ) {
							$item->value	=	$row->$field;
						} else {	
							if ( strpos( $item->extended, 'jcontentparams' ) !== false ) {
								$aparams		=	new JParameter( $row->attribs );
								$item->value	=	$aparams->get( $field );
							} else if ( strpos( $item->extended, 'jcontentmeta' ) !== false ) {
								 if ( $field == 'meta_desc' ) {
									$item->value	=	$row->metadesc;
								} else if ( $field == 'meta_key' ) {
									$item->value	=	$row->metakey;
								}
							} else { }
						}
					}
					$data	=	$item;
				} else {
					$data	=	null;
				}
				break;
			case 'external_subcategories':
				if ( ! $itemValue || $itemValue == 0 || $bool == true ) {
					$data	=	null;
				} else {
					//$desc	=	str_replace( '"', '\"', $row->text);
					//$query 	=	'SELECT id FROM #__categories WHERE description = "'.$desc.'"';
					//$catId	=	CCKjSeblodItem_Content::getResultFromDatabase( $query );
					//
					$catId		=	JRequest::getVar( 'id' );
					$catId		=	( strpos( $catId, ':' ) !== false ) ? substr($catId, 0, strpos($catId, ':')) : $catId;
					$catOption	=	JRequest::getVar( 'option' );
					$catView	=	JRequest::getVar( 'view' );
					$alpha		=	JRequest::getVar( 'alpha' );
					$whereAlpha	=	( $alpha ) ? ' AND ( title LIKE "'.$alpha.'%" OR title LIKE "'.strtoupper($alpha).'%" )' : '';
					switch ($item->ordering) {
						case 2:
							$ordering	=	'title DESC';
							break;
						case 1:
							$ordering	=	'title ASC';
							break;
						case 0:
						default:
							$ordering	=	'ordering ASC';
							break;
					}
					if ( $catId && $catOption == 'com_content' && $catView == 'category' ) { //TODO: VIEW / REWRITING URL / ID
						$query 	=	'SELECT * FROM #__categories WHERE published = 1 AND parent_id ='.$catId.$whereAlpha.' ORDER BY '.$ordering;
						$childs	=	CCKjSeblodItem_Content::getObjectListFromDatabase( $query );
					}
					$xi	=	0;
					if ( sizeof ( @$childs ) ) {
						foreach ( $childs as $child ) {
							$ids = array_keys( $templateMatches[1], $item->name );
							for ( $k = 0, $fields = array(), $n = count( $ids ); $k < $n; $k++ ) {
								if ( $templateMatches[3][$ids[$k]] ) {
									$fields[$k] = $templateMatches[3][$ids[$k]];
								}
							}
							if ( ! empty( $fields ) ) {
								$articleFields = array_keys( array_flip( $fields ) );
								preg_match_all( $regexContent, $child->description, $articleMatches );
								foreach ( $articleFields as $val ) {
									if ( $val == 'cat_link' || $val == 'catlink' ) {
										$itemObj		=	new stdClass();
										$parentLayout	=	( JRequest::getCmd( 'layout' ) ) ? '&layout='.JRequest::getCmd( 'layout' ) : '';
										$itemObj->value	=	JRoute::_( ContentHelperRoute::getCategoryRoute( $child->id.':'.$child->alias, $child->section ).$parentLayout );
										//$itemObj->value	=	JRoute::_( ContentHelperRoute::getCategoryRoute( $child->id.':'.$child->alias, $child->section ) );
										$data[$xi][$val]	=	$itemObj;
									} else if ( array_key_exists( $val, get_object_vars( $child ) ) ) {
										$itemObj		=	new stdClass();
										$itemObj->value	=	$child->$val;
										$data[$xi][$val]	=	$itemObj;
									} else {
										if  ( ( $aKey = array_search( $val, $articleMatches[2] ) ) !== false ) {
											$external	=	CCKjSeblodItem_Content::getContentItem( $val );
											$data[$xi][$val]	=	CCKjSeblodItem_Content::getDataI( $external, trim( $articleMatches[3][$aKey] ), $row, $valueOnly, $templateMatches, $regexContent, $bool, $tpl_type, $keys, 1 );
										}
									}
								}
							}
							$xi++;
						}
					} else {
						$data	=	null;
					}
				}
				break;
			case 'alias':
				$extended	=	CCKjSeblodItem_Content::getContentItem( $item->extended );
				if ( ! $extended->gACL || $extended->gACL == 17 || ( $extended->gACL > 0 && $extended->gACL != 17 && $uGID >= $extended->gACL ) || ( $author && $uID == $author ) ) {
					$data 	=	CCKjSeblodItem_Content::getDataI( $extended, $itemValue, $row, $valueOnly, $templateMatches, $regexContent, $bool, $tpl_type, $keys, 0, $item->name);
				} else {
					$data	=	null;
				}
				break;
			case 'content_type':
				$more_items =	( $tpl_type == 'list' ) ? $groups[$item->extended] : CCKjSeblodItem_Content::getItemsGroup( $item->extended, 'content', '', false );
				$xn			=	( $itemValue ) ? $itemValue : $item->rows;
				$gx			=	null;
				$gx_max		=	count( @$row->search );
				$gx_mem		=	array();
				for ( $xi = 0; $xi < $xn; $xi++ ) {
					$gx_num	=	0;
					$xf		=	count( $more_items );
					for ( $xj = 0; $xj < $xf; $xj++ ) {
						$more_itemName	=	$more_items[$xj]->name;
						$moreValue		=	trim( @$contentItemsValues[$more_itemName.'|'.$xi.'|'.$item->name] );
						if ( $more_items[$xj]->typename == 'external_article' && $bool == 1 ) {
							$data[$xi][$more_itemName]	=	 clone CCKjSeblodItem_Content::getDataI( $more_items[$xj], $moreValue, $row, $valueOnly, $templateMatches, $regexContent, $bool, $tpl_type, $keys, -2, $item->name, $xi);
						} else {
							$data[$xi][$more_itemName]	=	CCKjSeblodItem_Content::getDataI( $more_items[$xj], $moreValue, $row, $valueOnly, $templateMatches, $regexContent, $bool, $tpl_type, $keys, -2, $item->name, $xi);					
						}
						// * 4th dimension *
						// Link
						if ( @$more_items[$xj]->link && @$more_items[$xj]->link != '' ) {
							if ( $more_items[$xj]->link == 'article' ) {
								$link	=	''; //todo
							} else if ( is_numeric( $more_items[$xj]->link ) ) {
								$link	=	'index.php?option=com_cckjseblod&view=search&layout=search&searchid='.$more_items[$xj]->link.'&task=search&'.$more_itemName.'='.$moreValue;
							} else {
								$link	=	$more_items[$xj]->link;
							}
							$data[$xi][$more_itemName]->link	=	$link;
						}
						// Typography
						if ( @$more_items[$xj]->typography && @$more_items[$xj]->typography != 'none' && @$more_items[$xj]->html ) {
							// JText
							if ( strpos( $more_items[$xj]->html, 'J(' ) !== false ) {
								$jtextSearch	=	'#J\((.*)\)#U';
								preg_match_all( $jtextSearch, $more_items[$xj]->html, $jtextMatches );
								if ( sizeof( $jtextMatches[0] ) ) {
									foreach( $jtextMatches[0] as $jk => $jv ) {
										$more_items[$xj]->html	=	str_replace( $jv, JText::_( $jtextMatches[1][$jk] ), $more_items[$xj]->html );										
									}
								}
							}
							// Attributes
							$typoSearch		=	'#\*([a-zA-Z0-9_]*)\*#U';
							preg_match_all( $typoSearch, $more_items[$xj]->html, $typoMatches );
							if ( sizeof ( $typoMatches[1] ) ) {
								$html	=	$more_items[$xj]->html;
								foreach( $typoMatches[1] as $typo ) {
									$html	=	( trim(@$data[$xi][$more_itemName]->$typo) != '' ) ? str_replace( '*'.$typo.'*', $data[$xi][$more_itemName]->$typo, $html )
																								   : str_replace( '*'.$typo.'*', '', $html );
								}
							}
							$data[$xi][$more_itemName]->html	=	$html;
						}
						//
						if ( $data[$xi][$more_itemName] ) {
							@$data[$xi][$more_itemName]->content	=	$item->content;
							@$data[$xi][$more_itemName]->cols	=	$item->cols;
							@$data[$xi][$more_itemName]->label	=	( @$more_items[$xj]->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $more_items[$xj]->label ) : $more_items[$xj]->label ) : @$more_items[$xj]->title;
						}
						if ( is_null( $gx ) && $gx_max && ( $gx_num < $gx_max ) && @$row->search[$more_itemName] ) {
							if ( sizeof( @$row->search[$more_itemName] ) ) {
								foreach( @$row->search[$more_itemName] as $search ) {
									if ( @$data[$xi][$more_itemName]->value == $search && $search != '' ) {
										$gx_num++;
										break;
									}
								}
							}
						}
					}
					if ( is_null( $gx ) && $gx_max ) {
						if ( $gx_num == $gx_max ) {
							$gx	=	$xi;
						} else {
							if ( $gx_num > 0 ) {
								$gx_mem[$xi]	=	$gx_num;
							}
						}
					}
				}
				if ( is_null( $gx ) ) {
					arsort( $gx_mem, (int)'SORT_NUMERIC' );
					$gx	=	key( $gx_mem );
				}
				$data['group']			=	$item;
				@$data['group']->gx		=	( $gx != '' ) ? $gx : 0;
				@$data['group']->label	=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
				break;
			case 'field_x':
				$extended	=	CCKjSeblodItem_Content::getContentItem( $item->extended );
				if ( ! $extended->gACL || $extended->gACL == 17 || ( $extended->gACL > 0 && $extended->gACL != 17 && $uGID >= $extended->gACL ) || ( $author && $uID == $author ) ) {
					$regexItemX =	'#\|\|'.$item->name.'\|\|(.*?)\|\|/'.$item->name.'\|\|#s';
					preg_match_all( $regexItemX, $itemValue, $XMatches );
					for ( $xi=0, $xn=count($XMatches[1]); $xi<$xn; $xi++ ) {
						if ( $extended->typename == 'external_article' && $bool == 1 ) {
						$data[$xi] 				=	clone CCKjSeblodItem_Content::getDataI( $extended, trim( $XMatches[1][$xi] ), $row, $valueOnly, $templateMatches, $regexContent, $bool, $tpl_type, $keys, -1, $item->name);
						} else {
						$data[$xi] 				=	CCKjSeblodItem_Content::getDataI( $extended, trim( $XMatches[1][$xi] ), $row, $valueOnly, $templateMatches, $regexContent, $bool, $tpl_type, $keys, -1, $item->name);
						}
						@$data[$xi]->content	=	$item->content;
						@$data[$xi]->cols		=	$item->cols;
						@$data[$xi]->label		=	( @$item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : @$data[$xi]->label;
					}
				} else {
					$data	=	null;
				}
				break;
			case 'joomla_user':
				$extended	=	CCKjSeblodItem_Content::getContentItem( $item->extended );
				if ( ! $extended->gACL || $extended->gACL == 17 || ( $extended->gACL > 0 && $extended->gACL != 17 && $uGID >= $extended->gACL ) || ( $author && $uID == $author ) ) {
					$item->label	=	( $extended->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $extended->label ) : $extended->label ) : $item->title;
					$item->light	=	$extended->light;
					$item->display	=	$extended->display;
					$item->description	=	$extended->description;
					$item->tooltip	=	'AJAX:index.php?option=com_cckjseblod&amp;view=modal_tooltip&amp;cid[]='.$extended->id.
										'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$item->label;		
					//$item->container	=	$item->name.'_container';
					$userId	=	CCKjSeblodItem_Content::getResultFromDatabase( 'SELECT userid FROM #__jseblod_cck_users WHERE contentid ='.$row->id );
					JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
					$rowUser	=&	JTable::getInstance( 'user', 'JTable' );
					$rowUser->load( $userId );
					$itemName	=	$item->name;
					if ( array_key_exists( $itemName, get_object_vars( $rowUser ) ) ) {
						if ( $itemName == 'email' ) {
							$item->value	=	( $extended->format ) ? str_replace( '@', $extended->format, $rowUser->$itemName ) : $rowUser->$itemName;
						} else {
							$item->value	=	$rowUser->$itemName;
						}
					} else {
						if ( $itemName == 'sendemail' ) {
							$item->value	=	$rowUser->sendEmail;
						} else {
							$item->value	=	null;
						}
					}
					$data	=	$item;
				} else {
					$data	=	null;
				}
				break;
			default:
				if ( ! $item->gACL || $item->gACL == 17 || ( $item->gACL > 0 && $item->gACL != 17 && $uGID >= $item->gACL ) || ( $author && $uID == $author ) ) {
					$item->label	=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
					$item->tooltip	=	'AJAX:index.php?option=com_cckjseblod&amp;view=modal_tooltip&amp;cid[]='.$item->id.
										'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$item->label;
					$data			=	CCKjSeblodItem_Content::getDataII( $item, $itemValue, $row, $valueOnly );
				} else {
					$data	=	null;
				}
		}
		
		return $data;
	}
	
}
?>
