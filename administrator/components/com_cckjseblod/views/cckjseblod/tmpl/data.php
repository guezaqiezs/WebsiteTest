<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
jimport( 'joomla.html.pane' );

$dateNow 	=& JFactory::getDate();
$dateTime	= $dateNow->toFormat( '%Y_%m_%d' );

$alertExport	=	JText::sprintf( 'Please make a selection from the list to', JText::_( 'EXPORT' ) );

$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$buttons = array( 'Export'	=> array( 'Export', 'export_jseblod', "javascript: dataProcess('export');", 'onclick' ),
				  'Import'	=> array( 'Import', 'import_jseblod', "javascript: dataProcess('import');", 'onclick' ),
   				  'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				  'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );

$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );

$javascript = '
	window.addEvent( "domready",function(){
		var adminFormValidator = new FormValidator($("adminForm"));
		var JTooltips=new Tips($$(".hasTip2"),{maxTitleChars:50,fixed:false});
	});

	// Save Button
	function dataProcess(task) {
		var adminFormValidator = new FormValidator( $("adminForm") );

		if ( adminFormValidator.validate() ) {
			if ( task == "export" ) {
				var selection = $("selection").value;
				if ( selection ) {
					if ( $("toggle").value == "4" && $("output0").checked ) {
						if ( $("as-htmlcode").hasClass("display-no") ) {
							$("as-htmlcode").removeClass("display-no");					
							var selection = $("selection").value;
							url="index.php?option=com_cckjseblod&task=dataExportHTML&format=raw&selection="+selection;
							var a=new Ajax(url,{
								 method:"post",
								 update:"",
								 onComplete: function(response){ 
									$("htmlcode").value = response;
									$("htmlcode").focus();
									$("htmlcode").select();	
								 }
							}).request();				
						}
					} else {
						submitbutton("dataExportProcess");
					}
				} else {
					alert( "'.$alertExport.'" );
				}
			} else {
				submitbutton("dataImportProcess");
			}
		}
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form enctype="multipart/form-data" action="index.php" method="post" id="adminForm" name="adminForm">

<div>
	<fieldset class="adminform modal-bg-toolbar">
	<div class="header icon-48-data_jseblod" style="float: left">
		<?php echo JText::_( 'IMPORT EXPORT' ); ?>
	</div>
	<div style="float: right">
		<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
	</div>
	</fieldset>
	
    <fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'DATA PROCESS' ); ?></legend>
        
	<?php
    $tab_state_cookie_name = 'cck_data';
    $tab_state = JRequest::getInt($tab_state_cookie_name, 1, 'cookie');
    $tab_params = array('startOffset'=>$tab_state, 'onActive'=>"function(title, description){ description.setStyle('display', 'block'); title.addClass('open').removeClass('closed'); for (var i = 0, l = this.titles.length; i < l; i++) { if (this.titles[i].id == title.id) { Cookie.set('$tab_state_cookie_name', i); if ( i == 1 || i == 3 ) { if ( ! $('toolbar-export_jseblod').hasClass('display-no') ) { $('toolbar-export_jseblod').addClass('display-no'); } if ( $('toolbar-import_jseblod').hasClass('display-no') ) { $('toolbar-import_jseblod').removeClass('display-no'); } } else { if ( ! $('toolbar-import_jseblod').hasClass('display-no') ) { $('toolbar-import_jseblod').addClass('display-no'); } if ( $('toolbar-export_jseblod').hasClass('display-no') ) { $('toolbar-export_jseblod').removeClass('display-no'); } } $('toggle').value = i; } } } ");
    
    $pane =& JPane::getInstance( 'tabs', $tab_params );
    echo $pane->startPane( 'pane' );
    echo $pane->startPanel( _IMG_EXPORT .'&nbsp;&nbsp;&nbsp;'. JText::_( 'EXPORT CSV' ), 'panel1' );
    ?>

    <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-bottom: 10px;">
    <table class="admintable" >
        <tr>
            <td>
                <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                <?php echo JText::_( 'DESCRIPTION DATA PROCESS EXPORT CSV' ); ?>
            </td>
        </tr>
    </table>
    </span>
    <table class="admintable">
		<!--<tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php //echo JText::_( 'FIELDS' ); ?>::<?php //echo JText::_( 'SELECT FIELDS' ); ?>">
                    <?php //echo JText::_( 'FIELDS' ); ?>:
                </span>
            </td>
            <td>
                <?php //echo $this->lists['fields']; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
            </td>
        </tr>-->
        <tr>
            <td>
                <?php echo '<font color="gray">(*)&nbsp;' . JText::_( 'COMING SOON' ) . '</font>'; ?>
            </td>
        </tr>
    </table>
    
    <?php
    echo $pane->endPanel();
    echo $pane->startPanel( _IMG_IMPORT .'&nbsp;&nbsp;&nbsp;' . JText::_( 'IMPORT CSV' ), 'panel2' );
    ?>

    <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-bottom: 10px;">
    <table class="admintable" >
        <tr>
            <td>
                <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                <?php echo JText::_( 'DESCRIPTION DATA PROCESS IMPORT CSV' ); ?>
            </td>
        </tr>
    </table>
    </span>
    <table class="admintable">
        <tr>
            <td width="25" align="right" class="key_jseblod">
                <span class="editlinktip hasTip2" title="<?php echo JText::_( 'CONTENT TYPE' ); ?>::<?php echo JText::_( 'CONTENT TYPE OVERRIDE BALLOON' ); ?>">
                    <?php echo _IMG_BALLOON_LEFT; ?>
                </span>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT TYPE' ); ?>::<?php echo JText::_( 'SELECT CONTENT TYPE' ); ?>">
                    <?php echo JText::_( 'CONTENT TYPE' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['ctype']; ?>&nbsp;&nbsp;<span id="new-type-label" class="keytext_jseblod display-no"><?php echo JText::_( 'Title' ); ?>:&nbsp;&nbsp;</span><input class="inputbox required required-enabled display-no" type="text" id="new-type" name="new_type" maxlength="250" size="32" value="" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
            </td>
        </tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'STATE' ); ?>::<?php echo JText::_( 'SELECT STATE' ); ?>">
                    <?php echo JText::_( 'PUBLICATION STATE' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['state']; ?>
            </td>
        </tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
                <span class="editlinktip hasTip2" title="<?php echo JText::_( 'CATEGORY' ); ?>::<?php echo JText::_( 'CATEGORY OVERRIDE BALLOON' ); ?>">
                    <?php echo _IMG_BALLOON_LEFT; ?>
                </span>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY' ); ?>::<?php echo JText::_( 'SELECT CATEGORY' ); ?>">
                    <?php echo JText::_( 'CATEGORY' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['catid']; ?>
            </td>
        </tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'AUTHOR' ); ?>::<?php echo JText::_( 'SELECT AUTHOR' ); ?>">
                    <?php echo JText::_( 'AUTHOR' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['author']; ?>
            </td>
        </tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'ACCESS' ); ?>::<?php echo JText::_( 'SELECT ACCESS' ); ?>">
                    <?php echo JText::_( 'ACCESS' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['access']; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
            </td>
        </tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
                <span class="editlinktip hasTip2" title="<?php echo JText::_( 'SEPARATOR' ); ?>::<?php echo JText::_( 'SEPARATOR WINDOWS BALLOON' ); ?>">
                    <?php echo _IMG_BALLOON_LEFT; ?>
                </span>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'SEPARATOR' ); ?>::<?php echo JText::_( 'SELECT SEPARATOR' ); ?>">
                    <?php echo JText::_( 'SEPARATOR' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['separator']; ?>
            </td>
        </tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
                <span class="editlinktip hasTip2" title="<?php echo JText::_( 'FORCE UTF8' ); ?>::<?php echo JText::_( 'FORCE UTF8 BALLOON' ); ?>">
                    <?php echo _IMG_BALLOON_LEFT; ?>
                </span>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'FORCE UTF8' ); ?>::<?php echo JText::_( 'CHOOSE ENABLE OR NOT' ); ?>">
                    <?php echo JText::_( 'FORCE UTF8' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['utf8']; ?>
            </td>
        </tr>
        <tr>
            <td width="25" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'CSV FILE' ); ?>::<?php echo JText::_( 'UPLOAD CSV FILE' ); ?>">
                    <?php echo JText::_( 'CSV FILE' ); ?>:
                </span>
            </td>
            <td>
                <input class="input_box" type="file" id="import_file" name="import_file" size="32" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
            </td>
        </tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
            	<?php echo JText::_( 'UPDATE MERGE OVERWRITE'); ?>
			</td>
        </tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
                <span class="editlinktip hasTip2" title="<?php echo JText::_( 'MODE' ); ?>::<?php echo JText::_( 'IMPORT CSV MODE BALLOON' ); ?>">
                    <?php echo _IMG_BALLOON_LEFT; ?>
                </span>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'MODE' ); ?>::<?php echo JText::_( 'SELECT MODE' ); ?>">
                    <?php echo JText::_( 'MODE' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['control']; ?>
            </td>
        </tr>
        <?php if ( $this->langEnabled ) { ?>
        <tr>
        	<td colspan="3">
            </td>
        </tr>
        <tr>
        	<td colspan="3" class="keytext_jseblod">
            	<?php echo JText::_( 'LANGUAGES JOOMFISH'); ?>
			</td>
        </tr>
        <tr>
            <td width="25" align="right" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'LANG' ); ?>::<?php echo JText::_( 'SELECT LANGUAGE' ); ?>">
                    <?php echo JText::_( 'LANG' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['lang']; ?>
            </td>
        </tr>
        <?php } ?>
    </table>
    
	<?php
    echo $pane->endPanel();
    echo $pane->startPanel( _IMG_EXPORT .'&nbsp;&nbsp;&nbsp;' . JText::_( 'EXPORT XML' ), 'panel3' );
    ?>

    <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-bottom: 10px;">
    <table class="admintable" >
        <tr>
            <td>
                <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                <?php echo JText::_( 'DESCRIPTION DATA PROCESS EXPORT XML' ); ?>
            </td>
        </tr>
    </table>
    </span>
    <table class="admintable">
        <tr>
            <td>
                <?php echo '<font color="gray">(*)&nbsp;' . JText::_( 'COMING SOON' ) . '</font>'; ?>
            </td>
        </tr>
    </table>
    
	<?php
    echo $pane->endPanel();
    echo $pane->startPanel( _IMG_IMPORT .'&nbsp;&nbsp;&nbsp;' . JText::_( 'IMPORT XML' ), 'panel4' );
    ?>

    <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-bottom: 10px;">
    <table class="admintable" >
        <tr>
            <td>
                <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                <?php echo JText::_( 'DESCRIPTION DATA PROCESS IMPORT XML' ); ?>
            </td>
        </tr>
    </table>
    </span>
    <table class="admintable">
        <tr>
            <td>
                <?php echo '<font color="gray">(*)&nbsp;' . JText::_( 'COMING SOON' ) . '</font>'; ?>
            </td>
        </tr>
    </table>
    
    <?php
    echo $pane->endPanel();
    echo $pane->startPanel( _IMG_EXPORT .'&nbsp;&nbsp;&nbsp;' . JText::_( 'EXPORT HTML' ), 'panel5' );
    ?>

    <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-bottom: 10px;">
    <table class="admintable" >
        <tr>
            <td>
                <strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
                <?php echo JText::_( 'DESCRIPTION DATA PROCESS EXPORT HTML' ); ?>
            </td>
        </tr>
    </table>
    </span>
    <table class="admintable">
    	<?php if ( ! $this->actionMode ) { ?>
        <tr>
            <td width="25" align="right" class="key_jseblod">
                <span class="editlinktip hasTip2" title="<?php echo JText::_( 'OUTPUT' ); ?>::<?php echo JText::_( 'OUTPUT BALLOON' ); ?>">
                    <?php echo _IMG_BALLOON_LEFT; ?>
                </span>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'OUTPUT' ); ?>::<?php echo JText::_( 'SELECT OUTPUT' ); ?>">
                    <?php echo JText::_( 'OUTPUT' ); ?>:
                </span>
            </td>
            <td>
                <?php echo $this->lists['output']; ?>
            </td>
        </tr>
        <tr id="as-filename" class="display-no">
            <td width="25" align="right" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'FILENAME' ); ?>::<?php echo JText::_( 'EDIT FILENAME' ); ?>">
                    <?php echo JText::_( 'FILENAME' ); ?>:
                </span>
            </td>
            <td>
                <input class="inputbox" type="text" id="filename" name="filename" maxlength="250" size="32" value="<?php echo 'Export_'.$dateTime; ?>" />&nbsp;.&nbsp;<?php echo $this->lists['ext']; ?>
            </td>
        </tr>
        <tr id="as-htmlcode" class="display-no">
            <td width="25" align="right" class="key_jseblod">
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'HTML CODE' ); ?>::<?php echo JText::_( 'COPY HTML CODE' ); ?>">
                    <?php echo JText::_( 'HTML CODE' ); ?>:
                </span>
            </td>
            <td>
            	<textarea class="inputbox" id="htmlcode" name="htmlcode" style="width:100%;height:220px" cols="110" rows="25" ></textarea>
            </td>
        </tr>
        <?php } else { ?>
		<tr>
            <td>
                <?php echo '<font color="gray">(*)&nbsp;' . JText::_( 'AVAILABLE FROM ARTICLE MANAGER ONLY' ) . '</font>'; ?>
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php
    echo $pane->endPanel();
    echo $pane->endPane();
    ?>
    
   	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_cckjseblod" />
<input type="hidden" name="controller" value="" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="toggle" value="0" id="toggle" />
<input type="hidden" name="selection" id="selection" value="<?php echo $this->selection; ?>" />
<input type="hidden" name="action_mode" value="<?php echo $this->actionMode; ?>" />
<input type="hidden" name="import_csv[content_type_id]" value="0" />

<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>

<script type="text/javascript">
	$("content_type").addEvent("change", function(c) {
		c = new Event(c).stop();
		
		var layout = $("content_type").value;
		
		if ( layout != '' && layout == 0 ) {
			if ( $("new-type").hasClass("display-no") ) {
				$("new-type").removeClass("display-no");
			}
			if ( $("new-type-label").hasClass("display-no") ) {
				$("new-type-label").removeClass("display-no");
			}
		} else {
			if ( ! $("new-type").hasClass("display-no") ) {
				$("new-type").addClass("display-no");
			}
			if ( ! $("new-type-label").hasClass("display-no") ) {
				$("new-type-label").addClass("display-no");
			}
		}
	});
	$("output0").addEvent("change", function(oc) {
			oc = new Event(oc).stop();
			
			if ( ! $("as-filename").hasClass("display-no") ) {
				$("as-filename").addClass("display-no");
			}
		});
	$("output1").addEvent("change", function(od) {
			od = new Event(od).stop();
			
			if ( $("as-filename").hasClass("display-no") ) {
				$("as-filename").removeClass("display-no");
			}
			if ( ! $("as-htmlcode").hasClass("display-no") ) {
				$("as-htmlcode").addClass("display-no");
			}
		});
</script>