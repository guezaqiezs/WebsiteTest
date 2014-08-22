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

jimport( 'joomla.application.component.view' );

jimport('joomla.html.pane');

class  jefaqViewFaq  extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		$db =& JFactory::getDBO();

		$model = & $this->getModel();

		if($this->_layout == 'details')	{
			$faqRecord =& $this->get('Data');
			$this->assignRef('row',	$faqRecord);
		} else	{
			$context			= 'com_jefaq.s.id.list.';

			$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		's.ordering',	'cmd' );
			$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',			'word' );

			$where = array();

			$where[] = 's.state = 1';   // while published

			$where		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
			$orderby	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', s.id';

			// get the total number of records
			$query = 'SELECT count(*) FROM #__je_faq AS s '
				. $where
				;
			$db->setQuery( $query );

			if (!$db->query())	{
				echo $db->getErrorMsg();
			}

			$total = $db->loadResult();

			$query = 'SELECT s.* FROM #__je_faq AS s '
			. $where
			. $orderby
			;

			$db->setQuery( $query );
			$rows = $db->loadObjectList();

			// table ordering
			$lists['order_Dir']	= $filter_order_Dir;
			$lists['order']		= $filter_order;


			// Get title..
			$params		=& $mainframe->getParams('com_content');

			$this->assignRef('items',		$rows);
			$this->assignRef('lists',		$lists);
			$this->assignRef('params',		$params);
		}
		parent::display($tpl);
	}
}