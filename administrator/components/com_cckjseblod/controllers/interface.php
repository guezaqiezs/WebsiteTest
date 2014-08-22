<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

/**
 * Interface		Controller Class
 **/
class CCKjSeblodControllerInterface extends CCKjSeblodController
{
	/**
	 * Vars
	 **/
	//var $_isAuth = null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
		
		// Register Extra Tasks
		$this->registerTask( 'content', 'display' );
		$this->registerTask( 'pack', 'display' );
		$this->registerTask( 'save', 'save' );
		$this->registerTask( 'savenew', 'save' );
		$this->registerTask( 'savetranslate', 'save' );
		$this->registerTask( 'apply', 'save' );
		
		// Check User Auth
		//$user 	=& JFactory::getUser();
		//$isAuth = ( $user->get( 'gid' ) < _VIEW_ACCESS ) ? 0 : 1;
		//$this->_setValues( $isAuth );
		
	}
	
	/**
	 * Set Values 
	 **/
	//function _setValues( $isAuth )
	//{
		// Set Values
		//$this->_isAuth	= $isAuth;
	//}
	
	/**
	 * Display Default View
	 **/
	function display()
	{
		global $mainframe;
		
		// Check User Authorization
		//if ( ! $this->_isAuth ) {
			//$mainframe->redirect( _LINK_CCKJSEBLOD, JText::_( 'NOT AUTH' ), 'error' );
		//}
		
		switch( $this->getTask() ) {
			case 'content':
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'view', 'interface_content' );
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'doStore', false );
				JRequest::setVar( 'doApply', false );
				break;
			case 'pack':
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'view', 'interface_pack' );
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'doStore', false );
				JRequest::setVar( 'doApply', false );
				break;
			case 'close':
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'view', 'interface_close' );
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'doStore', false );
				JRequest::setVar( 'doApply', false );
				break;
			default:
				// Set Default View
				$view = JRequest::getCmd( 'view' );
				if ( empty( $view ) ) {
					JRequest::setVar( 'hidemainmenu', 1 );
					JRequest::setVar( 'view', 'interface' );
					JRequest::setVar( 'layout', 'default' );
					JRequest::setVar( 'doStore', false );
					JRequest::setVar( 'doApply', false );
				}
				break;
		}
		
		parent::display();
	}
	
	/**
	 * Save && Redirect
	 **/
	function save()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$curSession		=	JSession::getInstance( 'none',array() );
		$secureNum		=	$curSession->get("sabasecu_haoma");
		$captcha_enable =	JRequest::getInt( 'captcha_enable' );
		
		$cck		=	JRequest::getInt( 'cck' );
		$lang_id	=	JRequest::getInt( 'lang_id' );
		$actionMode	=	JRequest::getInt( 'actionmode' );
		$u_opt		=	JRequest::getVar( 'u_opt' );
		$u_task		=	JRequest::getVar( 'u_task' );
		$cat_id		=	JRequest::getInt( 'cat_id' );
		
		if ( $cck ) {
			if ( @$captcha_enable && @$captcha_enable == 1 ) {
				if ( ! isset( $_POST['cptcsecure'] ) )
				{
					$_POST['cptcsecure'] = "";
				}
				if ( $_POST['cptcsecure'] != $secureNum )
				{
					//TODO: get view, get layout >> user,registration!!
					$link		=	'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$typeid.'&templateid='.$templateid.'&Itemid='.$itemId;
					$msg		=	JText::_( 'CAPTCHA TRY AGAIN' );
					$msgType	=	'error';
					$this->setRedirect( $link, $msg, $msgType );
					return true;
				}
			}
			$model	= $this->getModel( 'interface_content' );
			
			if ( $rowId = $model->store( $actionMode ) ) {
				$msg = ( $actionMode ) ? ( ( $actionMode % 2 ) ? JText::_( 'CATEGORY SAVED' ) : ( ( $actionMode == 4 ) ? JText::_( 'PERSONAL CONTENT SAVED' ) : JText::_( 'USER SAVED' ) ) )
									   : ( JText::_( 'ARTICLE SAVED' ) );
				$msgType = 'message';
			} else {
				$msg = JText::_( 'An error has occurred' );
				$msgType = 'error';
			}
			
			// Checkin!
			//$model->checkin();		//TODO:checkin
			
			$task	=	$this->getTask();
			if ( $cck == 2 || $cck == 3 ) {
				switch( $this->getTask() ) {
					case 'apply':
						$link		=	'index.php?option=com_cckjseblod&controller=interface&task2=1&tmpl=component&artid='.$rowId.'&u_opt='.$u_opt.'&u_task='.$u_task.'&act='.$actionMode.'&cck=2&lang_id='.$lang_id;					
						break;
					case 'savenew':
						$pc_userid	=	( $actionMode == 4 ) ? '&userid='.JRequest::getInt( 'userid' ) : '';
						$link		=	'index.php?option=com_cckjseblod&controller=interface&tmpl=component&cat_id='.$cat_id.'&u_opt='.$u_opt.'&u_task='.$u_task.'&artid=0'.$pc_userid.'&act='.$actionMode.'&cck=2';						
						break;
          			case 'savetranslate':
						$langs		=	JRequest::getVar( 'jseblod_jfarttranslations' );
						$num		=	sizeof( $langs );
						if ( $num > 0 ) {
							$link	=	'index.php?option=com_cckjseblod&controller=interface&tmpl=component&artid='.$rowId.'&u_opt='.$u_opt.'&u_task='.$u_task.'&act='.$actionMode.'&cck=2&lang_id='.$langs[0];
							if ( $num > 1 ) {
								array_shift( $langs );
								$elems	=	( is_array( $langs ) ) ? implode( ',', $langs ) : $langs;
								$link	.=	'&lang_next='.$elems;
							} else {
								$link	.=	'&lang_next=0';
							}
						} else {
							$link		=	'index.php?option=com_cckjseblod&controller=interface&task=close&action='.$actionMode;
						}
						$msg		=	null;
						$msgType	=	null;
						break;
					case 'save':
					default:
						$link		=	'index.php?option=com_cckjseblod&controller=interface&task=close&action='.$actionMode.'&lang_id='.$lang_id;
						break;
				}
				$this->setRedirect( $link );
			} else {
				switch( $task ) {
					case 'apply':										
						$link		=	'index.php?option=com_cckjseblod&controller=interface&task2=1&artid='.$rowId.'&u_opt='.$u_opt.'&u_task='.$u_task.'&act='.$actionMode.'&cck=1&lang_id='.$lang_id;
						$msg		=	null;
						$msgType	=	null;
						break;
					case 'savenew':
						$link		=	'index.php?option=com_cckjseblod&controller=interface&cat_id='.$cat_id.'&u_opt='.$u_opt.'&u_task='.$u_task.'&artid=0&act='.$actionMode.'&cck=1';
						$msg		=	null;
						$msgType	=	null;
						break;
					case 'savetranslate':
						$langs		=	JRequest::getVar( 'jseblod_jfarttranslations' );
						$num		=	sizeof( $langs );
						if ( $num > 0 ) {
							$link	=	'index.php?option=com_cckjseblod&controller=interface&artid='.$rowId.'&u_opt='.$u_opt.'&u_task='.$u_task.'&act='.$actionMode.'&cck=1&lang_id='.$langs[0];
							if ( $num > 1 ) {
								array_shift( $langs );
								$elems	=	( is_array( $langs ) ) ? implode( ',', $langs ) : $langs;
								$link	.=	'&lang_next='.$elems;
							} else {
								$link	.=	'&lang_next=0';
							}
						} else {
							$link		=	( $actionMode ) ? ( ( $actionMode == 1 ) ? 'index.php?option=com_categories&section=com_content' : 'index.php?option=com_users' )
															: 'index.php?option=com_content';
						}
						$msg		=	null;
						$msgType	=	null;
						break;
					case 'save':
					default:
						$u_lang		=	( $lang_id ) ? '&lang='.CCK_LANG_ShortCode( $lang_id ) : '';
						$link		=	( $actionMode ) ? ( ( $actionMode == 1 ) ? 'index.php?option=com_categories&section=com_content' : 'index.php?option=com_users' )
														: 'index.php?option=com_content'.$u_lang;
						break;
				}
				$this->setRedirect( $link, $msg, $msgType );
			}
			
		} else {
			// Past CCK Field Into Editor
			JRequest::setVar( 'hidemainmenu', 1 );
			JRequest::setVar( 'view', 'interface_content' );
			JRequest::setVar( 'layout', 'store' );
			
			switch( $this->getTask() ) {
				case 'apply':
					JRequest::setVar( 'doApply', true );
					JRequest::setVar( 'actionmode', $actionMode );
					break;
				case 'save':
					JRequest::setVar( 'doApply', false );
					JRequest::setVar( 'actionmode', $actionMode );
					break;
				default:
					JRequest::setVar( 'doApply', false );
					JRequest::setVar( 'actionmode', $actionMode );
					break;
			}
			parent::display();
		}
	}
	
	/**
	 * Dynamic Select Ajax
	 **/
	function dynamicSelectAjax() {
    
		$where 		= JRequest::getVar( 'where', '', 'get', 'string' );
		$item		= JRequest::getVar( 'item', '', 'get', 'string' );
		$label		= JRequest::getVar( 'label', '', 'get', 'string' );
		
		if ( !is_numeric( $where ) ) {
			$where = '"' . $where . '"';
		}
		//
		if ( $where && $item ) {
			$itemInfo		=	CCKjSeblodItem_Form::getDynamicSelectInfoFromDatabase( $item );
			$itemInfoOpt	=	explode( '||', $itemInfo->options );
			$required	 	=	( $itemInfo->required ) ? 'required required-enabled' : '';
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
		
		$db	=&	JFactory::getDBO();
		$query	=	'SELECT s.* FROM #__jseblod_cck_items AS s WHERE s.id='.(int)$itemId;
		$db->setQuery( $query );
		$item	=	$db->loadObject();
		
		$suffix	=	date("H-i-s");
		include_once( JPATH_SITE.DS.'media'.DS.'jseblod'.DS.'captcha-math'.DS.'captchaimage.php' );
		
		$required	=	( $item->required ) ? 'required required-enabled' : '';
		$data		=	'<img src="'.JURI::root( true ).'/tmp/jseblodcck-captcha/captcha-'.$suffix.'.jpg" style="margin-bottom: 6px; cursor: pointer;"'
					.	'alt=" " title="Captcha Image" id="captcha" onclick="javascript:reloadCaptcha();" /><br />';
	   	$data		.=	'<input class="inputbox text '.$item->required.'" type="text"  name="cptcsecure" value="" size="'.$item->size.'" />';
		echo $data;
    }

	/**
	 * Get Author Ajax
	 **/
	function getAuthorAjax() {
    
		$db		=&	JFactory::getDBO();
		$artId	=	JRequest::getVar( 'art_id', '', 'get', 'string' );
		
		if ( $artId ) {
			$query 		=	'SELECT created_by FROM #__content WHERE id='.$artId;
			$db->setQuery( $query );
			$author	=	$db->loadResult();		
		}
		echo ( @$author ) ? $author : 0;
    }

	/**
	 * Import Xml
	 **/
	function importXml()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		$cck = JRequest::getInt( 'cck' );
		$brb = JRequest::getInt( 'brb' );
		$act = JRequest::getInt( 'act' );
		$u_opt = JRequest::getString( 'u_opt' );
		$u_task = JRequest::getString( 'u_task' );
		$lang_id = JRequest::getString( 'lang_id' );
		
		$model = $this->getModel( 'interface_pack' );
		
		if ( $success = $model->importXml() ) {
			/*if ( JString::strpos( $success, '||' ) !== false ) {
				$ignored = explode( '||', $success );
				$msg = JText::_( 'PACK IMPORTED' ) . ' ( ' . $ignored[1] . ' ' . JText::_( 'IGNORED' ) . ' )';
			} else {
				$msg = JText::_( 'PACK IMPORTED' );
			}
			$msgType = 'message';*/
		} /*else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}*/

		$brb	=	( $brb == 1 ) ? '&brb=1' : '';
		$tmpl	=	( $cck == 1 ) ? '' : '&tmpl=component';
		$cck	=	( $cck == 1 ) ? '&cck=1' : '';
		$act	=	'&act='.$act;
		$u_opt	=	'&u_opt='.$u_opt;
		$u_task	=	'&u_opt='.$u_task;
		$lang_id =	'&lang_id='.$lang_id;
		
		$link = _LINK_CCKJSEBLOD.'&controller=interface'.$brb.$cck.$act.$u_opt.$u_task.$lang_id.$tmpl;
		
		$this->setRedirect( $link/*, $msg, $msgType*/ );
	}
	
	/**
	 * Remove && Redirect
	 **/
	function remove()
	{
		// Check for Request Forgeries
		// JRequest::checkToken() or die( 'Invalid Token' );
		$artid		=	JRequest::getInt( 'artid' );
				
		$model 		=	$this->getModel( 'interface' );
		if ( $model->delete( $artid ) ) {
			$msg		=	JText::sprintf( 'Items Removed', $total );
			$msgType	=	'message';
		} else {
			$msg		=	JText::_( 'An error has occurred' );
			$msgType	=	'error';
		}
		
		
		$link	=	'index.php?option=com_users';
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Remove && Redirect
	 **/
	function removeAjax()
	{
		$ids		=	JRequest::getVar( 'artids' );
		
		$ids		=	substr( $ids, 0, -1 );
		if ( $ids ) {
			$model 	=	$this->getModel( 'interface' );
			$model->deleteAjax( $ids );
		}
	}
	/**
	 * Remove && Redirect
	 **/
	function removeAjaxCategory()
	{
		$ids	=	JRequest::getVar( 'catids' );
		
		$ids		=	substr( $ids, 0, -1 );
		if ( $ids ) {
			$model 	=	$this->getModel( 'interface' );
			$model->deleteAjaxCategory( $ids );
		}
	}
}
?>