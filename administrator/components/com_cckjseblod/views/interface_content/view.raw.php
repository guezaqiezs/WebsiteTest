<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

/**
 * Interface_Content		View Class
 **/
class CCKjSeblodViewInterface_Content extends JView
{
	/**
	 * Display Default View
	 **/ 
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$controller		=	JRequest::getWord( 'controller' );
		$document		=&	JFactory::getDocument();
		$model 			=&	$this->getModel();
		$user 			=&	CCK::USER_getUser();
		$cckId			=	JRequest::getInt( 'artid' );
		//
		$typeName		=	JRequest::getVar( 'content_type' );
		$cck 			=	JRequest::getInt( 'cck' );
		$brb 			=	JRequest::getInt( 'brb' );
		$act 			=	JRequest::getInt( 'act' );
		if ( $act == 4 ) {
			$userId		=	JRequest::getInt( 'pc' ); // Personal Content
		}
		$cat_id			=	JRequest::getString( 'cat_id' );
		$u_opt			=	JRequest::getString( 'u_opt' );
		$u_task			=	JRequest::getString( 'u_task' );
	  	$lang_id 		=	JRequest::getInt( 'lang_id' );
		$lang_next 		=	JRequest::getVar( 'lang_next' );
		$error			=	null;
		
		// Get Data from Model
		$contentType	=	$model->getData( $typeName );
		if ( ! $contentType ) {
			$error 	=	1;		//CONTENT TYPE NOT FOUND
		}
		
		if ( ! $error ) {
			require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_form.php' );
			$contentTemplate	=	CCKjSeblodItem_Form::getTemplate( $contentType->admintemplate, 1 );
			if ( !$contentTemplate ) {
				$error	=	2;		//TEMPLATE NOT FOUND
			}
			$template	=	@$contentTemplate->name;
			$path		=	JPATH_SITE.DS.'templates';
			$auto		=	@$contentTemplate->mode;
		}
		
		if ( ! $error ) {
			$client		=	'admin';
			$items		=	( $cck ) ? CCKjSeblodItem_Form::getItems( $contentType->id, $client, '', false, true ) : CCKjSeblodItem_Form::getItems( $contentType->id, $client, '', false );		
			$countItems	=	count( $items );
			if ( ! $countItems ) {
				$error	=	3;		//CONTENT TYPE EMPTY
			}
		}
		
		if ( ! $error ) {
			//--!!
			if ( $items[0]->typename == 'form_action' ) {
				$actionMode	=	$items[0]->bool2;
				$access		=	$items[0]->display;
				$catLocate	=	$items[0]->location;
				$maxC		=	$items[0]->maxlength;
				$maxCU		=	$items[0]->size;
				$duration	=	$items[0]->rows;
				if ( ! $cckId ) {
					if ( $access == -1 ) {
						$default_author	=	( @$items[0]->content ) ? $items[0]->content : 0;
						if ( $user->id != $default_author ) {
							$error	=	4;		//CONTENT TYPE NOTAUTH
						}	
					} else if ( $access == 17 ) {
					} else {
						if ( $user->gid < $access ) {
							$error	=	4;		//CONTENT TYPE NOTAUTH
						}
					}
					if ( $maxC && $catLocate ) {
						$num	=	HelperjSeblod_Helper::getNumItems( $catLocate, $actionMode );
						if ( $num >= $maxC && $user->gid != 25 ) {
							$error	=	5;		//MAXIMUM INTO CATEGORY
						}
					}
					if ( $maxCU && $catLocate && $user->id ) {
						$num	=	HelperjSeblod_Helper::getNumItems( $catLocate, $actionMode, $user->id );
						if ( $num >= $maxCU && $user->gid != 25 ) {
							$error	=	6;		//MAXIMUM INTO CATEGORY PER USER
						}
					}
					
				} else {
		  			$uEACL	=	$items[0]->uEACL;
					$gEACL	=	$items[0]->gEACL;
				}
			}
			
			if ( ! $error ) {
				
				// Get Existing Content
				$rowU	=	null;
				$regex	=	"#"._OPENING."(.*?)"._CLOSING."(.*?)"._OPENING."(/.*?)"._CLOSING."#s";
				if ( $cckId == -1 ) {
					$e_content = JRequest::getVar( 'e_content', '', 'post', 'string', JREQUEST_ALLOWRAW );
					preg_match_all( $regex, $e_content, $contentMatches );
					$content	=	new stdClass();
					
					if ( ! sizeof( $contentMatches[1] ) ) {
						$content->imported_intro	=	'';
						$content->imported_full		=	$e_content;
						$content->imported_text		=	$e_content;
					}
					
				} else {
					if ( $cckId ) {
						if ( $actionMode == 1 ) {
							$content	=	CCKjSeblodItem_Form::getObjectFromDatabase( 'SELECT * FROM #__categories WHERE id='.(int)$cckId );
							if ( $uEACL >= 0 && (
								( ( ! $uEACL || $uEACL == 0 ) && $user->id == $content->created_user_id )
								|| ( $uEACL == 1 && $user->id == $content->created_user_id && $user->gid >= $gEACL )
								|| ( $uEACL == 2 && ( $user->id == $content->created_user_id || $user->gid >= $gEACL ) )
								|| ( $uEACL == 3 && $user->gid >= $gEACL )
								|| ( $uEACL == 4 && $user->id != $content->created_user_id && $user->gid >= $gEACL )
							) ) {
								$full		=	$content->description;
								preg_match_all( $regex, $full, $contentMatches );
								if ( ! sizeof( $contentMatches[1] ) ) {
									$content->imported_intro	=	'';
									$content->imported_full		=	$content->description;
									$content->imported_text		=	$content->description;
								}
							} else {
								$error	=	4;		//CONTENT TYPE NOTAUTH
							}
						} else {
							if ( $lang_id ) {
								$content	=&	JTable::getInstance( 'content', 'JTable' );
								$content->load( $cckId );
								$content	=&	CCKjSeblodItem_Form::getContentRowFromJf( $content, $cckId, $lang_id );
							} else {
								$content	=&	JTable::getInstance( 'content', 'JTable' );
								$content->load( $cckId );
							}
							if ( $uEACL >= 0 && (
								( ( ! $uEACL || $uEACL == 0 ) && $user->id == $content->created_by )
								|| ( $uEACL == 1 && $user->id == $content->created_by && $user->gid >= $gEACL )
								|| ( $uEACL == 2 && ( $user->id == $content->created_by || $user->gid >= $gEACL ) )
								|| ( $uEACL == 3 && $user->gid >= $gEACL )
								|| ( $uEACL == 4 && $user->id != $content->created_by && $user->gid >= $gEACL )
							) ) {
								if( $content->fulltext ) {
									$full	=	$content->introtext.'rm_enable'.$content->fulltext;
									preg_match_all( $regex, $full, $contentMatches );
									if ( ! sizeof( $contentMatches[1] ) ) {
										$content->imported_intro	=	$content->introtext;
										$content->imported_full		=	$content->fulltext;
										$content->imported_text		=	$content->introtext.$content->fulltext;
										$content->importer_readmore	=	1;
									}
								} else {
									$full	=	$content->introtext.$content->fulltext;
									preg_match_all( $regex, $full, $contentMatches );
									if ( ! sizeof( $contentMatches[1] ) ) {
										$content->imported_intro	=	$content->introtext;
										$content->imported_full		=	'';
										$content->imported_text		=	$content->introtext;
									}
								}
								// rowU
								if ( $actionMode == 2 ) {
									$rowUId	= HelperjSeblod_Helper::getCCKUser( 'userid', 'contentid', $cckId );
									JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
									$rowU	=&	JTable::getInstance( 'user', 'JTable' );
									$rowU->load( $rowUId );
								}
							} else {
								$error	=	4;		//CONTENT TYPE NOTAUTH
							}
						}		
					}
				}
				
				if ( ! $error ) {
					
					include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonbefore_display.php' );
					$cckForm		=	null;
					$cckItems		=	null;
					$captchaEnable	=	null;
					$items1st		=	null;
					
					if ( sizeof( $items ) ) {
						
						foreach ( $items as $item ) {
							$itemName	=	$item->name;
							$itemValue	=	null;
							if ( $cckId ) {
								if ( $item->typename == 'alias_custom' ) {
									if ( ( $aKey = array_search( $item->extended, $contentMatches[1] ) ) !== false ) {
										$itemValue	=	$contentMatches[2][$aKey];
									}									
								} else {
									if ( ( $aKey = array_search( $itemName, $contentMatches[1] ) ) !== false ) {
										$itemValue	=	$contentMatches[2][$aKey];
									}
								}
							} else {
								// Live (Value)
								if ( $item->live == 'user' ) {
									if ( $item->prevalue == '' ) {
										$item->prevalue	=	$item->name;
									}
									if ( strpos( $item->prevalue, '==' ) !== false ) {
										$live_user  =  explode( '==', $item->prevalue );
									} else {
										$live_user[0]	=	$item->prevalue;
										$live_user[1]	=	'';
									}
				                  	// Profile
									$live_user[0]	=	str_replace( ' ', '', $live_user[0] );
									$profileFields	=	explode( ',', $live_user[0] );
									foreach (  $profileFields as $profileField ) {
										if ( trim( @$user->$profileField ) != '' ) {
											$itemValue	=	trim( @$user->$profileField );
											break;
										}
									}
						      		// Default
									if ( ! $itemValue ) {
										$itemValue	=	$live_user[1];  
								  	}
								} else {
									$itemValue	=	$item->prevalue;
								}
							}
							//-
							$doc->$itemName		=	CCKjSeblodItem_Form::getData( $item, $itemValue, $client, $cckId, $cck, $actionMode, $content, $rowU, $lang_id, $cat_id, @$contentMatches, null );
							//-
							if ( $item->typename == 'form_action' ) {
								if ( $auto != 1 ) {
									$buffer	=	JFile::read( $path.DS.$template.DS.$file.'.php' );
									if ( JString::strpos( $buffer, $item->name.'->form' ) === false ) {
										$mainframe->enqueueMessage( "ERROR FORM NOT FOUND", "error" );
										return true;
									}
								}
								$formName	=	$item->name;
								$state		=	( @$content->state != '' ) ? $content->state : $doc->$itemName->bool;
								$accessL	=	( @$content->access != '' ) ? $content->access : $doc->$itemName->bool5;
								$catId		=	( @$content->catid != '' ) ? $content->catid : $doc->$itemName->location;
								$userType	=	$doc->$itemName->format; //TODO:if edit get usertype. si pas traitement dans registration.
								if ( $actionMode == 1 ) {
									$userId		=	( @$content->created_user_id != '' ) ? ( $content->created_user_id ) : ( ( $user->id ) ? $user->id : $doc->$itemName->content );
									$formHidden = 	'<input type="hidden" id="jcontentformpublished" name="jcontentform[published]" value="'.$state.'" />'
												.	'<input type="hidden" id="jcontentformaccess" name="jcontentform[access]" value="'.$accessL.'" />'
												.	'<input type="hidden" id="jcontentformparent_id" name="jcontentform[parent_id]" value="'.$catId.'" />'
												.	'<input type="hidden" id="jcontentformcreated_user_id" name="jcontentform[created_user_id]" value="'.$userId.'" />'
												.	'<input type="hidden" id="jcontentformduration" name="jcontentform[duration]" value="'.$duration.'" />';
								} else {
									if ( ! @$userId ) {
										$userId	=	( @$content->created_by != '' ) ? ( $content->created_by ) : ( ( $user->id ) ? $user->id : $doc->$itemName->content );
									}	
									$override	=	( $actionMode == 2 && $doc->$itemName->ordering == 1 ) ? 1 : 0;
									$formHidden = 	'<input type="hidden" id="jcontentformstate" name="jcontentform[state]" value="'.$state.'" />'
												.	'<input type="hidden" id="jcontentformaccess" name="jcontentform[access]" value="'.$accessL.'" />'
												.	'<input type="hidden" id="jcontentformcatid" name="jcontentform[catid]" value="'.$catId.'" />'
												.	'<input type="hidden" id="jcontentformcreated_by" name="jcontentform[created_by]" value="'.$userId.'" />'
												.	'<input type="hidden" id="jcontentformusertype" name="jcontentform[usertype]" value="'.$userType.'" />'
												.	'<input type="hidden" id="jcontentformuseractivation" name="jcontentform[useractivation]" value="'.$doc->$itemName->bool4.'" />'
												.	'<input type="hidden" id="jcontentformoverride" name="jcontentform[override]" value="'.$override.'" />'
												.	'<input type="hidden" id="jcontentformduration" name="jcontentform[duration]" value="'.$duration.'" />';
								}
								if ( $auto == 1 ) {
									$cckForm	=	$itemName;
								}
							} else {
								if ( $item->typename == 'captcha_image' && $item->gEACL != -1 ) {
									$captchaEnable	=	1;
								}
								if ( $auto == 1 ) {
									$cckItems[]	=	$itemName;
								}
							}
							$items1st	.=	$item->id.',';
					}
				}
			}
		
			$items1st	=	substr( $items1st, 0, -1 );
			$formHidden .=	'<input type="hidden" id="jcontentexcluded" name="jcontentexcluded" value="'.$items1st.'" />';
			
			if ( ! @$formName ) {
				$mainframe->enqueueMessage( "CONTENT TYPE NO ACTION", "error" );
				return true;
			}
			if ( $auto == 1 ) {
				$doc->cckform	=	$cckForm;
				$doc->cckitems	=	$cckItems;
			}
			$doc->menu->title = $contentType->title;
			$doc->rooturl	=	JURI::root(true);
			$doc->template	=	$contentTemplate->name;
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonafter_display.php' );
			
			}
		}
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		//
		$this->assignRef( 'formName', $formName );
		$this->assignRef( 'formHidden', $formHidden );
		$this->assignRef( 'jsOnSubmit', $jsOnSubmit );
		//
		$this->assignRef( 'data', $data );
		$this->assignRef( 'actionMode', $actionMode );
		$this->assignRef( 'captchaEnable', $captchaEnable );
		$this->assignRef( 'cckId', $cckId );
		//
		$this->assignRef( 'contentType', $contentType );
		$this->assignRef( 'error', $error );
		$this->assignRef( 'cck', $cck );
		$this->assignRef( 'brb', $brb );
		$this->assignRef( 'act', $act );
		$this->assignRef( 'cat_id', $cat_id );
		$this->assignRef( 'u_opt', $u_opt );
		$this->assignRef( 'u_task', $u_task );
		$this->assignRef( 'lang_id', $lang_id );
		$this->assignRef( 'lang_next', $lang_next );
		if ( $act == 4 ) {
			$this->assignRef( 'userid', $userId );		
		}
		
		parent::display( $tpl );
	}
	
}
?>