/**
* @version		$Id: index.js 10702 2008-08-21 09:31:31Z eddieajau $
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * @version    	1.2.0
 * @package		jSeblod Extended Admin Menu
 **/

// TK made a function to allow different menus
function cckjs_menu_init(cckjs_element) {
	try {
	  //For IE6 - Background flicker fix
	  document.execCommand('BackgroundImageCache', false, true);
	} catch(e) {}

	document.cckjseblod_menu = null
	window.addEvent('load', function(){
		element = $(cckjs_element)
		if(!element.hasClass('disabled')) {
			var menu = new JCCKjSeblod_Menu(element)
			document.cckjseblod_menu = menu
		}
	});
}