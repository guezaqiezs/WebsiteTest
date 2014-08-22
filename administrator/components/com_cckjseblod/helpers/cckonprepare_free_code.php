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

// On Prepare Free Code
for ( $i = 0; $i < $textObj->nCodes; $i++ ) {
	$fieldName		=	$textObj->batchCodes[$i]['name'];
	$fieldValue		=	$items[$fieldName]->defaultvalue;
	
	$search		=	'#\$ARRAY\[\'([a-zA-Z0-9_]*)\'\]#U';
	preg_match_all( $search, $fieldValue, $matches );

	if ( sizeof ( $matches[1] ) ) {
		foreach( $matches[1] as $key => $value ) {
			$matches[2][$key]	=	$items[$value]->value;
		}
	}
	//
	$items	=	CCKjSeblodItem_Store::beforeContentSaveArray( $items, $textObj->batchCodes[$i]['code'] );	
	//
	if ( sizeof ( $matches[1] ) ) {
		foreach( $matches[1] as $key => $value ) {
			$textObj->text	=	str_replace( '::'.$value.'::'.$matches[2][$key].'::/'.$value.'::', '::'.$value.'::'.$items[$value]->value.'::/'.$value.'::', $textObj->text );
		}
	}

}
?>