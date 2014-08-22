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
class TableUsers extends JTable
{
	/**
	 * Vars
	 **/
	var $contentid 		=	null;			//Primary Key
	var $userid 		=	null;			//Int
	var $type			=	null;			//Varchar (50)
	var $registration	=	null;			//Tinyint (4)
	var $state			=	null;			//Tinyint (4)

	/**
	 * Constructor
	 **/
	function TableUsers( & $db ) {
		parent::__construct( '#__jseblod_cck_users', 'id', $db );
	}
	
	function setType( $id = null, $type )
	{
		if ( $id ) {
			$query = 'UPDATE '. $this->_tbl
			. ' SET type = "'.$type.'"'
			. ' WHERE contentid = '.(int)$id
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return true;
	}
}
?>