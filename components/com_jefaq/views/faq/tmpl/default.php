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

<div class="componentheading">
	<?php
		if($this->params->get('show_page_title', 1)) {
			echo $this->params->get('page_title');
		} else {
			echo JText::_( 'JE_FAQ_DETAILS' );
		}
	?>
</div>
<div id="contentarea">
<?php
	$pane = &JPane::getInstance('sliders', array('allowAllClose' => true));
	echo $pane->startPane('myPane');
	{
		$k = 0;
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)	{

			$row = &$this->items[$i];

			// Display here the details
			echo $pane->startPanel($row->questions, 'jeFAQquestions');
			echo $row->answers;
			echo $pane->endPanel();

			$k = 1 - $k;
		}
	}
	echo $pane->endPane();

?>
</div>

<br style="clear : both"/>

<!-- You should not remove this below contribution link, If you want to remove this link, then please get the written approval from jextn.com -->
