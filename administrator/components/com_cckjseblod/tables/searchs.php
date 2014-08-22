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
 * Content Searchs	Table Class
 **/
class TableSearchs extends JTable
{
	/**
	 * Vars
	 **/
	var $id = null;						//Primary Key

	var $title = null;					//Varchar (50)
	var $name = null;					//Varchar (50)
	var $category = null;				//Int
	var $content = null;				//Int
	var $searchtemplate = null;			//Int
	var $contenttemplate = null;		//Int
	
	var $description = null;			//Text
	var $published = null;				//Tinyint (4)
	
	var $checked_out = null;			//Int (UNSIGNED)
	var $checked_out_time = null;		//Datetime

	/**
	 * Constructor
	 **/
	function TableSearchs( & $db ) {
		parent::__construct( '#__jseblod_cck_searchs', 'id', $db );
	}

	/**
	 * Overloaded Check Function
	 **/	
	function check() {
		if( empty( $this->name ) ) {
			$this->name	=	$this->title;
			$this->name =	HelperjSeblod_Helper::stringURLSafe( $this->name );
			if( trim( str_replace( '_', '', $this->name ) ) == '' ) {
				$datenow	=&	JFactory::getDate();
				$this->name =	$datenow->toFormat( "%Y_%m_%d_%H_%M_%S" );
			}
		}
		
		return true;
	}
}
?>