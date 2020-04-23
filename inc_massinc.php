

<?php

    $sql = 'SELECT value from `gn4vars` where name="incfreigabe"  ';
    $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
    $allowed = mysql_result($SQL_Result, 0, 'value' );
    $isadmin = 0;
    $isscanner=0;
    $sql = 'SELECT name, value from `gn4vars` where value="'.$Benutzer['galaxie'].':'.$Benutzer['planet'].'"  ';
    $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
	$count = mysql_num_rows($SQL_Result);
	if($count=='1'){
	$admin = mysql_result($SQL_Result, 'name');
    if ( $admin == "admin") {
        $allowed = 1;
        $isadmin = 1;
        }
    if ( $admin == "assi") {
         $allowed = 1;
         $isadmin = 1;
         }
    if ( $admin == "scanner") {
         $isscanner=1;
         $allowed = 1;
         }
}
	if($allowed=='1') $stat = "Für user ist der Att-Planer Freigeben.";
	else $stat = "Der Att-Planer ist nicht für User Freigeschalten."

?>
<center>
  <table border="0" cellspacing="3" cellpadding="0">
    <tr>
      <td><font size="4">Att-Planer-Koordinator</font></td>
    </tr>
  </table>
  <br>
  <?php

    if ( $allowed == 0 ) {
        echo '<table width="100%" border="0" cellspacing="3" cellpadding="0">';
        echo '<tr>';
        echo '<td bgcolor="#ddf3ff">';
        echo '<div align="center"><b><font color="#990000" size="-1">Sorry';
        echo '- Im Moment ist der Attplaner nicht freigeschaltet für User.</font></b></div>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        return;
    }
    if ( $isadmin <> 0 or $isscanner <> 0 ) {
        echo '<font color="#800000"><b>'.$stat.'</b></font><br>';
    }
  ?>
  <br>
  <table width="100%" border="0" cellspacing="3" cellpadding="0">
    <tr>
      <td bgcolor="#ddf3ff"><b><font color="#000000" size="-1">Achtung! Jeder
        Spieler sollte nur maximal zwei Vormerkungen verwenden (mehr als 2 Flotten
        hat niemand) - eine eingetragene Vormerkung kann nur durch den Organisator
        bzw. seinen Vertreter zur&uuml;ckgenommen werden.</font></b></td>
    </tr>
  </table>
  <br>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#666666">
    <tr>
      <td>
      <?php
        $sql2 = 'select name, value from `gn4vars` where name="galainc" and value!="|"  order by id asc ';
	    $SQL_Result3 = tic_mysql_query( $sql2, $SQL_DBConn );
	    $count2 = mysql_num_rows( $SQL_Result3 ) or $count=0;

	    for ( $x=0; $x<$count2; $x++ ){
            $sql2 = 'select name, value from `gn4vars` where name="galainc" and value!="|"  order by id asc ';
	        $SQL_Result3 = tic_mysql_query( $sql2, $SQL_DBConn );
            $trennen1 = mysql_result( $SQL_Result3, $x, 'value' );
            $trennen = explode("|", $trennen1 );
            $vgala = $trennen[0];
            $beschreibung = $trennen[1];

        $sql = 'SELECT planet, bestaetigt, vorgemerkt, frei FROM `gn4incplanets` where gala="'.$vgala.'"  ORDER BY planet ';
        $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
        $count = mysql_num_rows( $SQL_Result ) or $count=0;

        for ( $i=0; $i<$count; $i++ ) {

            $plan         = mysql_result( $SQL_Result, $i, 'planet' );
            $bestaetigt   = mysql_result( $SQL_Result, $i, 'bestaetigt' );
            $vorgemerkt   = mysql_result( $SQL_Result, $i, 'vorgemerkt' );
            $frei         = mysql_result( $SQL_Result, $i, 'frei' );

            if ( $plan=='' )
                continue;

      ?>
        <table width="100%" border="0" cellspacing="3" cellpadding="0">
          <tr>
            <td bgcolor="#333333" width="17%"><font color="#FFFFFF"><b>
              <?php echo $vgala.':'.$plan; ?>
              </b></font></td>
            <?php
                if ( $i == 0 ) {
                    echo '<font size="-1" color="#ffffff"><b>Beschreibung:'.$beschreibung.'</b></font>';
                }
            ?>
            <td width="71%" rowspan="2">
              <table width="100%" border="0" cellspacing="5" cellpadding="0" bgcolor="#CCCCCC">
                <tr>
                  <td colspan="3" bgcolor="#EEEEEE"><font size="-1">
                    <?php
                    $sql = 'SELECT pts, me, ke FROM `gn4scans` WHERE rg="'.$vgala.'" and rp="'.$plan.'"  and type=0';
                    $SQL_Result2 = tic_mysql_query( $sql, $SQL_DBConn );
                    if ( mysql_num_rows($SQL_Result2) == 0 ) {
                        $pts = 0;
                        $me  = 0;
                        $ke  = 0;
                    } else {
                        $pts = mysql_result( $SQL_Result2, 0, 'pts' );
                        $me  = mysql_result( $SQL_Result2, 0, 'me' );
                        $ke  = mysql_result( $SQL_Result2, 0, 'ke' );
                    }
                    printf( "%s Pnkts &nbsp;-&nbsp; %d MEx &nbsp;-&nbsp; %d KEx\n", number_format($pts, 0, ',', '.'), $me, $ke );

                    $sql = 'SELECT glo,glr,gmr,gsr,ga FROM `gn4scans` WHERE rg="'.$vgala.'"  and rp="'.$plan.'" and type=3';
                    $SQL_Result2 = tic_mysql_query( $sql, $SQL_DBConn );
                    if ( mysql_num_rows($SQL_Result2) == 0 ) {
                        $lo     = 0;
                        $ro     = 0;
                        $mr     = 0;
                        $sr     = 0;
                        $aj     = 0;
                    } else {
                        $lo     = mysql_result($SQL_Result2, 0, 'glo' );
                        $ro     = mysql_result($SQL_Result2, 0, 'glr' );
                        $mr     = mysql_result($SQL_Result2, 0, 'gmr' );
                        $sr     = mysql_result($SQL_Result2, 0, 'gsr' );
                        $aj     = mysql_result($SQL_Result2, 0, 'ga' );
                    }
                    printf( " &nbsp;-&nbsp; %d LO &nbsp;-&nbsp; %d LR &nbsp;-&nbsp; %d MR &nbsp;-&nbsp; %d SR &nbsp;-&nbsp; %d AJ\n", $lo , $ro , $mr , $sr , $aj );

                  ?>
                    </font></td>
                </tr>
                <tr>
                  <td colspan="3" bgcolor="#EEEEEE"><font size="-1">
                    <?php
                    $sql = 'SELECT sfj ,sfb ,sff ,sfz ,sfkr ,sfsa ,sft ,sfka ,sfsu FROM `gn4scans` WHERE rg="'.$vgala.'"  and rp="'.$plan.'" and type=1';
                    $SQL_Result2 = tic_mysql_query( $sql, $SQL_DBConn );
                    if ( mysql_num_rows($SQL_Result2) == 0 ) {
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

                        $ja     = mysql_result($SQL_Result2, 0, 'sfj' );
                        $bo     = mysql_result($SQL_Result2, 0, 'sfb' );
                        $fr     = mysql_result($SQL_Result2, 0, 'sff' );
                        $ze     = mysql_result($SQL_Result2, 0, 'sfz' );
                        $kr     = mysql_result($SQL_Result2, 0, 'sfkr' );
                        $sl     = mysql_result($SQL_Result2, 0, 'sfsa' );
                        $tr     = mysql_result($SQL_Result2, 0, 'sft' );
                        $ka     = mysql_result($SQL_Result2, 0, 'sfka' );
                        $ca     = mysql_result($SQL_Result2, 0, 'sfsu' );
                    }
                    printf( "%d J&auml; &nbsp;-&nbsp; %d Bo &nbsp;-&nbsp; %d Fr &nbsp;-&nbsp; %d Ze &nbsp;-&nbsp; %d Kr &nbsp;-&nbsp; %d Schl &nbsp;-&nbsp; %d Tr &nbsp;-&nbsp; %d Schutz &nbsp;-&nbsp; %d Kaper\n", $ja ,$bo ,$fr ,$ze ,$kr ,$sl ,$tr ,$ca, $ka );
                  ?>
                    </font></td>
                </tr>
                <tr>
                  <td bgcolor="#CCFFCC" width="42%"><font size="-1">
                    <?php
                  if ( $bestaetigt == '' ) {
                      echo 'Akzeptiert:';
                  } else {
                        echo '<a href="javascript:NeuFenster( \'./display_fleets.php?fleet='.$bestaetigt.'\' )" >Akzeptiert:</a>';
                  }

                  echo $bestaetigt;
                  ?>
                    </font></td>
                  <td bgcolor="#CCFFFF" colspan="2"><font size="-1"> Vorgemerkt:
                    <?php
                    echo $vorgemerkt;
                  ?>
                    </font></td>
                </tr>
                <?php
                    if ( $isadmin == 1 ) {
                        echo '<tr>';
                        echo '<td width="42%">';
                        echo '<div align="right">';
                        echo '<form name="formremove" method="post" action="./main.php">';
                        echo '<INPUT TYPE="hidden" NAME="gala" VALUE="'.$vgala.'">';
                        echo '<INPUT TYPE="hidden" NAME="plant" VALUE="'.$plan.'">';
                        echo '<INPUT TYPE="hidden" NAME="playergala" VALUE="'.$Benutzer['galaxie'].'">';
                        echo '<INPUT TYPE="hidden" NAME="playerplanet" VALUE="'.$Benutzer['planet'].'">';
                        echo '<INPUT TYPE="hidden" NAME="modul" VALUE="massinc">';
                        echo '<INPUT TYPE="hidden" NAME="action" VALUE="incfunction">';
                        echo '<INPUT TYPE="hidden" NAME="subaction" VALUE="removebestaetigt">';
                        echo '<input type="submit" name="Abschicken232" value="Best&auml;tigung entfernen">';
                        echo '</form>';
                        echo '</div>';
                        echo '</td>';
                        echo '<td width="29%">';
                        echo '<div align="left">';
                        echo '<form name="formaddvm" method="post" action="./main.php">';
                        echo '<INPUT TYPE="hidden" NAME="gala" VALUE="'.$vgala.'">';
                        echo '<INPUT TYPE="hidden" NAME="plant" VALUE="'.$plan.'">';
                        echo '<INPUT TYPE="hidden" NAME="modul" VALUE="massinc">';
                        echo '<INPUT TYPE="hidden" NAME="action" VALUE="incfunction">';
                        echo '<INPUT TYPE="hidden" NAME="subaction" VALUE="addvormerkung">';
                        echo '<input type="submit" name="Abschicken2322" value="akzeptieren">';
                        echo '</form>';
                        echo '</div>';
                        echo '</td>';
                        echo '<td width="29%">';
                        echo '<div align="right">';
                        echo '<form name="formdelvm" method="post" action="./main.php">';
                        echo '<INPUT TYPE="hidden" NAME="gala" VALUE="'.$vgala.'">';
                        echo '<INPUT TYPE="hidden" NAME="plant" VALUE="'.$plan.'">';
                        echo '<INPUT TYPE="hidden" NAME="playergala" VALUE="'.$Benutzer['galaxie'].'">';
                        echo '<INPUT TYPE="hidden" NAME="playerplanet" VALUE="'.$Benutzer['planet'].'">';
                        echo '<INPUT TYPE="hidden" NAME="modul" VALUE="massinc">';
                        echo '<INPUT TYPE="hidden" NAME="action" VALUE="incfunction">';
                        echo '<INPUT TYPE="hidden" NAME="subaction" VALUE="removevormerkung">';
                        echo '<input type="submit" name="Abschicken2323" value="l&ouml;schen">';
                        echo '</form>';
                        echo '</div>';
                        echo '</td>';
                        echo '</tr>';
                    }
                  ?>
              </table>
            </td>
            <?php
                if ( $frei == 1 ) {
                    echo '<td width="12%" bgcolor="#999999" rowspan="2">&nbsp;';

                    echo '<form name="forma" method="post" action="./main.php">';
                    echo '<INPUT TYPE="hidden" NAME="gala" VALUE="'.$vgala.'">';
                    echo '<INPUT TYPE="hidden" NAME="plant" VALUE="'.$plan.'">';
                    echo '<INPUT TYPE="hidden" NAME="playergala" VALUE="'.$Benutzer['galaxie'].'">';
                    echo '<INPUT TYPE="hidden" NAME="playerplanet" VALUE="'.$Benutzer['planet'].'">';
                    echo '<INPUT TYPE="hidden" NAME="modul" VALUE="massinc">';
                    echo '<INPUT TYPE="hidden" NAME="fleet" VALUE="1">';
                    echo '<INPUT TYPE="hidden" NAME="action" VALUE="incfunction">';
                    echo '<INPUT TYPE="hidden" NAME="subaction" VALUE="incadd">';
                    echo '<input type="submit" name="f1add" value="Flotte1 vormerken">';
                    echo '</form>';

                    echo '<form name="formb" method="post" action="./main.php">';
                    echo '<INPUT TYPE="hidden" NAME="gala" VALUE="'.$vgala.'">';
                    echo '<INPUT TYPE="hidden" NAME="plant" VALUE="'.$plan.'">';
                    echo '<INPUT TYPE="hidden" NAME="playergala" VALUE="'.$Benutzer['galaxie'].'">';
                    echo '<INPUT TYPE="hidden" NAME="playerplanet" VALUE="'.$Benutzer['planet'].'">';
                    echo '<INPUT TYPE="hidden" NAME="modul" VALUE="massinc">';
                    echo '<INPUT TYPE="hidden" NAME="fleet" VALUE="2">';
                    echo '<INPUT TYPE="hidden" NAME="action" VALUE="incfunction">';
                    echo '<INPUT TYPE="hidden" NAME="subaction" VALUE="incadd">';
                    echo '<input type="submit" name="f2add" value="Flotte2 vormerken">';
                    echo '</form>';
                } else {
                    echo '<td width="12%" bgcolor="#999999" rowspan="2">';
                    echo '&nbsp;';
                }
              ?>
              <form name="form1" method="post" action="./main.php">
                <INPUT TYPE="hidden" NAME="gala" VALUE=<?php echo '"'.$vgala.'"';?>>
                <INPUT TYPE="hidden" NAME="plant" VALUE=<?php echo '"'.$plan.'"';?>>
                <INPUT TYPE="hidden" NAME="playergala" VALUE=<?php echo '"'.$Benutzer['galaxie'].'"';?>>
                <INPUT TYPE="hidden" NAME="playerplanet" VALUE=<?php echo '"'.$Benutzer['planet'].'"';?>>
                <INPUT TYPE="hidden" NAME="modul" VALUE="massinc">
                <INPUT TYPE="hidden" NAME="action" VALUE="incfunction">
                <INPUT TYPE="hidden" NAME="subaction" VALUE="removespecial">
                <INPUT TYPE="SUBMIT" NAME="fxrem" VALUE="Vormerkung(en) l&ouml;schen">
              </form>
            </td>
          </tr>
          <tr>
            <td bgcolor="#333333" width="17%">
              <?php
                if ( $isadmin == 1 ) {
                    echo '<form name="form2" method="post" action="./main.php">';
                    echo '<INPUT TYPE="hidden" NAME="gala" VALUE="'.$vgala.'">';
                    echo '<INPUT TYPE="hidden" NAME="plant" VALUE="'.$plan.'">';
                    echo '<INPUT TYPE="hidden" NAME="modul" VALUE="massinc">';
                    echo '<INPUT TYPE="hidden" NAME="action" VALUE="incfunction">';
                    echo '<INPUT TYPE="hidden" NAME="subaction" VALUE="close">';
                    echo '<select name="offen" size="1">';
                    $freisel='';
                    $sperrsel='';
                    if ( $frei == 1 ) $freisel=' SELECTED';
                    else $spersel=' SELECTED';

                    echo '<option value="0"'.$spersel.'>Geschlossen</option>';
                    echo '<option value="1"'.$freisel.'>Frei</option>';
                    echo '</select>';
                    echo '<br>';
                    echo '<input type="submit" name="Abschicken" value="&auml;ndern">';
                    echo '</form>';
                } else {
                    echo '&nbsp;';
                }
              ?>
              <?php
                if ( $isadmin == 1 ) {
                    echo '<form name="form1" method="post" action="./main.php">';
                    echo '<INPUT TYPE="hidden" NAME="gala" VALUE="'.$vgala.'">';
                    echo '<INPUT TYPE="hidden" NAME="plant" VALUE="'.$plan.'">';
                    echo '<INPUT TYPE="hidden" NAME="modul" VALUE="massinc">';
                    echo '<INPUT TYPE="hidden" NAME="action" VALUE="incfunction">';
                    echo '<INPUT TYPE="hidden" NAME="subaction" VALUE="removeplanet">';
                    echo '<INPUT TYPE="SUBMIT" NAME="fxrem" VALUE="Planet l&ouml;schen">';
                    echo '</form>';
}
?>
            </td>
          </tr>
        </table>
        <br>
      <?php
      }
        }
      ?>
      </td>
    </tr>

  </table>
  <br>
  <table width="100%" border="0" cellspacing="3" cellpadding="0" bgcolor="#999999">
    <tr>
      <td bgcolor="#ddf3ff">
        <p><font size="-1">Abk&uuml;rzungen:<br>
          Pkts=Punkte, MEx=Metallexen, KEx=Kristallexen, LO=Leichtes Orbitalgesch&uuml;tz;
          LR=Leichtes Raumgesch&uuml;tz, MR=Mittleres Rauimgesch&uuml;tz, SR=Schweres
          Raumgesch&uuml;tz, AJ=Abfangj&auml;ger<br>
          J&auml;=J&auml;ger, Bo=Bomber, Fr=Fregatte, Ze=Zerst&ouml;rer, Kr=Kreuzer,
          Schl=Schlachter, Tr=Tr&auml;ger, Schutz=Schutzschiffe, Kaper=Kaperschiffe<br>
          </font></p>
      </td>
    </tr>
  </table>
</center>
