<?php
class tournament{
    public $id;
    public $name;
    public $type='ID';
    public $nbRondes=5;
    public $address;
    public $postalcode;
    public $city;
    public $country='France';
    public $forumlink;
    public $datestart;
    public $dateend;
    public $participants = array();
    public $teamsparticipants = array();
    public $userid;
    public $cdf;
    public $naf;
    public $status=0;

    /* Loading datas */
    function loadFromId( $id ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM tournaments  WHERE id=?' );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->loadFromRow( $row );
        }
        $this->loadParticipants();
    }

    function loadFromRow( $row ){
        $this->id           = $row['id'];
        $this->name         = $row['name'];
        $this->type         = $row['type'];
        $this->nbRondes     = $row['nbRondes'];
        $this->address      = $row['address'];
        $this->postalcode   = $row['postalcode'];
        $this->city         = $row['city'];
        $this->country      = $row['country'];
        $this->forumlink    = $row['forumlink'];
        $this->datestart    = $row['datestart'];
        $this->dateend      = $row['dateend'];
        $this->userid       = $row['userid'];
        $this->cdf          = $row['cdf'];
        $this->naf          = $row['naf'];
        $this->status       = $row['status'];
    }

    function loadParticipants(){
        global $db;
        $query = '
          SELECT *, p.id as pid, c.name as coachname, p.cdf_points as pcdf_points
          FROM participants as p  
          LEFT JOIN coachs as c ON c.id = p.coachid
          WHERE tournamentid=? 
          ORDER BY p.delta
           ';
        $stmt = $db->prepare( $query );
        $stmt->bind_param('s', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $this->participants[] = $row;
        }
    }

    function loadTeamsParticipants(){
        global $db;
        $query = '
          SELECT *, p.id as pid, c.name as coachname, p.cdf_points as pcdf_points
          FROM teams_participants as tp  
          LEFT JOIN teams as t ON t.id = t.teamid
          WHERE tournamentid=? 
          ORDER BY t.delta
           ';
        $stmt = $db->prepare( $query );
        $stmt->bind_param('s', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $this->teamsparticipants[] = $row;
        }
    }

    
    /* Get lists of tournaments */
    static function displayDate( $d_debut, $d_fin, $hideyear = false ){
        $m_debut = strftime( '%B', strtotime($d_debut) );
        $m_fin = strftime( '%B', strtotime($d_fin) );

        $j_debut = strftime( '%d', strtotime($d_debut) );
        $j_fin = strftime( '%d', strtotime($d_fin) );

        if( $m_debut == $m_fin)
        {
            $date_output = $j_debut.'-'.$j_fin.' '.$m_debut;
        }
        else
        {
            $date_output = $j_debut.' '.$m_debut.'-'.$j_fin.' '.$m_fin;
        }
        if( $m_debut == $m_fin && $j_debut == $j_fin )
        {
            $date_output = $j_fin.' '.$m_debut;
        }
        if(!$hideyear){
            $date_output.= ' '.strftime( '%Y', strtotime($d_fin) );
        }
        return utf8_encode( $date_output );
    }


    function hasAccess( $user ){
        $res = false;
        if($user->id==$this->userid || $user->status >= 2 ) $res = true;
        if($user->status >= 1 && $this->id==0)$res = true;
        return  $res;
    }

    function render(){
        global $currentUser;
        ob_start();
        include __DIR__.'/../templates/tournament.tpl.php';
        return ob_get_clean();
    }
    function renderMap(){
        global $currentUser;
        ob_start();
        include __DIR__.'/../templates/block.map.tpl.php';
        return ob_get_clean();
    }
    function renderForm(){
        global $currentUser;
        global $coachslist;
        global $rosters;
        ob_start();
        include __DIR__.'/../templates/tournamentform.tpl.php';
        return ob_get_clean();
    }

    function save(){
        global $db;
        $query = '
          INSERT INTO tournaments ( 
            id,
            name,
            type,
            nbRondes,
            address,
            postalcode,
            city,
            country,
            forumlink,
            datestart,
            dateend,
            cdf,
            naf,
            status)
            VALUES
            (
            ?,?,?,?,?,?,?,?,?,?,?,?,?,?
            )
            ON DUPLICATE KEY UPDATE
            name=?,
            type=?,
            nbRondes=?,
            address=?,
            postalcode=?,
            city=?,
            country=?,
            forumlink=?,
            datestart=?,
            dateend=?,
            cdf=?,
            naf=?,
            status=?
           ';
        $stmt = $db->prepare( $query );
        if(!$stmt)echo $db->error;
        $stmt->bind_param('issisisssssiiissisisssssiii',
            $this->id,
            $this->name,
            $this->type,
            $this->nbRondes,
            $this->address,
            $this->postalcode,
            $this->city,
            $this->country,
            $this->forumlink,
            $this->datestart,
            $this->dateend,
            $this->cdf,
            $this->naf,
            $this->status,
            $this->name,
            $this->type,
            $this->nbRondes,
            $this->address,
            $this->postalcode,
            $this->city,
            $this->country,
            $this->forumlink,
            $this->datestart,
            $this->dateend,
            $this->cdf,
            $this->naf,
            $this->status
        );
        $stmt->execute();
        if(!$this->id)$this->id = $db->insert_id;
    }


    function setUserid( $userid ){
        global $db;
        $query = 'UPDATE tournaments SET userid = ? WHERE id=?';
        $stmt = $db->prepare( $query );
        if(!$stmt)echo $db->error;
        $stmt->bind_param('ii', $userid, $this->id);
        $stmt->execute();
    }
}
?>