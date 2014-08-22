<?php
/*
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
*/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.toolbar');
JHTML::_( 'stylesheet', 'cckjseblod_toolbar.css', 'administrator/modules/mod_cckjseblod_toolbar/assets/css/' );

$bar = & JToolBar::getInstance('toolbar');

global $mainframe, $option;
$doc	=&	JFactory::getDocument();
$task	=	JRequest::getVar( 'task' );
$uri	=	$mainframe->getSiteURL();
$langC	=	null;
if ( $option == 'com_cckjseblod' ) {
	$controller	=	JRequest::getVar( 'controller' );
	if ( $controller == 'types' && ! $task && $bar->_bar[3][3] == 'createHtml' ) {
		$bar->_bar[3][0]	=	'Link';
		$bar->_bar[3][3]	=	'#pagination-bottom';
	}
}

if ( $option == 'com_content' || $option == 'com_categories' || $option == 'com_users' || $option == 'com_templates' || $option == 'com_media' ) {
	jimport('joomla.language.help');
	$config =&	CCK::CORE_getConfig();
	
	$width 	=	( $config->modal_width ) ? $config->modal_width : 900;
	$height =	( $config->modal_height ) ? $config->modal_height : 540;
	
	if ( $task == 'copy' || $task == 'copyselect' || $task == 'movesect' || $task == 'moveselect' ) {
	} else if ( $task == 'edit' || $task == 'add' ) {
		switch ( $option ) {
			case 'com_categories':
				if ( JRequest::getVar( 'section' ) == 'com_content' ) {
					array_pop( $bar->_bar );
					$bar->appendButton( 'Popup', 'help', 'Help', JHelp::createURL( 'screen.content.categories', false ), $width, $height );
				}
				break;
			case 'com_content':
				$array	=	JRequest::getVar( 'cid',  0, '', 'array' );
				$cid	=	(int)$array[0];
				if ( $cid ) {
					$js = '
						window.addEvent("domready",function(){
							var currentid="'.$cid.'";
							url="index.php?option=com_cckjseblod&controller=interface&task=getAuthorAjax&format=raw&art_id="+currentid;
							var a=new Ajax(url,{
								 method:"post",
								 update:"",
								 onComplete: function(response){ 
									var author = $("detailscreated_by");
									optObj = new Option( "#Author#", response );								
									var k=0;
									var srcLen = author.length;			
									for (var i=0; i < srcLen; i++) {
										if (author.options[i].value == optObj.value ) {
											k++;
											break;
										}
									}
									if ( k == 0 ) {
										author.options[author.options.length] = optObj;
										author.options[author.options.length - 1].selected = true;
									}
								 }
							}).request();
						});
						
						function openPreview() {
							var url="'.$uri.'index.php?option=com_content&view=article&tmpl=component&id='.$cid.'";
							SqueezeBox.fromElement(url,{handler:"iframe",size:{x:'.$width.',y:'.$height.'}});
						}
					';
					JHTML::_( 'behavior.modal' );
					$doc->addScriptDeclaration( $js );
					$bar->_bar[0][0]	=	'Link';
					$bar->_bar[0][3]	=	'javascript: openPreview(0);';
				}
				array_pop( $bar->_bar );
				$bar->appendButton( 'Popup', 'help', 'Help', JHelp::createURL( 'screen.content', false ), $width, $height );
				break;
			case 'com_users':
				array_pop( $bar->_bar );
				$bar->appendButton( 'Popup', 'help', 'Help', JHelp::createURL( 'screen.users', false ), $width, $height );
				break;
			case 'com_templates':
				break;
			case 'com_media':
				break;
			default:
				break;
		}
	} else {
		switch ( $option ) {
			case 'com_categories':
				if ( JRequest::getVar( 'section' ) == 'com_content' ) {
					$editL	=	$config->category_edition_mode;
					$editB	=	$config->category_edition2_mode;
					//
					$bar->_bar[4][0]	=	'Link';
					$bar->_bar[4][3]	=	'javascript: trashCategory();';
					$bar->_bar[5][0]	=	'Link';
					$bar->_bar[5][3]	=	'javascript: openCEK_EditButton(0, \'index.php?option=com_categories&section=com_content&type=content&task=edit&cid[]=\', 1);';
					$bar->_bar[6][0]	=	'Link';
					$bar->_bar[6][3]	=	'javascript: openCEK_New('.$config->category_creation_mode.', \'index.php?option=com_categories&section=com_content&task=add\', 1);';
					array_pop( $bar->_bar );
					$bar->appendButton( 'Popup', 'help', 'Help', JHelp::createURL( 'screen.content.categories', false ), $width, $height );
				}
				break;
			case 'com_content':
				$editL	=	$config->article_edition_mode;
				$editB	=	$config->article_edition2_mode;
				//
				if ( CCK_LANG_Enable() ) {
					$langC	=	JRequest::getCmd( 'lang' );
					$langDefault = CCK_LANG_Default();
					$langC	=	( $langC ) ? $langC : $langDefault;
					if ( $langC != $langDefault ) {
						array_shift( $bar->_bar );
						array_shift( $bar->_bar );
						$bar->_bar[0][0]	=	'Link';
						$bar->_bar[0][1]	=	'publish_joomfish';
						$bar->_bar[0][3]	=	'javascript: langPublish(1, 0);';
						$bar->_bar[1][0]	=	'Link';
						$bar->_bar[1][1]	=	'unpublish_joomfish';
						$bar->_bar[1][3]	=	'javascript: langPublish(0, 0);';
						array_pop( $bar->_bar );
						array_pop( $bar->_bar );
						array_pop( $bar->_bar );
						array_pop( $bar->_bar );
						$link				=	'javascript: langTrash();';
						$bar->_bar[2]		=	array( 0 => 'Link', 1 => 'trash_joomfish', 2 => JText::_( 'Delete' ), 3 => $link, 4 => $bar->_bar[4][4], 5 => $bar->_bar[4][5] );
						array_pop( $bar->_bar );
						array_pop( $bar->_bar );
						$link				=	
						//
						$lang		=&	JFactory::getLanguage();
						$lang_tag	=	$lang->getTag();
						if ( ! ( $lang_tag == 'en-GB' || $lang_tag == 'fr-FR' || $lang_tag == 'de-DE' ) ) {
							$lang_tag = 'en-GB';
						}
						$link		=	'http://www.seblod.com/v1/help/cck/'.$lang_tag.'/joomfish.php';
						//
						$bar->appendButton( 'Popup', 'help_joomfish', 'Help', $link, $width, $height );
					} else {
						array_pop( $bar->_bar );
						$bar->appendButton( @$bar->_bar[9][0], @$bar->_bar[9][1], @$bar->_bar[9][2], @$bar->_bar[9][3], @$bar->_bar[9][4], @$bar->_bar[9][5] );
						$link				=	'javascript: openCEK_New('.$config->article_creation_mode.', \'index.php?option=com_content&task=add\', 0);';
						$bar->_bar[9]		=	array( 0 => 'Link', 1 => $bar->_bar[8][1], 2 => $bar->_bar[8][2], 3 => $link, 4 => $bar->_bar[8][4], 5 => $bar->_bar[8][5] );
						$link				=	'javascript: openCEK_EditButton(0, \'index.php?option=com_content&sectionid=-1&task=edit&cid[]=\', 0);';
						$bar->_bar[8]		=	array( 0 => 'Link', 1 => $bar->_bar[7][1], 2 => $bar->_bar[7][2], 3 => $link, 4 => $bar->_bar[7][4], 5 => $bar->_bar[7][5] );
						$link				=	'javascript: trashArticle();';
						$bar->_bar[7]		=	array( 0 => 'Link', 1 => $bar->_bar[6][1], 2 => $bar->_bar[6][2], 3 => $link, 4 => $bar->_bar[6][4], 5 => $bar->_bar[6][5] );
						$link				=	'javascript: langTranslate();';
						$bar->_bar[6]		=	array( 0 => 'Link', 1 => 'translate_jseblod', 2 => JText::_( 'TRANSLATE' ), 3 => $link, 4 => TRUE, 5 => FALSE );
						$bar->appendButton( 'Popup', 'help', 'Help', JHelp::createURL( 'screen.content', false ), $width, $height );
						//
						array_unshift( $bar->_bar, array( 'Link', 'data_jseblod', 'IMPORT EXPORT', 'javascript: openDataProcess(0);', $width, $height ) );
					}
				} else {
					$bar->_bar[6][0]	=	'Link';
					$bar->_bar[6][3]	=	'javascript: trashArticle();';
					$bar->_bar[7][0]	=	'Link';
					$bar->_bar[7][3]	=	'javascript: openCEK_EditButton(0, \'index.php?option=com_content&sectionid=-1&task=edit&cid[]=\', 0);';
					$bar->_bar[8][0]	=	'Link';
					$bar->_bar[8][3]	=	'javascript: openCEK_New('.$config->article_creation_mode.', \'index.php?option=com_content&task=add\', 0);';
					array_pop( $bar->_bar );
					$bar->appendButton( 'Popup', 'help', 'Help', JHelp::createURL( 'screen.content', false ), $width, $height );
					//
					array_unshift( $bar->_bar, array( 'Link', 'data_jseblod', 'IMPORT EXPORT', 'javascript: openDataProcess(0);', $width, $height ) );
				}
				break;
			case 'com_users':
				$editL	=	$config->user_edition_mode;
				$editB	=	$config->user_edition2_mode;
				//
				$bar->_bar[2][0]	=	'Link';
				$bar->_bar[2][3]	=	'javascript: openCEK_EditButton(0, \'index.php?option=com_users&view=user&task=edit&cid[]=\', 2);';
				$bar->_bar[3][0]	=	'Link';
				$bar->_bar[3][3]	=	'javascript: openCEK_New('.$config->user_creation_mode.', \'index.php?option=com_users&task=add&edit=true\', 2);';
				array_pop( $bar->_bar );
				$bar->appendButton( 'Popup', 'help', 'Help', JHelp::createURL( 'screen.users', false ), $width, $height );
				array_unshift( $bar->_bar, array( 'Link', 'adduser', 'ADD PERSONAL CONTENT', 'javascript: openCEK_New('.$config->user_creation_mode.', \'\', 4);' ) );
				//
				array_unshift( $bar->_bar, array( 'Link', 'data_jseblod', 'IMPORT EXPORT', 'javascript: openDataProcess(2);', $width, $height ) );
				break;
			case 'com_templates':
				$cck	=	JRequest::getCmd( 'cck' );
				if ( $cck && $cck == 1 ) {
					array_shift( $bar->_bar );
				}
				array_pop( $bar->_bar );
				$bar->appendButton( 'Popup', 'help', 'Help', JHelp::createURL( 'screen.templates', false ), $width, $height );
				break;
			case 'com_media':
				$link			=	'index.php?option=com_cckjseblod&task=media&tmpl=component';
				array_pop( $bar->_bar );
				array_unshift( $bar->_bar, array( 'Popup', 'process', 'Process', $link, $width, $height ) );
				$bar->appendButton( 'Popup', 'help', 'Help', JHelp::createURL( 'screen.mediamanager', false ), $width, $height );
				break;
			default:
				break;
		}
		if ( ( JRequest::getVar( 'section' ) && JRequest::getVar( 'section' ) != 'com_content' ) || $option == 'com_templates' || $option == 'com_media' ) {
		} else {
			$confirmDelete	=	JText::_( 'Are you Sure you Want to Delete' );
			$alertTrash		=	JText::sprintf( 'Please make a selection from the list to', JText::_( 'Trash' ) );
			$alertPublish	=	JText::sprintf( 'Please make a selection from the list to', JText::_( 'Publish' ) );
			$alertUnpublish	=	JText::sprintf( 'Please make a selection from the list to', JText::_( 'Unpublish' ) );
			$alertTranslate	=	JText::sprintf( 'Please make a selection from the list to', JText::_( 'Translate' ) );
			$js = '
				function openCEK_New( task, url_off, action ) {
					var u_opt =	"'.$option.'";
					if ( $("toggle-cck") ) {
						if ( $("toggle-cck").checked != true ) {
							task = 0;
						}
					}
					if ( action == 4 ) {
						if ( $("limit") ) {
							var max	= $("limit").value;
							for ( i=0; i < max; i++ ) {
								var elem = "cb"+i;
								if ( $(elem) ) {
									if ( $(elem).checked == true ) {
										var userid = $(elem).value;
										break;
									}
								} else {
									break;
								}
							}
						}
						if ( ! userid ) {
							alert("Please select a User from the list first.");
							return;
						}
						var userpc	=	"&userid="+userid;
					} else {
						var userpc	=	"";
					}
					if ( task == 2 ) {
						if ( $("catid") ) {
							var cat_id = $("catid").value;
							var url = "index.php?option=com_cckjseblod&controller=interface&cat_id="+cat_id+"&u_opt="+u_opt+"&artid=0&act="+action+"&cck=1";
							window.location.href = url;	
							return;
						} else {
							var url = "index.php?option=com_cckjseblod&controller=interface&artid=0"+userpc+"&u_opt="+u_opt+"&act="+action+"&cck=1";
							window.location.href = url;
							return;
						}
					} else if ( task == 1 ) {
						if ( $("catid") ) {
							var cat_id = $("catid").value;
							var url="index.php?option=com_cckjseblod&controller=interface&cat_id="+cat_id+"&u_opt="+u_opt+"&artid=0&tmpl=component&act="+action+"&cck=2";
							SqueezeBox.fromElement(url,{handler:"iframe",size:{x:'.$width.',y:'.$height.'},closeWithOverlay:false});	
							return;
						} else {
							var url="index.php?option=com_cckjseblod&controller=interface&artid=0"+userpc+"&u_opt="+u_opt+"&tmpl=component&act="+action+"&cck=2";
							SqueezeBox.fromElement(url,{handler:"iframe",size:{x:'.$width.',y:'.$height.'},closeWithOverlay:false});	
							return;
						}
					} else {
						submitbutton(\'add\');
						return;
					}
				}
				function openCEK_EditButton( artid, url_off, action ) {
					var u_opt =	"'.$option.'";
					var task = '.$editB.';
					if ( $("toggle-cck") ) {
						if ( $("toggle-cck").checked != true ) {
							task = 0;
						}
					}
					if ( $("limit") ) {
						var max	= $("limit").value;
						for ( i=0; i < max; i++ ) {
							var elem = "cb"+i;
							if ( $(elem) ) {
								if ( $(elem).checked == true ) {
									var artid = $(elem).value;
									break;
								}
							} else {
								break;
							}
						}
					}
					if ( artid ) {
						if ( task == 2 ) {
							var url = "index.php?option=com_cckjseblod&controller=interface&artid="+artid+"&u_opt="+u_opt+"&act="+action+"&cck=1";
							window.location.href = url;
							return;
						} else if ( task == 1 ) {
							var url="index.php?option=com_cckjseblod&controller=interface&tmpl=component&artid="+artid+"&u_opt="+u_opt+"&act="+action+"&cck=2";
							SqueezeBox.fromElement(url,{handler:"iframe",size:{x:'.$width.',y:'.$height.'},closeWithOverlay:false});	
							return;
						} else {
							window.location.href = url_off+artid;
							return;
						}
					} else {
						alert("Please select an Element from the list to edit");
					}
				}
				function openCEK_EditLink( artid, url_off, action, cat, lang_id ) {
					lang_id = (lang_id) ? lang_id : 0;
					var u_opt =	"'.$option.'";
					var task = '.$editL.';
					if ( $("toggle-cck") ) {
						if ( $("toggle-cck").checked != true ) {
							task = 0;
						}
					}
					if ( artid ) {
						if ( task == 0 ) {
							var cats = "'.$config->categories_fullscreen.'";
							if ( cats ) {
								cats = cats+",";
								if ( cats.indexOf(cat+",") != -1 ) {
									task = 2;
								}
							}
						}
						if ( task == 2 ) {
							var url = "index.php?option=com_cckjseblod&controller=interface&artid="+artid+"&u_opt="+u_opt+"&act="+action+"&cck=1&lang_id="+lang_id;
							window.location.href = url;
							return;
						} else if ( task == 1 ) {
							var url="index.php?option=com_cckjseblod&controller=interface&tmpl=component&artid="+artid+"&u_opt="+u_opt+"&act="+action+"&cck=2&lang_id="+lang_id;
							SqueezeBox.fromElement(url,{handler:"iframe",size:{x:'.$width.',y:'.$height.'},closeWithOverlay:false});	
							return;
						} else {
							window.location.href = url_off+artid;
							return;
						}
					} else {
						alert("Please select an Element from the list to edit");
					}
				}
				function openPreview( artid ) {
					var lang = "'.$langC.'";
					if ( artid == 0 ) {
						var max	= $("limit").value;
						for ( i=0; i < max; i++ ) {
							var elem = "cb"+i;
							if ( $(elem) ) {
								if ( $(elem).checked == true ) {
									var artid = $(elem).value;
									break;
								}
							} else {
								break;
							}
						}
					}
					if ( artid ) {
						var url="'.$uri.'index.php?option=com_content&view=article&tmpl=component&id="+artid+"&lang="+lang;
						SqueezeBox.fromElement(url,{handler:"iframe",size:{x:'.$width.',y:'.$height.'}});
					} else {
						alert("Please select an Article from the list to preview");
					}
				}
				function openDataProcess(action) {
					if ( $("catid") ) {
						var cat_id = $("catid").value;
					}
					var max	= $("limit").value;
					var checkids = "";
					for ( i=0; i < max; i++ ) {
						var elem = "cb"+i;
						if ( $(elem) ) {
							if ( $(elem).checked == true ) {
								checkids = $(elem).value+","+checkids;
							}
						}
					}
					var url="index.php?option=com_cckjseblod&task=data&tmpl=component&cat_id="+cat_id+"&artids="+checkids+"&act="+action;
					SqueezeBox.fromElement(url,{handler:"iframe",size:{x:'.$width.',y:'.$height.'}});
				}
				function trashArticle() {
					if (document.adminForm.boxchecked.value==0) {
						alert( "'.$alertTrash.'" );
					} else {
						var max	= $("limit").value;
						var checkids = "";
						for ( i=0; i < max; i++ ) {
							var elem = "cb"+i;
							if ( $(elem) ) {
								if ( $(elem).checked == true ) {
									checkids = $(elem).value+","+checkids;
								}
							}
						}
						var url = "index.php?option=com_cckjseblod&controller=interface&task=removeAjax&format=raw&artids="+checkids;
						var t=new Ajax(url,{
							 method:"post",
							 update:"",
							 onComplete: function(){ submitbutton("remove"); }
						}).request();
					}
				}
				function trashCategory() {
					if (document.adminForm.boxchecked.value==0) {
						alert( "'.$alertTrash.'" );
					} else {
						var max	= $("limit").value;
						var checkids = "";
						for ( i=0; i < max; i++ ) {
							var elem = "cb"+i;
							if ( $(elem) ) {
								if ( $(elem).checked == true ) {
									checkids = $(elem).value+","+checkids;
								}
							}
						}
						var url = "index.php?option=com_cckjseblod&controller=interface&task=removeAjaxCategory&format=raw&catids="+checkids;
						var t=new Ajax(url,{
							 method:"post",
							 update:"",
							 onComplete: function(){ submitbutton("remove"); }
						}).request();
					}
				}
				function langPublish( state, artid ) {
					var lang = "'.$langC.'";
					if ( ! artid ) {
						if (document.adminForm.boxchecked.value==0) {
							if ( ! state ) {
								alert( "'.$alertUnpublish.'" );
							} else {
								alert( "'.$alertPublish.'" );
							}
							return;
						}
						var max	= $("limit").value;
						var checkids = "";
						for ( i=0; i < max; i++ ) {
							var elem = "cb"+i;
							if ( $(elem) ) {
								if ( $(elem).checked == true ) {
									checkids = $(elem).value+","+checkids;
								}
							}
						}
					} else {
						var checkids = artid+",";
					}
					if ( ! state ) {
						var url = "index.php?option=com_cckjseblod&task=langUnpublish&artids="+checkids+"&lang="+lang;
					} else {
						var url = "index.php?option=com_cckjseblod&task=langPublish&artids="+checkids+"&lang="+lang;
					}
					window.location.href = url;
					return;
				}
				function langTrash() {
					var lang = "'.$langC.'";
					if (document.adminForm.boxchecked.value==0) {
						alert( "'.$alertTrash.'" );
						return;
					}
					var max	= $("limit").value;
					var checkids = "";
					for ( i=0; i < max; i++ ) {
						var elem = "cb"+i;
						if ( $(elem) ) {
							if ( $(elem).checked == true ) {
								checkids = $(elem).value+","+checkids;
							}
						}
					}
					var url = "index.php?option=com_cckjseblod&task=langTrash&artids="+checkids+"&lang="+lang;
					window.location.href = url;
					return;
				}
				function langTranslate() {
					if (document.adminForm.boxchecked.value==0) {
						alert( "'.$alertTranslate.'" );
						return;
					}
					var max	= $("limit").value;
					var checkids = "";
					for ( i=0; i < max; i++ ) {
						var elem = "cb"+i;
						if ( $(elem) ) {
							if ( $(elem).checked == true ) {
								checkids = $(elem).value+","+checkids;
							}
						}
					}
					var url = "index.php?option=com_cckjseblod&task=langTranslate&artids="+checkids;
					window.location.href = url;
					return;
				}
				function openUSER_Stuff( artid, userid ) {
					var action = 0;
					if ( artid == -1 ) {
						var url = "index.php?option=com_content&filter_authorid="+userid;
						window.location.href = url;
						return;
					}
					if ( $("action_userstuff1") && $("action_userstuff2") ) {
						if ( $("action_userstuff1").checked ) {
							var action = 1;
						} else if ( $("action_userstuff2").checked ) {
							var action = 2;
						} else  {
							var action = 0;
						}
					}
					if ( artid != 0 && userid != 0 ) {
						if ( action == 1 ) {
							openPreview( artid );
						} else if ( action == 2 ) {
							var answer = confirm("'.$confirmDelete.'");
							if ( ! answer ) {
								return;
							} else {
								var url = "index.php?option=com_cckjseblod&controller=interface&task=remove&artid="+artid;
								window.location.href = url;
								return;
							}
						} else {
							var u_opt =	"'.$option.'";
							var task = '.$editL.';
							if ( $("toggle-cck") ) {
								if ( $("toggle-cck").checked != true ) {
									task = 0;
								}
							}
							if ( task == 2 ) {
								var url = "index.php?option=com_cckjseblod&controller=interface&artid="+artid+"&userid="+userid+"&u_opt="+u_opt+"&act=4&cck=1";
								window.location.href = url;
								return;
							} else if ( task == 1 ) {
								var url="index.php?option=com_cckjseblod&controller=interface&tmpl=component&artid="+artid+"&userid="+userid+"&u_opt="+u_opt+"&act=4&cck=2";
								SqueezeBox.fromElement(url,{handler:"iframe",size:{x:'.$width.',y:'.$height.'},closeWithOverlay:false});	
								return;
							} else {
								window.location.href = "index.php?option=com_content&sectionid=-1&task=edit&cid[]="+artid;
								return;
							}
						}
					}
					return;
				}
			';
			JHTML::_( 'behavior.modal' );
			$doc->addScriptDeclaration( $js );
		}
	}
}

echo $bar->render('toolbar');