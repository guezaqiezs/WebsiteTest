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
 * CCKjSeblod			View Class
 **/
class CCKjSeblodViewCCKjSeblod extends JView
{
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isAuth )
	{
		JToolBarHelper::title( JText::_( '&nbsp;' ), 'jseblod.png' );
		JToolBarHelper::custom( 'cpanel_interface', 'interface', 'interface', JText::_( 'CONTENT MANAGER' ), false );
		JToolBarHelper::divider();
		if ( $isAuth ) {
			HelperjSeblod_Display::quickToolbarProcess();
		}
		//JToolBarHelper::custom( 'cpanel_help', 'helpjseblod', 'help', JText::_( 'Help' ), false );
		//HelperjSeblod_Display::quickToolbarSupport();
	}
	
	/**
	 * Display Default View
	 **/
	function display( $tpl = null )
	{
		// Get Request Vars
		global $mainframe, $option;
		$controller	=	JRequest::getWord( 'controller' );
		$document 	=&	JFactory::getDocument();
		$layout		=	$this->getLayout();
		$user 		=&	JFactory::getUser();
		
		// Set Flags
		$isAuth = ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
		
		// Php Version check
		$phpVersion	=	phpversion();
		if ( version_compare( $phpVersion, '5.0' ) < 0 ) {
			$mainframe->enqueueMessage( JText::sprintf( 'PHP5 IS REQUIRED', $phpVersion ), "error" );
			return true;
		}
		
		if ( $layout == 'data' ) {
			$selection	=	JRequest::getString( 'artids' );
			$actionMode	=	JRequest::getInt( 'act' );
			$category	=	JRequest::getInt( 'cat_id' );
			
			/**************
			 * Export CSV *
			 **************/
			$optFields			=	array();
			$optFields[] 		=	JHTML::_( 'select.option', 'title', JText::_( 'TITLE' ) );
			$optFields[] 		=	JHTML::_( 'select.option', 'introtext', JText::_( 'ALIAS' ) );
			$optFields[] 		=	JHTML::_( 'select.option', 'fulltext', JText::_( 'STATE' ) );
			$optFields[] 		=	JHTML::_( 'select.option', 'created', JText::_( 'CREATED DATE' ) );
			$optFields[] 		=	JHTML::_( 'select.option', 'authord', JText::_( 'AUTHOR' ) );
			$optFields[] 		=	JHTML::_( 'select.option', 'introtext_as_field', JText::_( 'INTROTEXT' ) );
			$selectFields[]		=	'title';
			$selectFields[]		=	'introtext_as_field';
			$lists['fields']	=	JHTML::_( 'select.genericlist', $optFields, 'fields[]', 'size="6" class="inputbox" multiple="multiple"', 'value', 'text', $selectFields );

			/***************
			 * Export HTML *
			 ***************/
			 
			$optOutput			=	array();
			$optOutput[] 		=	JHTML::_( 'select.option', 0, JText::_( 'COPY PASTE' ) );
			$optOutput[] 		=	JHTML::_( 'select.option', 1, JText::_( 'DOWNLOAD' ) );
			$lists['output']	=	JHTML::_( 'select.radiolist', $optOutput, 'output', 'class="inputbox"', 'value', 'text', '0', 'output' );

			$optExt				=	array();
			$optExt[] 			=	JHTML::_( 'select.option', 'html', JText::_( 'HTML' ) );
			$optExt[]	 		=	JHTML::_( 'select.option', 'txt', JText::_( 'TXT' ) );
			$lists['ext']		=	JHTML::_( 'select.genericlist', $optExt, 'extension', 'class="inputbox"', 'value', 'text', 'html' );

			/**********
			 * Import *
			 **********/
			$optCategories		=	array();
			$optCategories[]	=	JHTML::_('select.option', '', JText::_( 'SELECT A CATEGORY' ) );
			$optCategories		=	array_merge( $optCategories, HelperjSeblod_Helper::getJoomlaCategories() );
			$selectCategory		=	( $category ) ? $category : '';
			$lists['catid']		=	JHTML::_( 'select.genericlist', $optCategories, 'import_csv[catid]', 'class="inputbox" size="1"', 'value', 'text', $selectCategory );

			$optContentType		=	array();
			$optContentType[]	=	JHTML::_('select.option', '', JText::_( 'SELECT A CONTENT TYPE' ) );
			$optContentType[]	=	JHTML::_('select.option', 0, JText::_( 'ADD NEW CONTENT TYPE' ) );
			$optContentType		=	array_merge( $optContentType, HelperjSeblod_Helper::getContentTypesByName( $actionMode ) );
			$lists['ctype']		=	JHTML::_( 'select.genericlist', $optContentType, 'import_csv[content_type]', 'class="inputbox required required-enabled" size="1"', 'value', 'text', '', 'content_type' );
			
			$optState			=	array();
			$optState[]			=	JHTML::_('select.option', '1', JText::_( 'Published' ) );
			$optState[]			=	JHTML::_('select.option', '0', JText::_( 'Unpublished' ) );
			$optState[]			=	JHTML::_('select.option', '-1', JText::_( 'Archived' ) );
			$lists['state']		=	JHTML::_( 'select.genericlist', $optState, 'import_csv[state]', 'class="inputbox" size="1"', 'value', 'text', '' );
			
			$optAccess			=	array();
			$optAccess[]		=	JHTML::_('select.option', '0', JText::_( 'Public' ) );
			$optAccess[]		=	JHTML::_('select.option', '1', JText::_( 'Registered' ) );
			$optAccess[]		=	JHTML::_('select.option', '2', JText::_( 'Special' ) );
			$lists['access']	=	JHTML::_( 'select.genericlist', $optAccess, 'import_csv[access]', 'class="inputbox" size="1"', 'value', 'text', '' );
			
			$optUsers			=	array();
			$optUsers			=	array_merge( $optUsers, HelperjSeblod_Helper::getJoomlaAuthors( 24 ) );
			$lists['author']	=  JHTML::_('select.genericlist', $optUsers, 'import_csv[author]', 'class="inputbox"', 'value', 'text', $user->id );
			
			$optSeparator[]		=	JHTML::_('select.option', ',', JText::_( ',' ) );
			$optSeparator[] 	=	JHTML::_('select.option', ';', JText::_( ';' ) );
			$optSeparator[] 	=	JHTML::_('select.option', '|', JText::_( '|' ) );
			$optSeparator[] 	=	JHTML::_('select.option', '#', JText::_( '#' ) );
			$lists['separator']	=	JHTML::_('select.genericlist', $optSeparator, 'import_csv[separator]', 'size="1" class="inputbox"', 'value', 'text', ',' );

			$optControl[] 		=	JHTML::_('select.option', 0, JText::_( 'UPDATE MERGE' ) );
			$optControl[] 		=	JHTML::_('select.option', -1, JText::_( 'UPDATE OVERWRITE' ) );
			$lists['control']	=	JHTML::_('select.genericlist', $optControl, 'import_csv[update_mode]', 'size="1" class="inputbox"', 'value', 'text', ',' );

			$lists['utf8']		=	JHTML::_( 'select.booleanlist', 'import_csv[force_utf8]', 'class="inputbox"', 1 );

			$langEnabled		=	CCK_LANG_Enable();
			if ( $langEnabled ) {
				$langs	=	CCK_LANG_List();
				if ( sizeof( $langs ) ) {
					$optLangs	=	array();
					$default	=	CCK_Lang_Default();
					foreach( $langs as $lang ) {
						if ( $default == $lang->shortcode ) {
							$optLangs[]		=	JHTML::_( 'select.option', 0, $lang->name );
						} else {
							$optLangs[]		=	JHTML::_( 'select.option', $lang->id, $lang->name );
						}
					}
					$lists['lang']	=	JHTML::_( 'select.genericlist', $optLangs, 'import_csv[lang]', 'class="inputbox" size="1"', 'value', 'text', 0 );
				}
			}
			
			$this->assignRef( 'lists', $lists );
			$this->assignRef( 'selection', $selection );
			$this->assignRef( 'actionMode', $actionMode );
			$this->assignRef( 'langEnabled', $langEnabled );
		}
				
		if ( $layout == 'media' ) {
			$optImageProcess[]	=	JHTML::_('select.option', '', JText::_( 'ORIGINAL' ) );
			$optImageProcess[] 	=	JHTML::_('select.option', 'addcolor', JText::_( 'ADD COLOR' ) );
			$optImageProcess[] 	=	JHTML::_('select.option', 'crop', JText::_( 'CROP CENTER' ) );
			$optImageProcess[] 	=	JHTML::_('select.option', 'maxfit', JText::_( 'MAX FIT' ) );
			$optImageProcess[]	=	JHTML::_('select.option', 'stretch', JText::_( 'STRETCH' ) );
			$lists['original']	=	JHTML::_('select.genericlist', $optImageProcess, 'format', 'size="1" class="inputbox"', 'value', 'text', '' );
			
			$optThumbsProcess[] =	JHTML::_('select.option', '', JText::_( 'NONE' ) );
			$optThumbsProcess[] =	JHTML::_('select.option', 'addcolor', JText::_( 'ADD COLOR' ) );
			$optThumbsProcess[] =	JHTML::_('select.option', 'crop', JText::_( 'CROP CENTER' ) );
			$optThumbsProcess[] =	JHTML::_('select.option', 'maxfit', JText::_( 'MAX FIT' ) );
			$optThumbsProcess[] =	JHTML::_('select.option', 'stretch', JText::_( 'STRETCH' ) );
			$lists['thumb1']	=	JHTML::_('select.genericlist', $optThumbsProcess, 'thumb1', 'size="1" class="inputbox"', 'value', 'text', '' );
			$lists['thumb2']	=	JHTML::_('select.genericlist', $optThumbsProcess, 'thumb2', 'size="1" class="inputbox"', 'value', 'text', '' );
			$lists['thumb3']	=	JHTML::_('select.genericlist', $optThumbsProcess, 'thumb3', 'size="1" class="inputbox"', 'value', 'text', '' );

			$this->assignRef( 'lists', $lists );
		}
		
		// Push Data into Template
		$this->assignRef( 'option', $option );
		$this->assignRef( 'controller', $controller );
		$this->assignRef( 'document', $document );
		
		$this->_displayToolbar( $isAuth );
		
		parent::display( $tpl );
	}
	
}
?>