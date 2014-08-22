<?php
/**
 * jeFAQ package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

?>
<form action="index.php" method="post" name="adminForm">
	<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Filter Reset' ); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php
			echo $this->lists['state'];
			?>
		</td>
	</tr>
	</table>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="3%">
					<?php echo JText::_( 'JE_SER_NO' ); ?>
				</th>
				<th width="3%">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
				</th>
				<th  width="73%">
					<?php echo JHTML::_('grid.sort',  JText::_( 'JE_FAQ_QUESTIONS' ), 's.questions', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="7%">
						<?php echo JHTML::_('grid.sort',  JText::_( 'ORDER' ), 's.ordering',$this->lists['order_Dir'], $this->lists['order']); ?>
						<?php echo JHTML::_('grid.order',  $this->items ); ?>
				</th>
				<th width="5%">
					<?php echo JHTML::_('grid.sort',   JText::_( 'JE_FAQ_PUBLISHED' ), 's.state', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="3%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'JE_FAQ_ID', 's.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
					<?php echo $this->pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<?php
		$k = 0;
		$numFaq = count($this->items);
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)	{

			$row = &$this->items[$i];
			$checked 	= JHTML::_('grid.id',   $i, $row->id );
			$link 		= JRoute::_( 'index.php?option=com_jefaq&c=faq&task=edit&cid[]='. $row->id );

			$row->published = $row->state;
			$published		= JHTML::_('grid.published', $row, $i );

			// enable and disable for ordering field..
			$ordering = ($this->lists['order'] == 's.ordering');

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center">
					<?php echo $i+1; ?>
				</td>
				<td align="center">
					<?php echo $checked; ?>
				</td>
				<td align="left">
					<a href="<?php echo $link; ?>"><?php echo $row->questions; ?></a>
				</td>
				<td class="order">
					<!-- Oreder Up -->
					<span>
						<?php echo $this->pageNav->orderUpIcon($i, true, 'orderup', 'Move Up',isset($this->items[$i-1]) ); ?>
					</span>
					<!-- Oreder Up -->

					<!-- Oreder Down -->
					<span>
						<?php echo $this->pageNav->orderDownIcon($i, $numFaq, true, 'orderdown', 'Move Down',isset($this->items[$i+1]) ); ?>
					</span>
					<!-- Oreder Up -->
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
					<!-- order text boxes -->
					<input type="text" name="order[]" size="4" value="<?php echo $row->ordering;?>"	class="text_area" style="text-align: center" <?php echo $disabled; ?> />
				</td>
			    <td align="center">
					<?php echo $published	; ?>
				</td>
				<td align="center">
					<?php echo $row->id; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
	</table>
	<input type="hidden" name="option" value="com_jefaq" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="c" value="faq" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<p class="copyright" align="center">
	<?php echo JText::_( 'JE_DEVELOPED' ); ?> <a href="http://www.webhostings.in" title="<?php echo JText::_('JE_DEVELOPEDBY'); ?>" target="_blank"> <?php echo JText::_('JE_DEVELOPEDBY'); ?> </a>
	<br/>
	<?php echo JText::_( 'JE_COMMENTS' ); ?> <a href="http://extensions.joomla.org/extensions/directory-a-documentation/faq/12645" title="<?php echo JText::_('JE_RATINGS'); ?>" target="_blank"><?php echo JText::_( 'JE_HERE' ); ?></a>
</p>