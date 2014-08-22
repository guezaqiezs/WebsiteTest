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
 * Templates		Table Class
 **/
class TableTemplates extends JTable
{
	/**
	 * Vars
	 **/
	var $id = null;						//Primary Key

	var $title = null;					//Varchar (50)
	var $name = null;					//Varchar (50)
	var $category = null;				//Int
	var $type = null;					//Int
	var $mode = null;					//Int
		
	var $description = null;			//Text
	var $published = null;				//Tinyint (4)
	
	var $checked_out = null;			//Int (UNSIGNED)
	var $checked_out_time = null;		//Datetime

	/**
	 * Constructor
	 **/
	function TableTemplates( & $db ) {
		parent::__construct( '#__jseblod_cck_templates', 'id', $db );
	}
	
	/**
	 * Check whether Template in Hidden
	 **/
	function hidden() {
		$total	=	0;
		
		if ( ! $this->name ) {
			return 1;
		}
		$hidden	=	explode( ',', _ITEM_HIDDEN_TEMPLATE );
		if ( array_search( $this->name, $hidden ) ) {
		  $total = 1;
		}
		
		return $total;
	}
	
}
?>