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
		
		var typeitems =	new Array;
		var typeitems = typefields.split(",");
		var num = typeitems.length;
		var match = "";
		
		for(var i=0; i<num; i++) {
			var typeitem = typeitems[i];
			if ( $(typeitem+"_display") ) {
				var name = $(typeitem+"_display").name;
				if ( $(typeitem+"_bool") ) {
					var bool = $(typeitem+"_bool").value;
				} else {
					var bool = 0;
				}
				if ( $(typeitem+"_helper") ) {
					var helper = $(typeitem+"_helper").value;
				} else {
					var helper = "";
				}
				if ( $(typeitem+"_link") ) {
					var link = $(typeitem+"_link").value;
				} else {
					var link = "";
				}
				match += name+"::"+$(typeitem+"_display").value+"::"+bool+"::"+helper+"::"+link+"||";
			}
		}
		match = match.substr(0,match.length-2);
		parent.$("contentdisplay").value = match;
		
		window.parent.document.getElementById("sbox-window").close();	
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-types" style="float: left">
		<?php echo JText::_( 'CONTENT TYPE' ) . ': <small><small>[ '.JText::_( 'CONFIGURE CONTENT' ).' ]</small></small>'; ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'CONTENT FIELDS' ); ?></legend>
		<?php
        $tab_state_cookie_name = 'cck_content_type_content';
        $tab_state = JRequest::getInt($tab_state_cookie_name, 0, 'cookie');
        $tab_params = array('startOffset'=>$tab_state, 'onActive'=>"function(title, description){ description.setStyle('display', 'block'); title.addClass('open').removeClass('closed'); for (var i = 0, l = this.titles.length; i < l; i++) { if (this.titles[i].id == title.id) Cookie.set('$tab_state_cookie_name', i); } }");
        
        $pane =& JPane::getInstance( 'tabs', $tab_params );
        echo $pane->startPane( 'pane' );
        echo $pane->startPanel( JText::_( 'CONFIGURE CONTENT COMMON' ), 'panel1' );
        ?>
		<table class="admintable">
            <tr>
                <td width="5" align="center" class="key_jseblod">
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'TITLE' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'LINK' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'TYPOGRAPHY' ); ?>
                </td>
                <td id="helper_label" align="center" class="key_jseblod <?php echo ( @$this->parameter1 ? '' : 'display-no' )?>" style="color: #000000;">
                    <?php echo JText::_( 'HTML' ); ?>
                </td>
            </tr>
        	<?php
			if ( sizeof( $this->typeItems ) ) {
				//
				$optDisplay		=	array();
				$optDisplay[] 	=	JHTML::_( 'select.option', '', JText::_( 'NONE' ) );
				$optDisplay[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'PRESETS' ) );
				$optDisplay[] 	=	JHTML::_( 'select.option', 'bold', JText::_( 'BOLD' ) );
				$optDisplay[] 	=	JHTML::_( 'select.option', 'italic', JText::_( 'ITALIC' ) );
				$optDisplay[] 	=	JHTML::_( 'select.option', 'underline', JText::_( 'UNDERLINE' ) );
				$optDisplay[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				$optDisplay[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'MORE' ) );
				$optDisplay[] 	=	JHTML::_( 'select.option', 'free', JText::_( 'HTML' ) );
				$optDisplay[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				//
				$optLink		=	array();
				$optLink[] 		=	JHTML::_( 'select.option', '', JText::_( 'NONE' ) );
				$optLink[] 		=	JHTML::_( 'select.option', 'article', JText::_( 'ARTICLE' ) );
				$optLink[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'SEARCH' ) );
				$searchLink		=	HelperjSeblod_Helper::getSearchTypes();
				if ( count( $searchLink ) ) {
					$optLink	=	array_merge( $optLink, $searchLink );				
				}
				$optLink[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				//
				foreach ( $this->typeItems as $item ) {

					$selectDisplay	=	( @$this->typeItemValues[$item->name] ) ? $this->typeItemValues[$item->name] : '';
					$listDisplay	=	JHTML::_( 'select.genericlist', $optDisplay, $item->name, 'size="1" class="inputbox" onchange="setHelper( '.$item->id.', this.value )"', 'value', 'text', $selectDisplay, $item->id.'_display' );
					$selectLink		=	( @$this->typeItemValues[$item->name.'_link'] ) ? $this->typeItemValues[$item->name.'_link'] : '';
					$listLink		=	JHTML::_( 'select.genericlist', $optLink, $item->name.'_link', 'size="1" class="inputbox" onchange="setLink( '.$item->id.', this.value )"', 'value', 'text', $selectLink, $item->id.'_link' );
					//
					$helper			=	( @$this->typeItemValues[$item->name.'_helper'] ) ? $this->typeItemValues[$item->name.'_helper'] : '';
					//$link_helper	=	( @$this->typeItemValues[$item->name.'_link_helper'] ) ? $this->typeItemValues[$item->name.'_link_helper'] : '';
					?>
					<tr>
						<td width="5" align="right" class="key_jseblod">
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
                        <td>
							<?php echo $listLink; ?>
						</td>
                        <td>
							<?php echo $listDisplay; ?>
						</td>
						<td id="<?php echo $item->id; ?>_helper_td" <?php echo ( $selectDisplay == 'free' ) ? '' : 'class="display-no"'; ?>>
							<textarea id="<?php echo $item->id; ?>_helper" name="<?php echo $item->name?>_helper" cols="40" rows="3" style="overflow:hidden;" ><?php echo @$helper; ?></textarea>
						</td>
					</tr>
            <?php } } ?>
		</table><br />
        <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 2px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'LINK' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'LINK DESCRIPTION' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_( 'TYPOGRAPHY' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'TYPOGRAPHY DESCRIPTION' ); ?>
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

<script type="text/javascript">
	function setHelper( id, value) {
		if ( id ) {
			if ( value == "free" ) {
				if ( $(id+"_helper_td").hasClass("display-no") ) {
					$(id+"_helper_td").removeClass("display-no");
				}
				if ( $("helper_label").hasClass("display-no") ) {
					$("helper_label").removeClass("display-no");
				}
			} else {
				if ( ! $(id+"_helper_td").hasClass("display-no") ) {
					$(id+"_helper_td").addClass("display-no");
				}
			}
		}
	}
</script>