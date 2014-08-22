<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$empty	=	JText::_( 'SELECT A TYPE BY' );
$buttons = array('Empty'	=> array( 'Empty', 'trash', "javascript: window.parent.jEmptyType('$empty', '$this->object' );", 'onclick' ),
				 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
				 'Cancel'	=> array( 'Cancel', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ) );

$javascript ='
	window.addEvent("domready",function(){
	
		new SmoothScroll({ duration: 1000 });
		
		var AjaxTooltips = new MooTips($$(".ajaxTip"), {
			className: "ajaxTool",
			showOnClick: true,
			showOnMouseEnter: false,
			fixed: true
		});
	});
	';
$this->document->addScriptDeclaration( $javascript );
?>

<form action="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>&task=element&tmpl=component" method="post" id="adminForm" name="adminForm">

<div id="modal-top">
	<fieldset class="adminform modal-bg-toolbar">
		<div class="header icon-48-types" style="float: left">
			<?php echo JText::_( 'CONTENT TYPES' ) . ': <small><small>[ '.JText::_( 'SELECT' ).' ]</small></small>'; ?>
		</div>
		<div style="float: right">
			<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
		</div>
	</fieldset>

	<fieldset class="adminform">
		<table>
			<tr>
				<td align="left" width="100%">
   					<?php echo '<font color="grey"># ' . JText::_( 'FILTER IN' ) . '</font>';
						  echo $this->lists['filter_search']; ?>
					<input class="text_area" type="text" id="search" name="search" value="<?php echo $this->lists['search'];?>" style="text-align: center;" />
					<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					<button onclick="this.form.getElementById('filter_category').value='0';this.form.submit();"><?php echo JText::_( 'Search' ); ?></button>
					<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_search').value='0';this.form.getElementById('filter_category').value='0';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
				</td>
				<td nowrap="nowrap">
					<?php echo $this->lists['category']; ?>
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
					<th class="title">
						<?php echo JHTML::_( 'grid.sort', 'Title', 's.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					</th>
					<th class="title" width="35%" colspan="2">
						<?php echo JHTML::_( 'grid.sort', 'Category', 'categorytitle', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					</th>
					<th width="30">
						<?php echo JHTML::_( 'grid.sort', 'ID', 's.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					</th>
				</tr>			
			</thead>
			<?php
			$k = 0;
			for ( $i = 0, $n = count( $this->types_items ); $i < $n; $i++ )
			{
				$row 		=&	$this->types_items[$i];
				
				$tooltips['description'] = HelperjSeblod_Display::quickTooltipAjax( 'Description', $this->controller, 'description', $row->id );
				?>
				<style type="text/css">
					.typeColor<?php echo $i; ?> {
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
						<div class="title-left">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'SELECT CONTENT TYPE' ); ?>::<?php echo $row->title; ?>">	
								<?php echo _NBSP; ?><a href="#" onclick="window.parent.jSelectType('<?php echo $row->id; ?>', '<?php echo addslashes($row->title); ?>', '<?php echo $this->object; ?>');"><?php echo $row->title; ?></a>
							</span>
							<?php echo '<br />' . _NBSP . $row->name; ?>
						</div>
					</td>
					<td align="center" width="5%" valign="middle">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'FILTER IN CATEGORY' ); ?>::<?php echo $row->categorytitle; ?>">
							<a href="javascript: document.getElementById('filter_search').value='5';document.getElementById('search').value='<?php echo $row->category; ?>';submitform();" border="0" style="text-decoration: none;">
								<div class="<?php echo ( $row->categorycolor ) ? 'typeColor'.$i : 'typeNoColor'; ?>" style="vertical-align: middle;">
									<strong><?php echo $row->categoryintrochar; ?></strong>
								</div>
							</a>
						</span>
					</td>
					<td align="center">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'FILTER BY CATEGORY' ); ?>::<?php echo JText::_( $row->categorytitle ); ?>">
							<a href="javascript: document.getElementById('filter_category').value='<?php echo $row->category; ?>';submitform();" style="color: #666666">
								<?php echo $row->categorytitle; ?>
							</a>
						</span>
					</td>
					<td align="center">
						<?php HelperjSeblod_Display::quickSlideTo( 'modal-top', $row->id ); ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tfoot>
				<tr>
					<td><a href="#modal-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
					<td colspan="3" id="pagination-bottom"><?php echo $this->pagination->getListFooter(); ?></td>
					<td><a href="#modal-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
				</tr>
			</tfoot>
			</table>
		</div>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="element" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="action" value="<?php echo $this->action; ?>" />
<input type="hidden" name="object" value="<?php echo $this->object; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?><br />