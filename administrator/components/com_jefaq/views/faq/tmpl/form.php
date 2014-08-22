<?php
/**
 * jeFAQ package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('_JEXEC') or die('Restricted access');

$doc = & JFactory::getDocument();
$js  = JURI::base().'components/com_jefaq/assets/js/validate.js';
$doc->addScript($js);

$editor		=& JFactory::getEditor();
?>

<!-- Get the Editor Contents using the joomla editor function -->
<script language="javascript" type="text/javascript">

function editorContent()
{
	var text = <?php echo $editor->getContent( 'answers' ); ?>
	return text;
}

</script>
<!-- Script End -->

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'JE_FAQ_DETAILS' ); ?></legend>
			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_( 'JE_FAQ_QUESTIONS' ); ?>:
						</label>
					</td>
					<td>
						<input class="required" type="text" name="questions" id="questions" size="100" maxlength="256" value="<?php echo $this->row->questions;?>" onblur="elementvalidate(this.id)" />
					</td>
					<td>
						<span id="questions-error"></span>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key" valign="top">
						<label for="name">
							<?php echo JText::_( 'JE_FAQ_ANSWERS' ); ?>:
						</label>
					</td>
					<td valign="top">
						<?php
								//Editor parameters : areaname, content, width, height, cols, rows
								echo $editor->display( 'answers',  $this->row->answers , '100%', '300', '75', '30' ) ;
						?>
					</td>
					<td>
						<span id="answers-error"></span>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_( 'JE_FAQ_PUBLISHED' ); ?>:
						</label>
					</td>
					<td colspan="2">
						<?php
							$published = ($this->row->id) ? $this->row->state : 1;
							echo JHTML::_('select.booleanlist',  'state', '', $published );
						?>
					</td>
				</tr>
			</table>
	</fieldset>

	<div class="clr"></div>

	<input type="hidden" name="option" value="com_jefaq" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="c" value="faq" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<p class="copyright" align="center">
	<?php echo JText::_( 'JE_DEVELOPED' ); ?> <a href="http://www.webhostings.in" title="<?php echo JText::_('JE_DEVELOPEDBY'); ?>" target="_blank"> <?php echo JText::_('JE_DEVELOPEDBY'); ?> </a>
	<br/>
	<?php echo JText::_( 'JE_COMMENTS' ); ?> <a href="http://extensions.joomla.org/extensions/directory-a-documentation/faq/12645" title="<?php echo JText::_('JE_RATINGS'); ?>" target="_blank"><?php echo JText::_( 'JE_HERE' ); ?></a>
</p>