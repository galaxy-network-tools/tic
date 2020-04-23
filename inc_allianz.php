<!-- START: inc_allianz -->
<?php
	if (!isset($allianz)) $allianz = $Benutzer['allianz'];
	if (!isset($_GET['orderby'])) $_GET['orderby'] = "sektor";
	if (!isset($_GET['orderdir'])) $_GET['orderdir'] = "asc";
	$orderdir_new = ($_GET['orderdir'] == "asc"?"desc":"asc");
?>
<center>
	<h2>Allianz-&Uuml;bersicht</h2>
<?php
	if (isset($_GET['metanr'])) $_SESSION['metanr'] = $_GET['metanr'];
	if (!isset($_SESSION['metanr'])) $_SESSION['metanr'] = $Benutzer['ticid'];
	$SQL_Query = "SELECT * FROM gn4vars WHERE name='ticeb' ORDER BY value;";
	$SQL_Result_metas = tic_mysql_query($SQL_Query, $SQL_DBConn) or $error_code = 4;
	for ($m=0; $m<mysql_num_rows($SQL_Result_metas); $m++) {
		$MetaNummer = mysql_result($SQL_Result_metas, $m, 'ticid');
		$MetaName = mysql_result($SQL_Result_metas, $m, 'value');
		if ($MetaNummer == $_SESSION['metanr'])
			echo "		&nbsp;<b>".$MetaName."</b>&nbsp;\n";
		else
			echo "		&nbsp;<a href=\"main.php?modul=allianz&amp;metanr=".$MetaNummer."\">".$MetaName."</a>&nbsp;\n";
	}
	mysql_free_result($SQL_Result_metas);

	echo "		<br />\n";
	foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
		if ($AllianzInfo[$AllianzNummer]['meta'] == $_SESSION['metanr']) {
			if ($AllianzInfo[$allianz]['meta'] != $_SESSION['metanr']) $allianz = $AllianzNummer;
			if ($AllianzNummer == $allianz)
				echo "	&nbsp;<b>[ ".$AllianzInfo[$AllianzNummer]['tag']." ] ".$AllianzNummerName."</b>&nbsp;\n";
			else
				echo "	&nbsp;<a href=\"main.php?modul=allianz&amp;allianz=".$AllianzNummer."\">[ ".$AllianzTag[$AllianzNummer]." ]</a>&nbsp;\n";
		}
	}
?>
	<table width="100%">
		<colgroup>
			<col width="55" />
			<col width="*" />
			<col width="75" />
			<col width="75" />
			<col width="75" />
			<col width="75" />
			<col width="20%" />
			<col width="15%" />
		</colgroup>
		<tr class="datatablehead">
			<th><a class="<?=($_GET['orderby'] == "sektor" ? "datatablesortselected":"datatablesort")?>" href="main.php?modul=allianz&amp;allianz=<?=$allianz?>&amp;orderby=sektor&amp;orderdir=<?=$orderdir_new?>">Sektor</a></th>
			<th><a class="<?=($_GET['orderby'] == "rang" ? "datatablesortselected":"datatablesort")?>" href="main.php?modul=allianz&amp;allianz=<?=$allianz?>&amp;orderby=rang&amp;orderdir=<?=$orderdir_new?>">Rang</a> / <a class="<?=($_GET['orderby'] == "name" ? "datatablesortselected":"datatablesort")?>" href="main.php?modul=allianz&amp;orderby=name&amp;orderdir=<?=$orderdir_new?>">Name</a></th>
			<th><a class="<?=($_GET['orderby'] == "mexen" ? "datatablesortselected":"datatablesort") ?>" href="main.php?modul=allianz&amp;allianz=<?=$allianz?>&amp;orderby=mexen&amp;orderdir=<?=$orderdir_new?>">M-Exen</a></th>
			<th><a class="<?=($_GET['orderby'] == "kexen" ? "datatablesortselected":"datatablesort") ?>" href="main.php?modul=allianz&amp;allianz=<?=$allianz?>&amp;orderby=kexen&amp;orderdir=<?=$orderdir_new?>">K-Exen</a></th>
			<th><a class="<?=($_GET['orderby'] == "svs" ? "datatablesortselected":"datatablesort") ?>" href="main.php?modul=allianz&amp;allianz=<?=$allianz?>&amp;orderby=svs&amp;orderdir=<?=$orderdir_new?>">SVs</a></th>
			<th><a class="<?=($_GET['orderby'] == "sbs" ? "datatablesortselected":"datatablesort")?>" href="main.php?modul=allianz&amp;allianz=<?=$allianz?>&amp;orderby=sbs&amp;orderdir=<?=$orderdir_new?>">SBs</a></th>
			<th><a class="<?=($_GET['orderby'] == "stype" ? "datatablesortselected":"datatablesort")?>" href="main.php?modul=allianz&amp;allianz=<?=$allianz?>&amp;orderby=stype&amp;orderdir=<?=$orderdir_new?>">Scantyp</a></th>
			<th><a class="<?=($_GET['orderby'] == "login" ? "datatablesortselected":"datatablesort")?>" href="main.php?modul=allianz&amp;allianz=<?=$allianz?>&amp;orderby=login&amp;orderdir=<?=$orderdir_new?>">Last Login</a></th>
		</tr>
<?
	if($_GET['orderdir'] == "desc")
		$orderdir = "DESC";
	else
		$orderdir = "ASC";

	if ($_GET['orderby'] == "rang") {
		$orderstring = "a.rang ".$orderdir.", a.galaxie , a.planet";
	} elseif ($_GET['orderby'] == "name") {
		$orderstring = "a.name ".$orderdir;
	} elseif ($_GET['orderby'] == "svs") {
		$orderstring = "a.svs ".$orderdir.", a.galaxie, a.planet";
	} elseif ($_GET['orderby'] == "sbs") {
		 $orderstring = "a.sbs ".$orderdir.", a.galaxie, a.planet";
	} elseif ($_GET['orderby'] == "login") {
		$orderstring= "a.lastlogin ".$orderdir.", a.galaxie, a.planet";
	} elseif ($_GET['orderby'] == "mexen") {
		$orderstring = "b.me ".$orderdir.", a.galaxie, a.planet";;
	} elseif ($_GET['orderby'] == "kexen") {
		$orderstring = "b.ke ".$orderdir.", a.galaxie, a.planet";;
	} elseif ($_GET['orderby'] == "stype") {
		$orderstring = "a.scantyp ".$orderdir.", a.galaxie, a.planet";;
	} else {
		$orderstring = "a.galaxie ".$orderdir.", a.planet ".$orderdir;
	}

	$gesamt_exen_m = 0;
	$gesamt_exen_k = 0;
	$sb_spieler = 0;
	$gesamt_sbs = 0;
	$SQL_Result = tic_mysql_query('SELECT a.id, a.name, a.galaxie, a.planet, a.rang, a.scantyp, a.lastlogin, a.umod, a.svs, a.sbs, b.me, b.ke FROM `gn4accounts` as a LEFT JOIN `gn4scans` as b ON(a.galaxie = b.rg AND a.planet = b.rp AND b.type=0) WHERE a.allianz="'.$allianz.'" ORDER BY '.$orderstring.';', __FILE__, __LINE__) or $error_code = 4;
	$SQL_Num = mysql_num_rows($SQL_Result);
	for ($n = 0; $n < $SQL_Num; $n++) {
		$zusatz = 'normal';
		$lastlogin = mysql_result($SQL_Result, $n, 'lastlogin');
		if ($lastlogin == "" || $lastlogin == "0000-00-00" || $lastlogin == 0 || $lastlogin < (time() - (3 * 24 * 3600)) ) {
			$zusatz = 'inactive';
		}
		if (mysql_result($SQL_Result, $n, 'umod') != '') {
			$zusatz = 'umode';
		}
		if (mysql_result($SQL_Result, $n, 'id') == $Benutzer['id']) $zusatz = 'myself';

		if(mysql_result($SQL_Result, $n, 'me') != "")
			$exen_m = mysql_result($SQL_Result, $n, 'me');
		else
			$exen_m = "0";
		if(mysql_result($SQL_Result, $n, 'ke') != "")
			$exen_k = mysql_result($SQL_Result, $n, 'ke');
		else
			$exen_k = "0";

		$koord_g = mysql_result($SQL_Result, $n, 'galaxie');
		$koord_p = mysql_result($SQL_Result, $n, 'planet');

		echo "		<tr>\n";
		echo "			<td class=\"field".$zusatz."light\" align=\"center\"><a href=\"main.php?modul=showgalascans&amp;xgala=".$koord_g."&amp;xplanet=".$koord_p."&amp;displaymode=0\">".$koord_g.":".$koord_p."</a></td>\n";
		echo "			<td class=\"field".$zusatz."dark\"><img src=\"".$RangImage[mysql_result($SQL_Result, $n, 'rang')]."\" width=\"20\" height=\"20\" border=\"0\" alt=\"".$RangName[mysql_result($SQL_Result, $n, 'rang')]."\" title=\"".$RangName[mysql_result($SQL_Result, $n, 'rang')]."\" align=\"middle\" /> <a href=\"main.php?modul=anzeigen&amp;id=".mysql_result($SQL_Result, $n, 'id')."\">".mysql_result($SQL_Result, $n, 'name')."</a></td>\n";
		echo "			<td class=\"field".$zusatz."light\" align=\"right\">".ZahlZuText($exen_m)."</td>\n";
		echo "			<td class=\"field".$zusatz."dark\" align=\"right\">".ZahlZuText($exen_k)."</td>\n";
		echo "			<td class=\"field".$zusatz."light\" align=\"right\">".ZahlZuText(mysql_result($SQL_Result, $n, 'svs'))."</td>\n";
		echo "			<td class=\"field".$zusatz."dark\" align=\"right\">".ZahlZuText(mysql_result($SQL_Result, $n, 'sbs'))."</td>\n";
		echo "			<td class=\"field".$zusatz."light\">".$ScanTyp[mysql_result($SQL_Result, $n, 'scantyp')]."</td>\n";
		echo "			<td class=\"field".$zusatz."dark\" align=\"center\">".($lastlogin?strftime("%d.%m.%Y %H:%M", $lastlogin):"nie")."</td>\n";
		echo "		</tr>\n";
		$gesamt_exen_m = $gesamt_exen_m + $exen_m;
		$gesamt_exen_k = $gesamt_exen_k + $exen_k;
		$gesamt_sbs = $gesamt_sbs + mysql_result($SQL_Result, $n, 'sbs');
		if (mysql_result($SQL_Result, $n, 'sbs') > 0) $sb_spieler++;
	}
	if ($sb_spieler > 0)
		$durchschnitt_sbs = IntVal($gesamt_sbs / $sb_spieler);
	else
		$durchschnitt_sbs = 0;
?>
		<tr>
			<td colspan="2" align="right"><b>Extraktoren der Allianz:</b></td>
			<td class="fieldnormallight" align="right"><?=ZahlZuText($gesamt_exen_m)?></td>
			<td class="fieldnormaldark" align="right"><?=ZahlZuText($gesamt_exen_k)?></td>
			<td></td>
			<td class="fieldnormaldark" align="right"><?=ZahlZuText($durchschnitt_sbs)?></td>
			<td colspan="2" align="left"><b>Scanblocker pro Spieler</b></td>
		</tr>
	</table>
	<br />
	<b>(<u class="fieldumodelight">Blau</u> makierte Spieler sind im Urlaubs-Modus)</b><br />
	<b>(<u class="fieldinactivelight">Rot</u> makierte Spieler waren seit min. drei Tagen nicht online)</b>
</center>
<!-- ENDE: inc_allianz -->
