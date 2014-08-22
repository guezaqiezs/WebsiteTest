<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
jimport( 'joomla.html.pane' );
$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );

$javascript ='
	window.addEvent( "domready",function(){
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

<div class="col width-50">
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'ADMIN' ).' :: '.JText::_( 'ARTICLE MANAGER' ); ?></legend>
		<table class="admintable">
           	<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ARTICLE CREATION VIEW' ); ?>::<?php echo JText::_( 'ARTICLE CREATION VIEW BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ARTICLE CREATION VIEW' ); ?>::<?php echo JText::_( 'CHOOSE ARTICLE CREATION VIEW' ); ?>">
						<?php echo JText::_( 'ARTICLE CREATION VIEW' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['articleCreationMode']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ARTICLE EDITION2 VIEW' ); ?>::<?php echo JText::_( 'ARTICLE EDITION2 VIEW BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ARTICLE EDITION2 VIEW' ); ?>::<?php echo JText::_( 'CHOOSE ARTICLE EDITION2 VIEW' ); ?>">
						<?php echo JText::_( 'ARTICLE EDITION2 VIEW' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['articleEdition2Mode']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ARTICLE EDITION VIEW' ); ?>::<?php echo JText::_( 'ARTICLE EDITION VIEW BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ARTICLE EDITION VIEW' ); ?>::<?php echo JText::_( 'CHOOSE ARTICLE EDITION VIEW' ); ?>">
						<?php echo JText::_( 'ARTICLE EDITION VIEW' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['articleEditionMode']; ?>
				</td>
			</tr>
            <tr id="as-categories-fullscreen2" class="<?php echo ( ! @$this->configuration->article_edition_mode ) ? '' : 'display-no' ?>">
				<td colspan="3">
				</td>
			</tr>
            <tr id="as-categories-fullscreen" class="<?php echo ( ! @$this->configuration->article_edition_mode ) ? '' : 'display-no' ?>">
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ARTICLE EDITION FULL VIEW' ); ?>::<?php echo JText::_( 'SELECT ARTICLE EDITION FULLSCREEN VIEW' ); ?>">
						<?php echo JText::_( 'ARTICLE EDITION FULLSCREEN VIEW' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['catFullscreen']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
    
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'ADMIN' ).' :: '.JText::_( 'CATEGORY MANAGER' ); ?></legend>
		<table class="admintable">
        	<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY CREATION VIEW' ); ?>::<?php echo JText::_( 'CATEGORY CREATION VIEW BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY CREATION VIEW' ); ?>::<?php echo JText::_( 'CHOOSE CATEGORY CREATION VIEW' ); ?>">
						<?php echo JText::_( 'CATEGORY CREATION VIEW' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['categoryCreationMode']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY EDITION2 VIEW' ); ?>::<?php echo JText::_( 'CATEGORY EDITION2 VIEW BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY EDITION2 VIEW' ); ?>::<?php echo JText::_( 'CHOOSE CATEGORY EDITION2 VIEW' ); ?>">
						<?php echo JText::_( 'CATEGORY EDITION2 VIEW' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['categoryEdition2Mode']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY EDITION VIEW' ); ?>::<?php echo JText::_( 'CATEGORY EDITION VIEW BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY EDITION VIEW' ); ?>::<?php echo JText::_( 'CHOOSE CATEGORY EDITION VIEW' ); ?>">
						<?php echo JText::_( 'CATEGORY EDITION VIEW' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['categoryEditionMode']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
    
    <fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'ADMIN' ).' :: '.JText::_( 'USER MANAGER' ); ?></legend>
		<table class="admintable">
        	<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'USER CREATION VIEW' ); ?>::<?php echo JText::_( 'USER CREATION VIEW BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'USER CREATION VIEW' ); ?>::<?php echo JText::_( 'CHOOSE USER CREATION VIEW' ); ?>">
						<?php echo JText::_( 'USER CREATION VIEW' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['userCreationMode']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'USER EDITION2 VIEW' ); ?>::<?php echo JText::_( 'USER EDITION2 VIEW BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'USER EDITION2 VIEW' ); ?>::<?php echo JText::_( 'CHOOSE USER EDITION2 VIEW' ); ?>">
						<?php echo JText::_( 'USER EDITION2 VIEW' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['userEdition2Mode']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'USER EDITION VIEW' ); ?>::<?php echo JText::_( 'USER EDITION VIEW BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'USER EDITION VIEW' ); ?>::<?php echo JText::_( 'CHOOSE USER EDITION VIEW' ); ?>">
						<?php echo JText::_( 'USER EDITION VIEW' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['userEditionMode']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="col width-50">
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'SITE' ).' :: '.JText::_( 'DEFAULT ARTICLE SUBMISSION' ); ?></legend>
		<table class="admintable">
        	<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT TYPE' ); ?>::<?php echo JText::_( 'CONTENT TYPE ELEMENT' ); ?>">
						<?php echo JText::_( 'CONTENT TYPE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['contentTypeA']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FORM TEMPLATE' ); ?>::<?php echo JText::_( 'FORM TEMPLATE BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FORM TEMPLATE' ); ?>::<?php echo JText::_( 'CONTENT TEMPLATE ELEMENT' ); ?>">
						<?php echo JText::_( 'FORM TEMPLATE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['formTemplateA']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'MENU ITEMID' ); ?>::<?php echo JText::_( 'SELECT MENU ITEMID' ); ?>">
						<?php echo JText::_( 'MENU ITEMID' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['menuItemIdA']; ?>
				</td>
			</tr>
		</table>
	</fieldset> 
    
   	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'SITE' ).' :: '.JText::_( 'DEFAULT CATEGORY SUBMISSION' ); ?></legend>
		<table class="admintable">
        	<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT TYPE' ); ?>::<?php echo JText::_( 'CONTENT TYPE ELEMENT' ); ?>">
						<?php echo JText::_( 'CONTENT TYPE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['contentTypeC']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FORM TEMPLATE' ); ?>::<?php echo JText::_( 'FORM TEMPLATE BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FORM TEMPLATE' ); ?>::<?php echo JText::_( 'CONTENT TEMPLATE ELEMENT' ); ?>">
						<?php echo JText::_( 'FORM TEMPLATE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['formTemplateC']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'MENU ITEMID' ); ?>::<?php echo JText::_( 'SELECT MENU ITEMID' ); ?>">
						<?php echo JText::_( 'MENU ITEMID' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['menuItemIdC']; ?>
				</td>
			</tr>
		</table>
	</fieldset> 
    
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'SITE' ).' :: '.JText::_( 'DEFAULT USER REGISTRATION' ); ?></legend>
		<table class="admintable">
        	<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT TYPE' ); ?>::<?php echo JText::_( 'CONTENT TYPE ELEMENT' ); ?>">
						<?php echo JText::_( 'CONTENT TYPE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['contentTypeU']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FORM TEMPLATE' ); ?>::<?php echo JText::_( 'FORM TEMPLATE BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FORM TEMPLATE' ); ?>::<?php echo JText::_( 'CONTENT TEMPLATE ELEMENT' ); ?>">
						<?php echo JText::_( 'FORM TEMPLATE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['formTemplateU']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'MENU ITEMID' ); ?>::<?php echo JText::_( 'SELECT MENU ITEMID' ); ?>">
						<?php echo JText::_( 'MENU ITEMID' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['menuItemIdU']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
    
    <fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'SITE' ).' :: '.JText::_( 'DEFAULT USER SUBMISSION' ); ?></legend>
		<table class="admintable">
        	<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT TYPE' ); ?>::<?php echo JText::_( 'CONTENT TYPE ELEMENT' ); ?>">
						<?php echo JText::_( 'CONTENT TYPE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['contentTypeUS']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FORM TEMPLATE' ); ?>::<?php echo JText::_( 'FORM TEMPLATE BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FORM TEMPLATE' ); ?>::<?php echo JText::_( 'CONTENT TEMPLATE ELEMENT' ); ?>">
						<?php echo JText::_( 'FORM TEMPLATE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['formTemplateUS']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'MENU ITEMID' ); ?>::<?php echo JText::_( 'SELECT MENU ITEMID' ); ?>">
						<?php echo JText::_( 'MENU ITEMID' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['menuItemIdUS']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<script type="text/javascript">
	$("article_edition_mode0").addEvent("change", function(d) {
		d = new Event(d).stop();
		
		if ( $("article_edition_mode0").checked ) {
			if ( $("as-categories-fullscreen").hasClass("display-no") ) {
				$("as-categories-fullscreen").removeClass("display-no");
			}
			if ( $("as-categories-fullscreen2").hasClass("display-no") ) {
				$("as-categories-fullscreen2").removeClass("display-no");
			}
		}
	});
	$("article_edition_mode1").addEvent("change", function(b) {
		b = new Event(b).stop();
		
		if ( $("article_edition_mode1").checked ) {
			if ( ! $("as-categories-fullscreen").hasClass("display-no") ) {
				$("as-categories-fullscreen").addClass("display-no");
			}
			if ( ! $("as-categories-fullscreen2").hasClass("display-no") ) {
				$("as-categories-fullscreen2").addClass("display-no");
			}
		}
	});
	$("article_edition_mode2").addEvent("change", function(f) {
		f = new Event(f).stop();
		
		if ( $("article_edition_mode2").checked ) {
			if ( ! $("as-categories-fullscreen").hasClass("display-no") ) {
				$("as-categories-fullscreen").addClass("display-no");
			}
			if ( ! $("as-categories-fullscreen2").hasClass("display-no") ) {
				$("as-categories-fullscreen2").addClass("display-no");
			}
		}
	});
</script>