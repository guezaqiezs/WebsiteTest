<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
jimport( 'joomla.html.pane' );
JHTML::_( 'behavior.modal' );

//$this->document->addScript( _PATH_ROOT._PATH_MULTISELECT.'multiselect.js' );
//$this->document->addStyleSheet( _PATH_ROOT._PATH_MULTISELECT.'multiselect.css' );

$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$buttons = array('Apply'	=> array( 'Apply', 'apply_jseblod', "javascript: applySearch();", 'onclick' ),
				 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );
				 
$javascript ='	
	window.addEvent("domready",function(){
		var JTooltips = new Tips($$(".hasTip2"), { maxTitleChars: 50, fixed: false});
	});
	var applySearch= function() {
		var searchfields		=	"'.$this->searchItemIds.'";
		var searchfields_type 	=	"'.$this->searchItemTypes.'";
		var searchitems	= new Array;
		var searchitems = searchfields.split(",");
		var searchitems_type = new Array;
		var searchitems_type = searchfields_type.split(",");
		var num = searchitems.length;
		var match = "";
		
		for(var i=0; i<num; i++) {
			var searchitem = searchitems[i];
			if ( $(searchitem+"_match") ) {
				var name = $(searchitem+"_match").name;
				name =  name.substr(0,name.length-10);
				var prevalue = "";
				var helper = "";
				var helper2 = "";
				var target = "";
				var group = "";
				var live = "";
				if ( $(name) || document.adminForm.name ) {
					switch ( searchitems_type[i] ) {
						case "checkbox":
							if ( $(searchitem+"_helper") && $(searchitem+"_helper").value && ! $(searchitem+"_helper_td").hasClass("display-no") ) {
								var helper = $(searchitem+"_helper").value;
							} else {
								var helper = " ";
							}
							var elemlen = document.adminForm.elements[name].length;
							for (k=0; k<elemlen; k++) {
								if ( document.adminForm.elements[name][k] ) {
									if ( document.adminForm.elements[name][k].checked == true ) {
										prevalue += document.adminForm.elements[name][k].value;
										prevalue += helper;
									}
								}
							}
							prevalue = prevalue.substr(0,prevalue.length-1);
							break;
						case "radio":
							var elemlen = document.adminForm.elements[name].length;
							for (k=0; k<elemlen; k++) {
								if ( document.adminForm.elements[name][k] ) {
									if ( document.adminForm.elements[name][k].checked == true ) {
										prevalue = document.adminForm.elements[name][k].value;
										break;
									}
								}
							}
							break;
						case "select_multiple":
							if ( $(searchitem+"_helper") && $(searchitem+"_helper").value && ! $(searchitem+"_helper_td").hasClass("display-no") ) {
								var helper = $(searchitem+"_helper").value;
							} else {
								var helper = " ";
							}
							for ( k=0; k<$(name).length; k++ ) {
								if ( $(name).options[k].selected ) {
									prevalue += $(name).options[k].value;
									prevalue += helper;
								}
							}
							prevalue = prevalue.substr(0,prevalue.length-1);
							break;
						default:
							prevalue = $(name).value;
							break;
					}
				}
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
				if ( $(searchitem+"_live") ) {
					var live = $(searchitem+"_live").value;
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
				match += name+"::"+$(searchitem+"_match").value+"::"+prevalue+"::"+helper+"::"+helper2+"::"+target+"::"+group+"::"+live+"::"+stage+"::"+acl+"||";
			}
		}
		match = match.substr(0,match.length-2);
		parent.$("listmatch").value = match;
		
		var liststage = "req_stage";
		var liststages = "";
		for ( i = 1; i <= 4; i++ ) {
			if ( $(liststage+i) ) {
				liststages += $(liststage+i).value;
				liststages += "||";
			}
		}
		liststages = liststages.substr(0,liststages.length-2);
		parent.$("liststage").value = liststages;
		
		window.parent.document.getElementById("sbox-window").close();	
	}
	
	var setMatch = function( elem ) {
		if ( ! $(elem) ) { return; }
		var type = $(elem).value;
		var name = $(elem).name.substr(0,$(elem).name.length-5);
		var id = $(elem).id.substr(0,$(elem).id.length-5);			
		if ( type == "stage" || type == "stage_user" ) {
			if ( type == "stage" ) {
				while ($(id+"_match").firstChild) {
					$(id+"_match").removeChild($(id+"_match").firstChild);
				}
				var optGroup = document.createElement("optgroup");
				optGroup.label = "'.JText::_( 'MATCH TRUE' ).'";
				$(id+"_match").appendChild(optGroup);
				var objOption = new Option("'.JText::_( 'ANY WORDS EXACT' ).'", "any_exact");
				optGroup.appendChild(objOption);
				var optGroup = document.createElement("optgroup");
				optGroup.label = "'.JText::_( 'MATCH TRUE INDEXED' ).'";
				$(id+"_match").appendChild(optGroup);
				var objOption = new Option("'.JText::_( 'ANY INDEXED EXACT' ).'", "any_exact");
				optGroup.appendChild(objOption);
				$(id+"_match").disabled = false;
			} else {
				$(id+"_match").length = 1;
				$(id+"_match").disabled = true;
				$(id+"_match").options[0].text = "'.JText::_( 'ANY USERS EXACT' ).'";
				$(id+"_match").options[0].value = "user_any_exact";
			}
			$(id+"_match").options[0].selected = true;
		} else {
			if ( $(id+"_match").length < 5 ) {
				while ($(id+"_match").firstChild) {
					$(id+"_match").removeChild($(id+"_match").firstChild);
				}
				$(id+"_match").appendChild(new Option("'.JText::_( 'INHERIT' ).'", "inherit"));
				$(id+"_match").appendChild(new Option("'.JText::_( 'NONE' ).'", "none"));
				var optGroup = document.createElement("optgroup");
				optGroup.label = "'.JText::_( 'MATCH TRUE' ).'";
				$(id+"_match").appendChild(optGroup);
				optGroup.appendChild(new Option("'.JText::_( 'ALPHABETICAL' ).'", "alpha"));
				optGroup.appendChild(new Option("'.JText::_( 'ANY WORDS' ).'", "any"));
				optGroup.appendChild(new Option("'.JText::_( 'ANY WORDS EXACT' ).'", "any_exact"));
				optGroup.appendChild(new Option("'.JText::_( 'DEFAULT PHRASE' ).'", "all"));
				optGroup.appendChild(new Option("'.JText::_( 'EACH WORDS' ).'", "each"));
				optGroup.appendChild(new Option("'.JText::_( 'EXACT PHRASE' ).'", "exact"));
				optGroup.appendChild(new Option("'.JText::_( 'NUMERIC LOWER' ).'", "num_lower"));
				optGroup.appendChild(new Option("'.JText::_( 'NUMERIC HIGHER' ).'", "num_higher"));
				var optGroup = document.createElement("optgroup");
				optGroup.label = "'.JText::_( 'MATCH TRUE INDEXED' ).'";
				$(id+"_match").appendChild(optGroup);				
				optGroup.appendChild(new Option("'.JText::_( 'ANY WORDS EXACT INDEXED' ).'", "any_exact_index"));
				optGroup.appendChild(new Option("'.JText::_( 'EXACT PHRASE INDEXED' ).'", "exact_index"));				
				var optGroup = document.createElement("optgroup");
				optGroup.label = "'.JText::_( 'MATCH FALSE' ).'";
				$(id+"_match").appendChild(optGroup);
				optGroup.appendChild(new Option("'.JText::_( 'DEFAULT PHRASE FALSE' ).'", "any_exact"));
				optGroup.appendChild(new Option("'.JText::_( 'EXACT PHRASE FALSE' ).'", "any_exact"));
				$(id+"_match").disabled = false;
				$(id+"_match").disabled = false;
				$(id+"_match").options[0].selected = true;
			}
		}
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-searchs" style="float: left">
		<?php echo JText::_( 'SEARCH TYPE' ) . ': <small><small>[ '.JText::_( 'CONFIGURE LIST' ).' ]</small></small>'; ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'LIST ITEMS' ); ?></legend>
		<?php
        $tab_state_cookie_name = 'cck_search_type_list';
        $tab_state = JRequest::getInt($tab_state_cookie_name, 0, 'cookie');
        $tab_params = array('startOffset'=>$tab_state, 'onActive'=>"function(title, description){ description.setStyle('display', 'block'); title.addClass('open').removeClass('closed'); for (var i = 0, l = this.titles.length; i < l; i++) { if (this.titles[i].id == title.id) Cookie.set('$tab_state_cookie_name', i); } }");
        
        $pane =& JPane::getInstance( 'tabs', $tab_params );
        echo $pane->startPane( 'pane' );
        echo $pane->startPanel( JText::_( 'CONFIGURE LIST COMMON' ), 'panel1' );
        ?>
		<table class="admintable">
			<tr>
                <td width="5" align="center" class="key_jseblod">
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'TITLE' ); ?>
                </td>
                <td align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'VALUE' ); ?>
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
			require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'cckitem_search.php' );
			$obj1	=	null;
			$obj2	=	null;
			if ( sizeof( $this->searchItems ) ) {
				//
				$optMatchIdx	=	array();				
				$optMatchIdx[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'MATCH TRUE' ) );
				$optMatchIdx[] 	=	JHTML::_( 'select.option', 'any_exact', JText::_( 'ANY WORDS EXACT' ) );
				$optMatchIdx[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				$optMatchIdx[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'MATCH TRUE INDEXED' ) );
				$optMatchIdx[]	=	JHTML::_( 'select.option', 'index_any_exact', JText::_( 'ANY INDEXED EXACT' ) );
				$optMatchIdx[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				//
				$optMatch		=	array();
				$optMatch[] 	=	JHTML::_( 'select.option', 'inherit', JText::_( 'INHERIT' ) );
				$optMatch[] 	=	JHTML::_( 'select.option', 'none', JText::_( 'NONE' ) );
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
				$optMatch2		=	array();
				$optMatch2[] 	=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'MATCH TRUE' ) );
				$optMatch2[] 	=	JHTML::_( 'select.option', 'exact', JText::_( 'EXACT PHRASE' ) );
				$optMatch2[] 	=	JHTML::_( 'select.option', 'user_any_exact', JText::_( 'ANY USERS EXACT' ) );
				$optMatch2[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
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
				$i	=	0;
				foreach ( $this->searchItems as $item ) {
					$disabled	=	'';
					if ( $item->typename == 'search_operator' ) {
						// Type == Search Operator
						if ( $item->content == '((' ) {
							$operator	=	'(&rarr;';
						} else if ( $item->content == '))' ) {
							$operator	=	'&larr;)';
						} else {
							$operator	=	$item->content;
						}
						$listMatch	=	$operator.'<input type="hidden" name="'.$item->name.'_sabamatch" id="'.$item->id.'_match" value="none" />';						
					} else if ( $item->typename == 'alias_custom' && $item->stylextd == 'search_stage' ) {
						// Type == Alias Custom + Variation == Search Stage
						$selectMatch	=	( @$this->searchItemValues[$item->name] ) ? $this->searchItemValues[$item->name] : 'any_exact';
						$listMatch		=	JHTML::_( 'select.genericlist', $optMatchIdx, $item->name.'_sabamatch', 'size="1" class="inputbox" style="width: 150px;"'
										.	$disabled, 'value', 'text', $selectMatch, $item->id.'_match' );
					} else if ( @$this->searchItemValues[$item->name.'_live'] == 'stage' ) {
						// Live == Stage Article
						$selectMatch	=	( @$this->searchItemValues[$item->name] ) ? $this->searchItemValues[$item->name] : 'any_exact';
						$listMatch		=	JHTML::_( 'select.genericlist', $optMatchIdx, $item->name.'_sabamatch', 'size="1" class="inputbox" style="width: 150px;"'
										.	$disabled, 'value', 'text', $selectMatch, $item->id.'_match' );
					} else if ( @$this->searchItemValues[$item->name.'_live'] == 'stage_user' ) {
						// Live == Stage User
						$selectMatch	=	'user_any_exact';
						$disabled		=	'disabled="disabled"';
						$listMatch		=	JHTML::_( 'select.genericlist', $optMatch2, $item->name.'_sabamatch', 'size="1" class="inputbox" style="width: 150px;"'
										.	$disabled, 'value', 'text', $selectMatch, $item->id.'_match' );
					} else {
						$selectMatch	=	( @$this->searchItemValues[$item->name] ) ? $this->searchItemValues[$item->name] : 'inherit';
						$disabled		=	'';
						$listMatch		=	JHTML::_( 'select.genericlist', $optMatch, $item->name.'_sabamatch', 'size="1" class="inputbox" style="width: 150px;"'
										.	$disabled.' onchange="setHelper( '.$item->id.', this.value )"', 'value', 'text', $selectMatch, $item->id.'_match' );
					}

					$value	=	( @$this->searchItemValues[$item->name.'_value'] != '' ) ? $this->searchItemValues[$item->name.'_value'] : '';
					switch( $item->typename ) {
						case 'checkbox':
						case 'select_multiple':
							$separator	=	( @$this->searchItemValues[$item->name.'_helper'] ) ? $this->searchItemValues[$item->name.'_helper'] : ' ';
							if ( strpos( $value, $separator ) !== false ) {
								$value	=	explode( $separator, $value );
							}
							break;
						default:
							break;
					}
					$field			=	CCKjSeblodItem_Search::getData( $item, $value, 'list', 0, 0, 0, $obj1, $obj2, 0, 0 );
					// eCommerce (Cart)
					if ( $item->typename == 'search_generic' && $item->content == 'cart' ) {
						if ( $item->content == 'cart' ) {
							$value			=	( $value ) ? $value : 'id';
							$field->form	=	'<input class="inputbox text " type="text" id="'.$item->name.'" name="'.$item->name.'" maxlength="50" size="32" value="'.$value.'" />';
						} else {
							$item->typename = 'search_stage';
							$field			=	CCKjSeblodItem_Search::getData( $item, $value, 'list', 0, 0, 0, $obj1, $obj2, 0, 0 );
							$item->typename = 'search_generic';
						}
					}
					$helper			=	( @$this->searchItemValues[$item->name.'_helper'] ) ? $this->searchItemValues[$item->name.'_helper'] : '';
					$helper2		=	( @$this->searchItemValues[$item->name.'_helper2'] != '' ) ? $this->searchItemValues[$item->name.'_helper2'] : '';
					$listTrash		=	JHTML::_( 'select.genericlist', $optTrash, $item->name.'_helper2', 'size="1" class="inputbox"', 'value', 'text', 
																		$helper2, $item->id.'_helper2' );
					//
					?>
					<tr class="row<?php echo $this->searchItemValues[$item->name.'_color']; ?>">
						<td width="5" align="right" class="key_jseblod">
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
						<td align="left">
							<?php echo $field->form; ?>
						</td>
						<td align="center">
							<?php echo $listMatch; ?>
						</td>
						<td align="left" id="<?php echo $item->id; ?>_helper_td" <?php echo ( ( $selectMatch == 'any' || $selectMatch == 'any_exact' || $selectMatch == 'each' || $selectMatch == 'any_exact_index' || $selectMatch == 'num_lower' || $selectMatch == 'num_higher' ) && ! $disabled ) ? '' : 'class="display-no"'; ?>>
                        	<?php ?>
							<input type="text" id="<?php echo $item->id; ?>_helper" name="<?php echo $item->name?>_helper" value="<?php echo $helper; ?>" size="8" maxlength="15" />
						</td>
						<td align="left" id="<?php echo $item->id; ?>_helper_tip" <?php echo ( ( $selectMatch == 'any' || $selectMatch == 'any_exact' || $selectMatch == 'each' || $selectMatch == 'any_exact_index' ) && ! $disabled ) ? '' : 'class="display-no"'; ?>>
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
						<td align="left" id="<?php echo $item->id; ?>_helper2_td" <?php echo ( ( $selectMatch == 'any' || $selectMatch == 'any_exact' || $selectMatch == 'each' || $selectMatch == 'any_exact_index' ) && ! $disabled ) ? '' : 'class="display-no"'; ?>>
                            <?php echo $listTrash; ?>
                        </td>
						<td align="left" id="<?php echo $item->id; ?>_helper2_tip" <?php echo ( ( $selectMatch == 'any' || $selectMatch == 'any_exact' || $selectMatch == 'each' || $selectMatch == 'any_exact_index' ) && ! $disabled ) ? '': 'class="display-no"'; ?>>
							<span class="editlinktip hasTip2" title="<?php echo JText::_( 'LENGTH' ); ?>::<?php echo JText::_( 'SELECT IGNORED LENGTH' ); ?>">
								<?php echo _IMG_BALLOON_RIGHT; ?>
							</span>
						</td>
					</tr>
            <?php $i++; } } ?>
		</table><br />
        <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 2px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'VALUE' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'VALUE DESCRIPTION' ); ?>
				</td>
			</tr>
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
		echo $pane->startPanel( JText::_( 'CONFIGURE LIST ADVANCED' ), 'panel2' );
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
				$optLive		=	array();
				$optLive[] 		=	JHTML::_( 'select.option', '', JText::_( 'NONE' ) );
				$optLive[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'LIVE URL' ) );
				$optLive[] 		=	JHTML::_( 'select.option', 'url', JText::_( 'VAR' ) );
				$optLive[] 		=	JHTML::_( 'select.option', 'url_int', JText::_( 'VAR INT' ) );
				$optLive[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				$optLive[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'LIVE USER' ) );
				$optLive[] 		=	JHTML::_( 'select.option', 'user', JText::_( 'PROFILE' ) );
				$optLive[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
				$optLive[] 		=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'STAGE RESULTS' ) );
				$optLive[] 		=	JHTML::_( 'select.option', 'stage', JText::_( 'ARTICLE' ) );
				$optLive[] 		=	JHTML::_( 'select.option', 'stage_user', JText::_( 'USER' ) );
				$optLive[]		=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
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
				$i	=	0;
				foreach ( $this->searchItems as $item ) {
					// 					
					if ( $item->typename == 'search_operator' ) {
						// Type == Search Operator
						$disabled	=	' disabled="disabled"';
						$listLive	=	'';
					} else if ( $item->typename == 'alias_custom' && $item->stylextd == 'search_stage' ) {
						// Type == Alias Custom + Variation == Search Stage
						$selectLive	=	'stage';
						$disabled	=	' disabled="disabled"';
						$listLive	=	JHTML::_( 'select.genericlist', $optLive, $item->name.'_live', 'size="1" class="inputbox"'.$disabled, 'value', 'text', $selectLive, $item->id.'_live' );
					} else {
						$selectLive	=	( @$this->searchItemValues[$item->name.'_live'] ) ? $this->searchItemValues[$item->name.'_live'] : '';
						$disabled	=	'';
						$listLive	=	JHTML::_( 'select.genericlist', $optLive, $item->name.'_live', 'size="1" class="inputbox"'.$disabled.' onchange="setMatch(this);"', 'value', 'text', $selectLive, $item->id.'_live' );
					}					
					//
					$target			=	( @$this->searchItemValues[$item->name.'_target'] != '' ) ? explode( '~', $this->searchItemValues[$item->name.'_target'] ) : explode( '~', '~' );
					//
					$selectGroup	=	( @$this->searchItemValues[$item->name.'_group'] ) ? $this->searchItemValues[$item->name.'_group'] : '';
					$listGroup		=	JHTML::_( 'select.genericlist', $optGroup, $item->name.'_group', 'size="1" class="inputbox"', 'value', 'text', $selectGroup, $item->id.'_group' );
					//
					$selectStage	=	( @$this->searchItemValues[$item->name.'_stage'] ) ? $this->searchItemValues[$item->name.'_stage'] : 0;
					$listStage		=	JHTML::_( 'select.genericlist', $optStage, $item->name.'_stage', 'size="1" class="inputbox"', 'value', 'text', $selectStage, $item->id.'_stage' );
					?>
					<tr class="row<?php echo $this->searchItemValues[$item->name.'_color']; ?>">
						<td width="5" align="right" class="key_jseblod">
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
                        <?php if ( $item->typename == 'search_operator' ) { ?>
						<td align="center">
							-
						</td>
                        <?php } else { ?>
						<td align="left">
							<?php echo $listLive; ?>
						</td>                        
                        <?php } ?>
                        <?php
						if ( $item->typename == 'search_generic' || $disabled ) { ?>
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
                        <?php if ( $item->typename == 'search_operator' ) { ?>
						<td align="center">
							-
						</td>
                        <?php } else { ?>
						<td align="left">
							<?php echo $listGroup; ?>
						</td>
                        <?php } ?>
						<td align="left">
							<?php echo $listStage; ?>
						</td>
					</tr>
            <?php $i++; } } ?>
		</table><br />
		<table class="admintable">
        	<tr>
                <td width="5" align="right" class="key_jseblod">
                </td>
                <td width="150" align="center" class="key_jseblod" style="color: #000000;">
                    <?php echo JText::_( 'STAGE RESULTS' ); ?>
                </td>
            	<td>
					<?php echo JText::_( 'TEMP1' ) . ': ' . $this->lists['req1_stage']; ?>&nbsp;&nbsp;
					<?php echo JText::_( 'TEMP2' ) . ': ' . $this->lists['req2_stage']; ?>&nbsp;&nbsp;
					<?php echo JText::_( 'TEMP3' ) . ': ' . $this->lists['req3_stage']; ?>&nbsp;&nbsp;
					<?php echo JText::_( 'TEMP4' ) . ': ' . $this->lists['req4_stage']; ?>
                </td>
            </tr>
        </table>
        <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-top: 2px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;">
		<table class="admintable">
			<tr>
				<td>
					<strong><?php echo JText::_( 'LIVE' ); ?>:</strong><br />
                    <?php echo '&nbsp;&nbsp;> ' . JText::_( 'LIVE DESCRIPTION' ); ?>
                    <?php echo '<br />&nbsp;&nbsp;' . JText::_( 'MENU PARAMETERS' ) .': '. JText::_( 'MENU PARAMETERS DESCRIPTION' ); ?>
                    <?php echo '<br />&nbsp;&nbsp;' . JText::_( 'MODULE PARAMETERS' ) .': '. JText::_( 'MODULE PARAMETERS DESCRIPTION' ); ?>
                    <?php echo '<br />&nbsp;&nbsp;' . JText::_( 'STAGE RESULTS' ) .': '. JText::_( 'STAGE RESULTS DESCRIPTION' ); ?>
                    <?php echo '<br />&nbsp;&nbsp;' . JText::_( 'USER PROFILE' ) .': '. JText::_( 'USER PROFILE DESCRIPTION' ); ?>
				</td>
			</tr>
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
				$i	=	0;
				foreach ( $this->searchItems as $item ) {
					$selectACL		=	( @$this->searchItemValues[$item->name.'_acl'] != '' ) ? explode( ',', $this->searchItemValues[$item->name.'_acl'] )
																							   : array( '0', '18', '19', '20', '21', '23', '24', '25' );
					//$listACL		=	JHTML::_( 'select.genericlist', $optACL, $item->id.'_acl', 'size="1" class="inputbox multiple" multiple="multiple" style="width:520px;"', 'value', 'text', $selectACL, $item->id.'_acl' );
					$listACL		=	HelperjSeblod_Helper::checkBoxList( $optACL, $item->name.'_acl', 'class="inputbox checkbox"', 'value', 'text', $selectACL, $item->id.'_acl', false, 1, 1 );
					?>
					<!--<script type="text/javascript">
					window.addEvent( "domready",function(){
						$("<?php //echo $item->id.'_acl'; ?>").multiSelect();
					 });
                    </script>-->
					<tr class="row<?php echo $this->searchItemValues[$item->name.'_color']; ?>">
						<td width="5" align="right" class="key_jseblod">
						</td>
						<td width="150" align="right" class="keyy_jseblod">
							<?php echo $item->title; ?>:
						</td>
						<td align="left">
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
			if ( value == "any" || value == "any_exact"  || value == "each" || value == "any_exact_index" || value == "num_lower" || value == "num_higher" ) {
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
				if ( value == "any" || value == "any_exact"  || value == "each" || value == "any_exact_index" ) {
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