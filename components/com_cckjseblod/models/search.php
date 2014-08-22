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
jimport( 'joomla.error.profiler' );

/**
 * Search			Model Class
 **/
class CCKjSeblodModelSearch extends JModel
{
	/**
	 * Vars
	 **/
	var $_data			=	null;
	var $_template		=	null;
	var $_cache			=	0;
	var $_cacheGroups	=	'';
	var $_debug			=	0;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
		
		global $mainframe;

		$this->setValues();
		
		//Get configuration
		$config = JFactory::getConfig();

		// Get the pagination request variables
		$this->setState('limit', $mainframe->getUserStateFromRequest('com_cckjseblod.limit', 'limit', $config->getValue('config.list_limit'), 'int'));
		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));

		// Set the search parameters
		$keyword		= urldecode(JRequest::getString('searchword'));
		$match			= JRequest::getWord('searchphrase', '');
		$ordering		= JRequest::getWord('ordering', 'alpha');
		$this->setSearch($keyword, $match, $ordering);

		// Set Areas
		$this->setAreas();
	}

	/**
	 * Set Values
	 **/
	function setValues()
	{
		// Set Values
		$this->_data	=	null;
		$this->_dataRes	=	null;
	}
	
	/**
	 * Set Caching & Debug
	 */
	function setCaching( $cache, $cacheGroups, $debug )
	{
		$this->_cache		=	$cache;
		$this->_cacheGroups	=	$cacheGroups;
		$this->_debug		=	$debug;
	}
	
	/********************************************************************
	 ************************ SEARCH -> DEFAULT *************************
	 ********************************************************************/
	 
	/**
	 * Get Data from Database
	 **/
	function &getData()
	{
		if ( empty( $this->_data ) )
		{
			$where		=	$this->_buildContentWhere();
			$orderby	=	$this->_buildContentOrderBy();
				
			$query	= 'SELECT s.*, cc.title AS categorytitle'
					. ' FROM #__jseblod_cck_searchs AS s'
					. ' LEFT JOIN #__jseblod_cck_searchs_categories AS cc ON cc.id = s.category'
					. $where
					. $orderby
					;
			$this->_db->setQuery( $query );
			$this->_data	=	$this->_db->loadObjectList();
		}
		
		return $this->_data;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _buildContentWhere()
	{
		global $mainframe;
		$params	=&	$mainframe->getParams();

		$where	=	( $params->get( 'show_unpublished', 0 ) ) ? ' WHERE s.published >= 0' : ' WHERE s.published = 1';

		if ( $params->get( 'category_id', 0 ) ) {
			$catid	=	$params->get( 'category_id', 0 );
			$where	.=	' AND s.category = '.(int)$catid;
		}
		
		return $where;
	}
	
	/**
	 * Get Data from Database
	 **/
	function _buildContentOrderBy()
	{
		global $mainframe;
		$params	=&	$mainframe->getParams();
		
		$orderby	= ' ORDER BY ';
		$order		=	$params->get( 'orderby_sec', 'alpha' );
		
		if ( $order == 'ralpha' ) {
			$orderby	.=	's.title DESC';
		} else {
			$orderby	.=	's.title ASC';
		}
			
		return $orderby;
	}
	
	/********************************************************************
	 ********************** SEARCH -> SEARCH/RESULT *********************
 	 ********************************************************************/
	 
	/**
	 * Set Search
	 **/
	function setSearch($keyword, $match = '', $ordering = 'alpha')
	{
		if(isset($keyword)) {
			$this->setState('keyword', $keyword);
		}

		if(isset($match)) {
			$this->setState('match', $match);
		}

		if(isset($ordering)) {
			$this->setState('ordering', $ordering);
		}
	}

	/**
	 * Set Areas
	 **/
	function setAreas()
	{
		$active		=	array();
		$active[0]	=	'cckjseblod';
		
		$this->_areas['active']	=	$active;
	}

	/**
	 * Get Search Results
	 **/
	function getDataResult( $user )
	{
		$match	=	( $this->getState( 'match' ) ) ? $this->getState( 'match' ) : $this->getState( 'searchmode' );
		
		JPluginHelper::importPlugin( 'search', 'cckjseblod' );
		$dispatcher	=&	JDispatcher::getInstance();
		
		// Call Search Plugin ( with or without caching )
		if ( $this->_debug ) {
			$profiler	=	new JProfiler();
		}
		if ( $this->_cache ) {
			// Cache [ON]
			$cache	=&	JFactory::getCache();
			$cache->setCaching( 1 );
			$cache->_options['cachebase']	=	JPATH_CACHE.DS.'cck-cache-search'; //Method!
			$user		=	( $this->_cache == 2 && $user->id > 0 && strpos( ','.$this->_cacheGroups.',', ','.$user->gid.',' ) !== false ) ? $user : null;
			$results	=	$cache->call( array( $dispatcher, 'trigger' ), 'onSearch', array( '', $match, '', $this->_areas['active'], $this->getState('searchlimit'), $this->getState('keywords'),
																					 $this->getState('sortwords'), $this->getState('searchin'), $this->getState('searchlength'),
																					 $this->getState('Itemid'), $user, $this->_cache,
																					 $this->getState('stage'), $this->getState('stages'), $this->_debug ) );
		} else {
			// Cache [OFF]
			$results	=	$dispatcher->trigger( 'onSearch', array( '', $match, '', $this->_areas['active'], $this->getState('searchlimit'), $this->getState('keywords'),
																					 $this->getState('sortwords'), $this->getState('searchin'), $this->getState('searchlength'),
																					 $this->getState('Itemid'), $user, $this->_cache,
																					 $this->getState('stage'), $this->getState('stages'), $this->_debug ) );
		}
		$rows	=	array();
		foreach ( $results AS $result ) {
			$rows	=	array_merge( (array)$rows, (array)$result );
		}
		if ( $this->_debug ) {
			echo $profiler->mark( JText::_( 'SEARCH CACHING STATE'.$this->_cache ) );
			echo ' ~ <b>' . count( $rows ) .' '. JText::_( 'RESULTS' ) . '</b>';
			echo ( $this->getState('stage') == 0 ) ? '<br />' : '<br />';
		}
		
		if ( $this->getState('stage') == 0 ) {
			$this->_total	= count($rows);
			if($this->getState('limit') > 0) {
				$this->_dataRes	=	array_splice($rows, $this->getState('limitstart'), $this->getState('limit'));
			} else {
				$this->_dataRes	=	$rows;
			}
		} else {
			$this->_total	=	0;
			$this->_dataRes	=	$rows;
		}

		return $this->_dataRes;
	}

	/**
	 * Get Total
	 **/
	function getTotal()
	{
		return $this->_total;
	}

	/**
	 * Get Pagination
	 **/
	function getPagination( $class_sfx )
	{
		if ( empty( $this->_pagination ) )
		{
			jimport( 'joomla.html.pagination' );
			$this->_pagination	=	new JPagination( $this->getTotal(), $this->getState( 'limitstart' ), $this->getState( 'limit' ) );
		}
		$this->_pagination->html		=	( $this->_pagination->get( 'pages.total' ) > 1 ) ? $this->_pagination->getPagesLinks() : '';
		$this->_pagination->class_sfx	=	$class_sfx;

		return $this->_pagination;
	}
	 
}
?>