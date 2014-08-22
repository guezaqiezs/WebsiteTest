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

/**
 * Configuration	Table Class
 **/
class TableConfiguration extends JTable
{
	/**
	 * Vars
	 **/
	var $id = null;								//Primary Key
	
	var $jseblod_section = null;				//Int
	var $jseblod_category_auto = null;			//Int
	var $jseblod_category_default = null;		//Int
  	var $jseblod_category_default_reg = null;	//Int
	var $view_access_level = null;				//Int
	var $edit_access_level = null;				//Int
	var $article_creation_mode = null;			//Int
	var $article_edition_mode = null;			//Int
	var $article_edition2_mode = null;			//Int
	var $category_creation_mode = null;			//Int
	var $category_edition_mode = null;			//Int
	var $category_edition2_mode = null;			//Int
	var $user_creation_mode = null;				//Int
	var $user_edition_mode = null;				//Int
	var $user_edition2_mode = null;				//Int
	var $categories_fullscreen = null;			//Int
	var $modal_width = null;					//Int
	var $modal_height = null;					//Int
	var $opening = null;						//Varchar (50)
	var $closing = null;						//Varchar (50)
	var $template_default_category = null;		//Tinyint
	var $type_default_category = null;			//Tinyint
	var $item_default_category = null;			//Tinyint
	var $template_delete_mode = null;			//Int
	var $template_category_delete_mode = null;	//Int
	var $type_delete_mode = null;				//Int
	var $type_category_delete_mode = null;		//Int
	var $item_delete_mode = null;				//Int
	var $item_category_delete_mode = null;		//Int
	var $template_hidden = null;				//Text
	var $jtext_on_label = null;					//Tinyint
	var $adminform_tips = null;					//Tinyint
	var $siteform_tips = null;					//Tinyint
	var $notemplate_display = null;				//Tinyint
	var $import_default_mode = null;			//Tinyint
	var $export_empty_pack = null;				//Tinyint
	var $wysiwyg_editor = null;					//Tinyint
	var $login_enable = null;					//Tinyint
	var $login_typeid = null;					//Tinyint
	var $login_templateid = null;				//Tinyint
	var $login_itemid = null;					//Tinyint
	var $article_typeid = null;					//Tinyint
	var $article_templateid = null;				//Tinyint
	var $article_itemid = null;					//Tinyint
	var $category_typeid = null;				//Tinyint
	var $category_templateid = null;			//Tinyint
	var $category_itemid = null;				//Tinyint
	var $user_typeid = null;					//Tinyint
	var $user_templateid = null;				//Tinyint
	var $user_itemid = null;					//Tinyint
	var $validation_alert = null;				//Tinyint
	var $system_component = null;				//Tinyint
	var $system_modules = null;					//Tinyint
	var $icon_edit = null;						//Tinyint
	var $icon_pdf = null;						//Tinyint
	var $icon_print = null;						//Tinyint
	var $icon_email = null;						//Tinyint
	var $cek_column = null;						//Tinyint
	var $cek_column_article = null;				//Tinyint
	var $cek_column_category = null;			//Tinyint
	var $cek_column_user = null;				//Tinyint
	var $bool = null;							//Tinyint
	var $options = null;						//Text
	var $login_bool = null;						//Tinyint
	var $article_bool = null;					//Tinyint
	var $category_bool = null;					//Tinyint
	var $user_bool = null;						//Tinyint
	var $bool_publish = null;					//Tinyint
	var $bool_hide = null;						//Tinyint
	var $bool_check = null;						//Tinyint
	var $search_delete_mode;					//Tinyint
	var $search_category_delete_mode;			//Tinyint
	var $search_default_category;				//Tinyint
	var $restriction_type;						//Tinyint
	var $restriction_field;						//Tinyint
	var $restriction_content;					//Tinyint
	
	var $checked_out = null;					//Int (UNSIGNED)
	var $checked_out_time = null;				//Datetime
	
	/**
	 * Constructor
	 **/
	function TableConfiguration( & $db ) {
		parent::__construct( '#__jseblod_cck_configuration', 'id', $db );
	}

}
?>