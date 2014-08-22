<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.modal' );
$rel	=	"{handler: 'iframe', size: {x: 800, y: 500}}";

$dateNow 	=& JFactory::getDate();
$dateTime	= $dateNow->toFormat( '%Y_%m_%d' );
?>

<script type="text/javascript">
	function setRemoveMode(mode) {
		var elem 	=	document.getElementById("remove_mode");
		elem.value	=	mode;
	}
</script>

<form enctype="multipart/form-data" action="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>" method="post" id="adminForm" name="adminForm">

<div class="col width-50">
    <fieldset class="adminform" style="padding-bottom:0px;">
        <legend class="legend-border"><?php echo JText::_( 'CONTENT PACKS EXPORT' ); ?></legend>
   		<span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 0px; margin-bottom: 10px;">
		<table class="admintable" >
			<tr>
				<td>
					<strong><?php echo JText::_( 'Description' ); ?>:</strong><br />
					<?php echo JText::_( 'DESCRIPTION EXPORT CONTENT PACKS' ); ?>
				</td>
			</tr>
		</table>
		</span>
        <table class="admintable">
            <tr>
                <td>
                	<div id="cpanel">
						<?php
						$link = _LINK_CCKJSEBLOD_TEMPLATES;
						HelperjSeblod_Display::quickiconExportButton( $link, 'icon-48-templates.png', JText::_( 'TEMPLATES' ), 'template' );
						
						$link = _LINK_CCKJSEBLOD_TYPES;
						HelperjSeblod_Display::quickiconExportButton( $link, 'icon-48-types.png', JText::_( 'TYPES' ), 'type' );
						
						$link = _LINK_CCKJSEBLOD_ITEMS;
						HelperjSeblod_Display::quickiconExportButton( $link, 'icon-48-items.png', JText::_( 'ITEMS' ), 'item' );
						
						$link = _LINK_CCKJSEBLOD_SEARCHS;
						HelperjSeblod_Display::quickiconExportButton( $link, 'icon-48-searchs.png', JText::_( 'SEARCH TYPES' ), 'search' );
						?>
					</div>
                </td>
            </tr>
        </table>        
        <?php
		$n	=	count( $this->packElems );
		$templates	=	0;
		$types		=	0;
		$items		=	0;
		$searchs	=	0;
       	for ( $i = 0; $i < $n; $i++ ) {
			$row	=&	$this->packElems[$i];
			if ( $row->type == 'tmpl' ) {
				$templates++;
			} else if ( $row->type == 'type' ) {
				$types++;
			} else if ( $row->type == 'field' ) {
				$items++;
			} else if ( $row->type == 'search' ) {
				$searchs++;
			} else {}
		}
		?>
        <table class="admintable">
        <tr>
            <td colspan="3">
            </td>
        </tr
        ><tr height="26">
            <td width="25" class="key_jseblod">
	            <?php if ( $templates ) { ?>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'EMPTY TEMPLATES' ); ?>::<?php echo JText::_( 'EMPTY TEMPLATES BALLOON' ); ?>">
               		<a onclick="setRemoveMode('tmpl');submitbutton('remove');" style="cursor:pointer;" alt="Trash"><?php echo _IMG_TRASH; ?></a>
				</span>
                <?php } ?>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'TEMPLATES INTO PACK' ); ?>::<?php echo JText::_( 'TEMPLATES ADDED INTO PACK' ); ?>">
                    <?php echo JText::_( 'TEMPLATES INTO PACK' ); ?>:
                </span>
            </td>
            <td>
	   			<?php echo '<strong><font color="#6CC634">' . $templates . '&nbsp;' . JText::_( 'TEMPLATE TEMPLATES' ) . '</font></strong>'; ?>
            </td>
        </tr>
        <tr height="26">
            <td width="25" class="key_jseblod">
            	<?php if ( $types ) { ?>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'EMPTY TYPES' ); ?>::<?php echo JText::_( 'EMPTY TYPES BALLOON' ); ?>">
               		<a onclick="setRemoveMode('type');submitbutton('remove');" style="cursor:pointer;" alt="Trash"><?php echo _IMG_TRASH; ?></a>
				</span>
				<?php } ?>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'TYPES INTO PACK' ); ?>::<?php echo JText::_( 'TYPES ADDED INTO PACK' ); ?>">
                    <?php echo JText::_( 'TYPES INTO PACK' ); ?>:
                </span>
            </td>
            <td>
				<?php echo '<strong><font color="#6CC634">' . $types . '&nbsp;' . JText::_( 'CONTENT TYPE TYPES' ) . '</font></strong>'; ?>
            </td>
        </tr>
        <tr height="26">
            <td width="25" class="key_jseblod">
            	<?php if ( $items ) { ?>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'EMPTY ITEMS' ); ?>::<?php echo JText::_( 'EMPTY ITEMS BALLOON' ); ?>">
               		<a onclick="setRemoveMode('field');submitbutton('remove');" style="cursor:pointer;" alt="Trash"><?php echo _IMG_TRASH; ?></a>
				</span>
                <?php } ?>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'ITEMS INTO PACK' ); ?>::<?php echo JText::_( 'ITEMS ADDED INTO PACK' ); ?>">
                    <?php echo JText::_( 'ITEMS INTO PACK' ); ?>:
                </span>
            </td>
            <td>
				<?php echo '<strong><font color="#6CC634">' . $items . '&nbsp;' . JText::_( 'CONTENT ITEM ITEMS' ) . '</font></strong>'; ?>
            </td>
        </tr>
        <tr height="26">
            <td width="25" class="key_jseblod">
            	<?php if ( $searchs ) { ?>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'EMPTY SEARCH TYPES' ); ?>::<?php echo JText::_( 'EMPTY SEARCH TYPES BALLOON' ); ?>">
               		<a onclick="setRemoveMode('search');submitbutton('remove');" style="cursor:pointer;" alt="Trash"><?php echo _IMG_TRASH; ?></a>
				</span>
                <?php } ?>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'SEARCH TYPES INTO PACK' ); ?>::<?php echo JText::_( 'SEARCH TYPES ADDED INTO PACK' ); ?>">
                    <?php echo JText::_( 'SEARCH TYPES INTO PACK' ); ?>:
                </span>
            </td>
            <td>
				<?php echo '<strong><font color="#6CC634">' . $searchs . '&nbsp;' . JText::_( 'SEARCH TYPE TYPES' ) . '</font></strong>'; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
            </td>
        </tr>
        <tr height="26">
            <td width="25" class="key_jseblod">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT PACK' ); ?>::<?php echo JText::_( 'EXPORT CONTENT PACK BALLOON' ); ?>">
                    <?php echo _IMG_EXPORT; ?>
                </span>
            </td>
            <td width="100" align="right" class="key">
                <span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT PACK' ); ?>::<?php echo JText::_( 'EDIT CONTENT PACK NAME' ); ?>">
                    <?php echo JText::_( 'CONTENT PACK' ); ?>:
                </span>
            </td>
            <td>
                <input class="inputbox" type="text" id="name_package" name="name_package" maxlength="50" size="32" value="<?php echo 'Pack_'.$dateTime; ?>" />&nbsp;.zip
            </td>
        </tr>
    </table>
    </fieldset>
</div>

<div class="col width-50">
	<?php echo $this->loadTemplate( 'import' );?>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" id="remove_mode" name="remove_mode" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>