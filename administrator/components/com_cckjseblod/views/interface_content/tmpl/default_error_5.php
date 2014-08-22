<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<fieldset class="adminform_error">
	<table class="admintable" align="left">
		<tr>
			<td align="left" style="text-indent:30px;" >
				<?php echo JText::_( 'MAXIMUM INTO CATEGORY' ); ?>
			</td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset class="adminform">
<legend class="legend-border"><?php echo $this->contentType->title; ?></legend>
	<?php HelperjSeblod_Display::quickBackToSelection(); ?>
</fieldset>