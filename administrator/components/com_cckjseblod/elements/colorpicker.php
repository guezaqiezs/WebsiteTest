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
class JElementColorPicker extends JElement
{
	/**
	 * Element name
	 **/
	var	$_name = 'ColorPicker';
	
	function fetchElement( $name, $value = '', &$node, $control_name )
	{
		global $mainframe;
		
		$doc 				=&	JFactory::getDocument();
		$template 			=	$mainframe->getTemplate();
		$fieldName			=	$control_name.'['.$name.']';

		$path	=	JURI::root( true ).'/media/jseblod/moorainbow/';
		$doc->addScript( $path.'moorainbow.js' );
		$doc->addStyleSheet( $path.'moorainbow.css' );
		$js = '
			window.addEvent( "domready",function(){
				var init = "'.$value.'";
				if ( !init || init[0] != "#") { init = "#FFFFFF"; }
				R = HexToRGB( init, 0, 2 );
				G = HexToRGB( init, 2, 4);
				B = HexToRGB( init, 4, 6);
				
				var c1 = new MooRainbow( "'.$name.'Rainbow", {
					id: "'.$name.'Rainbow",
					wheel: false, 
					"startColor": [R, G, B],
					"onChange": function( color ) { $("'.$fieldName.'").value = color.hex; }
				});
			 });
			function HexToRGB(hexa,left,right) {return parseInt((cutHex(hexa)).substring(left,right),16)}
			function cutHex(hexa) {return (hexa.charAt(0)=="#") ? hexa.substring(1,7):hexa}
			';
		$doc->addScriptDeclaration( $js );

		$html	=	'<input class="inputbox" type="text" id="'.$fieldName.'" name="'.$fieldName.'" maxlength="10" size="14" value="'.$value.'" />&nbsp;&nbsp;'
				.	'<img src="'.$path.'images/color.png" id="'.$name.'Rainbow" border="0" alt=" " width="16" height="16" />';
		return $html;
	}
}

?>