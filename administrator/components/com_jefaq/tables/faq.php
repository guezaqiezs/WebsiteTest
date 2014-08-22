<?php
/**
 * jeFAQ package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class Tablefaq extends JTable
{
  var $id  		 = null;
  var $questions = null;
  var $answers   = null;
  var $ordering  = null;
  var $state     = null;

	function Tablefaq( &$db )
	{
		parent::__construct( '#__je_faq', 'id', $db );

	}
}
?>