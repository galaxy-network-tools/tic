<!-- START: inc_vergleich -->
<!-- Version: FlatMoon - Beta5 19.05.2005 11:15 Zurücksetzen der GNSimuclass -->
<?php
//$debug = 1;
$ka_eta = 999; // Wert hochsetzten für KürzesteAngreifer_ETA
$ftyp1[0] = 'ja';
$ftyp1[1] = 'bo';
$ftyp1[2] = 'fr';
$ftyp1[3] = 'ze';
$ftyp1[4] = 'kr';
$ftyp1[5] = 'sl';
$ftyp1[6] = 'tr';
$ftyp1[7] = 'kl';
$ftyp1[8] = 'su';
$ftyp1[9] = 'lo';
$ftyp1[10] = 'lr';
$ftyp1[11] = 'mr';
$ftyp1[12] = 'sr';
$ftyp1[13] = 'aj';

$ftyp2[0] = 'j';
$ftyp2[1] = 'b';
$ftyp2[2] = 'f';
$ftyp2[3] = 'z';
$ftyp2[4] = 'kr';
$ftyp2[5] = 'sa';
$ftyp2[6] = 't';
$ftyp2[7] = 'ka';
$ftyp2[8] = 'su';
$ftyp2[9] = 'glo';
$ftyp2[10] = 'glr';
$ftyp2[11] = 'gmr';
$ftyp2[12] = 'gsr';
$ftyp2[13] = 'ga';

$ftyp3[0] = 'J&auml;ger &quot;Leo&quot;';
$ftyp3[1] = 'Bomber &quot;Aquilae&quot;';
$ftyp3[2] = 'Fregatte &quot;Fornax&quot;';
$ftyp3[3] = 'Zerst&ouml;rer &quot;Draco&quot;';
$ftyp3[4] = 'Kreuzer &quot;Goron&quot;';
$ftyp3[5] = 'Schlachtschiff &quot;Pentalin&quot;';
$ftyp3[6] = 'Tr&auml;gerschiff &quot;Zenit&quot;';
$ftyp3[7] = 'Kaperschiff &quot;Kleptor&quot;';
$ftyp3[8] = 'Schutzschiff &quot;Cancri&quot;';
$ftyp3[9] = 'Leichtes Orbitalgesch&uuml;tz &quot;Rubium&quot;';
$ftyp3[10] = 'Leichtes Raumgesch&uuml;tz &quot;Pulsar&quot;';
$ftyp3[11] = 'Mittleres Raumgesch&uuml;tz &quot;Coon&quot;';
$ftyp3[12] = 'Schweres Raumgesch&uuml;tz &quot;Centurion&quot;';
$ftyp3[13] = 'Abfanj&auml;ger &quot;Horus&quot;';

$missing = "";
// summenwerte auf 0 setzen
// atter-summen
unset($asum);
// deffer-summen
unset($dsum);
$not_scanned = 0;
$SQL_angreifer = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` where verteidiger_galaxie=' . $_GET['xgala'] . ' and verteidiger_planet=' . $_GET['xplanet'] . ' and modus=1 ORDER BY eta;', $SQL_DBConn) or $error_code = 4;
$SQL_Num = mysqli_num_rows($SQL_angreifer);
$atter_galas = '';
for ($n = 0; $n < $SQL_Num; $n++) {
    $a_gala   = tic_mysql_result($SQL_angreifer, $n, 'angreifer_galaxie');
    $a_planet = tic_mysql_result($SQL_angreifer, $n, 'angreifer_planet');
    $a_fnr    = tic_mysql_result($SQL_angreifer, $n, 'flottennr');
    $atter_galas .= '<a href="./main.php?modul=showgalascans&displaymode=1&xgala=' . $a_gala . '&xplanet=' . $a_planet . '">' . $a_gala . ':' . $a_planet . '</a> ';
    $sql = 'select * from `gn4scans` where rg=' . $a_gala . ' and rp=' . $a_planet . ' and type=2'; // mili scan
    $SQL_result = tic_mysql_query($sql, $SQL_DBConn) or $error_code = 4;
    $SQL_Num2 = mysqli_num_rows($SQL_result);
    if ($SQL_Num2 > 0) {
        $mode = "angreifer";
        $flotte = $a_fnr;
        $eta = tic_mysql_result($SQL_angreifer, $n, 'eta');
        if ($eta < $ka_eta) $ka_eta = $eta;
        $flug[$mode][$a_gala . ':' . $a_planet . '.' . $flotte]['eta'] = $eta;
        $fzeit = tic_mysql_result($SQL_angreifer, $n, 'flugzeit');
        if (($eta + $fzeit) > $ka) $ka = ($eta + $fzeit);
        $flug[$mode][$a_gala . ':' . $a_planet . '.' . $flotte]['fzeit'] = $fzeit;

        while ($line = mysqli_fetch_array($SQL_result, MYSQL_ASSOC)) {
            for ($cnt = 0; $cnt <= 8; $cnt++) {
                $flug[$mode][$a_gala . ':' . $a_planet . '.' . $flotte][$ftyp1[$cnt]] = $line['sf' . $a_fnr . $ftyp2[$cnt]];
                $asum[$cnt] += $flug[$mode][$a_gala . ':' . $a_planet . '.' . $flotte][$ftyp1[$cnt]];
            }
            // DEBUG ausgabe
            $f_output .= "Angreifer " . $a_gala . ":" . $a_planet . '.' . $flotte . " erreicht das Ziel in " . $eta . " Ticks und verbleibt " . $fzeit . " von max 5 Ticks.<br>\n";
            $fl_output .= "Flotte " . $a_gala . ":" . $a_planet . '.' . $flotte . ": ";
            for ($cnt = 0; $cnt <= 8; $cnt++) {
                $fl_output .= $ftyp1[$cnt] . " = " . $line['sf' . $a_fnr . $ftyp2[$cnt]] . " ";
            }
            $fl_output .= "<br>\n";
            // DEBUG ende
        }
    } else {
        $not_scanned++;
        $missing .= "Scan von Angreifer " . $a_gala . ":" . $a_planet . " fehlt.<br>\n";
    }
}

$SQL_verteidiger = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` where verteidiger_galaxie=' . $_GET['xgala'] . ' and verteidiger_planet=' . $_GET['xplanet'] . ' and modus=2 ORDER BY eta;', $SQL_DBConn) or $error_code = 4;
$SQL_Num = mysqli_num_rows($SQL_verteidiger);
$deffer_galas = '<a href="./main.php?modul=showgalascans&displaymode=1&xgala=' . $_GET['xgala'] . '&xplanet=' . $_GET['xplanet'] . '">' . $_GET['xgala'] . ':' . $_GET['xplanet'] . '</a> ';
for ($n = 0; $n < $SQL_Num; $n++) {
    $d_gala   = tic_mysql_result($SQL_verteidiger, $n, 'angreifer_galaxie');
    $d_planet = tic_mysql_result($SQL_verteidiger, $n, 'angreifer_planet');
    $d_fnr    = tic_mysql_result($SQL_verteidiger, $n, 'flottennr');
    $deffer_galas .= '<a href="./main.php?modul=showgalascans&displaymode=1&xgala=' . $d_gala . '&xplanet=' . $d_planet . '">' . $d_gala . ':' . $d_planet . '</a> ';
    $sql = 'select * from `gn4scans` where rg=' . $d_gala . ' and rp=' . $d_planet . ' and type=2'; // mili scan
    $SQL_result = tic_mysql_query($sql, $SQL_DBConn) or $error_code = 4;
    $SQL_Num2 = mysqli_num_rows($SQL_result);
    if ($SQL_Num2 > 0 and $d_fnr > 0) {
        $mode = "verteidiger";
        $flotte = $d_fnr;
        $eta = tic_mysql_result($SQL_verteidiger, $n, 'eta');
        $flug[$mode][$d_gala . ':' . $d_planet . '.' . $flotte]['eta'] = $eta;
        $fzeit = tic_mysql_result($SQL_verteidiger, $n, 'flugzeit');
        if (($eta + $fzeit) > $kv) $kv = ($eta + $fzeit);
        $flug[$mode][$d_gala . ':' . $d_planet . '.' . $flotte]['fzeit'] = $fzeit;

        while ($line = mysqli_fetch_array($SQL_result, MYSQL_ASSOC)) {
            for ($cnt = 0; $cnt <= 8; $cnt++) {
                $flug[$mode][$d_gala . ':' . $d_planet . '.' . $flotte][$ftyp1[$cnt]] = $line['sf' . $d_fnr . $ftyp2[$cnt]];
                $dsum[$cnt] += $flug[$mode][$d_gala . ':' . $d_planet . '.' . $flotte][$ftyp1[$cnt]];
            }
            // DEBUG ausgabe
            $f_output .= "Verteidiger " . $d_gala . ":" . $d_planet . '.' . $flotte . " erreicht das Ziel in " . $eta . " Tick's und verbleibt " . $fzeit . " von max 20 Tick's.<br>\n";
            $fl_output .= "Flotte " . $d_gala . ":" . $d_planet . '.' . $flotte . ": ";
            for ($cnt = 0; $cnt <= 8; $cnt++) {
                $fl_output .= $ftyp1[$cnt] . " = " . $line['sf' . $d_fnr . $ftyp2[$cnt]] . " ";
            }
            $fl_output .= "<br>\n";
            // DEBUG ende
        }
    } else {
        $not_scanned++;
        $missing .= "Scan von Verteidiger " . $d_gala . ":" . $d_planet . " fehlt.<br>\n";
    }
}

// füge orbit des deffers hinzu
$sql = 'select * from `gn4scans` where rg=' . $_GET['xgala'] . ' and rp=' . $_GET['xplanet'] . ' and type=2'; // mili scan
$SQL_result = tic_mysql_query($sql, $SQL_DBConn) or $error_code = 4;
$SQL_Num2 = mysqli_num_rows($SQL_result);
if ($SQL_Num2 > 0) {
    $mode = "eigene";
    $flotte = 0;
    while ($line = mysqli_fetch_array($SQL_result, MYSQL_ASSOC)) {
        for ($cnt = 0; $cnt <= 8; $cnt++) {
            //				$flug[$mode][$flotte][$ftyp1[$cnt]] = tic_mysql_result( $SQL_result, 0, 'sf0'.$ftyp2[$cnt]);
            $flug[$mode][$flotte][$ftyp1[$cnt]] = $line['sf0' . $ftyp2[$cnt]];
            $dsum[$cnt] += $flug[$mode][$flotte][$ftyp1[$cnt]];
        }
    }
} else {
    $not_scanned++;
    $missing .= "Scan des Angegriffenen " . $_GET['xgala'] . ":" . $_GET['xplanet'] . " fehlt.<br>\n";
}

// füge NICHT-fliegenden flotte(n) des deffers hinzu
$SQL_rumflieg = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` where angreifer_galaxie=' . $_GET['xgala'] . ' and angreifer_planet=' . $_GET['xplanet'] . ' ORDER BY eta;', $SQL_DBConn) or $error_code = 4;
$SQL_Num = mysqli_num_rows($SQL_rumflieg);
for ($n = 0; $n < 2 - $SQL_Num; $n++) {
    if ($n < $SQL_Num)
        $fnr = tic_mysql_result($SQL_rumflieg, $n, 'flottennr');
    else
        $fnr = $n + 1;

    if ($fnr == '')
        $fnr = $n + 1;

    $sql = 'select * from `gn4scans` where rg=' . $_GET['xgala'] . ' and rp=' . $_GET['xplanet'] . ' and type=2'; // mili scan
    $SQL_result = tic_mysql_query($sql, $SQL_DBConn) or $error_code = 4;
    $SQL_Num2 = mysqli_num_rows($SQL_result);
    if ($SQL_Num2 > 0) {
        if ($fnr == 2) {
            $mode = "eigene";
            $flotte = 1;
            for ($cnt = 0; $cnt <= 8; $cnt++) {
                $flug[$mode][$flotte][$ftyp1[$cnt]] = tic_mysql_result($SQL_result, 0, 'sf1' . $ftyp2[$cnt]);
                $dsum[$cnt] += $flug[$mode][$flotte][$ftyp1[$cnt]];
            }
        } else {
            $mode = "eigene";
            $flotte = 2;
            for ($cnt = 0; $cnt <= 8; $cnt++) {
                $flug[$mode][$flotte][$ftyp1[$cnt]] = tic_mysql_result($SQL_result, 0, 'sf2' . $ftyp2[$cnt]);
                $dsum[$cnt] += $flug[$mode][$flotte][$ftyp1[$cnt]];
            }
        }
    }
}

// geschütze
$sql = 'select * from `gn4scans` where rg=' . $_GET['xgala'] . ' and rp=' . $_GET['xplanet'] . ' and type=3'; // geschütz scan
$SQL_result = tic_mysql_query($sql, $SQL_DBConn) or $error_code = 4;
$SQL_Num2 = mysqli_num_rows($SQL_result);
if ($SQL_Num2 > 0) {
    $mode = "eigene";
    $flotte = 0;
    for ($cnt = 9; $cnt <= 13; $cnt++) {
        $flug[$mode][$flotte][$ftyp1[$cnt]] = tic_mysql_result($SQL_result, 0, $ftyp2[$cnt]);
        $dsum[$cnt] += $flug[$mode][$flotte][$ftyp1[$cnt]];
    }
} else {
    $not_scanned++;
    $missing .= "Geschütze des Angegriffenen " . $_GET['xgala'] . ":" . $_GET['xplanet'] . " fehlen.<br>\n";
}

if ($atter_galas == '') $atter_galas = 'keine';
?>
<center>
    <p>
        <font size="4">Gegen&uuml;berstellung Angreifer/Verteidiger</font>
    </p>
    <table border="0" cellspacing="0" cellpadding="0" width="80%" bgcolor="#999999">
        <tr>
            <td>
                <table border="0" cellspacing="3" cellpadding="0" width="100%">
                    <tr bgcolor="#333333">
                        <td width="50%"><b>
                                <font size="-1" color="#FFFFFF">Verteidiger</font>
                            </b></td>
                        <td><b>
                                <font size="-1" color="#FFFFFF">Angreifer</font>
                            </b></td>
                    </tr>
                    <tr bgcolor="#eeeeee">
                        <td width="50%">
                            <font size="-1"><?php echo $deffer_galas; ?></font>
                        </td>
                        <td>
                            <font size="-1"><?php echo $atter_galas; ?></font>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <?php
    echo "	<input type=\"button\" name=\"Verweis2\" value=\"Zur Datenerfassung\" onClick=\"self.location.href='./main.php?modul=scans&txtScanGalaxie=" . $_GET['xgala'] . "&txtScanPlanet=" . $_GET['xplanet'] . "'\">\n";
    if ($not_scanned > 0) {
        echo "	<br>\n";
        echo "	<font size=\"-1\" color=\"#800000\">\nEs wurden " . $not_scanned . " Scan(s) nicht gefunden. Der Vergleich ist nicht korrekt.<br>\n" . $missing . "	</font>\n";
    }
    // DEBUG ausgabe
    if ($debug == 1) {
        echo "<br>\n -=DEBUG Ausgabe=-";
        echo "<br>\n -=Flottenbewegungen=-";
        echo "<br>\n" . $f_output;
        echo "<br>\n -=Die Flottenzusammensetzung=-";
        echo "<br>\n" . $fl_output;
    } else {
        // Ausgabe nur im Quellcode
        echo "<br>\n" . '<a target="_self" href="main.php?modul=vergleich&xgala=' . $_GET['xgala'] . '&xplanet=' . $_GET['xplanet'] . '&debug=1">[zum DEBUG Modus]</a>' . "<br>\n";
        echo "<!-- ### DEBUG ausgabe, wenn debug nicht 1 ist ###\n";
        echo $f_output . "\n";
        echo "Flotten:\n" . $fl_output . "\n";
        echo "-->\n";
    }
    // DEBUG ende
    ?>
    <br><br>
    <table border="0" cellspacing="0" cellpadding="0" width="80%" bgcolor="#333333">
        <tr>
            <td><b>
                    <font color="#FFFFFF" size="-1">&nbsp;Gesamterfassung aller Flotten</font>
                </b></td>
        </tr>
        <tr>
            <td>
                <table border="0" cellspacing="3" cellpadding="0" width="100%" bgcolor="#999999">
                    <tr bgcolor="#333333">
                        <td width="50%"><b>
                                <font color="#FFFFFF" size="-1">Verteidiger</font>
                            </b></td>
                        <td><b>
                                <font color="#FFFFFF" size="-1">Angreifer</font>
                            </b></td>
                    </tr>
                    <tr>
                        <td valign="top" width="50%">
                            <table border="0" cellspacing="3" cellpadding="0" width="100%">
                                <? for ($cnt = 0; $cnt <= 13; $cnt++) { ?>
                                    <tr bgcolor="#eeeeee">
                                        <td width="82%">
                                            <font size="-1"><? echo $ftyp3[$cnt] ?></font>
                                        </td>
                                        <td width="18%" align="right">
                                            <font size="-1"><?php echo $dsum[$cnt]; ?></font>
                                        </td>
                                    </tr>
                                <? } ?>
                            </table>
                        </td>
                        <td valign="top" width="50%">
                            <table border="0" cellspacing="3" cellpadding="0" width="100%">
                                <? for ($cnt = 0; $cnt <= 8; $cnt++) { ?>
                                    <tr bgcolor="#eeeeee">
                                        <td width="82%">
                                            <font size="-1"><? echo $ftyp3[$cnt] ?></font>
                                        </td>
                                        <td width="18%" align="right">
                                            <font size="-1"><?php echo $asum[$cnt]; ?></font>
                                        </td>
                                    </tr>
                                <? } ?>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>

    <?php    // füge orbit des deffers hinzu
    $sql = 'select * from `gn4scans` where rg=' . $_GET['xgala'] . ' and rp=' . $_GET['xplanet'] . ' and type=0'; // sektor scan
    $SQL_result = tic_mysql_query($sql, $SQL_DBConn) or $error_code = 4;
    $SQL_Num2 = mysqli_num_rows($SQL_result);
    if ($SQL_Num2 > 0) {
        $me = tic_mysql_result($SQL_result, 0, 'me');
        $ke = tic_mysql_result($SQL_result, 0, 'ke');
    }
    if ($flug['angreifer'] != '') {
        while (list($key, $val) = each($flug['angreifer'])) {
            $tick[($val['eta'] - 2)]['vortick2'] .= ' ' . $key;
            $tick[($val['eta'] - 1)]['vortick1'] .= ' ' . $key;
            if ($val['fzeit'] > 5) $val['fzeit'] = 5;
            for ($i = $val['eta']; $i < ($val['eta'] + $val['fzeit']); $i++) {
                $kampf['atter'][$i] .= ' ' . $key;
            }
        }
    } //end if != ''
    if ($flug['verteidiger'] != '') {
        while (list($key, $val) = each($flug['verteidiger'])) {
            if ($val['fzeit'] > 20) $val['fzeit'] = 20;
            for ($i = $val['eta']; $i < ($val['eta'] + $val['fzeit']); $i++) {
                $kampf['deffer'][$i] .= ' ' . $key;
            }
        }
    } // end if != ''
    if ($kampf['atter'] != '') {
        while (list($key, $val) = each($kampf['atter'])) {
            $tick[$key]['att'] = $val;
            $tick[$key]['deff'] = $kampf['deffer'][$key];
        }
    } // end if != ''

    include_once("GNSimuclass.php");
    $gnsimu = new GNSimu();
    $gnsimu->mexen = $me;
    $gnsimu->kexen = $ke;

    // Flotte zusammenzählen
    if ($tick != '') {
        ksort($tick);
        while (list($key, $val) = each($tick)) {  // ca 256zeile tiefer das ende der schleife

            // VORTICK noch 2 zuvor
            if ($val['vortick2'] != '') {
                unset($v);
                unset($a);
                $fa = explode(" ", $val['vortick2']);
                $fa2 = $fa;

                while (list($key2, $val2) = each($fa)) {
                    for ($cnt = 0; $cnt <= 8; $cnt++) {
                        $a[$cnt] += $flug['angreifer'][$val2][$ftyp1[$cnt]];
                    }
                }

                for ($k = 0; $k <= 2; $k++) {
                    for ($cnt = 0; $cnt <= 13; $cnt++) {
                        $v[$cnt] += $flug['eigene'][$k][$ftyp1[$cnt]];
                    }
                }

                for ($i = 0; $i < 14; $i++) {
                    $gnsimu->attaking[$i] = ($a[$i] ? $a[$i] : 0);
                    $gnsimu->deffending[$i] = ($v[$i] ? $v[$i] : 0);
                }
                echo '<table border="0" cellspacing="5" cellpadding="0" bgcolor="#999999"><tr><td>1ter Orbitaler Vortick<br>';
                echo "Tick: " . $key . " >> <br>Opfer: " . $_GET['xgala'] . ':' . $_GET['xplanet'] . " <br><font color=red>vs.</font> <br>Angreifer: " . $val['vortick2'] . "<br>\n";
                $gnsimu->ComputeTwoTickBefore();
                $gnsimu->PrintStatesGun();
                echo '</td></tr></table><br>';

                for ($i = 0; $i < 9; $i++) {
                    $averlust[$i] = $gnsimu->Oldatt[$i] - $gnsimu->attaking[$i];
                }

                while (list($key2, $val2) = each($fa2)) {
                    // gesamtverlust * eigene / gesamtflotte
                    for ($cnt = 0; $cnt <= 8; $cnt++) {
                        if ($averlust[$cnt] != 0) $flug['angreifer'][$val2][$ftyp1[$cnt]] = round($flug['angreifer'][$val2][$ftyp1[$cnt]] - ($averlust[$cnt] * $flug['angreifer'][$val2][$ftyp1[$cnt]] / $gnsimu->Oldatt[$cnt]));
                    }
                }
            } // ende if($val['vortick2'] != '') {

            // VORTICK noch 1 zuvor
            if ($val['vortick1'] != '') {
                unset($v);
                unset($a);
                $fa = explode(" ", $val['vortick1']);
                $fa2 = $fa;

                while (list($key2, $val2) = each($fa)) {
                    for ($cnt = 0; $cnt <= 8; $cnt++) {
                        $a[$cnt] += $flug['angreifer'][$val2][$ftyp1[$cnt]];
                    }
                }

                for ($k = 0; $k <= 2; $k++) {
                    for ($cnt = 0; $cnt <= 13; $cnt++) {
                        $v[$cnt] += $flug['eigene'][$k][$ftyp1[$cnt]];
                    }
                }

                for ($i = 0; $i < 14; $i++) {
                    $gnsimu->attaking[$i] = ($a[$i] ? $a[$i] : 0);
                    $gnsimu->deffending[$i] = ($v[$i] ? $v[$i] : 0);
                }
                echo '<table border="0" cellspacing="5" cellpadding="0" bgcolor="#999999"><tr><td>2ter Orbitaler Vortick<br>';
                echo "Tick: " . $key . " >> <br>Opfer: " . $_GET['xgala'] . ':' . $_GET['xplanet'] . " <br><font color=red>vs.</font> <br>Angreifer: " . $val['vortick1'] . "<br>\n";
                $gnsimu->ComputeOneTickBefore();
                $gnsimu->PrintStatesGun();
                echo '</td></tr></table><br>';

                for ($i = 0; $i < 9; $i++) {
                    $averlust[$i] = $gnsimu->Oldatt[$i] - $gnsimu->attaking[$i];
                }

                while (list($key2, $val2) = each($fa2)) {
                    // gesamtverlust * eigene / gesamtflotte
                    for ($cnt = 0; $cnt <= 8; $cnt++) {
                        if ($averlust[$cnt] != 0) $flug['angreifer'][$val2][$ftyp1[$cnt]] = round($flug['angreifer'][$val2][$ftyp1[$cnt]] - ($averlust[$cnt] * $flug['angreifer'][$val2][$ftyp1[$cnt]] / $gnsimu->Oldatt[$cnt]));
                    }
                }
            } // ende if($val['vortick1'] != '') {

            // Sachbearbeitung der Kampfhandlungen schiff vs Schiff
            if ($val['att'] != '') {
                // Rücksetzten von variablen
                unset($v);
                unset($a);
                $fa = explode(" ", $val['att']);
                $fa2 = $fa;
                $fv = explode(" ", $val['deff']);
                $fv2 = $fv;
                while (list($key2, $val2) = each($fa)) {
                    for ($cnt = 0; $cnt <= 8; $cnt++) {
                        $a[$cnt] += $flug['angreifer'][$val2][$ftyp1[$cnt]];
                    }
                }
                while (list($key2, $val2) = each($fv)) {
                    for ($cnt = 0; $cnt <= 8; $cnt++) {
                        $v[$cnt] += $flug['verteidiger'][$val2][$ftyp1[$cnt]];
                    }
                }
                for ($k = 0; $k <= 2; $k++) {
                    for ($cnt = 0; $cnt <= 13; $cnt++) {
                        $v[$cnt] += $flug['eigene'][$k][$ftyp1[$cnt]];
                    }
                }

                for ($i = 0; $i < 14; $i++) {
                    $gnsimu->attaking[$i] = ($a[$i] ? $a[$i] : 0);
                    $gnsimu->deffending[$i] = ($v[$i] ? $v[$i] : 0);
                }

                //berechnung des gewählten tickes mit ausgabe
    ?>
                <table border="0" cellspacing="5" cellpadding="0" bgcolor="#999999">
                    <tr>
                        <td>
                            <?php
                            if ($val['deff'] == '') {
                                $tmp = '';
                            } else {
                                $tmp = " <br>Verteidiger:" . $val['deff'];
                            }
                            echo "Tick: " . $key . " >> <br>Opfer: " . $_GET['xgala'] . ':' . $_GET['xplanet'] . $tmp . " <br><font color=red>vs.</font><br> Angreifer: " . $val['att'] . "<br>\n";
                            $gnsimu->Compute($i == $ticks - 1);
                            $gnsimu->PrintStates();
                            ?>
                        </td>
                    </tr>
                </table><br>
    <?php
                for ($i = 0; $i < 9; $i++) {
                    $averlust[$i] = $gnsimu->Oldatt[$i] - $gnsimu->attaking[$i];
                }
                for ($i = 0; $i < 14; $i++) {
                    $vverlust[$i] = $gnsimu->Olddeff[$i] - $gnsimu->deffending[$i];
                }

                while (list($key2, $val2) = each($fa2)) {
                    // gesamtverlust * eigene / gesamtflotte
                    for ($cnt = 0; $cnt <= 8; $cnt++) {
                        if ($averlust[$cnt] != 0) $flug['angreifer'][$val2][$ftyp1[$cnt]] = round($flug['angreifer'][$val2][$ftyp1[$cnt]] - ($averlust[$cnt] * $flug['angreifer'][$val2][$ftyp1[$cnt]] / $gnsimu->Oldatt[$cnt]));
                    }
                }

                while (list($key2, $val2) = each($fv2)) {
                    // gesamtverlust * eigene / gesamtflotte
                    for ($cnt = 0; $cnt <= 8; $cnt++) {
                        if ($vverlust[$cnt] != 0) $flug['verteidiger'][$val2][$ftyp1[$cnt]] = round($flug['verteidiger'][$val2][$ftyp1[$cnt]] - ($vverlust[$cnt] * $flug['verteidiger'][$val2][$ftyp1[$cnt]] / $gnsimu->Olddeff[$cnt]));
                    }
                }

                for ($k = 0; $k <= 2; $k++) {
                    for ($cnt = 0; $cnt <= 13; $cnt++) {
                        if ($vverlust[$cnt] != 0) $flug['eigene'][$k][$ftyp1[$cnt]] = round($flug['eigene'][$k][$ftyp1[$cnt]] - ($vverlust[$cnt] * $flug['eigene'][$k][$ftyp1[$cnt]] / $gnsimu->Olddeff[$cnt]));
                    }
                }
            } // Ende der $val['att'] != '' IFschleife
        } // ende der while-$tick schleife
    } // end if != ''

    if ($tick != '')    $gnsimu->PrintOverView();
    // include change #### "gnsim.php" ####
    echo "\n";
    ?>
    <!-- Überreste der einzelnen Flotten
<?php
reset($flug['angreifer']);
while (list($key, $val) = each($flug['angreifer'])) {
    if (trim($key) != "") {
        echo "Angreifer " . $key . ": ";
        for ($cnt = 0; $cnt <= 8; $cnt++) {
            echo $ftyp1[$cnt] . " = " . $val[$ftyp1[$cnt]] . " ";
        }
        echo "\n";
    }
}
reset($flug['verteidiger']);
while (list($key, $val) = each($flug['verteidiger'])) {
    if (trim($key) != "") {
        echo "Verteidiger " . $key . ": ";
        for ($cnt = 0; $cnt <= 8; $cnt++) {
            echo $ftyp1[$cnt] . " = " . $val[$ftyp1[$cnt]] . " ";
        }
        echo "\n";
    }
}
reset($flug['eigene']);
while (list($key, $val) = each($flug['eigene'])) {
    if (trim($key) != "") {
        echo "Eigene " . $key . ": ";
        for ($cnt = 0; $cnt <= 13; $cnt++) {
            echo $ftyp1[$cnt] . " = " . $val[$ftyp1[$cnt]] . " ";
        }
        echo "\n";
    }
}
print_r($tick);
print_r($kampf);

?>
-->
</center>
<!-- ENDE: inc_vergleich -->
