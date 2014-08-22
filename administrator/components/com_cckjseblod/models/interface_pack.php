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

jimport( 'joomla.application.component.model' );

/**
 * Interface_Pack		Model Class
 **/
class CCKjSeblodModelInterface_Pack extends JModel
{
	/**
	 * Vars
	 **/
	
	/**
	 * Constructor | Get ID from Request
	 **/
	function __construct()
	{
		
		parent::__construct();
	}
	
	function importXml()
	{
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'cckshare_import.php' );

	    $installPack 	=	JRequest::getVar( 'import_pack', null, 'files', 'array' );
		$mode			=	JRequest::getInt( 'import_mode' );
    $selection			=	JRequest::getInt( 'import_selection' );
    
		if ( $res = CCKjSeblodShare_Import::importContent_Pack( $installPack, $mode, $selection ) ) {
			return true; //return $res;
		}
		
		return false;
	}
	
}
?>