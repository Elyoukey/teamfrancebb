<?php
/* include bootstrap file */
include '../bootstrap.php';

$rosters = rosters::getAll();
$roster = null;
if( in_array($_GET['r'],$rosters) ) $roster = $_GET['r'];


$mainpage->variables['title']  = 'Classement par roster ';
echo $roster;
$coachlist = new coachslist();
$coachlist->getByRoster($roster);
$mainpage->variables['maincontent']  = $coachlist->renderByRoster();

echo $mainpage->render();


?>