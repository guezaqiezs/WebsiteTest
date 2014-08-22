<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$user	=& JFactory::getUser();
$rel 	=	"{handler: 'iframe', size: {x: "._MODAL_WIDTH.", y: "._MODAL_HEIGHT."}}";
$n		=	count( $this->searchs_items );
$stats	=	( $n > 0 ) ? $n : '#';

$dateNow 	=& JFactory::getDate();
$dateTime	= $dateNow->toFormat( '%Y_%m_%d' );
?>

<script type="text/javascript">
	window.addEvent("domready",function(){
		new SmoothScroll({ duration: 1000 });
		
		var AjaxTooltips = new MooTips($$(".ajaxTip"), {
			className: "ajaxTool",
			showOnClick: true,
			showOnMouseEnter: false,
			fixed: true
		});
	});
	
	function updateElement(elementId, html) {
		var elem = document.getElementById(elementId);
		memElem	= elem.innerHTML;
		elem.innerHTML = html;
	}
	
	function closeElement(closeId) {
		var closeElem = document.getElementById(closeId);
		closeElem.innerHTML = memElem;
		titleId = 0;
	}
	
	function liveProcess(id, content) {
		if (titleId) {
			closeElement( 'title-' + titleId );
		}
		if (lastId != id) {
			titleId = id;
			lastId = id;
			
			var process_button = '<?php echo JText::_( 'Save' ); ?>'
			var html =	'<div style="padding-top: 5px;"><input class="inputbox" type="text" id="live_title" name="live_title" maxlength="50" size="32" value="'+content+'" />' +
						'<input type="hidden" id="live_id" name="live_id" value="'+id+'" />' +
						'&nbsp;<button onclick="submitbutton(\'liveSave\');">'+process_button+'</button></div>';
			updateElement('title-' + titleId, html);
		} else {
			lastId = 0;
		}
	}
	
	var titleId = 0;
	var lastId	= 0;
	var memElem = '';
</script>

<form enctype="multipart/form-data" action="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>" method="post" id="adminForm" name="adminForm">

<div class="col width-100">
<fieldset class="adminform">
<legend class="legend-border"><?php echo JText::_( 'SEARCH TYPES' ) .' :: '. $stats; ?></legend>
<table>
   	<tr>
    	<td align="left" width="100%">
   			<?php echo '<font color="grey"># ' . JText::_( 'FILTER IN' ) . '</font><br />';
				  echo $this->lists['filter_search']; ?>
			<input class="text_area" type="text" id="search" name="search" value="<?php echo $this->lists['search'];?>" style="text-align: center;" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="this.form.getElementById('filter_category').value='0';this.form.getElementById('filter_assignment').value='0';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Search' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_search').value='0';this.form.getElementById('filter_category').value='0';this.form.getElementById('filter_assignment').value='0';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>			
		</td>
		<td nowrap="nowrap" align="right">
	        <?php echo $this->lists['category']; ?><br />
			<?php echo $this->lists['state']; ?>
		</td>
	</tr>
</table>

<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="30">
				<?php HelperjSeblod_Display::quickRefreshPage(); ?>
			</th>
			<th width="30">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->searchs_items ); ?>);" />
			</th>
			<th width="30">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEARCH IN CONTENT' ); ?>::<?php echo JText::_( 'CLICK TO SEARCH IN CONTENT' ); ?>">
					<?php echo _IMG_SEARCHS; ?>
				</span>
			</th>
			<th class="title">
				<?php echo JHTML::_( 'grid.sort', 'Title', 's.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th class="title" width="20%" colspan="2">
				<?php echo JHTML::_( 'grid.sort', 'Category', 'categorytitle', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th class="title" width="10%" colspan="2">
                <?php echo '#&nbsp;' . JText::_( 'LIST ITEMS' ); ?>
			</th>
			<th class="title" width="10%" colspan="2">
				<?php echo '#&nbsp;' . JText::_( 'SEARCH ITEMS' ); ?>
			</th>
			<th class="title" width="10%" colspan="2">
				<?php echo '#&nbsp;' . JText::_( 'FILTER ITEMS' ); ?>
			</th>
			<th class="title" width="10%">
				<?php echo JHTML::_( 'grid.sort', 'Published', 's.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="30">
				<?php echo JHTML::_( 'grid.sort', 'ID', 's.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
		</tr>			
	</thead>
	<?php
	$k = 0;
	for ( $i = 0; $i < $n; $i++ )
	{
		$row 				=& $this->searchs_items[$i];
		$checked			= JHTML::_( 'grid.checkedout', $row, $i );
		$published 			= ( $this->isAuth ) ? JHTML::_( 'grid.published', $row, $i ) : ( ( $row->published ) ? '<img src="images/tick.png" />' : '<img src="images/publish_x.png" />' ) ;
		$linkFilterFields	= JRoute::_( 'index.php?option='.$this->option.'&controller=items&contentfilter='.$row->id.'&sidefilter=filter' );
		$linkSearchFields	= JRoute::_( 'index.php?option='.$this->option.'&controller=items&searchfilter='.$row->id.'&sidefilter=search' );
		$linkCategory		= JRoute::_( 'index.php?option='.$this->option.'&controller=searchs_categories&task=edit&cid[]='.$row->category );
		$linkCategoryFilter	= JRoute::_( 'index.php?option='.$this->option.'&controller=searchs_categories&categoryfilter='.$row->category );
		$link 				= JRoute::_( 'index.php?option='.$this->option.'&controller='.$this->controller.'&task=edit&cid[]='. $row->id );
		$linkSearchContent	= JURI::root().'index.php?option='.$this->option.'&view=search&layout=search&task=search&searchid='.$row->id.$row->list;
		$tooltips['description'] = HelperjSeblod_Display::quickTooltipAjax( 'Description', $this->controller, 'description', $row->id );
		?>
		<style type="text/css">
			.searchColor<?php echo $i; ?> {
				width: 20px;
				height: 14px;
				background-color: <?php echo $row->categorycolor; ?>;
				color: <?php echo $row->categorycolorchar; ?>;
				padding-top:3px;
				padding-bottom:3px;
				vertical-align: middle;
				border: none;
			}
		</style>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
				<?php HelperjSeblod_Display::quickSlideTo( 'pagination-bottom', $i + 1 ); ?>
			</td>
			<td align="center">
				<?php echo $checked; ?>
			</td>
			<td align="center">
            	<?php if ( $row->published ) { ?>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEARCH IN CONTENT' ); ?>::<?php echo $row->title; ?>">
					<a target="_blank" href="<?php echo $linkSearchContent; ?>">
						<?php echo _IMG_SEARCHS; ?>
					</a>
				</span>
                <?php } ?>
			</td>
			<td align="left">
				<div class="title-left">
					<table>
						<tr>
							<td style="border: none;">
								<?php echo $tooltips['description']; ?>
							</td>
						</tr>
					</table>
				</div>
				<div class="title-left" id="title-<?php echo $row->id;?>">
		  			<?php	
					if (  JTable::isCheckedOut( $user->get( 'id' ), $row->checked_out ) ) { ?>
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT SEARCH' ); ?>::<?php echo $row->title; ?>">
						<?php echo _NBSP . $row->title ?>
           				</span>
                        <?php echo '<br />' . _NBSP . $row->name; ?>
					<?php } else { ?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT SEARCH' ); ?>::<?php echo $row->title; ?>">
					<?php echo _NBSP; ?><a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
          				</span>
						<?php echo '<br />' . _NBSP . $row->name; ?>
					<?php
					}
					?>
				</div>
				<div class="title-right">
					<table>
						<tr>
							<td style="border: none;">
								<?php if ( JTable::isCheckedOut( $user->get( 'id' ), $row->checked_out ) ) { ?>
									<span>
									<?php echo $checked; ?>
									</span>
								<?php } else { ?>
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'QUICK EDIT' ); ?>::<?php echo $row->title; ?>">
									<a href="javascript: liveProcess('<?php echo $row->id; ?>', '<?php echo addslashes( $row->title ); ?>' );"><?php echo _IMG_QUICK_EDIT; ?></a>
								</span>
								<?php } ?>
							</td>
						</tr>
					</table>
				</div>
			</td>
			<td align="center" width="4%" valign="middle">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'FILTER IN CATEGORY' ); ?>::<?php echo $row->categorytitle; ?>">
					<a href="javascript: document.getElementById('filter_search').value='5';document.getElementById('search').value='<?php echo $row->category; ?>';submitform();" border="0">
						<div class="<?php echo ( $row->categorycolor ) ? 'searchColor'.$i : 'searchNoColor'; ?>" style="vertical-align: middle;">
							<strong><?php echo $row->categoryintrochar; ?></strong>
						</div>
					</a>
				</span>
			</td>
			<td align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT CATEGORY' ); ?>::<?php echo $row->categorytitle; ?>">
					<a href="<?php echo $linkCategory; ?>"><?php echo $row->categorytitle; ?></a>
				</span>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'VIEW CATEGORY TREE' ); ?>::<?php echo $row->categorytitle; ?>">
					<br /><a style="color: #666666" href="<?php echo $linkCategoryFilter; ?>"><?php echo strtolower( JText::_( 'VIEW TREE' ) ); ?></a>
				</span>
			</td>
			<td align="center" width="4%">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT LIST ITEMS' ); ?>::<?php echo $row->title; ?>">
					<?php echo ( ! $row->listFields || $row->listFields == 1 ) ? '-' : '<a href="'.$linkSearchFields.'">'._IMG_LISTITEMS.'</a>'; ?>
				</span>
			</td>
			<td align="center" width="6%">
					<?php echo ( ! $row->listFields || $row->listFields == 1 ) ? '-' : $row->listFields . '&nbsp;' . JText::_( 'ITEMS' ); ?>
			</td>
			<td align="center" width="4%">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT SEARCH ITEMS' ); ?>::<?php echo $row->title; ?>">
					<?php echo ( ! $row->searchFields || $row->searchFields == 1 ) ? '-' : '<a href="'.$linkSearchFields.'">'._IMG_SEARCHITEMS.'</a>'; ?>
				</span>
			</td>
			<td align="center" width="6%">
				<?php echo ( ! $row->searchFields || $row->searchFields == 1 ) ? '-' : $row->searchFields . '&nbsp;' . JText::_( 'ITEMS' ); ?>
			</td>
			<td align="center" width="4%">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT FILTER ITEMS' ); ?>::<?php echo $row->title; ?>">
					<?php echo ( ! $row->filterFields || $row->filterFields == 1 ) ? '-' : '<a href="'.$linkFilterFields.'">'._IMG_FILTERITEMS.'</a>'; ?>
				</span>
			</td>
			<td align="center" width="6%">
					<?php echo ( ! $row->filterFields || $row->filterFields == 1 ) ? '-' : $row->filterFields . '&nbsp;' . JText::_( 'ITEMS' ); ?>
			</td>
			<td align="center">
				<?php echo $published; ?>
			</td>
			<td align="center">
				<?php HelperjSeblod_Display::quickSlideTo( 'border-top', $row->id ); ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	<tfoot>
		<tr>
			<td><a href="#border-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
			<td colspan="12" id="pagination-bottom"><?php echo $this->pagination->getListFooter(); ?></td>
			<td><a href="#border-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
		</tr>
	</tfoot>
	</table>
</div>

<?php
HelperjSeblod_Display::quickLegend();
?>
   
<div class="clr"></div>
</fieldset>

<?php if ( $this->isAuth ) { ?>
<br />
<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'BATCH PROCESS' ); ?></legend>
	<div>
		<table class="admintable">
			<tr>
				<td width="25" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SET TO CATEGORY' ); ?>::<?php echo JText::_( 'SET TO CATEGORY BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SET TO CATEGORY' ); ?>::<?php echo JText::_( 'SELECT CATEGORY' ); ?>">
						<?php echo JText::_( 'SET TO CATEGORY' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['batch_category']; ?>
				</td>
				<td>
					<div class="button2-left">
						<div class="next">
							<a onclick="submitbutton('batchCategory');" alt="Clean"><?php echo JText::_( 'UPDATE' ); ?></a>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</div>
</fieldset>

<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'PACK PROCESS' ); ?></legend>
	<div>
		<table class="admintable">
             <tr>
				<td width="25" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ADD INTO PACK' ); ?>::<?php echo JText::_( 'ADD INTO PACK BALLOON' ); ?>">
						<?php echo _IMG_EXPORT_ADD; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ADD INTO PACK' ); ?>::<?php echo JText::_( 'CLICK TO ADD INTO PACK' ); ?>">
						<?php echo JText::_( 'ADD INTO PACK' ); ?>:
					</span>
				</td>
                <td>
					<?php echo $this->lists['add_mode']; ?>
				</td>
                <td>
                	<?php echo $this->lists['goToPack']; ?>
				</td>
				<td>
                	<div class="button2-left">
						<div class="next">
							<!--<a onclick="submitbutton('addIntoPack');" alt="Add"><?php //echo JText::_( 'ADD' ); //COMING SOON //TODO ?></a>-->
						</div>
					</div>
				</td>
			</tr>
            <tr>
				<td colspan="5">
				</td>
			</tr>
			<tr>
				<td width="25" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EXPORT SEARCH TYPES PACK' ); ?>::<?php echo JText::_( 'EXPORT SEARCH TYPES BALLOON' ); ?>">
						<?php echo _IMG_EXPORT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EXPORT SEARCH TYPES' ); ?>::<?php echo JText::_( 'CHOOSE EXPORT SEARCH TYPES MODE' ); ?>">
						<?php echo JText::_( 'EXPORT SEARCH TYPES' ); ?>:
					</span>
				</td>
                <td>
					<?php echo $this->lists['export_mode']; ?>
				</td>
				<td >
					<input class="inputbox" type="text" id="name_package" name="name_package" maxlength="50" size="32" value="<?php echo 'Search_'.$dateTime; ?>" />&nbsp;.zip
				</td>
				<td>
					<div class="button2-left">
						<div class="next">
							<!--<a onclick="submitbutton('exportXml');" alt="Export"><?php //echo JText::_( 'Export' ); //COMING SOON //TODO ?></a>-->
						</div>
					</div>
				</td>
			</tr>
		</table>
	</div>
</fieldset>
<?php } ?>
</div>
 
<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>