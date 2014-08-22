<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
jimport( 'joomla.html.pane' );
JHTML::_( 'behavior.modal' );

$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$buttons = array('Apply'	=> array( 'Apply', 'apply_jseblod', "javascript: applySearch();", 'onclick' ),
				 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
				 
$javascript ='	
	window.addEvent("domready",function(){
		var JTooltips = new Tips($$(".hasTip2"), { maxTitleChars: 50, fixed: false});
	});
	var applySearch= function() {
		var searchfields	=	"'.$this->searchItemIds.'";
		
		var searchitems	=	new Array;
		var searchitems = searchfields.split(",");
		var num = searchitems.length;
		var match = "";
		
		for(var i=0; i<num; i++) {
			var searchitem = searchitems[i];
			if ( $(searchitem+"_display") ) {
				var name = $(searchitem+"_display").name;
				if ( $(searchitem+"_width") ) {
					var width = $(searchitem+"_width").value;
				} else {
					var width = "";
				}
				if ( $(searchitem+"_helper") ) {
					var helper = $(searchitem+"_helper").value;
				} else {
					var helper = "";
				}
				if ( $(searchitem+"_link") ) {
					var link = $(searchitem+"_link").value;
				} else {
					var link = "";
				}
				if ( $(searchitem+"_access") ) {
					var access = $(searchitem+"_access").value;
				} else {
					var access = "";
				}
				if ( $(searchitem+"_mode") ) {
					var mode = $(searchitem+"_mode").value;
				} else {
					var mode = "";
				}
				match += name+"::"+$(searchitem+"_display").value+"::"+width+"::"+helper+"::"+link+"::"+access+"::"+mode+"||";
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
	<div class="header icon-48-searchs" style="float: left">
		<?php echo JText::_( 'SEARCH TYPE' ) . ': <small><small>[ '.JText::_( 'CONFIGURE CONTENT' ).' ]</small></small>'; ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'CONTENT FIELDS' ); ?></legend>
		<?php
        $tab_state_cookie_name = 'cck_search_type_content';
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
			$suggestion	=	'';
			if ( sizeof( $this->searchItems ) ) {
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
				$optLink[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'JAVASCRIPT' ) );
				$optLink[] 		=	JHTML::_( 'select.option', 'js_simple', JText::_( 'JS SIMPLE' ) );
				$optLink[] 		=	JHTML::_( 'select.option', 'js_advanced', JText::_( 'JS ADVANCED' ) );
				$optLink[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				$optLink[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'SEARCH' ) );
				$searchLink		=	HelperjSeblod_Helper::getSearchTypes();
				if ( count( $searchLink ) ) {
					$optLink	=	array_merge( $optLink, $searchLink );				
				}
				$optLink[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				//
				foreach ( $this->searchItems as $item ) {
					$selectDisplay	=	( @$this->searchItemValues[$item->name] ) ? $this->searchItemValues[$item->name] : '';
					$listDisplay	=	JHTML::_( 'select.genericlist', $optDisplay, $item->name, 'size="1" class="inputbox" onchange="setHelper( '.$item->id.', this.value )"', 'value', 'text', $selectDisplay, $item->id.'_display' );
					$selectLink		=	( @$this->searchItemValues[$item->name.'_link'] ) ? $this->searchItemValues[$item->name.'_link'] : '';
					$listLink		=	JHTML::_( 'select.genericlist', $optLink, $item->name.'_link', 'size="1" class="inputbox" onchange="setLink( '.$item->id.', this.value )"', 'value', 'text', $selectLink, $item->id.'_link' );
					//
					$helper			=	( @$this->searchItemValues[$item->name.'_helper'] ) ? $this->searchItemValues[$item->name.'_helper'] : '';
					//$link_helper	=	( @$this->searchItemValues[$item->name.'_link_helper'] ) ? $this->searchItemValues[$item->name.'_link_helper'] : '';
					?>
					<tr>
						<td width="5" align="right" class="key_jseblod">
	                        <?php
							if ( $item->typename == 'ecommerce_cart' && ! $item->bool3 && strpos( $item->extended, '(' ) === false && strpos( $item->extended, '[' ) === false ) {
								$suggestion	.=	'- ' . $item->extended . '<br />';
								echo '<b>*</b>';
							}
                        	?>
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
                        <td align="left">
							<?php echo $listLink; ?>
						</td>
                        <td align="left">
							<?php echo $listDisplay; ?>
						</td>
						<td align="left" id="<?php echo $item->id; ?>_helper_td" <?php echo ( $selectDisplay == 'free' ) ? '' : 'class="display-no"'; ?>>
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
        <?php if ( $suggestion ) { ?>
		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 10px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
        <table class="admintable">
        	<tr>
    	    	<td>
                	<?php echo '<b>*</b> ' . JText::_( 'SUGGESTION CONTENT IMPROVEMENTS' ) . ': <br />'. $suggestion . JText::_( 'SUGGESTION CONTENT IMPROVEMENTS 2ND' ); ?>
	            </td>
            </tr>
        </table>
        </span>
        <?php } ?>
        <?php
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_( 'CONFIGURE CONTENT ADVANCED' ), 'panel2' );
		?>
		<table class="admintable">
            <tr>
                <td width="5" align="center" class="key_jseblod">
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'TITLE' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'ACCESS CONTENT' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'MODE' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'WIDTH' ); ?>
                </td>
            </tr>
        	<?php
			if ( sizeof( $this->searchItems ) ) {
				$optAccess		=	array();
				//$optAccess[]	=	JHTML::_( 'select.option', 'none', JText::_( 'NONE' ) );
				$optAccess[]	=	JHTML::_( 'select.option', '', JText::_( 'BY NAME' ) );
				//$optAccess[]	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'BY ORDER' ) );
				//$optAccess[]	=	JHTML::_( 'select.option', 'auto', JText::_( 'AUTO' ) );
				//$optAccess[]	=	JHTML::_( 'select.option', 'ORDER_1', JText::_( 'ORDER1' ) );
				//$optAccess[]	=	JHTML::_( 'select.option', 'ORDER_2', JText::_( 'ORDER2' ) );
				//$optAccess[]	=	JHTML::_( 'select.option', 'ORDER_3', JText::_( 'ORDER3' ) );
				//$optAccess[]	=	JHTML::_( 'select.option', 'ORDER_4', JText::_( 'ORDER4' ) );
				//$optAccess[]	=	JHTML::_( 'select.option', 'ORDER_5', JText::_( 'ORDER5' ) );
				//$optAccess[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				$optAccess[]	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'BY LOCATION' ) );
				if ( $this->locations && sizeof( $this->locations ) ) {
					foreach ( $this->locations as $loc ) {
						$optAccess[]	=	JHTML::_( 'select.option', 'LOCATION_'.$loc, $loc );
					}
				}
				$optAccess[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				$optAccess[]	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'HIDDEN' ) );
				$optAccess[]	=	JHTML::_( 'select.option', 'hidden', JText::_( 'BY NAME' ) . ' (-)' );
				$optAccess[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				//
				$optMode		=	array();
				$optMode[]		=	JHTML::_( 'select.option', '', JText::_( 'LIST IN' ) );
				$optMode[]		=	JHTML::_( 'select.option', 'one', JText::_( 'LIST OUT' ) );
				//
				foreach ( $this->searchItems as $item ) {
					//
					$width			=	( @$this->searchItemValues[$item->name.'_width'] ) ? $this->searchItemValues[$item->name.'_width'] : '';
					$selectAccess	=	( @$this->searchItemValues[$item->name.'_access'] ) ? $this->searchItemValues[$item->name.'_access'] : '';
					$listAccess		=	JHTML::_( 'select.genericlist', $optAccess, $item->name.'_access', 'size="1" class="inputbox" onchange=""', 'value', 'text', $selectAccess, $item->id.'_access' );
					$selectMode		=	( @$this->searchItemValues[$item->name.'_mode'] ) ? $this->searchItemValues[$item->name.'_mode'] : '';
					$listMode		=	JHTML::_( 'select.genericlist', $optMode, $item->name.'_mode', 'size="1" class="inputbox" onchange=""', 'value', 'text', $selectMode, $item->id.'_mode' );
					?>
					<tr>
						<td width="5" align="right" class="key_jseblod">
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
						<td align="left">
							<?php echo $listAccess; ?>
						</td>
						<td align="left">
							<?php echo $listMode; ?>
						</td>
                        <td align="left">
							<input type="text" id="<?php echo $item->id; ?>_width" name="<?php echo $item->name?>_width" value="<?php echo $width; ?>" size="8" maxlength="5" />
						</td>
					</tr>
            <?php } } ?>
		</table><br />
		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 2px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'ACCESS CONTENT' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'ACCESS CONTENT DESCRIPTION' ); ?>
                    <?php echo '<br />&nbsp;&nbsp;' . JText::_( 'BY NAME' ) .':&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. JText::_( 'BY NAME EXAMPLE' ); ?>
                    <?php echo '<br />&nbsp;&nbsp;' . JText::_( 'BY LOCATION' ) .': '. JText::_( 'BY LOCATION EXAMPLE' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_( 'TYPE' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'TYPE DESCRIPTION' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_( 'WIDTH' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'WIDTH DESCRIPTION' ); ?>
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