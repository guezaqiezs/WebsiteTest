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

$mainframe->registerEvent( 'onSearch', 'plgSearchCCKjSeblod' );
//$mainframe->registerEvent( 'onSearchAreas', 'plgSearchCCKjSeblodAreas' );

JPlugin::loadLanguage( 'plg_search_cckjseblod' );

/**
 * plgSearchCCKjSeblodAreas
 */
function &plgSearchCCKjSeblodAreas()
{
	static $areas = array(
		'cckjseblod' => 'CckjSeblod'
	);
	
	return $areas;
}

/**
 * plgSearchCCKjSeblod
 */
function plgSearchCCKjSeblod( $text, $phrase='', $ordering='', $areas=null, $limit=50, $texts='', $sort='', $in=0, $length=1, $Itemid=0, $user=null, $cache=0, $stage=0, $stages=null, $debug=0 )
{
	global $mainframe;
	
	if ( is_array( $areas ) ) {
		if ( ! array_intersect( $areas, array_keys( plgSearchCCKjSeblodAreas() ) ) ) {
			return array();
		}
	} else {
		return array();		
	}
	if ( ! $user ) {
		$user	=&	CCK::USER_getUser();
	}
	
	require_once( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_search'.DS.'helpers'.DS.'search.php' );
	require_once( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'search.cckjseblod.php' );
		
	$db				=&	JFactory::getDBO();	
	$nullDate 		=	$db->getNullDate();
	$date 			=&	JFactory::getDate();
	$now			=	$date->toMySQL();
	$user2			=	null;
	
	$moreX			=	array();
	$where			=	'';
  	$where2			=	'';
	$where_a_state	=	' AND a.state = 1';
	$where_a_up		=	' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )';
	$where_a_down	=	' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )';
	
	if ( $texts && sizeof( $texts ) ) {
		$operator	=	1;
		$temp		=	'';
		
		foreach ( $texts as $field ) {

			$value	=	'';
			if ( $stage == $field->stage ) {
				if ( @$field->live ) {
					if ( $field->live == 'stage' ) {
						if ( $field->indexedxtd ) {
							if ( @$stages[$field->value] ) {
								$temp	=	str_replace( ' ', ',', @$stages[$field->value] );
								if ( $temp ) {
									$value	=	CCK_DB_ResultArray( 'SELECT keyid FROM #__jseblod_cck_extra_index_key_'.$field->indexedxtd.' WHERE id IN ( '.$temp. ')' );
									$value	=	implode( ' ', $value );
								} else {
									$temp	=	'';
									$value	=	$temp;
								}
							}
						} else {
							$temp	=	@$stages[$field->value];
							$value	=	$temp;
						}
					} else if ( $field->live == 'stage_user' ) {
						$temp	=	@$stages[$field->value];
						if ( $temp ) {
							$temp	=	str_replace( ' ', ',', $temp );
						  	$value	=	CCK_DB_ResultArray( 'SELECT DISTINCT(userid) FROM #__jseblod_cck_users WHERE contentid IN ( '.$temp. ')' );
						} else {
						  	$value  = '';
            					}
    						$field->searchmatch	=	'user_any_exact'; // TODONEW !!
					} else if ( $field->live == 'user' ) {
						if ( strpos( $field->value, '==' ) !== false ) {
							$live_val  =  explode( '==', $field->value );
						} else {
							$live_val[0]	=	$field->value;
							$live_val[1]	=	'';
						}
						// Profile
						$live_val[0]	=	str_replace( ' ', '', $live_val[0] );
						$profileFields	=	explode( ',', $live_val[0] );
						foreach (  $profileFields as $profileField ) {
							$userObj	=	'user';
							if ( strpos( $profileField, '{' ) !== false ) {
								$profileField	=	substr( $profileField, 0, -1 );
								$pField			=	explode( '{', $profileField );
								if ( ! $user2 && trim( @$user->$pField[0] ) ) {
									$user2	=	CCK::USER_getUser( $user->$pField[0] );
								}
								$profileField	=	$pField[1];
								$userObj		=	'user2';
							}
							if ( @${$userObj}->id && trim( @${$userObj}->$profileField ) != '' ) {
								$temp	=	trim( ${$userObj}->$profileField );
								$value	=	$temp;
								break;
							}
						}
						// Default
						if ( ! $value ) {
							$temp	=	$live_val[1];
							$value	=	$temp;
						}	
					} else if ( $field->live == 'url' ) {
						if ( strpos( $field->value, '==' ) !== false ) {
							$live_val  =  explode( '==', $field->value );
						} else {
							$live_val[0]	=	$field->value;
							$live_val[1]	=	'';
						}
						$temp	=	JRequest::getString( $live_val[0], '', 'GET' );
						$value	=	$temp;
						// Default
						if ( ! $value ) {
							$temp	=	$live_val[1];
							$value	=	$temp;
						}
					} else if ( $field->live == 'url_int' ) {
						if ( strpos( $field->value, '==' ) !== false ) {
							$live_val  =  explode( '==', $field->value );
						} else {
							$live_val[0]	=	$field->value;
							$live_val[1]	=	'';
						}
						$temp	=	JRequest::getInt( $live_val[0], 0, 'GET' );
						$value	=	$temp;
						// Default
						if ( ! $value ) {
							$temp	=	$live_val[1];
							$value	=	$temp;
						}
					} else {
						$temp	=	@$field->value;
						$value	=	$temp;
					}
				} else {
					$temp	=	@$field->value;
					$value	=	$temp;
				}

				// -- Group :: begin
				if ( $temp != '' ) {
					$more	=	'';
					if ( $field->groupname ) {
						$more	=	'\\\|[0-9]\\\|'.$field->groupname;
						if ( $field->live == 'stage' || $field->live == 'stage_user' ) {
							$moreX[$field->name]	=	explode( ',', $temp );
						} else {
							$moreX[$field->name]	=	array( 0 => $temp );
						}
					}
				}
				// -- Group :: end
				
				$helper	=	@$field->helper;
				$helper2=	( @$field->helper2 == -1 ) ? $length : $field->helper2;
				//TODO value ===>> addslashes || addcslashes  !
				if ( ( $value != '' && $field->searchmatch != 'none' ) || $field->type == 48 ) {
					$ope			=	'';
					$sql			=	'';
					$name			=	$field->name;
					$searchmatch	=	( ! $field->searchmatch || $field->searchmatch == 'inherit' ) ? $phrase : $field->searchmatch;
					// --------
					$location	=	'a.introtext'; //TODO! >> array with fulltext 
					$BOF		=	'(::'.$name.$more.'::)';
					$EOF		=	'(::/'.$name.$more.'::)';
					if ( @$field->target && @$field->target != '~' && $field->type != 50 ) {
						$location	=	getTargetLocation( $name, $field->target );
						$BOF		=	'';
						$EOF		=	'';
					}
					// --------
					if ( $field->type == 11 ) {
						$field->type	=	$field->type2;
					}
					// Operator
					if ( $operator == 1 ) {
						$ope	=	( $where2 != '' ) ? ' AND' : '';
						$where2	.=	$ope;
					}
					// - Security SQL
					if ( ! is_array( $value ) ) {
						$value	=	$db->getEscaped( $value, false );
					}
					// -
					switch ( $field->type ) {
						case 47:
							$sql	=	' '.getQueryMatchGeneric( $searchmatch, $name, $value, $helper, $helper2, $field->content, $user->id, 'a' );
							if ( $field->content == 'state' ) {
								$where_a_state	=	'';
							}
							if ( $field->content == 'publish_down' && $searchmatch != 'num_lower' ) {
								$sql			=	'( a.publish_down = '.$db->Quote($nullDate).' OR '.$sql.' )';
								$where_a_down	=	'';
							}
							if ( $field->content == 'publish_up' && $searchmatch != 'num_higher' ) {
								$sql			=	'( a.publish_up = '.$db->Quote($nullDate).' OR '.$sql.' )';
								$where_a_up		=	'';
							}
							break;
						case 48:
							if ( $field->content == '((' ) {
								$operator	=	0;
								$sql		=	' (';
							} else if ( $field->content == '))' ) {
								$operator	=	1;
								$sql		=	' )';
							} else {
								$ope		=	' '.$field->content;
								$sql		=	$ope;
							}
							break;
						case 50:
							if ( strpos( $field->content, ',' ) !== false ) {
								$name		=	explode( ',', strtr( $field->content, array("\r\n" => '', "\r" => '', "\n" => '') ) );
								if ( sizeof( $name ) ) {
									$wheres2_cut	=	array();
									foreach( $name as $n ) {
										// --------
										$BOF	=	'(::'.$n.$more.'::)';
										$EOF	=	'(::/'.$n.$more.'::)';
										if ( @$field->target && @$field->target != '~' ) {
											$location	=	getTargetLocation( $n, $field->target );
											$BOF		=	'';
											$EOF		=	'';
										}
										// --------
										$wheres2_cut[]	=	getQueryMatch( $searchmatch, $n, $value, $helper, $helper2, $location, $BOF, $EOF, $field->extended );
									}
									$m	=	( $field->bool8 ) ? 'OR' : 'AND';
									$sql	=	' '.'((' . implode( ') '.$m.' (', $wheres2_cut ) . '))';
								}
							} else {
								$name	=	$field->content;
								// Currency
								if ( strpos( $name, '$' ) !== false ) {
									$currency	=	'';
									$fieldname	=	CCK_ECOMMERCE::CORE_getConfig_Value( 'currency_profile' );
									if ( $fieldname ) {
										$currency	=	@$user->$fieldname;
									}
									if ( ! $currency ) {
										$default_currency	=	CCK_ECOMMERCE::CORE_getConfig_Value( 'currency' );
										$currency			=	( $default_currency ) ? $default_currency : 'usd';
									}
									$name	=	str_replace( '$', $currency, $name );
								}
								// --------
								$BOF	=	'(::'.$name.$more.'::)';
								$EOF	=	'(::/'.$name.$more.'::)';
								if ( @$field->target && @$field->target != '~' ) {
									$location	=	getTargetLocation( $name, $field->target );
									$BOF		=	'';
									$EOF		=	'';
								}
								// --------
								$sql	=	' '.getQueryMatch( $searchmatch, $name, $value, $helper, $helper2, $location, $BOF, $EOF, $field->extended );
							}
							break;
						case 51:
							break;
						default:
							$sql	=	' '.getQueryMatch( $searchmatch, $name, $value, $helper, $helper2, $location, $BOF, $EOF, $field->extended );
							break;
					}
					// Query
					$where2	.=	$sql;
				} else {
					if ( @$field->live && $ope ) {
						$where2	=	substr( $where2, 0, - strlen( $ope ) );
					}
				}
			}
			
		}
		
		$where	=	$where2;
		// ------------------------ //
		//   INTROTEXT & FULLTEXT   //   TODO::not here ! not like that !
		// ------------------------ //
		if ( $where ) {
			if ( $in ) {
				$where	=	'('.$where.') OR ('.str_replace('a.introtext', 'a.fulltext', $where).')';
			}
			$where	=	' WHERE ( '.$where.' )';
		}
	}
	
	$order			=	'';
	$morder			=	'';
	$sort_string	=	'';
	if ( $ordering ) {
		
		switch ($ordering) {
			case 'newest':
				$order = 'a.created DESC';
				break;
			case 'oldest':
				$order = 'a.created ASC';
				break;
			case 'popular':
				$order = 'a.hits DESC';
				break;
			case 'category':
				$order = 'b.title ASC, a.title ASC';
				$morder = 'a.title ASC';
				break;
			case 'alpha':
			default:
				$order = 'a.title ASC';
				break;
		}
		
	} else { 
		if ( $sort && sizeof( $sort ) ) {
			foreach ( $sort as $s ) {
				if ( $stage == $s->stage ) {
					switch ( $s->typename ) {
						case 'joomla_content':
							if ( $s->contentdisplay == 'CUSTOM' ) {
								$val		=	'"' . str_replace( ',', '","', $s->helper ) . '"';
								$order		.=	', FIELD(a.'.$s->name.', '.$val.')';
							} else if ( $s->contentdisplay == 'CUSTOM_STAGE' ) {
								$val		=	trim ( @$stages[$s->helper] );
								$val		=	'"' . str_replace( ' ', '","', $val ) . '"';
								$order		.=	', FIELD(a.'.$s->name.', '.$val.')';
							} else {
								$order		.=	', a.'.$s->name.' '.$s->contentdisplay;
							}
							break;
						default:
							$sort_name		=	'sabasort_'.$s->name;
							$len_name		=	strlen( $s->name ) + 4;
							$bos			=	'(POSITION("::'.$s->name.'::" IN a.introtext)+'.$len_name.')';
							$eos			=	'(POSITION("::/'.$s->name.'::" IN a.introtext))';
							$los			=	$eos.'-'.$bos;
							if ( @$s->target ) {
								$target	=	explode( '~', $s->target );
								$target[0]	=	( $target[0] ) ? $target[0] : 0;
								$target[1]	=	( $target[1] ) ? $target[1] : 0;
								if ( $target[0] ) {
									$bos		=	strpos( $target[0], '-' ) !== false ? '('.$eos.$target[0].')' : '('.$bos.'+'.$target[0].')';
								}
								if ( $target[1] ) {
									$los	=	strpos( $target[1], '-' ) !== false ? '('.$eos.'-'.$bos.$target[1].')' : '('.$target[1].')';
								} else {
									$los	=	'('.$eos.'-'.$bos.')';
								}
							}
							$sort_string	.=	' , SUBSTRING( a.introtext, '.$bos.', '.$los.' ) as '.$sort_name;
							if ( $s->contentdisplay == 'CUSTOM' ) {
								$val		=	'"' . str_replace( ',', '","', $s->helper ) . '"';
								$order		.=	', FIELD('.$sort_name.', '.$val.')';
							} else if ( $s->contentdisplay == 'CUSTOM_STAGE' ) {
								$val		=	trim( @$stages[$s->helper] );
								// Indexed
								if ( $s->indexedxtd ) {
									if ( $val ) {
										$res	=	str_replace( ' ', ',', @$stages[$s->helper] );
										if ( $res ) {
											$temp	=	CCK_DB_ResultArray( 'SELECT keyid FROM #__jseblod_cck_extra_index_key_'.$s->indexedxtd
													   					   .' WHERE id IN ( '.$res. ') ORDER BY FIELD(id, '.$res. ')' );
										
											$val	=	implode( ' ', $temp );
										}
									}
								}
								//
								$val		=	'"' . str_replace( ' ', '","', $val ) . '"';
								$order		.=	', FIELD('.$sort_name.', '.$val.')';
							} else {
								$force		=	( $s->width == 'numeric' ) ? '+0' : '';
								$order		.=	', '.$sort_name.$force.' '.$s->contentdisplay;
							}
							break;
					}
				}
			}
			$order	=	substr( $order, 1 );
		}
	
	}
	if ( ! $order ) {
		$order	=	' a.title ASC';
	}
	
	$rows	=	array();
	if ( $stage == 0 ) {
		
		// Articles
		if ( $limit > 0 ) {
			$query	=	'SELECT'
					.	' a.id, a.title AS title, a.metadesc, a.metakey, a.catid, b.title as category, a.alias, a.sectionid,'
					.	' a.created AS created, a.created_by, a.created_by_alias, a.modified, a.publish_up, a.publish_down, a.hits, a.attribs,'
					.	' a.introtext, a.fulltext,'
					.	' CONCAT_WS( "/", u.title, b.title ) AS section,'
					.	' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'
					.	' CASE WHEN CHAR_LENGTH(b.alias) THEN CONCAT_WS(":", b.id, b.alias) ELSE b.id END as catslug,'
					.	' u.id AS sectionid,'
					.	' "2" AS browsernav'
					.	$sort_string
					.	' FROM #__content AS a'
					.	' INNER JOIN #__categories AS b ON b.id=a.catid'
					.	' INNER JOIN #__sections AS u ON u.id = a.sectionid'
					.	$where
					.	$where_a_state
					.	' AND u.published = 1'
					.	' AND b.published = 1'
					.	' AND a.access <= '.(int) $user->get( 'aid' )
					.	' AND b.access <= '.(int) $user->get( 'aid' )
					.	' AND u.access <= '.(int) $user->get( 'aid' )
					.	$where_a_up
					.	$where_a_down
					.	' GROUP BY a.id'
					.	' ORDER BY '. $order
					;
			$db->setQuery( $query, 0, $limit );
			$list	=	$db->loadObjectList();
			$limit -=	count( $list );
		}
		$results	=	array();
		if ( sizeof( $list ) ) {
			foreach( $list as $item ) {
				if ( searchHelper::checkNoHTML( $item, $text, array( 'text', 'title', 'metadesc', 'metakey' ) ) ) {
					$results[]			=	$item;
					$item->search		=	$moreX;
				}
			}
		}
		
	} else {
		
		// Ids
		if ( $limit > 0 ) {
			$query	=	'SELECT a.id'
					.	$sort_string
					.	' FROM #__content AS a'
					.	' INNER JOIN #__categories AS b ON b.id=a.catid'
					.	' INNER JOIN #__sections AS u ON u.id = a.sectionid'
					.	$where
					.	$where_a_state
					.	' AND u.published = 1'
					.	' AND b.published = 1'
					.	' AND a.access <= '.(int) $user->get( 'aid' )
					.	' AND b.access <= '.(int) $user->get( 'aid' )
					.	' AND u.access <= '.(int) $user->get( 'aid' )
					.	$where_a_up
					.	$where_a_down
					.	' GROUP BY a.id'
					.	' ORDER BY '. $order
					;
			$db->setQuery( $query );
			$list	=	$db->loadResultArray();
			$limit	-=	count( $list );
		}
		$results	=	array();
		$results	=	array_merge( $results, (array)$list );
		
	}
	
	// Debug
	if ( $debug ) {
		echo $query.'<br /><br />';
	}
	
	return $results;
}
