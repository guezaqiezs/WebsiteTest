<?php
/**
 * CustomFlash Joomla! 1.5 Native Component
 * @version 1.2.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.DS.'components'.DS.'com_customflash'.DS.'includes'.DS.'customflash.php');
  
$cfc=new CustomFlashClass;

$movieid=(int)$this->params->get( 'movieid' );
if($movieid!=0)
{
	$align=$this->params->get( 'align' );

	$movierow=$cfc->getMovieRowByID($movieid);
	
	$cf=new CustomFlashMovie;
	$cf->setFlashMovie($movierow);
	
	if($align=='')
		echo $cf->getFlashMovie();
	else
	{
		echo '<div style="text-align:'.$align.';">'.$cf->getFlashMovie().'</div>';
	}
}



?>