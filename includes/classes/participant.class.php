<?php
class participant{
    public $id;
    public $coachid;
    public $tournamentid;
    public $delta;
    public $roster;
    public $cdf_points;

    /* Loading datas */
    function loadFromId( $id ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM participants  WHERE id=?' );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->loadFromRow( $row );
        }
    }

    function loadFromRow( $row ){
        $this->id               = $row['id'];
        $this->coachid          = $row['coachid'];
        $this->tournamentid     = $row['tournamentid'];
        $this->delta            = $row['delta'];
        $this->roster           = $row['roster'];
        $this->cdf_points       = $row['cdf_points'];
    }

    static function deleteParticipantFromTournament( $tid ){
        global $db;
        $query = 'DELETE FROM participants WHERE tournamentid=? ';
        $stmt = $db->prepare( $query );
        $stmt->bind_param('i',$tid);
        $stmt->execute();
    }

    function save(){
        global $db;
        $query = '
          INSERT INTO participants ( 
            id,
            coachid,
            tournamentid,
            delta,
            roster,
            cdf_points
            )
            VALUES
            (
            ?,?,?,?,?,?
            )
            ON DUPLICATE KEY UPDATE
            
            coachid=?,
            tournamentid=?,
            delta=?,
            roster=?,
            cdf_points=?
           ';
        $stmt = $db->prepare( $query );
        $stmt->bind_param('iiiisdiiisd',
            $this->id,
            $this->coachid,
            $this->tournamentid,
            $this->delta,
            $this->roster,
            $this->cdf_points,
            $this->coachid,
            $this->tournamentid,
            $this->delta,
            $this->roster,
            $this->cdf_points
        );

        $stmt->execute();
//echo $db->error;
    }
}
?>