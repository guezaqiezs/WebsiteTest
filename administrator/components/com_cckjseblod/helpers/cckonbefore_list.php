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
 * On Before List
 **/
JPluginHelper::importPlugin( 'content' );
$db	=&	JFactory::getDBO();

$random		=	rand( 1, 100000 );
$cache 		=	false;
$file 		=	'index_jseblod'.$random;

$parameters = array(
	'template' 	=> $contentTemplate->name,
	'file'		=> $file.'.php',
	'directory'	=> JPATH_SITE.DS.'templates',
);

$fileToCopy 	=	$path.DS.$contentTemplate->name.DS.'index.php';
$fileToRender	=	$path.DS.$contentTemplate->name.DS.$file.'.php';
if ( JFile::exists( $fileToCopy ) ) {
	JFile::copy( $fileToCopy, $fileToRender );
}

$docR			=&	JDocument::getInstance( 'html' );
if ( $client == 'cart' ) {
	$docR->checkout	=	$params->get( 'show_checkout', '' );
}
$docR->list		=	array();
$cckItems 		=	array();
$cckWidth 		=	array();
$regexContent	=	"#(.*?)::(.*?)::(.*?)::/(.*?)::#s";
$more_width		=	0;

require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_content.php' );
if ( $searchType->content == 2 ) {
	$query	= 'SELECT ccc.name AS typename, cc.*, s.width AS more_width, s.contentdisplay AS typography, s.helper AS html, s.link, s.access, s.mode'
		. ' FROM #__jseblod_cck_search_item_content AS s'
		. ' LEFT JOIN #__jseblod_cck_searchs AS sc ON sc.id = s.searchid'
		. ' LEFT JOIN #__jseblod_cck_items AS cc ON cc.id = s.itemid'
		. ' LEFT JOIN #__jseblod_cck_items_types AS ccc ON ccc.id = cc.type'
		. ' WHERE sc.name = "'.$searchType->name.'" AND sc.published = 1 AND s.client = "content"'
		. ' ORDER BY s.ordering asc'
		;
	$db->setQuery( $query );
	$databaseItemsSave		=	$db->loadObjectList( 'name' );
	$nq						=	count ( $databaseItemsSave );
}

//$keys	=	array();
$keys		=	CCK::KEY_getMap();
$groups 	=	array();
$js_cart	=	'';

for ( $i = 0, $k = 0; $i < count( $list ); $i++ )
{
	$listItem	=&	$list[$i];
	$js				=	'';
	$js_cart_qty	=	'';
	$js_cart_fields	=	'';
	$js_cart_price	=	'';
	
	// Href
	if ( $sef == -1 ) {
		$listItem->href	=	ContentHelperRoute::getArticleRoute( $listItem->slug, $listItem->catslug, $listItem->sectionid );
	} else {
		$listItem->href	=	CCKjSeblodHelperRoute::getArticleRoute( $listItem->slug, $listItem->catslug, $sef, $sef_option, $itemId );
	}
	// Text
	$text			=	( $searchType->content == 3 ) ? $listItem->introtext : $listItem->introtext.$listItem->fulltext;
	preg_match_all( $regexContent, $text, $contentMatches );
	$contentCount		=	count( $contentMatches[2] );			

	$contentItemsValues	=	null;
	
	if ( $contentCount ) {
		$contentItemsValues	=	array();
		foreach($contentMatches[2] as $key => $val) {
			$contentItemsValues[$val]	=	$contentMatches[3][$key];
		}
	}
	
	if ( @$contentItemsValues ) {		
		if ( $searchType->content > 2 ) {
			$contentItems	=	implode ( '","', $contentMatches[2] );
			$contentItems	=	"\"".$contentItems."\"";
			$query	= 'SELECT cc.name AS typename, s.*'
				. ' FROM #__jseblod_cck_items AS s'
				. ' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
				. ' WHERE s.name IN ( '.$contentItems.' )'
				. ' ORDER BY s.ordering asc'
				;
			$db->setQuery( $query );
			$databaseItems	=	$db->loadObjectList( 'name' );
		} else {
			//
			foreach ( $databaseItemsSave as $data_key => $data_val ) {
				$databaseItems[$data_key]	=	clone $databaseItemsSave[$data_key];
			}
			//
		}
		
		if ( sizeof( $databaseItems ) ) {
			$x	=	0;
			foreach ( $databaseItems as $item ) {
				if ( empty( $item->mode ) || $item->mode == $i ) {
					if ( @$item->access ) {
						$itemName	=	( $item->access == 'hidden' ) ? $item->name : $item->access;
					//} else if ( @$item->access == 'auto' ) {
					//	$itemName	=	'ORDER'.$x;						
					} else {
						$itemName	=	$item->name;
						//
						if ( array_search( $item->name, $cckItems ) === false ) {
							$cckItems[] =	$item->name;
						}
						if ( $searchType->content == 2 && $i == 0 ) {
							$cckWidth[] =	@$item->more_width;
						}
					}
					if ( /*TO CONFIRM*/ 1 == 1 /*array_key_exists( $item->name, $contentItemsValues ) || $item->typename == 'joomla_content' || $item->typename == 'joomla_user' || $item->typename == 'joomla_readmore'*/ ) {
						$itemValue	=	( @$contentItemsValues[$item->name] != '' ) ? $contentItemsValues[$item->name] : null;		
						$itemValue 	=	trim( $itemValue );
						
						// Groups
						if ( $item->type == 20 && $item->extended != '' && ! isset( $groups[$item->extended] ) ) {
							$groups[$item->extended]	=	CCKjSeblodItem_Content::getItemsGroup( $item->extended, 'content', '', false );
						}
						//
	
						// eCommerce :: Cart Attributes
						if ( $item->type == 51 || $item->type == 52 || $item->type == 53 ) {
							if ( $item->type == 52 || $item->type == 53 ) {
								$item->acl					=	'';
								$item->editiondisplay		=	'';
								$item->submissiondisplay	=	'';
								$item->forced_html			=	'form';
								$listItem->$itemName		=	CCKjSeblodItem_Form::getData( $item, $itemValue, 'content', $listItem->id, null, 0, $listItem, $rowU, 0, 0, $contentItemsValues, null ); 
								//CHECK IT
							} else {
								$extended	=	CCK::FIELD_cleanExtended( $item->extended );
								$itemValue	=	( @$listItem->{$extended} ) ? $listItem->{$extended} : $itemValue;
								if ( ( ! $item->bool6 && $client == 'list' ) || ( ! $item->bool7 && $client == 'cart' ) ) {
									$item->acl					=	'';
									$item->editiondisplay		=	'';
									$item->submissiondisplay	=	'';
									if ( $item->bool3 != 1 ) {
										include ( 'administrator'.DS.'components'.DS.'com_cckjseblod_ecommerce'.DS.'helpers'.DS.'form.cart.cckjseblod_ecommerce.php' );
									}
									$item->forced_html		=	'form';
									if ( $client == 'cart' ) {
										$listItem->$itemName	=	CCKjSeblodItem_Form::getData( $item, $itemValue, $client, $listItem->pid, null, 0, $listItem, $rowU, 0, 0, null, null ); //CHECK IT
										if ( $listItem->$itemName->form != '' ) {
											if ( $item->bool3 ) {
												$js_cart_price	.=	$item->extended.',';
											}
											if ( $item->bool3 != 1 ) {
												$js_cart_fields	.=	$item->extended.',';
											}
											$master		=	( $item->extra == '1' ) ? 1 : 0;
											$js_cart	.= ' $("'.$listItem->pid.'_'.$extended.'").addEvent("change", function() { CCK_ECOMMERCE_CART_Update(this,"'.$extended.'",'.$listItem->pid.','.$listItem->cartid.','.$listItem->id.','.$item->bool3.','.$master.'); });';
										} else {
											$listItem->$itemName->form	=	'<input type="hidden" id="'.$listItem->pid.'_'.$extended.'" name="'.$listItem->pid.'_'.$extended.'" value="">' . '-';
										}
									} else {
										if ( $item->bool3 == 1 ) {
											$js_cart_qty	=	'quantity';
										} else {
											$js_cart_fields		.=	$item->extended.',';
										}
										$listItem->$itemName	=	CCKjSeblodItem_Form::getData( $item, $itemValue, 'content', $listItem->id, null, 0, $listItem, $rowU, 0, 0, null, null ); //CHECK IT
									}
								} else {
									$item->forced_html		=	'value';
									$listItem->$itemName	=	CCKjSeblodItem_Content::getData( $item, $itemValue, $listItem, $templateMatches, $regexContent, $auto, $uID, $uGID, $contentItemsValues, 'list', $groups, $keys );							
								}
							}
						} else {
							$listItem->$itemName	=	CCKjSeblodItem_Content::getData( $item, $itemValue, $listItem, $templateMatches, $regexContent, $auto, $uID, $uGID, $contentItemsValues, $client, $groups, $keys );
						}
						// eCommerce :: Cart Attributes
						
						if ( $auto && $listItem->$itemName != null && !is_array( @$listItem->$itemName ) && @$listItem->$itemName->value == '' && $listItem->$itemName->typename != 'panel_slider' && $listItem->$itemName->typename != 'sub_panel_tab' && @$listItem->$itemName->typename != 'ecommerce_cart' && @$listItem->$itemName->typename != 'ecommerce_cart_button' && @$listItem->$itemName->typename != 'ecommerce_price' ) {
							$listItem->$itemName = null;
						}
						
						// * 4th dimension *
						// Link
						if ( @$item->link && @$item->link != 'none' ) {
							if ( $item->link == 'article' ) {
								$listItem->$itemName->link	=	JRoute::_( $listItem->href );
							} else if ( $item->link == 'js_simple' ) {
								//Todo: checkbox, etc.... alias... etc...!!
								$listItem->$itemName->link	=	'javascript: $(\''.$item->name.'\').value=\''.$itemValue.'\';void(0);';
							} else if ( $item->link == 'js_advanced' ) {
								//Todo: checkbox, etc.... alias... etc...!!
								$listItem->$itemName->link	=	'javascript: $(\''.$item->name.'\').value=\''.$itemValue.'\';submitbutton(\'save\');';
							} else {
								$listItem->$itemName->link	=	'index.php?option=com_cckjseblod&view=search&layout=search&searchid='.$item->link.'&task=search&'.$item->name.'='.$itemValue.'&Itemid='.$itemId;
							}
						} else {
							@$listItem->$itemName->link	=	'';
						}
						// Typography
						if ( ( @$item->typography && @$item->typography != 'none' && @$item->html && @$listItem->$itemName->value != '' ) || @$listItem->$itemName->typename == 'ecommerce_cart_button' ) {
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
									$item->html	=	( trim(@$listItem->$itemName->$typo) != '' ) ? str_replace( '*'.$typo.'*', $listItem->$itemName->$typo, $item->html )
																									 : str_replace( '*'.$typo.'*', '', $item->html );
								}
							}
							//
							if ( strpos( $item->html, '#id#' ) !== false ) {
								$item->html	=	str_replace( '#id#', $listItem->id, $item->html );
							}
							//
							$listItem->$itemName->html	=	$item->html;
						} else {
							@$listItem->$itemName->html	=	'';
						}
						// * 4th dimension * //end
						$x++;
					}
				}
			}
		}
		$docR->list[$k]	=	new stdClass();
		$docR->list[$k]	=&	$listItem;
		$k++;
	}
	// ---------------------------
	// eCommerce :: Cart Attributes
	if ( $i == 0 ) {
		if ( $js_cart_price ) {
			$js_cart_price	=	( $js_cart_price ) ? substr( $js_cart_price, 0, -1 ) : '';
			$js				.=	'  var cck_cart_price = "'.$js_cart_price.'";';			
		}
		if ( $js_cart_qty || $js_cart_fields ) {
			$js_cart_fields	=	( $js_cart_fields ) ? substr( $js_cart_fields, 0, -1 ) : '';
			$js				.=	'  var cck_cart_qty = "'.$js_cart_qty.'";';
			$js				.=	'var cck_cart_fields = "'.$js_cart_fields.'";';
		}
		if ( $js ) {
			$doc	=&	JFactory::getDocument();
			$doc->addScriptDeclaration( $js );
		}
	}
	// eCommerce :: Cart Attributes
}
//
if ( $js_cart ) {
	$doc	=&	JFactory::getDocument();
	$doc->addScriptDeclaration( ' window.addEvent(\'domready\', function() {  '.$js_cart. ' }); ' );
}
//
$docR->cckwidth		=	$cckWidth;
if ( $auto == 1 ) {
	$docR->cckitems	=	$cckItems;
}
if ( @$myList == 1 ) {
	$dataL		=	$docR->render( $cache, $parameters );
} else {
	$dataR		=	$docR->render( $cache, $parameters );
}
?>