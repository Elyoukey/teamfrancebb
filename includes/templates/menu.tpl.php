<nav>
    <ul>
        <li>
            
            <a href="<?php echo BASE_PATH?>/classement/cdf_glissant.php">Classement CDF</a>
            <ul>
                <li> <a href="<?php echo BASE_PATH?>/classement/cdf_glissant.php">Classement Glissant</a> </li>
                <li> <a href="<?php echo BASE_PATH?>/classement">Classement CDF 2019</a> </li>
                <li> <a href="<?php echo BASE_PATH?>/classement?year=2018">Classement CDF 2018</a> </li>
                <li> <a href="<?php echo BASE_PATH?>/classement?year=2017">Classement CDF 2017</a> </li>
                <li> <a href="<?php echo BASE_PATH?>/classement?year=2016">Classement CDF 2016</a> </li>
                <li> <a href="<?php echo BASE_PATH?>/classement?year=2015">Classement CDF 2015</a> </li>
                <li> <a href="<?php echo BASE_PATH?>/classement?year=2014">Classement CDF 2014</a> </li>
                <li> <a href="<?php echo BASE_PATH?>/classement?year=2013">Classement CDF 2013</a> </li>
                <li> <a href="<?php echo BASE_PATH?>/classement?year=2012">Classement CDF 2012</a> </li>
            </ul>
        </li>
        <li>
            <a href="<?php echo BASE_PATH?>/tournois">Tournois</a>
            <ul>
                <li> <a href="<?php echo BASE_PATH?>/tournois/">Liste compl&egrave;te</a> </li>

                <?php if($currentUser->status > 0 ):?>
                    <li> <a href="<?php echo BASE_PATH?>/tournois/modifytournament.php">Ajouter un tournoi</a> </li>
                <?php endif; ?>
            </ul>
        </li>
        <li>
            <a href="http://teamfrancebb.positifforum.com" target="_blank">Forum</a>
        </li>
    </ul>
</nav>
