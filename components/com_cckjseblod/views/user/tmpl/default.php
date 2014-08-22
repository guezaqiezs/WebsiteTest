<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.mootools' );
JHTML::_( 'behavior.modal' );

require_once ( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' );
	
$n	=	count( $this->userItems );

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
		<?php echo JText::_( 'Name' ); ?>
	</td>
    <?php if ( $this->menu_params->get( 'show_username' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'username_width' ); ?>" class="sectiontableheader" align="left">
		<?php echo JText::_( 'Username' ); ?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_category' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'category_width' ); ?>" class="sectiontableheader" align="left">
		<?php echo JText::_( 'Category' ); ?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_email' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'email_width' ); ?>" class="sectiontableheader" align="left">
		<?php echo JText::_( 'E-mail' ); ?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_usergroup' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'usergroup_width' ); ?>" class="sectiontableheader" align="left">
		<?php echo JText::_( 'Group' ); ?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_state' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'state_width' ); ?>" class="sectiontableheader" align="left">
		<?php echo JText::_( 'State' ); ?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_date' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'date_width' ); ?>" class="sectiontableheader" align="left">
		<?php echo JText::_( 'Date' ); ?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_hits' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'hits_width' ); ?>" class="sectiontableheader" align="left">
		<?php echo JText::_( 'Hits' ); ?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_edit' ) ) : ?>
    <td width="30" class="sectiontableheader" align="center">
		<img src="media/jseblod/_icons/edit-default.png" alt=" " border="0" />
	</td>
    <?php endif; ?>
	<?php if ( $this->menu_params->get( 'enable_enable' ) || $this->menu_params->get( 'enable_block' ) || $this->menu_params->get( 'enable_delete' ) ) : ?>
    <td width="24" class="sectiontableheader" align="right" style="padding-right: 9px;">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->userItems ); ?>);" />
	</td>
    <?php endif; ?>
</tr>
<?php endif; ?>

<?php for ( $i = 0; $i < $n; $i++ ) {
	$row				=	$this->userItems[$i];
	$row->link			=	( @$row->slug && @$row->catslug && @$row->sectionid ) ? JRoute::_( ContentHelperRoute::getArticleRoute( $row->slug, $row->catslug, $row->sectionid ) ) : '';
	$row->edit_link		=	( @$row->content_typeid ) ? JRoute::_( 'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$row->content_typeid.'&cckid='.$row->id ) : '';
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
    	<?php if ( $row->link && $row->state && ( ! $row->category || ( $row->category && $row->cat_state ) ) ) { ?>
	        <a href="<?php echo $row->link; ?>"><?php echo $row->name; ?></a>
        <?php } else {
        	echo $row->name;
        } ?>
    </td>
    <?php if ( $this->menu_params->get( 'show_username' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'username_width' ); ?>" align="left">
        <?php echo $row->username; ?>
    </td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_category' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'category_width' ); ?>" align="left">
        <?php echo ( $row->category ) ? $row->category : '-'; ?>
    </td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_email' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'email_width' ); ?>" align="left">
        <?php echo $row->email; ?>
    </td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_usergroup' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'usergroup_width' ); ?>" align="left">
        <?php echo $row->usertype; ?>
    </td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_state' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'state_width' ); ?>" align="left">
	    <?php echo ( $row->block ) ?  '<img src="administrator/images/publish_x.png" alt=" " border="0" />' : '<img src="administrator/images/tick.png" alt=" " border="0" />'; ?>
    </td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_date' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'date_width' ); ?>" align="left">
        <?php echo JHTML::_('date',  $row->created, $this->date_format ); ?>
    </td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_hits' ) ) : ?>
    <td width="<?php echo $this->menu_params->get( 'hits_width' ); ?>" align="left">
        <?php echo $row->hits; ?>
    </td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'show_edit' ) ) : ?>
    <td width="30" align="center">
    	<?php if ( @$row->content_typeid && ! $row->checked_out ) { ?>
			<a href="<?php echo $row->edit_link; ?>"><img src="media/jseblod/_icons/edit-default.png" alt=" " border="0" /></a>
        <?php } else { echo '-'; }?>
	</td>
    <?php endif; ?>
    <?php if ( $this->menu_params->get( 'enable_enable' ) || $this->menu_params->get( 'enable_block' ) || $this->menu_params->get( 'enable_delete' ) ) : ?>
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