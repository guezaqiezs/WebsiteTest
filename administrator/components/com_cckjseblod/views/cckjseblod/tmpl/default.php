<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.modal' );
$rel	=	"{handler: 'iframe', size: {x: 800, y: 500}}";
//
JHTML::_( 'script', 'ToolTip_compressed.js', 'media/jseblod/tooltips/script/', true );
JHTML::_( 'stylesheet', 'stylesheet.css', 'media/jseblod/tooltips/' );
?>

<script type="text/javascript">
	window.addEvent('domready', function(){
		new ToolTip({tipper:'welcome', message:'Let\'s try SEBLOD 2.x for Joomla! 1.7 ! : )'});
	});
</script>

<style type="text/css">
.newsIntro {
	float: left;
	overflow: hidden;
	border: none;
	width: 85%;
	height: 30px;
	padding: 0px 5px 0px 5px;
}
</style>

<form action="index.php?option=<?php echo $this->option; ?>" method="post" id="adminForm" name="adminForm">

<div class="col width-100">
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'JSEBLOD CCK NEWS' ); ?></legend>
		<table class="admintable" width="100%">
			<tr>
            	<td width="90%" style="overflow: auto;">
					<?php
					$lang		=&	JFactory::getLanguage();
					$lang_tag	=	$lang->getTag();
					if ( $lang_tag == 'fr-FR' ) {
	                    echo JHTML::_( 'iframe', 'http://www.seblod.com/v1/news_intro_fr-FR.php', 'newsIntro', array('class' => 'newsIntro') );
					} else {
	                    echo JHTML::_( 'iframe', 'http://www.seblod.com/v1/news_intro.php', 'newsIntro', array('class' => 'newsIntro') );
					}
					?>
				</td>
                <td width="10%">
					<div class="button2-left">
						<div class="next">
							<a class="toolbar modal" rel="<?php echo $rel; ?>" href="http://www.seblod.com/v1/news.php" alt="News"><?php echo JText::_( 'READ' ); ?></a>
						</div>
					</div>
				</td> 
			</tr>
		</table>
	</fieldset>
</div>

<div class="clr"></div>

<div class="col width-50">
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'CONTROL PANEL' ); ?></legend>
		<table class="admintable">
			<tr>
				<td>
					<div id="cpanel">
                    	<div style="float: left">
						<?php
						$link = _LINK_CCKJSEBLOD_TEMPLATES;
						HelperjSeblod_Display::quickiconButton( $link, 'icon-48-templates.png', JText::_( 'TEMPLATE MANAGER' ), 'template' );
						
						$link = _LINK_CCKJSEBLOD_TYPES;
						HelperjSeblod_Display::quickiconButton( $link, 'icon-48-types.png', JText::_( 'CONTENT TYPE MANAGER BR' ), 'type' );
						
						$link = _LINK_CCKJSEBLOD_ITEMS;
						HelperjSeblod_Display::quickiconButton( $link, 'icon-48-items.png', JText::_( 'FIELD MANAGER' ), 'item' );
						
						$link = _LINK_CCKJSEBLOD_SEARCHS;
						HelperjSeblod_Display::quickiconButton( $link, 'icon-48-searchs.png', JText::_( 'SEARCH TYPE MANAGER BR' ), 'search' );
						
						$link = _LINK_CCKJSEBLOD_PACKS;
						HelperjSeblod_Display::quickiconButton( $link, 'icon-48-packs.png', JText::_( 'PACK MANAGER' ), 'pack' );
						
						$link = _LINK_CCKJSEBLOD_CONFIGURATION;
						HelperjSeblod_Display::quickiconButton( $link, 'icon-48-configuration.png', JText::_( 'CONFIG' ), 'configuration' );
						?>
                        </div>
                        <div class="clr"></div>
                    	<div style="float: left;">
                      	<span style="padding:10px; font-weight:bold; font-style:italic;"><?php echo JText::_( 'ADDONS FOR JSEBLOD CCK' ); ?></span>
                        <?php
						$db		=&	JFactory::getDBO();
						$db->setQuery( 'SELECT a.id FROM #__components AS a WHERE a.option = "com_cckjseblod_webservice"' );
						$res	=	$db->loadResult();
						if ( (int)$res > 0 ) {
							$link	=	'index.php?option=com_cckjseblod_webservice';
							HelperjSeblod_Display::quickiconButton( $link, 'icon-48-webservice.png', JText::_( 'ADDON JOB WEBSERVICE' ), 'addon' );
						} else {
							echo JText::_( 'NOT FOUND' ).'..';
						}
						?>
                        <div>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="col width-50">
	<fieldset class="adminform" style="padding-bottom: 6px;">
		<legend class="legend-border"><?php echo JText::_( 'OVERVIEW PANEL' ); ?></legend>
		<table class="admintable" style="margin-top:1px">
			<tr height="26">
				<td width="100" align="right" class="key">
					<?php echo JText::_( 'Name' ); ?>:
				</td>
				<td colspan="2"><?php echo 'SEBLOD 1.x ( Content Contruction Kit )'; ?></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<img src="components/com_cckjseblod/assets/images/list/icon-16-balloon-left.png" border="0" alt=" " style="display: none; width: 1px; height: 1px;" />
					<img src="components/com_cckjseblod/assets/images/list/icon-16-quick-edit.png" border="0" alt=" " style="display: none; width: 1px; height: 1px;" />
					<?php echo JText::_( 'Description' ); ?>:
				</td>
				<td colspan="2"><?php echo JText::_( 'CCKJSEBLOD DESCRIPTION' ); ?></td>
			</tr>
			<tr height="26">
				<td width="100" align="right" class="key">
					<?php echo JText::_( 'Version' ); ?>:
				</td>
				<td colspan="2"><?php echo '<span style="color:#004dbc; font-weight:bold;">'. _VERSION . '</span>'; ?></td>
			</tr>
            <tr height="26">
				<td width="100" align="right" class="key">
					<?php echo JText::_( 'Copyright' ); ?>:
				</td>
				<td colspan="2"><?php echo JText::_( 'CCKJSEBLOD COPYRIGHT' ); ?></td>
			</tr>
			<tr height="26">
				<td width="100" align="right" class="key">
					<?php echo JText::_( 'License' ); ?>:
				</td>
				<td colspan="2"><?php echo JText::_( 'CCKJSEBLOD LICENSE' ); ?> <a id="welcome" href="http://www.seblod.com" style="color:#004dbc;" target="_blank"><strong>http://www.seblod.com</strong></a></td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" id="search_string" name="search_string" value="" />
<input type="hidden" id="replace_string" name="replace_string" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php
HelperjSeblod_Display::quickCopyright();
?>