<?php

require_once "tournament.class.php";

class tournamentslist {
    var $tournaments;

    function     __construct(){

    }

    function getNext(){
        global $db;
        $sql ="SELECT * FROM tournaments WHERE datestart > NOW() ORDER BY datestart ASC  ";


        if (!$result = $db->query($sql)) {
            // to get the error information
            echo "Error: erreur dans la requete de liste de tournois \n";
            exit;
        }
        while ($tournoi = $result->fetch_assoc()) {
            $this->tournaments[] = $tournoi;
        }
    }
    
    function getNextMonth(){
        global $db;
        $sql ="SELECT * FROM tournaments WHERE datestart > NOW() AND datestart < DATE_ADD(NOW(), INTERVAL 1 MONTH) ORDER BY datestart ASC  ";

        if (!$result = $db->query($sql)) {
            // to get the error information
            echo "Error: erreur dans la requete de liste de tournois \n";
            exit;
        }
        while ($tournoi = $result->fetch_assoc()) {
            $this->tournaments[] = $tournoi;
        }
    }

    function getAll( $limit = '' ){
        global $db;
        $sql ="SELECT * FROM tournaments ORDER BY datestart DESC  ";

        if (!$result = $db->query($sql)) {
            // to get the error information
            echo "Error: erreur dans la requete de liste de tournois \n";
            exit;
        }
        while ($tournoi = $result->fetch_assoc()) {
            $this->tournaments[] = $tournoi;
        }
    }

    function getPalmares(){
        global $db;
        $sql ="SELECT * FROM tournaments 

        WHERE datestart < NOW() 
        AND status=1
        ORDER BY datestart DESC 
        LIMIT 0,5 ";


        if (!$result = $db->query($sql)) {
            // to get the error information
            echo "Error: erreur dans la requete de liste de tournois \n";
            exit;
        }
        while ($tournoi = $result->fetch_assoc()) {
            $queryParticipants = '
              SELECT *, p.id as pid
              FROM participants as p  
              LEFT JOIN coachs as c ON c.id = p.coachid
              WHERE tournamentid=? 
              ORDER BY p.delta
              LIMIT 0,3
               ';
            $stmtParticipants = $db->prepare( $queryParticipants );
            $stmtParticipants->bind_param('s', $tournoi['id']);
            $stmtParticipants->execute();
            $resultParticipants = $stmtParticipants->get_result();
            while ($participant = $resultParticipants->fetch_assoc()) {
                $tournoi['podium'][] = $participant;
            }

            $this->tournaments[] = $tournoi;


        }
    }

    function render(){
        global $currentUser;
        ob_start();
        include __DIR__.'/../templates/tournamentslist.tpl.php';
        return ob_get_clean();
    }
    function renderBlock(){
        global $currentUser;
        ob_start();
        include __DIR__.'/../templates/tournamentslist.block.tpl.php';
        return ob_get_clean();
    }

    function renderPalmares(){
        global $currentUser;
        ob_start();
        include __DIR__.'/../templates/palmares.block.tpl.php';
        return ob_get_clean();
    }

}

?>