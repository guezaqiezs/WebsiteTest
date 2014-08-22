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
 * CCKjSeblod		Item_Form Class
 **/
class CCKjSeblodItem_Form
{
	/**
	 * Get Data from Database
	 **/
	function getIntrotextFromJf( $cckId, $langId )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $cckId && $langId )
		{
			$query	= 'SELECT s.value'
					. ' FROM #__jf_content AS s'
					. ' WHERE s.reference_table="content" AND s.reference_field="introtext" AND s.reference_id='.$cckId.' AND s.language_id ='.$langId
					;
			$db->setQuery( $query );
			$introtext	=	$db->loadResult();
		}
		
		return $introtext;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getContentRowFromJf( $content, $cckId, $langId )
	{
		$db	=&	JFactory::getDBO();
			
		if ( $cckId && $langId )
		{
			$query	= 'SELECT s.*'
					. ' FROM #__jf_content AS s'
					. ' WHERE s.reference_table="content" AND s.reference_id='.$cckId.' AND s.language_id ='.$langId
					;
			$db->setQuery( $query );
			$tmp	=	$db->loadObjectList( 'reference_field' );
			
			$content->id		=	$cckId;
			$content->title		=	$tmp['title']->value;
			$content->alias		=	$tmp['alias']->value;
			$content->introtext	=	$tmp['introtext']->value;
			$content->fulltext	=	$tmp['fulltext']->value;
		}
		
		return $content;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getType( $typeId )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $typeId )
		{
			$query	= 'SELECT s.*'
					. ' FROM #__jseblod_cck_types AS s'
					. ' WHERE s.id = '.$typeId.' AND s.published = 1'
					;
			$db->setQuery( $query );
			$type	=	$db->loadObject();
		}
		
		return $type;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getTemplate( $templateId, $required )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $templateId )
		{
			$query	= 'SELECT s.id, s.name, s.mode'
					. ' FROM #__jseblod_cck_templates AS s'
					. ' WHERE s.id = '.$templateId.' AND s.published = 1'
					;
			$db->setQuery( $query );
			$template	=	$db->loadObject();
		}
		
		if ( $required ) {
			if ( ! @$template->id )
			{
				$query	= 'SELECT s.id, s.name, s.mode'
						. ' FROM #__jseblod_cck_templates AS s'
						. ' WHERE s.id = 1 AND s.published = 1'
						;
				$db->setQuery( $query );
				$template	=	$db->loadObject();
			}
		}
		
		return $template;
	}
	
	/**
	 * Get Items
	 **/
	function getItems( $contentTypeId, $client, $exclusion, $prename, $cck = false )
	{
		$db	=&	JFactory::getDBO();		
		
		if ( $client == 'all' )  {
			$where 	=	' WHERE cc.typeid = '.$contentTypeId;
		} else {
			$where 	=	' WHERE cc.client = "'.$client.'" AND cc.typeid = '.$contentTypeId;
		}
		$form 	=	true;
		if ( ! $form ) {
			$where	.=	' AND s.type != 25';
		}
		if ( $exclusion != '' ) {
			$where .= ' AND s.id NOT IN ('.$exclusion.')';
		}
		
		// ACL
		//$user		=&	JFactory::getUser();
		//$acl		=	','.$user->gid.',';
		//$where_acl	=	' AND ( cc.acl = "" OR cc.acl REGEXP ".*'.$acl.'.*" )';
		// ACL
		
		$orderby	=	' ORDER BY cc.ordering ASC';
		
		$query	= ' SELECT DISTINCT s.*, sc.name AS typename, cc.client, cc.submissiondisplay, cc.editiondisplay, cc.value as prevalue, cc.live, cc.acl'
				. ' FROM #__jseblod_cck_items AS s '
				. ' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
				. ' LEFT JOIN #__jseblod_cck_type_item AS cc ON cc.itemid = s.id'
				. $where
				//. $where_acl
				. $orderby
				;
		
		$db->setQuery( $query );
		$items	=	( $prename ) ? $db->loadObjectList( 'name' ) : $db->loadObjectList();
		
		if ( ! sizeof( $items ) ) {
			$items = array();
			return $items;
		}
		//
		if ( $cck == true ) {
			$substitute	=	null;
			$query	= ' SELECT COUNT(s.id)'
					. ' FROM #__jseblod_cck_items AS s'
					. ' LEFT JOIN #__jseblod_cck_type_item AS cc ON cc.itemid = s.id'
					. ' WHERE s.substitute > 0 AND cc.client = "'.$client.'" AND cc.typeid = '.$contentTypeId
					;
			$db->setQuery( $query );
			$substitute	=	$db->loadResult();
			if ( ! $substitute ) {
				$query	= ' SELECT s.*, sc.name AS typename'
						. ' FROM #__jseblod_cck_items AS s '
						. ' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
						. ' WHERE s.name = "title"'
						;
				$db->setQuery( $query );
				$itemTitle	=	$db->loadObjectList();
				// 4h dimension
				$itemTitle[0]->client				=	current($items)->client;
				$itemTitle[0]->submissiondisplay	=	'';
				$itemTitle[0]->editiondisplay		=	'';
				$itemTitle[0]->prevalue				=	'';
				$itemTitle[0]->live					=	'';
				$itemTitle[0]->acl					=	'';
				//
				if ( sizeof( $itemTitle ) ) {
					$f_action	=	array_shift( $items );
					$itemsplus	=	array_merge( $itemTitle, $items );
					array_unshift( $itemsplus, $f_action );
					return $itemsplus;
				}
			}
		}
		//
		
		return $items;
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
		
		$query	= ' SELECT DISTINCT s.*, sc.name AS typename, cc.client, cc.submissiondisplay, cc.editiondisplay, cc.value as prevalue, cc.live, cc.acl'
				. ' FROM #__jseblod_cck_items AS s '
				. ' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
				. ' LEFT JOIN #__jseblod_cck_type_item AS cc ON cc.itemid = s.id'
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
	 * Get Result From Database
	 **/
	function getObjectFromDatabase( $query )
	{
		$db	=&	JFactory::getDBO();
		
		$db->setQuery( $query );
		$object	=	$db->loadObject();
		
		return $object;
	}

	function getResultFromDatabase( $query )
	{
		$db	=&	JFactory::getDBO();
		
		$db->setQuery( $query );
		$result	=	$db->loadResult();
		
		return $result;
	}

	function getResultArrayFromDatabase( $query )
	{
		$db	=&	JFactory::getDBO();
		
		$db->setQuery( $query );
		$result	=	$db->loadResultArray();
		
		return $result;
	}
	
	function getObjectListFromDatabase( $query )
	{
		$db	=&	JFactory::getDBO();
		
		$db->setQuery( $query );
		$result	=	$db->loadObjectList();
		
		return $result;
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
	 * Get Content Type Name
	 **/
	function getContentTypeName( $contentTypeId )
	{
		$query	= 'SELECT name'
				. ' FROM #__jseblod_cck_types'
				. ' WHERE id = '.$contentTypeId
				;
		$this->_db->setQuery( $query );
		$contentTypeName	=	$this->_db->loadResult();
		
		return $contentTypeName;
	}

	/**
	 * Get List From Database
	 **/
	function getListFromDatabase( $query )
	{
		$db	=&	JFactory::getDBO();
		
		$db->setQuery( $query );
		$listElements	=	$db->loadObjectList();
		
		return $listElements;
	}
	
	/**
	 * Get List From Database
	 **/
	function getDynamicSelectInfoFromDatabase( $item )
	{
		$db	=&	JFactory::getDBO();
		
		$query	= ' SELECT s.options, s.location, s.required, s.name, s.selectlabel, s.content, s.extra, s.options2, s.id'
				. ' FROM #__jseblod_cck_items AS s '
				. ' WHERE s.name = "'.$item.'"'
				;
		$db->setQuery( $query );
		$itemInfo	=	$db->loadObject();
		
		return $itemInfo;
	}
	
	function getDynamicSelectInfoFromDatabasebyName( $item )
	{
		$db	=&	JFactory::getDBO();
		
		$query	= ' SELECT CONCAT(s.options, (CONCAT("||", (CONCAT(s.location, (CONCAT("||", (CONCAT(s.required, (CONCAT("||", (CONCAT(s.name, (CONCAT("||", s.selectlabel) )) )) )) )) )) )) ) )'
				. ' FROM #__jseblod_cck_items AS s '
				. ' WHERE s.name = "'.$item.'"'
				;
		$db->setQuery( $query );
		$itemInfo	=	$db->loadResult();
		
		return $itemInfo;
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
	 * Perform Br2nl
	 **/
	function br2nl($text)
	{
		return  preg_replace('/\<br(\s*)?\/?\>/i', "\n", $text);
	}
	
	function hexrgb( $hexstr ) {	
	    $int = hexdec( $hexstr );
		
    	return array( "r" => 0xFF & ($int >> 0x10), "g" => 0xFF & ($int >> 0x8), "b" => 0xFF & $int );
	}
	
	/**
	 * Get Display Variation
	 **/
	function getDisplayVariation( $data, $formdisplay, $value, $text, $form, $itemId, $itemName, $html, $more = '' ) {
		$data->form		=	'<input class="inputbox" type="hidden" id="'.$itemId.'" name="'.$itemName.'" value="'.$value.'" />';		
		
		if ( $formdisplay == 'value' ) {
			$data->form	.=	$text;
			$data->form	.=	$more;
		} else if ( $formdisplay == 'disabled' ) {
			if ( $html ) {
				$data->form	.=	str_replace( $html, $html.' disabled="disabled"', $form );
				$data->form	=	str_replace( array( 'required required-enabled', 'validate-one-required required-enabled' ), array( '', '' ), $data->form );
				$data->form	.=	$more;
			} else {
				$data->form	.=	'########'; // TODO !
			}
		} else {
			$data->display = 0;
			$data->form	.=	$more;
		}
		
		return $data;
	}
	
	/**
     * Get Data II
     **/
	function getDataII( &$item, $itemValue, $itemId, $itemName, $client, $artId, $fullscreen, $actionMode, &$row, $lang_id, $cat_id, $k = -1, $ran ) {
		global $mainframe;
		
		// ACL & Display (Submission/Edition) => Formdisplay
		if ( $item->acl ) {
			$user	=&	JFactory::getUser();
			if ( strpos( $item->acl, ','.$user->gid.',' ) === false ) {
				$item->formdisplay	=	'none';
			} else {
				$item->formdisplay	=	( $artId ) ? $item->editiondisplay : $item->submissiondisplay;				
			}
		} else {
			$item->formdisplay	=	( $artId ) ? $item->editiondisplay : $item->submissiondisplay;
		}
		//-
		
		$data			=	$item;
		$data->form		=	'';
		
		switch ( $item->typename ) {
				case 'external_article':
					$value		=	( $itemValue != '' ) ? $itemValue : '';
					$required	=	( $item->required ) ? 'required required-enabled' : '';
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
					$form		= 	JHTML::_( 'select.genericlist', $articles, $itemName, 'class="inputbox select '.$required.'" size="1"', 'value', 'text', $value, $itemId );
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$text	=	'';
						if ( $item->bool4 && $item->indexedxtd != '' && $value ) {
							$text	=	CCK_DB_Result( 'SELECT id FROM #__jseblod_cck_extra_index_key_'.$item->indexedxtd.' WHERE keyid="'.$value.'"' );
						}
						if ( $text != '' ) {
							$text	=	CCK_DB_Result( 'SELECT title FROM #__content WHERE id = '.$text );
						}
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $text, $form, $itemId, $itemName, '<select' );
					}
					$data->value	=	$value;
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
						$form	=	JHTML::_( 'select.booleanlist', $itemName, 'class="inputbox radio"', $value );
					} else {
						$form	= 	'<input class="inputbox" type="hidden" id="'.$itemId.'" name="'.$itemName.'" value="'.$value.'" />';
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$text	=	( $value == 1 ) ? JText::_( 'Yes' ) : JText::_( 'No' );
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $text, $form, $itemId, $itemName, '<input' );
					}
					$data->value	=	$value;
					break;
				//case 'joomla_content':
					//break;
				case 'external_subcategories':
					$value	=	( $itemValue != '' ) ? $itemValue : $item->bool;
					$form	=	JHTML::_( 'select.booleanlist', $itemName, 'class="inputbox radio"', $value );
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$text	=	( $value == 1 ) ? JText::_( 'Yes' ) : JText::_( 'No' );
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $text, $form, $itemId, $itemName, '<input' );
					}
					$data->value	=	$value;
					break;
				//case 'query_url':
					//break;
				//case 'query_user':
					//break;
				//case 'joomla_module':
					//break;
				case 'joomla_plugin_button':
					$value		=	( $itemValue != '' ) ? $itemValue : '';
					$required	=	( $item->required ) ? ( $value ? ' ' : '' ) : '';
					$validation	=	( $item->required ) ? '<input id="'.$itemName.'_required" name="'.$itemName.'_required" class="required required-enabled text" type="text" size="1" maxlength="0" style="width: 8px; height: 8px; text-align: center; cursor: default; margin-top: 5px; vertical-align: top;" disabled="disabled" value="'.$required.'" />&nbsp;' : '';
					$btPlugin	=	( $item->options ) ? array( $item->options ) : false;
					$btPlugins	=	HelperjSeblod_Helper::getPluginsButtonName();
					$buttons	=	array_diff($btPlugins, $btPlugin);
					$cckEditor	=&	JFactory::getEditor( 'cckjseblod' );
					$form		= 	'<textarea style="display: none;" id="'.$itemId.'" name="'.$itemName.'" cols="1" rows="1">'.$value.'</textarea>';
					$form		.=	$cckEditor->display( $itemName, '', 0, 0, $item->css, $validation, $buttons ) ;	
					
					$itemNameH	=	(strpos($itemName, '[]') !== false) ? substr( $itemName, 0, -2 ) : $itemName;
					if ( $value ) {
						$form	.=	'<input class="inputbox" type="hidden" id="'.$itemNameH.'_hidden" name="'.$itemNameH.'_hidden" value="'.htmlspecialchars($value).'" />';
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '' );
					}
					$data->value	=	$value;
					break;
				case 'form_action':
					$target	=	( $item->bool7 ) ? '_blank' : '_self';
					$form	=	( $client == 'admin' ) ? '' : '<form target="'.$target.'" enctype="multipart/form-data" action="index.php?option=com_cckjseblod&amp;view=type&amp;layout=form&amp;task=save&amp;cckid='.$artId.'&amp;Itemid='._ERROR_REFRESH_ITEMID.'" method="post" id="'.$itemName.$ran.'" name="'.$itemName.$ran.'">';
					$data->form		=	$form;
					$data->value	=	'';
					break;
				case 'captcha_image':
					if ( $artId && ( $item->gEACL == -1 || ( $item->gEACL == 1 && $client == 'site' ) ) ) {
						$form	=	null;
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
						$suffix		=	date("H-i-s");
						include_once( JPATH_SITE.DS.'media'.DS.'jseblod'.DS.'captcha-math'.DS.'captchaimage.php' );
						$required	=	( $item->required ) ? 'required required-enabled' : '';
						$form		=	'<img src="'.JURI::root( true ).'/tmp/jseblodcck-captcha/captcha-'.$suffix.'.jpg" style="margin-bottom: 6px; cursor: pointer;" alt="Captcha Image"'
									.	'title="Captcha Image" id="captcha" onclick="javascript:reloadCaptcha(this.src);" /><br />';
						$form		.=	'<input class="inputbox text '.$required.'" type="text"  name="cptcsecure" value="" size="'.$item->size.'" />';
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, '', '', $form, $itemId, $itemName, '' );
					}
					$data->value	=	'';
					break;
				case 'email':
					$value		=	( $itemValue != '' ) ? $itemValue : '';
					$value		=	( $value != ' ' ) ? $value : '';
					if ( $item->displayfield == 0 ) {
						$required	=	( $item->required ) ? 'required required-enabled' : '';
						$validation	=	( $item->validation ) ? ' ' . $item->validation : '';
						$form		=	'<input class="inputbox text '.$required.$validation.'" type="text" id="'.$itemId.'" name="'.$itemName.'" maxlength="250" size="'.$item->size.'" value="'.$value.'" />';
					} else {
						$form		= 	'<input class="inputbox" type="hidden" id="'.$itemId.'" name="'.$itemName.'" value="'.$value.'" />';
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '<input' );
					}
					$data->value	=	$value;
					break;
				case 'save':
					if ( ! $artId && $cat_id ) {
						$value		=	$cat_id;
					} else {
						$value		=	( @$row->catid != '' ) ? $row->catid : ( ( $itemValue != '' ) ? $itemValue : $item->defaultvalue );
					}
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
						$required		=	( $item->required ) ? 'validate-one-required required-enabled' : '';
						$orientation	=	( strstr( $item->format, '_' ) == '_v' ) ? true : false;
						$form			=	HelperjSeblod_Helper::radioList( $opts, $itemName, 'class="inputbox radio '.$required.'" size="1"', 'value', 'text', $value, $itemId, $orientation );
						$html			=	'<input';
					} else {
						$required	=	( $item->required ) ? 'required required-enabled' : '';
						$form		= 	JHTML::_( 'select.genericlist', $opts, $itemName, 'class="inputbox select '.$required.'" size="1"', 'value', 'text', $value, $itemId, $orientation);			
						$html		=	'<select';
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$text	=	'';
						if ( $value ) {
							$text	=	CCK_DB_Result( 'SELECT title FROM #__categories WHERE id = '.$value );
						}
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $text, $form, $itemId, $itemName, $html );
					}
					$data->value	=	$value;
					break;
				case 'button_free':
					$form	=	'<button class="'.$item->css.'" type="button" onclick="javascript: '.$item->options.'" name="'.$itemName
							.	'" style="'.$item->style.'">'.$item->label.'</button>';
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, '', '', $form, $itemId, $itemName, '' );
					}
					$data->value	=	'';
					break;
				case 'button_reset':
					$form	=	'<button class="'.$item->css.'" type="reset" name="'.$itemName.'">'.$item->label.'</button>';
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, '', '', $form, $itemId, $itemName, '' );
					}
					$data->value	=	'';
					break;
				case 'button_submit':
				  if ( $ran ) {
					   $form	=	'<button class="'.$item->css.'" type="button" onclick="javascript: submitbutton'.$ran.'(\'save\');" name="'.$itemName
					   			.	'" style="'.$item->style.'">'.$item->label.'</button>';
					} else {
					   $form	=	'<button class="'.$item->css.'" type="button" onclick="javascript: submitbutton(\'save\');" name="'.$itemName
					   			.	'" style="'.$item->style.'">'.$item->label.'</button>';
          			}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, '', '', $form, $itemId, $itemName, '' );
					}
					$data->value	=	'';
					break;
				case 'checkbox':
					$value		=	( $itemValue != '' ) ? $itemValue : $item->defaultvalue;
					$value		=	explode( $item->divider, $value );
					$required	 	= ( $item->required ) ? 'validate-one-required required-enabled' : '';
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
							if ( trim( $val ) ) {
								if ( JString::strpos( $val, '=' ) !== false ) {
									$opt	=	explode( '=', $val );
									$opts[]	= JHTML::_('select.option',  $opt[1], $opt[0], 'value', 'text' );
								} else {
									$opts[]	= JHTML::_('select.option',  $val, $val, 'value', 'text' );
								}
							}
						}
					}
					$orientation	=	( $item->bool ) ? true : false;
					$form		=	HelperjSeblod_Helper::checkBoxList( $opts, $itemName.'[]', 'class="inputbox checkbox '.$required.'"', 'value', 'text', $value, $itemId, $orientation, $item->cols, $item->bool2 );
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$text	=	CCKjSeblodItem_Form::getOptionText( $itemValue, $item->options, 1, $item->divider );
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $itemValue, $text, $form, $itemId, $itemName, '<input' );
					}
					$data->value	=	$value;
					break;
				case 'hidden':
					$value	=	( $itemValue != '' ) ? $itemValue : $item->defaultvalue;
					$form	=	'<input class="inputbox" type="hidden" id="'.$itemId.'" name="'.$itemName.'" value="'.$value.'" />';
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '' );
					}
					$data->value	=	$value;
					break;
				case 'password':
					if ( $actionMode == 2 && $artId ) {
						$value		=	'XXXX';
						$minlength	=	4;
					} else {
						$value		=	( $itemValue != '' ) ? $itemValue : '';
						$value		=	( $value != ' ' ) ? $value : '';
						$minlength	=	( $item->bool ) ? $item->bool : 6;
					}
					$required	=	( $item->required ) ? 'required required-enabled' : '';
					$validation	=	( $item->validation ) ? ' ' . $item->validation : '';
					$form	=	'<input class="inputbox password minLength '.$required.$validation.'" validatorProps="{minLength:'.$minlength.'}" type="password" id="'.$itemId.'" name="'.$itemName.'" maxlength="'.$item->maxlength.'" size="'.$item->size.'" value="'.$value.'" />';
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '<input' );
					}
					$data->value	=	$value;
					break;
				case 'radio':
					$value		=	( $itemValue != '' ) ? $itemValue : $item->defaultvalue;
					$required	 	= ( $item->required ) ? 'validate-one-required required-enabled' : '';
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
							if ( trim( $val ) ) {
								if ( JString::strpos( $val, '=' ) !== false ) {
									$opt	=	explode( '=', $val );
									$opts[]	= JHTML::_('select.option',  $opt[1], $opt[0], 'value', 'text' );
								} else {
									$opts[]	= JHTML::_('select.option',  $val, $val, 'value', 'text' );
								}
							}
						}
					}
					$orientation	=	( $item->bool ) ? true : false;
					$form			=	HelperjSeblod_Helper::radioList( $opts, $itemName, 'class="inputbox radio '.$required.'" size="1"', 'value', 'text', $value, $itemId, $orientation, $item->cols, $item->bool2 );
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$text	=	CCKjSeblodItem_Form::getOptionText( $value, $item->options );
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $text, $form, $itemId, $itemName, '<input' );
					}
					$data->value	=	$value;
					break;
				case 'text':
					if ( $artId && $item->substitute == 1 ) {
						$value	=	@$row->title;
					} else {
						$value	=	( $itemValue != '' ) ? $itemValue : $item->defaultvalue;
						$value	=	( $value != ' ' ) ? $value : '';
					}
					$value		=	htmlspecialchars( $value );
					$required	=	( $item->required ) ? 'required required-enabled' : '';
					$validation	=	( $item->validation ) ? ' ' . $item->validation : '';
					$style		=	( $item->style ) ? 'style="'.$item->style.'"' : '';

					$form		=	'<input class="inputbox text '.$required.$validation.'" type="text" id="'.$itemId.'" name="'.$itemName.'"'
								.	' maxlength="'.$item->maxlength.'" size="'.$item->size.'" value="'.$value.'" '.$style.' />';
					if ( $item->bool8 ) {
						$mark	=	explode( '||', $item->options2 );
						$min	=	( $mark[0] != '' ) ? $mark[0] : 1;
						$max	=	( $mark[1] != '' ) ? $mark[1] : 99;
					   	$form	=	'<span class="del" onclick="if($(\''.$itemId.'\').value && $(\''.$itemId.'\').value>='.($min+1).'){'
								.	'$(\''.$itemId.'\').value=$(\''.$itemId.'\').value.toInt()-1}else{$(\''.$itemId.'\').value='.$min.'}"></span>'
								.	$form
								.	'<span class="add" onclick="if($(\''.$itemId.'\').value && $(\''.$itemId.'\').value<='.($max-1).'){'
								.	'$(\''.$itemId.'\').value=$(\''.$itemId.'\').value.toInt()+1}else{$(\''.$itemId.'\').value='.$max.'}"></span>'
								;
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data		=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '<input' );
					}
					$data->value	=	$value;
					break;
				case 'select_dynamic':
					if ( ! $artId && $cat_id && $itemId == 'catid' ) {
						$value		=	$cat_id;
					} else if ( ! $artId && $cat_id && $itemId == 'sectionid' ) {
						$value		=	CCKjSeblodItem_Form::getResultFromDatabase( 'SELECT section FROM #__categories WHERE id='.$cat_id );
					} else {
						$value		= ( $itemValue != '' ) ? $itemValue : '';
					}
					$value			= ( $value != '' ) ? $value : $item->defaultvalue;
					if ( strpos( $value, '&amp;' ) !== false ) {
						$value	=	htmlspecialchars_decode( $value );
					}
					$required	 	= ( $item->required ) ? 'required required-enabled' : '';
					$opts	=	array();
					if ( $item->selectlabel ) {
						$label	=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->selectlabel ) : $item->selectlabel;
						$opts[]	= 	JHTML::_('select.option',  '', '- '.$label.' -', 'value', 'text' );
					}
					if ( $item->bool2 == 1 ) {
						//Free
						$query		=	$item->options;
					} else {
						// Construct
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
								$query		=	'SELECT '.$request[1].' AS text, '.$request[2].' AS value FROM '.$request[0].' WHERE '.$item->location
											.	' IN ( SELECT '.$item->location.' FROM '.$request[0].' WHERE '.$request[2].' = "'.$value.'" ) '.$whereclause.' ORDER BY '.$orderby;
							} else {
								if ( $artId ) {
									$parentName	=	CCK_DB_Result( 'SELECT name FROM #__jseblod_cck_items WHERE extended = "'.$item->name.'"' );
									if ( $parentName ) {
										$value	=	CCK_GET_ValueFromText( $row->introtext.$row->fulltext, $parentName );
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
					}
					if ( @$query ) {
						$getOpts = CCKjSeblodItem_Form::getListFromDatabase( $query );
					}
					if ( sizeof( @$getOpts ) ) {
						$opts	=	array_merge( $opts, $getOpts );
					}
					//
					$disabled	=	( $lang_id && ( $itemId == 'sectionid' || $itemId == 'catid' ) ) ? 'disabled="disabled"' : '';
					$form		=	JHTML::_( 'select.genericlist', $opts, $itemName, 'class="inputbox select '.$required.'" size="1" '.$disabled, 'value', 'text', $value, $itemId );
					// If Ajax Parent !
					if ( ( $item->bool == 2 || $item->bool == 3 ) && $item->extended && $item->elemxtd == 'item' && $item->bool2 != 1 ) {
						$itemInfo		=	CCKjSeblodItem_Form::getDynamicSelectInfoFromDatabase( $item->extended );
						if ( $k != -1 ) {
							$itemInfo->name	.=	'-'.$k;
						}
						if ( $itemInfo->selectlabel ) {
							$label	=	( _JTEXT_ON_LABEL == 1 ) ? JText::_( $itemInfo->selectlabel ) : $itemInfo->selectlabel;
						}
						$control = ( $client == 'site' ) ? '' : '&controller=interface';
						$script = '$("'.$itemId.'").addEvent("change",function(){
								var val = $("'.$itemId.'").value;
								var url="index.php?option=com_cckjseblod'.$control.'&format=raw&task=dynamicSelectAjax&item='.$item->extended.'&label='.$label.'&where="+val;
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
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data		=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '<select' );
					}
					$data->value	=	$value;
					break;	
				case 'select_multiple':
					$value		=	( $itemValue != '' ) ? $itemValue : $item->defaultvalue; 
					$value		=	explode( $item->divider, $value );
					$required	=	( $item->required ) ? 'required required-enabled' : '';
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
					$opts	=	array();
					if ( sizeof( $optionsSorted ) ) {
						foreach ( $optionsSorted as $val ) {
							if ( trim( $val ) ) {
								if ( JString::strpos( $val, '=' ) !== false ) {
									$opt	=	explode( '=', $val );
									$opts[]	= JHTML::_('select.option',  $opt[1], $opt[0], 'value', 'text' );
								} else {
									$opts[]	= JHTML::_('select.option',  $val, $val, 'value', 'text' );
								}
							}
						}
					}
					$size		=	( $item->rows ) ? $item->rows : count( $opts );
					$form	=	JHTML::_( 'select.genericlist', $opts, $itemName.'[]', 'class="inputbox select '.$required.'" multiple="multiple" size="'.$size.'"', 'value', 'text', $value, $itemId );
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$text	=	CCKjSeblodItem_Form::getOptionText( $itemValue, $item->options, 1, $item->divider );
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $itemValue, $text, $form, $itemId, $itemName, '<select' );
					}
					$data->value	=	$value;
					break;
				case 'select_numeric':
					$value		= ( $itemValue != '' ) ? $itemValue : $item->defaultvalue; 
					$required	= ( $item->required ) ? 'required required-enabled' : '';
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
					$form	=	JHTML::_( 'select.genericlist', $opts, $itemName, 'class="inputbox select '.$required.'" size="1"', 'value', 'text', $value, $itemId );
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '<select' );
					}
					$data->value	=	$value;
					break;
				case 'select_simple':
					$value			= ( $itemValue != '' ) ? $itemValue : $item->defaultvalue; 
					$required	 	= ( $item->required ) ? 'required required-enabled' : '';
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
							if ( trim( $val ) ) {
								if ( JString::strpos( $val, '=' ) !== false ) {
									$opt	=	explode( '=', $val );
									$opts[]	= JHTML::_('select.option',  $opt[1], $opt[0], 'value', 'text' );
								} else {
									$opts[]	= JHTML::_('select.option',  $val, $val, 'value', 'text' );
								}
							}
						}
					}
					$form	=	( sizeof( $opts ) ) ? JHTML::_( 'select.genericlist', $opts, $itemName, 'class="inputbox select '.$required.'" size="1"', 'value', 'text', $value, $itemId ) : '';
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$text	=	CCKjSeblodItem_Form::getOptionText( $value, $item->options );
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $text, $form, $itemId, $itemName, '<select' );
					}
					$data->value	=	$value;
					break;
				case 'textarea':
					$value		=	( $itemValue != '' ) ? CCKjSeblodItem_Form::br2nl( $itemValue ) : $item->defaultvalue;
					$value		=	( $value != ' ' ) ? $value : '';
					$required	=	( $item->required ) ? 'required required-enabled' : '';
					$validation	= 	( $item->validation ) ? ' ' . $item->validation : '';
					$maxlength	=	( $item->maxlength ) ? 'onkeydown="this.value=this.value.substring(0, '.$item->maxlength.');"' : '';
					$form		= 	'<textarea class="textarea '.$required.$validation.'" id="'.$itemId.'" name="'.$itemName.'" cols="'.$item->cols.'" rows="'.$item->rows.'" '.$maxlength. 'style="'.$item->style.'" >'.$value.'</textarea>';
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '<textarea' );
					}
					$data->value	=	$value;
					break;
				case 'wysiwyg_editor':
					$value 	= 	( $itemValue != '' ) ? $itemValue : $item->defaultvalue;
					$value	=	( $value != ' ' ) ? $value : '';	
					$required	= 	( $item->required ) ? ( $value ? 2 : 1 ) : 0;
					$user		=&	JFactory::getUser();
					
					if ( $item->importer == 1 && ! defined( '_IMPORTER_INTRO' ) && @$row->imported_intro != '' ) {
						$value	=	$row->imported_intro;
						define( '_IMPORTER_INTRO', 1 );
					}
					if ( $item->importer == 2 && ! defined( '_IMPORTER_FULL' ) && ( @$row->imported_full != '' || @$row->imported_text != '' ) ) {
						$value	=	( defined( '_IMPORTER_INTRO' ) ) ? $row->imported_full : $row->imported_text;
						define( '_IMPORTER_FULL', 1 );
					}
					if ( ! $user->id ) {
						$form	=	null;
					} else {
						if ( $item->bool ) {	// Default
	
							if ( $mainframe->isSite() && strpos( $item->format, 'tinypreset_' ) === false ) {

								$wysEditor	=&	JFactory::getEditor( $item->format ? $item->format : null );
								$form	=	$wysEditor->display( $itemName, $value, '100%', '280', '60', '20', array('pagebreak', 'readmore', 'cckjseblod') );
								
							} else {
								if ( $mainframe->isSite() ) {
									echo '<script type="text/javascript" src="'._PATH_ROOT.'/plugins/editors/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>';
								}
								echo HelperjSeblod_Display::quickWysiwyg( str_replace( 'tinypreset_', '', $item->format ) );
								$form	=	'<textarea class="mce_editable" id="'.$itemName.'" name="'.$itemName.'" cols="60" rows="20" style="width:100%; height:'.$item->height.'">'.$value.'</textarea>';
								
							}
							
						} else {	// Box
							
							$extra	=	'<textarea class="inputbox" style="display: none;" id="'.$itemId.'-wysiwyg" name="'.$itemName.'" cols="25" rows="3" style="overflow:hidden;">'
										.$value.'</textarea>';
							if ( strpos( $itemName, '[]' ) !== false ) {
								$itemNameH	=	substr( $itemName, 0, -2 );
								$extra2 	=	'<input class="inputbox" type="hidden" id="'.$itemId.'-wysiwyg_hidden" name="'.$itemNameH.'_hidden[]" value="" />';
							} else if ( $itemName[(strlen($itemName) - 1 )] == ']' ) {
								$itemNameH	=	substr( $itemName, 0, -1 );
								$extra2 	=	'<input class="inputbox" type="hidden" id="'.$itemId.'-wysiwyg_hidden" name="'.$itemNameH.'_hidden]" value="" />';
							} else {
								$extra2 	= 	'<input class="inputbox" type="hidden" id="'.$itemId.'-wysiwyg_hidden" name="'.$itemName.'_hidden" value="" />';
							}
							$modal	= 	HelperjSeblod_Display::quickModalWysiwygJs( 'Editor', '', $itemId.'-wysiwyg', 'pagebreak', $required, -1, $item->format, _MODAL_WIDTH, _MODAL_HEIGHT );
							$form	=	$extra.$extra2.$modal;
							
							$itemNameH	=	$itemId.'-wysiwyg_hidden';
							$script		=	'$("'.$itemNameH.'").value = $("'.$itemId.'-wysiwyg").value';
							echo '<script language="javascript" type="text/javascript">window.addEvent("domready",function(){'.$script.'});</script>';
							
						}
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '' );
					}
					$data->value	=	$value;
					break;
  				//case 'alias':
					// 1ST-2ND CLASS NOT 3RD!
				  	//break;
				case 'file':
					$required	= ( $item->required ) ? 'required required-enabled' : '';
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
							$val 	=	str_replace( '\\', '/', $val );
							$val 	=	substr( strstr( $val, $item->location ), strlen( $item->location ) );
							$cut	=	strrpos( $val, '.' );
							$ext	=	substr( $val, $cut+1 );
							if ( array_search( $ext, $extensions ) !== false ) {
								if ( $item->bool5 ) {
									$optionsFileList[] = JHTML::_( 'select.option', substr( $val, 0, $cut ), $val );
								} else {

									$optionsFileList[] = JHTML::_( 'select.option', $val, $val );
								}
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
						$list = JHTML::_( 'select.genericlist', $optionsFileList, $itemName.'[]', 'class="inputbox select '.$required.'" size="'.$item->rows.'" multiple="multiple"', 'value', 'text', $selectedFileList );
					} else {
						$list = JHTML::_( 'select.genericlist', $optionsFileList, $itemName, 'class="inputbox select '.$required.'" size="1"', 'value', 'text', $selectedFileList );
					}
					if ( $item->bool2 ) {
						$myoutput = '<input class="inputbox text notrequired-disabled" type="text" id="'.$itemName.'_location" name='.$itemName.'_location" maxlength="250"'
								  . 'size="32" value="'.$item->location.'" disabled="disabled" />';
					}
					$itemNameH = (strpos($itemName, '[]') !== false) ? substr( $itemName, 0, -2 ) : $itemName;
					@$myoutput .= '<input class="inputbox" type="hidden" id="'.$itemNameH.'_hidden" name="'.$itemNameH.'_hidden" value="'.$item->location.'" />';
					$form	=	$myoutput.$list;
					$value	=	$itemValue;
					if ( $item->bool6 != -1 && $itemValue && ! $item->bool4 ) {
						$title	=	strrpos( $itemValue, '/' ) ? substr( $itemValue, strrpos( $itemValue, '/' ) + 1 ) : $itemValue;
						if ( $item->bool6 == 1 ) {
							$form	.=	'&nbsp;&nbsp;<a href="'.JURI::root().$itemValue.'" target="_blank"><img src="'.JURI::root().'/media/jseblod/_icons/preview-default.png" alt="" /></a>';
						} else {
							$form	.=	'&nbsp;&nbsp;<a href="'.JURI::root().$itemValue.'" target="_blank">'.$title.'</a>';
						}
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '<select' );
					}
					$data->value	=	$value;
					break;
				case 'folder':
					$required	= ( $item->required ) ? 'required required-enabled' : '';
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
					$list = JHTML::_( 'select.genericlist', $optionsFileList, $itemName, 'class="inputbox select '.$required.'" size="1"', 'value', 'text', $selectedFolder );
					if ( $item->bool2 ) {
						$myoutput = '<input class="inputbox text notrequired-disabled" type="text" id="'.$itemName.'_location" name='.$itemName.'_location" maxlength="250" size="32" value="
						'.$item->location.'" disabled="disabled" />';
					}
					$itemNameH = (strpos($itemName, '[]') !== false) ? substr( $itemName, 0, -2 ) : $itemName;
					@$myoutput .= '<input class="inputbox" type="hidden" id="'.$itemNameH.'_hidden" name="'.$itemNameH.'_hidden" value="'.$item->location.'" />';
					$form	=	$myoutput.$list;
					$value	=	$itemValue;
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '<select' );
					}
					$data->value	=	$value;
					break;
				case 'media':
					$form	=	'coming soon...';
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, '', '', $form, $itemId, $itemName, '' );
					}
					$data->value	=	'';
					break;
				case 'upload_image':
					$value 		= 	( $itemValue != '' ) ? $itemValue : $item->location;
					$required	= 	( $item->required ) ? 'required required-disabled' : 'notrequired-disabled';
					if ( $item->bool2 ) {
						$extra		= 	'<input class="inputbox text '.$required.'" type="text" id="'.$itemName.'_location" name='.$itemName.'_location" maxlength="250" size="32" value="'
									.	$value.'" disabled="disabled" />';
					}
					$onchange	=	'';
					$chkbox		=	'';
					if ( strpos( $itemName, '[]' ) !== false ) { //FieldX
						$itemNameH	=	substr( $itemName, 0, -2 );
						$form_more 	=	'<input class="inputbox" type="hidden" id="'.$itemNameH.'_hidden[]" name="'.$itemNameH.'_hidden[]" value="'.$value.'" />';						
					} else if ( $itemName[(strlen($itemName) - 1 )] == ']' ) { //GroupX
						$itemNameH	=	substr( $itemName, 0, -1 );
						$form_more 	=	'<input class="inputbox" type="hidden" id="'.$itemNameH.'_hidden]" name="'.$itemNameH.'_hidden]" value="'.$value.'" />';
						if ( $item->bool7 && $itemValue ) {
							$onchange	=	'onchange="$(\''.$itemName.'_delete\').checked=true;"';
							$chkbox		=	'<input type="checkbox" id="'.$itemName.'_delete" name="'.$itemNameH.'_delete]" value="1" />';
						}
					} else { //Default
						$form_more	=	'<input class="inputbox" type="hidden" id="'.$itemName.'_hidden" name="'.$itemName.'_hidden" value="'.$value.'" />';
						if ( $item->bool7 && $itemValue ) {
							$onchange	=	'onchange="$(\''.$itemName.'_delete\').checked=true;"';
							$chkbox		=	'<input type="checkbox" id="'.$itemName.'_delete" name="'.$itemName.'_delete" value="1" />';
						}
					}
					$form	=	'<input class="inputbox file" type="file" id="'.$itemName.'" name="'.$itemName.'" size="'.$item->size.'" '.$onchange.' />';
					$form	=	@$extra.$form.$form_more;
					if ( $chkbox != '' ) {
						$span	=	'<span class="DescriptionTip" title="'.JText::_( 'DELETE FILE' ).'::'.JText::_( 'DELETE FILE DESCRIPTION' ).'">';
						$form	.=	'&nbsp;&nbsp;'.$span.$chkbox.'</span>';
					}
					$link		=	'javascript: SqueezeBox.fromElement(\''.JURI::root().$itemValue.'\', {handler: \'image\'});';
					if ( $item->bool6 != -1 && $itemValue ) {
						$title	=	strrpos( $itemValue, '/' ) ? substr( $itemValue, strrpos( $itemValue, '/' ) + 1 ) : $itemValue;
						if ( $item->bool6 > 1 ) {
							// 2 // 3 // 4 // 5
							if ( $item->bool6 == 2 ) {
								$width		=	( $item->width ) ? 'width="'.$item->width.'"' : '';
								$height		=	( $item->height ) ? 'height="'.$item->height.'"' : '';
								$preview	=	'<a href="javascript:void(0);" onclick="'.$link.'"><img src="'.JURI::root().$itemValue.'" alt="" '.$width.' '.$height
											.	' style="padding-bottom: 1px;" /></a>';
								$form		=	'&nbsp;&nbsp;'.$preview.'<br />'.$form;								
							} else {
								if ( $itemValue == $item->cc ) {
									$location	=	strrpos( $itemValue, '/' ) ? substr( $itemValue, 0, strrpos( $itemValue, '/' ) + 1 ) : '';
									$suffix		=	'';
									$suffix2	=	'';
								} else {
									$location	=	$item->location;
									if ( $item->bool3 ) {
										if ( $row && @$row->created_by ) {
											$suffix	=	$row->created_by.'/';
										} else {
											$suffix	=	'';
										}
									} else {
										$suffix	=	'';
									}
									if ( $item->bool4 && $artId > 0 ) {
										$suffix2	=	$artId.'/';
									} else {
										$suffix2	=	'';
									}
								}
								$preview	=	'<a href="javascript:void(0);" onclick="'.$link.'"><img src="'.JURI::root().$location.$suffix.$suffix2.'_thumb'.($item->bool6 - 2).'/'.$title
											.	'" alt="'.$title.'" style="padding-bottom: 1px;" /></a>';
								$form		=	'&nbsp;&nbsp;'.$preview.'<br />'.$form;	
							}
						} else if ( $item->bool6 == 1 ) {
							$preview	=	'<a href="javascript:void(0);" onclick="'.$link.'"><img src="'.JURI::root().'/media/jseblod/_icons/preview-default.png" alt="" /></a>';
							$form		.=	'&nbsp;&nbsp;'.$preview;
						} else {
							$preview	=	'<a href="javascript:void(0);" onclick="'.$link.'">'.$title.'</a>';
							$form		.=	'&nbsp;&nbsp;'.$preview;
						}
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$text	=	( @$preview ) ? $preview : $value;
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $text, $form, $itemId, $itemName, '<input', $form_more );
					}
					$data->value	=	$value;
					break;
				case 'upload_simple':
					$value 		= 	( $itemValue != '' ) ? $itemValue : $item->location;
					$required	= 	( $item->required ) ? 'required required-disabled' : 'notrequired-disabled';
					if ( $item->bool2 ) {
						$extra		= 	'<input class="inputbox text '.$required.'" type="text" id="'.$itemName.'_location" name='.$itemName.'_location" maxlength="250" size="32" value="'.
						$value.'" disabled="disabled" />';
					}
					$onchange	=	'';
					$chkbox		=	'';
					if ( strpos( $itemName, '[]' ) !== false ) { //FieldX
						$itemNameH	=	substr( $itemName, 0, -2 );
						$form_more 	=	'<input class="inputbox" type="hidden" id="'.$itemNameH.'_hidden[]" name="'.$itemNameH.'_hidden[]" value="'.$value.'" />';
					} else if ( $itemName[(strlen($itemName) - 1 )] == ']' ) { //GroupX
						$itemNameH	=	substr( $itemName, 0, -1 );
						$form_more 	=	'<input class="inputbox" type="hidden" id="'.$itemNameH.'_hidden]" name="'.$itemNameH.'_hidden]" value="'.$value.'" />';
						if ( $item->bool7 && $itemValue ) {
							$onchange	=	'onchange="$(\''.$itemName.'_delete\').checked=true;"';
							$chkbox		=	'<input type="checkbox" id="'.$itemName.'_delete" name="'.$itemNameH.'_delete]" value="1" />';
						}
					} else { //Default
						$form_more 	=	'<input class="inputbox" type="hidden" id="'.$itemName.'_hidden" name="'.$itemName.'_hidden" value="'.$value.'" />';
						if ( $item->bool7 && $itemValue ) {
							$onchange	=	'onchange="$(\''.$itemName.'_delete\').checked=true;"';
							$chkbox		=	'<input type="checkbox" id="'.$itemName.'_delete" name="'.$itemName.'_delete" value="1" />';
						}
					}
					$form 		=	'<input class="input_box" type="file" id="'.$itemName.'" name="'.$itemName.'" size="'.$item->size.'" '.$onchange.' />';
					$form		=	@$extra.$form.$form_more;
					if ( $chkbox != '' ) {
						$span	=	'<span class="DescriptionTip" title="'.JText::_( 'DELETE FILE' ).'::'.JText::_( 'DELETE FILE DESCRIPTION' ).'">';
						$form	.=	'&nbsp;&nbsp;'.$span.$chkbox.'</span>';
					}
					if ( $item->bool6 != -1 && $itemValue ) {
						$title	=	strrpos( $itemValue, '/' ) ? substr( $itemValue, strrpos( $itemValue, '/' ) + 1 ) : $itemValue;
						if ( $item->bool6 == 1 ) {
							$preview	=	'<a href="'.JURI::root().$itemValue.'" target="_blank"><img src="'.JURI::root().'/media/jseblod/_icons/preview-default.png" alt="" /></a>';
							$form		.=	'&nbsp;&nbsp;'.$preview;
						} else {
							$preview	=	'<a href="'.JURI::root().$itemValue.'" target="_blank">'.$title.'</a>';
							$form		.=	'&nbsp;&nbsp;'.$preview;
						}
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$text	=	( @$preview ) ? $preview : $value;
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $text, $form, $itemId, $itemName, '<input', $form_more );
					}
					$data->value	=	$value;
					break;
				//case 'ecommerce_cart':
					//break;
				case 'ecommerce_cart_button':
					$data->form	=	'';				
					break;
				case 'ecommerce_price':
					$data->form	=	'';
					break;
				//case 'web_service':
					//break;
				//case 'free_code':
					//break;
				case 'free_text':
					$value	=	$item->defaultvalue;
					$form	=	( $item->displayfield == -1 ) ? null : htmlspecialchars_decode($item->defaultvalue);
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '' );
					}
					$data->value	=	$value;
					break;
				//case 'content_type':
					// 1ST-2ND CLASS NOT 3RD!
					//break;
				//case 'field_x':
					// 1ST-2ND CLASS NOT 3RD!
					//break;
				case 'panel_slider':
					if ( $item->formdisplay == 'none' ) {
						$data->display = -1;
					}
					break;
				case 'sub_panel_tab':
					if ( $item->formdisplay == 'none' ) {
						$data->display = -1;
					}
					break;
				case 'joomla_menu':
					if ( ! $item->displayfield ) {
						$required	=	( $item->required ) ? 'required required-enabled' : '';
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
						$form	=	JHTML::_( 'select.genericlist', $optParentItem, $itemName, 'class="inputbox select '.$required.'" size="'.$item->size.'" '.$style, 'value', 'text', 
											 $selectParentItem );
					} else {
						$form	=	null;
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, '', '', $form, $itemId, $itemName, '' );
					}
					$data->value	=	'';
					break;
				//case 'color_picker':
					//break;
				case 'calendar':
					$value 		= 	( $itemValue != '' && $itemValue != '0000-00-00 00:00:00' ) ? $itemValue : '';
					$format		=	$item->format;
					if ( $format == 'd/m/Y' ) {
						$value	=	str_replace( '/', '-', $value );
					}
					$value    	=   ( $value ) ? date($item->format, strtotime( $value ) ) : '';
					$required	=	( $item->required ) ? 'required required-enabled' : '';
					
					$navigation = ( $item->content == 9 ) ? '' : $item->location;
					//$navigation = ( $item->content == -1 || $item->content == .5 ) ? 1 : $navigation;	// Buggy ??
					$navigations = ( $navigation ) ? " navigation: \"$navigation\"" : " navigation: 0";
					$navigations = " navigation: \"$navigation\"";
					$class 		=	( $navigation == 2 ) ? $item->style.'-alt' : $item->style.'-cal';
					$classes	=	"classes: [\"$class\"]";
					echo '<link rel="stylesheet" href="'._PATH_ROOT._PATH_CALENDAR.$item->style.'/'.$class.'.css" type="text/css" />';
					$form	=	null;
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
						$form	.= 	'<input class="text '.$required.'" type="text" id="'.$itemId.'" name="'.$itemName.'"  value="'.$value.'" />';
					  }
						$form	.=	JHTML::_( 'select.genericlist', $opts, $itemName.'_calendar_year', 'class="inputbox select '.$required.'" size="1" style="margin-left: 8px;"', 'value', 'text', $value_y,
									$itemId.'_calendar_year' );
						if ( ! $item->formdisplay ) {
							echo '<script language="javascript" type="text/javascript"> window.addEvent(\'domready\', function() {'
								. $itemId.' = new Calendar({ '.$itemId.': { '.$itemId.'_calendar_year: "Y", '.$itemId.': "'.$format.'" }},'
								. '{ '.$days.', '.$months.', '.$classes.', '.$navigations.' }); });</script>';
						}
					} else {
						$direction = ( $item->content == 9 ) ? '' : $item->content;
						$directions = ( $direction != '' ) ? "direction: \"$direction\", " : '';
						if ( ! $item->formdisplay ) {
							echo '<script language="javascript" type="text/javascript"> window.addEvent(\'domready\', function() {'
								. $itemId.' = new Calendar({ '.$itemId.': "'.$format.'" }, { '.$days.', '.$months.', '.$classes.', '.$directions.' '.$navigations.' }); });</script>';
						}
					}
					if ( ! $item->bool2 ) {
					  $form	.= 	'<input class="text '.$required.'" type="text" id="'.$itemId.'" name="'.$itemName.'"  value="'.$value.'" />';
					}
					if ( ! $item->formdisplay ) {
						$data->form	=	$form;
					} else {
						$data	=	CCKjSeblodItem_Form::getDisplayVariation( $data, $item->formdisplay, $value, $value, $form, $itemId, $itemName, '<input' );
					}
					$data->value	=	$value;
					break;
				//case 'joomla_user':
					//break;
				default:
					$data->value	=	'';
					break;
			}
			
		return $data;
	}
	
	/**
     * Get Data I
     **/
	function getDataI( &$item, $itemValue, $itemId, $itemName, $client, $artId, $fullscreen, $actionMode, &$row, $lang_id, $cat_id, $k = -1, $ran, $parent = null ) {
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
					// 4th dimension.
					$extended->client				=	$item->client;
					$extended->submissiondisplay	=	$item->submissiondisplay;
					$extended->editiondisplay		=	$item->editiondisplay;
					$extended->prevalue				=	$item->prevalue;
					$extended->live					=	$item->live;
					$extended->acl					=	$item->acl;
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
					$data			 		=	CCKjSeblodItem_Form::getDataII( $extended, $itemValue, $itemId, $itemName, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $k, $ran );		
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
					// 4th dimension.
					$extended->client				=	$item->client;
					$extended->submissiondisplay	=	$item->submissiondisplay;
					$extended->editiondisplay		=	$item->editiondisplay;
					$extended->prevalue				=	$item->prevalue;
					$extended->live					=	$item->live;
					$extended->acl					=	$item->acl;
					//
					for ( $xi=0, $xn=$item->rows; $xi<$xn; $xi++ ) {
					//for ( $xi=0, $xn=count($XMatches[1]); $xi<$xn; $xi++ ) {
						$extended				=	CCKjSeblodItem_Form::getDataII( $extended, @$XMatches[1][$xi], $extended->name, $extended->name.'[]', $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $k, $ran );
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
					$item			=	CCKjSeblodItem_Form::getDataII( $item, $itemValue, $itemId, $itemName, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $k, $ran );
					$data			= 	clone $item;
					break;
			}
			
		return $data;
	}
	
	/**
     * Get Data
     **/
	function getData( &$item, $itemValue, $client, $artId, $fullscreen, $actionMode, &$row, &$rowU, $lang_id, $cat_id, $contentMatches, $liveValues, $ran = null )
	{
		$k			=	-1;
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
									if ( $item->extended == 'jcontentdetailsmodified_by' && ! $itemValue  ) {
										$cur_user 	=&	JFactory::getUser();
										$itemValue	=	$cur_user->id;
									}
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
					// 4th dimension.
					$extended->client				=	$item->client;
					$extended->submissiondisplay	=	$item->submissiondisplay;
					$extended->editiondisplay		=	$item->editiondisplay;
					$extended->prevalue				=	$item->prevalue;
					$extended->live					=	$item->live;
					$extended->acl					=	$item->acl;
					//
					$extended->label		=	( $extended->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $extended->label ) : $extended->label ) : $extended->title;
					$extended->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$saveId.
												'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$extended->label;
					$extended->container	=	'jcontent'.$item->name.'_container';
					$extended		 		=	CCKjSeblodItem_Form::getDataII( $extended, $itemValue, $item->name, $itemName, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $k, $ran );
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
					// 4th dimension.
					$extended->client				=	$item->client;
					$extended->submissiondisplay	=	$item->submissiondisplay;
					$extended->editiondisplay		=	$item->editiondisplay;
					$extended->prevalue				=	$item->prevalue;
					$extended->live					=	$item->live;
					$extended->acl					=	$item->acl;
					//
					$extended->light		=	$item->light;
					$extended->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
					//
					if ( ! ( $extended->typename == 'panel_slider' || $extended->typename == 'sub_panel_tab'
						  || $extended->typename == 'hidden' || $extended->typename == 'query_url' || $extended->typename == 'query_user' ) ) {
						$extended->display	=	$item->display;
					}
					$extended->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$extended->id.
												'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$extended->label;
					$extended->container	=	$item->name.'_container';
					$data 					=	CCKjSeblodItem_Form::getDataI( $extended, $itemValue, $item->name, $item->name, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $k, $ran );
				}
				break;
			case 'alias_custom':			
				$extended				=	CCKjSeblodItem_Form::getContentItem( $item->extended );
				if ( $item->boolxtd ) {
					$item->name		=	$extended->name;
					$item->typename	=	$extended->typename;
					$extended		=	$item;
				}
				if ( $extended->type == 12 || $extended->type == 15 || $extended->type == 35 ) {
					$data	=	null;
				} else {
					//
					//$extended->id			=	$item->id;
					$extended->title		=	$item->title;
					$extended->category 	=	$item->category;
					$extended->type		 	=	$item->type;
					// REAL ALIAS
					if ( $item->stylextd ) {
						$extended->typename		=	$item->stylextd;
					}
					//
					$extended->light		=	$item->light;
					$extended->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
					// 4th dimension.
					$extended->client				=	$item->client;
					$extended->submissiondisplay	=	$item->submissiondisplay;
					$extended->editiondisplay		=	$item->editiondisplay;
					$extended->prevalue				=	$item->prevalue;
					$extended->live					=	$item->live;
					$extended->acl					=	$item->acl;
					//
					if ( ! ( $extended->typename == 'panel_slider' || $extended->typename == 'sub_panel_tab'
						  || $extended->typename == 'hidden' || $extended->typename == 'query_url' || $extended->typename == 'query_user' ) ) {
						$extended->display	=	$item->display;
					}
					$extended->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$extended->id.
												'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$extended->label;
					$extended->container	=	$item->name.'_container';
					$data 					=	CCKjSeblodItem_Form::getDataI( $extended, $itemValue, $extended->name, $extended->name, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $k, $ran );
				}
				break;
			case 'ecommerce_cart':
				break;
			case 'ecommerce_cart_button':
				break;
			case 'ecommerce_price':
				break;
			case 'content_type':
				$more_items 	=	CCKjSeblodItem_Form::getItemsGroup( $item->extended, $client, '', true );
				$rows			=	count( $liveValues );	// Live
				if ( $rows ) {
					$xn	=	$rows;
				} else {
					$xn	=	( $itemValue ) ? $itemValue : $item->rows;
				}
				for ( $xi=0; $xi<$xn; $xi++ ) {
					// Live
					if ( $rows ) {
						$liveContent	=	CCK::CONTENT_getValues( @$liveValues[$xi]->text );
					}
					//
					foreach( $more_items as $more_item ) {
						//
						$more_itemName	=	$more_item->name;
						$moreValue		=	'';
						
						if ( $artId ) {
							if ( sizeof( $contentMatches[1] ) ) {
								if ( ( @$aKey = array_search( $more_itemName.'|'.($xi).'|'.$item->name, $contentMatches[1] ) ) !== false ) {
									$moreValue	=	$contentMatches[2][$aKey];
								}
							}
						} else {
							// Live
							$liveName	=	'';
							$liveName	=	$more_item->prevalue;
							if ( $liveName == '' ) {
								$liveName	=	$more_itemName;
							}
							if ( $more_item->live == 'cart' ) {
								$moreValue	=	@$liveValues[$xi]->$liveName;
							} else if ( $more_item->live == 'product' ) {
								if ( ( $key = strpos( $liveName, '[' ) ) !== false ) {
									$liveNames[0]	=	substr( $liveName, 0, $key );
									$liveNames[1]	=	substr( $liveName, $key + 1, -1 );
									$liveExternal	=	CCK_DB_Object( 'SELECT * FROM #__jseblod_cck_items WHERE name ="'.$liveNames[0].'"' );
									if ( $liveExternal->bool4 && $liveExternal->indexedxtd ) {
										$liveId		=	CCK::KEY_getId( $liveExternal->indexedxtd, @$liveContent[$liveNames[0]] );
									}
									$moreValue	=	CCK_GET_Value( $liveId, $liveNames[1] );								
								} else {
									$moreValue	=	@$liveContent[$liveName];
								}
							} else {
							}
						}
						//
						$data[$xi][$more_itemName]->id			=	$more_item->id;
						$data[$xi][$more_itemName]->title		=	$more_item->title;
						$data[$xi][$more_itemName]->name		=	$more_item->name;
						$data[$xi][$more_itemName]->category 	=	$more_item->category;
						$data[$xi][$more_itemName]->type		=	$more_item->type;
						//
						$data[$xi][$more_itemName] 				=	CCKjSeblodItem_Form::getDataI( $more_item, $moreValue, $item->name.'-'.$xi.'-'.$more_item->name, $item->name.'['.$xi.']['.$more_item->name.']', $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $xi, $ran, $item->name );
						//
						@$data[$xi][$more_itemName]->name		=	$more_item->name;
						@$data[$xi][$more_itemName]->light		=	$more_item->light;
						@$data[$xi][$more_itemName]->label		=	( $more_item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $more_item->label ) : $more_item->label ) : $more_item->title;
						//$suffix				=	$xi + 1;
						@$data[$xi][$more_itemName]->substitute 	=	0;
						//$data[$xi]->label	=	'dasdas';
						//$data[$xi]->label		.=	' ('. $suffix .')';
						@$data[$xi][$more_itemName]->display		=	$more_item->display;//?
						@$data[$xi][$more_itemName]->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$more_item->id.
																		'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$more_item->label;
						@$data[$xi][$more_itemName]->container	=	$more_item->name.'-'.$xi.'_container';
						@$data[$xi][$more_itemName]->typename2	=	'content_type';
					}
				}
				$data['group']				=	$item;
				@$data['group']->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
				@$data['group']->tooltip	=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$item->id.
												'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.@$data['group']->label;
				@$data['group']->orientation=	$item->bool;
				@$data['group']->maximum	=	$item->maxlength;
				@$data['group']->repeatable	=	$item->bool2;
				@$data['group']->draggable	=	$item->bool3;
				@$data['group']->deletable	=	$item->bool4;
				$currentMax	=	$xi;
				echo '<script type="text/javascript">
					 var groupmax_'.$item->name.' = '.$currentMax.';
					 </script>';
				break;
			case 'field_x':
				$extended				=	CCKjSeblodItem_Form::getContentItem( $item->extended );
				// 4th dimension.
				$extended->client				=	$item->client;
				$extended->submissiondisplay	=	$item->submissiondisplay;
				$extended->editiondisplay		=	$item->editiondisplay;
				$extended->prevalue				=	$item->prevalue;
				$extended->live					=	$item->live;
				$extended->acl					=	$item->acl;
				//
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
						$data[$xi] 				=	CCKjSeblodItem_Form::getDataI( $extended, @$itemValueMatches[2][$xi], $item->name.'-'.$xi, $item->name.'[]', $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $k, $ran, $item->name );
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
						$data[$xi]->maximum		=	$item->maxlength;
						$data[$xi]->repeatable	=	$item->bool2;
						$data[$xi]->draggable	=	$item->bool3;
						$data[$xi]->deletable	=	$item->bool4;
					}
					$currentMax	=	$xi;
					echo '<script type="text/javascript">
						 var elemmax_'.$item->name.' = '.$currentMax.';
						 </script>';
				}
				break;
			case 'joomla_user':
				if ( $artId == -1 ) {
					$item->display	=	-1;
					$data	=	null;
				} else {			
					$itemName	=	$item->name;
					if ( ! $itemValue && $artId ) {
						if ( $rowU ) {
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
						} else {
							$itemValue	=	'';
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
					// 4th dimension.
					$extended->client				=	$item->client;
					$extended->submissiondisplay	=	$item->submissiondisplay;
					$extended->editiondisplay		=	$item->editiondisplay;
					$extended->prevalue				=	$item->prevalue;
					$extended->live					=	$item->live;
					$extended->acl					=	$item->acl;
					//
					$extended->label		=	( $extended->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $extended->label ) : $extended->label ) : $extended->title;
					$extended->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$saveId.
												'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$extended->label;
					$extended->container	=	'juser'.$item->name.'_container';
					$data			 		=	CCKjSeblodItem_Form::getDataII( $extended, $itemValue, $item->name, $itemName, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $k, $ran );
				}
				break;
			default:
				if ( $item->type == 12 || $item->type == 15 || $item->type == 22 || $item->type == 28 || $item->type == 35 || $item->type == 49 ) {
					$data	=	null;
				} else {
					$item->label		=	( $item->label ) ? ( ( _JTEXT_ON_LABEL == 1 ) ? JText::_( $item->label ) : $item->label ) : $item->title;
					$item->tooltip		=	'AJAX:index.php?option=com_cckjseblod&amp;'.$where.'=modal_tooltip&amp;cid[]='.$item->id.
											'&amp;format=raw&amp;from=items&amp;into=description&amp;legend='.$item->label;
					$item->container	=	$item->name.'_container';					
					$data				=	CCKjSeblodItem_Form::getDataII( $item, $itemValue, $item->name, $item->name, $client, $artId, $fullscreen, $actionMode, $row, $lang_id, $cat_id, $k, $ran );
					$data				=	$item;
				}
				break;
		}
		
		return $data;
	}

}
?>