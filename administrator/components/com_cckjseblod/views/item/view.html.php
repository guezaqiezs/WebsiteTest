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
 * Content Item		View Class
 **/
class CCKjSeblodViewItem extends JView
{
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isNew, $doCopy, $isAuth ) 
	{
		if ( $isAuth ) {
      		JToolBarHelper::custom( 'save', 'save_jseblod', 'save_jseblod', JText::_( 'Save' ), false ); //JToolBarHelper::save();
			JToolBarHelper::custom( 'apply', 'apply_jseblod', 'apply_jseblod', JText::_( 'Apply' ), false ); //JToolBarHelper::apply();
			JToolBarHelper::spacer();
		}
		if ( $isNew || $doCopy )  {
			$text = $doCopy ? JText::_( 'Copy' ) : JText::_( 'New' );
			JToolBarHelper::title(   JText::_( 'CONTENT ITEM' ).': <small><small>[ '.$text.' ]</small></small>', 'items.png' );
			JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Cancel' ), false ); //JToolBarHelper::cancel();
		} else {
			$text = $isAuth ? JText::_( 'Edit' ) : JText::_( 'View' );
			JToolBarHelper::title(   JText::_( 'CONTENT ITEM' ).': <small><small>[ '.$text.' ]</small></small>', 'items.png' );
			JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Close' ), false ); //JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::divider();
		HelperjSeblod_Display::help( 'item' );
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
		$task		=	JRequest::getVar( 'layout' );
		
		// View Create
		$assign		= JRequest::getVar( 'assign' );
		$new_f		= JRequest::getInt( 'new_f' );
		
		// Get Data from Model
		$item		=& $this->get( 'Data' );

		// Set Flags
		$isNew		= ( @$item->id > 0 ) ? 0 : 1;
		$doCopy		= JRequest::getVar( 'doCopy', false );
		$isAuth 	= ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
		// Checking!
		if ( JTable::isCheckedOut( $user->get( 'id' ), @$item->checked_out ) ) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', '', $item->title );
			$mainframe->redirect( _LINK_CCKJSEBLOD_ITEMS, $msg, 'notice' );
		}

		// Set Wysiwyg Modal
		$modals['description'] = HelperjSeblod_Display::quickModalWysiwyg( 'Description', $controller, 'description', 'pagebreak', 0, @$item->id, false );
		
		$tooltips['link_description'] = HelperjSeblod_Display::quickTooltipAjaxLink( 'Description', $controller, 'description', @$item->id );
		
		// Set Category List ( Select List )
		$optionCategories	= array();
		$optionCategories[]	= JHTML::_( 'select.option',  '', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
		$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
		$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
		$optionCategories	= ( ! $isNew && ! $item->categorystate ) ? array_merge( $optionCategories, HelperjSeblod_Helper::getItemCategories( true, false ) ) : array_merge( $optionCategories, HelperjSeblod_Helper::getItemCategories( true, true ) );
		$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
		$selectFilterInCategory	=	( JRequest::getInt( 'filter_search' ) == 6 ) ? JRequest::getInt( 'search' ) : '';
		$selectFilterCategory	=	( $selectFilterInCategory || JRequest::getInt( 'filter_category' ) ) ? ( ( $selectFilterInCategory ) ? $selectFilterInCategory : JRequest::getInt( 'filter_category' ) ) : ( _ITEM_DEFAULT_CAT ? _ITEM_DEFAULT_CAT : '' );
		$selectedCategory   	=	( ! $isNew ) ? $item->category : $selectFilterCategory;
		$lists['category']		=	JHTML::_( 'select.genericlist', $optionCategories, 'category', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectedCategory );
		
		// Set Default Item Type
		$type	=	( $isNew && JRequest::getInt( 'filter_type' ) ) ? $model->getType( JRequest::getInt( 'filter_type' ) ) : '';
		
		// Set Item Type Filter
		$types[]			= JHTML::_('select.option',  '', JText::_( 'SELECT A FIELD TYPE' ), 'value', 'text' );
		$types		= array_merge( $types, HelperjSeblod_Helper::getItemTypes() );
		$lists['type']		= JHTML::_('select.genericlist', $types, 'select_type', 'class="inputbox required required-enabled" size="1"', 'value', 'text', @$item->typename );
		
		// Create Search Filter
		$optionsValidation[] = JHTML::_( 'select.option', '', JText::_( 'None' ) );
		$optionsValidation[] = JHTML::_( 'select.option', 'validate-alpha', JText::_( 'ALPHA' ) );
		$optionsValidation[] = JHTML::_( 'select.option', 'validate-alphanum', JText::_( 'ALPHANUM' ) );
		$optionsValidation[] = JHTML::_( 'select.option', 'validate-number', JText::_( 'NUMBER' ) );
		$optionsValidation[] = JHTML::_( 'select.option', 'validate-email', JText::_( 'EMAIL' ) );
		$optionsValidation[] = JHTML::_( 'select.option', 'validate-url', JText::_( 'URL' ) );
		
		$validationSelected = ( ! $isNew ) ? $item->validation : '';
		$lists['validation'] = JHTML::_( 'select.genericlist', $optionsValidation, 'validation', 'size="1" class="inputbox"', 'value', 'text', $validationSelected );
		
		// Create Light Radio
		$lists['light']	= JHTML::_('select.booleanlist', 'light', 'class="inputbox"', ( ! $isNew ) ? $item->light : 1 );
		
		// Create Mandatory Radio
		$lists['required']	= JHTML::_('select.booleanlist', 'required', 'class="inputbox"', @$item->required );
		
		// Create Display Radio
		//if ( $item->typename == 'download_html' ) {
			//$item->display = ( $item->display == 1 ) ? 0 : 1;
		//}
		//$lists['display']	= JHTML::_('select.booleanlist', 'display', 'class="inputbox"', ( ! $isNew ) ? $item->display : 1 );
		//
				
		// Set Display Intro Text List
		$optDisplay[] = JHTML::_( 'select.option', 3, JText::_( 'ALWAYS' ) );
		//$optDisplay[] = JHTML::_( 'select.option', 2, JText::_( 'ON ITEM' ) );
		//$optDisplay[] = JHTML::_( 'select.option', 1, JText::_( 'ON COLLECTION' ) );
		$optDisplay[] = JHTML::_( 'select.option', 0, JText::_( 'Hide' ) );
		$selectDisplay = ( ! $isNew ) ? $item->display : 3;
		$lists['display'] = JHTML::_( 'select.genericlist', $optDisplay, 'display', 'size="1" class="inputbox"', 'value', 'text', $selectDisplay );

		// Set Display Intro Text List
		$optDisplayValue[]	=	JHTML::_( 'select.option', -1, JText::_( 'No' ) );
		$optDisplayValue[]	=	JHTML::_( 'select.option', 0, JText::_( 'Yes' ) );
		$selectDisplayValue	=	( ! $isNew ) ? $item->displayvalue : 0;
		$lists['displayvalue']	=	JHTML::_( 'select.radiolist', $optDisplayValue, 'displayvalue', 'size="1" class="inputbox"', 'value', 'text', $selectDisplayValue );
		
		// Set Display Intro Text List
		$optAllowEdition[]		=	JHTML::_( 'select.option', 0, JText::_( 'ALWAYS' ) );
		$optAllowEdition[]		=	JHTML::_( 'select.option', 1, JText::_( 'ON ADMIN' ) );
		$optAllowEdition[]		=	JHTML::_( 'select.option', -1, JText::_( 'NEVER' ) );
		$selectAllowEdition		=	( ! $isNew ) ? $item->gEACL : 0;
		$lists['allowEdition']	=	JHTML::_( 'select.genericlist', $optAllowEdition, 'gEACL', 'size="1" class="inputbox"', 'value', 'text', $selectAllowEdition );
		
		$lists['boolean']	= JHTML::_('select.booleanlist', 'bool', 'class="inputbox"', ( ! $isNew ) ? $item->bool : 1 );
		
		if ( ! $isNew ) {
			$selected_o = $item->ordering;
		}
			
		// Create Search Filter
		$options_ordering[] = JHTML::_('select.option', 0, JText::_( 'Following Options' ) );
		$options_ordering[] = JHTML::_('select.option', 1, JText::_( 'Alphabetical A>Z' ) );
		$options_ordering[] = JHTML::_('select.option', 2, JText::_( 'Alphabetical Z>A' ) );
		$lists['ordering'] = JHTML::_('select.genericlist', $options_ordering, 'ordering', 'size="1" class="inputbox"', 'value', 'text', @$selected_o );
				
		// Create Content Checkbox
		$optContent[] 		= JHTML::_( 'select.option',  '1', JText::_( 'Published' ) );
		$optContent[] 		= JHTML::_( 'select.option',  '0', JText::_( 'Unpublished' ) );
		$optContent[] 		= JHTML::_( 'select.option',  '-1', JText::_( 'Archived' ) );
		$selectContent 		= ( ! $isNew ) ? explode(',', $item->content ) : 1;
		$lists['content'] 	= HelperjSeblod_Helper::checkBoxList( $optContent, 'content[]', 'class="required"', 'value', 'text', $selectContent );
		
		$optAccessAuthor		=	array();
		$optAccessAuthor[] 		=	JHTML::_( 'select.option',  '-1', JText::_( 'No' ) );
		$optAccessAuthor[] 		=	JHTML::_( 'select.option',  '0', JText::_( 'Yes' ) );
		$selectAccessAuthor		=	( ! $isNew ) ? $item->uACL : 0;
		$lists['uACL']			=	JHTML::_( 'select.radiolist', $optAccessAuthor, 'uACL', 'size="1" class="inputbox"', 'value', 'text', $selectAccessAuthor );
		
		$optAccessGroup			=	array();
		$optAccessGroup[] 		=	JHTML::_( 'select.option', 17, '-&nbsp;'.JText::_( 'PUBLIC FRONTEND' ) );
		$optAccessGroup[] 		=	JHTML::_( 'select.option', 18, _NBSP.'-&nbsp;'.JText::_( 'Registered' ) );
		$optAccessGroup[] 		=	JHTML::_( 'select.option', 19, _NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Author' ) );
		$optAccessGroup[] 		=	JHTML::_( 'select.option', 20, _NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Editor' ) );
		$optAccessGroup[] 		=	JHTML::_( 'select.option', 21, _NBSP._NBSP._NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Publisher' ) );
		$optAccessGroup[] 		=	JHTML::_( 'select.option', 22, '-&nbsp;'.JText::_( 'PUBLIC BACKEND' ) );
		$optAccessGroup[] 		=	JHTML::_( 'select.option', 23, _NBSP.'-&nbsp;'.JText::_( 'Manager' ) );
		$optAccessGroup[] 		=	JHTML::_( 'select.option', 24, _NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Administrator' ) );
		$optAccessGroup[] 		=	JHTML::_( 'select.option', 25, _NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Super Administrator' ) );
		$optAccessGroup[] 		=	JHTML::_( 'select.option', -1, '-&nbsp;'.JText::_( 'NO GROUP ACCESS' ) );
		$selectAccessGroup		=	( @$item->gACL ) ? $item->gACL : 17;
		$lists['gACL']			=	JHTML::_( 'select.genericlist', $optAccessGroup, 'gACL', 'size="1" class="inputbox"', 'value', 'text', $selectAccessGroup );
		
		// Item Type CONTENT ITEM
		$modals['selectItem']	= HelperjSeblod_Display::quickModalTask( 'SELECT', '', 'extended', 'pagebreak', 'items', 'select' );
		//$modals['newItem']		= HelperjSeblod_Display::quickModalTask( 'CREATE', '', 'extended', 'readmore', 'items', 'create' );

		// Item Type CONTENT TYPE
		$modals['selectType']	= HelperjSeblod_Display::quickModalTask( 'SELECT', 'item', 'extended', 'pagebreak', 'types', 'select' );
		
		// Push Data to Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document',	$document );
		$this->assignRef( 'task',	$task );
		$this->assignRef( 'doCopy', $doCopy );
		$this->assignRef( 'assign', $assign );
		$this->assignRef( 'item', $item );
		$this->assignRef( 'type', $type );
		$this->assignRef( 'isNew', $isNew );
		$this->assignRef( 'isAuth', $isAuth );
		$this->assignRef( 'formTypes', $formTypes );
		$this->assignRef( 'lists', $lists );
		$this->assignRef( 'modals', $modals );
		$this->assignRef( 'tooltips', $tooltips );
		$this->assignRef( 'new_f', $new_f );
		
		$this->_displayToolbar( $isNew, $doCopy, $isAuth );
		
		parent::display($tpl);
	}
	
}
?>