<?php
/* include bootstrap file */
include '../bootstrap.php';

/* store hash in session */
$_SESSION['hash'] = filter_var( $_GET['hash'],FILTER_SANITIZE_STRING);

/* check hash */
$hash = new hash();
$hash->loadFromHash( $_GET['hash'] );

if(  strtotime($hash->date) < strtotime('-2 hour') ){
    header('Location: '.BASE_PATH.'/user/resetpassword_1.php');
}

$user = new user();
$user->loadFromId($hash->userid);

$mainpage->variables['title'] = 'Récupération de mot de passe pour '.$user->name;
$mainpage->variables['maincontent'] = $user->renderResetpassword2();

/* render main page*/
echo $mainpage->render( );
?>
