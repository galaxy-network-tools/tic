<?php
    if(isset($_GET['txtScanGalaxie']))
        $coords_gala = $_GET['txtScanGalaxie'];
    else if(isset($_POST['txtScanGalaxie']))
        $coords_gala = $_POST['txtScanGalaxie'];
    else if(isset($_POST['galakoord']))
        $coords_gala = $_POST['galakoord'];
    else
        $coords_gala = null;

    if(isset($_GET['txtScanPlanet']))
        $coords_planet = $_GET['txtScanPlanet'];
    else if(isset($_POST['txtScanPlanet']))
        $coords_planet = $_POST['txtScanPlanet'];
    else if(isset($_POST['planetkoord']))
        $coords_planet = $_POST['planetkoord'];
    else
        $coords_planet = null;


    if (!isset($coords_gala) || !isset($coords_planet)) {
        $SQL_Result = tic_mysql_query('SELECT * FROM `gn4scans` WHERE rg<>"0" AND rp<>"0" and ticid="'.$Benutzer['ticid'].'" ORDER BY rg, rp LIMIT 1;', $SQL_DBConn);
        if (mysql_num_rows($SQL_Result) != 0) {
            $coords_gala = mysql_result($SQL_Result, 0, 'rg');
            $coords_planet = mysql_result($SQL_Result, 0, 'rp');
        } else {
            $coords_gala = $Benutzer['galaxie'];
            $coords_planet = $Benutzer['planet'];
        }
    }
?>
  <table width="90%" align="center">
    <tr><td>
        <table border="0" cellspacing="2" cellpadding="0" width="100%">
          <tr>
            <td valign="top" width="33%" rowspan="2">
              <table border="0" cellspacing="2" cellpadding="0" align="center" width="100%">
                <tr>
                  <td class="datatablehead">Planetenscan abfragen</td>
                </tr>
                <tr>
                  <td class="fieldnormallight" align="center">
                    <form action="./main.php?modul=showgalascans&amp;displaymode=0" method="post">
                      <br />
                      <input type="text" size="4" maxlength="4" name="xgala" value="<?=$coords_gala?>" />
                      :
                      <input type="text" size="2" maxlength="2" name="xplanet" value="<?=$coords_planet?>" />
                      <input type="submit" value="Anzeigen" />
                    </form>
                  </td>
                </tr>
                <tr>
                  <td class="datatablehead" align="center">Galascans abfragen</td>
                </tr>
                <tr>
                  <td class="fieldnormallight" align="center">
                    <form action="./main.php?modul=showgalascans&amp;displaytype=1" method="post">
                      <br />
                      <input type="text" size="4" maxlength="4" name="xgala" value="<?=$coords_gala?>" />
                      <input type="submit" value="Anzeigen" name="submit" />
                    </form>
                  </td>
                </tr>
                <tr>
                  <td class="datatablehead" align="center">Sektor-Eingabe &quot;von Hand&quot;</td>
                </tr>
                <tr>
                  <td class="fieldnormallight" align="center">
                    <form method="post" action="./main.php?modul=scan_editor">
			<input type="hidden" name="scanart" value="sek">
                      <br />
                      <input type="text" name="galakoord" size="4" maxlength="4" value="<?=$coords_gala?>" />
                      :
                      <input type="text" name="planetkoord" size="2" maxlength="2" value="<?=$coords_planet?>" />
                      <input type="submit" name="manuell" value="Manuell erfassen" />
                    </form>
                  </td>
                </tr>
                <tr>
                  <td class="datatablehead" align="center" >Einheiten-Eingabe&quot;von Hand&quot;</td>
                </tr>
                <tr>
                  <td class="fieldnormallight" align="center">
                    <form method="post" action="./main.php?modul=scan_editor">
			<input type="hidden" name="scanart" value="unit">
                      <br />
                      <input type="text" name="galakoord" size="4" maxlength="4" value="<?=$coords_gala?>" />
                      :
                      <input type="text" name="planetkoord" size="2" maxlength="2" value="<?=$coords_planet?>" />
                      <input type="submit" name="manuell" value="Manuell erfassen" />
                    </form>
                  </td>
                </tr>
                <tr>
                  <td class="datatablehead" align="center" >Gesch&uuml;tz-Eingabe&quot;von Hand&quot;</td>
                </tr>
                <tr>
                  <td class="fieldnormallight" align="center" >
                    <form method="post" action="./main.php?modul=scan_editor">
			<input type="hidden" name="scanart" value="g">
                      <br />
                      <font size="-1">
                      <input type="text" name="galakoord" size="4" maxlength="4" value="<?=$coords_gala?>" />
                      :
                      <input type="text" name="planetkoord" size="2" maxlength="2" value="<?=$coords_planet?>" />
                      <input type="submit" name="manuell" value="Manuell erfassen" />
                      <br />
                      </font>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td align="center" class="datatablehead">Milit&auml;r-Eingabe&quot;von Hand&quot;</td>
                </tr>
		<tr>
                  <td class="fieldnormallight" align="center" >
                    <form method="post" action="./main.php?modul=scan_editor">
			<input type="hidden" name="scanart" value="mili">
                      <br />
                      <input type="text" name="galakoord" size="4" maxlength="4" value="<?=$coords_gala?>" />
                      :
                      <input type="text" name="planetkoord" size="2" maxlength="2" value="<?=$coords_planet?>" />
                      <input type="submit" name="manuell" value="Manuell erfassen" />
                    </form>
                  </td>
                </tr>
              </table>
            </td>
            <td width="67%" valign="top">
              <table width="100%" cellspacing="2">
                <tr>
                  <td class="datatablehead">Daten aus GN einf&uuml;gen(Clipboard)</td>
                </tr>
                <tr>
                  <td bgcolor="#ccccec"><font color="#303030" size="-1"><a href="help/scaneingabe.html">weiter
                    Infos und Hilfe zum Thema &quot;Scans ...&quot;</a></font></td>
                </tr>
                <tr>
                  <td class="fieldnormallight">
                    <form action="./main.php" method="post">
                      <input type="hidden" name="modul2" value="scan" />
                      <input type="hidden" name="action" value="addscan" />
                      <textarea cols="50" rows="25" name="txtScan"></textarea>
                      <br />
                      <input type="submit" value="Speichern" />
                    </form>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
  </td></tr>
</table>