<?php

class coach{

    var $id;
    var $name;
    var $cdf_points=0;
    var $userid;
    var $cdf_ranking=-1;
    var $comments='';


    /* create a user object */
    function __construct(){
    }

    /* Loading datas */
    function loadFromId( $id ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM coachs  WHERE id=?' );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->loadFromRow( $row );
        }

    }

    function loadFromName( $name ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM coachs  WHERE name=?' );
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->loadFromRow( $row );
        }
    }

    function loadFromRow( $row ){
        $this->id           = $row['id'];
        $this->name         = $row['name'];
        $this->cdf_points   = $row['cdf_points'];
        $this->userid       = $row['userid'];
        $this->cdf_ranking  = $row['cdf_ranking'];
        $this->comments     = $row['comments'];
    }

    function save(){
        global $db;
        $query = '
          INSERT INTO coachs ( 
            id,
            name,
            cdf_points,
            userid,
            cdf_ranking,
            comments
            )
            VALUES
            (
            ?,?,?,?,?,?
            )
            ON DUPLICATE KEY UPDATE
            
            name=?,
            cdf_points=?,
            userid=?,
            cdf_ranking=?,
            comments=?
           ';
        $stmt = $db->prepare( $query );
        $stmt->bind_param('isdiissdiis',
            $this->id,
            $this->name,
            $this->cdf_points,
            $this->userid,
            $this->cdf_ranking,
            $this->comments,
            $this->name,
            $this->cdf_points,
            $this->userid,
            $this->cdf_ranking,
            $this->comments

        );

        $stmt->execute();echo $db->error;

        return $db->insert_id;
    }


    /* Forms render function */


}


?>