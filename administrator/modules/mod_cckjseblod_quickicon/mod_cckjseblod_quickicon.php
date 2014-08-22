<?php
/**
* @version 			1.6.0
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			Extended Quick Icons - jSeblod CCK ( Content Construction Kit )
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mode		=	$params->get( 'mode', 2 );
$enable01	=	$params->get( 'enable01' );
$enable02	=	$params->get( 'enable02' );
$enable03	=	$params->get( 'enable03' );
$enable04	=	$params->get( 'enable04' );
$enable05	=	$params->get( 'enable05' );
$com		=	array();
$icon		=	array();
if ( $enable01 ) {
	$com[]	=	$params->get( 'component01' );
	$icon[]	=	$params->get( 'icon01' );
}
if ( $enable02 ) {
	$com[]	=	$params->get( 'component02' );
	$icon[]	=	$params->get( 'icon02' );
}
if ( $enable03 ) {
	$com[]	=	$params->get( 'component03' );
	$icon[]	=	$params->get( 'icon03' );
}
if ( $enable04 ) {
	$com[]	=	$params->get( 'component04' );
	$icon[]	=	$params->get( 'icon04' );
}
if ( $enable05 ) {
	$com[]	=	$params->get( 'component05' );
	$icon[]	=	$params->get( 'icon05' );
}
if (!defined( '_JOS_CCKJSEBLODQUICKICON_MODULE' ))
{
	/** ensure that functions are declared only once */
	define( '_JOS_CCKJSEBLODQUICKICON_MODULE', 1 );

	function quickiconCCKjSeblodButton( $link, $image, $text, $path )
	{
		global $mainframe;
		$lang		=&	JFactory::getLanguage();
		$template	=	$mainframe->getTemplate();
		?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo JHTML::_('image.site',  $image, $path, NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}
}
?>

<div id="cpanel">
	<?php
	if ( $mode ) {
		if ( $mode == 2 ) {
			$link	=	'index.php?option=com_cckjseblod&controller=templates';
			quickiconCCKjSeblodButton( $link, 'icon-48-templates.png', JText::_( 'TEMPLATE MANAGER' ), '/modules/mod_cckjseblod_quickicon/assets/images/' );
			$link	=	'index.php?option=com_cckjseblod&controller=types';
			quickiconCCKjSeblodButton( $link, 'icon-48-types.png', JText::_( 'CONTENT TYPE MANAGER' ), '/modules/mod_cckjseblod_quickicon/assets/images/' );
			$link	=	'index.php?option=com_cckjseblod&controller=items';
			quickiconCCKjSeblodButton( $link, 'icon-48-items.png', JText::_( 'FIELD MANAGER' ), '/modules/mod_cckjseblod_quickicon/assets/images/' );
			$link	=	'index.php?option=com_cckjseblod&controller=searchs';
			quickiconCCKjSeblodButton( $link, 'icon-48-searchs.png', JText::_( 'SEARCH TYPE MANAGER' ), '/modules/mod_cckjseblod_quickicon/assets/images/' );
		} else {
			$link	=	'index.php?option=com_cckjseblod';
			quickiconCCKjSeblodButton( $link, 'icon-48-cckjseblod.png', JText::_( 'JSEBLOD CCK' ), '/modules/mod_cckjseblod_quickicon/assets/images/' );
		}
		$link	=	'index.php?option=com_cckjseblod&amp;controller=interface&amp;act=-1&amp;cck=1';
		quickiconCCKjSeblodButton( $link, 'icon-48-content.png', JText::_( 'ADD NEW CONTENT' ), '/modules/mod_cckjseblod_quickicon/assets/images/' );
	}
	if ( sizeof( $com ) ) {
		$i	=	0;
		foreach ( $com AS $item ) {
			$link		=	null;
			$link		=	explode( '||', $item );
			$link[1]	=	'index.php?'.$link[1];
			$icon[$i]	=	( $icon[$i] ) ? $icon[$i] : 'icon-48-generic.png';
			quickiconCCKjSeblodButton( $link[1], $icon[$i], $link[0], '/templates/khepri/images/header/' );
			$i++;
		}
	}
	?>
</div>