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
 * Content Items	Table Class
 **/
class TableItems extends JTable
{
	/**
	 * Vars
	 **/
	var $id = null;					//Primary Key

	var $title = null;				//Varchar (50)
	var $name = null;				//Varchar (50)
	var $category = null;			//Int
	var $type = null;				//Int
	
	var $description = null;		//Text

	var $light = null;				//Tinyint (4)
	var $label = null;				//Varchar (50)
	var $selectlabel = null;		//Varchar (50)
	var $display = null;			//Int
	var $required = null;			//Tinyint (4)
	var $validation = null;			//Varchar (50)
	var $defaultvalue = null;		//Text
	var $options = null;			//Text
	var $maxlength = null;			//Int
	var $size = null;				//Int
	var $cols = null;				//Int
	var $rows = null;				//Int
	var $ordering = null;			//Int
	var $divider = null;			//Varchar (50)
	var $bool = null;				//Tinyint (4)
	var $extra = null;				//Varchar (50)
	var $location = null;			//Varchar (250)
	var $content = null;			//Varchar (50)
	var $extended = null;			//Varchar (50)
	var $style = null;				//Varchar (250)
	var $message = null;			//Text
	var $message2 = null;			//Text
	var $format = null;				//Varchar (50)
	var $mailto = null;				//Text
	var $cc = null;					//Text
	var $bcc = null;				//Text
	var $elemxtd = null;			//Text
	var $bool2 = null;				//Tinyint (4)
	var $displayfield = null;		//Tinyint (4)
	var $displayvalue = null;		//Tinyint (4)
	var $width = null;				//Int (4)
	var $height = null;				//Int (4)
	var $codebefore = null;			//Int (4)
	var $codeafter = null;			//Int (4)
	var $importer = null;			//Tinyint (4)
	var $substitute = null;			//Tinyint (4)
	var $indexed = null;			//Tinyint (4)
	var $indexedkey = null;			//Tinyint (4)
	var $indexedxtd = null;		//Varchar (50)
	var $bool3 = null;				//Tinyint (4)
	var $bool4 = null;				//Tinyint (4)
	var $bool5 = null;				//Tinyint (4)
	var $bool6 = null;				//Tinyint (4)
	var $bool7 = null;				//Tinyint (4)
	var $bool8 = null;				//Tinyint (4)
	var $url = null;				//Varchar (250)
	var $toadmin = null;			//Varchar (50)
	var $css = null;				//Varchar (250)
	var $uACL = null;				//Tinyint (4)
	var $gACL = null;				//Tinyint (4)
	var $uEACL = null;				//Tinyint (4)
	var $gEACL = null;				//Tinyint (4)
	var $stylextd = null;			//Varchar (50)
	var $boolxtd = null;			//Tinyint (4)
	var $beforesave = null;			//Text
	var $options2 = null;			//Text

	var $checked_out = null;		//Int (UNSIGNED)
	var $checked_out_time = null;	//Datetime
	
	/**
	 * Constructor
	 **/
	function TableItems( & $db ) {
		parent::__construct( '#__jseblod_cck_items', 'id', $db );
	}
	
	/**
	 * Check whether Item in Reserved
	 **/
	function reserved() {
		$total	=	0;
		
		if ( ! $this->name ) {
			return 1;
		}
		$query	= ' SELECT COUNT(s.id)'
			    . ' FROM #__jseblod_cck_items_reserved AS s'
			    . ' WHERE s.name = "'.$this->name.'"'
			    ;
    	$this->_db->setQuery( $query );
  		$total	=	$this->_db->loadResult();
		
		return $total;
	}
}
?>