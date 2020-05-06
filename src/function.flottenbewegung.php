<?php
// Ticks
//    $Ticks['angriffsflug'] = 30;
//    $Ticks['angriff'] = 5;
//    $Ticks['verteidigungsflug'] = 22;
//    $Ticks['verteidigen'] = 25;
//    $Ticks['lange'] = 15

    $tsec = $Ticks['lange']*60;
// Flottenbewegung hinzufügen
        $form_ok = true;
        if ((!isset($_POST['modus'])            && !isset($modus))
         || (!isset($_POST['txt_Angreifer_Galaxie'])    && !isset($txt_Angreifer_Galaxie))
         || (!isset($_POST['txt_Angreifer_Planet'])    && !isset($txt_Angreifer_Planet))
         || (!isset($_POST['txt_Verteidiger_Galaxie'])    && !isset($txt_Verteidiger_Galaxie))
         || (!isset($_POST['txt_Verteidiger_Planet'])    && !isset($txt_Verteidiger_Planet))
         || (!isset($_POST['lst_ETA'])            && !isset($lst_ETA))) {
            $form_ok = false;
            $error_code = 6;
            return;
        }
        if (!isset($modus)) $modus = $_POST['modus'];
        if (!isset($lst_ETA)) $lst_ETA = $_POST['lst_ETA'];
        if (!isset($lst_Flotte) && isset($_POST['lst_Flotte'])) $lst_Flotte = $_POST['lst_Flotte'];

        if (!isset($txt_Angreifer_Galaxie)) $txt_Angreifer_Galaxie = $_POST['txt_Angreifer_Galaxie'];
        if (!isset($txt_Angreifer_Planet)) $txt_Angreifer_Planet = $_POST['txt_Angreifer_Planet'];
        if (!isset($txt_Angreifer_Name)) $txt_Angreifer_Name = isset($_POST['txt_Angreifer_Name'])?$_POST['txt_Angreifer_Name']:"";
        if (!isset($txt_Verteidiger_Galaxie)) $txt_Verteidiger_Galaxie = $_POST['txt_Verteidiger_Galaxie'];
        if (!isset($txt_Verteidiger_Planet)) $txt_Verteidiger_Planet = $_POST['txt_Verteidiger_Planet'];
        if (!isset($txt_Verteidiger_Name)) $txt_Verteidiger_Name = isset($_POST['txt_Verteidiger_Name'])?$_POST['txt_Verteidiger_Name']:"";
        if (!isset($txt_not_safe) || ($txt_not_safe != 0 && $txt_not_safe != 1)) $txt_not_safe = 1;

        if (strlen($modus )< 2) {
            if ($modus == 0) $modus = "rueckflug";
            if ($modus == 1) $modus = "angreifen";
            if ($modus == 2) $modus = "verteidigen";
            if ($modus == 3) $modus = "rueckflug_angreifen";
            if ($modus == 4) $modus = "rueckflug_verteidigen";
        }

        /*
            Berechne ankunftzeit, flugzeit_ende und ruckflug_ende
        */
        $_time        = ((int)(time()/($tsec)))*($tsec);
        $_ankunft    = 0;
        $_flugzeit    = 0;
        $_ruckflug    = 0;

        $erfasser = $Benutzer['name'];

        if ($modus == "angreifen")
        {
            $lst_Flugzeit='5';
            $_ankunft  = $_time + ($lst_ETA * $tsec);
            $_flugzeit = $_ankunft + ($lst_Flugzeit * $tsec);
            $_ruckflug = $_flugzeit + (30 * $tsec);
            if (!isset($lst_Flotte)) $lst_Flotte = 0;
        }

        if ($modus == "verteidigen")
        {
            $_ankunft  = $_time + ($lst_ETA * $tsec);
// Zeit im Orbit und Rückflugzeit bestimmen
            if ($txt_Angreifer_Galaxie == $txt_Verteidiger_Galaxie) {    // gleiche Galaxie -> 20 Ticks Orbit, 18 Ticks Rückflug
                $lst_Flugzeit = 20;
                $rfdauer = 18;
            } else {
                $SQL_Result1 = tic_mysql_query("SELECT allianz, ticid FROM `gn4accounts` WHERE galaxie='".$txt_Angreifer_Galaxie."' limit 1;", $SQL_DBConn);
                $ismeta1 = mysql_affected_rows();
                $SQL_Result2 = tic_mysql_query("SELECT allianz, ticid FROM `gn4accounts` WHERE galaxie='".$txt_Verteidiger_Galaxie."' limit 1;", $SQL_DBConn);
                $ismeta2 = mysql_affected_rows();
                if ($ismeta1 && $ismeta2 && mysql_result($SQL_Result1, 0, 'ticid') == mysql_result($SQL_Result2, 0, 'ticid')) { // meta-intern
                    if (mysql_result($SQL_Result1, 0, 'allianz') == mysql_result($SQL_Result2, 0, 'allianz')) { // alli-intern
                        $lst_Flugzeit = 20;
                        $rfdauer = 20;
                    } else {
                        $lst_Flugzeit = 20;
                        $rfdauer = 22;
                    }
                } else { // fremddeffer (Bündnisse auch als Fremddeff :( )
                    $lst_Flugzeit = 17;
                    $rfdauer = 24;
                }
                mysql_free_result($SQL_Result1);
                mysql_free_result($SQL_Result2);
            }
            $_flugzeit = $_ankunft + ($lst_Flugzeit * $tsec);
            if (!isset($lst_Flotte)) $lst_Flotte = 1;
            if ($rfdauer=='24'&& $lst_Flugzeit== '20') $lst_Flugzeit= '17';
            $_ruckflug = $_flugzeit + ($rfdauer * $tsec);
            //echo "rfdauer = $rfdauer<br />";
        }

        if ($modus == "rueckflug" || $modus == "rueckflug_angreifen" ||$modus == "rueckflug_verteidigen")
        {
            $_ankunft  = 0;
            $_flugzeit = $lst_Flugzeit = 0;
            $_ruckflug = $_time + ($lst_ETA * $tsec);
        }

        if (!isset($tparser)) $tparser = 0;

//        echo "Modus: ".$modus." / AG: ".$txt_Angreifer_Galaxie." / AP: ".$txt_Angreifer_Planet." / VG: ".$txt_Verteidiger_Galaxie." / VP: ".$txt_Verteidiger_Planet." / ETA: ".$lst_ETA." / Flug: ".$lst_Flugzeit."<br>\n";
        if ($form_ok) {
            addgnuser($txt_Angreifer_Galaxie, $txt_Angreifer_Planet, $txt_Angreifer_Name);
            addgnuser($txt_Verteidiger_Galaxie, $txt_Verteidiger_Planet, $txt_Verteidiger_Name);

            $SQL_names = "modus, ticid, angreifer_galaxie, angreifer_planet, verteidiger_galaxie, verteidiger_planet, eta, flugzeit, flottennr, ankunft, flugzeit_ende, ruckflug_ende, erfasser, erfasst_am";
            $SQL_values = '"'.$Benutzer['ticid'].'", "'.$txt_Angreifer_Galaxie.'", "'.$txt_Angreifer_Planet.'", "'.$txt_Verteidiger_Galaxie.'", "'.$txt_Verteidiger_Planet.'", "'.$lst_ETA.'", "'.$lst_Flugzeit.'", "'.$lst_Flotte.'","'.$_ankunft.'", "'.$_flugzeit.'", "'.$_ruckflug.'", "'.$erfasser.'", "'.date("H").':'.date("i").' Uhr am '.date("d").'.'.date("m").'.'.date("Y").'"';

            if ($modus == 'angreifen') {
                $SQL_Result = tic_mysql_query('INSERT INTO `gn4flottenbewegungen` ('.$SQL_names.', tparser, save)    VALUES ("1", '.$SQL_values.', '.$tparser.', '.$txt_not_safe.');', $SQL_DBConn) or $error_code = 7;
            } elseif ($modus == 'verteidigen') {
                $SQL_Result = tic_mysql_query('INSERT INTO `gn4flottenbewegungen` ('.$SQL_names.', tparser)        VALUES ("2", '.$SQL_values.', '.$tparser.');', $SQL_DBConn) or $error_code = 7;
            } elseif ($modus == 'rueckflug_angreifen') {
                $SQL_Result = tic_mysql_query('INSERT INTO `gn4flottenbewegungen` ('.$SQL_names.')            VALUES ("3", '.$SQL_values.');', $SQL_DBConn) or $error_code = 7;
            } elseif ($modus == 'rueckflug_verteidigen') {
                $SQL_Result = tic_mysql_query('INSERT INTO `gn4flottenbewegungen` ('.$SQL_names.')            VALUES ("4", '.$SQL_values.');', $SQL_DBConn) or $error_code = 7;
            } elseif ($modus == 'rueckflug') {
                $SQL_Result = tic_mysql_query('INSERT INTO `gn4flottenbewegungen` ('.$SQL_names.')            VALUES ("0", '.$SQL_values.');', $SQL_DBConn) or $error_code = 7;
            }
        } else $error_code = 6;
    echo mysql_error();
?>
