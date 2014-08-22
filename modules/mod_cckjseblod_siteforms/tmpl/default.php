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
$lang     =&  JFactory::getLanguage();
$langTag =   $lang->getTag();
if ( JFile::exists( JPATH_SITE._PATH_FORMVALIDATOR.$langTag.'_formvalidator.js' ) ) {
  $document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.$langTag.'_formvalidator.js' );
} else {
  $document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
}
//
$document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );
$document->addScript( _PATH_ROOT._PATH_MOOTIPS.'mootips.js' );
$document->addStyleSheet( _PATH_ROOT._PATH_MOOTIPS.'mootips.css' );

$tipsOnClick	=	( _SITEFORM_ONCLICK ) ? _SITEFORM_ONCLICK : 0;


$javascript ='
	window.addEvent("domready",function(){	
		var siteFormValidator = new FormValidator(document.getElementById("'.$formName.$ran.'"));
		var tipsOnClick = "'.$tipsOnClick.'";
		if(tipsOnClick==1){var AjaxTooltips=new MooTips($$(".DescriptionTip"),{className:"ajaxTool",showOnClick:true,showOnMouseEnter:false,fixed:true})}else{var AjaxTooltips=new MooTips($$(".DescriptionTip"),{className:"ajaxTool",fixed:true})}
	});
	
	function submitbutton'.$ran.'(pressbutton) {
		var siteFormValidator = new FormValidator(document.getElementById("'.$formName.$ran.'"));
		
		if ( pressbutton == "save" ) {
			if (siteFormValidator.validate()) {
				document.'.$formName.$ran.'.submit();
				return;
			}
		}
	}
	';
$document->addScriptDeclaration( $javascript );
?>

<?php echo $data; ?>

<?php echo $formHidden; ?>

<input type="hidden" name="option" value="com_cckjseblod" />
<input type="hidden" name="itemid" value="<?php echo $itemId; ?>" />
<input type="hidden" name="current_url" value="<?php echo JURI::current(); ?>" />
<input type="hidden" name="typeid" value="<?php echo $typeid; ?>" />
<input type="hidden" name="templateid" value="<?php echo $templateid; ?>" />
<input type="hidden" name="formname" value="<?php echo $formName; ?>" />
<input type="hidden" name="actionmode" value="<?php echo $actionMode; ?>" />
<input type="hidden" name="captcha_enable" value="<?php echo $captchaEnable; ?>" />
<input type="hidden" name="content_id" value="<?php echo JRequest::getInt( 'id' ); ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>