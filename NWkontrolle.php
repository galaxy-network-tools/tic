<?php
    if(!isset($_GET['auto']))
    {
        if(!isset($NW_intervall))
            $NW_intervall = 120;
        if(!isset($NW_start))
            $NW_start     = 22*60;
        if(!isset($NW_stop))
            $NW_stop      = 12*60;
        if($NW_stop < $NW_start)
            $NW_stop += 24*60;

        $today = date("w");
        if(date("H")*60+date("i") < $NW_stop)
            $today--;
			
        if($today < 1)
            $today = 7+$today;
        $time = time() - ($today-1)*86400;

        tic_mysql_query("UPDATE gn4nachtwache SET done".$today."='1' WHERE ticid = '".$Benutzer['ticid']."' AND gala='".$Benutzer['galaxie']."' AND planet".$today." = '".$Benutzer['planet']."' AND ".$time." >= time AND ".($time-$NW_intervall*60)." <= time") or die(tic_mysql_error(__FILE__,__LINE__));

        tic_mysql_query("DELETE FROM gn4nachtwache WHERE time < ".(time()-1209600)) or die(tic_mysql_error(__FILE__,__LINE__));
    }
?>
