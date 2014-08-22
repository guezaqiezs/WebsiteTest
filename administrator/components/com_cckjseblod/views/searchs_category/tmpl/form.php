<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.modal' );

$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addScript( _PATH_ROOT._PATH_MOORAINBOW.'moorainbow.js' );

$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_MOORAINBOW.'moorainbow.css' );

$color		=	$this->category->color;
$colorchar	=	$this->category->colorchar;

$javascript ='
	window.addEvent( "domready",function(){
		
		var adminFormValidator = new FormValidator( $("adminForm") );
		
		$("title").addEvent("keyup",function(k){
			checkavailable(this.getValue());
		});
		
		$("title").addEvent("change",function(c){
			checkavailable(this.getValue());
		});
		
		var AjaxTooltips = new MooTips($$(".ajaxTip"), {
			className: "ajaxTool",
			fixed: true
		});
		
		var init = "'.$color.'";
		if ( !init ) { init = "#FFFFFF"; }
		R = HexToRGB( init, 0, 2 );
		G = HexToRGB( init, 2, 4);
		B = HexToRGB( init, 4, 6);
		
		var c1 = new MooRainbow( "colorRainbow", {
			id: "colorRainbow",
			wheel: false, 
      		"startColor": [R, G, B],
      		"onChange": function( color ) { $("color").value = color.hex; }
      	});
		
		var init2 = "'.$colorchar.'";
		if ( !init2 ) { init2 = "#FFFFFF"; }
		R2 = HexToRGB( init2, 0, 2 );
		G2 = HexToRGB( init2, 2, 4);
		B2 = HexToRGB( init2, 4, 6);
		
		var c2 = new MooRainbow( "colorcharRainbow", {
			id: "colorcharRainbow",
			wheel: false, 
		"startColor": [R2, G2, B2],
      		"onChange": function( color ) { $("colorchar").value = color.hex; }
      	});
	});
	
	function HexToRGB(hexa,left,right) {return parseInt((cutHex(hexa)).substring(left,right),16)}
	function cutHex(hexa) {return (hexa.charAt(0)=="#") ? hexa.substring(1,7):hexa}
	
	var checkavailable = function( available ){
	var url="index.php?option=com_cckjseblod&controller=searchs_categories&task=checkAvailability&format=raw&available="+available;
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
	
	function submitbutton( pressbutton ) {
		var form = document.adminForm;
		if ( pressbutton == "cancel" ) {
			submitform( pressbutton );
			return;
		}
		var adminFormValidator = new FormValidator( $("adminForm") );
		if (adminFormValidator.validate() && ! $("available").hasClass("available-failed") ) {
			submitform( pressbutton );
			return;
		}
	}
	';
	
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div class="col width-50">
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
					<input class="inputbox required required-enabled minLength" validatorProps="{minLength:3}" type="text" id="title" name="title" maxlength="50" size="32" value="<?php echo ( $this->doCopy ) ? JText::_( 'COPYOF' ) . $this->category->title : $this->category->title; ?>" />		
				</td>
			</tr>
			<tr>
            	<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key" colspan="2">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Published' ); ?>::<?php echo JText::_( 'CHOOSE PUBLISHED OR NOT' ); ?>">
						<?php echo JText::_( 'Published' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['published']; ?>
				</td>
			</tr>
			<?php if ( ! ( $this->category->id == 1 || $this->category->id == 2 ) ) { ?>
			<tr>
                <td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key" colspan="2">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Parent' ); ?>::<?php echo JText::_( 'SELECT PARENT' ); ?>">
						<?php echo JText::_( 'Parent' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['parent']; ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="4">
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Description' ); ?>::<?php echo JText::_( 'DESCRIPTION BALLOON' ); ?>">
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
	
	<fieldset class="adminform">
		<legend class="legend-border"><?php echo JText::_( 'Style' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COLOR' ); ?>::<?php echo JText::_( 'COLOR BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COLOR' ); ?>::<?php echo JText::_( 'PICK COLOR' ); ?>">
						<?php echo JText::_( 'COLOR' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox" type="text" id="color" name="color" maxlength="7" size="32" value="<?php echo $this->category->color; ?>" />&nbsp;&nbsp;
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COLOR' ); ?>::<?php echo JText::_( 'PICK COLOR' ); ?>">
						<?php echo _IMG_COLOR; ?>
					</span>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CHARACTER' ); ?>::<?php echo JText::_( 'CHARACTER BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CHARACTER' ); ?>::<?php echo JText::_( 'EDIT CHARACTER' ); ?>">
						<?php echo JText::_( 'CHARACTER' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox maxLength" validatorProps="{maxLength:2}" type="text" id="introchar" name="introchar" maxlength="3" size="32" value="<?php echo $this->category->introchar; ?>" />		
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CHARACTER COLOR' ); ?>::<?php echo JText::_( 'CHARACTER COLOR BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CHARACTER COLOR' ); ?>::<?php echo JText::_( 'PICK CHARACTER COLOR' ); ?>">
						<?php echo JText::_( 'CHARACTER COLOR' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox" type="text" id="colorchar" name="colorchar" maxlength="7" size="32" value="<?php echo $this->category->colorchar; ?>" />&nbsp;&nbsp;
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COLOR' ); ?>::<?php echo JText::_( 'PICK COLOR' ); ?>">
						<?php echo _IMG_COLORCHAR; ?>
					</span>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="clr"></div>
			
<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo ( $this->doCopy ) ? '' : @$this->category->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo @$this->category->id; ?>" />
<input type="hidden" name="name" value="<?php echo ( $this->doCopy ) ? '' : @$this->category->name; ?>" />
<input type="hidden" name="parentdb" value="<?php echo ( $this->doCopy ) ? '' : @$this->parentdb; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>