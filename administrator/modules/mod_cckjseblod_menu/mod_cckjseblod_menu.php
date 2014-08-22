<?php
/*
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright  		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
*/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( dirname(__FILE__).DS.'helper.php' );

$hide	= JRequest::getInt('hidemainmenu');

JHTML::_( 'stylesheet', 'cckjseblod_menu.css', 'administrator/modules/mod_cckjseblod_menu/assets/css/' );
JHTML::_( 'stylesheet', 'icon.css', 'administrator/components/com_cckjseblod/assets/css/' );

JHTML::_( 'script', 'cckjseblod_menu.js', 'administrator/modules/mod_cckjseblod_menu/assets/js/', false );
JHTML::_( 'script', 'cckjseblod_index.js', 'administrator/modules/mod_cckjseblod_menu/assets/js/', false );

// Module Id
$moduleid	=	$module->id;

$mode			=	$params->get( 'mode', 2 );
$menutitle		=	$params->get( 'menutitle', 'CCK' );
$modenew		=	$params->get( 'modenew', 1 );
$modecat		=	$params->get( 'modecat', 1 );
$modesitemenu	=	$params->get( 'modesitemenu' );
$modesitemodule	=	$params->get( 'modesitemodule' );
$modeexternal	=	$params->get( 'modeexternal', 1 );
//
$addons					=	array();
$addons['ecommerce']	=	$params->get( 'addon_ecommerce', 0 );
$addons['webservice']	=	$params->get( 'addon_webservice', 0 );

if ( $hide ) {
	modCCKMenuHelper::buildDisabledMenu( $mode, $menutitle, $moduleid );
} else {
	$enable01	=	$params->get( 'enable01' );
	$enable02	=	$params->get( 'enable02' );
	$enable03	=	$params->get( 'enable03' );
	$enable04	=	$params->get( 'enable04' );
	$enable05	=	$params->get( 'enable05' );
	$free01		=	$params->get( 'free01' );
	$free02		=	$params->get( 'free02' );
	$free03		=	$params->get( 'free03' );
	$free04		=	$params->get( 'free04' );
	$free05		=	$params->get( 'free05' );
	$com		=	array();
	if ( $free01 ) {
		$com['free01']	=	$params->get( 'free01_title' ).'||'.$params->get( 'free01_url' ).'||'.$params->get( 'free01_icon' );
	}
	if ( $enable01 ) {
		$com['comp01']	=	$params->get( 'component01' );
	}
	if ( $free02 ) {
		$com['free02']	=	$params->get( 'free02_title' ).'||'.$params->get( 'free02_url' ).'||'.$params->get( 'free02_icon' );
	}
	if ( $enable02 ) {
		$com['comp02']	=	$params->get( 'component02' );
	}
	if ( $free03 ) {
		$com['free03']	=	$params->get( 'free03_title' ).'||'.$params->get( 'free03_url' ).'||'.$params->get( 'free03_icon' );
	}
	if ( $enable03 ) {
		$com['comp03']	=	$params->get( 'component03' );
	}
	if ( $free04 ) {
		$com['free04']	=	$params->get( 'free04_title' ).'||'.$params->get( 'free04_url' ).'||'.$params->get( 'free04_icon' );
	}
	if ( $enable04 ) {
		$com['comp04']	=	$params->get( 'component04' );
	}
	if ( $free05 ) {
		$com['free05']	=	$params->get( 'free05_title' ).'||'.$params->get( 'free05_url' ).'||'.$params->get( 'free05_icon' );
	}
	if ( $enable05 ) {
		$com['comp05']	=	$params->get( 'component05' );
	}
	modCCKMenuHelper::buildMenu( $mode, $menutitle, $moduleid, $modenew, $modecat, $modesitemenu, $modesitemodule, $modeexternal, $addons, $com );
}

// TK added - call the init-js with the correct menuname
?>
<script type="text/javascript" charset="utf-8">
	var jseblodmenuid = '<?php echo $moduleid; ?>';
	cckjs_menu_init('cckjseblod_menu_jseblod'+jseblodmenuid);
/* ]]> */
</script>
