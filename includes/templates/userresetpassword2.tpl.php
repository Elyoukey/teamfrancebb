<form class="mainpage" action="<?php echo BASE_PATH?>/user/actions/resetpassword2.php" xmlns="http://www.w3.org/1999/html" method="POST">
    <div class="hname">
        <input name="hname" type="text" />
    </div>
    <label>Nouveau mot de passe</label>
    <input type="password" id="password" name="password" class="password"/>
    
    <button class="fa fa-eye"
            type="button"
            onclick="
                this.className = (document.getElementById('password').type=='password')?'fa fa-eye-slash':'fa fa-eye';
                document.getElementById('password').type=(document.getElementById('password').type=='password')?'text':'password';

"
    ></button>
    
    <br/>
    <input type="submit" value="valider"/>
</form>
