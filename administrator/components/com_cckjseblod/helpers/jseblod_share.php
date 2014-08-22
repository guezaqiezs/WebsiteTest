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
 * HelperjSeblod	Share Class
 **/
class HelperjSeblod_Share
{   
    /**
	 * Unpack Archive
	 */
   	function unpack( $p_filename, $ziptype = null )
	{
		jimport('joomla.filesystem.archive');
		
		// Path to the archive
		$archivename = $p_filename;

		// Clean the paths to use for archive extraction
		$archivename = JPath::clean($p_filename);
		
		// do the unpacking of the archive
		$ext = JFile::getExt(strtolower($archivename));
		$pathdir	=	$archivename;
		$pathdir = str_replace( ".".$ext, "",$pathdir);
		JFolder::create($pathdir);
        JFile::write($pathdir.DS."index.html", "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>");
		
		require_once( 'pclzip'.DS.'pclzip.lib.php' );
		$archive = new PclZip($archivename);
		if ( $archive->extract(PCLZIP_OPT_PATH, $pathdir) == 0 ) {
			return false;	die("Error : ".$archive->errorInfo(true));
  		}
		
		/*
		 * Lets set the extraction directory and package file in the result array so we can
		 * cleanup everything properly later on.
		 */
		$retval['extractdir'] = $pathdir;
		$retval['packagefile'] = $archivename;

		/*
		 * Try to find the correct install directory.  In case the package is inside a
		 * subdirectory detect this and set the install directory to the correct path.
		 *
		 * List all the items in the installation directory.  If there is only one, and
		 * it is a folder, then we will set that folder to be the installation folder.
		 */
		$dirList = array_merge(JFolder::files($pathdir, ''), JFolder::folders($pathdir, ''));

		if (count($dirList) == 1)
		{
			if (JFolder::exists($extractdir.DS.$dirList[0]))
			{
				$extractdir = JPath::clean($extractdir.DS.$dirList[0]);
			}
		}

		/*
		 * We have found the install directory so lets set it and then move on
		 * to detecting the extension type.
		 */
		$retval['dir'] = $pathdir;
		
		/*
		 * Get the extension type and return the directory/type array on success or
		 * false on fail.
		 */
		if ( $ziptype ) {
			if ( $retval['type'] = HelperjSeblod_Share::joomladetectType( $pathdir ) ) {
				return $retval;
			} else {
				return false;
			}			
		}
		else {
			if ( $retval['type'] = HelperjSeblod_Share::detectType( $pathdir ) ) {
				return $retval;
			} else {
				return false;
			}
		}
	}
   
   	/**
	 * Detect Content: Template, Types or Items ?
	 */
	function detectType($p_dir)
	{
		// Search the install dir for an xml file
		$files = JFolder::files($p_dir, '\.xml$', 1, true);

		if ( $n = count($files) > 0)
		{
			$i = 0;
			foreach ($files as $file)
			{
				$xmlDoc	=	new JSimpleXML;
				if ( ! $xmlDoc->loadFile( $file ) ) {
					return false;
				}

				$root =& $xmlDoc->document;
				
				if (!is_object($root) || ($root->name() != "cckjseblod"))
				{
					unset($xmlDoc);
					continue;
				}
				
				$type = $root->attributes('type');
				if ( $i == 0 ) {
					$base = $type;
				} else {
					if ( $type != $base ) {
						return false;
					}
				}
				// Free up memory from DOMIT parser
				$i++;
				if ( $i == $n ) {
					unset ($xmlDoc);
					return $type;
				}
			}
			
			JError::raiseWarning(1, JText::_('ERRORNOTFINDJOOMLAXMLSETUPFILE'));
			// Free up memory from DOMIT parser
			unset ($xmlDoc);
			return false;
		} else
		{
			JError::raiseWarning(1, JText::_('ERRORNOTFINDXMLSETUPFILE'));
			return false;
		}
	}
	function joomladetectType($p_dir)
	{
		// Search the install dir for an xml file
		$files = JFolder::files($p_dir, '\.xml$', 0, true);

		if ( $n = count($files) > 0)
		{
			$i = 0;
			foreach ($files as $file)
			{
				$xmlDoc	=	new JSimpleXML;
				if ( ! $xmlDoc->loadFile( $file ) ) {
					return false;
				}

				$root =& $xmlDoc->document;
				
				if (!is_object($root) || ($root->name() != "install"))
				{
					unset($xmlDoc);
					continue;
				}
				
				$type = $root->attributes('type');
				if ( $i == 0 ) {
					$base = $type;
				} else {
					if ( $type != $base ) {
						return false;
					}
				}
				// Free up memory from DOMIT parser
				$i++;
				if ( $i == $n ) {
					unset ($xmlDoc);
					return $type;
				}
			}
			
			JError::raiseWarning(1, JText::_('ERRORNOTFINDJOOMLAXMLSETUPFILE'));
			// Free up memory from DOMIT parser
			unset ($xmlDoc);
			return false;
		} else
		{
			JError::raiseWarning(1, JText::_('ERRORNOTFINDXMLSETUPFILE'));
			return false;
		}
	}
	
	/**
	 * Detect Content: Tmpl, Type or Item ?
	 **/
	function getType( $fileName )
	{
		$type	=	substr( $fileName, 0, 4 );
		
		if ( ! ( $type == 'tmpl' || $type == 'type' ) ) {
			$type	=	substr( $fileName, 0, 5 );
		}
		return ( $type );
	}
	
	
	
}
?>