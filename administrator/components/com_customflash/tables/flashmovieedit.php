<?php
/**
 * CustomFlash Joomla! 1.5 Native Component
 * @version 1.2.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/




// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

class TableFlashMovieEdit extends JTable
{

	var $id = null;
	var $moviename = null;
	var $checkflashavailability = null;
	
	var $file = null;
  	var $width = null;
  	var $height = null;
	var $quality = null;
  	var $wmode = null;
  	var $bgcolor = null;
  	var $play = null;
  	var $scale = null;
	var $style = null;
	var $alternativehtml = null;
	var $alternativeimage = null;
	var $flashvars = null;
	var $menu = null;
	var $cssclass = null;
	var $paramlist = null;

	
	function TableFlashMovieEdit(& $db)
	{
		parent::__construct('#__customflash', 'id', $db);
	}

}

?>