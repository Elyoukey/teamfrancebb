<?php
/* include bootstrap file */
include '../bootstrap.php';

$id = (int)$_GET['id'];

$tournament = new tournament();
$tournament->loadFromid($id);

$mainpage->variables['title'] = $tournament->name;
$mainpage->variables['maincontent'] = $tournament->render();

$mapblock  = new block();
$mapblock->variables['title'] = 'Localisation';
$mapblock->variables['content'] = $tournament->renderMap();

$mainpage->variables['blocks_left'] .= $mapblock->render();

/* render main page*/
echo $mainpage->render( );
?>