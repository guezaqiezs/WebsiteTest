<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin');
$mainframe->registerEvent( 'onPrepareContent', 'plgContentComponentbot' );

function plgContentComponentbot(&$row, &$params, $page=0 ) {
	// simple performance check to determine whether bot should process further
	$db =& JFactory::getDBO();
	// simple performance check to determine whether bot should process further
	if ( JString::strpos( $row->text, 'component' ) === false ) {
		return true;
	}
	// define the regular expression for the bot
	$plugin =& JPluginHelper::getPlugin('content', 'componentbot');
	$regex = "#{component}(.*?){/component}#s";
    $pluginParams = new JParameter( $plugin->params );
	

	// check whether mambot has been unpublished
	if ( !$pluginParams->get( 'enabled', 1 ) ) {
	$row->text = preg_replace( $regex, '', $row->text );
    return true;
     }

	// perform the replacement	
	$row->text = preg_replace_callback( $regex, 'botComponentCode_replacer', $row->text );	
	return true;
}


function botComponentCode_replacer( &$matches ) {
   global $mainframe;
	$plugin =& JPluginHelper::getPlugin('content', 'componentbot');
	$pluginParams = new JParameter( $plugin->params );
	 $height = $pluginParams->get( 'height', '500' );
	 $autoheightp = $pluginParams->get( 'height_auto', '0' );
	 $width	= $pluginParams->get( 'width', '500' );
	 
	 
	 if ($autoheightp !=0) {
		$autoheight = 'onload="iFrameHeight()"';
		$myscript =" <script language='javascript' type='text/javascript'>
		function iFrameHeight() {
			var h = 0;
			if ( !document.all ) {
				h = document.getElementById('componentbot').contentDocument.height;
				document.getElementById('componentbot').style.height = h + 60 + 'px';
			} else if( document.all ) {
				h = document.frames('componentbot').document.body.scrollHeight;
				document.all.blockrandom.style.height = h + 20 + 'px';
			}
		}
		</script>";
		} else {
		$autoheight ='';
		$myscript='';	
	 }
	$url = $matches[1];
	//$url = 'index2.php?' . $url;
	$url = 'index.php?'.$url.'&tmpl=component&print=1';
	$para =   'id="componentbot" '.
				'src="'.$url.'" '.
				'width="'.$width.'" '.
				'height="'.$height.'" '.
				'frameborder="0" '.
				'allowtransparency="true" '.
				'scrolling="no" ';

     $rurl = " ".$myscript."<iframe ".$autoheight." ".$para." >\n".
        "<p>Sorry, your browser cannot display frames!</p>\n".
        "</iframe>\n";

	return $rurl;
}

?>