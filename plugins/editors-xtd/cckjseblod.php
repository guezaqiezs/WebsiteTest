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

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.application.helper' );

/**
 * CCKjSeblod		Editor Button Plugin
 **/
class plgButtonCCKjSeblod extends JPlugin
{
	/**
	 * Constructor
	 **/
	function plgButtonCCKjSeblod( & $subject, $config )
	{
		parent::__construct( $subject, $config );
	}
	
	/**
	 * Display Button
	 **/
	function onDisplay( $name )
	{
		global $mainframe, $option;
		
		$task		=	JRequest::getVar( 'task' ); //+ var u_ctrl = "'.$controller.'";
		$doc 		=&	JFactory::getDocument();
		$template 	=	$mainframe->getTemplate();
		$client		=&	JApplicationHelper::getClientInfo( $mainframe->getClientId() );
		
		if ( $mainframe->isSite() ) {
			return;
		}
		
		// Get Config
		$config 	=&	CCK::CORE_getConfig();

		$width 		=	( $config->modal_width ) ? $config->modal_width : 900;
		$height 	=	( $config->modal_height ) ? $config->modal_height : 540;
		
		$array	=	JRequest::getVar( 'cid',  0, '', 'array' );
		$cid	=	( $option == 'com_content' || $option == 'com_categories' ) ? (int)$array[0] : -1;
		switch ( $option ) {
			case 'com_categories':
				$act	=	1;
				break;
			case 'com_content':
				$act	=	-2;
				break;
			default:
				$act	=	3;
				break;
		}
		
		if ( ! @$js ) {
			$js = '
				function openContentInterface(e_name) {
					window.addEvent("domready",function(){
						var artid = '.$cid.';
						var act = '.$act.';
						var u_opt = "'.$option.'";
						var u_task = "'.$task.'";
if(artid==-1){if(this.JContentEditor || this.tinyMCE){var e_content=tinyMCE.get(e_name).getContent();}else{var e_content=($(e_name))?$(e_name).value:"";}if(e_content){var e_match=e_content.match(/::jseblod::(.+?)::\/jseblod::/);if(e_match){var e_type=(e_match[1])?e_match[1]:""}else{var e_type=""}}else{var e_type=""}var url="index.php?option=com_cckjseblod&controller=interface&artid="+artid+"&e_type="+e_type+"&e_name="+e_name+"&u_opt="+u_opt+"&u_task="+u_task+"&act="+act+"&tmpl=component"}else{if($("catid")){var cat_id=$("catid").value}var url="index.php?option=com_cckjseblod&controller=interface&artid="+artid+"&cat_id="+cat_id+"&e_name="+e_name+"&u_opt="+u_opt+"&u_task="+u_task+"&act="+act+"&tmpl=component"}SqueezeBox.fromElement(url,{handler:"iframe",size:{x:'.$width.',y:'.$height.'},closeWithOverlay:false});
					});
				}
			';
		}
		
		$doc->addScriptDeclaration( $js );
					
		$path	=	( $client->id ) ? 'components/com_cckjseblod/assets/images/jseblod/j_button2_jseblod.png' :  	
									  'administrator/components/com_cckjseblod/assets/images/jseblod/j_button2_jseblod.png';
        $css	=	'.button2-left .jseblod { background: url( '.$path.') 100% 0 no-repeat; }';
        $doc->addStyleDeclaration( $css );
		
		$button	=	new JObject();
		$button->set( 'modal', false );
		$button->set( 'onclick', 'openContentInterface(\''.$name.'\');return false;' );
		$button->set( 'text', JText::_( 'Content Manager' ) );
		$button->set( 'name', 'jseblod' );
		$button->set( 'link', '#' );		//$button->set( 'link', 'javascript:void(0)' );
		
		return $button;
	}
}
?>