<?php
/* include bootstrap file */
include './bootstrap.php';

/* load homepage blocks*/

/* NAF MAP */
$block_naf = new block();
$block_naf->variables['title'] = 'Tournois naf';
$block_naf->variables['content'] = '<script src="http://www.thenaf.net/nafmap.js.php?width=191&amp;height=210&amp;zoom=4&amp;lat=46.732331&amp;lng=2.5"></script>';

/* PALMARES */
$block_palmares = new block();
$block_palmares->variables['title'] = 'Palmares';
$palmareslist = new tournamentslist();
$palmareslist->getPalmares();
$block_palmares->variables['content'] = $palmareslist->renderPalmares();

/* TOP 5 CDF */
$block_cdf = new block();
$block_cdf->variables['title'] = 'Top 5 CDF 2018';
$coachlist = new coachslist();
$coachlist->getCDF(5);
$block_cdf->variables['content'] = $coachlist->renderBlock();

/* TOP 5 CDF permanant */
$block_cdf_g = new block();
$block_cdf_g->variables['title'] = 'Top 5 CDF Glissant';
$coachlist = new coachslist();
$coachlist->getCDFGlissant(5);
$block_cdf_g->variables['content'] = $coachlist->renderBlock( 'glissant' );


/* TOURNOIS */
$block_nexttournaments = new block();
$block_nexttournaments->variables['title'] = 'Prochainement';
$tournamentlist = new tournamentslist();
$tournamentlist->getNextMonth();
$block_nexttournaments->variables['content'] = $tournamentlist->renderBlock();

/* LIENS */
$block_links = new block();
$block_links->variables['title'] = '';
$block_links->variables['content'] = file_get_contents( './includes/templates/links.block.tpl.php' );


/* FUMBBL *
$block_fumbbl = new block();
$block_fumbbl->variables['title'] = 'En ce moment sur fumbbl';
/**/

/**/
$mainpage->variables['title'] = 'France BloodBowl';
$mainpage->variables['page-type'] = 'home';

/**/
$mainpage->variables['center-column'] = '';
$mainpage->variables['left-column']   = '';
$mainpage->variables['right-column']  = '';


$mainpage->variables['blocks_left'] = $block_links->render().$mainpage->variables['blocks_left'];

$mainpage->variables['left-column'] .= $block_cdf->render();
$mainpage->variables['left-column'] .= $block_naf->render();

$mainpage->variables['center-column'] .= $block_cdf_g->render();
$mainpage->variables['center-column'] .= $block_nexttournaments->render();

$mainpage->variables['right-column'] .= $block_palmares->render();
//$mainpage->variables['right-column'] .= $block_fumbbl->render();

/* render main pqge*/
echo $mainpage->render( );
?>

