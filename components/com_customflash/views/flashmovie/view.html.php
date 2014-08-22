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

jimport( 'joomla.application.component.view'); //Important to get menu parameters
class CustomFlashViewFlashMovie extends JView {

	function display($tpl = null)
	{
		global $mainframe;
                
		
		$params = &JComponentHelper::getParams( 'com_customflash' );
		
		$this->assignRef('params',$params);
		
				
        parent::display($tpl);
	}
	
}
?>
	