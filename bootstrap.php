<?php
session_start();
/*
 * Initialize Base path
 * */
setlocale(LC_ALL, 'fr_FR');
define('BASE_PATH','http://www.teamfrancebb.com');
define('FILE_PATH','/www');

/* 
 * Mail configuration 
 *  */
define('MAIL_FROM','webmaster@teamfrancebb.com');

/*
    include db connection
*/
include('settings.php');

/* honeypot protection*/
include('includes/honeypot.php');


/*
 * load classes
 * */
include('includes/classes/mainpage.class.php');
include('includes/classes/menu.class.php');
include('includes/classes/footer.class.php');
include('includes/classes/block.class.php');
include('includes/classes/user.class.php');
include('includes/classes/tournament.class.php');
include('includes/classes/tournamentslist.class.php');
include('includes/classes/coach.class.php');
include('includes/classes/team.class.php');
include('includes/classes/coachslist.class.php');
include('includes/classes/rosters.class.php');
include('includes/classes/participant.class.php');
include('includes/classes/hash.class.php');

include('includes/password/password.inc');
include('includes/grid.php');
//

/* clean old hashes */
hash::clean();

/*
 * Initialize user session
 * */
$currentUser = new user();
if( !empty($_SESSION['userhash']) ){
    $currentUser->loadFromHash( $_SESSION['userhash'] );
}



/*
 * Load main page with default content
 * */
/* load main page */
$mainpage   = new mainpage();
/* load top menu */
$menu       = new menu();
/* load left blocks */

$leftblock  = new block();
$leftblock->variables['title'] = 'Acces';
$leftblock->variables['classes'] = 'login';
$leftblock->variables['content'] = $currentUser->renderLogin();


/* load footer */
$footer     = new footer();

$content = 'Cette page est vide, merci de rempliur la variable $mainpage->variables[\'maincontent\'] ';

/* default page fill */
$mainpage->variables['block_menu']      = $menu->render();
$mainpage->variables['blocks_left']      = $leftblock->render();
$mainpage->variables['maincontent']     = $content;
$mainpage->variables['blocks_footer']    = $footer->render();

?>