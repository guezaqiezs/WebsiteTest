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
 * On Prepare Registration
 **/
$user			=	clone( JFactory::getUser() );
$config			=&	JFactory::getConfig();
$authorize		=&	JFactory::getACL();
$date 			=&	JFactory::getDate();
$juser			=	JRequest::getVar( 'juser', array(), 'post', 'array');
$juserparams	=	JRequest::getVar( 'juserparams', array(), 'post', 'array');

if ( ! $cckId ) {
	$usersConfig	=	&JComponentHelper::getParams( 'com_users' );
	if ( $usersConfig->get( 'allowUserRegistration' ) == '0' && $client == 'site' ) {
		JError::raiseError( 403, JText::_( 'Access Forbidden' ) );
		return;
	}
	$newUsertype = $usersConfig->get( 'new_usertype' );
	if (!$newUsertype) {
		$newUsertype = 'Registered';
	}
	if ( $juser['password'] != $juser['password2'] ) {
		$mainframe->enqueueMessage( JText::_( 'PASSWORD VERIFICATION DOESNT MATCH' ), "error" );
		return false;
	}
	if ( ! $user->bind( $juser, 'usertype' ) ) {
		JError::raiseError( 500, $user->getError() );
	}
	if ( @$form['usertype'] ) {
		$newUsertype	=	$form['usertype'];
	}
	if ( @$juser['usertype'] ) {
		$newUsertype	=	$juser['usertype'];
	}
	$user->set( 'id', 0 );
	$user->set( 'usertype', $newUsertype );
	$user->set( 'gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ) );
	$user->set( 'registerDate', $date->toMySQL() );
	if ( $form['useractivation'] )
	{
		$user->set( 'block', '1' );
		jimport( 'joomla.user.helper' );
		$user->set( 'activation', JUtility::getHash( JUserHelper::genRandomPassword()) );
		$activationCode	=	$user->activation;
		//if ( $form['useractivation'] == 2 ) {
			//sendEmail
		//}
	}
	if ( @$juser['block'] != '' ) {
		$user->set( 'block', $juser['block'] );
	}
	if ( @$juser['sendemail'] != '' ) {
		$user->set( 'sendEmail', $juser['sendemail'] );
	} else {
		$user->set( 'sendEmail', 0 );
	}
	// Save
	if ( ! $user->save() )
	{
		JError::raiseWarning('', JText::_( $user->getError()));
		return false;
	}
	// Params
	if ( is_array( $juserparams ) )
	{
		$attribs	=	array();
		foreach ( $juserparams as $k => $v ) {
			$attribs[]	=	"$k=$v";
		}
		$userParams	=	implode( "\n", $attribs );
	}
	$query = 'UPDATE #__users'
		   . ' SET params = "'.$userParams.'"'
		   . ' WHERE id ='.(int)$user->id
		   ;
	$this->_db->setQuery( $query );
	$this->_db->query();
	
	$password	=	JRequest::getString( 'password', '', 'post', JREQUEST_ALLOWRAW );
	$password	=	preg_replace( '/[\x00-\x1F\x7F]/', '', $password );
	
	/*
	if ( $useractivation == 1 ) {
		$message  = JText::_( 'REG_COMPLETE_ACTIVATE' );
	} else {
		$message = JText::_( 'REG_COMPLETE' );
	}		
	*/
} else {
	$userId	=	HelperjSeblod_Helper::getCCKUser( 'userid', 'contentid', $cckId );
	$user->load( $userId );
	if ( $juser['password'] == 'XXXX' ) {
		$juser['password'] = '';
	}
	$user->bind( $juser );
	$user->sendEmail = ( @$juser['sendemail'] != '' ? $juser['sendemail'] : $user->sendEmail );
	$user->set( 'gid', $authorize->get_group_id( '', ( $user->usertype ), 'ARO' ) );	
	// Save
	if ( ! $user->save() )
	{
		JError::raiseWarning('', JText::_( $user->getError()));
		return false;
	}
	// Params
	if ( is_array( $juserparams ) )
	{
		$attribs	=	array();
		foreach ( $juserparams as $k => $v ) {
			$attribs[]	=	"$k=$v";
		}
		$userParams	=	implode( "\n", $attribs );
	}
	$query = 'UPDATE #__users'
		   . ' SET params = "'.$userParams.'"'
		   . ' WHERE id ='.(int)$user->id
		   ;
	$this->_db->setQuery( $query );
	$this->_db->query();
}

$newUser['userid']		=	$user->id;
?>