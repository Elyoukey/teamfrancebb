

    <?php foreach($this->tournaments as $i=>$tournament):?>
    <div class="tournament tournamentblock">
        <div class="sticker">
            <?php if ($tournament['cdf']): ?>
                <img src="<?php echo BASE_PATH?>/includes/images/sticker_nafcdf.gif" alt="tournoi homologué cdf" />
            <?php elseif($tournament['naf']):?>
                <img src="<?php echo BASE_PATH?>/includes/images/sticker_naf.gif" alt="tournoi homologué cdf" />
            <?php endif;?>
        </div>
        <a class="name" href="<?php echo BASE_PATH.'/tournois/details.php?id='.$tournament['id'];?>"><?php echo $tournament['name'];?></a>
        <div class="city">
            <?php echo $tournament['city'];?>
        </div>
        <div class="date" width="1" nowrap>
            <?php echo tournament::displayDate( $tournament['datestart'] , $tournament['dateend'], true); ?>
        </div>


    </div>
    <?php endforeach;?>

    <div class="details">
        <br/>
        <a href="<?php echo BASE_PATH;?>/tournois/index.php">Tous les tournois</a>
    </div>
