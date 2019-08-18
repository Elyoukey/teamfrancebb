<?php
/* include bootstrap file */
include '../bootstrap.php';

$tournamentslist = new tournamentslist();
$tournamentslist->getAll();

$mainpage->variables['title'] = 'Liste des votants';

$coachlist = new coachslist();
$coachlist->getVotants();

$mainpage->variables['maincontent'] = $coachlist->renderVotants();


/* render main page*/
echo $mainpage->render( );
?>