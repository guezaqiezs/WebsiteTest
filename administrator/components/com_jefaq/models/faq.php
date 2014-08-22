<?php
/**
 * jeFAQ package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class jefaqModelFaq extends JModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		$row =& $this->getTable();
		$row->load( $this->_id );
		return $row;
	}

	// save
	function store()
	{
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$row =& $this->getTable();
		
		// Bind the form fields
		$post	= JRequest::get('post', JREQUEST_ALLOWRAW);

		// get the next order
		if ($post[id] == '0') {
			$row->ordering = $row->getNextOrder();
		} else {
		}
		// ordering here..
		
		if (!$row->bind($post)) {
			return 0;
		}
		// Make sure data is valid
		if (!$row->check()) {
			return 0;
		}
		// Store it
		if (!$row->store())	{
			return 0;
		}
		return $row->id;
	}
	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Initialize variables
		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$result     = ($task == 'publish')?1:2;
		$neg_result     = ($task == 'publish')?-1:-2;
		$n			= count( $cid );

		if (empty( $cid )) {
			return 0;
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );

		$query = 'UPDATE #__je_faq'
		. ' SET state = ' . (int) $publish
		. ' WHERE id IN ( '. $cids.'  )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			return $neg_result;
		} else {
			return $result;
		}
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					return false;
				}
			}
		}
		return true;
	}

	// move up and down direction..
	function move($direction)
	{
		$row =& $this->getTable();

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );

		if (isset( $cid[0] ))
		{
			$row->load( (int) $cid[0] );
			$row->move($direction);

			$cache = & JFactory::getCache('com_jefaq');
			$cache->clean();
		}


		return true;
	}
	// end up and down direction..

	// function for save order
	function saveOrder()
	{
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array(), 'post', 'array' );

		$row =& $this->getTable();
		$total		= count( $cid );
		$conditions	= array();

		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}

		// update ordering values
		for ($i = 0; $i < $total; $i++)
		{
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					return JError::raiseError( 500, $db->getErrorMsg() );
				}
			}
		}

		return true;
	}
	// save order end
	
	function faqHelp()
	{
		?>
			<script>
				window.open('http://www.jextn.com/support/','_blank');
			</script>
		<?php
	}
}
?>