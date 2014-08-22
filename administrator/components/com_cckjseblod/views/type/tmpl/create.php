<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
jimport( 'joomla.html.pane' );
JHTML::_( 'behavior.modal' );
$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$buttons = array('Apply' 	=> array( 'Apply', 'apply_jseblod', "javascript: createContentType();", 'onclick' ),
				 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );

$into		= $this->into;
				 
$javascript ='
	window.addEvent( "domready",function(){	
		var adminFormValidator = new FormValidator( $("adminForm") );
		
		$("title").addEvent("keyup",function(k){
			checkavailable(this.getValue());
		});
		
		$("title").addEvent("change",function(c){
			checkavailable(this.getValue());
		});
	});
	
	var checkavailable = function( available ){
	var url="index.php?option=com_cckjseblod&controller=types&task=checkAvailability&format=raw&available="+available;
		var a=new Ajax(url,{
			method:"get",
			update:"",
			onComplete: function(response){ 
					if ( $("available").hasClass("available-enabled") ) {
						$("available").removeClass("available-enabled");
					}
					if ( response && response > 0 ) {
						if ( ! $("available").hasClass("available-failed") ) {
							if ( $("available").hasClass("available-passed") ) {
								$("available").removeClass("available-passed");
							} else if ( $("available").hasClass("available-enabled") ) {
								$("available").removeClass("available-enabled");
							}
							$("available").addClass("available-failed");
						}
					} else {
						if ( ! $("available").hasClass("available-passed") ) {
							if ( $("available").hasClass("available-failed") ) {
								$("available").removeClass("available-failed");
							}
							$("available").addClass("available-passed");
						}
					}
				}
		}).request();
	};
	
	// Save Button
	function createContentType() {
		var adminFormValidator = new FormValidator( $("adminForm") );
		
		if (adminFormValidator.validate() && ! $("available").hasClass("available-failed") ) {
			var into  			= "'.$into.'";
			var into_title  	= into+"_title";
			var into_category  	= into+"_category"; //Specific!
		
			var title = document.getElementById("title").value;
			var category = document.getElementById("category").value;			
			parent.document.getElementById(into).value = -1;
			parent.document.getElementById(into_title).value = title;
			parent.document.getElementById(into_category).value = category;
			window.parent.document.getElementById("sbox-window").close();
		}
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-types" style="float: left">
		<?php echo JText::_( 'CONTENT TYPE' ) . ': <small><small>[ '.JText::_( 'New' ).' ]</small></small>'; ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
		<legend class="legend-border"><?php echo JText::_( 'Details' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Title' ); ?>::<?php echo JText::_( 'TITLE BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="25" align="center" valign="middle" class="key_jseblod">
					<input class="inputbox available-enabled" type="text"  id="available" name="available" maxlength="0"  size="1" value="" disabled="disabled" style="width: 14px; height: 13px; text-align: center; cursor: default; vertical-align: middle;" />
				</td>
				<td width="100" align="right" class="keyy_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Title' ); ?>::<?php echo JText::_( 'EDIT TITLE' ); ?>">
						<?php echo JText::_( 'Title' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required required-enabled minLength" validatorProps="{minLength:3}" type="text" id="title" name="title" maxlength="50" size="32" value="" />
				</td>
			</tr>
			<tr>
            	<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key" colspan="2">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Category' ); ?>::<?php echo JText::_( 'SELECT CATEGORY' ); ?>">
						<?php echo JText::_( 'Category' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['category']; ?>
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