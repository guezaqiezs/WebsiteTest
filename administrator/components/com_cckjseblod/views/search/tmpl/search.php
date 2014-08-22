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
			if ( $(searchitem+"_match") ) {
				var name = $(searchitem+"_match").name;
				var helper = "";
				var helper2 = "";
				var target = "";
				var group = "";
				if ( $(searchitem+"_helper") ) {
					var helper = $(searchitem+"_helper").value;
				}
				if ( $(searchitem+"_helper2") ) {
					var helper2 = $(searchitem+"_helper2").value;
				}
				if ( $(searchitem+"_bot") && $(searchitem+"_eot") ) {
					var target = $(searchitem+"_bot").value+"~"+$(searchitem+"_eot").value;
				}
				if ( $(searchitem+"_group") ) {
					var group = $(searchitem+"_group").value;
				}
				if ( $(searchitem+"_stage") ) {
					var stage = $(searchitem+"_stage").value;
				}
				if ( $(searchitem+"_acl0") ) {
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
				match += name+"::"+$(searchitem+"_match").value+"::"+helper+"::"+helper2+"::"+target+"::"+group+"::"+stage+"::"+acl+"||";
			}
		}
		match = match.substr(0,match.length-2);
		parent.$("searchmatch").value = match;
		
		window.parent.document.getElementById("sbox-window").close();	
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-searchs" style="float: left">
		<?php echo JText::_( 'SEARCH TYPE' ) . ': <small><small>[ '.JText::_( 'CONFIGURE SEARCH' ).' ]</small></small>'; ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'SEARCH ITEMS' ); ?></legend>
		<?php
        $tab_state_cookie_name = 'cck_search_type_search';
        $tab_state = JRequest::getInt($tab_state_cookie_name, 0, 'cookie');
        $tab_params = array('startOffset'=>$tab_state, 'onActive'=>"function(title, description){ description.setStyle('display', 'block'); title.addClass('open').removeClass('closed'); for (var i = 0, l = this.titles.length; i < l; i++) { if (this.titles[i].id == title.id) Cookie.set('$tab_state_cookie_name', i); } }");
        
        $pane =& JPane::getInstance( 'tabs', $tab_params );
        echo $pane->startPane( 'pane' );
        echo $pane->startPanel( JText::_( 'CONFIGURE SEARCH COMMON' ), 'panel1' );
        ?>
		<table class="admintable">
            <tr>
                <td width="5" align="center" class="key_jseblod">
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'TITLE' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'MATCH' ); ?>
                </td>
                <td id="helper_label" align="center" class="key_jseblod <?php echo ( @$this->parameter1 ? '' : 'display-no' )?>" style="color: #000000;" colspan="2">
                    <?php echo JText::_( 'MARKER' ); ?>
                </td>
                <td id="helper2_label" align="center" class="key_jseblod <?php echo ( @$this->parameter2 ? '' : 'display-no' )?>" style="color: #000000;" colspan="2">
                    <?php echo JText::_( 'LENGTH' ); ?>
                </td>
            </tr>
        	<?php
			if ( sizeof( $this->searchItems ) ) {
				//
				$optMatch		=	array();
				$optMatch[] 	=	JHTML::_( 'select.option', 'inherit', JText::_( 'INHERIT' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'MATCH TRUE' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'alpha', JText::_( 'ALPHABETICAL' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'any', JText::_( 'ANY WORDS' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'any_exact', JText::_( 'ANY WORDS EXACT' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'all', JText::_( 'DEFAULT PHRASE' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'each', JText::_( 'EACH WORDS' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'exact', JText::_( 'EXACT PHRASE' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'num_lower', JText::_( 'NUMERIC LOWER' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'num_higher', JText::_( 'NUMERIC HIGHER' ) );
				$optMatch[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				$optMatch[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'MATCH TRUE INDEXED' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'any_exact_index', JText::_( 'ANY WORDS EXACT INDEXED' ) );
				$optMatch[]		=	JHTML::_( 'select.option', 'exact_index', JText::_( 'EXACT PHRASE INDEXED' ) );
				$optMatch[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				$optMatch[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'MATCH FALSE' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'all_excluded', JText::_( 'DEFAULT PHRASE FALSE' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'exact_excluded', JText::_( 'EXACT PHRASE FALSE' ) );
				$optMatch[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				//
				$optTrash		=	array();
				$optTrash[] 	=	JHTML::_( 'select.option', 0, JText::_( 'NONE' ) );
				$optTrash[] 	=	JHTML::_( 'select.option', -1, JText::_( 'INHERIT' ) );
				$optTrash[] 	=	JHTML::_( 'select.option', 1, JText::_( '1' ) );
				$optTrash[] 	=	JHTML::_( 'select.option', 2, JText::_( '2' ) );
				$optTrash[] 	=	JHTML::_( 'select.option', 3, JText::_( '3' ) );
				$optTrash[] 	=	JHTML::_( 'select.option', 4, JText::_( '4' ) );
				$optTrash[] 	=	JHTML::_( 'select.option', 5, JText::_( '5' ) );
				//
				foreach ( $this->searchItems as $item ) {
					$selectMatch	=	( @$this->searchItemValues[$item->name] ) ? $this->searchItemValues[$item->name] : 'inherit';
					$listMatch		=	JHTML::_( 'select.genericlist', $optMatch, $item->name, 'size="1" class="inputbox" style="width: 150px;" onchange="setHelper( '.$item->id.', this.value )"', 'value', 'text', $selectMatch, $item->id.'_match' );
					$helper			=	( @$this->searchItemValues[$item->name.'_helper'] != '' ) ? $this->searchItemValues[$item->name.'_helper'] : '';
					$helper2		=	( @$this->searchItemValues[$item->name.'_helper2'] != '' ) ? $this->searchItemValues[$item->name.'_helper2'] : '';
					$listTrash		=	JHTML::_( 'select.genericlist', $optTrash, $item->name.'_helper2', 'size="1" class="inputbox"', 'value', 'text', 
																		$helper2, $item->id.'_helper2' );
					//
					?>
					<tr>
						<td width="5" align="right" class="key_jseblod">
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
						<td align="left">
							<?php echo $listMatch; ?>
						</td>
						<td align="left" id="<?php echo $item->id; ?>_helper_td" <?php echo ( $selectMatch == 'any' || $selectMatch == 'any_exact' || $selectMatch == 'each' || $selectMatch == 'any_exact_index' || $selectMatch == 'num_lower' || $selectMatch == 'num_higher' ) ? '' : 'class="display-no"'; ?>>
							<input type="text" id="<?php echo $item->id; ?>_helper" name="<?php echo $item->name; ?>_helper" value="<?php echo $helper; ?>" size="8" maxlength="15" />
						</td>
						<td align="left" id="<?php echo $item->id; ?>_helper_tip" <?php echo ( $selectMatch == 'any' || $selectMatch == 'any_exact' || $selectMatch == 'each' || $selectMatch == 'any_exact_index' ) ? '' : 'class="display-no"'; ?>>
							<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DIVIDER' ); ?>::<?php echo JText::_( 'EDIT DIVIDER' ); ?>">
								<?php echo _IMG_BALLOON_RIGHT; ?>
							</span>
						</td>
						<td align="left" id="<?php echo $item->id; ?>_helper_tip2" <?php echo ( $selectMatch == 'num_lower' ) ? '' : 'class="display-no"'; ?>>
							<span class="editlinktip hasTip2" title="<?php echo JText::_( 'NUMERIC LOWER' ); ?>::<?php echo JText::_( 'EDIT NUMERIC LOWER' ); ?>">
								<?php echo _IMG_BALLOON_RIGHT; ?>
							</span>
						</td>
						<td align="left" id="<?php echo $item->id; ?>_helper_tip3" <?php echo ( $selectMatch == 'num_higher' ) ? '' : 'class="display-no"'; ?>>
							<span class="editlinktip hasTip2" title="<?php echo JText::_( 'NUMERIC HIGHER' ); ?>::<?php echo JText::_( 'EDIT NUMERIC HIGHER' ); ?>">
								<?php echo _IMG_BALLOON_RIGHT; ?>
							</span>
						</td>
						<td align="left" id="<?php echo $item->id; ?>_helper2_td" <?php echo ( $selectMatch == 'any' || $selectMatch == 'any_exact' || $selectMatch == 'any_exact_index' || $selectMatch == 'each' ) ? '' : 'class="display-no"'; ?>>
                            <?php echo $listTrash; ?>
                        </td>
						<td align="left" id="<?php echo $item->id; ?>_helper2_tip" <?php echo ( $selectMatch == 'any' || $selectMatch == 'any_exact' || $selectMatch == 'any_exact_index' || $selectMatch == 'each' ) ? '': 'class="display-no"'; ?>>
							<span class="editlinktip hasTip2" title="<?php echo JText::_( 'LENGTH' ); ?>::<?php echo JText::_( 'SELECT IGNORED LENGTH' ); ?>">
								<?php echo _IMG_BALLOON_RIGHT; ?>
							</span>
						</td>
					</tr>
			<?php } } ?>
		</table><br />
		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 2px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'MATCH' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'ALPHABETICAL' ) .': '. JText::_( 'DESCRIPTION ALPHABETICAL' ) . '<br />'; ?>
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'ANY WORDS' ) .': '. JText::_( 'DESCRIPTION ANY WORDS' ) . '<br />'; ?>
					<?php echo '&nbsp;&nbsp;> ' . JText::_( 'ANY WORDS EXACT' ) .': '. JText::_( 'DESCRIPTION ANY WORDS EXACT' ) . '<br />'; ?>
					<?php echo '&nbsp;&nbsp;> ' . JText::_( 'DEFAULT PHRASE' ) .': '. JText::_( 'DESCRIPTION DEFAULT PHRASE' ) . '<br />'; ?>
					<?php echo '&nbsp;&nbsp;> ' . JText::_( 'EACH WORDS' ) .': '. JText::_( 'DESCRIPTION EACH WORDS' ) . '<br />'; ?>
					<?php echo '&nbsp;&nbsp;> ' . JText::_( 'EXACT PHRASE' ) .': '. JText::_( 'DESCRIPTION EXACT PHRASE' ) . '<br />'; ?>
					<?php echo '&nbsp;&nbsp;> ' . JText::_( 'NUMERIC LOWER' ) .': '. JText::_( 'DESCRIPTION NUMERIC LOWER' ) . '<br />'; ?>
					<?php echo '&nbsp;&nbsp;> ' . JText::_( 'NUMERIC HIGHER' ) .': '. JText::_( 'DESCRIPTION NUMERIC HIGHER' ) . '<br />'; ?>
				</td>
			</tr>
		</table>
		</span>
        <?php
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_( 'CONFIGURE SEARCH ADVANCED' ), 'panel2' );
		?>
		<table class="admintable">
            <tr>
                <td width="5" align="center" class="key_jseblod">
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'TITLE' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'TARGET' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'GROUP' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'STAGE' ); ?>
                </td>
            </tr>
        	<?php
			if ( sizeof( $this->searchItems ) ) {
				//
				$optGroup		=	array();
				$optGroup[] 	=	JHTML::_( 'select.option', '', JText::_( 'NONE' ) );
				$optGroup[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'GROUP' ) );
				$groupCType		=	HelperjSeblod_Helper::getGroupCTypes();
				if ( count( $groupCType ) ) {
					$optGroup	=	array_merge( $optGroup, $groupCType );				
				}
				$optGroup[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				//
				$optStage		=	array();
				$optStage[] 	=	JHTML::_( 'select.option', '0', JText::_( 'FINAL' ).' ' );
				$optStage[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'TEMPORARY' ) );
				$optStage[] 	=	JHTML::_( 'select.option', '1', JText::_( 'TEMP1' ) );
				$optStage[] 	=	JHTML::_( 'select.option', '2', JText::_( 'TEMP2' ) );
				$optStage[] 	=	JHTML::_( 'select.option', '3', JText::_( 'TEMP3' ) );
				$optStage[] 	=	JHTML::_( 'select.option', '4', JText::_( 'TEMP4' ) );
				$optStage[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				//
				foreach ( $this->searchItems as $item ) {
					$target			=	( @$this->searchItemValues[$item->name.'_target'] != '' ) ? explode( '~', $this->searchItemValues[$item->name.'_target'] ) : explode( '~', '~' );
					//
					$selectGroup	=	( @$this->searchItemValues[$item->name.'_group'] ) ? $this->searchItemValues[$item->name.'_group'] : '';
					$listGroup		=	JHTML::_( 'select.genericlist', $optGroup, $item->name.'_group', 'size="1" class="inputbox"', 'value', 'text', $selectGroup, $item->id.'_group' );
					//
					$selectStage	=	( @$this->searchItemValues[$item->name.'_stage'] ) ? $this->searchItemValues[$item->name.'_stage'] : 0;
					$listStage		=	JHTML::_( 'select.genericlist', $optStage, $item->name.'_stage', 'size="1" class="inputbox"', 'value', 'text', $selectStage, $item->id.'_stage' );
					?>
					<tr>
						<td width="5" align="right" class="key_jseblod">
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
                        <?php
						if ( $item->typename == 'search_generic' ) { ?>
                            <td align="center">
                            -
                            </td>
                        <?php } else { ?>
                            <td align="left">
                                <input class="inputbox" type="text"  id="<?php echo $item->id; ?>_bot" name="<?php echo $item->name?>_bot" maxlength="4"  size="4" value="<?php echo $target[0]; ?>" style="
                                	text-align: center;" />
                                <input class="inputbox" type="text"  id="<?php echo $item->id; ?>_eot" name="<?php echo $item->name?>_eot" maxlength="4"  size="4" value="<?php echo $target[1]; ?>" style="	
                                	text-align: center;" />
                            </td>
                        <?php } ?>
						<td align="left">
							<?php echo $listGroup; ?>
						</td>
						<td align="left">
							<?php echo $listStage; ?>
						</td>
					</tr>
			<?php } } ?>
		</table><br />
		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 2px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'TARGET' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'TARGET DESCRIPTION' ); ?>
                    <?php echo '<br />' . JText::_( 'TARGET DESCRIPTION EXEMPLE' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_( 'GROUP' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'GROUP DESCRIPTION' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_( 'STAGE' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'STAGE DESCRIPTION' ); ?>
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
			if ( sizeof( $this->searchItems ) ) {
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
				foreach ( $this->searchItems as $item ) {
					$selectACL		=	( @$this->searchItemValues[$item->name.'_acl'] != '' ) ? explode( ',', $this->searchItemValues[$item->name.'_acl'] )
																							   : array( '0', '18', '19', '20', '21', '23', '24', '25' );
					$listACL		=	HelperjSeblod_Helper::checkBoxList( $optACL, $item->name.'_acl', 'class="inputbox checkbox"', 'value', 'text', $selectACL, $item->id.'_acl', false, 1, 1 );
					?>
					<!--<script type="text/javascript">
					window.addEvent( "domready",function(){
						$("<?php //echo $item->id.'_acl'; ?>").multiSelect();
					 });
                    </script>-->
					<tr>
						<td width="5" align="right" class="key_jseblod">
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
						<td align="left">
							<?php echo $listACL; ?>
						</td>
					</tr>
            <?php } } ?>
		</table><br />
        <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 2px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'ACL' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'ACL DESCRIPTION' ); ?>
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
			if ( value == "any" || value == "any_exact" || value == "each" || value == "any_exact_index" || value == "num_lower" || value == "num_higher" ) {
				if ( $(id+"_helper_td").hasClass("display-no") ) {
					$(id+"_helper_td").removeClass("display-no");
				}
				if ( value == "num_lower" ) {
					if ( $(id+"_helper_tip2").hasClass("display-no") ) {
						$(id+"_helper_tip2").removeClass("display-no");
					}
					if ( ! $(id+"_helper_tip").hasClass("display-no") ) {
						$(id+"_helper_tip").addClass("display-no");
					}
					if ( ! $(id+"_helper_tip3").hasClass("display-no") ) {
						$(id+"_helper_tip3").addClass("display-no");
					}
					if ( ! $(id+"_helper2_td").hasClass("display-no") ) {
						$(id+"_helper2_td").addClass("display-no");
					}
					if ( ! $(id+"_helper2_tip").hasClass("display-no") ) {
						$(id+"_helper2_tip").addClass("display-no");
					}
				} else if ( value == "num_higher" ) {
					if ( $(id+"_helper_tip3").hasClass("display-no") ) {
						$(id+"_helper_tip3").removeClass("display-no");
					}
					if ( ! $(id+"_helper_tip").hasClass("display-no") ) {
						$(id+"_helper_tip").addClass("display-no");
					}
					if ( ! $(id+"_helper_tip2").hasClass("display-no") ) {
						$(id+"_helper_tip2").addClass("display-no");
					}
					if ( ! $(id+"_helper2_td").hasClass("display-no") ) {
						$(id+"_helper2_td").addClass("display-no");
					}
					if ( ! $(id+"_helper2_tip").hasClass("display-no") ) {
						$(id+"_helper2_tip").addClass("display-no");
					}
				} else {
					if ( $(id+"_helper_tip").hasClass("display-no") ) {
						$(id+"_helper_tip").removeClass("display-no");
					}
					if ( ! $(id+"_helper_tip2").hasClass("display-no") ) {
						$(id+"_helper_tip2").addClass("display-no");
					}
					if ( ! $(id+"_helper_tip3").hasClass("display-no") ) {
						$(id+"_helper_tip3").addClass("display-no");
					}
					if ( $(id+"_helper2_td").hasClass("display-no") ) {
						$(id+"_helper2_td").removeClass("display-no");
					}
					if ( $(id+"_helper2_tip").hasClass("display-no") ) {
						$(id+"_helper2_tip").removeClass("display-no");
					}
				}
				if ( $("helper_label").hasClass("display-no") ) {
					$("helper_label").removeClass("display-no");
				}
				if ( value == "any" || value == "any_exact" || value == "any_exact_index" || value == "each"  ) {
					if ( $("helper2_label").hasClass("display-no") ) {
						$("helper2_label").removeClass("display-no");
					}
				}
			} else {
				if ( ! $(id+"_helper_td").hasClass("display-no") ) {
					$(id+"_helper_td").addClass("display-no");
				}
				if ( ! $(id+"_helper_tip").hasClass("display-no") ) {
					$(id+"_helper_tip").addClass("display-no");
				}
				if ( ! $(id+"_helper_tip2").hasClass("display-no") ) {
					$(id+"_helper_tip2").addClass("display-no");
				}
				if ( ! $(id+"_helper_tip3").hasClass("display-no") ) {
					$(id+"_helper_tip3").addClass("display-no");
				}
				if ( ! $(id+"_helper2_td").hasClass("display-no") ) {
					$(id+"_helper2_td").addClass("display-no");
				}
				if ( ! $(id+"_helper2_tip").hasClass("display-no") ) {
					$(id+"_helper2_tip").addClass("display-no");
				}
			}
		}
	}
</script>