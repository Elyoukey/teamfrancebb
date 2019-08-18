<div class="tournamentdetails">
    <?php if(  $this->hasAccess( $currentUser ) ):?>
        <div class="admin">
            <a href="<?php echo BASE_PATH?>/tournois/modifytournament.php?id=<?php echo $this->id;?>">Modifier</a>
        </div>
    <?php endif;?>
    <div class="infos">
        <?php if ($this->cdf):?>
            <img src="<?php echo BASE_PATH?>/includes/images/logo_cdf.gif" alt="tournoi homologuÃ© cdf" />
        <?php endif;?>

        <?php if ($this->naf):?>
            <img src="<?php echo BASE_PATH?>/includes/images/logo_naf.gif" alt="tournoi homologuÃ© naf" />
        <?php endif;?>
        <div class="location">
            <label>Où ?</label>
            <?php echo $this->address;?><br/>
            <?php echo $this->postalcode;?><br/>
            <?php echo $this->city;?><br/>
        </div>
        <div class="dates">
            <label>Quand ? </label>
            <?php echo tournament::displayDate( $this->datestart , $this->dateend); ?>
        </div>
        <div class="type">
            <b>Type: </b>
            <?php switch ($this->type){
                case 'ID':
                    echo 'Individuel';
                    break;
                case 'EP':
                    echo 'Equipe partielle';
                    break;
                case 'ET':
                    echo 'Equipe totale';
                    break;

            } ?><br/>
        </div>
        <div class="nbrondes">
            <b>Nombre de rondes: </b>
            <?php echo $this->nbRondes;?>
        </div>
        <div class="link">
            <a  href="<?php echo $this->forumlink;?>" target="_blank"><span class="fa fa-group"></span> Plus d'informations sur le forum</a>
        </div>

    </div>


    <div class="ranking">
        <b>Classement</b>
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>&nbsp;</th>
                <th>Coach</th>
            </tr>
            </thead>
            <?php foreach($this->participants as $i=>$p ):?>
                <tr class="rank<?php echo $i+1;?>">
                    <td align="right" >
                        <?php $class = ($i<3)?'fa fa-trophy':'';  ?>
                        <span class="<?php echo $class;?>">
                            <?php echo $i+1; ?>
                        </span>
                    </td>
                    <td align="center">
                        <?php
                        if( $p['roster'] ){
                            $img = BASE_PATH.'/includes/images/teamlogos/'. strtolower( str_replace( ' ','' , $p['roster']) )  .'.gif';
                        }else{
                            $img = BASE_PATH.'/includes/images/rosters/noroster.gif';
                        }
                        ?>
                        <img class="roster" src="<?php echo $img;?>" alt="<?php echo $p['roster'];?>"/>

                    </td>
                    <td>
                        <?php echo $p['name']?>
                        <small>(<?php echo number_format($p['pcdf_points'],4 ); ?>)</small>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    
    <?php if( $this->type == 'ET' || $this->type == 'ET'):?>
    <div class="ranking">
        <b>Classement d'équipe</b>
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Equipe</th>
            </tr>
            </thead>
            <?php foreach($this->teamsparticipants as $i=>$p ):?>
                <tr class="rank<?php echo $i+1;?>">
                    <td align="right" >
                        <?php $class = ($i<3)?'fa fa-trophy':'';  ?>
                        <span class="<?php echo $class;?>">
                            <?php echo $i+1; ?>
                        </span>
                    </td>
                    <td>
                        <?php echo $p['name']?>
                        <small>(<?php echo number_format($p['pcdf_points'],4 ); ?>)</small>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif;?>
</div>



