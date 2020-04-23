<?php

function sek_edit() {
	global $SQL_DBConn;

    $qry = 'pts, s, d, me, ke, a';

    $SQL_Result2 = tic_mysql_query('SELECT '.$qry.' FROM `gn4scans` WHERE rg="'.$_POST['galakoord'].'" AND rp="'.$_POST['planetkoord'].'" AND type="0";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
    $SQL_Num = mysql_num_rows($SQL_Result2);
    if ($SQL_Num == 0) {
        // keine scans da

        $scan_rn=gnuser($_POST['galakoord'], $_POST['planetkoord']);

        $scan_pts =  0;
        $scan_s   =  0;
        $scan_d   =  0;
        $scan_me  =  0;
        $scan_ke  =  0;
        $scan_a   =  0;

    } else {
        // scan ist in db vorhanden
        $scan_rn   =  gnuser($_POST['galakoord'], $_POST['planetkoord']);

        $scan_pts =  mysql_result($SQL_Result2, 0, 'pts');
        $scan_s   =  mysql_result($SQL_Result2, 0, 's');
        $scan_d   =  mysql_result($SQL_Result2, 0, 'd');
        $scan_me  =  mysql_result($SQL_Result2, 0, 'me');
        $scan_ke  =  mysql_result($SQL_Result2, 0, 'ke');
        $scan_a   =  mysql_result($SQL_Result2, 0, 'a');
    }
?>

<form name="form1" method="post" action="./main.php?modul=scans">
<input type="hidden" name="action" value="scan_edit" />
<input type="hidden" name="scanart" value="sek">
<input type="hidden" name="galakoord" value="<?=$_POST['galakoord']?>" />
<input type="hidden" name="planetkoord" value="<?=$_POST['planetkoord']?>" />
<table align="center">
    <tr>
      <td class="datatablehead" colspan="2">Manuelle Sektorbearbeitung (<?echo $_POST['galakoord'].':'.$_POST['planetkoord']; ?>)</td>
    </tr>
    <tr class="fieldnormaldark">
      <td align="left">Name:</td><td><input type="text" name="trn" value="<?=$scan_rn?>" /></td>
    </tr>
    <tr class="fieldnormallight">
      <td align="left">Punkte:</td>
      <td><input type="text" name="tpts" value="<?=$scan_pts?>" /></td>
    </tr>
    <tr class="fieldnormaldark">
      <td align="left">Schiffe:</td>
      <td><input type="text" name="ts" value="<?=$scan_s?>" /></td>
    </tr>
    <tr class="fieldnormallight">
      <td align="left">Defensiveinheiten:</td>
      <td><input type="text" name="td" value="<?=$scan_d?>" /></td>
    </tr>
    <tr class="fieldnormaldark">
      <td align="left">Metall Exen:</td>
      <td><input type="text" name="tme" value="<?=$scan_me?>" /></td>
    </tr>
    <tr class="fieldnormallight">
      <td align="left">Kristall Exen:</td>
      <td><input type="text" name="tke" value="<?=$scan_ke?>" /></td>
    </tr>
    <tr class="fieldnormaldark">
      <td align="left">Asteroiden:</td>
      <td><input type="text" name="ta" value="<?=$scan_a?>" /></td>
    </tr>
    <tr>
      <td colspan="2" class="datatablefoot"><input type="submit" name="Abschicken" value="&Auml;nderungen speichern"  /></td>
    </tr>
  </table>
</form>
<?php
}

function unit_edit() {
	global $SQL_DBConn;

    $qry = 'sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfko, sfka, sfsu';

    $SQL_Result2 = tic_mysql_query('SELECT '.$qry.' FROM `gn4scans` WHERE rg="'.$_POST['galakoord'].'" AND rp="'.$_POST['planetkoord'].'" AND type="1";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
    $SQL_Num = mysql_num_rows($SQL_Result2);
    if ($SQL_Num == 0) {
        // keine scans da

        $scan_rn=gnuser($_POST['galakoord'], $_POST['planetkoord']);

	$scan_sfj = 0;
	$scan_sfb = 0;
	$scan_sff = 0;
	$scan_sfz = 0;
	$scan_sfkr = 0;
	$scan_sfsa = 0;
	$scan_sft = 0;
	$scan_sfka = 0;
	$scan_sfsu = 0;

    } else {
        // scan ist in db vorhanden
        $scan_rn   =  gnuser($_POST['galakoord'], $_POST['planetkoord']);

	$scan_sfj = mysql_result($SQL_Result2, 0, 'sfj');
	$scan_sfb = mysql_result($SQL_Result2, 0, 'sfb');
	$scan_sff = mysql_result($SQL_Result2, 0, 'sff');
	$scan_sfz = mysql_result($SQL_Result2, 0, 'sfz');
	$scan_sfkr = mysql_result($SQL_Result2, 0, 'sfkr');
	$scan_sfsa = mysql_result($SQL_Result2, 0, 'sfsa');
	$scan_sft = mysql_result($SQL_Result2, 0, 'sft');
	$scan_sfka = mysql_result($SQL_Result2, 0, 'sfka');
	$scan_sfsu = mysql_result($SQL_Result2, 0, 'sfsu');

    }
?>
<form name="form1" method="post" action="./main.php?modul=scans">
  <input type="hidden" name="action" value="scan_edit" />
  <input type="hidden" name="scanart" value="unit">
  <input type="hidden" name="galakoord" value="<?=$_POST['galakoord']?>" />
  <input type="hidden" name="planetkoord" value="<?=$_POST['planetkoord']?>" />
  <input type="hidden" name="txtScanGalaxie" value="<?=$_POST['galakoord']?>" />
  <input type="hidden" name="txtScanPlanet" value="<?=$_POST['planetkoord']?>" />
  <table align="center">
    <tr>
      <td colspan="4" class="datatablehead">Manuelle Unitbearbeitung (<?echo $_POST['galakoord'].':'.$_POST['planetkoord']; ?>)</td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Name:</td><td colspan="3"><input type="text" name="trn" value="<?=$scan_rn?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>J&auml;ger &quot;Leo&quot;:</td>
      <td><input type="text" name="tsfj" size="8" value="<?=$scan_sfj?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Bomber &quot;Aquilae&quot;:</td>
      <td><input type="text" name="tsfb" size="8" value="<?=$scan_sfb?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Fregatten &quot;Fornax&quot;:</td>
      <td><input type="text" name="tsff" size="8" value="<?=$scan_sff?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Zerst&ouml;rer &quot;Draco&quot;:</td>
      <td><input type="text" name="tsfz" size="8" value="<?=$scan_sfz?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Kreuzer &quot;Goron&quot;:</td>
      <td><input type="text" name="tsfkr" size="8" value="<?=$scan_sfkr?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Schlachtschiffe &quot;Pentalin&quot;:</td>
      <td><input type="text" name="tsfsa" size="8" value="<?=$scan_sfsa?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Tr&auml;ger &quot;Zenit&quot;:</td>
      <td><input type="text" name="tsft" size="8" value="<?=$scan_sft?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Kaperschiffe &quot;Kleptor&quot;&quot;:</td>
      <td><input type="text" name="tsfka" size="8" value="<?=$scan_sfka?>" /> </td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Schutzschiffe &quot;Cancri&quot;:</td>
      <td><input type="text" name="tsfsu" size="8" value="<?=$scan_sfsu?>" /></td>
    </tr>
    <tr class="datatablefoot">
      <td colspan="4"><input type="submit" name="Abschicken" value="&Auml;nderungen speichern" /></td>
    </tr>
  </table>
</form>
<?php
}

function g_edit() {
	global $SQL_DBConn;

    $qry =        'glo, glr, gmr, gsr, ga';

    $SQL_Result2 = tic_mysql_query('SELECT '.$qry.' FROM `gn4scans` WHERE rg="'.$_POST['galakoord'].'" AND rp="'.$_POST['planetkoord'].'" AND type="3";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
    $SQL_Num = mysql_num_rows($SQL_Result2);
    if ($SQL_Num == 0) {
        // keine scans da

        $scan_rn=gnuser($_POST['galakoord'], $_POST['planetkoord']);

        $scan_glo =  0;
        $scan_glr =  0;
        $scan_gmr =  0;
        $scan_gsr =  0;
        $scan_ga  =  0;

    } else {
        // scan ist in db vorhanden
        $scan_rn   =  gnuser($_POST['galakoord'], $_POST['planetkoord']);

        $scan_glo =  mysql_result($SQL_Result2, 0, 'glo');
        $scan_glr =  mysql_result($SQL_Result2, 0, 'glr');
        $scan_gmr =  mysql_result($SQL_Result2, 0, 'gmr');
        $scan_gsr =  mysql_result($SQL_Result2, 0, 'gsr');
        $scan_ga  =  mysql_result($SQL_Result2, 0, 'ga');
    }
?>
<form name="form1" method="post" action="./main.php?modul=scans">
  <input type="hidden" name="action" value="scan_edit" />
  <input type="hidden" name="scanart" value="g">
  <input type="hidden" name="galakoord" value="<?=$_POST['galakoord']?>" />
  <input type="hidden" name="planetkoord" value="<?=$_POST['planetkoord']?>" />
  <input type="hidden" name="txtScanGalaxie" value="<?=$_POST['galakoord']?>" />
  <input type="hidden" name="txtScanPlanet" value="<?=$_POST['planetkoord']?>" />
  <table align="center">
    <tr>
      <td colspan="2" class="datatablehead">Manuelle Gesch&uuml;tzbearbeitung (<?echo $_POST['galakoord'].':'.$_POST['planetkoord']; ?>)</td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Name:</td><td><input type="text" name="trn" value="<?=$scan_rn?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Leichtes Orbitalgesch&uuml;tz &quot;Rubium&quot;:</td>
      <td><input type="text" name="tglo" value="<?=$scan_glo?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Leichtes Raumgesch&uuml;tz &quot;Pulsar&quot;:</td>
      <td><input type="text" name="tglr" value="<?=$scan_glr?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Mittleres Raumgesch&uuml;tz &quot;Coon&quot;:</td>
      <td><input type="text" name="tgmr" value="<?=$scan_gmr?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Schweres Raumgesch&uuml;tz &quot;Centurion&quot;:</td>
      <td><input type="text" name="tgsr" value="<?=$scan_gsr?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Abfangj&auml;ger &quot;Horus&quot;:</td>
      <td><input type="text" name="tga" value="<?=$scan_ga?>" /></td>
    </tr>
    <tr class="datatablefoot">
      <td colspan="2"><input type="submit" name="Abschicken" value="&Auml;nderungen speichern" /></td>
    </tr>
  </table>
  </form>
<?php
}

function mili_edit() {
	global $SQL_DBConn;

    $qry =        'sf0j, sf0b, sf0f, sf0z, sf0kr, sf0sa, sf0t, sf0ko, sf0ka, sf0su,';
    $qry = $qry . 'sf1j, sf1b, sf1f, sf1z, sf1kr, sf1sa, sf1t, sf1ko, sf1ka, sf1su,';
    $qry = $qry . 'sf2j, sf2b, sf2f, sf2z, sf2kr, sf2sa, sf2t, sf2ko, sf2ka, sf2su';

    $SQL_Result2 = tic_mysql_query('SELECT '.$qry.' FROM `gn4scans` WHERE rg="'.$_POST['galakoord'].'" AND rp="'.$_POST['planetkoord'].'" AND type="2";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
    $SQL_Num = mysql_num_rows($SQL_Result2);
    if ($SQL_Num == 0) {
        // keine scans da

        $scan_rn=gnuser($_POST['galakoord'], $_POST['planetkoord']);

        // Jï¿½er
        $scan_sf0j =  0;
        $scan_sf1j =  0;
        $scan_sf2j =  0;

        // bomber
        $scan_sf0b =  0;
        $scan_sf1b =  0;
        $scan_sf2b =  0;

        // fregs
        $scan_sf0f =  0;
        $scan_sf1f =  0;
        $scan_sf2f =  0;

        // zerries
        $scan_sf0z =  0;
        $scan_sf1z =  0;
        $scan_sf2z =  0;

        // kreuzer
        $scan_sf0kr = 0;
        $scan_sf1kr = 0;
        $scan_sf2kr = 0;

        // schlachter
        $scan_sf0sa = 0;
        $scan_sf1sa = 0;
        $scan_sf2sa = 0;

        // trï¿½er
        $scan_sf0t  = 0;
        $scan_sf1t  = 0;
        $scan_sf2t  = 0;

        // Kaper
        $scan_sf0ka = 0;
        $scan_sf1ka = 0;
        $scan_sf2ka = 0;

        // schutzies
        $scan_sf0su = 0;
        $scan_sf1su = 0;
        $scan_sf2su = 0;
    } else {
        // scan ist in db vorhanden
        $scan_rn   =  gnuser($_POST['galakoord'], $_POST['planetkoord']);

        // Jï¿½er
        $scan_sf0j =  mysql_result($SQL_Result2, 0, 'sf0j');
        $scan_sf1j =  mysql_result($SQL_Result2, 0, 'sf1j');
        $scan_sf2j =  mysql_result($SQL_Result2, 0, 'sf2j');

        // bomber
        $scan_sf0b =  mysql_result($SQL_Result2, 0, 'sf0b');
        $scan_sf1b =  mysql_result($SQL_Result2, 0, 'sf1b');
        $scan_sf2b =  mysql_result($SQL_Result2, 0, 'sf2b');

        // fregs
        $scan_sf0f =  mysql_result($SQL_Result2, 0, 'sf0f');
        $scan_sf1f =  mysql_result($SQL_Result2, 0, 'sf1f');
        $scan_sf2f =  mysql_result($SQL_Result2, 0, 'sf2f');

        // zerries
        $scan_sf0z =  mysql_result($SQL_Result2, 0, 'sf0z');
        $scan_sf1z =  mysql_result($SQL_Result2, 0, 'sf1z');
        $scan_sf2z =  mysql_result($SQL_Result2, 0, 'sf2z');

        // kreuzer
        $scan_sf0kr = mysql_result($SQL_Result2, 0, 'sf0kr');
        $scan_sf1kr = mysql_result($SQL_Result2, 0, 'sf1kr');
        $scan_sf2kr = mysql_result($SQL_Result2, 0, 'sf2kr');

        // schlachter
        $scan_sf0sa = mysql_result($SQL_Result2, 0, 'sf0sa');
        $scan_sf1sa = mysql_result($SQL_Result2, 0, 'sf1sa');
        $scan_sf2sa = mysql_result($SQL_Result2, 0, 'sf2sa');

        // trï¿½er
        $scan_sf0t  = mysql_result($SQL_Result2, 0, 'sf0t');
        $scan_sf1t  = mysql_result($SQL_Result2, 0, 'sf1t');
        $scan_sf2t  = mysql_result($SQL_Result2, 0, 'sf2t');

        // Kaper
        $scan_sf0ka = mysql_result($SQL_Result2, 0, 'sf0ka');
        $scan_sf1ka = mysql_result($SQL_Result2, 0, 'sf1ka');
        $scan_sf2ka = mysql_result($SQL_Result2, 0, 'sf2ka');

        // schutzies
        $scan_sf0su = mysql_result($SQL_Result2, 0, 'sf0su');
        $scan_sf1su = mysql_result($SQL_Result2, 0, 'sf1su');
        $scan_sf2su = mysql_result($SQL_Result2, 0, 'sf2su');
    }
?>
<form name="form1" method="post" action="./main.php?modul=scans">
  <input type="hidden" name="action" value="scan_edit" />
  <input type="hidden" name="scanart" value="mili">
  <input type="hidden" name="galakoord" value="<?=$_POST['galakoord']?>" />
  <input type="hidden" name="planetkoord" value="<?=$_POST['planetkoord']?>" />
  <input type="hidden" name="txtScanGalaxie" value="<?=$_POST['galakoord']?>" />
  <input type="hidden" name="txtScanPlanet" value="<?=$_POST['planetkoord']?>" />
  <table align="center">
    <tr>
      <td colspan="4" class="datatablehead">Manuelle Milit&auml;rbearbeitung (<?echo $_POST['galakoord'].':'.$_POST['planetkoord']; ?>)</td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Name:</td><td colspan="3"><input type="text" name="trn" value="<?=$scan_rn?>" /></td>
    </tr>
    <tr class="fieldnormallight" style="font-weight:bold;">
      <td></td>
      <td>Orbit</td>
      <td>Flotte 1</td>
      <td>Flotte 2</td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>J&auml;ger &quot;Leo&quot;:</td>
      <td><input type="text" name="tsf0j" size="8" value="<?=$scan_sf0j?>" /></td>
      <td><input type="text" name="tsf1j" size="8" value="<?=$scan_sf1j?>" /></td>
      <td><input type="text" name="tsf2j" size="8" value="<?=$scan_sf2j?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Bomber &quot;Aquilae&quot;:</td>
      <td><input type="text" name="tsf0b" size="8" value="<?=$scan_sf0b?>" /></td>
      <td><input type="text" name="tsf1b" size="8" value="<?=$scan_sf1b?>" /></td>
      <td><input type="text" name="tsf2b" size="8" value="<?=$scan_sf2b?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Fregatten &quot;Fornax&quot;:</td>
      <td><input type="text" name="tsf0f" size="8" value="<?=$scan_sf0f?>" /></td>
      <td><input type="text" name="tsf1f" size="8" value="<?=$scan_sf1f?>" /></td>
      <td><input type="text" name="tsf2f" size="8" value="<?=$scan_sf2f?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Zerst&ouml;rer &quot;Draco&quot;:</td>
      <td><input type="text" name="tsf0z" size="8" value="<?=$scan_sf0z?>" /></td>
      <td><input type="text" name="tsf1z" size="8" value="<?=$scan_sf1z?>" /></td>
      <td><input type="text" name="tsf2z" size="8" value="<?=$scan_sf2z?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Kreuzer &quot;Goron&quot;:</td>
      <td><input type="text" name="tsf0kr" size="8" value="<?=$scan_sf0kr?>" /></td>
      <td><input type="text" name="tsf1kr" size="8" value="<?=$scan_sf1kr?>" /></td>
      <td><input type="text" name="tsf2kr" size="8" value="<?=$scan_sf2kr?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Schlachtschiffe &quot;Pentalin&quot;:</td>
      <td><input type="text" name="tsf0sa" size="8" value="<?=$scan_sf0sa?>" /></td>
      <td><input type="text" name="tsf1sa" size="8" value="<?=$scan_sf1sa?>" /></td>
      <td><input type="text" name="tsf2sa" size="8" value="<?=$scan_sf2sa?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Tr&auml;ger &quot;Zenit&quot;:</td>
      <td><input type="text" name="tsf0t" size="8" value="<?=$scan_sf0t?>" /></td>
      <td><input type="text" name="tsf1t" size="8" value="<?=$scan_sf1t?>" /></td>
      <td><input type="text" name="tsf2t" size="8" value="<?=$scan_sf2t?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Kaperschiffe &quot;Kleptor&quot;&quot;:</td>
      <td><input type="text" name="tsf0ka" size="8" value="<?=$scan_sf0ka?>" /> </td>
      <td><input type="text" name="tsf1ka" size="8" value="<?=$scan_sf1ka?>" /></td>
      <td><input type="text" name="tsf2ka" size="8" value="<?=$scan_sf2ka?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Schutzschiffe &quot;Cancri&quot;:</td>
      <td><input type="text" name="tsf0su" size="8" value="<?=$scan_sf0su?>" /></td>
      <td><input type="text" name="tsf1su" size="8" value="<?=$scan_sf1su?>" /></td>
      <td><input type="text" name="tsf2su" size="8" value="<?=$scan_sf2su?>" /></td>
    </tr>
    <tr class="datatablefoot">
      <td colspan="4"><input type="submit" name="Abschicken" value="&Auml;nderungen speichern" /></td>
    </tr>
  </table>
</form>
<?php
}

if (isset($_POST['scanart'])) {
	if ( $_POST['galakoord'] == "" || $_POST['planetkoord'] == "" ) {
		echo '<p align="center"><b>Sorry - ohne Koordinaten geht das nicht!</b></p>';
		return;
	}
	eval($_POST['scanart']."_edit();");
}

?>