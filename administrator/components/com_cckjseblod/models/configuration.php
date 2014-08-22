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

jimport( 'joomla.application.component.model' );

/**
 * Configuration	Model Class
 **/
class CCKjSeblodModelConfiguration extends JModel
{
	/**
	 * Vars
	 **/
	var $_id		= null;
	var $_data		= null;
	var $_limits	= null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
		
		$id = 1;
		$this->setValues( $id );
	}

	/**
	 * Set Values
	 **/
	function setValues( $id )
	{
		// Set Values
		$this->_id		= $id;
		$this->_data	= null;
		$this->_limits	= null;
		$this->_templates	= null;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getData()
	{
		if ( empty( $this->_data ) ) {
			$row =& $this->getTable( 'configuration' );
			
			if ( $this->_id ) {
				$row->load( $this->_id );
			}
			$this->_data =& $row;
		}
		if ( ! $this->_data ) {
			$this->_data = new stdClass();
			$this->_data->id = 1;
			$this->_data->view_access_level = 23;
			$this->_data->edit_access_level = 24;
			$this->_data->opening = '::';
			$this->_data->closing = '::';
			$this->_data->modal_width = 800;
			$this->_data->modal_height = 500;
		}
		
		return $this->_data;
	}

	/**
	 * Get Data from Database
	 **/
	function &getTemplates()
	{			
		$query = 'SELECT s.name'
			   . ' FROM #__jseblod_cck_templates AS s'
			   . ' ORDER BY s.name asc'
			   ;
		$this->_db->setQuery( $query );
		$this->_templates = $this->_db->loadResultArray();
		
		return $this->_templates;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getLimits( $limit )
	{			
		$query = 'SELECT s.id AS value, s.'.$limit.' AS text'
			   . ' FROM #__jseblod_cck_items_limits AS s'
			   . ' ORDER BY s.id asc'
			   ;
		$this->_db->setQuery( $query );
		$this->_limits = $this->_db->loadObjectList();
		
		return $this->_limits;
	}
	
	/**
	 * Store Record(s)
	 **/
	function store()
	{
		$row =& $this->getTable( 'configuration' );
		$data = JRequest::get( 'post' );
		
		/**
		 * Extra POST Pre-Store
		 **/
		
		if ( isset( $data['selected_fullscreen'] ) ) {
			$nFullscrean = count( $data['selected_fullscreen'] );
			if ( $nFullscrean > 1 ) {
				$catFullscreen = implode( ',', $data['selected_fullscreen'] );
				$data['categories_fullscreen'] = $catFullscreen;
			} else {
				$data['categories_fullscreen'] = $data['selected_fullscreen'][0];
			}
		} else {
			$data['categories_fullscreen'] = '';
		}
		
		if ( isset( $data['selected_hidden'] ) ) {
			$nHidden = count( $data['selected_hidden'] );
			if ( $nHidden > 1 ) {
				$templateHidden = implode( ',', $data['selected_hidden'] );
				$data['template_hidden'] = $templateHidden;
			} else {
				$data['template_hidden'] = $data['selected_hidden'][0];
			}
		} else {
			$data['template_hidden'] = '';
		}
		
		/**
		 * Store !!
		 **/
		 
		// Bind Form Fields to Table
		if ( ! $row->bind( $data ) ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Make Sure Record is Valid
		if ( ! $row->check() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Store Web Link Table to Database
		if ( ! $row->store() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		return $row->id;
	}
	
	/**
	 * Reset CCK
	 **/
	function reset()
	{
		$config	=&	$this->getData();
		$section	=	( @$config->jseblod_section ) ? $config->jseblod_section : 0;
		$autocat	=	( @$config->jseblod_category_auto ) ? $config->jseblod_category_auto : 0;
		$defaultcat	=	( @$config->jseblod_category_default ) ? $config->jseblod_category_default : 0;
		$defaultregcat	=	( @$config->jseblod_category_default_reg ) ? $config->jseblod_category_default_reg : 0;
		
		if ( JFile::exists( JPATH_ADMINISTRATOR.DS.'components/com_cckjseblod/install'.DS.'reset.cckjseblod.sql' ) ) {
			$query = JFile::read( JPATH_ADMINISTRATOR.DS.'components/com_cckjseblod/install'.DS.'reset.cckjseblod.sql' );
		}
		
		$this->_db->setQuery( $query );
		if ( ! $this->_db->queryBatch() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		$query	= 'UPDATE #__jseblod_cck_configuration'
			  	. ' SET jseblod_section ='.$section
			  	. ', jseblod_category_auto ='.$autocat
			  	. ', jseblod_category_default ='.$defaultcat
			  	. ', jseblod_category_default_reg ='.$defaultregcat
				. ' WHERE id = 1'
				;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		$query	= 'UPDATE #__jseblod_cck_items'
				. ' SET location = "'.$defaultcat.'"'
				. ' WHERE id = 1'
				;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		$query	= 'UPDATE #__jseblod_cck_items'
				. ' SET location = "'.$defaultregcat.'"'
				. ' WHERE id = 121'
				;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		return true;
	}

	/**
	 * Update Version
	 **/
	function version_update()
	{
		jimport( 'joomla.filesystem.archive' );
		jimport( 'joomla.utilities.simplexml' );
		jimport( 'joomla.installer.helper' );
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'jseblod_share.php' );
		
	    $installVersion		=	JRequest::getVar( 'update_version', null, 'files', 'array' );
		
		$config		=&	JFactory::getConfig();
    	$tempFolder	=	$config->getValue( 'config.tmp_path' );
   
   		$fileName 	=	JFile::makeSafe( $installVersion['name'] );
    
    	$src	=	$installVersion['tmp_name'];
    	$dest	=	$tempFolder.DS.$fileName;

    	if ( strtolower( JFile::getExt( $fileName ) ) != 'zip' ) {
    		return false;
    	}
    	if ( ! JFile::upload( $src, $dest ) ) {
    		return false;
    	}
    	if ( ! extension_loaded( 'zlib' ) ) {
    		JError::raiseWarning( 'SOME_ERROR_CODE', JText::_( 'WARNINSTALLZLIB' ) );
    		return false;
    	}

		$fileUnpack	=	HelperjSeblod_Share::unpack( $dest, 'component' );
	    if ( ! $fileUnpack ) {
    		return false;
    	}		
		if ( JFolder::exists( $fileUnpack['extractdir'] ) ) {
			if ( JFile::exists( $fileUnpack['packagefile'] ) ) {
				JFile::delete( $fileUnpack['packagefile'] );
			}
		}
		if ( $fileUnpack['type'] != "component" ) {
			return false;
		}
		////C_Site
		if ( JFolder::exists( $fileUnpack['extractdir'].DS.'site' ) ) {
			JFolder::copy( $fileUnpack['extractdir'].DS.'site', JPATH_SITE.DS.'components'.DS.'com_cckjseblod', '', true );
		} else {
			return false;
		}
		if ( JFile::exists( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'languages'.DS.'en-GB.com_cckjseblod.ini' ) ) {
			if ( JFile::exists( JPATH_SITE.DS.'language'.DS.'en-GB'.DS.'en-GB.com_cckjseblod.ini' ) ) {
				JFile::delete( JPATH_SITE.DS.'language'.DS.'en-GB'.DS.'en-GB.com_cckjseblod.ini' );
			}
			JFile::copy( JPATH_SITE.DS.'components'.DS.'com_cckjseblod'.DS.'languages'.DS.'en-GB.com_cckjseblod.ini',
					   JPATH_SITE.DS.'language'.DS.'en-GB'.DS.'en-GB.com_cckjseblod.ini' );
	   	}
		////C_Xml
		if ( JFile::exists( $fileUnpack['extractdir'].DS.'manifest.xml' ) ) {
			JFile::delete( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'manifest.xml' );
			JFile::move( $fileUnpack['extractdir'].DS.'manifest.xml',
						 JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'manifest.xml' );
		} else {
			return false;
		}
		////C_Admin
		if ( JFolder::exists( $fileUnpack['extractdir'].DS.'admin' ) ) {
			JFolder::copy( $fileUnpack['extractdir'].DS.'admin', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod', '', true );
		} else {
			return false;
		}
		if ( JFile::exists( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'languages'.DS.'en-GB.com_cckjseblod.ini' ) ) {
			if ( JFile::exists( JPATH_ADMINISTRATOR.DS.'language'.DS.'en-GB'.DS.'en-GB.com_cckjseblod.ini' ) ) {
				JFile::delete( JPATH_ADMINISTRATOR.DS.'language'.DS.'en-GB'.DS.'en-GB.com_cckjseblod.ini' );
			}
			JFile::copy( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'languages'.DS.'en-GB.com_cckjseblod.ini',
					   JPATH_ADMINISTRATOR.DS.'language'.DS.'en-GB'.DS.'en-GB.com_cckjseblod.ini' );
	   	}
		////
		include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'install'.DS.'install2.cckjseblod.php' );
		//
		include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'install'.DS.'update.cckjseblod.php' );
		//
		if ( JFolder::exists( $fileUnpack['extractdir'] ) ) {
			JFolder::delete( $fileUnpack['extractdir'] );
		}

		return true;
	}
	
	/**
	 * Reset CCK
	 **/
	function update_quick_category( $title, $color )
	{
		if ( $title ) {
			$query	= 'UPDATE #__jseblod_cck_templates_categories AS s'
					. ' SET s.title="'.$title.'"'
					. ' WHERE s.name="quick_category"'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			$query	= 'UPDATE #__jseblod_cck_types_categories AS s'
					. ' SET s.title="'.$title.'"'
					. ' WHERE s.name="quick_category"'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			$query	= 'UPDATE #__jseblod_cck_items_categories AS s'
					. ' SET s.title="'.$title.'"'
					. ' WHERE s.name="quick_category"'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		if ( $color ) {
			$query	= 'UPDATE #__jseblod_cck_templates_categories AS s'
					. ' SET s.color="'.$color.'"'
					. ' WHERE s.name="quick_category"'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			$query	= 'UPDATE #__jseblod_cck_types_categories AS s'
					. ' SET s.color="'.$color.'"'
					. ' WHERE s.name="quick_category"'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			$query	= 'UPDATE #__jseblod_cck_items_categories AS s'
					. ' SET s.color="'.$color.'"'
					. ' WHERE s.name="quick_category"'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return true;
	}
}
?>