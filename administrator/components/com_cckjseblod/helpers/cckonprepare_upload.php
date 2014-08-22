<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

// On Prepare Upload
jimport('joomla.filesystem.file');
if ( !(bool) ini_get( 'file_uploads' ) ) {
	JError::raiseWarning( 'SOME_ERROR_CODE', JText::_( 'WARNINSTALLFILE' ) );
}
//	if ( $userfile['error'] || $userfile['size'] < 1 ) {
//		JError::raiseWarning( 'SOME_ERROR_CODE', JText::_( 'WARNINSTALLUPLOADERROR' ) );
//	}

$doSave	=	0;
for ( $k = 0; $k < $textObj->nUploads; $k++ ) {
	$field_name		=	$textObj->batchUploads[$k]['field_name'];
	$parent_name	=	@$textObj->batchUploads[$k]['parent_name'];
	$field_typename	=	$textObj->batchUploads[$k]['field_typename'];
	//
	$old_path		=	$textObj->batchUploads[$k]['file_path'];
	$file_path		=	$textObj->batchUploads[$k]['file_path'];
	$file_name		=	$textObj->batchUploads[$k]['file_name'];
	$tmp_name		=	$textObj->batchUploads[$k]['tmp_name'];
	$x2k			=	$textObj->batchUploads[$k]['r'];
	
	if ( $textObj->batchUploads[$k]['content_folder'] && $textObj->isNew ) {
		$file_path		.=	$textObj->item_id.'/';
		$file_location	=	$file_path.$file_name;
		$file_path		=	str_replace( '/', DS, $file_path );
		$location		=	JPATH_SITE.DS.$file_path.$file_name;
		if ( $x2k > -1 ) {
			if ( $parent_name ) { //GroupX
				$textObj->text	=	str_replace( '::'.$field_name.'|'.$x2k.'|'.$parent_name.'::'.$old_path.$file_name.'::/'.$field_name.'|'.$x2k.'|'.$parent_name.'::',
												 '::'.$field_name.'|'.$x2k.'|'.$parent_name.'::'.$file_location.'::/'.$field_name.'|'.$x2k.'|'.$parent_name.'::', $textObj->text );
			} else { //FieldX
				$textObj->text	=	str_replace( '||'.$field_name.'||'.$old_path.$file_name.'||/'.$field_name.'||', '||'.$field_name.'||'.$file_location.'||/'.$field_name.'||', $textObj->text );
			}
		} else {
			$textObj->text	=	str_replace( '::'.$field_name.'::'.$old_path.$file_name.'::/'.$field_name.'::', '::'.$field_name.'::'.$file_location.'::/'.$field_name.'::', $textObj->text );
		}
		$doSave	=	1;
	} else {
		$file_location	=	$file_path.$file_name;
		$file_path		=	str_replace( '/', DS, $file_path );
		$location		=	JPATH_SITE.DS.$file_path.$file_name;
	}
	
	if ( ! JFolder::exists( JPATH_SITE.DS.$file_path ) ) {
		JFolder::create( JPATH_SITE.DS.$file_path );
		JFile::write( JPATH_SITE.DS.$file_path.DS.'index.html', '<html><body bgcolor="#FFFFFF"></body></html>' );
	}
	if ( JFile::upload( $tmp_name, $location ) ) {
		$itemValue	=	$file_location;
				
		if ( $field_typename == 'upload_image' ) {
			// -- Image Process
			$item_content	=	$textObj->batchUploads[$k]['item_content'];
			$item_options	=	$textObj->batchUploads[$k]['item_options'];
			$item_format	=	$textObj->batchUploads[$k]['item_format'];
			$item_width		=	$textObj->batchUploads[$k]['item_width'];
			$item_height	=	$textObj->batchUploads[$k]['item_height'];
			$item_extra		=	$textObj->batchUploads[$k]['item_extra'];
		
			$newSize	=	getimagesize($location);
			$newWidth	=	$newSize[0];
			$newHeight	=	$newSize[1];
			$newRatio	=	$newWidth / $newHeight;	
			$newExt		=	substr( strrchr( $location, "." ), 1 );
			$waterI		=	$item_content;
			$waterExtI	=	substr( strrchr( $waterI, "." ), 1 );
			switch( $newExt ) {
				case 'gif':
				case 'GIF':
					$resImage	=	@ImageCreateFromGIF( $location );
					break;
				case 'jpg':
				case 'JPG':
        case 'jpeg': 
        case 'JPEG': 
					$resImage	=	@ImageCreateFromJPEG( $location );
					break;
				case 'png':
				case 'PNG':
					$resImage	=	@ImageCreateFromPNG( $location );
					break;
				default:
					break;
			}
			if ( ! $resImage ) {
				//...
			}
			//umask(0002);
			if ( $item_options && strpos( $item_options, '||' ) !== false && strpos( $item_options, '--' ) !== false ) {
				$options	=	explode( '||', $item_options );
			}
			array_unshift( $options, $item_format.'--'.$item_width.'--'.$item_height );
			if ( sizeof( $options ) ) {
				$i	=	0;
				foreach($options as $opts) {
					$opt	=	explode( '--', $opts );
					if ( $opt[0] ) {
						$newX	= 	0;
						$newY	=	0;
						$thumbX	=	0;
						$thumbY =	0;
						if ( ! $opt[1] && ! $opt[1] ) {
							break;
						}							
						$width	=  ( ! $opt[1] && $opt[2] ) ? round( $opt[2] * $newRatio ) : $opt[1];
						$height	=  ( $opt[1] && ! $opt[2] ) ? round( $opt[1] / $newRatio ) : $opt[2];
						$ratio	=	$width / $height;
						switch( $opt[0] )
						{
							case "addcolor":
								$thumbWidth		=	( $ratio > $newRatio ) ? round( $height * $newRatio ) : $width;
								$thumbHeight	=	( $ratio < $newRatio ) ? round( $width / $newRatio ) : $height;
								$thumbX			=	( $width / 2 ) - ( $thumbWidth / 2 );
								$thumbY			=	( $height / 2 ) - ( $thumbHeight / 2 );
								break;
							case "crop":
								$thumbWidth		=	( $ratio < $newRatio ) ? round( $height * $newRatio ) : $width;
								$thumbHeight	=	( $ratio > $newRatio ) ? round( $width / $newRatio ) : $height;
								$thumbX			=	( $width / 2 ) - ( $thumbWidth / 2 );
								$thumbY			=	( $height / 2 ) - ( $thumbHeight / 2 );
								break;
							case "maxfit":
								$width			=	( $width > $newWidth ) ? $newWidth : $width;
								$height			=	( $height > $newHeight ) ? $newHeight : $height;
								$width			=	( $ratio > $newRatio ) ? round( $height * $newRatio ) : $width;
								$height			=	( $ratio < $newRatio ) ? round( $width / $newRatio ) : $height;
								$thumbWidth		=	$width;
								$thumbHeight	=	$height;
								break;
							case "stretch":
								$thumbWidth		=	$width;
								$thumbHeight	=	$height;
								break;
							default:
								break;
						}
						$thumbImage	=	imageCreateTrueColor( $width, $height );
						if ( $newExt == 'png' || $newExt == 'PNG' ) {
							imagealphablending( $thumbImage, false );
						}
						//add color
						if( $opt[0] == 'addcolor' ) {
							$r		=	hexdec( substr( $item_extra, 1, 2 ) );
							$g		=	hexdec( substr( $item_extra, 3, 2 ) );
							$b		=	hexdec( substr( $item_extra, 5, 2 ) );
							$color	=	imagecolorallocate( $thumbImage, $r, $g, $b );
							imagefill( $thumbImage, 0, 0, $color );
						}
						//
						imagecopyresampled( $thumbImage, $resImage, $thumbX, $thumbY, $newX, $newY, $thumbWidth, $thumbHeight, $newWidth, $newHeight );
						if ( $i == 0 ) {
							//add mask
							if ( $opt[0] == 'maxfit' && $newHeight > $newWidth && JFile::exists( JPATH_SITE.DS.str_replace( '.'.$waterExtI, '2.'.$waterExtI, $waterI ) ) ) {
								$maskImage	=	ImageCreateFromPNG( JPATH_SITE.DS.str_replace( '.'.$waterExtI, '2.'.$waterExtI, $waterI ) );
								imagealphablending( $maskImage, 1 );
								imagecopy( $thumbImage, $maskImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight );
							} else {										
								if ( JFile::exists( JPATH_SITE.DS.$waterI ) ) {
									$maskImage	=	ImageCreateFromPNG( JPATH_SITE.DS.$waterI );
									imagealphablending( $maskImage, 1 );
									imagecopy( $thumbImage, $maskImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight );
								}
							}
							//
							$thumbLocation	=	$location;
							if ( JFile::exists( $location ) ) {
								JFile::delete( $location );
							}
						} else {
							if ( ! JFolder::exists( JPATH_SITE.DS.$file_path.'_thumb'.$i ) ) {
								JFolder::create( JPATH_SITE.DS.$file_path.'_thumb'.$i );
								JFile::write( JPATH_SITE.DS.$file_path.'_thumb'.$i.DS.'index.html', '<html><body bgcolor="#FFFFFF"></body></html>' );
							}
							$thumbLocation	=	JPATH_SITE.DS.$file_path.'_thumb'.$i.DS.$file_name;
						}
						switch( $newExt ) {
							case 'gif':
							case 'GIF':
								imagegif( $thumbImage, $thumbLocation );
								break;
							case 'jpg':
							case 'JPG':
							case 'jpeg': 
							case 'JPEG': 
								imagejpeg( $thumbImage, $thumbLocation, 90 );
								break;
							case 'png':
							case 'PNG':
								imagesavealpha($thumbImage, true);
								imagepng( $thumbImage, $thumbLocation, 9 );
								break;
							default:
								break;
						}
						imagedestroy( $thumbImage );
					}
					$i++;
				}
			}
			// -- Image Process End
		}
	} else {
		$itemValue	=	'';
		if ( $x2k > -1 ) {
			if ( $parent_name ) { //GroupX
				$textObj->text	=	str_replace( '::'.$field_name.'|'.$x2k.'|'.$parent_name.'::'.$old_path.$file_name.'::/'.$field_name.'|'.$x2k.'|'.$parent_name.'::',
												 '::'.$field_name.'|'.$x2k.'|'.$parent_name.'::'.$file_location.'::/'.$field_name.'|'.$x2k.'|'.$parent_name.'::', $textObj->text );
			} else { //FieldX
				$textObj->text	=	str_replace( '||'.$field_name.'||'.$old_path.$file_name.'||/'.$field_name.'||', '||'.$field_name.'||||/'.$field_name.'||', $textObj->text );
			}
		} else {
			$textObj->text	=	str_replace( '||'.$field_name.'||'.$old_path.$file_name.'||/'.$field_name.'||', '||'.$field_name.'||||/'.$field_name.'::', $textObj->text );
		}
		$doSave	=	1;
	}
}

if ( $doSave ) {
	$row		=&	JTable::getInstance( 'content' );
	$row->load( $textObj->item_id );
	if ( $actionMode == 1 ) {
		$row->description	=	$textObj->text;
	} else {
		$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
		$tagPos	= preg_match( $pattern, $textObj->text );
		if ( $tagPos == 0 )	{
			$row->introtext	= $textObj->text;
			$row->fulltext = '';
		} else 	{
			list( $row->introtext, $row->fulltext ) = preg_split( $pattern, $textObj->text, 2 );
		}
	}
	if ( ! $row->store() ) {
		$this->setError( $this->_db->getErrorMsg() );
	}	
}
?>