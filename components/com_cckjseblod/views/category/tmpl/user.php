<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.mootools' );
JHTML::_( 'behavior.modal' );

require_once ( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' );
	
$n	=	count( $this->categoryItems );

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
    <?php if ( $this->menu_params->get( 'show_state' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'state_width' ); ?>" class="sectiontableheader" align="left">
		<?php echo JText::_( 'State' ); ?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_author' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'author_width' ); ?>" class="sectiontableheader" align="left">
		<?php echo JText::_( 'Author' ); ?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_edit' ) ) : ?>
    <td width="30" class="sectiontableheader" align="center">
		<img src="media/jseblod/_icons/edit-default.png" alt=" " border="0" />
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'enable_publish' ) || $this->menu_params->get( 'enable_unpublish' ) || $this->menu_params->get( 'enable_delete' ) ) : ?>
    <td width="24" class="sectiontableheader" align="right" style="padding-right: 9px;">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->categoryItems ); ?>);" />
	</td>
    <?php endif; ?>
</tr>
<?php endif; ?>

<?php for ( $i = 0; $i < $n; $i++ ) {
	$row			=	$this->categoryItems[$i];
	$row->link		=	JRoute::_( ContentHelperRoute::getCategoryRoute( $row->slug, $row->section ).'&layout=default' );
	$row->edit_link	=	JRoute::_( 'index.php?option=com_cckjseblod&view=type&layout=category&typeid='.$row->content_typeid.'&cckid='.$row->id );
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
    <?php if ( $this->menu_params->get( 'show_state' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'state_width' ); ?>" align="left">
		<?php echo ( $row->published ) ?  '<img src="administrator/images/publish_g.png" alt=" " border="0" />' : '<img src="administrator/images/publish_x.png" alt=" " border="0" />'; ?>
    </td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_author' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'author_width' ); ?>" align="left">
        <?php echo $row->author; ?>
    </td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_edit' ) ) : ?>
    <td width="30" align="center">
    	<?php if ( $row->content_typeid && ! $row->checked_out ) { ?>
			<a href="<?php echo $row->edit_link; ?>"><img src="media/jseblod/_icons/edit-default.png" alt=" " border="0" /></a>
        <?php } else { echo '-'; }?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'enable_publish' ) || $this->menu_params->get( 'enable_unpublish' ) || $this->menu_params->get( 'enable_delete' ) ) : ?>
    <?php if ( ! $row->checked_out ) { ?>
    	<td width="24" align="right" style="padding-right: 10px;">
			<?php echo JHTML::_( 'grid.checkedout', $row, $i ); ?>
		</td>
    <?php } else { ?>
    	<td width="24" align="center">
			<?php echo '-'; ?>
		</td>
    <?php } endif; ?>
</tr>
<?php } ?>

</table>

<?php echo $this->loadTemplate( 'toolbar' ); ?>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="Itemid" value="<?php echo $this->itemId; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>