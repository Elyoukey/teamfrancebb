<label>Recherche:</label>
<input type="text" id="tournaments-filter" onkeyup="tablefilter(this.value, 'tournaments-list' )"/>
<table class="tournamentslist" id="tournaments-list" width="100%">
    <thead>
        <tr>
            <th>Tournoi</th>
            <th>Date</th>
            <th>Ville</th>
        </tr>
    </thead>
    <?php foreach($this->tournaments as $i=>$tournament):?>
    <tr class=" <?php if( strtotime($tournament['dateend']) < time() )echo 'passed' ?> ">
        <td class="name" width="55%" >
            <a href="<?php echo BASE_PATH.'/tournois/details.php?id='.$tournament['id'];?>"><?php echo $tournament['name'];?></a>
            <?php if($currentUser->id == $tournament['userid'] || $currentUser->status>=2 ):?>
                [<a href="<?php echo BASE_PATH?>/tournois/modifytournament.php?id=<?php echo $tournament['id'];?>" class="small"> modifier </a>]
            <?php endif;?>
        </td>
        <td class="date" width="1" nowrap><?php echo tournament::displayDate( $tournament['datestart'] , $tournament['dateend']); ?></td>
        <td class="city"><?php echo $tournament['city'];?></td>
        <td class="cdf">
            <?php if ($tournament['cdf']):?>
                <img src="<?php echo BASE_PATH?>/includes/images/logo_cdf.gif" alt="tournoi homologué cdf" />
            <?php else:?>
            <?php endif;?>
        </td>
        <td class="naf">
            <?php if ($tournament['naf']):?>
                <img src="<?php echo BASE_PATH?>/includes/images/logo_naf.gif" alt="tournoi homologué naf" />
            <?php else:?>
            <?php endif;?>
        </td>
    </tr>
    <?php endforeach;?>
</table>