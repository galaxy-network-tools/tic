<?PHP
// Nachricht schreiben
    if ($_POST['action'] == 'nachrichtschreiben') {
        if ($Benutzer['rang'] <= $Rang_GC)
            $error_code = 5;
        else {
            if (!isset($_POST['txtTitel'])) $_POST['txtTitel'] = '';
            if (!isset($_POST['txtText'])) $_POST['txtText'] = '';
            if (!isset($_POST['txtHC'])) $_POST['txtHC'] = '';


            if ($_POST['txtTitel'] == '' || $_POST['txtText'] == '')
                $error_code = 6;
            else {
                $_POST['txtText'] = str_replace("\n", '<BR>', $_POST['txtText']);
                $ticid=$Benutzer['ticid'];
                if ($_POST['txtHC'] == 'HC') {
                    $ticid="H".$Benutzer['ticid'];
                } else if ($_POST['txtHC'] == 'SHC') {
                    $ticid="SHC";
                } else if ($_POST['txtHC'] == 'alle') {
                    $ticid="alle";
                }
                $SQL_Result = tic_mysql_query('INSERT INTO `gn4nachrichten` (ticid, name, zeit, titel, text) VALUES ("'.$ticid.'", "'.$Benutzer['galaxie'].':'.$Benutzer['planet'].' '.$Benutzer['name'].' ('.$RangName[$Benutzer['rang']].' @ ['.$AllianzTag[$Benutzer['allianz']].'])", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$_POST['txtTitel'].'", "'.$_POST['txtText'].'")', $SQL_DBConn) or $error_code = 7;
                if ($error_code == 0) LogAction("Nachricht geschrieben: Titel='".$_POST['txtTitel']."';");
            }
        }
    }
?>
