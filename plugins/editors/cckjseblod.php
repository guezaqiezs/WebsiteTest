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

jimport( 'joomla.plugin.plugin' );

/**
 * CCKjSeblod		Editor Plugin
 **/
class plgEditorCCKjSeblod extends JPlugin
{
	/**
	 * Constructor
	 **/
	function plgEditorCCKjSeblod(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Method to handle the onInitEditor event.
	 *  - Initializes the Editor
	 */
	function onInit()
	{	
		/*$txt =	"<script type=\"text/javascript\">
				function insertPluginValue(myField, myValue) {
					myField.value = myValue;
				}
				</script>";*/
		$txt	=	"";
		return $txt;
	}

	/**
	 * jSeblod CCK Editor - copy editor content to form field
	 */
	function onSave( $editor ) {
		return;
	}

	/**
	 * jSeblod CCK Editor - get the editor content
	 */
	function onGetContent( $editor ) {
		return "document.getElementById( '$editor' ).value;\n";
	}

	/**
	 * jSeblod CCK Editor - set the editor content
	 */
	function onSetContent( $editor, $html ) {
		return "document.getElementById( '$editor' ).value = $html;\n";
	}

	/**
	 * jSeblod CCK Editor - display the editor
	 */
	function onDisplay( $name, $content, $width, $height, $css, $validation, $buttons = true )
	{	
		$buttons	=	$this->_displayButtons( $name, $buttons, $css, $validation );
		$editor		=	$buttons;
		
		return $editor;
	}

	function onGetInsertMethod($name)
	{
		//$doc = & JFactory::getDocument();
		$js="<script type=\"text/javascript\">\tfunction jInsertEditorText( text, editor ) {
			$(editor).value = text;
			var required = editor+'_required';
			if ( $(required) ) {
				if ( text != '' ) {
					$(required).value = ' ';
				} else {
					$(required).value = '';
				}
			}
		}</script>";
		echo $js;
		//$doc->addScriptDeclaration($js);
		//insertPluginValue( document.getElementById(editor), text )

		return true;
	}

	/**
	 * jSeblod CCK Editor - display buttons
	 */
	function _displayButtons( $name, $buttons, $css, $validation )
	{
		JHTML::_( 'behavior.modal', 'a.modal-button' );
		
		$args['name'] = $name;
		$args['event'] = 'onGetInsertMethod';

		$return = '';
		$results[] = $this->update($args);
		foreach ($results as $result) {
			if (is_string($result) && trim($result)) {
				$return .= $result;
			}
		}

		if( ! empty( $buttons ) )
		{
			$results	=	$this->_subject->getButtons($name, $buttons);

			foreach ( $results as $button )
			{
				$name	=	$button->get('name');
				if (  @name && @$name != 'readmorev'  )
				{
					$href		= ( $button->get( 'link' ) ) ? 'href="'.$button->get( 'link' ).'"' : null;
					$onclick	= ( $button->get( 'onclick' ) ) ? 'onclick="'.$button->get( 'onclick' ).'"' : null;
					
					if ( $css != '' ) {
						$modal	=	( $button->get( 'modal' ) ) ? 'class="modal '.$css.'"' : null;
						$return	.=	"<a ".$modal." title=\"".$button->get('text')."\" ".$href." ".$onclick." rel=\"".$button->get('options')."\">".$button->get('text')."</a>";
					} else {
						$modal	=	( $button->get( 'modal' ) ) ? 'class="modal"' : null;
						$return	.=	"<div class=\"button2-left\"><div class=\"".$button->get('name')."\"><a ".$modal." title=\"".$button->get('text')."\" ".$href." ".$onclick." rel=\"".
									$button->get('options')."\">".$validation.$button->get('text')."</a></div></div>\n";
					}
				}
			}
		}

		return $return;
	}
	
}