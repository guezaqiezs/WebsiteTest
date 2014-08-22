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

// Component Helper
jimport('joomla.application.component.helper');

/**
 * Helper
 **/
class CCKjSeblodHelperRoute
{
	function isSEF()
	{
		global $mainframe;
		
		$sef	=	$mainframe->getCfg( 'sef' );
		
		if ( $sef ) {
			return true;
		}
		
		return false;
	}

	/**
	 * Get Article Route
	 **/
	function getArticleRoute( $id, $catid, $sef, $option, $itemId = 0 )
	{
		$link	=	'index.php?'.$option.'&view=article';
		
		if ( ! $sef ) {
			$link	.=	'&catid='.$catid;
		}
		if ( $id ) {
			$link	.=	'&id='.$id; 
		}
		if ( $itemId ) {
			$link	.=	'&Itemid='.$itemId;
		}
		
		return $link;
	}
}
?>
