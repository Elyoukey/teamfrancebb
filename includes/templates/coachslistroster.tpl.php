<select>
    <?php foreach($rosters as $r):?>
    <option value="<?php echo $r;?>"><?php echo $r;?></option>
    <?php endforeach;?>
</select>
<?php if( empty($this->coachs) ):?>
    Le classement est vide pour le moment.
<?php else:?>
<table class="coachlist ranking full">
    <thead>
    <tr>
        <th >&nbsp;</th>
        <th >Points</th>
        <th >&nbsp;</th>
        <th >NAF</th>
        <th >Détails</th>
    </tr>
    </thead>
    <tbody>
    
    
    <?php foreach ($this->coachs as $i=>$c):?>
        <tr>
            <td class="ranking " valign="top" align="right">
                <?php $class = ($i<3)?'fa fa-trophy':'';  ?>
                <div class="rank<?php echo $c['rank'];?> <?php echo $class;?>" style="padding: 6px;">
                   <?php echo $c['rank'];?>
                </div>

            </td>
            <td class="cdf_points" valign="top" align="right"><?php echo number_format( $c['total'], 2);?></td>
            <td class="name " valign="top"><?php echo $c['name'];?></td>
            <td class="naf-number" valign="top"><?php echo ($c['naf'])?$c['naf']:'';?></td>
            <td class="comments" valign="top">
                <div class="details">
                    <?php echo $c['comments'];?>
                </div>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php endif;?>