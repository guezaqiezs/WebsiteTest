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

/**
 * Content Types	Table Class
 **/
class TablePacks extends JTable
{
	/**
	 * Vars
	 **/
	var $id = null;					//Int

	var $name = null;				//Varchar (50)
	var $type = null;				//Varchar (50)
	var $mode = null;				//Tinyint (4)

	/**
	 * Constructor
	 **/
	function TablePacks( & $db ) {
		parent::__construct( '#__jseblod_cck_packs', 'id', $db );
	}
	
}
?>