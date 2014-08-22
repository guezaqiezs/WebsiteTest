<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.mootools' );
JHTML::_( 'behavior.modal' );
//
$lang     =&  JFactory::getLanguage();
$langTag =   $lang->getTag();
//

$javascript ='
	';
$document->addScriptDeclaration( $javascript );
?>

<?php
if ( $mode == 'title' ) { ?>
	<ul class="latestnews<?php echo $params->get( 'moduleclass_sfx' ); ?>">
	<?php if ( sizeof( $list ) ) {
		foreach ( $list as $item ) {
		$item->href	=	CCKjSeblodHelperRoute::getArticleRoute( $item->slug, $item->catslug, $sef, $sef_option, $itemId );
		?>
		<li class="latestnews<?php echo $params->get( 'moduleclass_sfx' ); ?>">
			<a href="<?php echo $item->href; ?>" class="latestnews<?php echo $params->get( 'moduleclass_sfx' ); ?>">
				<?php echo modCCKjSeblod_ListHelper::getTitle( $item->title, $start, $end ); ?></a>
		</li>
	<?php } } ?>
	</ul> <?php
} else {
    if ( $searchType->content >= 2 ) {
		echo $dataL;
	} else { ?>
		<table class="contentpaneopen<?php echo $params->get('moduleclass_sfx'); ?>">
			<tr>
				<td>
				<?php
				foreach( $list as $item ) { ?>
	                <fieldset>
                    <div>
                        <?php if ( $params->get( 'show_num', 1 )) { ?>
                        <span class="small<?php echo $params->get('moduleclass_sfx'); ?>">
                            <?php echo $item->count.'. ';?>
                        </span>
                        <?php } ?>
                        <?php if ( $item->href ) :
                            if ( $params->get( 'show_title', 1 )) {
                            if ($item->browsernav == 1 ) : ?>
                                <a href="<?php echo JRoute::_($item->href); ?>" target="_blank">
                            <?php else : ?>
                                <a href="<?php echo JRoute::_($item->href); ?>">
                            <?php endif;
    
                            echo $item->title;
    
                            if ( $item->href ) : ?>
                                </a>
                            <?php endif; }
                            if ( $params->get( 'show_category', 1 )) {
                            if ( $item->section ) : ?>
                                <?php if ( $params->get( 'show_num', 1 ) || $params->get( 'show_title', 1 ))  { ?>
                                <br /><?php } ?>
                                <span class="small<?php echo $params->get('moduleclass_sfx'); ?>">
                                    (<?php echo $item->section; ?>)
                                </span>
                            <?php endif; ?>
                            <?php } ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php echo $item->text; ?>
                    </div>
                    <?php
                        if ( $params->get( 'show_date', 0 )) { ?>
                    <div class="small<?php echo $params->get('moduleclass_sfx'); ?>">
                        <?php echo $item->created; ?>
                    </div>
                    <?php } ?>
					</fieldset>
				<?php } ?>
				</td>
			</tr>
		</table> <?php
    }
}
?>

<?php if ( $more_link && $more_label && $total ) { ?>
	<div style="text-align: center;">
		<a href="index.php?Itemid=<?php echo $more_link; ?>"><?php echo JText::_( $more_label ); ?></a>
	</div>
<?php } ?>