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
 * Content Type		View Class
 **/
class CCKjSeblodViewType extends JView
{
	/**
	 * Display Delete Toolbar
	 **/
	function _displayDeleteToolbar() 
	{
		JToolBarHelper::title(   JText::_( 'CONTENT TYPE' ).': <small><small>[ '.JText::_( 'Delete' ).' ]</small></small>', 'types.png' );
		JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Close' ), false ); //JToolBarHelper::cancel( 'cancel', 'Close' );
		JToolBarHelper::divider();
		HelperjSeblod_Display::help( 'types' );
	}
	
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isNew, $doCopy, $isAuth ) 
	{
		$bar	=&	JToolBar::getInstance( 'toolbar' );
		$bar->appendButton( 'Link', 'adminitems_jseblod', 'ADMIN', 'javascript: typeInterface(\'admin\');', _MODAL_WIDTH, _MODAL_HEIGHT );
		$bar->appendButton( 'Link', 'siteitems_jseblod', 'SITE', 'javascript: typeInterface(\'site\');', _MODAL_WIDTH, _MODAL_HEIGHT );
		$bar->appendButton( 'Link', 'contentitems_jseblod', 'CONTENT', 'javascript: typeInterface(\'content\');', _MODAL_WIDTH, _MODAL_HEIGHT );
		JToolBarHelper::spacer();
		if ( $isAuth ) {
			JToolBarHelper::custom( 'save', 'save_jseblod', 'save_jseblod', JText::_( 'Save' ), false ); //JToolBarHelper::save();
			JToolBarHelper::custom( 'apply', 'apply_jseblod', 'apply_jseblod', JText::_( 'Apply' ), false ); //JToolBarHelper::apply();;
			JToolBarHelper::spacer();
		}
		if ( $isNew || $doCopy )  {
			$text = $doCopy ? JText::_( 'Copy' ) : JText::_( 'New' );
			JToolBarHelper::title(   JText::_( 'CONTENT TYPE' ).': <small><small>[ '.$text.' ]</small></small>', 'types.png' );
			JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Cancel' ), false ); //JToolBarHelper::cancel();;
		} else {
			$text = $isAuth ? JText::_( 'Edit' ) : JText::_( 'View' );
			JToolBarHelper::title(   JText::_( 'CONTENT TYPE' ).': <small><small>[ '.$text.' ]</small></small>', 'types.png' );
			JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Close' ), false ); //JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::divider();
		HelperjSeblod_Display::help( 'type' );
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
		$doAssign	=	JRequest::getVar( 'doAssign', false );
		$doAdmin	=	JRequest::getVar( 'doAdmin', false );
		$doSite		=	JRequest::getVar( 'doSite', false );
		$doContent	=	JRequest::getVar( 'doContent', false );
		
		if ( $doDelete ) {
		
			$typeRemoveItems	=& $this->get( 'RemoveData' );
			
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'typeRemoveItems', $typeRemoveItems );
			
			$this->_displayDeleteToolbar();
			
		} else if ( $doAssign ) {
			
			$lists['assignedCategories']	= JHTML::_( 'select.genericlist', array(), 'selected_categories[]', 'class="inputbox" size="18" onDblClick="addSelectedToListAndSelect(\'adminForm\',\'selected_categories\',\'available_categories\', \'\',\'\');delSelectedFromList(\'adminForm\',\'selected_categories\');" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			$lists['availableCategories']	= JHTML::_( 'select.genericlist', array(), 'available_categories', 'class="inputbox" size="18" onDblClick="addSelectedToListAndSelect(\'adminForm\',\'available_categories\',\'selected_categories\', \'\',\'\');delSelectedFromList(\'adminForm\',\'available_categories\');" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );			
			
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'into', $into );
			$this->assignRef( 'lists', $lists );

		} else if ( $doAdmin ) {
			
			$liveAssigned	=	JRequest::getVar( 'typeitems' );
			$liveValues		=	JRequest::getVar( 'typevalues' );
			
			$typeItems		=	$model->getItemsTypeInterface( $liveAssigned );
			//
			//
			$liveItems		=	explode( '||', $liveValues );
			if ( sizeof( $liveItems ) ) {
				$f				=	0;
				$typeItemValues	=	array();
				foreach ( $liveItems as $assigned ) {
					$assignedValue	=	explode( '::', $assigned );
					$typeItemValues[$assignedValue[0]] 							=	@$assignedValue[1];
					$typeItemValues[$assignedValue[0].'_submissiondisplay']		=	@$assignedValue[2];
					$typeItemValues[$assignedValue[0].'_editiondisplay']		=	@$assignedValue[3];
					$typeItemValues[$assignedValue[0].'_value']					=	@$assignedValue[4];
					//$typeItemValues[$assignedValue[0].'_helper']				=	@$assignedValue[5];
					$typeItemValues[$assignedValue[0].'_live']					=	@$assignedValue[5];
					$typeItemValues[$assignedValue[0].'_acl']					=	@$assignedValue[6];
					$f++;
				}
			}
			
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'typeItems', $typeItems );
			$this->assignRef( 'typeItemValues', $typeItemValues );
			$this->assignRef( 'typeItemIds', $liveAssigned );
			
		} else if ( $doSite ) {
		
			$liveAssigned	=	JRequest::getVar( 'typeitems' );
			$liveValues		=	JRequest::getVar( 'typevalues' );
			
			$typeItems		=	$model->getItemsTypeInterface( $liveAssigned );
			//
			//
			$liveItems		=	explode( '||', $liveValues );
			if ( sizeof( $liveItems ) ) {
				$f				=	0;
				$typeItemValues	=	array();
				foreach ( $liveItems as $assigned ) {
					$assignedValue	=	explode( '::', $assigned );
					$typeItemValues[$assignedValue[0]] 							=	@$assignedValue[1];
					$typeItemValues[$assignedValue[0].'_submissiondisplay']		=	@$assignedValue[2];
					$typeItemValues[$assignedValue[0].'_editiondisplay']		=	@$assignedValue[3];
					$typeItemValues[$assignedValue[0].'_value']					=	@$assignedValue[4];
					//$typeItemValues[$assignedValue[0].'_helper']				=	@$assignedValue[5];
					$typeItemValues[$assignedValue[0].'_live']					=	@$assignedValue[5];
					$typeItemValues[$assignedValue[0].'_acl']					=	@$assignedValue[6];
					$f++;
				}
			}
			
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'typeItems', $typeItems );
			$this->assignRef( 'typeItemValues', $typeItemValues );
			$this->assignRef( 'typeItemIds', $liveAssigned );
			
		} else if ( $doContent ) {
		
			$liveAssigned	=	JRequest::getVar( 'typeitems' );
			$liveValues		=	JRequest::getVar( 'typevalues' );
			
			$typeItems		=	$model->getItemsTypeInterface( $liveAssigned );
			//
			$parameter1		=	0;
			if ( strpos( $liveValues, '::free::' ) !== false ) {
				$parameter1	=	1;
			}
			//
			$liveItems		=	explode( '||', $liveValues );
			if ( sizeof( $liveItems ) ) {
				$typeItemValues	=	array();
				foreach ( $liveItems as $assigned ) {
					$assignedValue	=	explode( '::', $assigned );
					$typeItemValues[$assignedValue[0]] 				=	@$assignedValue[1];
					$typeItemValues[$assignedValue[0].'_bool']		=	@$assignedValue[2];
					$typeItemValues[$assignedValue[0].'_helper']	=	str_replace( array( '[[', ']]', '@@', '^^' ), array( '<', '>', '&', '#' ), @$assignedValue[3] );
					$typeItemValues[$assignedValue[0].'_link']		=	@$assignedValue[4];
				}
			}
			
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'typeItems', $typeItems );
			$this->assignRef( 'typeItemValues', $typeItemValues );
			$this->assignRef( 'typeItemIds', $liveAssigned );
			$this->assignRef( 'parameter1', $parameter1 );
		
		} else {
		
			$into					= JRequest::getVar( 'into' );
			
			// Get Data from Model
			$type					=& $this->get( 'Data' );
			
			
			$assignedCategories		=& $this->get( 'AssignedCategories' );
			$availableCategories		=& $this->get( 'AvailableCategories' );
			

			
			//
			
			//$lists['assignedCategories']	= JHTML::_( 'select.genericlist', $assignedCategories, 'selected_categories[]', 'class="inputbox" size="25" onDblClick="addSelectedToList(\'adminForm\',\'selected_categories\',\'available_categories\');delSelectedFromList(\'adminForm\',\'selected_categories\');" multiple="multiple" style="padding: 6px; width: 170px;"', 'value', 'text' );
			//
			
			$selected_categories	=&	$this->get( 'AssignedJoomlaCategories' );
			$available_categories	=&	$this->get( 'AvailableJoomlaCategories' );

			$assignedAdminFields	=	$model->getAssignedFields( 'admin' );
			$availableAdminFields	=	$model->getAvailableFields( 'admin' );
			$assignedSiteFields		=	$model->getAssignedFields( 'site' );
			$availableSiteFields	=	$model->getAvailableFields( 'site' );
			$assignedContentFields	=	$model->getAssignedFieldsRight( 'content' );
			$availableContentFields	=	$model->getAvailableFieldsRight( 'content' );
			$assignedEmailFields	=	$model->getAssignedFieldsRight( 'email' );
			$availableEmailFields	=	$model->getAvailableFieldsRight( 'email' );
			
			$admin_urls				=	$model->getAssignedAdminUrls();
			$type->admin_url		=	( @$admin_urls->url ) ? $admin_urls->url : '';
			// Set Flags
			$isNew		= ( @$type->id > 0 ) ? 0 : 1;
			$doCopy		= JRequest::getVar( 'doCopy', false );
			$isAuth 	= ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
		
			
			// Checking!
			if ( JTable::isCheckedOut( $user->get( 'id' ), @$type->checked_out ) ) {
				$msg = JText::sprintf( 'DESCBEINGEDITTED', '', $type->title );
				$mainframe->redirect( _LINK_CCKJSEBLOD_TYPES, $msg, 'notice' );
			}
		
			// Set Wysiwyg Modal
			$modals['description'] = HelperjSeblod_Display::quickModalWysiwyg( 'Description', $controller, 'description', 'pagebreak', 0, @$type->id, false );
			
			$tooltips['link_description'] = HelperjSeblod_Display::quickTooltipAjaxLink( 'Description', $controller, 'description', @$type->id );		
			
			// Set Assign to Joomla Categories Modal
			$modals['selectJoomlaCategories']	= HelperjSeblod_Display::quickModalTask( 'ASSIGN', '', 'joomla_categories', 'pagebreak', $controller, 'assign' );
			
			// Set Assign to Template Modal
			$modals['selectAdminTemplate']	= HelperjSeblod_Display::quickModalTask( 'ASSIGN', '', 'admintemplate', 'pagebreak', 'templates', 'select', _MODAL_WIDTH, _MODAL_HEIGHT, 'tpl_type=1' );

			// Set Assign to Template Modal
			$modals['selectSiteTemplate']	= HelperjSeblod_Display::quickModalTask( 'ASSIGN', '', 'sitetemplate', 'pagebreak', 'templates', 'select', _MODAL_WIDTH, _MODAL_HEIGHT, 'tpl_type=1' );

			// Set Assign to Template Modal
			$modals['selectContentTemplate']	=	HelperjSeblod_Display::quickModalTask( 'ASSIGN', '', 'contenttemplate', 'pagebreak', 'templates', 'select', _MODAL_WIDTH, _MODAL_HEIGHT, 'tpl_type=0' );
			
			// Set Published List ( Boolean List )
			$selectedPublished	= ( $isNew ) ? 1 : $type->published;
			$lists['published'] = JHTML::_( 'select.booleanlist', 'published', 'class="inputbox"', $selectedPublished );
			
			// Set Joomla Category Assignments
			if ( $doCopy ) {
				$assignedCategories = array();
			}
			$lists['hiddenAssignedCategories']	= JHTML::_( 'select.genericlist', $assignedCategories, 'selected_categories[]', 'class="inputbox" size="25" onDblClick="addSelectedToListAndSelect(\'adminForm\',\'selected_categories\',\'available_categories\', \'\',\'\');delSelectedFromList(\'adminForm\',\'selected_categories\');" multiple="multiple" style="width: 1px; height: 1px; border: 1px solid #F6F6F6; display: none"', 'value', 'text' );
			$lists['hiddenAvailableCategories']	= JHTML::_( 'select.genericlist', $availableCategories, 'available_categories', 'class="inputbox" size="25" onDblClick="addSelectedToListAndSelect(\'adminForm\',\'available_categories\',\'selected_categories\', \'\',\'\');delSelectedFromList(\'adminForm\',\'available_categories\');" multiple="multiple" style="width: 1px; height: 1px; border: 1px solid #F6F6F6; display: none"', 'value', 'text' );
			
			// Set Category List ( Select List )
			$optionCategories	= array();
			$optionCategories[]	= JHTML::_( 'select.option',  '', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
			$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
			$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
			$optionCategories	= ( ! $isNew && ! $type->categorystate ) ? array_merge( $optionCategories, HelperjSeblod_Helper::getTypeCategories( true, false ) ) : array_merge( $optionCategories, HelperjSeblod_Helper::getTypeCategories( true, true ) );
			$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$selectFilterInCategory	=	( JRequest::getInt( 'filter_search' ) == 5 ) ? JRequest::getInt( 'search' ) : '';
			$selectFilterCategory	=	( $selectFilterInCategory || JRequest::getInt( 'filter_category' ) ) ? ( ( $selectFilterInCategory ) ? $selectFilterInCategory : JRequest::getInt( 'filter_category' ) ) : ( _TYPE_DEFAULT_CAT ? _TYPE_DEFAULT_CAT : '' );
			$selectedCategory   = ( ! $isNew ) ? $type->category : $selectFilterCategory;
			$lists['category']	= JHTML::_( 'select.genericlist', $optionCategories, 'category', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectedCategory );
			
			// Set Field Assignments List ( Double Select List )
			$OptionsAvailableAdminFields = array();
			$OptionsAvailableAdminFields	= array_merge( $OptionsAvailableAdminFields, $availableAdminFields );
			$lists['assignedAdminFields']	= JHTML::_( 'select.genericlist', $assignedAdminFields, 'selected_adminfields[]', 'class="inputbox" size="18" onDblClick="dblclick_do(\'adminForm\',\'selected_adminfields\', adminForm.selected_adminfields.selectedIndex, \'admin\');" multiple="multiple" style="padding: 6px; margin-top:4px; width: 190px; height: 254px;"', 'value', 'text' );
			$lists['availableAdminFields']	= JHTML::_( 'select.genericlist', $OptionsAvailableAdminFields, 'available_adminfields', 'class="inputbox" size="21" onDblClick="trytoassigntoadmin();" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );			
			$lists['hiddenAdminFields']	= JHTML::_( 'select.genericlist', array(), 'hidden_adminfields', 'class="inputbox" size="1" multiple="multiple" style="width: 1px; height: 1px; border: 1px solid #F6F6F6; display: none;"', 'value', 'text' );
			
					
			// Set Field Assignments List ( Double Select List )
			$OptionsAvailableSiteFields = array();
			$OptionsAvailableSiteFields	= array_merge( $OptionsAvailableSiteFields, $availableSiteFields );
			$lists['assignedSiteFields']	= JHTML::_( 'select.genericlist', $assignedSiteFields, 'selected_sitefields[]', 'class="inputbox" size="18" onDblClick="dblclick_do(\'adminForm\',\'selected_sitefields\', adminForm.selected_sitefields.selectedIndex, \'site\');" multiple="multiple" style="padding: 6px; margin-top:4px; width: 190px; height: 254px;"', 'value', 'text' );
			$lists['availableSiteFields']	= JHTML::_( 'select.genericlist', $OptionsAvailableSiteFields, 'available_sitefields', 'class="inputbox" size="21" onDblClick="trytoassigntosite();" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			$lists['hiddenSiteFields']	= JHTML::_( 'select.genericlist', array(), 'hidden_sitefields', 'class="inputbox" size="1" multiple="multiple" style="width: 1px; height: 1px; border: 1px solid #F6F6F6; display: none;"', 'value', 'text' );
			
			// Set Field Assignments List ( Double Select List )
			$OptionsAvailableEmailFields = array();
			$OptionsAvailableEmailFields	= array_merge( $OptionsAvailableEmailFields, $availableEmailFields );
			$lists['assignedEmailFields']	= JHTML::_( 'select.genericlist', $assignedEmailFields, 'selected_emailfields[]', 'class="inputbox" size="21" onDblClick="dblclick_do(\'adminForm\',\'selected_emailfields\', adminForm.selected_emailfields.selectedIndex, \'email\');" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			$lists['availableEmailFields']	= JHTML::_( 'select.genericlist', $OptionsAvailableEmailFields, 'available_emailfields', 'class="inputbox" size="21" onDblClick="trytoassigntoemail();" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			$lists['hiddenEmailFields']	= JHTML::_( 'select.genericlist', array(), 'hidden_emailfields', 'class="inputbox" size="1" multiple="multiple" style="width: 1px; height: 1px; border: 1px solid #F6F6F6; display: none;"', 'value', 'text' );
			
			$OptionsAvailableContentFields = array();
			$OptionsAvailableContentFields	= array_merge( $OptionsAvailableContentFields, $availableContentFields );
			$lists['assignedContentFields']	= JHTML::_( 'select.genericlist', $assignedContentFields, 'selected_contentfields[]', 'class="inputbox" size="21" onDblClick="dblclick_do(\'adminForm\',\'selected_contentfields\', adminForm.selected_contentfields.selectedIndex, \'content\');" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			$lists['availableContentFields'] = JHTML::_( 'select.genericlist', $OptionsAvailableContentFields, 'available_contentfields', 'class="inputbox" size="21" onDblClick="trytoassigntocontent();" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			$lists['hiddenContentFields']	= JHTML::_( 'select.genericlist', array(), 'hidden_contentfields', 'class="inputbox" size="1" multiple="multiple" style="width: 1px; height: 1px; border: 1px solid #F6F6F6; display: none;"', 'value', 'text' );
						
			$optFormAction		= array();
			$defaultAction	=	$model->getDefaultAction();
			//$optFormAction[]	= JHTML::_( 'select.option',  1, $defaultAction, 'value', 'text' );
			$optFormAction	= array_merge( $optFormAction, $defaultAction );
			$optFormAction	= array_merge( $optFormAction, $model->getFormActionItems() );
			$formActionSelect 	=	$model->getFormAction( 'admin' );
			$required = ( sizeof( $assignedAdminFields ) ) ? 'required required-enabled' : '';
			$lists['adminActionItems']	= JHTML::_( 'select.genericlist', $optFormAction, 'adminaction_item', 'class="inputbox '.$required.'" size="1"  style="padding: 6px; width: 190px;"', 'value', 'text', $formActionSelect );
			$optFormAction		= array();
			$optFormAction	= array_merge( $optFormAction, $defaultAction );
			$optFormAction	= array_merge( $optFormAction, $model->getFormActionItems() );
			$formActionSelect 	=	$model->getFormAction( 'site' );
			$required = ( sizeof( $assignedSiteFields ) ) ? 'required required-enabled' : '';
			$lists['siteActionItems']	= JHTML::_( 'select.genericlist', $optFormAction, 'siteaction_item', 'class="inputbox '.$required.'" size="1"  style="padding: 6px; width: 190px;"', 'value', 'text', $formActionSelect );
			
			// Set Item Types List ( Select List )
			$optionItemTypes			= array();
			$optionItemTypes[]			= JHTML::_( 'select.option',  '', JText::_( 'ALL ITEM TYPES' ), 'value', 'text' );
			$optionItemTypes			= array_merge( $optionItemTypes, HelperjSeblod_Helper::getItemTypes( true ) );
			$lists['adminFieldTypes']	= JHTML::_( 'select.genericlist', $optionItemTypes, 'adminfield_types', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			$lists['siteFieldTypes']	= JHTML::_( 'select.genericlist', $optionItemTypes, 'sitefield_types', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			$lists['emailFieldTypes']	= JHTML::_( 'select.genericlist', $optionItemTypes, 'emailfield_types', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			$lists['contentFieldTypes']	= JHTML::_( 'select.genericlist', $optionItemTypes, 'contentfield_types', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );

			// Set Item Categories List ( Select List )
			$optionItemCategories			= array();
			$optionItemCategories[]			= JHTML::_( 'select.option',  '', JText::_( 'ALL ITEM CATEGORIES' ), 'value', 'text' );
			$optionItemCategories[]			= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
			$optionItemCategories			= array_merge( $optionItemCategories, HelperjSeblod_Helper::getItemCategories2( true, false ) );
			$lists['adminFieldCategories']	= JHTML::_( 'select.genericlist', $optionItemCategories, 'adminfield_categories', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			$lists['siteFieldCategories']	= JHTML::_( 'select.genericlist', $optionItemCategories, 'sitefield_categories', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			$lists['emailFieldCategories']	= JHTML::_( 'select.genericlist', $optionItemCategories, 'emailfield_categories', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			$lists['contentFieldCategories']	= JHTML::_( 'select.genericlist', $optionItemCategories, 'contentfield_categories', 'class="inputbox" size="1" style="width: 160px;"', 'value', 'text', '' );
			
			//echo $selected_categories.'<br />';
			//echo $available_categories.'<br />';
			
			$optViewComponent		=	array();
			$optViewComponent[]		=	JHTML::_( 'select.option',  'url_add', JText::_( 'ADD' ), 'value', 'text' );
			$optViewComponent[]		=	JHTML::_( 'select.option',  'url', JText::_( 'ALWAYS' ), 'value', 'text' );
			$optViewComponent[]		=	JHTML::_( 'select.option',  'url_edit', JText::_( 'EDIT' ), 'value', 'text' );
			$selectViewComponent	=	( @$admin_urls->url ) ? $admin_urls->type : 'url';
			$lists['viewComponent']	= JHTML::_( 'select.genericlist', $optViewComponent, 'admin_url_type', 'class="inputbox" size="1"', 'value', 'text', $selectViewComponent );
			
			if ( $isNew ) {
				$defaultSubmission	=	$model->_getTemplate( 1 );
				$this->assignRef( 'defaultSubmission', $defaultSubmission );
				$defaultContent		=	$model->_getTemplate( 3 );
				$this->assignRef( 'defaultContent', $defaultContent );
			}
	
			// 1
			$adminform	=	'';
			if ( sizeof( $assignedAdminFields ) ) {
				foreach ( $assignedAdminFields as $assignedAdminField ) {
						$adminform	.=	@$assignedAdminField->name.'::'.@$assignedAdminField->typography.'::'.@$assignedAdminField->submissiondisplay.'::'.@$assignedAdminField->editiondisplay.'::'.@$assignedAdminField->prevalue.'::'.@$assignedAdminField->live.'::'.@$assignedAdminField->acl.'||';
				}
			}
			$adminform	=	substr( $adminform, 0, -2 );
			// 2
			$siteform	=	'';
			if ( sizeof( $assignedSiteFields ) ) {
				foreach ( $assignedSiteFields as $assignedSiteField ) {
						$siteform	.=	@$assignedSiteField->name.'::'.@$assignedSiteField->typography.'::'.@$assignedSiteField->submissiondisplay.'::'.@$assignedSiteField->editiondisplay.'::'.@$assignedSiteField->prevalue.'::'.@$assignedSiteField->live.'::'.@$assignedSiteField->acl.'||';
				}
			}
			$siteform	=	substr( $siteform, 0, -2 );
			// 3
			$contentdisplay	=	'';
			if ( sizeof( $assignedContentFields ) ) {
				foreach ( $assignedContentFields as $assignedContentField ) {
					$contentdisplay	.=	@$assignedContentField->name.'::'.@$assignedContentField->contentdisplay.'::'.@$assignedContentField->bool.'::'.@$assignedContentField->helper.'::'.@$assignedContentField->link.'||';
				}
			}
			$contentdisplay	=	substr( $contentdisplay, 0, -2 );
			
			// Push Data to Template
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'doCopy', $doCopy );
			$this->assignRef( 'into', $into );
			$this->assignRef( 'type', $type );
			$this->assignRef( 'selected_categories', $selected_categories );
			$this->assignRef( 'available_categories', $available_categories );
			$this->assignRef( 'lists', $lists );
			$this->assignRef( 'modals', $modals );
			$this->assignRef( 'tooltips', $tooltips );
			$this->assignRef( 'adminform', $adminform );
			$this->assignRef( 'siteform', $siteform );
			$this->assignRef( 'contentdisplay', $contentdisplay );
			
			$this->_displayToolbar( $isNew, $doCopy, $isAuth );
			
		}
		
		parent::display( $tpl );
	}
	
}
?>