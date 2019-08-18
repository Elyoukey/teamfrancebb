<?php
/**/
include '../../bootstrap.php';

$headers = '';
$headers .= 'From: '.MAIL_FROM. "\r\n";
$headers .= 'Reply-To: '.MAIL_FROM. "\r\n" ;
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";



$name = filter_var( $_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
$userhash = filter_var( $_POST['hash'], FILTER_SANITIZE_STRING);
$email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL);
$password = user_hash_password( $_POST['password'],5 );

/* Test fields */
if( !$name || !$email || !$password ){
    $_SESSION['messages'][] = 'Tous les champs sont obligatoires';
    header('Location: '.BASE_PATH.'/user/newaccount.php');
}

/* Save account */
$user = new user();
$user->loadFromHash( $userhash );

$user->name = $name;
$user->email = $email;
$user->password = $password;
$user->status = 0;
$user->hash = md5( $name.$email.time() );
$res = $user->saveNew();
if( !$res ){
    $_SESSION['messages'][] = 'Impossible de créer ce compte, 
    nom d\'utilisateur ou adresse email déja utilisé.';
    header('Location: '.BASE_PATH);
    die();
}

$user->id = $db->insert_id;
// Check for coach in db
if( !$userhash ){
    $query = 'SELECT * FROM coachs WHERE name = ?';
    $stmt = $db->prepare( $query );
    $stmt->bind_param('s',$name);
    $result = $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        //if coach in db
        //link if not already linked
        $user->coachid = $row['id'];
        $message = 'Le coach existant '.$coach->name.' '.$coach->id.' a été associé avec le nouvel utilisateur '.$user->name.' '.$user->id;
        mail( 'elyoukey@gmail.com' ,'Coach associé automatiquement '.BASE_PATH, $message, $headers);
    }else{
        //if not coach in db
        //create coach
        $coach = new coach();
        $coach->name = $name;
        $coach->userid = $user->id;
        $coach->save();
        $user->coachid = $db->insert_id;
        $message = 'Nouveau coach '.$coach->name.' ('.$coach->id.') associé avec le nouvel utilisateur '.$user->name.' ('.$user->id.')';
        mail( 'elyoukey@gmail.com' ,'Coach associé automatiquement '.BASE_PATH, $message, $headers);
    }
}
$user->save();

//send email
$link = BASE_PATH.'/user/actions/validateaccount.php?hash='.$user->hash;
$message = 'Pour activer votre compte vous pouvez cliquer sur ce lien: <br/>'."\n\r".'<a href="'.$link.'">'.$link.'</a>';

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");
mail($user->email,'Activation de votre compte sur '.BASE_PATH, $message, $headers);

$_SESSION['messages'][] = 'Votre compte <b>'.$user->name.'</b> a été créé.

Un mail vous a été envoyé. 

Merci de cliquer sur le lien qu\'il contient pour activer votre compte.

Ce mail peut mettre quelques minutes à arriver.

Vérifiez bien dans vos spam.';

header('Location: '.BASE_PATH);

?>