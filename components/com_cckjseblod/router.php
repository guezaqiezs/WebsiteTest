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
 * Router
 **/
function CCKjSeblodBuildRoute( &$query )
{
	$segments	=	array();
	
	// Prevent Router on Task
	if ( @$query['task'] == 'download' ) {
		return $segments;
	}
	// Prevent Router on View
	if ( @$query['view'] == 'type' ) {
		return $segments;
	}
	
	if ( isset( $query['catid'] ) ) {
		$segments[]	=	$query['catid'];
		unset( $query['catid'] );
	}
	if( isset( $query['id'] ) ) {
		$segments[]	=	$query['id'];
		unset( $query['id'] );
	}
	unset( $query['view'] );
	
	return $segments;
}

function CCKjSeblodParseRoute( $segments )
{
	$vars	=	array();
	$count	=	count( $segments );
//	$menus	=	&JSite::getMenu();
//	$menu	=	$menus->getActive();
	
	switch ( $count ) {
		case 2:
			$vars['option']	=	'com_content';
			$vars['view']	=	'article';
			$vars['catid']	=	$segments[0];
			$vars['id']		=	$segments[1];
//			$vars['Itemid'] =	$menu->id;	
			break;
		case 1:
			$vars['option']	=	'com_content';
			$vars['view']	=	'article';
			$vars['id']		=	$segments[0];
//			$vars['Itemid'] =	$menu->id;
			break;
		default:
			break;
	}
	
	return $vars;
}
?>