<?php
/* include bootstrap file */
include '../bootstrap.php';

if( !$currentUser->id ){
    $_SESSION['messages'][] = 'Action non autorisée';
    header('Location: '.BASE_PATH);
    die();
}

$id = (empty($_GET['id']))?0:(int)$_GET['id'];
$tournament = new tournament();
if($id){
    $tournament->loadFromId($id);
}

if( !$tournament->hasAccess($currentUser) ){
    $_SESSION['messages'][] = 'Accès interdit';
    header('Location: '.BASE_PATH);
}

$coachslist = new coachslist();
$coachslist->getAll();

$rosters = rosters::getAll();

$mainpage->variables['js'][] = BASE_PATH.'/includes/js/tournament.js';

$mainpage->variables['title'] = ($id)?'Modification':'Ajout';
$mainpage->variables['maincontent'] = $tournament->renderForm();


/* render main pqge*/
echo $mainpage->render( );
?>