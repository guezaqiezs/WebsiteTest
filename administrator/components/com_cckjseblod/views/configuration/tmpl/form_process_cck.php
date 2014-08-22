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
	<legend class="legend-border"><?php echo JText::_( 'BOX DIMENSIONS' ); ?></legend>
		<table class="admintable">
  			<tr>
            	<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'INTERFACE WIDTH' ); ?>::<?php echo JText::_( 'EDIT INTERFACE WIDTH' ); ?>">
						<?php echo JText::_( 'INTERFACE WIDTH' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required-enabled required minLength" validatorProps="{minLength:3}" type="text" id="modal_width" name="modal_width" maxlength="4" size="16" value="<?php echo $this->configuration->modal_width; ?>" />
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'INTERFACE HEIGHT' ); ?>::<?php echo JText::_( 'EDIT INTERFACE HEIGHT' ); ?>">
						<?php echo JText::_( 'INTERFACE HEIGHT' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required-enabled required minLength" validatorProps="{minLength:3}" type="text" id="modal_height" name="modal_height" maxlength="4" size="16" value="<?php echo $this->configuration->modal_height; ?>" />
				</td>
			</tr>
		</table>
	</fieldset>
    
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'CONTENT EDITION KIT' ); ?></legend>
		<table class="admintable">
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'RESTRICTIONS' ); ?>::<?php echo JText::_( 'SELECT RESTRICTION LEVEL' ); ?>">
						<?php echo JText::_( 'RESTRICTIONS' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['restrictC']; ?>
				</td>
			</tr>
            <tr>
				<td colspan="3">
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'DEFAULT COLUMN NUM' ); ?>::<?php echo JText::_( 'SELECT DEFAULT COLUMN NUM' ); ?>">
						<?php echo JText::_( 'DEFAULT COLUMN NUM' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['defaultColumn']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CEK COLUMN NUM' ); ?>::<?php echo JText::_( 'SELECT CEK COLUMN NUM' ); ?>">
						<?php echo JText::_( 'ARTICLE COLUMN NUM' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['articleColumn']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CEK COLUMN NUM' ); ?>::<?php echo JText::_( 'SELECT CEK COLUMN NUM' ); ?>">
						<?php echo JText::_( 'CATEGORY COLUMN NUM' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['categoryColumn']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CEK COLUMN NUM' ); ?>::<?php echo JText::_( 'SELECT CEK COLUMN NUM' ); ?>">
						<?php echo JText::_( 'USER COLUMN NUM' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['userColumn']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
    
    <fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'FORMS' ); ?></legend>
		<table class="admintable">
			<tr class="display-no">
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ITEM OPENING' ); ?>::<?php echo JText::_( 'SELECT ITEM OPENING' ); ?>">
						<?php echo JText::_( 'ITEM OPENING' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['opening']; ?>
				</td>
			</tr>
			<tr class="display-no">
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ITEM CLOSING' ); ?>::<?php echo JText::_( 'SELECT ITEM CLOSING' ); ?>">
						<?php echo JText::_( 'ITEM CLOSING' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['closing']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ADMIN FORM TOOLTIPS' ); ?>::<?php echo JText::_( 'SELECT ADMIN FORM TOOLTIPS MODE' ); ?>">
						<?php echo JText::_( 'ADMIN FORM TOOLTIPS' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['adminFormTips']; ?>
				</td>                
			</tr>
      		<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SITE FORM TOOLTIPS' ); ?>::<?php echo JText::_( 'SELECT SITE FORM TOOLTIPS MODE' ); ?>">
						<?php echo JText::_( 'SITE FORM TOOLTIPS' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['siteFormTips']; ?>
				</td>                
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'VALIDATION ALERT' ); ?>::<?php echo JText::_( 'VALIDATION ALERT BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'VALIDATION ALERT' ); ?>::<?php echo JText::_( 'CHOOSE VALIDATION ALERT OR NOT' ); ?>">
						<?php echo JText::_( 'VALIDATION ALERT' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['validAlert']; ?>
				</td>                
			</tr>
            <tr>
            	<td colspan="3">
                </td>
            </tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'JTEXT ON SELECTLABEL' ); ?>::<?php echo JText::_( 'JTEXT ON SELECTLABEL BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'JTEXT ON SELECTLABEL' ); ?>::<?php echo JText::_( 'CHOOSE JTEXT ON SELECTLABEL OR NOT' ); ?>">
						<?php echo JText::_( 'JTEXT ON SELECTLABEL' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['jTextOnLabel']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TINYMCE PRESETS SKIN' ); ?>::<?php echo JText::_( 'SELECT TINYMCE PRESETS SKIN' ); ?>">
						<?php echo JText::_( 'TINYMCE PRESETS SKIN' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['tinySkin']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>	

<div class="col width-50">
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'REGISTRATION BY JSEBLOD' ); ?></legend>
   		<table class="admintable">
            <tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ENABLE LOGIN MODULES' ); ?>::<?php echo JText::_( 'ENABLE LOGIN MODULES BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ENABLE LOGIN MODULES' ); ?>::<?php echo JText::_( 'CHOOSE JSEBLOD REGISTRATION OR NOT' ); ?>">
						<?php echo JText::_( 'ENABLE LOGIN MODULES' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['loginEnable']; ?>
				</td>
			</tr>        
        </table>
 	</fieldset>
               
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'THIRD PARTY COMPATIBILITY' ); ?></legend>
   		<table class="admintable">
            <tr>
                <td width="25" align="right" class="key_jseblod">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'SYSTEM PROCESS COMPONENT' ); ?>::<?php echo JText::_( 'SYSTEM PROCESS COMPONENT BALLOON' ); ?>">
                        <?php echo _IMG_BALLOON_LEFT; ?>
                    </span>
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'SYSTEM PROCESS COMPONENT' ); ?>::<?php echo JText::_( 'CHOOSE ENABLE SYSTEM PROCESS COMPONENT OR NOT' ); ?>">
                        <?php echo JText::_( 'ON COMPONENT' ); ?>:
                    </span>
                </td>
                <td>
                    <?php echo $this->lists['systemComponent']; ?>
                </td>
            </tr>
            <tr>
                <td width="25" align="right" class="key_jseblod">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'SYSTEM PROCESS MODULES' ); ?>::<?php echo JText::_( 'SYSTEM PROCESS MODULES BALLOON' ); ?>">
                        <?php echo _IMG_BALLOON_LEFT; ?>
                    </span>
                </td>
                <td width="100" align="right" class="key">
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'SYSTEM PROCESS MODULES' ); ?>::<?php echo JText::_( 'CHOOSE ENABLE SYSTEM PROCESS MODULES OR NOT' ); ?>">
                        <?php echo JText::_( 'ON MODULES' ); ?>:
                    </span>
                </td>
                <td>
                    <?php echo $this->lists['systemModules']; ?>
                </td>
            </tr>
		</table>
	</fieldset>
        
    <fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'JOOMFISH INTEGRATION' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'MODE' ); ?>::<?php echo JText::_( 'CHOOSE MODE' ); ?>">
						<?php echo JText::_( 'MODE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['jf_mode']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'HIDE DEFAULT JF' ); ?>::<?php echo JText::_( 'HIDE DEFAULT JF BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'HIDE DEFAULT JF' ); ?>::<?php echo JText::_( 'CHOOSE HIDE DEFAULT JF OR NOT' ); ?>">
						<?php echo JText::_( 'HIDE DEFAULT JF' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['jf_hide']; ?>
				</td>
			</tr>
            <tr>
            	<td colspan="3">
                </td>
            </tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'LANG ALL CHECKED' ); ?>::<?php echo JText::_( 'LANG ALL CHECKED BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'LANG ALL CHECKED' ); ?>::<?php echo JText::_( 'CHOOSE LANG ALL CHECKED OR NOT' ); ?>">
						<?php echo JText::_( 'LANG ALL CHECKED' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['jf_check']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'PUBLISH AFTER EDIT' ); ?>::<?php echo JText::_( 'PUBLISH AFTER EDIT BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'PUBLISH AFTER EDIT' ); ?>::<?php echo JText::_( 'CHOOSE PUBLISH AFTER EDIT OR NOT' ); ?>">
						<?php echo JText::_( 'PUBLISH AFTER EDIT' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['jf_publish']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
    
<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'ARTICLE ICONS' ); ?></legend>
	    <table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ICON EDIT' ); ?>::<?php echo JText::_( 'CHOOSE ICON EDIT OR NOT' ); ?>">
						<?php echo JText::_( 'ICON EDIT' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['iconEdit']; ?>
				</td>
			</tr>
            <!--<tr>
            	<td colspan="3">
                </td>
            </tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php //echo JText::_( 'ICON PDF' ); ?>::<?php //echo JText::_( 'CHOOSE ICON PDF OR NOT' ); ?>">
						<?php //echo JText::_( 'ICON PDF' ); ?>:
					</span>
				</td>
				<td>
					<?php //echo $this->lists['iconPdf']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php //echo JText::_( 'ICON PRINT' ); ?>::<?php //echo JText::_( 'CHOOSE ICON PRINT OR NOT' ); ?>">
						<?php //echo JText::_( 'ICON PRINT' ); ?>:
					</span>
				</td>
				<td>
					<?php //echo $this->lists['iconPrint']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php //echo JText::_( 'ICON EMAIL' ); ?>::<?php //echo JText::_( 'CHOOSE ICON EMAIL OR NOT' ); ?>">
						<?php //echo JText::_( 'ICON EMAIL' ); ?>:
					</span>
				</td>
				<td>
					<?php //echo $this->lists['iconEmail']; ?>
				</td>
			</tr>-->
	    </table>
	</fieldset>
</div>
