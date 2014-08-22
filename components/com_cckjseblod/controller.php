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

jimport( 'joomla.application.component.controller' );

/**
 * Component Controller
 **/
class CCKjSeblodController extends JController
{
	/**
	 * Display Default View
	 **/
	function display()
	{
		// Display
		parent::display(true);
	}
	
	function download()
	{
		$id		=	JRequest::getInt( 'id' );
		$field	=	JRequest::getVar( 'file' );
		$group	=	JRequest::getVar( 'group' );
		$gx		=	JRequest::getVar( 'gx', 0, 'get', 'int' );
		$user	=&	JFactory::getUser();
		$uID	=	( $user->id ) ? $user->id : 0;
		$uGID	=	( $user->gid ) ? $user->gid : 0;
		$author	=	null;
		
		if ( ! $id ) {
			$file	=	$field;	
		} else {
			$row	=	CCK_DB_Object( 'SELECT CONCAT( s.introtext, s.fulltext ) AS text, created_by AS author FROM #__content AS s WHERE s.id ='.$id );
			$author	=	$row->author;
			if ( $group ) {
				$regex	=	CCK::CONTENT_getRegex_Group( $field, $group, $gx );
			} else {
				$regex	=	CCK::CONTENT_getRegex_Field( $field );
			}
			preg_match( $regex, $row->text, $matches );
			if ( $matches[1] ) {
				$file	=	str_replace( '/', DS, $matches[1] );
			}
		}
		// !!
		$item	=	CCK_DB_Object( 'SELECT s.* FROM #__jseblod_cck_items AS s WHERE s.name= "'.$field.'"' );
		if ( $item->type == 18 && $item->type && ! $item->bool3 ) {
			$path	=	JPATH_ROOT.DS.$item->location.$file;
		} else {
			$path	=	JPATH_ROOT.DS.$file;			
		}
		// !!
		if ( ! $item->gACL || $item->gACL == 17 || ( $item->gACL > 0 && $item->gACL != 17 && $uGID >= $item->gACL ) || ( @$author && $uID == $author ) ) {
			if ( JFile::exists ( $path ) ) {
				$size	=	filesize( $path ); 
				$ext	=	strtolower( substr ( strrchr( $path, '.' ) , 1 ) );
				$name	=	substr ( $path, strrpos ( $path, DS ) + 1, strrpos( $path, '.' ) );
		
				if ( $path ) {
					if ( $id ) {
						HelperjSeblod_Helper::downloadHits( $id, $field, $group, $gx );
					}
					set_time_limit( 0 );
					@ob_end_clean();
					include( JPATH_ROOT.'/components/com_cckjseblod/download.php' );
				}
			} else {
				$this->setRedirect( 'index.php', JText::_( 'ALERT FILE DOESNT EXIST' ), 'notice' );
			}
		} else {
			if ( $uID ) {
				$this->setRedirect( 'index.php', JText::_( 'ALERT VIEW NOT AUTH' ), "error" );
			} else {
				$this->setRedirect( 'index.php?option=com_user&view=login', JText::_( 'ALERT VIEW NOT AUTH LOGIN' ), "error" );
			}
		}
	}
	
	function save()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$curSession		=	JSession::getInstance( 'none',array() );
		$secureNum		=	$curSession->get("sabasecu_haoma");
		$captcha_enable =	JRequest::getVar( 'captcha_enable' );
		
		$itemId			=	JRequest::getInt( 'itemid' );
		$typeid			=	JRequest::getInt( 'typeid' );
		$templateid		=	JRequest::getInt( 'templateid' );
		
		if ( @$captcha_enable && @$captcha_enable == 1 ) {
			if ( JFolder::exists( JPATH_SITE.DS.'tmp'.DS.'jseblodcck-captcha' ) ) {
				$trash	=	JFolder::files( JPATH_SITE.DS.'tmp'.DS.'jseblodcck-captcha', '.', false, true );
				foreach( $trash as $t ) {
					if ( strpos( $t, 'jseblodcck-captcha' ) !== false ) {
						JFile::delete ( $t );			
					}
				}
			}
			if ( ! isset( $_POST['cptcsecure'] ) )
			{
				$_POST['cptcsecure'] = "";
			}
			if ( $_POST['cptcsecure'] != $secureNum )
			{
				global $mainframe;
				//TODO: get view, get layout >> user,registration!!
				$link		=	'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$typeid.'&templateid='.$templateid.'&Itemid='.$itemId;
				$msg		=	JText::_( 'CAPTCHA TRY AGAIN' );
				$msgType	=	'error';
				$mainframe->enqueueMessage( $msg, $msgType );
				//$this->setRedirect( $link, $msg, $msgType );
				parent::display();
				return true;
			}
		}
		
		$actionMode	=	JRequest::getInt( 'actionmode' );
		$formName	=	JRequest::getVar( 'formname' );
		
		$model		=	$this->getModel( 'type' );
		$actionObj	=&	$model->getActionAttribs( $formName );
		if ( $rowId = $model->store( $actionMode ) ) {
			$msg 	=	@$actionObj->message ? ( $actionObj->message )
											 : ( ( $actionMode ) ? ( ( $actionMode % 2 ) ? JText::_( 'CATEGORY SAVED' )
																						 : ( ( $actionMode == 4 ) ? JText::_( 'PERSONAL CONTENT SAVED' ) : JText::_( 'USER SAVED' ) ) )
												  				 : ( JText::_( 'ARTICLE SAVED' ) ) );
			$msgType	=	@$actionObj->style;
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
			$link		=	'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$typeid.'&templateid='.$templateid.'&Itemid='.$itemId;
		}
		if ( ! @$link ) {
			switch( $actionObj->bool3 ) {
				case 4:
					$backU	=	( JRequest::getVar( 'current_url' ) ) ? JRequest::getVar( 'current_url' ) : 'index.php';
					$link	=	$backU;
					break;
				case 3:
					$link	=	$actionObj->url;
					break;
				case 2:
					$link	=	'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$typeid.'&templateid='.$templateid.'&Itemid='.$itemId.'&thanks='.$typeid;
					break;
				case 1:
					$link	=	'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$typeid.'&templateid='.$templateid.'&cckid='.$rowId.'&Itemid='.$itemId.'&thanks='.$typeid;
					break;
				case 0:
				default:
					if ( $actionMode == 1 ) {
						$link	=	'index.php?option=com_content&view=category&layout=blog&id='.$rowId.'&Itemid='.$itemId.'&thanks='.$typeid;
					} else {	
						$link	=	'index.php?option=com_content&view=article&id='.$rowId.'&Itemid='.$itemId.'&thanks='.$typeid;
					}
					break;	
			}
		}
		if ( $msgType ) {
			if ( $msgType == 'error' ) {
				//$this->setRedirect( $link, $msg, $msgType );
				parent::display();
			} else {
				$this->setRedirect( $link, $msg, $msgType );
			}
		} else {
			$this->setRedirect( $link );
		}
	}
	
	/**
	 * Dynamic Select Ajax
	 **/
	function dynamicSelectAjax() {
    
		$where 	=	JRequest::getVar( 'where', '', 'get', 'string' );
		$item	=	JRequest::getVar( 'item', '', 'get', 'string' );
		$label	=	JRequest::getVar( 'label', '', 'get', 'string' );
		$client	=	JRequest::getVar( 'client', '', 'get', 'string' );
		
		if ( !is_numeric( $where ) ) {
			$where = '"' . $where . '"';
		}
		//
		if ( $where && $item ) {
			$itemInfo		=	CCKjSeblodItem_Form::getDynamicSelectInfoFromDatabase( $item );
			$live_options	=	'';
			if ( CCK_LANG_Enable() ) {
				$lang			=&	JFactory::getLanguage();
				$lang_tag		=	$lang->getTag();
				$live_options	=	CCK_DB_Result( 'SELECT s.value FROM #__jf_content AS s LEFT JOIN #__languages AS cc ON cc.id = s.language_id'
												 . ' WHERE s.reference_id = '.(int)$itemInfo->id.' AND s.reference_table = "jseblod_cck_items" AND s.reference_field = "options"'
												 . ' AND cc.code = "'.$lang_tag.'"' );
			}
			$itemInfoOpt	=	explode( '||', ( $live_options ) ? $live_options : $itemInfo->options );
			$required	 	=	( $itemInfo->required && $client != 'search' ) ? 'required required-enabled' : '';
			$wherec			=	( $itemInfo->content ) ? explode( '||', $itemInfo->content ) : '';
			$whereplus		=	( $itemInfo->options2 ) ? $itemInfo->options2 : '';
			if ( $wherec && @$wherec[1] ) {
				switch ( $wherec[1] ) {
					case 'INF':
						$wherec[1] = '<';
						break;
					case 'SUP':
						$wherec[1] = '>';
						break;
					case 'IN':
						$wherec[1] = 'IN';
						$wherec[2] = '('.$wherec[2].')';
						break;
					case 'NOTIN':
						$wherec[1] = 'NOT IN';
						$wherec[2] = '('.$wherec[2].')';
						break;
					case 'LIKE%':
						$wherec[1] = 'LIKE';
						$wherec[2] = '"%'.$wherec[2].'"';
						break;
					case '%LIKE':
						$wherec[1] = 'LIKE';
						$wherec[2] = '"'.$wherec[2].'%"';
						break;
					case '%LIKE%':
						$wherec[1] = 'LIKE';
						$wherec[2] = '"%'.$wherec[2].'%"';
						break;
					case 'NOTLIKE%':
						$wherec[1] = 'NOT LIKE';
						$wherec[2] = '"%'.$wherec[2].'"';
						break;
					case '%NOTLIKE':
						$wherec[1] = 'NOT LIKE';
						$wherec[2] = '"'.$wherec[2].'%"';
						break;
					case '%NOTLIKE%':
						$wherec[1] = 'NOT LIKE';
						$wherec[2] = '"%'.$wherec[2].'%"';
						break;
					default:
						$wherec[2] = '"'.$wherec[2].'"';
						break;
				}
				$ope			=	( $wherec[1] == 'INF' || $wherec[1] == 'SUP' ) ? ( ( $wherec[1] == 'INF' ) ? '<' : '>' ) : $wherec[1];
				$whereclause	=	( $wherec ) ? ' AND '.$wherec[0].' '.$ope.' '.$wherec[2] : '';
			}
			if ( $whereplus ) {
				@$whereclause	.=	' AND '.$whereplus;
			}
			$orderby		=	( $itemInfo->extra ) ? str_replace( '||', ' ', $itemInfo->extra ) : $itemInfoOpt[1];
			$query	=	'SELECT '.$itemInfoOpt[1].' AS text, '.$itemInfoOpt[2].' AS value FROM '.$itemInfoOpt[0]
					.	' WHERE '.$itemInfo->location.' = '.$where.@$whereclause
					.	' ORDER BY '.$orderby;
			$opts		=	array();
			if ( $label ) {
				$opts[]	= 	JHTML::_('select.option',  '', '- '.$label.' -', 'value', 'text' );
			}
			$getAjaxResults = CCKjSeblodItem_Form::getListFromDatabase( $query );
			
			if ( sizeof( $getAjaxResults ) ) {
				$opts		= array_merge( $opts, $getAjaxResults );
				$name		=	( $itemInfo->name == 'jcontentcatid' ) ? 'jcontent[catid]' : $itemInfo->name;
				$list		= JHTML::_( 'select.genericlist', $opts, $name, 'class="inputbox select '.$required.'" size="1"', 'value', 'text', '' );
				echo $list;
			} else {
				echo JText::_( 'NO ITEMS' );
			}
        } else {
			echo JText::_( 'NO ITEMS' );
        }
    }
	
	/**
	 * Captcha Ajax
	 **/
	function captchaAjax() {
		$itemId	=	JRequest::getVar( 'captcha_id', '', 'get', 'int' );
		$old	=	JRequest::getVar( 'old' );
		
		$oldFile	=	strstr( $old, 'tmp' );
		if ( JFile::exists( str_replace( '/', DS, $oldFile ) ) ) {
			JFile::delete( $oldFile );
		}
		
		$db	=&	JFactory::getDBO();
		$query	=	'SELECT s.* FROM #__jseblod_cck_items AS s WHERE s.id='.(int)$itemId;
		$db->setQuery( $query );
		$item	=	$db->loadObject();
		
		$suffix	=	date("H-i-s");
		include_once( JPATH_SITE.DS.'media'.DS.'jseblod'.DS.'captcha-math'.DS.'captchaimage.php' );
		
		$required	=	( $item->required ) ? 'required required-enabled' : '';
		$data		=	'<img src="'.JURI::root( true ).'/tmp/jseblodcck-captcha/captcha-'.$suffix.'.jpg" style="margin-bottom: 6px; cursor: pointer;"'
					.	'alt=" " title="Captcha Image" id="captcha" onclick="javascript:reloadCaptcha(this.src);" /><br />';
	   	$data		.=	'<input class="inputbox text '.$item->required.'" type="text"  name="cptcsecure" value="" size="'.$item->size.'" />';
		echo $data;
    }
	
	/*
	 * Article Add
	 **/
	function article_add( $toview = 'article' )
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		if ( $toview == 'user' ) {
			$typeid		=	( _USER_OWN_TYPEID ) ? _USER_OWN_TYPEID : 0;
			$templateid	=	( _USER_OWN_TEMPLATEID ) ? _USER_OWN_TEMPLATEID : 0;
			$itemId		=	( _USER_OWN_ITEMID ) ? _USER_OWN_ITEMID : JRequest::getInt( 'Itemid' );
		} else {
			$typeid		=	( _ARTICLE_TYPEID ) ? _ARTICLE_TYPEID : 0;
			$templateid	=	( _ARTICLE_TEMPLATEID ) ? _ARTICLE_TEMPLATEID : 0;
			$itemId		=	( _ARTICLE_ITEMID ) ? _ARTICLE_ITEMID : JRequest::getInt( 'Itemid' );
		}
				
		if ( $typeid ) {
			$add_typeid	=	JRequest::getInt( 'add_typeid' );
			if ( $add_typeid && $add_typeid != 0 ) {
				$link		=	'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$add_typeid.'&templateid=0&Itemid='.$itemId;
			} else {
				$link		=	'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$typeid.'&templateid='.$templateid.'&Itemid='.$itemId;
			}
									
			$this->setRedirect( $link );
		} else {
			$link		=	'index.php?option=com_cckjseblod&view=article&layout=user&Itemid='.$itemId;			
			
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
			
			$this->setRedirect( $link, $msg, $msgType );
		}
	}	 
	
	/*
	 * User Add
	 **/
	function user_add( $toview = 'user' )
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		if ( $toview == 'user' ) {
			$typeid		=	( _USER_OWN_TYPEID ) ? _USER_OWN_TYPEID : 0;
			$templateid	=	( _USER_OWN_TEMPLATEID ) ? _USER_OWN_TEMPLATEID : 0;
			$itemId		=	( _USER_OWN_ITEMID ) ? _USER_OWN_ITEMID : JRequest::getInt( 'Itemid' );
		} else {
			$typeid		=	( _ARTICLE_TYPEID ) ? _ARTICLE_TYPEID : 0;
			$templateid	=	( _ARTICLE_TEMPLATEID ) ? _ARTICLE_TEMPLATEID : 0;
			$itemId		=	( _ARTICLE_ITEMID ) ? _ARTICLE_ITEMID : JRequest::getInt( 'Itemid' );
		}
				
		if ( $typeid ) {
			$add_typeid	=	JRequest::getInt( 'add_typeid' );
			if ( $add_typeid && $add_typeid != 0 ) {
				$link		=	'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$add_typeid.'&templateid=0&Itemid='.$itemId;
			} else {
				$link		=	'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$typeid.'&templateid='.$templateid.'&Itemid='.$itemId;
			}
									
			$this->setRedirect( $link );
		} else {
			$link		=	'index.php?option=com_cckjseblod&view=article&layout=user&Itemid='.$itemId;			
			
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
			
			$this->setRedirect( $link, $msg, $msgType );
		}
	}
	
	/**
	 * Article Publish
	 **/
	function article_publish( $toview = 'article' )
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );			
		$cid		=	JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		
		$model		=	$this->getModel( $toview );
		if ( $model->publish( $cid, 1 ) ) {
			$msg		=	JText::_( 'ARTICLE PUBLISHED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view='.$toview.'&layout=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * User Publish
	 **/
	function user_publish( $toview = 'user' )
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );			
		$cid		=	JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		
		$model		=	$this->getModel( $toview );
		if ( $model->publish( $cid, 1 ) ) {
			$msg		=	JText::_( 'ARTICLE PUBLISHED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view='.$toview.'&layout=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Article Unpublish
	 **/
	function article_unpublish( $toview = 'article' )
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );			
		$cid		=	JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		
		$model		=	$this->getModel( $toview );
		if ( $model->publish( $cid, 0 ) ) {
			$msg		=	JText::_( 'ARTICLE UNPUBLISHED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view='.$toview.'&layout=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	} 
	
	/**
	 * User Unpublish
	 **/
	function user_unpublish( $toview = 'user' )
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );			
		$cid		=	JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		
		$model		=	$this->getModel( $toview );
		if ( $model->publish( $cid, 0 ) ) {
			$msg		=	JText::_( 'ARTICLE UNPUBLISHED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view='.$toview.'&layout=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	} 
	
	/**
	 * Article Delete
	 **/
	function article_trash( $toview = 'article' )
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );
		
		$model		=	$this->getModel( $toview );
		if ( $model->trash() ) {
			$msg		=	JText::_( 'ARTICLE DELETED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view='.$toview.'&layout=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * User Delete
	 **/
	function user_trash( $toview = 'user' )
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );
		
		$model		=	$this->getModel( $toview );
		if ( $model->trash() ) {
			$msg		=	JText::_( 'ARTICLE DELETED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view='.$toview.'&layout=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Category Add
	 **/
	function category_add()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$typeid		=	( _CATEGORY_TYPEID ) ? _CATEGORY_TYPEID : 0;
		$templateid	=	( _CATEGORY_TEMPLATEID ) ? _CATEGORY_TEMPLATEID : 0;
		$itemId		=	( _CATEGORY_ITEMID ) ? _CATEGORY_ITEMID : JRequest::getInt( 'Itemid' );
				
		if ( $typeid ) {
			$link	=	'index.php?option=com_cckjseblod&view=type&layout=category&typeid='.$typeid.'&templateid='.$templateid.'&Itemid='.$itemId;
									
			$this->setRedirect( $link );
		} else {
			$link		=	'index.php?option=com_cckjseblod&view=article&layout=user&Itemid='.$itemId;			
			
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
			
			$this->setRedirect( $link, $msg, $msgType );
		}		
	}	
	
	/**
	 * Category Publish
	 **/
	function category_publish()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );			
		$cid		=	JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		
		$model		=	$this->getModel( 'category' );
		if ( $model->publish( $cid, 1 ) ) {
			$msg		=	JText::_( 'CATEGORY PUBLISHED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view=category&layout=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Category Unpublish
	 **/
	function category_unpublish()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );			
		$cid		=	JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		
		$model		=	$this->getModel( 'category' );
		if ( $model->publish( $cid, 0 ) ) {
			$msg		=	JText::_( 'CATEGORY UNPUBLISHED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view=category&layout=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	} 
	 
	/**
	 * Category Delete
	 **/
	function category_trash()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );
		
		$model		=	$this->getModel( 'category' );
		if ( $model->trash() ) {
			$msg		=	JText::_( 'CATEGORY DELETED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view=category&layout=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	}

	/*
	 * User New
	 **/
	function user_new()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$typeid		=	( _USER_TYPEID ) ? _USER_TYPEID : 0;
		$templateid	=	( _USER_TEMPLATEID ) ? _USER_TEMPLATEID : 0;
		$itemId		=	( _USER_ITEMID ) ? _USER_ITEMID : JRequest::getInt( 'Itemid' );
			
		if ( $typeid ) {
			$link	=	'index.php?option=com_cckjseblod&view=type&layout=user&typeid='.$typeid.'&templateid='.$templateid.'&Itemid='.$itemId;
									
			$this->setRedirect( $link );
		} else {
			$link		=	'index.php?option=com_cckjseblod&view=article&layout=user&Itemid='.$itemId;			
			
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
			
			$this->setRedirect( $link, $msg, $msgType );
		}
	}	
	
	/**
	 * User Enable
	 **/
	function user_enable()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );			
		$cid		=	JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		
		$model		=	$this->getModel( 'user' );
		if ( $model->enable( $cid, 0 ) ) {
			$msg		=	JText::_( 'USER ENABLED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * User Block
	 **/
	function user_block()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );			
		$cid		=	JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		
		$model		=	$this->getModel( 'user' );
		if ( $model->enable( $cid, 1 ) ) {
			$msg		=	JText::_( 'USER BLOCKED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	} 

	/**
	 * User Delete
	 **/
	function user_delete()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$itemId		=	JRequest::getInt( 'Itemid' );
		
		$model		=	$this->getModel( 'user' );
		if ( $model->remove() ) {
			$msg		=	JText::_( 'USER DELETED' );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		$link	=	'index.php?option=com_cckjseblod&view=user&Itemid='.$itemId;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * User Activate
	 **/
	function activate()
	{
		global	$mainframe;
		$db			=& JFactory::getDBO();
		$user 		=& JFactory::getUser();
		
		if ( $user->get( 'id' ) ) {
			$mainframe->redirect( 'index.php' );
			return;
		}
		
		$usersConfig			=	&JComponentHelper::getParams( 'com_users' );
		$userActivation			=	$usersConfig->get( 'useractivation' );
		$allowUserRegistration	=	$usersConfig->get( 'allowUserRegistration' );
		
		if ( $allowUserRegistration == '0' || $userActivation == '0' ) {
			JError::raiseError( 403, JText::_( 'Access Forbidden' ) );
			return;
		}
		
		$activation	=	JRequest::getVar('activation', '', '', 'alnum' );
		$activation	=	$db->getEscaped( $activation );
		
		$link		=	'index.php';
		
		if ( empty( $activation ) ) {
			$msg		=	JText::_( 'UNABLE TO FIND A USER WITH GIVEN ACTIVATION STRING' );
			$msgType	=	'error';
		} else {
			$model		=	$this->getModel( 'user' );
			if ( $model->activate( $activation ) ) {
				$msg		=	JText::_( 'REG_ACTIVATE_COMPLETE' );	
				$msgType	=	'message';
			} else {
				$msg		=	JText::_( 'UNABLE TO FIND A USER WITH GIVEN ACTIVATION STRING' );
				$msgType	=	'error';
			}		
		}
		
		$this->setRedirect( $link, $msg, $msgType );
	}	
}
?>