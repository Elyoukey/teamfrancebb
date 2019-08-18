<?php
class menu{

    function render(){
        global $currentUser;
        ob_start();
        include __DIR__.'/../templates/menu.tpl.php';
        return ob_get_clean();
    }

}