<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$js	=  'window.parent.document.getElementById(\'sbox-window\').close();
		var url = "'.$this->ajaxUrl.'";
		var ComponentLayout = window.parent.$("PushLayout_CCK");
		new Ajax(url, {
			method: "post",
			update: ComponentLayout,
			evalScripts:true,
			onComplete: function(){
	
			}
		}).request();
';

$this->document->addScriptDeclaration( $js );			
?>