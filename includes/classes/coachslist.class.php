<?php

require_once "tournament.class.php";

class coachslist {
    var $coachs;

    function     __construct(){

    }

    function getNext(){
        global $db;
    }

    function getAll( $limit = '' ){
        global $db;

        $sql ="SELECT * FROM coachs ORDER BY name ASC  ";
        if($limit)$sql.=$limit;

        if (!$result = $db->query($sql)) {
            // to get the error information
            echo "Error: erreur dans la requete de liste de coachs \n";
            exit;
        }
        while ($coach = $result->fetch_assoc()) {
            $this->coachs[] = $coach;
        }
    }

    function getByRoster( $roster = 'AMAZON'){
        global $db;
var_Dump($roster);
        $sql ='
        SELECT 
        p.cdf_points  AS total,
        c.id AS cid,
        c.name AS name
        FROM 
        participants AS p
        LEFT JOIN coachs AS c ON c.id=p.coachid
        WHERE 
        roster = ?
        ORDER BY total DESC
        ';
        $stmt = $db->prepare( $sql );
        $stmt->bind_param('s', $roster);

        $stmt->execute();
        $result = $stmt->get_result();
        while ($coach = $result->fetch_assoc()) {
            $this->coachs[] = $coach;
        }
        var_Dump($this->coachs);
    }

    function getCDF( $top = null){
        global $db;

        $sql ='
        SELECT 
        *,
        @curRank := @curRank + 1 AS rank
        FROM 
        coachs c,
        (SELECT @curRank := 0) r
        WHERE c.comments <> ""
        ORDER BY c.cdf_points DESC
        
        ';
        if($top){
            $sql.=' LIMIT 0,'.$top;
        }
        if (!$result = $db->query($sql)) {
            // to get the error information
            echo "Error: erreur dans la requete de liste de coachs \n";
            exit;
        }
        while ($coach = $result->fetch_assoc()) {
            $this->coachs[] = $coach;
        }
    }
    function getCDFGlissant( $top = null){
        global $db;

        $sql ='
        SELECT 
        *,
        cdf_g_points AS cdf_points,
        g_comments AS comments,
        @curRank := @curRank + 1 AS rank
        FROM 
        coachs c,
        (SELECT @curRank := 0) r
        WHERE c.g_comments <> ""
        ORDER BY c.cdf_g_points DESC
        
        ';
        if($top){
            $sql.=' LIMIT 0,'.$top;
        }
        if (!$result = $db->query($sql)) {
            // to get the error information
            echo "Error: erreur dans la requete de liste de coachs du classement glissant \n";
            exit;
        }
        while ($coach = $result->fetch_assoc()) {
            $this->coachs[] = $coach;
        }
    }
    
    function getVotants(){
        global $db;

        $sql ='SELECT distinct(email), u.name
            FROM `participants` AS p 
            LEFT JOIN coachs AS c ON p.coachid = c.id
            LEFT JOIN users AS u ON u.coachid = c.id
            
            LEFT JOIN tournaments AS t ON t.id = p.tournamentid
            
            WHERE t.datestart > "2016-01-01 00:00:00"
            ORDER by u.name
        
        ';

        if (!$result = $db->query($sql)) {
            // to get the error information
            echo "Error: erreur dans la requete de liste de coachs \n";
            exit;
        }
        while ($coach = $result->fetch_assoc()) {
            $this->coachs[] = $coach;
        }
    }

    function render(){
        global $currentUser;
        ob_start();
        include __DIR__.'/../templates/coachslist.tpl.php';
        return ob_get_clean();
    }
    function renderByRoster(){
        global $currentUser;
        global $rosters;
        ob_start();
        include __DIR__.'/../templates/coachslistroster.tpl.php';
        return ob_get_clean();
    }

    function renderBlock( $glissant = false ){
        global $currentUser;
        ob_start();
        include __DIR__.'/../templates/coachslist.block.tpl.php';
        return ob_get_clean();
    }
    
    function renderVotants(){
        global $currentUser;
        ob_start();
        include __DIR__.'/../templates/coachslistvotants.tpl.php';
        return ob_get_clean();
    }

}

?>