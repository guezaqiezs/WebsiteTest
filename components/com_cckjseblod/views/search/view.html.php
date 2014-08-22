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
jimport( 'joomla.error.profiler' );

/**
 * View Class
 **/
class CCKjSeblodViewSearch extends JView
{
	/**
	 * Display
	 **/
	function display( $tpl = null, $i_cckId = null, $i_searchid = null )
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
		
		/********************************************************************
 		 ************************* SEARCH -> DEFAULT ************************
	 	 ********************************************************************/
	 
		if ( $layout == 'default' ) {
			$searchsItems	=	$model->getData();
			
			$menus	=	&JSite::getMenu();
			$menu 	=	$menus->getActive();
			if ( is_object( $menu ) ) {
				$menu_params	=	new JParameter( $menu->params );
			}
			
			if ( count( $searchsItems ) == 1 && @$menu_params && $menu_params->get( 'auto_redirect', 0 ) ) {
				$i_cckId	=	0;
				$i_typeid	=	@$typesItems[0]->id;
				$this->setLayout( 'search' );
			} else {
				if ( @$menu_params && $menu_params->get( 'page_title' ) ) {
					$page_title	=	$menu_params->get( 'page_title' );
					$document->setTitle( $page_title );	
				} else {
					$page_title	=	JText::_( 'SEARCH TYPES' );
				}
		
				$this->assignRef( 'option', $option);
				$this->assignRef( 'controller', $controller );
				$this->assignRef( 'document', $document );
				//
				$this->assignRef( 'itemId', $itemId );
				$this->assignRef( 'menu_params', $menu_params );
				$this->assignRef( 'page_title', $page_title );
				//
				$this->assignRef( 'searchsItems', $searchsItems );
				
				parent::display( $tpl );
				return;
			}
		}

		/********************************************************************
 		 ******************** SEARCH -> SEARCH(FORM/RESULTS)*****************
	 	 ********************************************************************/
		$path		=	JPATH_THEMES;
					
		$user 		=&	CCK::USER_getUser();
		$uID		=	( $user->id ) ? $user->id : 0;
		$uGID		=	( $user->gid ) ? $user->gid : 0;
		$cckId		=  	( $i_cckId ) ? $i_cckId : ( ( $user->id ) ? JRequest::getInt( 'cckid' ) : 0 );
		//
		$itemId		=	JRequest::getInt( 'Itemid' );
		$searchid	=  	( $i_searchid ) ? $i_searchid : JRequest::getInt( 'searchid' );
		$templateid =  	JRequest::getInt( 'templateid' );
		
		// Get Data from Model
		$searchType	=	CCKjSeblodItem_Search::getSearch( $searchid );
		if ( ! $searchType ) {
			$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE NOT FOUND' ), "error" );
			return true;
		}
		
		if ( $params->get( 'show_form', 1 ) ) {
			$searchTemplate	=	CCKjSeblodItem_Form::getTemplate( $templateid, 0 );
			if ( ! $searchTemplate ) {
				$searchTemplate	=	CCKjSeblodItem_Form::getTemplate( $searchType->searchtemplate, 1 ); //TODO SEARCH
			}
			if ( !$searchTemplate ) {
				$mainframe->enqueueMessage( JText::_( 'TEMPLATE NOT FOUND' ), "error" );
				return true;
			}
			$template	=	@$searchTemplate->name;
			$auto		=	@$searchTemplate->mode;
		}
		
		$client			=	'search';
		$items			=	CCKjSeblodItem_Search::getItemsSearch( $searchType->id, $client, '', false, true );
		$itemsList		=	CCKjSeblodItem_Search::getItemsSearch( $searchType->id, 'list', '', true, true );
		$countItems		=	count( $items );
		$countItemsList	=	count( $itemsList );
		if ( ! $countItems /*&& ! $countItemsList*/ ) {
			$mainframe->enqueueMessage( JText::_( 'CONTENT TYPE EMPTY' ), "error" );
			return true;
		}
		// Live => Field=Value
		if ( $params->get( 'list_live', '' ) ) {
			$tempList	=	explode( '<br />', strtr( $params->get( 'list_live', '' ), array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />') ) );
			if ( sizeof( $tempList ) ) {
				foreach ( $tempList as $key => $val ) {
					$tab				=	explode( '=', $val );
					if ( isset( $tab[1] ) ) {
						$liveList[$tab[0]]	=	$tab[1];
					}
				}
			}
		}
		//
		
		//--!!
		if ( $items[0]->typename == 'search_action' ) {
			$actionMode		=	0; // TODOO ERASE ACTION MODE DANS CCKITEM_SEARCH.PHP
			$method			=	$items[0]->bool;
			$searchAreas	=	$items[0]->location;
			$limit			=	$items[0]->size;
			$searchLimit	=	$items[0]->maxlength;
			$searchIn		=	$items[0]->bool2;
			$searchLength	=	$items[0]->bool4;
			$searchMode		=	$items[0]->format;
			$message		=	$items[0]->message;
			$style			=	$items[0]->style;
			$cache			=	$items[0]->bool5;
			$cacheGroups	=	$items[0]->extra;
			$cacheRender	=	$items[0]->bool8;
			$debug			=	$items[0]->bool6;
			$sef			=	$items[0]->bool3;
		}
		$model->setCaching( $cache, $cacheGroups, $debug );
		
		define( '_ERROR_REFRESH_ITEMID',	$itemId );
		$formHidden = 	'';
		$task		=	JRequest::getVar( 'task' );
		if ( $task == 'search' ) {
			if ( $method ) {
				$post	=	JRequest::get( 'post' );
			} else {
				$post	=	JRequest::get( 'get' );
			}
			define( '_ERROR_REFRESH_TRUE',	'TRUE' );
		}
		if ( $params->get( 'show_form', 1 ) ) {
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonbefore_display.php' );
			$cckForm		=	null;
			$cckItems		=	null;
			if ( sizeof( $items ) ) {
				foreach ( $items as $item ) {
					$itemName	=	$item->name;
					$itemValue	=	null;
					if ( $task == 'search' ) {
						if ( $item->type == 11 ) {
							$itemName	=	$item->extended;
							$itemValue	=	@$post[$item->extended];
						//} else if ( $item->extended ) {
							//$prefix		=	str_replace( $itemName, '', $item->extended );
							//$itemValue	=	@$post[$prefix][$itemName];
						} else {
							$itemValue	=	@$post[$itemName];
						}
					}
					// - Security XSS
					if ( ! is_array( $itemValue ) ) {
						$itemValue	=	htmlspecialchars( $itemValue, ENT_QUOTES );
					}
					// -
					//T
					$doc->$itemName	=	CCKjSeblodItem_Search::getData( $item, $itemValue, $client, $cckId, null, $actionMode, $content, $rowU, 0, 0 );
					
					if ( $item->typename == 'search_action' ) {
						if ( $auto != 1 ) {
							$buffer	=	JFile::read( $path.DS.$template.DS.$file.'.php' );
							if ( JString::strpos( $buffer, $item->name.'->form' ) === false ) {
								$mainframe->enqueueMessage( "ERROR FORM NOT FOUND", "error" );
								return true;
							}
						}
						$formName		=	$item->name;
						if ( $auto == 1 ) {
							$cckForm	=	$itemName;
						}
						$searchAction	=	$doc->$itemName->form;
					} else {
						if ( $auto == 1 ) {
							$cckItems[]	=	$itemName;
						}
					}
				}
			}
		
			if ( ! @$formName ) {
				//$mainframe->enqueueMessage( "CONTENT TYPE NO ACTION", "error" );
				//return true; //TODO SEARCH
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
			}
			$doc->template = $searchTemplate->name;
			include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonafter_display.php' );
			$doc = null;
		} else {
			$getSearchAction	=	CCKjSeblodItem_Search::getData( $items[0], '', $client, $cckId, null, $actionMode, $content, $rowU, 0, 0 );
			$searchAction		=	$getSearchAction->form;
		}
		$menus	=	&JSite::getMenu();
		$menu	=	$menus->getActive();

		if ( is_object( $menu ) ) {
			$menu_params	=	new JParameter( $menu->params );
			if ( ! $menu_params->get( 'page_title') ) {
				$params->set( 'page_title',	$searchType->title );
				$page_title	=	$searchType->title;
			} else {
				$page_title	=	$menu_params->get( 'page_title' );
			}
		} else {
			$params->set( 'page_title',	JText::_( 'Search' ) );
			$page_title	=	$searchType->title;
		}

		$document->setTitle( $params->get( 'page_title' ) );
		
		$params	= &$mainframe->getParams();
		$params->merge( $menu_params );
		
		/********************************************************************
 		 ********************** SEARCH -> SEARCH/RESULT *********************
	 	 ********************************************************************/
		if ( $task == 'search' ) {
			$client	=	'list';
			require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_search'.DS.'helpers'.DS.'search.php' );
			
			// Initialize some variables
			$pathway  =& $mainframe->getPathway();
			$uri      =& JFactory::getURI();
	
			$error	= '';
			$rows	= null;
			$total	= 0;
	
			// Get some data from the model
			$areas      	=	&$this->get( 'areas' );
			$state 			=	&$this->get( 'state' );
			$searchword 	=	$state->get( 'keyword' );
			if ( $limit != -1 ) {
				$state->set( 'limit', $mainframe->getUserStateFromRequest( $limit, 'limit', $limit, 'int' ) );
			}
			$state->set( 'searchlimit', $searchLimit );
			$state->set( 'searchin', $searchIn );
			$state->set( 'searchmode', $searchMode );
			$state->set( 'searchlength', $searchLength );
			$searchphrase	=	( $state->get( 'match' ) ) ? $state->get( 'match' ) : $searchMode;
			
			// ALL
			$stage		=	0;
			$stages		=	array();
			$items		=	array_merge( $items, $itemsList );
			if ( $countItems ) {
				foreach( $items as $item ) {
					$itemValue	=	null;
					if ( $item->type == 11 ) {
						$item->name		=	$item->extended;
						$item->type2	=	CCK_DB_Result( 'SELECT s.type FROM #__jseblod_cck_items AS s WHERE s.name="'.$item->extended.'"' );
					}
					// Live
					//if ( $item->live == 'menu' ) {
					$itemValue	=	@$liveList[$item->name];
					//}
					if ( JRequest::getVar( $item->name ) != '' && !( $item->type == 27 || $item->type == 32 || $item->type == 43 ) ) {
						$itemValue	=	JRequest::getVar( $item->name );
						if ( is_array( $itemValue ) ) {
							$itemValue	=	implode( ( ( $item->helper ) ? $item->helper : ' ' ), $itemValue );
						}
						$itemValue	=	urldecode( $itemValue );
					}
					//
					if ( $itemValue != '' ) {
						$item->value	=	$itemValue;
					}
					//T
					if ( $method == 0 && @$itemsList[$item->name] /* && $item->value */ ) {
						$formHidden	.=	'<input type="hidden" name="'.$item->name.'" value="'.$item->value.'"/>';
					}
					// Stage
					if ( @$item->stage != 0 ) {
						$stages[$item->stage]	=	$item->stage_state;
					}
					// - Security
					// -
				}
			}
			$state->set( 'keywords', $items );
			$state->set( 'Itemid', $itemId );
			// Sort
			$sort	=	CCKjSeblodItem_Search::getItemsSearchContent( $searchType->id, 'sort' );
			$state->set( 'sortwords', $sort );
			//
						
			// Stage & Results (List)
			$countStages	=	count( $stages );
			if ( $countStages ) {
				for( $stage =  1; $stage <= $countStages; $stage++ ) {
					if ( ! $error ) {
						$state->set( 'stage', $stage );
						$state->set( 'stages', $stages );
						// Search
						$list	=	$model->getDataResult( $user );
						if ( ! $list && $stages[$stage] == 0 ) {
							$error	=	1;
							break;							
						}
						$stages[$stage]	=	implode( ' ', $list );
						// Debug
						if ( $debug ) {
							echo ( $debug == 2 ) ? JText::_( 'TEMPORARY RESULTS' ).' '.$stages[$stage].'<br /><hr class="cck-debug" /><br />' : '<hr class="cck-debug" /><br />';
						}
					}
				}
			}
			if ( ! $error ) {
				$state->set( 'stage', 0 );
				$state->set( 'stages', $stages );
				$list		=	$model->getDataResult( $user );
			} 
			//
			
			$total		=	&$this->get( 'total' );
			$pagination	=	$model->getPagination( @$params->get('pageclass_sfx') );
			$dataR		=	null;
			// Debug
			if ( $debug ) {
				$profiler	=	new JProfiler();
			}
			if ( $total ) {
				//
				if ( $sef == -1 ) {
					require_once ( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' );
					$sef_option		=	'';
				} else {
					require_once ( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'route.php' );
					if ( CCKjSeblodHelperRoute::isSEF() ) {
						$sef_option		=	'';
					} else {
						$sef_option		=	'option=com_content';
						$sef			=	0;
					}
				}
				// Redirect
				if ( $total == 1 && @$params->get( 'auto_redirect', 0 ) ) {
					if ( $sef == -1 ) {
						$href	=	ContentHelperRoute::getArticleRoute( $list[0]->slug, $list[0]->catslug, $list[0]->sectionid );
					} else {
						$href	=	CCKjSeblodHelperRoute::getArticleRoute( $list[0]->slug, $list[0]->catslug, $sef, $sef_option, $itemId );
					}
					if ( $href ) {
						$this->redirect( JRoute::_( $href ) );
						return;
					}
				}
				// Render
				if ( $searchType->content >= 2 ) {
					// List Template
					if ( $cacheRender ) {
						$cache	=&	JFactory::getCache();
						$cache->setCaching( 1 );
						$cache->_options['cachebase']	=	JPATH_CACHE.DS.'cck-cache-render'; //Method!
						$dataR	=	$cache->call( array( 'CCKjSeblodViewSearch', 'render' ), $list, $searchType, $path, $client, $itemId, $sef, $sef_option, $uID, $uGID );
					} else {
						$dataR	=	$this->render( $list, $searchType, $path, $client, $itemId, $sef, $sef_option, $uID, $uGID );
					}
					//
				} else {
					// Content Template
					$dispatcher	=&	JDispatcher::getInstance();
					JPluginHelper::importPlugin( 'content' );
					
					for ( $i = 0; $i < count( $list ); $i++ )
					{
						$listItem		=&	$list[$i];
						if ( $sef == -1 ) {
							$listItem->href	=	ContentHelperRoute::getArticleRoute( $listItem->slug, $listItem->catslug, $listItem->sectionid );
						} else {
							$listItem->href	=	CCKjSeblodHelperRoute::getArticleRoute( $listItem->slug, $listItem->catslug, $sef, $sef_option, $itemId );	
						}
						$rows[$i]				=	$listItem; //$rows[$i]->id = $listItem->id;
						$rows[$i]->text			=	( $searchType->content ) ? $listItem->introtext : $listItem->introtext.$listItem->fulltext;
						$rows[$i]->parameters	=	new JParameter( @$rows[$i]->attribs );
						$rows[$i]->event		=	new stdClass ();
						$results	=	$dispatcher->trigger( 'onPrepareContent', array ( &$rows[$i], & $rows[$i]->parameters, 0 ) );
						$results	=	$dispatcher->trigger( 'onAfterDisplayTitle', array ( $rows[$i], & $rows[$i]->parameters, 0 ) );
						$rows[$i]->event->afterDisplayTitle = trim( implode( "\n", $results ) );
						$results	=	$dispatcher->trigger( 'onBeforeDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, 0 ) );
						$rows[$i]->event->beforeDisplayContent = trim( implode( "\n", $results ) );
						$results	=	$dispatcher->trigger( 'onAfterDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, 0 ) );
						$rows[$i]->event->afterDisplayContent = trim( implode( "\n", $results ) );
						$listItem->text =	$rows[$i]->text;
						
						$listItem->created	=	( $listItem->created ) ? JHTML::Date( $listItem->created ) : '';
						$listItem->count	=	$i + 1;
					}
					//
				}
			}
			// Debug
			if ( $debug ) {
				echo $profiler->mark( JText::_( 'RENDER CACHING STATE'.$cacheRender ) ) . '<br /><br />';
			}
			//
			$this->result	= JText::sprintf( 'TOTALRESULTSFOUND', $total );
	
			$this->assignRef('pagination',  $pagination);
			$this->assignRef('results',		$list);
			
			$this->assignRef('lists',		$lists);
	
			$this->assign('ordering',		$state->get('ordering'));
			$this->assign('searchword',		$searchword);
			$this->assign('searchphrase',	$searchphrase);
			$this->assign('searchareas',	$areas);
	
			$this->assign('total',			$total);
			$this->assign('action', 	    $uri->toString());
			
			$this->assign('content', 	    $searchType->content );
			$this->assign('dataR',			$dataR );
			
		}
		/********************************************************************
 		 ********************************************************************/
		
		// Push Data into Template
		$this->assignRef( 'option', $option);
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		$this->assignRef( 'task', $task );
		$this->assignRef( 'params',	$params);
		$this->assignRef( 'page_title', $page_title );
		$this->assignRef( 'message', $message );
		$this->assignRef( 'style', $style );
		//
		$this->assignRef( 'formName', $formName );
		$this->assignRef( 'searchAction', $searchAction );
		$this->assignRef( 'formHidden', $formHidden );
		$this->assignRef( 'jsOnSubmit', $jsOnSubmit );
		//
		$this->assignRef( 'data', $data );
		//	
		$this->assignRef( 'searchid', $searchType->id );
		$this->assignRef( 'templateid', $searchTemplate->id );
		$this->assignRef( 'client', $client );
		$this->assignRef( 'itemId', $itemId );
		
		parent::display( $tpl );
		define( '_JSEBLOD_SITEFORM_SINGLEPASS',	'done' );
		}
	}
	
	/**
	 * Render
	 **/
	function render( $list, $searchType, $path, $client, $itemId, $sef, $sef_option, $uID, $uGID )
	{		
		$dataR		=	null;
		$item		=	null;
		$dispatcher	=&	JDispatcher::getInstance();
		
		$contentTemplate	=	CCKjSeblodItem_Form::getTemplate( $searchType->contenttemplate, 1 );
		$auto				=	$contentTemplate->mode;
		$myList				=	0;
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckonbefore_list.php' );
		
		$rows[$i]->id			=	0;
		$rows[$i]->text			=	$dataR;
		$rows[$i]->parameters	=	new JParameter( @$rows[$i]->attribs );
		$rows[$i]->event		=	new stdClass ();
		$results	=	$dispatcher->trigger( 'onPrepareContent', array ( &$rows[$i], & $rows[$i]->parameters, 0 ) );
		$results	=	$dispatcher->trigger( 'onAfterDisplayTitle', array ( $rows[$i], & $rows[$i]->parameters, 0 ) );
		$rows[$i]->event->afterDisplayTitle = trim( implode( "\n", $results ) );
		$results	=	$dispatcher->trigger( 'onBeforeDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, 0 ) );
		$rows[$i]->event->beforeDisplayContent = trim( implode( "\n", $results ) );
		$results	=	$dispatcher->trigger( 'onAfterDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, 0 ) );
		$rows[$i]->event->afterDisplayContent = trim( implode( "\n", $results ) );
		$dataR 		=	$rows[$i]->text;
		
		//$listItem->created	=	( $listItem->created ) ? JHTML::Date( $listItem->created ) : '';
		//$listItem->count		=	$i + 1;
		foreach( $docR as $key => $value ) {
			$docR->key	=	null;
			$docR->value =	null;
		}
		if ( JFile::exists( $fileToRender ) ) {
			JFile::delete( $fileToRender );
		}
		
		return $dataR;
	}
	
	/**
	 * Redirect
	 **/
	function redirect( $url )
	{
		global $mainframe;
		
		$url	=	htmlspecialchars_decode( $url );
		$mainframe->redirect( $url );
	}
}
?>