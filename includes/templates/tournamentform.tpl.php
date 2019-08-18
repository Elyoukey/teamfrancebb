<form class="tournamentform" action="<?php echo BASE_PATH?>/tournois/actions/save.php" xmlns="http://www.w3.org/1999/html" method="POST">

    <input type="hidden" name="id" value="<?php echo ($this->id)?$this->id:'';?>"/>

    <label for="name">Nom*</label><input type="text" name="name" value="<?php echo $this->name;?>"/>
    <label for="cdf">CDF</label><input type="checkbox" name="cdf" value="1" <?php echo ($this->cdf)?'checked="checked"':'';?>/>
    <label for="cdf">NAF</label><input type="checkbox" name="naf" value="1" <?php echo ($this->naf)?'checked="checked"':'';?>/>
    <label for="typeID">Individuel</label>
        <input type="radio" id="typeID" name="type" value="ID" <?php echo ($this->type=='ID')?'checked="checked"':'';?>/>
    <label for="typeEP">Equipe partielle</label>
        <input type="radio" id="typeEP" name="type" value="EP" <?php echo ($this->type=='EP')?'checked="checked"':'';?>/>
    <label for="typeET">Equipe totale</label>
        <input type="radio" id="typeET" name="type" value="ET" <?php echo ($this->type=='ET')?'checked="checked"':'';?>/>
    <label for="cdf">Nombre de rondes</label>
        <input type="number" name="nbRondes" value="<?php echo $this->nbRondes;?>" />

    <label for="datestart">Dates </label>
    du
    <select name="datestart[day]">
        <?php for($i=1;$i<=31;$i++):?>
            <option value="<?php echo $i;?>" <?php if( $i == date('j',strtotime($this->datestart)) )echo'selected="selected"' ?>>
                <?php echo $i;?>
            </option>
        <?php endfor;?>
    </select>
    <select name="datestart[month]">
        <?php for($i=1;$i<13;$i++):?>
            <option value="<?php echo $i;?>" <?php if( $i == date('n',strtotime($this->datestart)) )echo'selected="selected"' ?> >
                <?php echo utf8_encode( strftime('%B',mktime(1,1,1,$i,1) ) );?>
            </option>
        <?php endfor;?>
    </select>
    <select name="datestart[year]">
        <?php for($i=-10;$i<20;$i++):?>
            <option value="<?php echo $i+2016;?>" <?php if( $i+2016 == date('Y',strtotime($this->datestart)) )echo'selected="selected"' ?> >
                <?php echo $i+2016;?>
            </option>
        <?php endfor;?>
    </select>
    <br/>
    au
    <select name="dateend[day]">
        <?php for($i=1;$i<=31;$i++):?>
            <option value="<?php echo $i;?>" <?php if( $i == date('j',strtotime($this->dateend)) )echo'selected="selected"' ?> >
                <?php echo $i;?>
            </option>
        <?php endfor;?>
    </select>
    <select name="dateend[month]">
        <?php for($i=1;$i<13;$i++):?>
            <option value="<?php echo $i;?>" <?php if( $i == date('n',strtotime($this->dateend)) )echo'selected="selected"' ?> >
                <?php echo utf8_encode( strftime('%B',mktime(1,1,1,$i,1) ) );?>
            </option>
        <?php endfor;?>
    </select>
    <select name="dateend[year]">
        <?php for($i=-10;$i<20;$i++):?>
            <option value="<?php echo $i+2016;?>" <?php if( $i+2016 == date('Y',strtotime($this->dateend)) )echo'selected="selected"' ?>>
                <?php echo $i+2016;?>
            </option>
        <?php endfor;?>
    </select>


    <label for="address">Adresse</label>
    <textarea name="address"><?php echo $this->address;?></textarea>

    <label for="postalcode">Code postal</label>
    <input type="text" name="postalcode" value="<?php echo $this->postalcode;?>"/>
    <label for="city">Ville</label>
    <input type="text" name="city" value="<?php echo $this->city;?>"/>
    <label for="country">Pays</label>
    <input type="text" name="country" value="<?php echo $this->country;?>"/>
    <label for="forumlink">Lien (sur le forum)</label>
    <input type="text" name="forumlink" value="<?php echo $this->forumlink;?>"/>
    <i class="small">Précisez un lien vers les règles ou vers un topic du forum. Merci</i>
    <label for="cdf">Classement validé</label>
        <input type="checkbox" name="status" value="1" <?php echo ($this->status)?'checked="checked"':'';?>/>
    <br/>
    <i class="small">Merci de cocher cette case une fois le classement saisie. Ainsi le calcul du classement CDF pourra s'effectuer automatiquement.</i>
    <br/>
    <br/>
    <input type="submit" value="Enregistrer"/>

    <hr/>
    <?php if(!empty($this->id)):?>
    <div class="coachslist">
        <fieldset class="">
            <legend>Tous les coachs</legend>
            <select multiple="true" size="15" id="selectCoaches">
                <?php foreach($coachslist->coachs as $i=>$c):?>
                    <option value="<?php echo $c['id'];?>"><?php echo $c['name'];?></option>
                <?php endforeach;?>
            </select>
            <i class="small">Liste de tous les coachs du site, merci de chercher d'abord dans cette liste avant d'ajouter des coachs</i>
            <div class="buttons">
                <select id="roster_coaches" name="rostercoaches" >
                    <option value=""> -- roster -- </option>
                    <?php foreach ($rosters as $j=>$roster): ?>
                        <option value="<?php echo $roster;?>" >
                            <?php echo $roster;?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="button" value=">>" onclick="moveCoach()"/>
            </div>
        </fieldset>


        <fieldset>
            <legend>Création de coach</legend>
            <div class="buttons">
                <input type="button" value=">>" onclick="moveNewCoach()"/>
            </div>
            <input name="newcoach" id="newCoachName">
            <br/>
            <i class="small">Si un coach n'est pas dans la liste au dessus, il est possible de le créer ici. Merci de bien vérifier pour éviter les doublons !</i>

        </fieldset>

    </div>

    <fieldset class="participantslist">
        <legend>Classement</legend>
        <ol id="participantsList" class="ui-sortable">
        <?php foreach($this->participants as $i=>$p):?>

                <li id="participant_<?php echo $p['id'];?>">
                    <?php echo $p['name'];?>
                    <input type="hidden" name="coachids[]" value="<?php echo $p['coachid'];?>">
                    <input type="hidden" name="coachnames[]" value="<?php echo $p['coachname'];?>">
                    <br/>
                    <select name="rosters[]" value="<?php echo $p['roster'];?>">
                        <option value=""> -- roster -- </option>
                        <?php foreach ($rosters as $j=>$roster): ?>
                            <option value="<?php echo $roster;?>" <?php echo($roster==$p['roster'])?'selected="selected"':''; ?> >
                                <?php echo $roster;?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input class="delete" type="button" value="X" onclick="deleteRow(this)"/>
                </li>

        <?php endforeach;?>
        </ol>
    </fieldset>
    <hr/>
    <input type="submit" value="Enregistrer"/>
    <?php endif;?>
</form>