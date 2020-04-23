<?PHP
// Account erstellen
    if ($_POST['action'] == 'accounterstellen') {
        $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE UPPER(name)=UPPER("'.$_POST['txtAccName'].'") OR (galaxie="'.$_POST['txtAccGalaxie'].'" AND planet="'.$_POST['txtAccPlanet'].'");', $SQL_DBConn) or $error_code = 4;
        if (mysql_num_rows($SQL_Result) != 0)
            $error_code = 9;
        else {
            if ($Benutzer['rang'] == $Rang_GC) $_POST['txtAccGalaxie'] = $Benutzer['galaxie'];
            if ($Benutzer['rang'] < $Rang_Techniker) $_POST['lstAllianz'] = $Benutzer['allianz'];
            if (!isset($_POST['txtAccName'])) $_POST['txtAccName'] = '';
            if (!isset($_POST['txtAccGalaxie'])) $_POST['txtAccGalaxie'] = '';
            if (!isset($_POST['txtAccPlanet'])) $_POST['txtAccPlanet'] = '';
            if (!isset($_POST['txtAccPasswort'])) $_POST['txtAccPasswort'] = '';
            if (!isset($_POST['lstAllianz'])) $_POST['lstAllianz'] = '';
            if (!isset($_POST['lstRang'])) $_POST['lstRang'] = 0;
            if ($_POST['txtAccName'] == '' || $_POST['txtAccGalaxie'] == '' || $_POST['txtAccPlanet'] == '' || $_POST['txtAccPasswort'] == '' || $_POST['lstAllianz'] == '') {
                $error_code = 6;
            } else {
                if ($Benutzer['rang'] <= $_POST['lstRang'])
                    $error_code = 5;
                else {
                    addgnuser($_POST['txtAccGalaxie'], $_POST['txtAccPlanet'], $_POST['txtAccName']);
                    $SQL_Result = tic_mysql_query('INSERT INTO `gn4accounts` (ticid, name, passwort, galaxie, planet, rang, allianz) VALUES ("'.$AllianzInfo[$_POST['lstAllianz']]['meta'].'", "'.$_POST['txtAccName'].'", "'.md5($_POST['txtAccPasswort']).'","'.$_POST['txtAccGalaxie'].'", "'.$_POST['txtAccPlanet'].'", "'.$_POST['lstRang'].'", "'.$_POST['lstAllianz'].'")', $SQL_DBConn) or $error_code = 7;
                    if ($error_code == 0) LogAction("Account erstellt: Name=".$_POST['txtAccName']."; Koordinaten=".$_POST['txtAccGalaxie'].":".$_POST['txtAccPlanet']."; Rang=".$_POST['lstRang']."; Allianz=".$_POST['lstAllianz'].";");
                }
            }
        }
    }
?>
