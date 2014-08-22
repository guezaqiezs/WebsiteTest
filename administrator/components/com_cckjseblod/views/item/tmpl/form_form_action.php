<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php 
// Specific Attributes
$lists['boolean']	= JHTML::_('select.booleanlist', 'bool', 'class="inputbox"', ( ! $this->isNew ) ? $this->item->bool : 1 );

$optAuthors			=	array();
$optAuthors[]		= 	JHTML::_('select.option',  '', JText::_( 'SELECT AN AUTHOR' ), 'value', 'text' );
$optAuthors			=	array_merge( $optAuthors, HelperjSeblod_Helper::getJoomlaAuthors() );
$selectAuthors		=  @$this->item->content;
$lists['authors'] =  JHTML::_('select.genericlist', $optAuthors, 'content', 'class="inputbox required required-enabled" size="1"', 'value', 'text', $selectAuthors );

$optAccessLevel			=	array();
$optAccessLevel[] 		=	JHTML::_( 'select.option', 17, '-&nbsp;'.JText::_( 'PUBLIC FRONTEND' ) );
$optAccessLevel[] 		=	JHTML::_( 'select.option', 18, _NBSP.'-&nbsp;'.JText::_( 'Registered' ) );
$optAccessLevel[] 		=	JHTML::_( 'select.option', 19, _NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Author' ) );
$optAccessLevel[] 		=	JHTML::_( 'select.option', 20, _NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Editor' ) );
$optAccessLevel[] 		=	JHTML::_( 'select.option', 21, _NBSP._NBSP._NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Publisher' ) );
$optAccessLevel[] 		=	JHTML::_( 'select.option', 22, '-&nbsp;'.JText::_( 'PUBLIC BACKEND' ) );
$optAccessLevel[] 		=	JHTML::_( 'select.option', 23, _NBSP.'-&nbsp;'.JText::_( 'Manager' ) );
$optAccessLevel[] 		=	JHTML::_( 'select.option', 24, _NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Administrator' ) );
$optAccessLevel[] 		=	JHTML::_( 'select.option', 25, _NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Super Administrator' ) );
$optAccessLevel[] 		=	JHTML::_( 'select.option', -1, '-&nbsp;'.JText::_( 'DEFAULT AUTHOR ONLY' ) );
$selectAccessLevel		=	( @$this->item->display ) ? @$this->item->display : 19;
$lists['accessLevel']	=	JHTML::_( 'select.genericlist', $optAccessLevel, 'display', 'size="1" class="inputbox"', 'value', 'text', $selectAccessLevel );

$optJoomlaCategories		=	array();
$optJoomlaCategories[]		= 	JHTML::_('select.option',  0, JText::_( 'UNCATEGORISED' ), 'value', 'text' );
$getJoomlaCategories		=	HelperjSeblod_Helper::getJoomlaCategories();
if ( sizeof( $getJoomlaCategories ) ) {
	$optJoomlaCategories		=	array_merge( $optJoomlaCategories, $getJoomlaCategories );
}
$selectJoomlaCategories	=	@$this->item->location;
$lists['joomlaCategories']	=	JHTML::_('select.genericlist', $optJoomlaCategories, 'location', 'class="inputbox" size="1"', 'value', 'text', $selectJoomlaCategories );

$optAccess			=	array();
$optAccess[] 		=	JHTML::_( 'select.option', 0, JText::_( 'PUBLIC' ) );
$optAccess[] 		=	JHTML::_( 'select.option', 1, JText::_( 'REGISTERED' ) );
$optAccess[] 		=	JHTML::_( 'select.option', 2, JText::_( 'SPECIAL' ) );
$selectAccess		=	( ! $this->isNew ) ? $this->item->bool5 : 0;
$lists['access'] 	=	JHTML::_( 'select.genericlist', $optAccess, 'bool5', 'size="1" class="inputbox"', 'value', 'text', $selectAccess );

$optActionMode			=	array();
$optActionMode[] 		=	JHTML::_( 'select.option', 0, JText::_( 'JOOMLA ARTICLE SUBMISSION' ) );
$optActionMode[] 		=	JHTML::_( 'select.option', 1, JText::_( 'JOOMLA CATEGORY SUBMISSION' ) );
$optActionMode[] 		=	JHTML::_( 'select.option', 3, JText::_( 'JOOMLA EXTENSIONS DESCRIPTION' ) );
$optActionMode[] 		=	JHTML::_( 'select.option', 2, JText::_( 'JOOMLA USER REGISTRATION' ) );
$optActionMode[] 		=	JHTML::_( 'select.option', 4, JText::_( 'JOOMLA USER SUBMISSION' ) );
$selectActionMode		=	( ! $this->isNew ) ? $this->item->bool2 : 0;
$lists['actionMode'] 	=	JHTML::_( 'select.genericlist', $optActionMode, 'bool2', 'size="1" class="inputbox required required-enabled"', 'value', 'text', $selectActionMode );

$optUserType			=	array();
$optUserType[] 			=	JHTML::_( 'select.option', '', '-&nbsp;'.JText::_( 'USER TYPE FROM CONFIG' ) );
$optUserType[] 			=	JHTML::_( 'select.option', 'Registered', '-&nbsp;'.JText::_( 'Registered' ) );
$optUserType[] 			=	JHTML::_( 'select.option', 'Author', _NBSP._NBSP.'-&nbsp;'.JText::_( 'Author' ) );
$optUserType[] 			=	JHTML::_( 'select.option', 'Editor', _NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Editor' ) );
$optUserType[] 			=	JHTML::_( 'select.option', 'Publisher', _NBSP._NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Publisher' ) );
//$optUserType[] 			=	JHTML::_( 'select.option', '</OPTGROUP>', '' );
//$optUserType[] 			=	JHTML::_( 'select.option', '<OPTGROUP>', JText::_( 'BACK-END' ) );
$optUserType[] 			=	JHTML::_( 'select.option', 'Manager', '-&nbsp;'.JText::_( 'Manager' ) );
$optUserType[] 			=	JHTML::_( 'select.option', 'Administrator', _NBSP._NBSP.'-&nbsp;'.JText::_( 'Administrator' ).' (*)' );
$optUserType[] 			=	JHTML::_( 'select.option', 'Super Administrator', _NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Super Administrator' ).' (*)' );
$selectUserType			=	@$this->item->format;
$lists['userType']		=	JHTML::_( 'select.genericlist', $optUserType, 'format', 'size="1" class="inputbox"', 'value', 'text', $selectUserType );

$optUserActivate			=	array();
$optUserActivate[] 		=	JHTML::_( 'select.option', 1, JText::_( 'No' ) );
$optUserActivate[] 		=	JHTML::_( 'select.option', 0, JText::_( 'Yes' ) );
$optUserActivate[] 		=	JHTML::_( 'select.option', 2, JText::_( 'AFTER EMAIL' ) );
$selectUserActivate		=	( ! $this->isNew ) ? $this->item->bool4 : 2;
$lists['userActivate'] 	=	JHTML::_( 'select.radiolist', $optUserActivate, 'bool4', 'size="1" class="inputbox required required-enabled"', 'value', 'text', $selectUserActivate );

$optUserOverride		=	array();
$optUserOverride[] 		=	JHTML::_( 'select.option', 0, JText::_( 'No' ) );
$optUserOverride[] 		=	JHTML::_( 'select.option', 1, JText::_( 'Yes' ) );
$selectUserOverride		=	( ! $this->isNew ) ? $this->item->ordering : 1;
$lists['userOverride'] 	=	JHTML::_( 'select.radiolist', $optUserOverride, 'ordering', 'size="1" class="inputbox required required-enabled"', 'value', 'text', $selectUserOverride );

$optAccessEdition		=	array();
$optAccessEdition[] 	=	JHTML::_( 'select.option', 18, _NBSP.'-&nbsp;'.JText::_( 'Registered' ) );
$optAccessEdition[] 	=	JHTML::_( 'select.option', 19, _NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Author' ) );
$optAccessEdition[] 	=	JHTML::_( 'select.option', 20, _NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Editor' ) );
$optAccessEdition[] 	=	JHTML::_( 'select.option', 21, _NBSP._NBSP._NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Publisher' ) );
$optAccessEdition[] 	=	JHTML::_( 'select.option', 22, '-&nbsp;'.JText::_( 'PUBLIC BACKEND' ) );
$optAccessEdition[] 	=	JHTML::_( 'select.option', 23, _NBSP.'-&nbsp;'.JText::_( 'Manager' ) );
$optAccessEdition[] 	=	JHTML::_( 'select.option', 24, _NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Administrator' ) );
$optAccessEdition[] 	=	JHTML::_( 'select.option', 25, _NBSP._NBSP._NBSP._NBSP._NBSP.'-&nbsp;'.JText::_( 'Super Administrator' ) );
$selectAccessEdition	=	( @$this->item->gEACL ) ? @$this->item->gEACL : 20;
$lists['groupEditionAccess']	=	JHTML::_( 'select.genericlist', $optAccessEdition, 'gEACL', 'size="1" class="inputbox"', 'value', 'text', $selectAccessEdition );

$optAccessAuthor		=	array();
$optAccessAuthor[] 		=	JHTML::_( 'select.option', 0, JText::_( 'AUTHOR ONLY' ) );
$optAccessAuthor[] 		=	JHTML::_( 'select.option', 1, JText::_( 'AUTHOR MINIMUM GROUP LEVEL ONLY' ) );
$optAccessAuthor[] 		=	JHTML::_( 'select.option', 2, JText::_( 'AUTHOR AND GROUP' ) );
$optAccessAuthor[] 		=	JHTML::_( 'select.option', 3, JText::_( 'AUTHOR WITH MINIMUM GROUP LEVEL AND GROUP' ) );
$optAccessAuthor[] 		=	JHTML::_( 'select.option', 4, JText::_( 'GROUP ONLY' ) );
$optAccessAuthor[] 		=	JHTML::_( 'select.option', -1, JText::_( 'NONE' ) );
$selectAccessAuthor		=	( ! $this->isNew ) ? $this->item->uEACL : 2;
$lists['editionAccess']	=	JHTML::_( 'select.genericlist', $optAccessAuthor, 'uEACL', 'size="1" class="inputbox"', 'value', 'text', $selectAccessAuthor );

$modals['message']			=	HelperjSeblod_Display::quickModalWysiwyg( 'EDITOR', $this->controller, 'message', 'pagebreak', 0, @$this->item->id, false );
$tooltips['link_message']	=	HelperjSeblod_Display::quickTooltipAjaxLink( JText::_( 'MESSAGE' ), $this->controller, 'message', @$this->item->id );

$modals['message2']			=	HelperjSeblod_Display::quickModalWysiwyg( 'EDITOR', $this->controller, 'message2', 'pagebreak', 0, @$this->item->id, false );
$tooltips['link_message2']	=	HelperjSeblod_Display::quickTooltipAjaxLink( JText::_( 'MESSAGE' ), $this->controller, 'message2', @$this->item->id );

$optMessageStyle		=	array();
$optMessageStyle[] 		=	JHTML::_( 'select.option', '', JText::_( 'NONE' ) );
$optMessageStyle[] 		=	JHTML::_( 'select.option', 'message', JText::_( 'MESSAGE' ) );
$optMessageStyle[] 		=	JHTML::_( 'select.option', 'notice', JText::_( 'NOTICE' ) );
$selectMessageStyle		=	( ! $this->isNew ) ? $this->item->style : 'message';
$lists['messageStyle'] 	=	JHTML::_( 'select.genericlist', $optMessageStyle, 'style', 'size="1" class="inputbox"', 'value', 'text', $selectMessageStyle );

$optRedirection			=	array();
$optRedirection[] 		=	JHTML::_( 'select.option', 0, JText::_( 'CONTENT' ) );
$optRedirection[] 		=	JHTML::_( 'select.option', 4, JText::_( 'CURRENT' ) );
$optRedirection[] 		=	JHTML::_( 'select.option', 1, JText::_( 'EDITION' ) );
$optRedirection[] 		=	JHTML::_( 'select.option', 2, JText::_( 'FORM' ) );
$optRedirection[] 		=	JHTML::_( 'select.option', 3, JText::_( 'URL' ) );
$selectRedirection		=	( ! $this->isNew ) ? $this->item->bool3 : 0;
$lists['redirection'] 	=	JHTML::_( 'select.genericlist', $optRedirection, 'bool3', 'size="1" class="inputbox"', 'value', 'text', $selectRedirection );

$optTarget				=	array();
$optTarget[] 			=	JHTML::_( 'select.option', 0, JText::_( 'TARGET SELF' ) );
$optTarget[] 			=	JHTML::_( 'select.option', 1, JText::_( 'TARGET BLANK' ) );
$selectTarget			=	( ! $this->isNew ) ? $this->item->bool7 : 0;
$lists['target'] 		=	JHTML::_( 'select.genericlist', $optTarget, 'bool7', 'size="1" class="inputbox"', 'value', 'text', $selectTarget );
?>

<fieldset class="adminform">
<legend class="legend-border">
	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'FORM ACTION' ); ?>::<?php echo JText::_( 'DESCRIPTION FORM ACTION' ); ?>">
		<?php echo JText::_( 'FORM ACTION' ); ?>
    </span>
</legend>
	<table class="admintable">
    	<tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ACTION MODE' ); ?>::<?php echo JText::_( 'SELECT ACTION MODE' ); ?>">
					<?php echo JText::_( 'ACTION MODE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['actionMode']; ?>
			</td>
		</tr>
	</table>
	<table id="as-noext-1" class="admintable header_jseblod <?php echo ( @$this->item->bool2 != 3 ) ? '' : 'display-no' ?>">
		<tr>
			<td>
				<?php echo JText::_( 'NOTE ACTION').' :: '.JText::_( 'SUBMISSION REGISTRATION' ); ?>
			</td>
		</tr>
	</table>
	<table id="as-noext-2" class="admintable <?php echo ( @$this->item->bool2 != 3 ) ? '' : 'display-no' ?>">
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PUBLICATION' ); ?>::<?php echo JText::_( 'PUBLICATION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'PUBLICATION' ); ?>::<?php echo JText::_( 'CHOOSE PUBLISHED OR NOT' ); ?>">
					<?php echo JText::_( 'PUBLICATION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['boolean']; ?>
			</td>
		</tr>
		<!--<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php //echo JText::_( 'PUBLICATION DURATION' ); ?>::<?php //echo JText::_( 'PUBLICATION DURATION BALLOON' ); ?>">
					<?php //echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php //echo JText::_( 'PUBLICATION DURATION' ); ?>::<?php //echo JText::_( 'EDIT PUBLICATION DURATION' ); ?>">
					<?php //echo JText::_( 'PUBLICATION DURATION' ); ?>:
				</span>
			</td>
			<td>
	            <input class="inputbox" type="text" id="rows" name="rows" maxlength="50" size="16" value="<?php //echo ( $this->isNew ) ? 0 : $this->item->rows; ?>" />
			</td>
		</tr>-->
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ACCESS LEVEL' ); ?>::<?php echo JText::_( 'ACCESS LEVEL BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'ACCESS LEVEL' ); ?>::<?php echo JText::_( 'SELECT ACCESS LEVEL' ); ?>">
					<?php echo JText::_( 'ACCESS LEVEL' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['access']; ?>
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CATEGORY CATEGORY PARENT' ); ?>::<?php echo JText::_( 'CATEGORY CATEGORY PARENT BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CATEGORY CATEGORY PARENT' ); ?>::<?php echo JText::_( 'SELECT CATEGORY' ); ?>">
					<?php echo JText::_( 'CATEGORY CATEGORY PARENT' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['joomlaCategories']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MAX PER CATEGORY' ); ?>::<?php echo JText::_( 'MAX PER CATEGORY BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MAX PER CATEGORY' ); ?>::<?php echo JText::_( 'EDIT MAX PER CATEGORY' ); ?>">
					<?php echo JText::_( 'MAX PER CATEGORY' ); ?>:
				</span>
			</td>
			<td>
	            <input class="inputbox" type="text" id="maxlength" name="maxlength" maxlength="50" size="16" value="<?php echo ( $this->isNew ) ? 0 : $this->item->maxlength; ?>" />
                <?php echo '&nbsp;<font color="grey">(' . JText::_( 'EXCEPT SUPER ADMIN' ) . ')</font>'; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MAX PER CATEGORY USER' ); ?>::<?php echo JText::_( 'MAX PER CATEGORY USER BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MAX PER CATEGORY USER' ); ?>::<?php echo JText::_( 'EDIT MAX PER CATEGORY USER' ); ?>">
					<?php echo JText::_( 'MAX PER CATEGORY USER' ); ?>:
				</span>
			</td>
			<td>
  				<input class="inputbox" type="text" id="size" name="size" maxlength="50" size="16" value="<?php echo ( $this->isNew ) ? 0 : $this->item->size; ?>" />
                <?php echo '&nbsp;<font color="grey">(' . JText::_( 'EXCEPT SUPER ADMIN' ) . ')</font>'; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEFAULT AUTHOR' ); ?>::<?php echo JText::_( 'DEFAULT AUTHOR BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'DEFAULT AUTHOR' ); ?>::<?php echo JText::_( 'SELECT DEFAULT AUTHOR' ); ?>">
					<?php echo JText::_( 'DEFAULT AUTHOR' ); ?>:
				</span>
			</td>
			<td>
          		<?php echo $lists['authors']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CREATION ACCESS' ); ?>::<?php echo JText::_( 'CREATION ACCESS BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'CREATION ACCESS' ); ?>::<?php echo JText::_( 'SELECT CREATION ACCESS' ); ?>">
					<?php echo JText::_( 'CREATION ACCESS' ); ?>:
				</span>
			</td>
			<td>
                <?php echo $lists['accessLevel']; ?>
			</td>
		</tr>
        <tr id="as-url-access" class="<?php echo ( @$this->item->display == 17 ) ? 'display-no' : '' ?>">
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'URL NOT AUTHORIZED' ); ?>::<?php echo JText::_( 'EDIT URL NOT AUTHORIZED' ); ?>">
					<?php echo JText::_( 'URL NOT AUTHORIZED' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="extra" name="extra" maxlength="250" size="40" value="<?php echo ( ! @$this->item->extra ) ? 'index.php?option=com_user&view=login' : $this->item->extra; ?>" />
			</td>
		</tr>
	</table>
	<table id="as-noext-3" class="admintable header_jseblod <?php echo ( @$this->item->bool2 == 2 ) ? '' : 'display-no' ?>">
		<tr>
			<td>
				<?php echo JText::_( 'NOTE ACTION').' :: '.JText::_( 'REGISTRATION' ); ?>
			</td>
		</tr>
	</table>
	<table id="as-noext-4" class="admintable <?php echo ( @$this->item->bool2 == 2 ) ? '' : 'display-no' ?>">
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'USER ACTIVATION' ); ?>::<?php echo JText::_( 'SELECT USER ACTIVATION' ); ?>">
					<?php echo JText::_( 'USER ACTIVATION' ); ?>:
				</span>
			</td>
			<td>
                <?php echo $lists['userActivate']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
            	<span class="editlinktip hasTip2" title="<?php echo JText::_( 'USER OVERRIDE' ); ?>::<?php echo JText::_( 'USER OVERRIDE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'USER OVERRIDE' ); ?>::<?php echo JText::_( 'SELECT USER OVERRIDE' ); ?>">
					<?php echo JText::_( 'USER OVERRIDE' ); ?>:
				</span>
			</td>
			<td>
                <?php echo $lists['userOverride']; ?>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'USER TYPE' ); ?>::<?php echo JText::_( 'USER TYPE BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'USER TYPE' ); ?>::<?php echo JText::_( 'SELECT USER TYPE' ); ?>">
					<?php echo JText::_( 'USER TYPE' ); ?>:
				</span>
			</td>
			<td>
                <?php echo $lists['userType']; ?>
			</td>
		</tr>
        <tr>
			<td colspan="3">
				<?php echo JText::_( 'REGISTRATION USER TYPE EXPLANATION' ); ?>
			</td>
		</tr>
	</table>
	<table id="as-noext-5" class="admintable header_jseblod <?php echo ( @$this->item->bool2 != 3 ) ? '' : 'display-no' ?>">
		<tr>
			<td>
				<?php echo JText::_( 'NOTE ACTION').' :: '.JText::_( 'EDITION' ); ?>
			</td>
		</tr>
	</table>
	<table id="as-noext-6" class="admintable <?php echo ( @$this->item->bool2 != 3 ) ? '' : 'display-no' ?>">
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'EDITION ACCESS' ); ?>::<?php echo JText::_( 'EDITION ACCESS BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'EDITION ACCESS' ); ?>::<?php echo JText::_( 'SELECT EDITION ACCESS' ); ?>">
					<?php echo JText::_( 'EDITION ACCESS' ); ?>:
				</span>
			</td>
			<td>
          <?php echo $lists['editionAccess']; ?>
			</td>
		</tr>
        <tr id="as-group" class="<?php echo ( $this->isNew || @$this->item->uEACL > 0  ) ? '' : 'display-no' ?>">
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'GROUP EDITION ACCESS' ); ?>::<?php echo JText::_( 'GROUP EDITION ACCESS BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'GROUP EDITION ACCESS' ); ?>::<?php echo JText::_( 'SELECT GROUP EDITION ACCESS' ); ?>">
					<?php echo JText::_( 'GROUP EDITION ACCESS' ); ?>:
				</span>
			</td>
			<td>
                <?php echo $lists['groupEditionAccess']; ?>
			</td>
		</tr>
	</table>
	<table id="as-noext-7" class="admintable header_jseblod <?php echo ( @$this->item->bool2 != 3 ) ? '' : 'display-no' ?>">
		<tr>
			<td>
				<?php echo JText::_( 'NOTE AFTER ACTION').' :: '.JText::_( 'MESSAGE REDIRECTION' ); ?>
			</td>
		</tr>
	</table>
	<table id="as-noext-8" class="admintable <?php echo ( @$this->item->bool2 != 3 ) ? '' : 'display-no' ?>">
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MESSAGE SUBMISSION' ); ?>::<?php echo JText::_( 'MESSAGE SUBMISSION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MESSAGE SUBMISSION' ); ?>::<?php echo JText::_( 'EDIT MESSAGE' ); ?>">
					<?php echo JText::_( 'MESSAGE SUBMISSION' ); ?>:
				</span>
			</td>
			<td>
                <span class="ajaxTip2" title="<?php echo $tooltips['link_message']; ?>">
					<?php echo $modals['message']; ?>
				</span>
			</td>
		</tr>
		<tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MESSAGE EDITION' ); ?>::<?php echo JText::_( 'MESSAGE EDITION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MESSAGE EDITION' ); ?>::<?php echo JText::_( 'EDIT MESSAGE' ); ?>">
					<?php echo JText::_( 'MESSAGE EDITION' ); ?>:
				</span>
			</td>
			<td>
                <span class="ajaxTip2" title="<?php echo $tooltips['link_message2']; ?>">
					<?php echo $modals['message2']; ?>
				</span>
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'MESSAGE STYLE' ); ?>::<?php echo JText::_( 'SELECT MESSAGE STYLE' ); ?>">
					<?php echo JText::_( 'MESSAGE STYLE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['messageStyle']; ?>
			</td>
		</tr>
        <tr>
        	<td colspan="3">
            </td>
        </tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'REDIRECTION' ); ?>::<?php echo JText::_( 'REDIRECTION BALLOON' ); ?>">
					<?php echo _IMG_BALLOON_LEFT; ?>
				</span>
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'REDIRECTION' ); ?>::<?php echo JText::_( 'EDIT REDIRECTION' ); ?>">
					<?php echo JText::_( 'REDIRECTION' ); ?>:
				</span>
			</td>
			<td>
            	 <?php echo $lists['redirection']; ?>
			</td>
		</tr>
        <tr id="as-url" class="<?php echo ( @$this->item->bool3 == 3 ) ? '' : 'display-no' ?>">
            <td width="25" align="right" class="key_jseblod">
            </td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'REDIRECTION URL' ); ?>::<?php echo JText::_( 'EDIT REDIRECTION URL' ); ?>">
					<?php echo JText::_( 'REDIRECTION URL' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" id="url" name="url" maxlength="250" size="40" value="<?php echo @$this->item->url; ?>" />
			</td>
		</tr>
        <tr>
			<td width="25" align="right" class="key_jseblod">
			</td>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip2" title="<?php echo JText::_( 'TARGET' ); ?>::<?php echo JText::_( 'SELECT TARGET' ); ?>">
					<?php echo JText::_( 'TARGET' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['target']; ?>
			</td>
		</tr>
	</table>
</fieldset>

<script type="text/javascript">
$("bool2").addEvent("change", function(r) {
		r = new Event(r).stop();
		
		var layout = $("bool2").value;
		
		if ( layout == 3 ) {
			for ( var i = 1; i<=8; i++ ) {
				var field="as-noext-"+i;
				if ( ! $(field).hasClass("display-no") ) {
					$(field).addClass("display-no");
				}
			}
		} else {
			for ( var i = 1; i<=8; i++ ) {
				var field="as-noext-"+i;
				if ( ((layout == 0 || layout == 1 || layout == 4) && i != 3 && i != 4) || (layout == 2) ) {
					if ( $(field).hasClass("display-no") ) {
						$(field).removeClass("display-no");
					}
				} else {
					if ( ! $(field).hasClass("display-no") ) {
						$(field).addClass("display-no");
					}
				}
			}
		}
		
		
	});

$("bool3").addEvent("change", function(u) {
		u = new Event(u).stop();
		
		var layout = $("bool3").value;
		
		if ( layout == 3 ) {
			if ( $("as-url").hasClass("display-no") ) {
				$("as-url").removeClass("display-no");
			}
		} else {
			if ( ! $("as-url").hasClass("display-no") ) {
				$("as-url").addClass("display-no");
			}			
		}
	});

$("display").addEvent("change", function(d) {
		d = new Event(d).stop();
		
		var layout = $("display").value;
		
		if ( layout != 17 ) {
			if ( $("as-url-access").hasClass("display-no") ) {
				$("as-url-access").removeClass("display-no");
			}
		} else {
			if ( ! $("as-url-access").hasClass("display-no") ) {
				$("as-url-access").addClass("display-no");
			}			
		}
	});


$("uEACL").addEvent("change", function(a) {
		a = new Event(a).stop();
		
		var layout = $("uEACL").value;
		
		if ( layout == 0 || layout == -1 ) {
			if ( ! $("as-group").hasClass("display-no") ) {
				$("as-group").addClass("display-no");
			}
		} else {
			if ( $("as-group").hasClass("display-no") ) {
				$("as-group").removeClass("display-no");
			}
		}
	});
</script>

<?php if ( ! ( @$this->item->typename == 'alias_custom' || @$this->item->typename == 'search_multiple' || @$this->item->typename == 'ecommerce_cart' ) ) { ?>
<input type="hidden" name="elemxtd" value="" />
<input type="hidden" name="extended" value="" />
<input type="hidden" name="type" value="form_action" />
<?php } ?>