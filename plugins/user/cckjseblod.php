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

jimport( 'joomla.plugin.plugin' );

class plgUserCCKjSeblod extends JPlugin
{
	function plgUserJoomla( & $subject, $config ) {
		parent::__construct( $subject, $config );
	}
	
	function onAfterStoreUser($user, $isnew, $success, $msg)
	{
		global $mainframe;
		if ( $success && ! $isnew ) {
			$userInfos		=	$this->_getUserInfos( $user['id'] );
			if ( $userInfos == null ) {
				return true;
			}
			$action			=	$this->_getAction( $userInfos->type );
			if ( $user['block'] == 0 ) {
				if ( $user['activation'] ) {
					$cfg_MailFrom	=	$mainframe->getCfg( 'mailfrom' );
					$cfg_FromName	=	$mainframe->getCfg( 'fromname' );
					if ( $cfg_MailFrom != '' && $cfg_FromName != '')
					{
						$mailFrom	=	$cfg_MailFrom;
						$fromName	=	$cfg_FromName;
					}
					$emailFields	=	$this->_getEmailFields( $userInfos->type );
					foreach ( $emailFields as $eField ) {
						if ( $eField->bool == 5 || $eField->bool == 7 ) {
							$dest		=	array();
							$message	=	str_replace( '[username]', $user['username'], $eField->message );
							$message	=	str_replace( '[sitename]', $mainframe->getCfg( 'sitename' ), $message );
							$message	=	str_replace( '[siteurl]', JURI::base(), $message );
							$subj		=	str_replace( '[username]', $user['username'], $eField->content );
							$subj		=	str_replace( '[sitename]', $mainframe->getCfg( 'sitename' ), $subj );
							$subj		=	str_replace( '[siteurl]', JURI::base(), $subj );
							if ( $eField->bool == 7 ) {
								$dest[]	=	$user['email'];
							}
							if ( $eField->mailto ) {
								$dest[]	=	$eField->mailto;
							}
							if ( $eField->toadmin ) {
								if ( strpos( $eField->toadmin, ',' ) !== false ) {
									$recips = explode( ',', $eField->toadmin );
									foreach( $recips as $recip ) {
										$recip_mail	=	$this->_getEmailByUser( $recip );
										if ( $recip_mail ) {
											$dest[]	=	$recip_mail;
										}
									}
								} else {
									$recip_mail	=	$this->_getEmailByUser( $eField->toadmin );
									if ( $recip_mail ) {
										$dest[]	=	$recip_mail;
									}
								}
							}
							if ( $dest ) {
								JUtility::sendMail( $mailFrom, $fromName, $dest, $subj, $message, true );
							}
							if ( $eField->cc ) {
								$dest	=	array();
								$dest[]	=	$eField->cc;
								JUtility::sendMail( $mailFrom, $fromName, $dest, $subj, $message, true );
							}
							if ( $eField->bcc ) {
								$dest	=	array();
								$dest[]	=	$eField->bcc;
								JUtility::sendMail( $mailFrom, $fromName, $dest, $subj, $message, true );
							}
						}
					}
					$userUp	=&	JTable::getInstance( 'user', 'JTable' );
					$userUp->load( $user[id] );
					$userUp->set( 'activation', '' );
					$userUp->store();
				}
				if ( $action->ordering ) {
					JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
					$content	=&	JTable::getInstance( 'content', 'JTable' );
					$content->load( $userInfos->contentid );
					if ( $content->state == 0 ) {
						$content->set( 'state', 1 );
						$content->store();
					}
				}
			} else {
				$userInfos		=	$this->_getUserInfos( $user['id'] );
				if ( $action->ordering ) {
					JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
					$content	=&	JTable::getInstance( 'content', 'JTable' );
					$content->load( $userInfos->contentid );
					if ( $content->state == 1 ) {
						$content->set( 'state', 0 );
						$content->store();
					}
				}
			}
		}
	}
	
	function onAfterDeleteUser( $user, $succes, $msg )
	{
		global $mainframe;
		
		if ( $succes ) {
			$userId	=	$user['id'];
			$db		=&	JFactory::getDBO();
			
			// Remove Indexed from Profile (Content)
			$profileId	=	CCK::USER_getProfileId( $user['id'] );
			
			// Indexed (as Key)
			$fields	=	CCK_DB_ResultArray( 'SELECT name FROM #__jseblod_cck_items WHERE indexedkey = 1' );
			if ( sizeof( $fields ) ) {
				foreach( $fields as $field ) {
					CCK::INDEX_deleteIndexed_Key( $field, $profileId );
				}
			}
			// Indexed
			$fields	=	CCK_DB_ResultArray( 'SELECT name FROM #__jseblod_cck_items WHERE indexed = 1' );
			if ( sizeof( $fields ) ) {
				foreach( $fields as $field ) {
					CCK::INDEX_deleteIndexed( $field, $profileId );
				}
			}
			
			$query	= 'DELETE c.*, u.* FROM #__content c LEFT JOIN #__jseblod_cck_users u ON c.id=u.contentid WHERE u.userid ='.(int)$user['id'];
			
			$db->setQuery( $query );
			if ( ! $db->query() ) {
				return false;
			}
		}
	}
	
	function _getUserInfos( $userId )
	{
		$db		=&	JFactory::getDBO();
		
		$where 	= ' WHERE s.userid = '.$userId;
		$query	= ' SELECT s.contentid, s.type'
				. ' FROM #__jseblod_cck_users AS s '
				. $where
				;
		$db->setQuery( $query );
		$userInfos	=	$db->loadObject();
		
		return $userInfos;
	}

	function _getAction( $contentType )
	{
		$db		=&	JFactory::getDBO();
		
		$where 	= ' WHERE cc.client = "admin" AND ccc.name = "'.$contentType.'"'
				. ' AND s.type = 25';
		
		$query	= ' SELECT s.ordering'
				. ' FROM #__jseblod_cck_items AS s '
				. ' LEFT JOIN #__jseblod_cck_type_item AS cc ON cc.itemid = s.id'
				. ' LEFT JOIN #__jseblod_cck_types AS ccc ON ccc.id = cc.typeid'
				. $where
				;
		$db->setQuery( $query );
		$action	=	$db->loadObject();
		
		return $action;
	}
	
	function _getEmailFields( $contentType )
	{
		$db		=&	JFactory::getDBO();
		
		$where 	= ' WHERE cc.client = "admin" AND ccc.name = "'.$contentType.'"'
				. ' AND s.type = 33';
		
		$query	= ' SELECT s.bool, s.mailto, s.toadmin, s.content, s.message, s.cc'
				. ' FROM #__jseblod_cck_items AS s '
				. ' LEFT JOIN #__jseblod_cck_type_item AS cc ON cc.itemid = s.id'
				. ' LEFT JOIN #__jseblod_cck_types AS ccc ON ccc.id = cc.typeid'
				. $where
				;
		$db->setQuery( $query );
		$fields	=	$db->loadObjectList();
		
		return $fields;
	}
	
	function _getEmailByUser( $userId )
	{
		$db		=&	JFactory::getDBO();
				
		$query	= ' SELECT s.email'
				. ' FROM #__users AS s '
				. ' WHERE s.id = '.(int)$userId
				;
		$db->setQuery( $query );
		$email	=	$db->loadResult();
		
		return $email;
	}
}

?>