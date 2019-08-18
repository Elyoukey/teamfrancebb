<?php
/* include bootstrap file */
include '../bootstrap.php';

$id = (int)$_GET['id'];



$mainpage->variables['title'] = 'Samba Action Calculator';

$mainpage->variables['maincontent']  = file_get_contents( '../includes/templates/sac.tpl.php' );
/* render main page*/
echo $mainpage->render( );

?>