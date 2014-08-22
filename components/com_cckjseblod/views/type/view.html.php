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

jimport( 'joomla.application.component.view' );
jimport( 'joomla.filesystem.file' );

/**
 * View Class
 **/
class CCKjSeblodViewType extends JView
{
	/**
	 * Redirect (NOTAUTH)
	 **/
	function redirectNotAuth( $userId, $url, $message, $type )
	{
		global $mainframe;
		
		if ( $userId ) {
			$mainframe->redirect( 'index.php', JText::_( 'CONTENT TYPE NOTAUTH' ), "error" );
		} else {
			$url	=	( $url ) ? $url : 'index.php?option=com_user&view=login';
			$mainframe->redirect( $url, JText::_( 'CONTENT TYPE NOTAUTH' ), "error" );
		}
	}
	
	/**
	 * Display Default View
	 **/
	function display( $tpl = null, $i_cckId = null, $i_typeid = null )
	{
		// Get Request Vars
		if ( ! defined( '_JSEBLOD_SITEFORM_SINGLEPASS' ) ) {
		global $mainframe, $option;
		$controller =	JRequest::getWord( 'controller' );
		$document	=&	JFactory::getDocument();
		$itemId		=	JRequest::getCmd( 'Itemid' );
		$layout		=	$this->getLayout();
		$model 		=&	$this->getModel();
		$params 	=	&$mainframe->getParams();
		
		if ( $layout == 'default' ) {
			$typesItems	=	$model->getData();
			
			$menus	=	&JSite::getMenu();
			$menu 	=	$menus->getActive();
			if ( is_object( $menu ) ) {
				$menu_params	=	new JParameter( $menu->params );
			}
			
			if ( count( $typesItems ) == 1 && @$menu_params && $menu_params->get( 'auto_redirect', 0 ) ) {
				$i_cckId	=	0;
				$i_typeid	=	@$typesItems[0]->id;
				$this->setLayout( 'form' );
			} else {
				if ( @$menu_params && $menu_params->get( 'page_title' ) ) {
					$page_title	=	$menu_params->get( 'page_title' );
					$document->setTitle( $page_title );	
				} else {
					$page_title	=	JText::_( 'CONTENT TYPES' );
				}
		
				$this->assignRef( 'option', $option);
				$this->assignRef( 'controller', $controller );
				$this->assignRef( 'document', $document );
				//
				$this->assignRef( 'itemId', $itemId );
				$this->assignRef( 'menu_params', $menu_params );
				$this->assignRef( 'page_title', $page_title );
				//
				$this->assignRef( 'typesItems', $typesItems );
				
				parent::display( $tpl );
				return;
			}
		}
		
		$user 		=&	CCK::USER_getUser();
		$user2		=	null;
		$cckId		=  	( $i_cckId ) ? $i_cckId : ( ( $user->id ) ? JRequest::getInt( 'cckid' ) : 0 );
		//
		$itemId		=	JRequest::getInt( 'Itemid' );
		$typeid		=  	( $i_typeid ) ? $i_typeid : JRequest::getInt( 'typeid' );
		$templateid =  	JRequest::getInt( 'templateid' );
		
		// Live => Field=Value
		if ( $params->get( 'site_live', '' ) ) {
			$tempSite	=	explode( '<br />', strtr( $params->get( 'site_live', '' ), array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />') ) );
			if ( sizeof( $tempSite ) ) {
				foreach ( $tempSite as $key => $val ) {
					$tab				=	explode( '=', $val );
					$liveSite[$tab[0]]	=	$tab[1];
				}
			}
		}
		//
		
		define( '_ERROR_REFRESH_ITEMID',	$itemId );
		$task		=	JRequest::getVar( 'task' );
		if ( $task == 'save' ) {
			$post	=	JRequest::get( 'post' );
			define( '_ERROR_REFRESH_TRUE',	'TRUE' );
		}
		
		// Get Data from Model
		$contentType		=	CCKjSeblodItem_Form::getType( $typeid );
		if ( ! $contentType ) {
			$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE NOT FOUND' ), "error" );
			return true;
		}
		
		$contentTemplate	=	CCKjSeblodItem_Form::getTemplate( $templateid, 0 );
		if ( ! $contentTemplate ) {
			$contentTemplate	=	CCKjSeblodItem_Form::getTemplate( $contentType->sitetemplate, 1 );
		}
		if ( !$contentTemplate ) {
			$mainframe->enqueueMessage( JText::_( 'TEMPLATE NOT FOUND' ), "error" );
			return true;
		}
		$template	=	@$contentTemplate->name;
		$path		=	JPATH_THEMES;
		$auto		=	@$contentTemplate->mode;
		
		$client		=	'site';
		$items		=	CCKjSeblodItem_Form::getItems( $contentType->id, $client, '', false, true );
		
		$countItems	=	count( $items );
		if ( ! $countItems ) {
			$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE EMPTY' ), "error" );
			return true;
		}
		
		//--!!
		if ( $items[0]->typename == 'form_action' ) {
			$actionMode	=	$items[0]->bool2;
			$access		=	$items[0]->display;
			$catLocate	=	$items[0]->location;
			$maxC		=	$items[0]->maxlength;
			$maxCU		=	$items[0]->size;
			$duration	=	$items[0]->rows;
			// Registration + Joomfish >> Only Default Language.
			// //TODO
			
			if ( ! $cckId ) {
				if ( $access == -1 ) {
					$default_author	=	( @$items[0]->content ) ? $items[0]->content : 0;
					if ( $user->id != $default_author ) {
						$this->redirectNotAuth( $user->id, $items[0]->extra, JText::_( 'CONTENT TYPE NOTAUTH' ), 'error' ); //$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE NOTAUTH' ), "error" );
						return true;
					}				
				} else if ( $access == 17 ) {
				} else {
					if ( $user->gid < $access ) {
						$this->redirectNotAuth( $user->id, $items[0]->extra, JText::_( 'CONTENT TYPE NOTAUTH' ), 'error' ); //$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE NOTAUTH' ), "error" );
						return true;
					}
				}
				if ( $maxC && $catLocate ) {
					$num	=	HelperjSeblod_Helper::getNumItems( $catLocate, $actionMode );
					if ( $num >= $maxC && $user->gid != 25 ) {
						$mainframe->enqueueMessage( JText::_( 'MAXIMUM INTO CATEGORY' ), "notice" );
						return true;
					}
				}
				if ( $maxCU && $catLocate && $user->id ) {
					$num	=	HelperjSeblod_Helper::getNumItems( $catLocate, $actionMode, $user->id );
					if ( $num >= $maxCU && $user->gid != 25 ) {
						$mainframe->enqueueMessage( JText::_( 'MAXIMUM INTO CATEGORY PER USER' ), "notice" );
						return true;
					}
				}
				
			} else {
		  		$uEACL	=	$items[0]->uEACL;
				$gEACL	=	$items[0]->gEACL;
			}
		}
		
		// Get Existing Content
		$rowU	=	null;
		if ( $cckId ) {
			if ( ! $user->id ) {
				$this->redirectNotAuth( $user->id, $items[0]->extra, JText::_( 'CONTENT TYPE NOTAUTH' ), 'error' ); //$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE NOTAUTH' ), "error" );
				return true;			
			}
			$regex	=	"#"._OPENING."(.*?)"._CLOSING."(.*?)"._OPENING."(/.*?)"._CLOSING."#s";
			
			if ( $actionMode == 1 ) {
				$content	=	CCKjSeblodItem_Form::getObjectFromDatabase( 'SELECT * FROM #__categories WHERE id='.(int)$cckId );
				if ( $uEACL >= 0 && (
					( ( ! $uEACL || $uEACL == 0 ) && $user->id == $content->created_user_id )
					|| ( $uEACL == 1 && $user->id == $content->created_user_id && $user->gid >= $gEACL )
					|| ( $uEACL == 2 && ( $user->id == $content->created_user_id || $user->gid >= $gEACL ) )
					|| ( $uEACL == 3 && $user->gid >= $gEACL )
					|| ( $uEACL == 4 && $user->id != $content->created_user_id && $user->gid >= $gEACL )
				) ) {
					$full	=	$content->description;
				} else {
					$this->redirectNotAuth( $user->id, $items[0]->extra, JText::_( 'CONTENT TYPE NOTAUTH' ), 'error' ); //$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE NOTAUTH' ), "error" );
					return true;
				}
				
			} else {

				$content	=&	JTable::getInstance( 'content', 'JTable' );
				$content->load( $cckId );
				
				if ( $uEACL >= 0 && (
					( ( ! $uEACL || $uEACL == 0 ) && $user->id == $content->created_by )
					|| ( $uEACL == 1 && $user->id == $content->created_by && $user->gid >= $gEACL )
					|| ( $uEACL == 2 && ( $user->id == $content->created_by || $user->gid >= $gEACL ) )
					|| ( $uEACL == 3 && $user->gid >= $gEACL )
					|| ( $uEACL == 4 && $user->id != $content->created_by && $user->gid >= $gEACL )
				) ) {
					if( $content->fulltext ) {
						$full	=	$content->introtext.'rm_enable'.$content->fulltext;
					} else {
						$full	=	$content->introtext.$content->fulltext;
					}				
					// rowU
					if ( $actionMode == 2 ) {
						$rowUId	= HelperjSeblod_Helper::getCCKUser( 'userid', 'contentid', $cckId );
						JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
						$rowU	=&	JTable::getInstance( 'user', 'JTable' );
						$rowU->load( $rowUId );
					}
				} else {
					$this->redirectNotAuth( $user->id, $items[0]->extra, JText::_( 'CONTENT TYPE NOTAUTH' ), 'error' ); //$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE NOTAUTH' ), "error" );
					return true;
				}
			}
			
			preg_match_all( $regex, $full, $contentMatches );
		} else {
			$rowU	=	null;
		}
		
		include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonbefore_display.php' );
		$cckForm		=	null;
		$cckItems		=	null;
		$captchaEnable	=	null;
		$items1st		=	null;
		if ( sizeof( $items ) ) {
			foreach ( $items as $item ) {
				$itemName	=	$item->name;
				$itemValue	=	null;
				
				if ( $task == 'save' ) {
					if ( $item->extended ) {
						$prefix		=	str_replace( $itemName, '', $item->extended );
						$itemValue	=	@$post[$prefix][$itemName];
					} else {
						$itemValue	=	@$post[$itemName];
					}
				} else {
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
						//if ( $item->live == 'menu' ) {
						$itemValue	=	@$liveSite[$item->name];
						//}
						if ( $item->live == 'url' ) {
							$itemValue	=	JRequest::getString( $item->prevalue, '', 'GET' );
						} else if ( $item->live == 'url_int' ) {
							$itemValue	=	JRequest::getInt( $item->prevalue, '', 'GET' );
						} else if ( $item->live == 'user' ) {
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
								if ( trim( @${$userObj}->$profileField ) != '' ) {
									$itemValue	=	trim( ${$userObj}->$profileField );
									break;
								}
							}
							// Default
							if ( ! $itemValue ) {
								$itemValue	=	$live_user[1];  
							}
						} else if ( $item->live == 'cart' ) {
							$itemValue	=	'';
						} else if ( $item->live == 'cart_title' ) {
							$itemValue	=	CCK_ECOMMERCE::CART_getTitle( $user->id );
						} else {
							if ( ! $itemValue ) {
								$itemValue	=	$item->prevalue;
							}
						}
					}
				}
				//-
				$doc->$itemName		=	CCKjSeblodItem_Form::getData( $item, $itemValue, $client, $cckId, null, $actionMode, $content, $rowU, 0, 0, @$contentMatches, null );
				//-
				if ( $item->typename == 'form_action' ) {
					if ( $auto != 1 ) {
						$buffer	=	JFile::read( $path.DS.$template.DS.$file.'.php' );
						if ( JString::strpos( $buffer, $item->name.'->form' ) === false ) {
							$mainframe->enqueueMessage( "ERROR FORM NOT FOUND", "error" );
							return true;
						}
					}
					$formName		=	$item->name;
					$state			=	( @$content->state != '' ) ? $content->state : $doc->$itemName->bool;
					$accessL		=	( @$content->access != '' ) ? $content->access : $doc->$itemName->bool5;
					$catId			=	( @$content->catid != '' ) ? $content->catid : $doc->$itemName->location;
					$userId			=	( @$content->created_by != '' ) ? ( $content->created_by ) : ( ( $user->id ) ? $user->id : $doc->$itemName->content );
					$userType		=	( @$user->id && @$user->id == @$content->created_by ) ? $user->usertype : $doc->$itemName->format;
					if ( $actionMode == 1 ) {
						$formHidden = 	'<input type="hidden" id="jcontentformpublished" name="jcontentform[published]" value="'.$state.'" />'
									.	'<input type="hidden" id="jcontentformaccess" name="jcontentform[access]" value="'.$accessL.'" />'
									.	'<input type="hidden" id="jcontentformparent_id" name="jcontentform[parent_id]" value="'.$catId.'" />'
									.	'<input type="hidden" id="jcontentformcreated_user_id" name="jcontentform[created_user_id]" value="'.$userId.'" />'
									.	'<input type="hidden" id="jcontentformduration" name="jcontentform[duration]" value="'.$duration.'" />';
					} else {
						$formHidden = 	'<input type="hidden" id="jcontentformstate" name="jcontentform[state]" value="'.$state.'" />'
									.	'<input type="hidden" id="jcontentformaccess" name="jcontentform[access]" value="'.$accessL.'" />'
									.	'<input type="hidden" id="jcontentformcatid" name="jcontentform[catid]" value="'.$catId.'" />'
									.	'<input type="hidden" id="jcontentformcreated_by" name="jcontentform[created_by]" value="'.$userId.'" />'
									.	'<input type="hidden" id="jcontentformusertype" name="jcontentform[usertype]" value="'.$userType.'" />'
									.	'<input type="hidden" id="jcontentformuseractivation" name="jcontentform[useractivation]" value="'.$doc->$itemName->bool4.'" />'
									.	'<input type="hidden" id="jcontentformduration" name="jcontentform[duration]" value="'.$duration.'" />';
					}
					if ( $auto == 1 ) {
						$cckForm	=	$itemName;
					}
				} else {
					if ( $item->typename == 'captcha_image' &&
						(( ! ( $item->gEACL == -1 || ( $item->gEACL == 1 && $client == 'site' ) ) && $cckId )
						|| ( $item->gEACL == 0 )
						|| ( ( $item->gEACL == -1 || ( $item->gEACL == 1 && $client == 'site' ) ) && ! $cckId )) ) {
						$captchaEnable	=	1;
					}
					if ( $auto == 1 ) {
						$cckItems[]	=	$itemName;
					}
				}
				$items1st	.=	$item->id.',';
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
		
		$menu	=	CCKjSeblodItem_Form::getObjectFromDatabase( 'SELECT name AS title, link, browserNav AS target FROM #__menu WHERE id= '.$itemId );
		if ( @$menu ) {
			$doc->menu->link	=	JRoute::_( @$menu->link );
			$doc->menu->target	=	@$menu->target;
			$doc->menu->title	=	( @$menu->title ) ? $menu->title : JText::_( 'EDIT CCK ARTICLE' );
		} else {
			$doc->menu->title	=	@$contentType->title;;
		}
		$doc->template = $contentTemplate->name;
		include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonafter_display.php' );
			
		// Push Data into Template
		$this->assignRef( 'option', $option);
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
		$this->assignRef( 'typeid', $contentType->id );
		$this->assignRef( 'templateid', $contentTemplate->id );
		$this->assignRef( 'itemId', $itemId );
		
		parent::display( $tpl );
		define( '_JSEBLOD_SITEFORM_SINGLEPASS',	'done' );
		}
	}
	
}
?>