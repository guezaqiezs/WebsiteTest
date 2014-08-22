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
 * On After Display
 **/
$data	=	$doc->render( $cache, $rparams );

// Flush Items && Values
foreach( $doc as $key => $value ) {
	$doc->key	=	null;
	$doc->value	=	null;
}

// Delete File To Render
if ( JFile::exists( $fileToRender ) ) {
	JFile::delete( $fileToRender );
}
?>