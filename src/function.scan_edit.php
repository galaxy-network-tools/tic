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

    $postData = new PostData($_POST);

    $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$trg.'" AND rp="'.$trp.'" AND type="'.$ttype.'";', $SQL_DBConn);
    $insert_names = 'sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfka, sfsu';
    $insert_values = '"'.$postData->getInt('tsfj').'", "'.$postData->getInt('tsfb').'", "'.$postData->getInt('tsff').'", "'.$postData->getInt('tsfz').'", "'.$postData->getInt('tsfkr').'", "'.$postData->getInt('tsfsa').'", "'.$postData->getInt('tsft').'", "'.$postData->getInt('tsfka').'", "'.$postData->getInt('tsfsu').'"';
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

    $postData = new PostData($_POST);

    $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$trg.'" AND rp="'.$trp.'" AND type="'.$ttype.'";', $SQL_DBConn);
    $insert_names = 'glo, glr, gmr, gsr, ga';
    $insert_values = '"'.$postData->getInt('tglo').'", "'.$postData->getInt('tglr').'", "'.$postData->getInt('tgmr').'", "'.$postData->getInt('tgsr').'", "'.$postData->getInt('tga').'"';
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

    $postData = new PostData($_POST);

    $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$trg.'" AND rp="'.$trp.'" AND type="'.$ttype.'";', $SQL_DBConn);
    $insert_names = 'sf0j, sf0b, sf0f, sf0z, sf0kr, sf0sa, sf0t, sf0ka, sf0su';
    $insert_names = $insert_names.', sf1j, sf1b, sf1f, sf1z, sf1kr, sf1sa, sf1t, sf1ka, sf1su, status1';
    $insert_names = $insert_names.', sf2j, sf2b, sf2f, sf2z, sf2kr, sf2sa, sf2t, sf2ka, sf2su, status2';
    $insert_values = '"'.$postData->getInt('tsf0j').'", "'.$postData->getInt('tsf0b').'", "'.$postData->getInt('tsf0f').'", "'.$postData->getInt('tsf0z').'", "'.$postData->getInt('tsf0kr').'", "'.$postData->getInt('tsf0sa').'", "'.$postData->getInt('tsf0t').'", "'.$postData->getInt('tsf0ka').'", "'.$postData->getInt('tsf0su').'"';
    $insert_values = $insert_values.', "'.$postData->getInt('tsf1j').'", "'.$postData->getInt('tsf1b').'", "'.$postData->getInt('tsf1f').'", "'.$postData->getInt('tsf1z').'", "'.$postData->getInt('tsf1kr').'", "'.$postData->getInt('tsf1sa').'", "'.$postData->getInt('tsf1t').'", "'.$postData->getInt('tsf1ka').'", "'.$postData->getInt('tsf1su').'", "'.$tstatus1.'"';
    $insert_values = $insert_values.', "'.$postData->getInt('tsf2j').'", "'.$postData->getInt('tsf2b').'", "'.$postData->getInt('tsf2f').'", "'.$postData->getInt('tsf2z').'", "'.$postData->getInt('tsf2kr').'", "'.$postData->getInt('tsf2sa').'", "'.$postData->getInt('tsf2t').'", "'.$postData->getInt('tsf2ka').'", "'.$postData->getInt('tsf2su').'", "'.$tstatus2.'"';
    addgnuser($trg, $trp, $_POST['trn']);
    $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$ttype.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$trg.'", "'.$trp.'", "'.$tgen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');


    $ttype = 1;
    // jäger
    $postData->setField('tsfj', $postData->getInt('tsf0j') + $postData->getInt('tsf1j') +$postData->getInt('tsf2j'));

    // bomber
    $postData->setField('tsfb', $postData->getInt('tsf0b') + $postData->getInt('tsf1b') + $postData->getInt('tsf2b'));

    // fregs
    $postData->setField('tsff', $postData->getInt('tsf0f') + $postData->getInt('tsf1f') + $postData->getInt('tsf2f'));

    // zerries
    $postData->setField('tsfz', $postData->getInt('tsf0z') + $postData->getInt('tsf1z') + $postData->getInt('tsf2z'));

    // kreuzer
    $postData->setField('tsfkr', $postData->getInt('tsf0kr') + $postData->getInt('tsf1kr') + $postData->getInt('tsf2kr'));

    // schlachter
    $postData->setField('tsfsa', $postData->getInt('tsf0sa') + $postData->getInt('tsf1sa') + $postData->getInt('tsf2sa'));

    // träger
    $postData->setField('tsft', $postData->getInt('tsf0t')  + $postData->getInt('tsf1t') + $postData->getInt('tsf2t'));

    // komisches ding
    $sfko = 0;

    // Kaper
    $postData->setField('tsfka', $postData->getInt('tsf0ka') + $postData->getInt('tsf1ka') + $postData->getInt('tsf2ka'));

    // schutzies
    $postData->setField('tsfsu', $postData->getInt('tsf0su') + $postData->getInt('tsf1su') +$postData->getInt('tsf2su'));

    $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$trg.'" AND rp="'.$trp.'" AND type="'.$ttype.'";', $SQL_DBConn);
    $insert_names = 'sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfka, sfsu';
    $insert_values = '"'.$postData->getInt('tsfj').'", "'.$postData->getInt('tsfb').'", "'.$postData->getInt('tsff').'", "'.$postData->getInt('tsfz').'", "'.$postData->getInt('tsfkr').'", "'.$postData->getInt('tsfsa').'", "'.$postData->getInt('tsft').'", "'.$postData->getInt('tsfka').'", "'.$postData->getInt('tsfsu').'"';
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
