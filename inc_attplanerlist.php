<center>
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><font size="+2"><center>ATT-Planer Übersicht</font></td>
  </tr>
  <tr>
    <td bgcolor="#6490BB" class="menu" width="350"><font face="Verdana" size="-1"><center><a href="./main.php?modul=atteinplanen">Neues Att-Ziel erfassen</a></font></td>
  </tr>
  <tr><TD></TD></TR>
  <tr>
    <td bgcolor="#6490BB" class="menu" width="350"><font face="Verdana" size="-1"><center><a href="./main.php?modul=attplanerlist">Attplaner II - Liste aktuallisieren</a></font></td>
  </tr>
</table>
<?php

  // inc_attplanerlist.php

  // Variablen auslesen, damit nur die Berechtigten Atts angezeigt werden
     $Ally = $Benutzer['allianz'];
     $Meta = $Benutzer['ticid'];
     $error_code=0;
     $ptsmultiplikator=6; //max punkte die auf das Opfer fliegen können
     $attgrenze = 3; //max punkte die ein Angreifer weniger punkter als sein Opfer haben kann


     if ($Benutzer['attplaner'] == 0) {
       $SQL = "SELECT * FROM gn4attplanung WHERE (freigabe = 1) and (forall = 1 or formeta = ".$Meta." or forallianz = ".$Ally.") or id = ".$Benutzer['id']." order by attdatum, attzeit, galaxie, planet;";
     } else {
       $SQL = "SELECT * FROM gn4attplanung WHERE (forall = 1 or formeta = ".$Meta." or forallianz = ".$Ally.") order by attdatum, attzeit, galaxie, planet;";
     }

 	   $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
     $SQL_Num = mysql_num_rows($SQL_Result);

     echo '<br><center> <table border="1" cellspacing="0" cellpadding="0"><TR>';
     echo '<TD bgcolor="#6490BB"><b>Zieldaten</TD>';
     echo '<TD bgcolor="#6490BB"><b>Planung</TD>';
     echo '<TD bgcolor="#6490BB"><b>Eingecheckte Flotten:</TD>';
     echo '<TD bgcolor="#6490BB"><b>Information</TD>';
     echo '<TD bgcolor="#6490BB"><b>Funktion</TD>';
     echo '<TD bgcolor="#6490BB"><b>Verwaltung</TD></TR>';

     for ( $i=0; $i<$SQL_Num; $i++ )  {

      // prüfen, ob Attplung veraltet ist..
      $lfd = mysql_result($SQL_Result, $i, "lfd");

      $testdate = date("d").'.'.date("m").'.'.date("Y");

      $yesterday = strftime ("%d.%m.%Y", strtotime("-1 day"));
      $timesep = explode(".",$yesterday);
      $yesterday = mktime(0, 0, 0, $timesep[1], $timesep[0], $timesep[2]);

      $attdate2 = ConvertDatumToText(mysql_result($SQL_Result, $i, "attdatum"));
      $timesep = explode(".",$attdate2);
      $ATTDate3 = mktime(0, 0, 0, $timesep[1], $timesep[0], $timesep[2]);

      if ($yesterday > $ATTDate3) {
          // veraltete Planung löschen
          $ret = del_attplanlfd($lfd);
      } else {
          //planung anzeigen

       $rg = mysql_result($SQL_Result, $i, 'galaxie');
       $rp = mysql_result($SQL_Result, $i, 'planet');

       $SQL2='select * from `gn4scans` where rg='.mysql_result($SQL_Result, $i, 'galaxie').' and rp='.mysql_result($SQL_Result, $i, 'planet').' and type=0';

       $SQL2_Result = tic_mysql_query($SQL2) or die(tic_mysql_error(__FILE__,__LINE__));

       if (mysql_num_rows($SQL2_Result) != 0) {
          $szeit  = mysql_result($SQL2_Result, 0, 'zeit' );
          $sgen   = mysql_result($SQL2_Result, 0, 'gen' );
          $pts    = mysql_result($SQL2_Result, 0, 'pts' );
          $me     = mysql_result($SQL2_Result, 0, 'me' );
          $ke     = mysql_result($SQL2_Result, 0, 'ke' );
          $s      = mysql_result($SQL2_Result, 0, 's' );
          $d      = mysql_result($SQL2_Result, 0, 'd' );
          $a      = mysql_result($SQL2_Result, 0, 'a' );
          $maxpts = $pts * $ptsmultiplikator;
       } else {
         $szeit= "???";
         $sgen = "???";
         $pts = 0;
         $me = "???";
         $ke = "???";
         $s = "???";
         $d = "???";
         $a = "???";
         $maxpts = 0;
       }

       $AttStatus = mysql_result($SQL_Result, $i, "attstatus");
       $freigabe  = mysql_result($SQL_Result, $i, "freigabe");

       if ($freigabe == 0) {
            $attcolor="DDDDDD";
       } else {
           if ($testdate >= $attdate2 or $AttStatus != 0) {
               $attcolor = $ATTSTATUSHTML[$AttStatus];
           } else {
              $attcolor="DDDDDD";
           }
       }

       $output = OnMouseFlotte($rg, $rp, $Benutzer['punkte'],"");

       echo '<TR>';
       echo '<TD bgcolor="#'.$attcolor.'"><b>';
       $output2 = InfoText("Ziel wurde erfasst durch Spieler:<br><b>".GetUserInfos(mysql_result($SQL_Result, $i, "id"))." von ".GetAllianzName(mysql_result($SQL_Result, $i, "id")));
       echo '<a href="./main.php?modul=showgalascans&xgala='.$rg.'&xplanet='.$rp.'" '.$output2.'>';
       echo $rg.":".$rp." ".gnuser($rg, $rp);
       echo '</a>';

   		 echo " <font color=#0000FF>";

       echo "<a href=\"./main.php?modul=showgalascans&xgala=".$rg."&xplanet=".$rp."\"".($Benutzer['help']?" onmouseover=\"return overlib('".$output."');\" onmouseout=\"return nd();\"":"").">";

       echo GetScans2($SQL_DBConn, $rg, $rp)."</a></B></font><br>";
       echo 'Exxen M: '.$me."<br>Exxen K: ".$ke."<br>";
       echo 'Pkt: '.ZahlZuText(intval($pts/1000))."K<br>";
       echo 'Def: '.$d.'<br>Ships: '.$s."<br>";
       echo '</TD>';


// Planung
       echo '<TD bgcolor="#'.$attcolor.'">';
       echo '<b><center>';
       echo $attdate2;
       echo "<br>".substr( mysql_result($SQL_Result, $i, "attzeit"),0,5)."<BR>";
// Status
       echo $ATTSTATUSINFO[$AttStatus];

       echo '<br>';
       if (mysql_result($SQL_Result, $i, "forall") == 1) {
          echo '</b><font size=1>[ATT_for_all]</font>';
       } elseif (mysql_result($SQL_Result, $i, "formeta") == $Meta) {
          echo '</b><font size=1>[Meta-ATT]</font>';
       } elseif (mysql_result($SQL_Result, $i, "forallianz") == $Ally) {
          echo '</b><font size=1>[ALLY-ATT]</font>';
       }
       echo '</TD>';

// Eingecheckte Flotten
       $SumPts = 0;
       echo '<TD bgcolor="#DDDDDD">';
       $SQL2='select * from `gn4attflotten` where lfd='.$lfd.';';

		   $SQL2_Result = tic_mysql_query($SQL2) or die(tic_mysql_error(__FILE__,__LINE__));

		   $SQL2_Num = mysql_num_rows($SQL2_Result);
       echo '<table border="1" cellspacing="0" cellpadding="0" width="100%">';
       $allowedittext = false;
       $rowanzahl=4;
       for ( $i2=0; $i2<$SQL2_Num; $i2++ )  {
         $rowanzahl = $rowanzahl +2;
         $uid = mysql_result($SQL2_Result, $i2, "id");
// Berücksichtigung der Flottennr.
         $flottenr = mysql_result($SQL2_Result, $i2, "flottenr");
         $ATTETA = '';
         echo '<TR>';
         echo '<TD>';
         $uidstatus = check_attflottenstatus($uid,$flottenr,$rg,$rp,$AttStatus,$lfd);
         echo '<img src="./bilder/scans/'.$PIC[$uidstatus].'">';
         echo '</TD>';

         if ($uid == $Benutzer['id']) {
            echo '<TD bgcolor="#'.$ATTSTATUSHTML[0].'"><center>';
            $allowedittext = true;
         } else {
            echo '<TD colspan=2><center>';
         }

//         echo GetUserInfos($uid);
         echo Get_ScanID($uid, $Benutzer['help'], 0);

         echo "<br>".Get_FlottenNr($uid, $Benutzer['help'],$flottenr);
         $upts = GetUserPts(mysql_result($SQL2_Result, $i2, "id"));
         if ($ATTETA != '') {
            echo '-ETA:<b>'.$ATTETA."</b><br>";
         }

         echo ' Pkt: '.ZahlZuText(intval($upts / 1000)).'K';
// Wenn ID = BenutzerID dann löschen anbieten
         if (mysql_result($SQL2_Result, $i2, "id") == $Benutzer['id']) {
              echo '</TD><TD bgcolor="#'.$ATTSTATUSHTML[$AttStatus].'">';
              echo '<form name="delattflotte" method="post" action="./main.php">';
              echo '<INPUT TYPE="hidden" NAME="lfd" VALUE="'.mysql_result($SQL2_Result, $i2, "lfd").'">';
              echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.mysql_result($SQL2_Result, $i2, "id").'">';
              echo '<INPUT TYPE="hidden" NAME="flotte" VALUE="'.mysql_result($SQL2_Result, $i2, "flottenr").'">';
              echo '<INPUT TYPE="hidden" NAME="fkt" VALUE="delattflotte">';
              echo '<INPUT TYPE="hidden" NAME="action" VALUE="attplaneradmin">';
              echo '<INPUT TYPE="hidden" NAME="modul"  VALUE="attplanerlist">';
              echo '<input type="submit" name="Remove" value="del">';
              echo '</Form>';
         }
         echo '</TD></TR>';
       }

       $SQL3='select id from `gn4attflotten` where lfd='.$lfd.' group by id;';
		   $SQL3_Result = tic_mysql_query($SQL3) or die(tic_mysql_error(__FILE__,__LINE__));
		   $SQL3_Num = mysql_num_rows($SQL3_Result);
       for ( $i3=0; $i3<$SQL3_Num; $i3++ )  {
          $upts = GetUserPts(mysql_result($SQL3_Result, $i3, "id"));
          $SumPts = $SumPts + $upts;
       }
       if ($SumPts >= $maxpts && $maxpts != 0) {
          echo '<TR><TD colspan=3 bgcolor="#'.$ATTSTATUSHTML[1].'"><center><b>';
       } else {
          echo '<TR><TD colspan=3 bgcolor="#DDDDDD"><center>';
       }
       echo 'SumPkt:'.ZahlZuText(intval($SumPts / 1000)).'K max.'.ZahlZuText(intval($maxpts/1000)).'K';

       echo '</table>';
       echo '</TD>';
// Info
       echo '<TD bgcolor="#DDDDDD" width=30><center>';
       if ($allowedittext == true) {
          echo '<form name="changeinfo" method="post" action="./main.php">';
          echo '<INPUT TYPE="hidden" NAME="lfd" VALUE="'.mysql_result($SQL_Result, $i, "lfd").'">';
          echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$Benutzer['id'].'">';
          echo '<center><textarea NAME="info" cols=15 rows='.$rowanzahl.'>'.mysql_result($SQL_Result, $i, "info").'</textarea><br>';
          echo '<INPUT TYPE="hidden" NAME="fkt" VALUE="changeinfo">';
          echo '<INPUT TYPE="hidden" NAME="action" VALUE="attplaneradmin">';
          echo '<INPUT TYPE="hidden" NAME="modul"  VALUE="attplanerlist">';
          echo '<input type="submit" name="ADD" value="Speichern">';
          echo '</form>';
       } else {
           echo mysql_result($SQL_Result, $i, "info");
       }
       echo '</TD>';
// Funktionen
       echo '<TD bgcolor="#6490BB"><center>';
       if ($freigabe !=0 && $AttStatus == 0) {

              if ($pts*$attgrenze < $Benutzer['punkte'] && $pts!=0) {
                         $Buttontext1 = "Ziel zu schwach #1";
                         $Buttontext2 = "Ziel zu schwach #2";
              } else {
                         $Buttontext1 = "Flotte#1 einplanen";
                         $Buttontext2 = "Flotte#2 einplanen";
              }
              echo '<form name="addattflotte" method="post" action="./main.php">';
              echo '<INPUT TYPE="hidden" NAME="lfd" VALUE="'.mysql_result($SQL_Result, $i, "lfd").'">';
              echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$Benutzer['id'].'">';
              echo '<INPUT TYPE="hidden" NAME="flotte" VALUE="1">';
              echo '<INPUT TYPE="hidden" NAME="fkt" VALUE="addattflotte">';
              echo '<INPUT TYPE="hidden" NAME="action" VALUE="attplaneradmin">';
              echo '<INPUT TYPE="hidden" NAME="modul"  VALUE="attplanerlist">';
              echo '<input type="submit" name="Checkin" value="'.$Buttontext1.'">';
              echo '</Form>';

              echo '<form name="addattflotte" method="post" action="./main.php">';
              echo '<INPUT TYPE="hidden" NAME="lfd" VALUE="'.mysql_result($SQL_Result, $i, "lfd").'">';
              echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$Benutzer['id'].'">';
              echo '<INPUT TYPE="hidden" NAME="flotte" VALUE="2">';
              echo '<INPUT TYPE="hidden" NAME="fkt" VALUE="addattflotte">';
              echo '<INPUT TYPE="hidden" NAME="action" VALUE="attplaneradmin">';
              echo '<INPUT TYPE="hidden" NAME="modul"  VALUE="attplanerlist">';
              echo '<input type="submit" name="Checkin" value="'.$Buttontext2.'">';
              echo '</Form>';
        } else {
          if ($freigabe ==0 ) echo '<b>Nicht<br>freigegeben!';
        }
// Status änderungen
        if ($freigabe !=0 ) {
              echo '<form name="attstatuschange" method="post" action="./main.php">';
              echo '<INPUT TYPE="hidden" NAME="lfd" VALUE="'.mysql_result($SQL_Result, $i, "lfd").'">';
              echo '<INPUT TYPE="hidden" NAME="fkt" VALUE="attstatuschange">';
              echo '<INPUT TYPE="hidden" NAME="action" VALUE="attplaneradmin">';
              echo '<INPUT TYPE="hidden" NAME="modul"  VALUE="attplanerlist">';
              echo '<select name="attstatus">';
              for ($ix=0; $ix < 6 ; $ix++) {
                  if ($AttStatus == $ix) {
                      $selected = " selected ";
                  } else {
                      $selected = "";
                  }
                  echo '<option '.$selected.' value="'.$ix.'">'.$ATTSTATUSINFO[$ix].'</option>';
              }
              echo '</select>';
              echo '<input type="submit" name="Change" value="SET">';
              echo '</Form>';
        }
        echo '</TD>';

        echo '<TD bgcolor="#6490BB"><center>';
              if ($Benutzer['attplaner'] != 0) {
                if (AttPlanerRights( mysql_result($SQL_Result, $i, 'forallianz'),  mysql_result($SQL_Result, $i, 'formeta'),  mysql_result($SQL_Result, $i, 'forall'), $Benutzer['attplaner'], $Benutzer['ticid'], $Benutzer['allianz']) == true) {
// Attplaner funktionen

                  if (mysql_result($SQL_Result, $i, "freigabe") == false) {
                    echo '<a href="./main.php?modul=attplanerlist&lfd='.mysql_result($SQL_Result, $i, "lfd").'&fkt=attfreigabe&action=attplaneradmin2">';
                    echo '<img src="./bilder/attplaner/Ziel_freigabe.gif" width="128" height="25" border="0">';
                    echo '</a><br>';
                  }
                  include './inkl_attplanerfkts.php';
                }
              } elseif ($Benutzer['id'] == mysql_result($SQL_Result, $i, "id") and mysql_result($SQL_Result, $i, "freigabe") == false) {
// Der Zielerfasser darf solange das Ziel umplanen und löschen, bis das Ziel von einem Attplaner frei gegeben wurde
                  include './inkl_attplanerfkts.php';
              }
       echo '</TD>';
       echo '</TR>';
     }
    }
echo '	</table><br>
	<font size=1><b>(<u>Achtung:</u> Bei der Flottenanzeige (Ampel) wird ab sofort auch die Flottennr. berücksichtigt!)</b></font>
  <br>
	<font size=1><b>(<u>Info:</u> Bitte nur sinnvolle Text im Informationsfeld eintragen)</b></font>
	<br>';

?>
