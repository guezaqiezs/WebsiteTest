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
 * Configuration	View Class
 **/
class CCKjSeblodViewConfiguration extends JView
{
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isAuth ) 
	{
		JToolBarHelper::title(   JText::_( 'CONFIG' ), 'configuration.png' );
		if ( $isAuth ) {
			JToolBarHelper::custom( 'save', 'save_jseblod', 'save_jseblod', JText::_( 'Save' ), false ); //JToolBarHelper::save();
			JToolBarHelper::custom( 'apply', 'apply_jseblod', 'apply_jseblod', JText::_( 'Apply' ), false ); //JToolBarHelper::apply();
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Close' ), false ); //JToolBarHelper::cancel( 'cancel', 'Close' );
		JToolBarHelper::divider();
		if ( $isAuth ) {
			HelperjSeblod_Display::quickToolbarOperations();
		}
		HelperjSeblod_Display::help('configuration');
	}

	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$controller = JRequest::getWord( 'controller' );
		$model 		=& $this->getModel();
		$user 		=& JFactory::getUser();
				
		// Get Data from Model
		$configuration	= $model->getData();
		
		// Checking!		// No checkin cause buggy 'hidemainmenu' ? ( also hide submenu .. )
		//if ( JTable::isCheckedOut( $user->get( 'id' ), $configuration->checked_out ) ) {
			//$msg = JText::sprintf( 'DESCBEINGEDITTED', '', 'Configuration' );
			//$mainframe->redirect( _LINK_CCKJSEBLOD, $msg, 'notice' );
		//}
		
		JHTML::_('behavior.switcher');
		
		// Build the component's submenu
		$contents = '';
		$tmplpath = dirname(__FILE__).DS.'tmpl';
		ob_start();
		require_once($tmplpath.DS.'navigation.php');
		$contents = ob_get_contents();
		ob_end_clean();
		
		// Set document data
		$document =& JFactory::getDocument();
		$document->setBuffer($contents, 'modules', 'submenu');
		
		// Get Data from Model
		$templateNames	= $model->getTemplates();
		
		$opening 		= $model->getLimits( 'opening' );
		$closing 		= $model->getLimits( 'closing' );
		
		// Set Flags
		$isAuth = ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
				
		// Create Template Types Filter
		$lists['opening'] = JHTML::_('select.genericlist', $opening, 'opening', 'class="inputbox required required-enabled" size="1"', 'text', 'text', $configuration->opening );
		$lists['closing'] = JHTML::_('select.genericlist', $closing, 'closing', 'class="inputbox required required-enabled" size="1"', 'text', 'text', $configuration->closing );

		// Set Source Folders List ( Select List )
		$templateHidden = explode( ',', $configuration->template_hidden );
		$folders = JFolder::folders( JPATH_SITE.DS.'templates' );
		$optionsHidden		= array();
		$selectedHidden		= $templateHidden;
		if ( $folders ) {
			foreach( $folders as $value ) {
				if ( ( array_search( $value, $templateNames ) === false ) ) {
					$optionsHidden[] = JHTML::_( 'select.option', $value, $value );
				}
			}
		}
		$lists['template_hidden']	= JHTML::_('select.genericlist', $optionsHidden, 'selected_hidden[]', 'class="inputbox" size="12" multiple="multiple" style="width: 147px;"', 'value', 'text', $selectedHidden );
		
		// Admin Form Tooltips Mode
		$optAdminFormTips		= array();
		$optAdminFormTips[] 	= JHTML::_( 'select.option', 1, JText::_( 'ON CLICK' ) );
		$optAdminFormTips[] 	= JHTML::_( 'select.option', 0, JText::_( 'ON HOVER' ) );
		$selectedAdminFormTips	= $configuration->adminform_tips;
		$lists['adminFormTips'] = JHTML::_( 'select.radiolist', $optAdminFormTips, 'adminform_tips', 'class="inputbox"', 'value', 'text', $selectedAdminFormTips );

		// Admin Form Tooltips Mode
		$optNoTemplateDisplay		=	array();
		$optNoTemplateDisplay[] 	=	JHTML::_( 'select.option', 0, JText::_( 'VALUE AND LABEL AND TOOLTIP' ) );
		$optNoTemplateDisplay[] 	=	JHTML::_( 'select.option', 1, JText::_( 'VALUE AND TOOLTIP' ) );
		$optNoTemplateDisplay[] 	=	JHTML::_( 'select.option', 2, JText::_( 'VALUE AND LABEL' ) );
		$optNoTemplateDisplay[] 	=	JHTML::_( 'select.option', 3, JText::_( 'VALUE' ) );

		//$selectNoTemplateDisplay	=	$configuration->notemplate_display;
		//$lists['noTemplateDisplay'] 	=	JHTML::_( 'select.genericlist', $optNoTemplateDisplay, 'notemplate_display', 'class="inputbox required required-enabled" style="width: 147px;"', 'value', 'text', $selectNoTemplateDisplay );

		// Site Form Tooltips Mode
		$selectedSiteFormTips	= $configuration->siteform_tips;
		$lists['siteFormTips'] = JHTML::_( 'select.radiolist', $optAdminFormTips, 'siteform_tips', 'class="inputbox"', 'value', 'text', $selectedSiteFormTips );

		// Select Label..
		$selectJText	= $configuration->jtext_on_label;
		$lists['jTextOnLabel'] = JHTML::_( 'select.booleanlist', 'jtext_on_label', 'class="inputbox"', $selectJText );
	
		// Empty Pack
		$selectEmptyPack			=	$configuration->export_empty_pack;
		$lists['exportEmptyPack']	=	JHTML::_( 'select.booleanlist', 'export_empty_pack', 'class="inputbox"', $selectEmptyPack );
	
		// Set Delete Mode
		$optImportDefaultMode			= array();
		$optImportDefaultMode[] 		= JHTML::_( 'select.option', 0, JText::_( 'IGNORE EXISTING' ) );
		$optImportDefaultMode[] 		= JHTML::_( 'select.option', 1, JText::_( 'UPDATE EXISTING' ) );
		$selectImportDefaultMode		= $configuration->import_default_mode;
		$lists['importDefaultMode'] 	= JHTML::_( 'select.radiolist', $optImportDefaultMode, 'import_default_mode', 'size="1" class="inputbox"', 'value', 'text', $selectImportDefaultMode );
	
		// Set Delete Mode
		$optTemplateDefaultCategory			= array();
		$optTemplateDefaultCategory[] 		= JHTML::_( 'select.option', 0, JText::_( 'None' ) );
		$optTemplateDefaultCategory[] 		= JHTML::_( 'select.option', 1, JText::_( 'QUICK CATEGORY' ) );
		$selectTemplateDefaultCategory		= $configuration->template_default_category;
		$lists['templateDefaultCategory'] 	= JHTML::_( 'select.radiolist', $optTemplateDefaultCategory, 'template_default_category', 'size="1" class="inputbox"', 'value', 'text', $selectTemplateDefaultCategory );
	
		// Set Delete Mode
		$optTypeDefaultCategory			= array();
		$optTypeDefaultCategory[] 		= JHTML::_( 'select.option', 0, JText::_( 'None' ) );
		$optTypeDefaultCategory[] 		= JHTML::_( 'select.option', 1, JText::_( 'QUICK CATEGORY' ) );
		$selectTypeDefaultCategory		= $configuration->type_default_category;
		$lists['typeDefaultCategory'] 	= JHTML::_( 'select.radiolist', $optTypeDefaultCategory, 'type_default_category', 'size="1" class="inputbox"', 'value', 'text', $selectTypeDefaultCategory );
		
		// Set Delete Mode
		$optItemDefaultCategory			= array();
		$optItemDefaultCategory[] 		= JHTML::_( 'select.option', 0, JText::_( 'None' ) );
		$optItemDefaultCategory[] 		= JHTML::_( 'select.option', 1, JText::_( 'QUICK CATEGORY' ) );
		$selectItemDefaultCategory		= $configuration->item_default_category;
		$lists['itemDefaultCategory'] 	= JHTML::_( 'select.radiolist', $optItemDefaultCategory, 'item_default_category', 'size="1" class="inputbox"', 'value', 'text', $selectItemDefaultCategory );
	
		// Set Delete Mode
		$optSearchDefaultCategory		= array();
		$optSearchDefaultCategory[] 	= JHTML::_( 'select.option', 0, JText::_( 'None' ) );
		$optSearchDefaultCategory[] 	= JHTML::_( 'select.option', 1, JText::_( 'QUICK CATEGORY' ) );
		$selectSearchDefaultCategory	= $configuration->search_default_category;
		$lists['searchDefaultCategory'] = JHTML::_( 'select.radiolist', $optSearchDefaultCategory, 'search_default_category', 'size="1" class="inputbox"', 'value', 'text', $selectSearchDefaultCategory );
		
		// Set Delete Mode
		$optionsTypeCategoryDeleteMode		= array();
		$optionsTypeCategoryDeleteMode[] 	= JHTML::_( 'select.option', 1, JText::_( 'DELETE TYPE CATEGORY WITHOUT' ) );
		$optionsTypeCategoryDeleteMode[] 	= JHTML::_( 'select.option', 0, JText::_( 'ALWAYS ASK CONFIRMATION' ) );
		$optionsTypeCategoryDeleteMode[] 	= JHTML::_( 'select.option', -1, JText::_( 'DELETE TYPE CATEGORY WITH' ) );
		$selectedTypeCategoryDeleteMode		= $configuration->type_category_delete_mode;
		$lists['typeCategoryDeleteMode'] 	= JHTML::_( 'select.genericlist', $optionsTypeCategoryDeleteMode, 'type_category_delete_mode', 'size="1" class="inputbox required required-enabled"', 'value', 'text', $selectedTypeCategoryDeleteMode );
	
		// Set Delete Mode
		$optionsTemplateDeleteMode		= array();
		$optionsTemplateDeleteMode[] 	= JHTML::_( 'select.option', 1, JText::_( 'DELETE TEMPLATE WITHOUT' ) );
		$optionsTemplateDeleteMode[] 	= JHTML::_( 'select.option', 0, JText::_( 'ALWAYS ASK CONFIRMATION' ) );
		$optionsTemplateDeleteMode[] 	= JHTML::_( 'select.option', -1, JText::_( 'DELETE TEMPLATE WITH' ) );
		$selectedTemplateDeleteMode		= $configuration->template_delete_mode;
		$lists['templateDeleteMode'] 	= JHTML::_( 'select.genericlist', $optionsTemplateDeleteMode, 'template_delete_mode', 'size="1" class="inputbox"  style="width: 270px;"', 'value', 'text', $selectedTemplateDeleteMode );

		// Set Delete Mode
		$optionsTemplateCategoryDeleteMode		= array();
		$optionsTemplateCategoryDeleteMode[] 	= JHTML::_( 'select.option', 1, JText::_( 'DELETE TEMPLATE CATEGORY WITHOUT' ) );
		$optionsTemplateCategoryDeleteMode[] 	= JHTML::_( 'select.option', 0, JText::_( 'ALWAYS ASK CONFIRMATION' ) );
		$optionsTemplateCategoryDeleteMode[] 	= JHTML::_( 'select.option', -1, JText::_( 'DELETE TEMPLATE CATEGORY WITH' ) );
		$selectedTemplateCategoryDeleteMode		= $configuration->template_category_delete_mode;
		$lists['templateCategoryDeleteMode'] 	= JHTML::_( 'select.genericlist', $optionsTemplateCategoryDeleteMode, 'template_category_delete_mode', 'size="1" class="inputbox"  style="width: 270px;"', 'value', 'text', $selectedTemplateCategoryDeleteMode );
	
		// Set Delete Mode
		$optionsTypeDeleteMode		= array();
		$optionsTypeDeleteMode[] 	= JHTML::_( 'select.option', 1, JText::_( 'DELETE TYPE WITHOUT' ) );
		$optionsTypeDeleteMode[] 	= JHTML::_( 'select.option', 0, JText::_( 'ALWAYS ASK CONFIRMATION' ) );
		$optionsTypeDeleteMode[] 	= JHTML::_( 'select.option', -1, JText::_( 'DELETE TYPE WITH' ) );
		$selectedTypeDeleteMode		= $configuration->type_delete_mode;
		$lists['typeDeleteMode'] 	= JHTML::_( 'select.genericlist', $optionsTypeDeleteMode, 'type_delete_mode', 'size="1" class="inputbox"  style="width: 270px;"', 'value', 'text', $selectedTypeDeleteMode );

		// Set Delete Mode
		$optionsTypeCategoryDeleteMode		= array();
		$optionsTypeCategoryDeleteMode[] 	= JHTML::_( 'select.option', 1, JText::_( 'DELETE TYPE CATEGORY WITHOUT' ) );
		$optionsTypeCategoryDeleteMode[] 	= JHTML::_( 'select.option', 0, JText::_( 'ALWAYS ASK CONFIRMATION' ) );
		$optionsTypeCategoryDeleteMode[] 	= JHTML::_( 'select.option', -1, JText::_( 'DELETE TYPE CATEGORY WITH' ) );
		$selectedTypeCategoryDeleteMode		= $configuration->type_category_delete_mode;
		$lists['typeCategoryDeleteMode'] 	= JHTML::_( 'select.genericlist', $optionsTypeCategoryDeleteMode, 'type_category_delete_mode', 'size="1" class="inputbox"  style="width: 270px;"', 'value', 'text', $selectedTypeCategoryDeleteMode );

		// Set Delete Mode
		$optionsItemDeleteMode		= array();
		$optionsItemDeleteMode[] 	= JHTML::_( 'select.option', 1, JText::_( 'DELETE ITEM WITHOUT' ) );
		$optionsItemDeleteMode[] 	= JHTML::_( 'select.option', 0, JText::_( 'ALWAYS ASK CONFIRMATION' ) );
		$selectedItemDeleteMode		= $configuration->item_delete_mode;
		$lists['itemDeleteMode'] 	= JHTML::_( 'select.genericlist', $optionsItemDeleteMode, 'item_delete_mode', 'size="1" class="inputbox" style="width: 270px;"', 'value', 'text', $selectedItemDeleteMode );

		// Set Delete Mode
		$optionsItemCategoryDeleteMode		= array();
		$optionsItemCategoryDeleteMode[] 	= JHTML::_( 'select.option', 1, JText::_( 'DELETE ITEM CATEGORY WITHOUT' ) );
		$optionsItemCategoryDeleteMode[] 	= JHTML::_( 'select.option', 0, JText::_( 'ALWAYS ASK CONFIRMATION' ) );
		$optionsItemCategoryDeleteMode[] 	= JHTML::_( 'select.option', -1, JText::_( 'DELETE ITEM CATEGORY WITH' ) );
		$selectedItemCategoryDeleteMode		= $configuration->item_category_delete_mode;
		$lists['itemCategoryDeleteMode'] 	= JHTML::_( 'select.genericlist', $optionsItemCategoryDeleteMode, 'item_category_delete_mode', 'size="1" class="inputbox" style="width: 270px;"', 'value', 'text', $selectedItemCategoryDeleteMode );
		
		// Set Delete Mode
		$optionsSearchDeleteMode	= array();
		$optionsSearchDeleteMode[] 	= JHTML::_( 'select.option', 1, JText::_( 'DELETE TYPE WITHOUT' ) );
		$optionsSearchDeleteMode[] 	= JHTML::_( 'select.option', 0, JText::_( 'ALWAYS ASK CONFIRMATION' ) );
		$optionsSearchDeleteMode[] 	= JHTML::_( 'select.option', -1, JText::_( 'DELETE TYPE WITH' ) );
		$selectedSearchDeleteMode	= $configuration->search_delete_mode;
		$lists['searchDeleteMode'] 	= JHTML::_( 'select.genericlist', $optionsSearchDeleteMode, 'search_delete_mode', 'size="1" class="inputbox"  style="width: 270px;"', 'value', 'text', $selectedSearchDeleteMode );

		// Set Delete Mode
		$optionsSearchCategoryDeleteMode	= array();
		$optionsSearchCategoryDeleteMode[] 	= JHTML::_( 'select.option', 1, JText::_( 'DELETE TYPE CATEGORY WITHOUT' ) );
		$optionsSearchCategoryDeleteMode[] 	= JHTML::_( 'select.option', 0, JText::_( 'ALWAYS ASK CONFIRMATION' ) );
		$optionsSearchCategoryDeleteMode[] 	= JHTML::_( 'select.option', -1, JText::_( 'DELETE TYPE CATEGORY WITH' ) );
		$selectedSearchCategoryDeleteMode	= $configuration->search_category_delete_mode;
		$lists['searchCategoryDeleteMode'] 	= JHTML::_( 'select.genericlist', $optionsSearchCategoryDeleteMode, 'search_category_delete_mode', 'size="1" class="inputbox"  style="width: 270px;"', 'value', 'text', $selectedSearchCategoryDeleteMode );
		
		// Set Creation Mode
		$optionsElemAddEditMode		=	array();
		$optionsElemAddEditMode[] 	=	JHTML::_( 'select.option', 1, '<strong><font color="#6CC634">'.JText::_( 'BOX' ).'</font></strong>' );
		$optionsElemAddEditMode[] 	=	JHTML::_( 'select.option', 0, '<strong><font color="#666666">'.JText::_( 'DEFAULT' ).'</font></strong>' );
		$optionsElemAddEditMode[] 	=	JHTML::_( 'select.option', 2, '<strong><font color="#6CC634">'.JText::_( 'FULLSCREEN' ).'</font></strong>' );
		$lists['articleCreationMode'] 	=	JHTML::_( 'select.radiolist', $optionsElemAddEditMode, 'article_creation_mode', 'size="1" class="inputbox"', 'value', 'text', $configuration->article_creation_mode );
		$lists['articleEditionMode'] 	=	JHTML::_( 'select.radiolist', $optionsElemAddEditMode, 'article_edition_mode', 'size="1" class="inputbox"', 'value', 'text', $configuration->article_edition_mode );
		$lists['articleEdition2Mode'] 	=	JHTML::_( 'select.radiolist', $optionsElemAddEditMode, 'article_edition2_mode', 'size="1" class="inputbox"', 'value', 'text', $configuration->article_edition2_mode );
		$lists['categoryCreationMode'] 	=	JHTML::_( 'select.radiolist', $optionsElemAddEditMode, 'category_creation_mode', 'size="1" class="inputbox"', 'value', 'text', $configuration->category_creation_mode );
		$lists['categoryEditionMode'] 	=	JHTML::_( 'select.radiolist', $optionsElemAddEditMode, 'category_edition_mode', 'size="1" class="inputbox"', 'value', 'text', $configuration->category_edition_mode );
		$lists['categoryEdition2Mode'] 	=	JHTML::_( 'select.radiolist', $optionsElemAddEditMode, 'category_edition2_mode', 'size="1" class="inputbox"', 'value', 'text', $configuration->category_edition2_mode );
		$lists['userCreationMode']	 	=	JHTML::_( 'select.radiolist', $optionsElemAddEditMode, 'user_creation_mode', 'size="1" class="inputbox"', 'value', 'text', $configuration->user_creation_mode );
		$lists['userEditionMode'] 		=	JHTML::_( 'select.radiolist', $optionsElemAddEditMode, 'user_edition_mode', 'size="1" class="inputbox"', 'value', 'text', $configuration->user_edition_mode );
		$lists['userEdition2Mode'] 		=	JHTML::_( 'select.radiolist', $optionsElemAddEditMode, 'user_edition2_mode', 'size="1" class="inputbox"', 'value', 'text', $configuration->user_edition2_mode );
		
		// Set Delete Mode
		$optGroup	=	array();
		$optGroup[] 	= JHTML::_( 'select.option', 23, _NBSP.'-&nbsp;'.JText::_( 'Manager' ) );
		$optGroup[] 	= JHTML::_( 'select.option', 24, _NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Administrator' ) );
		$optGroup[] 	= JHTML::_( 'select.option', 25, _NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Super Administrator' ) );
		$selectViewGroup		= $configuration->view_access_level;		
		$lists['viewGroup'] 	= JHTML::_( 'select.genericlist', $optGroup, 'view_access_level', 'size="1" class="inputbox"', 'value', 'text', $selectViewGroup );
		$selectEditGroup		= $configuration->edit_access_level;
		$lists['editGroup'] 	= JHTML::_( 'select.genericlist', $optGroup, 'edit_access_level', 'size="1" class="inputbox"', 'value', 'text', $selectEditGroup );
				
		$optCatFullscreen		=	HelperjSeblod_Helper::getJoomlaCategories();
		$selectCatFullscreen	=	( @$configuration->categories_fullscreen ) ? explode( ',', $configuration->categories_fullscreen ) : '';
		$lists['catFullscreen']	=	JHTML::_( 'select.genericlist', $optCatFullscreen, 'selected_fullscreen[]', 'class="inputbox" size="12" multiple="multiple" style="width: 147px;"', 'value', 'text', $selectCatFullscreen );
		
		// Set TinyMCE Presets Skin
		$optionsTinySkin		=	array();
		$optionsTinySkin[] 		=	JHTML::_( 'select.option', 'default', JText::_( 'DEFAULT' ) );
		$optionsTinySkin[] 		=	JHTML::_( 'select.option', 'o2k7_black', JText::_( 'OFFICE2007 BLACK' ) );
		$optionsTinySkin[] 		=	JHTML::_( 'select.option', 'o2k7', JText::_( 'OFFICE2007 BLUE' ) );
		$optionsTinySkin[] 		=	JHTML::_( 'select.option', 'o2k7_silver', JText::_( 'OFFICE2007 SILVER' ) );
		$selectTinySkin			=	$configuration->wysiwyg_editor;
		$lists['tinySkin']		=	JHTML::_( 'select.genericlist', $optionsTinySkin, 'wysiwyg_editor', 'size="1" class="inputbox"', 'value', 'text', $selectTinySkin);
		
		$lists['validAlert']	=	JHTML::_('select.booleanlist', 'validation_alert', 'class="inputbox"', $configuration->validation_alert );
		
		$lists['loginEnable']	=	JHTML::_('select.booleanlist', 'login_enable', 'class="inputbox"', $configuration->login_enable );
		
		$optionContentType		=	array();
		$optionContentType[] 	=	JHTML::_( 'select.option', 0, JText::_( 'SELECT A TYPE' ) );
		$optionContentType		=	array_merge( $optionContentType, HelperjSeblod_Helper::getContentTypes() );
		$selectContentTypeA		=	$configuration->article_typeid;
		$lists['contentTypeA']	=	JHTML::_( 'select.genericlist', $optionContentType, 'article_typeid', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectContentTypeA );
		$selectContentTypeC		=	$configuration->category_typeid;
		$lists['contentTypeC']	=	JHTML::_( 'select.genericlist', $optionContentType, 'category_typeid', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectContentTypeC );
		$selectContentTypeU		=	$configuration->login_typeid;
		$lists['contentTypeU']	=	JHTML::_( 'select.genericlist', $optionContentType, 'login_typeid', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectContentTypeU );
		$selectContentTypeUS	=	$configuration->user_typeid;
		$lists['contentTypeUS']	=	JHTML::_( 'select.genericlist', $optionContentType, 'user_typeid', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectContentTypeUS );
		
		$optionContentTemplate	=	array();
		$optionContentTemplate[]=	JHTML::_( 'select.option', 0, JText::_( 'SELECT A TEMPLATE' ) );
		$optionContentTemplate	=	array_merge( $optionContentTemplate, HelperjSeblod_Helper::getContentTemplates() );
		$selectContentTemplateA	=	$configuration->article_templateid;
		$lists['formTemplateA']	=	JHTML::_( 'select.genericlist', $optionContentTemplate, 'article_templateid', 'class="inputbox" size="1"', 'value', 'text', $selectContentTemplateA );
		$selectContentTemplateC	=	$configuration->category_templateid;
		$lists['formTemplateC']	=	JHTML::_( 'select.genericlist', $optionContentTemplate, 'category_templateid', 'class="inputbox" size="1"', 'value', 'text', $selectContentTemplateC );
		$selectContentTemplateU	=	$configuration->login_templateid;
		$lists['formTemplateU']	=	JHTML::_( 'select.genericlist', $optionContentTemplate, 'login_templateid', 'class="inputbox" size="1"', 'value', 'text', $selectContentTemplateU );
		$selectContentTemplateUS	=	$configuration->user_templateid;
		$lists['formTemplateUS']	=	JHTML::_( 'select.genericlist', $optionContentTemplate, 'user_templateid', 'class="inputbox" size="1"', 'value', 'text', $selectContentTemplateUS );
		
		$lists['menuItemIdA']	=	'<input class="inputbox" type="text" id="article_itemid" name="article_itemid" size="16" value="'.$configuration->article_itemid.'" />';
		
		$optMenuItemId			=	array();
		$optMenuItemId[]		=	JHTML::_( 'select.option', 0, JText::_( 'SELECT A MENU ITEMID' ) );
		
		$lists['menuItemIdA']	=	JHTML::_( 'select.genericlist', array_merge( $optMenuItemId, JHTML::_( 'menu.linkoptions', false ) ), 'article_itemid', 'class="inputbox" size="1"', 'value', 'text', $configuration->article_itemid );
		$lists['menuItemIdC']	=	JHTML::_( 'select.genericlist', array_merge( $optMenuItemId, JHTML::_( 'menu.linkoptions', false ) ), 'category_itemid', 'class="inputbox" size="1"', 'value', 'text', $configuration->category_itemid );
		$lists['menuItemIdU']	=	JHTML::_( 'select.genericlist', array_merge( $optMenuItemId, JHTML::_( 'menu.linkoptions', false ) ), 'login_itemid', 'class="inputbox" size="1"', 'value', 'text', $configuration->login_itemid );
		$lists['menuItemIdUS']	=	JHTML::_( 'select.genericlist', array_merge( $optMenuItemId, JHTML::_( 'menu.linkoptions', false ) ), 'user_itemid', 'class="inputbox" size="1"', 'value', 'text', $configuration->user_itemid );
		
		$lists['systemComponent']	=	JHTML::_('select.booleanlist', 'system_component', 'class="inputbox"', $configuration->system_component );
		$lists['systemModules']		=	JHTML::_('select.booleanlist', 'system_modules', 'class="inputbox"', $configuration->system_modules );

		// Set Icon Edit
		$optionsIconEdit		=	array();
		$optionsIconEdit[] 		=	JHTML::_( 'select.option', 0, JText::_( 'DEFAULT' ) );
		// TODO::BEGIN
		$optionsIconEdit[] 		=	JHTML::_( 'select.option', 1, JText::_( 'HIDE' ) );
		$selectIconEdit			=	( @$configuration->icon_edit ) ? $configuration->icon_edit : 0;
		// TODO::END
		$lists['iconEdit']		=	JHTML::_( 'select.radiolist', $optionsIconEdit, 'icon_edit', 'size="1" class="inputbox"', 'value', 'text', $selectIconEdit);
		
		// Set Icon PDF
		$optionsIconPDF		=	array();
		$optionsIconPDF[] 		=	JHTML::_( 'select.option', 0, JText::_( 'DEFAULT' ) );
		$selectIconPDF			=	$configuration->icon_pdf;
		$lists['iconPdf']		=	JHTML::_( 'select.radiolist', $optionsIconPDF, 'icon_pdf', 'size="1" class="inputbox" disabled="disabled"', 'value', 'text', $selectIconPDF);
		
		// Set Icon Print
		$optionsIconPrint		=	array();
		$optionsIconPrint[]		=	JHTML::_( 'select.option', 0, JText::_( 'DEFAULT' ) );
		$selectIconPrint		=	$configuration->icon_print;
		$lists['iconPrint']		=	JHTML::_( 'select.radiolist', $optionsIconPrint, 'icon_print', 'size="1" class="inputbox" disabled="disabled"', 'value', 'text', $selectIconPrint);
		
		// Set Icon Email
		$optionsIconEmail		=	array();
		$optionsIconEmail[] 	=	JHTML::_( 'select.option', 0, JText::_( 'DEFAULT' ) );
		$selectIconEmail		=	$configuration->icon_email;
		$lists['iconEmail']		=	JHTML::_( 'select.radiolist', $optionsIconEmail, 'icon_email', 'size="1" class="inputbox" disabled="disabled"', 'value', 'text', $selectIconEmail);

		// Set Delete Mode
		$optCEKColumn		=	array();
		$optCEKColumn[] 	=	JHTML::_( 'select.option', 1, 1 );
		$optCEKColumn[] 	=	JHTML::_( 'select.option', 2, 2 );
		$optCEKColumn[] 	=	JHTML::_( 'select.option', 3, 3 );
		$optCEKColumn[] 	=	JHTML::_( 'select.option', 4, 4 );
		$optCEKColumn[] 	=	JHTML::_( 'select.option', 5, 5 );
		$selectColumn				=	$configuration->cek_column;
		$lists['defaultColumn']		=	JHTML::_( 'select.genericlist', $optCEKColumn, 'cek_column', 'size="1" class="inputbox" style="width: 83px;"', 'value', 'text', $selectColumn );
		
		$selectArtColumn			=	$configuration->cek_column_article;
		$lists['articleColumn']		=	JHTML::_( 'select.genericlist', $optCEKColumn, 'cek_column_article', 'size="1" class="inputbox" style="width: 83px;"', 'value', 'text', $selectArtColumn );

		$selectCatColumn			=	$configuration->cek_column_category;
		$lists['categoryColumn']	=	JHTML::_( 'select.genericlist', $optCEKColumn, 'cek_column_category', 'size="1" class="inputbox" style="width: 83px;"', 'value', 'text', $selectCatColumn );
		
		$selectUserColumn		=	$configuration->cek_column_user;
		$lists['userColumn']	=	JHTML::_( 'select.genericlist', $optCEKColumn, 'cek_column_user', 'size="1" class="inputbox" style="width: 83px;"', 'value', 'text', $selectUserColumn );

		// Set Delete Mode
		$optModeJf			=	array();
		$optModeJf[] 		=	JHTML::_( 'select.option', 0, JText::_( 'DEFAULT' ) );
		$optModeJf[] 		=	JHTML::_( 'select.option', 1, JText::_( 'EXTENDED' ) );
		$selectModeJf		=	$configuration->bool;
		$lists['jf_mode']	=	JHTML::_( 'select.radiolist', $optModeJf, 'bool', 'size="1" class="inputbox"', 'value', 'text', $selectModeJf );
		
		$lists['jf_publish']	= JHTML::_('select.booleanlist', 'bool_publish', 'class="inputbox"', $configuration->bool_publish );
		
		$lists['jf_check']	= JHTML::_('select.booleanlist', 'bool_check', 'class="inputbox"', $configuration->bool_check );
		
		$lists['jf_hide']	= JHTML::_('select.booleanlist', 'bool_hide', 'class="inputbox"', $configuration->bool_hide );
		
		$optRestriction		=	array();
		$optRestriction[]	=	JHTML::_( 'select.option', 3, JText::_( 'HIGHER' ) );
		$optRestriction[]	=	JHTML::_( 'select.option', 2, JText::_( 'HIGH' ) );
		$optRestriction[]	=	JHTML::_( 'select.option', 1, JText::_( 'MEDIUM' ) );
		$optRestriction[]	=	JHTML::_( 'select.option', 0, JText::_( 'LOW' ) );
		$lists['restrictT']	=	JHTML::_( 'select.genericlist', $optRestriction, 'restriction_type', 'size="1" class="inputbox"', 'value', 'text', $configuration->restriction_type );
		$lists['restrictF']	=	JHTML::_( 'select.genericlist', $optRestriction, 'restriction_field', 'size="1" class="inputbox"', 'value', 'text', $configuration->restriction_field );
		$lists['restrictC']	=	JHTML::_( 'select.genericlist', $optRestriction, 'restriction_content', 'size="1" class="inputbox"', 'value', 'text', $configuration->restriction_content );
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		$this->assignRef( 'configuration', $configuration );
		$this->assignRef( 'lists', $lists );
		
		$this->_displayToolbar( $isAuth );
		
		parent::display( $tpl );
	}
	
}
?>