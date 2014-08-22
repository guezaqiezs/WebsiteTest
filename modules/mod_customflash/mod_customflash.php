<?php
/**
 * CustomFlash Joomla! 1.5 Native Component
 * @version 1.2.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/


defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.DS.'components'.DS.'com_customflash'.DS.'includes'.DS.'customflash.php');


$cf=new CustomFlashMovie;

$movieid=$params->get('movieid');

if($movieid==0)
{
	$cf->checkflashavailability=(int)$params->get('checkflashavailability');
	$cf->alternativehtml=$params->get('alternativehtml');
	$cf->alternativeimage=$params->get('alternativeimage');
	
	$cf->file=$params->get('file');
	$cf->width=(int)(preg_replace("/[^0-9]/", "", $params->get('width')));
	$cf->height=(int)(preg_replace("/[^0-9]/", "", $params->get('height')));

	$cf->quality=strtolower(trim(preg_replace("/[^a-zA-Z]/", "", $params->get('quality')))); //high
	$cf->wmode=strtolower(trim(preg_replace("/[^a-z]/", "", $params->get('wmode')))); //default transparent

	$cf->bgcolor=strtolower(trim(preg_replace("/[^a-z0-9]/", "", $params->get('bgcolor')))); //default #000000

	$cf->play=(int)$params->get('play');
	$cf->scale=(int)(preg_replace("/[^a-z]/", "", $params->get('scale')));
	$cf->flashvars=$params->get('flashvars');
	
	
}
else
{
	$cfc=new CustomFlashClass;
	$movierow=$cfc->getMovieRowByID($movieid);
	$cf->setFlashMovie($movierow);
}

echo $cf->getFlashMovie();


?>