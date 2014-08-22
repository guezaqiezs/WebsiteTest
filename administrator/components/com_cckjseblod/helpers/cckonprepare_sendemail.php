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
 * On Prepare SendEmail
 **/

function _getFields( $contentType ) {
	$db		=&	JFactory::getDBO();
	
	$where 	= ' WHERE cc.client = "email" AND ccc.name = "'.$contentType.'"'
			. ' AND s.type != 25 AND s.type != 27 AND s.type != 32 AND s.type != 38';
	$orderby	=	' ORDER BY cc.ordering ASC';
	
	$query	= ' SELECT sc.name AS typename, s.*'
			. ' FROM #__jseblod_cck_items AS s '
			. ' LEFT JOIN #__jseblod_cck_items_types AS sc ON sc.id = s.type'
			. ' LEFT JOIN #__jseblod_cck_type_item_email AS cc ON cc.itemid = s.id'
			. ' LEFT JOIN #__jseblod_cck_types AS ccc ON ccc.id = cc.typeid'
			. $where
			. $orderby
			;
	$db->setQuery( $query );
	$fields	=	$db->loadObjectList();
	
	return $fields;
}

$cfg_MailFrom	=	@$mainframe->getCfg( 'mailfrom' );
$cfg_FromName	=	@$mainframe->getCfg( 'fromname' );
if ( $cfg_MailFrom != '' && $cfg_FromName != '')
{
	$mailFrom	=	$cfg_MailFrom;
	$fromName	=	$cfg_FromName;
}
for ( $i = 0; $i < $textObj->nEmails; $i++ ) {
	$mailFrom	=	$cfg_MailFrom;
	if ( $textObj->batchEmails[$i]['valid'] == 1 ) {
		if ( $textObj->batchEmails[$i]['moredest'] ) {
			$moredest	=	array();
			$moref		=	explode( '<br />', strtr( $textObj->batchEmails[$i]['moredest'], array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />') ) );
			if ( sizeof( $moref ) ) {
				foreach( $moref as $f ) {
					if ( $f ) {
						if ( $items[$f]->value ) {
							$mored	=	$items[$f]->value;
							$mored	=	strtr( $mored, array( "\r\n" => '', "\r" => '', "\n" => '', "<br />" => '' ) );
							if ( strpos( $mored, ';' ) !== false ) {
								$moreds		=	explode( ';', $mored );
								foreach( $moreds as $m ) {
									$m	=	trim( $m );
									if ( $m != '' && filter_var( $m, FILTER_VALIDATE_EMAIL ) ) {
										$moredest[]	=	$m;
									}
								}
							} else {
								$moredest[]	=	$mored;
							}
	
						}
					}
				}
			}
			$textObj->batchEmails[$i]['dest']	=	array_merge( $textObj->batchEmails[$i]['dest'], $moredest );
		}
		if ( $textObj->batchEmails[$i]['moredest_bcc'] ) {
			$moredest	=	array();
			$moref		=	explode( '<br />', strtr( $textObj->batchEmails[$i]['moredest_bcc'], array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />') ) );				
			if ( sizeof( $moref ) ) {
				foreach( $moref as $f ) {
					if ( $f ) {
						if ( $items[$f]->value ) {
							$mored	=	$items[$f]->value;
							$mored	=	strtr( $mored, array( "\r\n" => '', "\r" => '', "\n" => '', "<br />" => '' ) );
							if ( strpos( $mored, ';' ) !== false ) {
								$moreds		=	explode( ';', $mored );
								foreach( $moreds as $m ) {
									$m	=	trim( $m );
									if ( $m != '' && filter_var( $m, FILTER_VALIDATE_EMAIL ) ) {
										$moredest[]	=	$m;
									}
								}
							} else {
								$moredest[]	=	$mored;
							}
	
						}
					}
				}
			}
			$textObj->batchEmails[$i]['dest_bcc']	=	array_merge( $textObj->batchEmails[$i]['dest_bcc'], $moredest );
		}
		if ( $mailFrom && $fromName && ( $textObj->batchEmails[$i]['dest'] || $textObj->batchEmails[$i]['dest_cc'] || $textObj->batchEmails[$i]['dest_bcc'] )
									&& $textObj->batchEmails[$i]['subject'] && $textObj->batchEmails[$i]['message'] ) {
			$body	=	$textObj->batchEmails[$i]['message'];
			$body	=	str_replace( '[username]', $items['username']->value, $body );
			$body	=	str_replace( '[activation]', JURI::root().'index.php?option=com_cckjseblod&task=activate&activation='.$activationCode, $body );
			$body	=	str_replace( '[sitename]', $mainframe->getCfg( 'sitename' ), $body );
			$body	=	str_replace( '[siteurl]', JURI::base(), $body );
			$subj	=	$textObj->batchEmails[$i]['subject'];
			$subj	=	str_replace( '[username]', $items['username']->value, $subj );
			$subj	=	str_replace( '[sitename]', $mainframe->getCfg( 'sitename' ), $subj );
			$subj	=	str_replace( '[siteurl]', JURI::base(), $subj );
			// Field one by one
			$matches	=	null;
			preg_match_all( '#\#([a-zA-Z0-9_]*)\##U', $body, $matches );
			if ( sizeof ( $matches[1] ) ) {
				foreach( $matches[1] as $match ) {
					$body	=	( trim($match) && trim(@$items[$match]->value) ) ? str_replace( '#'.$match.'#', $items[$match]->value, $body ) : str_replace( '#'.$match.'#', '', $body );
				}
			}
			//
			if ( $textObj->batchEmails[$i]['fields'] ) {
				if ( $textObj->batchEmails[$i]['fields'] == 'email' ) {
					$fields	=	_getFields( $contentType );	
					$bodyF	=	null;
					if ( sizeof( $fields ) ) {
  					foreach ( $fields as $field ) {
  						$fieldName	=	$field->name;
  						if ( ! ( $items[$fieldName]->typename2 == 'password' && $items[$fieldName]->value == 'XXXX' ) && trim( $items[$fieldName]->value ) ) {
  							$bodyF	.=	'- '.$items[$fieldName]->label2._LANG_SEPARATOR.trim($items[$fieldName]->value).'<br /><br />';
  						}
  					}
					}
					$body	=	( strpos( $body, '[fields]' ) !== false ) ? str_replace( '[fields]', $bodyF, $body ) : $body.substr( $bodyF, 0, -12 );
				} else {
					$body	=	( strpos( $body, '[fields]' ) !== false ) ? str_replace( '[fields]', $textObj->body, $body ) : $body.substr( $textObj->body, 0, -12 );
				}
			}
			// Update Paths
			$body	=	HelperjSeblod_Helper::absolutePaths( $body );
			
			$contentId	=	JRequest::getInt( 'content_id' );
			if ( $textObj->batchEmails[$i]['from_type'] == 3 ) {
				$fromVal	=	$items[$textObj->batchEmails[$i]['from']]->value;
				if ( $fromVal ) {
					$mailFrom	=	$fromVal;
					$fromName	=	$fromVal;
				}
			} else if ( $textObj->batchEmails[$i]['from_type'] == 2 ) {
				$articleVal	=	CCK_GET_Value( $contentId, $textObj->batchEmails[$i]['from'] );
				$mailFrom	=	( $articleVal ) ? $articleVal : $mailFrom;
			} else if ( $textObj->batchEmails[$i]['from_type'] == 1 ) {
				$mailFrom	=	$textObj->batchEmails[$i]['from'];
			}
			JUtility::sendMail( $mailFrom, $fromName, $textObj->batchEmails[$i]['dest'], $subj, $body, $textObj->batchEmails[$i]['format'], $textObj->batchEmails[$i]['dest_cc'], $textObj->batchEmails[$i]['dest_bcc'] );
		}
	}
}
?>