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
 * Interface	View Class
 **/
class CCKjSeblodViewInterface extends JView
{
	/**
	 * Get User
	 **/	
	function _getOneShotUser( $userId, $contentTypeObj ) {
		$datenow			=&	JFactory::getDate();
		
		JTable::addIncludePath( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table' );
		$rowUser			=&	JTable::getInstance( 'user' );
		$rowUser->load( $userId );
		$row				=&	JTable::getInstance( 'content' );
		$row->title			=	$rowUser->username;
		$row->state			=	$contentTypeObj->state;
		$row->alias 		=	JFilterOutput::stringURLSafe( $row->title );
		if( trim( str_replace( '-', '', $row->alias ) ) == '' ) {	
			$row->alias		=	$datenow->toFormat( "%Y-%m-%d-%H-%M-%S" );
		}
		$row->catid			=	$contentTypeObj->category;
		$row->sectionid		=	CCKjSeblodItem_Form::getResultFromDatabase( 'SELECT section FROM #__categories WHERE id = '.$contentTypeObj->category );
		$row->access		=	$contentTypeObj->access;
		$row->created		=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
		$row->publish_up	=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
		$row->introtext		=	'<br />::jseblod::'.$contentTypeObj->name.'::/jseblod::<br />::jseblodend::::/jseblodend::'; //Incomplete but not really a pb!
		$row->created_by	=	$userId;
		if ( ! $row->store() ) {
			return false;
		}
		$artId	=	$row->id;
		// U
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'tables' );
		$newUser					=	array();
		$newUser['contentid']		=	$row->id;
		$newUser['userid']			=	$userId;
		$newUser['type']			=	$contentTypeObj->name;
		$newUser['registration']	=	1;
		$CCKUser	=&	JTable::getInstance( 'users', 'Table' );
		$CCKUser->bind( $newUser );
		$CCKUser->setType( $newUser['contentid'], $newUser['type'] );
		$CCKUser->store();
		//
		
		return $artId;
	}

	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$user 		=& JFactory::getUser();
		$controller	= JRequest::getWord( 'controller' );
		$document	=& JFactory::getDocument();
		$model 		=&	$this->getModel();
		
		$cck = JRequest::getInt( 'cck' );
		$brb = JRequest::getInt( 'brb' );
		$act = JRequest::getInt( 'act' );
		
		// Before Loading Current View Check Whether Content Type Assigned
		$artId 		=	JRequest::getInt( 'artid' );
		$cat_id		=	JRequest::getInt( 'cat_id' );
		$u_opt 		=	JRequest::getVar( 'u_opt' );
		$u_task 		=	JRequest::getVar( 'u_task' );
		
    	$lang_id 	=	JRequest::getVar( 'lang_id' );
		$lang_next	=	JRequest::getVar( 'lang_next' );
		$e_name 	=	JRequest::getVar( 'e_name' );
		
		$selection = JRequest::getInt( 'selection' );
		
		// Others...
		if ( ! $selection ) {
			if ( $artId == -1 ) {
				$contentType = JRequest::getVar( 'e_type' );
				if ( $contentType ) {
					$doAjax = 1;
				}
				// By Component Option
				if ( ! @$contentType && $u_opt ) {
					$contentTypeObj	=	$model->getContentTypeByUrl( $u_opt, $artId );
					if ( $contentTypeObj ) {
						$contentType	=	$contentTypeObj->name;
					}
					$doAjax			=	( @$contentType ) ? 1 : 0;
				}
			} else {
				if ( $act == 1 ) {
					if ( $artId ) {
						$article =& $this->get( 'Category' );
						$regex = "#"._OPENING."jseblod"._CLOSING."(.*?)"._OPENING."/jseblod"._CLOSING."#s";
						preg_match_all( $regex, $article->content, $contentMatches );
						$contentType = @$contentMatches[1][0];
						$doAjax = ( $contentType ) ? 1 : 0;
						
						// By Url ( Component Option )
						if ( ! @$contentType && $u_opt ) {
							$contentTypeObj	=	$model->getContentTypeByUrl( $u_opt, $artId );
							if ( $contentTypeObj ) {
								$contentType	=	$contentTypeObj->name;
							}
							$doAjax 		=	( $contentType ) ? 1 : 0;
						}
					}
				} else if ( $act == 4 ) {
					$userId	=	JRequest::getInt( 'userid' );
					if ( $artId && $userId ) {
						$contentType	=	CCKjSeblodItem_Form::getResultFromDatabase( 'SELECT type FROM #__jseblod_cck_users WHERE contentid='.$artId.' AND userid='.$userId );
						$doAjax 		=	( $contentType ) ? 1 : 0;
					} else if ( $artId && ! $userId ) {
						$article =& $model->getArticle( $artId, $lang_id );					
						$regex = "#"._OPENING."jseblod"._CLOSING."(.*?)"._OPENING."/jseblod"._CLOSING."#s";
						preg_match_all( $regex, $article->content, $contentMatches );
						$contentType = @$contentMatches[1][0];
						$doAjax = ( $contentType ) ? 1 : 0;
					} else {}
				} else {
					if ( $act == 2 ) {
						if ( $artId ) {
							$task2	=	JRequest::getInt( 'task2' );
							if ( $task2 == 1 ) {								
								$contentType	=	CCKjSeblodItem_Form::getResultFromDatabase( 'SELECT type FROM #__jseblod_cck_users WHERE contentid ='.$artId.' AND registration = 1' );								
								$doAjax = ( $contentType ) ? 1 : 0;
							} else {
								$userId	=	$artId;
								$artId	=	CCKjSeblodItem_Form::getResultFromDatabase( 'SELECT contentid FROM #__jseblod_cck_users WHERE userid ='.$userId.' AND registration = 1' );
								if ( ! $artId ) {
									$contentTypeObj	=	$model->getContentTypeByUrl( $u_opt, $artId, true );
									$contentType	=	$contentTypeObj->name;
									if ( $contentType ) {
										$artId		=	$this->_getOneShotUser( $userId, $contentTypeObj );
										$doAjax		=	1;
									}
								}
							}
						}
					}
					// By Article Content
					if ( ! @$contentType && $artId ) {
						$article =& $model->getArticle( $artId, $lang_id );					
						$regex = "#"._OPENING."jseblod"._CLOSING."(.*?)"._OPENING."/jseblod"._CLOSING."#s";
						preg_match_all( $regex, $article->content, $contentMatches );
						$contentType = @$contentMatches[1][0];
						$doAjax = ( $contentType ) ? 1 : 0;
						// By Redirection
						$cat_id = ( ! $cat_id ) ? $article->catid : $cat_id;
					}
					// By Joomla Category Assignment
					if ( ! @$contentType && $cat_id ) {
						$contentType = $model->getContentTypeByCat( $cat_id );
						$doAjax = ( $contentType ) ? 1 : 0;
					}
					// By Url ( Component Option )
					if ( ! @$contentType && $u_opt ) {
						$contentTypeObj	=	$model->getContentTypeByUrl( $u_opt, $artId );
						if ( $contentTypeObj ) {
							$contentType	=	$contentTypeObj->name;
						}
						$doAjax			=	( @$contentType ) ? 1 : 0;
					}
				}
				// By Url
				if ( ! @$contentType && JRequest::getVar( 'ccktype' ) ) {
					$contentType = JRequest::getVar( 'ccktype' );
					$doAjax = 1;
				}
			}
		}
		// Ajax ( SetContentLayout) or Not ( SetSelectionLayout) ??
		if ( @$doAjax ) {
			$this->assignRef( 'option', 		$option );
			$this->assignRef( 'controller', 	$controller );
			$this->assignRef( 'document',		$document );
			$this->assignRef( 'contentType',	$contentType );
			$this->assignRef( 'artId', 			$artId );
			$this->assignRef( 'cck', 			$cck );
			$this->assignRef( 'brb', 			$brb );
			$this->assignRef( 'act', 			$act );
			$this->assignRef( 'cat_id', 			$cat_id );
			$this->assignRef( 'u_opt', 			$u_opt );
			$this->assignRef( 'u_task', 			$u_task );
			$this->assignRef( 'lang_id', 		$lang_id );
			$this->assignRef( 'lang_next', 		$lang_next );
			$this->assignRef( 'e_name', 		$e_name );
			if ( $act == 4 ) {
				$this->assignRef( 'userid', 	$userId );
			}
		} else {
			// Get Data from Model
			$pagination		=	$model->getPagination( $act );
			$typesItems		=	$model->getData( $act );
			// Set Flags
			//$isAuth = ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
			
			// Get User State
			$filter_order		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order',			'filter_order',			's.title',	'cmd' );
			$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_order_Dir',		'filter_order_Dir',		'asc',		'cmd' );
			$filter_category	= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_category',		'filter_category',		0,			'int' );
			$filter_search		= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.filter_search',		'filter_search',		0,			'int' );
			$search				= $mainframe->getUserStateFromRequest( $option.'.'.$controller.'.search',				'search',				'',			'string' );
			$search				= JString::strtolower( $search );

			// Set Table Ordering
			$lists['order_Dir']	= $filter_order_Dir;
			$lists['order']		= $filter_order;
			
			// Set Search Filter
			$options_search[] 		= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'CONTENT TYPES' ) );
			$options_search[] 		= JHTML::_( 'select.option', '0', JText::_( 'Title' ) );
			$options_search[] 		= JHTML::_( 'select.option', '1', JText::_( 'Name' ) );
			$options_search[] 		= JHTML::_( 'select.option', '2', JText::_( 'Description' ) );
			$options_search[] 		= JHTML::_( 'select.option', '3', JText::_( 'MINUS ID' ) . '&nbsp;(*)' );
			$options_search[]		= JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$options_search[] 		= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'TYPE CATEGORIES' ) );
			$options_search[] 		= JHTML::_( 'select.option', '4', JText::_( 'CATEGORY TITLE' ) );
			$options_search[] 		= JHTML::_( 'select.option', '5', JText::_( 'CATEGORY ID' ) . '&nbsp;(*)' );
			$options_search[]		= JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$options_search[] 		= JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'CONTENT ITEM' ) );
			$options_search[] 		= JHTML::_('select.option', '6', JText::_( 'CONTENT ITEM TITLE' ) );
			$options_search[] 		= JHTML::_('select.option', '7', JText::_( 'CONTENT ITEM NAME' ) );
			$options_search[] 		= JHTML::_('select.option', '8', JText::_( 'CONTENT ITEM ID' ) . '&nbsp;(*)' );
			$options_search[]		= JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$lists['filter_search'] = JHTML::_( 'select.genericlist', $options_search, 'filter_search', 'size="1" class="inputbox"', 'value', 'text', $filter_search );
			
			// Set Search Box
			$lists['search']	= $search;
			
			// Set Category Filter
			$javascript 	= 'onchange="document.adminForm.submit();"';
			$optionCategories	= array();
			$optionCategories[]	= JHTML::_( 'select.option',  '0', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
			$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
			$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
			$optionCategories	= array_merge( $optionCategories, HelperjSeblod_Helper::getTypeCategories( false, true ) );
			$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$lists['category']	= JHTML::_('select.genericlist', $optionCategories, 'filter_category', $javascript, 'value', 'text', $filter_category );
			
			// Push Data into Template
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'typesItems', $typesItems );
			$this->assignRef( 'pagination', $pagination );
			//$this->assignRef( 'isAuth', $isAuth );
			$this->assignRef( 'artId', 		$artId );
			$this->assignRef( 'lists', $lists );
			$this->assignRef( 'cck', $cck );
			$this->assignRef( 'brb', $brb );
			$this->assignRef( 'act', $act );
			$this->assignRef( 'cat_id', $cat_id );
			$this->assignRef( 'u_opt', $u_opt );
			$this->assignRef( 'u_task', $u_task );
			$this->assignRef( 'lang_id', $lang_id );
			$this->assignRef( 'e_name', $e_name );
			if ( $act == 4 ) {
				$this->assignRef( 'userid', $userId );
			}
		}
		
		parent::display( $tpl );
	}
}
?>