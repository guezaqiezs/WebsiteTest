<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<table class="contentpaneopen<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<tr>
		<td>
		<?php
		foreach( $this->results as $result ) { ?>
			<fieldset>
				<div>
                	<?php if ( $this->params->get( 'show_num', 1 )) { ?>
					<span class="small<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
						<?php echo $this->pagination->limitstart + $result->count.'. ';?>
					</span>
                    <?php } ?>
					<?php if ( $result->href ) :
	   					if ( $this->params->get( 'show_title', 1 )) {
						if ($result->browsernav == 1 ) : ?>
							<a href="<?php echo JRoute::_($result->href); ?>" target="_blank">
						<?php else : ?>
							<a href="<?php echo JRoute::_($result->href); ?>">
						<?php endif;

						echo $this->escape($result->title);

						if ( $result->href ) : ?>
							</a>
						<?php endif; }
	   					if ( $this->params->get( 'show_category', 1 )) {
						if ( $result->section ) : ?>
		                    <?php if ( $this->params->get( 'show_num', 1 ) || $this->params->get( 'show_title', 1 ))  { ?>
							<br /><?php } ?>
							<span class="small<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
								(<?php echo $this->escape($result->section); ?>)
							</span>
						<?php endif; ?>
                        <?php } ?>
					<?php endif; ?>
				</div>
				<div>
					<?php echo $result->text; ?>
				</div>
				<?php
					if ( $this->params->get( 'show_date', 0 )) { ?>
				<div class="small<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
					<?php echo $result->created; ?>
				</div>
				<?php } ?>
			</fieldset>
		<?php } ?>
		</td>
	</tr>
</table>
