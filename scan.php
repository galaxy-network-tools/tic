<?PHP
    include('./accdata.php');

    // Verbindung zur Datenbank aufbauen
    $SQL_DBConn = @mysql_connect($db_info['host'], $db_info['user'], $db_info['password']) or $error_code = 1;
    @mysql_select_db($db_info['dbname'], $SQL_DBConn) or $error_code = 2;

    include('./functions.php');

    for (reset($HTTP_GET_VARS); $k = key($HTTP_GET_VARS); next($HTTP_GET_VARS)) $$k = TextZuZahl($$k);
    for (reset($HTTP_POST_VARS); $k = key($HTTP_POST_VARS); next($HTTP_POST_VARS)) $$k = TextZuZahl($$k);

    $action=$_GET['action'];
    $g=$_GET['g'];
    $p=$_GET['p'];
    $pw=$_GET['pw'];
    $rg=$_GET['rg'];
    $rp=$_GET['rp'];
    $rn=$_GET['rn'];
    $gen=$_GET['gen'];
    $pts=$_GET['pts'];
    $s=$_GET['s'];
    $d=$_GET['d'];
    $me=$_GET['me'];
    $ke=$_GET['ke'];
    $a=$_GET['a'];
    $sfj=$_GET['sfj'];
    $sfb=$_GET['sfb'];
    $sff=$_GET['sff'];
    $sfz=$_GET['sfz'];
    $sfkr=$_GET['sfkr'];
    $sfsa=$_GET['sfsa'];
    $sft=$_GET['sft'];
    $sfko=$_GET['sfko'];
    $sfka=$_GET['sfka'];
    $sfsu=$_GET['sfsu'];
    $sf0j=$_GET['sf0j'];
    $sf0b=$_GET['sf0b'];
    $sf0f=$_GET['sf0f'];
    $sf0z=$_GET['sf0z'];
    $sf0kr=$_GET['sf0kr'];
    $sf0sa=$_GET['sf0sa'];
    $sf0t=$_GET['sf0t'];
    $sf0ko=$_GET['sf0ko'];
    $sf0ka=$_GET['sf0ka'];
    $sf0su=$_GET['sf0su'];
    $sf1j=$_GET['sf1j'];
    $sf1b=$_GET['sf1b'];
    $sf1f=$_GET['sf1f'];
    $sf1z=$_GET['sf1z'];
    $sf1kr=$_GET['sf1kr'];
    $sf1sa=$_GET['sf1sa'];
    $sf1t=$_GET['sf1t'];
    $sf1ko=$_GET['sf1ko'];
    $sf1ka=$_GET['sf1ka'];
    $sf1su=$_GET['sf1su'];
    $ziel1=$_GET['ziel1'];
    $status1=$_GET['status1'];
    $sf2j=$_GET['sf2j'];
    $sf2b=$_GET['sf2b'];
    $sf2f=$_GET['sf2f'];
    $sf2z=$_GET['sf2z'];
    $sf2kr=$_GET['sf2kr'];
    $sf2sa=$_GET['sf2sa'];
    $sf2t=$_GET['sf2t'];
    $sf2ko=$_GET['sf2ko'];
    $sf2ka=$_GET['sf2ka'];
    $sf2su=$_GET['sf2su'];
    $ziel2=$_GET['ziel2'];
    $status2=$_GET['status2'];
    $glo=$_GET['glo'];
    $glr=$_GET['glr'];
    $gmr=$_GET['gmr'];
    $gsr=$_GET['gsr'];
    $ga=$_GET['ga'];
    $gr=$_GET['gr'];



    if (!isset($action)) $action = '';
    if (!isset($g)) $g = 0;
    if (!isset($p)) $p = 0;
    if (!isset($pw)) $pw = '';
    if (!isset($rg)) $rg = 0;
    if (!isset($rp)) $rp = 0;
    if (!isset($rn)) $rn = '';
    if (!isset($gen)) $gen = 0;
    if (!isset($pts)) $pts = 0;
    if (!isset($s)) $s = 0;
    if (!isset($d)) $d = 0;
    if (!isset($me)) $me = 0;
    if (!isset($ke)) $ke = 0;
    if (!isset($a)) $a = 0;
    if (!isset($sfj)) $sfj = 0;
    if (!isset($sfb)) $sfb = 0;
    if (!isset($sff)) $sff = 0;
    if (!isset($sfz)) $sfz = 0;
    if (!isset($sfkr)) $sfkr = 0;
    if (!isset($sfsa)) $sfsa = 0;
    if (!isset($sft)) $sft = 0;
    if (!isset($sfko)) $sfko = 0;
    if (!isset($sfka)) $sfka = 0;
    if (!isset($sfsu)) $sfsu = 0;
    if (!isset($sf0j)) $sf0j = 0;
    if (!isset($sf0b)) $sf0b = 0;
    if (!isset($sf0f)) $sf0f = 0;
    if (!isset($sf0z)) $sf0z = 0;
    if (!isset($sf0kr)) $sf0kr = 0;
    if (!isset($sf0sa)) $sf0sa = 0;
    if (!isset($sf0t)) $sf0t = 0;
    if (!isset($sf0ko)) $sf0ko = 0;
    if (!isset($sf0ka)) $sf0ka = 0;
    if (!isset($sf0su)) $sf0su = 0;
    if (!isset($sf1j)) $sf1j = 0;
    if (!isset($sf1b)) $sf1b = 0;
    if (!isset($sf1f)) $sf1f = 0;
    if (!isset($sf1z)) $sf1z = 0;
    if (!isset($sf1kr)) $sf1kr = 0;
    if (!isset($sf1sa)) $sf1sa = 0;
    if (!isset($sf1t)) $sf1t = 0;
    if (!isset($sf1ko)) $sf1ko = 0;
    if (!isset($sf1ka)) $sf1ka = 0;
    if (!isset($sf1su)) $sf1su = 0;
    if (!isset($ziel1)) $ziel1 = '';
    if (!isset($status1)) $status1 = 0;
    if (!isset($sf2j)) $sf2j = 0;
    if (!isset($sf2b)) $sf2b = 0;
    if (!isset($sf2f)) $sf2f = 0;
    if (!isset($sf2z)) $sf2z = 0;
    if (!isset($sf2kr)) $sf2kr = 0;
    if (!isset($sf2sa)) $sf2sa = 0;
    if (!isset($sf2t)) $sf2t = 0;
    if (!isset($sf2ko)) $sf2ko = 0;
    if (!isset($sf2ka)) $sf2ka = 0;
    if (!isset($sf2su)) $sf2su = 0;
    if (!isset($ziel2)) $ziel2 = '';
    if (!isset($status2)) $status2 = 0;
    if (!isset($glo)) $glo = 0;
    if (!isset($glr)) $glr = 0;
    if (!isset($gmr)) $gmr = 0;
    if (!isset($gsr)) $gsr = 0;
    if (!isset($ga)) $ga = 0;
    if (!isset($gr)) $gr = 0;

    if ($action == '') {
        header('Location: ./scan_help.html');
        die();
    }



    $SQL_Result = tic_mysql_query('SELECT id, name, passwort FROM `gn4accounts` WHERE galaxie="'.$g.'" and passwort="'.md5($pw).'" AND planet="'.$p.'";', $SQL_DBConn);
    if (mysql_num_rows($SQL_Result) != 1) die("<html>\n<body>\nERROR 0 Spieler nicht gefunden\n</body>\n</html>");
    $name = mysql_result($SQL_Result, 0, 'name');
    CountScans(mysql_result($SQL_Result, 0, 'id'));

    addgnuser($rg, $rp, $rn);

    if ($action == 'ss') {
        $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$rg.'" AND rp="'.$rp.'" AND type="0";', $SQL_DBConn);
        $insert_names = 'pts, s, d, me, ke, a';
        $insert_values = '"'.$pts.'", "'.$s.'", "'.$d.'", "'.$me.'", "'.$ke.'", "'.$a.'"';
        $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("0", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$g.'", "'.$p.'", "'.$rg.'", "'.$rp.'", "'.$gen.'", '.$insert_values.');', $SQL_DBConn) or die("<html>\n<body>\nERROR 2 Konnte Datensatz nicht schreiben\n</body>\n</html>");
    }
    if ($action == 'se') {
        $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$rg.'" AND rp="'.$rp.'" AND type="1";', $SQL_DBConn);
        $insert_names = 'sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfko, sfka, sfsu';
        $insert_values = '"'.$sfj.'", "'.$sfb.'", "'.$sff.'", "'.$sfz.'", "'.$sfkr.'", "'.$sfsa.'", "'.$sft.'", "'.$sfko.'", "'.$sfka.'", "'.$sfsu.'"';
        $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("1", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$g.'", "'.$p.'", "'.$rg.'", "'.$rp.'", "'.$gen.'", '.$insert_values.');', $SQL_DBConn) or die("<html>\n<body>\nERROR 2 Konnte Datensatz nicht schreiben\n</body>\n</html>");
    }
    if ($action == 'sm') {
        $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$rg.'" AND rp="'.$rp.'" AND type="2";', $SQL_DBConn);
        $insert_names = 'sf0j, sf0b, sf0f, sf0z, sf0kr, sf0sa, sf0t, sf0ko, sf0ka, sf0su';
        $insert_names = $insert_names.', sf1j, sf1b, sf1f, sf1z, sf1kr, sf1sa, sf1t, sf1ko, sf1ka, sf1su, ziel1, status1';
        $insert_names = $insert_names.', sf2j, sf2b, sf2f, sf2z, sf2kr, sf2sa, sf2t, sf2ko, sf2ka, sf2su, ziel2, status2';
        $insert_values = '"'.$sf0j.'", "'.$sf0b.'", "'.$sf0f.'", "'.$sf0z.'", "'.$sf0kr.'", "'.$sf0sa.'", "'.$sf0t.'", "'.$sf0ko.'", "'.$sf0ka.'", "'.$sf0su.'"';
        $insert_values = $insert_values.', "'.$sf1j.'", "'.$sf1b.'", "'.$sf1f.'", "'.$sf1z.'", "'.$sf1kr.'", "'.$sf1sa.'", "'.$sf1t.'", "'.$sf1ko.'", "'.$sf1ka.'", "'.$sf1su.'", "'.$ziel1.'", "'.$status1.'"';
        $insert_values = $insert_values.', "'.$sf2j.'", "'.$sf2b.'", "'.$sf2f.'", "'.$sf2z.'", "'.$sf2kr.'", "'.$sf2sa.'", "'.$sf2t.'", "'.$sf2ko.'", "'.$sf2ka.'", "'.$sf2su.'", "'.$ziel2.'", "'.$status2.'"';
        $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("2", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$g.'", "'.$p.'", "'.$rg.'", "'.$rp.'", "'.$gen.'", '.$insert_values.');', $SQL_DBConn) or die("<html>\n<body>\nERROR 2 Konnte Datensatz nicht schreiben\n</body>\n</html>");
    }
    if ($action == 'sg') {
        $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$rg.'" AND rp="'.$rp.'" AND type="3";', $SQL_DBConn);
        $insert_names = 'glo, glr, gmr, gsr, ga, gr';
        $insert_values = '"'.$glo.'", "'.$glr.'", "'.$gmr.'", "'.$gsr.'", "'.$ga.'", "'.$gr.'"';
        $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("3", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$g.'", "'.$p.'", "'.$rg.'", "'.$rp.'", "'.$gen.'", '.$insert_values.');', $SQL_DBConn) or die("<html>\n<body>\nERROR 2 Konnte Datensatz nicht schreiben\n</body>\n</html>");
    }
    die("<html>\n<body>\nOK\n</body>\n</html>");
?>
