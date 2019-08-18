<?php
/* include bootstrap file */
include '../../bootstrap.php';

if( !$currentUser->id ){
    $_SESSION['messages'][] = 'Action non autorisÃÂ©e';
    header('Location: '.BASE_PATH);
    die();
}

/* Sanitize inputs */
$tournament = new tournament();
$tournament->id = (int)( $_POST['id']);
$tournament->name = filter_var( $_POST['name'], FILTER_SANITIZE_STRING);
$tournament->type = ($_POST['type'] == 'ID' )?'ID':( ($_POST['type'] == 'EP' )?'EP':( ($_POST['type'] == 'ET' )?'ET':'ID' ) );
$tournament->nbRondes = (int)($_POST['nbRondes']);
$tournament->address = filter_var( $_POST['address'], FILTER_SANITIZE_STRING);
$tournament->city = filter_var( $_POST['city'], FILTER_SANITIZE_STRING);
$tournament->country = filter_var( $_POST['country'], FILTER_SANITIZE_STRING);
$tournament->forumlink = filter_var( trim($_POST['forumlink']), FILTER_SANITIZE_STRING);
$tournament->datestart = (int)$_POST['datestart']['year'].'-'.(int)$_POST['datestart']['month'].'-'.(int)$_POST['datestart']['day'];
$tournament->dateend = (int)$_POST['dateend']['year'].'-'.(int)$_POST['dateend']['month'].'-'.(int)$_POST['dateend']['day'];
$tournament->cdf = (empty($_POST['cdf']))?0:1;
$tournament->naf = (empty($_POST['naf']))?0:1;
$tournament->status = (empty($_POST['status']))?0:1;

/* test values */
if( !$tournament->name ){
    $_SESSION['messages'][] = 'Merci de remplir au moins le nom du tournoi';
    header('Location: '.BASE_PATH.'/tournois/modifytournament.php');
    die();
}

/* save tournament*/
$setuserid = (empty($tournament->id));
$tournament->save();
if($setuserid)$tournament->setUserid( $currentUser->id );

/* delete all participants */
participant::deleteParticipantFromTournament( $tournament->id );

/* get ranking factor */
$nbParticipants = sizeof($_POST['coachids']);
$R = cdf_grid_getR( $tournament->type, $tournament->nbRondes, $nbParticipants );

/** recreate participants*/
if( !empty($_POST['coachids'])){
    foreach($_POST['coachids'] as $i=>$p){


        if( $_POST['coachids'][$i] == '' ){//create new coach
            $c = new coach();
            $c->name = filter_var( $_POST['coachnames'][$i], FILTER_SANITIZE_STRING);
            $coachid = $c->id = $c->save();
        }else{
            $coachid = (int)$_POST['coachids'][$i];
        }

        $participant = new participant();
        $participant->coachid          = $coachid;
        $participant->tournamentid     = (int)( $_POST['id']);
        $participant->delta            = $i;
        $participant->roster           = filter_var($_POST['rosters'][$i], FILTER_SANITIZE_STRING);
        $cl = $i+1;
        $participant->cdf_points       = $R*($nbParticipants-$cl )/($nbParticipants+$cl-2);
        $participant->save();
    }
}

$_SESSION['messages'][] = 'Tournoi enregistré.';
header('Location: '.BASE_PATH.'/tournois/details.php?id='.$tournament->id);



