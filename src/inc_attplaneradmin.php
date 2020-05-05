<center>
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><font size="+2">ATT-Planer2 - Configuration</font></td>
  </tr>
</table>
<?php
   // Prüfen, ob die ConfigSeite bearbeitet werden darf
   if ($Benutzer['rang'] >= $Rang_VizeAdmiral) {
     // Informationen für die Meta / Ally anzeigen
     $Ally = $Benutzer['allianz'];
     $Meta = $Benutzer['ticid'];

     //Anzeigen der Meta-Attplaner
    echo '<center> <table border="1" cellspacing="0" cellpadding="0"><TR>';
    echo '<TD bgcolor="#6490BB"><b>User</TD>';
    echo '<TD bgcolor="#6490BB"><b>Rang</TD>';
    echo '<TD bgcolor="#6490BB"><b>Verantwortlich</TD>';
    echo '<TD bgcolor="#6490BB"><b>Funktion</TD></TR>';

    $SQL= "select * from gn4accounts where attplaner > 0;";
    $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
		$SQL_Num = mysql_num_rows($SQL_Result);
    for ( $i=0; $i<$SQL_Num; $i++ )
    {
        echo '<TR>';
        echo '<TD>';
        echo mysql_result($SQL_Result, $i, 'galaxie').':'.mysql_result($SQL_Result, $i, 'planet').' '.mysql_result($SQL_Result, $i, 'name');
        echo '</TD>';
        echo '<TD>'.$RangName[mysql_result($SQL_Result, $i, 'rang')].'</TD>';

        $PlanerRights = mysql_result($SQL_Result, $i, 'attplaner');

        echo '<TD>'.$PlanerTyps[$PlanerRights].'</TD>';

        if ($Benutzer['rang'] >= $Rang_Techniker) {
           $flagdel =1;
        }
        if (mysql_result($SQL_Result, $i, 'allianz') == $Ally && $Benutzer['rang'] >= $Rang_VizeAdmiral)
        {
           $flagdel =1;
        }

        echo '</TD><TD>';
        if ($flagdel == 1) {
          echo '<form name="attplanerremove" method="post" action="./main.php">';
          echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.mysql_result($SQL_Result, $i, 'id').'">';
          echo '<INPUT TYPE="hidden" NAME="fkt" VALUE="remove">';
          echo '<INPUT TYPE="hidden" NAME="action" VALUE="attplaneradmin">';
          echo '<INPUT TYPE="hidden" NAME="modul"  VALUE="attplaneradmin">';
          echo '<input type="submit" name="DEL" value="Loeschen">';
          echo '</Form>';
        }
        echo '</TD></TR>';
    }
    echo '</Table><br>';

    // Neuer Attplaner anlegen
    if ($Benutzer['rang'] >= $Rang_VizeAdmiral) {

       echo '<center> <table border="" cellspacing="0" cellpadding="0"><TR>';
       echo '<TD bgcolor="#6490BB">';
       echo '<form name="newattplaner" method="post" action="./main.php">';
       echo '<INPUT TYPE="hidden" NAME="action" VALUE="attplaneradmin">';
       echo '<INPUT TYPE="hidden" NAME="fkt"    VALUE="newattplaner">';
       echo '<INPUT TYPE="hidden" NAME="modul"  VALUE="attplaneradmin">';

       $SQL2= "select * from gn4accounts where Attplaner = 0 and ticid = ".$Meta." AND allianz=".$Ally." order by galaxie,planet;";
       if ($Benutzer['rang'] > $Rang_Techniker) {
          $SQL2= "select * from gn4accounts order by galaxie, planet;";
       }
//
		   $SQL2_Result = tic_mysql_query($SQL2) or die(tic_mysql_error(__FILE__,__LINE__));
		   $SQL2_Num = mysql_num_rows($SQL2_Result);
       echo '<b>Neuer ATT-Planer bestimmen: <select name="id">';
       for ($i2=0; $i2 < $SQL2_Num; $i2++ ) {
             echo '<option value="'.mysql_result($SQL2_Result, $i2, 'id').'">';
             echo mysql_result($SQL2_Result, $i2, 'galaxie').':'.mysql_result($SQL2_Result, $i2, 'planet').' '.mysql_result($SQL2_Result, $i2, 'name').' - '.$RangName[mysql_result($SQL2_Result, $i2, 'rang')];
             echo '</option>';
       }
       echo '</select>';
       echo '</TD></TR>';
       echo '<TR><TD bgcolor="#6490BB"><center>';
       echo '<b>Berechtigung: <select name="attplaner">';
       for ($i2=1; $i2 < 4; $i2++ ) {
          echo '<option value="'.$i2.'">'.$PlanerTyps[$i2].'</option>';
       }
       echo '</select>';
       echo '</TD></TR>';
       echo '<TR><TD bgcolor="#6490BB"><center>';

       echo '<br><input type="submit" name="SAVE" value="Auswaehlen">';
       echo '</form>';
       echo '</TD></TR></table>';
    }
   } else {

     echo 'Du hast keine Berechtigung die ATT-PLANER-ConfigSeite zu besuchen!';

   }

?>

