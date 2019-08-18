<?php foreach($this->tournaments as $i=>$tournament):?>
<?php if(!empty($tournament['podium'])):?>

    <div class="tournament palmaresblock">
        <a class="name" href="<?php echo BASE_PATH.'/tournois/details.php?id='.$tournament['id'];?>"><?php echo $tournament['name'];?></a>
        <div class="date" >
            <?php echo tournament::displayDate( $tournament['datestart'] , $tournament['dateend'], true); ?>
        </div>
        <div class="podium ranking">
            <?php foreach ($tournament['podium'] as $j=>$coach ):
                if( $coach['roster'] ){
                    $background = 'includes/images/teamlogos/'. strtolower( str_replace( ' ','' , $coach['roster']) )  .'.gif';
                }else{
                    $background = 'includes/images/rosters/noroster.gif';
                }
                ?>
                <div class="coach">
                    <span class="ranking rank<?php echo $j+1;?> fa fa-trophy" style="background:none"></span>
                    <img class="roster" src="<?php echo $background;?>" alt="<?php echo $coach['roster'];?>"/>
                    <?php echo $coach['name'];?>
                </div>
            <?php endforeach;?>
        </div>


    </div>
<?php endif;?>
<?php endforeach;?>

