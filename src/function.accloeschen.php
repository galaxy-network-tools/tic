<?php
// Account löschen
    if ($_GET['action'] == 'accloeschen') {
        if (!isset($_GET['id'])) $_GET['id'] = '';
        if ($_GET['id'] != '') {
        if($Benutzer['rang']==0) die;
            $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE id="'.$_GET['id'].'";', $SQL_DBConn) or $error_code = 4;
            if (mysql_num_rows($SQL_Result) == 1) {
                $tmp_rang = mysql_result($SQL_Result, 0, 'rang');
                $tmp_galaxie = mysql_result($SQL_Result, 0, 'galaxie');
                $tmp_alli = mysql_result($SQL_Result,0,'allianz');
                if ($Benutzer['allianz'] != $tmp_alli && $Benutzer['rang']<4 ){
                $error_code = 5;
                }else{
                if ($tmp_rang >= $Benutzer['rang'])
                    $error_code = 5;
                else {
                    if (!($Benutzer['rang'] == $Rang_GC && $tmp_galaxie != $Benutzer['galaxie']))
                        $SQL_Result = tic_mysql_query('DELETE FROM `gn4accounts` WHERE id='.$_GET['id'].'', $SQL_DBConn) or $error_code = 7;
                        if ($error_code == 0) LogAction("Account gelöscht: ID=".$_GET['id'].";");
                    else
                        $error_code = 5;
                }
                }
            } else {
                $error_code = 8;
            }
        } else $error_code = 6;
    }
?>
