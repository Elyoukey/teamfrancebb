<?php
class footer{
    function render(){
        ob_start();
        include __DIR__.'/../templates/footer.tpl.php';
        return ob_get_clean();
    }
}