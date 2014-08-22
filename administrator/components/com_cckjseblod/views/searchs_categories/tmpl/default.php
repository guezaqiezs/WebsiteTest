<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$user	=&	JFactory::getUser();

$linkParent	=	JRoute::_( 'index.php?option='.$this->option.'&controller='.$this->controller.'&categoryfilter='.$this->parentId );

$n		=	count( $this->categoriesItems );
$stats	=	( $n > 0 ) ? $n : '#';
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

<form action="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>" method="post" id="adminForm" name="adminForm">

<fieldset>
<legend class="legend-border"><?php echo JText::_( 'SEARCH TYPE CATEGORIES' ) .' :: '. $stats; ?></legend>
<table>
	<tr>
		<td align="left" width="100%">
   			<?php echo '<font color="grey"># ' . JText::_( 'FILTER IN' ) . '</font>';
				  echo $this->lists['filter_search']; ?>
			<input class="text_area" type="text" id="search" name="search" value="<?php echo $this->lists['search'];?>" style="text-align: center;" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="this.form.getElementById('filter_category').value='0';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Search' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_search').value='0';this.form.getElementById('filter_category').value='0';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php echo $this->lists['filter_category']; ?>
			<?php echo $this->lists['filter_state']; ?>
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
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->categoriesItems ); ?>);" />
			</th>
			<th width="30">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'GO TO PARENT' ); ?>::<?php echo JText::_( 'CLICK TO GO TO PARENT' ); ?>">
					<a href="<?php echo $linkParent; ?>"><?php echo _IMG_PARENT; ?></a>
				</span>
			</th>
			<th width="30">
				<?php echo JHTML::_( 'grid.sort', _IMG_CATEGORY, 's.lft', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_( 'grid.sort', 'Title', 's.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th class="title" width="10%">
				<?php echo JHTML::_( 'grid.sort', 'DEPTH', 'depth', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th class="title" width="10%">
				<?php echo JHTML::_( 'grid.sort', 'COLOR', 'color', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th class="title" width="20%" colspan="2">
				<?php echo '#&nbsp;' . JText::_( 'SEARCH TYPES' ); ?>
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
		$row 			=&	$this->categoriesItems[$i];
		$checked		=	JHTML::_( 'grid.checkedout', $row, $i );
		$mychecked		=	( $row->id == 1 || $row->id == 2 ) ? '<img src="images/checked_out.png" alt="X" />' : JHTML::_( 'grid.checkedout', $row, $i );		
		$published 		=	( $this->isAuth ) ? JHTML::_( 'grid.published', $row, $i ) : ( ( $row->published ) ? '<img src="images/tick.png" />' : '<img src="images/publish_x.png" />' );
		
		$link 				=	JRoute::_( 'index.php?option='.$this->option.'&controller='.$this->controller.'&task=edit&cid[]='.$row->id );
		$linkFilterCategory	=	JRoute::_( 'index.php?option='.$this->option.'&controller='.$this->controller.'&categoryfilter='.$row->id );
		$linkSearchs		=	JRoute::_( 'index.php?option='.$this->option.'&controller=searchs&categoryfilter='.$row->id );
		$displaySearchs		=	( $row->displaySearchs ) ? '<span class="editlinktip hasTip" title="'.JText::_( 'EDIT SEARCHS' ).'::'.$row->title.'"><a href="'.$linkSearchs.'">'._IMG_SEARCHS_24.'</a></span>' : '-';
				
		$tooltips['description']	=	HelperjSeblod_Display::quickTooltipAjax( 'Description', $this->controller, 'description', $row->id );
		$borderNoname				=	( $row->id == 1 ) ? 'style="border-bottom: 1px solid #999999;"' : '';
		?>
		<style type="text/css">
			.categoryColor<?php echo $i; ?> {
				width: 60px;
				height: 14px;
				background-color: <?php echo $row->color; ?>;
				color: <?php echo $row->colorchar; ?>;
				padding-top:3px;
				padding-bottom:3px;
				vertical-align: middle;
				border: none;
			}
		</style>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center" <?php echo $borderNoname; ?>>
				<?php HelperjSeblod_Display::quickSlideTo( 'pagination-bottom', $i + 1 ); ?>
			</td>
			<td align="center" <?php echo $borderNoname; ?>>
				<?php echo $mychecked; ?>
			</td>
			<td align="left" colspan="3" <?php echo $borderNoname; ?>>
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
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT CATEGORY' ); ?>::<?php echo $row->title; ?>">
		  			<?php
					if (  JTable::isCheckedOut( $user->get( 'id' ), $row->checked_out ) ) {
						if ( $row->id == 1 || $row->id == 2 ) {
							echo _NBSP . $row->title;
							echo '<br />' . _NBSP . strtolower( JText::_( $row->name ) );
						} else {
							echo _NBSP . str_repeat( '.'._NBSP2, $row->depth) . $row->title;
							echo '<br />' . _NBSP . str_repeat( '.'._NBSP2, $row->depth ) . $row->name;
						}
					} else {
						if ( $row->id == 1 || $row->id == 2 ) { ?>
							<?php echo _NBSP; ?><a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
							<?php echo '<br />' . _NBSP . strtolower( JText::_( $row->name ) ); ?>
						<?php } else { ?>
							<?php echo _NBSP . str_repeat( '.'._NBSP2, $row->depth) ?><a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
							<?php echo '<br />' . _NBSP . str_repeat( '.'._NBSP2, $row->depth ) . $row->name; ?>
						<?php } ?>
					<?php
					}
					?>
				</span>
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
			<td align="center" <?php echo $borderNoname; ?>>
				<?php echo ( $row->id == 1 ) ? '#' : $row->depth; ?>
			</td>
			<td align="center" <?php echo $borderNoname; ?>>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'FILTER BY CATEGORY' ); ?>::<?php echo $row->title; ?>">
					<a href="<?php echo $linkFilterCategory; ?>" border="0">
						<div class="<?php echo ( $row->color ) ? 'categoryColor'.$i : 'categoryNoColor'; ?>">
						<strong><?php echo $row->introchar; ?></strong>
						</div>
					</a>
				</span>
			</td>
			<td align="center" width="4%" <?php echo $borderNoname; ?>>
				<?php echo $displaySearchs; ?>
			</td>
			<td align="center" width="16%" <?php echo $borderNoname; ?>>
				<?php echo ( $row->displaySearchs ) ? $row->displaySearchs . '&nbsp;' . ( ( $row->displaySearchs == 1 ) ? JText::_( 'SEARCH TYPE' ) : JText::_( 'SEARCH TYPES' ) ) : '-'; ?>
			</td>
			<td align="center" <?php echo $borderNoname; ?>>
				<?php echo $published; ?>
			</td>
			<td align="center" <?php echo $borderNoname; ?>>
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
			<td colspan="9" id="pagination-bottom"><?php echo $this->pagination->getListFooter(); ?></td>
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