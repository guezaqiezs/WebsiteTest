<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

// No Direct Access
defined('_JEXEC') or die('Restricted access');

/**
 * HelperjSeblod  Display Class
 **/
class HelperjSeblod_Display
{
    /**
     * Quick Icon Button
     **/
	function quickiconButton( $link, $image, $text, $add_text )
	{
		global $mainframe;
		$lang		=& JFactory::getLanguage();
		$template	= $mainframe->getTemplate();
		$rel 		= "{handler: 'iframe', size: {x: "._MODAL_WIDTH.", y: 320}}";
		?>
		<div style="float:<?php echo ( $lang->isRTL() ) ? 'right' : 'left'; ?>;  width: 108px; margin-right: 7px; margin-bottom: 5px;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo JHTML::_( 'image.site',  $image, '/components/com_cckjseblod/assets/images/cpanel/', NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
            <?php if ( $add_text != 'addon'  ) { ?>
			<?php if ( $add_text != 'configuration' ) { 
            	if ( $add_text == 'pack' ) { ?>
                <div class="icon_jseblod">
					<a href="<?php echo $link."&task=add&tmpl=component"; ?>" class="modal" rel="<?php echo $rel; ?>">
						<?php echo JHTML::_( 'image.site',  'icon-32-add-'.$add_text.'.png', '/components/com_cckjseblod/assets/images/cpanel/', NULL, NULL, $add_text ); ?>
					</a>
				</div>
                <?php } else { ?>
				<div class="icon_jseblod">
					<a href="<?php echo $link."&task=add"; ?>">
						<?php echo JHTML::_( 'image.site',  'icon-32-add-'.$add_text.'.png', '/components/com_cckjseblod/assets/images/cpanel/', NULL, NULL, $add_text ); ?>
					</a>
				</div>
			<?php } } else { ?>
				<div class="icon_jseblod">
           			<a href="<?php echo $link; ?>">
						<?php echo JHTML::_( 'image.site',  'icon-32-'.$add_text.'.png', '/components/com_cckjseblod/assets/images/cpanel/', NULL, NULL, $add_text ); ?>
                    </a>
				</div>
            <?php } ?>
            <?php } ?>
		</div>
		<?php
	}

    /**
     * Quick Icon Export Button
     **/
	function quickiconExportButton( $link, $image, $text, $add_text )
	{
		global $mainframe;
		$lang		=& JFactory::getLanguage();
		$template	= $mainframe->getTemplate();
		?>
		<div style="float:<?php echo ( $lang->isRTL() ) ? 'right' : 'left'; ?>;  width: 108px; margin-right: 7px; margin-bottom: 5px;">
				<div class="icon_jseblod">
					<a href="<?php echo $link."#pagination-bottom"; ?>">
						<?php echo JHTML::_( 'image.site',  'icon-32-export-'.$add_text.'.png', '/components/com_cckjseblod/assets/images/cpanel/', NULL, NULL, $add_text ); ?>
					</a>
				</div>
		</div>
		<?php
	}

	/**
	 * Quick Modal
     **/
	function quickModal( $text, $element, $class, $option, $controller, $task, $extra, $cid, $width = _MODAL_WIDTH, $height = _MODAL_HEIGHT )
	{
		$element	=	( $element ) ? '&amp;element=' . $element : null;
		
		$task		=   ( $task ) ? 'task=' . $task . '&amp;' : null;
		$extra 		=	( $extra ) ? $extra . '&amp;' : null;
		$cid		=	( $cid ) ? 'cid[]=' . $cid . '&amp;' : null;
		$link 		= 	'index.php?option='.$option.'&amp;controller='.$controller.'&amp;'.$task.$extra.$cid.'tmpl=component'.$element;
		$modal	 	= 	"<div class=\"button2-left\"><div class=\"$class\"><a class=\"modal\" title=\"".JText::_( $text )."\" href=\"$link\" rel=\"{handler: 'iframe', size: {x: $width, y: $height}}\">".JText::_( $text )."</a></div></div>\n";
		
		return  $modal;
	}
	
	/**
	 * Quick Modal Task
     **/
	function quickModalTask( $text, $from, $into, $class, $controller, $task, $width = _MODAL_WIDTH, $height = _MODAL_HEIGHT, $extra = '' )
	{
		global $option;
	
		$from		=	( $from ) ? '&amp;from=' . $from : null;
		$into		=	( $into ) ? '&amp;into=' . $into : null;
		$extra		=	( $extra ) ? '&amp;'.$extra : null;
		
		$link 		= 	'index.php?option='.$option.'&amp;controller='.$controller.'&amp;task='.$task.'&amp;tmpl=component'.$from.$into.$extra;
		$modal	 	= 	"<div class=\"button2-left\"><div class=\"$class\"><a class=\"modal\" title=\"".JText::_( $text )."\" href=\"$link\" rel=\"{handler: 'iframe', size: {x: $width, y: $height}}\">".JText::_( $text )."</a></div></div>\n";
		
		return  $modal;
	}
	
	/**
	 * Quick Modal Image
     **/
	function quickModalImage( $text, $from, $into, $class, $id, $width = _MODAL_WIDTH, $height = _MODAL_HEIGHT )
	{
		global $option;
	
		$from		=	( $from ) ? '&amp;from=' . $from : null;
		$into		=	( $into ) ? '&amp;e_name=' . $into : null;
		// TODO ADD userId;
		
		//$link 		= 	'index.php?option='.$option.'&amp;controller=modal_image&amp;id='.$id.'&amp;tmpl=component'.$from.$into;
		$link			=	"javascript: openSwampyBrowser('sbfile', '', 'image', window);";
		$modal	 	= 	"<div class=\"button2-left\"><div class=\"$class\"><a  title=\"".JText::_( $text )."\" href=\"$link\" >".JText::_( $text )."</a></div></div>\n";
		
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
		
		$link 		= 	'index.php?option='.$option.'&amp;controller=modal_upload&amp;tmpl=component'.$from.$into;
		$modal	 	= 	"<div class=\"button2-left\"><div class=\"$class\"><a class=\"modal\" title=\"".JText::_( $text )."\" href=\"$link\" rel=\"{handler: 'iframe', size: {x: $width, y: $height}}\">".JText::_( $text )."</a></div></div>\n";
		
		return  $modal;
	}
	
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
		
		$link 		= 	'index.php?option='.$option.'&amp;controller=modal_wysiwyg'.$cid.'&amp;tmpl=component'.$from.$into.$mode;
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
		
		$link 		= 	'index.php?option='.$option.'&amp;controller=modal_wysiwyg'.$cid.'&amp;tmpl=component'.$from.$into.$mode;
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
	 * Quick Tooltip Ajax
     **/
	function quickTooltipAjax( $text, $from, $into, $cid )
	{
		global $option;
		
		$from		=	( $from ) ? '&amp;from=' . $from : null;
		$into		=	( $into ) ? '&amp;into=' . $into : null;
		$cid		=	( $cid ) ? '&amp;cid[]=' . $cid : null;
		$text		=	( $text ) ? '&amp;legend=' . $text : null;
		
		$link 		= 	'AJAX:index.php?option='.$option.'&amp;controller=modal_tooltip'.$cid.'&amp;tmpl=component'.$from.$into.$text;
		$tooltip 	= 	"<span class=\"ajaxTip\" title=\"$link\">"._IMG_BALLOON_LEFT."</span>";
		
		return  $tooltip;
	}
	
	/**
	 * Quick Tooltip Ajax Link
     **/
	function quickTooltipAjaxLink( $text, $from, $into, $cid )
	{
		global $option;
		
		$from		=	( $from ) ? '&amp;from=' . $from : null;
		$into		=	( $into ) ? '&amp;into=' . $into : null;
		$cid		=	( $cid ) ? '&amp;cid[]=' . $cid : null;
		$text		=	( $text ) ? '&amp;legend=' . $text : null;
		
		$link 		= 	'AJAX:index.php?option='.$option.'&amp;controller=modal_tooltip'.$cid.'&amp;tmpl=component'.$from.$into.$text;
		
		return  $link;
	}
	
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
     * Quick Toolbar Button
     **/
/*	function quickToolbarPreview( $templateId )
	{
		$bar	=&	JToolBar::getInstance( 'toolbar' );
		
		$link	=	JRoute::_( 'index.php?option=com_cckjseblod&controller=templates&task=preview&cid[]='.$templateId.'&tmpl=component' );
		
		$bar->appendButton( 'Popup', 'preview', 'preview', $link, _MODAL_WIDTH, _MODAL_HEIGHT );
	}
*/	
	/**
     * Quick Toolbar Button
     **/
	function quickToolbarReserved()
	{
		$bar	=&	JToolBar::getInstance( 'toolbar' );
		
		$link	=	JRoute::_( 'index.php?option=com_cckjseblod&controller=items&task=reserved&tmpl=component' );
		
		$bar->appendButton( 'Popup', 'lock', 'RESERVED', $link, _MODAL_WIDTH, _MODAL_HEIGHT );
	}
	
	/**
     * Quick Toolbar Button
     **/
	function quickToolbarOperations()
	{
		$bar	=&	JToolBar::getInstance( 'toolbar' );
		
		$link	=	JRoute::_( 'index.php?option=com_cckjseblod&controller=configuration&task=operations&tmpl=component' );
		
		$bar->appendButton( 'Popup', 'operations_jseblod', 'OPERATIONS', $link, _MODAL_WIDTH, 510 );
	}
	
	/**
     * Quick Toolbar Button
     **/
	function quickToolbarProcess()
	{
		$bar	=&	JToolBar::getInstance( 'toolbar' );
		
		$link	=	JRoute::_( 'index.php?option=com_cckjseblod&task=process&tmpl=component' );
		
		$bar->appendButton( 'Popup', 'process', 'PROCESS', $link, _MODAL_WIDTH, 280 );
	}
	
	/**
     * Quick Toolbar Button
     **/
	function quickToolbarSupport()
	{
		$bar	=&	JToolBar::getInstance( 'toolbar' );
		
		$link	=	JRoute::_( 'http://extensions.joomla.org/extensions/news-production/content-construction/9128' );
		
		$bar->appendButton( 'Link', 'support_jseblod', 'SUPPORT', $link, _MODAL_WIDTH, _MODAL_HEIGHT );
	}
	
	/**
     * Quick Refresh Page
     **/
	function quickRefreshPage()
	{
		?>
		<a href="javascript: this.location.reload();"><img src="components/com_cckjseblod/assets/images/icon-18-refresh.gif" border="0" /></a>
		<?php
	}
	
	/**
     * Quick Slide To
     **/
	function quickSlideTo( $direction, $text = null )
	{
		$direction	=	'#' . $direction;
		$text		=	( $text ) ? $text : JText::_( 'Num' );
		?>
		<a href="<?php echo $direction; ?>" style="color: #666666; text-decoration: none;">&nbsp;<?php echo $text; ?>&nbsp;</a>
		<?php
	}
	
	/**
     * Quick Legend
     **/
	function quickLegend()
	{
		?>
		<div style="float: left">
			<?php echo '<font color="gray">(*)&nbsp;' . JText::_( 'EXACT' ) . '</font>'; ?>		
		</div>
		<?php
	}
	
	/**
     * Quick Back To Top
     **/
	function quickBackToTop()
	{
		?>
		<div style="float:right" padding-bottom:6px;>
        <span>
			<a href="#border-top" style="color: gray"><?php echo JText::_( 'BACK TO TOP' ); ?></a><?php echo _NBSP; ?>
            </span>
		</div>
		<?php
	}
	
	/**
     * Quick Back To Selection
     **/
	function quickBackToSelection()
	{
		?>
		<div style="float:left" padding-bottom:6px;>
	        <span>
				<?php echo _NBSP . '<font color="grey">' . JText::_( 'CLICK ON SELECTION' ) . '</font>'; ?>
            </span>
		</div>
		<?php
	}
	
	/**
     * Quick Back To Top Modal
     **/
	function quickBackToTopModal()
	{
		?>
		<div style="float:right">
			<a href="#modal-top" style="color: gray"><?php echo JText::_( 'BACK TO TOP' ); ?></a><?php echo _NBSP; ?>
		</div>
		<?php
	}
	
	/**
     * Quick Copyright
     **/
	function quickCopyright()
	{
		?>
		<div class="copyright_jseblod">
  			<?php echo '<strong>'.JText::_( 'Content Construction Kit' ).' '._VERSION.'</strong>'; ?>
			<br /><a target="_blank" href="http://www.seblod.com"><?php echo 'SEBLOD 1.x'; ?></a>
            <?php echo '<strong>'.' &copy 2011</strong>'; ?>
		</div>
		<?php
	}
	
	/**
     * Toolbar Help
     **/
	function help($ref)
	{
		$bar	=&	JToolBar::getInstance( 'toolbar' );
		
		$lang		=&	JFactory::getLanguage();
		$lang_tag	=	$lang->getTag();
		if ( ! ( $lang_tag == 'en-GB' || $lang_tag == 'fr-FR' || $lang_tag == 'de-DE' ) ) {
			$lang_tag = 'en-GB';
		}
		$link		=	'http://www.seblod.com/v1/help/cck/'.$lang_tag.'/'.$ref.'.php';
		$bar->appendButton( 'Popup', 'helpjseblod', 'Help', $link, _MODAL_WIDTH, _MODAL_HEIGHT );
	}
	
}
?>