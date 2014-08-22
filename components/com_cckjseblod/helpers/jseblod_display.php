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
 * HelperjSeblod	Display Class
 **/
class HelperjSeblod_Display
{
	/**
	 * Quick Toolbar
     **/
	function quickToolbar( $buttons )
	{ 	?>
		<div id="toolbar" class="toolbar">
		<table class="toolbar">
			<tr>
				<?php
				foreach( $buttons AS $item ) {
				if ( $item[1] == 'spacer' ) { ?>
					<td class="spacer"> </td>
				<?php } else if ( $item[1] == 'divider' ) { ?>
					<td class="divider"> </td>
				<?php } else { ?>
					<td id="toolbar-<?php echo $item[1]; ?>" class="button"> <?php
					if ( $item[3] == 'ajaxtip' ) { ?>
						<a class="toolbar" href="#">
							<span class="icon-32-<?php echo $item[1]; ?> ajaxTip" title="<?php echo $item[2]; ?>"></span>
							<?php echo JText::_( $item[0] ); ?>
						</a>
					<?php } else if ( $item[3] == 'id' ) { ?>
						<a class="toolbar" href="#" id="<?php echo $item[2]; ?>">
							<span class="icon-32-<?php echo $item[1]; ?>" title="<?php echo $item[0]; ?>"></span>
							<?php echo JText::_( $item[0] ); ?>
						</a>
					<?php } else if ( $item[3] == 'onclick' ) { ?>
						<a class="toolbar" href="#" onclick="<?php echo $item[2]; ?>">
							<span class="icon-32-<?php echo $item[1]; ?>" title="<?php echo $item[0]; ?>"></span>
							<?php echo JText::_( $item[0] ); ?>
						</a>
					<?php } else { ?>
						<a class="toolbar" href="<?php echo $item[2]; ?>" target="_self">
							<span class="icon-32-<?php echo $item[1]; ?>" title="<?php echo $item[0]; ?>"></span>
							<?php echo JText::_( $item[0] ); ?>
						</a>
				<?php } ?>
					</td>
				<?php } } ?>
			</tr>
		</table>
	</div>
	<?php }
	
	/**
	 * Quick Modal Wysiwyg
     **/
	function quickModalWysiwyg( $text, $from, $into, $class, $required, $cid, $mode, $width = _MODAL_WIDTH, $height = _MODAL_HEIGHT )
	{
		global $option;

		if ( $cid == -1 ) {
			$hidden	=	'<input type="hidden" id="'.$into.'_updated" name="'.$into.'_updated" value="1" />';
		} else {
			$hidden	=	'<input type="hidden" id="'.$into.'" name="'.$into.'" value="" />'
					.   '<input type="hidden" id="'.$into.'_updated" name="'.$into.'_updated" value="0" />';
		}
		
		$from		=	( $from ) ? '&amp;from=' . $from : null;
		$name		=	$into.'_required';
		$into		=	( $into ) ? '&amp;into=' . $into : null;
		$cid		=	( $cid ) ? '&amp;cid[]=' . $cid : null;
		$mode		=	( $mode ) ? '&amp;e_editor='.$mode : null;
	
		if ( $required ) {
			$value		=	( $required == 2 ) ? ' ' : '';
			$required	=	"<input id=\"$name\" name=\"$name\" class=\"required required-enabled\" type=\"text\" size=\"1\" maxlength=\"0\" style=\"width: 8px; height: 8px; text-align: 	
							center; cursor: default; margin-top: 5px; vertical-align: top;\" disabled=\"disabled\" value=\"$value\" />&nbsp;";
		} else {
			$required	=	'';
		}
	
		$link 		= 	'index.php?option=com_cckjseblod&amp;view=modal_wysiwyg'.$cid.'&amp;tmpl=component'.$from.$into.$mode;
		$modal	 	= 	"<div class=\"button2-left\"><div class=\"$class\"><a class=\"modal\" title=\"\" href=\"$link\" rel=\"{handler: 'iframe', size: {x: $width, y: $height}}\">".$required.JText::_( $text )."</a></div></div>\n";
		$modal		=	$hidden . $modal;
		
		return  $modal;
	}
	
	/**
	 * Quick Modal Wysiwyg Js
     **/
	function quickModalWysiwygJs( $text, $from, $into, $class, $required, $cid, $mode, $width = _MODAL_WIDTH, $height = _MODAL_HEIGHT )
	{
		global $option;
		
		if ( $cid == -1 ) {
			$hidden	=	'<input type="hidden" id="'.$into.'_updated" name="'.$into.'_updated" value="1" />';
		} else {
			$hidden	=	'<input type="hidden" id="'.$into.'" name="'.$into.'" value="" />'
					.   '<input type="hidden" id="'.$into.'_updated" name="'.$into.'_updated" value="0" />';
		}
		
		$from		=	( $from ) ? '&amp;from=' . $from : null;
		$name		=	$into.'_required';
		$into		=	( $into ) ? '&amp;into=' . $into : null;
		$cid		=	( $cid ) ? '&amp;cid[]=' . $cid : null;
		$mode		=	( $mode ) ? '&amp;e_editor='.$mode : null;
		
		if ( $required ) {
			$value		=	( $required == 2 ) ? ' ' : '';
			$required	=	"<input id=\"$name\" name=\"$name\" class=\"required required-enabled\" type=\"text\" size=\"1\" maxlength=\"0\" style=\"width: 8px; height: 8px; text-align: 	
							center; cursor: default; margin-top: 5px; vertical-align: top;\" disabled=\"disabled\" value=\"$value\" />&nbsp;";
		} else {
			$required	=	'';
		}
		
		$link 		= 	'index.php?option=com_cckjseblod&amp;view=modal_wysiwyg'.$cid.'&amp;tmpl=component'.$from.$into.$mode;
		$link		=	'javascript: SqueezeBox.fromElement(\''.$link.'\', {handler: \'iframe\', size: {x: '.$width.', y: '.$height.'}});';
		$modal	 	= 	"<div class=\"button2-left\"><div class=\"$class\"><a title=\"\" href=\"javascript:void(0);\" onclick=\"$link\">".$required.JText::_( $text )."</a></div></div>\n";
		$modal		=	$hidden . $modal;
		
		return  $modal;
	}
	
	function quickWysiwyg( $e_editor )
	{
		$db	=&	JFactory::getDBO();
		
		$query		=	'SELECT template FROM #__templates_menu WHERE client_id = 0 AND menuid = 0';
		$db->setQuery( $query );
		$siteTmpl	=	$db->loadResult();
		
		if ( strpos( _WYSIWYG_EDITOR, '_' ) !== false ) {
			$skinT	=	explode( '_', _WYSIWYG_EDITOR );
			$skin	=	'skin : "'.$skinT[0].'", skin_variant : "'.$skinT[1].'"';
		} else {
			$skin	=	'skin : "'._WYSIWYG_EDITOR.'"';
		}
		
		switch ( $e_editor ) {
			case 'extended':
				$script	=	'
					<script type="text/javascript">
						tinyMCE.init({
						// General
						dialog_type : "modal",
						directionality: "ltr",
						editor_selector : "mce_editable",
						language : "en",
						mode : "specific_textareas",
						plugins : "paste,searchreplace,insertdatetime,table,emotions,media,advhr,directionality,fullscreen,layer,style,visualchars,nonbreaking,advimage,advlink,contextmenu,inlinepopups",
						'.$skin.',
						theme : "advanced",
						// Callbacks
						file_browser_callback : "",
						// Cleanup/Output
						cleanup : true,
						cleanup_on_startup : false,
						entity_encoding : "raw",
						extended_valid_elements : "hr[id|title|alt|class|width|size|noshade],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],a[class|name|href|target|title|onclick|rel]",
						force_br_newlines : "true", force_p_newlines : "false", forced_root_block : "",
						invalid_elements : "applet",
						// URL
						relative_urls : true,
						remove_script_host : false,
						document_base_url : "'.JURI::root().'",
						// Layout
						content_css : "'.JURI::root().'templates/'.$siteTmpl.'/css/template.css, '.JURI::root().'templates/system/css/editor.css",
						// Advanced theme
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_source_editor_height : "550",
						theme_advanced_source_editor_width : "750",
						theme_advanced_statusbar_location : "bottom", theme_advanced_path : true,
						theme_advanced_buttons1_add_before : "",
						theme_advanced_buttons2_add_before : "cut,copy,paste,pastetext,pasteword,selectall,|,search,replace,|",
						theme_advanced_buttons3_add_before : "tablecontrols",
						theme_advanced_buttons1_add : "fontselect,fontsizeselect",
						theme_advanced_buttons2_add : "|,insertdate,inserttime,forecolor,backcolor",
						theme_advanced_buttons3_add : "emotions,media,advhr,ltr,rtl,fullscreen",
						theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,styleprops,visualchars,nonbreaking",
						plugin_insertdate_dateFormat : "%Y-%m-%d",
						plugin_insertdate_timeFormat : "%H:%M:%S",
						fullscreen_settings : {
						theme_advanced_path_location : "top"
						}
						});
					</script>';	
				break;
			case 'simple':
				$script	=	'
					<script type="text/javascript">
						tinyMCE.init({
						// General
						directionality: "ltr",
						editor_selector : "mce_editable",
						language : "en",
						mode : "specific_textareas",
						'.$skin.',
						theme : "simple",
						// Cleanup/Output
						cleanup : true,
						cleanup_on_startup : false,
						entity_encoding : "raw",
						force_br_newlines : "true", force_p_newlines : "false", forced_root_block : "",
						// URL
						relative_urls : true,
						remove_script_host : false,
						document_base_url : "'.JURI::root().'",
						// Layout
						content_css : "'.JURI::root().'templates/'.$siteTmpl.'/css/template.css, '.JURI::root().'templates/system/css/editor.css"
						});
					</script>';
				break;
			case 'advanced':
        	default:
				$script	=	'
					<script type="text/javascript">
						tinyMCE.init({
						// General
						directionality: "ltr",
						editor_selector : "mce_editable",
						language : "en",
						mode : "specific_textareas",
						'.$skin.',
						theme : "advanced",
						// Cleanup/Output
						cleanup : true,
						cleanup_on_startup : false,
						entity_encoding : "raw",
						extended_valid_elements : 								"hr[id|title|alt|class|width|size|noshade],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],a[class|name|href|target|title|onclick|rel]",
						force_br_newlines : "true", force_p_newlines : "false", forced_root_block : "",
						invalid_elements : "applet",
						// URL
						relative_urls : true,
						remove_script_host : false,
						document_base_url : "'.JURI::root().'",
						// Layout
						content_css : "'.JURI::root().'templates/'.$siteTmpl.'/css/template.css, '.JURI::root().'templates/system/css/editor.css",
						// Advanced theme
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_source_editor_height : "550",
						theme_advanced_source_editor_width : "750",
						theme_advanced_statusbar_location : "bottom", theme_advanced_path : true
						});
					</script>';
				break;
		}
		return $script;
	}
	
	/**
	 * Quick Modal Image
     **/
	function quickModalImage( $text, $from, $into, $class, $width = _MODAL_WIDTH, $height = _MODAL_HEIGHT )
	{
		global $option;
	
		$from		=	( $from ) ? '&amp;from=' . $from : null;
		$into		=	( $into ) ? '&amp;e_name=' . $into : null;
		
		$link 		= 	'index.php?option=com_cckjseblod&amp;view=modal_image&amp;tmpl=component'.$from.$into;
		$modal	 	= 	"<div class=\"button2-left\"><div class=\"$class\"><a class=\"modal\" title=\"".JText::_( $text )."\" href=\"$link\" rel=\"{handler: 'iframe', size: {x: $width, y: $height}}\">".JText::_( $text )."</a></div></div>\n";
		
		return  $modal;
	}
	
	/**
	 * Quick Modal Upload
     **/
	function quickModalUpload( $text, $from, $into, $class, $width = _MODAL_WIDTH, $height = _MODAL_HEIGHT )
	{
		global $option;
	
		$from		=	( $from ) ? '&amp;from=' . $from : null;
		$into		=	( $into ) ? '&amp;into=' . $into : null;
		
		$link 		= 	'index.php?option='.$option.'&amp;view=modal_upload&amp;tmpl=component'.$from.$into;
		$modal	 	= 	"<div class=\"button2-left\"><div class=\"$class\"><a class=\"modal\" title=\"".JText::_( $text )."\" href=\"$link\" rel=\"{handler: 'iframe', size: {x: $width, y: $height}}\">".JText::_( $text )."</a></div></div>\n";
		
		return  $modal;
	}
}
?>