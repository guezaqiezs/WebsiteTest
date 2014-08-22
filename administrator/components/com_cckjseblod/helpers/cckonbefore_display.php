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
 * On Before Display
 **/
// Initialize Parameters
$random		=	rand( 1, 100000 );
$cache 		=	false;
$file 		=	'index_jseblod'.$random;

// Create File to Render from index.php
$fileToCopy 	=	$path.DS.$template.DS.'index.php';
$fileToRender	=	$path.DS.$template.DS.$file.'.php';
if ( JFile::exists( $fileToCopy ) ) {
	JFile::copy( $fileToCopy, $fileToRender );
}

$rparams	=	array(
	'template' 	=> $template,
	'file'		=> $file.'.php',
	'directory'	=> $path,
);
	
// Create New HTML Document
$doc	=&	JDocument::getInstance( 'html' );
?>