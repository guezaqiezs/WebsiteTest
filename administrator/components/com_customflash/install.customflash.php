<?php
/**
 * CustomFlash Joomla! 1.5 Native Component
 * @version 1.2.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

function com_install()
{
	jimport('joomla.filesystem.file');

	$filestodelete=array();

	//Module to update
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_customflash'.DS.'index.html';
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_customflash'.DS.'mod_customflash.php';
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_customflash'.DS.'mod_customflash.xml';
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_customflash';
	
	
	//Plugin to update
	$filestodelete[]=JPATH_SITE.DS.'plugins'.DS.'content'.DS.'customflash.php';
	$filestodelete[]=JPATH_SITE.DS.'plugins'.DS.'content'.DS.'customflash.xml';
	$filestodelete[]=JPATH_SITE.DS.'plugins'.DS.'content'.DS.'customflash';
	
	
	
	foreach($filestodelete as $file)
	{
		if(file_exists($file))
		{
			if(is_dir($file))
				rmdir($file);
				
			else
				unlink($file);
		}
		
	}	
	
		
	rename(JPATH_SITE.DS.'components'.DS.'com_customflash'.DS.'module',JPATH_SITE.DS.'modules'.DS.'mod_customflash');
	rename(JPATH_SITE.DS.'components'.DS.'com_customflash'.DS.'plugin'.DS.'customflash.php',JPATH_SITE.DS.'plugins'.DS.'content'.DS.'customflash.php');
	rename(JPATH_SITE.DS.'components'.DS.'com_customflash'.DS.'plugin'.DS.'customflash.xml',JPATH_SITE.DS.'plugins'.DS.'content'.DS.'customflash.xml');
	
	

	rmdir(JPATH_SITE.DS.'components'.DS.'com_customflash'.DS.'plugin');
	


	if (file_exists(JPATH_SITE.DS."components".DS."com_customflash".DS."customflash.php"))
       	{


		echo '<h1>Custom Flash 1.2.1 installed succesfully</h1>
		<p>For more info go to Components/Custom Flash/Documentation.</p>
		
		
		<div style="text-align:right;"><a href="http://www.designcompasscorp.com/index.php?option=com_content&view=article&id=508&Itemid=709" target="_blank"><img src="../components/com_customflash/images/compasslogo.png" border=0></a></div>';
	}
	else
	{
		echo '<font color="red">Sorry, something went wrong while installing Custom Flash your web site</font>';
	}
	
	$db	= & JFactory::getDBO();
	//Add plugin
	$query = 'SELECT count(*) FROM #__plugins WHERE `element`="customflash"';
	$db->setQuery( $query );
	$total_rows = $db->loadResult();
	
	if($total_rows==0)
	{
		$query ='INSERT `#__plugins` SET `name`="Content - Custom Flash", `element`="customflash", `folder`="content", `published`=1';
		$db->setQuery( $query );
		if (!$db->query())    die( $db->stderr());
	}


	function AddColumnIfNotExist($tablename, $columnname, $filedtype, $options)
    {
		$db =& JFactory::getDBO();

		
	$query="
CREATE PROCEDURE addcol() BEGIN
IF NOT EXISTS(
	SELECT * FROM information_schema.COLUMNS
	WHERE COLUMN_NAME='".$columnname."' AND TABLE_NAME='".$tablename."' 
	)
	THEN
		ALTER TABLE `".$tablename."`
		ADD COLUMN `".$columnname."` ".$filedtype." ".$options.";

END IF;
END;

	";

	
	$db->setQuery("DROP PROCEDURE IF EXISTS addcol;" );
	$db->query();
	
	$db->setQuery( $query );
	if (!$db->query())    die( $db->stderr());
	
	//echo $query;
	$db->setQuery( "CALL addcol();" );
	if (!$db->query())    die( $db->stderr());
	
	$db->setQuery("DROP PROCEDURE addcol;" );
	if (!$db->query())    die( $db->stderr());
	
	
	
    }
	//end functions




	AddColumnIfNotExist($db->getPrefix().'customflash', 'alternativehtml', 'text', 'NOT NULL');
	AddColumnIfNotExist($db->getPrefix().'customflash', 'alternativeimage', 'varchar(255)', 'NOT NULL');
	AddColumnIfNotExist($db->getPrefix().'customflash', 'style', 'varchar(255)', 'NOT NULL');
	AddColumnIfNotExist($db->getPrefix().'customflash', 'flashvars', 'varchar(1024)', 'NOT NULL');
	AddColumnIfNotExist($db->getPrefix().'customflash', 'menu', 'tinyint(1)', 'NOT NULL');
	AddColumnIfNotExist($db->getPrefix().'customflash', 'cssclass', 'varchar(100)', 'NOT NULL');
	
	AddColumnIfNotExist($db->getPrefix().'customflash', 'loop', 'tinyint(1)', 'NOT NULL');
	AddColumnIfNotExist($db->getPrefix().'customflash', 'paramlist', 'text', '');

}	
	
?>
