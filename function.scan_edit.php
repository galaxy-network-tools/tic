<?php

function sek_write_to_db() {
	global $SQL_DBConn, $Benutzer;

    $trg = $_POST['galakoord'];
    $trp= $_POST['planetkoord'];
    $ttype = 0;
    $tgen = 99;

    $tgr=0;


    $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$trg.'" AND rp="'.$trp.'" AND type="'.$ttype.'";', $SQL_DBConn);
    $insert_names = 'pts, s, d, me, ke, a';
    $insert_values = '"'.$_POST['tpts'].'", "'.$_POST['ts'].'", "'.$_POST['td'].'", "'.$_POST['tme'].'", "'.$_POST['tke'].'", "'.$_POST['ta'].'"';
    $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$ttype.'","'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$trg.'", "'.$trp.'", "'.$tgen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
    addgnuser($trg, $trp, $_POST['trn']);
    // ???
    $txtScanGalaxie = $_POST['galakoord'];
    $txtScanPlanet = $_POST['planetkoord'];
}

function unit_write_to_db() {
	global $SQL_DBConn, $Benutzer;


    $trg = $_POST['galakoord'];
    $trp= $_POST['planetkoord'];
    $ttype = 1;
    $tgen = 99;

    $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$trg.'" AND rp="'.$trp.'" AND type="'.$ttype.'";', $SQL_DBConn);
    $insert_names = 'sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfka, sfsu';
    $insert_values = '"'.$_POST['tsfj'].'", "'.$_POST['tsfb'].'", "'.$_POST['tsff'].'", "'.$_POST['tsfz'].'", "'.$_POST['tsfkr'].'", "'.$_POST['tsfsa'].'", "'.$_POST['tsft'].'", "'.$_POST['tsfka'].'", "'.$_POST['tsfsu'].'"';
    $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$ttype.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$trg.'", "'.$trp.'", "'.$tgen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
    addgnuser($trg, $trp, $_POST['trn']);

    // ???
    $txtScanGalaxie = $_POST['galakoord'];
    $txtScanPlanet = $_POST['planetkoord'];
}

function g_write_to_db() {
	global $SQL_DBConn, $Benutzer;

    $trg = $_POST['galakoord'];
    $trp= $_POST['planetkoord'];
    $ttype = 3;
    $tgen = 99;

    $tgr=0;


    $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$trg.'" AND rp="'.$trp.'" AND type="'.$ttype.'";', $SQL_DBConn);
    $insert_names = 'glo, glr, gmr, gsr, ga';
    $insert_values = '"'.$_POST['tglo'].'", "'.$_POST['tglr'].'", "'.$_POST['tgmr'].'", "'.$_POST['tgsr'].'", "'.$_POST['tga'].'"';
    $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$ttype.'","'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$trg.'", "'.$trp.'", "'.$tgen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
    addgnuser($trg, $trp, $_POST['trn']);
    // ???
    $txtScanGalaxie = $_POST['galakoord'];
    $txtScanPlanet = $_POST['planetkoord'];

}

function mili_write_to_db() {
	global $SQL_DBConn, $Benutzer;

    $trg = $_POST['galakoord'];
    $trp= $_POST['planetkoord'];
    $ttype = 2;
    $tgen = 99;

    $tsf0ko=0;
    $tsf1ko=0;
    $tsf2ko=0;

    $tstatus0 = 4;
    $tstatus1 = 4;
    $tstatus2 = 4;

    $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$trg.'" AND rp="'.$trp.'" AND type="'.$ttype.'";', $SQL_DBConn);
    $insert_names = 'sf0j, sf0b, sf0f, sf0z, sf0kr, sf0sa, sf0t, sf0ka, sf0su';
    $insert_names = $insert_names.', sf1j, sf1b, sf1f, sf1z, sf1kr, sf1sa, sf1t, sf1ka, sf1su, status1';
    $insert_names = $insert_names.', sf2j, sf2b, sf2f, sf2z, sf2kr, sf2sa, sf2t, sf2ka, sf2su, status2';
    $insert_values = '"'.$_POST['tsf0j'].'", "'.$_POST['tsf0b'].'", "'.$_POST['tsf0f'].'", "'.$_POST['tsf0z'].'", "'.$_POST['tsf0kr'].'", "'.$_POST['tsf0sa'].'", "'.$_POST['tsf0t'].'", "'.$_POST['tsf0ka'].'", "'.$_POST['tsf0su'].'"';
    $insert_values = $insert_values.', "'.$_POST['tsf1j'].'", "'.$_POST['tsf1b'].'", "'.$_POST['tsf1f'].'", "'.$_POST['tsf1z'].'", "'.$_POST['tsf1kr'].'", "'.$_POST['tsf1sa'].'", "'.$_POST['tsf1t'].'", "'.$_POST['tsf1ka'].'", "'.$_POST['tsf1su'].'", "'.$_POST['tstatus1'].'"';
    $insert_values = $insert_values.', "'.$_POST['tsf2j'].'", "'.$_POST['tsf2b'].'", "'.$_POST['tsf2f'].'", "'.$_POST['tsf2z'].'", "'.$_POST['tsf2kr'].'", "'.$_POST['tsf2sa'].'", "'.$_POST['tsf2t'].'", "'.$_POST['tsf2ka'].'", "'.$_POST['tsf2su'].'", "'.$_POST['tstatus2'].'"';
    addgnuser($trg, $trp, $_POST['trn']);
    $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$ttype.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$trg.'", "'.$trp.'", "'.$tgen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');


    $ttype = 1;
    // jï¿½er
    $_POST['tsfj'] = $_POST['tsf0j'] + $_POST['tsf1j'] +$_POST['tsf2j'];

    // bomber
    $_POST['tsfb'] = $_POST['tsf0b'] + $_POST['tsf1b'] + $_POST['tsf2b'];

    // fregs
    $_POST['tsff'] = $_POST['tsf0f'] + $_POST['tsf1f'] + $_POST['tsf2f'];

    // zerries
    $_POST['tsfz'] = $_POST['tsf0z'] + $_POST['tsf1z'] + $_POST['tsf2z'];

    // kreuzer
    $_POST['tsfkr'] = $_POST['tsf0kr'] + $_POST['tsf1kr'] + $_POST['tsf2kr'];

    // schlachter
    $_POST['tsfsa'] = $_POST['tsf0sa'] + $_POST['tsf1sa'] + $_POST['tsf2sa'];

    // trï¿½er
    $_POST['tsft']  = $_POST['tsf0t']  + $_POST['tsf1t'] + $_POST['tsf2t'];

    // komisches ding
    $sfko = 0;

    // Kaper
    $_POST['tsfka'] = $_POST['tsf0ka'] + $_POST['tsf1ka'] + $_POST['tsf2ka'];

    // schutzies
    $_POST['tsfsu'] = $_POST['tsf0su'] + $_POST['tsf1su'] +$_POST['tsf2su'];

    $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$trg.'" AND rp="'.$trp.'" AND type="'.$ttype.'";', $SQL_DBConn);
    $insert_names = 'sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfka, sfsu';
    $insert_values = '"'.$_POST['tsfj'].'", "'.$_POST['tsfb'].'", "'.$_POST['tsff'].'", "'.$_POST['tsfz'].'", "'.$_POST['tsfkr'].'", "'.$_POST['tsfsa'].'", "'.$_POST['tsft'].'", "'.$_POST['tsfka'].'", "'.$_POST['tsfsu'].'"';
    $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$ttype.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$trg.'", "'.$trp.'", "'.$tgen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
    addgnuser($trg, $trp, $_POST['trn']);

    // ???
    $txtScanGalaxie = $_POST['galakoord'];
    $txtScanPlanet = $_POST['planetkoord'];
}

if (isset($_POST['scanart'])) {
	eval($_POST['scanart']."_write_to_db();");
}

?>