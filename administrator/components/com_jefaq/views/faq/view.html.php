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

		if($this->_layout == 'form') {
			$faqRecord =& $this->get('Data');

			$text = $faqRecord->id ? JText::_( 'JE_FAQ_EDIT' ) : JText::_( 'JE_FAQ_NEW' );
			JToolBarHelper::title(   JText::_( 'JE_FAQ' ).': <small><small>[ ' . $text.' ]</small></small>' ,'faq.png');
			JToolBarHelper::apply();
			JToolBarHelper::save();
			if ($faqRecord->id)	{
				JToolBarHelper::cancel( 'cancel', 'Close' );
			} else	{
				JToolBarHelper::cancel();
			}

			$this->assignRef('row',	        $faqRecord);
		} else {
			JToolBarHelper::title(   JText::_( 'JE_FAQ' ) . ' - ' .  JText::_( 'JE_FAQ_COM' ), 'faq.png' );
			JToolBarHelper::publish();
			JToolBarHelper::unpublish();
			JToolBarHelper::deleteListX(JText::_( 'JE_WARN_DELETE' ));
			JToolBarHelper::editListX();
			JToolBarHelper::addNewX();
			JToolBarHelper::custom('help_faq','help', '',JText::_( 'JE_HELP' ), false);

			$context			= 'com_jefaq.answers.list.';

			$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		's.ordering',	'cmd' );
			$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',			'word' );
			$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',			'word' );
			$search				= $mainframe->getUserStateFromRequest( $context.'search',			'search',			'',			'string' );

			$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
			$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );

			$where = array();

			if ( $filter_state ) {
				if ( $filter_state == 'P' ) {
					$where[] = 's.state = 1';
				} else if ($filter_state == 'U' ) {
					$where[] = 's.state = 0';
				}
			}

			if ($search) {
				$where[] = 'LOWER(s.questions) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );

			}

			$where		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
			$orderby	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', s.id';

			// get the total number of records
			$query = 'SELECT count(*) FROM #__je_faq AS s '
				. $where
				;
			$db->setQuery( $query );
			if (!$db->query()) {
				echo $db->getErrorMsg();
			}

			$total = $db->loadResult();

			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $total, $limitstart, $limit );

			$query = 'SELECT s.* FROM #__je_faq AS s '
			. $where
			. $orderby
			;

			$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
			$rows = $db->loadObjectList();

			// state filter
			$lists['state']	= JHTML::_('grid.state',  $filter_state );

			// table ordering
			$lists['order_Dir']	= $filter_order_Dir;
			$lists['order']		= $filter_order;

			// search filter
			$lists['search']= $search;

			$this->assignRef('items',		$rows);
			$this->assignRef('pageNav',		$pageNav);
			$this->assignRef('lists',		$lists);
		}

		parent::display($tpl);
	}
}
