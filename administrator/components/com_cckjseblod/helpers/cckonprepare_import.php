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
jimport( 'joomla.installer.helper' );
jimport( 'joomla.utilities.simplexml' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'jseblod_share.php' );

$config		=&	JFactory::getConfig();
$tempFolder	=	$config->getValue( 'config.tmp_path' );

$mode		=	JRequest::getInt( 'import_pack' );	

$installPack 		=	JRequest::getVar( 'install_package', null, 'files', 'array' );
$fileName 	=	JFile::makeSafe( $installPack['name'] );

$src	=	$installPack['tmp_name'];
$dest	=	$tempFolder.DS.$fileName;
if ( strtolower( JFile::getExt( $fileName ) ) != 'zip' ) {
	return false;
}
if ( ! JFile::upload( $src, $dest ) ) {
	return false;
}
if ( ! extension_loaded( 'zlib' ) ) {
	JError::raiseWarning( 'SOME_ERROR_CODE', JText::_( 'WARNINSTALLZLIB' ) );
	return false;
}

$fileUnpack	=	HelperjSeblod_Share::unpack( $dest );
if ( ! $fileUnpack ) {
	return false;
}

$files = JFolder::files($fileUnpack['extractdir'], '\.xml$', 1, true);
?>