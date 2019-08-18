<?php
/* check $_POST et $_GET for honeypot field  */
if( !empty($_POST['hnumber']) ){
    //save users IP for later check


    //
    
    //redirect
    header('Location: '.BASE_PATH );
    die();
}

?>