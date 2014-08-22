<?php
/**
 * @version		$Id: install.php 24 2009-03-31 04:07:57Z eddieajau $
 * @package		JXtended.Reports
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// load the component language file
$language = &JFactory::getLanguage();
$language->load('com_cckjseblod');

//$nPaths = $this->_paths;
$status = new JObject();
$status->modules	=	array();
$status->plugins	=	array();
$status->templates	=	array();

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * MODULE INSTALLATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$modules = &$this->manifest->getElementByPath('modules');
if (is_a($modules, 'JSimpleXMLElement') && count($modules->children())) {

	foreach ($modules->children() as $module)
	{
		$mname		= $module->attributes('module');
		$mclient	= JApplicationHelper::getClientInfo($module->attributes('client'), true);

		// Set the installation path
		if (!empty ($mname)) {
			$this->parent->setPath('extension_root', $mclient->path.DS.'modules'.DS.$mname);
		} else {
			$this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.JText::_('No module file specified'));
			return false;
		}

		/*
		 * If the module directory already exists, then we will assume that the
		 * module is already installed or another module is using that directory.
		 */
		if (file_exists($this->parent->getPath('extension_root'))&&!$this->parent->getOverwrite()) {
			$this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.JText::_('Another module is already using directory').': "'.$this->parent->getPath('extension_root').'"');
			return false;
		}

		// If the module directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		/*
		 * Since we created the module directory and will want to remove it if
		 * we have to roll back the installation, lets add it to the
		 * installation step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		$element = &$module->getElementByPath('files');
		if ($this->parent->parseFiles($element, -1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy language files
		$element = &$module->getElementByPath('languages');
		if ($this->parent->parseLanguages($element, $mclient->id) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy media files
		$element = &$module->getElementByPath('media');
		if ($this->parent->parseMedia($element, $mclient->id) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		$mtitle		= $module->attributes('title');
		$mposition	= $module->attributes('position');
		$mordering	= $module->attributes('ordering');
		$mstate		= $module->attributes('state');
		$mreplace	= $module->attributes('replace');

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */
		$db = &JFactory::getDBO();

		// Check to see if a plugin by the same name is already installed
		$query = 'SELECT `id`' .
				' FROM `#__modules`' .
				' WHERE module = '.$db->Quote($mname);
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}
		$id = $db->loadResult();

		// Was there a plugin already installed with the same name?
		if ($id) {

			if (!$this->parent->getOverwrite())
			{
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.JText::_('Module').' "'.$pname.'" '.JText::_('already exists!'));
				return false;
			}

		} else {	
			if ($mtitle && $mposition) {
				$row = & JTable::getInstance('module');
				$row->title		= $mtitle;
				$row->ordering	= ( $mordering == 0 ) ? 0 : $row->getNextOrder("position='".$mposition."'");
				$row->position	= $mposition;
				$row->showtitle	= 0;
				$row->iscore	= 0;
				$row->access	= ($mclient->id) == 1 ? 2 : 0;
				$row->client_id	= $mclient->id;
				$row->module	= $mname;
				$row->published	= $mstate;
				$row->params	= '';
	
				if (!$row->store()) {
					// Install failed, roll back changes
					$this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.$db->stderr(true));
					return false;
				}
			}
		}
		if ($mreplace) {
			$db->setQuery(
				'UPDATE `#__modules` SET' .
				' `published` = 0' .
				' WHERE `module` LIKE "'.$mreplace.'"'
			);
			if (!$db->Query()) {
				$this->parent->abort(JText::_('Module Toolbar Error').': '.$db->stderr(true));
				return false;
			}	
		}

		$status->modules[] = array('name'=>$mname,'client'=>$mclient->name);
	}
}


/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * PLUGIN INSTALLATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$plugins = &$this->manifest->getElementByPath('plugins');
if (is_a($plugins, 'JSimpleXMLElement') && count($plugins->children())) {

	foreach ($plugins->children() as $plugin)
	{
		$pname		= $plugin->attributes('plugin');
		$pgroup		= $plugin->attributes('group');
		$pnote		= $plugin->attributes('note');

		// Set the installation path
		if (!empty($pname) && !empty($pgroup)) {
			$this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.$pgroup);
		} else {
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('No plugin file specified'));
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// If the plugin directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		/*
		 * If we created the plugin directory and will want to remove it if we
		 * have to roll back the installation, lets add it to the installation
		 * step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		$element = &$plugin->getElementByPath('files');
		if ($this->parent->parseFiles($element, -1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy all necessary files
		$element = &$plugin->getElementByPath('languages');
		if ($this->parent->parseLanguages($element, 1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy media files
		$element = &$plugin->getElementByPath('media');
		if ($this->parent->parseMedia($element, 1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */
		$db = &JFactory::getDBO();

		// Check to see if a plugin by the same name is already installed
		$query = 'SELECT `id`' .
				' FROM `#__plugins`' .
				' WHERE folder = '.$db->Quote($pgroup) .
				' AND element = '.$db->Quote($pname);
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}
		$id = $db->loadResult();

		// Was there a plugin already installed with the same name?
		if ($id) {

			if (!$this->parent->getOverwrite())
			{
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Plugin').' "'.$pname.'" '.JText::_('already exists!'));
				return false;
			}

		} else {
			$row =& JTable::getInstance('plugin');
			$pnote = ( $pnote ) ? $pnote : '';
			$row->name = JText::_(ucfirst($pgroup)).' - '.JText::_(ucfirst($pname)).' '.$pnote;
			$row->ordering = 0;
			$row->folder = $pgroup;
			$row->iscore = 0;
			$row->access = 0;
			$row->client_id = 0;
			$row->element = $pname;
			$row->published = 1;
			$row->params = '';

			if (!$row->store()) {
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
				return false;
			}
		}

		$status->plugins[] = array('name'=>$pname,'group'=>$pgroup);
	}
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * TEMPLATE INSTALLATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$templates = &$this->manifest->getElementByPath('templates');
if (is_a($templates, 'JSimpleXMLElement') && count($templates->children())) {

	foreach ($templates->children() as $template)
	{
		$tname		= $template->attributes('template');
		$tgroup		= $template->attributes('group');
		$ttype		= $template->attributes('type');

		// Set the installation path
		if (!empty($tname) && !empty($tgroup)) {
			$this->parent->setPath('extension_root', JPATH_ROOT.DS.'templates'.DS.str_replace( 'tpl_', '', $tname));
		} else {
			$this->parent->abort(JText::_('Template').' '.JText::_('Install').': '.JText::_('No template file specified'));
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// If the template directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('Template').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		/*
		 * If we created the template directory and will want to remove it if we
		 * have to roll back the installation, lets add it to the installation
		 * step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		$element = &$template->getElementByPath('files');
		if ($this->parent->parseFiles($element, -1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy all necessary files
		$element = &$template->getElementByPath('languages');
		if ($this->parent->parseLanguages($element, 1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy media files
		$element = &$template->getElementByPath('media');
		if ($this->parent->parseMedia($element, 1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		$status->templates[] = array('name'=>$tname,'group'=>$tgroup,'type'=>$ttype);
	}
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * CATEGORIES CREATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$categories = &$this->manifest->getElementByPath('categories');
if (is_a($categories, 'JSimpleXMLElement') && count($categories->children())) {

	foreach ($categories->children() as $category)
	{
		$cname		= $category->attributes('category');
		$ctitle		= $category->attributes('title');
		$cstate		= $category->attributes('state');
		$cinherited	= $category->attributes('inherited');
		$csection	= 'jseblod-cck';
		
		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */
		$db = &JFactory::getDBO();

		// Check to see if a category by the same name is already created
		$query = 'SELECT `id`' .
				' FROM `#__sections`' .
				' WHERE alias = '.$db->Quote($csection);
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Section').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}
		$csectionid = $db->loadResult();
		
		if ($csectionid) {
			
		} else {
			$row = & JTable::getInstance('section');
			$row->title				= 'jSeblod CCK';
			$row->alias				= $csection;
			$row->scope				= 'content';
			$row->ordering			= 0;
			$row->image_position	= 'left';
			$row->published			= 1;
			$row->params			= '';

			if (!$row->store()) {
				// Creation failed, roll back changes
				$this->parent->abort(JText::_('Section').' '.JText::_('Install').': '.$db->stderr(true));
				return false;
			}
			$csectionid = $row->id;
		}
		
		// Check to see if a category by the same name is already created
		$query = 'SELECT `id`' .
				' FROM `#__categories`' .
				' WHERE alias = '.$db->Quote($cname);
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Category').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}
		$id = $db->loadResult();
		
		// Was there a category already created with the same name?
		if ($id) {
			if ( $cinherited ) {
				
				$query	= 'UPDATE #__jseblod_cck_items'
						. ' SET location = "'.$id.'"'
						. ' WHERE name = "'.$cinherited.'"'
						;
				$db->setQuery($query);
				if (!$db->Query()) {
					$this->parent->abort(JText::_('Category').' '.JText::_('Install').': '.$db->stderr(true));
					return false;
				}
			}
		} else {	
			if ($ctitle && $cname) {
				$row = & JTable::getInstance('category');
				$row->title				= $ctitle;
				$row->alias				= $cname;
				$row->section			= $csectionid;
				$row->ordering			= $row->getNextOrder("section='".$csection."'");
				$row->image_position	= 'left';
				$row->published			= $cstate;
				$row->params			= '';
	
				if (!$row->store()) {
					// Creation failed, roll back changes
					$this->parent->abort(JText::_('Category').' '.JText::_('Install').': '.$db->stderr(true));
					return false;
				}
			}
			
			if ( $cinherited ) {
				
				$query	= 'UPDATE #__jseblod_cck_items'
						. ' SET location = "'.$row->id.'"'
						. ' WHERE name = "'.$cinherited.'"'
						;
				$db->setQuery($query);
				if (!$db->Query()) {
					$this->parent->abort(JText::_('Category').' '.JText::_('Install').': '.$db->stderr(true));
					return false;
				}
			}
		}
		
		$status->categories[] = array('name'=>$cname);
	}
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * SQUEEZEBOX STYLE (Joomla 1.6)
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
 
$cssFile	=	JPATH_SITE.DS.'media'.DS.'system'.DS.'css'.DS.'modal.css';
$oldCss		=	JPATH_SITE.DS.'media'.DS.'system'.DS.'css'.DS.'modal.css.jseblod.old';
if ( ! JFile::exists( $oldCss ) ) {
	JFile::move( $cssFile, $oldCss );
	JFile::move( JPATH_SITE.DS.'media'.DS.'jseblod'.DS.'_joomla16'.DS.'modal.css', $cssFile );
}

$query		=	( 'SHOW COLUMNS FROM #__categories' );
$db->setQuery( $query );
$columns	=	$db->loadResultArray();
if ( array_search( 'created_user_id', $columns ) === false ) {
	$query		=	( 'ALTER TABLE #__categories ADD created_user_id int(10) unsigned NOT NULL DEFAULT "62"' );
	$db->setQuery( $query );
	if (!$db->Query()) {
		$this->parent->abort(JText::_('Category Update').': '.$db->stderr(true));
		return false;
	}
}

$bugFix		=	JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'editor.php';
$bugFile	=	JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'editor.php';
if ( JFile::exists( $bugFix ) ) {
	if ( JFile::exists( $bugFile ) ) {
		JFile::delete( $bugFile );	
	}
	JFile::move( $bugFix, $bugFile );
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * UPDATE DATAS
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$db			=	&JFactory::getDBO();
$query		=	'SELECT s.params FROM #__components AS s WHERE s.option LIKE "com_cckjseblod"';
$db->setQuery( $query );
$current	=	$db->loadResult();
$current	=	( $current == '' ) ? '1.5.0.RC3' : substr( strstr( $current, '=' ), 1 );

$versionFile	=	JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'_VERSION.php';
if ( JFile::exists( $versionFile ) ) {
	$version	=	JFile::read( $versionFile );
}

$updates		=	array();
$updates[0]		=	'1.5.0.RC3';
$updates[1]		=	'1.5.0.RC4';
$updates[2]		=	'1.5.0.RC4-2';
$updates[3]		=	'1.5.0.RC5';
$updates[4]		=	'1.5.0.RC6';
$updates[5]		=	'1.5.0.RC7';
$updates[6]		=	'1.5.0.RC8';
$updates[7]		=	'1.5.0.RC9';
$updates[8]		=	'1.5.0.STABLE';
$updates[9]		=	'1.5.1';
$updates[10]	=	'1.5.2';
$updates[11]	=	'1.5.3';
$updates[12]	=	'1.5.5';
$updates[13]	=	'1.6.0';
$updates[14]	=	'1.6.1';
$updates[15]	=	'1.6.2';
$updates[16]	=	'1.7.0.RC1';
$updates[17]	=	'1.7.0.RC2';
$updates[18]	=	'1.7.0.RC3';
$updates[19]	=	'1.7.0.RC4';
$updates[20]	=	'1.8.0';
$updates[21]	=	'1.8.1';
$updates[22]	=	'1.8.2';
$updates[23]	=	'1.9.0';

$i	=	array_search( $current, $updates );
$n	=	array_search( $version, $updates );
for ( $i = $i + 1; $i <= $n; $i++ ) {
	$updateFile	=	JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cckjseblod'.DS.'install'.DS.'upgrades'.DS.'upgrade.'.strtolower($updates[$i]).'.cckjseblod.sql';
	if ( JFile::exists( $updateFile ) ) {
		$query	=	JFile::read( $updateFile );
		$db->setQuery( $query );
		$db->queryBatch();
	}
}

$query	= 'UPDATE #__components AS s'
		. ' SET s.params = "version='.$version.'"'
		. ' WHERE s.option LIKE "com_cckjseblod"'
		;
$db->setQuery( $query );
$db->query();

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * OUTPUT TO SCREEN
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
 
/**
 * Quick Toolbar
 **/
function quickToolbar( $buttons, $class )
{ 	?>
	<div id="toolbar" class="<?php echo $class; ?>">
	<table class="<?php echo $class; ?>">
		<tr>
			<?php
			foreach( $buttons AS $item ) { ?>
				<td id="toolbar-<?php echo $item[1]; ?>" class="button">
					<a class="<?php echo $class; ?>" href="<?php echo $item[2]; ?>" target="_self">
						<span class="icon-32-<?php echo $item[1]; ?>" title="<?php echo $item[0]; ?>"></span>
						<?php echo JText::_( $item[0] ); ?>
					</a>
				</td>
			<?php } ?>
		</tr>
	</table>
</div>
<?php }

JHTML::_( 'behavior.mootools' );
$js			=
'window.addEvent("domready",function(){ new SmoothScroll({ duration: 1000 }); });
var applyQuickConfig	=	function() {
	var	modal_width	= $("modal_width").value;
	var	modal_height = $("modal_height").value;
	var	restriction_type = $("restriction_type").value;
	var	restriction_field = $("restriction_field").value;
	var	quick_title = $("quick_title").value;
	var	quick_color = $("quick_color").value;
	quick_color = quick_color.replace( "#", "*" );
	var url = "index.php?option=com_cckjseblod&task=quickConfig&modal_width="+modal_width+"&modal_height="+modal_height+"&restriction_type="+restriction_type+"&restriction_field="+restriction_field+"&quick_title="+quick_title+"&quick_color="+quick_color;
	var q=new Ajax(url,{
		 method:"post",
		 update:"",
		 onComplete: function(){ window.location.href = "index.php?option=com_cckjseblod"; }
	}).request(); }';
$document	=&	JFactory::getDocument();
$document->addScriptDeclaration( $js );

JHTML::_( 'stylesheet', 'icon.css', 'administrator/components/com_cckjseblod/assets/css/' );
JHTML::_( 'stylesheet', 'install.css', 'administrator/components/com_cckjseblod/assets/css/' );

$buttons		=	array(	'CCK CPanel'	=> array( 'Quick Config', 'jseblod', "#quick-config", 'href' ) );
$buttonsBottom	=	array(	'Apply'			=> array( 'Apply', 'apply_jseblod', "javascript: applyQuickConfig();", 'href' ) );
	
$rows	=	0;
$optRestriction				=	array();
$optRestriction[] 			=	JHTML::_( 'select.option', 3, JText::_( 'HIGHER' ) );
$optRestriction[] 			=	JHTML::_( 'select.option', 2, JText::_( 'HIGH' ) );
$optRestriction[] 			=	JHTML::_( 'select.option', 1, JText::_( 'MEDIUM' ) );
$optRestriction[]	 		=	JHTML::_( 'select.option', 0, JText::_( 'LOW' ) );
$lists['restrictionType'] 	=	JHTML::_( 'select.genericlist', $optRestriction, 'restriction_type', 'size="1" class="inputbox"', 'value', 'text', 0 );
$lists['restrictionField'] 	=	JHTML::_( 'select.genericlist', $optRestriction, 'restriction_field', 'size="1" class="inputbox"', 'value', 'text', 0 );
?>

<span style="display: block; color: #fff; background: #000; border: 3px dashed #004dbc; padding: 5px; margin-bottom: 10px;">
<table class="admintable" width="100%">
	<tr>
    	<td>
			<a href="<?php echo JRoute::_( 'index.php?option=com_cckjseblod' ); ?>"><img src="components/com_cckjseblod/assets/images/jseblod/icon-85-jseblod.png" width="341" height="85" alt="SEBLOD 1.x" align="left" border="0" />        
        </td>
    	<td>&nbsp;&nbsp;&nbsp;</td>
        <td>
             <?php echo	'<b>Please Empty your Browser Cache (Firefox, Opera, Safari, Chrome, IE) to complete properly the installation.<br /><br />'
			 		.	'Each upgrade will overwrite & update templates source files and parameters, so if you need to edit or change them, first make a copy, and use the copy.<br />'
            		.	'We also strongly suggest you to configure the New Lines option of Wysiwyg Editors as Linebreaks (BR) instead of Paragraphs (P) for proper content integrity.<br />'
            		.	'Finally, do not enable FTP functionality of Joomla in order to prevent (Templates, CCK Packs) uploading issues.&nbsp;&nbsp;Now Enjoy !!</b>';
					
			?>
        </td>
        <td valign="middle">
			<div style="float: right;">
				<?php quickToolbar( $buttons, 'toolbar2' ); ?>
			</div>
        </td>
    </tr>
</table>
</span>

<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="3"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="4"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row0">
        	<td width="19"><img src="components/com_cckjseblod/assets/images/joomla/icon-17-component.png" width="17" height="17" border="0" alt="Component" /></td>
			<td class="key" colspan="2"><strong><a style="color:grey;" href="<?php echo JRoute::_( 'index.php?option=com_cckjseblod' ); ?>"><?php echo 'SEBLOD 1.x '.JText::_('Component'); ?></a></strong></td>
			<td><strong><font color="green"><?php echo JText::_('Installed'); ?></font></strong></td>
		</tr>
<?php if (count($status->modules)) : ?>
		<tr>
	        <th></th>
			<th><?php echo JText::_('Module'); ?></th>
			<th><?php echo JText::_('Client'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->modules as $module) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
	        <td width="19"><img src="components/com_cckjseblod/assets/images/joomla/icon-17-module.png" width="17" height="17" border="0" alt="Module" /></td>
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo ucfirst($module['client']); ?></td>
			<td><strong><font color="green"><?php echo JText::_('Installed'); ?></font></strong></td>
		</tr>
	<?php endforeach;
endif;
if (count($status->plugins)) : ?>
		<tr>
	        <th></th>
			<th><?php echo JText::_('Plugin'); ?></th>
			<th><?php echo JText::_('Group'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->plugins as $plugin) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
        	<td width="19"><img src="components/com_cckjseblod/assets/images/joomla/icon-17-plugin.png" width="17" height="17" border="0" alt="Plugin" /></td>
			<td class="key"><?php echo $plugin['name']; ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><font color="green"><?php echo JText::_('Installed'); ?></font></strong></td>
		</tr>
	<?php endforeach;
endif;
if (count($status->templates)) : ?>
		<tr>
	        <th></th>
			<th><?php echo JText::_('Template'); ?></th>
			<th><?php echo JText::_('Type'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->templates as $template) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
        	<td width="19"><img src="components/com_cckjseblod/assets/images/joomla/icon-17-tool.png" width="17" height="17" border="0" alt="Template" /></td>
			<td class="key"><?php echo $template['name']; ?></td>
			<td class="key"><?php echo ucfirst($template['group']).' ('.ucfirst($template['type']).')'; ?></td>
			<td><strong><font color="green"><?php echo JText::_('Installed'); ?></font></strong></td>
		</tr>
	<?php endforeach;
endif; ?>
	</tbody>
</table>

<table class="adminlist">
	<thead>
		<tr>
			<th class="title" style="border-bottom: none;"><?php echo JText::_('QUICK CONFIG'); ?></th>
		</tr>
	</thead>
</table>
<div class="col width-30">
	<fieldset class="adminform">
	<legend class="legend-border" style="color: #666666;"><?php echo JText::_( 'BOX DIMENSIONS' ); ?></legend>
		<table class="admintable">
        	<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'WIDTH' ); ?>::<?php echo JText::_( 'EDIT WIDTH' ); ?>">
						<?php echo JText::_( 'WIDTH' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox" type="text" id="modal_width" name="modal_width" maxlength="4" size="16" value="900" />
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'HEIGHT' ); ?>::<?php echo JText::_( 'EDIT HEIGHT' ); ?>">
						<?php echo JText::_( 'HEIGHT' ); ?>:
					</span>
				</td>
				<td>
					<input class="inputbox" type="text" id="modal_height" name="modal_height" maxlength="4" size="16" value="550" />
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="col width-30">
	<fieldset class="adminform">
	<legend class="legend-border" style="color: #666666;"><?php echo JText::_( 'RESTRICTION LEVELS' ); ?></legend>
		<table class="admintable">
        	<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTENT TYPES' ); ?>::<?php echo JText::_( 'CONTENT TYPES' ); ?>">
						<?php echo JText::_( 'CONTENT TYPES' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $lists['restrictionType']; ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FIELDS' ); ?>::<?php echo JText::_( 'FIELDS' ); ?>">
						<?php echo JText::_( 'FIELDS' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $lists['restrictionField']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="col width-30">
	<fieldset class="adminform">
	<legend class="legend-border" style="color: #666666;"><?php echo JText::_( 'QUICK CATEGORIES' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TITLE' ); ?>::<?php echo JText::_( 'EDIT TITLE' ); ?>">
						<?php echo JText::_( 'TITLE' ); ?>:
					</span>
				</td>
				<td>
	                <input class="inputbox" type="text" id="quick_title" name="quick_title" maxlength="50" size="24" value="Quick Category" />
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COLOR' ); ?>::<?php echo JText::_( 'EDIT COLOR' ); ?>">
						<?php echo JText::_( 'COLOR' ); ?>:
					</span>
				</td>
   				<td>
	                <input class="inputbox" type="text" id="quick_color" name="quick_color" maxlength="50" size="24" value="#ffd700" />
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div>
<table class="admintable">  
    </tr>
		<td valign="middle">
        <div style="float: right;">
    	<?php quickToolbar( $buttonsBottom, 'toolbar' ); ?>
        </div>
    	</td>
    </tr>
</table>
</div>

<div class="clr"></div>

<span id="quick-config" style="display: block; color: #fff; background: #000; border: 3px dashed #004dbc; padding: 5px; margin-bottom: 10px;">
<table class="admintable" >
	<tr>
        <td>
			<img src="components/com_cckjseblod/assets/images/joomla/icon-15-native.png" width="75" height="16" alt="15 Native" align="left" />
        </td>
    </tr>
</table>
</span>