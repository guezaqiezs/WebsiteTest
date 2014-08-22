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
jimport('joomla.application.component.controller');

class jefaqControllerFaq extends JController
{
	function __construct( $config = array() )
	{
		parent::__construct( $config );
	}

	function display()
	{
		JRequest::setVar( 'view', 'faq');
		parent::display();
	}

	function details()
	{
		JRequest::setVar( 'view', 'faq');
		JRequest::setVar( 'layout', 'details'  );
		parent::display();
	}
}
?>