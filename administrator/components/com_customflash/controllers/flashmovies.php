<?php
/**
 * CustomFlash Joomla! 1.5 Native Component
 * @version 1.2.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');


class CustomFlashControllerFlashMovies extends JController
{
	/**
	 * New option item wizard
	 */
	function display()
	{
		JRequest::setVar( 'view', 'flashmovies');
		
		parent::display();
	}


	function newItem()
	{
		JRequest::setVar( 'view', 'flashmovieedit');
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	function edit()
	{
		
		JRequest::setVar( 'view', 'flashmovieedit');
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	/**
	 * Saves a option item
	 */
	
	function save()
	{
		// get our model
		$model = &$this->getModel('flashmovieedit');
		// attempt to store, update user accordingly
		
		if($this->_task == 'save')
		{
			$link 	= 'index.php?option=com_customflash&controller=flashmovies';
		}
		
		
		if ($model->store())
		{
			$msg = JText::_( 'MOVIE SAVED SUCCESSFULLY' );
			$this->setRedirect($link, $msg);
		}
		else
		{
			$msg = JText::_( 'MOVIE WAS UNABLE TO SAVE');
			$this->setRedirect($link, $msg, 'error');
		}
			
	}

	/**
	* Cancels an edit operation
	*/
	function cancelItem()
	{
		global $mainframe;

		$model = $this->getModel('item');
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_customflash&controller=flashmovies');
	}

	/**
	* Cancels an edit operation
	*/
	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_customflash&controller=flashmovies');
	}

	/**
	* Form for copying item(s) to a specific option
	*/
	

	



	function remove()
	{
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		
		$model =& $this->getModel('flashmovieedit');
		
		$model->ConfirmRemove();
	}
	
	function remove_confirmed()
	{
		

		// Get some variables from the request
		
		$cid	= JRequest::getVar( 'cid', array(), 'get', 'array' );
		JArrayHelper::toInteger($cid);

		if (!count($cid)) {
			$this->setRedirect( 'index.php?option=com_customflash&controller=flashmovies', JText::_('NO FLASH MOVIES SELECTED') );
			return false;
		}

		$model =& $this->getModel('flashmovieedit');
		if ($n = $model->delete($cid)) {
			$msg = JText::sprintf( 'MOVIE_S DELETED', $n );
			$this->setRedirect( 'index.php?option=com_customflash&controller=flashmovies', $msg );
		} else {
			$msg = $model->getError();
			$this->setRedirect( 'index.php?option=com_customflash&controller=flashmovies', $msg,'error' );
		}
		
	}

	
	function copyItem()
	{
	    $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
	    
	    
	    
	    $model = $this->getModel('flashmovieedit');
	    
	    
	    if($model->copyItem($cid))
	    {
			$msg = JText::_( 'MOVIE_S COPIED SUCCESSFULLY' );
			$link 	= 'index.php?option=com_customflash&controller=flashmovies';
			$this->setRedirect($link, $msg);
	    }
	    else
	    {
			$msg = JText::_( 'MOVIE_S WAS UNABLE TO COPY' );
			$link 	= 'index.php?option=com_customflash&controller=flashmovies';
			$this->setRedirect($link, $msg,'error');
	    }
	    
	    
	    
	}


	
}
