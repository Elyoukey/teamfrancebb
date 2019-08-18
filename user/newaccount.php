<?php
/* include bootstrap file */
include '../bootstrap.php';

/* load blocks*/

$user = new user();
$mainpage->variables['title'] = 'Nouveau compte';
$mainpage->variables['maincontent'] = $user->renderCreateaccount();

/* render main pqge*/
echo $mainpage->render( );
?>

