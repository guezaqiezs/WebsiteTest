<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$n	=	count( $this->assignmentsItems );
$stats	=	( $n > 0 ) ? $n : '#';
?>

<script type="text/javascript">
	window.addEvent("domready",function(){
		new SmoothScroll({ duration: 1000 });
	});
</script>

<form action="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>" method="post" id="adminForm" name="adminForm">

<div class="col width-100">
<table class="adminform" style="text-indent: 8px;">
	<tbody>
		<tr>
			<td>
				<?php echo '<font color="gray">' . JText::_( 'THE ALL MENU ITEM TYPE IS NOT DISPLAYED' ) . '</font><br />'; ?>
			</td>
		</tr>
	</tbody>
</table>

<fieldset class="adminform">
<legend class="legend-border"><?php echo JText::_( 'TEMPLATE SITE VIEWS' ) .' :: '. $stats; ?></legend>
<table>
	<tr>
		<td align="left" width="100%">
   			<?php echo '<font color="grey"># ' . JText::_( 'FILTER IN' ) . '</font><br />';
				  echo $this->lists['filter_search']; ?>
			<input class="text_area" type="text" id="search" name="search" value="<?php echo $this->lists['search'];?>" style="text-align: center;" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="this.form.getElementById('filter_type').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Search' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_search').value='0';this.form.getElementById('filter_type').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		<td nowrap="nowrap" align="bottom">
			<br />
			<?php echo $this->lists['type']; ?>
			<?php echo $this->lists['filter_state']; ?>
		</td>
	</tr>
</table>

<div id="editcell">
	<table class="adminlist">
		<thead>
        	<tr height="28">
				<th width="30" height="28" rowspan="2">
					<?php HelperjSeblod_Display::quickRefreshPage(); ?>
				</th>
				<th class="title" colspan="2">
					<?php echo JText::_( 'SITE VIEWS' ); ?>
				</th>
				<th class="title" width="40%" colspan="3">
					<?php echo JText::_( 'TEMPLATE' ); ?>
				</th>
				<th width="30" rowspan="2">
					<?php HelperjSeblod_Display::quickRefreshPage(); ?>
				</th>
			</tr>
			<tr height="28">
				<th class="title">
					<?php echo JHTML::_( 'grid.sort', 'Title', 'assignmenttitle', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="title" width="20%">
					<?php echo JHTML::_( 'grid.sort', 'Type', 'assignmenttypename', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
                <th width="30">
					<?php echo JHTML::_( 'grid.sort', 'ID', 'templateid', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="title" width="25%">
					<?php echo JHTML::_( 'grid.sort', 'Title', 'templatetitle', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="title" width="10%">
					<?php echo JHTML::_( 'grid.sort', 'Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<?php
		$k = 0;
		for ( $i = 0, $n = count( $this->assignmentsItems ); $i < $n; $i++ )
		{
			$row 			=& $this->assignmentsItems[$i];
			$published		= ( $row->published ) ? '<img src="images/tick.png" alt="published" />' : '<img src="images/publish_x.png" alt="unpublished" />';
			$linkTemplate	= JRoute::_( 'index.php?option='.$this->option.'&controller=templates&task=edit&cid[]='.$row->templateid );
			$linkTemplateFilter	= JRoute::_( 'index.php?option='.$this->option.'&controller='.$this->controller.'&templatefilter='.$row->templateid );
			if ( $row->assignmenttypename == 'category' ) {
				$linkAssignment = JRoute::_( 'index.php?option=com_categories&section=com_content&task=edit&cid[]='.$row->assignmentid );
				$linkTarget = '_blank';
			} else if ( $row->assignmenttypename == 'menu' ) {
				$linkAssignment = ( $row->assignmenttitle ) ? JRoute::_( 'index.php?option=com_menus&menutype='.$row->assignmentextra.'&task=edit&cid[]='.$row->assignmentid ) : JRoute::_( 'index.php?option=com_menus' );
				$linkTarget = '_blank';
				$row->assignmenttitle = ( $row->assignmenttitle ) ? $row->assignmenttitle : '# ' . JText::_( 'ALL MENUS' ) . ' #';
			} else if ( $row->assignmenttypename == 'url' ) {
				$linkAssignment	= JRoute::_( 'index.php?option='.$this->option.'&controller=templates&task=edit&cid[]='.$row->templateid.'&tab=3' );
				$linkTarget = '_self';
			}
			?>
			<tr class="<?php echo "row$k"; ?>" height="28">
				<td align="center">
					<?php HelperjSeblod_Display::quickSlideTo( 'pagination-bottom', $i + 1 ); ?>
				</td>
				<td align="left">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT ASSIGNMENT' ); ?>::<?php echo $row->assignmenttitle . ' ( '.$row->assignmenttypetitle.' )'; ?>">
						<?php echo _NBSP; ?><a target="<?php echo $linkTarget; ?>" href="<?php echo $linkAssignment; ?>"><?php echo $row->assignmenttitle; ?></a>
					</span>
				</td>
				<td align="center">
					<?php
					if ( $row->assignmenttypetitle == JText::_( "JOOMLA CATEGORY" ) ) {
						echo '<a href="javascript: document.getElementById(\'filter_type\').value=\'category\';submitform();" style="color: green;">' . $row->assignmenttypetitle . '</a>';
					} else if ( $row->assignmenttypetitle == JText::_( "MENU ITEM" ) ) {
						echo '<a href="javascript: document.getElementById(\'filter_type\').value=\'menu\';submitform();" style="color: red;">' . $row->assignmenttypetitle . '</a>';
					} else if ( $row->assignmenttypetitle == JText::_( "SITE URL" ) ) {
						echo '<a href="javascript: document.getElementById(\'filter_type\').value=\'url\';submitform();" style="color: black;">' . $row->assignmenttypetitle . '</a>';
					} else {
						echo $row->assignmenttypetitle;
					}
					?>
				</td>
				<td align="center" width="30">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FILTER IN TEMPLATE' ); ?>::<?php echo $row->templatetitle; ?>">
						<a href="<?php echo $linkTemplateFilter; ?>" border="0">
							&nbsp;<?php echo $row->templateid; ?>&nbsp;
						</a>
					</span>
				</td>
				<td align="center">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT TEMPLATE' ); ?>::<?php echo $row->templatetitle; ?>">
						<a href="<?php echo $linkTemplate; ?>"><?php echo $row->templatetitle; ?></a>
					</span>
				</td>
				<td align="center">
					<?php echo $published; ?>
				</td>
				<td align="center">
					<?php HelperjSeblod_Display::quickSlideTo( 'border-top', $i + 1 ); ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		<tfoot>
			<tr>
				<td><a href="#border-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
				<td colspan="5" id="pagination-bottom"><?php echo $this->pagination->getListFooter(); ?></td>
				<td><a href="#border-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
			</tr>
		</tfoot>
	</table>
</div>

<?php
HelperjSeblod_Display::quickLegend();
?><br />

<div class="clr"></div>
</fieldset>

<?php
$n	=	count( $this->allMenuItems ); ?>
<br />
<fieldset class="adminform">
<legend class="legend-border"><?php echo JText::_( 'TEMPLATE SITE VIEWS' ) .' ( '. JText::_( "ALL MENU ITEMS" ) .' )'; ?></legend>
<div id="editcell">
	<table class="adminlist">
		<thead>
            <tr height="28">
				<th width="30" height="28" rowspan="2">
					<?php HelperjSeblod_Display::quickRefreshPage(); ?>
				</th>
				<th class="title" colspan="2">
					<?php echo JText::_( 'SITE VIEWS' ); ?>
				</th>
				<th class="title" width="40%" colspan="3">
					<?php echo JText::_( 'TEMPLATE' ); ?>
				</th>
				<th width="30" rowspan="2">
					<?php HelperjSeblod_Display::quickRefreshPage(); ?>
				</th>
			</tr>
			<tr height="28">
				<th class="title">
					<?php echo '#&nbsp;' . JText::_( 'Title' ); ?>					
				</th>
				<th class="title" width="20%">
					<?php echo '#&nbsp;' . JText::_( 'Type' ); ?>
				</th>
                <th width="30">
					<?php echo '#&nbsp;' . JText::_( 'ID' ); ?>
				</th>
				<th class="title" width="25%">
					<?php echo '#&nbsp;' . JText::_( 'Title' ); ?>
				</th>
				<th class="title" width="10%">
					<?php echo '#&nbsp;' . JText::_( 'Published' ); ?>
				</th>
			</tr>
		</thead>
		<?php
		$k = 0;
		for ( $i = 0; $i < $n; $i++ )
		{
			$row 			=& $this->allMenuItems[$i];
			$published		= ( $row->published ) ? '<img src="images/tick.png" alt="published" />' : '<img src="images/publish_x.png" alt="unpublished" />';
			$linkTemplate	= JRoute::_( 'index.php?option='.$this->option.'&controller=templates&task=edit&cid[]='.$row->templateid );
			$linkTemplateFilter	= JRoute::_( 'index.php?option='.$this->option.'&controller='.$this->controller.'&templatefilter='.$row->templateid );
			$linkAssignment	= JRoute::_( 'index.php?option=com_menus' );
			$linkTarget = '_self';
			?>
			<tr class="<?php echo "row$k"; ?>" height="28">
				<td align="center">
					<?php echo $i+'1'; ?>
				</td>
				<td align="left">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT ASSIGNMENT' ); ?>::<?php echo $row->assignmenttitle . ' ( '.$row->assignmenttypetitle.' )'; ?>">
						<?php echo _NBSP; ?><a target="<?php echo $linkTarget; ?>" href="<?php echo $linkAssignment; ?>"><?php echo $row->assignmenttitle; ?></a>
					</span>
				</td>
				<td align="center">
                    <?php echo '<font color="red">' . $row->assignmenttypetitle . '</font>'; ?>
				</td>
				<td align="center" width="30">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FILTER IN TEMPLATE' ); ?>::<?php echo $row->templatetitle; ?>">
						<a href="<?php echo $linkTemplateFilter; ?>" border="0">
							&nbsp;<?php echo $row->templateid; ?>&nbsp;
						</a>
					</span>
				</td>
				<td align="center">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT TEMPLATE' ); ?>::<?php echo $row->templatetitle; ?>">
						<a href="<?php echo $linkTemplate; ?>"><?php echo $row->templatetitle; ?></a>
					</span>
				</td>
				<td align="center">
					<?php echo $published; ?>
				</td>
				<td align="center">
					<?php echo $i+'1'; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		<tfoot>
			<tr>
				<td height="28"><a href="#border-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
				<td height="28" colspan="5"></td>
				<td height="28"><a href="#border-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
			</tr>
		</tfoot>
	</table>
</div>
<div class="clr"></div>
</fieldset>

</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="view" value="<?php echo $this->view; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>