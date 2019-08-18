<?php
class hash{
    public $id;
    public $userid;
    public $date;
    public $hash;

    function __construct(){
        $this->hash = sha1( md5( rand().time().uniqid() ) );
    }

    function loadFromId( $id ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM hashes  WHERE id=?' );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->loadFromRow( $row );
        }
    }

    function loadFromHash( $hash ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM hashes  WHERE hash=?' );
        $stmt->bind_param('s', $hash);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->loadFromRow( $row );
        }
        echo $db->error;
    }

    function loadFromRow( $row ){
        $this->id = $row['id'];
        $this->userid = $row['userid'];
        $this->date = $row['date'];
        $this->hash = $row['hash'];
    }

    function save(){
        global $db;
        $query = '
          INSERT INTO hashes ( 
            userid,
            date,
            hash
            )
            VALUES
            (
            ?,now(),?
            )
           ';
        $stmt = $db->prepare( $query );
        $stmt->bind_param('is',
            $this->userid,
            $this->hash
        );

        $stmt->execute();
    }

    static function clean(){
        global $db;
        /* reset all old hashes */
        $query = '
        DELETE FROM hashes WHERE date < "'.date('Y-m-d H:i:s',strtotime('-1 hour')).'"
        ';
        $db->query($query);
    }

}