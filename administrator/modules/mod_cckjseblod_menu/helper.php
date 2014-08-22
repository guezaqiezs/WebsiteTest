<?php
/*
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright  		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
*/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( dirname( __FILE__ ).DS.'cckjseblod_menu.php' );

class modCCKMenuHelper
{
	/**
	 * Show the menu
	 * @param string The current user type
	 */
	function buildMenu( $mode, $menutitle, $moduleid, $modenew, $modecat, $modesitemenu, $modesitemodule, $modeexternal, $addons, $com )
	{
		global $mainframe;

		$lang		=&	JFactory::getLanguage();
		$user		=&	JFactory::getUser();
		$db			=&	JFactory::getDBO();
		$usertype	=	$user->get( 'usertype' );

		/*
		 * Get the menu object
		 */
		$menu	=	new JAdminCSSCCKMenu();

		/*
		 * Content SubMenu
		 */
		$jseblodcck_label	=	( $menutitle != '' ) ? str_replace('&nbsp;', '', $menutitle) : 'CCK';
		if ( $mode ) {
			$cckadmin_label		=	JText::_( 'CCK ADMIN' );
			$menu->addChild( new JCCKMenuNode( $jseblodcck_label ), true );
			$menu->addChild( new JCCKMenuNode( JText::_( 'ADD NEW CONTENT' ), 'index.php?option=com_cckjseblod&controller=interface&act=-1&cck=1', 'class:content-manager' ) );
			//
			if ( $mode == 2 ) {
				$menu->addSeparator();
				//
				if ( $modenew || $modecat ) {
					$menu->addChild( new JCCKMenuNode( JText::_( 'TEMPLATE MANAGER' ), 'index.php?option=com_cckjseblod&controller=templates', 'class:template-manager' ), true );
					if ( $modenew ) {
						$menu->addChild( new JCCKMenuNode( JText::_( 'NEW' ), 'index.php?option=com_cckjseblod&controller=templates&task=add', 'class:j_arrow_red' ) );
					}
					if ( $modecat ) {
						$menu->addChild( new JCCKMenuNode( JText::_( 'CATEGORIES' ), 'index.php?option=com_cckjseblod&controller=templates_categories', 'class:j_arrow_red' ) );
					}
					$menu->getParent();
				} else {
					$menu->addChild( new JCCKMenuNode( JText::_( 'TEMPLATE MANAGER' ), 'index.php?option=com_cckjseblod&controller=templates', 'class:template-manager' ) );
				}
				//
				if ( $modenew || $modecat ) {
					$menu->addChild( new JCCKMenuNode( JText::_( 'CONTENT TYPE MANAGER' ), 'index.php?option=com_cckjseblod&controller=types', 'class:type-manager' ), true );
					if ( $modenew ) {
						$menu->addChild( new JCCKMenuNode( JText::_( 'NEW' ), 'index.php?option=com_cckjseblod&controller=types&task=add', 'class:j_arrow_red' ) );
					}
					if ( $modecat ) {
						$menu->addChild( new JCCKMenuNode( JText::_( 'CATEGORIES' ), 'index.php?option=com_cckjseblod&controller=types_categories', 'class:j_arrow_red' ) );
					}
					$menu->getParent();
				} else {
					$menu->addChild( new JCCKMenuNode( JText::_( 'CONTENT TYPE MANAGER' ), 'index.php?option=com_cckjseblod&controller=types', 'class:type-manager' ) );
				}
				//
				if ( $modenew || $modecat ) {
					$menu->addChild( new JCCKMenuNode( JText::_( 'FIELD MANAGER' ), 'index.php?option=com_cckjseblod&controller=items', 'class:item-manager' ), true );
					if ( $modenew ) {
						$menu->addChild( new JCCKMenuNode( JText::_( 'NEW' ), 'index.php?option=com_cckjseblod&controller=items&task=add', 'class:j_arrow_red' ) );
					}
					if ( $modecat ) {
						$menu->addChild( new JCCKMenuNode( JText::_( 'CATEGORIES' ), 'index.php?option=com_cckjseblod&controller=items_categories', 'class:j_arrow_red' ) );
					}
					$menu->getParent();
				} else {
					$menu->addChild( new JCCKMenuNode( JText::_( 'FIELD MANAGER' ), 'index.php?option=com_cckjseblod&controller=items', 'class:item-manager' ) );
				}
				//
				if ( $modenew || $modecat ) {
					$menu->addChild( new JCCKMenuNode( JText::_( 'SEARCH TYPE MANAGER' ), 'index.php?option=com_cckjseblod&controller=searchs', 'class:search-manager' ), true );
					if ( $modenew ) {
						$menu->addChild( new JCCKMenuNode( JText::_( 'NEW' ), 'index.php?option=com_cckjseblod&controller=searchs&task=add', 'class:j_arrow_red' ) );
					}
					if ( $modecat ) {
						$menu->addChild( new JCCKMenuNode( JText::_( 'CATEGORIES' ), 'index.php?option=com_cckjseblod&controller=searchs_categories', 'class:j_arrow_red' ) );
					}
					$menu->getParent();
				} else {
					$menu->addChild( new JCCKMenuNode( JText::_( 'SEARCH TYPE MANAGER' ), 'index.php?option=com_cckjseblod&controller=searchs', 'class:search-manager' ) );
				}
				//
				$menu->addChild( new JCCKMenuNode( JText::_( 'PACK MANAGER' ), 'index.php?option=com_cckjseblod&controller=packs', 'class:pack-manager' ) );
				
			}
			//
			if ( $modesitemenu || $modesitemodule ) {
				$menu->addChild( new JCCKMenuNode( $cckadmin_label, 'index.php?option=com_cckjseblod', 'class:jseblod' ), true );
				if ( $modesitemenu ) {
					$menu->addChild( new JCCKMenuNode( JText::_( 'NEW MENU' ),
									'index.php?option=com_menus&task=type&menutype=mainmenu&cid[]=&expand=cckjseblod', 'class:j_arrow_red' ) );
				}
				if ( $modesitemenu && $modesitemodule ) {
					$menu->addSeparator();
				}
				if ( $modesitemodule ) {
					$menu->addChild( new JCCKMenuNode( JText::_( 'NEW MODULE' ), 'index.php?option=com_modules', 'class:j_arrow_red' ) );
				}
				$menu->getParent();
			} else {
				$menu->addChild( new JCCKMenuNode( $cckadmin_label, 'index.php?option=com_cckjseblod', 'class:cckjseblod' ) );
			}
			if ( $addons['webservice'] ) {
				$menu->addSeparator();
				if ( $addons['webservice'] == 1 || $addons['webservice'] == 3 ) {
					$menu->addChild( new JCCKMenuNode( JText::_( 'WEBSERVICE ADDON' ), 'index.php?option=com_cckjseblod_webservice', 'class:webservice-addon' ), true );
				}
				if ( $addons['webservice'] == 2 || $addons['webservice'] == 3 ) {
					$menu->addChild( new JCCKMenuNode( JText::_( 'WEBSERVICE MANAGER' ), 'index.php?option=com_cckjseblod_webservice&controller=webservices', 'class:webservice-manager' ), true );
					$menu->getParent();
					$menu->addChild( new JCCKMenuNode( JText::_( 'TASK MANAGER' ), 'index.php?option=com_cckjseblod_webservice&controller=tasks', 'class:task-manager' ), true );
					$menu->getParent();
					$menu->addChild( new JCCKMenuNode( JText::_( 'JOB MANAGER' ), 'index.php?option=com_cckjseblod_webservice&controller=jobs', 'class:job-manager' ), true );
					if ( $addons['webservice'] == 3 ) {
						$menu->getParent();
					}
				}
				$menu->getParent();
			}
			if ( $modeexternal ) {
				$menu->addSeparator();
				$menu->addChild( new JCCKMenuNode( 'SEBLOD.com', 'http://www.seblod.com', 'class:helpjseblod' ), true );
				$menu->addChild( new JCCKMenuNode( JText::_( 'DOCUMENTATION' ),
									'http://www.seblod.com/network/documentation.html', 'class:j_arrow_red' ) );
				$menu->addChild( new JCCKMenuNode( JText::_( 'FORUM' ),
									'http://www.seblod.com/network/forum.html', 'class:j_arrow_red' ) );
				$menu->getParent();
			}
			//
		} else {
			$menu->addChild( new JCCKMenuNode( $jseblodcck_label ), true );
		}
		if ( sizeof( $com ) ) {
			$menu->addSeparator();
			foreach ( $com AS $key => $item ) {
				$link		=	null;
				$link		=	explode( '||', $item );
				if ( strpos( $key, 'free' ) === false ) {
					$link[1]	=	'index.php?'.$link[1];
					$link[2]	=	( $link[2] ) ? $link[2] : 'js/ThemeOffice/component.png';
				} else {
					$link[2]	=	( $link[2] ) ? str_replace( 'icon-16-', '', $link[2] ) : 'js/ThemeOffice/component.png';
				}
				$menu->addChild( new JCCKMenuNode( JText::_( $link[0] ), $link[1], $link[2] ) );
			}
		}

		$menu->getParent();
		// TK added menutitle to menuname
		$menu->renderMenu( 'cckjseblod_menu_jseblod'.$moduleid, '' );
	}

	/**
	 * Show an disbaled version of the menu, used in edit pages
	 *
	 * @param string The current user type
	 */
	function buildDisabledMenu( $mode, $menutitle, $moduleid )
	{
		$lang	 =& JFactory::getLanguage();
		$user	 =& JFactory::getUser();
		$usertype = $user->get('usertype');

		$text	=	JText::_( 'Menu inactive for this Page', true );

		// Get the menu object
		$menu	=	new JAdminCSSCCKMenu();

		// jSeblod CCK SubMenu
		$jseblodcck_label	=	( $menutitle != '' ) ? str_replace('&nbsp;', '', $menutitle) : 'CCK';
		if ( $mode ) {
			$menu->addChild( new JCCKMenuNode( $jseblodcck_label, null, 'disabled' ) );
		} else {
			$menu->addChild( new JCCKMenuNode( $jseblodcck_label, null, 'disabled' ) );
		}
		// TK added menutitle and cckjseblod_menu class
		$menu->renderMenu( 'cckjseblod_menu_jseblod'.$moduleid, 'cckjseblod_menu disabled' );
	}
}
?>
