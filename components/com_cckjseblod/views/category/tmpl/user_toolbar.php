<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<style type="text/css">
button.common_submit {
    background-color:<?php echo $this->menu_params->get( 'button_bgcolor', '#6cc634' ) ?>;
    border:0 none;
    color:<?php echo $this->menu_params->get( 'button_color', '#ffffff' ) ?>;
    font-size:11px;
    font-weight:bold;
    height:24px;
	margin-left:2px;
	margin-right:2px;
	cursor:pointer;
}
</style>
<br />
<?php if ( $this->menu_params->get( 'enable_add' ) == 'left' ) : ?>
    <button style="float: <?php echo $this->menu_params->get( 'enable_add', 'left' ) ?>;" class="common_submit" type="submit" name="task" value="category_add">
        <?php echo JText::_( 'NEW' ); ?>
    </button>
<?php endif; ?>
<?php if ( $this->menu_params->get( 'enable_publish' ) == 'left' ) : ?>
	<button style="float: <?php echo $this->menu_params->get( 'enable_publish', 'left' ) ?>;" class="common_submit" type="submit" name="task" value="category_publish">
		<?php echo JText::_( 'PUBLISH' ); ?>
	</button>
<?php endif; ?>
<?php if ( $this->menu_params->get( 'enable_unpublish' ) == 'left' ) : ?>
	<button style="float: <?php echo $this->menu_params->get( 'enable_unpublish', 'left' ) ?>;" class="common_submit" type="submit" name="task" value="category_unpublish">
		<?php echo JText::_( 'UNPUBLISH' ); ?>
	</button>
<?php endif; ?>
<?php if ( $this->menu_params->get( 'enable_delete' ) == 'left' ) : ?>
    <button style="float: <?php echo $this->menu_params->get( 'enable_delete', 'left' ) ?>;" class="common_submit" type="submit" name="task" value="category_trash">
        <?php echo JText::_( 'DELETE' ); ?>
    </button>
<?php endif; ?>

<?php if ( $this->menu_params->get( 'enable_delete' ) == 'right' ) : ?>
    <button style="float: <?php echo $this->menu_params->get( 'enable_delete', 'left' ) ?>;" class="common_submit" type="submit" name="task" value="category_trash">
        <?php echo JText::_( 'DELETE' ); ?>
    </button>
<?php endif; ?>
<?php if ( $this->menu_params->get( 'enable_unpublish' ) == 'right' ) : ?>
	<button style="float: <?php echo $this->menu_params->get( 'enable_unpublish', 'left' ) ?>;" class="common_submit" type="submit" name="task" value="category_unpublish">
		<?php echo JText::_( 'UNPUBLISH' ); ?>
	</button>
<?php endif; ?>
<?php if ( $this->menu_params->get( 'enable_publish' ) == 'right' ) : ?>
	<button style="float: <?php echo $this->menu_params->get( 'enable_publish', 'left' ) ?>;" class="common_submit" type="submit" name="task" value="category_publish">
		<?php echo JText::_( 'PUBLISH' ); ?>
	</button>
<?php endif; ?>
<?php if ( $this->menu_params->get( 'enable_add' ) == 'right' ) : ?>
    <button style="float: <?php echo $this->menu_params->get( 'enable_add', 'left' ) ?>;" class="common_submit" type="submit" name="task" value="category_add">
        <?php echo JText::_( 'NEW' ); ?>
    </button>
<?php endif; ?>