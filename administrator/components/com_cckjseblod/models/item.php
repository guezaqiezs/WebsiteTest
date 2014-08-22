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

jimport( 'joomla.application.component.model' );

/**
 * Content Item		Model Class
 **/
class CCKjSeblodModelItem extends JModel
{
	/**
	var $_id					= null;
	var $_data					= null;
	var $_types	= null;
	var $_typeid	= null;
	var $_typename	= null;
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
		
		$array = JRequest::getVar( 'cid',  0, '', 'array' );
		$this->setValues( (int)$array[0] );
	}

	/**
	 * Set Values
	 **/
	function setValues( $id )
	{
		// Set Values
		$this->_id		= $id;
		$this->_data	= null;
		$this->_types	= null;
		$this->_typeid		= null;
		$this->_typename	= null;
		$this->_typetitle	= null;
	}

	/**
	 * Get Data from Database
	 **/
	function &getData()
	{
		if (empty( $this->_data )) {
			$row =& $this->getTable('items');
			
			if ($this->_id) {
				$row->load($this->_id);
				if ( ! $row->checked_out ) {
					$user =& JFactory::getUser();
					// Checkout!
					$row->checkout( $user->get('id') );
				}
				$this->_data =& $row;
				$this->_data->typename = $this->_getTypename( $this->_data->type );
				$this->_data->typetitle = $this->_getTypeTitle( $this->_data->type );
				$this->_data->categorystate = $this->_getCategoryState( $this->_data->category );
				if ( $this->_data->typename == 'joomla_content' || $this->_data->typename == 'select_dynamic' || $this->_data->typename == 'alias'
					|| $this->_data->typename == 'alias_custom' || $this->_data->typename == 'ecommerce_cart' || $this->_data->typename == 'field_x' || $this->_data->typename == 'joomla_user' ) {
					if ( $this->_data->extended ) {
						$this->_data->extendedTitle =	$this->_getExtendedTitle( $this->_data->extended, 'items' );
						$this->_data->extendedId	=	$this->_getExtendedId( $this->_data->extended, 'items' );
					}
				} else if ( $this->_data->typename == 'content_type' ) {
					if ( $this->_data->extended ) {
						$this->_data->extendedTitle =	$this->_getExtendedTitle( $this->_data->extended, 'types' );
						$this->_data->extendedId	=	$this->_getExtendedId( $this->_data->extended, 'types' );
					}
				} else if ( $this->_data->typename == 'ecommerce_cart_button' && $this->_data->bool3 > 0 ) {
					if ( $this->_data->options ) {
						if ( strpos( $this->_data->options, ',' ) !== false ) {
							$opts							=	explode( ',', $this->_data->options );
							$this->_data->optionsTitle		=	$this->_getExtendedTitle( $opts[0], 'items' );
							$this->_data->optionsId			=	$this->_getExtendedId( $opts[0], 'items' );
							$this->_data->optionsTitle_2nd	=	$this->_getExtendedTitle( $opts[1], 'items' );
							$this->_data->optionsId_2nd		=	$this->_getExtendedId( $opts[1], 'items' );
						} else {
							$this->_data->optionsTitle	=	$this->_getExtendedTitle( $this->_data->options, 'items' );
							$this->_data->optionsId		=	$this->_getExtendedId( $this->_data->options, 'items' );
						}
					}
					if ( $this->_data->options2 ) {
						$opts	=	null;
						if ( strpos( $this->_data->options2, ',' ) !== false ) {
							$opts							=	explode( ',', $this->_data->options2 );
							$this->_data->options2Title		=	$this->_getExtendedTitle( $opts[0], 'items' );
							$this->_data->options2Id		=	$this->_getExtendedId( $opts[0], 'items' );
							$this->_data->options2Title_2nd	=	$this->_getExtendedTitle( $opts[1], 'items' );
							$this->_data->options2Id_2nd	=	$this->_getExtendedId( $opts[1], 'items' );
						} else {
							$this->_data->options2Title		=	$this->_getExtendedTitle( $this->_data->options2, 'items' );
							$this->_data->options2Id		=	$this->_getExtendedId( $this->_data->options2, 'items' );
						}
					}
					if ( $this->_data->defaultvalue ) {
						$this->_data->defaultvalueTitle =	$this->_getExtendedTitle( $this->_data->defaultvalue, 'items' );
						$this->_data->defaultvalueId	=	$this->_getExtendedId( $this->_data->defaultvalue, 'items' );
					}
					if ( $this->_data->style ) {
						$this->_data->styleTitle =	$this->_getTemplateTitle( $this->_data->style );
					}
				} else {}
			}			
		}
		
		return $this->_data;
	}

	/**
	 * Get Data from Database
	 **/
	function &getType( $typeId )
	{
		$where	=	' WHERE s.id = '.$typeId;
      		
  		$query	= 'SELECT s.*'
				. ' FROM #__jseblod_cck_items_types AS s'
				. $where
				;
    	$this->_db->setQuery( $query );
  		$this->_type	=	$this->_db->loadObject();
		
		return $this->_type;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getTypeid( $typename )
	{
		$where = ' WHERE s.name = "'.$typename.'"';
      		
  		$query = ' SELECT s.id'
  			. ' FROM #__jseblod_cck_items_types AS s'
  			. $where
  		;
    	$this->_db->setQuery( $query );
  		$this->_typeid = $this->_db->loadResult();
		
		return $this->_typeid;
	}

	/**
	 * Get Data from Database
	 **/
	function _getTypename( $typeid )
	{
		if (empty( $this->_types ))
		{
      $where = ' WHERE s.id = '.$typeid;
      		
  		$query = ' SELECT s.name'
  			. ' FROM #__jseblod_cck_items_types AS s'
  			. $where
  		;
    	$this->_db->setQuery( $query );
  		$this->_typename = $this->_db->loadResult();
		}
		return $this->_typename;
	}

		/**
	 * Get Data from Database
	 **/
	function _getTypeTitle( $typeid )
	{
		if (empty( $this->_types ))
		{
		$where = ' WHERE s.id = '.$typeid;
			
  		$query = ' SELECT s.title'
  			. ' FROM #__jseblod_cck_items_types AS s'
  			. $where
  		;
    	$this->_db->setQuery( $query );
  		$this->_typetitle = $this->_db->loadResult();
		}
		return $this->_typetitle;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getTemplateTitle( $templateid )
	{
		$where = ' WHERE s.id = '.$templateid;
			
  		$query = ' SELECT s.title'
  			. ' FROM #__jseblod_cck_templates AS s'
  			. $where
  			;
    	$this->_db->setQuery( $query );
  		$this->_templatetitle = $this->_db->loadResult();
		
		return $this->_templatetitle;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getExtendedTitle( $extended, $extendedType )
	{
		$where = ' WHERE s.name = "'.$extended.'"';
		
  		$query = ' SELECT s.title'
  			. ' FROM #__jseblod_cck_'.$extendedType.' AS s'
  			. $where
  		;
    	$this->_db->setQuery( $query );
  		$this->_extendedTitle = $this->_db->loadResult();
		
		return $this->_extendedTitle;
	}
	
	function _getExtendedId( $extended, $extendedType )
	{
		$where = ' WHERE s.name = "'.$extended.'"';
		
  		$query = ' SELECT s.id'
  			. ' FROM #__jseblod_cck_'.$extendedType.' AS s'
  			. $where
  		;
    	$this->_db->setQuery( $query );
  		$this->_extendedId = $this->_db->loadResult();
		
		return $this->_extendedId;
	}
	
	function _getExtendedType( $extendedId )
	{
  		$query	= 'SELECT cc.name'
  				. ' FROM #__jseblod_cck_items AS s'
  				. ' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
				. ' WHERE s.id ='.(int)$extendedId;
	  			;
    	$this->_db->setQuery( $query );
  		$extendedType	=	$this->_db->loadResult();
		
		return $extendedType;
	}
	
	function _getExtendedObj( $extendedId )
	{
  		$query	= 'SELECT s.*'
  				. ' FROM #__jseblod_cck_items AS s'
				. ' WHERE s.id ='.(int)$extendedId;
	  			;
    	$this->_db->setQuery( $query );
  		$extendedObj	=	$this->_db->loadObject();
		
		return $extendedObj;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getCategoryState( $categoryId )
	{
		$where = ' WHERE s.id = '.$categoryId;
      		
  		$query = ' SELECT s.published'
  			. ' FROM #__jseblod_cck_items_categories AS s'
  			. $where
  		;
    	$this->_db->setQuery( $query );
  		$this->_categorystate = $this->_db->loadResult();
		
		return $this->_categorystate;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _setSubstitute( $name, $substitute )
	{
		if ( $name ) {
			$where = ' WHERE s.extended = "'.$name.'"';
				
			$query	= ' UPDATE #__jseblod_cck_items AS s'
					. ' SET s.substitute = '.(int)$substitute
					. $where
					;
			$this->_db->setQuery( $query );
			$this->_db->query();
		}
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getSubstitute( $name )
	{
		$substitute	=	0;
		if ( $name ) {
			$where	= ' WHERE s.name = "'.$name.'"';
				
			$query	= ' SELECT s.substitute'
					. ' FROM #__jseblod_cck_items AS s'
					. $where
					;
			$this->_db->setQuery( $query );
			$substitute	=	$this->_db->loadResult();
		}
		
		return $substitute;
	}
	
	/**
	 * Store Record(s)
	 **/
	function store()
	{
		global	$mainframe;
		$dbpref	=	$mainframe->getCfg('dbprefix');
		$row 	=& $this->getTable( 'items' );
		$data 	= JRequest::get( 'post' );
				
		/**
		 * Extra POST Pre-Store
		 **/
		$data['title']			=	trim( $data['title'] );
		$data['description']	=	( $data['description_updated'] == 1 ) ? JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW )
		: ( ( ! $data['id'] ) ? HelperjSeblod_Helper::getWysiwygContent( 'description', 'items', $data['cid'][0] ) : HelperjSeblod_Helper::getWysiwygContent( 'description', 'items', $data['id'] ) );
		
		$data['codebefore']	=	JRequest::getVar( 'codebefore', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$data['codeafter']	=	JRequest::getVar( 'codeafter', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		if ( isset( $data['type'] ) && $data['type'] ) {
			$itemType = $data['type'];
			$data['type'] = $this->_getTypeid( $data['type'] );
		}
		if ( $itemType == 'alias_custom' ) {
			if ( $data['boolxtd'] == 2 ) {
				// Update !
				$extended	=	$this->_getExtendedObj( $data['extended_id'] );
				
				$except		=	array( 'id', 'title', 'name', 'category', 'type', 'description', 'extended', 'elemxtd', 'stylextd', 'boolxtd', 'checked_out', 'checked_out_time' );
				foreach( $extended as $key => $value ) {
					if ( array_search( $key, $except ) === false ) {
						$data[$key]	=	$value;
					}
				}
				$data['boolxtd']	=	1;
			} else if ( $data['boolxtd'] == 1 ) {
				$itemType			=	( $data['stylextd'] ) ? $data['stylextd'] : $this->_getExtendedType( $data['extended_id'] );
				$extended			=	$this->_getExtendedObj( $data['extended_id'] );
				$data['content']	=	$extended->content;
			} else {
				$extended				=	$this->_getExtendedObj( $data['extended_id'] );
				$data['indexedxtd']	=	$extended->indexedxtd;
				if ( $data['indexedxtd'] ) {
					$data['bool4']	=	$extended->bool4;
				}
			}
			$data['elemxtd']	=	'item';
		}
		
		// Specific Ecommerce Cart
		if ( $itemType == 'ecommerce_cart' ) {
			if ( $data['boolxtd'] == 1 ) {
				$itemType			=	( $data['stylextd'] ) ? $data['stylextd'] : $this->_getExtendedType( $data['extended_id'] );
				$extended			=	$this->_getExtendedObj( $data['extended_id'] );
			}
			if ( $data['bool3'] == 1 ) {
				$data['extended']	=	'quantity';
			} else {
				if ( $data['extended'] ) {
					if ( ( $key = strpos( $data['extended'], '[' ) ) !== false ) {
						$refer	=	substr( $data['extended'], $key + 1, -1 );
					} else if ( ( $key = strpos( $data['extended'], '(' ) ) !== false ) {
						$refer	=	substr( $data['extended'], $key + 1, -1 );
					} else {
						$refer	=	$data['extended'];
					}
				}
				// Update DB
				if ( $refer ) {
					$query	=	'SHOW COLUMNS FROM #__jseblod_cck_ecommerce_cart_product';
					$this->_db->setQuery( $query );
					$columns	=	$this->_db->loadResultArray();
					if ( array_search( $refer, $columns ) === false ) {
						$query	=	'ALTER TABLE `#__jseblod_cck_ecommerce_cart_product` ADD `'.$refer.'` VARCHAR( 50 ) NOT NULL';
						$this->_db->setQuery( $query );
						if ( ! $this->_db->query() ) {
							return false;
						}
					}
					$query	=	'SHOW COLUMNS FROM #__jseblod_cck_ecommerce_cart_product_temp';
					$this->_db->setQuery( $query );
					$columns	=	$this->_db->loadResultArray();
					if ( array_search( $refer, $columns ) === false ) {
						$query	=	'ALTER TABLE `#__jseblod_cck_ecommerce_cart_product_temp` ADD `'.$refer.'` VARCHAR( 50 ) NOT NULL';
						$this->_db->setQuery( $query );
						if ( ! $this->_db->query() ) {
							return false;
						}
					}
				}
			}
		}
		
		// Specific Ecommerce Cart Button
		if ( $itemType == 'ecommerce_cart_button' ) {
			if ( $data['options'] ) {
				if ( $data['options_2nd'] ) {
					$data['options']	.=	','.$data['options_2nd'];
				}
			}
			if ( $data['options2'] ) {
				if ( $data['options2_2nd'] ) {
					$data['options2']	.=	','.$data['options2_2nd'];
				}
			}
			if ( isset( $data['selected_extra'] ) ) {
				$extra	=	count( $data['selected_extra'] );
				if ( $extra > 1 ) {
					$extra	=	implode( ',', $data['selected_extra'] );
					$data['extra'] = $extra;
				} else {
					$data['extra'] = $data['selected_extra'][0];
				}
			} else {
				$data['extra'] = '';
			}
		}
		
		// Item Type ECOMMERCE PRICE
		if ( $itemType == 'ecommerce_price' ) {
			if ( isset( $data['selected_options'] ) ) {
				$extra	=	count( $data['selected_options'] );
				if ( $extra > 1 ) {
					$extra	=	implode( ',', $data['selected_options'] );
					$data['options'] = $extra;
				} else {
					$data['options'] = $data['selected_options'][0];
				}
			} else {
				$data['options'] = '';
			}	
		}
		
		if ( $itemType == 'search_multiple' ) {
			if ( $data['boolxtd'] == 1 ) {
				$itemType	=	$data['stylextd'];
			}
		}
				
		// Specific Substitute Process
		if ( $itemType == 'text' || $itemType == 'hidden' || $itemType == 'checkbox' || $itemType == 'radio' || $itemType == 'select_simple' || $itemType == 'select_multiple' || $itemType == 'select_numeric' || $itemType == 'external_article' || $itemType == 'save' ) {
			$this->_setSubstitute( $data['name'], $data['substitute'] );
		} else {
			$data['substitute']	=	$this->_getSubstitute( $data['extended'] );
		}

		// Indexed
		if ( $data['indexed'] ) {
			$query	=	'CREATE TABLE IF NOT EXISTS `#__jseblod_cck_extra_index_'.$data['name'].'` ('
					.	' `id` int(11) NOT NULL,'
					.	' `indexid` varchar(50) NOT NULL,'
					.	' PRIMARY KEY (`id`,`indexid`),'
					.	' KEY `id` (`id`),'
					.	' KEY `indexid` (`indexid`)'
					.	' ) ENGINE=MyISAM DEFAULT CHARSET=utf8;'
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				//return false;
			}
		}

		// Item Type TEXT
		if ( $itemType == 'text' ) {
			if ( $data['indexedkey'] ) {
				$query	=	'CREATE TABLE IF NOT EXISTS `#__jseblod_cck_extra_index_key_'.$data['name'].'` ('
						.	' `id` int(11) NOT NULL,'
						.	' `keyid` varchar(50) NOT NULL,'
						. 	' PRIMARY KEY (`id`),'
						.	' KEY `keyid` (`keyid`)'
						.	' ) ENGINE=MyISAM DEFAULT CHARSET=utf8;'
						;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					//return false;
				}
			}			
			// Incrementer
			if ( $data['bool8'] ) {
				$data['options2']	=	$data['options2_1'].'||'.$data['options2_2'];
			}
		}
		
		// Item Type EXTERNAL ARTICLE
		if ( $itemType == 'external_article' ) {
			if ( isset( $data['content'] ) ) {
				$content = $data['content'];
				if ( is_array( $content ) ) {
					$content = implode( ',', $content );
					$data['content'] = $content;
				}
			} else {
				$data['content'] = '1';
			}
			$data['options2']	=	$data['options2_content'] .'||'. $data['options2_list'] .'||'. $data['options2_cart'];
	
			if ( isset( $data['selected_categories'] ) ) {
				$nCat = count( $data['selected_categories'] );
				if ( $nCat > 1 ) {
					$categories = implode( ',', $data['selected_categories'] );
					$data['options'] = $categories;
				} else {
					$data['options'] = $data['selected_categories'][0];
				}
			} else {
				$data['options'] = '';
			}
			if ( $data['bool4'] ) {
				// Update DB
				// ...
			} else {
				$data['indexedxtd']	=	'';
			}
		}
		
		// Item JOOMLA CONTENT
		if ( $itemType == 'joomla_content' ) {
			$data['elemxtd']	=	'item';
		}
		
		// Item Type JOOMLA MODULE
		if ( $itemType == 'joomla_module' ) {
			//$data['display'] = -1;
			//$data['light'] = 0;
		}
		
		// Item Type QUERY URL || QUERY USER
		if ( $itemType == 'query_url' || $itemType == 'query_user' ) {
			$cleanTable			=	str_replace( $dbpref, '#__', $data['db_tables'] );
			$data['options'] 	=	$cleanTable.'||'.$data['db_fields'].'||'.$data['db_secondfields'];
		}
		
		// Item Type HIDDEN
		if ( $itemType == 'hidden' ) {
			//$data['display'] = -1;
			//$data['light'] = 0;
		}

		// Item Type CHECKBOX || RADIO || SELECT SIMPLE || SELECT MULTIPLE || FREECODE
		if ( $itemType == 'checkbox' || $itemType == 'radio' || $itemType == 'select_simple' || $itemType == 'select_multiple' || $itemType == 'free_code' ) {
			if ( isset( $data['options'] ) && $data['options'] ) {
				$options = $data['options'];
				if ( is_array( $options ) ) {
					$opts = null;
					if ( sizeof( $options ) ) {
						foreach ( $options as $val ) {
							if ( $val != '' ) {
								$opts .= $val . '||';
							}
						}
					}
					$options = substr( $opts, 0, -2 );
					$data['options'] = $options;
				}
			}
		}
		
		// Item Type CHECKBOX || SELECT MULTIPLE
		if ( $itemType == 'checkbox' || $itemType == 'select_multiple' ) {
			if ( ! isset( $data['divider'] ) || $data['divider'] == '' ) {
				$data['divider'] = ',';
			}
		}
		
		// Item Type SELECT DYNAMIC 
		if ( $itemType == 'select_dynamic' ) {
			if ( $data['bool2'] ) {
				$data['options']	=	 JRequest::getVar( 'options', '', 'post', 'string', JREQUEST_ALLOWRAW );	
			} else {
				$cleanTable			=	str_replace( $dbpref, '#__', $data['db_tables'] );
				$data['options'] 	=	$cleanTable.'||'.$data['db_fields'].'||'.$data['db_secondfields'];
				$data['location'] 	=	( $data['bool'] ) ? $data['db_thirdfields'] : '';
				$data['content'] 	=	( $data['db_fourthfields'] ) ? $data['db_fourthfields'].'||'.$data['db_fourth'].'||'.$data['db_fourth_content'] : '';
				$data['extra'] 		=	( $data['db_fifthfields'] ) ? $data['db_fifthfields'].'||'.$data['db_fifth'] : '';
				if ( $data['bool'] == 2 ) {
					$data['elemxtd'] = 'item';
				} else {
					$data['elemxtd'] = '';
					$data['extended'] = '';
				}
				//
				$data['options2']	=	JRequest::getVar( 'options2', '', 'post', 'string', JREQUEST_ALLOWRAW );
			}
		}
		
		// Item Type SELECT NUMERIC
		if ( $itemType == 'select_numeric' ) {
			$data['options'] = $data['opt_first'].'||'.$data['opt_start'].'||'.$data['opt_step'].'||'.$data['opt_end'].'||'.$data['opt_last'];
		}
		
		// Item Type FORM ACTION || BUTTON RESET || BUTTON SUBMIT
		if ( $itemType == 'button_reset' || $itemType == 'button_submit' ) {
			$data['display'] = 0;
			$data['light'] = 0;
		}
		if ( $itemType == 'form_action' || $itemType == 'search_action' ) {
			$data['light'] = 0;
			$data['message'] = ( $data['message_updated'] == 1 ) ? JRequest::getVar( 'message', '', 'post', 'string', JREQUEST_ALLOWRAW )
			: ( ( ! $data['id'] ) ? HelperjSeblod_Helper::getWysiwygContent( 'message', 'items', $data['cid'][0] ) : HelperjSeblod_Helper::getWysiwygContent( 'message', 'items', $data['id'] ) );
			$data['message2'] = ( $data['message2_updated'] == 1 ) ? JRequest::getVar( 'message2', '', 'post', 'string', JREQUEST_ALLOWRAW )
			: ( ( ! $data['id'] ) ? HelperjSeblod_Helper::getWysiwygContent( 'message2', 'items', $data['cid'][0] ) : HelperjSeblod_Helper::getWysiwygContent( 'message2', 'items', $data['id'] ) );
			// TODO:delete == 3 empty BEGIN !! and then add publication->duraction :)
			if ( $itemType == 'form_action' ) {
				$data['cols']	=	0;
				$data['rows']	=	0;
			} 
			// TODO:delete == 3 empty END !! and then add publication->duraction :)
		}
		
		if ( $itemType == 'search_action' ) {
			if ( isset( $data['extra'] ) ) {
				$extra	=	$data['extra'];
				if ( is_array( $extra ) ) {
					$extra			=	implode( ',', $extra );
					$data['extra']	=	$extra;
				}
			} else {
				$data['extra'] = '18,19,20,21,23,24,25';
			}
		}
		
		// Item Type CAPTCHA IMAGE
		if ( $itemType == 'captcha_image' ) {
			if ( $data['bool'] == 1 ) {
				$data['bool2'] = $data['bool2_word'];
			} else {
				if ( sizeof( $data['bool2_math'] > 1 ) ) {
					$res	=	0;
					foreach ( $data['bool2_math'] as $item ) {
						$res	=	$res + $item;
					}
					$data['bool2'] = $res;
				} else {
					$data['bool2'] = $data['bool2_math'][0];
				}			
			}
		}
		
		// Item Type EMAIL
		if ( $itemType == 'email' ) {
			if ( isset( $data['toadmin'] ) ) {
				$nTo = count( $data['toadmin'] );
				if ( $nTo > 1 ) {
					$recipients	=	implode( ',', $data['toadmin'] );
					$data['toadmin']	=	$recipients;
				} else {
					$data['toadmin'] = $data['toadmin'][0];
				}
			} else {
				$data['toadmin'] = '';
			}
			$data['message'] = ( $data['message_updated'] == 1 ) ? JRequest::getVar( 'message', '', 'post', 'string', JREQUEST_ALLOWRAW )
			: ( ( ! $data['id'] ) ? HelperjSeblod_Helper::getWysiwygContent( 'message', 'items', $data['cid'][0] ) : HelperjSeblod_Helper::getWysiwygContent( 'message', 'items', $data['id'] ) );
		}
		
		// Item Type SAVE
		if ( $itemType == 'save' ) {
			if ( isset( $data['selected_categories'] ) ) {
				$nCat = count( $data['selected_categories'] );
				if ( $nCat > 1 ) {
					$categories = implode( ',', $data['selected_categories'] );
					$data['options'] = $categories;
				} else {
					$data['options'] = $data['selected_categories'][0];
				}
			} else {
				$data['options'] = '';
			}
		}
		
		// Item Type TEXTAREA
		if ( $itemType == 'textarea' ) {
			$textareaData = JRequest::getVar( 'defaultvalue', '', 'post', 'string', JREQUEST_ALLOWRAW );
			$data['defaultvalue'] = ( $textareaData ) ? $textareaData : $data['defaultvalue'];
		}
		
		// Item Type WYSIWYG
		if ( $itemType == 'wysiwyg_editor' || $itemType == 'free_text' ) {
			$data['defaultvalue'] = ( $data['defaultvalue_updated'] == 1 ) ? JRequest::getVar( 'defaultvalue', '', 'post', 'string', JREQUEST_ALLOWRAW )
			: ( ( ! $data['id'] ) ? HelperjSeblod_Helper::getWysiwygContent( 'defaultvalue', 'items', $data['cid'][0] ) : HelperjSeblod_Helper::getWysiwygContent( 'defaultvalue', 'items', $data['id'] ) );
		}
		
		// Item Type FREE CODE
		if ( $itemType == 'free_code' ) {
			$textareaData			=	JRequest::getVar( 'defaultvalue', '', 'post', 'string', JREQUEST_ALLOWRAW );
			$data['defaultvalue']	=	( $textareaData ) ? $textareaData : $data['defaultvalue'];
		}
		
		// Item ALIAS
		if ( $itemType == 'alias' ) {
			$data['elemxtd']	=	'item';
		}
		
		// Item Type FOLDER || FILE || UPLOAD SIMPLE || UPLOAD IMAGE
		if ( $itemType == 'folder' || $itemType == 'file' || $itemType == 'upload_simple' || $itemType == 'upload_image' ) {
			$data['location'] = str_replace( '\\', '/', $data['location'] );
			$nb = strlen( $data['location'] );
			for ( $i = $nb - 1; $i != 0; $i-- ) {
				if ( $data['location'][$i] == '/' ) {
					$data['location'] = substr( $data['location'], 0, -1 );
				} else {
					break;
				}
			}
			$data['location'][strlen( $data['location'] )] = '/';
			$nb = strlen( $data['location'] );
			for ( $i = 0; $i < $nb; ) {
				if ( $data['location'][$i] == '/' ) {
					$data['location'] = substr( $data['location'], 1 );
				} else {
					break;
				}
			}
	
			// Item Type FOLDER || FILE || UPLOAD SIMPLE || UPLOAD IMAGE
			if ( $itemType == 'upload_image' ) {
				$data['options']	=	$data['thumb1'].'--'.$data['width1'].'--'.$data['height1'].'||'
									.	$data['thumb2'].'--'.$data['width2'].'--'.$data['height2'].'||'
									.	$data['thumb3'].'--'.$data['width3'].'--'.$data['height3'].'||'
									.	$data['thumb4'].'--'.$data['width4'].'--'.$data['height4'].'||'
									.	$data['thumb5'].'--'.$data['width5'].'--'.$data['height5'];
									
			}
			if ( $itemType != 'folder' ) {
				if ( $data['options2'] ) {
					$data['options2']	=	str_replace( ' ', '', $data['options2'] );
				}
			}
		}
		
		// Item FIELD X
		if ( $itemType == 'field_x' ) {
			$data['content']	=	JRequest::getVar( 'content', '', 'post', 'string', JREQUEST_ALLOWRAW );
			$data['elemxtd']	=	'item';
		}
		// Item GROUP CONTENT TYPE
		if ( $itemType == 'content_type' ) {
			$data['content']	=	JRequest::getVar( 'content', '', 'post', 'string', JREQUEST_ALLOWRAW );
			$data['elemxtd']	=	'type';
			//$data['display']	=	3;
		}
		
		// Item Type PANEL
		if ( $itemType == 'panel_slider' ) {
			$data['display'] = -3;
		}

		// Item Type SUB PANEL
		if ( $itemType == 'sub_panel_tab' ) {
			$data['display'] = -2;
		}

		// Item JOOMLA MENU
		if ( $itemType == 'joomla_menu' ) {
			if ( $data['bool'] == 1 ) {
				$data['location']	=	$data['location_item'];
			} else {
				$data['location']	=	$data['location_type'];
			}
		}

		// Item CALENDAR
		if ( $itemType == 'calendar' ) {
			if ( $data['bool'] ) {
				$data['options']	=	$data['opt_start'].'||'.$data['opt_end'];
			}
		}
		
		// Item SEARCH GENERIC
		
		if ( isset( $data['content'] ) ) {
			$content = $data['content'];
			if ( is_array( $content ) ) {
				$content = implode( ',', $content );
				$data['content'] = $content;
			}
		} else {
			$data['content'] = '';
		}
		
		// Item JOOMLA USER
		if ( $itemType == 'joomla_user' ) {
			$data['elemxtd']	=	'item';
		}
		
		/**
		 * Store !!
		 **/
		
		// Bind Form Fields to Table
		if ( ! $row->bind( $data ) ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Make Sure Item is Available ( Not in Reserved )
		if ( $row->reserved() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Make Sure Record is Valid
		if ( ! $row->check() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Store Web Link Table to Database
		if ( ! $row->store() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		/**
		 * Extra SQL Post-Store
		 **/
		return $row->id;
	}

	/**
	 * Reserved Store
	 **/
	function reserved_store()
	{
		$data = JRequest::get( 'post' );
		$nReserved	=	count( $data['reserved_items'] );
		
		// Delete Reserved Items
		$query = 'DELETE FROM #__jseblod_cck_items_reserved';
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Re-Add Reserved Items
		if ( $nReserved ) {
			$reservedValues = null;
			if ( sizeof( $data['reserved_items'] ) ) {
				foreach ( $data['reserved_items'] as $val ) {
					$reservedValues .= ', ( "'.$val.'" ) ';
				}
			}
			$reservedValues = substr( $reservedValues, 1 );
			$query = 'INSERT INTO #__jseblod_cck_items_reserved ( name )'
				   . ' VALUES ' . $reservedValues;
			
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Batch Category Process
	 **/
	function batchCategory()
	{
		$cids		=	JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$categoryId	=	JRequest::getInt( 'category' );
		
		$inCids = implode( ',', $cids );
		
		if ( $categoryId && count( $cids ) && $inCids )
		{
			$n = count( $cids );
			$query = 'UPDATE #__jseblod_cck_items'
				   . ' SET category = '.(int)$categoryId
				   . ' WHERE id IN ( '.$inCids.' )'
				   ;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return $n;
	}
	
	/**
	 * Batch Copy Process
	 **/
	function batchCopy()
	{
		$cids		=	JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$prefix		=	JRequest::getString( 'prefix' );
		$prefix_old	=	JRequest::getString( 'prefix_old' );
		
		$n	=	count( $cids );
		if ( $n ) {
			foreach( $cids as $cid ) {
				$item		=&	JTable::getInstance( 'items', 'Table' );
				$item->load( $cid );
				$item->id	=	null;
				if ( $prefix_old && $prefix && strpos( $item->name, $prefix_old ) !== false && strpos( $item->name, $prefix_old ) == 0 ) {
					$length		=	strlen( $prefix_old );
					$item->name	=	substr( $item->name, $length );
					$item->name	=	$prefix.$item->name;
				} else {
					$item->name	=	$item->name.'_copy';
				}
				if ( $prefix_old && $prefix && strpos( strtolower($item->title), strtolower($prefix_old) ) !== false && strpos( strtolower($item->title), strtolower($prefix_old) ) == 0 ) {
					$length		=	strlen( $prefix_old );
					$item->title=	substr( $item->title, $length );
					$item->title=	ucfirst( $prefix.$item->title );
				} else {
					$item->title=	$item->name;
				}
				$item->store();
			}
		}
		
		return $n;
	}
	
	/**
	 * Live Store Record
	 **/
	function liveStore()
	{
		$liveId		=	JRequest::getInt( 'live_id' );
		$liveTitle	=	JRequest::getVar( 'live_title', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		if ( $liveId ) {
			$query	= 'UPDATE #__jseblod_cck_items'
					. ' SET title = "'.$liveTitle.'"'
					. ' WHERE id = '.(int)$liveId
					;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return $liveId;
	}
	
	/**
	 * Delete Record(s)
	 **/
	function delete()
	{
		global $mainframe;
		
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& $this->getTable( 'items' );

		if ( $n = count( $cids ) )
		{
			foreach( $cids as $cid ) {
				if ( $cid == 1 || $cid == 2 || $cid == 3 || $cid == 10 || $cid == 11 || $cid == 12 || $cid == 13 || $cid == 14
					|| $cid == 15 || $cid == 22 || $cid == 23 || $cid == 24 || $cid == 25 || $cid == 26 || $cid == 27 || $cid == 120 || $cid == 121 || $cid == 274 || $cid == 290 ) {
					$mainframe->enqueueMessage( JText::_( 'ALERT DEL NOT AUTH' ), "notice" );
					$n = $n - 1;
				} else {
					//
					$row->load( $cid );
					if ( $row->type == 1 ) {
						if ( $row->indexedkey ) {
							$query	=	'DROP TABLE #__jseblod_cck_extra_index_key_'.$row->name;
							$this->_db->setQuery( $query );
							if ( ! $this->_db->query() ) {
								return false;
							}
						}
					} else if ( $row->type == 7 ) {
						if ( $row->indexed ) {
							$query	=	'DROP TABLE #__jseblod_cck_extra_index_'.$row->name;
							$this->_db->setQuery( $query );
							if ( ! $this->_db->query() ) {
								return false;
							}
						}
					} else if ( $row->type == 51 ) {
						if ( $row->extended ) {
							if ( ( $key = strpos( $row->extended, '[' ) ) !== false ) {
								$refer	=	substr( $row->extended, $key + 1, -1 );
							} else if ( ( $key = strpos( $row->extended, '(' ) ) !== false ) {
								$refer	=	substr( $row->extended, $key + 1, -1 );
							} else {
								$refer	=	$row->extended;
							}
						}
						$query	=	'SHOW COLUMNS FROM #__jseblod_cck_ecommerce_cart_product';
						$this->_db->setQuery( $query );
						$columns	=	$this->_db->loadResultArray();
						if ( array_search( @$refer, $columns ) !== false ) {
							$query	=	'ALTER TABLE `#__jseblod_cck_ecommerce_cart_product` DROP `'.$refer.'`';
							$this->_db->setQuery( $query );
							if ( ! $this->_db->query() ) {
								return false;
							}
						}
						$query	=	'SHOW COLUMNS FROM #__jseblod_cck_ecommerce_cart_product_temp';
						$this->_db->setQuery( $query );
						$columns	=	$this->_db->loadResultArray();
						if ( array_search( @$refer, $columns ) !== false ) {
							$query	=	'ALTER TABLE `#__jseblod_cck_ecommerce_cart_product_temp` DROP `'.$refer.'`';
							$this->_db->setQuery( $query );
							if ( ! $this->_db->query() ) {
								return false;
							}
						}
					} else { }
					//
					
					if ( ! $row->delete( $cid ) ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
					
					/**
					 * Extra SQL Post-Delete
					 **/
					// Delete: Assignments Type|Item
					$query = 'DELETE FROM #__jseblod_cck_packs WHERE elemid = '.$cid.' AND type = "item"';
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
					
					// Delete: Assignments Type|Item
					$query = 'DELETE FROM #__jseblod_cck_type_item WHERE itemid = '.$cid;
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
				}
			}						
		}
		
		return $n;
	}
	
	/**
	 * Checkout Record
	 **/
	function checkout( $uid = null )
	{
		if ( $this->_id ) {
			$row =& $this->getTable( 'items' );
			
			// Check User Id
			if ( is_null( $uid ) ) {
				$user	=& JFactory::getUser();
				$uid	= $user->get( 'id' );
			}
			
			// Checkout!
			if( ! $row->checkout( $uid, $this->_id ) ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
			return true;
		}
		
		return false;
	}
	
	/**
	 * Checkin Record
	 **/
	function checkin()
	{		
		if ( $this->_id ) {
			$row =& $this->getTable( 'items' );
			
			// Checkin!
			if( ! $row->checkin( $this->_id ) ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return false;
	}
	
	/**
	 * Add into Pack
	 **/
	function addIntoPack()
	{
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'cckshare_export.php' );
		
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		if ( count( $cids ) && $cids[0] )
		{
			CCKjSeblodShare_Export::addIntoPack( $cids, 'field', 0 );
			
			return true;
		}
		
		return false;
	}
	
	function exportXml()
	{
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'cckshare_export.php' );
		
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$fileName	=	JRequest::getVar( 'name_package' );
		
		$inCids = implode( ',', $cids );
		
		if ( $n = count( $cids ) && $inCids )
		{
			if ( $file	=	CCKjSeblodShare_Export::exportContent_Items( $inCids, $fileName ) ) {
				return $file;
			}
			
			return false;
		}
		
		return false;
	}

}	
?>