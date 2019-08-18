<?php
setlocale(LC_ALL,'fr_FR.UTF-8');
// on se connecte à MySQL
$db = mysql_connect('cl1-sql22', 'francebb4', 'qxtrvvv');

// on sélectionne la base
mysql_select_db('francebb4',$db);

// on crée la requête SQL
$sql = '
	SELECT * 
	FROM 
		tournaments t
	WHERE t.datestart > NOW()
	ORDER BY t.datestart ASC;
	';

// on envoie la requête
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

// on fait une boucle qui va faire un tour pour chaque enregistrement
$row=0;
while($data = mysql_fetch_assoc($req))
{
	// on affiche les informations de l'enregistrement en cours
	//echo $data['title'];
	//echo $data['field_tourneydate_value'];
	//echo $data['field_tourneydate_value2'];
	/****** TITRE **********/
	$titre = addslashes (utf8_encode( $data['name'] ));
	
	/****** DATES *******/
	$d_debut = $data['datestart'];
	$d_fin = $data['dateend'];
	
	$m_debut = strftime( '%B', strtotime($d_debut) );
	$m_fin = strftime( '%B', strtotime($d_fin) );
	
	$j_debut = strftime( '%d', strtotime($d_debut) );
	$j_fin = strftime( '%d', strtotime($d_fin) );
	
	if( $m_debut == $m_fin)
	{
		$date_output = $j_debut.'-'.$j_fin.' '.$m_debut;
	}
	else
	{
		$date_output = $j_debut.' '.$m_debut.'-'.$j_fin.' '.$m_fin;
	}
	if( $m_debut == $m_fin && $j_debut == $j_fin )
	{
		$date_output = $j_fin.' '.$m_debut;
	}
		
	$date_output;
	
	/****** row style **********/
	$rowstyle = '';
	$rowstyle .= "font-family: arial black, arial;";
	$rowstyle .= "color: black;";
	$rowstyle .= "padding: 3px;";
	$rowstyle .= "font-size: 11px;";
	
	if($row%2)
	{
		$rowstyle .= "background:silver;";
	}
	else
	{
		$rowstyle .= "background: white;";
	}
?>


	item = $('<div style="<?php echo $rowstyle; ?>"></div>');
	item.append("<span><?php echo $date_output; ?><span style='color:#cb000a'>&#9733;</span></span>");
	item.append("<a href='http://www.teamfrancebb.com/tournois/details.php?id=<?php echo  $data['id'];?>' target='_blank'><?php echo $titre; ?></a><span style='color:#cb000a'>&#9733;</span>");
	$('#edf_tournois_a_venir').append(item);
	<?php
	$row++;
}

// on ferme la connexion à mysql
mysql_close();
?>

