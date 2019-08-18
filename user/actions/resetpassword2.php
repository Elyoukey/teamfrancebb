<?php
/**/
include '../../bootstrap.php';

/* check hash */
//create token and store
$hash = new hash();
$sessionhash = filter_var( $_SESSION['hash'], FILTER_SANITIZE_STRING);
$hash->loadFromHash( $sessionhash );

/* don't act on too old hashes */
if(  strtotime($hash->date) < strtotime('-2 hour') ){
    $_SESSION['messages'][] = 'Ce lien a expiré.';
    header('Location: '.BASE_PATH.'/user/resetpassword_1.php');
}

/**/
// find user
$user = new user();
$user->loadFromId( $hash->userid );
if(!$user->id){
    header('Location: '.BASE_PATH.'/user/resetpassword_1.php');
}

$user->password = user_hash_password($_POST['password']);
$user->save();

$_SESSION['messages'][] = 'Votre mot de passe a été réinitialisé.';

header('Location: '.BASE_PATH );

?>
