<?php
/**
* @author       	http://www.seblod.com
* @origin       	Constantin Boiangiu (http://www.php-help.ro)
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

$currentSession	=	JSession::getInstance( 'none', array() );

$captcha_w		=	( @$item->width ) ? $item->width : 150;
$captcha_h		=	( @$item->height ) ? $item->height : 50;
$directory		=	JPATH_SITE.DS.'tmp'.DS.'jseblodcck-captcha';
if ( ! JFolder::exists( JPATH_SITE.DS.'tmp'.DS.'jseblodcck-captcha' ) ) {
	JFolder::create( JPATH_SITE.DS.'tmp'.DS.'jseblodcck-captcha' );
	JFile::write( $directory.DS."index.html", "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>");
}
$img_path		= 	$directory.DS.'captcha-'.$suffix.'.jpg';

$angle			=	20;

$min_font_size	=	( $item->bool ) ? 14 : 12;
$max_font_size	=	18;
$font_path		=	JPATH_SITE.DS.'media'.DS.'jseblod'.DS.'captcha-math'.DS.'fonts'.DS.'courbd.ttf';

$bg_size		= 	13;

// Mode
if ( $item->bool ) {
	$length		=	( $item->bool2 ) ? $item->bool2 : 4;
	$string		=	null;
	if ( $item->bool3 ) {
		for ( $i = 0; $i < $length; $i++ ) {
			$string	.=	chr( rand( 65, 90 ) );
		}
	} else {
		for ( $i = 0; $i < $length; $i++ ) {
			$string	.=	chr( rand( 97, 122 ) );
		}		
	}
	$currentSession->set( "sabasecu_haoma", $string );
} else {
	$length			=	3;
	$operators		=	array( 1 => '+', 2 => '*', 4 => '-' );
	$first_num		=	rand( 1, 5 );
	$second_num		=	rand( 6, 11 );
	
	if ( $item->bool2 == 1 || $item->bool2 == 2 || $item->bool2 == 4 ) {
		$function	=	$operators[$item->bool2];
	} else if ( $item->bool2 == 3 ) {
		$operators	=	null;
		$operators	=	array( '+', '*' );
		shuffle( $operators );
		$function	=	$operators[0];
	} else if ( $item->bool2 == 5 ) {
		$operators	=	null;
		$operators	=	array( '+', '-' );
		shuffle( $operators );
		$function		=	$operators[0];
	} else if ( $item->bool2 == 6 ) {
		$operators	=	null;
		$operators	=	array( '*', '-' );
		shuffle( $operators );
		$function	=	$operators[0];
	} else if ( $item->bool2 == 7 ) {
		shuffle( $operators );
		$function	=	$operators[0];
	} else {
		$function	=	'+';
	}
	$expression = $second_num.$function.$first_num;
	
	eval( "\$session_var=".$expression.";" );
	$currentSession->set( "sabasecu_haoma", $session_var );
}


// Image
$img			=	imagecreate( $captcha_w, $captcha_h );

$color			=	CCKjSeblodItem_Form::hexrgb( $item->content );
$color_text		=	imagecolorallocate( $img, $color['r'], $color['g'], $color['b'] );
$color			=	CCKjSeblodItem_Form::hexrgb( $item->location );
$color_bg 		=	imagecolorallocate($img, $color['r'], $color['g'], $color['b']);
$color			=	CCKjSeblodItem_Form::hexrgb( $item->extra );
$color_grid 	=	imagecolorallocate($img, $color['r'], $color['g'], $color['b']);

imagefill( $img, 0, 0, $color_bg );	

for ( $t = $bg_size; $t < $captcha_w; $t += $bg_size ){
	imageline( $img, $t, 0, $t, $captcha_h, $color_grid );
}
for ( $t = $bg_size; $t < $captcha_h; $t += $bg_size ){
	imageline( $img, 0, $t, $captcha_w, $t, $color_grid );
}

$item_space		=	$captcha_w / $length;

if ( $item->bool ) {
	for ( $i = 0, $pos = 0; $i < $length; $i++ ) {
		$pos	=	$i + 1;
		imagettftext(
		$img,
		rand(
			$min_font_size,
			$max_font_size
		),
		rand( -$angle , $angle ),
		rand( $pos*$item_space-30, $pos*$item_space-20),
		rand( 25, $captcha_h-20 ),
		$color_text,
		$font_path,
		$string[$i]);
	}
} else {
// 1
imagettftext(
	$img,
	rand(
		$min_font_size,
		$max_font_size
	),
	rand( -$angle , $angle ),
	rand( 10, $item_space-20 ),
	rand( 25, $captcha_h-20 ),
	$color_text,
	$font_path,
	$second_num);

// 2
imagettftext(
	$img,
	rand(
		$min_font_size,
		$max_font_size
	),
	rand( -$angle, $angle ),
	rand( $item_space, 2*$item_space-20 ),
	rand( 25, $captcha_h-25 ),
	$color_text,
	$font_path,
	$function);

// 3
imagettftext(
	$img,
	rand(
		$min_font_size,
		$max_font_size
	),
	rand( -$angle, $angle ),
	rand( 2*$item_space, 3*$item_space-20),
	rand( 25, $captcha_h-25 ),
	$color_text,
	$font_path,
	$first_num);
}

// Create
imagejpeg($img, $img_path );
?>