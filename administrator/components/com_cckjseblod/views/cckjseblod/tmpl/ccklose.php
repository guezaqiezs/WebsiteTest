<?php
/* Copyright (C) 2011 SEBLOD. All Rights Reserved. */

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php
$js	=  'window.parent.document.getElementById(\'sbox-window\').close();';

$this->document->addScriptDeclaration( $js );			
?>

<?php
HelperjSeblod_Display::quickCopyright();
?>