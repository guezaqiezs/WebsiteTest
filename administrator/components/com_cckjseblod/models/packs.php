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
 * Packs		Model Class
 **/
class CCKjSeblodModelPacks extends JModel
{
	/**
	 * Vars
	 **/
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

	}

	/**
	 * Get Data from Database
	 **/
	function getData()
	{
		if ( empty( $this->_data ) )
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query );
		}
		
		return $this->_data;
	}

	/**
	 * Return Database Query
	 **/
	function _buildQuery()
	{
		global $mainframe;
		
		$query = 'SELECT s.*'
				.' FROM #__jseblod_cck_packs AS s'
				.' ORDER BY s.type DESC'
				;
			
		 return $query;
	}

	/**
	 * Empty Pack
	 **/
	function remove()
	{
		$mode	=	JRequest::getWord( 'remove_mode' );
		if ( $mode != '' ) {
			$where	=	' WHERE type = "'.$mode.'"';
		}
		
		$query  = 'DELETE FROM #__jseblod_cck_packs'
				. $where;
			
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		 return true;
	}
	
	function importXml()
	{ 
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'cckshare_import.php' );

	    $installPack 	=	JRequest::getVar( 'import_pack', null, 'files', 'array' );
		$mode			=	JRequest::getInt( 'import_mode' );
		$selection		=	JRequest::getInt( 'import_selection' );
		
		if ( $res = CCKjSeblodShare_Import::importContent_Pack( $installPack, $mode, $selection ) ) {
			return true; //return $res;
		}
		
		return false;
	}

	function exportXml()
	{
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'cckshare_export.php' );
		
		$fileName	=	JRequest::getVar( 'name_package' );
		$packElems	=	$this->getData();
		
		if ( count( $packElems ) )
		{
			if ( $file	=	CCKjSeblodShare_Export::exportContent_Pack( $packElems, $fileName ) ) {
				if ( _EXPORT_EMPTY_PACK ) {
					$this->remove();
				}
				return $file;
			}
			
			return false;
		}
		
		return false;
	}

}
?>