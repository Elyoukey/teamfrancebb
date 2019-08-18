<?php
/* include bootstrap file */
include '../bootstrap.php';


$coachlist = new coachslist();
$coachlist->getCDFGlissant();
$mainpage->variables['maincontent']  = $coachlist->render();


echo $mainpage->render();


?>