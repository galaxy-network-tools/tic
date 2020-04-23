<?php
    if (isset($_POST['id'])) $id=$_POST['id'];
    if (isset($_GET['id'])) $id=$_GET['id'];
    if (!isset($id)) $id = 0;
    if ($id == 0) $error_code = 8;
    $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE id="'.$id.'";', $SQL_DBConn) or $error_code = 4;
    if (mysql_num_rows($SQL_Result) != 1) $error_code = 8;
    if ($error_code != 0)
        include('./inc_errors.php');
    else {
        $zeig_id = mysql_result($SQL_Result, 0, 'id');
        $zeig_galaxie = mysql_result($SQL_Result, 0, 'galaxie');
        $zeig_planet = mysql_result($SQL_Result, 0, 'planet');
        $zeig_name = gnuser($zeig_galaxie, $zeig_planet);
        $zeig_rang = mysql_result($SQL_Result, 0, 'rang');
        $zeig_allianz = mysql_result($SQL_Result, 0, 'allianz');
        $zeig_svs = mysql_result($SQL_Result, 0, 'svs');
        $zeig_sbs = mysql_result($SQL_Result, 0, 'sbs');
        $zeig_umod = mysql_result($SQL_Result, 0, 'umod');
        $zeig_scantyp = mysql_result($SQL_Result, 0, 'scantyp');
        $user_ll = mysql_result($SQL_Result, 0, 'lastlogin');
        $zeit_scaneintraege = mysql_result($SQL_Result, 0, 'scans');

        $SQL_Result2 = tic_mysql_query('SELECT me, ke FROM `gn4scans` WHERE rg="'.$zeig_galaxie.'" AND rp="'.$zeig_planet.'" and ticid="'.$Benutzer['ticid'].'" AND type="0";', $SQL_DBConn) or $error_code = 4;
        if (mysql_num_rows($SQL_Result2) == 1) {
            $zeig_exen_m = mysql_result($SQL_Result2, 0, 'me');
            $zeig_exen_k = mysql_result($SQL_Result2, 0, 'ke');
        } else {
            $zeig_exen_m = 0;
            $zeig_exen_k = 0;
        }
        $SQL_Result2 = tic_mysql_query('SELECT COUNT(*) FROM `gn4forum` WHERE autorid="'.$zeig_id.'" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
        $SQL_Row2 = mysql_fetch_row($SQL_Result2);
        $zeig_posts = $SQL_Row2[0];

        $flottennr[0] = '<B><I>Unbekannt</I></B>';
        $flottennr[1] = '<B>1. Flotte</B>';
        $flottennr[2] = '<B>2. Flotte</B>';

?>
<CENTER>
  <TABLE WIDTH=70%>
    <TR>
      <TD BGCOLOR=#333333><font color="#FFFFFF" size="-1"><B>
        <?=$zeig_galaxie?>
        :
        <?=$zeig_planet?>
        [
        <?=$AllianzTag[$zeig_allianz]?>
        ]
        <?=$zeig_name?>
        (
        <?=$RangName[$zeig_rang]?>
        )</B></font></TD>
    </TR>
    <TR>
      <TD><font size="-1"><BR>
        </font></TD>
    </TR>
    <TR>
      <TD>
<?
// Anzeige des Lastlogins in der Headline
       echo '<P CLASS="dunkel"><B><font size="-1">Spielerinformationen: (last login '.($user_ll?date("d.m.Y H:i", $user_ll):"<i>nie</i>").')</font></B></P>';
?>
      </TD>
    </TR>
    <TR>
      <TD>
        <P CLASS="hell">
        <TABLE>
          <TR>
            <TD><font size="-1">Scantyp:</font></TD>
            <TD><font size="-1">
              <?=$ScanTyp[$zeig_scantyp]?>
              </font></TD>
          </TR>
          <TR>
            <TD><font size="-1">Scanverstärker:</font></TD>
            <TD><font size="-1">
              <?=$zeig_svs?>
              </font></TD>
          </TR>
          <TR>
            <TD><font size="-1">Scanblocker:</font></TD>
            <TD><font size="-1">
              <?=$zeig_sbs?>
              </font></TD>
          </TR>
          <TR>
            <TD><font size="-1">Metall Extraktoren:</font></TD>
            <TD><font size="-1">
              <?=$zeig_exen_m?>
              </font></TD>
          </TR>
          <TR>
            <TD><font size="-1">Kristall Extraktoren:</font></TD>
            <TD><font size="-1">
              <?=$zeig_exen_k?>
              </font></TD>
          </TR>

          <TR>
            <TD><font size="-1">ScanEinträge im TIC:</font></TD>
            <TD><font size="-1"><?=$zeit_scaneintraege?>
              </font></TD>
          </TR>

          <TR>
            <TD><font size="-1">Foren Posts:</font></TD>
            <TD><font size="-1">
              <?=$zeig_posts?>
              </font></TD>
          </TR>
        </TABLE>
        <font size="-1"></P> </font></TD>
    </TR>
    <?
            if ($zeig_umod != '') {
                echo '<TR><TD><BR></TD></TR>';
                echo '<TR>';
                echo '  <TD BGCOLOR=#'.$htmlstyle['dunkel_blau'].'><font size="-1"><B>Urlaubs-Modus: '.$zeig_umod.'</B></font></TD>';
                echo '</TR>';
                if ($Benutzer['rang'] >= $Rang_GC && $Benutzer['allianz'] == $zeig_allianz) {
                    echo '<TR>';
                    echo '  <TD BGCOLOR=#'.$htmlstyle['hell_blau'].'>';
                    echo '      <FORM ACTION="./main.php" METHOD="POST">';
                    echo '          <INPUT TYPE="hidden" NAME="modul" VALUE="anzeigen">';
                    echo '          <INPUT TYPE="hidden" NAME="id" VALUE="'.$zeig_id.'">';
                    echo '          <INPUT TYPE="hidden" NAME="action" VALUE="umod">';
                    echo '          <INPUT TYPE="hidden" NAME="UModID" VALUE="'.$zeig_id.'">';
                    echo '          <INPUT TYPE="submit" VALUE="Urlaubs-Modus deaktivieren">';
                    echo '      </FORM>';
                    echo '  </TD>';
                    echo '</TR>';
                }
            } else {
                if ($Benutzer['rang'] >= $Rang_GC && $Benutzer['allianz'] == $zeig_allianz) {
                    echo '<TR><TD><BR></TD></TR>';
                    echo '<TR>';
                    echo '  <TD BGCOLOR=#'.$htmlstyle['dunkel'].'><B><font size="-1">Urlaubs-Modus</font></B></TD>';
                    echo '</TR>';
                    echo '<TR>';
                    echo '  <TD BGCOLOR=#'.$htmlstyle['hell'].'>';
                    echo '      <FORM ACTION="./main.php" METHOD="POST">';
                    echo '          <INPUT TYPE="hidden" NAME="modul" VALUE="anzeigen">';
                    echo '          <INPUT TYPE="hidden" NAME="id" VALUE="'.$zeig_id.'">';
                    echo '          <INPUT TYPE="hidden" NAME="action" VALUE="umod">';
                    echo '          <INPUT TYPE="hidden" NAME="UModID" VALUE="'.$zeig_id.'">';
                    echo '          <TABLE>';
                    echo '              <TR>';
                    echo '                  <TD><font size="-1">Zeitraum des Urlaubs:</font></TD> <TD><INPUT TYPE="text" NAME="txtUModZeit" VALUE="'.date("d").'.'.date("m").'.'.date("Y").'-XX.XX.XXXX" MAXLENGTH=21 SIZE=SMall><font size="-1"> (Falls unbekannt einfach so lassen)</font></TD>';
                    echo '              </TR>';
                    echo '              <TR>';
                    echo '                  <TD><BR></TD><TD><INPUT TYPE="submit" VALUE="Urlaubs-Modus aktivieren"></TD>';
                    echo '              </TR>';
                    echo '          </TABLE>';
                    echo '      </FORM>';
                    echo '  </TD>';
                    echo '</TR>';
                }
            }
        ?>
    <TR>
      <TD><font size="-1"><BR>
        </font></TD>
    </TR>
    <TR>
      <TD>
        <P CLASS="dunkel"><B><font size="-1">Greift an:</font></B></P>
      </TD>
    </TR>
    <TR>
      <TD> <font size="-1">
        <?
                    $SQL_Result = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` WHERE modus="1" AND angreifer_galaxie="'.$zeig_galaxie.'" AND angreifer_planet="'.$zeig_planet.'" ORDER BY eta;', $SQL_DBConn) or $error_code = 4;
                    $SQL_Num = mysql_num_rows($SQL_Result);
                    if ($SQL_Num == 0)
                        echo '<P CLASS="hell"><font size="-1">Seine/Ihre Flotten greifen nicht an.</font></P>';
                    else {
                        echo '<CENTER><P CLASS="hell">';
                        echo '  Seine/Ihre Flotten sind auf dem Weg zu folgenden Spielern:';
                        echo '  <TABLE>';
                        for ($n = 0; $n < $SQL_Num; $n++) {
                            $scan = ' <A HREF="./main.php?modul=showgalascans&displaymode=0&xgala='.mysql_result($SQL_Result, $n, 'verteidiger_galaxie').'&xplanet='.mysql_result($SQL_Result, $n, 'verteidiger_planet').'">'.GetScans2($SQL_DBConn, mysql_result($SQL_Result, $n, 'verteidiger_galaxie'), mysql_result($SQL_Result, $n, 'verteidiger_planet')).'</A>';
                            echo '<TR><TD><font size="-1">Name: </td><td><B>'.mysql_result($SQL_Result, $n, 'verteidiger_galaxie').':'.mysql_result($SQL_Result, $n, 'verteidiger_planet').'</B></font></TD>';
                            $disptime = mysql_result($SQL_Result, $n, 'eta') * $Ticks['lange'] - $tick_abzug;
                            $disptime = getime4display( $disptime );
                            echo '<TD><font size="-1">ETA: </td><td><B>'.$disptime.'</B></font></TD>';
                            echo '<TD><font size="-1">Angriffslänge: </td><td><B>'.getime4display(mysql_result($SQL_Result, $n, 'flugzeit') * $Ticks['lange']).'</B></font></TD>';
                            echo '<TD><font size="-1">Flotte: </td><td>'.$flottennr[mysql_result($SQL_Result, $n, 'flottennr')].'</font></TD>';
                            echo '<TD><font size="-1">'.$scan.'</font> <td>';
                            /* anstelle von */
//                            if ($Benutzer['rang'] > $Rang_GC || $Benutzer['galaxie'] == $zeig_galaxie) echo ', <A HREF="./main.php?modul=anzeigen&id='.$id.'&action=flotteloeschen&flottenid='.mysql_result($SQL_Result, $n, 'id').'">Löschen</A>, <A HREF="./main.php?modul=flotteaendern&id='.$id.'&flottenid='.mysql_result($SQL_Result, $n, 'id').'">Ändern</A>';
                            /* dies hier */
                            echo '<FORM ACTION="./main.php" METHOD="POST">';
                            echo '<INPUT TYPE="hidden" NAME="modul" VALUE="anzeigen">';
                            echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
                            echo '<INPUT TYPE="hidden" NAME="action" VALUE="flotteloeschen">';
                            echo '<INPUT TYPE="hidden" NAME="flottenid" VALUE="'.mysql_result($SQL_Result, $n, 'id').'">';
                            echo '<INPUT TYPE="submit" VALUE="Löschen"> </form></td><td>';
                            echo '<FORM ACTION="./main.php" METHOD="POST">';
                            echo '<INPUT TYPE="hidden" NAME="modul" VALUE="flotteaendern">';
                            echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
                            echo '<INPUT TYPE="hidden" NAME="flottenid" VALUE="'.mysql_result($SQL_Result, $n, 'id').'">';
                            echo '<INPUT TYPE="submit" VALUE="Ändern"> </form>';
                            echo '</TD></TR>';
                        }
                        echo '  </TABLE>';
                        echo '</P></CENTER>';
                    }
                ?>
        </font></TD>
    </TR>
    <?
//            if ($Benutzer['rang'] > $Rang_GC || !($Benutzer['rang'] <= $Rang_GC && $Benutzer['galaxie'] != $zeig_galaxie)) {

    echo '<TR>';
    echo  '<TD>';
        echo '<P CLASS="hell"> <font size="-1">';
        echo '<FORM ACTION="./main.php" METHOD="POST">';
        echo '<INPUT TYPE="hidden" NAME="modul" VALUE="flotten">';
        echo '<INPUT TYPE="hidden" NAME="selected" VALUE="greiftan">';
        echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
        echo '<INPUT TYPE="submit" VALUE="Bewegung Hinzf."> </form>';
        echo '</form></font></P>';
      echo '</TD>';
    echo '</TR>';

//            }
        ?>
    <TR>
      <TD><font size="-1"><BR>
        </font></TD>
    </TR>
    <TR>
      <TD>
        <P CLASS="dunkel"><B><font size="-1">Verteidigt:</font></B></P>
      </TD>
    </TR>
    <TR>
      <TD> <font size="-1">
        <?
                    $SQL_Result = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` WHERE modus="2" AND angreifer_galaxie="'.$zeig_galaxie.'" AND angreifer_planet="'.$zeig_planet.'" ORDER BY eta;', $SQL_DBConn) or $error_code = 4;
                    $SQL_Num = mysql_num_rows($SQL_Result);
                    if ($SQL_Num == 0)
                        echo '<P CLASS="hell"><font size="-1">Seine/Ihre Flotten verteidigen niemanden.</font></P>';
                    else {
                        echo '<CENTER><P CLASS="hell">';
                        echo '<font size="-1">  Seine/Ihre Flotten sind auf dem Weg zu folgenden Spielern:</font>';
                        echo '  <TABLE>';
                        for ($n = 0; $n < $SQL_Num; $n++) {
                            $scan = ' <A HREF="./main.php?modul=showgalascans&displaymode=0&xgala='.mysql_result($SQL_Result, $n, 'verteidiger_galaxie').'&xplanet='.mysql_result($SQL_Result, $n, 'verteidiger_planet').'">'.GetScans2($SQL_DBConn, mysql_result($SQL_Result, $n, 'verteidiger_galaxie'), mysql_result($SQL_Result, $n, 'verteidiger_planet')).'</A>';
                            echo '<TR><TD><font size="-1">Name:</td><td> <B>'.mysql_result($SQL_Result, $n, 'verteidiger_galaxie').':'.mysql_result($SQL_Result, $n, 'verteidiger_planet').'</B></font></TD>';
                            $disptime = mysql_result($SQL_Result, $n, 'eta') * $Ticks['lange'] - $tick_abzug;
                            $disptime = getime4display( $disptime );
                            echo '<TD><font size="-1">ETA:</td><td><B>'.$disptime.'</B></font></TD>';
                            echo '<TD><font size="-1">Verteidigungslänge:</td><td> <B>'.getime4display(mysql_result($SQL_Result, $n, 'flugzeit') * $Ticks['lange']).'</B></font></TD>';
                            echo '<TD><font size="-1">Flotte:</td><td> '.$flottennr[mysql_result($SQL_Result, $n, 'flottennr')].'</font></TD>';
                            echo '<TD><font size="-1">'.$scan.'</font></td><td>';
//                            if ($Benutzer['rang'] > $Rang_GC || !($Benutzer['rang'] <= $Rang_GC && $Benutzer['galaxie'] != $zeig_galaxie)) echo ', <A HREF="./main.php?modul=anzeigen&id='.$id.'&action=flotteloeschen&flottenid='.mysql_result($SQL_Result, $n, 'id').'">Löschen</A>, <A HREF="./main.php?modul=flotteaendern&id='.$id.'&flottenid='.mysql_result($SQL_Result, $n, 'id').'">&Auml;ndern</A>';
                            echo '<FORM ACTION="./main.php" METHOD="POST">';
                            echo '<INPUT TYPE="hidden" NAME="modul" VALUE="anzeigen">';
                            echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
                            echo '<INPUT TYPE="hidden" NAME="action" VALUE="flotteloeschen">';
                            echo '<INPUT TYPE="hidden" NAME="flottenid" VALUE="'.mysql_result($SQL_Result, $n, 'id').'">';
                            echo '<INPUT TYPE="submit" VALUE="Löschen"> </form></td><td>';
                            echo '<FORM ACTION="./main.php" METHOD="POST">';
                            echo '<INPUT TYPE="hidden" NAME="modul" VALUE="flotteaendern">';
                            echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
                            echo '<INPUT TYPE="hidden" NAME="flottenid" VALUE="'.mysql_result($SQL_Result, $n, 'id').'">';
                            echo '<INPUT TYPE="submit" VALUE="Ändern"> </form>';
                            echo '</TD></TR>';
                        }
                        echo '  </TABLE>';
                        echo '</P></CENTER>';
                    }
                ?>
        </font></TD>
    </TR>
    <?
//            if ($Benutzer['rang'] > $Rang_GC || !($Benutzer['rang'] <= $Rang_GC && $Benutzer['galaxie'] != $zeig_galaxie)) {

    echo '<TR>';
    echo  '<TD>';
        echo '<P CLASS="hell"> <font size="-1">';
        echo '<FORM ACTION="./main.php" METHOD="POST">';
        echo '<INPUT TYPE="hidden" NAME="modul" VALUE="flotten">';
       echo '<INPUT TYPE="hidden" NAME="selected" VALUE="verteidigt">';
       echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
        echo '<INPUT TYPE="submit" VALUE="Bewegung Hinzf."> </form>';
        echo '</form></font></P>';
      echo '</TD>';
    echo '</TR>';

//            }
        ?>
    <TR>
      <TD><font size="-1"><BR>
        </font></TD>
    </TR>
    <TR>
      <TD>
        <P CLASS="dunkel"><B><font size="-1">Wird angegriffen von:</font></B></P>
      </TD>
    </TR>
    <TR>
      <TD> <font size="-1">
        <?
                    $zeig_deff='0';
					$SQL_Result = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` WHERE modus="1" AND verteidiger_galaxie="'.$zeig_galaxie.'" AND verteidiger_planet="'.$zeig_planet.'" ORDER BY eta;', $SQL_DBConn) or $error_code = 4;
                    $SQL_Num = mysql_num_rows($SQL_Result);
					for ($x=0;$x<$SQL_Num;$x++){
					$save = mysql_result($SQL_Result,$x,'save');
     				$zeig_deff = $zeig_deff+$save;
					  }

					if ($SQL_Num == 0) {
                        $SQL_Result = tic_mysql_query('UPDATE `gn4accounts` SET deff="0" WHERE id="'.$id.'";', $SQL_DBConn) or $error_code = 7;
                        echo '<P CLASS="hell"><font size="-1">Er/Sie wird von niemandem angegriffen.</font></P>';
                    } else {
                        if (!$zeig_deff > 0)
                            echo '<CENTER><P CLASS="hell_gruen">';
                        else
                            echo '<CENTER><P CLASS="hell_rot">';
                        echo '  Folgende Flotten sind im Anflug:';
                        echo '  <TABLE>';
                        for ($n = 0; $n < $SQL_Num; $n++) {
                            $scan = ' <A HREF="./main.php?modul=showgalascans&displaymode=0&xgala='.mysql_result($SQL_Result, $n, 'angreifer_galaxie').'&xplanet='.mysql_result($SQL_Result, $n, 'angreifer_planet').'">'.GetScans2($SQL_DBConn, mysql_result($SQL_Result, $n, 'angreifer_galaxie'), mysql_result($SQL_Result, $n, 'angreifer_planet')).'</A>';
                            echo '<TR><TD><font size="-1">Name: </td><td><B>'.mysql_result($SQL_Result, $n, 'angreifer_galaxie').':'.mysql_result($SQL_Result, $n, 'angreifer_planet').'</B></font></TD>';
                            $disptime = mysql_result($SQL_Result, $n, 'eta') * $Ticks['lange'] - $tick_abzug;
                            $disptime = getime4display( $disptime );
                            echo '<TD><font size="-1">ETA: </td><td><B>'.$disptime.'</B></font></TD>';
                            echo '<TD><font size="-1">Angriffslänge: </td><td><B>'.getime4display(mysql_result($SQL_Result, $n, 'flugzeit') * $Ticks['lange']).'</B></font></TD>';
                            echo '<TD><font size="-1">Flotte: </td><td>'.$flottennr[mysql_result($SQL_Result, $n, 'flottennr')].'</font></TD>';
                            echo '<TD><font size="-1">'.$scan.'</font><td>';
//                            if ($Benutzer['rang'] > $Rang_GC || !($Benutzer['rang'] <= $Rang_GC && $Benutzer['galaxie'] != $zeig_galaxie)) echo ', <A HREF="./main.php?modul=anzeigen&id='.$id.'&action=flotteloeschen&flottenid='.mysql_result($SQL_Result, $n, 'id').'">Löschen</A>, <A HREF="./main.php?modul=flotteaendern&id='.$id.'&flottenid='.mysql_result($SQL_Result, $n, 'id').'">Ändern</A>';
                            echo '<FORM ACTION="./main.php" METHOD="POST">';
                            echo '<INPUT TYPE="hidden" NAME="modul" VALUE="anzeigen">';
                            echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
                            echo '<INPUT TYPE="hidden" NAME="action" VALUE="flotteloeschen">';
                            echo '<INPUT TYPE="hidden" NAME="flottenid" VALUE="'.mysql_result($SQL_Result, $n, 'id').'">';
                            echo '<INPUT TYPE="submit" VALUE="Löschen"> </form></td><td>';
                            echo '<FORM ACTION="./main.php" METHOD="POST">';
                            echo '<INPUT TYPE="hidden" NAME="modul" VALUE="flotteaendern">';
                            echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
                            echo '<INPUT TYPE="hidden" NAME="flottenid" VALUE="'.mysql_result($SQL_Result, $n, 'id').'">';
                            echo '<INPUT TYPE="submit" VALUE="Ändern"> </form>';
                            echo '</TD></TR>';
                        }
                        echo '  </TABLE>';
                        if (!$zeig_deff == 0)
                            echo '<BR><A HREF="./main.php?modul=anzeigen&id='.$id.'&need_galaxie='.$zeig_galaxie.'&need_planet='.$zeig_planet.'">[ Es wird Verteidigung benötigt! ]</A><BR>';
                        else
                            echo '<BR><A HREF="./main.php?modul=anzeigen&id='.$id.'&needno_galaxie='.$zeig_galaxie.'&needno_planet='.$zeig_planet.'">[ Es wird keine Verteidigung benötigt! ]</A><BR>';
//                        echo '<BR><A HREF="./main.php?modul=kampfsimulator&txtKampfGalaxie='.$zeig_galaxie.'&txtKampfPlanet='.$zeig_planet.'">Kampfsimulation ansehen</A><BR><BR>';
                        echo '</P></CENTER>';
                    }
                ?>
        </font></TD>
    </TR>
    <?
//            if ($Benutzer['rang'] > $Rang_GC || !($Benutzer['rang'] <= $Rang_GC && $Benutzer['galaxie'] != $zeig_galaxie)) {

    echo '<TR>';
    echo  '<TD>';
        echo '<P CLASS="hell"> <font size="-1">';
        echo '<FORM ACTION="./main.php" METHOD="POST">';
        echo '<INPUT TYPE="hidden" NAME="modul" VALUE="flotten">';
        echo '<INPUT TYPE="hidden" NAME="selected" VALUE="wirdangegriffen">';
        echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
        echo '<INPUT TYPE="submit" VALUE="Bewegung Hinzf."> </form>';
        echo '</form></font></P>';
      echo '</TD>';
    echo '</TR>';

//            }
        ?>
    <TR>
      <TD><font size="-1"><BR>
        </font></TD>
    </TR>
    <TR>
      <TD>
        <P CLASS="dunkel"><B><font size="-1">Wird verteidigt von:</font></B></P>
      </TD>
    </TR>
    <TR>
      <TD> <font size="-1">
        <?
                    $SQL_Result = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` WHERE modus="2" AND verteidiger_galaxie="'.$zeig_galaxie.'" AND verteidiger_planet="'.$zeig_planet.'" ORDER BY eta;', $SQL_DBConn) or $error_code = 4;
                    $SQL_Num = mysql_num_rows($SQL_Result);
                    if ($SQL_Num == 0)
                        echo '<P CLASS="hell">Er/Sie wird von niemandem verteidigt.</P>';
                    else {
                        echo '<CENTER><P CLASS="hell">';
                        echo '  Folgende Flotten sind im Anflug:';
                        echo '  <TABLE>';
                        for ($n = 0; $n < $SQL_Num; $n++) {
                            $scan = ' <A HREF="./main.php?modul=showgalascans&displaymode=0&xgala='.mysql_result($SQL_Result, $n, 'angreifer_galaxie').'&xplanet='.mysql_result($SQL_Result, $n, 'angreifer_planet').'">'.GetScans2($SQL_DBConn, mysql_result($SQL_Result, $n, 'angreifer_galaxie'), mysql_result($SQL_Result, $n, 'angreifer_planet')).'</A>';

                            echo '<TR><TD><font size="-1">Name: </td><td><B>'.mysql_result($SQL_Result, $n, 'angreifer_galaxie').':'.mysql_result($SQL_Result, $n, 'angreifer_planet').'</B></font></TD>';
                            $disptime = mysql_result($SQL_Result, $n, 'eta') * $Ticks['lange'] - $tick_abzug;
                            $disptime = getime4display( $disptime );
                            echo '<TD><font size="-1">ETA: </td><td><B>'.$disptime.'</B></font></TD>';
                            echo '<TD><font size="-1">Verteidigungslänge: </td><td><B>'.getime4display(mysql_result($SQL_Result, $n, 'flugzeit') * $Ticks['lange']).'</B></font></TD>';
                            echo '<TD><font size="-1">Flotte: </td><td>'.$flottennr[mysql_result($SQL_Result, $n, 'flottennr')].'</font></TD>';
                            echo '<TD><font size="-1">'.$scan.'</font><td>';
//                            if ($Benutzer['rang'] > $Rang_GC || !($Benutzer['rang'] <= $Rang_GC && $Benutzer['galaxie'] != $zeig_galaxie)) echo ', <A HREF="./main.php?modul=anzeigen&id='.$id.'&action=flotteloeschen&flottenid='.mysql_result($SQL_Result, $n, 'id').'">Löschen</A>, <A HREF="./main.php?modul=flotteaendern&id='.$id.'&flottenid='.mysql_result($SQL_Result, $n, 'id').'">Ändern</A>';
                            echo '<FORM ACTION="./main.php" METHOD="POST">';
                            echo '<INPUT TYPE="hidden" NAME="modul" VALUE="anzeigen">';
                            echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
                            echo '<INPUT TYPE="hidden" NAME="action" VALUE="flotteloeschen">';
                            echo '<INPUT TYPE="hidden" NAME="flottenid" VALUE="'.mysql_result($SQL_Result, $n, 'id').'">';
                            echo '<INPUT TYPE="submit" VALUE="Löschen"> </form></td><td>';
                            echo '<FORM ACTION="./main.php" METHOD="POST">';
                            echo '<INPUT TYPE="hidden" NAME="modul" VALUE="flotteaendern">';
                            echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
                            echo '<INPUT TYPE="hidden" NAME="flottenid" VALUE="'.mysql_result($SQL_Result, $n, 'id').'">';
                            echo '<INPUT TYPE="submit" VALUE="Ändern"> </form>';
                            echo '</TD></TR>';
                        }
                        echo '  </TABLE>';
                        echo '</P></CENTER>';
                    }
                ?>
        </font></TD>
    </TR>
    <?
//            if ($Benutzer['rang'] > $Rang_GC || !($Benutzer['rang'] <= $Rang_GC && $Benutzer['galaxie'] != $zeig_galaxie)) {

    echo '<TR>';
    echo  '<TD>';
        echo '<P CLASS="hell"> <font size="-1">';
        echo '<FORM ACTION="./main.php" METHOD="POST">';
        echo '<INPUT TYPE="hidden" NAME="modul" VALUE="flotten">';
        echo '<INPUT TYPE="hidden" NAME="selected" VALUE="wirdverteidigt">';
        echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
        echo '<INPUT TYPE="submit" VALUE="Bewegung Hinzf."> </form>';
        echo '</form></font></P>';
      echo '</TD>';
    echo '</TR>';

//            }
        ?>
    <TR>
      <TD><font size="-1"><BR>
        </font></TD>
    </TR>
    <TR>
      <TD>
        <P CLASS="dunkel"><B><font size="-1">Rückflug:</font></B></P>
      </TD>
    </TR>
    <TR>
      <TD> <font size="-1">
        <?
                    $SQL_Result = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` WHERE ( modus="0" or modus="3" or modus="4" ) AND angreifer_galaxie="'.$zeig_galaxie.'" AND angreifer_planet="'.$zeig_planet.'" ORDER BY eta;', $SQL_DBConn) or $error_code = 4;
                    $SQL_Num = mysql_num_rows($SQL_Result);
                    if ($SQL_Num == 0)
                        echo '<P CLASS="hell">Es befindet sich keine Flotte auf dem Rückflug.</P>';
                    else {
                        echo '<CENTER><P CLASS="hell">';
                        echo '  Folgende Flotten sind auf dem Rückflug:';
                        echo '  <TABLE>';
                        for ($n = 0; $n < $SQL_Num; $n++) {
                            echo '<TR><TD><font size="-1">Name: <B>'.mysql_result($SQL_Result, $n, 'verteidiger_galaxie').':'.mysql_result($SQL_Result, $n, 'verteidiger_planet').'</B></font></TD>';

                            $disptime = mysql_result($SQL_Result, $n, 'eta') * $Ticks['lange'] - $tick_abzug;
                            $disptime = getime4display( $disptime );
                            echo '<TD><font size="-1">ETA: <B>'.$disptime.'</B></font></TD>';

                            echo '<TD><font size="-1">Flotte: '.$flottennr[mysql_result($SQL_Result, $n, 'flottennr')].'</font></TD>';
                            echo '<TD>';
//                            if ($Benutzer['rang'] > $Rang_GC || !($Benutzer['rang'] <= $Rang_GC && $Benutzer['galaxie'] != $zeig_galaxie)) echo '<A HREF="./main.php?modul=anzeigen&id='.$id.'&action=flotteloeschen&flottenid='.mysql_result($SQL_Result, $n, 'id').'">Löschen</A>, <A HREF="./main.php?modul=flotteaendern&id='.$id.'&flottenid='.mysql_result($SQL_Result, $n, 'id').'">Ändern</A></TD>';
                            echo '<FORM ACTION="./main.php" METHOD="POST">';
                            echo '<INPUT TYPE="hidden" NAME="modul" VALUE="anzeigen">';
                            echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
                            echo '<INPUT TYPE="hidden" NAME="action" VALUE="flotteloeschen">';
                            echo '<INPUT TYPE="hidden" NAME="flottenid" VALUE="'.mysql_result($SQL_Result, $n, 'id').'">';
                            echo '<INPUT TYPE="submit" VALUE="Löschen"> </form></td><td>';
                            echo '<FORM ACTION="./main.php" METHOD="POST">';
                            echo '<INPUT TYPE="hidden" NAME="modul" VALUE="flotteaendern">';
                            echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$id.'">';
                            echo '<INPUT TYPE="hidden" NAME="flottenid" VALUE="'.mysql_result($SQL_Result, $n, 'id').'">';
                            echo '<INPUT TYPE="submit" VALUE="Ändern"> </form>';
                            echo '</TD></form></TR>';
                        }
                        echo '  </TABLE>';
                        echo '</P></CENTER>';
                    }
                ?>
        </font></TD>
    </TR>
  </TABLE>
</CENTER>
<?
    }
?>
