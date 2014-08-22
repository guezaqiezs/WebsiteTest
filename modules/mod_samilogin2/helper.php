<?php
/**
* @version		1.0.0
* @package		Sami Login
* @copyright	Copyright (C) 2011 samielkady.com Open Source Matters. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modSamiLogin2Helper{
	function getReturnURL( $type){
		// stay on the same page
		$uri = JFactory::getURI();
		$url = $uri->toString(array('path', 'query', 'fragment'));
		return base64_encode($url);
	}
	function getType(){
		$user = & JFactory::getUser();
		return (!$user->get('guest')) ? 'logout' : 'login';
	}
}
