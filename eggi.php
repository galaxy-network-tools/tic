<?php
    include('./accdata.php');
    include('./functions.php');

    $SQL_DBConn = mysql_connect($db_info['host'], $db_info['user'], $db_info['password']);
    mysql_select_db($db_info['dbname'], $SQL_DBConn);


    if (!isset($_GET['passwort'])) $_GET['passwort'] = '';
    $SQL_Result0 = tic_mysql_query('SELECT ticid FROM `gn4vars` WHERE name="botpw" AND value="'.$_GET['passwort'].'";', $SQL_DBConn);

    if (mysql_num_rows($SQL_Result0) != 1) die('Incorrect password');


    include('./globalvars.php');
    include('./vars.php');

    $irc_text['fett'] = '';
    $irc_text['unterstrichen'] = '';
    $irc_text['farbe'] = '';
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

    $irc_listfarbe[0] = $irc_farbe['weiss'];
    $irc_listfarbe[1] = $irc_farbe['hellgrau'];

    include('./globalvars.php');

    $tick_abzug = intval(date('i') / 15);
    $tick_abzug = date('i') - $tick_abzug * 15;


    if (!isset($_GET['modus'])) $_GET['modus'] = 0;

// Alle Atts anzeigen
    if ($_GET['modus'] == 0) {

        $SQL_Result1 = tic_mysql_query('SELECT galaxie, planet, name, allianz FROM `gn4accounts` ORDER BY galaxie, planet;', $SQL_DBConn);
        $SQL_Result2 = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` WHERE modus="1" && save="1"  ORDER BY eta, verteidiger_galaxie, verteidiger_planet;', $SQL_DBConn);

        $SQL_Num1 = mysql_num_rows($SQL_Result1);
        $SQL_Num2 = mysql_num_rows($SQL_Result2);

        $text = '';
        $farbe = 0;

        for ($n = 0; $n < $SQL_Num1; $n++) {
            $ziel_galaxie = mysql_result($SQL_Result1, $n, 'galaxie');
            $ziel_planet = mysql_result($SQL_Result1, $n, 'planet');
            $ziel_name = mysql_result($SQL_Result1, $n, 'name');
            $ziel_allianz = $AllianzTag[mysql_result($SQL_Result1, $n, 'allianz')];
            $incomming_counter = 0;

            for ($x = 0; $x < $SQL_Num2; $x++) {
                 $time=mysql_result($SQL_Result2, $x, 'ankunft');
                if ($ziel_galaxie == mysql_result($SQL_Result2, $x, 'verteidiger_galaxie') && $ziel_planet == mysql_result($SQL_Result2, $x, 'verteidiger_planet')) {    // && mysql_result($SQL_Result2, $x, 'eta') >= 18
                    $incomming_counter++;
                    $atter_eta = (eta($time) * 15 - $tick_abzug);
                    if ($incomming_counter == 1) {
                        $etas = $irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.mysql_result($SQL_Result2, $x, 'angreifer_galaxie').':'.mysql_result($SQL_Result2, $x, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].' ('.GetScans($SQL_DBConn, mysql_result($SQL_Result2, $x, 'angreifer_galaxie'), mysql_result($SQL_Result2, $x, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.$atter_eta.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].')';
                    } else {
                        $etas = $etas.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].','.$irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.mysql_result($SQL_Result2, $x, 'angreifer_galaxie').':'.mysql_result($SQL_Result2, $x, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].' ('.GetScans($SQL_DBConn, mysql_result($SQL_Result2, $x, 'angreifer_galaxie'), mysql_result($SQL_Result2, $x, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.$atter_eta.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].')';
                    }
                }
            }

            if ($incomming_counter > 0) {
                $text = $text."\n".$irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.$ziel_galaxie.':'.$ziel_planet.' ['.$ziel_allianz.'] '.$ziel_name.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].' hat'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_listfarbe[$farbe].' '.$incomming_counter.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_listfarbe[$farbe].' Incomming(s):'.$irc_text['farbe'].$etas;
                if ($farbe == 0)
                    $farbe = 1;
                else
                    $farbe = 0;
            }
        }

        if ($text == '') $text = "\n".$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' Es werden momentan keine Verteidiger benötigt.';

// Einzelnen Att anzeigen
    } elseif($_GET['modus'] == 1) {

        if (!isset($_GET['koord']))
            $text = ' Sie müssen eine Koordinate angeben!';
        else {
            $tmp_pos = strpos($_GET['koord'], ':');
            if ($tmp_pos == 0)
                $text = ' Sie müssen eine gültige Koordinate angeben! ('.$_GET['koord'].')';
            else {
                $tmp_galaxie = substr($_GET['koord'], 0, $tmp_pos);
                $tmp_planet = substr($_GET['koord'], $tmp_pos + 1);
                $SQL_Result = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` WHERE verteidiger_galaxie="'.$tmp_galaxie.'" AND verteidiger_planet="'.$tmp_planet.'" ORDER BY eta, angreifer_galaxie, angreifer_planet;', $SQL_DBConn);
                $incomming_counter = 0;
                $deff_counter = 0;
                $tmp_atter = '';
                $tmp_deffer = '';
                for ($n = 0; $n < mysql_num_rows($SQL_Result); $n++) {
                    $time=mysql_result($SQL_Result, $n, 'ankunft');
                    if (mysql_result($SQL_Result, $n, 'modus') == 1) {
                        $incomming_counter++;
                        $atter_eta = (eta($time) * 15 - $tick_abzug);
                        if ($incomming_counter == 1) {
                            $tmp_atter = $irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.mysql_result($SQL_Result, $n, 'angreifer_galaxie').':'.mysql_result($SQL_Result, $n, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' ('.GetScans($SQL_DBConn, mysql_result($SQL_Result, $n, 'angreifer_galaxie'), mysql_result($SQL_Result, $n, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$atter_eta.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].')';
                        } else {
                            $tmp_atter = $tmp_atter." 00,01 ".$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.mysql_result($SQL_Result, $n, 'angreifer_galaxie').':'.mysql_result($SQL_Result, $n, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' ('.GetScans($SQL_DBConn, mysql_result($SQL_Result, $n, 'angreifer_galaxie'), mysql_result($SQL_Result, $n, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$atter_eta.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].')';
                        }
                    } elseif (mysql_result($SQL_Result, $n, 'modus') == 2) {
                        $deff_counter++;
                        $deffer_eta = (eta($time) * 15 - $tick_abzug);
                        if ($deff_counter == 1) {
                            $tmp_deffer = $irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.mysql_result($SQL_Result, $n, 'angreifer_galaxie').':'.mysql_result($SQL_Result, $n, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' ('.GetScans($SQL_DBConn, mysql_result($SQL_Result, $n, 'angreifer_galaxie'), mysql_result($SQL_Result, $n, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$deffer_eta.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].')';
                        } else {
                            $tmp_deffer = $tmp_deffer." 00,01 ".$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.mysql_result($SQL_Result, $n, 'angreifer_galaxie').':'.mysql_result($SQL_Result, $n, 'angreifer_planet').$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' ('.GetScans($SQL_DBConn, mysql_result($SQL_Result, $n, 'angreifer_galaxie'), mysql_result($SQL_Result, $n, 'angreifer_planet')).' ETA'.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$deffer_eta.$irc_text['farbe'].$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].')';
                        }
                    }
                }
                $SQL_Result = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` WHERE verteidiger_galaxie="'.$tmp_galaxie.'" AND verteidiger_planet="'.$tmp_planet.'" ORDER BY verteidiger_galaxie;', $SQL_DBConn);
                $count =  mysql_num_rows($SQL_Result);
                if ( $count == 0 ){
                    echo 'Der hat kein inc du depp!!!';
                return;
                } else {
                $text = "\n".$irc_text['farbe'].$irc_farbe['orange'].','.$irc_farbe['weiss'].' '.gnuser($tmp_galaxie, $tmp_planet).' 01,00(12,00'.$tmp_galaxie.':'.$tmp_planet.'01,00)'.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' hat'.$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$incomming_counter.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' Angreifer und'.$irc_text['farbe'].$irc_farbe['blau'].','.$irc_farbe['weiss'].' '.$deff_counter.$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' Verteidiger'."\n".$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' Angreifer: '.$tmp_atter."\n".$irc_text['farbe'].$irc_farbe['schwarz'].','.$irc_farbe['weiss'].' Verteidiger: '.$tmp_deffer;
        }
            }
        }

    }




// Scans vom Koods anzeigen


elseif($_GET['modus'] == 2) {


				$tmp_pos = strpos($_GET['koord'], ':');

            if ($tmp_pos == 0)

                $text = ' Sie müssen eine gültige Koordinate angeben! ('.$_GET['koord'].')';

				else { $tmp_galaxie = substr($_GET['koord'], 0, $tmp_pos);
                $tmp_planet = substr($_GET['koord'], $tmp_pos + 1);


		$sql='select * from `gn4scans` where rg='.$tmp_galaxie.' and rp='.$tmp_planet.' ';
			$SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
		 $count =  mysql_num_rows($SQL_Result);
    if ( $count == 0 ) {
        echo 'Keine Scans vorhanden.';
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
                $rpnext = mysql_result($SQL_Result, $i+1, 'rp' );
            else
                $rpnext = 999;

            $p = mysql_result($SQL_Result, $i, 'p' );
            $g = mysql_result($SQL_Result, $i, 'g' );
            $type = mysql_result($SQL_Result, $i, 'type' );
            $rp = mysql_result($SQL_Result, $i, 'rp' );
            $rg = mysql_result($SQL_Result, $i, 'rg' );
            $rname = gnuser($rg, $rp);
            $rscans .= sprintf( "%d ", $type );
//echo '<br>type='.$type.' - ';
            switch( $type ) {   // scan-type
                case 0: // sektor
                	$sname	= gnuser($g, $p);
                    $szeit  = mysql_result($SQL_Result, $i, 'zeit' );
                    $sgen   = mysql_result($SQL_Result, $i, 'gen' );
                    $pts    = mysql_result($SQL_Result, $i, 'pts' );
                    $me     = mysql_result($SQL_Result, $i, 'me' );
                    $ke     = mysql_result($SQL_Result, $i, 'ke' );
                    $s      = mysql_result($SQL_Result, $i, 's' );
                    $d      = mysql_result($SQL_Result, $i, 'd' );
                    $a      = mysql_result($SQL_Result, $i, 'a' );
                    break;
                case 1: // unit
                	$uname	= gnuser($g, $p);
                    $uzeit  = mysql_result($SQL_Result, $i, 'zeit' );
                    $ugen   = mysql_result($SQL_Result, $i, 'gen' );
                    $ja     = mysql_result($SQL_Result, $i, 'sfj' );
                    $bo     = mysql_result($SQL_Result, $i, 'sfb' );
                    $fr     = mysql_result($SQL_Result, $i, 'sff' );
                    $ze     = mysql_result($SQL_Result, $i, 'sfz' );
                    $kr     = mysql_result($SQL_Result, $i, 'sfkr' );
                    $sl     = mysql_result($SQL_Result, $i, 'sfsa' );
                    $tr     = mysql_result($SQL_Result, $i, 'sft' );
                    $ka     = mysql_result($SQL_Result, $i, 'sfka' );
                    $ca     = mysql_result($SQL_Result, $i, 'sfsu' );
                    break;
                case 2: // mili-scan
                	  $mname	= gnuser($g, $p);
                    $mzeit  = mysql_result($SQL_Result, $i, 'zeit' );
                    $mgen   = mysql_result($SQL_Result, $i, 'gen' );
                    $ja0    = mysql_result($SQL_Result, $i, 'sf0j' );
                    $bo0    = mysql_result($SQL_Result, $i, 'sf0b' );
                    $fr0    = mysql_result($SQL_Result, $i, 'sf0f' );
                    $ze0    = mysql_result($SQL_Result, $i, 'sf0z' );
                    $kr0    = mysql_result($SQL_Result, $i, 'sf0kr' );
                    $sl0    = mysql_result($SQL_Result, $i, 'sf0sa' );
                    $tr0    = mysql_result($SQL_Result, $i, 'sf0t' );
                    $ka0    = mysql_result($SQL_Result, $i, 'sf0ka' );
                    $ca0    = mysql_result($SQL_Result, $i, 'sf0su' );
                    $ja1    = mysql_result($SQL_Result, $i, 'sf1j' );
                    $bo1    = mysql_result($SQL_Result, $i, 'sf1b' );
                    $fr1    = mysql_result($SQL_Result, $i, 'sf1f' );
                    $ze1    = mysql_result($SQL_Result, $i, 'sf1z' );
                    $kr1    = mysql_result($SQL_Result, $i, 'sf1kr' );
                    $sl1    = mysql_result($SQL_Result, $i, 'sf1sa' );
                    $tr1    = mysql_result($SQL_Result, $i, 'sf1t' );
                    $ka1    = mysql_result($SQL_Result, $i, 'sf1ka' );
                    $ca1    = mysql_result($SQL_Result, $i, 'sf1su' );
                    $ja2    = mysql_result($SQL_Result, $i, 'sf2j' );
                    $bo2    = mysql_result($SQL_Result, $i, 'sf2b' );
                    $fr2    = mysql_result($SQL_Result, $i, 'sf2f' );
                    $ze2    = mysql_result($SQL_Result, $i, 'sf2z' );
                    $kr2    = mysql_result($SQL_Result, $i, 'sf2kr' );
                    $sl2    = mysql_result($SQL_Result, $i, 'sf2sa' );
                    $tr2    = mysql_result($SQL_Result, $i, 'sf2t' );
                    $ka2    = mysql_result($SQL_Result, $i, 'sf2ka' );
                    $ca2    = mysql_result($SQL_Result, $i, 'sf2su' );

                    break;
                case 3: // geschütz
                	$gname	= gnuser($g, $p);
                    $gzeit  = mysql_result($SQL_Result, $i, 'zeit' );
                    $ggen   = mysql_result($SQL_Result, $i, 'gen' );
                    $lo     = mysql_result($SQL_Result, $i, 'glo' );
                    $lr     = mysql_result($SQL_Result, $i, 'glr' );
                    $mr     = mysql_result($SQL_Result, $i, 'gmr' );
                    $sr     = mysql_result($SQL_Result, $i, 'gsr' );
                    $aj     = mysql_result($SQL_Result, $i, 'ga' );
                    break;
                default:
                    echo '????huh?!??? - Ohooooh';
                    break;
            }
						// echo '('.$rpnext.' <>'. $rp.')';
        if ( $rpnext <> $rp ) {

						if($_GET['istscanart'] == 'sek') {
                        			$text =       	  "\n".'00,10Sektorscan (01,10 '.$sgen.' %00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)';
                        			$text = $text."\n".'00,01Punkte: 07,01'.number_format($pts, 0, ',', '.').' 00,01Astros: 07,01'.$a;
                        			$text = $text."\n".'00,01Schiffe: 07,01'.$s.' 00,01Geschütze: 07,01'.$d.'';
                        			$text = $text."\n".'00,01Metall-Exen: 07,01'.$me.' 00,01Kristall-Exen: 07,01'.$ke.'';
                        			$text = $text."\n".'00,01Datum: 07,01'.$szeit.' 00,01gescannt von: 07,01'.$sname.'';
                        			}


						if($_GET['istscanart'] == 'einheit') {

						$text = 	  "\n".'00,10Einheitenscan (01,10 '.$ugen.' %00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)';
						$text = $text."\n".'00,01Leo: 07,01'.$ja.' 00,01Aquilae: 07,01'.$bo.' 00,01Fronax: 07,01'.$fr.' 00,01Draco: 07,01'.$ze.' 00,01Goron: 07,01'.$kr.'';
						$text = $text."\n".'00,01Pentalin: 07,01'.$sl.' 00,01Zenit: 07,01'.$tr.' 00,01Cleptor: 07,01'.$ka.' 00,01Cancri: 07,01'.$ca.'';
						$text = $text."\n".'00,01Datum: 07,01'.$uzeit.' 00,01gescannt von: 07,01'.$uname.'';


						}

						if($_GET['istscanart'] == 'gscan') {

						$text = 	  "\n".'00,10Geschützscan (01,10 '.$ggen.' %00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)';
						$text = $text."\n".'00,01Rubium: 07,01'.$lo.' 00,01Pulsar: 07,01'.$lr.' 00,01Coon: 07,01'.$mr.'';
						$text = $text."\n".'00,01Centurion: 07,01'.$sr.' 00,01Horus: 07,01'.$aj.'';
						$text = $text."\n".'00,01Datum: 07,01'.$gzeit.' 00,01gescannt von: 07,01'.$gname.'';


						}

						if($_GET['istscanart'] == 'mili') {

						$text = 	  "\n".'00,10Militärscan (01,10 '.$mgen.' %00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)';
						$text = $text."\n".'00,1Orbit: 07,01'.$ja0.' 00,1Leo 07,01'.$bo0.' 00,1Aquilae 07,01'.$fr0.' 00,1Fornax 07,01'.$ze0.' 00,1Draco 07,01'.$kr0.' 00,1Goron 07,01'.$sl0.' 00,1Pentalin 07,01'.$tr0.' 00,1Zenit 07,01'.$ka0.' 00,1Cleptor 07,01'.$ca0.' 00,1Cancri ';
						$text = $text."\n".'00,01Flotte1: 07,01'.$ja1.' 00,01Leo 07,01'.$bo1.' 00,01Aquilae 07,01'.$fr1.' 00,01Fornax 07,01'.$ze1.' 00,01Draco 07,01'.$kr1.' 00,01Goron 07,01'.$sl1.' 00,01Pentalin 07,01'.$tr1.' 00,01Zenit 07,01'.$ka1.' 00,01Cleptor 07,01'.$ca1.' 00,01Cancri ';
						$text = $text."\n".'00,01Flotte2: 07,01'.$ja2.' 00,01Leo 07,01'.$bo2.' 00,01Aquilae 07,01'.$fr2.' 00,01Fornax 07,01'.$ze2.' 00,01Draco 07,01'.$kr2.' 00,01Goron 07,01'.$sl2.' 00,01Pentalin 07,01'.$tr2.' 00,01Zenit 07,01'.$ka2.' 00,01Cleptor 07,01'.$ca2.' 00,01Cancri ';
						$text = $text."\n".'00,01Datum: 07,01'.$mzeit.' 00,01gescannt von: 07,01'.$mname.'';
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

	### Ally Status abfragen
	elseif($_GET['modus']==3) {
   $SQL_Result5 = tic_mysql_query('SELECT id, name, tag, info_bnds, info_naps, info_inoffizielle_naps, info_kriege, code FROM `gn4allianzen` where ticid="'.$Benutzer['ticid'].'" ;', $SQL_DBConn);

   $SQL_Num5=mysql_num_rows($SQL_Result5);

   for($x='0';$x<$SQL_Num5;$x++){
	 $id=mysql_result($SQL_Result5,$x,'id');
	 $name=mysql_result($SQL_Result5,$x,'name');
	 $tag=mysql_result($SQL_Result5,$x,'tag');
	 $bnds=mysql_result($SQL_Result5,$x,'info_bnds');
	 $naps=mysql_result($SQL_Result5,$x,'info_naps');
	 $defcon=mysql_result($SQL_Result5,$x,'code');
	 $krieg=mysql_result($SQL_Result5,$x,'info_kriege');
     if (!isset($id)) $id = 0;
     if (!isset($name)) $name = 0;
     if (!isset($tag)) $tag = 0;
     if ($bnds=='') $bnds = 0;
     if ($naps=='') $naps = 0;
     if ($defcon=='') $defcon = 0;
     if ($krieg=='') $krieg = 0;
	 $text=$text.$id."|".$name."|".$tag."|".$bnds."|".$naps."|".$defcon."|".$krieg."\n";
   }
 }
    ###Tic Statistiken
   	elseif($_GET['modus']==4) {
           		$SQL_Result1 = tic_mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row1 = mysql_fetch_row($SQL_Result1);
                $SQL_Result2 = tic_mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where modus=1 and ticid="'.$Benutzer['ticid'].'"' , $SQL_DBConn);
                $SQL_Row2 = mysql_fetch_row($SQL_Result2);
                $SQL_Result3 = tic_mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where modus=2and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row3 = mysql_fetch_row($SQL_Result3);
                $SQL_Result4 = tic_mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where modus>2 and ticid="'.$Benutzer['ticid'].'" or modus=0 and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row4 = mysql_fetch_row($SQL_Result4);
                $SQL_Result5 = tic_mysql_query('SELECT COUNT(*) FROM `gn4accounts` where ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row5 = mysql_fetch_row($SQL_Result5);
                $SQL_Result8 = tic_mysql_query('SELECT COUNT(*) FROM `gn4forum` WHERE belongsto="0" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row8 = mysql_fetch_row($SQL_Result8);
                $SQL_Result9 = tic_mysql_query('SELECT COUNT(*) FROM `gn4forum` WHERE NOT belongsto="0" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row9 = mysql_fetch_row($SQL_Result9);
                $SQL_Result10 = tic_mysql_query('SELECT COUNT(*) FROM `gn4scans` where ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row10 = mysql_fetch_row($SQL_Result10);
                $text= "00,01Anzahl Flottenbewegungen: 07,01".ZahlZuText($SQL_Row1[0])."\n00,01Anzahl Verteidingungsflüge: 07,01".ZahlZuText($SQL_Row2[0])."\n00,01Anzahl Angriffsflüge: 07,01".ZahlZuText($SQL_Row3[0])."\n00,01Anzahl Rückflüge: 07,01".ZahlZuText($SQL_Row4[0])."\n"."00,01Anzahl der T.I.C. Accounts: 07,01".ZahlZuText($SQL_Row5[0])."\n"."00,01Forenstatistik: 07,01"."\n"."00,01Themen: 07,01".ZahlZuText($SQL_Row8[0])."\n"."00,01Antworten: 07,01".ZahlZuText($SQL_Row9[0])."\n"."00,01Scan Datenbank: 07,01"."\n"."00,01Anzahl Scans: 07,01".ZahlZuText($SQL_Row10[0])."\n"."00,01Letzte Scansäuberung: 07,01".$lastscanclean;







   	}
    ### Top5 Scaner
   	elseif($_GET['modus']==5) {
	 		$SQL_Result11 = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE scantyp = 1 and ticid="'.$Benutzer['ticid'].'" ORDER BY svs DESC;', $SQL_DBConn);
			$text=$text."\n".$irc_text['farbe'].$irc_farbe['orange']."MILI-SCANNER";
			for ($n = 0; $n < 5; $n++) {
					$name  = mysql_result($SQL_Result11, $n, 'name' );
					$svs = mysql_result($SQL_Result11, $n, 'svs' );
					$gala = mysql_result($SQL_Result11, $n, 'galaxie' );
					$planet = mysql_result($SQL_Result11, $n, 'planet' );
					$text=$text."\n".$name." ( ".$gala.":".$planet." ) hat ".$svs." svs";
			}
			$SQL_Result12 = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE scantyp = 2 and ticid="'.$Benutzer['ticid'].'" ORDER BY svs DESC;', $SQL_DBConn);
						$text=$text."\n".$irc_text['farbe'].$irc_farbe['orange']."NEWS-SCANNER";
						for ($m = 0; $m < 5; $m++) {
								$name  = mysql_result($SQL_Result12, $m, 'name' );
								$svs = mysql_result($SQL_Result12, $m, 'svs' );
								$gala = mysql_result($SQL_Result12, $m, 'galaxie' );
								$planet = mysql_result($SQL_Result12, $m, 'planet' );


						        $text=$text."\n".$name." ( ".$gala.":".$planet." ) hat ".$svs." svs";
			}


	}elseif($_GET['modus']==6) {
$text=$_GET['text'];
$text=str_replace(",","",$text);
$text=str_replace(".","",$text);
$text=str_replace("%","",$text);
$scan = explode (" ", $text);
$nr = count($scan);
  if($scan['1']=='Sektorscan'){
  $modi=0;
  $qry=$scan['5'].', '.$scan['6'].', '.$scan['7'].', '.$scan['8'].', '.$scan['9'];
  $formart='pts, s, d, me, ke';
  }
  if($scan['1']=='Einheitenscan'){
  $modi=1;
  $qry=$scan['5'].', '.$scan['6'].', '.$scan['7'].', '.$scan['8'].', '.$scan['9'].', '.$scan['10'].', '.$scan['11'].', '.$scan['12'].', '.$scan['13'];
  $formart = 'sfka, sfsu, sff, sfz, sfkr, sfsa, sft, sfj, sfb';
  }
  if($scan['1']=='Militärscan'){
  $modi=2;
  $qry=$scan['5'].', '.$scan['6'].', '.$scan['7'].', '.$scan['8'].', '.$scan['9'].', '.$scan['10'].', '.$scan['11'].', '.$scan['12'].', '.$scan['13'].', '.$scan['14'];
  $qry=$qry.', '.$scan['15'].', '.$scan['16'].', '.$scan['17'].', '.$scan['18'].', '.$scan['19'].', '.$scan['20'].', '.$scan['21'].', '.$scan['22'].', '.$scan['25'].', '.$scan['26'];
  $qry=$qry.', '.$scan['27'].', '.$scan['28'].', '.$scan['29'].', '.$scan['30'].', '.$scan['31'].', '.$scan['32'].', '.$scan['33'];
    $formart = 'sf0ka, sf0su, sf0f, sf0z, sf0kr, sf0sa, sf0t, sf0j, sf0b';
    $formart = $formart.', sf1ka, sf1su, sf1f, sf1z, sf1kr, sf1sa, sf1t, sf1j, sf1b';
    $formart = $formart.', sf2ka, sf2su, sf2f, sf2z, sf2kr, sf2sa, sf2t, sf2j, sf2b';
  }
  if($scan['1']=='Geschützscan'){
  $modi=3;
  $qry=$scan['5'].', '.$scan['6'].', '.$scan['7'].', '.$scan['8'].', '.$scan['9'];
  $formart = 'glo, glr, gmr, gsr, ga';
  }
 if(isset($modi)){
 $koords=explode(":",$scan['4']);
 $koord= explode(":",$_GET['koord']);
 tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$koords['0'].'" AND rp="'.$koords['1'].'" AND type="'.$modi.'";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
 tic_mysql_query('INSERT INTO `gn4scans` (p, g, type, zeit, rg, rp, gen, '.$formart.') VALUES ("'.$koord['0'].'", "'.$koord['1'].'", "'.$modi.'", "'.date("H:i d.m.Y").'", "'.$koords['0'].'", "'.$koords['1'].'", "'.$scan['2'].'", '.$qry.');', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
addgnuser($koords['0'], $koords['1'], $scan['3']);
}
}elseif($_GET['modus']==7) {
if(isset($_GET['auth'])){
$SQL_Result=tic_mysql_query('SELECT * FROM `gn4accounts` WHERE authnick="'.$_GET['auth'].'";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
$ok= 'ok';
if(mysql_num_rows($SQL_Result)!=1){
$ok = 'fail '.mysql_num_rows($SQL_Result);
}else{
$ticid  = mysql_result($SQL_Result, 0, 'ticid' );
$ally = mysql_result($SQL_Result, 0, 'allianz' );
$status = mysql_result($SQL_Result, 0, 'spy' );
$rang = mysql_result($SQL_Result, 0, 'rang' );

echo $ok.'|'.$status.'|'.$ticid.'|'.$ally.'|'.$rang;
}
}
}









	if ($_GET['modus']==4)
	{
		$text = $irc_text['fett'].$irc_text['farbe'].$irc_farbe['weiss'].','.$irc_farbe['dunkelblau'].'[T.I.C - Statistik]'.$irc_text['farbe'].$irc_text['fett']."\n".$text;
		$text = $text."\n".$irc_text['farbe'].$irc_farbe['weiss'].','.$irc_farbe['dunkelgrau'].'[ http://'.$HTTP_HOST.'     coding by http://www.tic-entwickler.de]'.$irc_text['farbe'];
	}
	elseif ($_GET['modus']==5)
		{
			$text = $irc_text['fett'].$irc_text['farbe'].$irc_farbe['weiss'].','.$irc_farbe['dunkelblau'].'[T.I.C - Scanner]'.$irc_text['farbe'].$irc_text['fett'].$text;
			$text = $text."\n".$irc_text['farbe'].$irc_farbe['weiss'].','.$irc_farbe['dunkelgrau'].'[ http://'.$HTTP_HOST.'     coding by http://www.tic-entwickler.de]'.$irc_text['farbe'];
	}
    elseif ($_GET['modus']!=3 && $_GET['modus']!=4 && $_GET['modus']!=5 && $_GET['modus']!=6 && $_GET['modus']!=7)
    {
    	$text = $irc_text['fett'].$irc_text['farbe'].$irc_farbe['weiss'].','.$irc_farbe['dunkelblau'].'[ T.I.C. | Tactical Information Center ]'.$irc_text['farbe'].$irc_text['fett']."".$text;
		$text = $text."\n".$irc_text['farbe'].$irc_farbe['weiss'].','.$irc_farbe['dunkelgrau'].'[ http://'.$HTTP_HOST.'     coding by http://www.tic-entwickler.de]'.$irc_text['farbe'];
    }
    echo $text;
?>
