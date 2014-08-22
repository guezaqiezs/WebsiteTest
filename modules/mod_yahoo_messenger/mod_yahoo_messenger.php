<?php
/**
* @version		$Id: mod_yahoo_messenger.php 2010-07-15 02:42:00 GMT+1 Nigeria $
* @package		Joomla
* @copyright	Copyright (C) 2010 www.lekeojikutu.com. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once

echo '<a href="ymsgr:sendim?'.$params->get( 'name' ).'"><img border="0" alt="" src="http://opi.yahoo.com/online?u='.$params->get( 'name' ).'&amp;m=g&amp;t=2" />  </a>';
