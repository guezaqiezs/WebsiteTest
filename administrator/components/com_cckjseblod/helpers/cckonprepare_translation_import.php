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

// On Prepare Translation (Import)
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'tables'.DS.'JFContent.php' );

$query	=	'SELECT id, reference_field FROM #__jf_content'
		.	' WHERE reference_table="'.$default['action_text'].'"'
		.	' AND reference_id='.(int)$row->id
		.	' AND language_id='.(int)$default['lang']
		;
$jf_rows	=	CCK_DB_ObjectList( $query, 'reference_field' );
if ( $row->id ) {
	// Title
	$jf						=&	JTable::getInstance( 'jfContent', '' );
	if ( @$jf_rows['title']->id ) {
		$jf->load( $jf_rows['title']->id );
	}
	$jf->language_id		=	$default['lang'];
	$jf->reference_id		=	$row->id;
	$jf->reference_table	=	$default['action_jf_text'];
	$jf->reference_field	=	'title';
	$jf->value				=	$row->title;
	$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
	$jf->modified_by		=	$default['author'];
	$jf->published			=	$default['state'];
	if ( ! $jf->store() ) {
		return false;
	}
	// Alias
	$jf						=&	JTable::getInstance( 'jfContent', '' );
	if ( @$jf_rows['alias']->id ) {
		$jf->load( $jf_rows['alias']->id );
	}
	$jf->language_id		=	$default['lang'];
	$jf->reference_id		=	$row->id;
	$jf->reference_table	=	$default['action_jf_text'];
	$jf->reference_field	=	'alias';
	$jf->value				=	$row->alias;
	$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
	$jf->modified_by		=	$default['author'];
	$jf->published			=	$default['state'];
	if ( ! $jf->store() ) {
		return false;
	}
	// Others
	if ( $default['action_action'] == 1 ) {
		// Description
		$jf						=&	JTable::getInstance( 'jfContent', '' );
		if ( @$jf_rows['description']->id ) {
			$jf->load( $jf_rows['description']->id );
		}
		$jf->language_id		=	$default['lang'];
		$jf->reference_id		=	$row->id;
		$jf->reference_table	=	$default['action_jf_text'];
		$jf->reference_field	=	'description';
		$jf->value				=	$row->introtext;
		$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
		$jf->modified_by		=	$default['author'];
		$jf->published			=	$default['state'];
		if ( ! $jf->store() ) {
			return false;
		}
	} else {
		// Introtext
		$jf						=&	JTable::getInstance( 'jfContent', '' );
		if ( @$jf_rows['introtext']->id ) {
			$jf->load( $jf_rows['introtext']->id );
		}
		$jf->language_id		=	$default['lang'];
		$jf->reference_id		=	$row->id;
		$jf->reference_table	=	$default['action_jf_text'];
		$jf->reference_field	=	'introtext';
		$jf->value				=	$row->introtext;
		$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
		$jf->modified_by		=	$default['author'];
		$jf->published			=	$default['state'];
		if ( ! $jf->store() ) {
			return false;
		}
		// Fulltext
		$jf						=&	JTable::getInstance( 'jfContent', '' );
		if ( @$jf_rows['fulltext']->id ) {
			$jf->load( $jf_rows['fulltext']->id );
		}
		$jf->language_id		=	$default['lang'];
		$jf->reference_id		=	$row->id;
		$jf->reference_table	=	$default['action_jf_text'];
		$jf->reference_field	=	'fulltext';
		$jf->value				=	$row->fulltext;
		$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
		$jf->modified_by		=	$default['author'];
		$jf->published			=	$default['state'];
		if ( ! $jf->store() ) {
			return false;
		}
		// Attribs
		$jf						=&	JTable::getInstance( 'jfContent', '' );
		if ( @$jf_rows['attribs']->id ) {
			$jf->load( $jf_rows['attribs']->id );
		}
		$jf->language_id		=	$default['lang'];
		$jf->reference_id		=	$row->id;
		$jf->reference_table	=	$default['action_jf_text'];
		$jf->reference_field	=	'attribs';
		$jf->value				=	$row->attribs;
		$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
		$jf->modified_by		=	$default['author'];
		$jf->published			=	$default['state'];
		if ( ! $jf->store() ) {
			return false;
		}
	}
}
?>