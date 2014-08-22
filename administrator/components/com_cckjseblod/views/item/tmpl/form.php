<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.modal' );
$task = JRequest::getWord( 'task' );

$cid = @$this->item->id;

$typetitle = ( $this->type ) ? JText::_( $this->type->title ) : null;

$this->document->addScript( _PATH_ROOT._PATH_MOORAINBOW.'moorainbow.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_MOORAINBOW.'moorainbow.css' );
$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );
$this->document->addStyleSheet( _PATH_ROOT.'/administrator/components/com_menus/assets/type.css');
$this->document->addStyleSheet( _PATH_ROOT.'/administrator/components/com_cckjseblod/assets/css/jtree.css');
$javascript ='
	window.addEvent("domready",function(){
		
		var adminFormValidator=new FormValidator($("adminForm"));$("name").addEvent("keyup",function(k){checkavailable(this.getValue())});$("name").addEvent("change",function(c){checkavailable(this.getValue())});var JTooltips=new Tips($$(".hasTip2"),{maxTitleChars:50,fixed:false});var AjaxTooltips=new MooTips($$(".ajaxTip"),{className:"ajaxTool",fixed:true});var AjaxTooltips=new MooTips($$(".ajaxTip2"),{className:"ajaxTool",fixed:true});if($("db_tables")){$("db_tables").addEvent("change",function(dt){var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=1&table="+this.getValue();var field=$("fields-container");var a=new Ajax(url,{method:"get",update:field,onComplete:function(){}}).request();var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=2&table="+this.getValue();var secondfield=$("secondfields-container");var b=new Ajax(url,{method:"get",update:secondfield,onComplete:function(){}}).request();var 
url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=4&table="+this.getValue();var fourthfield=$("fourthfields-container");var d=new Ajax(url,{method:"get",update:fourthfield,onComplete:function(){}}).request();var
url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=5&table="+this.getValue();var fifthfield=$("fifthfields-container");var e=new Ajax(url,{method:"get",update:fifthfield,onComplete:function(){}}).request();var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=3&table="+this.getValue();var thirdfield=$("thirdfields-container");var c=new Ajax(url,{method:"get",update:thirdfield,onComplete:function(){}}).request()})}
		
		var inittypetitle = "'.$typetitle.'";
		if ( inittypetitle ) {
			$("select_type").value = inittypetitle;
		}
		
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

		// NEW All Attributes by Ajax
		
		var setAttributesLayout = function( layout, typetitle ){
			
			var task = "'.$task.'";
			var cid = "'.$cid.'";
			
			$("select_type").value=typetitle;var url="index.php?option=com_cckjseblod&controller=items&task="+task+"&cid[]="+cid+"&layout="+layout+"&format=raw";var ItemLayout=$("PushLayout");new Ajax(url,{method:"post",update:ItemLayout,evalScripts:true,onComplete:function(){var JTooltips=new Tips($$(".hasTip2"),{maxTitleChars:50,fixed:false});var AjaxTooltips=new MooTips($$(".ajaxTip2"),{className:"ajaxTool",fixed:true});SqueezeBox.initialize({});$$("a.modal").each(function(el){el.addEvent("click",function(e){new Event(e).stop();SqueezeBox.fromElement(el)})});if($("db_tables")){$("db_tables").addEvent("change",function(dt){var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=1&table="+this.getValue();var field=$("fields-container");var a=new Ajax(url,{method:"get",update:field,onComplete:function(){}}).request();var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=2&table="+this.getValue();var secondfield=$("secondfields-container");var b=new Ajax(url,{method:"get",update:secondfield,onComplete:function(){}}).request();var 
url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=4&table="+this.getValue();var fourthfield=$("fourthfields-container");var d=new Ajax(url,{method:"get",update:fourthfield,onComplete:function(){}}).request();var
url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=5&table="+this.getValue();var fifthfield=$("fifthfields-container");var e=new Ajax(url,{method:"get",update:fifthfield,onComplete:function(){}}).request();var url="index.php?option=com_cckjseblod&controller=items&format=raw&task=getTableFields&req=3&table="+this.getValue();var thirdfield=$("thirdfields-container");var c=new Ajax(url,{method:"get",update:thirdfield,onComplete:function(){}}).request()})}}}).request();
			
		};
	
	var checkavailable=function(available){var url="index.php?option=com_cckjseblod&controller=items&task=checkAvailability&format=raw&available="+available;var a=new Ajax(url,{method:"get",update:"",onComplete:function(response){if($("available").hasClass("available-enabled")){$("available").removeClass("available-enabled")}if(response&&response>0){if(!$("available").hasClass("available-failed")){if($("available").hasClass("available-passed")){$("available").removeClass("available-passed")}else if($("available").hasClass("available-enabled")){$("available").removeClass("available-enabled")}$("available").addClass("available-failed")}}else{if(!$("available").hasClass("available-passed")){if($("available").hasClass("available-failed")){$("available").removeClass("available-failed")}$("available").addClass("available-passed")}}}}).request()};function submitbutton(pressbutton){var form=document.adminForm;if(pressbutton=="cancel"){submitform(pressbutton);return}var adminFormValidator=new FormValidator($("adminForm"));if(adminFormValidator.validate()&&!$("available").hasClass("available-failed")){$("name").disabled="";submitform(pressbutton);return}}
	
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

<div class="col width-50">
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'ITEM TYPES' ); ?></legend>
		<table class="admintable" width="100%">
			<tr>
				<td width="50%" valign="top">
					<table class="admintable">
						<ul id="menu-item" class="jtree">
                            <li id="picker-node"><div class="node-open"><span></span><font color="#0B55C4"><?php echo JText::_( 'Alias' ); ?></font></div>
								<ul>
                                	<li id="link-node" class="last"><div class="node-open"><span></span><?php echo JText::_( 'Alias' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'ALIAS CUSTOM' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('alias_custom', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'ALIAS CUSTOM' ); ?>::<?php echo JText::_( 'DESCRIPTION ALIAS CUSTOM' ); ?>"><?php echo JText::_( 'ALIAS CUSTOM IT' ); ?></a></div></li>
   											<?php $typetitle = addslashes(JText::_( 'Alias' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('alias', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'Alias' ); ?>::<?php echo JText::_( 'DESCRIPTION ALIAS' ); ?>"><?php echo JText::_( 'ALIAS IT' ); ?></a></div></li>
										</ul>
									</li>
								</ul>
							</li>
							<li><div></div></li>
							<li id="file-node"><div class="node-open"><span></span><font color="#0B55C4"><?php echo JText::_( 'FILE FOLDER' ); ?></font></div>
								<ul>
									<li id="link-node"><div class="node-open"><span></span><?php echo JText::_( 'LIST' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'File' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('file', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'File' ); ?>::<?php echo JText::_( 'DESCRIPTION FILE' ); ?>"><?php echo JText::_( 'File' ); ?></a></div></li>
										<?php $typetitle = addslashes(JText::_( 'FOLDER' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('folder', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'FOLDER' ); ?>::<?php echo JText::_( 'DESCRIPTION FOLDER' ); ?>"><?php echo JText::_( 'FOLDER' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'MEDIA' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('media', '');" title="<?php echo JText::_( 'MEDIA' ); ?>::<?php echo JText::_( 'DESCRIPTION MEDIA' ); ?>"><?php echo JText::_( 'MEDIA IT' ); ?></a></div></li>
										</ul>
									</li>
									<li id="link-node" class="last"><div class="node-open"><span></span><?php echo JText::_( 'Upload' ); ?></div>
										<ul>
   											<?php $typetitle = addslashes(JText::_( 'UPLOAD IMAGE' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('upload_image', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'UPLOAD IMAGE' ); ?>::<?php echo JText::_( 'DESCRIPTION UPLOAD IMAGE' ); ?>"><?php echo JText::_( 'UPLOAD IMAGE IT' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'UPLOAD SIMPLE' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('upload_simple', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'UPLOAD SIMPLE' ); ?>::<?php echo JText::_( 'DESCRIPTION UPLOAD SIMPLE' ); ?>"><?php echo JText::_( 'UPLOAD SIMPLE IT' ); ?></a></div></li>
										</ul>
									</li>
								</ul>
							</li>
							<li><div></div></li>
							<li id="form-node"><div class="node-open"><span></span><font color="#0B55C4"><?php echo JText::_( 'FORM' ); ?></font></div>
	                            <ul>
                    				<?php $typetitle = addslashes(JText::_( 'FORM ACTION' )); ?>
									<li><div class="leaf"><span></span><strong><a class="hasTip" style="color: #6CC634" href="javascript: setAttributesLayout('form_action', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'FORM ACTION' ); ?>::<?php echo JText::_( 'DESCRIPTION FORM ACTION' ); ?>"><?php echo '<font color="#6CC634">'.JText::_('ACTION').'</font>'; ?></a></strong></div></li>
									<?php $typetitle = addslashes(JText::_( 'CAPTCHA IMAGE' )); ?>
									<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('captcha_image', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'CAPTCHA IMAGE' ); ?>::<?php echo JText::_( 'DESCRIPTION CAPTCHA' ); ?>"><?php echo JText::_('CAPTCHA IMAGE IT'); ?></a></div></li>
									<?php $typetitle = addslashes(JText::_( 'Email' )); ?>
									<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('email', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'Email' ); ?>::<?php echo JText::_( 'DESCRIPTION EMAIL' ); ?>"><?php echo JText::_( 'Email' ); ?></a></div></li>
									<?php $typetitle = addslashes(JText::_( 'SAVE FORM' )); ?>
									<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('save', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'SAVE FORM' ); ?>::<?php echo JText::_( 'DESCRIPTION SAVE FORM' ); ?>"><?php echo JText::_( 'SAVE FORM IT' ); ?></a></div></li>
									<li id="link-node"><div class="node-open"><span></span><?php echo JText::_( 'BUTTON' ); ?></div>
										<ul>
  											<?php $typetitle = addslashes(JText::_( 'BUTTON FREE' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('button_free', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'BUTTON FREE' ); ?>::<?php echo JText::_( 'DESCRIPTION BUTTON FREE' ); ?>"><?php echo JText::_('FREE IT'); ?></a></div></li>
   											<?php $typetitle = addslashes(JText::_( 'BUTTON RESET' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('button_reset', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'BUTTON RESET' ); ?>::<?php echo JText::_( 'DESCRIPTION BUTTON RESET' ); ?>"><?php echo JText::_('RESET'); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'BUTTON SUBMIT' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('button_submit', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'BUTTON SUBMIT' ); ?>::<?php echo JText::_( 'DESCRIPTION BUTTON SUBMIT' ); ?>"><?php echo JText::_('SUBMIT'); ?></a></div></li>
										</ul>
									</li>
								</ul>
								<ul>
									<li id="link-node"><div class="node-open"><span></span><?php echo JText::_( 'INPUT' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'CHECKBOX' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('checkbox', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'CHECKBOX' ); ?>::<?php echo JText::_( 'DESCRIPTION CHECKBOX' ); ?>"><?php echo JText::_( 'CHECKBOX IT' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'HIDDEN' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('hidden', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'HIDDEN' ); ?>::<?php echo JText::_( 'DESCRIPTION HIDDEN' ); ?>"><?php echo JText::_( 'HIDDEN IT' ); ?></a></div></li>
                                            <?php $typetitle = addslashes(JText::_( 'PASSWORD' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('password', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'PASSWORD' ); ?>::<?php echo JText::_( 'DESCRIPTION PASSWORD' ); ?>"><?php echo JText::_('PASSWORD'); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'RADIO' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('radio', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'RADIO' ); ?>::<?php echo JText::_( 'DESCRIPTION RADIO' ); ?>"><?php echo JText::_( 'RADIO IT' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'Text' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('text', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'TEXT' ); ?>::<?php echo JText::_( 'DESCRIPTION TEXT' ); ?>"><?php echo JText::_( 'TEXT IT' ); ?></a></div></li>
										</ul>
									</li>
								</ul>
                                <ul>
                                	<li id="picker-node"><div class="node-open"><span></span><?php echo JText::_( 'PICKER' ); ?></div>
                                        <ul>
											<?php $typetitle = addslashes(JText::_( 'CALENDAR' )); ?>
                                            <li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('calendar', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'CALENDAR' ); ?>::<?php echo JText::_( 'DESCRIPTION CALENDAR' ); ?>"><?php echo JText::_( 'CALENDAR' ); ?></a></div></li>
                                            <?php $typetitle = addslashes(JText::_( 'COLOR PICKER' )); ?>
                                            <li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('color_picker', '');" title="<?php echo JText::_( 'COLOR PICKER' ); ?>::<?php echo JText::_( 'DESCRIPTION COLOR PICKER' ); ?>"><?php echo JText::_( 'COLOR PICKER IT' ); ?></a></div></li>
                                        </ul>
                                    </li>
                                </ul>
								<ul>
									<li id="link-node"><div class="node-open"><span></span><?php echo JText::_( 'SELECT' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'SELECT DYNAMIC' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('select_dynamic', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'SELECT DYNAMIC' ); ?>::<?php echo JText::_( 'DESCRIPTION SELECT DYNAMIC' ); ?>"><?php echo JText::_( 'SELECT DYNAMIC IT' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'SELECT MULTIPLE' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('select_multiple', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'SELECT MULTIPLE' ); ?>::<?php echo JText::_( 'DESCRIPTION SELECT MULTIPLE' ); ?>"><?php echo JText::_( 'SELECT MULTIPLE IT' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'SELECT NUMERIC' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('select_numeric', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'SELECT NUMERIC' ); ?>::<?php echo JText::_( 'DESCRIPTION SELECT NUMERIC' ); ?>"><?php echo JText::_( 'SELECT NUMERIC IT' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'SELECT SIMPLE DROPDOWN' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('select_simple', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'SELECT SIMPLE DROPDOWN' ); ?>::<?php echo JText::_( 'DESCRIPTION SELECT SIMPLE' ); ?>"><?php echo JText::_( 'SELECT SIMPLE IT' ); ?></a></div></li>
										</ul>
									</li>
								</ul>
								<ul>
									<li id="link-node" class="last"><div class="node-open"><span></span><?php echo JText::_( 'TEXTAREA' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'TEXTAREA' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('textarea', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'TEXTAREA' ); ?>::<?php echo JText::_( 'DESCRIPTION TEXTAREA' ); ?>"><?php echo JText::_( 'TEXTAREA' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'WYSIWYG EDITOR' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('wysiwyg_editor', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'WYSIWYG EDITOR' ); ?>::<?php echo JText::_( 'DESCRIPTION WYSIWYG EDITOR' ); ?>"><?php echo JText::_( 'WYSIWYG EDITOR IT' ); ?></a></div></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</table>
				</td>
				<td width="50%" valign="top">
					<table class="admintable">
						<ul id="menu-item" class="jtree">
							<li id="file-node"><div class="node-open"><span></span><font color="#0B55C4"><?php echo JText::_( 'ADD ONS' ); ?></font></div>
								<ul>
									<li id="link-node"><div class="node-open"><span></span><?php echo JText::_( 'ECOMMERCE' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'ECOMMERCE CART' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('ecommerce_cart', '');" title="<?php echo JText::_( 'CART' ); ?>::<?php echo JText::_( 'DESCRIPTION CART' ); ?>"><?php echo JText::_( 'CART IT' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'ECOMMERCE CART BUTTON' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('ecommerce_cart_button', '');" title="<?php echo JText::_( 'CART BUTTON' ); ?>::<?php echo JText::_( 'DESCRIPTION CART BUTTON' ); ?>"><?php echo JText::_( 'CART BUTTON IT' ); ?></a></div></li>
										<?php $typetitle = addslashes(JText::_( 'ECOMMERCE PRICE' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('ecommerce_price', '');" title="<?php echo JText::_( 'PRICE' ); ?>::<?php echo JText::_( 'DESCRIPTION PRICE' ); ?>"><?php echo JText::_( 'PRICE IT' ); ?></a></div></li>
										</ul>
									</li>
									<li id="link-node" class="last"><div class="node-open"><span></span><?php echo JText::_( 'WEB SERVICE' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'WEB SERVICE' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('web_service', '');" title="<?php echo JText::_( 'WEB SERVICE' ); ?>::<?php echo JText::_( 'DESCRIPTION WEB SERVICE' ); ?>"><?php echo JText::_( 'WEB SERVICE IT' ); ?></a></div></li>
										</ul>
									</li>
								</ul>
							</li>
							<li><div></div></li>
                            <li id="free-node"><div class="node-open"><span></span><font color="#0B55C4"><?php echo JText::_( 'FREE' ); ?></font></div>
                            	<ul>
   									<?php $typetitle = addslashes(JText::_( 'FREE CODE' )); ?>
									<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('free_code', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'FREE CODE' ); ?>::<?php echo JText::_( 'DESCRIPTION FREE CODE' ); ?>"><?php echo JText::_('FREE CODE IT'); ?></a></div></li>
   									<?php $typetitle = addslashes(JText::_( 'FREE TEXT' )); ?>
									<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('free_text', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'FREE TEXT' ); ?>::<?php echo JText::_( 'DESCRIPTION FREE TEXT' ); ?>"><?php echo JText::_('FREE TEXT IT'); ?></a></div></li>
								</ul>
                            </li>
							<li><div></div></li>
							<li id="group-node"><div class="node-open"><span></span><font color="#0B55C4"><?php echo JText::_( 'Group' ); ?></font></div>
								<ul>
									<li id="link-node"><div class="node-open"><span></span><?php echo JText::_( 'COLLECTION' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'FIELDX ARRAY' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('field_x', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'FIELDX' ); ?>::<?php echo JText::_( 'DESCRIPTION ITEM X ARRAY' ); ?>"><?php echo JText::_( 'ITEM X ARRAY IT' ); ?></a></div></li>
  											<?php $typetitle = addslashes(JText::_( 'GROUP CONTENT TYPE' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('content_type', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'GROUPX' ); ?>::<?php echo JText::_( 'DESCRIPTION CONTENT TYPE ARRAY' ); ?>"><?php echo JText::_( 'GROUP CONTENT TYPE IT' ); ?></a></div></li>											
										</ul>
									</li>
								</ul>
								<ul>
									<li id="link-node" class="last"><div class="node-open"><span></span><?php echo JText::_( 'Layout' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'PANEL SLIDER' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('panel_slider', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'PANEL SLIDER' ); ?>::<?php echo JText::_( 'DESCRIPTION PANEL SLIDER' ); ?>"><?php echo JText::_( 'PANEL SLIDER IT' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'SUB PANEL TAB' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('sub_panel_tab', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'SUB PANEL TAB' ); ?>::<?php echo JText::_( 'DESCRIPTION SUB PANEL TAB' ); ?>"><?php echo JText::_( 'SUB PANEL TAB IT' ); ?></a></div></li>
										</ul>
									</li>
								</ul>
							</li>
                            <li><div></div></li>
<li id="picker-node"><div class="node-open"><span></span><font color="#0B55C4"><?php echo JText::_( 'Joomla' ); ?></font></div>
								<ul>
                                	<li id="link-node"><div class="node-open"><span></span><?php echo JText::_( 'CORE' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'JOOMLA CONTENT' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('joomla_content', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'JOOMLA CONTENT' ); ?>::<?php echo JText::_( 'DESCRIPTION JOOMLA CONTENT' ); ?>"><?php echo JText::_( 'JOOMLA CONTENT' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'JOOMLA MENU' )); ?>
                                            <li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('joomla_menu', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'JOOMLA MENU' ); ?>::<?php echo JText::_( 'DESCRIPTION JOOMLA MENU' ); ?>"><?php echo JText::_( 'JOOMLA MENU' ); ?></a></div></li>
                                            <?php $typetitle = addslashes(JText::_( 'JOOMLA READMORE' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('joomla_readmore', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'JOOMLA READMORE' ); ?>::<?php echo JText::_( 'DESCRIPTION JOOMLA READMORE' ); ?>"><?php echo JText::_( 'JOOMLA READMORE' ); ?></a></div></li>
                                            <?php $typetitle = addslashes(JText::_( 'JOOMLA USER' )); ?>
                                            <li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('joomla_user', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'JOOMLA USER' ); ?>::<?php echo JText::_( 'DESCRIPTION JOOMLA USER' ); ?>"><?php echo JText::_( 'JOOMLA USER' ); ?></a></div></li>
										</ul>
									</li>
								</ul>
                                <ul>
									<li id="link-node"><div class="node-open"><span></span><?php echo JText::_( 'DATABASE' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'QUERY URL' )); ?>
                                            <li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('query_url', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'QUERY URL' ); ?>::<?php echo JText::_( 'DESCRIPTION QUERY URL' ); ?>"><?php echo JText::_( 'QUERY URL IT' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'QUERY USER' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('query_user', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'QUERY USER' ); ?>::<?php echo JText::_( 'DESCRIPTION QUERY USER' ); ?>"><?php echo JText::_( 'QUERY USER IT' ); ?></a></div></li>
										</ul>
									</li>
                                </ul>
                                <ul>
									<li id="link-node"><div class="node-open"><span></span><?php echo JText::_( 'EXTENSION' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'JOOMLA MODULE' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('joomla_module', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'JOOMLA MODULE' ); ?>::<?php echo JText::_( 'DESCRIPTION JOOMLA MODULE' ); ?>"><?php echo JText::_( 'JOOMLA MODULE' ); ?></a></div></li>
   											<?php $typetitle = addslashes(JText::_( 'JOOMLA PLUGIN BUTTON' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('joomla_plugin_button', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'JOOMLA PLUGIN BUTTON' ); ?>::<?php echo JText::_( 'DESCRIPTION JOOMLA PLUGIN BUTTON' ); ?>"><?php echo JText::_( 'JOOMLA PLUGIN BUTTON IT' ); ?></a></div></li>
   											<?php $typetitle = addslashes(JText::_( 'JOOMLA PLUGIN CONTENT' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('joomla_plugin_content', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'JOOMLA PLUGIN CONTENT' ); ?>::<?php echo JText::_( 'DESCRIPTION JOOMLA PLUGIN CONTENT' ); ?>"><?php echo JText::_( 'JOOMLA PLUGIN CONTENT IT' ); ?></a></div></li>
										</ul>
									</li>
								</ul>
                                <ul>
									<li id="link-node" class="last"><div class="node-open"><span></span><?php echo JText::_( 'EXTERNAL' ); ?></div>
										<ul>
											<?php $typetitle = addslashes(JText::_( 'EXTERNAL ARTICLE' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('external_article', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'EXTERNAL ARTICLE' ); ?>::<?php echo JText::_( 'DESCRIPTION EXTERNAL ARTICLE' ); ?>"><?php echo JText::_( 'EXTERNAL ARTICLE IT' ); ?></a></div></li>
											<?php $typetitle = addslashes(JText::_( 'EXTERNAL SUBCATEGORIES' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('external_subcategories', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'EXTERNAL SUBCATEGORIES' ); ?>::<?php echo JText::_( 'DESCRIPTION EXTERNAL SUBCATEGORIES' ); ?>"><?php echo JText::_( 'EXTERNAL SUBCATEGORIES' ); ?></a></div></li>
										</ul>
									</li>
								</ul>
							</li>
                            <li><div></div></li>
								<li id="form-node"><div class="node-open"><span></span><font color="#0B55C4"><?php echo JText::_( 'SEARCH' ); ?></font></div>
	                            <ul>
                    				<?php $typetitle = addslashes(JText::_( 'SEARCH ACTION' )); ?>
									<li><div class="leaf"><span></span><strong><a class="hasTip" style="color: #6CC634" href="javascript: setAttributesLayout('search_action', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'SEARCH ACTION' ); ?>::<?php echo JText::_( 'DESCRIPTION SEARCH ACTION' ); ?>"><?php echo '<font color="#6CC634">'.JText::_( 'ACTION' ).'</font>'; ?></a></strong></div></li>
                                    <?php $typetitle = addslashes(JText::_( 'SEARCH OPERATOR' )); ?>
                                    <li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('search_operator', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'SEARCH OPERATOR' ); ?>::<?php echo JText::_( 'DESCRIPTION SEARCH OPERATOR' ); ?>"><?php echo JText::_( 'SEARCH OPERATOR IT' ); ?></a></div></li>
									<li id="link-node" class="last"><div class="node-open"><span></span><?php echo JText::_( 'SEARCH' ); ?></div>
										<ul>
  											<?php $typetitle = addslashes(JText::_( 'SEARCH GENERIC' )); ?>
											<li><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('search_generic', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'SEARCH GENERIC' ); ?>::<?php echo JText::_( 'DESCRIPTION SEARCH GENERIC' ); ?>"><?php echo JText::_('SEARCH GENERIC IT'); ?></a></div></li>
                                            <?php $typetitle = addslashes(JText::_( 'SEARCH MULTIPLE' )); ?>
											<li class="last"><div class="leaf"><span></span><a class="hasTip" href="javascript: setAttributesLayout('search_multiple', '<?php echo $typetitle; ?>');" title="<?php echo JText::_( 'SEARCH MULTIPLE' ); ?>::<?php echo JText::_( 'DESCRIPTION SEARCH MULTIPLE' ); ?>"><?php echo JText::_('SEARCH MULTIPLE IT'); ?></a></div></li>
										</ul>
									</li>
								</ul>
							</li>                        
						</ul>
					</table>				
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="col width-50">
	
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
					<input class="inputbox required minLength required-enabled" validatorProps="{minLength:3}" type="text"  id="title" name="title" maxlength="50" size="32" value="<?php echo ( $this->doCopy ) ? JText::_( 'COPYOF' ) . $this->item->title : @$this->item->title; ?>" />
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
						<input class="inputbox required validate-alphanum-lower-under minLength <?php echo ( @$this->item->id && ! $this->doCopy ) ? 'required-disabled' : 'required-enabled'; ?>" 
                        validatorProps="{minLength:3}" type="text" id="name" name="name" maxlength="50" size="32" value="<?php echo ( $this->doCopy ) ? JText::_( 'COPYOF NAME' ) . @$this->item->name : @
						$this->item->name; ?>" />
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
				<td colspan="1">
					<?php echo $this->lists['category']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="4">
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ITEM TYPE' ); ?>::<?php echo JText::_( 'ITEM TYPE BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key" colspan="2">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ITEM TYPE' ); ?>::<?php echo JText::_( 'PICK ITEM TYPE' ); ?>">
						<?php echo JText::_( 'ITEM TYPE' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox required required-disabled" type="text" id="select_type" name="select_type" maxlength="50" size="32" value="<?php echo JText::_( @$this->item->typetitle ); ?>" disabled="disabled" />
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
				echo $this->loadTemplate( $this->item->typename );
			} else if ( $this->isNew && $this->type && JFile::exists( dirname(__FILE__) .DS. 'form_' . $this->type->name . '.php' ) ) {
				echo $this->loadTemplate( $this->type->name );
			}else {
				echo $this->loadTemplate( 'default' );
			}
		?>
	    </div>
	</div>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo ( $this->doCopy ) ? '' : @$this->item->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo @$this->item->id; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>