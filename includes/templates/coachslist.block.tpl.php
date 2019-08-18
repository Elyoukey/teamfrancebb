<?php if( empty($this->coachs) ):?>
 
<?php else:?>
    <table class="coachlist ranking block">
    
        <tbody>
        <?php foreach ($this->coachs as $i=>$c):?>
            <tr>
                <td class="ranking " align="right" width="1" nowrap>
                    <?php $class = ($i<3)?'fa fa-trophy':'';  ?>
                    <div class="rank<?php echo $c['rank'];?> <?php echo $class;?>" style="padding: 6px;">
                       <?php echo $c['rank'];?>
                    </div>
    
                </td>
                <td width="1" class="cdf_points" align="right"><?php echo number_format( $c['cdf_points'], 2);?></td>
                <td class="name " ><?php echo $c['name'];?></td>
    
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <div class="details">
        <?php if($glissant):?>
            <a href="<?php echo BASE_PATH;?>/classement/cdf_glissant.php">Classement glissant</a>
        <?php else: ?>
            <a href="<?php echo BASE_PATH;?>/classement/index.php">Classement sur l'année <?php echo date('Y');?></a>
        <?php endif;?>
    </div>
<?php endif;?>