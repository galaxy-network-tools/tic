<?PHP
/*
    print("typ           ".$_GET['typ']           ."<br>" );
    print("ursprungsgala ".$_GET['ursprungsgala'] ."<br>" );
    print("ursprungssek  ".$_GET['ursprungssek']  ."<br>" );
    print("ursprungsname ".$_GET['ursprungsname'] ."<br>" );
    print("zielgala      ".$_GET['zielgala']      ."<br>" );
    print("zielsek       ".$_GET['zielsek']       ."<br>" );
    print("zielname      ".$_GET['zielname']      ."<br>" );
    print("eta           ".$_GET['eta']           ."<br>" );
    print("rueckkehr     ".$_GET['rueckkehr']     ."<br>" );
    print("flotte        ".$_GET['flotte']        ."<br>" );
    print("flugzeit      ".$_GET['flugzeit']      ."<br>" );
*/
    include('./accdata.php');

    // Verbindung zur Datenbank aufbauen
    $SQL_DBConn = mysql_connect($db_info['host'], $db_info['user'], $db_info['password']) or die('mysql con failed!');
    mysql_select_db($db_info['dbname'], $SQL_DBConn) or die('db selection failed!');

    $SQL_Result0 = tic_mysql_query('SELECT name, value FROM `gn4vars` WHERE name="botpw";', $SQL_DBConn);



    if (!isset($_GET['passwort'])) $_GET['passwort'] = '';
    $SQL_Result0 = tic_mysql_query('SELECT name, value FROM `gn4vars` WHERE name="botpw" AND value="'.$_GET['passwort'].'";', $SQL_DBConn);

    if (mysql_num_rows($SQL_Result0) != 1) die('Incorrect password');

    $ticid=mysql_result($SQL_Result0,0,'ticid');



    // Variablen laden
    include('./vars.php');

    if ( !isset( $_GET['typ']           ) ) die("parmerror 1<br>");
    if ( !isset( $_GET['ursprungsgala'] ) ) die("parmerror 2<br>");
    if ( !isset( $_GET['ursprungssek']  ) ) die("parmerror 3<br>");
    if ( !isset( $_GET['ursprungsname'] ) ) die("parmerror 4<br>");
    if ( !isset( $_GET['zielgala']      ) ) die("parmerror 5<br>");
    if ( !isset( $_GET['zielsek']       ) ) die("parmerror 6<br>");
    if ( !isset( $_GET['zielname']      ) ) die("parmerror 7<br>");
    if ( !isset( $_GET['eta']           ) ) die("parmerror 8<br>");
    if ( !isset( $_GET['rueckkehr']     ) ) die("parmerror 9<br>");
    if ( !isset( $_GET['flotte']        ) ) die("parmerror a<br>");
    if ( !isset( $_GET['flugzeit']) ) die("parmerror b<br>");

    $d_typ=99;
    $reverse=0;
    if ( strcmp( $_GET['typ'], "greift_an" ) == 0 ) {
        $d_typ=1;
    } else if ( strcmp( $_GET['typ'], "verteidigt" ) == 0 ) {
        $d_typ=2;
    } else if ( strcmp( $_GET['typ'], "wird_angegriffen" ) == 0 ) {
        $d_typ=1;
        $reverse=1;
    } else if ( strcmp( $_GET['typ'], "wird_verteidigt" ) == 0 ) {
        $d_typ=2;
        $reverse=1;
    } else die("parmerror b");

    if ($_GET['rueckkehr'] == 1) $d_typ = 0;


    $setcmd = 'insert into `gn4flottenbewegungen` (modus, angreifer_galaxie, angreifer_planet, verteidiger_galaxie, verteidiger_planet, eta, flugzeit, flottennr) ';

    $_GET['eta'] = (int)($_GET['eta']/15);
    if ( $reverse == 0 ){
        // verteidiger=ursprung

        $setcmd = $setcmd . 'VALUES ( "'.$d_typ.'",';
        $setcmd = $setcmd . '"'.$_GET['ursprungsgala'].'",';
        $setcmd = $setcmd . '"'.$_GET['ursprungssek'].'",';
        $setcmd = $setcmd . '"'.$_GET['zielgala'].'",';
        $setcmd = $setcmd . '"'.$_GET['zielsek'].'",';
        $setcmd = $setcmd . '"'.$_GET['eta'].'",';
        $setcmd = $setcmd . '"'.$_GET['flugzeit'].'",';
        $setcmd = $setcmd . '"'.$_GET['flotte'].'")';
        $von_gala = $_GET['ursprungsgala'];
        $von_planet = $_GET['ursprungssek'];
    } else {
        // verteidiger=ziel

        $setcmd = $setcmd . 'VALUES ( "'.$d_typ.'",';
        $setcmd = $setcmd . '"'.$_GET['zielgala'].'",';
        $setcmd = $setcmd . '"'.$_GET['zielsek'] .'",';
        $setcmd = $setcmd . '"'.$_GET['ursprungsgala'].'",';
        $setcmd = $setcmd . '"'.$_GET['ursprungssek'].'",';
        $setcmd = $setcmd . '"'.$_GET['eta'].'",';
        $setcmd = $setcmd . '"'.$_GET['flugzeit'].'",';
        $setcmd = $setcmd . '"'.$_GET['flotte'].'")';
        $von_gala = $_GET['zielgala'];
        $von_planet = $_GET['zielsek'];
    }

    if ( $d_typ==1 and $reverse == 1 ){
        $SQL_Resultx = tic_mysql_query( 'select deff from `gn4accounts` where galaxie="'.$_GET['zielgala'].'" and planet="'.$_GET['zielsek'].'" and ticid="'.$ticid.'"', $SQL_DBConn) or die('<br>mist - n db-error!!!');
        if (  mysql_num_rows($SQL_Resultx) > 0 ) {
            $SQL_Resultx = tic_mysql_query( 'select id from `gn4flottenbewegungen` where verteidiger_galaxie="'.$_GET['zielgala'].'" and verteidiger_planet="'.$_GET['zielsek'].'" and flottennr="'.$_GET['flotte'].'" and ticid="'.$ticid.'"', $SQL_DBConn) or die('<br>mist - n db-error!!!');
            if (  mysql_num_rows($SQL_Resultx) == 0 ) {
                /* noch kein eintrag drin */
                tic_mysql_query('UPDATE `gn4accounts` SET deff=1 WHERE where galaxie="'.$_GET['zielgala'].'" and planet="'.$_GET['zielsek'].'" and ticid="'.$ticid.'"', $SQL_DBConn) or die('<br>mist - n db-error!!!');
            }
        }

    }

    $delcommand = 'DELETE FROM `gn4flottenbewegungen` WHERE flottennr='.$_GET['flotte'].' and angreifer_galaxie='.$von_gala.' and angreifer_planet='.$von_planet.' and ticid='.$ticid.'';
    $SQL_Result = tic_mysql_query( $delcommand, $SQL_DBConn) or die('<br>mist - n db-error!!!');
    $SQL_Result = tic_mysql_query( $setcmd, $SQL_DBConn)or die("dberror d<br>");
?>
</body>
</html>
