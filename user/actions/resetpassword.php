<?php
/**/
include '../../bootstrap.php';

$email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL );

/**/
// find user withe mail
$user = new user();
$user->loadFromEmail( $email  );

if(!$user->id){
    header('Location: '.BASE_PATH.'/user/resetpassword_1.php');
}

//create token and store
$hash = new hash();
$hash->userid = $user->id;
$hash->save();

$_SESSION['hash']=$hash->hash;

//send email
$link = BASE_PATH.'/user/resetpassword_2.php?hash='.$hash->hash;
$message = 'Bonjour '.$user->name.'<br/>
Une demande de réinitialisation de mot de passe a été envoyée pour le site '.BASE_PATH.'.<br/>
<br/>
Pour renouveller votre mot de passe, vous pouvez cliquer sur ce lien: <br/>'."\n\r".'<a href="'.$link.'">'.$link.'</a><br/>
<br/>
Si vous n\'êtes pas à l\'origine de cette demande vous pouvez ignorer ce message.<br/>
<br/>
Merci.
';

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");

$headers = '';
$headers .= 'From: '.MAIL_FROM. "\r\n" .
$headers .= 'Reply-To: '.MAIL_FROM. "\r\n" ;
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

mail($user->email,'Récupération de mot de passe sur '.BASE_PATH, $message, $headers);

$_SESSION['messages'][] = 'Un mail vous a été envoyé. 

Merci de cliquer sur le lien qu\'il contient pour accéder au formulaire de réinitialisation du mot de passe.

Ce mail peut mettre quelques minutes à arriver.

Vérifiez bien dans vos spam.';

header('Location: '.BASE_PATH );

?>
