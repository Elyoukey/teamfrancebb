<?php
$timer = time();
/* include bootstrap file */
include '../bootstrap.php';

/* reset all coaches */
$query = '
    UPDATE coachs SET cdf_g_points=0, g_comments ="" WHERE 1 
';
$db->query($query);

/* get all relevant tournaments *
$query = '
  SELECT * FROM tournaments WHERE status = 1 AND YEAR(datestart) = year(NOW());
';
$stmt = $db->prepare( $query );
echo $db->error;
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    var_dump($row);
}
*/
$date_g = date( 'Y-m-d 00:00:00' ,strtotime('-1 year') );

/* get all coaches */
$query = '
  SELECT c.id as cid, c.name AS cname FROM coachs AS c
  LEFT JOIN participants AS p on p.coachid = c.id
  LEFT JOIN tournaments AS t ON t.id = p.tournamentid
  
  WHERE 
  t.status=1
  AND
  t.datestart > "'.$date_g.'"
  ;
';
$stmt = $db->prepare( $query );
if(!$stmt)echo $db->error;
$stmt->execute();
$result = $stmt->get_result();
$counter=0;
/* for each coach */
while ($coach = $result->fetch_assoc()) {
    $counter ++;
    $total = 0;
    $comments = '';
    $i=0;
    $id = $coach['cid'];

    $queryP = ' 
      SELECT cdf_points, t.name as tname
      FROM participants as p
      LEFT JOIN tournaments as t ON t.id = p.tournamentid
      WHERE 
      t.status=1 
      AND 
      p.coachid='.$id.'
      AND 
      t.datestart > "'.$date_g.'"
      AND 
      t.cdf = 1
      ORDER BY p.cdf_points DESC
    ';
    $stmtP = $db->prepare($queryP);
    $stmtP->execute();

    $resultP = $stmtP->get_result();
    $tComments = array();
    while ($p = $resultP->fetch_assoc()) {

        if($i<4){
            $total += $p['cdf_points'];
            $tComments[] = '<span class="cdf_active">'.number_format($p['cdf_points'],4).' '.$p['tname'].'</span>';

        }else{
            $tComments[] = '<span class="cdf_inactive">'.number_format($p['cdf_points'],4).' '.$p['tname'].'</span>';
        }
        $comments .='<br/>';
        $i++;
    }

    $comments = implode('<br/>',$tComments);

    $queryUpdate = '
        UPDATE coachs SET 
        cdf_g_points = ?, 
        g_comments = ?
        WHERE id = ? 
    ';

    $stmt = $db->prepare( $queryUpdate );
    $stmt->bind_param( 'dsi',
        $total,
        $comments,
        $id
    );
    $stmt->execute();

}

echo $counter.'<br/>';
echo time() - $timer;

?>