<?php
/* include bootstrap file */
include '../../bootstrap.php';

$name = filter_var( $_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
$password = filter_var( $_POST['password'] , FILTER_SANITIZE_STRING);

$user = new user();
if( $user->authenticate( $_POST['name'], $_POST['password'] ) ){
    $_SESSION['userhash'] = $user->hash;
    $_SESSION['messages'][] = 'Connection réussie.';
}
else{
    $_SESSION['messages'][] = 'Login incorrect.';
}
header('Location: '.BASE_PATH);
?>