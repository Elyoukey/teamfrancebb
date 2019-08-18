<?php
/* include bootstrap file */
include '../bootstrap.php';

$tournamentslist = new tournamentslist();
$tournamentslist->getAll();

$mainpage->variables['title'] = 'Les tournois';
$mainpage->variables['maincontent']     = $tournamentslist->render();

/* render main page*/
echo $mainpage->render( );
?>