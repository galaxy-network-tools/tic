<?php
 // Session-Regisriren
session_start();
include 'sessionhelpers.inc.php';
if (isset($_POST['login']))
{
    $userid=check_user($_POST['username'], $_POST['userpass']);
    if ($userid!=false)
        login($userid);
    else
        echo 'Ihre Anmeldedaten waren nicht korrekt!';
}


include('./accdata.php' );
//    include('./functions.php');
    include('./globalvars.php');


    // Verbindung zur Datenbank aufbauen
    $SQL_DBConn = mysqli_connect($db_info['host'], $db_info['user'], $db_info['password']) or $error_code = 1;
    mysqli_select_db($db_info['dbname'], $SQL_DBConn) or $error_code = 2;

    // Variablen laden
    include('./vars.php');

    $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE id="'.$_SESSION['userid'].'";', $SQL_DBConn) or $error_code = 4;
    if (mysqli_num_rows($SQL_Result) != 0) {
            // Nameinfos setzen
            $Benutzer['id'] = tic_mysql_result($SQL_Result, 0, 'id');
            $Benutzer['ticid']=tic_mysql_result($SQL_Result, 0, 'ticid');
            $Benutzer['name'] = tic_mysql_result($SQL_Result, 0, 'name');
            $Benutzer['galaxie'] = tic_mysql_result($SQL_Result, 0, 'galaxie');
            $Benutzer['planet'] = tic_mysql_result($SQL_Result, 0, 'planet');
            $Benutzer['rang'] = tic_mysql_result($SQL_Result, 0, 'rang');
            $Benutzer['allianz'] = tic_mysql_result($SQL_Result, 0, 'allianz');
            $Benutzer['umod'] = tic_mysql_result($SQL_Result, 0, 'umod');
            $Benutzer['tcausw']=tic_mysql_result($SQL_Result, 0, 'tcausw');

            $SQL_Result2 = tic_mysql_query('SELECT blind FROM `gn4allianzen` WHERE id="'.$Benutzer['allianz'].'";', $SQL_DBConn);
            if (mysqli_num_rows($SQL_Result2) != 1) {
                $Benutzer['blind'] = 1;
            } else {
                $Benutzer['blind'] = tic_mysql_result($SQL_Result2, 0, 'blind');
            }
            $SQL_Result = tic_mysql_query('UPDATE `gn4accounts` SET lastlogin="'.date("Y").'-'.date("m").'-'.date("d").'" WHERE id="'.$Benutzer['id'].'";', $SQL_DBConn) or $error_code = 7;
    } else {
        echo 'Nicht Eingelogt';
        exit;
    }

?>
