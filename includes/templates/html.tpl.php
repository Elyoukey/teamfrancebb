<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo BASE_PATH?>/includes/css/style.css" media="all" />
    <link rel="stylesheet" href="<?php echo BASE_PATH?>/includes/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_PATH?>/includes/images/favicon.jpg">
    <script src="<?php echo BASE_PATH;?>/includes/js/jquery-3.1.1.min.js" ></script>
    <script src="<?php echo BASE_PATH;?>/includes/js/jquery-ui-1.12.1.custom/jquery-ui.min.js" ></script>
    <script src="<?php echo BASE_PATH;?>/includes/js/generic.js" ></script>
    <?php foreach( $variables['js'] as $script):?>
        <script src="<?php echo $script;?>" ></script>
    <?php endforeach;?>
    <title><?php echo $variables['title'];?></title>
    
    <meta property="og:title" content="<?php echo $variables['title'];?>" />
    <meta property="og:image" content="<?php echo BASE_PATH;?>/includes/images/pompom.png?r=<?php echo uniqid();?>" />

</head>
<body>
<?php if( !empty($_SESSION['messages']) ):
    ?>
    <div class="messages" onclick="this.style.display='none'">
        <?php
        foreach($_SESSION['messages'] as $i=>$m):?>
            <?php echo nl2br( filter_var( $m, FILTER_SANITIZE_STRING) );?><br/>
        <?php endforeach;;?>
        <?php unset($_SESSION['messages']);?>
    </div>
<?php endif; ?>
    <div id="edf8-page-container">
        <header id="edf8-header" class="content-header clearfix" >
            <?php echo $variables['block_menu'];?>
        </header>
        <div class="pompom behind"></div>
        <div class="pompom before"></div>
        <a class="logo" href="<?php echo BASE_PATH?>"></a>

        <div id="edf8-layout-container" class="layout-container" >
            <div id="edf8-left-column" class="left-column" >
                <?php echo $variables['blocks_left'];?>
            </div>

            <?php
            include 'page-'.$variables['page-type'].'.tpl.php';
            ?>


            <footer >
                <?php echo $variables['blocks_footer'];?>
            </footer>
        </div>
    </div>
</body>

</html>