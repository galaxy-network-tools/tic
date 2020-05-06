<?php

    $passwort = $_GET['passwort'];
    if (!isset($_GET['passwort'])) $passwort = '';
    if ($passwort != 'test') die('Incorrect password');

//passwort für den eggdrop muss mit dem im script übereinstimmen

    include('./functions.php');
    include('./accdata.php');
    include('./globvars.php');

    $irc_text['fett'] = '';
    $irc_text['unterstrichen'] = '';
    $irc_text['farbe'] = chr(3);
    $irc_farbe['weiss'] = '0';
    $irc_farbe['schwarz'] = '1';
    $irc_farbe['dunkelblau'] = '2';
    $irc_farbe['dunkelgruen'] = '3';
    $irc_farbe['rot'] = '4';
    $irc_farbe['braun'] = '5';
    $irc_farbe['lila'] = '6';
    $irc_farbe['orange'] = '7';
    $irc_farbe['gelb'] = '8';
    $irc_farbe['hellgruen'] = '9';
    $irc_farbe['tuerkise'] = '10';
    $irc_farbe['hellblau'] = '11';
    $irc_farbe['blau'] = '12';
    $irc_farbe['rosa'] = '13';
    $irc_farbe['dunkelgrau'] = '14';
    $irc_farbe['hellgrau'] = '15';

    include('./globalvars.php');

    $irc_listfarbe[0] = $irc_farbe['weiss'];
    $irc_listfarbe[1] = $irc_farbe['hellgrau'];

    $tick_abzug = intval(date('i') / $Ticks['lange']);
    $tick_abzug = date('i') - $tick_abzug * $Ticks['lange'];

    $SQL_DBConn = mysqli_connect($db_info['host'], $db_info['user'], $db_info['password']);
    mysqli_select_db($db_info['dbname'], $SQL_DBConn);

    include('./vars.php');

    if (!isset($_GET['modus'])) {
    $modus = 0;
    } else {
    $modus = $_GET['modus'];
    }
    $koord = $_GET['koord'];
    $istscanart = $_GET['istscanart'];

// Alle Atts anzeigen
    if ($modus == 0) {

        $SQL_Result1 = tic_mysql_query('SELECT galaxie, planet, allianz FROM `gn4accounts` ORDER BY galaxie, planet;', $SQL_DBConn);
        $SQL_Result2 = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` WHERE modus="1" && save="1" ORDER BY eta, verteidiger_galaxie, verteidiger_planet;', $SQL_DBConn);

        $SQL_Num1 = mysqli_num_rows($SQL_Result1) or $SQL_Num1=0;
        $SQL_Num2 = mysqli_num_rows($SQL_Result2) or $SQL_Num2=0;

        $text = '';
        $farbe = 0;

        for ($n = 0; $n < $SQL_Num1; $n++) {
            $ziel_galaxie = tic_mysql_result($SQL_Result1, $n, 'galaxie');
            $ziel_planet = tic_mysql_result($SQL_Result1, $n, 'planet');
            $ziel_name = gnuser($ziel_galaxie, $ziel_planet);
            $ziel_allianz = $AllianzTag[tic_mysql_result($SQL_Result1, $n, 'allianz')];
            //$eta = tic_mysql_result($SQL_Result2, $n, 'eta');
            $incomming_counter = 0;

            for ($x = 0; $x < $SQL_Num2; $x++) {
                if ($ziel_galaxie == tic_mysql_result($SQL_Result2, $x, 'verteidiger_galaxie') && $ziel_planet == tic_mysql_result($SQL_Result2, $x, 'verteidiger_planet') && tic_mysql_result($SQL_Result2, $x, 'eta') >= 18) {    // && tic_mysql_result($SQL_Result2, $x, 'eta') >= 18
                    $incomming_counter++;
                    $atter_eta = (tic_mysql_result($SQL_Result2, $x, 'eta') * $Ticks['lange']) - $tick_abzug;
                    $atter_eta_tic = preg_replace('/(\.)(.*)/', '', $atter_eta / $Ticks['lange']);

                    if ($incomming_counter == 1) {
                        $etas = $irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.tic_mysql_result($SQL_Result2, $x, 'angreifer_galaxie').':'.tic_mysql_result($SQL_Result2, $x, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].' ('.GetScans_irc($SQL_DBConn, tic_mysql_result($SQL_Result2, $x, 'angreifer_galaxie'), tic_mysql_result($SQL_Result2, $x, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.$atter_eta."min|".$atter_eta_tic."Ticks".$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].')';
                    } else {
                        $etas = $etas.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].','.$irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.tic_mysql_result($SQL_Result2, $x, 'angreifer_galaxie').':'.tic_mysql_result($SQL_Result2, $x, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].' ('.GetScans_irc($SQL_DBConn, tic_mysql_result($SQL_Result2, $x, 'angreifer_galaxie'), tic_mysql_result($SQL_Result2, $x, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.$atter_eta."min|".$atter_eta_tic."Ticks".$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].')';
                    }
                }
            }

            if ($incomming_counter > 0) {
                $text = $text.'° '.$irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.$ziel_galaxie.':'.$ziel_planet.' ['.$ziel_allianz.'] '.$ziel_name.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].' hat'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.$incomming_counter.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].' Incomming(s):'.$irc_text['farbe'].$etas;
                if ($farbe == 0) {
                    $farbe = 1;
                } else {
                    $farbe = 0;
                }
            }
        }

        if ($text == '') {
            $text = '° '.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' Es werden momentan keine Verteidiger benötigt.';
        } else {
            $text = $text.'° '.$irc_text['farbe'].$irc_farbe['rot'].','.$irc_listfarbe[$farbe].'!inc !deff';
        }

        // Einzelnen Att anzeigen
    } elseif($modus == 1) {

        if (!isset($koord))
            $text = '° Sie müssen eine Koordinate angeben!';
        else {
        $tmp_pos = strpos($koord, ':');
        if ($tmp_pos == 0)
                $text = '° Sie müssen eine gültige Koordinate angeben! ('.$koord.')';
            else {
                $tmp_galaxie = substr($koord, 0, $tmp_pos);
                $tmp_planet = substr($koord, $tmp_pos + 1);
                $SQL_Result = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` WHERE verteidiger_galaxie="'.$tmp_galaxie.'" AND verteidiger_planet="'.$tmp_planet.'" ORDER BY eta, angreifer_galaxie, angreifer_planet;', $SQL_DBConn);
                $incomming_counter = 0;
                $deff_counter = 0;
                $tmp_atter = '';
                $tmp_deffer = '';
                for ($n = 0; $n < mysqli_num_rows($SQL_Result); $n++) {
                    if (tic_mysql_result($SQL_Result, $n, 'modus') == 1) {
                        $incomming_counter++;
                        $atter_eta = (tic_mysql_result($SQL_Result, $n, 'eta') * $Ticks['lange']) - $tick_abzug;
                        $atter_eta_tic = preg_replace('/(\.)(.*)/', '', $atter_eta / $Ticks['lange']);

                        if ($incomming_counter == 1) {
                            $tmp_atter = $irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.tic_mysql_result($SQL_Result, $n, 'angreifer_galaxie').':'.tic_mysql_result($SQL_Result, $n, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' ('.GetScans_irc($SQL_DBConn, tic_mysql_result($SQL_Result, $n, 'angreifer_galaxie'), tic_mysql_result($SQL_Result, $n, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$atter_eta."min|".$atter_eta_tic."Ticks".$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].')';
                        } else {
                            $tmp_atter = $tmp_atter.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].', '.$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.tic_mysql_result($SQL_Result, $n, 'angreifer_galaxie').':'.tic_mysql_result($SQL_Result, $n, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' ('.GetScans_irc($SQL_DBConn, tic_mysql_result($SQL_Result, $n, 'angreifer_galaxie'), tic_mysql_result($SQL_Result, $n, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$atter_eta."min|".$atter_eta_tic."Ticks".$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].')';
                        }
                    } elseif (tic_mysql_result($SQL_Result, $n, 'modus') == 2) {
                        $deff_counter++;
                        $deffer_eta = (tic_mysql_result($SQL_Result, $n, 'eta') * $Ticks['lange']) - $tick_abzug;
                        $deffer_eta_tic = preg_replace('/(\.)(.*)/', '', $deffer_eta / $Ticks['lange']);
                        if ($deff_counter == 1) {
                            $tmp_deffer = $irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.tic_mysql_result($SQL_Result, $n, 'angreifer_galaxie').':'.tic_mysql_result($SQL_Result, $n, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' ('.GetScans_irc($SQL_DBConn, tic_mysql_result($SQL_Result, $n, 'angreifer_galaxie'), tic_mysql_result($SQL_Result, $n, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$deffer_eta."min|".$deffer_eta_tic."Ticks".$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].')';
                        } else {
                            $tmp_deffer = $tmp_deffer.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].', '.$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.tic_mysql_result($SQL_Result, $n, 'angreifer_galaxie').':'.tic_mysql_result($SQL_Result, $n, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' ('.GetScans_irc($SQL_DBConn, tic_mysql_result($SQL_Result, $n, 'angreifer_galaxie'), tic_mysql_result($SQL_Result, $n, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$deffer_eta."min|".$deffer_eta_tic."Ticks".$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].')';
                        }
                    }
                }
                $text = '°'.$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$tmp_galaxie.':'.$tmp_planet.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' hat'.$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$incomming_counter.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' Angreifer und'.$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$deff_counter.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' Verteidiger°'.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' Angreifer: '.$tmp_atter.'°'.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' Verteidiger: '.$tmp_deffer;
            }
        }

    }




// Scans vom Koods anzeigen


elseif($modus == 2) {


                $tmp_pos = strpos($koord, ':');

            if ($tmp_pos == 0)

                $text = '° Sie müssen eine gültige Koordinate angeben! ('.$koord.')';

                else { $tmp_galaxie = substr($koord, 0, $tmp_pos);
                $tmp_planet = substr($koord, $tmp_pos + 1);


        $sql='select * from `gn4scans` where rg='.$tmp_galaxie.' and rp='.$tmp_planet.' ';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
         $count =  mysqli_num_rows($SQL_Result);
    if ( $count == 0 ) {
        echo 'Sorry - Keine Scans vorhanden.';
        return;
    } else {


                    // all
        // sektor
        $pts = 0; $me  = 0; $ke  = 0; $sgen=0; $szeit='-'; $s=0; $d=0; $a=0;
        // unit init
        $ja   = 0; $bo   = 0; $fr   = 0; $ze   = 0; $kr   = 0; $sl   = 0; $tr   = 0; $ka   = 0; $ca   = 0; $ugen=0; $uzeit='-';
        // mili init
        $ja0  = 0; $bo0  = 0; $fr0  = 0; $ze0  = 0; $kr0  = 0; $sl0  = 0; $tr0  = 0; $ka0  = 0; $ca0  = 0; $mgen=0; $mzeit='-';
        $ja1  = 0; $bo1  = 0; $fr1  = 0; $ze1  = 0; $kr1  = 0; $sl1  = 0; $tr1  = 0; $ka1  = 0; $ca1  = 0;
        $ja2  = 0; $bo2  = 0; $fr2  = 0; $ze2  = 0; $kr2  = 0; $sl2  = 0; $tr2  = 0; $ka2  = 0; $ca2  = 0;
        // gscan
        $lo = 0; $ro = 0; $mr = 0; $sr = 0; $aj = 0; $ggen=0; $gzeit='-';
        $rscans = '';

        for ( $i=0; $i<$count; $i++ ) {


        if ( $i<($count-1) )
                $rpnext = tic_mysql_result($SQL_Result, $i+1, 'rp' );
            else
                $rpnext = 999;

            $type = tic_mysql_result($SQL_Result, $i, 'type' );
            $rp = tic_mysql_result($SQL_Result, $i, 'rp' );
            $rg = tic_mysql_result($SQL_Result, $i, 'rg' );
            $rname = gnuser($rg, $rp);
            $rscans .= sprintf( "%d ", $type );
//echo '<br>type='.$type.' - ';
            switch( $type ) {   // scan-type
                case 0: // sektor
                    $szeit  = tic_mysql_result($SQL_Result, $i, 'zeit' );
                    $sgen   = tic_mysql_result($SQL_Result, $i, 'gen' );
                    $pts    = tic_mysql_result($SQL_Result, $i, 'pts' );
                    $me     = tic_mysql_result($SQL_Result, $i, 'me' );
                    $ke     = tic_mysql_result($SQL_Result, $i, 'ke' );
                    $s      = tic_mysql_result($SQL_Result, $i, 's' );
                    $d      = tic_mysql_result($SQL_Result, $i, 'd' );
                    $a      = tic_mysql_result($SQL_Result, $i, 'a' );
                    break;
                case 1: // unit
                    $uzeit  = tic_mysql_result($SQL_Result, $i, 'zeit' );
                    $ugen   = tic_mysql_result($SQL_Result, $i, 'gen' );
                    $ja     = tic_mysql_result($SQL_Result, $i, 'sfj' );
                    $bo     = tic_mysql_result($SQL_Result, $i, 'sfb' );
                    $fr     = tic_mysql_result($SQL_Result, $i, 'sff' );
                    $ze     = tic_mysql_result($SQL_Result, $i, 'sfz' );
                    $kr     = tic_mysql_result($SQL_Result, $i, 'sfkr' );
                    $sl     = tic_mysql_result($SQL_Result, $i, 'sfsa' );
                    $tr     = tic_mysql_result($SQL_Result, $i, 'sft' );
                    $ka     = tic_mysql_result($SQL_Result, $i, 'sfka' );
                    $ca     = tic_mysql_result($SQL_Result, $i, 'sfsu' );
                    break;
                case 2: // mili-scan
                    $mzeit  = tic_mysql_result($SQL_Result, $i, 'zeit' );
                    $mgen   = tic_mysql_result($SQL_Result, $i, 'gen' );
                    $ja0    = tic_mysql_result($SQL_Result, $i, 'sf0j' );
                    $bo0    = tic_mysql_result($SQL_Result, $i, 'sf0b' );
                    $fr0    = tic_mysql_result($SQL_Result, $i, 'sf0f' );
                    $ze0    = tic_mysql_result($SQL_Result, $i, 'sf0z' );
                    $kr0    = tic_mysql_result($SQL_Result, $i, 'sf0kr' );
                    $sl0    = tic_mysql_result($SQL_Result, $i, 'sf0sa' );
                    $tr0    = tic_mysql_result($SQL_Result, $i, 'sf0t' );
                    $ka0    = tic_mysql_result($SQL_Result, $i, 'sf0ka' );
                    $ca0    = tic_mysql_result($SQL_Result, $i, 'sf0su' );
                    $ja1    = tic_mysql_result($SQL_Result, $i, 'sf1j' );
                    $bo1    = tic_mysql_result($SQL_Result, $i, 'sf1b' );
                    $fr1    = tic_mysql_result($SQL_Result, $i, 'sf1f' );
                    $ze1    = tic_mysql_result($SQL_Result, $i, 'sf1z' );
                    $kr1    = tic_mysql_result($SQL_Result, $i, 'sf1kr' );
                    $sl1    = tic_mysql_result($SQL_Result, $i, 'sf1sa' );
                    $tr1    = tic_mysql_result($SQL_Result, $i, 'sf1t' );
                    $ka1    = tic_mysql_result($SQL_Result, $i, 'sf1ka' );
                    $ca1    = tic_mysql_result($SQL_Result, $i, 'sf1su' );
                    $ja2    = tic_mysql_result($SQL_Result, $i, 'sf2j' );
                    $bo2    = tic_mysql_result($SQL_Result, $i, 'sf2b' );
                    $fr2    = tic_mysql_result($SQL_Result, $i, 'sf2f' );
                    $ze2    = tic_mysql_result($SQL_Result, $i, 'sf2z' );
                    $kr2    = tic_mysql_result($SQL_Result, $i, 'sf2kr' );
                    $sl2    = tic_mysql_result($SQL_Result, $i, 'sf2sa' );
                    $tr2    = tic_mysql_result($SQL_Result, $i, 'sf2t' );
                    $ka2    = tic_mysql_result($SQL_Result, $i, 'sf2ka' );
                    $ca2    = tic_mysql_result($SQL_Result, $i, 'sf2su' );

                    break;
                case 3: // geschütz
                    $gzeit  = tic_mysql_result($SQL_Result, $i, 'zeit' );
                    $ggen   = tic_mysql_result($SQL_Result, $i, 'gen' );
                    $lo     = tic_mysql_result($SQL_Result, $i, 'glo' );
                    $lr     = tic_mysql_result($SQL_Result, $i, 'glr' );
                    $mr     = tic_mysql_result($SQL_Result, $i, 'gmr' );
                    $sr     = tic_mysql_result($SQL_Result, $i, 'gsr' );
                    $aj     = tic_mysql_result($SQL_Result, $i, 'ga' );
                    break;
                default:
                    echo '????huh?!??? - Ohooooh';
                    break;
            }
                        // echo '('.$rpnext.' <>'. $rp.')';
        if ( $rpnext <> $rp ) {

                        if($istscanart == 'sek') {

                        $text =       '° Name: ('.$rg.':'.$rp.') - '.$rname.' ';
                        $text = $text.'° Punkte: '.number_format($pts, 0, ',', '.').'';
                        $text = $text.'° Metall Exxen: '.$me.'';
                        $text = $text.'° Kristall Exxen: '.$ke.'';
                        $text = $text.'° Schiffe: '.$s.'';
                        $text = $text.'° Verteidigung: '.$d.'';
                        $text = $text.'° Astros: '.$a.'';
                        $text = $text.'° Genauigkeit: '.$sgen.' %';
                        $text = $text.'° Datum: '.$szeit.'';

                        }


                        if($istscanart == 'einheit') {

                        $text =       '° Name: ('.$rg.':'.$rp.') - '.$rname.' ';
                        $text = $text.'° Jäger: '.$ja.'';
                        $text = $text.'° Bomber: '.$bo.'';
                        $text = $text.'° Fregatte: '.$fr.'';
                        $text = $text.'° Zerstörer: '.$ze.'';
                        $text = $text.'° Kreuzer: '.$kr.'';
                        $text = $text.'° Schlachtschiff: '.$sl.'';
                        $text = $text.'° Trägerschiff: '.$tr.'';
                        $text = $text.'° Kaperschiff: '.$ka.'';
                        $text = $text.'° Schutzschiff: '.$ca.'';
                        $text = $text.'° Genauigkeit: '.$ugen.' %';
                        $text = $text.'° Datum: '.$uzeit.'';


                        }

                        if($istscanart == 'gscan') {

                        $text =       '° Name: ('.$rg.':'.$rp.') - '.$rname.' ';
                        $text = $text.'° Leichtes Orbitalgeschütz: '.$lo.'';
                        $text = $text.'° Leichtes Raumgeschütz: '.$lr.'';
                        $text = $text.'° Mittleres Raumgeschütz: '.$mr.'';
                        $text = $text.'° Schweres Raumgeschütz: '.$sr.'';
                        $text = $text.'° Abfangjäger: '.$aj.'';
                        $text = $text.'° Genauigkeit: '.$ggen.' %';
                        $text = $text.'° Datum: '.$gzeit.'';



                        }

                        if($istscanart == 'mili') {

                        $text =       '° Name: ('.$rg.':'.$rp.') - '.$rname.' ';
                        $text = $text.'° Orbit: '.$ja0.' Leo | '.$bo0.' Aquilae | '.$fr0.' Fornax | '.$ze0.' Draco | '.$kr0.' Goron | '.$sl0.' Pentalin | '.$tr0.' Zenit | '.$ka0.' Cleptor | '.$ca0.' Cancri ';
                        $text = $text.'° Flotte1: '.$ja1.' Leo | '.$bo1.' Aquilae | '.$fr1.' Fornax | '.$ze1.' Draco | '.$kr1.' Goron | '.$sl1.' Pentalin | '.$tr1.' Zenit | '.$ka1.' Cleptor | '.$ca1.' Cancri ';
                        $text = $text.'° Flotte2: '.$ja2.' Leo | '.$bo2.' Aquilae | '.$fr2.' Fornax | '.$ze2.' Draco | '.$kr2.' Goron | '.$sl2.' Pentalin | '.$tr2.' Zenit | '.$ka2.' Cleptor | '.$ca2.' Cancri ';
                        $text = $text.'° Genauigkeit: '.$mgen.' %';
                        $text = $text.'° Datum: '.$mzeit.'';

                        }




        // all
            // sektor
            $pts = 0; $me  = 0; $ke  = 0; $sgen=0; $szeit='-'; $s=0; $d=0; $a=0;
            // unit init
            $ja   = 0; $bo   = 0; $fr   = 0; $ze   = 0; $kr   = 0; $sl   = 0; $tr   = 0; $ka   = 0; $ca   = 0; $ugen=0; $uzeit='-';
            // mili init
            $ja0  = 0; $bo0  = 0; $fr0  = 0; $ze0  = 0; $kr0  = 0; $sl0  = 0; $tr0  = 0; $ka0  = 0; $ca0  = 0; $mgen=0; $mzeit='-';
            $ja1  = 0; $bo1  = 0; $fr1  = 0; $ze1  = 0; $kr1  = 0; $sl1  = 0; $tr1  = 0; $ka1  = 0; $ca1  = 0;
            $ja2  = 0; $bo2  = 0; $fr2  = 0; $ze2  = 0; $kr2  = 0; $sl2  = 0; $tr2  = 0; $ka2  = 0; $ca2  = 0;
            // gscan
            $lo = 0; $ro = 0; $mr = 0; $sr = 0; $aj = 0; $ggen=0; $gzeit='-';
            $rscans = '';


        }



                                        }

            }





    }



}

    $text = $irc_text['fett'].$irc_text['farbe'].$irc_farbe['weiss'].','.$irc_farbe['dunkelblau'].'[ T.I.C. | Tactical Information Center      ]'.$irc_text['farbe'].$irc_text['fett'].$text;
    //$text = $text.'°'.$irc_text['farbe'].$irc_farbe['weiss'].','.$irc_farbe['dunkelgrau'].'[ http://'.$HTTP_HOST.'/stic     coding by ShadowoftheDragon + Pchen]'.$irc_text['farbe'];

    echo $text;

function GetScans_irc($SQL_DBConn, $galaxie, $planet) {
    global $irc_text, $irc_farbe, $irc_listfarbe, $farbe;

    $scan_type[0] = 'S';
    $scan_type[1] = 'E';
    $scan_type[2] = 'M';
    $scan_type[3] = 'G';
    $scan_type[4] = 'N';

    $datumx = date('d.m.Y');

    $SQL_Result = tic_mysql_query('SELECT * FROM `gn4scans` WHERE rg="'.$galaxie.'" AND rp="'.$planet.'" ORDER BY type;') or die(tic_mysqli_error(__FILE__,__LINE__));
    //echo "Scan: ".'SELECT * FROM `gn4scans` WHERE rg="'.$galaxie.'" AND rp="'.$planet.'" ORDER BY type;<br />';
    $SQL_Num = mysqli_num_rows($SQL_Result);
    if ($SQL_Num == 0) {
        return '[-]';
    } else {
        $tmp_result = '[';
        for ($n = 0; $n < $SQL_Num; $n++) {
            if ($datumx == substr(tic_mysql_result($SQL_Result, $n, 'zeit'),-10)) {
                $fc1 = "";
                $fc2 = "";
            } else {
                $fc1 = $irc_text['farbe']."04";
                $fc2 = $irc_text['farbe'].$irc_farbe['schwarz'].$irc_listfarbe[$farbe];
            }

            $tmp_result = $tmp_result.$fc1.$scan_type[tic_mysql_result($SQL_Result, $n, 'type')].$fc2;
        }
        $tmp_result = $tmp_result.']';
        //    echo "Scan=>$tmp_result<br />";
        return $tmp_result;
    }
    return null;
}
?>