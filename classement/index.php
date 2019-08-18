<?php
/* include bootstrap file */
include '../bootstrap.php';

$year = date('Y');
if( !empty($_GET['year']) ) $year = intval( $_GET['year'] );

$file = './cdf'.$year.'.html';
$mainpage->variables['title']  = 'Classement CDF '.$year;
if( file_exists($file) ){
    $mainpage->variables['maincontent']  = file_get_contents( $file );
    /* render main page*/
    echo $mainpage->render( );

}else{
    $coachlist = new coachslist();
    $coachlist->getCDF();
    $mainpage->variables['maincontent']  = $coachlist->render();

}
echo $mainpage->render();


?>