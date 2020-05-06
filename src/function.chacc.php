<?php
// Accountinformationen ändern
    if ($_GET['action'] == 'chacc') {
        if (!isset($_GET['id'])) $_GET['id'] = '';
        if (!isset($_GET['lstChRang'])) $_GET['lstChRang'] = '';
        if (!isset($_GET['txtChGalaxie'])) $_GET['txtChGalaxie'] = '';
        if (!isset($_GET['txtChPlanet'])) $_GET['txtChPlanet'] = '';
        if (!isset($_GET['lstChAllianz'])) $_GET['lstChAllianz'] = '';
        if ($Benutzer['rang'] == $Rang_GC) $_GET['txtChGalaxie'] = $Benutzer['galaxie'];
        if ($Benutzer['rang'] != $Rang_Techniker) $_GET['lstChAllianz'] = $Benutzer['allianz'];
        if ($_GET['id'] != '' && $_GET['lstChRang'] != '' && $_GET['txtChGalaxie'] != '' && $_GET['txtChPlanet'] != '' && $_GET['lstChAllianz'] != '') {
            $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE id="'.$_GET['id'].'";', $SQL_DBConn) or $error_code = 4;
            if (mysqli_num_rows($SQL_Result) == 1) {
                $tmp_rang = tic_mysql_result($SQL_Result, 0, 'rang');
                if ($tmp_rang >= $Benutzer['rang'] || $_GET['lstChRang'] >= $Benutzer['rang'])
                    $error_code = 5;
                else {
                    $SQL_Result = tic_mysql_query('UPDATE `gn4accounts` SET rang="'.$_GET['lstChRang'].'", galaxie="'.$_GET['txtChGalaxie'].'", planet="'.$_GET['txtChPlanet'].'", allianz="'.$_GET['lstChAllianz'].'" WHERE id="'.$_GET['id'].'" and ticid="'.$Benutzer['ticid'].'";', $SQL_DBConn) or $error_code = 7;
                    if ($error_code == 0) LogAction("Accountdaten geändert: ID=".$_GET['id']."; Koordinaten=".$_GET['txtChGalaxie'].":".$_GET['txtChPlanet']."; Rang=".$_GET['lstChRang']."; Allianz=".$_GET['lstChAllianz'].";");
                }
            } else {
                $error_code = 8;
            }
        } else $error_code = 6;
    }
?>
