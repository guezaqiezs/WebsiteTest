<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.modal' );
jimport( 'joomla.html.pane' );
$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );

$javascript ='
	window.addEvent( "domready",function(){
		// AdminFormValidator
		var adminFormValidator = new FormValidator( $("adminForm") );
	});
		
	function submitbutton( pressbutton ) {
		var form = document.adminForm;
		if ( pressbutton == "cancel" ) {
			submitform( pressbutton );
			return;
		}
		var adminFormValidator = new FormValidator( $("adminForm") );
		if ( adminFormValidator.validate() ) {
			submitform( pressbutton );
			return;
		}
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form enctype="multipart/form-data" action="index.php" method="post" id="adminForm" name="adminForm">

<div id="config-document">
	<div id="page-process-cck">
		<?php require_once(dirname(__FILE__).DS.'form_process_cck.php'); ?>
	</div>
    <div id="page-process-cek">
		<?php require_once(dirname(__FILE__).DS.'form_process_cek.php'); ?>
	</div>
    <div id="page-process-site">
		<?php require_once(dirname(__FILE__).DS.'form_process_site.php'); ?>
	</div>
   	<!--<div id="page-help">
		<?php /*require_once(dirname(__FILE__).DS.'form_help.php');*/ ?>
	</div>-->
   	<!--<div id="page-update">
		<?php /*require_once(dirname(__FILE__).DS.'form_update.php');*/ ?>
	</div>-->
</div>

	
<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo @$this->configuration->id; ?>" />
<input type="hidden" name="quick_title" value="" id="quick_title" />
<input type="hidden" name="quick_color" value="" id="quick_color" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>