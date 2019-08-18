
<form action="<?php echo BASE_PATH?>/user/actions/saveaccount.php" xmlns="http://www.w3.org/1999/html" method="POST">

    <label for="name">Nom*</label>
    <input type="text" name="name"/>

    <label for="password">Mot de passe*</label>
    <input id="password" type="password" name="password"/>
    <button class="fa fa-eye"
            type="button"
            onclick="
                this.className = (document.getElementById('password').type=='password')?'fa fa-eye-slash':'fa fa-eye';
                document.getElementById('password').type=(document.getElementById('password').type=='password')?'text':'password';"
    ></button>

    <label for="name">Email*</label>
    <input type="email" name="email"/>

    <div class="hname">
        <input type="text" name="hname" />
        <input type="text" name="hash" value=""/>
    </div>
    <hr/>
    <input type="submit" value="Enregistrer"/>
</form>