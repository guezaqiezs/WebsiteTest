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
 * Search			Element Class
 **/
class JElementSearch extends JElement
{
	/**
	 * Element name
	 **/
	var	$_name = 'Search';
	
	function fetchElement( $name, $value, &$node, $control_name )
	{
		global $mainframe, $option;
		
		// Include Tables
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'tables' );
		
		$db				=&	JFactory::getDBO();
		$doc 			=&	JFactory::getDocument();
		$template 		=	$mainframe->getTemplate();
		$fieldName		=	$control_name.'['.$name.']';
		$search			=&	JTable::getInstance( 'searchs', 'Table' );

		// Get Config
		$config 	=&	CCK::CORE_getConfig();

		$width 		=	( $config->modal_width ) ? $config->modal_width : 800;
		$height 	=	( $config->modal_height ) ? $config->modal_height : 480;
		
		if ( $value ) {
			$search->load( $value );
		} else {
			$search->title	=	JText::_( 'SELECT A SEARCH BY' );
		}

		$js = "
			function jSelectSearch(id, title, object) {
				document.getElementById(object + '_id').value = id;
				document.getElementById(object + '_name').value = title;
				var opt	=	\"".$option."\";
				if ( opt == 'com_menus' ) {
					if ( document.adminForm && document.adminForm.name && document.adminForm.name.value == '' ) {
						document.adminForm.name.value = title;
					}
				}
				document.getElementById('sbox-window').close();
			}
			function jEmptySearch(title, object) {
				document.getElementById(object + '_id').value = 0;
				document.getElementById(object + '_name').value = title;
				document.getElementById('sbox-window').close();
			}";
		$doc->addScriptDeclaration( $js );

		$link	=	'index.php?option=com_cckjseblod&amp;controller=searchs&amp;task=element&amp;action=-1&amp;tmpl=component&amp;object='.$name;

		JHTML::_( 'behavior.modal', 'a.modal' );
		$html1	=	'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars( $search->title, ENT_QUOTES, 'UTF-8' ).'" disabled="disabled" /></div>';
		$html2	=	'<div class="button2-left"><div class="pagebreak"><a class="modal" title="'.JText::_( 'SELECT A SEARCH BY' ).'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: '.$width.', y: '.$height.'}}">'.JText::_( 'SELECT' ).'</a></div></div>'."\n";
		$html2	.=	'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.(int)$value.'" />';
		$html	=	'<table><tr><td>'.$html1.'</td><td align="center">'.$html2.'</td></tr></table>';
		
		return $html;
	}
}

?>