<table class="coachlist ranking full">
    <thead>
    <tr>
       
        <th >name</th>
        <th >email</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->coachs as $i=>$c):?>
        <tr>
            <td class="name " valign="top" align="left"><?php echo $c['name'];?></td>
            <td class="email" valign="top"><?php echo $c['email'];?></td>
            
        </tr>
    <?php endforeach;?>
    </tbody>
</table>