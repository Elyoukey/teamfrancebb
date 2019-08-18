<?php

class team{

    var $id;
    var $name;
    var $cdf_points=0;
    var $cdf_g_points=0;
    var $comments='';
    var $g_comments='';


    /* create a user object */
    function __construct(){
    }

    /* Loading datas */
    function loadFromId( $id ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM teams  WHERE id=?' );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->loadFromRow( $row );
        }

    }

    function loadFromName( $name ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM teams  WHERE name=?' );
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
        $this->cdf_g_points = $row['cdf_g_points'];
        $this->comments     = $row['comments'];
        $this->g_comments   = $row['g_comments'];
    }

    function save(){
        global $db;
        $query = '
          INSERT INTO teams ( 
            id,
            name,
            cdf_points,
            cdf_g_points,
            comments,
            g_comments
            )
            VALUES
            (
            ?,?,?,?,?,?
            )
            ON DUPLICATE KEY UPDATE
            
            name=?,
            cdf_points=?,
            cdf_g_points=?,
            comments=?,
            g_comments=?
           ';
        $stmt = $db->prepare( $query );
        $stmt->bind_param('isddsssddss',
            $this->id,
            $this->name,
            $this->cdf_points,
            $this->cdf_g_points,
            $this->comments,
            $this->g_comments,
            $this->name,
            $this->cdf_points,
            $this->cdf_g_points,
            $this->comments,
            $this->g_comments

        );

        $stmt->execute();echo $db->error;

        return $db->insert_id;
    }


    /* Forms render function */


}


?>