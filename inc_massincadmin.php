<center>
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><font size="+2">Massinc-Planer - Admin Seite</font></td>
  </tr>
</table>
<?php
    /* admintyp 1=organisator  */

    $allowed = 0;

    $user_galaxy[0] = '0';
    $user_planet[0] = '0';
    $user_type[0]   = '0';
    $user_galaxy[1] = '0';
    $user_planet[1] = '0';
    $user_type[1]   = '0';
    $user_galaxy[2] = '0';
    $user_planet[2] = '0';
    $user_type[2]   = '0';
    $user_galaxy[3] = '0';
    $user_planet[3] = '0';
    $user_type[3]   = '0';

    $sql = 'SELECT name, value from `gn4vars` WHERE name="admin" or name="assi" ';
    $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
    $count = mysql_num_rows($SQL_Result);
    for ( $i=0; $i<$count; $i++ ) {
        $user_value[$i] = mysql_result($SQL_Result, $i, 'value' );
        $user_trennen = explode(":", $user_value[$i]);
        $user_galaxy[$i] = $user_trennen[0];
        $user_planet[$i] = $user_trennen[1];
        $user_type[$i]   = mysql_result($SQL_Result, $i, 'name' );

        if ( $user_galaxy[$i] == $Benutzer['galaxie'] and $user_planet[$i] == $Benutzer['planet'] ) {
            $allowed = 1;
            // kein break!
        }
    }

    if ( $Benutzer['rang'] < $Rang_Admiral and $allowed==0 ) {
        echo '<p><font size="-1" color="#800000"><b>Sorry - Zugang verweigert</b></font></p>';
        return;
    }

?>
  <br>
  <table border="0" cellspacing="3" cellpadding="0" width="600">
    <tr>
      <td bgcolor="#333333" width="116" valign="top"><b><font size="-1" color="#FFFFFF">Administratoren</font></b></td>
      <td width="347" bgcolor="#eeeeee">
        <form name="form1" method="post" action="./main.php">
          <INPUT TYPE="hidden" NAME="modul" VALUE="massincadmin">
          <INPUT TYPE="hidden" NAME="action" VALUE="incadmin">
          <INPUT TYPE="hidden" NAME="subaction" VALUE="adminchange">
          <table border="0" cellspacing="0" cellpadding="2" width="292" align="center">
            <tr>
              <td width="92"><font size="-1">Organisator</font></td>
              <td width="192"> <font size="-1">
                <input type="text" name="organisator" <?php echo 'value="'.$user_galaxy[0].':'.$user_planet[0].'"'; ?> size="10">
                (Gala:Sektor)</font></td>
            </tr>
            <tr>
              <td width="92"><font size="-1">Vertreter 1</font></td>
              <td width="192"> <font size="-1">
                <input type="text" name="vertreter1" <?php echo 'value="'.$user_galaxy[1].':'.$user_planet[1].'"'; ?> size="10">
                (Gala:Sektor)</font></td>
            </tr>
            <tr>
              <td width="92"><font size="-1">Vertreter 2</font></td>
              <td width="192"> <font size="-1">
                <input type="text" name="vertreter2" <?php echo 'value="'.$user_galaxy[2].':'.$user_planet[2].'"'; ?> size="10">
                (Gala:Sektor)</font></td>
            </tr>
            <tr>
              <td width="92"><font size="-1">Vertreter 3</font></td>
              <td width="192"> <font size="-1">
                <input type="text" name="vertreter3" <?php echo 'value="'.$user_galaxy[3].':'.$user_planet[3].'"'; ?> size="10">
                (Gala:Sektor)</font></td>
            </tr>
            <tr>
              <td width="92">&nbsp;</td>
              <td width="192" align="right">
                <input type="submit" name="Abschicken" value="Eintragen">
              </td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
    <tr>
      <td width="116">&nbsp;</td>
      <td width="347" bgcolor="#ddf3ff"><font size="-1">Beschreibung: Nur der
        Organisator hat Zugriff auf die Scanner und Gala-Koords der Ziel-Galaxien.
        Ausserdem kann er die Benutzung der Mass-Inc-Seiten freischalten und dort
        weitere Aktionen durchf&uuml;hren.</font></td>
    </tr>

    <?php
    if ( $allowed==0 ) {
        echo '</table>';
        return; // abbruch !!!
    }

    // scanner query ...............................................

    $scannercount = 5;
    for ( $i=0; $i<$scannercount; $i++ ) {
        $user_galaxy[$i] = '0';
        $user_planet[$i] = '0';
        $user_type[$i]   = '0';
    }
    $sql = 'SELECT name, value from `gn4vars` WHERE name="scanner" ';
    $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
    $count = mysql_num_rows($SQL_Result) or $count=0;
    for ( $i=0; $i<$count; $i++ ) {
        $user_value[$i] = mysql_result($SQL_Result, $i, 'value' );
        $user_trennen = explode(":", $user_value[$i]);
        $user_galaxy[$i] = $user_trennen[0];
        $user_planet[$i] = $user_trennen[1];
        $user_type[$i]   = mysql_result($SQL_Result, $i, 'name' );
    }

    ?>

    <tr>
      <td width="116">&nbsp;</td>
      <td width="347">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#333333" width="116" valign="top"><font color="#FFFFFF"><b><font size="-1">Scanner</font></b></font></td>
      <td width="347" bgcolor="#eeeeee">
        <form name="form3" method="post" action="./main.php">
          <INPUT TYPE="hidden" NAME="modul" VALUE="massincadmin">
          <INPUT TYPE="hidden" NAME="action" VALUE="incadmin">
          <INPUT TYPE="hidden" NAME="subaction" VALUE="scannerchange">
          <table border="0" cellspacing="0" cellpadding="2" align="center" width="292">
            <tr>
              <td width="93"><font size="-1">Scanner 1</font></td>
              <td width="191"><font size="-1">
                <input type="text" name="scanner1" <?php echo 'value="'.$user_galaxy[0].':'.$user_planet[0].'"'; ?> size="10">
                (Gala:Sektor)</font></td>
            </tr>
            <tr>
              <td width="93"><font size="-1">Scanner 2</font></td>
              <td width="191"><font size="-1">
                <input type="text" name="scanner2" <?php echo 'value="'.$user_galaxy[1].':'.$user_planet[1].'"'; ?> size="10">
                (Gala:Sektor)</font></td>
            </tr>
            <tr>
              <td width="93"><font size="-1">Scanner 3</font></td>
              <td width="191"><font size="-1">
                <input type="text" name="scanner3" <?php echo 'value="'.$user_galaxy[2].':'.$user_planet[2].'"'; ?> size="10">
                (Gala:Sektor)</font></td>
            </tr>
            <tr>
              <td width="93"><font size="-1">Scanner 4</font></td>
              <td width="191"><font size="-1">
                <input type="text" name="scanner4" <?php echo 'value="'.$user_galaxy[3].':'.$user_planet[3].'"'; ?> size="10">
                (Gala:Sektor)</font></td>
            </tr>
            <tr>
              <td width="93"><font size="-1">Scanner 5</font></td>
              <td width="191"><font size="-1">
                <input type="text" name="scanner5" <?php echo 'value="'.$user_galaxy[4].':'.$user_planet[4].'"'; ?> size="10">
                (Gala:Sektor)</font></td>
            </tr>
            <tr>
              <td width="93">&nbsp;</td>
              <td width="191" align="right">
                <input type="submit" name="Abschicken3" value="Eintragen">
              </td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
    <tr>
      <td width="116" valign="top">&nbsp;</td>
      <td width="347" bgcolor="#ddf3ff"><font size="-1">Auswahl der Scanner. Diese
        k&ouml;nnen die Mass-Inc-Seiten einsehen, ohne jedoch sich vormerken zu
        k&ouml;nnen. Damit k&ouml;nnen sie dort ihre Scans &uuml;berpr&uuml;fen.</font></td>
    </tr>
  </table>
  <br>
  <table border="0" cellspacing="3" width="750">
    <tr>
      <td bgcolor="#333333" width="116" valign="top"><b><font size="-1" color="#FFFFFF">Ziel-Galxien</font></b></td>
      <td width="347" bgcolor="#eeeeee">
        <table border="0" cellspacing="2" width="100%" align="center">
          <tr bgcolor="#333333">
            <td colspan="2"><b><font size="-1" color="#FFFFFF">GalaKoords</font></b>
            </td>
          </tr>
          <tr>
            <td>
              <form name="form2" method="post" action="./main.php">
                <table width="100%" border="0" cellspacing="2">
                  <INPUT TYPE="hidden" NAME="modul" VALUE="massincadmin">
                  <INPUT TYPE="hidden" NAME="action" VALUE="incadmin">
                  <INPUT TYPE="hidden" NAME="subaction" VALUE="galachange">
                  <tr>
                    <?php

                    for ( $i=0; $i<10; $i++ ) {
                        $ziel[$i]= 0;
                        $zieldesc[$i]= '';
                    }

                    $sql = 'SELECT name, id, value from `gn4vars` where name="galainc" order by id ASC ';

                    $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
                    $count = mysql_num_rows($SQL_Result);

                    for ( $i=0; $i<10; $i++ ) {
                        echo '<tr>';
                        $nr = $i+1;
                        if ( $i<$count ) {
                            $zielkord[$i]= mysql_result($SQL_Result, $i, 'value' );
                            $zieltren= explode("|", $zielkord[$i]);
                            $ziel[$i]= $zieltren[0];
                            $zieldesc[$i]= $zieltren[1];
                        }
                        echo '<td width="80"> <font size="-1">'.$nr.'. Ziel </font></td>';
                        echo '<td width="150"><font size="-1"> Gala-Nummer:&nbsp;<input type="text" name="ziel'.$i.'" value="'.$ziel[$i].'" size="4">';
                        echo '</font></td>';
                        echo '<td><font size="-1"> Besonderheit:&nbsp<input type="text" name="zieldesc'.$i.'" value="'.$zieldesc[$i].'" size="30">';
                        echo '</font></td>';
                        echo '</tr>';
                    }

                    ?>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="right">
                      <input type="submit" name="Abschicken2" value="&Uuml;bernehmen">
                    </td>
                  </tr>
                </table>
              </form>
            </td>
          </tr>
          <tr>
            <td>&nbsp; </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="116" valign="top">&nbsp;</td>
      <td width="347" bgcolor="#ddf3ff"><font size="-1">Eingabe der Ziel Galas.
        Diese werden dann in der Mass-Inc Seite angezeigt. Um ein Galaxie zu l&ouml;schen,
        sollte der Eintrag geleert werden.</font></td>
    </tr>
  </table>
  <br>
  <table width="600" border="0" cellspacing="3" cellpadding="0">
    <tr>
      <td>
        <form name="form4" method="post" action="./main.php">
          <INPUT TYPE="hidden" NAME="modul" VALUE="massincadmin">
          <INPUT TYPE="hidden" NAME="action" VALUE="incadmin">
          <INPUT TYPE="hidden" NAME="subaction" VALUE="activechange">
          <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr bgcolor="#333333">
              <td colspan="3"><font color="#FFFFFF"><b><font size="-1">Benutzerfreigabe</font></b></font></td>
            </tr>
            <tr bgcolor="#eeeeee">
            <?php
                $sql = 'SELECT value from `gn4vars` where name="incfreigabe" ';
                $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
                $count = mysql_num_rows($SQL_Result) or $count=0;
                $selected='';
                $sel=0;
                if ( $count >0 ) {
                    $sel = mysql_result($SQL_Result, 0, 'value' );
                    if ( $sel==1 ){
                        $selected = 'checked';
                    }
                }

            ?>
              <td width="5%" height="38"> <font size="-1">
                <input type="checkbox" name="check" <?php echo $selected; ?> >
                </font></td>
              <td width="36%" height="38"><font size="-1">Freigegeben zur Benutzung
                </font></td>
              <td width="59%" height="38"> <font size="-1">
                <input type="submit" name="Abschicken4" value="&Uuml;bernehmen">
                </font></td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
    <tr>
      <td bgcolor="#ddf3ff"><font size="-1" >Durch die Freischaltung k&ouml;nnen
        die Benutzer die Seite einsehen und ihre Vormerkungen eintragen.<br>
        </font><font color="#ff0000">
        <b>Achtung: Beim Einschalten werden alle bestehenden Reservierungen
        gel&ouml;scht und die Daten neu angelegt!!!</b></font></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</center>
