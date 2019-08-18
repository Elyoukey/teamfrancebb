<?php
/* 

 */
function cdf_grid_getR( $type, $nbRondes, $nbParticipants)
{
    if(!$nbParticipants)return false;
    if(!$nbRondes)return false;
    if(!$type)return false;


    $percent = 100;
    switch ($type)
    {
    case 'ID':
        $percent = 100;

        break;
    case 'EP':
        $percent = 98;

        break;
    case 'ET':
        $percent = 92;
        
        break;
    }
    
if( $nbRondes < 5 )
{
	$percent = $percent + 10*($nbRondes - 5 );
}
else
{
    $percent = $percent + 1*($nbRondes - 5 );
}

    $n = 95 + floor( ($nbParticipants-1) /10);
    return $n*$percent/100;
    
}


?>
