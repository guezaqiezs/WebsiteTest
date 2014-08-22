<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

	<table class="adminForm">
		<tr>
			<td valign="top" width="5%">
			</td>
			<td valign="top" width="20%">
				<strong><?php echo JText::_( 'Number of Items' ); ?>:</strong><br />
				<font color="#000066"><strong><?php echo count( $this->categoriesItems ); ?></strong></font><br /><br />
			</td>
			<td valign="top" width="25%">
				<strong><?php echo JText::_( 'Items being Deleted' ); ?>:</strong><br />
				<?php echo "<ol>";
				    if ( sizeof( $this->categoriesItems ) ) {
  					  foreach ( $this->categoriesItems as $item ) {
  						echo "<li>". $item->title ."</li>";
  					  }
  					}
					  echo "</ol>"; ?>
			</td>
			<td valign="top" width="25%">
				<strong><?php echo JText::_( 'DELETE TEMPLATE CATEGORY WITHOUT' ); ?></strong><br /><br /><br />
				<a class="icon-32-delete_jseblod" style="border: 1px dotted gray; width: 70px; padding: 10px; margin-left: 50px; background-repeat: no-repeat; padding-left: 40px; " href="javascript:void submitbutton('remove')">
				<?php echo JText::_( 'Delete' ); ?></a>
			</td>
			<td valign="top" width="25%">
				<strong><?php echo JText::_( 'DELETE TEMPLATE CATEGORY WITH' ); ?></strong><br /><br /><br />
				<a class="icon-32-delete_jseblod" style="border: 1px dotted gray; width: 70px; padding: 10px; margin-left: 50px; background-repeat: no-repeat; padding-left: 40px; " href="javascript:void submitbutton('removeAll')">
				<?php echo JText::_( 'Delete' ); ?></a>
			</td>
			<td valign="top">
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table><br /><br />
	
	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="1" />
	<?php
	if ( sizeof( $this->categoriesItems ) ) {
  	foreach ( $this->categoriesItems as $item ) {
  		$id	=	$item->id;
  		echo "\n<input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
  	}
  }
	?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>