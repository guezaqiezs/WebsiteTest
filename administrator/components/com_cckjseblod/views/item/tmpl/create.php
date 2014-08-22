<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.modal' );

//$task = NEW!!
$this->document->addScript( _PATH_ROOT._PATH_MOORAINBOW.'moorainbow.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_MOORAINBOW.'moorainbow.css' );
$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

if ( ! $this->isAuth ) {
$buttons = array( 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
} else {
$buttons = array('Save' 	=> array( 'Save', 'save_jseblod', "javascript: createContentItem();", 'onclick' ),
				 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
}

$cid 	=	@$this->item->id;
$text 	=	@$this->item->title;
$val	=	$cid . ( ( @$this->item->typename ) ? '-'.$this->item->typename : '' ) . ( ( @$this->item->category ) ? '-'.$this->item->category : '' );
$assign	=	$this->assign;
$new_f	=	@$this->new_f;
$boolAction	=	( @$this->item->type == 25 ) ? 1 : 0;

$javascript ='
	window.addEvent("domready",function(){
	
		var adminFormValidator = new FormValidator($("adminForm"));
		var JTooltips=new Tips($$(".hasTip2"),{maxTitleChars:50,fixed:false});
		$("name").addEvent("keyup",function(k){
			checkavailable(this.getValue());
		});
		
		$("name").addEvent("change",function(c){
			checkavailable(this.getValue());
		});
		
		if ( $("db_tables") ) {
			$("db_tables").addEvent("change",function(dt){
				var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=1&table="+this.getValue();
				var field = $("fields-container");
				var a=new Ajax(url,{
					method:"get",
					update:field,
					onComplete: function(){}
				}).request();
				var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=2&table="+this.getValue();
				var secondfield = $("secondfields-container");
				var b=new Ajax(url,{
					method:"get",
					update:secondfield,
					onComplete: function(){}
				}).request();
				var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=4&table="+this.getValue();
				var fourthfield = $("fourthfields-container");
				var d=new Ajax(url,{
					method:"get",
					update:fourthfield,
					onComplete: function(){}
				}).request();
				var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=5&table="+this.getValue();
				var fifthfield = $("fifthfields-container");
				var e=new Ajax(url,{
					method:"get",
					update:fifthfield,
					onComplete: function(){}
				}).request();
				var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=3&table="+this.getValue();
				var thirdfield = $("thirdfields-container");
				var c=new Ajax(url,{
					method:"get",
					update:thirdfield,
					onComplete: function(){}
				}).request();
			});
		}
		//
		var value  	= "'.$val.'";
		var new_f 	= "'.$new_f.'";
		var boolaction 	= "'.$boolAction.'";
		if ( value && new_f == 2 ) {
			window.parent.document.getElementById("sbox-window").close();
		} else if ( value && new_f == 1 ) {
			if ( boolaction == 1 ) {
				var text = "'.$text.'";
				var actionvalue = "'.$cid.'";
				addToList(parent.$("adminaction_item"), text, actionvalue);
				addToList(parent.$("siteaction_item"), text, actionvalue);
			} else {
				var text	= "'.$text.'";
				var assign	= "'.$assign.'";
				var select	= "selected_"+assign+"fields";
				addToList(parent.$(select), text, value);
				if ( assign == "admin" ) {
					var select	= "available_sitefields";
				} else {
					var select	= "available_adminfields";
				}
				addToList(parent.$(select), text, value);
				addToList(parent.$("selected_contentfields"), text, value);
				addToList(parent.$("available_emailfields"), text, value);
			}
			window.parent.document.getElementById("sbox-window").close();
		}
		
		// All Attributes by Ajax 
	    $("select_type").addEvent("change", function(t) {
			t = new Event(t).stop();
			
			if ( $("select_type").value ) {
				var layout = $("select_type").value;
			} else {
				var layout = "default";
			}
			
			var cid = "'.$cid.'";
			var url = "index.php?option=com_cckjseblod&controller=items&task=add&cid[]="+cid+"&layout="+layout+"&format=raw";
			var ItemLayout = $("PushLayout");
			new Ajax(url, {
				method: "post",
				update: ItemLayout,
				evalScripts:true,
				onComplete: function(){
						
						var JTooltips = new Tips($$(".hasTip2"), { maxTitleChars: 50, fixed: false});
						var AjaxTooltips = new MooTips($$(".ajaxTip2"), {
							className: "ajaxTool",
							fixed: true
						});
						
						SqueezeBox.initialize({});
						$$("a.modal").each(function(el) {
							el.addEvent("click", function(e) {
								new Event(e).stop();
								SqueezeBox.fromElement(el);
							});
						});
						
						if ( $("db_tables") ) {
							$("db_tables").addEvent("change",function(dt){
								var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=1&table="+this.getValue();
								var field = $("fields-container");
								var a=new Ajax(url,{
									method:"get",
									update:field,
									onComplete: function(){}
								}).request();
								var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=2&table="+this.getValue();
								var secondfield = $("secondfields-container");
								var b=new Ajax(url,{
									method:"get",
									update:secondfield,
									onComplete: function(){}
								}).request();
								var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=4&table="+this.getValue();
								var fourthfield = $("fourthfields-container");
								var d=new Ajax(url,{
									method:"get",
									update:fourthfield,
									onComplete: function(){}
								}).request();
								var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=5&table="+this.getValue();
								var fifthfield = $("fifthfields-container");
								var e=new Ajax(url,{
									method:"get",
									update:fifthfield,
									onComplete: function(){}
								}).request();
								var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=3&table="+this.getValue();
								var thirdfield = $("thirdfields-container");
								var c=new Ajax(url,{
									method:"get",
									update:thirdfield,
									onComplete: function(){}
								}).request();
							});
						}
					}
			}).request();	
		});
		
		$("title").addEvent("change", function() { generateFieldName() });
	});
	
	function generateFieldName() { 
    	var title = $("title").getProperty("value"); 
    	var name = $("name").getProperty("value"); 
		if (!name && title) { 
			name = title.toLowerCase().replace(/^\s+|\s+$/g,"").replace(/\s/g, "_").replace(/[^a-z0-9_]/gi, ""); 
      $("name").setProperty("value", name);
			checkavailable(name);
    	} 
	}
	
	function addToList(listField, newText, newValue) {
		if ( ( newValue == "" ) || ( newText == "" ) ) {
			alert("You cannot add blank values!");
		} else {
		  var len = listField.length++; // Increase the size of list and return the size
		  listField.options[len].value = newValue;
		  listField.options[len].text = newText;
		  listField.selectedIndex = len; // Highlight the one just entered (shows the user that it was entered)
		}
	}
	var checkavailable = function( available ){
		var url="index.php?option=com_cckjseblod&controller=items&task=checkAvailability&format=raw&available="+available;
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
	function createContentItem() {
		var adminFormValidator = new FormValidator( $("adminForm") );
		
		if ( adminFormValidator.validate() ) {
			$("name").disabled="";
			submitform( "recall" );
		}
	}
	var fieldExtended = function(field) {
		if ( $(field) ) {
		var field_id	=	$(field).value;
		if ( field_id ) {
			window.addEvent("domready",function(){
				var url="index.php?option=com_cckjseblod&controller=items&task=create&new_f=0&tmpl=component&cid[]="+field_id;
				SqueezeBox.fromElement(url, {handler: "iframe", size: {x: '._MODAL_WIDTH.', y: '._MODAL_HEIGHT.'}});
			});
		}
		}
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-items" style="float: left">
		<?php
		$subtitle = ( ! @$this->item->id ) ? JText::_( 'New' ) : JText::_( 'ASSIGN' );
		echo JText::_( 'CONTENT ITEM' ) . ': <small><small>[ '.$subtitle.' ]</small></small>';
		?>
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
				</td>
				<td width="100" align="right" class="key" colspan="2">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Title' ); ?>::<?php echo JText::_( 'EDIT TITLE' ); ?>">
						<?php echo JText::_( 'Title' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required minLength required-enabled" validatorProps="{minLength:3}" type="text"  id="title" name="title" maxlength="50" size="32" value="<?php echo @$this->item->title; ?>" />
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Name' ); ?>::<?php echo JText::_( 'ITEM NAME BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="25" align="center" valign="middle" class="key_jseblod">
					<input class="inputbox available-enabled" type="text"  id="available" name="available" maxlength="0"  size="1" value="" disabled="disabled" style="width: 14px; height: 13px; text-align: center; cursor: default; vertical-align: middle;" />
				</td>
				<td width="100" align="right" class="keyy_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Name' ); ?>::<?php echo JText::_( 'EDIT NAME' ); ?>">
						<?php echo JText::_( 'Name' ); ?>:
					</span>
				</td>
				<td>
                	<?php if ( @$this->item->id && ! @$this->doCopy ) { ?>
                    	<?php echo $this->item->name; ?>
                    	<input type="hidden" id="name" name="name" value="<?php echo $this->item->name; ?>" />
                    <?php } else { ?>
					<input class="inputbox required validate-alphanum-lower-under minLength <?php echo ( @$this->item->id && ! $this->doCopy ) ? 'required-disabled' : 'required-enabled'; ?>" validatorProps="{minLength:3}" type="text" id="name" name="name" maxlength="50" size="32" value="<?php echo @$this->item->name; ?>" />
                    <?php } ?>
				</td>
			</tr>
			<tr>
                <td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key" colspan="2">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY' ); ?>::<?php echo JText::_( 'SELECT CATEGORY' ); ?>">
						<?php echo JText::_( 'CATEGORY' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['category']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="4">
				</td>
			</tr>
			<tr>
            	<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key" colspan="2">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Type' ); ?>::<?php echo JText::_( 'SELECT ITEM TYPE' ); ?>">
						<?php echo JText::_( 'ITEM TYPE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['type']; ?>
				</td>
			</tr>
            <tr>
				<td colspan="4">
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Description' ); ?>::<?php echo JText::_( 'DESCRIPTION BALLOON ITEM' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key" colspan="2">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Description' ); ?>::<?php echo JText::_( 'VIEW EDIT DESCRIPTION' ); ?>">
						<?php echo JText::_( 'Description' ); ?>:
					</span>
				</td>
				<td>
					<span class="ajaxTip" title="<?php echo $this->tooltips['link_description']; ?>">
						<?php echo $this->modals['description']; ?>
					</span>
				</td>
			</tr>
		</table>
	</fieldset>
		
	<div id="ItemLayout">
		<div id="PushLayout" style="padding-top:1px;">
			<?php
			if ( @$this->item->id && JFile::exists( dirname(__FILE__) .DS. 'form_' . $this->item->typename . '.php' ) ) {
				//echo $this->loadTemplate( $this->item->typename );
				include_once( dirname(__FILE__) . DS. 'form_' . $this->item->typename . '.php' );
			} else {
            	echo $this->loadTemplate( 'default' );
			}
			?>
	    </div>
	</div>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="recall" />
<input type="hidden" name="assign" value="<?php echo $this->assign; ?>" />
<input type="hidden" name="new_f" value="<?php echo $new_f; ?>" />
<input type="hidden" name="id" value="<?php echo @$this->item->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo @$this->item->id; ?>" />

<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?><br />