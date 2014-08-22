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

// On Prepare Submission (Category)
$jcontent	=	JRequest::getVar( 'jcontent', array(), 'post', 'array');
if ( @$jcontent['title'] ) {
	if( empty( $jcontent['alias'] ) ) {
		$jcontent['alias'] = $jcontent['title'];
	}
	$jcontent['alias'] = JFilterOutput::stringURLSafe( $jcontent['alias'] );
	
	if(trim(str_replace('-','',$jcontent['alias'])) == '') {
		$datenow =& JFactory::getDate();
		$jcontent['alias'] = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
	}
}
$jcontentdetails	=	JRequest::getVar( 'jcontentdetails', array(), 'post', 'array');

//
$form['parent_id']	=	( $textObj->save && $textObj->save != 0 ) ? $textObj->save : $form['parent_id'];
//

/**
 * On Prepare Submission (Category)
 **/

$datenow	=&	JFactory::getDate();

$row		=&	JTable::getInstance( 'category' );
$row->id	=	$cckId;

if ( $row->id ) {
	$rowStored	=&	JTable::getInstance( 'category', 'JTable' );
	$rowStored->load( $row->id );
}

$textObj->text = str_replace( '<br>', '<br />', $textObj->text );
$row->description	=	$textObj->text;

$row->bind( $form );
if ( @$form['parent_id'] ) {
	$row->section	=	HelperjSeblod_Helper::getJoomlaSectionId( $form['parent_id'] );
}
$row->bind( $jcontent );
if ( @$jcontent['parent_id'] ) {
	$row->section	=	HelperjSeblod_Helper::getJoomlaSectionId( $jcontent['parent_id'] );
}

if ( ! $row->section ) {
	$row->section	=	CCKjSeblodItem_Form::getResultFromDatabase( 'SELECT id FROM #__sections WHERE alias = "jseblod-cck"' );
}
if ( @$form['created_user_id'] ) {
	$row->created_user_id	=	$form['created_user_id'];
}
if ( @$jcontent['created_user_id'] ) {
	$row->created_user_id	=	$jcontent['created_user_id'];
}
if ( sizeof( $textObj->substitute ) ) {
	$title	=	null;
	foreach( $textObj->substitute as $sub ) {
		$title .= $sub.' ';
	}
	$row->title	=	trim( $title );
}
if ( ! $row->title ) {
	$row->title	=	$datenow->toFormat( "%Y %m %d %H %M %S" );
}
if ( ! $row->alias ) {
	$row->alias = JFilterOutput::stringURLSafe( $row->title );
	if( trim( str_replace( '-', '', $row->alias ) ) == '' ) {
		$row->alias = $datenow->toFormat( "%Y-%m-%d-%H-%M-%S" );
	}
}
$row->bind( $jcontentdetails );
if ( ! $row->id ) {
	$whereSection	=	"section = " . $this->_db->Quote( $row->section );
	$row->ordering	=	$row->getNextOrder( $whereSection );
}
if ( ! $row->store() ) {
	$this->setError( $this->_db->getErrorMsg() );
	return false;
}

// Menu
$textObj->isNew			=	( $cckId ) ? 0 : 1;

$textObj->item_title	=	$row->title;
$textObj->item_id		=	$row->id;
$textObj->item_alias	=	$row->alias;
$textObj->item_state	=	$row->published;
$textObj->item_access	=	$row->access;
?>