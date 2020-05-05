<!-- START: inc_scanner -->
<?php
	if (!isset($_SESSION['scanner_orderby'])) $_SESSION['scanner_orderby'] = "svs";
	if (isset($_GET['md_orderby']) && ($_GET['md_orderby'] == "svs" || $_GET['md_orderby'] == "scans")) $_SESSION['scanner_orderby'] = $_GET['md_orderby'];

	if (!isset($_SESSION['scanner_orderdir'])) $_SESSION['scanner_orderdir'] = "desc";
	if (isset($_GET['md_orderdir']) && ($_GET['md_orderdir'] == "asc" || $_GET['md_orderdir'] == "desc")) $_SESSION['scanner_orderdir'] = $_GET['md_orderdir'];
	$_SESSION['scanner_orderdir_new'] = ($_SESSION['scanner_user_orderdir'] == "asc"?"desc":"asc");

	if (!isset($_SESSION['scanner_filter'])) $_SESSION['scanner_filter'] = "meta";
	if (isset($_GET['md_filter']) && ($_GET['md_filter'] == "meta" || $_GET['md_filter'] == "all50")) $_SESSION['scanner_filter'] = $_GET['md_filter'];

	if (!isset($_SESSION['scanner_meta'])) $_SESSION['scanner_meta'] = $Benutzer['ticid'];
	if (isset($_GET['md_meta'])) $_SESSION['scanner_meta'] = $_GET['md_meta'];
?>
<center>
	<font size="+1">[&Uuml;bersicht der Scanner]</font><br>
	<a href="./main.php?modul=scanner&md_filter=meta"><?= $_SESSION['scanner_filter'] == "meta"?"<b>":"" ?>Meta<?= $_SESSION['scanner_filter'] == "meta"?"</b>":"" ?></a> -
	<a href="./main.php?modul=scanner&md_filter=all50"><?= $_SESSION['scanner_filter'] == "all50"?"<b>":"" ?>Alle Top50<?= $_SESSION['scanner_filter'] == "all50"?"</b>":"" ?></a><br />
<?php
	if ($_SESSION['scanner_filter'] == "meta") {
		$SQL_Query = "SELECT * FROM gn4vars WHERE name='ticeb' ORDER BY value;";
		$SQL_Result_metas = tic_mysql_query($SQL_Query, $SQL_DBConn);
		while ($meta = mysql_fetch_assoc($SQL_Result_metas)) {
			if ($meta['ticid'] == $_SESSION['scanner_meta'])
				echo "		&nbsp;<font size=\"+1\"><b><nobr>".$meta['value']."<nobr></font></b>&nbsp;\n";
			else
				echo "		&nbsp;<a href=\"./main.php?modul=scanner&md_meta=".$meta['ticid']."\"><nobr> ".$meta['value']." <nobr></a>&nbsp;\n";
		}
		mysql_free_result($SQL_Result_metas);
	}
?>
	<br />
	<table border="0" cellspacing="2" cellpadding="1" class="datatable">
		<colgroup>
			<?= $_SESSION['scanner_filter'] == "all50"?"<col width=\"75\">":"" ?>
			<col width="55">
			<col width="100">
			<col width="75">
			<col width="50">
			<col width="20">
			<?= $_SESSION['scanner_filter'] == "all50"?"<col width=\"75\">":"" ?>
			<col width="55">
			<col width="100">
			<col width="75">
			<col width="50">
		</colgroup>
		<tr>
			<th class="datatablehead" colspan="<?= $_SESSION['scanner_filter'] == "all50"?"5":"4" ?>"><?= $ScanTyp[1] ?></th>
			<td></td>
			<th class="datatablehead" colspan="<?= $_SESSION['scanner_filter'] == "all50"?"5":"4" ?>"><?= $ScanTyp[2] ?></th>
		</tr>
		<tr>
			<?= $_SESSION['scanner_filter'] == "all50"?"<th class=\"datatablehead\">Meta</th>":"" ?>
			<th class="datatablehead">Sektor</th>
			<th class="datatablehead">Spieler</th>
			<th class="datatablehead">
				<a href="./main.php?modul=scanner&md_orderby=svs&md_orderdir=<?=($_SESSION['scanner_orderby']=="svs"?$_SESSION['scanner_orderdir_new']:$_SESSION['scanner_orderdir'])?>"><span class="datatablesort<?=($_SESSION['scanner_orderby']=="svs"?"selected":"")?>">SVs</span></a>
			</th>
			<th class="datatablehead">
				<a href="./main.php?modul=scanner&md_orderby=scans&md_orderdir=<?=($_SESSION['scanner_orderby']=="scans"?$_SESSION['scanner_orderdir_new']:$_SESSION['scanner_orderdir'])?>"><span class="datatablesort<?=($_SESSION['scanner_orderby']=="scans"?"selected":"")?>">Scans</span></a>
			</th>
			<td></td>
			<?= $_SESSION['scanner_filter'] == "all50"?"<th class=\"datatablehead\">Meta</th>":"" ?>
			<th class="datatablehead">Sektor</th>
			<th class="datatablehead">Spieler</th>
			<th class="datatablehead">
				<a href="./main.php?modul=scanner&md_orderby=svs&md_orderdir=<?=($_SESSION['scanner_orderby']=="svs"?$_SESSION['scanner_orderdir_new']:$_SESSION['scanner_orderdir'])?>"><span class="datatablesort<?=($_SESSION['scanner_orderby']=="svs"?"selected":"")?>">SVs</span></a>
			</th>
			<th class="datatablehead">
				<a href="./main.php?modul=scanner&md_orderby=scans&md_orderdir=<?=($_SESSION['scanner_orderby']=="scans"?$_SESSION['scanner_orderdir_new']:$_SESSION['scanner_orderdir'])?>"><span class="datatablesort<?=($_SESSION['scanner_orderby']=="scans"?"selected":"")?>">Scans</span></a>
			</th>
		</tr>
<?php
	$sort = $_SESSION['scanner_orderby']." ".$_SESSION['scanner_orderdir'].", ".($_SESSION['scanner_orderby']=="svs"?"scans":"svs")." desc";
	$SQL_Query = "SELECT galaxie, planet, name, allianz, svs, umod, scans, lastlogin FROM `gn4accounts` WHERE scantyp='1'".($_SESSION['scanner_filter'] == "meta"?" and ticid='".$_SESSION['scanner_meta']."'":"")." AND svs > 0 ORDER BY ".$sort.", galaxie, planet".($_SESSION['scanner_filter'] == "meta"?"":" LIMIT 50").";";
	$SQL_Result_Scanner1 = tic_mysql_query($SQL_Query, $SQL_DBConn);
	$SQL_Query = "SELECT galaxie, planet, name, allianz, svs, umod, scans, lastlogin FROM `gn4accounts` WHERE scantyp='2'".($_SESSION['scanner_filter'] == "meta"?" and ticid='".$_SESSION['scanner_meta']."'":"")." AND svs > 0 ORDER BY ".$sort.", galaxie, planet".($_SESSION['scanner_filter'] == "meta"?"":" LIMIT 50").";";
	$SQL_Result_Scanner2 = tic_mysql_query($SQL_Query, $SQL_DBConn);

	$time_online = time() - 300;
	
	$Scanner1_row = mysql_fetch_assoc($SQL_Result_Scanner1);
	$Scanner2_row = mysql_fetch_assoc($SQL_Result_Scanner2);
	while ($Scanner1_row || $Scanner2_row) {
?>
		<tr>
<?php
		if ($Scanner1_row) {
			$color = "normal";
			if ($Scanner1_row['lastlogin'] > $time_online) $color = "scanneron";
			if ($Scanner1_row['umod'] != "") $color = "umode";
			if ($_SESSION['scanner_filter'] == "all50") echo "			<td class=\"field".$color."light\" style=\"text-align: center;\">".$AllianzInfo[$Scanner1_row['allianz']]['metaname']."</td>\n";
			echo "			<td class=\"field".$color."dark\" style=\"text-align: center;\">".$Scanner1_row['galaxie'].":".$Scanner1_row['planet']."</td>\n";
			echo "			<td class=\"field".$color."light\" style=\"text-align: left;\">[ ".$AllianzInfo[$Scanner1_row['allianz']]['tag']." ] ".$Scanner1_row['name']."</td>\n";
			echo "			<td class=\"field".$color."dark\" style=\"text-align: right;\">".$Scanner1_row['svs']."</td>\n";
			echo "			<td class=\"field".$color."light\" style=\"text-align: right;\">".$Scanner1_row['scans']."</td>\n";
		} else {
?>
			<td colspan="<?= $_SESSION['scanner_filter'] == "all50"?"5":"4" ?>"></td>
<?php
		}
?>
			<td></td>
<?php
		if ($Scanner2_row) {
			$color = "normal";
			if ($Scanner2_row['lastlogin'] > $time_online) $color = "scanneron";
			if ($Scanner2_row['umod'] != "") $color = "umode";
			if ($_SESSION['scanner_filter'] == "all50") echo "			<td class=\"field".$color."light\" style=\"text-align: center;\">".$AllianzInfo[$Scanner2_row['allianz']]['metaname']."</td>\n";
			echo "			<td class=\"field".$color."dark\" style=\"text-align: center;\">".$Scanner2_row['galaxie'].":".$Scanner2_row['planet']."</td>\n";
			echo "			<td class=\"field".$color."light\" style=\"text-align: left;\">[ ".$AllianzInfo[$Scanner2_row['allianz']]['tag']." ] ".$Scanner2_row['name']."</td>\n";
			echo "			<td class=\"field".$color."dark\" style=\"text-align: right;\">".$Scanner2_row['svs']."</td>\n";
			echo "			<td class=\"field".$color."light\" style=\"text-align: right;\">".$Scanner2_row['scans']."</td>\n";
		} else {
?>
			<td colspan="<?= $_SESSION['scanner_filter'] == "all50"?"5":"4" ?>"></td>
<?php
		}
?>
		</tr>
<?php
		$Scanner1_row = mysql_fetch_assoc($SQL_Result_Scanner1);
		$Scanner2_row = mysql_fetch_assoc($SQL_Result_Scanner2);
	}
?>
	</table>
	<br>
	<font size="-1"><b><u>Gr&uuml;n</u> makierte Spieler sind online</b></font><br />
	<font size="-1"><b><u>Blau</u> makierte Spieler sind im Urlaubs-Modus</b></font><br />
</center>
<!-- ENDE: inc_scanner -->
