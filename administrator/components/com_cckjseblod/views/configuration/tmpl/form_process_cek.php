<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
jimport( 'joomla.html.pane' );
$this->document->addScript( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_FORMVALIDATOR.'formvalidator.css' );

$javascript ='
	window.addEvent( "domready",function(){
		var adminFormValidator = new FormValidator( $("adminForm") );
	});
		
	function submitbutton( pressbutton ) {
		var form = document.adminForm;
		if ( pressbutton == "cancel" ) {
			submitform( pressbutton );
			return;
		}
		var adminFormValidator = new FormValidator( $("adminForm") );
		if ( adminFormValidator.validate() ) {
			submitform( pressbutton );
			return;
		}
	}
	';
$this->document->addScriptDeclaration( $javascript );
?>

<div class="col width-50">
    <fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'BACK END ACCESS LEVEL' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'VIEW ACCESS LEVEL' ); ?>::<?php echo JText::_( 'VIEW ACCESS LEVEL BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'VIEW ACCESS LEVEL' ); ?>::<?php echo JText::_( 'SELECT MINIMUM VIEW ACCESS LEVEL' ); ?>">
						<?php echo JText::_( 'VIEW ACCESS LEVEL' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['viewGroup']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT ACCESS LEVEL' ); ?>::<?php echo JText::_( 'EDIT ACCESS LEVEL BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT ACCESS LEVEL' ); ?>::<?php echo JText::_( 'SELECT MINIMUM EDIT ACCESS LEVEL' ); ?>">
						<?php echo JText::_( 'EDIT ACCESS LEVEL' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['editGroup']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
    
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'DEFAULT CATEGORY' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TEMPLATE DEFAULT CATEGORY' ); ?>::<?php echo JText::_( 'SELECT TEMPLATE DEFAULT CATEGORY' ); ?>">
						<?php echo JText::_( 'TEMPLATE DEFAULT CATEGORY' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['templateDefaultCategory']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TYPE DEFAULT CATEGORY' ); ?>::<?php echo JText::_( 'SELECT TYPE DEFAULT CATEGORY' ); ?>">
						<?php echo JText::_( 'TYPE DEFAULT CATEGORY' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['typeDefaultCategory']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ITEM DEFAULT CATEGORY' ); ?>::<?php echo JText::_( 'SELECT ITEM DEFAULT CATEGORY' ); ?>">
						<?php echo JText::_( 'ITEM DEFAULT CATEGORY' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['itemDefaultCategory']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEARCH DEFAULT CATEGORY' ); ?>::<?php echo JText::_( 'SELECT SEARCH DEFAULT CATEGORY' ); ?>">
						<?php echo JText::_( 'SEARCH DEFAULT CATEGORY' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['searchDefaultCategory']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
    
 <fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'DELETE MODE' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TEMPLATE DELETE MODE' ); ?>::<?php echo JText::_( 'SELECT TEMPLATE DELETE MODE' ); ?>">
						<?php echo JText::_( 'TEMPLATES' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['templateDeleteMode']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TEMPLATE CATEGORY DELETE MODE' ); ?>::<?php echo JText::_( 'SELECT TEMPLATE CATEGORY DELETE MODE' ); ?>">
						<?php echo JText::_( 'TEMPLATE CATEGORIES' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['templateCategoryDeleteMode']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TYPE DELETE MODE' ); ?>::<?php echo JText::_( 'SELECT TYPE DELETE MODE' ); ?>">
						<?php echo JText::_( 'CONTENT TYPES' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['typeDeleteMode']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TYPE CATEGORY DELETE MODE' ); ?>::<?php echo JText::_( 'SELECT TYPE CATEGORY DELETE MODE' ); ?>">
						<?php echo JText::_( 'TYPES CATEGORIES' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['typeCategoryDeleteMode']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ITEM DELETE MODE' ); ?>::<?php echo JText::_( 'SELECT ITEM DELETE MODE' ); ?>">
						<?php echo JText::_( 'ITEMS' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['itemDeleteMode']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ITEM CATEGORY DELETE MODE' ); ?>::<?php echo JText::_( 'SELECT ITEM CATEGORY DELETE MODE' ); ?>">
						<?php echo JText::_( 'ITEM CATEGORIES' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['itemCategoryDeleteMode']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEARCH TYPE DELETE MODE' ); ?>::<?php echo JText::_( 'SELECT SEARCH TYPE DELETE MODE' ); ?>">
						<?php echo JText::_( 'SEARCH TYPES' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['searchDeleteMode']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEARCH TYPE CATEGORY DELETE MODE' ); ?>::<?php echo JText::_( 'SELECT SEARCH TYPE CATEGORY DELETE MODE' ); ?>">
						<?php echo JText::_( 'SEARCH TYPE CATEGORIES' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['searchCategoryDeleteMode']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="col width-50">
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'CONTENT PACK' ); ?></legend>
		<table class="admintable">
        	<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EMPTY PACK AFTER EXPORT' ); ?>::<?php echo JText::_( 'CHOOSE EMPTY PACK AFTER EXPORT OR NOT' ); ?>">
						<?php echo JText::_( 'EMPTY PACK AFTER EXPORT' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['exportEmptyPack']; ?>
				</td>
			</tr>
            <tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'DEFAULT IMPORT MODE' ); ?>::<?php echo JText::_( 'SELECT DEFAULT IMPORT MODE' ); ?>">
						<?php echo JText::_( 'DEFAULT IMPORT MODE' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['importDefaultMode']; ?>
				</td>                
			</tr>
		</table>
	</fieldset>
    
	<fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'TEMPLATES' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'HIDDEN TEMPLATES' ); ?>::<?php echo JText::_( 'HIDDEN TEMPLATES BALLOON' ); ?>">
						<?php echo _IMG_BALLOON_LEFT; ?>
					</span>
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'HIDDEN TEMPLATES' ); ?>::<?php echo JText::_( 'SELECT HIDDEN TEMPLATES' ); ?>">
						<?php echo JText::_( 'HIDDEN TEMPLATES' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['template_hidden']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
 
    <fieldset class="adminform">
	<legend class="legend-border"><?php echo JText::_( 'RESTRICTION LEVELS' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'RESTRICTION LEVEL ON CONTENT TYPES' ); ?>::<?php echo JText::_( 'SELECT RESTRICTION LEVEL' ); ?>">
						<?php echo JText::_( 'CONTENT TYPES' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['restrictT']; ?>
				</td>
			</tr>
			<tr>
				<td width="25" align="right" class="key_jseblod">
				</td>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'RESTRICTION LEVEL ON FIELDS' ); ?>::<?php echo JText::_( 'SELECT RESTRICTION LEVEL' ); ?>">
						<?php echo JText::_( 'ITEMS' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['restrictF']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>