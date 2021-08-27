<center>
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><font size="+2">Neuer ATT Einplanen</font></td>
  </tr>
</table>
<?php
// atteinplanen

   if (!isset($_GET['fkt'])) $fkt = '';
   if (!isset($_GET['lfd'])) $lfd = '';

   echo '<br><table border="1" cellspacing="0" cellpadding="0"><tr>';
   echo '<TD bgcolor="#6490BB"><b>Ziel-Galaxie:</TD>';
   echo '<TD bgcolor="#6490BB"><b>Ziel-Planet: </TD>';
   echo '<TD bgcolor="#6490BB"><b>Angriffsdatum:</TD>';
   echo '<TD bgcolor="#6490BB"><b>AngriffsUhrzeit:</TD>';
   echo '<TD bgcolor="#6490BB"><b>Einplanen für:</TD>';
   echo '<TD bgcolor="#6490BB"><b>Freigabe:</TD></TR>';

   echo '<TR><TD bgcolor="#0000FF">';
   echo '<form name="attadd" method="post" action="./main.php">';
   echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.$Benutzer['id'].'">';
   echo '<INPUT TYPE="hidden" NAME="fkt" VALUE="attadd">';
   echo '<INPUT TYPE="hidden" NAME="action" VALUE="attplaneradmin">';
   echo '<INPUT TYPE="hidden" NAME="modul"  VALUE="attplanerlist">';

   // Werte
   $freigabesel = "";
   if ($fkt == "attumplanung") {
     $SQL = "SELECT * FROM gn4attplanung WHERE lfd=".$lfd.";";

     $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));

     if (mysqli_num_rows($SQL_Result) !=0) {
        $galaxie = tic_mysql_result($SQL_Result, 0, 'galaxie');
        $planet =  tic_mysql_result($SQL_Result, 0, 'planet');
        $attdatum = ConvertDatumToText(tic_mysql_result($SQL_Result, 0, 'attdatum'));
        $attzeit = substr(tic_mysql_result($SQL_Result, 0, 'attzeit'),0,5);
        if (tic_mysql_result($SQL_Result, 0, 'forallianz') != 0) {
           $ForAllianz = " selected ";
        } else {
           $ForAllianz = "";
        }
        if (tic_mysql_result($SQL_Result, 0, 'formeta') != 0) {
           $ForMeta = " selected ";
        } else {
           $ForMeta = "";
        }
        if (tic_mysql_result($SQL_Result, 0, 'forall') != 0) {
           $ForAll = " selected ";
        } else {
           $ForAll = "";
        }
        $info = tic_mysql_result($SQL_Result, 0, 'info');
        if (tic_mysql_result($SQL_Result, 0, 'freigabe') != 0) {
             $freigabesel = " selected";
        }
        echo '<INPUT TYPE="hidden" NAME="lfd"  VALUE="'.$lfd.'">';
     }
   } else {
     $galaxie = "";
     $planet = "";
     $attdatum = date("d").'.'.date("m").'.'.date("Y");
     $attzeit = date("H").':00';
     $ForAllianz = "";
     $ForMeta = "";
     $ForAll = "";
     $info = "";
   }

   echo '<center><INPUT TYPE="TEXT" NAME="galaxie" SIZE="4" MAXSIZE="4" VALUE="'.$galaxie.'"></TD>';
   echo '<TD bgcolor="#0000FF"><center><INPUT TYPE="TEXT" NAME="planet"  SIZE="4" MAXSIZE="2" value="'.$planet.'"></TD>';
   echo '<TD bgcolor="#0000FF"><center><INPUT TYPE="TEXT" NAME="attdatum" SIZE="10" MAXSIZE="10" Value="'.$attdatum.'"></TD>';
   echo '<TD bgcolor="#0000FF"><center><INPUT TYPE="TEXT" NAME="attzeit"  SIZE="5" MAXSIZE="5" Value="'.$attzeit.'"></TD>';

   echo '<TD bgcolor="#0000FF">';
   echo '<select name="attfor">';
   echo '<option value="Allianz" '.$ForAllianz.'>Allianz</option>';
   echo '<option value="Meta" '.$ForMeta.'>Meta</option>';
   echo '<option value="Alle" '.$ForAll.'>Alle</option>';
   echo '</select>';
   echo '</TD><TD bgcolor="#6490BB">';
   if ($Benutzer['attplaner'] != 0) {
       echo '<select name="freigabe">';
       echo '<option value="0">Nicht freigegeben</option>';
       echo '<option value="1" '.$freigabesel.'>Sofort freigeben</option>';
       echo '</select>';
   } else {
     echo '-nicht möglich-';
     echo '<INPUT TYPE="hidden" NAME="freigabe"  VALUE="0">';
   }
   echo '</TD></TR>';

   echo '<TR>';
   echo '<TD bgcolor="#6490BB"><b>Bemerkungen:</TD>';
   echo '<TD colspan=4 bgcolor="#0000FF"><center><INPUT TYPE="TEXT" NAME="info" SIZE="60" MAXSIZE="255" value="'.$info.'"></TD>';
   echo '<TD bgcolor="#6490BB"><center><input type="submit" name="ADD" value="Einplanen"></TD>';

   echo '</TR></Table>';

   echo '<br><font size="-1">(Wenn keine Planet erfaßt wird, dann werden alle <b>gescannten Planeten der Galaxie</b> mit hinzugefügt!)</font><br>';
?>

