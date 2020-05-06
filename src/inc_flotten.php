<?php
    if (!isset($_POST['id'])) $_POST['id'] = 0;
    if ($_POST['id'] == 0) $error_code = 8;
    $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE id="'.$_POST['id'].'";', $SQL_DBConn) or $error_code = 4;
    if (mysqli_num_rows($SQL_Result) != 1) $error_code = 8;
    if ($error_code != 0)
        include('./inc_errors.php');
    else {
        $zeig_name = tic_mysql_result($SQL_Result, 0, 'name');
        $zeig_galaxie = tic_mysql_result($SQL_Result, 0, 'galaxie');
        $zeig_planet = tic_mysql_result($SQL_Result, 0, 'planet');
?>
<CENTER>

<TABLE>
  <TR>
    <TD BGCOLOR=#333333><font color="#FFFFFF" size="-1"><B>Flottenbewegung hinzuf&uuml;gen</B></font></TD>
  </TR>
  <TR>
    <TD>
      <P CLASS="hell">
      <FORM ACTION="./main.php" METHOD="POST" NAME="frmFlottenbewegung">
        <font size="-1">
        <INPUT TYPE="hidden" NAME="modul" VALUE="anzeigen">
        <INPUT TYPE="hidden" NAME="action" VALUE="flottenbewegung">
        <INPUT TYPE="hidden" NAME="id" VALUE="<?=$_POST['id']?>">
        <?php
            if (!isset($_POST['selected'])) $_POST['selected'] = '';
            if ($_POST['selected'] == 'greiftan') {
                echo '<INPUT TYPE="hidden" NAME="modus" VALUE="angreifen">';
                echo '<INPUT TYPE="hidden" NAME="txt_Angreifer_Galaxie" VALUE="'.$zeig_galaxie.'">';
                echo '<INPUT TYPE="hidden" NAME="txt_Angreifer_Planet" VALUE="'.$zeig_planet.'">';
                echo '<B>'.$zeig_galaxie.':'.$zeig_planet.' </B> greift <INPUT TYPE="text" NAME="txt_Verteidiger_Galaxie" SIZE="4" MAXLENGTH=4> : <INPUT TYPE="text" NAME="txt_Verteidiger_Planet" SIZE="2" MAXLENGTH=2>  an. ETA: <SELECT NAME="lst_ETA" SIZE="1">';
                for ($n = $Ticks['angriffsflug']; $n >= 0; $n--)
                    echo '<OPTION VALUE="'.$n.'">'.getime4display($n * $Ticks['lange'] - $tick_abzug).'</OPTION>';
                echo '</SELECT>';
                echo 'Flotte: <SELECT NAME="lst_Flotte" SIZE="1">';
                echo '  <OPTION VALUE="0">Unbekannt</OPTION>';
                echo '  <OPTION VALUE="1">1. Flotte</OPTION>';
                echo '  <OPTION VALUE="2">2. Flotte</OPTION>';
                echo '</SELECT> ';
                echo '<A HREF="javascript:document.frmFlottenbewegung.submit()">Hinzuf&uuml;gen</A>';
            } elseif ($_POST['selected'] == 'wirdangegriffen') {
                echo '<INPUT TYPE="hidden" NAME="modus" VALUE="angreifen">';
                echo '<INPUT TYPE="hidden" NAME="txt_Verteidiger_Galaxie" VALUE="'.$zeig_galaxie.'">';
                echo '<INPUT TYPE="hidden" NAME="txt_Verteidiger_Planet" VALUE="'.$zeig_planet.'">';
                echo '<INPUT TYPE="text" NAME="txt_Angreifer_Galaxie" SIZE="4" MAXLENGTH=4> : <INPUT TYPE="text" NAME="txt_Angreifer_Planet" SIZE="2" MAXLENGTH=2> greift <B>'.$zeig_galaxie.':'.$zeig_planet.'</B> an. ETA: <SELECT NAME="lst_ETA" SIZE="1">';
                for ($n = $Ticks['angriffsflug']; $n >= 0; $n--)
                    echo '<OPTION VALUE="'.$n.'">'.getime4display($n * $Ticks['lange'] - $tick_abzug).'</OPTION>';
                echo '</SELECT>';
                echo 'Flotte: <SELECT NAME="lst_Flotte" SIZE=1>';
                echo '  <OPTION VALUE="0">Unbekannt</OPTION>';
                echo '  <OPTION VALUE="1">1. Flotte</OPTION>';
                echo '  <OPTION VALUE="2">2. Flotte</OPTION>';
                echo '</SELECT> ';
                echo '<A HREF="javascript:document.frmFlottenbewegung.submit()">Hinzuf&uuml;gen</A>';
            } elseif ($_POST['selected'] == 'verteidigt') {
                echo '<INPUT TYPE="hidden" NAME="modus" VALUE="verteidigen">';
                echo '<INPUT TYPE="hidden" NAME="txt_Angreifer_Galaxie" VALUE="'.$zeig_galaxie.'">';
                echo '<INPUT TYPE="hidden" NAME="txt_Angreifer_Planet" VALUE="'.$zeig_planet.'">';
                echo '<B>'.$zeig_galaxie.':'.$zeig_planet.'</B> verteidigt <INPUT TYPE="text" NAME="txt_Verteidiger_Galaxie" SIZE="4" MAXLENGTH=4> : <INPUT TYPE="text" NAME="txt_Verteidiger_Planet" SIZE="2" MAXLENGTH=2> . ETA: <SELECT NAME="lst_ETA" SIZE="1">';
                for ($n = $Ticks['verteidigungsflug']; $n >= 0; $n--)
                    echo '<OPTION VALUE="'.$n.'">'.getime4display($n * $Ticks['lange'] - $tick_abzug).'</OPTION>';
                echo '</SELECT>';
                echo 'Flotte: <SELECT NAME="lst_Flotte" SIZE=1>';
                echo '  <OPTION VALUE="0">Unbekannt</OPTION>';
                echo '  <OPTION VALUE="1">1. Flotte</OPTION>';
                echo '  <OPTION VALUE="2">2. Flotte</OPTION>';
                echo '</SELECT> ';
                echo '<A HREF="javascript:document.frmFlottenbewegung.submit()">Hinzuf&uuml;gen</A>';
            } elseif ($_POST['selected'] == 'wirdverteidigt') {
                echo '<INPUT TYPE="hidden" NAME="modus" VALUE="verteidigen">';
                echo '<INPUT TYPE="hidden" NAME="txt_Verteidiger_Galaxie" VALUE="'.$zeig_galaxie.'">';
                echo '<INPUT TYPE="hidden" NAME="txt_Verteidiger_Planet" VALUE="'.$zeig_planet.'">';
                echo '<INPUT TYPE="text" NAME="txt_Angreifer_Galaxie" SIZE="4" MAXLENGTH=4> : <INPUT TYPE="text" NAME="txt_Angreifer_Planet" SIZE="2" MAXLENGTH=2> verteidigt <B>'.$zeig_galaxie.':'.$zeig_planet.'</B>. ETA: <SELECT NAME="lst_ETA" SIZE="1">';
                for ($n = $Ticks['angriffsflug']; $n >= 0; $n--)
                    echo '<OPTION VALUE="'.$n.'">'.getime4display($n * $Ticks['lange'] - $tick_abzug).'</OPTION>';
                echo '</SELECT>';
                echo 'Flotte: <SELECT NAME="lst_Flotte" SIZE=1>';
                echo '  <OPTION VALUE="0">Unbekannt</OPTION>';
                echo '  <OPTION VALUE="1">1. Flotte</OPTION>';
                echo '  <OPTION VALUE="2">2. Flotte</OPTION>';
                echo '</SELECT> ';
                echo '<A HREF="javascript:document.frmFlottenbewegung.submit()">Hinzuf&uuml;gen</A>';
            } elseif ($_POST['selected'] == 'rueckflug') {
                echo '<INPUT TYPE="hidden" NAME="modus" VALUE="rueckflug">';
                echo '<INPUT TYPE="hidden" NAME="txt_Angreifer_Galaxie" VALUE="'.$zeig_galaxie.'">';
                echo '<INPUT TYPE="hidden" NAME="txt_Angreifer_Planet" VALUE="'.$zeig_planet.'">';
                echo '<INPUT TYPE="hidden" NAME="lst_Flugzeit" VALUE="0">';
                echo 'Flotte befindet sich auf dem Rückflug von <INPUT TYPE="text" NAME="txt_Verteidiger_Galaxie" SIZE="4" MAXLENGTH=4> : <INPUT TYPE="text" NAME="txt_Verteidiger_Planet" SIZE="2" MAXLENGTH=2> . ETA: <SELECT NAME="lst_ETA" SIZE="1">';
                for ($n = $Ticks['angriffsflug']; $n >= 0; $n--)
                    echo '<OPTION VALUE="'.$n.'">'.getime4display($n * $Ticks['lange'] - $tick_abzug).'</OPTION>';
                echo '</SELECT> ';
                echo 'Flotte: <SELECT NAME="lst_Flotte" SIZE=1>';
                echo '  <OPTION VALUE="0">Unbekannt</OPTION>';
                echo '  <OPTION VALUE="1">1. Flotte</OPTION>';
                echo '  <OPTION VALUE="2">2. Flotte</OPTION>';
                echo '</SELECT> ';
                echo '<A HREF="javascript:document.frmFlottenbewegung.submit()">Hinzuf&uuml;gen</A>';
            }
        ?>
        </font>
      </FORM>
      </TD>
  </TR>
</TABLE>
</CENTER>
<?php
    }
?>
