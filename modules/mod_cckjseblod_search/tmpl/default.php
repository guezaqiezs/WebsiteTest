<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.mootools' );
JHTML::_( 'behavior.modal' );
$document->addScript( _PATH_ROOT._PATH_CALENDAR.'calendar.js');
//
$document->addScript( _PATH_ROOT._PATH_MOOTIPS.'mootips.js' );
$document->addStyleSheet( _PATH_ROOT._PATH_MOOTIPS.'mootips.css' );

$tipsOnClick	=	( _SITEFORM_ONCLICK ) ? _SITEFORM_ONCLICK : 0;

$javascript ='
	window.addEvent("domready",function(){	
		var tipsOnClick = "'.$tipsOnClick.'";
		if(tipsOnClick==1){var AjaxTooltips=new MooTips($$(".DescriptionTip"),{className:"ajaxTool",showOnClick:true,showOnMouseEnter:false,fixed:true})}else{var AjaxTooltips=new MooTips($$(".DescriptionTip"),{className:"ajaxTool",fixed:true})}
	});
	
	function submitbutton'.$ran.'(pressbutton) {
		if ( pressbutton == "save" ) {
			document.'.$formName.$ran.'.submit();
			return;
		}
	}
	';
$document->addScriptDeclaration( $javascript );
?>

<?php echo $data; ?>

<?php echo $formHidden; ?>

<input type="hidden" name="option" value="com_cckjseblod" />
<input type="hidden" name="view" value="search" />
<input type="hidden" name="layout" value="search" />
<input type="hidden" name="task" value="search" />
<input type="hidden" name="Itemid" value="<?php echo $itemId; ?>" />
<input type="hidden" name="searchid" value="<?php echo $searchid; ?>" />
<input type="hidden" name="templateid" value="<?php echo $templateid; ?>" />
</form>