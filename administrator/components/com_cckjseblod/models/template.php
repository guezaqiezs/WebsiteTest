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
jimport( 'joomla.installer.installer' );
jimport( 'joomla.installer.helper' );

/**
 * Template			Model Class
 **/
class CCKjSeblodModelTemplate extends JModel
{
	/**
	 * Vars
	 **/
	var $_id			= null;
	var $_data			= null;
	var $_templateNames	= null;
	var $_urls 			= null;
	var $_menuItems 	= null;
	var $_removeData	= null;
	
	/**
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
		// Set Id and Wipe Data
		$this->_id						= $id;
		$this->_data					= null;
		$this->_templateNames			= null;
		$this->_menuItems				= null;
		$this->_removeData				= null;
		
		$this->_categories				= null;
		$this->_assigned_categories		= null;
		$this->_available_categories	= null;
		$this->_urls 					= null;
	}
	
	/**
	 * Get Data from Database
	 **/
	function &getData()
	{
		if ( empty( $this->_data ) ) {
			$row =& $this->getTable( 'templates' );
			
			if ( $this->_id ) {
				$row->load( $this->_id );
				if ( ! $row->checked_out ) {
					$user =& JFactory::getUser();
					// Checkout!
					$row->checkout( $user->get('id') );
				}
				$this->_data =& $row;
				$this->_data->categorystate = $this->_getCategoryState( $this->_data->category );
			}
		}
		
		return $this->_data;
	}

	/**
	 * Get Data from Database
	 **/
	function getTemplateNames()
	{
		if ( empty( $this->_templateNames ) )
		{
			$orderby	= ' ORDER BY s.name ASC';
			
			$query = ' SELECT s.name'
				   . ' FROM #__jseblod_cck_templates AS s'
				   . $orderby
				   ;
			$this->_db->setQuery( $query );
			$this->_templateNames = $this->_db->loadResultArray();
		}
		
		return $this->_templateNames;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getAllMenuItems()
	{
		$where= ' WHERE s.templateid = '.(int) $this->_id . ' AND s.menuid = 0';
			
		$query = ' SELECT COUNT( s.menuid )'
			   . ' FROM #__jseblod_cck_template_menu AS s'
			   . $where
			   ;
    	$this->_db->setQuery( $query );
  		$allMenuItems = $this->_db->loadResult();
		
		return $allMenuItems;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getMenuItems()
	{
		if ( empty( $this->_menuItems ) )
		{
			$where= ' WHERE s.templateid = '.(int) $this->_id . ' AND cc.id AND cc.published >= 0';
			
			$query = ' SELECT s.menuid AS value'
				   . ' FROM #__jseblod_cck_template_menu AS s'
				   . ' LEFT JOIN #__menu AS cc ON cc.id = s.menuid '
				   . $where
				   ;
			$this->_menuItems = $this->_getList( $query );
		}
		
		return $this->_menuItems;
	}
	
	/**
	 * Get Data from Database
	 **/
	function getUrls()
	{
		if ( empty( $this->_urls ) )
		{
			$where		= ' WHERE s.templateid = '.$this->_id;
			$orderby	= ' ORDER BY s.id ASC';
			
			$query = ' SELECT s.*'
				   . ' FROM #__jseblod_cck_template_url AS s'
				   . $where
				   . $orderby
				   ;
			$this->_urls = $this->_getList( $query );
		}
		
		return $this->_urls;
	}
	/**
	 * Get Data from Database
	 **/
	function &getAssignedCategories()
	{
		if ($this->_id) {
			if (empty( $this->_assigned_categories ))
			{
				$where = ' WHERE templateid = '.$this->_id . ' AND cc.id';
				
				$query = 'SELECT s.catid AS value, (CONCAT( cc.title,(CONCAT(" ( ", (CONCAT(ccc.title, " )")) )) )) AS text'
					   . ' FROM #__jseblod_cck_template_cat AS s'
					   . ' LEFT JOIN #__categories AS cc ON cc.id = s.catid '
					   . ' LEFT JOIN #__sections AS ccc ON ccc.id = cc.section '					   
					   . $where
					   . ' ORDER BY cc.title asc' ;
				$this->_db->setQuery( $query );
				$this->_assigned_categories = $this->_db->loadObjectList();
			}
		} else { $this->_assigned_categories = array() ; }
		
		return $this->_assigned_categories;
	}
	

	
	/**
	 * Get Data from Database
	 **/
	function &getAvailableCategories()
	{
		if (empty( $this->_available_categories ))
		{
			$where = ' WHERE s.section NOT LIKE "%com_%" ';
			$excluded = $this->_getExcludedCategories( true ); //todo//configuration//
			if ( $excluded ) {
				$where .= ' AND s.id NOT IN ('.$excluded.')';
			}

			$query = 'SELECT s.id AS value, (CONCAT( s.title,(CONCAT(" ( ", (CONCAT(cc.title, " )")) )) )) AS text'
				   . ' FROM #__categories AS s'
				   . ' LEFT JOIN #__sections AS cc ON cc.id = s.section '	
				   . $where
				   . ' ORDER BY s.title asc ;' ;
			$this->_db->setQuery( $query );
			$this->_available_categories = $this->_db->loadObjectList();
		}
		return $this->_available_categories;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getExcludedCategories( $all )
	{
		if ( empty( $this->_categories ) ) {

			$where = ' WHERE templateid = '.$this->_id;
			if ( $all ) {
				$where = '';
			}
			
			$query = ' SELECT catid FROM #__jseblod_cck_template_cat '
					. $where
					. ';';
			$this->_db->setQuery( $query );
			$this->_categories = $this->_db->loadResultArray();
			if ( is_array( $this->_categories ) ) {
				$this->_categories = implode( ',', $this->_categories );
			}
		}
		
		return $this->_categories;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _getCategoryState( $categoryId )
	{
		$where = ' WHERE s.id = '.$categoryId;
      		
  		$query = ' SELECT s.published'
  			. ' FROM #__jseblod_cck_templates_categories AS s'
  			. $where
  		;
    	$this->_db->setQuery( $query );
  		$this->_categorystate = $this->_db->loadResult();
		
		return $this->_categorystate;
	}
	
	/**
	 * Store Record(s)
	 **/
	function store()
	{
		$row =& $this->getTable( 'templates' );
		$data = JRequest::get( 'post' );
		
		/**
		 * Extra POST Pre-Store
		 **/
		$data['title']			=	trim( $data['title'] );
		$data['description']	=	( $data['description_updated'] == 1 ) ? JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW )
		: ( ( ! $data['id'] ) ? HelperjSeblod_Helper::getWysiwygContent( 'description', 'templates', $data['cid'][0] ) : HelperjSeblod_Helper::getWysiwygContent( 'description', 'templates', $data['id'] ) );
		$isNew	= ( $data['id'] ) ? 0 : 1;
		
		/**
		 * Special Template Upload & Install
		 **/
		
		if ( $data['select_install'] == 'upload' || $data['select_install'] == 'folder'  ) {
			
			$doUpload = JRequest::getVar( 'install_package', null, 'files', 'array' );
			if ( $data['select_install'] == 'upload' && $doUpload['name'] ) {
				$row->load( $this->_id );
				if ( ! $isNew && $row->name && JFolder::exists( JPATH_SITE.DS.'templates'.DS.$row->name ) ) {
					$result = $this->_removeTemplate( $row->name );
				}
				if ( $this->_installTemplate() ) {
					$cache = &JFactory::getCache( 'mod_menu' );
					$cache->clean();
					$templateName = $this->getState( 'name' );
				} else {
					return false;
				}
			} else if ( $data['select_install'] == 'folder' && $data['name'] ) {
				if ( $data['install_folder'] && $data['install_folder'] != $data['name'] ) {
					$srcName = $data['install_folder'];
					$templateName = $data['name'];
					if ( JFolder::exists( JPATH_SITE.DS.'templates'.DS.$srcName ) && ! JFolder::exists( JPATH_SITE.DS.'templates'.DS.$templateName ) ) {
						JFolder::copy( JPATH_SITE.DS.'templates'.DS.$srcName,
									   JPATH_SITE.DS.'templates'.DS.$templateName );
					} else {
					//MESSAGE ERROR
					}
					$manifest = JPATH_SITE.DS.'templates'.DS.$templateName.DS.'templateDetails.xml';
					if ( JFile::exists( $manifest ) ) {
						$buffer = JFile::read( $manifest );
						$buffer = str_replace( '<name>'.$srcName.'</name>', '<name>'.$templateName.'</name>', $buffer );
						JFile::write( $manifest, $buffer );
						//MESSAGE OK
					}
					
				} else {
					$templateName = $data['name'];
					//MESSAGE OK
				}
			}
			$data['name'] = $templateName;
		}
		
		/**
		 * Store !!
		 **/	
		// Bind Form Fields to Table
		if ( ! $row->bind( $data ) ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		// Make Sure Template is Available ( Not in Hidden )
		if ( $row->hidden() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Make Sure Record is Valid
		if ( ! $row->check() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		$row->type	=	$data['tpl_type']; //!important;
		
		// Store Web Link Table to Database
		if ( ! $row->store() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		/**
		 * Extra SQL Post-Store
		 **/
		
		if ( $data['select_install'] == 'default' ) {
			$params	= JRequest::getVar('params', array(), 'post', 'array');
			$file = JPATH_SITE.DS.'templates'.DS.$row->name.DS.'params.ini';
			if ( JFile::exists( $file ) && count( $params ) )
			{
				$registry = new JRegistry();
				$registry->loadArray( $params );
				$txt = $registry->toString();
				JFile::write( $file, $txt );
			}
		}
		
		// Delete Joomla Category Assignements
		$query = 'DELETE FROM #__jseblod_cck_template_cat WHERE templateid = '.$row->id;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Insert Joomla Category Assignments
		$nCat = count( $data['selected_categories'] );
		if ( $nCat ) {
			$nCatA = 1;
			$assignmentsValuesCat = null;
			foreach ( $data['selected_categories'] as $val ) {
				$assignmentsValuesCat .= ', ( '.$row->id.', '.(int)$val.', "category" ) ';
				$nTotalCat++;
			}
			$assignmentsValuesCat = substr( $assignmentsValuesCat, 1 );
			$query = 'INSERT INTO #__jseblod_cck_template_cat ( templateid, catid, type )'
				   . ' VALUES ' . $assignmentsValuesCat;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		// Delete Menu Item Assignements
		$query = 'DELETE FROM #__jseblod_cck_template_menu WHERE templateid = '.(int) $row->id;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// Insert Menu Item Assignments
		$nMenus	= count( $data['selected_menus'] );
		$selectedMenus = $data['selected_menus'];
		JArrayHelper::toInteger( $selectedMenus );
		
		if ( $data['menus'] == 'all' ) {
			$nMenuA = 1;
			$query = 'INSERT INTO #__jseblod_cck_template_menu ( templateid, menuid, type )'
				   . ' VALUES ( '.(int) $row->id.', 0, "menu" )';
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		} else {
			if ( $nMenus ) {
				foreach ( $selectedMenus as $item ) {
					if ( $item ) {
						$nMenuA = 1;
						$query = 'INSERT INTO #__jseblod_cck_template_menu ( templateid, menuid, type )'
							   . ' VALUES ( '.(int) $row->id.', '.(int) $item.', "menu" )';
						$this->_db->setQuery( $query );
						if ( ! $this->_db->query() ) {
							$this->setError( $this->_db->getErrorMsg() );
							return false;
						}
					}
				}
			}
		}		
		// Delete Site Url Assignements
		$query = 'DELETE FROM #__jseblod_cck_template_url WHERE templateid = '.$row->id;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		// Insert Site Url Assignments
		$nUrl = count( $data['urls_exact'] );
		if ( $nUrl ) {
			for ( $i = 0; $i < $nUrl; $i++ ) {
				if ( $data['urls_title'][$i] && $data['urls_url'][$i] ) {
					$nUrlA = 1;
					$urlId= $i + 1;
					$urlTitle = $data['urls_title'][$i];
					$urlUrl = $data['urls_url'][$i];
					$urlExact = $data['urls_exact'][$i];
					$query = 'INSERT INTO #__jseblod_cck_template_url ( templateid, urlid, title, url, exact, type )'
						   . ' VALUES ( '.$row->id.', '.$urlId.', "'.$urlTitle.'", "'.$urlUrl.'", '.$urlExact.', "url" )';
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
				}
			}
		}
		// New|Update Content Type && New Content Items

		if ( $data['type'] == -1 || $data['type'] == -2 ) {

			$title = $data['type_title'];
			$category = $data['type_category'];
			
			if ( $data['type'] == -2 ) {
				$name = $data['name'];
			} else {
     			$name =	HelperjSeblod_Helper::stringURLSafe( $title );
    			if( trim( str_replace( '_', '', $name ) ) == '' ) {
    				$datenow	=&	JFactory::getDate();
    				$name =	$datenow->toFormat( "%Y_%m_%d_%H_%M_%S" );
    			}
  			}
			
			// Check If Content Type Exist ( by name )
			$query = 'SELECT s.id'
					.' FROM #__jseblod_cck_types AS s'
					.' WHERE s.name = "'.$name.'"'
					;
			$this->_db->setQuery( $query );
			$existingType = $this->_db->loadResult();
			
			if ( $existingType ) {
				$newContentTypeId = $existingType;
			} else {
				$query = ' INSERT INTO #__jseblod_cck_types ( title, name, category, admintemplate, sitetemplate, contenttemplate, description, published )'
					   . ' VALUES ( "'.$title.'", "'.$name.'", '.(int)$category.', 1, 1, '.(int)$row->id.', "", 1 )';
					;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
				$newContentTypeId = $this->_db->insertid();
			}			
			
			// Insert Joomla Category Assignments Into Content Type
			$query = 'SELECT catid FROM #__jseblod_cck_type_cat';
			$this->_db->setQuery( $query );
			$excludedCat = $this->_db->loadResultArray();
			
			if ( $nCat ) {
				$assignmentsValuesCat = null;
				foreach ( $data['selected_categories'] as $val ) {
					if ( ! in_array( $val, $excludedCat )  ) {
						$assignmentsValuesCat .= ', ( '.(int)$newContentTypeId.', '.(int)$val.', "category" ) ';
					}
				}
				$assignmentsValuesCat = substr( $assignmentsValuesCat, 1 );
				if ( $assignmentsValuesCat ) {
					$query = 'INSERT INTO #__jseblod_cck_type_cat ( typeid, catid, type )'
						   . ' VALUES ' . $assignmentsValuesCat;
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
				}
			}
		}

		if ( $data['type'] && $data['type_title'] ) {
			$contentTypeId	= ( $newContentTypeId ) ? $newContentTypeId : $data['type'];
			
			// Get ContentType From File
			if ( JFile::exists( JPATH_SITE.DS.'templates'.DS.$row->name.DS.'index.php' ) ) {
				$buffer = JFile::read( JPATH_SITE.DS.'templates'.DS.$row->name.DS.'index.php' );
			}
			$regex = "#jSeblod->([a-zA-Z0-9_]*)#s";
			preg_match_all($regex, $buffer, $matches);
			$newContentItems = array_keys( array_flip( $matches[1] ) );
			
			// Get ContentItems From Database
			$query = ' SELECT s.name FROM #__jseblod_cck_items AS s '
				   ;
			$this->_db->setQuery( $query );
			$contentItems = $this->_db->loadResultArray();
			
			// Push NewContentItems Into Database
			for( $i = 0, $j = 0, $n = count( $newContentItems ); $i < $n; $i++ ) {
				if ( ! in_array( $newContentItems[$i], $contentItems ) ) {
					if ( $j == 0 ) {
						$itemsValues = ' ( "", "'.$newContentItems[$i].'", "'.$newContentItems[$i].'", "", 1, 1, 1, 3 ) ';
						$j++;
					} else {
						$itemsValues .= ', ( "", "'.$newContentItems[$i].'", "'.$newContentItems[$i].'", "", 1, 1, 1, 3 ) ';
					}
				}
			}
			if ( $j > 0 ) {
				$query = 'INSERT INTO #__jseblod_cck_items ( `id`, `title`, `name`, `description`, `category`, `type`, `light`, `display` ) VALUES ' . $itemsValues;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
			}
			
			// Get NewContentItemsID Into Database
			$newContentItemsName = implode ( '","', $newContentItems );
			$newContentItemsName = "\"".$newContentItemsName."\"";
			$query = ' SELECT s.id FROM #__jseblod_cck_items AS s'
					.' WHERE s.name IN ( '.$newContentItemsName.' )';
					;
			$this->_db->setQuery( $query );
			$newContentItemsId = $this->_db->loadResultArray();
			
			// Get Type|Item Assignments From Database
			$query = ' SELECT s.itemid FROM #__jseblod_cck_type_item AS s'
					.' WHERE s.client = "admin" AND s.typeid = '.$contentTypeId
					;
			$this->_db->setQuery( $query );
			$assignmentsAdminItemsId = $this->_db->loadResultArray();
			
			$query = ' SELECT s.itemid FROM #__jseblod_cck_type_item AS s'
					.' WHERE s.client = "site" AND s.typeid = '.$contentTypeId
					;
			$this->_db->setQuery( $query );
			$assignmentsSiteItemsId = $this->_db->loadResultArray();
			
			$query = ' SELECT s.itemid FROM #__jseblod_cck_type_item_email AS s'
					.' WHERE s.client = "content" AND s.typeid = '.$contentTypeId
					;
			$this->_db->setQuery( $query );
			$assignmentsContentItemsId = $this->_db->loadResultArray();
			
			$assignmentsValues	=	null;
			$assignmentsValuesContent	=	null;
			$j	=	( count( $assignmentsAdminItemsId ) ? count( $assignmentsAdminItemsId ) : 1 ) ;
			$k	=	( count( $assignmentsSiteItemsId ) ? count( $assignmentsSiteItemsId ) : 1 ) ;
			$x	=	( count( $assignmentsContentItemsId ) ? count( $assignmentsContentItemsId ) : 0 ) ;
			$x++;
			if ( $j == 1 ) {
				$assignmentsValues = ' ( '.$contentTypeId.', 1, "admin", 1 ) ';
			}
			$j++;
			if ( $k == 1 ) {
				if ( ! $assignmentsValues ) {
					$assignmentsValues = ' ( '.$contentTypeId.', 1, "site", 1 ) ';
				} else {
					$assignmentsValues .= ', ( '.$contentTypeId.', 1, "site", 1 ) ';
				}
			}
			$k++;
			// Push ContentType NewAssignments Into Database
			for( $i = 0, $n = count( $newContentItemsId ); $i < $n; $i++ ) {
				if ( ! in_array( $newContentItemsId[$i], $assignmentsAdminItemsId ) ) {
					if ( ! $assignmentsValues ) {
						$assignmentsValues = ' ( '.$contentTypeId.', '.$newContentItemsId[$i].', "admin", '.$j.' ) ';
					} else {
						$assignmentsValues .= ', ( '.$contentTypeId.', '.$newContentItemsId[$i].', "admin", '.$j.' ) ';
					}
					$j++;
				}
				if ( ! in_array( $newContentItemsId[$i], $assignmentsSiteItemsId ) ) {
					if ( ! $assignmentsValues ) {
						$assignmentsValues = ' ( '.$contentTypeId.', '.$newContentItemsId[$i].', "site", '.$k.' ) ';
					} else {
						$assignmentsValues .= ', ( '.$contentTypeId.', '.$newContentItemsId[$i].', "site", '.$k.' ) ';
					}
					$k++;
				}
				if ( ! in_array( $newContentItemsId[$i], $assignmentsContentItemsId ) ) {
					if ( ! $assignmentsValuesContent ) {
						$assignmentsValuesContent = ' ( '.$contentTypeId.', '.$newContentItemsId[$i].', "content", '.$x.' ) ';
					} else {
						$assignmentsValuesContent .= ', ( '.$contentTypeId.', '.$newContentItemsId[$i].', "content", '.$x.' ) ';
					}
					$x++;
				}
			}
			if ( $assignmentsValues ) {
				$query = 'INSERT INTO #__jseblod_cck_type_item ( `typeid`, `itemid`, `client`, `ordering` ) VALUES ' . $assignmentsValues;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
			}
			if ( $assignmentsValuesContent ) {
				$query = 'INSERT INTO #__jseblod_cck_type_item_email ( `typeid`, `itemid`, `client`, `ordering` ) VALUES ' . $assignmentsValuesContent;
				$this->_db->setQuery( $query );
				if ( ! $this->_db->query() ) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
			}
		}

		// Save Locations & Positions
		HelperjSeblod_Helper::setTemplateLocPos( 'location', $data['locations'], $row->name );
		
		$this->_triggerViews();
		
		return $row->id;
	}
	
	/**
	 * Trigger Views
	 **/
	function _triggerViews()
	{
		// Categories
		$query	= 'SELECT COUNT(s.templateid)'
				. ' FROM #__jseblod_cck_template_cat AS s'
				;
		$this->_db->setQuery( $query );
		$cat	=	$this->_db->loadResult();
		$cat	=	( $cat ) ? 1 : 0;
		
		// Menu
		$query	= 'SELECT COUNT(s.templateid)'
				. ' FROM #__jseblod_cck_template_menu AS s'
				;
		$this->_db->setQuery( $query );
		$menu	=	$this->_db->loadResult();
		$menu	=	( $menu ) ? 1 : 0;
		
		// Url
		$query	= 'SELECT COUNT(s.templateid)'
				. ' FROM #__jseblod_cck_template_url AS s'
				. ' WHERE s.exact=0'
				;
		$this->_db->setQuery( $query );
		$url	=	$this->_db->loadResult();
		$url	=	( $url ) ? 1 : 0;
		
		// Url E
		$query	= 'SELECT COUNT(s.templateid)'
				. ' FROM #__jseblod_cck_template_url AS s'
				. ' WHERE s.exact=1'
				;
		$this->_db->setQuery( $query );
		$url_e	=	$this->_db->loadResult();
		$url_e	=	( $url_e ) ? 1 : 0;
		
		// Update Config
		$query	= 'UPDATE #__jseblod_cck_configuration'
				. ' SET views_category = '.(int)$cat
				. ' , views_menu = '.(int)$menu
				. ' , views_url = '.(int)$url
				. ' , views_url_e = '.(int)$url_e
				. ' WHERE id = 1'
				;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->query() ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
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
			$query = 'UPDATE #__jseblod_cck_templates'
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
	 * Live Store Record
	 **/
	function liveStore()
	{
		$liveId		=	JRequest::getInt( 'live_id' );
		$liveTitle	=	JRequest::getVar( 'live_title', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		if ( $liveId ) {
			$query	= 'UPDATE #__jseblod_cck_templates'
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
	 * Get Data from Database
	 **/
	function &getRemoveData()
	{
		if ( empty( $this->_removeData ) )
		{
			$cids 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
			$inCids = implode( ',', $cids );
			
			$query = 'SELECT s.id, s.title'
				   . ' FROM #__jseblod_cck_templates AS s'
				   . ' WHERE s.id IN ( '.$inCids.' )'
				   ;
			$this->_db->setQuery( $query );
			$this->_removeData = $this->_db->loadObjectList();
		}
		
		return $this->_removeData;
	}
	
	/**
	 * Install Template
	 **/
	function _installTemplate()
	{
		global $mainframe;

		$this->setState('action', 'install');

		$package = $this->_getPackageFromUpload();
		
		// Check If Package Unpacked
		if ( ! $package ) {
			$this->setState( 'message', 'Unable to find install package' );
			return false;
		}

		// Get Installer Instance
		$installer =& JInstaller::getInstance();
		
		// Install Package
		if (!$installer->install($package['dir'])) {
			// Error Installing Package
			$msg = JText::sprintf('Error: Error Installing Package', JText::_($package['type']), JText::_('Error'));
			$result = false;
		} else {
			// Package Installed Sucessfully
			$msg = JText::sprintf('Template Installed', JText::_($package['type']), JText::_('Success'));
			$result = true;
		}

		// Set Model State Values
		$mainframe->enqueueMessage($msg);
		$this->setState('name', $installer->_adapters['template']->get('name'));	//$this->setState('name', $installer->get('name'));
		$this->setState('result', $result);
		$this->setState('message', $installer->message);
		$this->setState('extension.message', $installer->get('extension.message'));

		// Cleanup Install Files
		if (!is_file($package['packagefile'])) {
			$config =& JFactory::getConfig();
			$package['packagefile'] = $config->getValue('config.tmp_path').DS.$package['packagefile'];
		}

		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']); //TODOCURRENT

		return $result;
	}
	
	
	/**
	 * Upload, Unpack and Get Template Package File Informations
	 **/
	function _getPackageFromUpload()
	{
		// Get Uploaded File Information
		$userfile = JRequest::getVar('install_package', null, 'files', 'array' );

		// Make Sure File Uploads Enabled in Php
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLFILE'));
			return false;
		}

		// Make Sure Zlib Loaded
		if (!extension_loaded('zlib')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLZLIB'));
			return false;
		}

		// If No Uploaded File
		if (!is_array($userfile) ) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('No file selected'));
			return false;
		}

		// Check If Problem Uploading File.
		if ( $userfile['error'] || $userfile['size'] < 1 )
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLUPLOADERROR'));
			return false;
		}

		// Build Appropriate Paths
		$config =& JFactory::getConfig();
		$tmp_dest 	= $config->getValue('config.tmp_path').DS.$userfile['name'];
		
		$tmp_src	= $userfile['tmp_name'];
		
		// Move Uploaded File
		$uploaded = JFile::upload($tmp_src, $tmp_dest);
		
		// Unpack Downloaded Package File
		$package = JInstallerHelper::unpack($tmp_dest);
		
		return $package;
	}
	
	/**
	 * Delete Record(s)
	 **/
	function delete( $deleteMode )
	{
		global $mainframe;
		
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& $this->getTable( 'templates' );

		if ( $n = count( $cids ) )
		{
			foreach($cids as $cid) {
				if ( $cid == 1 || $cid == 2 || $cid == 3 )  {
					$mainframe->enqueueMessage( JText::_( 'ALERT DEL NOT AUTH' ), "notice" );
					$n = $n - 1;
				} else {
					if ( $deleteMode == -1 ) {
						$row->load( $cid );
						if ( $row->name && JFolder::exists( JPATH_SITE.DS.'templates'.DS.$row->name ) ) {
							$res = $this->_removeTemplate( $row->name );
						}
					}
					if ( ! $row->delete( $cid ) ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
					
					// Delete Assignments From Template|Cat
					$query = 'DELETE FROM #__jseblod_cck_template_cat WHERE templateid = '.$cid;
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
					
					// Delete Assignments From Template|Menu
					$query = 'DELETE FROM #__jseblod_cck_template_menu WHERE templateid = '.$cid;
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
					
					// Delete Assignments From Template|Url
					$query = 'DELETE FROM #__jseblod_cck_template_url WHERE templateid = '.$cid;
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
				
					$query = 'DELETE FROM #__jseblod_cck_packs WHERE elemid = '.$cid.' AND type = "tmpl"';
					$this->_db->setQuery( $query );
					if ( ! $this->_db->query() ) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
				}
			}						
		}
		
		return $n;
	}

	/**
	 * Remove Template
	 **/
	function _removeTemplate( $eid )
	{
		global $mainframe;
		
		// Get an installer object for the extension type
		$installer =& JInstaller::getInstance();
		
		// Uninstall the chosen extensions
		$result	= $installer->uninstall( 'template', $eid, 0 );
		
		if ( $result === false ) {
			// There was an error in uninstalling the package
			$msg = JText::sprintf( 'Error: Error Removing Template', JText::_( $this->_type ), JText::_( 'Error' ) );
			$result = false;
		} else {
			// Package uninstalled sucessfully
			$msg = JText::sprintf( 'Template Removed', JText::_( $this->_type ), JText::_( 'Success' ) );
			$result = true;
		}
		
		$mainframe->enqueueMessage( $msg );
		$this->setState( 'action', 'remove' );
		$this->setState( 'name', $installer->get( 'name' ) );
		$this->setState( 'message', $installer->message );
		$this->setState( 'extension.message', $installer->get( 'extension.message' ) );
		
		return $result;
	}
	
	function publish( $cid = array(), $publish = 1 )
	{
		$user 	=& JFactory::getUser();
			
		if ( count( $cid ) ) {
			JArrayHelper::toInteger( $cid );
			$cids = implode( ',', $cid );
				
			$query = 'UPDATE #__jseblod_cck_templates'
				   . ' SET published = '.(int)$publish
				   . ' WHERE id IN ( '.$cids.' )'
				   ;
			$this->_db->setQuery( $query );
			if ( ! $this->_db->query() ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Checkout Record
	 **/
	function checkout( $uid = null )
	{
		if ( $this->_id ) {
			$row =& $this->getTable( 'templates' );
			
			// Check User Id
			if ( is_null( $uid ) ) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
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
			$row =& $this->getTable( 'templates' );
			
			// Checkin!
			if( ! $row->checkin( $this->_id ) ) {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
		
		return false;
	}
	
	/**
	 * Return Database Query
	 **/
	function _buildQueryCategory( $parentid )
	{
		$query  = 'SELECT s.name, (COUNT(parent.name) - (sub_tree.depth + 1)) AS depth'
				. ' FROM #__jseblod_cck_items_categories AS s,'
				. ' #__jseblod_cck_items_categories AS parent,'
				. ' #__jseblod_cck_items_categories AS sub_parent,'
				. ' ('
		            . ' SELECT s.name, (COUNT(parent.name) - 1) AS depth'
		            . ' FROM #__jseblod_cck_items_categories AS s,'
		            . ' #__jseblod_cck_items_categories AS parent'
		            . ' WHERE s.lft BETWEEN parent.lft AND parent.rgt'
		            . ' AND s.id = '.$parentid
		            . ' GROUP BY s.name'
		            . ' ORDER BY s.lft'
					. ' ) AS sub_tree'
				. ' WHERE s.lft BETWEEN parent.lft AND parent.rgt'
				. ' AND s.lft BETWEEN sub_parent.lft AND sub_parent.rgt'
				. ' AND sub_parent.name = sub_tree.name'
				. ' GROUP BY s.name'
				. ' HAVING depth <= 1'
				. ' ORDER BY s.lft'
				;
      
		return $query;
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
			CCKjSeblodShare_Export::addIntoPack( $cids, 'tmpl', 0 );
			
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
			if ( $file	=	CCKjSeblodShare_Export::exportContent_Templates( $inCids, $fileName ) ) {
				return $file;
			}
			
			return false;
		}
		
		return false;
	}
	
}
?>