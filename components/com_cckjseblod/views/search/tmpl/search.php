<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
JHTML::_( 'behavior.mootools' );
JHTML::_( 'behavior.modal' );
$this->document->addScript( _PATH_ROOT._PATH_CALENDAR.'calendar.js');
//
$this->document->addScript( _PATH_ROOT._PATH_MOOTIPS.'mootips.js' );
$this->document->addStyleSheet( _PATH_ROOT._PATH_MOOTIPS.'mootips.css' );

$tipsOnClick		=	( _SITEFORM_ONCLICK ) ? _SITEFORM_ONCLICK : 0;
$formName			=	$this->formName;

$javascript ='
	window.addEvent("domready",function(){	
		var tipsOnClick = "'.$tipsOnClick.'";
		if(tipsOnClick==1){var AjaxTooltips=new MooTips($$(".DescriptionTip"),{className:"ajaxTool",showOnClick:true,showOnMouseEnter:false,fixed:true})}else{var AjaxTooltips=new MooTips($$(".DescriptionTip"),{className:"ajaxTool",fixed:true})}
	});
	';
	
if ( $formName ) { 
$javascript .='
	function submitbutton(pressbutton) {		
		if ( pressbutton == "save" ) {
			document.'.$formName.'.submit();
			return;
		}		
	}
	';
}
$this->document->addScriptDeclaration( $javascript );
?>

<?php
if ( $this->params->get( 'show_page_title', 1 ) ) { 
	echo '<div class="componentheading">' . $this->page_title . '</div>';
}
?>

<?php
if ( $this->params->get( 'show_form', 1 ) ) {
	echo $this->data;
} else {
	echo $this->searchAction;
}

echo $this->formHidden;
?>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="view" value="search" />
<input type="hidden" name="layout" value="search" />
<input type="hidden" name="task" value="search" />
<input type="hidden" name="Itemid" value="<?php echo $this->itemId; ?>" />
<input type="hidden" name="searchid" value="<?php echo $this->searchid; ?>" />
<input type="hidden" name="templateid" value="<?php echo $this->templateid; ?>" />

<?php
if( $this->task == 'search' ) {
	// --- TASK=>SEARCH
	
	if ( @$this->total > 0 ) {
		// Count
		if ( $this->params->get( 'show_nb_item', 1 ) || $this->params->get( 'show_nb_page', 1 ) ) {
			echo '<br /><div align="center"><div>';
			// Results Count
			if ( $this->params->get( 'show_nb_item', 1 ) ) {
				echo '<span style="float: left;">'.$this->total.'&nbsp;'.JText::_( $this->params->get( 'show_label_item', 'Results' ) ).'</span>';
			}
			// Pages Count
			if ( $this->params->get( 'show_nb_page', 1 ) ) {
				echo $this->pagination->getPagesCounter();
			}
			echo '</div></div>';
		}
		// Pagination (top)    
		if ( $this->params->get( 'show_pagination', 0 ) && $this->pagination->html ) {
			echo '<div align="center" class="cck-pagination'.$this->pagination->class_sfx.'">' . $this->pagination->html . '</div>';
		}
		// Close Form
		echo '</form>';
		// Results   
		if ( $this->content >= 2 ) {
			echo $this->dataR;
		} else {
			echo $this->loadTemplate('results');
		}
		// Pagination (bottom)
		if ( $this->pagination->html ) {
			echo '<br /><div align="center" class="cck-pagination'.$this->pagination->class_sfx.'">' . $this->pagination->html . '</div>';
		}
	} else {
		echo '</form>';
		
		switch ( $this->style ) {
			case 'message':
				$mainframe->enqueueMessage( $this->message, 'message' );
				break;
			case 'notice':
				$mainframe->enqueueMessage( $this->message, 'notice' );
				break;
			case 'text':
				echo '<br />'.$this->message;
				break;
			default:
				break;
		}
	}
	
} else {
	// --- TASK=>NO
	
	echo '</form>';
	//TODO
}
?>