<?php
    include('./accdata.php');
    include('./functions.php');
    include('./globalvars.php');


    // Verbindung zur Datenbank aufbauen
    $SQL_DBConn = mysql_connect($db_info['host'], $db_info['user'], $db_info['password']) or $error_code = 1;
    mysql_select_db($db_info['dbname'], $SQL_DBConn) or $error_code = 2;

    $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE id="'.$_SESSION['userid'].'";', $SQL_DBConn) or $error_code = 4;
    if (mysql_num_rows($SQL_Result) != 0) {
            // Nameinfos setzen
            $logged_out = 0;
            $Benutzer['id'] = mysql_result($SQL_Result, 0, 'id');
            $Benutzer['ticid'] = mysql_result($SQL_Result, 0, 'ticid');
            $Benutzer['name'] = mysql_result($SQL_Result, 0, 'name');
            $Benutzer['galaxie'] = mysql_result($SQL_Result, 0, 'galaxie');
            $Benutzer['planet'] = mysql_result($SQL_Result, 0, 'planet');
            $Benutzer['rang'] = mysql_result($SQL_Result, 0, 'rang');
            $Benutzer['allianz'] = mysql_result($SQL_Result, 0, 'allianz');
            $Benutzer['scantyp'] = mysql_result($SQL_Result, 0, 'scantyp');
            $Benutzer['svs'] = mysql_result($SQL_Result, 0, 'svs');
            $Benutzer['sbs'] = mysql_result($SQL_Result, 0, 'sbs');
            $Benutzer['umod'] = mysql_result($SQL_Result, 0, 'umod');
            $Benutzer['taktiksort'] = mysql_result($SQL_Result, 0, 'taktiksort');
    } else {
        echo '<FONT COLOR=#FF0000>Falscher Benutzername</FONT><BR><B>T.I.C. | Tactical Information Center</B>';
        exit;
    }
?>
<html>
<head>
<link rel="stylesheet" href="tic.css" type="text/css">
</head>
<body>
<center>

  <table border="1" cellspacing="1" cellpadding="0">
  <tr>
      <td><font size="+2">Allianz-Flotten&uuml;bersicht</font></td>
  </tr>
</table>


  <br>
  <table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="#666666">
    <tr bgcolor="#ffffff">
      <td width="37"><font size="-1"><b><font color="#000000">Gala</font></b></font></td>
      <td width="57"><font size="-1"><b><font color="#000000">Name</font></b></font></td>
      <td colspan="5"><font size="-1"><b><font color="#000000">Deffensiv</font></b></font></td>
      <td colspan="9"><font size="-1"><b><font color="#000000">Offensiv</font></b></font></td>
    </tr>
    <tr bgcolor="#ffffff">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><font size="-1"><b><font color="#000000">LOs</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">LR</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">MR</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">SR</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">AJs</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">J&auml;ger</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">Bomber</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">Fregs</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">Zerries</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">Kreuzer</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">Schlachter</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">Tr&auml;ger</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">Kapers</font></b></font></td>
      <td><font size="-1"><b><font color="#000000">Schutzies</font></b></font></td>
    </tr>
    <?php
    $SQL_Result2 = tic_mysql_query('SELECT id, name, galaxie, planet FROM `gn4accounts` WHERE allianz="'.$Benutzer['allianz'].'" and ticid="'.$Benutzer['ticid'].'" order by galaxie, planet', $SQL_DBConn);
    for ( $i=0; $i<mysql_num_rows($SQL_Result2); $i++ ) {
        $_POST['gala']   = mysql_result($SQL_Result2, $i, 'galaxie');
        $_POST['planet'] = mysql_result($SQL_Result2, $i, 'planet');
        $name   = mysql_result($SQL_Result2, $i, 'name');

        $SQL_Result = tic_mysql_query('SELECT sfj ,sfb ,sff ,sfz ,sfkr ,sfsa ,sft ,sfka ,sfsu FROM `gn4scans` WHERE rg="'.$_POST['gala'].'" and rp="'.$_POST['planet'].'" and ticid="'.$Benutzer['ticid'].'" and type=1', $SQL_DBConn);
        $SQL_Result3 = tic_mysql_query('SELECT glo,glr,gmr,gsr,ga FROM `gn4scans` WHERE rg="'.$_POST['gala'].'" and rp="'.$_POST['planet'].'" and ticid="'.$Benutzer['ticid'].'" and type=3', $SQL_DBConn);

        if ( mysql_num_rows($SQL_Result) == 0 ) {
            $ja     = " ";
            $bo     = " ";
            $fr     = " ";
            $ze     = " ";
            $kr     = " ";
            $sl     = " ";
            $tr     = " ";
            $ka     = " ";
            $ca     = " ";
        } else {

            $ja     = mysql_result($SQL_Result, 0, 'sfj' );
            $bo     = mysql_result($SQL_Result, 0, 'sfb' );
            $fr     = mysql_result($SQL_Result, 0, 'sff' );
            $ze     = mysql_result($SQL_Result, 0, 'sfz' );
            $kr     = mysql_result($SQL_Result, 0, 'sfkr' );
            $sl     = mysql_result($SQL_Result, 0, 'sfsa' );
            $tr     = mysql_result($SQL_Result, 0, 'sft' );
            $ka     = mysql_result($SQL_Result, 0, 'sfka' );
            $ca     = mysql_result($SQL_Result, 0, 'sfsu' );
        }
        if ( mysql_num_rows($SQL_Result3) == 0 ) {
            $lo     = " ";
            $ro     = " ";
            $mr     = " ";
            $sr     = " ";
            $aj     = " ";
        } else {
            $lo     = mysql_result($SQL_Result3, 0, 'glo' );
            $ro     = mysql_result($SQL_Result3, 0, 'glr' );
            $mr     = mysql_result($SQL_Result3, 0, 'gmr' );
            $sr     = mysql_result($SQL_Result3, 0, 'gsr' );
            $aj     = mysql_result($SQL_Result3, 0, 'ga' );
        }
        echo '<tr>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$_POST['gala'].':'.$_POST['planet'].'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$name.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$lo.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$ro.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$mr.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$sr.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$aj.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$ja.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$bo.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$fr.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$ze.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$kr.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$sl.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$tr.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$ka.'</font></td>';
        echo '<td bgcolor="#ffffff"><font size="-1">'.$ca.'</font></td>';
        echo '</tr>';
        }
    ?>
  </table>
  <br>
</center>
</body>
</html>
