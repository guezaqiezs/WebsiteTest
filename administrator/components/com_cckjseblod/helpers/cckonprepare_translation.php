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

// On Prepare Translation (Article)
if ( $row->id ) {
// ADD TRANSLATIONS

	$query			=	'SELECT s.language_id FROM #__jf_content AS s WHERE s.reference_table="content" AND s.reference_field="title" AND s.reference_id='.(int)$row->id;
	$this->_db->setQuery( $query );
	$translations	=	$this->_db->loadResultArray();
	$langDefaultId	=	CCK_LANG_DefaultId();
	foreach( $langs as $elem ) {
		if ( $elem != $langDefaultId && ( ! $translations || ( $translations && ( array_search( $elem, $translations ) === false ) ) ) ) {
			$jf						=&	JTable::getInstance( 'jfContent', '' );
			$jf->language_id		=	$elem;
			$jf->reference_id		=	$row->id;
			$jf->reference_table	=	'content';
			$jf->reference_field	=	'title';
			$jf->value				=	$row->title;
			$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
			$jf->modified_by		=	$userC->id;
			$jf->published			=	0;
			if ( ! $jf->store() ) {
				return false;
			}
			//
			$jf						=&	JTable::getInstance( 'jfContent', '' );
			$jf->language_id		=	$elem;
			$jf->reference_id		=	$row->id;
			$jf->reference_table	=	'content';
			$jf->reference_field	=	'alias';
			$jf->value				=	$row->alias;
			$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
			$jf->modified_by		=	$userC->id;
			$jf->published			=	0;
			if ( ! $jf->store() ) {
				return false;
			}
			//
			$jf						=&	JTable::getInstance( 'jfContent', '' );
			$jf->language_id		=	$elem;
			$jf->reference_id		=	$row->id;
			$jf->reference_table	=	'content';
			$jf->reference_field	=	'introtext';
			$jf->value				=	$row->introtext;
			$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
			$jf->modified_by		=	$userC->id;
			$jf->published			=	0;
			if ( ! $jf->store() ) {
				return false;
			}
			//
			$jf						=&	JTable::getInstance( 'jfContent', '' );
			$jf->language_id		=	$elem;
			$jf->reference_id		=	$row->id;
			$jf->reference_table	=	'content';
			$jf->reference_field	=	'fulltext';
			$jf->value				=	$row->fulltext;
			$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
			$jf->modified_by		=	$userC->id;
			$jf->published			=	0;
			if ( ! $jf->store() ) {
				return false;
			}
			//
			$jf						=&	JTable::getInstance( 'jfContent', '' );
			$jf->language_id		=	$elem;
			$jf->reference_id		=	$row->id;
			$jf->reference_table	=	'content';
			$jf->reference_field	=	'attribs';
			$jf->value				=	$row->attribs;
			$jf->modified			=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
			$jf->modified_by		=	$userC->id;
			$jf->published			=	0;
			if ( ! $jf->store() ) {
				return false;
			}
		}
	}
	
} else {
// EDIT TRANSLATIONS

	$row->id	=	$cckId;
	$jcontent	=	JRequest::getVar( 'jcontent', array(), 'post', 'array');
	
	$datenow	=&	JFactory::getDate();
	
	$jf_rows	=	CCKjSeblodItem_Form::getResultArrayFromDatabase( 'SELECT id FROM #__jf_content WHERE reference_table="content" AND reference_id='
																	.(int)$cckId.' AND language_id='.(int)$lang_id );
	
	/***********
	 *  Title  * 
	 ***********/
	
	if ( @$jcontent['title'] ) {
		$jf_title	=	@$jcontent['title'];
	}
	
	if ( sizeof( $textObj->substitute ) ) {
		$title	=	null;
		foreach( $textObj->substitute as $sub ) {
			$title .= $sub.' ';
		}
		$jf_title	=	trim( $title );
	}
	if ( ! @$jf_title ) {
		$jf_title	=	$datenow->toFormat( "%Y %m %d %H %M %S" );
	}
	 
	/**************************
	 *  Introtext & Fulltext  *
	 **************************/
	
	$textObj->text = str_replace( '<br>', '<br />', $textObj->text );
	$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
	$tagPos	= preg_match( $pattern, $textObj->text );
	if ( $tagPos == 0 )	{
		$jf_introtext	=	$textObj->text;
		$jf_fulltext	=	'';
	} else 	{
		list( $jf_introtext, $jf_fulltext ) = preg_split( $pattern, $textObj->text, 2 );
	}
	 
	/*************
	 *  Attribs  * 
	 *************/
	
	$jf_attribs	=	'';
	
	//
	//
	//
	
	$modified	=	$datenow->toFormat( "%Y-%m-%d %H:%M:%S" );
	$published	=	( _BOOL_PUBLISH ) ? 1 : 0;
	
	foreach ( $jf_rows as $jf_row ) {
		$jf	=&	JTable::getInstance( 'jfContent', '' );
		$jf->load( $jf_row );
		switch ( $jf->reference_field ) {
			case 'title':
				$jf->value			=	$jf_title;
				$jf->modified		=	$modified;
				$jf->modified_by	=	$userC->id;
				$jf->published		=	( @$jf->published ) ? $jf->published : $published;
				break;
			case 'alias':	
				if ( @$jcontent['title'] ) {
					if( empty( $jcontent['alias'] ) ) {
						$jcontent['alias'] = $jcontent['title'];
					}
					$jcontent['alias'] = JFilterOutput::stringURLSafe( $jcontent['alias'] );
					
					if(trim(str_replace('-','',$jcontent['alias'])) == '') {
						$jcontent['alias'] = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
					}
				}
				if ( @$jcontent['alias'] ) {
					$jf_alias	=	$jcontent['alias'];
				}
				if ( ! @$jf_alias ) {
					if ( $jf->value ) {
						$jf_alias	=	$jf->value;
					} else {
						$jf_alias	=	JFilterOutput::stringURLSafe( $jf_title );
						if( trim( str_replace( '-', '', $jf_alias ) ) == '' ) {
							$jf_alias	=	$datenow->toFormat( "%Y-%m-%d-%H-%M-%S" );
						}
					}
				}
				$jf->value			=	$jf_alias;
				$jf->modified		=	$modified;
				$jf->modified_by	=	$userC->id;
				$jf->published		=	( @$jf->published ) ? $jf->published : $published;
				break;
			case 'introtext':
				$jf->value			=	$jf_introtext;
				$jf->modified		=	$modified;
				$jf->modified_by	=	$userC->id;
				$jf->published		=	( @$jf->published ) ? $jf->published : $published;
				break;
			case 'fulltext':
				$jf->value			=	$jf_fulltext;
				$jf->modified		=	$modified;
				$jf->modified_by	=	$userC->id;
				$jf->published		=	( @$jf->published ) ? $jf->published : $published;
				break;
			case 'attribs':
				//$jf->value		=	$jf_attribs;
				$jf->modified		=	$modified;
				$jf->modified_by	=	$userC->id;
				$jf->published		=	( @$jf->published ) ? $jf->published : $published;
				break;
			default:
				break;
		}
		
		if ( ! $jf->store() ) {
			return false;
		}
	}
}
?>