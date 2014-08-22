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
 * CCKjSeblod		Item_Store Class
 **/
class CCKjSeblodItem_Store
{
	/**
	 * Get Data from Database
	 **/
	function getStoredContent( $cckId, $actionMode )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $actionMode == 1 )  {
			$query = ' SELECT s.description FROM #__categories AS s WHERE id = '.(int)$cckId;
		} else {
			$query = ' SELECT CONCAT(s.introtext, s.fulltext) FROM #__content AS s WHERE id = '.(int)$cckId;
		}
		$db->setQuery( $query );
		$storedContent	=	$db->loadResult();
		
		return $storedContent;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getContentItem( $itemName )
	{
		$db	=&	JFactory::getDBO();
		
		$where	=	' WHERE s.name = "'.$itemName.'"';
		
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
	 * Eval Process on $VALUE
	 **/
	function beforeContentSave( $VALUE, $process )
	{
		eval( $process );
		
		return $VALUE;
	}
	
	/**
	 * Eval Process on $ARRAY
	 **/
	function beforeContentSaveArray( $ARRAY, $process )
	{
		eval( $process );
		
		return $ARRAY;
	}
	
	function getDataII( $textObj, $asText, $itemName, $itemValue, $item, $contentType, $items, $data, $cckId, $cck, $actionMode, $client, $k = -1, $parentName = '', $r = -1 )
	{	
		$contentReturn		=	null;
		$contentReturn2		=	null;
		
		$item->formdisplay	=	( $cckId ) ? @$item->editiondisplay : @$item->submissiondisplay;

		switch ( $item->typename ) {
			case 'external_article':
				if ( $item->substitute == 2 ) {
					if ( $itemValue ) {
						if ( $item->bool4 && $item->indexedxtd ) {
							$articleId	=	CCK::KEY_getId( $item->indexedxtd, $itemValue );
						} else {
							$articleId	=	$itemValue;
						}
						if ( $articleId ) {
							$textObj->substitute[]	=	CCK_DB_Result( 'SELECT title FROM #__content WHERE id='.$articleId );
						}
					}
				}
				// Indexed
				if ( $item->indexed ) {
					$textObj->batchIndexed[$textObj->nIndexed]['name']	=	$item->name;
					$textObj->batchIndexed[$textObj->nIndexed]['id']	=	$itemValue;
					$textObj->nIndexed++;
				}
				$contentReturn	=	$itemValue;
			 	break;
			case 'joomla_readmore':
				if ( $itemValue == 1 ) {
					$itemValue	=	'<hr id="system-readmore" />';
				} else {
					$itemValue	=	' ';
					$asText		=	false;
				}
				$contentReturn	=	$itemValue;
				break;
			//case 'joomla_content':
				// #
			 	//break;
			//case 'external_subcategories':
				// DEFAULT!
			 	//break;
			case 'query_url':
				$itemValue      = htmlspecialchars($itemValue);
				$contentReturn	= $itemValue;
			 	break;
			case 'query_user':
				$itemValue      = htmlspecialchars($itemValue);
				$contentReturn	= $itemValue;
				break;	
			//case 'joomla_module':
				// DEFAULT!
			 	//break;
			case 'joomla_plugin_button':
				$itemValue		=	JRequest::getVar( $itemName, '', 'post', 'string', JREQUEST_ALLOWRAW );
				$itemValueH		=	JRequest::getVar( $itemName.'_hidden', '', 'post', 'string', JREQUEST_ALLOWRAW );
				$itemValueH		=	htmlspecialchars_decode( $itemValueH );
				if ( $item->beforesave && $itemValue && $itemValueH != $itemValue ) {
					$itemValue	=	CCKjSeblodItem_Store::beforeContentSave( $itemValue, $item->beforesave );
				}
				$contentReturn	=	$itemValue;
				break;
			case 'joomla_plugin_content':
				$itemValue		=	$item->defaultvalue;
				$contentReturn	=	$itemValue;
				$textObj->batchPlugins[$textObj->nPlugins]['name']	=	$itemName;
				$textObj->nPlugins++;
				break;
			case 'form_action':
				$asText	=	false;
				break;
			case 'captcha_image':
				$asText	=	false;
				break;
			case 'email':
				if ( ( ( $actionMode == 0 || $actionMode == 1 ) && !$cckId && $item->bool == 1 )
				  || ( ( $actionMode == 0 || $actionMode == 1 ) && $cckId && $item->bool == 2 )
  				  || ( ( $actionMode == 0 || $actionMode == 1 ) && $item->bool == 6 )
				  || ( ( $actionMode == 2 ) && !$cckId && $item->bool == 3 ) 
				  || ( ( $actionMode == 2 ) && $cckId && $item->bool == 4 )
				  || ( ( $actionMode == 2 ) && $item->bool == 8 ) ) {
					
					global $mainframe;
					$siteName		=	@$mainframe->getCfg('sitename');
	
					$textObj->batchEmails[$textObj->nEmails]['valid']	=	0;
					$textObj->batchEmails[$textObj->nEmails]['subject']	=	( $item->content ) ? $item->content : $siteName . '::' . JText::_( 'ARTICLE SUBMISSION' );
					$textObj->batchEmails[$textObj->nEmails]['message']	=	( $item->message ) ? htmlspecialchars_decode($item->message) :
																			sprintf ( JText::_('ARTICLE_SUBMISSION_MESSAGE'), $siteName, JURI::root() );
					$textObj->batchEmails[$textObj->nEmails]['format']	=	1;
					$textObj->batchEmails[$textObj->nEmails]['fields']	=	( $item->location ) ? $item->location : '';
					$textObj->batchEmails[$textObj->nEmails]['dest']	=	array();
					$textObj->batchEmails[$textObj->nEmails]['from_type']	=	( $item->bool2 ) ? $item->bool2 : 0;
					$textObj->batchEmails[$textObj->nEmails]['from']		=	( $item->extra ) ? $item->extra : '';

					$textObj->batchEmails[$textObj->nEmails]['dest']		=	array();
					$textObj->batchEmails[$textObj->nEmails]['dest_cc']		=	array();
					$textObj->batchEmails[$textObj->nEmails]['dest_bcc']	=	array();
					
					if ( $item->mailto ) {
						$textObj->batchEmails[$textObj->nEmails]['dest'][]	=	$item->mailto;
						$textObj->batchEmails[$textObj->nEmails]['valid']	=	1;
					}
					if ( $item->toadmin ) {
						if ( strpos( $item->toadmin, ',' ) !== false ) {
							$recips = explode( ',', $item->toadmin );
							foreach( $recips as $recip ) {
								$recip_mail = CCKjSeblodItem_Form::getResultFromDatabase( 'SELECT email FROM #__users WHERE id='.$recip );
								if ( $recip_mail ) {
									$textObj->batchEmails[$textObj->nEmails]['dest'][]	=	$recip_mail;
									$textObj->batchEmails[$textObj->nEmails]['valid']		=	1;
								}
							}
						} else {
							$recip_mail = CCKjSeblodItem_Form::getResultFromDatabase( 'SELECT email FROM #__users WHERE id='.$item->toadmin );
							if ( $recip_mail ) {
								$textObj->batchEmails[$textObj->nEmails]['dest'][]	=	$recip_mail;
								$textObj->batchEmails[$textObj->nEmails]['valid']		=	1;
							}
						}
					}
					if ( $itemValue ) {
						$textObj->batchEmails[$textObj->nEmails]['dest'][]	= 	$itemValue;
						$textObj->batchEmails[$textObj->nEmails]['valid']		=	1;
					}
					if ( $item->cc ) {
						$cc	=	explode( '<br />', strtr( $item->cc, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />') ) );
						$textObj->batchEmails[$textObj->nEmails]['dest_cc']	=	array();
						if ( sizeof( $cc ) ) {
							foreach ( $cc as $c ) {
								$c	=	trim( $c );
								if ( $c != '' ) {
									$textObj->batchEmails[$textObj->nEmails]['dest_cc'][]	=	$c;
								}
							}
						}
						$textObj->batchEmails[$textObj->nEmails]['valid']	=	1;
					}
					if ( $item->bcc ) {
						$bcc	=	explode( '<br />', strtr( $item->bcc, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />') ) );
						$textObj->batchEmails[$textObj->nEmails]['dest_bcc']	=	array();
						if ( sizeof( $bcc ) ) {
							foreach ( $bcc as $c ) {
								$c	=	trim( $c );
								if ( $c != '' ) {
									$textObj->batchEmails[$textObj->nEmails]['dest_bcc'][]	=	$c;
								}
							}
						}
						$textObj->batchEmails[$textObj->nEmails]['valid']	=	1;
					}
					if ( $item->options2 ) {
						$textObj->batchEmails[$textObj->nEmails]['moredest']	=	$item->options2;
						$textObj->batchEmails[$textObj->nEmails]['valid']		=	1;
					}
					if ( $item->options ) {
						$textObj->batchEmails[$textObj->nEmails]['moredest_bcc']	=	$item->options;
						$textObj->batchEmails[$textObj->nEmails]['valid']			=	1;
					}
					$textObj->nEmails++;
				}
				$itemValue	=	htmlspecialchars($itemValue);
				$itemValue	=	( $itemValue == '' ) ? ' ' : $itemValue;
				$contentReturn	=	$itemValue;
				break;
			case 'save':
				$textObj->save	=	$itemValue;
				if ( $item->substitute == 2 ) {
					if ( $itemValue ) {
						$textObj->substitute[]	=	CCK_DB_Result( 'SELECT title FROM #__categories WHERE id='.$itemValue );
					}
				} else {}
				$contentReturn	=	$itemValue;
				break;
			case 'button_free':
				$asText	=	false;
				break;
			case 'button_reset':
				$asText	=	false;
				break;
			case 'button_submit':
				$asText	=	false;
				break;
			case 'checkbox':
				if ( isset( $itemValue ) ) {
					$checked	=	$itemValue;
					if ( is_array( $checked ) ) {
						$checked 		=	implode( $item->divider, $checked );
						$itemValue		=	htmlspecialchars( $checked );	//$itemValue = $checked;
					} else {
						$itemValue		=	htmlspecialchars( $itemValue );	//$itemValue = $itemValue;
					}
					$itemValue	=	( $itemValue == '' ) ? ' ' : $itemValue;
					if ( $item->substitute == 1 ) {
						$textObj->substitute[]	=	$itemValue;
					} else if ( $item->substitute == 2 ) {
						$textObj->substitute[]	=	CCKjSeblodItem_Store::getOptionText( $itemValue, $item->options, 1, $item->divider );
					} else {}
					$contentReturn	=	$itemValue;
				} else {
					$contentReturn	=	' ';
				}
				break;
			case 'hidden':
				$itemValue	=	htmlspecialchars($itemValue);
				$itemValue	=	( $itemValue == '' ) ? ' ' : $itemValue;
				if ( $item->substitute == 1 ) {
					$textObj->substitute[]	=	$item->defaultvalue;
				} else if ( $item->substitute == 2 ) {
					$textObj->substitute[]	=	'[created_date]'.$item->format;
				} else if ( $item->substitute == 3 ) {
					$textObj->substitute[]	=	'[modified_date]'.$item->format;
				} else {}
				$contentReturn	=	$itemValue;
				break;
			//case 'password':
				// DEFAULT!
			 	//break;
			case 'radio':
				$itemValue		=	htmlspecialchars( $itemValue );
				$itemValue		=	( $itemValue == '' ) ? ' ' : $itemValue;
				if ( $item->substitute == 1 ) {
					$textObj->substitute[]	=	$itemValue;
				} else if ( $item->substitute == 2 ) {
					$textObj->substitute[]	=	CCKjSeblodItem_Store::getOptionText( $itemValue, $item->options );
				} else {}
				$contentReturn	=	$itemValue;
				// Indexed
				if ( $item->indexed ) {
					$textObj->batchIndexed[$textObj->nIndexed]['name']	=	$item->name;
					$textObj->batchIndexed[$textObj->nIndexed]['id']	=	$itemValue;
					$textObj->nIndexed++;
				}
			 	break;
			case 'text':
				//$itemValue	=	htmlspecialchars( $itemValue );
				$itemValue	=	( $itemValue == '' ) ? ' ' : $itemValue;
				if ( $item->substitute == 1 ) {
					$textObj->substitute[]	=	$itemValue;
					$contentReturn2	=	$itemValue;
				} else if ( $item->substitute == 2 ) {
					$textObj->substitute[]	=	$itemValue;
					$contentReturn	=	$itemValue;
				} else {
					$contentReturn	=	$itemValue;
				}
				// Indexed (as Key)
				if ( $item->indexedkey ) {
					$textObj->indexedkey['name']	=	$item->name;
					$textObj->indexedkey['id']		=	$itemValue;
				}
				// Indexed
				if ( $item->indexed ) {
					$textObj->batchIndexed[$textObj->nIndexed]['name']	=	$item->name;
					$textObj->batchIndexed[$textObj->nIndexed]['id']	=	$itemValue;
					$textObj->nIndexed++;
				}
				break;
			case 'select_dynamic':
				$itemValue		=	htmlspecialchars( $itemValue );
				$itemValue		=	( $itemValue == '' ) ? ' ' : $itemValue;
				$contentReturn	=	$itemValue;
			 	break;
			case 'select_multiple':
				if ( isset( $itemValue ) ) {
					if ( is_array( $itemValue ) ) {
						$nb = count( $itemValue );
						if ( $nb > 1 ) {
							$itemValueMod = implode( $item->divider, $itemValue );
						} else {
							$itemValueMod = ( $itemValue[0] ) ? htmlspecialchars( $itemValue[0] ) : '';
						}
					} else {
						$itemValueMod	=	( $itemValue ) ? htmlspecialchars( $itemValue ) : '';
					}
				} else {
					$itemValueMod = '';
				}
				$itemValueMod	=	( $itemValueMod == '' ) ? ' ' : $itemValueMod;
				if ( $item->substitute == 1 ) {
					$textObj->substitute[]	=	$itemValueMod;
				} else if ( $item->substitute == 2 ) {
					$textObj->substitute[]	=	CCKjSeblodItem_Store::getOptionText( $itemValueMod, $item->options, 1, $item->divider );
				} else {}
				$contentReturn	=	$itemValueMod;
				break;
			case 'select_numeric':
				$itemValue		=	htmlspecialchars( $itemValue );
				$itemValue		=	( $itemValue == '' ) ? ' ' : $itemValue;
				if ( $item->substitute == 1 ) {
					$textObj->substitute[]	=	$itemValue;
				}
				$contentReturn	=	$itemValue;
			 	break;
			case 'select_simple':
				$itemValue		=	htmlspecialchars( $itemValue );
				$itemValue		=	( $itemValue == '' ) ? ' ' : $itemValue;
				if ( $item->substitute == 1 ) {
					$textObj->substitute[]	=	$itemValue;
				} else if ( $item->substitute == 2 ) {
					$textObj->substitute[]	=	CCKjSeblodItem_Store::getOptionText( $itemValue, $item->options );
				} else {}
				$contentReturn	=	$itemValue;
				// Indexed
				if ( $item->indexed ) {
					$textObj->batchIndexed[$textObj->nIndexed]['name']	=	$item->name;
					$textObj->batchIndexed[$textObj->nIndexed]['id']	=	$itemValue;
					$textObj->nIndexed++;
				}
			 	break;
			case 'textarea':
				$textareaData	=	JRequest::getVar( $itemName, '', 'post', 'string', JREQUEST_ALLOWRAW );
				if ( $k != -1 ) {
					$textareaData	=	@$textareaData[$k];
				}
				$itemValue		=	( $textareaData ) ? $textareaData : $itemValue;
				$itemValue		=	htmlspecialchars($itemValue);
				$itemValue		=	preg_replace("((\r\n)+)", '<br />', $itemValue);
				$itemValue		=	( $itemValue == '' ) ? ' ' : $itemValue;
				$contentReturn	=	$itemValue;
				break;
			case 'wysiwyg_editor':
				if ( ! $parentName ) {
					if ( ! $item->bool ) {
						$itemItem	=	$itemName.'_hidden';
					} else {
						$itemItem	=	$itemName;
					}
					$itemValue		=	JRequest::getVar( $itemItem, '', 'post', 'string', JREQUEST_ALLOWRAW );
				}
				if ( $k != -1 && $parentName == '' ) {
					$itemValue	=	$itemValue[$k];
				}
				$itemValue		=	( $itemValue == '' ) ? ' ' : $itemValue;
				$itemValue		=	( @$cck ) ? $itemValue : htmlspecialchars($itemValue); //TODO:??
				$contentReturn	=	$itemValue;
				break;
			//case 'alias':
				// DEFAULT!
			 	//break;
			//case 'alias_custom':
				// 1ST-2ND CLASS NOT 3RD!
				//break;
			case 'file':
				$location = $data[$itemName.'_hidden'];
				if ( $item->bool4 ) {
					$nFile = count( $itemValue );
					$valueFile = $itemValue;
					if ( $nFile > 1 ) {
						$sep = ( $item->divider ) ? $item->divider : ',';
						$itemValue = ( $item->bool3 ) ? implode( $sep.$location, $valueFile ) : implode( $sep, $valueFile );
					} else {
						$itemValue = $valueFile[0];
					}
					$itemValue = ( $itemValue ) ? ( ( $item->bool3 ) ? $location.$itemValue : $itemValue ) : '';
				} else {
					$itemValue 		=  ( $itemValue ) ? ( ( $item->bool3 ) ? $location.$itemValue : $itemValue ) : '';
				}
				$contentReturn	=	$itemValue;
				break;
			case 'folder':
				$location		=	$data[$itemName.'_hidden'];
				$itemValue 		=  ( $itemValue ) ? ( ( $item->bool3 ) ? $location.$itemValue : $itemValue ) : '';
				$contentReturn	=	$itemValue;
			 	break;
			case 'media':
				$itemValue		=	$data[$itemName.'_hidden'];
				//$itemValue		=	( $itemValue == '' ) ? ' ' : $itemValue;
				$contentReturn	=	$itemValue;
				break;
			case 'upload_image':
				global $mainframe;
				$itemPath		=	$data[$itemName.'_hidden'];
				$deleteBox		=	@$data[$itemName.'_delete'];
				
				$userfile 		= ( $parentName ) ? JRequest::getVar( $parentName, null, 'files', 'array' ) : JRequest::getVar( $itemName, null, 'files', 'array' );
				if ( is_array( $userfile['name'] ) ) {
					if ( $parentName ) {
						$userfile_name		=	$userfile['name'][$k][$itemName];
						$userfile_type		=	$userfile['type'][$k][$itemName];
						$userfile_tmp_name	=	$userfile['tmp_name'][$k][$itemName];
						$userfile_error		=	$userfile['error'][$k][$itemName];
						$userfile_size		=	$userfile['size'][$k][$itemName];
						$userfile				=	null;
						$userfile				=	array();
						$userfile['name']		=	$userfile_name;
						$userfile['type']		=	$userfile_type;
						$userfile['tmp_name']	=	$userfile_tmp_name;
						$userfile['error']		=	$userfile_error;
						$userfile['size']		=	$userfile_size;
					} else {
						$userfile_name		=	$userfile['name'][$k];
						$userfile_type		=	$userfile['type'][$k];
						$userfile_tmp_name	=	$userfile['tmp_name'][$k];
						$userfile_error		=	$userfile['error'][$k];
						$userfile_size		=	$userfile['size'][$k];
						$userfile				=	null;
						$userfile				=	array();
						$userfile['name']		=	$userfile_name;
						$userfile['type']		=	$userfile_type;
						$userfile['tmp_name']	=	$userfile_tmp_name;
						$userfile['error']		=	$userfile_error;
						$userfile['size']		=	$userfile_size;
					}
				}
				
				if ( $deleteBox == 1 ) {
					$title	=	strrpos( $itemPath, '/' ) ? substr( $itemPath, strrpos( $itemPath, '/' ) + 1 ) : $itemPath;
					if ( $itemPath != $item->cc ) {
						if ( $item->bool3 ) {
							$user_folder	=	substr( $itemPath, 0, strrpos( $itemPath, '/' ) );
							if ( $item->bool4 ) {
								$user_folder=	substr( $user_folder, 0, strrpos( $user_folder, '/' ) );
							}
							$user_folder	=	substr( $user_folder, strrpos( $user_folder, '/' )+1 ) . DS ;
						} else {
							$user_folder	=	'';
						}
						$content_folder		=	( $item->bool4 ) ? $cckId.DS : '';
						if ( JFile::exists( JPATH_SITE.DS.$item->location.$user_folder.$content_folder.'_thumb1'.DS.$title ) ) {
							JFile::delete( JPATH_SITE.DS.$item->location.$user_folder.$content_folder.'_thumb1'.DS.$title );
						}
						if ( JFile::exists( JPATH_SITE.DS.$item->location.$user_folder.$content_folder.'_thumb2'.DS.$title ) ) {
							JFile::delete( JPATH_SITE.DS.$item->location.$user_folder.$content_folder.'_thumb2'.DS.$title );
						}
						if ( JFile::exists( JPATH_SITE.DS.$item->location.$user_folder.$content_folder.'_thumb3'.DS.$title ) ) {
							JFile::delete( JPATH_SITE.DS.$item->location.$user_folder.$content_folder.'_thumb3'.DS.$title );
						}
						if ( JFile::exists( JPATH_SITE.DS.$item->location.$user_folder.$content_folder.'_thumb4'.DS.$title ) ) {
							JFile::delete( JPATH_SITE.DS.$item->location.$user_folder.$content_folder.'_thumb4'.DS.$title );
						}
						if ( JFile::exists( JPATH_SITE.DS.$item->location.$user_folder.$content_folder.'_thumb5'.DS.$title ) ) {
							JFile::delete( JPATH_SITE.DS.$item->location.$user_folder.$content_folder.'_thumb5'.DS.$title );
						}
						if ( JFile::exists( JPATH_SITE.DS.$itemPath ) ) {
							JFile::delete( JPATH_SITE.DS.$itemPath );
						}
					}
					$itemPath	=	'';
				}
				if ( ! $item->maxlength || ( $item->maxlength && $item->maxlength == 50 ) || ( $item->maxlength && $item->maxlength != 50 && $userfile['size'] < $item->maxlength ) ) {
					if ( $userfile && $userfile['name'] && $userfile['tmp_name'] ) {
						if ( $item->options2 ) {
							$legals	=	explode( ',', $item->options2 );
							$legal		=	( strrpos( $userfile['name'], '.' ) ) ? substr( $userfile['name'], strrpos( $userfile['name'], '.' ) + 1 ) : '';
							if ( $legal && array_search( $legal, $legals ) === false ) {
								$contentReturn	=	$itemValue;
								$mainframe->enqueueMessage( $userfile['name'] .' '. JText::_( 'LEGAL EXTENSIONS NOTICE' ), 'notice' );
								break;
							}
						}						
						$file_path	=	$item->location;
						$file_path	.=	( $item->bool3 && _USER_CURRENT ) ? _USER_CURRENT.'/' : '';
						$file_path	.=	( $item->bool4 && $cckId > 0 ) ? $cckId.'/' : '';
						//!
						$textObj->batchUploads[$textObj->nUploads]['field_name']		=	$itemName;
						$textObj->batchUploads[$textObj->nUploads]['parent_name']		=	$parentName;
						$textObj->batchUploads[$textObj->nUploads]['field_typename']	=	$item->typename;
						$textObj->batchUploads[$textObj->nUploads]['file_path']			=	$file_path;
						$textObj->batchUploads[$textObj->nUploads]['content_folder']	=	( $item->bool4 ) ? $item->bool4 : 0;
						$textObj->batchUploads[$textObj->nUploads]['file_name']			=	$userfile['name'];
						$textObj->batchUploads[$textObj->nUploads]['tmp_name']			=	$userfile['tmp_name'];
						$textObj->batchUploads[$textObj->nUploads]['item_content']		=	$item->content;
						$textObj->batchUploads[$textObj->nUploads]['item_options']		=	$item->options;
						$textObj->batchUploads[$textObj->nUploads]['item_format']		=	$item->format;
						$textObj->batchUploads[$textObj->nUploads]['item_width']		=	$item->width;
						$textObj->batchUploads[$textObj->nUploads]['item_height']		=	$item->height;
						$textObj->batchUploads[$textObj->nUploads]['item_extra']		=	$item->extra;
						$textObj->batchUploads[$textObj->nUploads]['k']					=	$k;
						$textObj->batchUploads[$textObj->nUploads]['r']					=	$r;
						$textObj->nUploads++;
						//!
						$itemValue	=	$file_path.$userfile['name'];
					} else {
						if ( $userfile['name'] ) {
							$mainframe->enqueueMessage( JText::_( 'INVALID FILE' ), "error" );
						}
						if ( $item->location == $itemPath ) {
							$itemValue	=	( $item->cc ) ? $item->cc : '';
						} else {
							if ( $k != -1 && $parentName == '' ) {
								$itemValue	=	$itemPath[$k];
							} else {
								$itemValue	=	$itemPath;
							}
						}
					}
				} else {
					$mainframe->enqueueMessage( $userfile['name'] .' '. JText::_( 'MAX FILESIZE NOTICE' ), 'notice' );
				}
				$contentReturn	=	$itemValue;
				break;
			case 'upload_simple':
				global $mainframe;
				$itemPath		=	$data[$itemName.'_hidden'];
				$deleteBox		=	@$data[$itemName.'_delete'];
				
				if ( $deleteBox == 1 ) {
					if ( JFile::exists( JPATH_SITE.DS.$itemPath ) ) {
						JFile::delete( JPATH_SITE.DS.$itemPath );
					}
					$itemPath	=	'';
				}
				$userfile 		= ( $parentName ) ? JRequest::getVar( $parentName, null, 'files', 'array' ) : JRequest::getVar( $itemName, null, 'files', 'array' );
				if ( is_array( $userfile['name'] ) ) {
					if ( $parentName ) {
						$userfile_name		=	$userfile['name'][$k][$itemName];
						$userfile_type		=	$userfile['type'][$k][$itemName];
						$userfile_tmp_name	=	$userfile['tmp_name'][$k][$itemName];
						$userfile_error		=	$userfile['error'][$k][$itemName];
						$userfile_size		=	$userfile['size'][$k][$itemName];
						$userfile				=	null;
						$userfile				=	array();
						$userfile['name']		=	$userfile_name;
						$userfile['type']		=	$userfile_type;
						$userfile['tmp_name']	=	$userfile_tmp_name;
						$userfile['error']		=	$userfile_error;
						$userfile['size']		=	$userfile_size;
					} else {
						$userfile_name		=	$userfile['name'][$k];
						$userfile_type		=	$userfile['type'][$k];
						$userfile_tmp_name	=	$userfile['tmp_name'][$k];
						$userfile_error		=	$userfile['error'][$k];
						$userfile_size		=	$userfile['size'][$k];
						$userfile				=	null;
						$userfile				=	array();
						$userfile['name']		=	$userfile_name;
						$userfile['type']		=	$userfile_type;
						$userfile['tmp_name']	=	$userfile_tmp_name;
						$userfile['error']		=	$userfile_error;
						$userfile['size']		=	$userfile_size;
					}
				}
				
				if ( ! $item->maxlength || ( $item->maxlength && $item->maxlength == 50 ) || ( $item->maxlength && $item->maxlength != 50 && $userfile['size'] < $item->maxlength ) ) {
					if ( $userfile && $userfile['name'] && $userfile['tmp_name'] ) {
						if ( $item->options2 ) {
							$legals	=	explode( ',', $item->options2 );
							$legal		=	( strrpos( $userfile['name'], '.' ) ) ? substr( $userfile['name'], strrpos( $userfile['name'], '.' ) + 1 ) : '';
							if ( $legal && array_search( $legal, $legals ) === false ) {
								$contentReturn	=	$itemValue;
								$mainframe->enqueueMessage( $userfile['name'] .' '. JText::_( 'LEGAL EXTENSIONS NOTICE' ), 'notice' );
								break;
							}
						}
						$file_path	=	$item->location;
						$file_path	.=	( $item->bool3 && _USER_CURRENT ) ? _USER_CURRENT.'/' : '';
						$file_path	.=	( $item->bool4 && $cckId > 0 ) ? $cckId.'/' : '';
						//!
						$textObj->batchUploads[$textObj->nUploads]['field_name']		=	$itemName;
						$textObj->batchUploads[$textObj->nUploads]['parent_name']		=	$parentName;
						$textObj->batchUploads[$textObj->nUploads]['field_typename']	=	$item->typename;
						$textObj->batchUploads[$textObj->nUploads]['file_path']			=	$file_path;
						$textObj->batchUploads[$textObj->nUploads]['content_folder']	=	( $item->bool4 ) ? $item->bool4 : 0;
						$textObj->batchUploads[$textObj->nUploads]['file_name']			=	$userfile['name'];
						$textObj->batchUploads[$textObj->nUploads]['tmp_name']			=	$userfile['tmp_name'];
						$textObj->batchUploads[$textObj->nUploads]['k']					=	$k;
						$textObj->batchUploads[$textObj->nUploads]['r']					=	$r;
						$textObj->nUploads++;
						//!
						$itemValue	=	$file_path.$userfile['name'];
					} else {
						if ( $userfile['name'] ) {
							$mainframe->enqueueMessage( JText::_( 'INVALID FILE' ), "error" );
						}
						if ( $item->location == $itemPath ) {
							$itemValue = null;
						} else {
							if ( $k != -1 && $parentName == '' ) {
								$itemValue	=	$itemPath[$k];
							} else {
								$itemValue = $itemPath;
							}
						}
					}
				} else {
					$mainframe->enqueueMessage( $userfile['name'] .' '. JText::_( 'MAX FILESIZE NOTICE' ), 'notice' );
				}
				$contentReturn	=	$itemValue;
				break;
			case 'free_code':
				if ( $item->formdisplay != 'none' ) {
					$textObj->batchCodes[$textObj->nCodes]['name']	=	$itemName;
					$textObj->batchCodes[$textObj->nCodes]['vars']	=	$item->options;
					if ( $item->bool2 ) {
						// File
						$path	=	JPATH_SITE.DS.$item->location;
						if ( JFile::exists( $path ) ) {
							$code	=	JFile::read( $path );
							$code	=	str_replace( array( '<?php', '?>' ), '', $code );
						}
					} else {
						// Code
						$code	=	$item->defaultvalue;
					}
					if ( $item->options ) {
						$opts	=	explode( '||', $item->options );
						$n		=	sizeof( $opts );
						$precode=	'';
						if ( $n ) {
							for ( $i = 0; $i < $n; $i++ ) {
								$precode	.=	'$variable'.($i+1).' = \''.$opts[$i].'\';'."\n";
							}
						}
						$code	=	"\n".$precode.$code;
					}
					$textObj->batchCodes[$textObj->nCodes]['code']	=	$code;
					$textObj->nCodes++;
				}
			 	break;
			//case 'free_text':
				// DEFAULT!
			 	//break;
			//case 'field_x':
				// 1ST-2ND CLASS NOT 3RD!
				//break;
			//case 'panel_slider':
				// DEFAULT!
			 	//break;
			//case 'sub_panel_tab':
				// DEFAULT!
			 	//break;
			case 'joomla_menu':
				if ( ! $item->displayfield ) {
					$textObj->batchMenus[$textObj->nMenus]['location']	=	$itemValue;
				} else {
					$textObj->batchMenus[$textObj->nMenus]['location']	=	$item->location;
				}
				$textObj->batchMenus[$textObj->nMenus]['layout']	=	$item->bool2;
				$textObj->batchMenus[$textObj->nMenus]['params']	=	$item->bool3;
				$textObj->batchMenus[$textObj->nMenus]['inherit']	=	$item->extra;
				$textObj->nMenus++;
				break;
			//case 'color_picker':
				// DEFAULT!
			 	//break;
			case 'calendar':
				$itemValue		=	htmlspecialchars($itemValue);
				if ( $item->bool ) {
					$year =	JRequest::getVar( $itemName.'_calendar_year' );
					$itemValue	=	( $item->bool2 ) ? $itemValue.$year : $year.$itemValue;
				}
				$contentReturn	=	$itemValue;
				break;
			default:
				$itemValue	=	htmlspecialchars($itemValue);
				$itemValue	=	( $itemValue == '' ) ? ' ' : $itemValue;
				$contentReturn	=	$itemValue;
				break;
		}
		
		if ( $asText == true ) {
			$textObj->text	.=	'<br />'._OPENING.$itemName._CLOSING.$contentReturn._OPENING.'/'.$itemName._CLOSING;
		}
		$curLabel	=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
		if ( ( trim ( $contentReturn ) )
			&& ( ! ( $item->typename == 'form_action' || $item->typename == 'free_text' || $item->typename == 'button_submit' || $item->typename == 'button_reset'
					|| $item->typename == 'captcha_image' || $itemName == 'password2' ) && ( ! ( $item->typename == 'password' && $contentReturn == 'XXXX' ) ) ) ) {
			
			$textObj->body	.=	'- '.$curLabel._LANG_SEPARATOR.trim($contentReturn).'<br /><br />';
		}
		if ( trim ( $contentReturn2 ) ) {
			$textObj->body	.=	'- '.$curLabel._LANG_SEPARATOR.trim($contentReturn2).'<br /><br />';
    	}
		$textObj->curValue	=	$contentReturn;
		$textObj->curType	=	$item->typename;
		$textObj->curLabel	=	$curLabel;
				
		return $textObj;
	}
	
	function getDataI(  ) {
		
	}
	
	function getData( $contentType, $items, $data, $cckId, $cck, $actionMode, $client, $items2nd ) {
		$textObj				=	new stdClass();
		$textObj->text			=	'<br />'._OPENING.'jseblod'._CLOSING.$contentType._OPENING.'/jseblod'._CLOSING;
															
		$textObj->substitute	=	array();
		$textObj->indexedkey	=	null;
		$textObj->nIndexed		=	0;
		$textObj->batchIndexed	=	array();
		$textObj->nCodes		=	0;
		$textObj->batchCodes	=	array();
		$textObj->objGroups		=	array();
		$textObj->nEmails		=	0;
		$textObj->batchEmails	=	array();
		$textObj->nMenus		=	0;
		$textObj->batchMenus	=	array();
		$textObj->nPlugins		=	0;
		$textObj->batchPlugins	=	array();
		$textObj->nUploads		=	0;
		$textObj->batchUploads	=	array();
		$textObj->body			=	'';
		$textObj->curValue		=	null;
		$textObj->curLabel		=	null;
		$textObj->save			=	0;
		
		if ( sizeof( $items ) ) {	
			foreach ( $items as $item ) {
				$textObj->cur			=	null;
				switch ( $item->typename ) {
					case 'joomla_content':
						break;
					case 'alias_custom':
						$extended		=	CCKjSeblodItem_Form::getContentItem( $item->extended );
						$itemName		=	$extended->name;
						$itemValue		=	@$data[$itemName];
						if ( $item->boolxtd ) {
							$item->typename	=	$extended->typename;
							$extended		=	$item;
						}
						$textObj		=	CCKjSeblodItem_Store::getDataII( $textObj, true, $itemName, $itemValue, $extended, $contentType, $items, $data, $cckId, $cck, $actionMode, $client );
						$item->value		=	$textObj->curValue;
						$item->typename2	=	$textObj->curType;
						$item->label2		=	$textObj->curLabel;
						break;
					case 'field_x':
						$extended		=	CCKjSeblodItem_Form::getContentItem( $item->extended );
						$itemName		=	$item->name;
						if ( $extended->typename == 'upload_image' || $extended->typename == 'upload_simple' ) {
							@$data[$itemName.'_hidden'];
							$upload_x	=	1;
						} else {
							@$data[$itemName];
							$upload_x	=	0;
						}
						$itemValue		=	( $extended->typename == 'upload_image' || $extended->typename == 'upload_simple' ) ? @$data[$itemName.'_hidden'] : @$data[$itemName];
						$saveText		=	$textObj->text;
						if ( sizeof( $itemValue ) ) {
							$itemValueMod	=	'<br />'._OPENING.$itemName._CLOSING;
							$i	=	0;
							foreach ( $itemValue as $val ) {
								$tempObj	=	new stdClass();
								if ( $val != '' ) {
									$tempObj		=	CCKjSeblodItem_Store::getDataII( $textObj, true, $itemName, $val, $extended, $contentType, $items, $data, $cckId, $cck, $actionMode, $client, $i, '', 1 );									
									if ( empty( $upload_x ) || ( $upload_x && ( $tempObj->curValue != $extended->location ) ) ) {
										$itemValueMod	.=	'<br />||'.$itemName.'||'.$tempObj->curValue.'||/'.$itemName.'||';
									}
								}
								$i++;
							}
							$itemValueMod	.=	'<br />'._OPENING.'/'.$itemName._CLOSING;
						}
						$textObj->text		=	$saveText.$itemValueMod;
						$item->value		=	$itemValueMod;
						$item->typename2	=	$itemValueMod;
						$item->label2		=	$textObj->curLabel;
						break;
					case 'content_type':
						$more_data			=	JRequest::getVar( $item->name, array(), 'post', 'array', JREQUEST_ALLOWRAW );
						$more_text			=	'';
						$xn					=	count( $more_data );
						$itemName			=	$item->name;
						$itemValue			=	$xn;
						$textObj->text		.=	'<br />'._OPENING.$itemName._CLOSING.$itemValue._OPENING.'/'.$itemName._CLOSING;
						$xi					=	0;
						$tempObj_value		=	array();
						foreach ( $more_data as $key => $group ) {
							$item->value		=	$item->name;
							$item->typename2	=	$item->name;
							$item->label2		=	$item->label;
							// MORE
							$more_text			.=	'<br />::jseblod_'.$itemName.'::'.$item->extended.'::/jseblod_'.$itemName.'::';
							$more_items 		=	CCKjSeblodItem_Form::getItemsGroup( $item->extended, $client, '', true );
							$tempObj_value[$xi]	=	array();
							if ( sizeof( $more_items ) ) {
								foreach( $more_items as $more_item ) {
									$more_itemName	=	$more_item->name;
									$more_itemValue	=	@$group[$more_itemName];
									$textObj		=	CCKjSeblodItem_Store::getDataII( $textObj, false, $more_itemName, $more_itemValue, $more_item, $contentType, $more_items, $group, $cckId, $cck, $actionMode, $client, $key, $itemName, $xi );
									$more_text		.=	'<br />::'.$more_itemName.'|'.$xi.'|'.$itemName.'::'.$textObj->curValue.'::/'.$more_itemName.'|'.$xi.'|'.$itemName.'::';
									//
									$tempObj_value[$xi][$more_itemName]			=	$more_item;
									$tempObj_value[$xi][$more_itemName]->value	=	$textObj->curValue;
								}
							}
							$more_text			.=	'<br />::jseblodend_'.$itemName.'::::/jseblodend_'.$itemName.'::';
							$xi++;
						}
						$textObj->text					.=	'<br />' . $more_text . '<br />';
						$textObj->objGroups[$itemName]	=	$tempObj_value;
						// MORE
						break;
					case 'joomla_user':
						$extended		=	CCKjSeblodItem_Form::getContentItem( $item->extended );
						$itemName		=	$item->name;
						$juserTemp		=	JRequest::getVar( 'juser', array(), 'post', 'array');
						$itemValue		=	$juserTemp[$itemName];
						//$juserTemp		=	JRequest::getVar( 'juser', array(), 'post', 'array');
						//$substitute[]	=	$juserTemp[$itemName];
						$textObj			=	CCKjSeblodItem_Store::getDataII( $textObj, false, $item->name, $itemValue, $extended, $contentType, $items, $data, $cckId, $cck, $actionMode, $client );
						$item->value		=	$textObj->curValue;
						$item->typename2	=	$textObj->curType;
						$item->label2		=	$textObj->curLabel;
						break;
					default:
						$itemName			=	$item->name;
						$itemValue			=	@$data[$itemName];
						$textObj			=	CCKjSeblodItem_Store::getDataII( $textObj, true, $itemName, $itemValue, $item, $contentType, $items, $data, $cckId, $cck, $actionMode, $client );
						$item->value		=	$textObj->curValue;
						$item->typename2	=	$textObj->curValue;
						$item->label2		=	$textObj->curLabel;
						break;
				}
			}
		}
		//
		if ( sizeof( $items2nd ) && $cckId ) {
			$regex	=	"#"._OPENING."(.*?)"._CLOSING."(.*?)"._OPENING."(/.*?)"._CLOSING."#s";
			$storedContent	=	CCKjSeblodItem_Store::getStoredContent( $cckId, $actionMode );
			preg_match_all( $regex, $storedContent, $contentMatches );
			foreach ( $items2nd as $item2nd ) {
				if ( ( $aKey = array_search( $item2nd->name, $contentMatches[1] ) ) !== false ) {
					if ( strpos( $textObj->text, '::/'.$item2nd->name.'::' ) === false ) {
						$storedValue	=	$contentMatches[2][$aKey];
						$textObj->text	.=	'<br />'._OPENING.$item2nd->name._CLOSING.$storedValue._OPENING.'/'.$item2nd->name._CLOSING;
					}
				}
			}
		}
		$textObj->text	.=	'<br />::jseblodend::::/jseblodend::';
		
		return $textObj;
	}
	
}
?>