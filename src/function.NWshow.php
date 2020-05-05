<?php
    if(!isset($NW_intervall))
        $NW_intervall = 120;
    if(!isset($NW_start))
        $NW_start     = 22*60;
    if(!isset($NW_stop))
        $NW_stop      = 12*60;
    if($NW_stop < $NW_start)
        $NW_stop += 24*60;

    if(isset($_POST['nachtwache']))
    {
        $today = date("w");
        if(date("H")*60+date("i") < $NW_stop)
            $today--;
	    if($today <= 0)
            $today = 7+$today;
        $todayoffset = ($today-1) * 86400;

        foreach($_POST['nachtwache'] as $time => $data)
        {
            $sqlquery1 = array();
            $sqlquery2 = array();
            $sqlquery3 = array();

            for($i = ($today + ($time+$todayoffset > time() ? 0 : 1));$i <= 7;$i++)
            {
                $sqlquery1[] = "planet".$i;
                $sqlquery2[] = injsafe($data[$i-1]);
                $sqlquery3[] = "planet".$i." = '".injsafe($data[$i-1])."'";
            }
            $SQL_Result = tic_mysql_query("SELECT COUNT(time) FROM gn4nachtwache WHERE gala = '".injsafe($_POST['gala'])."' AND ticid = '".$Benutzer['ticid']."' AND time = '".injsafe($time)."'") or die(tic_mysql_error(__FILE__,__LINE__));
            $count = mysql_fetch_row($SQL_Result);
            if($count[0])
            {
                tic_mysql_query("UPDATE gn4nachtwache SET ".implode(", ", $sqlquery3)." WHERE gala = '".injsafe($_POST['gala'])."' AND ticid = '".$Benutzer['ticid']."' AND time = '".injsafe($time)."'") or die(tic_mysql_error(__FILE__,__LINE__));
            }
            else
            {
                tic_mysql_query("INSERT INTO gn4nachtwache (time, ticid, gala, ".implode(", ", $sqlquery1).") VALUES('".injsafe($time)."', '".$Benutzer['ticid']."', '".injsafe($_POST['gala'])."', '".implode("', '", $sqlquery2)."')") or die(tic_mysql_error(__FILE__, __LINE__));
            }
        }
    }
    if(isset($_POST['nextnachtwache']))
    {
        foreach($_POST['nextnachtwache'] as $time => $data)
        {
            $sqlquery1 = array();
            $sqlquery2 = array();
            $sqlquery3 = array();
            for($i = 0;$i < 7;$i++)
            {
                $sqlquery1[] = "planet".($i+1);
                $sqlquery2[] = injsafe($data[$i]);
                $sqlquery3[] = "planet".($i+1)." = '".injsafe($data[$i])."'";
            }
            $SQL_Result = tic_mysql_query("SELECT COUNT(time) FROM gn4nachtwache WHERE gala = '".injsafe($_POST['gala'])."' AND ticid = '".$Benutzer['ticid']."' AND time = '".injsafe($time)."'") or die(tic_mysql_error(__FILE__,__LINE__));
            $count = mysql_fetch_row($SQL_Result);
            if($count[0])
            {
                tic_mysql_query("UPDATE gn4nachtwache SET ".implode(", ", $sqlquery3)." WHERE gala = '".injsafe($_POST['gala'])."' AND ticid = '".$Benutzer['ticid']."' AND time = '".injsafe($time)."'") or die(tic_mysql_error(__FILE__,__LINE__));
            }
            else
            {
                tic_mysql_query("INSERT INTO gn4nachtwache (time, ticid, gala, ".implode(", ", $sqlquery1).") VALUES('".injsafe($time)."', '".$Benutzer['ticid']."', '".injsafe($_POST['gala'])."', '".implode("', '", $sqlquery2)."')") or die(tic_mysql_error(__FILE__, __LINE__));
            }
        }
    }

?>
