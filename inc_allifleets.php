<!-- START: inc_allifleets -->
<?php
	$allianz = isset($_GET['allianz'])?$_GET['allianz']:$Benutzer['allianz'];
?>
<center>
	<h2>Allianz-Flotten&uuml;bersicht</h2>
<?php
	if (isset($_GET['metanr'])) $_SESSION['metanr'] = $_GET['metanr'];
	else if (!isset($_SESSION['metanr'])) $_SESSION['metanr'] = $Benutzer['ticid'];
	$SQL_Query = "SELECT * FROM gn4vars WHERE name='ticeb' ORDER BY value;";
	$SQL_Result_metas = tic_mysql_query($SQL_Query, $SQL_DBConn) or $error_code = 4;
	for ($m=0; $m<mysql_num_rows($SQL_Result_metas); $m++) {
		$MetaNummer = mysql_result($SQL_Result_metas, $m, 'ticid');
		$MetaName = mysql_result($SQL_Result_metas, $m, 'value');
		if ($MetaNummer == $_SESSION['metanr'])
			echo "<h2>".$MetaName."</h2>\n";
		else
			echo "<h2><a href=\"./main.php?modul=allifleets&metanr=".$MetaNummer."\">".$MetaName."</a></h2>\n";
	}
	mysql_free_result($SQL_Result_metas);
	foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
		if ($AllianzInfo[$AllianzNummer]['meta'] == $_SESSION['metanr']) {
			if ($AllianzInfo[$allianz]['meta'] != $_SESSION['metanr']) $allianz = $AllianzNummer;
			if ($AllianzNummer == $allianz)
				echo "<h3>[ ".$AllianzInfo[$AllianzNummer]['tag']." ] ".$AllianzNummerName."</h3>\n";
			else
				echo "<h3><a href=\"./main.php?modul=allifleets&allianz=".$AllianzNummer."\">[ ".$AllianzTag[$AllianzNummer]." ]</h3>\n";
		}
	}
?>
	<table width="100%">
		<colgroup>
			<col width="55" />
			<col width="*" />
			<col width="6%" />
			<col width="6%" />
			<col width="5%" />
			<col width="5%" />
			<col width="6%" />
			<col width="6%" />
			<col width="6%" />
			<col width="6%" />
			<col width="5%" />
			<col width="5%" />
			<col width="5%" />
			<col width="5%" />
			<col width="6%" />
			<col width="6%" />
		</colgroup>
		<tr class="datatablehead">
			<th>Sektor</th>
			<th>Name</th>
			<th colspan="5">Deffensiv</th>
			<th colspan="9">Offensiv</th>
		</tr>
		<tr class="datatablehead">
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th title="Leichtes Orbitalgesch&uuml;tz">LO</th>
			<th title="Leichtes Raumgesch&uuml;tz">LR</th>
			<th title="Mittleres Raumgesch&uuml;tz">MR</th>
			<th title="Schweres Raumgesch&uuml;tz">SR</th>
			<th title="Abfangj&auml;ger">AJ</th>
			<th title="J&auml;ger">J&auml;</th>
			<th title="Bomber">Bo</th>
			<th title="Fregatten">Fr</th>
			<th title="Zerst&ouml;rer">Ze</th>
			<th title="Kreuzer">Kr</th>
			<th title="Schlachtschiffe">SS</th>
			<th title="Tr&auml;ger">Tr</th>
			<th title="Kaperschiffe">Ka</th>
			<th title="Schildschiffe">Sch</th>
		</tr>
<?php
    $gja = 0;
    $gbo = 0;
    $gfr = 0;
    $gze = 0;
    $gkr = 0;
    $gsl = 0;
    $gtr = 0;
    $gka = 0;
    $gca = 0;
    $glo = 0;
    $gro = 0;
    $gmr = 0;
    $gsr = 0;
    $gaj = 0;
	$SQL_Result2 = tic_mysql_query('SELECT a.id, a.name, a.galaxie, a.planet, b.sfj, b.sfb, b.sff, b.sfz, b.sfkr, b.sfsa, b.sft, b.sfka, b.sfsu, c.glo, c.glr, c.gmr, c.gsr, c.ga FROM `gn4accounts` AS a LEFT JOIN `gn4scans` AS b ON(a.galaxie = b.rg AND a.planet = b.rp AND b.type = 1) LEFT JOIN `gn4scans` AS c ON(a.galaxie = c.rg AND a.planet = c.rp AND c.type = 3) WHERE a.allianz="'.$allianz.'" order by a.galaxie, a.planet', $SQL_DBConn);
	$color = 0;
	if(mysql_num_rows($SQL_Result2) > 0)
	{
		for ( $i=0; $i<mysql_num_rows($SQL_Result2); $i++ ) {
			$color = !$color;
			$ftype = "normal";
			$gala   = mysql_result($SQL_Result2, $i, 'galaxie');
			$planet = mysql_result($SQL_Result2, $i, 'planet');
			$name   = mysql_result($SQL_Result2, $i, 'name');
			
			
			$ja = mysql_result($SQL_Result2, $i, 'sfj' );
			$bo = mysql_result($SQL_Result2, $i, 'sfb' );
			
			$fr     = mysql_result($SQL_Result2, $i, 'sff' );
			$ze     = mysql_result($SQL_Result2, $i, 'sfz' );
			$kr     = mysql_result($SQL_Result2, $i, 'sfkr' );
			$sl     = mysql_result($SQL_Result2, $i, 'sfsa' );
			$tr     = mysql_result($SQL_Result2, $i, 'sft' );
			$ka     = mysql_result($SQL_Result2, $i, 'sfka' );
			$ca     = mysql_result($SQL_Result2, $i, 'sfsu' );
			$lo     = mysql_result($SQL_Result2, $i, 'glo' );
			$ro     = mysql_result($SQL_Result2, $i, 'glr' );
			$mr     = mysql_result($SQL_Result2, $i, 'gmr' );
			$sr     = mysql_result($SQL_Result2, $i, 'gsr' );
			$aj     = mysql_result($SQL_Result2, $i, 'ga' );
			$gja += $ja;
			$gbo += $bo;
			$gfr += $fr;
			$gze += $ze;
			$gkr += $kr;
			$gsl += $sl;
			$gtr += $tr;
			$gka += $ka;
			$gca += $ca;
			$glo += $lo;
			$gro += $ro;
			$gmr += $mr;
			$gsr += $sr;
			$gaj += $aj;
			echo "		<tr class=\"field".$ftype.($color ? "dark" : "light")."\">\n";
			echo "			<td align=\"center\">".$gala.":".$planet."</td>\n";
			echo "			<td>".$name."</td>\n";
			echo "			<td title=\"Leichtes Orbitalgesch&uuml;tz\" align=\"right\">".IntVal($lo)."</td>\n";
			echo "			<td title=\"Leichtes Raumgesch&uuml;tz\" align=\"right\">".IntVal($ro)."</td>\n";
			echo "			<td title=\"Mittleres Raumgesch&uuml;tz\" align=\"right\">".IntVal($mr)."</td>\n";
			echo "			<td title=\"Schweres Raumgesch&uuml;tz\" align=\"right\">".IntVal($sr)."</td>\n";
			echo "			<td title=\"Abfangj&auml;ger\" align=\"right\">".IntVal($aj)."</td>\n";
			echo "			<td title=\"J&auml;ger\" align=\"right\">".IntVal($ja)."</td>\n";
			echo "			<td title=\"Bomber\" align=\"right\">".IntVal($bo)."</td>\n";
			echo "			<td title=\"Fregatten\" align=\"right\">".IntVal($fr)."</td>\n";
			echo "			<td title=\"Zerst&ouml;rer\" align=\"right\">".IntVal($ze)."</td>\n";
			echo "			<td title=\"Kreuzer\" align=\"right\">".IntVal($kr)."</td>\n";
			echo "			<td title=\"Schlachtschiffe\" align=\"right\">".IntVal($sl)."</td>\n";
			echo "			<td title=\"Tr&auml;ger\" align=\"right\">".IntVal($tr)."</td>\n";
			echo "			<td title=\"Kaperschiffe\" align=\"right\">".IntVal($ka)."</td>\n";
			echo "			<td title=\"Schildschiffe\" align=\"right\">".IntVal($ca)."</td>\n";
			echo "		</tr>\n";
			}
			$color = !$color;
			echo "		<tr class=\"fieldnormal".($color ? "dark" : "light")."\" style=\"font-weight:bold;\">\n";
			echo "			<td align=\"center\">Allianz</td>\n";
			echo "			<td>Gesammt</td>\n";
			echo "			<td title=\"Leichtes Orbitalgesch&uuml;tz\" align=\"right\">".IntVal($glo)."</td>\n";
			echo "			<td title=\"Leichtes Raumgesch&uuml;tz\" align=\"right\">".IntVal($gro)."</td>\n";
			echo "			<td title=\"Mittleres Raumgesch&uuml;tz\" align=\"right\">".IntVal($gmr)."</td>\n";
			echo "			<td title=\"Schweres Raumgesch&uuml;tz\" align=\"right\">".IntVal($gsr)."</td>\n";
			echo "			<td title=\"Abfangj&auml;ger\" align=\"right\">".IntVal($gaj)."</td>\n";
			echo "			<td title=\"J&auml;ger\" align=\"right\">".IntVal($gja)."</td>\n";
			echo "			<td title=\"Bomber\" align=\"right\">".IntVal($gbo)."</td>\n";
			echo "			<td title=\"Fregatten\" align=\"right\">".IntVal($gfr)."</td>\n";
			echo "			<td title=\"Zerst&ouml;rer\" align=\"right\">".IntVal($gze)."</td>\n";
			echo "			<td title=\"Kreuzer\" align=\"right\">".IntVal($gkr)."</td>\n";
			echo "			<td title=\"Schlachtschiffe\" align=\"right\">".IntVal($gsl)."</td>\n";
			echo "			<td title=\"Tr&auml;ger\" align=\"right\">".IntVal($gtr)."</td>\n";
			echo "			<td title=\"Kaperschiffe\" align=\"right\">".IntVal($gka)."</td>\n";
			echo "			<td title=\"Schildschiffe\" align=\"right\">".IntVal($gca)."</td>\n";
			echo "		</tr>\n";
			$gja = IntVal($gja/mysql_num_rows($SQL_Result2));
			$gbo = IntVal($gbo/mysql_num_rows($SQL_Result2));
			$gfr = IntVal($gfr/mysql_num_rows($SQL_Result2));
			$gze = IntVal($gze/mysql_num_rows($SQL_Result2));
			$gkr = IntVal($gkr/mysql_num_rows($SQL_Result2));
			$gsl = IntVal($gsl/mysql_num_rows($SQL_Result2));
			$gtr = IntVal($gtr/mysql_num_rows($SQL_Result2));
			$gka = IntVal($gka/mysql_num_rows($SQL_Result2));
			$gca = IntVal($gca/mysql_num_rows($SQL_Result2));
			$glo = IntVal($glo/mysql_num_rows($SQL_Result2));
			$gro = IntVal($gro/mysql_num_rows($SQL_Result2));
			$gmr = IntVal($gmr/mysql_num_rows($SQL_Result2));
			$gsr = IntVal($gsr/mysql_num_rows($SQL_Result2));
			$gaj = IntVal($gaj/mysql_num_rows($SQL_Result2));
			$color = !$color;
			echo "		<tr class=\"fieldnormal".($color ? "dark" : "light")."\" style=\"font-weight:bold;\">\n";
			echo "			<td align=\"center\">Allianz</td>\n";
			echo "			<td>Durchschnitt</td>\n";
			echo "			<td title=\"Leichtes Orbitalgesch&uuml;tz\" align=\"right\">".$glo."</td>\n";
			echo "			<td title=\"Leichtes Raumgesch&uuml;tz\" align=\"right\">".$gro."</td>\n";
			echo "			<td title=\"Mittleres Raumgesch&uuml;tz\" align=\"right\">".$gmr."</td>\n";
			echo "			<td title=\"Schweres Raumgesch&uuml;tz\" align=\"right\">".$gsr."</td>\n";
			echo "			<td title=\"Abfangj&auml;ger\" align=\"right\">".$gaj."</td>\n";
			echo "			<td title=\"J&auml;ger\" align=\"right\">".$gja."</td>\n";
			echo "			<td title=\"Bomber\" align=\"right\">".$gbo."</td>\n";
			echo "			<td title=\"Fregatten\" align=\"right\">".$gfr."</td>\n";
			echo "			<td title=\"Zerst&ouml;rer\" align=\"right\">".$gze."</td>\n";
			echo "			<td title=\"Kreuzer\" align=\"right\">".$gkr."</td>\n";
			echo "			<td title=\"Schlachtschiffe\" align=\"right\">".$gsl."</td>\n";
			echo "			<td title=\"Tr&auml;ger\" align=\"right\">".$gtr."</td>\n";
			echo "			<td title=\"Kaperschiffe\" align=\"right\">".$gka."</td>\n";
			echo "			<td title=\"Schildschiffe\" align=\"right\">".$gca."</td>\n";
			echo "		</tr>\n";
		}
		else
		{
			echo "<tr class=\"datatablefoot\" style=\"font-weight:bold;\"><td>Diese Allianz hat keine Mitglieder</td></tr>";
		}

?>
	</table>
</center>
<!-- ENDE: inc_allifleets -->
