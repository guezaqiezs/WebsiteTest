<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
jimport( 'joomla.html.pane' );
JHTML::_( 'behavior.modal' );
$init = @$this->contentType;

$this->document->addScript( _PATH_ROOT._PATH_CALENDAR.'calendar.js');
$this->document->addScript( _PATH_ROOT.'/plugins/editors/tinymce/jscripts/tiny_mce/tiny_mce.js');

//$this->document->addScript( _PATH_ROOT._PATH_SWAMPYBROWSER.'sb.js' );
//$javascript =	'';
//$this->document->addScriptDeclaration( $javascript );

$lang     =&  JFactory::getLanguage();
$langTag =   $lang->getTag();
if ( JFile::exists( JPATH_SITE._PATH_FORMVALIDATOR.$langTag.'_formvalidator.js' ) ) {
  $this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.$langTag.'_formvalidator.js' );
} else {
  $this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
}

$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );

$this->document->addScript( JURI::root( true ).'/media/system/js/tabs.js' );
$this->document->addStyleSheet( 'templates/khepri/css/icon.css' );

$tipsOnClick	=	( _ADMINFORM_ONCLICK ) ? _ADMINFORM_ONCLICK : 0;
$cck			=	$this->cck;
$brb			=	$this->brb;
$act    	  	= 	$this->act;
$cat_id			=	$this->cat_id;
$u_opt			=	$this->u_opt;
$u_task			=	$this->u_task;
$lang_id		=	$this->lang_id;
$lang_next		=	@$this->lang_next;
$e_name			=	$this->e_name;
$userid			=	@$this->userid;
$lang 			=&	JFactory::getLanguage();
$lang_tag		=	$lang->getTag();
$artId 		 	=	@$this->artId;

$cols = ( $act % 2 ) ? ( $act == 1 ? _CEK_COLUMN_CATEGORY : _CEK_COLUMN ) : ( ( $act == 2 || $act == 4 ) ? _CEK_COLUMN_USER : _CEK_COLUMN_ARTICLE );

$contentType	= ( @$this->contentType ) ? $this->contentType : '';

$fs	=	'index.php?option=com_cckjseblod&controller=interface&artid='.$artId.'&cck=1&act='.$act;
if ( $cck ) {
	if ( $cck == 2 ) {
		$lk		=	"javascript: window.parent.document.getElementById('sbox-window').close();";
		$action	=	'index.php?option='.$this->option.'&amp;controller='.$this->controller.'&amp;task=selection&amp;tmpl=component';
	} else {
		//$cols	=	( $act != '' ) ? $cols : _CEK_COLUMN;
		$action	= 	'index.php?option='.$this->option.'&amp;controller='.$this->controller.'&amp;task=selection';
		$u_lang	=	( $this->lang_id ) ? '&lang='.CCK_LANG_ShortCode( $this->lang_id ) : '';
		$lk2	=	( $act && $act > 0 ) ? (( $act == 1 ) ? 'index.php?option=com_categories&section=com_content' : 'index.php?option=com_users' ) : 'index.php?option=com_content'.$u_lang;
		$lk		=	( $this->brb ) ? ( ( $this->brb == 2 ) ? 'index.php?option=com_cckjseblod' : 'index.php?option=com_cckjseblod&controller=types' ) : $lk2;
	}
	$buttons	= array('New'		=> array( 'New', 'new_jseblod', "javascript: setPackLayout();", 'onclick' ),
						'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
						'Cancel'	=> array( 'Close', 'cancel_jseblod', $lk, 'href' ),
						'Divider'	=> array( 'Divider', 'divider', "#", '#' ),
						'Refresh' 	=> array( 'Refresh', 'refresh_jseblod', "javascript: this.location.reload();", 'href' ) );
	if ( $cck == 2 && $act != 3 ) {
		$buttons['Fullscr']	=	array( 'Fullscreen', 'redirect_jseblod', 'javascript: parent.location.href=\''.$fs.'\'', 'onclick' );
	}
} else {
	$action		= 'index.php?option='.$this->option.'&amp;controller='.$this->controller.'&amp;task=selection&amp;tmpl=component';
	$buttons = array('New'		=> array( 'New', 'new_jseblod', "javascript: setPackLayout();", 'onclick' ),
					 'Spacer'	=> array( 'Spacer', 'spacer', "#", '#' ),
					 'Cancel'	=> array( 'Close', 'cancel_jseblod', "javascript: window.parent.document.getElementById('sbox-window').close();", 'onclick' ),
					 'Divider'	=> array( 'Divider', 'divider', "#", '#' ),
					 'Refresh' 	=> array( 'Refresh', 'refresh_jseblod', "javascript: this.location.reload();", 'href' ) );
	if ( $act != 3 ) {
		$buttons['Fullscr']	=	array( 'Fullscreen', 'redirect_jseblod', 'javascript: parent.location.href=\''.$fs.'\'', 'onclick' );
	}
}

$javascript = '
		window.addEvent("domready", function(){
			new SmoothScroll({duration:1000});var AjaxTooltips=new MooTips($$(".ajaxTip"),{className:"ajaxTool",showOnClick:true,showOnMouseEnter:false,fixed:true});

			var content = "'.$contentType.'";			
			if ( content ) {
				setContentLayout( content );
			}
			
			if ( $("setSelectionLayout") ) {
				$("setSelectionLayout").addEvent("click", function(s) {
					s = new Event(s).stop();
					var cck	= "'.$cck.'";
					var brb	= "'.$brb.'";
					var act	= "'.$act.'";
					var cat_id	= "'.$cat_id.'";
					var u_opt	= "'.$u_opt.'";
					var u_task	= "'.$u_task.'";
					var lang_id	= "'.$lang_id.'";
					var pc = "'.$userid.'";
					var url="index.php?option=com_cckjseblod&controller=interface&format=raw&lang_id="+lang_id+"&cat_id="+cat_id+"&u_opt="+u_opt+"&u_task="+u_task+"&act="+act+"&pc="+pc+"&brb="+brb+"&cck="+cck;var InterfaceLayout=$("PushLayout");InterfaceLayout.innerHTML="<div style=\'width: 100px; height: 20px; border: 1px dashed #FFD700; background-color: #ffd; color: #666666; padding-top: 5px; padding-left: 20px;\'><strong>Ajax Loading ...</strong></div>";new Ajax(url,{method:"get",update:InterfaceLayout,evalScripts:true,onComplete:function(){var AjaxTooltips=new MooTips($$(".ajaxTip"),{className:"ajaxTool",showOnClick:true,showOnMouseEnter:false,fixed:true})}}).request();
				});
			}
		});
		var setPackLayout = function(){
			window.addEvent("domready", function(){
				var cck	= "'.$cck.'";
				var brb	= "'.$brb.'";
				var act	= "'.$act.'";
				var artid = "'.$artId.'";
				var cat_id = "'.$cat_id.'";
				var u_opt = "'.$u_opt.'";
				var u_task = "'.$u_task.'";
				var lang_id = "'.$lang_id.'";
				var e_name = "'.$e_name.'";
				var pc = "'.$userid.'";
				var e_content = (artid == -1) ? parent.$(e_name).value : "";
				var url = "index.php?option=com_cckjseblod&controller=interface&task=pack&format=raw&artid='.$artId.'&content_type="+content+"&lang_id="+lang_id+"&cat_id="+cat_id+"&u_opt="+u_opt+"&u_task="+u_task+"&act="+act+"&pc="+pc+"&brb="+brb+"&cck="+cck;
				var InterfaceLayout=$("PushLayout");InterfaceLayout.innerHTML="<div style=\'width: 100px; height: 20px; border: 1px dashed #FFD700; background-color: #ffd; color: #666666; padding-top: 5px; padding-left: 20px;\'><strong>Ajax Loading ...</strong></div>";new Ajax(url,{method:"post",update:InterfaceLayout,data:"e_content="+e_content,evalScripts:true,onComplete:function(){var JTooltips=new Tips($$(".hasTip"),{maxTitleChars:50,fixed:false});var AjaxTooltips=new MooTips($$(".ajaxTip"),{className:"ajaxTool"});$("setSelectionLayout").addEvent("click",function(s){s=new Event(s).stop();var url="index.php?option=com_cckjseblod&controller=interface&task=selection&format=raw&lang_id="+lang_id+"&cat_id="+cat_id+"&u_opt="+u_opt+"&u_task="+u_task+"&act="+act+"&pc="+pc+"&brb="+brb+"&cck="+cck;var InterfaceLayout=$("PushLayout");InterfaceLayout.innerHTML="<div style=\'width: 100px; height: 20px; border: 1px dashed #FFD700; background-color: #ffd; color: #666666; padding-top: 5px; padding-left: 20px;\'><strong>Ajax Loading ...</strong></div>";new Ajax(url,{method:"get",update:InterfaceLayout,evalScripts:true,onComplete:function(){new SmoothScroll({duration:1000});var AjaxTooltips=new MooTips($$(".ajaxTip"),{className:"ajaxTool",showOnClick:true,showOnMouseEnter:false,fixed:true})}}).request()})}}).request();
			});
		}
		
		var setContentLayout = function( content ){
			window.addEvent("domready", function(){
				var cck	= "'.$cck.'";
				var brb	= "'.$brb.'";
				var act	= "'.$act.'";
				var artid = "'.$artId.'";
				var pc = "'.$userid.'";
				var cat_id = "'.$cat_id.'";
				var u_opt = "'.$u_opt.'";
				var u_task = "'.$u_task.'";
				var lang_id = "'.$lang_id.'";
				var lang_next = "'.$lang_next.'";
				var e_name = "'.$e_name.'";
				var e_content = (artid == -1) ? parent.$(e_name).value : "";
				var url = "index.php?option=com_cckjseblod&controller=interface&task=content&format=raw&artid='.$artId.
						'&content_type="+content+"&lang_next="+lang_next+"&lang_id="+lang_id+"&cat_id="+cat_id+"&u_opt="+u_opt+"&u_task="+u_task+"&act="+act+"&pc="+pc+"&brb="+brb+"&cck="+cck;
				var InterfaceLayout = $("PushLayout");
				InterfaceLayout.innerHTML = "<div style=\'width: 100px; height: 20px; border: 1px dashed #FFD700; background-color: #ffd; color: #666666; padding-top: 5px; padding-left: 20px;\'><strong>Ajax Loading ...</strong></div>";
				new Ajax(url, {
					method: "post",
					update: InterfaceLayout,
					data:"e_content="+e_content,
					evalScripts:true,
					onComplete: function(){
						
						

						
						
						var JTooltips = new Tips($$(".hasTip"), { maxTitleChars: 50, fixed: false});
						var adminFormValidator = new FormValidator( $("adminForm") );
						var AjaxTooltips = new MooTips($$(".ajaxTip"), {
							className: "ajaxTool"
						});
						var tipsOnClick = "'.$tipsOnClick.'";
						if(tipsOnClick==1){var AjaxTooltips2=new MooTips($$(".DescriptionTip"),{className:"ajaxTool",showOnClick:true,showOnMouseEnter:false,fixed:true})}else{var AjaxTooltips2=new MooTips($$(".DescriptionTip"),{className:"ajaxTool",fixed:true})}$("setSelectionLayout").addEvent("click",function(s){s=new Event(s).stop();var url="index.php?option=com_cckjseblod&controller=interface&task=selection&format=raw&lang_id="+lang_id+"&cat_id="+cat_id+"&u_opt="+u_opt+"&u_task="+u_task+"&act="+act+"&pc="+pc+"&brb="+brb+"&cck="+cck;var InterfaceLayout=$("PushLayout");InterfaceLayout.innerHTML="<div style=\'width: 100px; height: 20px; border: 1px dashed #FFD700; background-color: #ffd; color: #666666; padding-top: 5px; padding-left: 20px;\'><strong>Ajax Loading ...</strong></div>";new Ajax(url,{method:"get",update:InterfaceLayout,evalScripts:true,onComplete:function(){new SmoothScroll({duration:1000});var AjaxTooltips=new MooTips($$(".ajaxTip"),{className:"ajaxTool",showOnClick:true,showOnMouseEnter:false,fixed:true})}}).request()});new Accordion($$(".panel h3.jpane-toggler"),$$(".panel div.jpane-slider"),{onActive:function(toggler,i){toggler.addClass("jpane-toggler-down");toggler.removeClass("jpane-toggler")},onBackground:function(toggler,i){toggler.addClass("jpane-toggler");toggler.removeClass("jpane-toggler-down")},duration:300,opacity:false});$$("dl.tabs").each(function(tabs){new JTabs(tabs,{})});SqueezeBox.initialize({});$$("a.modal").each(function(el){el.addEvent("click",function(e){new Event(e).stop();SqueezeBox.fromElement(el)})});
					}
				}).request();
			});
		}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<div id="InterfaceLayout">
	<div id="PushLayout" style="padding-top:1px;">
	
	<?php if ( ! @$contentType ) { ?>
	
	<form action="<?php echo $action; ?>" method="post" id="adminForm" name="adminForm">
	
	<div id="modal-top">
		<fieldset class="adminform modal-bg-toolbar">
			<div class="header icon-48-interface" style="float: left; color: brown;">
				<?php echo JText::_( 'CONTENT MANAGER' ) . ': <small><small>[ '.JText::_( 'SELECT CONTENT TYPE' ).' ]</small></small>'; ?>
			</div>
			<div style="float: right">
				<?php HelperjSeblod_Display::quickToolbar( $buttons ); ?>
			</div>
		</fieldset>   
		<fieldset class="adminform">
			<table>
				<tr>
					<td align="left" width="100%">
						<?php echo '<font color="grey"># ' . JText::_( 'FILTER IN' ) . '</font>';
							  echo $this->lists['filter_search']; ?>
						<input class="text_area" type="text" id="search" name="search" value="<?php echo $this->lists['search'];?>" style="text-align: center;" />
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
						<button onclick="this.form.getElementById('filter_category').value='0';this.form.submit();"><?php echo JText::_( 'Search' ); ?></button>
						<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_search').value='0';this.form.getElementById('filter_category').value='0';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
					<td nowrap="nowrap">
						<?php echo $this->lists['category']; ?>
					</td>
				</tr>
			</table>

			<?php if ( $cols >= 2 && $cols <= 5 ) { ?>
            
            <div id="editcell">
                <table class="adminlist" cellpadding="1">
                <thead>
					<tr>	
						<th height="28" class="title" colspan="<?php echo $cols * 2; ?>">
							<?php echo '# '.JText::_( 'Title' ); ?>
						</th>
                	</tr>			
				</thead>
                <tbody>
                <?php
                $altRow	=	0;
                $count	=	count( $this->typesItems );
                $pct	=	floor( 100 / $cols );
                $rows	=	ceil( $count / $cols );
                $posn	=	0;
                do {
                ?>
                    <tr class="<?php echo 'row' . $altRow; ?>" valign="top">
                    <?php
                    $altRow	=	1 - $altRow;
                    for ( $col = 0; $col < $cols; ++$col ) :
                        if ( ( $mod = $posn + $col * $rows ) >= $count ) : ?>
                            <td width="<?php echo $pct; ?>%">&nbsp;</td>
                            <?php
                            continue;
                        endif;
                        $row						=&	$this->typesItems[$mod];
						$tooltips['description']	=	HelperjSeblod_Display::quickTooltipAjax( 'Description', 'types', 'description', $row->id );
                        ?>
						<style type="text/css">
                            .typeColor<?php echo $mod; ?> {
                                width: 20px;
                                height: 14px;
                                background-color: <?php echo $row->categorycolor; ?>;
                                color: <?php echo $row->categorycolorchar; ?>;
                                padding-top:3px;
                                padding-bottom:3px;
								margin-top:5px;
								margin-right:6px;
                                border: none;
                            }
                        </style>
                        <td width="<?php echo $pct; ?>%">
                            <div class="title-left">
                                <table>
                                    <tr>
                                        <td style="border: none;">
                                            <?php echo $tooltips['description']; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="title-left">
                                <?php echo _NBSP; ?><a href="#" onclick="setContentLayout('<?php echo $row->name ?>');"><?php echo $row->title; ?></a>
                                <?php echo '<br />' . _NBSP . $row->name; ?>
                            </div>
                            <div style="float: right">
	                            <span class="editlinktip hasTip" title="<?php echo JText::_( 'FILTER IN CATEGORY' ); ?>::<?php echo $row->categorytitle; ?>">
                                    <a href="javascript: document.getElementById('filter_search').value='5';document.getElementById('search').value='<?php echo $row->category; ?>
                                    	';submitform();" border="0">
                                        <div class="<?php echo ( $row->categorycolor ) ? 'typeColor'.$mod : 'typeNoColor'; ?>" style="vertical-align: middle;">
                                            <strong>
												<?php echo strlen( $row->categoryintrochar ) == 2 ? '&nbsp;'.$row->categoryintrochar : '&nbsp;&nbsp;'.$row->categoryintrochar ; ?>
                                            </strong>
                                        </div>
                                    </a>
                                </span>
                            </div>
                        </td>
                        <?php
                    endfor;
                    ++$posn;
                    ?>
                    </tr>
                <?php
                } while ( $posn < $rows );
                ?>
                </tbody>
                <tfoot>
                    <tr>
						<td height="28" colspan="<?php echo $cols * 2; ?>" id="pagination-bottom">
                        	<a href="#modal-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a>
                        </td>
                    </tr>
                </tfoot>
                </table>
            </div>
            
            <?php } else { ?>
            
			<div id="editcell">
				<table class="adminlist">
				<thead>
					<tr>
						<th width="30">
							<?php HelperjSeblod_Display::quickRefreshPage(); ?>
						</th>			
						<th class="title">
							<?php echo JHTML::_( 'grid.sort', 'Title', 's.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
						</th>
						<th class="title" width="35%" colspan="2">
							<?php echo JHTML::_( 'grid.sort', 'Category', 'categorytitle', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
						</th>
						<th width="30">
							<?php echo JHTML::_( 'grid.sort', 'ID', 's.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
						</th>
					</tr>			
				</thead>
				<?php
				$k = 0;
				for ( $i = 0, $n = count( $this->typesItems ); $i < $n; $i++ )
				{
					$row =& $this->typesItems[$i];
					$tooltips['description'] = HelperjSeblod_Display::quickTooltipAjax( 'Description', 'types', 'description', $row->id );
					?>
				<style type="text/css">
					.typeColor<?php echo $i; ?> {
						width: 20px;
						height: 14px;
						background-color: <?php echo $row->categorycolor; ?>;
						color: <?php echo $row->categorycolorchar; ?>;
						padding-top:3px;
						padding-bottom:3px;
						vertical-align: middle;
						border: none;
					}
				</style>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
							<?php HelperjSeblod_Display::quickSlideTo( 'pagination-bottom', $i + 1 ); ?>
						</td>
					<td align="left">
						<div class="title-left">
							<table>
								<tr>
									<td style="border: none;">
										<?php echo $tooltips['description']; ?>
									</td>
								</tr>
							</table>
						</div>
                        <div class="title-left">
                        
                            <?php
                            echo _NBSP2; ?><a href="#" onclick="setContentLayout('<?php echo $row->name ?>');"><?php echo $row->title; ?></a>
                            <?php echo '<br />' . _NBSP2 . $row->name; ?>
                        </div>
						</td>
					<td align="center" width="5%" valign="middle">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'FILTER IN CATEGORY' ); ?>::<?php echo $row->categorytitle; ?>">
							<a href="javascript: document.getElementById('filter_search').value='5';document.getElementById('search').value='<?php echo $row->category; ?>';submitform();" border="0">
								<div class="<?php echo ( $row->categorycolor ) ? 'typeColor'.$i : 'typeNoColor'; ?>" style="vertical-align: middle;">
									<strong><?php echo $row->categoryintrochar; ?></strong>
								</div>
							</a>
						</span>
					</td>
					<td align="center">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'FILTER BY CATEGORY' ); ?>::<?php echo JText::_( $row->categorytitle ); ?>">
							<a href="javascript: document.getElementById('filter_category').value='<?php echo $row->category; ?>';submitform();" style="color: #666666">
								<?php echo $row->categorytitle; ?>
							</a>
						</span>
					</td>
                    <td align="center">
                        <?php if ( ! $cck || $cck == 2 ) { ?>
                            <?php HelperjSeblod_Display::quickSlideTo( 'modal-top', $row->id ); ?>
                        <?php } else { ?>
                            <?php HelperjSeblod_Display::quickSlideTo( 'border-top', $row->id ); ?>
                        <?php } ?>
                    </td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				<tfoot>
					<tr>
						<?php if ( ! $cck || $cck == 2 ) { ?>
						<td><a href="#modal-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
						<td colspan="3" id="pagination-bottom"><?php echo ( $this->pagination ) ? $this->pagination->getListFooter() : ''; ?></td>
						<td><a href="#modal-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
						<?php } else { ?>
						<td><a href="#border-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
						<td colspan="3" id="pagination-bottom"><?php echo ( $this->pagination ) ? $this->pagination->getListFooter() : ''; ?></td>
						<td><a href="#border-top" style="color: gray; text-decoration: none;">&nbsp;<img src="images/sort_asc.png" />&nbsp;</a></td>
						<?php } ?>
					</tr>
				</tfoot>
				</table>
			</div>
            
            <?php } ?>
            
		</fieldset>
        
        <?php if ( $cck ) { ?>
	    <span class="note2_jseblod" style="border: 1px dashed #DDDDDD; padding: 6px; text-align:center; margin:0 10px 10px;">
			<?php echo JText::_( 'CONTENT EDITION KIT FULLSCREEN FOR ARTICLES ONLY' ); ?>
        </span>
	    <?php } ?>
	</div>

	<div class="clr"></div>

	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="cck" value="<?php echo $this->cck; ?>" />
  	<input type="hidden" name="brb" value="<?php echo $this->brb; ?>" />
  	<input type="hidden" name="act" value="<?php echo $this->act; ?>" />
  	<input type="hidden" name="cat_id" value="<?php echo @$this->cat_id; ?>" />
  	<input type="hidden" name="u_opt" value="<?php echo @$this->u_opt; ?>" />
  	<input type="hidden" name="u_task" value="<?php echo @$this->u_task; ?>" />
  	<input type="hidden" name="lang_id" value="<?php echo @$this->lang_id; ?>" />
	<input type="hidden" name="e_name" value="<?php echo @$this->e_name; ?>" />
	<input type="hidden" name="userid" value="<?php echo @$this->userid; ?>" />
  	<input type="hidden" name="selection" value="1" />
	
	<?php echo JHTML::_('form.token'); ?>
	</form>
	<?php
	HelperjSeblod_Display::quickCopyright();
	?><?php if ( ! $cck ) { echo '<br />'; } } ?>
	
	</div>
</div>
