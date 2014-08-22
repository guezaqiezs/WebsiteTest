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

require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'helpers'.DS.'core.cckjseblod.php' );

class plgSystemCCKjSeblod extends JPlugin
{	
	function plgSystemCCKjSeblod( & $subject, $config )
	{
		
		$this->setConfig();
		parent::__construct( $subject, $config );
	}
	
	function setConfig()
	{
		$this->_CCKConfig	=&	CCK::CORE_getConfig();
	}
	
	function onAfterDispatch()
	{
		global $mainframe, $option;
		
		if ( $mainframe->isAdmin() ) {
			return;
		}
		if ( $option == 'com_cckjseblod' || $option == 'com_dump' ) {
			return;
		}
		
		//
		$view	=	JRequest::getCmd( 'view' );
		$task	=	JRequest::getCmd( 'task' );
		$ret	=	JRequest::getCmd( 'ret' );
		$itemId	=	JRequest::getCmd( 'Itemid' );
		
		if ( ( $option == 'com_content' && $view == 'article' && $task == 'edit' ) || ( $option == 'com_content' && $task == 'edit' && $ret ) ) {
			$id			=	JRequest::getInt( 'id' );
			$introtext	=	CCK_DB_Result( 'SELECT s.introtext FROM #__content AS s WHERE id='.$id );
			$regex		=	"#::jseblod::(.*?)::/jseblod::#s";
			preg_match_all( $regex, $introtext, $contentMatches );
			$contentType	=	@$contentMatches[1][0];
			if ( $contentType ) {
				$type	=	CCK_DB_Object( 'SELECT s.id, s.sitetemplate AS templateid FROM #__jseblod_cck_types AS s WHERE s.name="'.$contentType.'"' );
			}
			if ( @$type ) {
				$type->templateid	=	( $type->templateid ) ? $type->templateid : 1;
				if ( $type->id && $type->templateid ) {
					$url	=	'index.php?option=com_cckjseblod&view=type&layout=form&typeid='.$type->id.'&templateid='.$type->templateid.'&cckid='.$id;
					if ( $itemId ) {
						$url	.=	'&Itemid='.$itemId;
					}
					$mainframe->redirect( $url );
				}
			}
		}
		//
		$document	=&	JFactory::getDocument();
		if ( $this->_CCKConfig->login_enable ) {
				$layout	=	JRequest::getCmd( 'layout' );
				$itemId	=	JRequest::getCmd( 'Itemid' );
				
				if ( $option == 'com_user' && $view == 'register' ) {	
					$url = 'index.php?option=com_cckjseblod&view=type&layout=user&typeid='.$this->_CCKConfig->login_typeid
						 .	'&templateid='.$this->_CCKConfig->login_templateid.'&Itemid='.$this->_CCKConfig->login_itemid;
					$mainframe->redirect( $url );
				}
				if ( $option == 'com_user' && $view == 'user' && $layout == 'form' ) {
					$url = 'index.php?option=com_cckjseblod&view=user&layout=form&Itemid='.$itemId;
					$mainframe->redirect( $url );
				}
			}
		//
	
		// #
		// # System Process Component
		// #
		if ( $this->_CCKConfig->system_component ) {
			$buffer		=	$document->getBuffer( 'component' );
			
			$dispatcher	=&	JDispatcher::getInstance();
			JPluginHelper::importPlugin( 'content' );
			$limitstart	=	JRequest::getVar( 'limitstart', 0, '', 'int' );

			$search		=	'#::jseblod::(.*)::/jseblodend::#sU';
			preg_match_all( $search, $buffer, $matches );
			
			if ( sizeof( $matches[1] ) ) {
				$k	=	0;
				foreach ( $matches[1] as $match ) {
					
					$requested	=	'::jseblod::'.$match.'::/jseblodend::';
					
					$i	=	0;
					$rows[$i]->id			=	0;
					$rows[$i]->text			=	$requested;
					$rows[$i]->parameters	=	new JParameter( @$rows[$i]->attribs );
					$rows[$i]->event		=	new stdClass ();
					$results	=	$dispatcher->trigger( 'onPrepareContent', array ( &$rows[$i], & $rows[$i]->parameters, $limitstart ) );
					$results	=	$dispatcher->trigger( 'onAfterDisplayTitle', array ( $rows[$i], & $rows[$i]->parameters, $limitstart ) );
					$rows[$i]->event->afterDisplayTitle = trim( implode( "\n", $results ) );
					$results	=	$dispatcher->trigger( 'onBeforeDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, $limitstart ) );
					$rows[$i]->event->beforeDisplayContent = trim( implode( "\n", $results ) );
					$results	=	$dispatcher->trigger( 'onAfterDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, $limitstart ) );
					$rows[$i]->event->afterDisplayContent = trim( implode( "\n", $results ) );
				
					$buffer	=	str_replace( '<br />::jseblod::', '::jseblod::', $buffer );		
					$buffer	=	str_replace( $matches[0][$k], $rows[$i]->text, $buffer );
					
					$document->setBuffer( $buffer, 'component' );
					$k++;
				}
			}
		}
		
		if ( $this->_CCKConfig->icon_edit ) {
			$buffer		=	$document->getBuffer( 'component' );
			$search		=	'#<a(.*)ret=(.*)(images/M_images/edit.png|images/M_images/edit_unpublished.png)(.*)</a>#U';
			preg_match_all( $search, $buffer, $matches );
			if ( sizeof( $matches[0] ) ) {
				foreach ( $matches[0] as $match ) {
					$buffer	=	str_replace( $match, '', $buffer );
				}
			}
			$document->setBuffer( $buffer, 'component' );
		}
	}
	
	function onAfterRender()
	{
		global $mainframe, $option;
				
		if ( $mainframe->isSite() ) {

			$document	=&	JFactory::getDocument();

			// #
			// # System Process Icon
			// #
				/*
				$buffer		=	JResponse::getBody();
				
				// Pdf
				$search		=	'#images/M_images/pdf_button.png#';
				$replace	=	'media/jseblod/_icons/pdf-default.png';
				$buffer		=	preg_replace( $search, $replace, $buffer );
				// Print
				$search		=	'#images/M_images/printButton.png#';
				$replace	=	'media/jseblod/_icons/print-default.png';
				$buffer		=	preg_replace( $search, $replace, $buffer );
				// Mail
				$search		=	'#images/M_images/emailButton.png#';
				$replace	=	'media/jseblod/_icons/email-default.png';
				$buffer		=	preg_replace( $search, $replace, $buffer );
				// Edit
				$search		=	'#<span class="hasTip"(.*)><a href=(.*)images/M_images/edit.png" alt="'.JText::_('Edit').'"  /></a></span>#';
				$replace	=	'<span id="editbutton_cck"><a href="javascript: cckEditContent();">'
							.	'<img src="media/jseblod/_icons/edit-default.png" alt="edit" /></a></span>';
				$buffer		=	preg_replace( $search, $replace, $buffer );
				
				JResponse::setBody( $buffer );
				*/
					
			// #
			// # System Process Login Module
			// #
			if ( $this->_CCKConfig->login_enable ) {
				$buffer		=	JResponse::getBody();
				
				$search		=	'#index.php\?option=com_user&amp;view=register#';
				$replace	=	'index.php?option=com_cckjseblod&view=type&layout=user&typeid='.$this->_CCKConfig->login_typeid
							.	'&templateid='.$this->_CCKConfig->login_templateid.'&Itemid='.$this->_CCKConfig->login_itemid;
				$buffer		=	preg_replace( $search, $replace, $buffer );		
				
				JResponse::setBody( $buffer );
			}
			
			// #
			// # System Process Modules
			// #
			if ( $this->_CCKConfig->system_modules ) {
				$itemId		=	JRequest::getCmd('Itemid');
				$itemId		=	( $itemId ) ? $itemId : 0;
				$excluded	=	'( "mod_mainmenu", "mod_login", "mod_whosonline", "mod_breadcrumbs", "mod_cckjseblod_login", "mod_cckjseblod_siteforms", "mod_cckjseblod_search", "mod_cckjseblod_list", "mod_cckjseblod_ecommerce_cart" )';
				
				$query		=	'SELECT DISTINCT cc.position FROM #__modules_menu AS s'
							.	' LEFT JOIN #__modules AS cc ON cc.id = s.moduleid'
							.	' WHERE ( s.menuid = 0 OR s.menuid = '.$itemId.' ) AND cc.published = 1 AND cc.module NOT IN '.$excluded;
				$positions	=	CCK::DB_loadResultArray( $query );

				if ( sizeof( $positions ) ) { 
					$dispatcher	=&	JDispatcher::getInstance();
					JPluginHelper::importPlugin( 'content' );
					$limitstart	=	JRequest::getVar( 'limitstart', 0, '', 'int' );
					$rmFields	=	CCK::DB_loadResultArray( 'SELECT s.name FROM #__jseblod_cck_items AS s WHERE s.type = 34' );
					
					foreach ( $positions as $pos ) {

						$body	=	JResponse::getBody();
						$buffer	=	$document->getBuffer( 'modules', $pos );

						$end		=	null;
						if ( sizeof( $rmFields ) ) {
							foreach( $rmFields as $rmField ) {
								$end	.=	'::'.$rmField.'::|';
							}
						}
						$end	=	'('.$end.'::/jseblodend::)';
						$search	=	'#::jseblod::(.*)'.$end.'#sU';
						preg_match_all( $search, $buffer, $matches );
						
						if ( sizeof( $matches[1] ) ) {
							$k	=	0;
							foreach ( $matches[1] as $match ) {
								if ( strpos( $match, '::jseblodend::' ) === false ) {
									$match	=	$match.'::jseblodend::';
								}
								$requested	=	'::jseblod::'.$match.'::/jseblodend::';
								
								$i	=	0;
								$rows[$i]->id					=	0;
								$rows[$i]->text					=	$requested;
								$rows[$i]->parameters			=	new JParameter( @$rows[$i]->attribs );
								$rows[$i]->event				=	new stdClass ();
								$rows[$i]->cckjseblod_location	=	'module';
								$results	=	$dispatcher->trigger( 'onPrepareContent', array ( &$rows[$i], & $rows[$i]->parameters, $limitstart ) );
								$results	=	$dispatcher->trigger( 'onAfterDisplayTitle', array ( $rows[$i], & $rows[$i]->parameters, $limitstart ) );
								$rows[$i]->event->afterDisplayTitle = trim( implode( "\n", $results ) );
								$results	=	$dispatcher->trigger( 'onBeforeDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, $limitstart ) );
								$rows[$i]->event->beforeDisplayContent = trim( implode( "\n", $results ) );
								$results	=	$dispatcher->trigger( 'onAfterDisplayContent', array ( & $rows[$i], & $rows[$i]->parameters, $limitstart ) );
								$rows[$i]->event->afterDisplayContent = trim( implode( "\n", $results ) );
								
								$body	=	str_replace( '<br />::jseblod::', '::jseblod::', $body );
								$body	=	str_replace( $matches[0][$k], $rows[$i]->text, $body);
								JResponse::setBody( $body );
								$k++;
							}
						}
						
					}
				}
			}
			
			// #
			// # System Process Search
			// #
			if ( $option == 'com_search' ) {
				$buffer		=	JResponse::getBody();
				$search		=	'#::([a-z0-9_]*)::#';
				$replace	=	'';
				$buffer		=	preg_replace( $search, $replace, $buffer );
				$search		=	'#::/([a-z0-9_]*)::#';
				$replace	=	'';
				$buffer		=	preg_replace( $search, $replace, $buffer );
				JResponse::setBody( $buffer );
			}
			
		} else {	
			// ## MENU:begin
			/*
			$buffer		=	JResponse::getBody();
			// eCommerce
			$search		=	'<li><a class="icon-16-icon-16-ecommerce" href="index.php?option=com_cckjseblod_ecommerce">jSeblod CCK eCommerce [Add-on]</a></li>';
			$replace	=	'';
			$buffer		=	str_replace( $search, $replace, $buffer );
			// WebService
			$search		=	'<li><a class="icon-16-icon-16-webservices" href="index.php?option=com_cckjseblod_webservice">jSeblod CCK Job & WebService [Add-on]</a></li>';
			$replace	=	'';
			$buffer		=	str_replace( $search, $replace, $buffer );
			// jSeblod CCK //TODO:check component exist or not??
			$search		=	'<li><a class="icon-16-icon-16-jseblod" href="index.php?option=com_cckjseblod">jSeblod CCK</a></li>';
			$replace	=	'<li class="node"><a class="icon-16-icon-16-jseblod" href="index.php?option=com_cckjseblod">jSeblod CCK</a>'
						.	'<ul id="menu-news-feeds" class="menu-component">'
						.	'<li><a class="icon-16-icon-16-ecommerce" href="index.php?option=com_cckjseblod_ecommerce">eCommerce [Add-on]</a></li>'
						.	'<li><a class="icon-16-icon-16-webservices" href="index.php?option=com_cckjseblod_webservice">Job & WebService [Add-on]</a></li>'
						.	'</ul></li>';
			$buffer		=	str_replace( $search, $replace, $buffer );
			JResponse::setBody( $buffer );
			*/
			// ## MENU:end
			
			$controller		=	JRequest::getCmd( 'controller' );
			if ( $option == 'com_cckjseblod' && ! $controller ) {
				//	$support	=	'href="http://extensions.joomla.org/extensions/news-production/content-construction/9128"';
				//	$buffer		=	JResponse::getBody();
				//	$buffer		=	str_replace( $support, 'target="_blank" '.$support, $buffer );
				//	JResponse::setBody( $buffer );
				return true;
			}
			
			if ( ! ( $option == 'com_content' || $option == 'com_categories' || $option == 'com_users'
					|| $option == 'com_menus' || $option == 'com_templates' || $option == 'com_media' || $option == 'com_cckjseblod' ) ) {
				return true;
			}
			$task		=	JRequest::getCmd( 'task' );
			
			if ( $task != 'add' && $task != 'edit' && $task != 'element' ) {
			
			$buffer		=	JResponse::getBody();		
			$search		=	null;
			$replace	=	null;
			$matchIds	=	null;
			
			switch ( $option ) {
				case 'com_cckjseblod':
					$controller	=	JRequest::getCmd( 'controller' );
					$tmpl		=	JRequest::getCmd( 'tmpl' );
					if ( $controller == 'interface' && $tmpl != 'component' ) {
						$search		=	'#<div id="toolbar-box">(.*)<div id="element-box">#sU';	
						$replace	=	'<div id="element-box">';	
						$buffer		=	preg_replace( $search, $replace, $buffer );
					}
					break;
				case 'com_categories':
					$search		=	'#<a href="index.php\?option=com_categories&amp;section=com_content&amp;task=edit&amp;cid\[\]=([0-9]*)&amp;type=content">'
								.	'([\n\r\t]*)(.*)</a>([\n\r\t]*)</span>([\n\r\t]*)</td>#';
					preg_match_all( $search, $buffer, $matchIds );
					$categories	=	CCK::DB_loadObjectList( 'SELECT s.id, s.parent_id, p.title FROM #__categories AS s'
															 .' LEFT JOIN #__categories AS p ON p.id = s.parent_id WHERE s.section NOT LIKE "%com_%"', 'id' );
					for ( $i = 0, $n = count( $matchIds[1] ); $i < $n; $i++ ) {
						$id			=	$matchIds[1][$i];
						$title		=	$matchIds[3][$i];
						$search		=	'<a href="index.php?option=com_categories&amp;section=com_content&amp;task=edit&amp;cid[]='.$id
									.	'&amp;type=content">'.$matchIds[2][$i].$matchIds[3][$i].'</a>'.$matchIds[4][$i].'</span>'.$matchIds[5][$i].'</td>';
						if ( @$categories[$id] && $parentId = @$categories[$id]->parent_id ) {
							$parentTitle	=	$categories[$id]->title;
							$parentLink		=	'<a href="javascript: openCEK_EditLink('.$parentId
											.	', \'index.php?option=com_categories&section=com_content&type=content&task=edit&cid[]=\', 1);">'.$parentTitle.'</a>';
							$replace		=	'-&nbsp;-&nbsp;&nbsp;<a href="javascript: openCEK_EditLink('.$id
											.	', \'index.php?option=com_categories&section=com_content&type=content&task=edit&cid[]=\', 1);">'.$matchIds[3][$i]
											.	'</a>&nbsp;&nbsp;('.$parentId.')&nbsp;</span></td><td>'.$parentLink.'</td>';
						} else {
							$replace	=	'<a href="javascript: openCEK_EditLink('.$id
										.	', \'index.php?option=com_categories&section=com_content&type=content&task=edit&cid[]=\', 1);">'.$matchIds[3][$i]
										.	'</a></span></td><td>#</td>';
						}
						$buffer		=	str_replace( $search, $replace, $buffer );
					}
					//				
					$search		=	'/<th width="5%">[\n\t\r]*<a href="javascript:tableOrdering/';
					$replace	=	'<th width="15%"># '.JText::_( 'Parent Category' ).'</th><th width="5%"><a href="javascript:tableOrdering';
					$buffer		=	preg_replace( $search, $replace, $buffer );
					//
					$search		=	'<form';
					$replace	=	'<div id="PushLayout_CCK"><form';
					$buffer		=	str_replace( $search, $replace, $buffer );
					$search		=	'</form>';
					$replace	=	'</form></div>';
					$buffer		=	str_replace( $search, $replace, $buffer );	
					break;
				case 'com_content':
					$joomfish	=	CCK::DB_loadResult( 'SELECT COUNT(s.id) FROM #__components AS s WHERE s.option="com_joomfish"' );
					//** JOOMFISH CONTENT BY JSEBLOD :: BEGIN **//
					if ( $joomfish ) {
						$langDefault	=	CCK_LANG_Default();
						if ( $this->_CCKConfig->bool_hide ) {
							$search		=	"#<span class='modtranslate'>(.*)</span>#sU";
							$replace	=	'';
							$buffer		=	preg_replace( $search, $replace, $buffer );
						}
						$langs		=	CCK::DB_loadObjectList( 'SELECT * FROM #__languages ORDER BY ordering ASC', 'shortcode' );
						$lang		=	JRequest::getCmd( 'lang' );
						$lang		=	( $lang ) ? $lang : $langDefault;
						$langId		=	$langs[$lang]->id;
						//
						$elems		=	null;
						foreach ( $langs as $elem ) {
						  	$defaultImg = ( $elem->shortcode == $langDefault ) ? ' *' : '';
							if ( $elem->shortcode == $lang ) {
								$elems	.=	'<li><a class="active" href="index.php?option=com_content&lang='.$elem->shortcode.'">'
										.	$elem->code.$defaultImg.'</a></li>';
							} else {
								$elems	.=	'<li><a href="index.php?option=com_content&lang='.$elem->shortcode.'">'
										.	$elem->code.$defaultImg.'</a></li>';
							}
						}
						$search		=	'#<div id="element-box">#';
						$replace	=	'<div id="submenu-box"><div class="t"><div class="t"><div class="t"></div></div></div><div class="m">'
									.	'<ul id="submenu">'.$elems.'</ul>'
									.	'<div class="clr"></div></div><div class="b"><div class="b"><div class="b"></div></div></div></div><div class="clr"></div><div id="element-box">';
						$buffer		=	preg_replace( $search, $replace, $buffer );
						//
						$search2		=	'#<a href="index.php\?option=com_categories&amp;task=edit&amp;cid\[\]=([0-9]*)#';
						preg_match_all( $search2, $buffer, $matches2 );						
						$search	=	'#<tr class="(row0|row1)">(.*)<a href="index.php\?option=com_content&amp;sectionid=([0-9-]*)&amp;task=edit&amp;cid\[\]=([0-9]*)">'
								.	'(.*)</a>([\n\r\t]*)</td>([\n\r\t]*)<td align="center">(.*)</td>(.*)</tr>#sU';
						preg_match_all( $search, $buffer, $matches );
						$tbody	=	'';
						if ( sizeof( @$matches[4] ) ) {
							$trads	=	array();
							$i		=	0;
							if ( $lang && $lang != $langDefault ) {
								foreach ( $matches[4] as $match ) {
									$catid	=	@$matches2[1][$i];
									$trads[$match]	=	CCK::DB_loadObjectList( 'SELECT language_id, value as title, published FROM #__jf_content WHERE reference_table="content"'
																				  .' AND reference_field="title" AND reference_id='.(int)$match, 'language_id' );
									if ( @$trads[$match][$langId] ) {
										$jfid	=	$trads[$match][$langId]->language_id;
										$main	=	'&nbsp;<a style="color: grey;" href="javascript: openPreview('.$match.');">'
												.	'<img src="components/com_cckjseblod/assets/images/list/icon-16-mouse.png" alt=" " /></a>&nbsp;&nbsp;&nbsp;'
												.	'<a href="javascript: openCEK_EditLink('.$match.', \'index.php?option=com_content&sectionid=-1&task=edit&cid[]=\', 0, '.$catid.', '
												.	$trads[$match][$langId]->language_id.');">';
										$flag	=	'<td align="center"><span class="editlinktip hasTip" title="'.JText::_( 'LANGUAGE' ).' :: '.$langs[$lang]->name.'">'
												.	'<img src="../components/com_joomfish/images/flags/'.$lang.'.gif" alt="" /></span></td>';
										if ( $trads[$match][$langId]->published ) {
											$state	=	'<td align="center"><a href="javascript: langPublish(0, '.$match.');">'
													.	'<img src="components/com_cckjseblod/assets/images/16/icon-16-joomfish_y.png" alt="" border="0" /></a></td>';
										} else {
											$state	=	'<td align="center"><a href="javascript: langPublish(1, '.$match.');">'
													.	'<img src="components/com_cckjseblod/assets/images/16/icon-16-joomfish_n.png" alt="" border="0" /></a></td>';
										}
										$tbody	.=	'<tr class="'.$matches[1][$i].'">'.$matches[2][$i].$main.@$trads[$match][$langId]->title.'</a></td>'.$flag.$state.$matches[9][$i].'</tr>';
									}
									$i++;
								}
							} else {
								if ( $this->_CCKConfig->bool ) {
									foreach ( $matches[4] as $match ) {
										$trads[$match]	=	CCK::DB_loadObjectList( 'SELECT cc.code AS text, s.language_id AS value FROM #__jf_content AS s'
																					 	. ' LEFT JOIN #__languages AS cc ON cc.id = s.language_id'
																						. ' WHERE s.reference_table="content"'
																				  		. ' AND s.reference_field="title" AND s.reference_id='.(int)$match );
										$catid	=	@$matches2[1][$i];
										$main	=	'&nbsp;<a style="color: grey;" href="javascript: openPreview('.$match.');">'
												.	'<img src="components/com_cckjseblod/assets/images/list/icon-16-mouse.png" alt=" " /></a>&nbsp;&nbsp;'
												.	'<a href="javascript: openCEK_EditLink('.$match.', \'index.php?option=com_content&sectionid=-1&task=edit&cid[]=\', 0, '.$catid.',0);">';
										$onchange	=	'openCEK_EditLink('.$match.', \'index.php?option=com_content&sectionid=-1&task=edit&cid[]=\', 0, '.$catid.', this.value)';
										$js			=	'onchange="if(this.value){'.$onchange.';}" ';
										$optLang	=	array();
										$num		=	count( $trads[$match] );
										if ( $num ) {
											$label		=	'- '.$num.' -';
											$optLang[]	=	JHTML::_( 'select.option',  '', $label, 'value', 'text' );
											$optLang	=	array_merge( $optLang, $trads[$match] );
											$list		=	JHTML::_('select.genericlist', $optLang, 'lang'.$match, $js.'style="width:66px;padding:1px;"', 'value', 'text','','lang'.$match );
											$flag		=	'<td align="center">'.$list.'</td>';
										} else {
											$flag		=	'<td align="center"><span class="editlinktip hasTip" title="'.JText::_( 'LANGUAGE' ).' :: '.$langs[$lang]->name.'">'
														.	'<img src="../components/com_joomfish/images/flags/'.$lang.'.gif" alt="" /></span></td>';											
										}
										$state		=	'<td align="center">'.$matches[8][$i].'</td>';
										$tbody		.=	'<tr class="'.$matches[1][$i].'">'.$matches[2][$i].$main.$matches[5][$i].'</a></td>'.$flag.$state.$matches[9][$i].'</tr>';
										$i++;
									}
								} else {
									foreach ( $matches[4] as $match ) {
										$catid	=	@$matches2[1][$i];
										$main	=	'&nbsp;<a style="color: grey;" href="javascript: openPreview('.$match.');">'
												.	'<img src="components/com_cckjseblod/assets/images/list/icon-16-mouse.png" alt=" " /></a>&nbsp;&nbsp;'
												.	'<a href="javascript: openCEK_EditLink('.$match.', \'index.php?option=com_content&sectionid=-1&task=edit&cid[]=\', 0, '.$catid.',0);">';
										$flag	=	'<td align="center"><span class="editlinktip hasTip" title="'.JText::_( 'LANGUAGE' ).' :: '.$langs[$lang]->name.'">'
												.	'<img src="../components/com_joomfish/images/flags/'.$lang.'.gif" alt="" /></span></td>';
										$state	=	'<td align="center">'.$matches[8][$i].'</td>';
										$tbody	.=	'<tr class="'.$matches[1][$i].'">'.$matches[2][$i].$main.$matches[5][$i].'</a></td>'.$flag.$state.$matches[9][$i].'</tr>';
										$i++;
									}
								}
							}
						}
						$search		=	'#<tbody>(.*)</tbody>#s';
						$replace	=	'<tbody>'.$tbody.'</tbody>';
						$buffer		=	preg_replace( $search, $replace, $buffer );
						//
						$search		=	'<th width="1%" nowrap="nowrap">';
						$replace	=	'<th width="1%" nowrap="nowrap"><img src="../components/com_joomfish/images/flags/'.$lang.'.gif" alt="" /></th><th width="1%" nowrap="nowrap">';
						$buffer		=	str_replace( $search, $replace, $buffer );
						//
						if ( $lang && $lang != $langDefault ) {
							$search		=	'#<tr align="center">(.*)<img src="images/disabled.png"(.*)</tr>#sU';
							$replace	=	'<tr align="center">'
										.	'<td><img src="components/com_cckjseblod/assets/images/16/icon-16-joomfish_y.png" border="0" alt="'.JText::_( 'Visible' ).'" /></td>'
										.	'<td>'.JText::_( 'Published and is' ).' <u>'.JText::_( 'Current' ).'</u>&nbsp;&nbsp;&nbsp;|</td>'
										.	'<td><img src="components/com_cckjseblod/assets/images/16/icon-16-joomfish_n.png" border="0" alt="'.JText::_( 'Finished' ).'" /></td>'
										.	'<td>'.JText::_( 'Not Published' ).'</td>'
                            			.	'</tr>';
							$buffer		=	preg_replace( $search, $replace, $buffer );
						}
					//** JOOMFISH CONTENT BY JSEBLOD :: END **//					
					} else {
						$search			=	'#<a href="index.php\?option=com_content&amp;sectionid=([0-9-]*)&amp;task=edit&amp;cid\[\]=([0-9]*)#';
						preg_match_all( $search, $buffer, $matchIds );
						$search2		=	'#<a href="index.php\?option=com_categories&amp;task=edit&amp;cid\[\]=([0-9]*)#';
						preg_match_all( $search2, $buffer, $matchIds2 );
						$i	=	0;
						foreach ( $matchIds[2] as $id ) {
							$catid		=	@$matchIds2[1][$i];
							$search		=	'<a href="index.php?option=com_content&amp;sectionid='.$matchIds[1][$i].'&amp;task=edit&amp;cid[]='.$id.'">';
							$replace	=	'&nbsp;<a style="color: grey;" href="javascript: openPreview('.$id.');">'
										.	'<img src="components/com_cckjseblod/assets/images/list/icon-16-mouse.png" alt=" " /></a>&nbsp;&nbsp;';
							$replace	.=	'<a href="javascript: openCEK_EditLink('.$id.', \'index.php?option=com_content&sectionid=-1&task=edit&cid[]=\', 0, '.$catid.', 0);">';
							$buffer		=	str_replace( $search, $replace, $buffer );
							$i++;
						}
					}
					$search		=	'<form';
					$replace	=	'<div id="PushLayout_CCK"><form';
					$buffer		=	str_replace( $search, $replace, $buffer );
					$search		=	'</form>';
					$replace	=	'</form></div>';
					$buffer		=	str_replace( $search, $replace, $buffer );		
					break;
				case 'com_users':
					$search		=	'#<a href="index.php\?option=com_users&amp;view=user&amp;task=edit&amp;cid\[\]=([0-9]*)">'
								.	'([\n\r\t]*)(.*)</a>([\n\r\t]*)</td>([\n\r\t]*)<td>([\n\r\t]*)(.*)([\n\r\t]*)</td>#';
					preg_match_all( $search, $buffer, $matchIds );
					$users		=	CCK::DB_loadObjectList( 'SELECT userid, contentid FROM #__jseblod_cck_users WHERE registration = 1', 'userid' );
					//TODO ONLY ONE QUERY FOR ALL USERS!
					for ( $i = 0, $n = count( $matchIds[1] ); $i < $n; $i++ ) {
						$id			=	$matchIds[1][$i];
						// User's Stuff List
						$usersStuff		=	CCK::DB_loadObjectList( 'SELECT s.contentid AS value, cc.title AS text, sc.title as ctype FROM #__jseblod_cck_users AS s'
																	 .' LEFT JOIN #__content AS cc ON cc.id = s.contentid'
																	 .' LEFT JOIN #__jseblod_cck_types AS sc ON sc.name = s.type'
																	 .' WHERE registration = 0 AND userid='.$id.' ORDER BY ctype ASC, text ASC', '' );
						$js				=	'onchange="if(this.value){openUSER_Stuff(this.value, '.$id.');}"';
						$optUserStuff	=	array();
						$count	=	sizeof( $usersStuff );
						if ( ! $count ) {
							$listUserStuff	=	'&nbsp;&nbsp;'.JText::_( 'No Personal Content' );
						} else {
							$optUserStuff[]	=	JHTML::_( 'select.option',  0, '- '.JText::_( 'Select Personal Content' ).' -', 'value', 'text' );
							for ( $k = 0; $k < $count; $k++ ) {
								if ( $k == 0 ) {
									$optUserStuff[] = JHTML::_( 'select.option', '<OPTGROUP>', $usersStuff[$k]->ctype );
								} else if ( $k > 0 && $usersStuff[$k]->ctype != $usersStuff[$k - 1]->ctype ) {
									$optUserStuff[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
									$optUserStuff[] =	JHTML::_( 'select.option', '<OPTGROUP>', $usersStuff[$k]->ctype );
								} else {}
								$optUserStuff[]	=	JHTML::_( 'select.option', $usersStuff[$k]->value, $usersStuff[$k]->text, 'value', 'text' );
							}
							$optUserStuff[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
							$optUserStuff[] =	JHTML::_( 'select.option', '<OPTGROUP>', '# '.JText::_( 'GO TO' ).' #' );
							$optUserStuff[]	=	JHTML::_( 'select.option', -1, JText::_( 'Article Manager' ), 'value', 'text' );
							$optUserStuff[]	=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
							$listUserStuff	=	JHTML::_('select.genericlist', $optUserStuff, 'open_userstuff'.$id, $js.' style="width: 158px; padding: 2px;"', 'value', 'text', '', 'open_userstuff'.$id );
						}
						//
						$search		=	'<a href="index.php?option=com_users&amp;view=user&amp;task=edit&amp;cid[]='.$id.'">'
								.	$matchIds[2][$i].$matchIds[3][$i].'</a>'.$matchIds[4][$i].'</td>'.$matchIds[5][$i].'<td>'.$matchIds[6][$i].$matchIds[7][$i].$matchIds[8][$i].'</td>';
						if ( @$users[$id] && $contentId	= @$users[$id]->contentid ) {		
							$replace	=	'<div style="float: left; vertical-align: middle;"><table><tr><td style="border: none;"><a href="javascript: openPreview('.$contentId.');">'
										.	'<img src="components/com_cckjseblod/assets/images/list/icon-16-mouse.png" alt=" " /></a></td></tr></table></div>';									
						} else {
							$replace	=	'<div style="float: left; vertical-align: middle;"><table><tr><td style="border: none;"><img src="components/com_cckjseblod/assets/images/list/icon-16-blank.png" alt=" " /></td></tr></table></div>';	
						}
						$replace	.=	'<div style="float: left; vertical-align: middle;">&nbsp;<a href="javascript: openCEK_EditLink('.$id.', \'index.php?option=com_users&view=user&task=edit&cid[]=\', 2);">'
					.$matchIds[3][$i].'</a><br />'.'&nbsp;'.$matchIds[7][$i].'</div></td><td>'.$listUserStuff.'</td>';
						$buffer		=	str_replace( $search, $replace, $buffer );
					}
					//
					$optUserAction		=	array();
					$optUserAction[]	=	JHTML::_( 'select.option', 0, '<img src="components/com_cckjseblod/assets/images/list/icon-18-cckview.png" alt="E">', 'value', 'text' );
					$optUserAction[]	=	JHTML::_( 'select.option', 1, '<img src="components/com_cckjseblod/assets/images/list/icon-16-mouse.png" alt="P">', 'value', 'text' );
					$optUserAction[]	=	JHTML::_( 'select.option',  2, '<img src="components/com_cckjseblod/assets/images/list/icon-16-trash.png" alt="R">', 'value', 'text' );
					$listUserAction		=	JHTML::_('select.radiolist', $optUserAction, 'action_userstuff', '', 'value', 'text', 0, 'action_userstuff' );
//  					$search		=	JText::_( 'Reset' ).'</button>';
					//$replace	=	JText::_( 'Reset' ).'</button>'.'&nbsp;&nbsp;'.$listUserAction;
				//	$buffer		=	str_replace( $search, $replace, $buffer );
					//
					$search		=	'#</th>([\n\r\t]*)<th width="15%" class="title" >([\n\r\t]*)<a href="(.*)username(.*)>(.*)</a>#';
					preg_match( $search, $buffer, $matches );
					$search		=	$matches[0];
					$replace	=	'&nbsp;//&nbsp;&nbsp;<a href="'.$matches[3].'username'.$matches[4].'>'.$matches[5].'</a></th><th width="15%" class="title" >'.$listUserAction;
					$buffer		=	str_replace( $search, $replace, $buffer );
					//
					$search		=	'<form';
					$replace	=	'<div id="PushLayout_CCK"><form';
					$buffer		=	str_replace( $search, $replace, $buffer );
					$search		=	'</form>';
					$replace	=	'</form></div>';
					$buffer		=	str_replace( $search, $replace, $buffer );	
					break;
				case 'com_templates':
					$client		=	JRequest::getCmd( 'client' );
					$cck		=	JRequest::getCmd( 'cck' );
					//
					$search		=	'#<ul id="submenu">(.*)</ul>#sU';
					if ( $client == 0 ) {
						if ( $cck == 1 ) {
							$replace	=	'<ul id="submenu"><li><a href="index.php?option=com_templates&client=0">'.JText::_( 'SITE' ).'</a></li>'
										.	'<li><a href="index.php?option=com_templates&client=1">'.JText::_( 'ADMINISTRATOR' ).'</a></li>'
										.	'<li><a class="active" href="index.php?option=com_templates&client=0&cck=1">'
										.	JText::_( 'CONTENT AND FORM' ).'&nbsp;&nbsp;<img src="components/com_cckjseblod/assets/images/jseblod/icon-12-jseblod.png" alt=" "></a></li></ul>';
						} else {
							$replace	=	'<ul id="submenu"><li><a class="active" href="index.php?option=com_templates&client=0">'.JText::_( 'SITE' ).'</a></li>'
										.	'<li><a href="index.php?option=com_templates&client=1">'.JText::_( 'ADMINISTRATOR' ).'</a></li>'
										.	'<li><a href="index.php?option=com_templates&client=0&cck=1">'
										.	JText::_( 'CONTENT AND FORM' ).'&nbsp;&nbsp;<img src="components/com_cckjseblod/assets/images/jseblod/icon-12-jseblod.png" alt=" "></a></li></ul>';
						}
					} else {
						$replace	=	'<ul id="submenu"><li><a href="index.php?option=com_templates&client=0">'.JText::_( 'SITE' ).'</a></li>'
									.	'<li><a class="active" href="index.php?option=com_templates&client=1">'.JText::_( 'ADMINISTRATOR' ).'</a></li>'
									.	'<li><a href="index.php?option=com_templates&client=0&cck=1">'
									.	JText::_( 'CONTENT AND FORM' ).'&nbsp;&nbsp;<img src="components/com_cckjseblod/assets/images/jseblod/icon-12-jseblod.png" alt=" "></a></li></ul>';
					}
					$buffer		=	preg_replace( $search, $replace, $buffer );
					//
					if ( $client == 0 ) {
						jimport( 'joomla.filesystem.file' );
						$search		=	'#<tr class="row0">(.*)name="cid\[\]" value="(.*)" onclick(.*)</tr>#sU';
						preg_match_all( $search, $buffer, $matches );
						if ( $cck == 1 ) {
							if ( sizeof( $matches[2] ) ) {
								$cckTemplates	=	CCK::DB_loadResultArray( 'SELECT name FROM #__jseblod_cck_templates' );
								$tbody	=	'';
								$i		=	0;
								foreach ( $matches[2] as $match ) {
									if ( array_search( $match, $cckTemplates ) !== false ) {
										$tbody	.=	'<tr class="row0">'.$matches[1][$i].'name="cid[]" value="'.$matches[2][$i].'" onclick'.$matches[3][$i].'</tr>';
									} else {
										$file	=	JPATH_SITE.DS.'templates'.DS.$match.DS.'index.php';
										if ( JFile::exists( $file ) ) {
											$buf	=	JFile::read( $file );
											if ( strpos( $buf, 'jSeblod' ) !== false ) {
												$tbody	.=	'<tr class="row0">'.$matches[1][$i].'name="cid[]" value="'.$matches[2][$i].'" onclick'.$matches[3][$i].'</tr>';
											}
										}
									}
									$i++;
								}
							}
							$search		=	'#<tbody>(.*)</tbody>#sU';
							$replace	=	'<tbody>'.$tbody.'</tbody>';
							$buffer		=	preg_replace( $search, $replace, $buffer );
						} else {
							if ( sizeof( $matches[2] ) ) {
								$cckTemplates	=	CCK::DB_loadResultArray( 'SELECT name FROM #__jseblod_cck_templates' );
								$tbody	=	'';
								$i		=	0;
								foreach ( $matches[2] as $match ) {
									if ( array_search( $match, $cckTemplates ) === false ) {
										$file	=	JPATH_SITE.DS.'templates'.DS.$match.DS.'index.php';
										if ( JFile::exists( $file ) ) {
											if ( $match == 'beez' || $match == 'ja_purity' || $match == 'rhuk_milkyway' ) {
												$tbody	.=	'<tr class="row0">'.$matches[1][$i].'name="cid[]" value="'.$matches[2][$i].'" onclick'.$matches[3][$i].'</tr>';												
											} else {
												$buf	=	JFile::read( $file );
												if ( strpos( $buf, 'jSeblod' ) === false ) {
													$tbody	.=	'<tr class="row0">'.$matches[1][$i].'name="cid[]" value="'.$matches[2][$i].'" onclick'.$matches[3][$i].'</tr>';
												}
											}
										}
									}
									$i++;
								}
							}
							$search		=	'#<tbody>(.*)</tbody>#sU';
							$replace	=	'<tbody>'.$tbody.'</tbody>';
							$buffer		=	preg_replace( $search, $replace, $buffer );
						}
					}
					break;
				case 'com_menus':
					// eCommerce
					$search		=	'#(<li >|<li>)(.*)id="cckjseblod_ecommerce"(.*)([\n\r\t]*)</li>#U';
					preg_match_all( $search, $buffer, $matches );
					if ( @$matches[0][0] ) {
						$buffer	=	str_replace( $matches[0][0], '', $buffer );
					}
					// WebService
					$search		=	'#(<li >|<li>)(.*)id="cckjseblod_webservice"(.*)([\n\r\t]*)</li>#U';
					preg_match_all( $search, $buffer, $matches );
					if ( @$matches[0][0] ) {
						$buffer	=	str_replace( $matches[0][0], '', $buffer );
					}
					break;
				case 'com_media':
					$search		=	'#<td>(.*)class="img-preview"(.*)<img(.*)/>(.*)</td>#sU';
					preg_match_all( $search, $buffer, $matches );
					if ( sizeof( $matches[3] ) ) {
						foreach( $matches[3] as $match ) {
							$search		=	'#'.$match.'#';
							$replace	=	' src="components/com_cckjseblod/assets/images/list/icon-16-mouse.png" width="16" height="16" alt="Preview" border="0" ';
							$buffer		=	preg_replace( $search, $replace, $buffer );
						}
					}
					break;
				default:
					break;
			}
		
			JResponse::setBody( $buffer );
			}
		}
	}
}

class CCK_objSorter 
{
	var $property;
	var $sorted;

    function CCK_objSorter( $objects_array, $property=null ) {
        $sample	=	@$objects_array[0];
        $vars	=	get_object_vars( $sample );
		
        if ( isset( $property ) ) {
            if ( isset( $sample->$property ) ) {
                $this->property	=	$property;
                usort( $objects_array, array( $this, '_compare' ) ) ;
            }
			else {
                $this->sorted	=	false;
                return;
            }
        }
        else {
            list( $property, $var )	=	each( $sample );
            $this->property      	=	$property;
            usort( $objects_array, array( $this, '_compare' ) );
        }
		
        $this->sorted	=	( $objects_array );
    }

    function _compare( $a, $b ) {
        $property	=	$this->property;
		
        if ( $a->$property == $b->$property ) {
			return 0;
		}
		
        return ( $a->$property < $b->$property ) ? -1 : 1;
    }
}

function CCK_GET_ConstructionField( $itemName )
{
	$db	=&	JFactory::getDBO();
	
	$where	=	' WHERE s.name = "'.$itemName.'"';
	
	$query	= 'SELECT cc.name AS typename, s.*'
			. ' FROM #__jseblod_cck_items AS s'
			. ' LEFT JOIN #__jseblod_cck_items_types AS cc ON cc.id = s.type'
			. $where
			;
	$db->setQuery( $query );
	$res	=	$db->loadObject();
	
	return $res;
}

function CCK_LANG_ShortCode( $id )
{
	$db	=&	JFactory::getDBO();
	
	$query	= 'SELECT s.shortcode'
			. ' FROM #__languages AS s'
			. ' WHERE s.id = '.(int)$id;
			;
	$db->setQuery( $query );
	$res	=	$db->loadResult();
	
	return $res;
}

function CCK_LANG_Id( $shortcode )
{
	$db	=&	JFactory::getDBO();
	
	$query	= 'SELECT s.id'
			. ' FROM #__languages AS s'
			. ' WHERE s.shortcode = "'.$shortcode.'"';
			;
	$db->setQuery( $query );
	$res	=	$db->loadResult();
	
	return $res;
}

function CCK_LANG_Enable()
{
	$db	=&	JFactory::getDBO();
	
	$query	= 'SELECT COUNT(s.id)'
			. 'FROM #__components AS s'
			. ' WHERE s.option="com_joomfish"'
			;
	$db->setQuery( $query );
	$res	=	$db->loadResult();
	
	return $res;
}

function CCK_LANG_List()
{
	$db	=&	JFactory::getDBO();
	
	$query	= 'SELECT *'
			. ' FROM #__languages'
			. ' ORDER BY ordering'
			;
	$db->setQuery( $query );
	$res	=	$db->loadObjectList();
	
	return $res;
}

function CCK_LANG_Default()
{
	$langParams	=	JComponentHelper::getParams( 'com_languages' );
	$langSite	=	$langParams->get( "site", 'en-GB' );
	$res		=	substr( $langSite, 0, 2 );
	
	return $res;
}

function CCK_LANG_DefaultId()
{
	$langParams	=	JComponentHelper::getParams( 'com_languages' );
	$langSite	=	$langParams->get( "site", 'en-GB' );
	$shortCode	=	substr( $langSite, 0, 2 );
	
	$db	=&	JFactory::getDBO();
	
	$where	=	' WHERE s.name = "'.$itemName.'"';
	
	$query	= 'SELECT s.id'
			. ' FROM #__languages AS s'
			. ' WHERE s.shortcode="'.$shortCode.'"';
			;
	$db->setQuery( $query );
	$res	=	$db->loadResult();
	
	return $res;
}
//
function CCK_DB_Result( $query )
{
	$db		=&	JFactory::getDBO();

	$db->setQuery( $query );
	$res	=	$db->loadResult();
	
	return $res;
}
function CCK_DB_ResultArray( $query )
{
	$db		=&	JFactory::getDBO();

	$db->setQuery( $query );
	$res	=	$db->loadResultArray();
	
	return $res;
}
//
function CCK_DB_Object( $query )
{
	$db		=&	JFactory::getDBO();

	$db->setQuery( $query );
	$res	=	$db->loadObject();
	
	return $res;
}
function CCK_DB_ObjectList( $query, $key = null )
{
	$db		=&	JFactory::getDBO();

	$db->setQuery( $query );
	$res	=	$db->loadObjectList( $key );
	
	return $res;
}
//
function CCK_DB_AssocList( $query )
{
	$db		=&	JFactory::getDBO();

	$db->setQuery( $query );
	$res	=	$db->loadAssocList();
 
	return $res;
}
//
function CCK_DB_Delete( $query )
{
	$db		=&	JFactory::getDBO();
	
	$db->setQuery( $query );
	if ( ! $db->query() ) {
		return false;
	}
	
	return true;
}

/******************
* CONTENT PROCESS *
*******************/
function CCK_GET_Value( $id, $fields, $mode = false ) {
	if ( ! $id ) {
		return null;
	}
	
	$text	=	CCK_DB_Result( 'SELECT CONCAT(s.introtext, s.fulltext) as text FROM #__content AS s WHERE s.id='.(int)$id );
	if ( !$text ) {
		return null;
	}
	if ( ! $fields ) {
		return null;
	}
	if ( ! is_array( $fields ) ) {
		$field		=	$fields;
		$fields		=	array();
		$fields[]	=	$field;
	}
	$items	=	array_flip( $fields );

	foreach( $items as $key => $value ) {
		$matches	=	null;
		if ( $k = strpos( $key, '[' ) ) {
			$key1	=	substr( $key, 0, $k );
			$regex	=	'#::'.$key1.'::(.*?)::/'.$key1.'::#s';
			preg_match( $regex, $text, $matches );
			$id		=	( @$matches[1] ) ? $matches[1] : 0;
			if ( $id ) {
				$text2	=	CCK_DB_Result( 'SELECT CONCAT(s.introtext, s.fulltext) as text FROM #__content AS s WHERE s.id='.(int)$id );
				if ( $text2 ) {
					$key2	=	substr( $key, $k+1, -1 );
					$regex2	=	'#::'.$key2.'::(.*?)::/'.$key2.'::#s';
					preg_match( $regex2, $text2, $matches2 );
					unset( $items[$key] );
					if ( $mode == true ) {
						$items[$key]			=	( @$matches2[1] ) ? $matches2[1] : '';
					} else {
						$items[$key1][$key2]	=	( @$matches2[1] ) ? $matches2[1] : '';
					}
					if ( @$field ) {
						return ( $items[$key1][$key2] );
					}
				}
			}
		} else {
			$regex		=	'#::'.$key.'::(.*?)::/'.$key.'::#s';	
			preg_match( $regex, $text, $matches );
		
			$items[$key]	=	( @$matches[1] ) ? $matches[1] : '';
			if ( @$field ) {
				return ( $items[$key] );
			}
		}
	}

	return $items;
}

function CCK_GET_ValueFromText( $text, $fields, $mode = false ) {
	if ( !$text ) {
		return null;
	}
	
	if ( ! $fields ) {
		return null;
	}
	if ( ! is_array( $fields ) ) {
		$field		=	$fields;
		$fields		=	array();
		$fields[]	=	$field;
	}
	$items	=	array_flip( $fields );

	foreach( $items as $key => $value ) {
		$matches	=	null;
		if ( $k = strpos( $key, '[' ) ) {
			$key1	=	substr( $key, 0, $k );
			$regex	=	'#::'.$key1.'::(.*?)::/'.$key1.'::#s';
			preg_match( $regex, $text, $matches );
			$id		=	( @$matches[1] ) ? $matches[1] : 0;
			if ( $id ) {
				$text2	=	CCK_DB_Result( 'SELECT CONCAT(s.introtext, s.fulltext) as text FROM #__content AS s WHERE s.id='.(int)$id );
				if ( $text2 ) {
					$key2	=	substr( $key, $k+1, -1 );
					$regex2	=	'#::'.$key2.'::(.*?)::/'.$key2.'::#s';
					preg_match( $regex2, $text2, $matches2 );
					unset( $items[$key] );
					if ( $mode == true ) {
						$items[$key]			=	( @$matches2[1] ) ? $matches2[1] : '';
					} else {
						$items[$key1][$key2]	=	( @$matches2[1] ) ? $matches2[1] : '';
					}
					if ( @$field ) {
						return ( $items[$key1][$key2] );
					}
				}
			}
		} else {
			$regex		=	'#::'.$key.'::(.*?)::/'.$key.'::#s';	
			preg_match( $regex, $text, $matches );
		
			$items[$key]	=	( @$matches[1] ) ? $matches[1] : '';
			if ( @$field ) {
				return ( $items[$key] );
			}
		}
	}

	return $items;
}

/*************
* TEMPLATING *
**************/
function CCK_CONTENT_SimpleForm( $value, $item, $array = false, $displayLabel ) {
	$dblClick	=	( $array == true ) ? 'ondblclick="addOption();"' : '';

	// Email, Text, Search Generic
	if ( $displayLabel == 2 && ( $item->typename == 'email' || $item->typename == 'text' || ( $item->typename == 'search_generic' && ! @$item->value ) ) ) {
		$value	=	str_replace( 'value="', 'value="'.$item->label.'" '.$dblClick.' onblur="if(this.value==\'\'){this.value=\''.$item->label.'\';}"'
																				 .' onfocus="if(this.value==\''.$item->label.'\'){this.value=\'\';}', $value );
	}

	return $value;
}

function CCK_CONTENT_DefaultContent( $value, $item, $array = false ) {
	// eCommerce Cart Forced
	if ( @$item->typename == 'ecommerce_cart' || @$item->typename == 'ecommerce_cart_button' || @$item->typename == 'ecommerce_price' ) {
		$forced	=	@$item->forced_html ? $item->forced_html : 'form';
		$value	=	@$item->$forced;
	}
	//4TH DIMENSION
	if ( @$item->html ) {
		$value	=	$item->html;
	} else {
		
		// Joomla Module, Media, Text (Url), Upload Image
		if ( @$item->typename == 'text' && @$item->validation == 'validate-url' ) {
			return '<a href="'.$value.'" target="_blank">'.$value.'</a>';
		} else if ( @$item->typename == 'file' ) {
			$alt	=	JFile::stripExt( substr( strrchr( $item->value, "/" ), 1 ) );
			$alt	=	( $alt ) ? $alt : $item->label;
			$ext	=	substr( strrchr( $item->value, "." ), 1 );
			if ( $item->bool5 ) {
				return $value;
			} else {
				if ( $ext == 'jpg' || $ext == 'JPG' || $ext == 'jpeg' || $ext == 'JPEG' || $ext == 'png' || $ext == 'PNG'
				|| $ext == 'gif' || $ext == 'GIF' || $ext == 'bmp' || $ext == 'BMP' || $ext == 'tiff' || $ext == 'TIFF' ) {
					$width	=	( $item->width ) ? 'width='.$item->width : '';
					$height	=	( $item->height ) ? 'height='.$item->height : '';
					$value	=	'<img src="'.$value.'" alt="'.$alt.'" '.$width.' '.$height.' />';
				} else {
					return '<a href="'.$item->value.'" target="_blank">'.$alt.'</a>';
				}
			}
		} else if ( @$item->typename == 'upload_simple' ) {
			$alt	=	JFile::stripExt( substr( strrchr( $item->value, "/" ), 1 ) );
			$alt	=	( $alt ) ? $alt : $item->label;
			return '<a href="'.$item->value.'" target="_blank">'.$alt.'</a>';
		} else if ( @$item->typename == 'upload_image' ) {
			$alt	=	JFile::stripExt( substr( strrchr( $item->value, "/" ), 1 ) );
			$alt	=	( $alt ) ? $alt : $item->label;
			$ext	=	substr( strrchr( $item->value, "." ), 1 );
			if ( $ext == 'jpg' || $ext == 'JPG' || $ext == 'jpeg' || $ext == 'JPEG' || $ext == 'png' || $ext == 'PNG'
			|| $ext == 'gif' || $ext == 'GIF' || $ext == 'bmp' || $ext == 'BMP' || $ext == 'tiff' || $ext == 'TIFF' ) {
				$width	=	( $item->defaultvalue == '_' && $item->width && ! $item->format ) ? 'width='.$item->width : '';
				$height	=	( $item->defaultvalue == '_' && $item->height && ! $item->format ) ? 'height='.$item->height : '';
				
				$thumb	=	$item->defaultvalue;
				$zoom	=	$item->url;
				$image	=	( $item->defaultvalue != '_' && array_key_exists( $thumb, get_object_vars( $item ) ) ) ? $item->$thumb : $value;
				$box	=	( $zoom != '_' && array_key_exists( $zoom, get_object_vars( $item ) ) ) ? $item->$zoom : $value;
				if ( $item->url && JFile::exists( $box ) ) {
					return	'<a class="modal" rel="{handler: \'image\'}" href="'.$box.'" title="'.$alt.'"><img src="'.$image.'" alt="'.$alt.'" '.$width.' '.$height.' /></a>';
				} else {
					$value	=	'<img src="'.$image.'" alt="'.$alt.'" '.$width.' '.$height.' />';
				}
			}
		}
		else {}
		
	}
	if ( @$item->link && ( strpos( $value, 'href' ) === false ) ) {
		$value	=	'<a href="'.$item->link.'">'.$value.'</a>';
	}
	
	return $value;
}

?>