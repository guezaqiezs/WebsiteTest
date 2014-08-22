<?php
/**
* Twitter-Roll 'The Module' for J1.5
* @version 1.0.4
* @author GraphicAholic
* http://www.graphicaholic.com
* Based on http://jquery.malsup.com/twitter/
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* 
**/
defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
if ($params->get('load_jquery') == 1) {
	$document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js');
}
$document->addScriptDeclaration("jQuery.noConflict();");
$document->addStyleSheet( JURI::base() . 'modules/mod_twitterroll/css/jquery.ui.theme.css', 'text/css');
$title = $params->get('title', '');
$titleLink = $params->get('titleLink', 'http://');
$tweetScroll = $params->get('tweetScroll', 4000);
$searchTerm = $params->get('searchTerm', '');
$colorExterior = $params->get('colorExterior', '#dddddd');
$colorInterior = $params->get('colorInterior', '#f6f6f6');
$borderEffect = $params->get('borderEffect', '');
$noborderEffect = $params->get('noborderEffect', '');
$height = $params->get('height', 300);
$width = $params->get('width', 'auto');
$avatar = $params->get('avatar', '');
if($avatar == "0") $avatar = "true";
if($avatar == "1") $avatar = "false";
$pause = $params->get('pause','');
if($pause == "0") $pause = "true";
if($pause == "1") $pause = "false";
$time = $params->get('time','');
if($time == "0") $time = "true";
if($time == "1") $time = "false";
$bird = $params->get('bird','');
if($bird == "0") $bird = "false";
if($bird == "1") $bird = "true";
$moduleId = $module->id;
?>
<div id="twitter<?php echo $moduleId; ?>"></div>
<style type="text/css">
#twitter<?php echo $moduleId; ?> {
  height:<?php echo $height; ?>;
  width:<?php echo $width; ?>;
}
</style>
<?php if ($borderEffect == "1") : ?>
<script type="text/javascript" src="modules/mod_twitterroll/js/twitter.search.border.js"></script>
<script type="text/javascript">
var $tmb = jQuery.noConflict();
$tmb(function(){
  $tmb('#twitter<?php echo $moduleId; ?>').twitterSearchesB({ 
    term:  '<?php echo $searchTerm; ?>', 
    title: '<?php echo $title; ?>', 
    titleLink: '<?php echo $titleLink; ?>', 
    timeout: <?php echo $tweetScroll; ?>,
    colorExterior: '<?php echo $colorExterior; ?>',
    colorInterior: '<?php echo $colorInterior; ?>',
    avatar: <?php echo $avatar; ?>,
    pause: <?php echo $pause; ?>,
    time: <?php echo $time; ?>,
    bird: <?php echo $bird; ?>

  });
});
</script>
<?php endif ; ?>
<?php if ($noborderEffect == "1") : ?>
<script type="text/javascript" src="modules/mod_twitterroll/js/twitter.search.noborder.js"></script>
<script type="text/javascript">
var $tmn = jQuery.noConflict();
$tmn(function(){
  $tmn('#twitter<?php echo $moduleId; ?>').twitterSearchesN({ 
    term:  '<?php echo $searchTerm; ?>', 
    title: '<?php echo $title; ?>', 
    titleLink: '<?php echo $titleLink; ?>', 
    timeout: <?php echo $tweetScroll; ?>,
	colorInterior: '<?php echo $colorInterior; ?>',
    avatar: <?php echo $avatar; ?>,
    pause: <?php echo $pause; ?>,
    time: <?php echo $time; ?>,
    bird: <?php echo $bird; ?>

  });
});
</script>
<?php endif ; ?>