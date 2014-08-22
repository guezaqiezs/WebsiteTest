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

// On Prepare Content Plugin
for ( $i = 0; $i < $textObj->nPlugins; $i++ ) {
	$pluginName		=	$textObj->batchPlugins[$i]['name'];
	$pluginValue	=	$items[$pluginName]->value;
	$pluginField	=	'::'.$pluginName.'::'.$pluginValue.'::/'.$pluginName.'::';

	$search		=	'#\#([a-zA-Z0-9_]*)\##U';
	preg_match_all( $search, $pluginValue, $matches );
	if ( sizeof ( $matches[1] ) ) {
		foreach( $matches[1] as $match ) {
			$pluginValue	=	( trim(@$items[$match]->value) ) ? str_replace( '#'.$match.'#', $items[$match]->value, $pluginValue ) : str_replace( '#'.$match.'#', '', $pluginValue );
		}
	}
	
	$textObj->text	=	str_replace( $pluginField, '::'.$pluginName.'::'.$pluginValue.'::/'.$pluginName.'::', $textObj->text );
}
?>