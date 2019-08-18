<?php

class mainpage{
    public $variables ;

    function __construct(){
        $this->variables = array(
            'page-type'     =>'default',
            'js'            =>array(),
            'block_menu'    =>'',
            'blocks_left'   =>'',
            'title'         =>'',
            'maincontent'   =>'',
            'blocks_footer' =>''

        );
}
    function render(){
        global $currentUser;

        $variables = $this->variables;
        ob_start();
        include __DIR__.'/../templates/html.tpl.php';
        return ob_get_clean();
    }
}
?>