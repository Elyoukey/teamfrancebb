<?php
/* include bootstrap file */
include '../bootstrap.php';

/* load blocks*/
$user = new user();
$mainpage->variables['title'] = 'Mot de passe oublié - étape 1';
$mainpage->variables['maincontent'] = $user->renderResetpassword1();

/* render main pqge*/
echo $mainpage->render( );
?>
