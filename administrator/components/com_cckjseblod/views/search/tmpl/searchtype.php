<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );

$buttons = array('Save'	=> array( 'Save', 'save_jseblod', "javascript: saveAutoType();", 'onclick' ), 
				 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );

$javascript ='
	window.addEvent( "domready",function(){	
		var adminFormValidator = new FormValidator( $("adminForm") );
	});

	function saveAutoType() {
		var adminFormValidator = new FormValidator( $("adminForm") );
		
		if (adminFormValidator.validate() ) {
						
			parent.$("autotype_id").value = $("content_type").value;
			parent.$("autotype_list").value = $("items_list").value;
			parent.$("autotype_content").value = $("items_content").value;
			parent.$("title").value = "   ";
			
			parent.submitbutton("autoType");
			window.parent.document.getElementById("sbox-window").close();
		}
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-searchs" style="float: left">
		<?php echo JText::_( 'SEARCH TYPE' ) . ': <small><small>[ '.JText::_( 'AUTO TYPE' ).' ]</small></small>'; ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
       
    <fieldset class="adminform">
		<legend class="legend-border"><?php echo JText::_( 'AUTO TYPE' ); ?></legend>
		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 8px; margin-left: 2px; margin-right: 2px; margin-bottom:8px;">
        <table class="admintable">
            <tr>
                <td>
                    <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                    <?php echo JText::_( 'DESCRIPTION AUTO SEARCH TYPE' ); ?>
                </td>
            </tr>
        </table>
        </span>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT TYPE' ); ?>::<?php echo JText::_( 'SELECT CONTENT TYPE' ); ?>">
						<?php echo JText::_( 'CONTENT TYPE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['content_type']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'LIST' ); ?>::<?php echo JText::_( 'SELECT LIST MODE' ); ?>">
						<?php echo JText::_( 'LIST' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['items_list']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEARCH' ); ?>::<?php echo JText::_( 'SELECT SEARCH MODE' ); ?>">
						<?php echo JText::_( 'SEARCH' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['mode']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT' ); ?>::<?php echo JText::_( 'SELECT CONTENT MODE' ); ?>">
						<?php echo JText::_( 'CONTENT' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['items_content']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="" />
<input type="hidden" name="cid[]" value="" />
<input type="hidden" name="name" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?><br />