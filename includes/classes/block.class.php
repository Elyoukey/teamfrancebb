<?php
class block{
    public $variables ;

    function __construct(){
        $this->variables = array(
            'title'=>'',
            'content'=>'',
            'classes'=>'',
            'picto'=>''
        );
    }
    function render(){
        $variables = $this->variables;
        ob_start();
        include __DIR__.'/../templates/block.tpl.php';
        return ob_get_clean();
    }
}
?>