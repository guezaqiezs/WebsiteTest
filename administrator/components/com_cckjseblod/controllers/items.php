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

jimport( 'joomla.application.component.controller' );

/**
 * Content Items	Controller Class
 **/
class CCKjSeblodControllerItems extends CCKjSeblodController
{
	/**
	 * Vars
	 **/
	var $_isAuth = null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		// Register Extra Tasks
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'copy', 'edit' );
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'recall', 'save' );
		$this->registerTask( 'create', 'edit' );
		$this->registerTask( 'reserved', 'display' );
		
		// Check User Auth
		$user 	=& JFactory::getUser();
		$isAuth = ( $user->get( 'gid' ) < _VIEW_ACCESS ) ? 0 : 1;
		$this->_setValues( $isAuth );
	}
	
	/**
	 * Set Values 
	 **/
	function _setValues( $isAuth )
	{
		// Set Values
		$this->_isAuth	= $isAuth;
	}
	
	/**
	 * Display Default View
	 **/
	function display()
	{
		global $mainframe;
		
		// Check User Authorization
		if ( ! $this->_isAuth ) {
			$mainframe->redirect( _LINK_CCKJSEBLOD, JText::_( 'Alertnotauth' ), 'error' );
		}
		
		switch( $this->getTask() ) {
			case 'select':
				JRequest::setVar( 'view', 'items' );
				JRequest::setVar( 'layout', 'select' );
				JRequest::setVar( 'doReserved', false );
				break;
			case 'reserved':
				JRequest::setVar( 'view', 'items' );
				JRequest::setVar( 'layout', 'reserved' );
				JRequest::setVar( 'doReserved', true );
				break;
			default:
				// Set Default View
				$view = JRequest::getCmd( 'view' );
				if ( empty( $view ) ) {
					JRequest::setVar( 'view', 'items' );
					JRequest::setVar( 'layout', 'default' );
					JRequest::setVar( 'doReserved', false );
				}
				break;
		}
		
		parent::display();
	}
	
	/**	FROM 1ST ( View = Items ) **/
	/**
	 * Display Edit Form
	 **/
	function edit()
	{	
		global $mainframe;
		
		// Check User Authorization;
		if ( ! $this->_isAuth ) {
			$mainframe->redirect( _LINK_CCKJSEBLOD, JText::_( 'Alertnotauth' ), 'error' );
		}
		
		switch( $this->getTask() ) {
			case 'add':
			case 'edit':
				JRequest::setVar( 'view', 'item' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				break;
			case 'copy':
				JRequest::setVar( 'view', 'item' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', true );
				break;
			case 'create':
				JRequest::setVar( 'view', 'item' );
				JRequest::setVar( 'layout', 'create' );
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'doCopy', false );
				break;
			default:
				break;
		}

		$layout = ( @$layout == 'create' ) ? 'create' : JRequest::getWord( 'layout' );
		switch( $layout ) {
			case 'joomla_content':
				JRequest::setVar( 'layout', 'form_joomla_content' );
				break;
			case 'joomla_readmore':
				JRequest::setVar( 'layout', 'form_joomla_readmore' );
				break;
			case 'external_article':
				JRequest::setVar( 'layout', 'form_external_article' );
				break;
			case 'external_subcategories':
				JRequest::setVar( 'layout', 'form_external_subcategories' );
				break;
			case 'query_url':
			  	JRequest::setVar( 'layout', 'form_query_url' );
				break;
			case 'query_user':
			  JRequest::setVar( 'layout', 'form_query_user' );
				break;
			case 'joomla_module':
			  	JRequest::setVar( 'layout', 'form_joomla_module' );
				break;
			case 'joomla_plugin_button':
			  	JRequest::setVar( 'layout', 'form_joomla_plugin_button' );
				break;
			case 'joomla_plugin_content':
			  	JRequest::setVar( 'layout', 'form_joomla_plugin_content' );
				break;
			case 'folder':
			 	JRequest::setVar( 'layout', 'form_folder' );
				break;
			case 'media':
				JRequest::setVar( 'layout', 'form_media' );
				break;
			case 'file':
			  JRequest::setVar( 'layout', 'form_file' );
				break;
			case 'upload_image':
			  	JRequest::setVar( 'layout', 'form_upload_image' );
				break;
			case 'upload_simple':
			  	JRequest::setVar( 'layout', 'form_upload_simple' );
				break;
			case 'checkbox':
				JRequest::setVar( 'layout', 'form_checkbox' );
				break;
			case 'hidden':
				JRequest::setVar( 'layout', 'form_hidden' );
				break;
			case 'radio':
			  JRequest::setVar( 'layout', 'form_radio' );
				break;
			case 'text':
				JRequest::setVar( 'layout', 'form_text' );
				break;
			case 'select_dynamic':
				JRequest::setVar( 'layout', 'form_select_dynamic' );
				break;
			case 'select_multiple':
				JRequest::setVar( 'layout', 'form_select_multiple' );
				break;
			case 'select_numeric':
				JRequest::setVar( 'layout', 'form_select_numeric' );
				break;
			case 'select_simple':
				JRequest::setVar( 'layout', 'form_select_simple' );
				break;
			case 'textarea':
				JRequest::setVar( 'layout', 'form_textarea' );
				break;
			case 'wysiwyg_editor':
				JRequest::setVar( 'layout', 'form_wysiwyg_editor' );
				break;
			case 'form_action':
				JRequest::setVar( 'layout', 'form_form_action' );
				break;
			case 'captcha_image':
				JRequest::setVar( 'layout', 'form_captcha_image' );
				break;
			case 'email':
				JRequest::setVar( 'layout', 'form_email' );
				break;
			case 'save':
				JRequest::setVar( 'layout', 'form_save' );
				break;
			case 'password':
				JRequest::setVar( 'layout', 'form_password' );
				break;
			case 'button_free':
				JRequest::setVar( 'layout', 'form_button_free' );
				break;
			case 'button_reset':
				JRequest::setVar( 'layout', 'form_button_reset' );
				break;
			case 'button_submit':
				JRequest::setVar( 'layout', 'form_button_submit' );
				break;
			case 'free_code':
				JRequest::setVar( 'layout', 'form_free_code' );
				break;
			case 'free_text':
				JRequest::setVar( 'layout', 'form_free_text' );
				break;
			case 'alias':
				JRequest::setVar( 'layout', 'form_alias' );
				break;
			case 'alias_custom':
				JRequest::setVar( 'layout', 'form_alias_custom' );
				break;
			case 'ecommerce_cart':
				JRequest::setVar( 'layout', 'form_ecommerce_cart' );
				break;
			case 'ecommerce_cart_button':
				JRequest::setVar( 'layout', 'form_ecommerce_cart_button' );
				break;
			case 'ecommerce_price':
				JRequest::setVar( 'layout', 'form_ecommerce_price' );
				break;
			case 'web_service':
				JRequest::setVar( 'layout', 'form_web_service' );
				break;
			case 'content_type':
				JRequest::setVar( 'layout', 'form_content_type' );
				break;
			case 'field_x':
				JRequest::setVar( 'layout', 'form_field_x' );
				break;
			case 'panel_slider':
				JRequest::setVar( 'layout', 'form_panel_slider' );
				break;
			case 'sub_panel_tab':
				JRequest::setVar( 'layout', 'form_sub_panel_tab' );
				break;
			//case 'export':
			//	JRequest::setVar( 'layout', 'form_export' );
			//	break;
			case 'joomla_menu':
				JRequest::setVar( 'layout', 'form_joomla_menu' );
				break;
			case 'color_picker':
				JRequest::setVar( 'layout', 'form_color_picker' );
				break;
			case 'calendar':
				JRequest::setVar( 'layout', 'form_calendar' );
				break;
			case 'search_action':
				JRequest::setVar( 'layout', 'form_search_action' );
				break;
			case 'search_generic':
				JRequest::setVar( 'layout', 'form_search_generic' );
				break;
			case 'search_multiple':
				JRequest::setVar( 'layout', 'form_search_multiple' );
				break;
			case 'search_operator':
				JRequest::setVar( 'layout', 'form_search_operator' );
				break;
			case 'joomla_user':
				JRequest::setVar( 'layout', 'form_joomla_user' );
				break;
			case 'default':
				JRequest::setVar( 'layout', 'create_default' );
				break;
			case 'create':
				break;
			default:
				JRequest::setVar( 'layout', 'form' );
				break;
		}
		
		parent::display();
	}
	
	/**
	 * Remove && Redirect
	 **/
	function remove()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'item' );
		
		if ( $total = $model->delete() ) {
			$msg = JText::sprintf( 'Items Removed', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_ITEMS;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Categories Redirection
	 **/
	function categories()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link = _LINK_CCKJSEBLOD_ITEMS_CATEGORIES;
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Add Category Redirection
	 **/
	function addcategory()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link = _LINK_CCKJSEBLOD_ITEMS_CATEGORIES . '&task=add';
		
		$this->setRedirect( $link );
	}

	/**
	 * Save Order
	 **/	
	/*function saveOrder()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
				
		$model = $this->getModel( 'item' );

		//if ( $total = $model->batchCategory() ) {
			//$msg = JText::sprintf( 'ITEMS UPDATED', $total );
			//$msgType = 'message';
			$cids =	JRequest::getVar( 'cid', array(0), 'post', 'array' );
			$total = count( $cids );
			$msg = JText::sprintf( 'ITEMS UPDATED', $total );
			$msgType = 'notice';			
		//} else {
			//$msg = JText::_( 'An error has occurred' );
			//$msgType = 'error';
		//}
		
		$link = _LINK_CCKJSEBLOD_ITEMS;
		
		$this->setRedirect( $link, $msg, $msgType );
	}*/
	
	/**
	 * Batch Category Process
	 **/
	function batchCategory()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
				
		$model = $this->getModel( 'item' );

		if ( $total = $model->batchCategory() ) {
			$msg = JText::sprintf( 'ITEMS UPDATED', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_ITEMS;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Batch Copy Process
	 **/
	function batchCopy()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
				
		$model = $this->getModel( 'item' );

		if ( $total = $model->batchCopy() ) {
			$msg = JText::sprintf( 'ITEMS COPIED', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_ITEMS;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Batch Content Type Process
	 **/
	/*function batchContentType()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
				
		$model = $this->getModel( 'item' );

		if ( $total = $model->batchContentType() ) {
			$msg = JText::sprintf( 'ITEMS UPDATED', $total );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_ITEMS;
		
		$this->setRedirect( $link, $msg, $msgType );
	}*/
	
	/**	FROM 2ND ( View = Item ) **/
	/**
	 * Save && Redirect
	 **/
	function save()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'item' );

		if ( $rowId = $model->store() ) {
			$msg = JText::_( 'Item Saved' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		// Checkin!
		$model->checkin();
		switch( $this->getTask() ) {
			case 'apply':
				$link = ( $rowId ) ? _LINK_CCKJSEBLOD_ITEMS.'&task=edit&cid[]='.$rowId : _LINK_CCKJSEBLOD_ITEMS;
				break;
			case 'recall':
				$data 	= JRequest::get( 'post' );
				$assign	=	$data['assign'];
				$new_f	=	( $data['new_f'] == 1 ) ? $data['new_f'] : 2;
				$link	=	( $rowId ) ? _LINK_CCKJSEBLOD_ITEMS.'&task=create&cid[]='.$rowId.'&assign='.$assign.'&new_f='.$new_f.'&tmpl=component' : _LINK_CCKJSEBLOD_ITEMS.'&task=create&assign='.$assign.'&tmpl=component';
				break;
			case 'save':
				$link = _LINK_CCKJSEBLOD_ITEMS;
				break;
			default:
				break;
		}
		
		if ( $this->getTask() == 'recall' ) {
			$this->setRedirect( $link );
		} else {
			$this->setRedirect( $link, $msg, $msgType );
		}
	}
	
	/**
	 * Save Reserved && Redirect
	 **/
	function reserved_save()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'item' );

		if ( $res = $model->reserved_store() ) {
			$msg = JText::_( 'Item Saved' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		// Checkin!
		//$model->checkin();
		
		$link = _LINK_CCKJSEBLOD_ITEMS.'&task=reserved&close=1&tmpl=component';
		
		$this->setRedirect( $link );
	}
	
	/**
	 * Live Save && Redirect
	 **/
	function liveSave()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'item' );
		
		if ( $rowId = $model->liveStore() ) {
			//$msg = JText::_( 'Item Saved' );
			//$msgType = 'message';
		} else {
			//$msg = JText::_( 'An error has occurred' );
			//$msgType = 'error';
		}
		
		$link = _LINK_CCKJSEBLOD_ITEMS;
		
		$this->setRedirect( $link );	//$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Back
	 **/
	function back()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$link = _LINK_CCKJSEBLOD_TYPES;
		
		$this->setRedirect( $link );
	}

	/**
	 * Cancel && Redirect
	 **/ 
	function cancel()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'item' );
		
		// Checkin!
		$model->checkin();
		
		$link = _LINK_CCKJSEBLOD_ITEMS;
		
		$this->setRedirect( $link );
	}
	
	/**	FROM 2ND ( View = Item ) AJAX CALL **/
	function getTableFields() {
    // Get the value of the table variable, don't forget to cast its type (cmd should do here)
        if( $table = JRequest::getVar( 'table', '', 'get', 'cmd' ) ) {
            $db =& JFactory::getDBO();
            // Grab the fields for the selected table
            $fields =& $db->getTableFields( $table, true );
            if( sizeof( $fields[$table] ) ) {
				$list = null;
                // We found some fields so let's create the HTML list
                $options = array();
                foreach( $fields[$table] as $field => $type ) {
                    $options[] = JHTML::_( 'select.option', $field, $field );
                }
				$request = JRequest::getVar( 'req', '', 'get', 'cmd' );
				if ( $request == 1 ) {
					$list =& JHTML::_( 'select.genericlist', $options, 'db_fields', 'class="required required-enable"', 'value', 'text', '', 'db_fields' );
				} else if ( $request == 2 ) {
					$list =& JHTML::_( 'select.genericlist', $options, 'db_secondfields', 'class="required required-enable"', 'value', 'text', '', 'db_secondfields' );
				} else if ( $request == 4 ) {
					$list =& JHTML::_( 'select.genericlist', $options, 'db_fourthfields', 'class="required required-enable"', 'value', 'text', '', 'db_fourthfields' );
				} else if ( $request == 5 ) {
					$list =& JHTML::_( 'select.genericlist', $options, 'db_fifthfields', 'class="required required-enable"', 'value', 'text', '', 'db_fifthfields' );
				} else if ( $request == 3 ) {
					$list =& JHTML::_( 'select.genericlist', $options, 'db_thirdfields', 'class="required required-enable"', 'value', 'text', '', 'db_thirdfields' );
				}
                // Remember that this is the same as a normal request, so displaying means echo, not return the list
                echo $list;
                // Return to keep the application from going anywhere else
                return;
            }
        } else {
            echo JText::_( 'No table selected' );
        }
    }
	
	/**
	 * Check Availability [ Ajax ]
	 **/
	function checkAvailability()
	{
		$available	=	0;
		$total		=	0;
		
		if ( $available = JRequest::getVar( 'available', '', 'get', 'string' ) ) {
			$db	=& JFactory::getDBO();
			$where 		= ' WHERE s.name = "'.$available.'" OR sc.name = "'.$available.'"';
			
			$query = ' SELECT COUNT( s.id ) AS used, COUNT( sc.id ) AS reserved'
				   . ' FROM #__jseblod_cck_items AS s, #__jseblod_cck_items_reserved AS sc'
				   . $where
				   ;
			$db->setQuery( $query );
			$items	=	$db->loadObject();
			$total	=	$items->used + $items->reserved;
		} 
		
		echo $total;
	}
	
	/**
	 * Add into Pack
	 **/
	function addIntoPack()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'item' );
		
		if ( $model->addIntoPack() ) {
			$msg = JText::_( 'ELEMENTS ADDED' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
		}
		
		$redirect	=	JRequest::getVar( 'add_redirection' );
		$link		=	$redirect ? _LINK_CCKJSEBLOD_PACKS : _LINK_CCKJSEBLOD_ITEMS;
		
		$this->setRedirect( $link, $msg, $msgType );
	}
	
	/**
	 * Export Xml
	 **/
	function exportXml()
	{
		// Check for Request Forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel( 'item' );
		
		if ( $file = $model->exportXml() ) {
			$this->setRedirect( 'components/com_cckjseblod/download.php?file='.$file );
			$msg = JText::_( 'ITEMS EXPORTED' );
			$msgType = 'message';
		} else {
			$msg = JText::_( 'An error has occurred' );
			$msgType = 'error';
			$link = _LINK_CCKJSEBLOD_ITEMS;
			$this->setRedirect( $link, $msg, $msgType );
		}
		
		//$link = _LINK_CCKJSEBLOD_ITEMS;
		
		//$this->setRedirect( $link, $msg, $msgType );
	}
	
}
?>