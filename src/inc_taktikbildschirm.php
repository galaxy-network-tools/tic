<!-- START: inc_taktikbildschirm -->
<?php
	include('./helper_scans.inc.php');

	function trimname($name) {
		if (strlen($name) > 12)
			return substr($name, 0, 10)."..";
		return $name;
	}

/*** Modi *********
* 1 -> Incomings  *
* 2 -> Flotten    *
* 3 -> Alle       *
* 4 -> Allianz    *
* 5 -> Galaxie    *
******************/

	$mode = (isset($_GET['mode']) && $_GET['mode'] >= 1 && $_GET['mode'] <= 5)?$_GET['mode']:"1";
	$allianz = isset($_GET['allianz'])?$_GET['allianz']:$Benutzer['allianz'];
	if (isset($_GET['md_orderby']) && ($_GET['md_orderby'] == "sektor" || $_GET['md_orderby'] == "rang" || $_GET['md_orderby'] == "name")) $_SESSION['taktik_orderby'] = $_GET['md_orderby'];
	$md_orderby = (isset($_SESSION['taktik_orderby']))?$_SESSION['taktik_orderby']:"sektor";
	if (isset($_GET['md_orderdir']) && ($_GET['md_orderdir'] == "asc" || $_GET['md_orderdir'] == "desc")) $_SESSION['taktik_orderdir'] = $_GET['md_orderdir'];
	$md_orderdir = (isset($_SESSION['taktik_orderdir']))?$_SESSION['taktik_orderdir']:"asc";
	$md_orderdir_new = ($md_orderdir == "asc"?"desc":"asc");

	$scripturl="./main.php?modul=taktikbildschirm&mode=".$mode.($mode == 4?"&allianz=".$allianz:"");
	switch ($mode) {
		case 1:
			echo "	<font size=\"+1\">[Taktik - Incs und Defs]</font><br>\n";
			break;
		case 2:
			echo "	<font size=\"+1\">[Taktik - Flottenbewegungen]</font><br>\n";
			break;
		case 3:
			echo "	<font size=\"+1\">[Taktik - Alle]</font><br>\n";
			break;
		case 4:
			echo "	<font size=\"+1\">[Taktik - Allianzen]</font><br>\n";
			break;
		case 5:
			echo "	<font size=\"+1\">[Taktik - Galaxie ".$Benutzer['galaxie']." ]</font>\n";
			break;
	}

	switch ($mode) {
		case 1:
		case 2:
		case 3:
		case 4:
			if (isset($_GET['metanr'])) $_SESSION['metanr'] = $_GET['metanr'];
			if (!isset($_SESSION['metanr'])) $_SESSION['metanr'] = $Benutzer['ticid'];
			$SQL_Query = "SELECT id as ticid, name as value FROM gn4meta ORDER BY name;";
			$SQL_Result_metas = tic_mysql_query($SQL_Query, $SQL_DBConn) or $error_code = 4;
			for ($m=0; $m<mysql_num_rows($SQL_Result_metas); $m++) {
				$MetaNummer = mysql_result($SQL_Result_metas, $m, 'ticid');
				$MetaName = mysql_result($SQL_Result_metas, $m, 'value');
				if ($MetaNummer == $_SESSION['metanr'])
					echo "		&nbsp;<font size=\"+1\"><b><nobr>".$MetaName."<nobr></font></b>&nbsp;\n";
				else
					echo "		&nbsp;<a href=\"./main.php?modul=taktikbildschirm&mode=".$mode."&metanr=".$MetaNummer."\"><nobr> ".$MetaName." <nobr></a>&nbsp;\n";
			}
			mysql_free_result($SQL_Result_metas);
			break;
	}
	if ($mode == 4) {
		echo "		<br>\n";
		foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
			if ($AllianzInfo[$AllianzNummer]['meta'] == $_SESSION['metanr']) {
				if ($AllianzInfo[$allianz]['meta'] != $_SESSION['metanr']) $allianz = $AllianzNummer;
				if ($AllianzNummer == $allianz)
					echo "	&nbsp;<font size=\"+1\"><b><nobr>[ ".$AllianzInfo[$AllianzNummer]['tag']." ] ".$AllianzNummerName."<nobr></font></b>&nbsp;\n";
				else
					echo "	&nbsp;<a href=\"./main.php?modul=taktikbildschirm&mode=4&allianz=".$AllianzNummer."\"><nobr>[ ".$AllianzTag[$AllianzNummer]." ]<nobr></a>&nbsp;\n";
			}
		}
	}

	if ($md_orderby == "sektor") {
		$orderstring = "galaxie ".$md_orderdir.", planet ".$md_orderdir;
	} elseif ($md_orderby == "rang") {
		$orderstring = "rang ".$md_orderdir.", galaxie , planet";
	} elseif ($md_orderby == "name") {
		$orderstring = "name ".$md_orderdir;
	}

	switch ($mode) {
		case 1:
		case 2:
		case 3:
			$SQL_Query = "
				SELECT gn4accounts.* FROM gn4accounts
				LEFT JOIN gn4allianzen ON gn4accounts.allianz = gn4allianzen.id
				WHERE gn4allianzen.ticid='".$_SESSION['metanr']."' ORDER BY ".$orderstring.";";
			break;
		case 4:
			$SQL_Query = "SELECT * FROM gn4accounts WHERE allianz=".$allianz." ORDER BY ".$orderstring.";";
			break;
		case 5:
			$SQL_Query = "SELECT * FROM gn4accounts WHERE galaxie=".$Benutzer['galaxie']." ORDER BY ".$orderstring.";";
			break;
	}

	$SQL_Result_user = tic_mysql_query($SQL_Query, $SQL_DBConn); // or error("Error while bilding 'taktik' (step 1).<br>".$SQL_Query, ERROR_SQL, false);
	$SQL_Num_user = mysql_num_rows($SQL_Result_user);

	$time_online = time() - 300;

	include('./inc_taktikbildschirm'.$Benutzer['tcausw'].'.php');
?>
<!-- ENDE: inc_taktikbildschirm -->
