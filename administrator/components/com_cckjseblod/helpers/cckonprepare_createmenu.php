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

// On Prepare CreateMenu
if ( $textObj->isNew ) {

for ( $i = 0; $i < $textObj->nMenus; $i++ ) {
	if ( $textObj->batchMenus[$i]['location'] ) {
		$rowMenu	=&	JTable::getInstance( 'menu' );
		
		if ( strpos( $textObj->batchMenus[$i]['location'], 'menutype-' ) === 0 ) {
			$menutypeId	=	str_replace( 'menutype-', '', $textObj->batchMenus[$i]['location'] );
			$query		=	'SELECT s.menutype, MAX(s.ordering) AS ordering FROM #__menu AS s LEFT JOIN #__menu_types AS cc ON cc.menutype = s.menutype WHERE cc.id='.(int)$menutypeId.' GROUP BY s.menutype';
			$this->_db->setQuery( $query );
			$menutype	=	$this->_db->loadObject();
			$rowMenu->parent	=	0;
			$rowMenu->sublevel	=	0;
		} else {		
			$query	=	'SELECT menutype, MAX(ordering) AS ordering, sublevel FROM #__menu WHERE id='.(int)$textObj->batchMenus[$i]['location'].' GROUP BY menutype';
			$this->_db->setQuery( $query );
			$menutype	=	$this->_db->loadObject();
			
			$rowMenu->parent	=	$textObj->batchMenus[$i]['location'];
			$rowMenu->sublevel	=	$menutype->sublevel + 1;
		}
		
		$rowMenu->menutype	=	$menutype->menutype;
		$rowMenu->name		=	$textObj->item_title;
		$rowMenu->alias		=	$textObj->item_alias;
		$rowMenu->ordering	=	$menutype->ordering + 1;
		$rowMenu->type		=	'component';
		$rowMenu->published	=	$textObj->item_state;
		$rowMenu->parent	=	$textObj->batchMenus[$i]['location'];
		$rowMenu->componentid	=	20;
		$rowMenu->access	=	$textObj->item_access;		
		
		// Inherited Params
		if ( $textObj->batchMenus[$i]['params'] && $textObj->batchMenus[$i]['inherit'] ) {
			$inherit	=&	JTable::getInstance( 'menu' );
			$inherit->load( $textObj->batchMenus[$i]['inherit'] );
		}
		
	switch ( $textObj->batchMenus[$i]['layout'] ) {
		case '2':
			$rowMenu->link		=	'index.php?option=com_content&view=category&id='.$textObj->item_id;
			if ( @$inherit ) {
				$rowMenu->params	=	$inherit->params;
			} else {
			$rowMenu->params	=
	'display_num=10
	show_headings=1
	show_date=0
	date_format=
	filter=1
	filter_type=title
	orderby_sec=
	show_pagination=1
	show_pagination_limit=1
	show_feed_link=1
	show_noauth=
	show_title=
	link_titles=
	show_intro=
	show_section=
	link_section=
	show_category=
	link_category=
	show_author=
	show_create_date=
	show_modify_date=
	show_item_navigation=
	show_readmore=
	show_vote=
	show_icons=
	show_pdf_icon=
	show_print_icon=
	show_email_icon=
	show_hits=
	feed_summary=
	page_title=
	show_page_title=1
	pageclass_sfx=
	menu_image=-1
	secure=0	
	';
			}
			break;
		case '1':
			$rowMenu->link		=	'index.php?option=com_content&view=category&layout=blog&id='.$textObj->item_id;
			if ( @$inherit ) {
				$rowMenu->params	=	$inherit->params;
			} else {
			$rowMenu->params	=
	'show_description=1
	show_description_image=0
	num_leading_articles=1
	num_intro_articles=4
	num_columns=2
	num_links=4
	orderby_pri=
	orderby_sec=
	multi_column_order=0
	show_pagination=2
	show_pagination_results=1
	show_feed_link=1
	show_noauth=
	show_title=
	link_titles=
	show_intro=
	show_section=
	link_section=
	show_category=
	link_category=
	show_author=
	show_create_date=
	show_modify_date=
	show_item_navigation=
	show_readmore=
	show_vote=
	show_icons=
	show_pdf_icon=
	show_print_icon=
	show_email_icon=
	show_hits=
	feed_summary=
	page_title=
	show_page_title=1
	pageclass_sfx=
	menu_image=-1
	secure=0	
	';
			}
			break;
		default:
			$rowMenu->link		=	'index.php?option=com_content&view=article&id='.$textObj->item_id;
			if ( @$inherit ) {
				$rowMenu->params	=	$inherit->params;
			} else {
			$rowMenu->params	=
	'show_noauth=
	show_title=
	link_titles=
	show_intro=
	show_section=
	link_section=
	show_category=
	link_category=
	show_author=
	show_create_date=
	show_modify_date=
	show_item_navigation=
	show_readmore=
	show_vote=
	show_icons=
	show_pdf_icon=
	show_print_icon=
	show_email_icon=
	show_hits=
	feed_summary=
	page_title=
	show_page_title=1
	pageclass_sfx=
	menu_image=-1
	secure=0	
	';
			}
			break;
	}
		
		$rowMenu->store();
	}
}

}
?>