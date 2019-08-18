<?php
/* include configuration */
include('./includes/classes/mainpage.class.php');
include('./includes/classes/menu.class.php');

$variables = array();
/* load main template */
$mainpage = new mainpage();
/* render main template*/
echo $mainpage->render( );
?>