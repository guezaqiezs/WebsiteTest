<?php
$allowed_ext	=	array (
  	// archives
  	'zip' => 'application/zip',
  	'tgz' => 'application/x-compressed',
	'rar' => 'application/x-rar-compressed',
  	'gz' => 'application/x-gzip',

  	// documents
  	'pdf' => 'application/pdf',
  	'doc' => 'application/msword',
  	'xls' => 'application/vnd.ms-excel',
  	'ppt' => 'application/vnd.ms-powerpoint',
  	'pps' => 'application/vnd.ms-powerpoint',
  	'txt' => 'text/plain',
  	'csv' => 'text/csv',
  
  	// executables
  	'exe' => 'application/octet-stream',

  	// images
  	'gif' => 'image/gif',
  	'png' => 'image/png',
  	'jpg' => 'image/jpeg',
  	'jpeg' => 'image/jpeg',
  	'tif' => 'image/tiff',
  	'tiff' => 'image/tiff',
	'bmp' => 'image/bmp',
	
	// audio
  	'mp3' => 'audio/mpeg',
  	'wav' => 'audio/x-wav',

  	// video
  	'mpeg' => 'video/mpeg',
	'mpg' => 'video/mpeg',
  	'mpe' => 'video/mpeg',
  	'mov' => 'video/quicktime',
  	'avi' => 'video/x-msvideo',
   	'mp4' => 'video/mp4',
	'flv' => 'video/x-flv'
);

if ( @$allowed_ext[$ext] == '' ) {
	$mtype	=	'';
	if ( function_exists( 'mime_content_type' ) ) {
		$mtype	=	mime_content_type( $path );
	} else if ( function_exists( 'finfo_file' ) ) {
		$finfo = finfo_open( FILEINFO_MIME );
		$mtype = finfo_file( $finfo, $path );
		finfo_close( $finfo );
  	}
	if ($mtype == '') {
    	$mtype	=	"application/force-download";
	}
} else {
	$mtype	=	$allowed_ext[$ext];
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: $mtype");
header("Content-Disposition: attachment; filename=\"$name\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . $size);

$file	=	@fopen($path,"rb");
if ( $file ) {
	while( ! feof( $file ) ) {
    	print( fread( $file, 1024*8 ) );
    	flush();
    	if ( connection_status()!= 0 ) {
			@fclose( $file );
			die();
		}
	}
	@fclose( $file );
}
?>