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
 * Content Template	Element Class
 **/
class JElementTemplateList extends JElement
{
	/**
	 * Element name
	 **/
	var	$_name = 'Templates';
	
	function fetchElement( $name, $value, &$node, $control_name )
	{
		global $mainframe;
		
		// Include Tables
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'tables' );
		
		$db					=&	JFactory::getDBO();
		$doc 				=&	JFactory::getDocument();
		$template 			=	$mainframe->getTemplate();
		$fieldName			=	$control_name.'['.$name.']';
		$contentTemplate	=&	JTable::getInstance( 'templates', 'Table' );

		// Get Config
    $config 	=&	CCK::CORE_getConfig();

		$width 		=	( $config->modal_width ) ? $config->modal_width : 800;
		$height 	=	( $config->modal_height ) ? $config->modal_height : 480;
		
		if ( $value ) {
			$contentTemplate->load( $value );
		} else {
			$contentTemplate->title	=	JText::_( 'SELECT A TEMPLATE BY' );
		}

		$js = "
			function jSelectTemplate(id, title, object) {
				document.getElementById(object + '_id').value = id;
				document.getElementById(object + '_name').value = title;
				document.getElementById('sbox-window').close();
			}
			function jEmptyTemplate(title, object) {
				document.getElementById(object + '_id').value = 0;
				document.getElementById(object + '_name').value = title;
				document.getElementById('sbox-window').close();
			}";
		$doc->addScriptDeclaration( $js );

		//$link	=	'index.php?option=com_cckjseblod&amp;controller=templates&amp;task=element&amp;tmpl=component&amp;selectcat=5&amp;object='.$name;
		$link	=	'index.php?option=com_cckjseblod&amp;controller=templates&amp;task=element&amp;tpl_type=2&amp;tmpl=component&amp;object='.$name;
		
		JHTML::_( 'behavior.modal', 'a.modal' );
		$html1	=	'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars( $contentTemplate->title, ENT_QUOTES, 'UTF-8' ).'" disabled="disabled" /></div>';
		$html2	=	'<div class="button2-left"><div class="pagebreak"><a class="modal" title="'.JText::_( 'SELECT A TEMPLATE BY' ).'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: '.$width.', y: '.$height.'}}">'.JText::_( 'SELECT' ).'</a></div></div>'."\n";
		$html2	.=	'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.(int)$value.'" />';
		$html	=	'<table><tr><td>'.$html1.'</td><td align="center">'.$html2.'</td></tr></table>';
		
		return $html;
	}
}

?>