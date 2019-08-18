<?php
/**/
include '../../bootstrap.php';

/* test token */
$hash = filter_var( $_GET['hash'] , FILTER_SANITIZE_STRING);

/* update account */
$user = new user();
$user->loadFromHash( $hash );
$user->status = 1;
$user->save();

/* redirect */
$_SESSION['messages'][] = 'Votre compte a été validé. 
Vous pouvez maintenant vous connecter.';
header('Location: '.BASE_PATH);


?>