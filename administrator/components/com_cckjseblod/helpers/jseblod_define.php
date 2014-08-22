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

define( "_LINK_CCKJSEBLOD",							"index.php?option=com_cckjseblod" );
define( "_LINK_CCKJSEBLOD_CONFIGURATION",			"index.php?option=com_cckjseblod&controller=configuration" );
define( "_LINK_CCKJSEBLOD_INTERFACE",				"index.php?option=com_cckjseblod&controller=interface" );
define( "_LINK_CCKJSEBLOD_ITEMS",					"index.php?option=com_cckjseblod&controller=items" );
define( "_LINK_CCKJSEBLOD_ITEMS_CATEGORIES",		"index.php?option=com_cckjseblod&controller=items_categories" );
define( "_LINK_CCKJSEBLOD_PACKS",					"index.php?option=com_cckjseblod&controller=packs" );
define( "_LINK_CCKJSEBLOD_SEARCHS",					"index.php?option=com_cckjseblod&controller=searchs" );
define( "_LINK_CCKJSEBLOD_SEARCHS_CATEGORIES",		"index.php?option=com_cckjseblod&controller=searchs_categories" );
define( "_LINK_CCKJSEBLOD_TEMPLATES",				"index.php?option=com_cckjseblod&controller=templates" );
define( "_LINK_CCKJSEBLOD_TEMPLATES_CATEGORIES",	"index.php?option=com_cckjseblod&controller=templates_categories" );
define( "_LINK_CCKJSEBLOD_TEMPLATES_VIEWS",			"index.php?option=com_cckjseblod&controller=templates_views" );
define( "_LINK_CCKJSEBLOD_TYPES",					"index.php?option=com_cckjseblod&controller=types" );
define( "_LINK_CCKJSEBLOD_TYPES_CATEGORIES",		"index.php?option=com_cckjseblod&controller=types_categories" );

$root	=	JURI::root( true );
define( "_PATH_ROOT",			$root );
define( "_PATH_CALENDAR",		'/media/jseblod/calendar/' );
define( "_PATH_FORMVALIDATOR",	'/media/jseblod/formvalidator/' );
define( "_PATH_MOORAINBOW",		'/media/jseblod/moorainbow/' );
define( "_PATH_MULTISELECT",	'/media/jseblod/multiselect/' );

define( '_IMG_32_JSEBLOD', 	'<img src="components/com_cckjseblod/assets/images/jseblod/icon-32-jseblod.png" border="0" alt=" " />' );

define( '_IMG_TEMPLATES',	'<img src="components/com_cckjseblod/assets/images/submenu/icon-16-templates.png" border="0" alt=" " width="16" height="16" />' );
define( '_IMG_TYPES',		'<img src="components/com_cckjseblod/assets/images/submenu/icon-16-types.png" border="0" alt=" " width="16" height="16" />' );
define( '_IMG_ITEMS', 		'<img src="components/com_cckjseblod/assets/images/submenu/icon-16-items.png" border="0" alt=" " width="16" height="16" />' );
define( '_IMG_PACKS', 		'<img src="components/com_cckjseblod/assets/images/submenu/icon-16-packs.png" border="0" alt=" " width="16" height="16" />' );
define( '_IMG_SEARCHS',		'<img src="components/com_cckjseblod/assets/images/submenu/icon-16-searchs.png" border="0" alt=" " width="16" height="16" />' );
define( '_IMG_ADMINITEMS', 	'<img src="components/com_cckjseblod/assets/images/list/icon-16-adminitems.png" border="0" alt=" " width="18" height="18" />' );
define( '_IMG_SITEITEMS', 	'<img src="components/com_cckjseblod/assets/images/list/icon-16-siteitems.png" border="0" alt=" " width="18" height="18" />' );
define( '_IMG_EMAILITEMS', 	'<img src="components/com_cckjseblod/assets/images/list/icon-16-emailitems.png" border="0" alt=" " width="18" height="18" />' );
define( '_IMG_CONTENTITEMS', '<img src="components/com_cckjseblod/assets/images/list/icon-16-contentitems.png" border="0" alt=" " width="18" height="18" />' );
define( '_IMG_FILTERITEMS', '<img src="components/com_cckjseblod/assets/images/list/icon-16-filteritems.png" border="0" alt=" " width="18" height="18" />' );
define( '_IMG_LISTITEMS', 	'<img src="components/com_cckjseblod/assets/images/list/icon-16-listitems.png" border="0" alt=" " width="18" height="18" />' );
define( '_IMG_SEARCHITEMS', '<img src="components/com_cckjseblod/assets/images/list/icon-16-searchitems.png" border="0" alt=" " width="18" height="18" />' );

define( '_IMG_TEMPLATES_24',	'<img src="components/com_cckjseblod/assets/images/list/icon-24-templates.png" border="0" alt=" " width="24" height="24" />' );
define( '_IMG_TYPES_24',		'<img src="components/com_cckjseblod/assets/images/list/icon-24-types.png" border="0" alt=" " width="24" height="24" />' );
define( '_IMG_ITEMS_24',		'<img src="components/com_cckjseblod/assets/images/list/icon-24-items.png" border="0" alt=" " width="24" height="24" />' );
define( '_IMG_SEARCHS_24',		'<img src="components/com_cckjseblod/assets/images/list/icon-24-searchs.png" border="0" alt=" " width="24" height="24" />' );

define( '_IMG_CCKVIEW',		'<img src="components/com_cckjseblod/assets/images/list/icon-18-cckview.png" border="0" alt=" " />' );
define( '_IMG_VIEW',		'<img src="components/com_cckjseblod/assets/images/list/icon-20-assignments.png" border="0" alt=" " />' );

define( '_IMG_CONFIG', 		'<img src="components/com_cckjseblod/assets/images/submenu/icon-16-configuration.png" border="0" alt=" " width="16" height="16" />' );

define( '_IMG_ADD', 		'<img src="components/com_cckjseblod/assets/images/list/icon-16-add.gif" border="0" alt=" " />' );
define( '_IMG_BALLOON_RIGHT', '<img src="components/com_cckjseblod/assets/images/list/icon-16-balloon-right.png" border="0" alt=" " />' );
define( '_IMG_BALLOON_LEFT', '<img src="components/com_cckjseblod/assets/images/list/icon-16-balloon-left.png" border="0" alt=" " />' );
define( '_IMG_CATEGORY',	'<img src="components/com_cckjseblod/assets/images/list/icon-16-category.png" border="0" alt=" " />' );
define( '_IMG_CATEGORIES',	'<img src="components/com_cckjseblod/assets/images/list/icon-16-categories.png" border="0" alt=" " />' );
define( '_IMG_COLOR',		'<img src="'._PATH_ROOT._PATH_MOORAINBOW.'images/color.png" id="colorRainbow" border="0" alt=" " width="16" height="16" />' );
define( '_IMG_COLORCHAR',	'<img src="'._PATH_ROOT._PATH_MOORAINBOW.'images/color.png" id="colorcharRainbow" border="0" alt=" " width="16" height="16" />' );
define( '_IMG_COLOR_TEXT',	'<img src="'._PATH_ROOT._PATH_MOORAINBOW.'images/color.png" id="contentRainbow" border="0" alt=" " width="16" height="16" />' );
define( '_IMG_COLOR_BACK',	'<img src="'._PATH_ROOT._PATH_MOORAINBOW.'images/color.png" id="locationRainbow" border="0" alt=" " width="16" height="16" />' );
define( '_IMG_COLOR_GRID',	'<img src="'._PATH_ROOT._PATH_MOORAINBOW.'images/color.png" id="extraRainbow" border="0" alt=" " width="16" height="16" />' );
define( '_IMG_DEFAULT',		'<img src="components/com_cckjseblod/assets/images/list/icon-16-default.png" border="0" alt=" " />' );
define( '_IMG_DEL', 		'<img src="components/com_cckjseblod/assets/images/list/icon-16-del.gif" border="0" alt=" " />' );
define( '_IMG_EDIT', 		'<img src="components/com_cckjseblod/assets/images/list/icon-18-edit.png" border="0" alt=" " />' );
define( '_IMG_EXPORT', 		'<img src="components/com_cckjseblod/assets/images/list/icon-16-export.png" border="0" alt=" " />' );
define( '_IMG_EXPORT_ADD', 	'<img src="components/com_cckjseblod/assets/images/list/icon-16-export-add.png" border="0" alt=" " />' );
define( '_IMG_EXTERNAL', 	'<img src="components/com_cckjseblod/assets/images/list/icon-10-external.png" border="0" alt=" " />' );
define( '_IMG_IMPORT', 		'<img src="components/com_cckjseblod/assets/images/list/icon-16-import.png" border="0" alt=" " />' );
define( '_IMG_INTERNAL', 	'<img src="components/com_cckjseblod/assets/images/list/icon-10-internal.png" border="0" alt=" " />' );
define( '_IMG_MENU',		'<img src="components/com_cckjseblod/assets/images/list/icon-16-menu.png" border="0" alt=" " />' );
define( '_IMG_QUICK_EDIT',	'<img src="components/com_cckjseblod/assets/images/list/icon-16-quick-edit.png" border="0" alt=" " />' );
define( '_IMG_PARENT', 		'<img src="components/com_cckjseblod/assets/images/list/icon-16-parent.png" border="0" alt=" " />' );
define( '_IMG_SOURCES',		'<img src="components/com_cckjseblod/assets/images/list/icon-18-html.png" border="0" alt=" " />' );
define( '_IMG_SOURCES_A',	'<img src="components/com_cckjseblod/assets/images/list/icon-16-auto.png" border="0" alt=" " height="36" />' );
define( '_IMG_SOURCES_C',	'<img src="components/com_cckjseblod/assets/images/list/icon-16-custom.png" border="0" alt=" " height="36" />' );
define( '_IMG_TRASH', 		'<img src="components/com_cckjseblod/assets/images/list/icon-16-trash.png" border="0" alt=" " />' );
define( '_IMG_WARNING',		'<img src="components/com_cckjseblod/assets/images/list/icon-16-warning.png" border="0" alt=" " />');
define( '_IMG_EXT', 		'<img src="components/com_cckjseblod/assets/images/list/icon-10-xtd.png" border="0" alt=" " />' );
define( '_IMG_URL',			'<img src="components/com_cckjseblod/assets/images/list/icon-16-url.png" border="0" alt=" " />' );

define( '_IMG_XML',			'<img src="components/com_cckjseblod/assets/images/list/icon-16-xml.png" border="0" alt=" " />' );
define( '_IMG_PHP',			'<img src="components/com_cckjseblod/assets/images/list/icon-16-php.png" border="0" alt=" " />' );
define( '_IMG_CSS',			'<img src="components/com_cckjseblod/assets/images/list/icon-16-css.png" border="0" alt=" " />' );
define( '_IMG_JS',			'<img src="components/com_cckjseblod/assets/images/list/icon-16-js.png" border="0" alt=" " />' );
define( '_IMG_INI',			'<img src="components/com_cckjseblod/assets/images/list/icon-16-ini.png" border="0" alt=" " />' );

/**
 * NBSP
 **/
 
define( '_NBSP', str_repeat( '&nbsp;', 3 ) );
define( '_NBSP2', str_repeat( '&nbsp;', 5 ) );

/**
 * CCK Config
 **/
 

$config 	=&	CCK::CORE_getConfig();
if ( $config ) {
	define( "_VIEW_ACCESS",						$config->view_access_level );
	define( "_EDIT_ACCESS",						$config->edit_access_level );
	define( "_OPENING",							$config->opening );
	define( "_CLOSING",							$config->closing );
	define( "_MODAL_WIDTH",						$config->modal_width );
	define( "_MODAL_HEIGHT",					$config->modal_height );	
	define( "_TEMPLATE_DEFAULT_CAT",			$config->template_default_category );
	define( "_TYPE_DEFAULT_CAT",				$config->type_default_category );
	define( "_ITEM_DEFAULT_CAT",				$config->item_default_category );
	define( "_SEARCH_DEFAULT_CAT",				$config->search_default_category );
	define( "_TEMPLATE_HIDDEN",					$config->template_hidden );
	define( "_TEMPLATE_DELETE_MODE",			$config->template_delete_mode );
	define( "_TEMPLATE_CATEGORY_DELETE_MODE",	$config->template_category_delete_mode );
	define( "_TYPE_DELETE_MODE", 				$config->type_delete_mode );
	define( "_TYPE_CATEGORY_DELETE_MODE",		$config->type_category_delete_mode );
	define( "_ITEM_CATEGORY_DELETE_MODE",		$config->item_category_delete_mode );
	define( "_SEARCH_DELETE_MODE",				$config->search_delete_mode );
	define( "_SEARCH_CATEGORY_DELETE_MODE",		$config->search_category_delete_mode );
	define( "_ITEM_HIDDEN_TEMPLATE",			$config->template_hidden );
	define( "_JTEXT_ON_LABEL",					$config->jtext_on_label );
	define( "_ADMINFORM_ONCLICK",				$config->adminform_tips );
	define( "_IMPORT_DEFAULT_MODE",				$config->import_default_mode );
	define( "_EXPORT_EMPTY_PACK",				$config->export_empty_pack );
	define( "_WYSIWYG_EDITOR",					$config->wysiwyg_editor );
	define( "_DEFAULT_SECTION",					$config->jseblod_section );
	define( "_VALIDATION_ALERT",				$config->validation_alert);
	define( "_CEK_COLUMN",						$config->cek_column);
	define( "_CEK_COLUMN_ARTICLE",				$config->cek_column_article);
	define( "_CEK_COLUMN_CATEGORY",				$config->cek_column_category);
	define( "_CEK_COLUMN_USER",					$config->cek_column_user);
	define( "_BOOL_PUBLISH",					$config->bool_publish);
	define( "_BOOL_CHECK",						$config->bool_check);
	define( "_RESTRICTION_TYPE",				$config->restriction_type);
	define( "_RESTRICTION_FIELD",				$config->restriction_field);
	define( "_RESTRICTION_CONTENT",				$config->restriction_content);
} else {
	define( "_VIEW_ACCESS",				23 );
	define( "_EDIT_ACCESS",				24 );
	define( "_OPENING",					'::' );
	define( "_CLOSING",					'::' );
	define( "_MODAL_WIDTH",				900 );
	define( "_MODAL_HEIGHT",			540 );
	define( "_WYSIWYG_EDITOR",			'default' );
}

//TODO:: HREF | IMG | JROUTE >> USE "CLIENT->" !!

?>