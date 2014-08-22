<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.mootools' );
JHTML::_( 'behavior.modal' );

require_once ( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' );
	
$n	=	count( $this->searchsItems );

$javascript ='
		';
$this->document->addScriptDeclaration( $javascript );
?>

<?php if ( $this->menu_params->get( 'show_page_title', 1 ) ) : ?>
<div class="componentheading"><?php echo $this->page_title; ?></div>
<?php endif; ?>

<form action="index.php" method="post" id="adminForm" name="adminForm">

<?php if ( $this->menu_params->get( 'show_headings' ) ) : ?>
<table class="category" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="30" class="sectiontableheader" align="center">
		<?php echo JText::_( 'Num' ); ?>
	</td>
   	<td class="sectiontableheader" align="left">
		<?php echo JText::_( 'Title' ); ?>
	</td>
    <?php if ( $this->menu_params->get( 'show_category' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'category_width' ); ?>" class="sectiontableheader" align="left">
		<?php echo JText::_( 'Category' ); ?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_state' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'state_width' ); ?>" class="sectiontableheader" align="left">
		<?php echo JText::_( 'State' ); ?>
	</td>
    <?php endif; ?>
</tr>
<?php endif; ?>

<?php for ( $i = 0; $i < $n; $i++ ) {
	$row				=	$this->searchsItems[$i];
	//$row->link		=	JRoute::_( 'index.php?option=com_cckjseblod&view=search&layout=search&searchid='.$row->id.'&templateid=0&Itemid='.$this->itemId );
	$row->link		=	JRoute::_( 'index.php?option=com_cckjseblod&view=search&layout=search&searchid='.$row->id.'&Itemid='.$this->itemId ); //TODO: add params if needed..
?>
<tr class="sectiontableentry<?php echo ( $i % 2 == 0 ) ? 2 : 1;?>">
    <td width="30" align="center">
	    <?php if ( $this->menu_params->get( 'num_format', 0 ) ) {
	        echo $row->id;
        } else {
	       	echo $i + 1;
        } ?>
    </td>
    <td align="left">
    	<?php if ( $row->published ) { ?>
	        <a href="<?php echo $row->link; ?>"><?php echo $row->title; ?></a>
        <?php } else {
        	echo $row->title;
        } ?>
    </td>
    <?php if ( $this->menu_params->get( 'show_category' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'category_width' ); ?>" align="left">
        <?php echo $row->categorytitle; ?>
    </td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_state' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'state_width' ); ?>" align="left">
	    <?php echo ( $row->published ) ?  '<img src="administrator/images/publish_g.png" alt=" " border="0" />' : '<img src="administrator/images/publish_x.png" alt=" " border="0" />'; ?>
    </td>
    <?php endif; ?>
</tr>
<?php } ?>

</table>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="Itemid" value="<?php echo $this->itemId; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>