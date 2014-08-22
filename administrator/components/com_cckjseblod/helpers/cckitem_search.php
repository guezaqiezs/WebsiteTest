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
 * CCKjSeblod		Item_Search Class
 **/
class CCKjSeblodItem_Search
{
	/**
	 * Get Data from Database
	 **/
	function &getSearch( $searchId )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $searchId )
		{
			$query	= 'SELECT s.*'
					. ' FROM #__jseblod_cck_searchs AS s'
					. ' WHERE s.id = '.$searchId.' AND s.published = 1'
					;
			$db->setQuery( $query );
			$search	=	$db->loadObject();
		}
		
		return $search;
	}
	
	/**
	 * Get Items
	 **/
	function getItemsSearch( $searchId, $client, $exclusion, $prename, $cck = false )
	{
		$db	=&	JFactory::getDBO();		
		
		if ( $client == 'all' )  {
			$where 	=	' WHERE cc.searchid = '.$searchId;
		} else {
			$where 	=	' WHERE cc.client = "'.$client.'" AND cc.searchid = '.$searchId;
		}
		$form 	=	true;
		if ( ! $form ) {
			$where	.=	' AND s.type != 25';
		}
		if ( $exclusion != '' ) {
			$where .= ' AND s.id NOT IN ('.$exclusion.')';
		}
		
		// ACL
		$user		=&	JFactory::getUser();
		$acl		=	','.$user->gid.',';
		$where_acl	=	' AND ( cc.acl = "" OR cc.acl REGEXP ".*'.$acl.'.*" )';
		// ACL
		
		$orderby	=	' ORDER BY cc.ordering ASC';
		
		$query	= ' SELECT DISTINCT s.*, sc.name AS typename, cc.client, cc.searchmatch, cc.value, cc.helper, cc.helper2, cc.target, cc.groupname, cc.live, cc.stage, cc.stage_state, cc.acl'
				. ' FROM #__jseblod_cck_items AS s '
				. ' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
				. ' LEFT JOIN #__jseblod_cck_search_item AS cc ON cc.itemid = s.id'
				. $where
				. $where_acl
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
	 * Get Items Content
	 **/
	function getItemsSearchContent( $searchId, $client )
	{
		$db	=&	JFactory::getDBO();		
		
		$items 		=	array();
		$where 		=	' WHERE cc.client = "'.$client.'" AND cc.searchid = '.$searchId;
		$orderby	=	' ORDER BY cc.ordering ASC';
		
		$query	= ' SELECT DISTINCT s.id, s.name, s.indexedxtd, sc.name AS typename, cc.client, cc.contentdisplay, cc.width, cc.helper, cc.target, cc.groupname, cc.stage, cc.acl'
				. ' FROM #__jseblod_cck_items AS s '
				. ' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
				. ' LEFT JOIN #__jseblod_cck_search_item_content AS cc ON cc.itemid = s.id'
				. $where
				. $orderby
				;
		
		$db->setQuery( $query );
		$items	=	$db->loadObjectList();
		
		return $items;
	}
	
	/**
     * Get Data II
     **/
	function getDataII( &$item, $itemValue, $itemId, $itemName, $client, $artId, $fullscreen, $actionMode, &$row, $lang_id, $cat_id, $ran ) {
		global $mainframe;
		
		switch ( $item->typename ) {
				case 'external_article':
						$value		=	( $itemValue != '' ) ? $itemValue : '';
						$label		=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->selectlabel ) : $item->selectlabel;
						$articles 	= 	array();
						$articles[]	= 	JHTML::_('select.option',  '', '- '.$label.' -', 'value', 'text' );
						if ( $item->bool ) {
							$next		=	HelperjSeblod_Helper::getNextAutoIncrement( 'content' );
							$articles[]	= 	JHTML::_('select.option',  $next, '- ( + 1 ) -', 'value', 'text' );
							$next		=	(int)$next + 1;
							$articles[]	= 	JHTML::_('select.option',  $next, '- ( + 2 ) -', 'value', 'text' );
							$next		=	(int)$next + 1;
							$articles[]	= 	JHTML::_('select.option',  $next, '- ( + 3 ) -', 'value', 'text' );
							$next		=	(int)$next + 1;
							$articles[]	= 	JHTML::_('select.option',  $next, '- ( + 4 ) -', 'value', 'text' );
						}
						if ( $item->bool4 && $item->indexedxtd != '' ) {
							$articles	= 	array_merge( $articles, HelperjSeblod_Helper::getJoomlaArticles( $item->options, $item->content, $item->bool2, $item->indexedxtd ) );
						} else {
							$articles	= 	array_merge( $articles, HelperjSeblod_Helper::getJoomlaArticles( $item->options, $item->content, $item->bool2 ) );
						}
						$data		= 	JHTML::_( 'select.genericlist', $articles, $itemName, 'class="inputbox select" size="1"', 'value', 'text', $value );
					break;
				case 'joomla_readmore':
					$value		=	( $itemValue != '' ) ? ( ( strpos( $itemValue, 'rm_enable' ) === true ) ? 1 : 0 ) : $item->bool;
					if ( @$row->importer_readmore == 1 ) {
						$value	=	1;	
					} else {
						if ( ! $itemValue ) {
							$value	=	$item->bool;
						} else {
							if ( $itemValue == 'rm_enable' || strpos( $itemValue, 'rm_enable' ) === true || strpos( $itemValue, 'rm_enable' ) === true ) {
								$value	=	1;
							} else {
								$value	=	0;
							}
						}
					}
					if ( ( $client == 'admin' && ( $item->displayfield == 0 || $item->displayfield == 1 ) )
					  || ( $client == 'site' && ( $item->displayfield == 0 || $item->displayfield == 2 ) )  ) { 
						$data		=	JHTML::_( 'select.booleanlist', $itemName, 'class="inputbox radio"', $value );
					} else {
						$data	 	= 	'<input class="inputbox" type="hidden" id="'.$itemId.'" name="'.$itemName.'" value="'.$value.'" />';
					}
					break;
				//case 'joomla_content':
					//break;
				case 'external_subcategories':
					$value		=	( $itemValue != '' ) ? $itemValue : $item->bool;
					$data		=	JHTML::_( 'select.booleanlist', $itemName, 'class="inputbox radio"', $value );
					break;
				//case 'query_url':
					//break;
				//case 'query_user':
					//break;
				//case 'joomla_module':
					//break;
				case 'joomla_plugin_button':
					$value		=	( $itemValue != '' ) ? $itemValue : '';
					$validValue	=	( $value ) ? '.' : '';
					$validation	=	( $required ) ? '<input class="required required-enabled" type="text" size="1" maxlength="0" style="width: 12px; height: 10px; text-align: center; cursor: default; margin-top: 4px; vertical-align: top;" disabled="disabled" value="'.$validValue.'" />&nbsp;' : '<input class="notrequired" type="text" size="1" maxlength="0" style="width: 12px; height: 10px; text-align: center; cursor: default; margin-top: 4px; vertical-align: top;" disabled="disabled" value="'.$validValue.'" />&nbsp;';
					$btPlugin	=	( $item->options ) ? array( $item->options ) : false;
					$btPlugins	=	HelperjSeblod_Helper::getPluginsButtonName();
					$buttons	=	array_diff($btPlugins, $btPlugin);
					$cckEditor	=&	JFactory::getEditor( 'cckjseblod' );
					$data		= 	'<textarea style="display: none;" id="'.$itemId.'" name="'.$itemName.'" cols="1" rows="1">'.$value.'</textarea>';
					$data		.=	$cckEditor->display( $itemName, '', 0, 0, $item->css, $validation, $buttons ) ;	
					
					$itemNameH	=	(strpos($itemName, '[]') !== false) ? substr( $itemName, 0, -2 ) : $itemName;
					if ( $value ) {
						$data	.=	'<input class="inputbox" type="hidden" id="'.$itemNameH.'_hidden" name="'.$itemNameH.'_hidden" value="'.htmlspecialchars($value).'" />';
					}					
					break;
				/*case 'form_action':
					break;*/
				case 'captcha_image':
					if ( $artId && ( $item->gEACL == -1 || ( $item->gEACL == 1 && $client == 'site' ) ) ) {
						$data = null;
					} else {
						$control = ( $client == 'site' ) ? '' : '&controller=interface';
						echo '<script language="javascript" type="text/javascript">
							var reloadCaptcha = function(src){
								window.addEvent("domready", function(){
									var url = url="index.php?option=com_cckjseblod'.$control.'&format=raw&task=captchaAjax&captcha_id='.$item->id.'";
									var CaptchaLayout = $("'.$itemName.'_container");
									new Ajax(url, {
										method: "get",
										update: CaptchaLayout,
										data:"old="+src,
										evalScripts:true,
										onComplete: function(){
										}
									}).request();
								});
							}
							</script>';
						//$suffix	=	date("H-i-s");
						$suffix	=	null;
						include_once( JPATH_SITE.DS.'media'.DS.'jseblod'.DS.'captcha-math'.DS.'captchaimage.php' );
						//$data		.=	'<span style="display:block; font-size:10px; color:#999999;">click on the image to reload it</span>';
						$data		=	'<img src="'.JURI::root( true ).'/tmp/jseblodcck-captcha-'.$suffix.'.jpg" style="margin-bottom: 6px; cursor: pointer;" alt="Captcha Image"'
									.	'title="Captcha Image" id="captcha" onclick="javascript:reloadCaptcha(this.src);" /><br />';
						$data		.=	'<input class="inputbox text" type="text"  name="cptcsecure" value="" size="'.$item->size.'" />';
					}
					break;
				case 'email':
					$value		=	( $itemValue != '' ) ? $itemValue : '';
					$value		=	( $value != ' ' ) ? $value : '';
					if ( $item->displayfield == 0 ) {
						$validation	=	( $item->validation ) ? ' ' . $item->validation : '';
						$data	=	'<input class="inputbox text '.$validation.'" type="text" id="'.$itemId.'" name="'.$itemName.'" maxlength="250" size="'.$item->size.'" value="'.$value.'" />';
					} else {
						$data	= 	'<input class="inputbox" type="hidden" id="'.$itemId.'" name="'.$itemName.'" value="'.$value.'" />';
					}	
					break;
				case 'save':
					$value		=	$itemValue;
					$opts			= 	array();
					$orientation	=	null;
					if ( $item->selectlabel && $item->format == 'generic' ) {
						$label		=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->selectlabel ) : $item->selectlabel;
						$opts[]		= 	JHTML::_('select.option',  '', '- '.$label.' -', 'value', 'text' );
					}
					if ( $item->bool2 == 2 ) {
						$options	=	CCKjSeblodItem_Form::getObjectListFromDatabase( 'SELECT title AS text, id AS value FROM #__categories WHERE parent_id IN ('.$item->options.') ORDER BY title' );
					} else if ( $item->bool2 == 1 ) {
						$cur_user		=&	JFactory::getUser();
						$options	=	CCKjSeblodItem_Form::getObjectListFromDatabase( 'SELECT title AS text, id AS value FROM #__categories WHERE created_user_id ='.$cur_user->id.' ORDER BY title' );
					} else {
						$options	=	CCKjSeblodItem_Form::getObjectListFromDatabase( 'SELECT title AS text, id AS value FROM #__categories WHERE id IN ('.$item->options.') ORDER BY title' );
					}
					if ( count( $options ) ) {
						$opts	=	array_merge( $opts, $options );
					}
					if ( $item->format != 'generic' ) {
						$orientation	=	( strstr( $item->format, '_' ) == '_v' ) ? true : false;
						$data		=	HelperjSeblod_Helper::radioList( $opts, $itemName, 'class="inputbox radio" size="1"', 'value', 'text', $value, $itemId, $orientation );
					} else {
						$data	= 	JHTML::_( 'select.genericlist', $opts, $itemName, 'class="inputbox select" size="1"', 'value', 'text', $value, $itemId, $orientation);			
					}
					break;
				case 'button_free':
					$data	=	'<input class="'.$item->css.'" type="button" onclick="javascript: '.$item->options.'" name="'.$itemName
							.	'" style="'.$item->style.'" value="'.$item->label.'" />';
					break;
				case 'button_reset':
					$data	=	'<input class="'.$item->css.'" type="reset" name="'.$itemName.'" value="'.$item->label.'" />';
					break;
				case 'button_submit':
				  if ( $ran ) {
					   $data	=	'<input class="'.$item->css.'" type="button" onclick="javascript: submitbutton'.$ran.'(\'save\');" name="'.$itemName
					   			.	'" style="'.$item->style.'" value="'.$item->label.'" />';
					} else {
					   $data	=	'<input class="'.$item->css.'" type="button" onclick="javascript: submitbutton(\'save\');" name="'.$itemName
					   			.	'" style="'.$item->style.'" value="'.$item->label.'" />';
          			}
					break;
				case 'checkbox':
					if ( $client == 'list' ) {
						$value		=	$itemValue;
					} else {
						$value		=	( $itemValue != '' ) ? $itemValue : $item->defaultvalue;
					}
					$options = explode( '||', $item->options );
					if ( $item->ordering == 1 ) {
						natsort( $options );
						$optionsSorted = array_slice( $options, 0 );
					} else if ( $item->ordering == 2 ) {
						natsort( $options );
						$optionsSorted = array_reverse( $options, true );
					} else {
						$optionsSorted = $options;
					}
					$opts 		= array();
					if ( sizeof( $optionsSorted ) ) {
						foreach ( $optionsSorted as $val ) {
							if ( JString::strpos( $val, '=' ) !== false ) {
								$opt	=	explode( '=', $val );
								$opts[]	= JHTML::_('select.option',  $opt[1], $opt[0], 'value', 'text' );
							} else {
								$opts[]	= JHTML::_('select.option',  $val, $val, 'value', 'text' );
							}
						}
					}
					$orientation = ( $item->bool ) ? true : false;
					if ( $client == 'list' ) {
						$data		=	HelperjSeblod_Helper::checkBoxList( $opts, $itemName, 'class="inputbox checkbox"', 'value', 'text', $value, $itemId, $orientation, $item->cols, $item->bool2 );
					} else {
						$data		=	HelperjSeblod_Helper::checkBoxList( $opts, $itemName.'[]', 'class="inputbox checkbox"', 'value', 'text', $value, $itemId, $orientation, $item->cols, $item->bool2 );
					}
					break;
				//case 'hidden':
					//break;
				case 'password':
					if ( $actionMode == 2 && $artId ) {
						$value		=	'XXXX';
						$minlength	=	4;
					} else {
						$value		=	( $itemValue != '' ) ? $itemValue : '';
						$value		=	( $value != ' ' ) ? $value : '';
						$minlength	=	( $item->bool ) ? $item->bool : 6;
					}
					$validation	=	( $item->validation ) ? ' ' . $item->validation : '';
					$data = 'dd';
					$data		=	'<input class="inputbox password minLength '.$validation.'" validatorProps="{minLength:'.$minlength.'}" type="password" id="'.$itemId.'" name="'.$itemName.'" maxlength="'.$item->maxlength.'" size="'.$item->size.'" value="'.$value.'" />';
					break;
				case 'radio':
					$value		=	( $itemValue != '' ) ? $itemValue : null;
					$options = explode( '||', $item->options );
					if ( $item->ordering == 1 ) {
						natsort( $options );
						$optionsSorted	=	array_slice( $options, 0 );
					} else if ( $item->ordering == 2 ) {
						natsort( $options );
						$optionsSorted	=	array_reverse( $options, true );
					} else {
						$optionsSorted	=	$options;
					}						
					$opts 		= array();
					if ( sizeof( $optionsSorted ) ) {
						foreach ( $optionsSorted as $val ) {
							if ( JString::strpos( $val, '=' ) !== false ) {
								$opt	=	explode( '=', $val );
								$opts[]	= JHTML::_('select.option',  $opt[1], $opt[0], 'value', 'text' );
							} else {
								$opts[]	= JHTML::_('select.option',  $val, $val, 'value', 'text' );
							}
						}
					}
					$orientation = ( $item->bool ) ? true : false;
					$data		=	HelperjSeblod_Helper::radioList( $opts, $itemName, 'class="inputbox radio" size="1"', 'value', 'text', $value, $itemId, $orientation, $item->cols, $item->bool2 );
					break;
				case 'text':
					if ( $client == 'list' ) {
						$value		=	$itemValue;
					} else {
						$value		=	( $itemValue != '' ) ? $itemValue : $item->defaultvalue;
					}
					$value		=	( $value != ' ' ) ? $value : '';
					$validation	=	( $item->validation ) ? ' ' . $item->validation : '';
					$style		=	( $item->style ) ? 'style="'.$item->style.'"' : '';
					$data		=	'<input class="inputbox text '.$validation.'" type="text" id="'.$itemId.'" name="'.$itemName.'"'
								.	' maxlength="'.$item->maxlength.'" size="'.$item->size.'" value="'.$value.'" '.$style.' />';
					break;
				case 'select_dynamic':
					if ( ! $artId && $cat_id && $itemId == 'catid' ) {
						$value		=	$cat_id;
					} else if ( ! $artId && $cat_id && $itemId == 'sectionid' ) {
						$value		=	CCKjSeblodItem_Form::getResultFromDatabase( 'SELECT section FROM #__categories WHERE id='.$cat_id );
					} else {
						$value		= ( $itemValue != '' ) ? $itemValue : '';
					}
					
					$opts	=	array();
					if ( $item->selectlabel ) {
						$label	=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->selectlabel ) : $item->selectlabel;
						$opts[]	= 	JHTML::_('select.option',  '', '- '.$label.' -', 'value', 'text' );
					}
					
					$request	=	explode( '||', $item->options );
					$where		=	( $item->content ) ? explode( '||', $item->content ) : '';
					if ( $where && @$where[1] ) {
						switch ( $where[1] ) {
							case 'INF':
								$where[1] = '<';
								break;
							case 'SUP':
								$where[1] = '>';
								break;
							case 'IN':
								$where[1] = 'IN';
								$where[2] = '('.$where[2].')';
								break;
							case 'NOTIN':
								$where[1] = 'NOT IN';
								$where[2] = '('.$where[2].')';
								break;
							case 'LIKE%':
								$where[1] = 'LIKE';
								$where[2] = '"%'.$where[2].'"';
								break;
							case '%LIKE':
								$where[1] = 'LIKE';
								$where[2] = '"'.$where[2].'%"';
								break;
							case '%LIKE%':
								$where[1] = 'LIKE';
								$where[2] = '"%'.$where[2].'%"';
								break;
    					case 'NOTLIKE%':
    						$where[1] = 'NOT LIKE';
    						$where[2] = '"%'.$where[2].'"';
    						break;
    					case '%NOTLIKE':
    						$where[1] = 'NOT LIKE';
    						$where[2] = '"'.$where[2].'%"';
    						break;
							case '%NOTLIKE%':
								$where[1] = 'NOT LIKE';
								$where[2] = '"%'.$where[2].'%"';
								break;
							default:
								$where[2] = '"'.$where[2].'"';
								break;
						}
						$ope	=	( $where[1] == 'INF' || $where[1] == 'SUP' ) ? ( ( $where[1] == 'INF' ) ? '<' : '>' ) : $where[1];
					}
					$orderby	=	( $item->extra ) ? str_replace( '||', ' ', $item->extra ) : $request[1];
					// Ajax Child !	
					if ( ( $item->bool == 1 || $item->bool == 3 ) && $item->location ) {
						$value	=	JString::trim($value);
						if ( $value ) {
							$whereclause	=	( $where && @$ope ) ? ' AND '.$where[0].' '.$ope.' '.$where[2] : '';
							if ( $item->options2 ) {
								$whereclause	.=	' AND '.$item->options2;
							}
							$query			=	'SELECT '.$request[1].' AS text, '.$request[2].' AS value FROM '.$request[0].' WHERE '.$item->location
											.	' IN ( SELECT '.$item->location.' FROM '.$request[0].' WHERE '.$request[2].' = "'.$value.'" ) '.$whereclause.' ORDER BY '.$orderby;
						} else {
							$parentName	=	CCK_DB_Result( 'SELECT name FROM #__jseblod_cck_items WHERE extended = "'.$item->name.'"' );
							if ( $parentName ) {
								$value	=	JRequest::getString( $parentName );
								if ( $value ) {
									$whereclause	=	( $where && @$ope ) ? ' AND '.$where[0].' '.$ope.' '.$where[2] : '';
									if ( $item->options2 ) {
										$whereclause	.=	' AND '.$item->options2;
									}
									$query			=	'SELECT '.$request[1].' AS text, '.$request[2].' AS value FROM '.$request[0].' WHERE '.$item->location.' = "'.$value.'" '.$whereclause
													.	' ORDER BY '.$orderby;
									$value			=	'';
								}
							}
						}
					} else {
						// Ajax Parent or No Ajax ! 
						if ( $item->bool != 1 ) { 
							$whereclause	=	( $where && @$ope ) ? ' WHERE '.$where[0].' '.$ope.' '.$where[2] : '';
							if ( $item->options2 ) {
								$whereclause	=	( @$whereclause ) ? $whereclause.' AND '.$item->options2 : ' WHERE '.$item->options2;
							}
							$query			=	'SELECT '.$request[1].' AS text, '.$request[2].' AS value FROM '.$request[0].$whereclause.' ORDER BY '.$orderby;
						}
					}
					if ( @$query ) {
						$getOpts = CCKjSeblodItem_Form::getListFromDatabase( $query );
					}
					if ( sizeof( @$getOpts ) ) {
						$opts	=	array_merge( $opts, $getOpts );
					}
					//
					$disabled	=	( $lang_id && ( $itemId == 'sectionid' || $itemId == 'catid' ) ) ? 'disabled="disabled"' : '';
					$data	=	JHTML::_( 'select.genericlist', $opts, $itemName, 'class="inputbox select" size="1" '.$disabled, 'value', 'text', $value, $itemId );
					// If Ajax Parent !
					if ( ( $item->bool == 2 || $item->bool == 3 ) && $item->extended && $item->elemxtd == 'item' ) {
						$itemInfo		=	CCKjSeblodItem_Form::getDynamicSelectInfoFromDatabase( $item->extended );
						if ( $itemInfo->selectlabel ) {
							$label	=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $itemInfo->selectlabel ) : $itemInfo->selectlabel;
						}
						$control = ( $client == 'site' ) ? '' : '&controller=interface';
						$script = '$("'.$itemId.'").addEvent("change",function(){
								var val = $("'.$itemId.'").value;
								var url="index.php?option=com_cckjseblod'.$control.'&format=raw&task=dynamicSelectAjax&client=search&item='.$item->extended.'&label='.$label.'&where="+val;
								var fieldname = "'.$itemInfo->name.'"+"_container";
								var field = $(fieldname);
								var a=new Ajax(url,{
									method:"get",
									update:field,
									evalScripts:true,
									onComplete: function(){}
								}).request();
							});
							';
						$scriptjs = '<script language="javascript" type="text/javascript">window.addEvent("domready",function(){'.$script.'});</script>';
						echo $scriptjs;
					}
					break;	
				case 'select_multiple':
					if ( $client == 'list' ) {
						$value		=	$itemValue;
					} else {
						$value			= ( $itemValue != '' ) ? $itemValue : $item->defaultvalue; 
					}
					$options = explode( '||', $item->options );
					if ( $item->ordering == 1 ) {
						natsort( $options );
						$optionsSorted = array_slice( $options, 0 );
					} else if ( $item->ordering == 2 ) {
						natsort( $options );
						$optionsSorted = array_reverse( $options, true );
					} else {
						$optionsSorted = $options;
					}
					$opts 		= array();
					if ( sizeof( $optionsSorted ) ) {
						foreach ( $optionsSorted as $val ) {
							if ( JString::strpos( $val, '=' ) !== false ) {
								$opt	=	explode( '=', $val );
								$opts[]	= JHTML::_('select.option',  $opt[1], $opt[0], 'value', 'text' );
							} else {
								$opts[]	= JHTML::_('select.option',  $val, $val, 'value', 'text' );
							}
						}
					}
					$size	=	( $item->rows ) ? $item->rows : count( $opts );
					$data	=	JHTML::_( 'select.genericlist', $opts, $itemName.'[]', 'class="inputbox select" multiple="multiple" size="'.$size.'"', 'value', 'text', $value, $itemId );
					break;
				case 'select_numeric':
					if ( $client == 'list' ) {
						$value		=	$itemValue;
					} else {
						$value		= ( $itemValue != '' ) ? $itemValue : $item->defaultvalue; 
					}
					$options 	= explode( '||', $item->options );
					$opts 		= array();
					if ( $item->selectlabel ) {
						$label	=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->selectlabel ) : $item->selectlabel;
						$opts[]	= JHTML::_('select.option',  '', '- '.$label.' -', 'value', 'text' );
					}
					if ( $options[0] != '' ) {
						$opts[]	= JHTML::_('select.option', $options[0], $options[0], 'value', 'text' );
					}
					$val	=	( $options[1] ? $options[1] : 0 );
					$val2 = $val;
					$step	=	( $options[2] ? $options[2] : 0 );
					$limit 	=	( $options[3] ? $options[3] : 0 );
					if ( $step && $val || $step && $limit || $step && $val && $limit ) {
						while ( 69 ) {
							if ( $item->bool == 0 && $val <= $limit  ) {
								$opts[]	=	JHTML::_('select.option', $val, $val, 'value', 'text' );
								$val	=	$val + $step;
							} else if ( $item->bool == 1 && $val <= $limit  ) {
								$opts[]	=	JHTML::_('select.option', $val, $val, 'value', 'text' );
								$val	=	$val * $step;
							} else if ( $item->bool == 2 && $val >= $limit  ) {
								$opts[]	=	JHTML::_('select.option', $val, $val, 'value', 'text' );
								$val	=	$val - $step;
							} else if ( $item->bool == 3 && $val > $limit  ) {
								$opts[]	=	JHTML::_('select.option', $val, $val, 'value', 'text' );
								$val	=	floor( $val / $step );
							} else {
								break;
							}
						}
					}
					if ( $options[4] != '' ) {
						$opts[]	= JHTML::_('select.option', $options[4], $options[4], 'value', 'text' );
					}
					$data	=	JHTML::_( 'select.genericlist', $opts, $itemName, 'class="inputbox select" size="1"', 'value', 'text', $value, $itemId );
					break;
				case 'select_simple':
					if ( $client == 'list' ) {
						$value		=	$itemValue;
					} else {
						$value			= ( $itemValue != '' ) ? $itemValue : $item->defaultvalue; 
					}
					$options = explode( '||', $item->options );
					if ( $item->ordering == 1 ) {
						natsort( $options );
						$optionsSorted = array_slice( $options, 0 );
					} else if ( $item->ordering == 2 ) {
						natsort( $options );
						$optionsSorted = array_reverse( $options, true );
					} else {
						$optionsSorted = $options;
					}
					$opts 		= array();
					if ( $item->selectlabel ) {
						$label	=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->selectlabel ) : $item->selectlabel;
						$opts[]	= JHTML::_('select.option',  '', '- '.$label.' -', 'value', 'text' );
					}
					if ( sizeof( $optionsSorted ) ) {
						foreach ( $optionsSorted as $val ) {
							if ( JString::strpos( $val, '=' ) !== false ) {
								$opt	=	explode( '=', $val );
								$opts[]	= JHTML::_('select.option',  $opt[1], $opt[0], 'value', 'text' );
							} else {
								$opts[]	= JHTML::_('select.option',  $val, $val, 'value', 'text' );
							}
						}
					}
					$data	=	JHTML::_( 'select.genericlist', $opts, $itemName, 'class="inputbox select" size="1"', 'value', 'text', $value, $itemId );
					break;
				case 'textarea':
					if ( $client == 'list' ) {
						$value		=	( $itemValue != '' ) ? CCKjSeblodItem_Form::br2nl( $itemValue ) : '';
					} else {
						$value		=	( $itemValue != '' ) ? CCKjSeblodItem_Form::br2nl( $itemValue ) : $item->defaultvalue;
					}
					$value		=	( $value != ' ' ) ? $value : '';
					$validation	= 	( $item->validation ) ? ' ' . $item->validation : '';
					$maxlength	=	( $item->maxlength ) ? 'onkeydown="this.value=this.value.substring(0, '.$item->maxlength.');"' : '';
					$data 		= 	'<textarea class="textarea '.$validation.'" id="'.$itemId.'" name="'.$itemName.'" cols="'.$item->cols.'" rows="'.$item->rows.'" '.$maxlength. 'style="'.$item->style.'" >'.$value.'</textarea>';
					break;
				case 'wysiwyg_editor':
					if ( $client == 'list' ) {
						$value		=	$itemValue;
					} else {
						$value		=	( $itemValue != '' ) ? $itemValue : $item->defaultvalue;
					}
					$value		=	( $value != ' ' ) ? $value : '';
					$validation	=	( $item->validation ) ? ' ' . $item->validation : '';
					$style		=	( $item->style ) ? 'style="'.$item->style.'"' : '';
					$data		=	'<input class="inputbox text '.$validation.'" type="text" id="'.$itemId.'" name="'.$itemName.'"'
								.	' maxlength="'.$item->maxlength.'" size="'.$item->size.'" value="'.$value.'" '.$style.' />';
					break;
  				//case 'alias':
					// 1ST-2ND CLASS NOT 3RD!
				  	//break;
				case 'file':
					$extensions	= explode( ',', $item->options );
					$path = substr( $item->location, 0, -1 );
					$files = JFolder::files( JPATH_SITE.DS.$path, '.', $item->bool, true );
					$optionsFileList 	= array();
					if ( $item->selectlabel && ! $item->bool4 ) {
						$label				=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->selectlabel ) : $item->selectlabel;
						$optionsFileList[]	=	JHTML::_('select.option',  '', '- '.$label.' -', 'value', 'text' );
					}
					if ( $files ) {
						foreach( $files as $val ) {
							$ext = substr( $val , strrpos( $val, '.' ) +1 );
							$val = str_replace( '\\', '/', $val );
							$val = substr( strstr( $val, $item->location ), strlen( $item->location ) );
							if ( array_search( $ext, $extensions ) !== false ) {
								$optionsFileList[] = JHTML::_( 'select.option', $val, $val );
							}
						}
					}
					if ( $itemValue && strpos( $itemValue, $item->location ) !== false ) {
						$file = str_replace( $item->location, '', $itemValue );
					}
					$selectedFileList = ( $itemValue && @$file ) ? $file : $itemValue;
					if ( $item->bool4 ) {
						$sep = ( $item->divider ) ? $item->divider : ',';
						$selectedFileList = explode( $sep, $selectedFileList );
						$list = JHTML::_( 'select.genericlist', $optionsFileList, $itemName.'[]', 'class="inputbox select" size="'.$item->rows.'" multiple="multiple"', 'value', 'text', $selectedFileList );
					} else {
						$list = JHTML::_( 'select.genericlist', $optionsFileList, $itemName, 'class="inputbox select" size="1"', 'value', 'text', $selectedFileList );
					}
					if ( $item->bool2 ) {
						$myoutput = '<input class="inputbox text notrequired-disabled" type="text" id="'.$itemName.'_location" name='.$itemName.'_location" maxlength="250"'
								  . 'size="32" value="'.$item->location.'" disabled="disabled" />';
					}
					$itemNameH = (strpos($itemName, '[]') !== false) ? substr( $itemName, 0, -2 ) : $itemName; //TODO other _hidden
					@$myoutput .= '<input class="inputbox" type="hidden" id="'.$itemNameH.'_hidden" name="'.$itemNameH.'_hidden" value="'.$item->location.'" />';
					$data = $myoutput.$list;
					break;
				case 'folder':
					$path = substr( $item->location, 0, -1 );
					$files = JFolder::folders( JPATH_SITE.DS.$path, '.', $item->bool, true );
					$optionsFileList 	= array();
					if ( $item->selectlabel && ! $item->bool4 ) {
						$label				=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->selectlabel ) : $item->selectlabel;
						$optionsFileList[]	=	JHTML::_('select.option',  '', '- '.$label.' -', 'value', 'text' );
					}
					if ( $files ) {
						foreach( $files as $val ) {
							$ext = substr( $val , strrpos( $val, '.' ) +1 );
							$val = str_replace( '\\', '/', $val );
							$val = substr( strstr( $val, $item->location ), strlen( $item->location ) );
							if ( $path == 'administrator/language' || $path == 'language' ) {
								if ( strpos( $val, '-' ) !== false ) {
									$optionsFileList[] = JHTML::_( 'select.option', $val, $val );
								}
							} else {
								$optionsFileList[] = JHTML::_( 'select.option', $val, $val );
							}
						}
					}
					//if ( $itemValue && strpos( $itemValue, '.' ) !== false ) {
					//	$file = substr( strstr( $itemValue, $item->location ), strlen( $item->location ) );
					//}
					if ( $itemValue && strpos( $itemValue, $item->location ) !== false ) {
						$itemValue	=	str_replace( $item->location, '', $itemValue );
					}
					$selectedFolder = ( $itemValue ) ? $itemValue : '';
					$list = JHTML::_( 'select.genericlist', $optionsFileList, $itemName, 'class="inputbox select" size="1"', 'value', 'text', $selectedFolder );
					if ( $item->bool2 ) {
						$myoutput = '<input class="inputbox text notrequired-disabled" type="text" id="'.$itemName.'_location" name='.$itemName.'_location" maxlength="250" size="32" value="
						'.$item->location.'" disabled="disabled" />';
					}
					@$myoutput .= '<input class="inputbox" type="hidden" id="'.$itemName.'_hidden" name="'.$itemName.'_hidden" value="'.$item->location.'" />';
					$data = $myoutput.$list;
					break;
				case 'media':
					$value 		=	( $itemValue != '' ) ? $itemValue : '';				
					
					$data	=	'<input id="sbfile" type="text" name="sbfile" value="" size="25" />';
					$data	.=	"<a href=\"javascript: openSwampyBrowser('sbfile', '', 'image', window);\">Select File or Image</a>";
					
					$modal		= 	HelperjSeblod_Display::quickModalImage( 'Image', '', $itemName, 'image', $item->id, 850, 570 );
					$data		=	$data.$modal;
					
					//$extra	 	=	'<input class="inputbox text" type="text" id="'.$itemName.'" name='.$itemName.'" maxlength="250" size="32" value="'.$value.'" disabled="disabled" />';
					//$extra2 	= 	'<input class="inputbox" type="hidden" id="'.$itemName.'_hidden" name="'.$itemName.'_hidden" value="'.$value.'" />';
					//$modal		=	HelperjSeblod_Display::quickModalImage( 'IMAGE', $item->location, $itemName, 'image', _MODAL_WIDTH -70, _MODAL_HEIGHT -180 );
					//$data 		=	$extra.$extra2.$modal;
					//$data		=	'<div style="float: left; vertical-align: middle;">'.$extra.$extra2.'</div><div style="float: left;">'.$modal.'</div>';
					break;
				case 'upload_image':
					$value		=	( $itemValue != '' ) ? $itemValue : '';
					$value		=	( $value != ' ' ) ? $value : '';
					$validation	=	( $item->validation ) ? ' ' . $item->validation : '';
					$style		=	( $item->style ) ? 'style="'.$item->style.'"' : '';
					$data		=	'<input class="inputbox text '.$validation.'" type="text" id="'.$itemId.'" name="'.$itemName.'"'
								.	' maxlength="'.$item->maxlength.'" size="'.$item->size.'" value="'.$value.'" '.$style.' />';
					break;
				case 'upload_simple':
					$value		=	( $itemValue != '' ) ? $itemValue : '';
					$value		=	( $value != ' ' ) ? $value : '';
					$validation	=	( $item->validation ) ? ' ' . $item->validation : '';
					$style		=	( $item->style ) ? 'style="'.$item->style.'"' : '';
					$data		=	'<input class="inputbox text '.$validation.'" type="text" id="'.$itemId.'" name="'.$itemName.'"'
								.	' maxlength="'.$item->maxlength.'" size="'.$item->size.'" value="'.$value.'" '.$style.' />';
					break;
				case 'free_text':
					$data	=	( $item->displayfield == -1 ) ? null : htmlspecialchars_decode($item->defaultvalue);
					break;
				//case 'content_type':
					// 1ST-2ND CLASS NOT 3RD!
					//break;
				//case 'field_x':
					// 1ST-2ND CLASS NOT 3RD!
					//break;
				//case 'panel_slider':
					//break;
				//case 'sub_panel_tab':
					//break;
				case 'joomla_menu':
					if ( ! $item->displayfield ) {
						$style		=	( $item->style ) ? 'style="'.$item->style.'"' : '';
						require_once ( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'html'.DS.'menutree.php' );
						$optParentItem = array();
						if ( $item->size == 1 && $item->selectlabel ) {
							$label	=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->selectlabel ) : $item->selectlabel;
							$optParentItem[]	= JHTML::_('select.option',  '', '- '.$label.' -', 'value', 'text' );
						}
						if ( $item->bool == 0 ) {		
							$selectParentType	=	str_replace( 'menutype-', '', $item->location );
							$optParentItem		=	array_merge( $optParentItem, JHTML::_( 'menutree.linkoptions', $selectParentType ) );
						} else {
							$optParentItem		=	array_merge( $optParentItem, JHTML::_( 'menutree.linkoptions' ) );
						}
						$selectParentItem	=	@$item->location;
						$data	=	JHTML::_( 'select.genericlist', $optParentItem, $itemName, 'class="inputbox select" size="'.$item->size.'" '.$style, 'value', 'text', 
											 $selectParentItem );
					} else {
						$data	=	null;
					}
					break;
				//case 'color_picker':
					//break;
				case 'calendar':
						$value 		= 	( $itemValue != '' && $itemValue != '0000-00-00 00:00:00' ) ? $itemValue : '';
						$format		=	$item->format;
						$value    	=   ( $value ) ? $value : '';
						
						$navigation		= ( $item->content == 9 ) ? '' : $item->location;
						//$navigation	=	( $item->content == -1 || $item->content == .5 ) ? 1 : $navigation;	// Buggy ??
						$navigations	=	( $navigation ) ? " navigation: \"$navigation\"" : " navigation: 0";
						$navigations	=	" navigation: \"$navigation\"";
						$style			=	( $item->style ) ? $item->style : 'default';
						$class 			=	( $navigation == 2 ) ? $style.'-alt' : $style.'-cal';
						$classes		=	"classes: [\"$class\"]";
						echo '<link rel="stylesheet" href="'._PATH_ROOT._PATH_CALENDAR.$style.'/'.$class.'.css" type="text/css" />';
						$data	=	null;
					$days	=	'days: [\''.JText::_('SUNDAY').'\', \''.JText::_('MONDAY').'\', \''.JText::_('TUESDAY').'\', \''.JText::_('WEDNESDAY').'\', \''.JText::_('THURSDAY').'\', \''.JText::_('FRIDAY').'\', \''.JText::_('SATURDAY').'\']';
					$months	=	'months: [\''.JText::_('JANUARY').'\', \''.JText::_('FEBRUARY').'\', \''.JText::_('MARCH').'\', \''.JText::_('APRIL').'\', \''.JText::_('MAY').'\', \''.JText::_('JUNE').'\', \''.JText::_('JULY').'\', \''.JText::_('AUGUST').'\', \''.JText::_('SEPTEMBER').'\', \''.JText::_('OCTOBER').'\', \''.JText::_('NOVEMBER').'\', \''.JText::_('DECEMBER').'\']';
						if ( $item->bool ) {
							if ( ! defined( '_ERROR_REFRESH_TRUE' ) ) {
								$value_y	=	( $item->bool2 ) ? substr( $value, strlen($value) - 4 ) : substr( $value, 0, 4 );
								$value		=	( $item->bool2 ) ? substr( $value, 0, -4 ) : substr( $value, 4 );
							} else {
								$value_y	=	JRequest::getVar( $itemName.'_calendar_year' );
							}
							$opts 	=	array();
							$limit	=	explode( '||', $item->options );										
							if ( ( $limit[0] - $limit[1] ) < 0 ) {
								for ( $i = $limit[0], $n = $limit[1]; $i <= $n; $i++ ) {
									$opts[]	=	JHTML::_('select.option', $i, $i, 'value', 'text' );
								}
							} else {
								for ( $i = $limit[0], $n = $limit[1]; $i >= $n; $i-- ) {
								$opts[]	=	JHTML::_('select.option', $i, $i, 'value', 'text' );
								}	
							}
							if ( $item->bool2 ) {
						    $data	.= 	'<input class="text" type="text" id="'.$itemId.'" name="'.$itemName.'"  value="'.$value.'" />';
						  }
							$data	.=	JHTML::_( 'select.genericlist', $opts, $itemName.'_calendar_year', 'class="inputbox select" size="1" style="margin-left: 8px;"', 'value', 'text', $value_y,
										$itemId.'_calendar_year' );
							echo '<script language="javascript" type="text/javascript"> window.addEvent(\'domready\', function() {'
								. $itemId.' = new Calendar({ '.$itemId.': { '.$itemId.'_calendar_year: "Y", '.$itemId.': "'.$format.'" }},'
							. '{ '.$days.', '.$months.', '.$classes.', '.$navigations.' }); });</script>';
						} else {
							$direction = ( $item->content == 9 ) ? '' : $item->content;
							if ( $item->type == 11 ) { //TODO::BUGGY
								$direction	=	0;
							}
							$directions = ( $direction != '' ) ? "direction: \"$direction\", " : '';
							echo '<script language="javascript" type="text/javascript"> window.addEvent(\'domready\', function() {'
							. $itemId.' = new Calendar({ '.$itemId.': "'.$format.'" }, { '.$days.', '.$months.', '.$classes.', '.$directions.' '.$navigations.' }); });</script>';
						}
						if ( ! $item->bool2 ) {
						  $data	.= 	'<input class="text" type="text" id="'.$itemId.'" name="'.$itemName.'"  value="'.$value.'" />';
						}
					break;
				//case 'joomla_user':
					//break;
				case 'search_action':
						$method	=	( $item->bool ) ? 'post' : 'get';
						if ( $client == 'cart' ) {
							$view	=	'cart';
							$layout	=	'products';
						} else {
							$view	=	'search';
							$layout	=	'search';
						}
						$target	=	( $item->bool7 ) ? '_blank' : '_self';
						$data	=	'<form target="'.$target.'" action="index.php?option=com_cckjseblod&amp;view='.$view.'&amp;layout='.$layout.'&amp;task=search" method="'.$method.'" id="'.$itemName.$ran.'" name="'.$itemName.$ran.'">';
					break;
				case 'search_generic':
					$value		=	$itemValue;
					$value		=	( $value != ' ' ) ? $value : '';
					$validation	=	( $item->validation ) ? ' ' . $item->validation : '';
					$style		=	( $item->style ) ? 'style="'.$item->style.'"' : '';
					$data		=	'<input class="inputbox text '.$validation.'" type="text" id="'.$itemId.'" name="'.$itemName.'"'
								.	' maxlength="'.$item->maxlength.'" size="'.$item->size.'" value="'.$value.'" '.$style.' />';
					break;
				case 'search_stage':
					$optStage		=	array();
					$optStage[] 	=	JHTML::_( 'select.option', '', '- '.JText::_( 'SELECT STAGE RESULT' ).' -' );
					$optStage[] 	=	JHTML::_( 'select.option', '1', JText::_( 'TEMP1' ) );
					$optStage[] 	=	JHTML::_( 'select.option', '2', JText::_( 'TEMP2' ) );
					$optStage[] 	=	JHTML::_( 'select.option', '3', JText::_( 'TEMP3' ) );
					$optStage[] 	=	JHTML::_( 'select.option', '4', JText::_( 'TEMP4' ) );
					$selectStage	=	( $itemValue ) ? $itemValue : '';
					$data		=	JHTML::_( 'select.genericlist', $optStage, $itemName, 'size="1" class="inputbox"', 'value', 'text', $selectStage, $itemId );
					break;
				case 'search_operator':
					$data		=	'<input type="hidden" name="'.$itemName.'" id="'.$itemId.'" value="" />';
				default:
					break;
			}
			
		return @$data;
	}
	
	/**
     * Get Data I
     **/
	function getDataI( &$item, $itemValue, $itemId, $itemName, $client, $artId, $fullscreen, $actionMode, &$row, $lang_id, $cat_id, $ran, $parent = null ) {
		$where		=	( $client == 'admin' ) ? 'controller' : 'view';
		
		switch ( $item->typename ) {
				case 'alias':
					$extended				=	CCKjSeblodItem_Form::getContentItem( $item->extended );
					//
					$extended->id			=	$item->id;
					$extended->title		=	$item->title;
					$extended->name			=	$item->name;
					$extended->category 	=	$item->category;
					$extended->type		 	=	$item->type;
					//
					$extended->light		=	$item->light;
					$extended->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
					if ( ! ( $extended->typename == 'panel_slider' || $extended->typename == 'sub_panel_tab'
						  || $extended->typename == 'hidden' || $extended->typename == 'query_url' || $extended->typename == 'query_user' ) ) {
						$extended->display	=	$item->display;
					}
					$extended->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$extended->id.
												'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$extended->label;
					$extended->container	=	$item->name.'_container';
					$extended->form 		=	CCKjSeblodItem_Search::getDataII( $extended, $itemValue, $itemId, $itemName, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $ran );										
					$data 					=	clone $extended;
					break;
				case 'field_x':
					//$search = ( $parent ) ? $parent : $item->name;
					//$regexItemX =	'#\|\|'.$search.'\|\|(.*?)\|\|/'.$search.'\|\|#s';
					$regexItemX =	( $parent ) ? '#\|\|'.$parent.'\|\|(.*?)\|\|/'.$parent.'\|\|#s' : '#\.\.'.$item->name.'\.\.(.*?)\.\./'.$item->name.'\.\.#s' ;
					preg_match_all( $regexItemX, $itemValue, $XMatches );
					$extended	=	CCKjSeblodItem_Form::getContentItem( $item->extended );
					//
					$extended->id				=	$item->id;
					$extended->title			=	$item->title;
					$extended->name				=	$item->name;
					$extended->category 		=	$item->category;
					$extended->type		 		=	$item->type;
					//
					for ( $xi=0, $xn=$item->rows; $xi<$xn; $xi++ ) {
					//for ( $xi=0, $xn=count($XMatches[1]); $xi<$xn; $xi++ ) {
						$extended->form			=	CCKjSeblodItem_Search::getDataII( $extended, @$XMatches[1][$xi], $extended->name, $extended->name.'[]', $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $ran );
						$extended->light		=	$extended->light;
						$extended->label		=	( $extended->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
						//$suffix					=	$xi + 1;
						//$extended->label		.=	' ('. $suffix .')';
						$extended->display		=	$extended->display;//?
						$extended->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$extended->id.
													'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$extended->label;
						$extended->container	=	$item->name.'-'.$xi.'_container';
						$data[$xi] 		=	clone $extended;
					}
					break;
				default:
						$item->form		=	CCKjSeblodItem_Search::getDataII( $item, $itemValue, $itemId, $itemName, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $ran );
						$data			= 	clone $item;
					break;
			}
			
		return $data;
	}
	
	/**
     * Get Data
     **/
	function getData( &$item, $itemValue, $client, $artId, $fullscreen, $actionMode, &$row, &$rowU, $lang_id, $cat_id, $ran = null )
	{
		$objVal		=	null;
		$where		=	( $client == 'admin' ) ? 'controller' : 'view';
		
		switch ( $item->typename ) {
			case 'joomla_content':
				if ( $artId == -1 ) {
					$item->display	=	-1;
					$data	=	null;
				} else {
					$extended	=	CCKjSeblodItem_Form::getContentItem( $item->extended );
					JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
					if ( $artId ) {
						if ( $actionMode == 1 ) {
							$itemName	=	$item->name;
							if ( array_key_exists( $itemName, get_object_vars( $row ) ) ) {
								$itemValue	=	$row->$itemName;
							}
						} else {
							if ( $item->name == 'frontpage' ) {
								$res	=	CCKjSeblodItem_Form::getResultFromDatabase( 'SELECT COUNT(content_id) FROM #__content_frontpage WHERE content_id ='.$artId );
								$itemValue	=	( $res ) ? 1 : 0;
							} else {
								$itemName	=	$item->name;
								if ( array_key_exists( $itemName, get_object_vars( $row ) ) ) {
									$itemValue	=	$row->$itemName;
								} else {
									if ( strpos( $item->extended, 'jcontentparams' ) !== false ) {
										$aparams	=	new JParameter( $row->attribs );
										$itemValue	=	$aparams->get( $itemName );
									} else if ( strpos( $item->extended, 'jcontentmeta' ) !== false ) {
										if ( $itemName == 'meta_robots' || $itemName == 'meta_author' ) {
											$aparams	=	new JParameter( $row->metadata );
											$realName	=	substr( $itemName, 5 );
											$itemValue	=	$aparams->get( $realName );
										} else if ( $itemName == 'meta_desc' ) {
											$itemValue	=	$row->metadesc;
										} else if ( $itemName == 'meta_key' ) {
											$itemValue	=	$row->metakey;
										}
									} else { }
								}
							}
						}
					} else {
						if ( $item->extended == 'jcontentdetailscreated_by' ) {
							$cur_user 	=&	JFactory::getUser();
							$itemValue	=	$cur_user->id;
						}
					}
					$itemName 				=	substr( $extended->name, 0, ( strlen( $item->name ) * -1 ) ) . '[' . $item->name . ']';
					//
					$saveId					=	$extended->id;
					$extended->id			=	$item->id;
					$extended->title		=	$item->title;
					$extended->name			=	$item->name;
					$extended->category 	=	$item->category;
					$extended->type		 	=	$item->type;
					//
					$extended->label		=	( $extended->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $extended->label ) : $extended->label ) : $extended->title;
					$extended->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$saveId.
												'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$extended->label;
					$extended->container	=	'jcontent'.$item->name.'_container';
					$extended->form 		=	CCKjSeblodItem_Search::getDataII( $extended, $itemValue, $item->name, $itemName, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $ran );
					$data 					=	$extended;
				}
				break;
			case 'alias':
				$extended				=	CCKjSeblodItem_Form::getContentItem( $item->extended );
				if ( $extended->type == 12 || $extended->type == 15 || $extended->type == 35 ) {
					$data	=	null;
				} else {
					//
					//$extended->id			=	$item->id;
					$extended->title		=	$item->title;
					$extended->name			=	$item->name;
					$extended->category 	=	$item->category;
					$extended->type		 	=	$item->type;
					//
					$extended->light		=	$item->light;
					$extended->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
					if ( ! ( $extended->typename == 'panel_slider' || $extended->typename == 'sub_panel_tab'
						  || $extended->typename == 'hidden' || $extended->typename == 'query_url' || $extended->typename == 'query_user' ) ) {
						$extended->display	=	$item->display;
					}
					$extended->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$extended->id.
												'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$extended->label;
					$extended->container	=	$item->name.'_container';
					$data 					=	CCKjSeblodItem_Search::getDataI( $extended, $itemValue, $item->name, $item->name, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $ran );
				}
				break;
			case 'alias_custom':
				$extended				=	CCKjSeblodItem_Form::getContentItem( $item->extended );
				if ( $item->boolxtd ) {
					if ( $client != 'list' ) {
						$item->name		=	$extended->name;
					}
					$item->typename	=	$extended->typename;
					$extended		=	$item;
				}
				if ( $extended->type == 12 || $extended->type == 15 || $extended->type == 35 ) {
					$data	=	null;
				} else {
					//
					//$extended->id			=	$item->id;
					$extended->title		=	$item->title;
					if ( $client == 'list' ) {
						$extended->name			=	$item->name;
					}
					$extended->category 	=	$item->category;
					$extended->type		 	=	$item->type;
					// REAL ALIAS
					if ( $item->stylextd ) {
						$extended->typename		=	$item->stylextd;
					}
					//
					$extended->light		=	$item->light;
					$extended->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
					if ( ! ( $extended->typename == 'panel_slider' || $extended->typename == 'sub_panel_tab'
						  || $extended->typename == 'hidden' || $extended->typename == 'query_url' || $extended->typename == 'query_user' ) ) {
						$extended->display	=	$item->display;
					}
					$extended->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$extended->id.
												'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$extended->label;
					$extended->container	=	$item->name.'_container';
					$data 					=	CCKjSeblodItem_Search::getDataI( $extended, $itemValue, $extended->name, $extended->name, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $ran );
				}
				break;
			case 'ecommerce_cart':
					$item->extended		=	CCK::FIELD_cleanExtended( $item->extended );
					//	
					$extended			=	CCKjSeblodItem_Form::getContentItem( $item->extended );
					if ( $item->boolxtd ) {
						$item->name		=	$extended->name;
						$item->typename	=	$extended->typename;
						$extended		=	$item;
					}
					if ( $item->stylextd ) {
						$extended->typename		=	$item->stylextd;
					}
					$extended->label	=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
					$extended->tooltip	=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$item->id.
											'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$item->label;
					$extended->container	=	$item->name.'_container';
					$data 				=	CCKjSeblodItem_Search::getDataI( $extended, $itemValue, $item->extended, $item->extended, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, -1, $ran );
				break;
			case 'search_multiple':
				//
				$item->typename		=	$item->stylextd;
				//
				$item->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
				$item->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$item->id.
									'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$item->label;
				$item->container	=	$item->name.'_container';
				$data 				=	CCKjSeblodItem_Search::getDataI( $item, $itemValue, $item->name, $item->name, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $ran );
				break;
			case 'field_x':
				$extended				=	CCKjSeblodItem_Form::getContentItem( $item->extended );
				if ( $extended->type == 12 || $extended->type == 15 || $extended->type == 35 ) {
					$data	=	null;
				} else {
					$regexClassI			=	'#\|\|(.*?)\|\|(.*?)\|\|/(.*?)\|\|#s';
					preg_match_all( $regexClassI, $itemValue, $itemValueMatches );
					$xn =	count( $itemValueMatches[1] );
					$xn	=	( $xn ) ? $xn : $item->rows;
					//for ( $xi=0, $xn=$item->rows; $xi<$xn; $xi++ ) {
					for ( $xi=0; $xi<$xn; $xi++ ) {
						//
						$data[$xi]->id			=	$item->id;
						$data[$xi]->title		=	$item->title;
						$data[$xi]->name		=	$item->name;
						$data[$xi]->category 	=	$item->category;
						$data[$xi]->type		=	$item->type;
						//
						$data[$xi] 				=	CCKjSeblodItem_Search::getDataI( $extended, @$itemValueMatches[2][$xi], $item->name.'-'.$xi, $item->name.'[]', $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $ran, $item->name );
						//
						$data[$xi]->name		=	$item->name;
						$data[$xi]->light		=	$extended->light;
						$data[$xi]->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) :
													( ( $extended->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $extended->label ) : $extended->label ) : $extended->title );
						//$suffix					=	$xi + 1;
						$data[$xi]->substitute 	=	0;
						//$data[$xi]->label		.=	' ('. $suffix .')';
						$data[$xi]->display		=	$extended->display;//?
						$data[$xi]->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$extended->id.
													'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$data[$xi]->label;
						$data[$xi]->container	=	$item->name.'-'.$xi.'_container';	
					}
					$currentMax	=	$item->maxlength - $xi;
					echo '<script type="text/javascript">
						 var fieldmax_'.$item->name.' = '.$currentMax.';
						 </script>';
					echo '<script type="text/javascript">function addOption(fieldname){
						var max = eval("fieldmax_"+fieldname);
						if ( max > 0 ) {
							eval("fieldmax_"+fieldname+"--");
							var p = $("add-elem-parent-"+fieldname);
							var newElement = $("add-elem-child-0-"+fieldname).cloneNode(true);
							if(p) { p.adopt(newElement); }
						}
						}</script>';
				}
				break;
			case 'joomla_user':
				if ( $artId == -1 ) {
					$item->display	=	-1;
					$data	=	null;
				} else {			
					$itemName	=	$item->name;
					if ( ! $itemValue && $artId ) {
						if ( array_key_exists( $itemName, get_object_vars( $rowU ) ) ) {
							$itemValue	=	$rowU->$itemName;
						} else if ( $itemName == 'sendemail' ) {
							$itemValue	=	$rowU->sendEmail;
						} else {
							if ( strpos( $item->extended, 'juserparams' ) !== false ) {
								$aparams	=	new JParameter( $rowU->params );
								$itemValue	=	$aparams->get( $itemName );
							}
						}
					}
					$extended	=	CCKjSeblodItem_Form::getContentItem( $item->extended );
					$itemName 	=	substr( $extended->name, 0, ( strlen( $item->name ) * -1 ) ) . '[' . $item->name . ']';
					$saveId					=	$extended->id;
					$extended->id			=	$item->id;
					$extended->title		=	$item->title;
					$extended->name			=	$item->name;
					$extended->category 	=	$item->category;
					$extended->type		 	=	$item->type;
					//
					$extended->label		=	( $extended->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $extended->label ) : $extended->label ) : $extended->title;
					$extended->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$saveId.
												'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$extended->label;
					$extended->container	=	'juser'.$item->name.'_container';
					$extended->form 		=	CCKjSeblodItem_Search::getDataII( $extended, $itemValue, $item->name, $itemName, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $ran );
					$data 					=	$extended;
				}
				break;
			default:
				if ( $item->type == 12 || $item->type == 15 || $item->type == 28 || $item->type == 35 ) {
					$data	=	null;
				} else {
					$item->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
					$item->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$item->id.
											'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$item->label;
					$item->container	=	$item->name.'_container';
					$item->form			=	CCKjSeblodItem_Search::getDataII( $item, $itemValue, $item->name, $item->name, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $ran );
					$item->value		=	$itemValue;
					$data				= $item;				
				}
				break;
		}
		
		return $data;
	}

}
?>