<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
jimport( 'joomla.html.pane' );
JHTML::_( 'behavior.modal' );

$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$buttons = array('Apply'	=> array( 'Apply', 'apply_jseblod', "javascript: applyType();", 'onclick' ),
				 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
				 
$javascript ='	
	window.addEvent("domready",function(){
		var JTooltips = new Tips($$(".hasTip2"), { maxTitleChars: 50, fixed: false});
	});
	var applyType= function() {
		var typefields = "'.$this->typeItemIds.'";
		
		var typeitems	= new Array;
		var typeitems = typefields.split(",");
		var num = typeitems.length;
		var match = "";
		
		for(var i=0; i<num; i++) {
			var typeitem = typeitems[i];
			if ( $(typeitem+"_display") ) {
				var name = $(typeitem+"_display").name;
				if ( $(typeitem+"_submissiondisplay") ) {
					var submissiondisplay = $(typeitem+"_submissiondisplay").value;
				} else {
					var editiondisplay = "";
				}
				if ( $(typeitem+"_editiondisplay") ) {
					var editiondisplay = $(typeitem+"_editiondisplay").value;
				} else {
					var editiondisplay = "";
				}
				if ( $(typeitem+"_value") ) {
					var value = $(typeitem+"_value").value;
				} else {
					var value = "";
				}
				if ( $(typeitem+"_live") ) {
					var live = $(typeitem+"_live").value;
				} else {
					var live = "";
				}
				if ( $(typeitem+"_acl0") ) {
					var acl = "";
					var acl_name = name+"_acl";
					var elemlen = document.adminForm.elements[acl_name].length;
					for (k=0; k<elemlen; k++) {
						if ( document.adminForm.elements[acl_name][k] ) {
							if ( document.adminForm.elements[acl_name][k].checked == true ) {
								acl += document.adminForm.elements[acl_name][k].value;
								acl += ",";
							}
						}
					}
					acl = acl.substr(0,acl.length-1);
				}
				match += name+"::"+"::"+submissiondisplay+"::"+editiondisplay+"::"+value+"::"+live+"::"+acl+"||";
			}
		}
		match = match.substr(0,match.length-2);
		parent.$("siteform").value = match;
		
		window.parent.document.getElementById("sbox-window").close();	
	}
	var setDisplay= function( val, key ) {
		var typefields = "'.$this->typeItemIds.'";
		var typeitems = new Array;
		var typeitems = typefields.split(",");
		var num = typeitems.length;
		
		for(var i=0; i<num; i++) {
			var elem = $(typeitems[i]+"_"+key+"display");
			if ( elem ) {
				elem.value = val;
			}
		}
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-types" style="float: left">
		<?php echo JText::_( 'CONTENT TYPE' ) . ': <small><small>[ '.JText::_( 'CONFIGURE SITE' ).' ]</small></small>'; ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'SITE FORM ITEMS' ); ?></legend>
		<?php
        $tab_state_cookie_name = 'cck_content_type_site';
        $tab_state = JRequest::getInt($tab_state_cookie_name, 0, 'cookie');
        $tab_params = array('startOffset'=>$tab_state, 'onActive'=>"function(title, description){ description.setStyle('display', 'block'); title.addClass('open').removeClass('closed'); for (var i = 0, l = this.titles.length; i < l; i++) { if (this.titles[i].id == title.id) Cookie.set('$tab_state_cookie_name', i); } }");
        
        $pane =& JPane::getInstance( 'tabs', $tab_params );
        echo $pane->startPane( 'pane' );
        echo $pane->startPanel( JText::_( 'CONFIGURE SITE COMMON' ), 'panel1' );
        ?>
		<table class="admintable">
            <tr>
                <td width="5" align="center" class="key_jseblod">
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'TITLE' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'DISPLAY SUBMISSION' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'DISPLAY EDITION' ); ?>
                </td>
            </tr>
        	<?php
			if ( sizeof( $this->typeItems ) ) {
				//				
				$optDisplay		=	array();
				$optDisplay[] 	=	JHTML::_( 'select.option', 'none', JText::_( 'HIDDEN' ) );
				$optDisplay[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'DISPLAY' ) );
				$optDisplay[] 	=	JHTML::_( 'select.option', '', JText::_( 'FORM' ) );
				$optDisplay[] 	=	JHTML::_( 'select.option', 'disabled', JText::_( 'FORM DISABLED' ) );
				$optDisplay[] 	=	JHTML::_( 'select.option', 'value', JText::_( 'VALUE' ) );
				$optDisplay[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				//
				$f	=	0;
				foreach ( $this->typeItems as $item ) {
					if ( $item->typename == 'content_type' ) {
						$listDisplaySubmission		=	'-';						
						$listDisplayEdition		=	'-';
					} else {
						$selectDisplaySubmission	=	( @$this->typeItemValues[$item->name.'_submissiondisplay'] ) ? $this->typeItemValues[$item->name.'_submissiondisplay'] : '';
						$listDisplaySubmission		=	JHTML::_( 'select.genericlist', $optDisplay, $item->name, 'size="1" class="inputbox"', 'value', 'text', $selectDisplaySubmission, $item->id.'_submissiondisplay' );
						$selectDisplayEdition		=	( @$this->typeItemValues[$item->name.'_editiondisplay'] ) ? $this->typeItemValues[$item->name.'_editiondisplay'] : '';
						$listDisplayEdition			=	JHTML::_( 'select.genericlist', $optDisplay, $item->name, 'size="1" class="inputbox"', 'value', 'text', $selectDisplayEdition, $item->id.'_editiondisplay' );
					}
					//
					?>
					<tr>
						<td width="5" align="right" class="key_jseblod">
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
                        <td align="center">
							<?php echo $listDisplaySubmission; ?><input type="hidden" name="<?php echo $item->name; ?>" id="<?php echo $item->id.'_display'; ?>" value="" />
						</td>
                        <td align="center">
							<?php echo $listDisplayEdition; ?>
						</td>
					</tr>
            <?php $f++; } } ?>
            <tr>
            	<td>
                </td>
            	<td>
                </td>
            	<td align="middle">
                	<a href="javascript: setDisplay( '', 'submission' );"><?php echo JText::_( 'SET FORM' ); ?></a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="javascript: setDisplay( 'value', 'submission' );"><?php echo JText::_( 'SET VALUE' ); ?></a>
                </td>
            	<td align="middle">
                	<a href="javascript: setDisplay( '', 'edition' );"><?php echo JText::_( 'SET FORM' ); ?></a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="javascript: setDisplay( 'value', 'edition' );"><?php echo JText::_( 'SET VALUE' ); ?></a>
                </td>
            </tr>
		</table><br />
        <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 2px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'DISPLAY' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'DISPLAY DESCRIPTION' ); ?>
				</td>
			</tr>
		</table>
		</span>
        <?php
        echo $pane->endPanel();
        echo $pane->startPanel( JText::_( 'CONFIGURE SITE ADVANCED' ), 'panel2' );
        ?>
		<table class="admintable">
            <tr>
                <td width="5" align="center" class="key_jseblod">
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'TITLE' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'LIVE VALUE' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'VALUE' ); ?>
                </td>
            </tr>
        	<?php
			if ( sizeof( $this->typeItems ) ) {
				//
				$optLive		=	array();
				$optLive[] 		=	JHTML::_( 'select.option', '', JText::_( 'DEFAULT' ) );
				$optLive[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'LIVE URL' ) );
				$optLive[] 		=	JHTML::_( 'select.option', 'url', JText::_( 'VAR' ) );
				$optLive[] 		=	JHTML::_( 'select.option', 'url_int', JText::_( 'VAR INT' ) );
				$optLive[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				$optLive[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'LIVE USER' ) );
				$optLive[] 		=	JHTML::_( 'select.option', 'user', JText::_( 'PROFILE' ) );
				$optLive[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				$optLive[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'ECOMMERCE' ) );
				$optLive[] 		=	JHTML::_( 'select.option', 'cart', JText::_( 'CART ATTRIBUTES' ) );
				$optLive[] 		=	JHTML::_( 'select.option', 'cart_title', JText::_( 'CART TITLE' ) );
				$optLive[] 		=	JHTML::_( 'select.option', 'product', JText::_( 'PRODUCT ATTRIBUTES' ) );
				$optLive[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				//
				foreach ( $this->typeItems as $item ) {
					if ( $item->typename == 'content_type' ) {
						$listLive	=	'-';
						$value		=	'';
						$valueLive	=	'-';
						$align		=	'align="middle"';
					} else {
						$selectLive	=	( @$this->typeItemValues[$item->name.'_live'] ) ? $this->typeItemValues[$item->name.'_live'] : '';
						$listLive	=	JHTML::_( 'select.genericlist', $optLive, $item->name.'_live', 'size="1" class="inputbox"', 'value', 'text', $selectLive, $item->id.'_live' );
						$value		=	( @$this->typeItemValues[$item->name.'_value'] != '' ) ? $this->typeItemValues[$item->name.'_value'] : '';
						$valueLive	=	'<input type="text" name="'.$item->name.'_value" id="'.$item->id.'_value" maxlength="100" size="60" value="'.$value.'" />';
						$align		=	'align="left"';
					}
					?>
					<tr>
						<td width="5" align="right" class="key_jseblod">
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
						<td <?php echo $align; ?>>
							<?php echo $listLive; ?>
						</td>
                        <td <?php echo $align; ?>>
                            <?php echo $valueLive; ?>
						</td>
					</tr>
            <?php } } ?>
		</table><br />
        <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 2px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'LIVE VALUE' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'LIVE TYPE SITE DESCRIPTION' ); ?>
                    <?php echo '<br />&nbsp;&nbsp;' . JText::_( 'MENU PARAMETERS' ) .': '. JText::_( 'MENU PARAMETERS TYPE DESCRIPTION' ); ?>
                    <?php echo '<br />&nbsp;&nbsp;' . JText::_( 'USER PROFILE' ) .': '. JText::_( 'USER PROFILE TYPE DESCRIPTION' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_( 'VALUE' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'VALUE TYPE DESCRIPTION' ); ?>
				</td>
			</tr>
		</table>
		</span>
		<?php
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_( 'CONFIGURE LIST ACL' ), 'panel3' );
		?>
		<table class="admintable">
			<tr>
                <td width="5" align="center" class="key_jseblod">
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'TITLE' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'ACL' ); ?>
                </td>
            </tr>
        	<?php
			if ( sizeof( $this->typeItems ) ) {
				//
				$optACL		=	array();
				$optACL[] 	=	JHTML::_( 'select.option', '0', '<font color="green">' . JText::_( 'Public' ) . '</font>' );
				$optACL[] 	=	JHTML::_( 'select.option', '18', '<font color="red">' . JText::_( 'Registered' ) . '</font>' );
				$optACL[] 	=	JHTML::_( 'select.option', '19', '<font color="red">' . JText::_( 'Author' ) . '</font>' );
				$optACL[] 	=	JHTML::_( 'select.option', '20', '<font color="red">' . JText::_( 'Editor' ) . '</font>' );
				$optACL[] 	=	JHTML::_( 'select.option', '21', '<font color="red">' . JText::_( 'Publisher' ) . '</font>' );
				$optACL[] 	=	JHTML::_( 'select.option', '23', '<font color="black">' . JText::_( 'Manager' ) . '</font>' );
				$optACL[] 	=	JHTML::_( 'select.option', '24', '<font color="black">' . JText::_( 'Administrator' ) . '</font>' );
				$optACL[] 	=	JHTML::_( 'select.option', '25', '<font color="black">' . JText::_( 'Super Administrator' ) . '</font>' );
				//
				$i	=	0;
				foreach ( $this->typeItems as $item ) {
					if ( $item->typename == 'content_type' ) {
						$listACL	=	'-';
						$align		=	'align="center"';
					} else {
						$selectACL		=	( @$this->typeItemValues[$item->name.'_acl'] != '' ) ? explode( ',', $this->typeItemValues[$item->name.'_acl'] )
																								   : array( '0', '18', '19', '20', '21', '23', '24', '25' );
						$listACL		=	HelperjSeblod_Helper::checkBoxList( $optACL, $item->name.'_acl', 'class="inputbox checkbox"', 'value', 'text', $selectACL, $item->id.'_acl', false, 1, 1 );
						$align		=	'align="left"';
					}
					?>
					<!--<script type="text/javascript">
					window.addEvent( "domready",function(){
						$("<?php //echo $item->id.'_acl'; ?>").multiSelect();
					 });
                    </script>-->
					<tr class="row<?php echo $this->typeItemValues[$item->name.'_color']; ?>">
						<td width="5" align="right" class="key_jseblod">
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
						<td <?php echo $align; ?>>
							<?php echo $listACL; ?>
						</td>
					</tr>
            <?php $i++; } } ?>
		</table><br />
        <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 2px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'ACL' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'ACL TYPE DESCRIPTION' ); ?>
				</td>
			</tr>
		</table>
		</span>
		<?php
        echo $pane->endPanel();
        echo $pane->endPane();
        ?>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>