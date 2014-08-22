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

// On Prepare Import CSV
switch ( $typename ) {
	case 'file':
	case 'folder':
		$pre	=	( @$fields[$fieldname]->bool3 ) ? @$fields[$fieldname]->location : '';
		$value	=	$pre.$value;
		$text	.=	'::'.$fieldname.'::'.$value.'::/'.$fieldname.'::<br />';
		break;
	case 'upload_image':
	case 'upload_simple':
		$pre	=	@$fields[$fieldname]->location;
		$value	=	$pre.$value;
		$text	.=	'::'.$fieldname.'::'.$value.'::/'.$fieldname.'::<br />';
		break;
	case 'save':
		if ( @$fields[$fieldname]->bool4 && @$fields[$fieldname]->indexedxtd ) {
			$contentId	=	CCK::KEY_getId( $fields[$fieldname]->indexedxtd, $value );
		} else {
			$contentId	=	$value;
		}
		$default['save']	=	$contentId;
		if ( @$fields[$fieldname]->substitute == 2 ) {
			if ( $value ) {
				$default['substitute'][]	=	CCK_DB_Result( 'SELECT title FROM #__categories WHERE id='.$contentId );
			}
		} else {}
		$text	.=	'::'.$fieldname.'::'.$contentId.'::/'.$fieldname.'::<br />';
		break;
	case 'hidden':
		if ( @$fields[$fieldname]->substitute == 1 ) {
			$default['substitute'][]	=	@$fields[$fieldname]->defaultvalue;
		}
		$text	.=	'::'.$fieldname.'::'.$value.'::/'.$fieldname.'::<br />';
		break;
	case 'radio':
		if ( @$fields[$fieldname]->substitute == 1 ) {
			$default['substitute'][]	=	$itemValue;
		} else if ( @$fields[$fieldname]->substitute == 2 ) {
			$default['substitute'][]	=	HelperjSeblod_Helper::getOptionText( $value, @$fields[$fieldname]->options );
		} else {}
		if ( @$fields[$fieldname]->indexed ) {
			$default['batchIndexed'][$default['nIndexed']]['name']	=	$fieldname;
			$default['batchIndexed'][$default['nIndexed']]['id']	=	$value;
			$default['nIndexed']++;
		}
		$text	.=	'::'.$fieldname.'::'.$value.'::/'.$fieldname.'::<br />';
		break;
	case 'text':
		if ( @$fields[$fieldname]->substitute ) {
			$default['substitute'][]	=	$value;
		}
		if ( @$fields[$fieldname]->indexedkey ) {
			if ( $value ) {
				$default['index_key_name']	=	$fieldname;
				$default['index_key_id']	=	$value;
				$contentId	=	CCK::KEY_getId( $fieldname, $value );
				if ( $contentId ) {
					$row->load( $contentId );
				}
			}
		}
		if ( @$fields[$fieldname]->indexed ) {
			$default['batchIndexed'][$default['nIndexed']]['name']	=	$fieldname;
			$default['batchIndexed'][$default['nIndexed']]['id']	=	$value;
			$default['nIndexed']++;
		}
		$text	.=	'::'.$fieldname.'::'.$value.'::/'.$fieldname.'::<br />';
		break;
	case 'select_dynamic':
		if ( @$fields[$fieldname]->bool4 && @$fields[$fieldname]->indexedxtd ) {
			$value	=	CCK::KEY_getId( $fields[$fieldname]->indexedxtd, $value );
		}
		$text	.=	'::'.$fieldname.'::'.$value.'::/'.$fieldname.'::<br />';
		break;
	case 'select_simple':
		if ( @$fields[$fieldname]->substitute == 1 ) {
			$default['substitute'][]	=	$itemValue;
		} else if ( @$fields[$fieldname]->substitute == 2 ) {
			$default['substitute'][]	=	HelperjSeblod_Helper::getOptionText( $value, @$fields[$fieldname]->options );
		} else {}
		if ( @$fields[$fieldname]->indexed ) {
			$default['batchIndexed'][$default['nIndexed']]['name']	=	$fieldname;
			$default['batchIndexed'][$default['nIndexed']]['id']	=	$value;
			$default['nIndexed']++;
		}
		$text	.=	'::'.$fieldname.'::'.$value.'::/'.$fieldname.'::<br />';
		break;
	case 'field_x':
		$vals = explode( '||', $value );
		$temp = '';
		if ( sizeof( $vals ) ) {
			foreach( $vals as $val ) {
				if ( $val ) {
					$temp .= '||'.$fieldname.'||'.$val.'||/'.$fieldname.'||<br />';
				}
			}
		}
		$temp	=	( $temp ) ? '<br />'.$temp : '';
		$text	.=	'::'.$fieldname.'::'.$temp.'::/'.$fieldname.'::<br />';
		break;
	case 'content_type':
		$groups	=	explode( '|G|', $value );
		$more_text	=	'';
		if ( sizeof( $groups ) ) {
			$g	=	0;
			foreach( $groups as $group ) {
				if ( $group ) {
					$more_text	=	'<br />::jseblod_'.$fieldname.'::'.@$fields[$fieldname]->extended.'::/jseblod_'.$fieldname.'::';
					$vals	=	explode( '|F|', $group );
					if ( sizeof( $vals ) ) {
						$v	=	0;
						foreach ( $vals as $val ) {
							if ( @$subfields[$fieldname][$v]->typename == 'external_article' ) {
								if ( @$subfields[$fieldname][$v]->indexed ) {
									$default['batchIndexed'][$default['nIndexed']]['name']	=	@$subfields[$fieldname][$v]->name;
									$default['batchIndexed'][$default['nIndexed']]['id']	=	$val;
									$default['nIndexed']++;
								}
							}
							$more_text	.=	'<br />::'.@$subfields[$fieldname][$v]->name.'|'.$g.'|'.$fieldname.'::'.$val.'::/'.@$subfields[$fieldname][$v]->name.'|'.$g.'|'.$fieldname.'::';
							$v++;
						}
					}
					$more_text	.=	'<br />::jseblodend_'.$fieldname.'::::/jseblodend_'.$fieldname.'::';
					$g++;
				}
				@$group_text[$fieldname]	.=	'<br />'.$more_text;
			}
		}
		$text .= '::'.$fieldname.'::'.$g.'::/'.$fieldname.'::<br />';
		break;
	case 'joomla_readmore':
		if ( $value == 1 ) {
			$value	=	'<hr id="system-readmore" />';
			$text	.=	'::'.$fieldname.'::'.$value.'::/'.$fieldname.'::<br />';
		} else {
			$value	=	'';
			$text	.=	'';
		}
		break;
	case 'joomla_content':
		//$extended		=	CCKjSeblodItem_Content::getContentItem( @$fields[$fieldname]->extended );
		if ( $fieldname == 'frontpage' ) {
			//TODO
		} else {
			if ( array_key_exists( $fieldname, get_object_vars( $row ) ) ) {
				$row->$fieldname	=	$value;
			} else {
				if ( strpos( @$fields[$fieldname]->extended, 'jcontentparams' ) !== false ) {
					$default['params']	.=	$fieldname.'='.$value."\n";
				} else if ( $fieldname == 'meta_desc' ) {
					$default['meta']['desc']	=	$value;
				} else if ( $fieldname == 'meta_key' ) {
					$default['meta']['key']	=	$value;
				} else {
					$default['meta']['data']	.=	str_replace( 'meta_', '', $fieldname ).'='.$value."\n";
				}
			}
		}
		break;
	case 'joomla_user':
		if ( $default['action_mode'] == 2 ) {
			//$extended			=	CCKjSeblodItem_Content::getContentItem( @$fields[$fieldname]->extended );
			$rowU[$fieldname]	=	$value;
		}
		break;
	case 'external_article':
		if ( @$fields[$fieldname]->substitute == 2 ) {
			if ( $value ) {
				if ( @$fields[$fieldname]->bool4 && @$fields[$fieldname]->indexedxtd ) {
					$contentId	=	CCK::KEY_getId( $fields[$fieldname]->indexedxtd, $value );
				} else {
					$contentId	=	$value;
				}
				if ( $contentId ) {
					$default['substitute'][]	=	CCK_DB_Result( 'SELECT title FROM #__content WHERE id='.$contentId );
				}
			}
		}
		if ( @$fields[$fieldname]->indexed ) {
			$default['batchIndexed'][$default['nIndexed']]['name']	=	$fieldname;
			$default['batchIndexed'][$default['nIndexed']]['id']	=	$value;
			$default['nIndexed']++;
		}
		$text	.=	'::'.$fieldname.'::'.$value.'::/'.$fieldname.'::<br />';
		break;
	default:
		$text	.=	'::'.$fieldname.'::'.$value.'::/'.$fieldname.'::<br />';
		break;
}