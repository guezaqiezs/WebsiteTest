<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 20098 jSeblod. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Template			View Class
 **/
class CCKjSeblodViewTemplate extends JView
{
	/**
	 * Display Delete Toolbar
	 **/
	function _displayDeleteToolbar() 
	{
		JToolBarHelper::title(   JText::_( 'TEMPLATE' ).': <small><small>[ '.JText::_( 'Delete' ).' ]</small></small>', 'templates.png' );
		JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Close' ), false ); //JToolBarHelper::cancel( 'cancel', 'Close' );
		JToolBarHelper::divider();
		HelperjSeblod_Display::help( 'templates' );
	}
	
	/**
	 * Display Toolbar
	 **/
	function _displayToolbar( $isNew, $doCopy, $isAuth, $templateId ) 
	{
		if ( $isAuth ) {
			JToolBarHelper::custom( 'save', 'save_jseblod', 'save_jseblod', JText::_( 'Save' ), false ); //JToolBarHelper::save();
			JToolBarHelper::custom( 'apply', 'apply_jseblod', 'apply_jseblod', JText::_( 'Apply' ), false ); //JToolBarHelper::apply();;
			JToolBarHelper::spacer();
		}
		if ( $isNew || $doCopy )  {
			$text = $doCopy ? JText::_( 'Copy' ) : JText::_( 'New' );
			JToolBarHelper::title(   JText::_( 'TEMPLATE' ).': <small><small>[ '.$text.' ]</small></small>', 'templates.png' );
			JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Cancel' ), false ); //JToolBarHelper::cancel();
		} else {
			$text = $isAuth ? JText::_( 'Edit' ) : JText::_( 'View' );
			JToolBarHelper::title(   JText::_( 'TEMPLATE' ).': <small><small>[ '.$text.' ]</small></small>', 'templates.png' );
		  JToolBarHelper::custom( 'cancel', 'cancel_jseblod', 'cancel_jseblod', JText::_( 'Close' ), false ); //JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::divider();
		HelperjSeblod_Display::help( 'template' );
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

		$doDelete	= JRequest::getVar( 'doDelete', false );
		$doPreview	= JRequest::getVar( 'doPreview', false );
		$doSource	= JRequest::getVar( 'doSource', false );
		$doParams	= JRequest::getVar( 'doParams', false );
		$doLocations= JRequest::getVar( 'doLocations', false );
		
		$isAuth 	= ( $user->get( 'gid' ) < _EDIT_ACCESS ) ? 0 : 1;
		
		if ( $doDelete ) {
			
			$templateRemoveItems	=& $this->get( 'RemoveData' );
			
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'templateRemoveItems', $templateRemoveItems );
			
			$this->_displayDeleteToolbar();
			
		} else if ( $doPreview ) {
			
			$template	=&	$this->get( 'Data' );
			
			/**
			 * Render jSeblod Content Template
			 **/
			
			// Initialize Parameters
			$random		=	rand( 1, 100000 );
			$cache 		=	'0'; 				//$cache = getCfg('caching');
			$file 		=	'index_jseblod'.$random;	//JRequest::getCmd('tmpl', 'index');
			
			// Create File to Render from index.php
			$fileToCopy 	=	JPATH_SITE.DS.'templates'.DS.$template->name.DS.'index.php';
			$fileToRender	=	JPATH_SITE.DS.'templates'.DS.$template->name.DS.$file.'.php';
			if ( JFile::exists( $fileToCopy ) ) {
				JFile::copy( $fileToCopy, $fileToRender );
				$buffer	=	JFile::read( $fileToRender );
			}
			
			$params	=	array(
				'template' 	=> $template->name,
				'file'		=> $file.'.php',
				'directory'	=> JPATH_SITE.DS.'templates',
			);
				
			// Create New HTML Document
			$doc	=&	JDocument::getInstance( 'html' );
			
			$doc->rooturl	=	JURI::root( true );
			
			// Push jSeblod Content Items into Template
			
			// TODO:...
			
			$data	=	$doc->render( $cache, $params );
			
			// Flush Items && Values
			foreach( $doc as $key => $value ) {
				$doc->key	=	null;
				$doc->value	=	null;
			}

			// Delete File To Render
			if ( JFile::exists( $fileToRender ) ) {
				JFile::delete( $fileToRender );
			}
			
			/**
			 * End
			 **/
		
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document', $document );
			
			$this->assignRef( 'path', $fileToCopy );
			$this->assignRef( 'data', $data );
			
		} else if ( $doSource ) {
			
			// Get Request Vars
			$dir = JRequest::getVar( 'dir' );
			$file = JRequest::getVar( 'file' );
			
			// Get Data from Model
			$template =& $this->get( 'Data' );
				
			$extension = JFile::getExt( $file );
			if ( $extension == 'php' || $extension == 'xml' ) {
				if ( JString::strpos( $file, '.css.' ) !== false ) {
					$path = JPATH_SITE.DS.'templates'.DS.$template->name.DS.'css'.DS.$file;
				} else if ( JString::strpos( $file, '.js.' ) !== false ) {
					$path = JPATH_SITE.DS.'templates'.DS.$template->name.DS.'js'.DS.$file;
				} else {
					$path = JPATH_SITE.DS.'templates'.DS.$template->name.DS.$file;
				}
			} else {
				$path = JPATH_SITE.DS.'templates'.DS.$template->name.DS.$dir.DS.$file;
			}
			if ( JFile::exists( $path ) ) {
				$source = JFile::read( $path );
			}
			
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'template', $template );
			$this->assignRef( 'dir', $dir );
			$this->assignRef( 'file', $file );
			$this->assignRef( 'path', $path );
			$this->assignRef( 'source', $source );
			$this->assignRef( 'isAuth', $isAuth );
		
		} else if ( $doParams ) {
			
			$template =& $this->get( 'Data' );
			$xml	=	JPATH_SITE.DS.'templates'.DS.$template->name.DS.'templateDetails.xml';
			if ( JFile::exists( $xml ) ) {
				$xmlFile	= 'templateDetails.xml';
			}
			$ini	= JPATH_SITE.DS.'templates'.DS.$template->name.DS.'params.ini';
			// Ini File
			if ( JFile::exists( $ini ) ) {
				$content = JFile::read( $ini );
				$params = new JParameter( $content, $xml, 'template' );
			}
				
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'isAuth', $isAuth );
			
			$this->assignRef( 'template', $template );
			$this->assignRef( 'params', $params );
	
		} else if ( $doLocations ) {
			
			$template 	=&	$this->get( 'Data' );
			$locations	=	null;
			if ( @$template->name ) {
				$locations		=	HelperjSeblod_Helper::getTemplateLocations( $template->name );
				$optLoc			=	array();
				$countLoc		=	sizeof( $locations );
				if ( $countLoc ) {
					foreach ( $locations as $loc ) {
						$optLoc[]	=	JHTML::_( 'select.option', $loc, $loc );
					}
				}
				$locations 	=	JHTML::_( 'select.genericlist', $optLoc, 'locations[]', 'size="'.($countLoc + 5).'"class="inputbox" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text', '');
			}
				
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'isAuth', $isAuth );
			
			$this->assignRef( 'template', $template );
			$this->assignRef( 'locations', $locations );
			
		} else {
		
			jimport('joomla.filesystem.folder');
			
			// Get Data from Model
			$template 				=& $this->get( 'Data' );
			$templateNames			=& $this->get( 'TemplateNames' );
			$assignedCategories		=& $this->get( 'AssignedCategories' );
			$availableCategories	=& $this->get( 'AvailableCategories' );
			$menuItems 				=& $this->get( 'MenuItems' );
			$allMenuItems 			=& $this->get( 'AllMenuItems' );
			$urlsItems 				=& $this->get( 'Urls' );
			
			// Set Flags
			$isNew		= ( @$template->id > 0 ) ? 0 : 1;
			$doCopy		= JRequest::getVar( 'doCopy', true );
			
			// Checking!
			if ( JTable::isCheckedOut( $user->get( 'id' ), @$template->checked_out ) ) {
				$msg = JText::sprintf( 'DESCBEINGEDITTED', '', $template->title );
				$mainframe->redirect( _LINK_CCKJSEBLOD_TEMPLATES, $msg, 'notice' );
			}
						
			// Set Wysiwyg Modal
			$modals['description'] = HelperjSeblod_Display::quickModalWysiwyg( 'Description', $controller, 'description', 'pagebreak', 0, @$template->id, false );
			
			$tooltips['link_description'] = HelperjSeblod_Display::quickTooltipAjaxLink( 'Description', $controller, 'description', @$template->id );
			
			// Set Preview Modal
			if ( ! $isNew ) {
			$modals['preview']	= HelperjSeblod_Display::quickModal( 'Preview', null, 'image', $option, $controller, 'preview', '', $template->id );
			}
			
			// Set Type Modal
			$modals['selectType']	= HelperjSeblod_Display::quickModalTask( 'SELECT', 'template', 'type', 'pagebreak', 'types', 'select' );
			$modals['newType']		= HelperjSeblod_Display::quickModalTask( 'CREATE', '', 'type', 'readmore', 'types', 'create', 680, 230);
			
			// Set Published List ( Boolean List )
			$selectedPublished	= ( $isNew ) ? 1 : $template->published;
			$lists['published'] = JHTML::_( 'select.booleanlist', 'published', 'class="inputbox"', $selectedPublished );
			
			// Set Category List ( Select List )
			$optionCategories	= array();
			$optionCategories[]	= JHTML::_( 'select.option',  '', JText::_( 'SELECT A CATEGORY' ), 'value', 'text' );
			$optionCategories[]	= JHTML::_( 'select.option',  '1', JText::_( 'QUICK CATEGORY' ), 'value', 'text' );
			$optionCategories[] = JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'Categories' ) );
			$optionCategories	= ( ! $isNew && ! $template->categorystate ) ? array_merge( $optionCategories, HelperjSeblod_Helper::getTemplateCategories( true, false ) ) : array_merge( $optionCategories, HelperjSeblod_Helper::getTemplateCategories( true, true ) );
			$optionCategories[]	= JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$selectFilterInCategory	=	( JRequest::getInt( 'filter_search' ) == 5 ) ? JRequest::getInt( 'search' ) : '';
			$selectFilterCategory	=	( $selectFilterInCategory || JRequest::getInt( 'filter_category' ) ) ? ( ( $selectFilterInCategory ) ? $selectFilterInCategory : JRequest::getInt( 'filter_category' ) ) : ( _TEMPLATE_DEFAULT_CAT ? _TEMPLATE_DEFAULT_CAT : '' );
			$selectedCategory   = ( ! $isNew ) ? $template->category : $selectFilterCategory;
			$lists['category']	= JHTML::_( 'select.genericlist', $optionCategories, 'category', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectedCategory );

			// Set Install List ( Select List )
			$optInstall		=	array();
			if ( $isNew ) {
				$selectInstall	=	'upload';
			} else if ( $doCopy ) {
				$selectInstall	=	'folder';
			} else {
				$optInstall[]	=	JHTML::_( 'select.option',  'default', JText::_( 'NO UPDATE' ), 'value', 'text' );
				$selectInstall	=	'default';
			}
			$optInstall[]	=	JHTML::_( 'select.option',  'folder', JText::_( 'FROM EXISTING FOLDER' ), 'value', 'text' );
			$optInstall[]	=	JHTML::_( 'select.option',  'upload', JText::_( 'FROM PACKAGE UPLOAD' ), 'value', 'text' );
			
			$lists['install']	=	JHTML::_( 'select.genericlist', $optInstall, 'select_install', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectInstall );
			
			// Set Source Folders List ( Select List )
			$templateHidden = explode( ',', _TEMPLATE_HIDDEN );
			$folders = JFolder::folders( JPATH_SITE.DS.'templates' );
			$optionsFolders		= null;
			$optionsFolders[]	= JHTML::_('select.option',  '', '- '.JText::_( 'SELECT A FOLDER' ).' -', 'value', 'text' );
			$selectedFolder		= ( $doCopy ) ? $template->name : '';
			if ( $folders ) {
				foreach( $folders as $value ) {
					if ( $doCopy ) {
						if ( array_search( $value, $templateHidden ) === false ) {
							$optionsFolders[] = JHTML::_( 'select.option', $value, $value );
						}
					} else {
						if ( ( array_search( $value, $templateNames ) === false ) && ( array_search( $value, $templateHidden ) === false ) ) {
							$optionsFolders[] = JHTML::_( 'select.option', $value, $value );
						}
					}
				}
			}
			$lists['folders']	= JHTML::_('select.genericlist', $optionsFolders, 'install_folder', 'class="inputbox" size="1"', 'value', 'text', $selectedFolder );
			
			// Set Joomla Category Views List ( Double Select List )
			if ( $doCopy ) {
				$assignedCategories = array();
			}
			$lists['assignedCategories']	= JHTML::_( 'select.genericlist', $assignedCategories, 'selected_categories[]', 'class="inputbox" size="20" onDblClick="addSelectedToListAndSelect(\'adminForm\',\'selected_categories\',\'available_categories\', \'\');delSelectedFromList(\'adminForm\',\'selected_categories\');" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			$lists['availableCategories']	= JHTML::_( 'select.genericlist', $availableCategories, 'available_categories', 'class="inputbox" size="20" onDblClick="addSelectedToListAndSelect(\'adminForm\',\'available_categories\',\'selected_categories\', \'\');delSelectedFromList(\'adminForm\',\'available_categories\');" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text' );
			
			// Set Menu Item Views List ( Select List )
			if ( $isNew ) {
				$menuItems = array( JHTML::_( 'select.option',  '-1' ) );
				$template->pages = 'none';
			}
			else {
				if ( $allMenuItems ) {
					$template->pages = 'all';
				} else if ( empty( $menuItems ) ) {
					$menuItems = array( JHTML::_( 'select.option',  '-1' ) );
					$template->pages = 'none';
				} else {
					$template->pages = null;
				}
			}
			$optionMenus	= JHTML::_( 'menu.linkoptions' );
			$lists['menus']	= JHTML::_( 'select.genericlist', $optionMenus, 'selected_menus[]', 'class="inputbox" size="23" multiple="multiple" style="padding: 6px; padding-bottom: 15px; width: 190px;"', 'value', 'text', $menuItems, 'selected_menus' );
			
			
			// Set Parameters Modal
			if ( ! $isNew ) {

			}
			
			// Clean Template Folder
			HelperjSeblod_helper::clean( @$template->name );
			
			// Set Sources Folder && Files List
			if ( ! $isNew && ! $doCopy )  {
				// Php Files
				if ( JFolder::exists( JPATH_SITE.DS.'templates'.DS.$template->name ) ) {
					$baseDir 	= JPATH_SITE.DS.'templates'.DS.$template->name;
					$phpFiles	= JFolder::files( $baseDir, '.php$', false, false);
				}
				// Css Files
				if ( JFolder::exists( JPATH_SITE.DS.'templates'.DS.$template->name.DS.'css' ) ) {
					$cssDir 	= JPATH_SITE.DS.'templates'.DS.$template->name.DS.'css';
					$cssFiles	= JFolder::files( $cssDir, '.css', false, false);
				}
				// Js Files
				if ( JFolder::exists( JPATH_SITE.DS.'templates'.DS.$template->name.DS.'js' ) ) {
					$jsDir 		= JPATH_SITE.DS.'templates'.DS.$template->name.DS.'js';
					$jsFiles	= JFolder::files( $jsDir, '.js', false, false);
				}
				// Xml Files
				$xml	= JPATH_SITE.DS.'templates'.DS.$template->name.DS.'templateDetails.xml';
				if ( JFile::exists( $xml ) ) {
					$xmlFile = 'templateDetails.xml';
				}
				$ini	= JPATH_SITE.DS.'templates'.DS.$template->name.DS.'params.ini';
				// Ini File
				if ( JFile::exists( $ini ) ) {
					$content = JFile::read( $ini );
					$params = new JParameter( $content, $xml, 'template' );
				}
			}
			
			$optType		=	array();
			$optType[] 		=	JHTML::_( 'select.option', '', JText::_( 'SELECT A TEMPLATE TYPE' ) );
			$optType[] 		=	JHTML::_( 'select.option', 0, JText::_( 'CONTENT' ) );
			$optType[] 		=	JHTML::_( 'select.option', 1, JText::_( 'FORM' ) );
			$optType[] 		=	JHTML::_( 'select.option', 2, JText::_( 'LIST' ) );	
			$optType[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
			$optType[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'ECOMMERCE' ) );
			$optType[] 		=	JHTML::_( 'select.option', 5, JText::_( 'GRID' ) );	
			$optType[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
			
			$selectType		=	( ! $isNew ) ? $template->type : '';
			$lists['type'] 	=	JHTML::_( 'select.genericlist', $optType, 'tpl_type', 'size="1" class="inputbox required required-enabled"', 'value', 'text', $selectType );
			
			$optMode		=	array();
			$optMode[] 		=	JHTML::_( 'select.option', 1, '<strong><font color="#666666">'.JText::_( 'AUTO' ).'</font></strong>' );
			$optMode[] 		=	JHTML::_( 'select.option', 0, '<strong><font color="#6CC634">'.JText::_( 'CUSTOM' ).'</font></strong>' );			
			$selectMode		=	( ! $isNew ) ? $template->mode : 0;
			$lists['mode'] 	=	JHTML::_( 'select.radiolist', $optMode, 'mode', 'size="1" class="inputbox"', 'value', 'text', $selectMode );
			
			// Positions
			if ( @$template->name ) {
				$locations		=	HelperjSeblod_Helper::getTemplateLocations( $template->name );
				$optLoc			=	array();
				$countLoc		=	sizeof( $locations );
				if ( $countLoc ) {
					foreach ( $locations as $loc ) {
						$optLoc[]	=	JHTML::_( 'select.option', $loc, $loc );
					}
				}
				$lists['loc'] 	=	JHTML::_( 'select.genericlist', $optLoc, 'locations[]', 'size="'.($countLoc + 5).'"class="inputbox" multiple="multiple" style="padding: 6px; width: 190px;"', 'value', 'text', '');
			}
			
			// Push Data to Template
			$this->assignRef( 'option', $option );
			$this->assignRef( 'controller', $controller );
			$this->assignRef( 'document',	$document );
			$this->assignRef( 'doCopy', $doCopy );
			$this->assignRef( 'template', $template );
			$this->assignRef( 'urlsItems', $urlsItems );
			$this->assignRef( 'baseDir', $baseDir );
			$this->assignRef( 'xmlFile', $xmlFile );
			$this->assignRef( 'phpFiles', $phpFiles );
			$this->assignRef( 'cssDir', $cssDir );
			$this->assignRef( 'cssFiles', $cssFiles );
			$this->assignRef( 'jsDir', $jsDir );
			$this->assignRef( 'jsFiles', $jsFiles );
			$this->assignRef( 'countLoc', $countLoc );
			$this->assignRef( 'params', $params );
			$this->assignRef( 'lists', $lists );
			$this->assignRef( 'modals', $modals );
			$this->assignRef( 'tooltips', $tooltips );
			
			$this->_displayToolbar( $isNew, $doCopy, $isAuth, @$template->id );
		
		}
		
		parent::display( $tpl );
	}
	
}
?>