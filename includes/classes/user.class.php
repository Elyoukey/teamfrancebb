<?php

class user{
    var $id;
    var $name;
    var $email;
    var $password;
    var $hash;
    var $coachid;
    var $status;


    /* create a user object */
    function __construct(){
        global $db;

    }

    /* Loading datas */
    function loadFromId( $id ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM users  WHERE id=?' );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->loadFromRow( $row );
        }

    }

    function loadFromHash( $hash ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM users  WHERE hash=?' );
        $stmt->bind_param('s', $hash);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->loadFromRow( $row );
        }
    }

    function loadFromName( $name ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM users  WHERE name=?' );
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->loadFromRow( $row );
        }
    }

    function loadFromEmail( $email ){
        global $db;
        $stmt = $db->prepare( 'SELECT * FROM users  WHERE email=?' );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->loadFromRow( $row );
        }
    }

    function loadFromRow( $row ){
        $this->id       = $row['id'];
        $this->name     = $row['name'];
        $this->password = $row['password'];
        $this->email    = $row['email'];
        $this->coachid  = $row['coachid'];
        $this->hash     = $row['hash'];
        $this->status   = $row['status'];
    }

    function save(){
        global $db;
        $query = '
          INSERT INTO users ( 
            id,
            name,
            email,
            coachid,
            password,
            hash,
            status
            )
            VALUES
            (
            ?,?,?,?,?,?,?
            )
            ON DUPLICATE KEY UPDATE
            
            name=?,
            email=?,
            coachid=?,
            password=?,
            hash=?,
            status=?
           ';
        $stmt = $db->prepare( $query );
        $stmt->bind_param('ississississi',
            $this->id,
            $this->name,
            $this->email,
            $this->coachid,
            $this->password,
            $this->hash,
            $this->status,
            $this->name,
            $this->email,
            $this->coachid,
            $this->password,
            $this->hash,
            $this->status
        );
        return $stmt->execute();
    }
    function saveNew(){
        global $db;
        $query = '
          INSERT INTO users ( 
            name,
            email,
            coachid,
            password,
            hash,
            status
            )
            VALUES
            (
            ?,?,?,?,?,?
            )
           ';
        $stmt = $db->prepare( $query );
        $stmt->bind_param('ssissi',
            $this->name,
            $this->email,
            $this->coachid,
            $this->password,
            $this->hash,
            $this->status

        );
        return $stmt->execute();


    }

    /**/
    function authenticate( $name, $password ){
        global $db;
        $this->loadFromName($name);
        $this->name = $name;
        if( $this->status < 1 )return false;

        if (user_check_password($password, $this)) {
            return true;
        }
        else{
            return false;
        }
    }


    /* Forms render function */
    function renderLogin(){
        global $currentUser;
        ob_start();
        if( !empty($this->id) ){
            include __DIR__.'/../templates/userinfos.tpl.php';
        }else{
            include __DIR__.'/../templates/loginform.tpl.php';
        }

        return ob_get_clean();
    }

    function renderCreateaccount(){
        ob_start();
        include __DIR__.'/../templates/userform.tpl.php';
        return ob_get_clean();
    }

    function renderResetpassword1(){
        ob_start();
        include __DIR__.'/../templates/userresetpassword1.tpl.php';
        return ob_get_clean();
    }
    function renderResetpassword2(){
        ob_start();
        include __DIR__.'/../templates/userresetpassword2.tpl.php';
        return ob_get_clean();
    }
    /* */

}


?>