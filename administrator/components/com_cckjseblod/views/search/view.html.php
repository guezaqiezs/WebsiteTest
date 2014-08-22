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

/**
 * Search			View Class
 **/
class CCKjSeblodViewSearch extends JView
{
	/**
	 * Display Delete Toolbar
	 **/
	function _displayDeleteToolbar() 
	{
		JToolBarHelper::title(   JText::_( 'SEARCH TYPE' ).': <small><small>[ '.JText::_( 'Delete' ).' ]</small></small>', 'searchs.png' );
		JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Close' ), false ); //JToolBarHelper::cancel( 'cancel', 'Close' );
		JToolBarHelper::divider();
		HelperjSeblod_Display::help( 'searchs' );
	}
	
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isNew, $doCopy, $isAuth ) 
	{
		$bar	=&	JToolBar::getInstance( 'toolbar' );
		if ( $isNew && $isAuth ) {
			$bar->appendButton( 'Popup', 'types_jseblod', 'AUTO TYPE', 'index.php?option=com_cckjseblod&controller=searchs&task=searchtype&tmpl=component', _MODAL_WIDTH, 380 );
			JToolBarHelper::spacer();
		}
		$bar->appendButton( 'Link', 'listitems_jseblod', 'LIST', 'javascript: searchInterface(\'list\');', _MODAL_WIDTH, _MODAL_HEIGHT );
		$bar->appendButton( 'Link', 'searchitems_jseblod', 'SEARCH', 'javascript: searchInterface(\'search\');', _MODAL_WIDTH, _MODAL_HEIGHT );
		$bar->appendButton( 'Link', 'contentitems_jseblod', 'CONTENT', 'javascript: searchInterface(\'content\');', _MODAL_WIDTH, _MODAL_HEIGHT );
		JToolBarHelper::spacer();
		if ( $isAuth ) {
			JToolBarHelper::custom( 'save', 'save_jseblod', 'save_jseblod', JText::_( 'Save' ), false ); //JToolBarHelper::save();
			JToolBarHelper::custom( 'apply', 'apply_jseblod', 'apply_jseblod', JText::_( 'Apply' ), false ); //JToolBarHelper::apply();;
			JToolBarHelper::spacer();
		}
		if ( $isNew || $doCopy )  {
			$text = $doCopy ? JText::_( 'Copy' ) : JText::_( 'New' );
			JToolBarHelper::title(   JText::_( 'SEARCH TYPE' ).': <small><small>[ '.$text.' ]</small></small>', 'searchs.png' );
			JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Cancel' ), false ); //JToolBarHelper::cancel();;
		} else {
			$text = $isAuth ? JText::_( 'Edit' ) : JText::_( 'View' );
			JToolBarHelper::title(   JText::_( 'SEARCH TYPE' ).': <small><small>[ '.$text.' ]</small></small>', 'searchs.png' );
			JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Close' ), false ); //JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::divider();
		HelperjSeblod_Display::help( 'search' );
	}
	
	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$user 		=& JFactory::getUser();
		$controller = JRequest::getWord( 'controller' );
		$document	=& JFactory::getDocument();
		$model 		=& $this->getModel();
		
		
		$doDelete	=	JRequest::getVar( 'doDelete', false );
		$doSearch	=	JRequest::getVar( 'doSearch', false );
		$doList		=	JRequest::getVar( 'doList', false );
		$doType		=	JRequest::getVar( 'doType', false );
		$doContent	=	JRequest::getVar( 'doContent', false );
		
		if ( $doDelete ) {
		
			$searchRemoveItems	=& $this->get( 'RemoveData' );
			
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'searchRemoveItems', $searchRemoveItems );
			
			$this->_displayDeleteToolbar();
			
		} else if ( $doSearch ) {
			
			$liveAssigned	=	JRequest::getVar( 'searchitems' );
			$liveValues		=	JRequest::getVar( 'searchvalues' );
			
			$searchItems	=	$model->getItemsSearchInterface( $liveAssigned );
			//
			$parameter1		=	0;
			$parameter2		=	0;			
			if ( strpos( $liveValues, '::any::' ) !== false || strpos( $liveValues, '::any_exact::' ) !== false || strpos( $liveValues, '::each::' ) !== false
				|| strpos( $liveValues, '::any_exact_index::' ) !== false || strpos( $liveValues, '::num_lower::' ) !== false || strpos( $liveValues, '::num_higher::' ) !== false ) {
				$parameter1	=	1;
			}
			if ( strpos( $liveValues, '::any::' ) !== false || strpos( $liveValues, '::any_exact::' ) !== false || strpos( $liveValues, '::each::' ) !== false 
				|| strpos( $liveValues, '::any_exact_index::' ) !== false ) {
				$parameter2	=	1;
			}
			//
			$liveItems		=	explode( '||', $liveValues );
			if ( sizeof( $liveItems ) ) {
				$searchItemValues	=	array();
				foreach ( $liveItems as $assigned ) {
					$assignedValue	=	explode( '::', $assigned );
					if ( @$assignedValue[1] ) {
						$searchItemValues[$assignedValue[0]] 			=	@$assignedValue[1];
						$searchItemValues[$assignedValue[0].'_helper']	=	@$assignedValue[2];
						$searchItemValues[$assignedValue[0].'_helper2']	=	@$assignedValue[3];
						$searchItemValues[$assignedValue[0].'_target']	=	@$assignedValue[4];
						$searchItemValues[$assignedValue[0].'_group']	=	@$assignedValue[5];
						$searchItemValues[$assignedValue[0].'_stage']	=	@$assignedValue[6];
						$searchItemValues[$assignedValue[0].'_acl']		=	@$assignedValue[7];
					}
				}
			}
			
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'searchItems', $searchItems );
			$this->assignRef( 'searchItemValues', $searchItemValues );
			$this->assignRef( 'searchItemIds', $liveAssigned );
			$this->assignRef( 'parameter1', $parameter1 );
			$this->assignRef( 'parameter2', $parameter2 );
			
		} else if ( $doList ) {
			
			$liveAssigned	=	JRequest::getVar( 'searchitems' );
			$liveValues		=	JRequest::getVar( 'searchvalues' );
			$liveTypes		=	'';
			
			$searchItems	=	$model->getItemsSearchInterface( $liveAssigned );
			if ( sizeof( $searchItems ) ) {
				foreach ( $searchItems as $searchItem ) {
					$liveTypes	.=	$searchItem->typename.',';
				}
				$liveTypes	=	substr( $liveTypes, 0, -1 );
			}
			//
			$parameter1		=	0;
			$parameter2		=	0;			
			if ( strpos( $liveValues, '::any::' ) !== false || strpos( $liveValues, '::any_exact::' ) !== false || strpos( $liveValues, '::each::' ) !== false
				|| strpos( $liveValues, '::any_exact_index::' ) !== false || strpos( $liveValues, '::num_lower::' ) !== false || strpos( $liveValues, '::num_higher::' ) !== false ) {
				$parameter1	=	1;
			}
			if ( strpos( $liveValues, '::any::' ) !== false || strpos( $liveValues, '::any_exact::' ) !== false || strpos( $liveValues, '::each::' ) !== false
				|| strpos( $liveValues, '::any_exact_index::' ) !== false ) {
				$parameter2	=	1;
			}
			//
			$liveItems		=	explode( '||', $liveValues );
			if ( sizeof( $liveItems ) ) {
				$searchItemValues	=	array();
				$f	=	0;
				foreach ( $liveItems as $assigned ) {
					$assignedValue	=	explode( '::', $assigned );
					if ( @$assignedValue[1] ) {
						$searchItemValues[$assignedValue[0]] 			=	@$assignedValue[1];
						$searchItemValues[$assignedValue[0].'_value']	=	@$assignedValue[2];
						$searchItemValues[$assignedValue[0].'_helper']	=	@$assignedValue[3];
						$searchItemValues[$assignedValue[0].'_helper2']	=	@$assignedValue[4];
						$searchItemValues[$assignedValue[0].'_target']	=	@$assignedValue[5];
						$searchItemValues[$assignedValue[0].'_group']	=	@$assignedValue[6];
						$searchItemValues[$assignedValue[0].'_live']	=	@$assignedValue[7];
						$searchItemValues[$assignedValue[0].'_stage']	=	@$assignedValue[8];
						$searchItemValues[$assignedValue[0].'_acl']		=	@$assignedValue[9];
					}
					$f++;
				}
			}
			
			$liststage	=	JRequest::getVar( 'liststage' );
			$liststages	=	explode( '||', $liststage );
			
			$optRequiredStage		=	array();
			$optRequiredStage[] 	=	JHTML::_( 'select.option', 0, JText::_( 'REQUIRED' ) );
			$optRequiredStage[] 	=	JHTML::_( 'select.option', 1, JText::_( 'OPTIONAL' ) );
			$selectRequiredStage1	=	( @$liststages[0] ) ? 1 : 0;
			$selectRequiredStage2	=	( @$liststages[1] ) ? 1 : 0;
			$selectRequiredStage3	=	( @$liststages[2] ) ? 1 : 0;
			$selectRequiredStage4	=	( @$liststages[3] ) ? 1 : 0;
			$lists['req1_stage']	=	JHTML::_( 'select.genericlist', $optRequiredStage, 'req_stage1', 'size="1"', 'value', 'text', $selectRequiredStage1 );
			$lists['req2_stage']	=	JHTML::_( 'select.genericlist', $optRequiredStage, 'req_stage2', 'size="1"', 'value', 'text', $selectRequiredStage2 );
			$lists['req3_stage']	=	JHTML::_( 'select.genericlist', $optRequiredStage, 'req_stage3', 'size="1"', 'value', 'text', $selectRequiredStage3 );
			$lists['req4_stage']	=	JHTML::_( 'select.genericlist', $optRequiredStage, 'req_stage4', 'size="1"', 'value', 'text', $selectRequiredStage4 );
						
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'searchItems', $searchItems );
			$this->assignRef( 'searchItemValues', $searchItemValues );
			$this->assignRef( 'searchItemIds', $liveAssigned );
			$this->assignRef( 'searchItemTypes', $liveTypes );
			$this->assignRef( 'lists', $lists );
			$this->assignRef( 'parameter1', $parameter1 );
			$this->assignRef( 'parameter2', $parameter2 );
			
		} else if ( $doContent ) {
		
			$liveAssigned	=	JRequest::getVar( 'searchitems' );
			$liveValues		=	JRequest::getVar( 'searchvalues' );
			$templateId		=	JRequest::getVar( 'tmpl_id' );
			
			$searchItems	=	$model->getItemsSearchInterface( $liveAssigned );
			//
			$parameter1		=	0;
			if ( strpos( $liveValues, '::free::' ) !== false ) {
				$parameter1	=	1;
			}
			//
			$liveItems		=	explode( '||', $liveValues );
			if ( sizeof( $liveItems ) ) {
				$searchItemValues	=	array();
				foreach ( $liveItems as $assigned ) {
					$assignedValue	=	explode( '::', $assigned );
					//if ( @$assignedValue[1] ) {
						$searchItemValues[$assignedValue[0]] 				=	@$assignedValue[1];
						$searchItemValues[$assignedValue[0].'_width']		=	@$assignedValue[2];
						$searchItemValues[$assignedValue[0].'_helper']		=	str_replace( array( '[[', ']]', '@@', '^^' ), array( '<', '>', '&', '#' ), @$assignedValue[3] );
						$searchItemValues[$assignedValue[0].'_link']		=	@$assignedValue[4];
						//$searchItemValues[$assignedValue[0].'_link_helper']	=	@$assignedValue[5];
						$searchItemValues[$assignedValue[0].'_access']		=	@$assignedValue[5];
						$searchItemValues[$assignedValue[0].'_mode']		=	@$assignedValue[6];
					//}
				}
			}
			
			// Get Template Locations
			$templateName	=	$model->_getTemplateName( $templateId );
			$locations		=	HelperjSeblod_Helper::getTemplateLocations( $templateName );
			
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'searchItems', $searchItems );
			$this->assignRef( 'searchItemValues', $searchItemValues );
			$this->assignRef( 'searchItemIds', $liveAssigned );	
			$this->assignRef( 'locations', $locations );
			$this->assignRef( 'parameter1', $parameter1 );
		
		} else if ( $doType ) {
									
			$optContentTypes		=	array();
			$optContentTypes[]		=	JHTML::_( 'select.option',  '0', JText::_( 'SELECT A CONTENT TYPE' ), 'value', 'text' );
			$optContentTypes		=	array_merge( $optContentTypes, HelperjSeblod_Helper::getContentTypes() );
			$lists['content_type']	=	JHTML::_( 'select.genericlist', $optContentTypes, 'content_type', 'class="inputbox required required-enabled" size="1"', 'value', 'text' );

			$optMode				=	array();
			$optMode[]				=	JHTML::_( 'select.option',  0, JText::_( 'NONE' ), 'value', 'text' );
			$lists['mode']			=	JHTML::_( 'select.genericlist', $optMode, 'mode', 'class="inputbox" size="1"', 'value', 'text' );

			$optItems				=	array();
			$optItems[]				=	JHTML::_( 'select.option',  0, JText::_( 'NONE' ), 'value', 'text' );
			$optItems[]				=	JHTML::_( 'select.option',  1, JText::_( 'CONTENT TYPE' ), 'value', 'text' );
			$lists['items_list']	=	JHTML::_( 'select.genericlist', $optItems, 'items_list', 'class="inputbox" size="1"', 'value', 'text', 1 );
			
			$optItems				=	array();
			$optItems[]				=	JHTML::_( 'select.option',  0, JText::_( 'NONE' ), 'value', 'text' );
			$optItems[]				=	JHTML::_( 'select.option',  1, JText::_( 'ALL' ), 'value', 'text' );
			$lists['items_content']	=	JHTML::_( 'select.genericlist', $optItems, 'items_content', 'class="inputbox" size="1"', 'value', 'text' );

			$lists['bool']			=	JHTML::_( 'select.booleanlist', 'bool', 'class="inputbox"', 1 );
			
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'lists', $lists );

		} else {
		
			$into					= JRequest::getVar( 'into' );
			
			// Get Data from Model
			$search					=& $this->get( 'Data' );
			
			$assignedSearchFields	=	$model->getAssignedFields( 'search' );
			$availableSearchFields	=	$model->getAvailableFields( 'search' );
			$assignedListFields		=	$model->getAssignedFields( 'list' );
			$availableListFields	=	$model->getAvailableFields( 'list' );
			$assignedContentFields	=	$model->getAssignedFieldsContent( 'content' );
			$availableContentFields	=	$model->getAvailableFieldsContent( 'content' );
			$assignedSortFields		=	$model->getAssignedFieldsContent( 'sort' );
						
			// Set Flags
			$isNew		= ( @$search->id > 0 ) ? 0 : 1;
			$doCopy		= JRequest::getVar( 'doCopy', false );
			$isAuth 	= ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
		
			// Checking!
			if ( JTable::isCheckedOut( $user->get( 'id' ), @$search->checked_out ) ) {
				$msg = JText::sprintf( 'DESCBEINGEDITTED', '', $search->title );
				$mainframe->redirect( _LINK_CCKJSEBLOD_SEARCHS, $msg, 'notice' );
			}
		
			// Set Wysiwyg Modal
			$modals['description'] = HelperjSeblod_Display::quickModalWysiwyg( 'Description', $controller, 'description', 'pagebreak', 0, @$search->id, false );
			
			$tooltips['link_description'] = HelperjSeblod_Display::quickTooltipAjaxLink( 'Description', $controller, 'description', @$search->id );		
					
			// Set Assign to Template Modal
			$modals['selectSearchTemplate']	= HelperjSeblod_Display::quickModalTask( 'ASSIGN', '', 'searchtemplate', 'pagebreak', 'templates', 'select', _MODAL_WIDTH, _MODAL_HEIGHT, 'tpl_type=1' );
			
			// Set Assign to Template Modal
			$modals['selectContentTemplate']	=	HelperjSeblod_Display::quickModalTask( 'ASSIGN', '', 'contenttemplate', 'pagebreak', 'templates', 'select', _MODAL_WIDTH, _MODAL_HEIGHT, 'tpl_type=2' );
			
			// Set Published List ( Boolean List )
			$selectedPublished	= ( $isNew ) ? 1 : $search->published;
			$lists['published'] = JHTML::_( 'select.booleanlist', 'published', 'class="inputbox"', $selectedPublished );
			
			// Set Category List ( Select List )
			$optionCategories	= array();
			$optionCategories[]	= JHTML::_( 'select.option',  '', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
			$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
			$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
			$optionCategories	= ( ! $isNew && ! $search->categorystate ) ? array_merge( $optionCategories, HelperjSeblod_Helper::getSearchCategories( true, false ) ) : array_merge( $optionCategories, HelperjSeblod_Helper::getSearchCategories( true, true ) );
			$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$selectFilterInCategory	=	( JRequest::getInt( 'filter_search' ) == 5 ) ? JRequest::getInt( 'search' ) : '';
			$selectFilterCategory	=	( $selectFilterInCategory || JRequest::getInt( 'filter_category' ) ) ? ( ( $selectFilterInCategory ) ? $selectFilterInCategory : JRequest::getInt( 'filter_category' ) ) : ( _SEARCH_DEFAULT_CAT ? _SEARCH_DEFAULT_CAT : '' );
			$selectedCategory   = ( ! $isNew ) ? $search->category : $selectFilterCategory;
			$lists['category']	= JHTML::_( 'select.genericlist', $optionCategories, 'category', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectedCategory );
			
			// Set Field Assignments List ( Double Select List )
			$OptionsAvailableSearchFields 	= array();
			$OptionsAvailableSearchFields	= array_merge( $OptionsAvailableSearchFields, $availableSearchFields );
			$lists['assignedSearchFields']	= JHTML::_( 'select.genericlist', $assignedSearchFields, 'selected_searchfields[]', 'class="inputbox" size="18" onDblClick="dblclick_do(\'adminForm\',\'selected_searchfields\', adminForm.selected_searchfields.selectedIndex, \'search\');" multiple="multiple" style="padding: 6px; margin-top:4px; width: 190px; height: 254px;"', 'value', 'text' );
			$lists['availableSearchFields']	= JHTML::_( 'select.genericlist', $OptionsAvailableSearchFields, 'available_searchfields', 'class="inputbox" size="21" onDblClick="trytoassigntosearch();" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			$lists['hiddenSearchFields']	= JHTML::_( 'select.genericlist', array(), 'hidden_searchfields', 'class="inputbox" size="1" multiple="multiple" style="width: 1px; height: 1px; border: 1px solid #F6F6F6; display: none;"', 'value', 'text' );
			
			// Set Field Assignments List ( Double Select List )
			$OptionsAvailableListFields 	= array();
			$OptionsAvailableListFields	= array_merge( $OptionsAvailableListFields, $availableListFields );
			$lists['assignedListFields']	= JHTML::_( 'select.genericlist', $assignedListFields, 'selected_listfields[]', 'class="inputbox" size="21" onDblClick="dblclick_do(\'adminForm\',\'selected_listfields\', adminForm.selected_listfields.selectedIndex, \'list\');" multiple="multiple" style="padding: 6px; margin-top:4px; width: 190px; height: 254px;"', 'value', 'text' );
			$lists['availableListFields']	= JHTML::_( 'select.genericlist', $OptionsAvailableListFields, 'available_listfields', 'class="inputbox" size="21" onDblClick="trytoassigntolist();" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			$lists['hiddenListFields']	= JHTML::_( 'select.genericlist', array(), 'hidden_listfields', 'class="inputbox" size="1" multiple="multiple" style="width: 1px; height: 1px; border: 1px solid #F6F6F6; display: none;"', 'value', 'text' );
			
			$OptionsAvailableContentFields = array();
			$OptionsAvailableContentFields	= array_merge( $OptionsAvailableContentFields, $availableContentFields );
			$lists['assignedContentFields']	= JHTML::_( 'select.genericlist', $assignedContentFields, 'selected_contentfields[]', 'class="inputbox" size="21" onDblClick="dblclick_do(\'adminForm\',\'selected_contentfields\', adminForm.selected_contentfields.selectedIndex, \'content\');" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			$lists['availableContentFields'] = JHTML::_( 'select.genericlist', $OptionsAvailableContentFields, 'available_contentfields', 'class="inputbox" size="21" onDblClick="trytoassigntocontent();" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			$lists['hiddenContentFields']	= JHTML::_( 'select.genericlist', array(), 'hidden_contentfields', 'class="inputbox" size="1" multiple="multiple" style="width: 1px; height: 1px; border: 1px solid #F6F6F6; display: none;"', 'value', 'text' );
			
			$optSearchAction		= array();
			$defaultAction	=	$model->getDefaultAction();
			$optSearchAction		= array();
			$optSearchAction	= array_merge( $optSearchAction, $defaultAction );
			$optSearchAction	= array_merge( $optSearchAction, $model->getSearchActionItems() );
			$searchActionSelect 	=	$model->getSearchAction( 'search' );
			$required = ( sizeof( $assignedSearchFields ) ) ? 'required required-enabled' : '';
			$lists['searchActionItems']	= JHTML::_( 'select.genericlist', $optSearchAction, 'searchaction_item', 'class="inputbox '.$required.'" size="1"  style="padding: 6px; width: 190px;" onchange="$(\'listaction_item\').value=this.value;"', 'value', 'text', $searchActionSelect );
			$lists['listActionItems']	= JHTML::_( 'select.genericlist', $optSearchAction, 'listaction_item', 'class="inputbox '.$required.'" size="1"  style="padding: 6px; width: 190px;" onchange="$(\'searchaction_item\').value=this.value;"', 'value', 'text', $searchActionSelect );
			
			// Set Item Types List ( Select List )
			$optionItemTypes			= array();
			$optionItemTypes[]			= JHTML::_( 'select.option',  '', JText::_( 'ALL ITEM TYPES' ), 'value', 'text' );
			$optionItemTypes			= array_merge( $optionItemTypes, HelperjSeblod_Helper::getItemTypes( true ) );
			$lists['listFieldTypes']	= JHTML::_( 'select.genericlist', $optionItemTypes, 'listfield_types', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			$lists['searchFieldTypes']	= JHTML::_( 'select.genericlist', $optionItemTypes, 'searchfield_types', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			$lists['contentFieldTypes']	= JHTML::_( 'select.genericlist', $optionItemTypes, 'contentfield_types', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			
			// Set Item Categories List ( Select List )
			$optionItemCategories			= array();
			$optionItemCategories[]			= JHTML::_( 'select.option',  '', JText::_( 'ALL ITEM CATEGORIES' ), 'value', 'text' );
			$optionItemCategories[]			= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
			$optionItemCategories			= array_merge( $optionItemCategories, HelperjSeblod_Helper::getItemCategories2( true, false ) );
			$lists['listFieldCategories']	= JHTML::_( 'select.genericlist', $optionItemCategories, 'listfield_categories', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			$lists['searchFieldCategories']	= JHTML::_( 'select.genericlist', $optionItemCategories, 'searchfield_categories', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			$lists['contentFieldCategories']	= JHTML::_( 'select.genericlist', $optionItemCategories, 'contentfield_categories', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			
			$optContent			=	array();		
			$optContent[]		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'CONTENT TEMPLATES' ) );
			$optContent[] 		=	JHTML::_( 'select.option', 1, JText::_( 'CONTENT TEMPLATE INTROTEXT' ) );
			$optContent[] 		=	JHTML::_( 'select.option', 0, JText::_( 'CONTENT TEMPLATE FULLTEXT' ) );
			$optContent[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$optContent[]		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'LIST TEMPLATES' ) );
			$optContent[] 		=	JHTML::_( 'select.option', 3, JText::_( 'LIST TEMPLATE INTROTEXT' ) );
			$optContent[] 		=	JHTML::_( 'select.option', 4, JText::_( 'LIST TEMPLATE FULLTEXT' ) );
			$optContent[] 		=	JHTML::_( 'select.option', 2, JText::_( 'LIST TEMPLATE CUSTOMTEXT' ) );

			$optContent[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$selectContent		=	( ! $isNew ) ? $search->content : 0;
			$lists['content'] 	=	JHTML::_( 'select.genericlist', $optContent, 'content', 'size="1" class="inputbox"', 'value', 'text', $selectContent );
			
			if ( $isNew ) {
				$defaultSubmission	=	$model->_getTemplate( 1 );
				$this->assignRef( 'defaultSubmission', $defaultSubmission );
				$defaultContentId	=	CCK_DB_Result( 'SELECT s.id FROM #__jseblod_cck_templates AS s WHERE s.name = "default_list"' );
				$defaultContent		=	$model->_getTemplate( $defaultContentId );
				$this->assignRef( 'defaultContent', $defaultContent );
				$this->assignRef( 'defaultContentId', $defaultContentId );
			}
			
			// 1
			$searchmatch	=	'';
			if ( sizeof( $assignedSearchFields ) ) {
				foreach ( $assignedSearchFields as $assignedSearchField ) {
					if ( $assignedSearchField->searchmatch ) {
						$searchmatch	.=	$assignedSearchField->name.'::'.$assignedSearchField->searchmatch.'::'.$assignedSearchField->helper.'::'.$assignedSearchField->helper2.'::'.$assignedSearchField->target.'::'.$assignedSearchField->groupname.'::'.$assignedSearchField->stage.'::'.$assignedSearchField->acl.'||';
					}
				}
			}
			$searchmatch	=	substr( $searchmatch, 0, -2 );
			// 2
			$listmatch	=	'';
			$liststages	=	array();
			if ( sizeof( $assignedListFields ) ) {
				foreach ( $assignedListFields as $assignedListField ) {
					if ( $assignedListField->searchmatch ) {
						$listmatch	.=	$assignedListField->name.'::'.$assignedListField->searchmatch.'::'.$assignedListField->prevalue.'::'.$assignedListField->helper.'::'.$assignedListField->helper2.'::'.$assignedListField->target.'::'.$assignedListField->groupname.'::'.$assignedListField->live.'::'.$assignedListField->stage.'::'.$assignedListField->acl.'||';
					}
					if ( @$assignedListField->stage ) {
						if ( ! @$liststages[@$assignedListField->stage - 1] ) {
							$liststages[@$assignedListField->stage - 1]	=	( @$assignedListField->stage_state != '' ) ? @$assignedListField->stage_state : 0;
						}
					}
				}
			}
			$listmatch	=	substr( $listmatch, 0, -2 );
			// 3
			$contentdisplay	=	'';
			if ( sizeof( $assignedContentFields ) ) {
				foreach ( $assignedContentFields as $assignedContentField ) {
					//if ( $assignedContentField->contentdisplay ) {
						$contentdisplay	.=	@$assignedContentField->name.'::'.@$assignedContentField->contentdisplay.'::'.@$assignedContentField->width.'::'.@$assignedContentField->helper.'::'.@$assignedContentField->link.'::'.@$assignedContentField->access.'::'.@$assignedContentField->mode.'||';
					//}
				}
			}
			$contentdisplay	=	substr( $contentdisplay, 0, -2 );
			
			// Liststage
			$liststages[0]	=	( @$liststages[0] != '' ) ? $liststages[0] : 0;
			$liststages[1]	=	( @$liststages[1] != '' ) ? $liststages[1] : 0;
			$liststages[2]	=	( @$liststages[2] != '' ) ? $liststages[2] : 0;
			$liststages[3]	=	( @$liststages[3] != '' ) ? $liststages[3] : 0;
			$liststage		=	implode( '||', $liststages );
			
			// Sort
			$optSort				=	array();
			$optSort[]				=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'SORT CORE' ) );
			$optSort[]				=	JHTML::_( 'select.option',  25, JText::_( 'CREATED DATE' ) );
			$optSort[]				=	JHTML::_( 'select.option',  10, JText::_( 'TITLE' ) );
			$optSort[]				=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$optSort[]				=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'SORT CUSTOM' ) );
			$optSort[]				=	JHTML::_( 'select.option',  '--', JText::_( 'SELECT A FIELD' ) );
			if ( @$assignedSortFields[0]->id ) {
				$selectSort1	=	$assignedSortFields[0]->id;
				if ( ! ( $selectSort1 == '10' || $selectSort1 == '25' ) ) {
					$optSort[]	=	JHTML::_( 'select.option',  $selectSort1, $assignedSortFields[0]->text );
				}
			} else {
				$selectSort1	=	10;
			}
			$optSort[]				=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$lists['sort1']			=	JHTML::_( 'select.genericlist', $optSort, 'sort[]', 'size="1"', 'value', 'text', $selectSort1, 'sort1' );

//			$optSort[]				=	JHTML::_( 'select.option',  'authorname', JText::_( 'AUTHOR NAME' ) );
//			$optSort[]				=	JHTML::_( 'select.option',  'categorytitle', JText::_( 'CATEGORY TITLE' ) );
//			$optSort[]				=	JHTML::_( 'select.option',  'hits', JText::_( 'HITS' ) );
			
			$optSort				=	array();
			$optSort[]				=	JHTML::_( 'select.option',  0, JText::_( 'NONE' ) );
			$optSort[]				=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'SORT CORE' ) );
			$optSort[]				=	JHTML::_( 'select.option',  25, JText::_( 'CREATED DATE' ) );
			$optSort[]				=	JHTML::_( 'select.option',  10, JText::_( 'TITLE' ) );
			$optSort[]				=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$optSort[]				=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'SORT CUSTOM' ) );
			$optSort[]				=	JHTML::_( 'select.option',  '--', JText::_( 'SELECT A FIELD' ) );
			$optSort[]				=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
			if ( @$assignedSortFields[1]->id ) {
				$selectSort2	=	$assignedSortFields[1]->id;
				if ( ! ( $selectSort2 == '10' || $selectSort2 == '25' ) ) {
					$optSort[]	=	JHTML::_( 'select.option',  $selectSort2, $assignedSortFields[1]->text );
				}
			} else {
				$selectSort2	=	0;
			}
			if ( @$assignedSortFields[2]->id ) {
				$selectSort3	=	$assignedSortFields[2]->id;
				if ( ! ( $selectSort3 == '10' || $selectSort3 == '25' ) ) {
					$optSort[]	=	JHTML::_( 'select.option',  $selectSort3, $assignedSortFields[2]->text );
				}
			} else {
				$selectSort3	=	0;
			}
			if ( @$assignedSortFields[3]->id ) {
				$selectSort4	=	$assignedSortFields[3]->id;
				if ( ! ( $selectSort4 == '10' || $selectSort4 == '25' ) ) {
					$optSort[]	=	JHTML::_( 'select.option',  $selectSort4, $assignedSortFields[3]->text );
				}
			} else {
				$selectSort4	=	0;
			}
			$lists['sort2']			=	JHTML::_( 'select.genericlist', $optSort, 'sort[]', 'size="1"', 'value', 'text', $selectSort2, 'sort2' );
			$lists['sort3']			=	JHTML::_( 'select.genericlist', $optSort, 'sort[]', 'size="1"', 'value', 'text', $selectSort3, 'sort3' );
			$lists['sort4']			=	JHTML::_( 'select.genericlist', $optSort, 'sort[]', 'size="1"', 'value', 'text', $selectSort4, 'sort4' );
			
			$optSortType			=	array();
			$optSortType[]			=	JHTML::_( 'select.option',  'ASC', JText::_( 'ASCENDANT' ), 'value', 'text' );
			$optSortType[]			=	JHTML::_( 'select.option',  'DESC', JText::_( 'DESCENDANT' ), 'value', 'text' );
			$optSortType[]			=	JHTML::_( 'select.option', 	'<OPTGROUP>', JText::_( 'VALUE' ) );
			$optSortType[]			=	JHTML::_( 'select.option',	'CUSTOM', JText::_( 'CUSTOM' ), 'value', 'text' );
			$optSortType[] 			=	JHTML::_( 'select.option', 	'CUSTOM_STAGE', JText::_( 'STAGE RESULTS' ), 'value', 'text' );
			$optSortType[]			=	JHTML::_( 'select.option', 	'</OPTGROUP>', '' );
			
			$selectSortType1		=	( @$assignedSortFields[0]->contentdisplay ) ? $assignedSortFields[0]->contentdisplay : 'ASC';
			$selectSortType2		=	( @$assignedSortFields[1]->contentdisplay ) ? $assignedSortFields[1]->contentdisplay : 'ASC';
			$selectSortType3		=	( @$assignedSortFields[2]->contentdisplay ) ? $assignedSortFields[2]->contentdisplay : 'ASC';
			$selectSortType4		=	( @$assignedSortFields[3]->contentdisplay ) ? $assignedSortFields[3]->contentdisplay : 'ASC';
			$lists['sort1_type']	=	JHTML::_( 'select.genericlist', $optSortType, 'sort_type[]', 'size="1"', 'value', 'text', $selectSortType1, 'sorttype1' );
			$lists['sort2_type']	=	JHTML::_( 'select.genericlist', $optSortType, 'sort_type[]', 'size="1"', 'value', 'text', $selectSortType2, 'sorttype2' );
			$lists['sort3_type']	=	JHTML::_( 'select.genericlist', $optSortType, 'sort_type[]', 'size="1"', 'value', 'text', $selectSortType3, 'sorttype3' );
			$lists['sort4_type']	=	JHTML::_( 'select.genericlist', $optSortType, 'sort_type[]', 'size="1"', 'value', 'text', $selectSortType4, 'sorttype4' );
			
			$optSortMode			=	array();
			$optSortMode[]			=	JHTML::_( 'select.option',  '', JText::_( 'TEXT' ), 'value', 'text' );
			$optSortMode[]			=	JHTML::_( 'select.option',  'numeric', JText::_( 'NUMERIC' ), 'value', 'text' );
			$selectSortMode1		=	( @$assignedSortFields[0]->width ) ? $assignedSortFields[0]->width : '';
			$selectSortMode2		=	( @$assignedSortFields[1]->width ) ? $assignedSortFields[1]->width : '';
			$selectSortMode3		=	( @$assignedSortFields[2]->width ) ? $assignedSortFields[2]->width : '';
			$selectSortMode4		=	( @$assignedSortFields[3]->width ) ? $assignedSortFields[3]->width : '';
			$lists['sort1_mode']	=	JHTML::_( 'select.genericlist', $optSortMode, 'sort_mode[]', 'size="1"', 'value', 'text', $selectSortMode1 );
			$lists['sort2_mode']	=	JHTML::_( 'select.genericlist', $optSortMode, 'sort_mode[]', 'size="1"', 'value', 'text', $selectSortMode2 );
			$lists['sort3_mode']	=	JHTML::_( 'select.genericlist', $optSortMode, 'sort_mode[]', 'size="1"', 'value', 'text', $selectSortMode3 );
			$lists['sort4_mode']	=	JHTML::_( 'select.genericlist', $optSortMode, 'sort_mode[]', 'size="1"', 'value', 'text', $selectSortMode4 );
			
			$lists['sort1_helper']	=	'<input class="inputbox" type="text"  id="sort_helper1" name="sort_helper[]" maxlength="250" size="26" value="'.@$assignedSortFields[0]->helper.'" />';
			$lists['sort2_helper']	=	'<input class="inputbox" type="text"  id="sort_helper2" name="sort_helper[]" maxlength="250" size="26" value="'.@$assignedSortFields[1]->helper.'" />';
			$lists['sort3_helper']	=	'<input class="inputbox" type="text"  id="sort_helper3" name="sort_helper[]" maxlength="250" size="26" value="'.@$assignedSortFields[2]->helper.'" />';
			$lists['sort4_helper']	=	'<input class="inputbox" type="text"  id="sort_helper4" name="sort_helper[]" maxlength="250" size="26" value="'.@$assignedSortFields[3]->helper.'" />';
			
			$selectSortTarget		=	( @$assignedSortFields[0]->target ) ? explode( '~', $assignedSortFields[0]->target ) : explode( '~', '~' );
			$lists['sort1_bot']		=	'<input class="inputbox" type="text"  id="sort_bot1" name="sort_bot[]" maxlength="4"  size="4" value="'.$selectSortTarget[0].'" style="text-align: center;" />';
			$lists['sort1_eot']		=	'<input class="inputbox" type="text"  id="sort_eot1" name="sort_eot[]" maxlength="4"  size="4" value="'.$selectSortTarget[1].'" style="text-align: center;" />';
			$selectSortTarget		=	( @$assignedSortFields[1]->target ) ? explode( '~', $assignedSortFields[1]->target ) : explode( '~', '~' );
			$lists['sort2_bot']		=	'<input class="inputbox" type="text"  id="sort_bot2" name="sort_bot[]" maxlength="4"  size="4" value="'.$selectSortTarget[0].'" style="text-align: center;" />';
			$lists['sort2_eot']		=	'<input class="inputbox" type="text"  id="sort_eot2" name="sort_eot[]" maxlength="4"  size="4" value="'.$selectSortTarget[1].'" style="text-align: center;" />';
			$selectSortTarget		=	( @$assignedSortFields[2]->target ) ? explode( '~', $assignedSortFields[2]->target ) : explode( '~', '~' );
			$lists['sort3_bot']		=	'<input class="inputbox" type="text"  id="sort_bot3" name="sort_bot[]" maxlength="4"  size="4" value="'.$selectSortTarget[0].'" style="text-align: center;" />';
			$lists['sort3_eot']		=	'<input class="inputbox" type="text"  id="sort_eot3" name="sort_eot[]" maxlength="4"  size="4" value="'.$selectSortTarget[1].'" style="text-align: center;" />';
			$selectSortTarget		=	( @$assignedSortFields[3]->target ) ? explode( '~', $assignedSortFields[3]->target ) : explode( '~', '~' );
			$lists['sort4_bot']		=	'<input class="inputbox" type="text"  id="sort_bot4" name="sort_bot[]" maxlength="4"  size="4" value="'.$selectSortTarget[0].'" style="text-align: center;" />';
			$lists['sort4_eot']		=	'<input class="inputbox" type="text"  id="sort_eot4" name="sort_eot[]" maxlength="4"  size="4" value="'.$selectSortTarget[1].'" style="text-align: center;" />';
			
			$optStage				=	array();
			$optStage[] 			=	JHTML::_( 'select.option', '0', JText::_( 'FINAL' ).' ' );
			$optStage[] 			=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'TEMPORARY' ) );
			$optStage[] 			=	JHTML::_( 'select.option', '1', JText::_( 'TEMP1' ) );
			$optStage[] 			=	JHTML::_( 'select.option', '2', JText::_( 'TEMP2' ) );
			$optStage[] 			=	JHTML::_( 'select.option', '3', JText::_( 'TEMP3' ) );
			$optStage[] 			=	JHTML::_( 'select.option', '4', JText::_( 'TEMP4' ) );
			$optStage[]				=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$selectStage1			=	( @$assignedSortFields[0]->stage ) ? $assignedSortFields[0]->stage : '0';
			$selectStage2			=	( @$assignedSortFields[1]->stage ) ? $assignedSortFields[1]->stage : '0';
			$selectStage3			=	( @$assignedSortFields[2]->stage ) ? $assignedSortFields[2]->stage : '0';
			$selectStage4			=	( @$assignedSortFields[3]->stage ) ? $assignedSortFields[3]->stage : '0';
			$lists['sort1_stage']	=	JHTML::_( 'select.genericlist', $optStage, 'sort_stage[]', 'size="1"', 'value', 'text', $selectStage1 );
			$lists['sort2_stage']	=	JHTML::_( 'select.genericlist', $optStage, 'sort_stage[]', 'size="1"', 'value', 'text', $selectStage2 );
			$lists['sort3_stage']	=	JHTML::_( 'select.genericlist', $optStage, 'sort_stage[]', 'size="1"', 'value', 'text', $selectStage3 );
			$lists['sort4_stage']	=	JHTML::_( 'select.genericlist', $optStage, 'sort_stage[]', 'size="1"', 'value', 'text', $selectStage4 );
						
			// Push Data to Template
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'doCopy', $doCopy );
			$this->assignRef( 'into', $into );
			$this->assignRef( 'search', $search );
			$this->assignRef( 'lists', $lists );
			$this->assignRef( 'modals', $modals );
			$this->assignRef( 'tooltips', $tooltips );
			$this->assignRef( 'searchmatch', $searchmatch );
			$this->assignRef( 'listmatch', $listmatch );
			$this->assignRef( 'liststage', $liststage );
			$this->assignRef( 'contentdisplay', $contentdisplay );
			$this->assignRef( 'selectSort2', $selectSort2 );
			$this->assignRef( 'selectSort3', $selectSort3 );
			$this->assignRef( 'selectSort4', $selectSort4 );
			$this->assignRef( 'selectSortType1', $selectSortType1 );
			$this->assignRef( 'selectSortType2', $selectSortType2 );
			$this->assignRef( 'selectSortType3', $selectSortType3 );
			$this->assignRef( 'selectSortType4', $selectSortType4 );
			
			$this->_displayToolbar( $isNew, $doCopy, $isAuth );
		}
		
		parent::display( $tpl );
	}
	
}
?>
