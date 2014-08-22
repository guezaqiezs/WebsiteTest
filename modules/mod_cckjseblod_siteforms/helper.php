<?php
/**
* @version 			1.6.0
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			Site Forms - jSeblod CCK ( Content Construction Kit )
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

class modCCKjSeblod_SiteFormsHelper {
	
	function &getData( $contentTypeId )
	{
		$db	=&	JFactory::getDBO();
		
		if ( $contentTypeId ) {
			$query	= 'SELECT s.*'
					. ' FROM #__jseblod_cck_types AS s'
					. ' WHERE s.id = '.$contentTypeId.' AND s.published = 1'
					;
			$db->setQuery( $query );
			$data	=	$db->loadObject();
		}
		
		return $data;
	}
	
	function &getTemplate( $contentTemplateId )
	{
		$db	=&	JFactory::getDBO();
				
		if ( $contentTemplateId ) {
			$query	= 'SELECT s.id, s.name, s.mode'
					. ' FROM #__jseblod_cck_templates AS s'
					. ' WHERE s.id = '.$contentTemplateId.' AND s.published = 1'
					;
			$db->setQuery( $query );
			$template	=	$db->loadObject();
		}
	
		return $template;
	}
}