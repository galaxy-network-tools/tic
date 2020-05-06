<?php
	function getscannames( $scantype ) {
		$sn = explode( ' ', $scantype );
		$res = '';
		$snarr = array( 'Sektor', 'Einheiten', 'Mili', 'Gesch&uuml;tz' );
		for ( $j=0; $j< count( $sn )-1; $j++ ) {
			$idx = $sn[$j];
			if ( $j < count( $sn )-2 )
				$res .= $snarr[ $idx ].' / ';
			else
				$res .= $snarr[ $idx ];
		}
		return $res;
	}

	if(!isset($xgala)) {
		if(isset($_GET['xgala']))
			$xgala = $_GET['xgala'];
		else if(isset($_POST['xgala']))
			$xgala = $_POST['xgala'];
		else
			$xgala = null;
	}

	if(!isset($xplanet)) {
		if(isset($_GET['xplanet']))
			$xplanet = $_GET['xplanet'];
		else if(isset($_POST['xplanet']))
			$xplanet = $_POST['xplanet'];
		else
			$xplanet = null;
	}

	if(!isset($_GET['displaytype']))
		$_GET['displaytype'] = 0;   // einzelner planet = 0 / gala= 1 / query = 2

	$sql='';
	$error_occured=0;
	switch($_GET['displaytype']) {
		case 1: // einzelner planet = 0 / gala= 1 / query = 2
			if ( !isset( $xgala ))
				$error_occured = 3;
			else
				$sql  = 'select * from `gn4scans` where rg='.intval($xgala).' order by rp, type';
			break;
		case 2: // einzelner planet = 0 / gala= 1 / query = 2
			if ( !isset( $_GET['qvar']) )
				$error_occured = 4;
			else if ( !isset( $_GET['qoperator'] ) )
				$error_occured = 5;
			else if ( !isset( $_GET['qval'] ) )
				$error_occured = 6;
			else {
				$tmparr = explode( ',', $_GET['qvar']);
				if ( strcmp( $_GET['qoperator'], "<" ) == 0 )
					$sortdir='ASC';
				else
					$sortdir='DESC';
				$sql = "
					SELECT gn4scans.* FROM `gn4scans`
					".((isset($_GET['qlimit']) && $_GET['qlimit'] > 0)?"LEFT JOIN gn4accounts ON (gn4scans.rg = gn4accounts.galaxie AND gn4scans.rp = gn4accounts.planet)":"")."
					WHERE ".$tmparr[0]." ".$_GET['qoperator']." '".$_GET['qval']."' AND type=".$tmparr[1]."
					".((isset($_GET['qlimit']) && $_GET['qlimit'] > 0)?"AND gn4accounts.name ".($_GET['qlimit'] == 1?"IS":"IS NOT")." NULL":"")."
					ORDER BY ".$tmparr[0]." ".$sortdir.", rg, rp
					LIMIT 10";
			}
			break;
		default:
			if ( !isset( $xgala ) )
				$error_occured = 1;
			else if ( !isset( $xplanet ))
				$error_occured = 2;
			else
				$sql='select * from `gn4scans` where rg='.intval($xgala).' and rp='.intval($xplanet).' order by type';
			break;
	}
	if ( $error_occured > 0){
		echo '<b><font color="#800000">Internal Error ('.$error_occured.')!!! - aborted!</font></b> <br />';
		return;
	}
?>
	<h2>Scans, Scans, Scans - Scanausgaben</h2>
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr><td class="datatablehead">Scanausgaben</td></tr>
		<tr><td>
			<table width="100%" cellspacing="3" cellpadding="0">
				<tr class="fieldnormallight">
					<td valign="top" width="30%">
						<form name="form1" method="get" action="./main.php">
							<input type="hidden" name="modul" value="showgalascans" />
							<input type="hidden" name="displaytype" value="0" />
							<table width="100%" cellspacing="0" cellpadding="3">
								<tr><td class="datatablehead">Spezieller Planet</td></tr>
								<tr><td>Einzelner Planet</td></tr>
								<tr><td>Gala:Planet <input type="text" name="xgala" size="4" value="<?=(isset($xgala) ? $xgala : "")?>" /> : <input type="text" name="xplanet" size="2" value="<?=(isset($xplanet) ? $xplanet : "")?>" /></td></tr>
								<tr><td align="right"><input type="submit" value="Anzeigen" /></td></tr>
							</table>
						</form>
					</td>
					<td valign="top" width="30%">
						<form name="form2" method="get" action="./main.php">
							<input type="hidden" name="modul" value="showgalascans" />
							<input type="hidden" name="displaytype" value="1" />
							<table width="100%" cellspacing="0" cellpadding="3">
								<tr><td class="datatablehead">Galaxie anzeigen</td></tr>
								<tr><td>Nachbar-Galas:</td></tr>
								<tr><td>Galaxie:<input type="button" name="Verweis" value="&lt;&lt;" onclick="self.location.href='./main.php?modul=showgalascans&amp;action=findgala&amp;displaytype=1&amp;direction=previous&amp;xgala=<?= (isset($xgala) ? $xgala : "") ?>'" /><input type="text" name="xgala" size="4" value="<?= (isset($xgala) ? $xgala : "") ?>" /><input type="button" name="Verweis" value="&gt;&gt;" onclick="self.location.href='./main.php?modul=showgalascans&amp;action=findgala&amp;displaytype=1&amp;direction=next&amp;xgala=<?= (isset($xgala) ? $xgala : "") ?>'" /></td></tr>
								<tr><td align="right"><input type="submit" value="Anzeigen" /></td></tr>
							</table>
						</form>
					</td>
					<td valign="top" width="40%">
						<form name="form3" method="post" action="./main.php?modul=showgalascans&amp;displaytype=1&amp;xgala=<?=(isset($xgala) ? $xgala : "")?>&amp;xplanet=<?=(isset($xplanet) ? $xplanet : "")?>">
							<table width="100%" cellspacing="0" cellpadding="3">
								<tr><td class="datatablehead">Suche Planeten</td></tr>
								<tr><td>
									<select name="qvar">
										<option value="pts,0"<?= ( isset($_GET['qvar']) && $_GET['qvar']== "pts,0" )?" selected":"" ?>>Punkte</option>
										<option value="sfsu,1"<?= ( isset($_GET['qvar']) && $_GET['qvar']== "sfsu,1" )?" selected":"" ?>>Schutzies</option>
										<option value="ga,3"<?= ( isset($_GET['qvar']) && $_GET['qvar']== "ga,3" )?" selected":"" ?>>Abfangj&auml;ger</option>
										<option value="me,0"<?= ( isset($_GET['qvar']) && $_GET['qvar']== "me,0" )?" selected":"" ?>>Metall-Exen</option>
										<option value="ke,0"<?= ( isset($_GET['qvar']) && $_GET['qvar']== "ke,0" )?" selected":"" ?>>Kristall-Exen</option>
									</select>
									<select name="qoperator">
										<option value="&gt;"<?= ( isset($_GET['qoperator']) && $_GET['qoperator']== ">" )?" selected":"" ?>>gr&ouml;&szlig;er</option>
										<option value="&lt;"<?= ( !isset($_GET['qoperator']) || $_GET['qoperator']== "<" )?" selected":"" ?>>kleiner</option>
									</select>
									<select name="qlimit">
										<option value="0"<?= (isset($_GET['qlimit']) && $_GET['qlimit'] == 0)?" selected":"" ?> >alle</option>
										<option value="1"<?= (isset($_GET['qlimit']) && $_GET['qlimit'] == 1)?" selected":"" ?> >keine TICler</option>
										<option value="2"<?= (isset($_GET['qlimit']) && $_GET['qlimit'] == 2)?" selected":"" ?> >nur TICler</option>
									</select>
								</td></tr>
								<tr><td>Kriterium: <input type="text" name="qval" value="<?php if(isset($_GET['qval'])) echo '"'.$_GET['qval'].'"'; ?>" /></td></tr>
								<tr><td align="right">(&lt;=10 Treffer) <input type="submit" value="Anzeigen" /></td></tr>
							</table>
						</form>
					</td>
				</tr>
			</table>
		</td></tr>
	</table>
	<form action="./main.php?modul=scans" method="post">
		<input type="hidden" name="txtScanGalaxie" value="<?= (isset($xgala) ? $xgala : "") ?>" />
		<input type="hidden" name="txtScanPlanet" value="<?= (isset($xplanet) ? $xplanet : "") ?>" />
		<input type="submit" value="Zur Datenerfassung" />
	</form>
	<br />
<?php
//	echo "sql=".$sql;
	$SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
	$count =  mysqli_num_rows($SQL_Result);
	if ( $count == 0 ) {
		echo '<font color="#800000" size="-1"><b>Sorry - Keine Scans vorhanden.</b></font>';
		return;
	} else {
		// all
		// sektor
		$pts = 0; $me  = 0; $ke  = 0; $sgen=0; $szeit='-'; $s=0; $d=0; $a=0;
		// unit init
		$ja   = 0; $bo   = 0; $fr   = 0; $ze   = 0; $kr   = 0; $sl   = 0; $tr   = 0; $ka   = 0; $ca   = 0; $ugen=0; $uzeit='-';
		// mili init
		$ja0  = 0; $bo0  = 0; $fr0  = 0; $ze0  = 0; $kr0  = 0; $sl0  = 0; $tr0  = 0; $ka0  = 0; $ca0  = 0; $mgen=0; $mzeit='-';
		$ja1  = 0; $bo1  = 0; $fr1  = 0; $ze1  = 0; $kr1  = 0; $sl1  = 0; $tr1  = 0; $ka1  = 0; $ca1  = 0;
		$ja2  = 0; $bo2  = 0; $fr2  = 0; $ze2  = 0; $kr2  = 0; $sl2  = 0; $tr2  = 0; $ka2  = 0; $ca2  = 0;
		// gscan
		$lo = 0; $lr = 0; $mr = 0; $sr = 0; $aj = 0; $ggen=0; $gzeit='-';
		$rscans = '';

		for ( $i=0; $i<$count; $i++ ) {
			if ( $i<($count-1) )
				$rpnext = tic_mysql_result($SQL_Result, $i+1, 'rp' );
			else
				$rpnext = 999;

			$type = tic_mysql_result($SQL_Result, $i, 'type' );
			$rp = tic_mysql_result($SQL_Result, $i, 'rp' );
			$rg = tic_mysql_result($SQL_Result, $i, 'rg' );
			$rname = gnuser($rg, $rp);
			$rscans .= sprintf( "%d ", $type );
//echo '<br />type='.$type.' - ';
			switch( $type ) {   // scan-type
				case 0: // sektor
					$szeit	= tic_mysql_result($SQL_Result, $i, 'zeit' );
					$sgen	= tic_mysql_result($SQL_Result, $i, 'gen' );
					$pts	= tic_mysql_result($SQL_Result, $i, 'pts' );
					$me	= tic_mysql_result($SQL_Result, $i, 'me' );
					$ke	= tic_mysql_result($SQL_Result, $i, 'ke' );
					$s	= tic_mysql_result($SQL_Result, $i, 's' );
					$d	= tic_mysql_result($SQL_Result, $i, 'd' );
					$a	= tic_mysql_result($SQL_Result, $i, 'a' );
					break;
				case 1: // unit
					$uzeit	= tic_mysql_result($SQL_Result, $i, 'zeit' );
					$ugen	= tic_mysql_result($SQL_Result, $i, 'gen' );
					$ja	= tic_mysql_result($SQL_Result, $i, 'sfj' );
					$bo	= tic_mysql_result($SQL_Result, $i, 'sfb' );
					$fr	= tic_mysql_result($SQL_Result, $i, 'sff' );
					$ze	= tic_mysql_result($SQL_Result, $i, 'sfz' );
					$kr	= tic_mysql_result($SQL_Result, $i, 'sfkr' );
					$sl	= tic_mysql_result($SQL_Result, $i, 'sfsa' );
					$tr	= tic_mysql_result($SQL_Result, $i, 'sft' );
					$ka	= tic_mysql_result($SQL_Result, $i, 'sfka' );
					$ca	= tic_mysql_result($SQL_Result, $i, 'sfsu' );
					break;
				case 2: // mili-scan
					$mzeit	= tic_mysql_result($SQL_Result, $i, 'zeit' );
					$mgen	= tic_mysql_result($SQL_Result, $i, 'gen' );
					$ja0	= tic_mysql_result($SQL_Result, $i, 'sf0j' );
					$bo0	= tic_mysql_result($SQL_Result, $i, 'sf0b' );
					$fr0	= tic_mysql_result($SQL_Result, $i, 'sf0f' );
					$ze0	= tic_mysql_result($SQL_Result, $i, 'sf0z' );
					$kr0	= tic_mysql_result($SQL_Result, $i, 'sf0kr' );
					$sl0	= tic_mysql_result($SQL_Result, $i, 'sf0sa' );
					$tr0	= tic_mysql_result($SQL_Result, $i, 'sf0t' );
					$ka0	= tic_mysql_result($SQL_Result, $i, 'sf0ka' );
					$ca0	= tic_mysql_result($SQL_Result, $i, 'sf0su' );
					$ja1	= tic_mysql_result($SQL_Result, $i, 'sf1j' );
					$bo1	= tic_mysql_result($SQL_Result, $i, 'sf1b' );
					$fr1	= tic_mysql_result($SQL_Result, $i, 'sf1f' );
					$ze1	= tic_mysql_result($SQL_Result, $i, 'sf1z' );
					$kr1	= tic_mysql_result($SQL_Result, $i, 'sf1kr' );
					$sl1	= tic_mysql_result($SQL_Result, $i, 'sf1sa' );
					$tr1	= tic_mysql_result($SQL_Result, $i, 'sf1t' );
					$ka1	= tic_mysql_result($SQL_Result, $i, 'sf1ka' );
					$ca1	= tic_mysql_result($SQL_Result, $i, 'sf1su' );
					$ja2	= tic_mysql_result($SQL_Result, $i, 'sf2j' );
					$bo2	= tic_mysql_result($SQL_Result, $i, 'sf2b' );
					$fr2	= tic_mysql_result($SQL_Result, $i, 'sf2f' );
					$ze2	= tic_mysql_result($SQL_Result, $i, 'sf2z' );
					$kr2	= tic_mysql_result($SQL_Result, $i, 'sf2kr' );
					$sl2	= tic_mysql_result($SQL_Result, $i, 'sf2sa' );
					$tr2	= tic_mysql_result($SQL_Result, $i, 'sf2t' );
					$ka2	= tic_mysql_result($SQL_Result, $i, 'sf2ka' );
					$ca2	= tic_mysql_result($SQL_Result, $i, 'sf2su' );

					break;
				case 3: // geschtz
					$gzeit	= tic_mysql_result($SQL_Result, $i, 'zeit' );
					$ggen	= tic_mysql_result($SQL_Result, $i, 'gen' );
					$lo	= tic_mysql_result($SQL_Result, $i, 'glo' );
					$lr	= tic_mysql_result($SQL_Result, $i, 'glr' );
					$mr	= tic_mysql_result($SQL_Result, $i, 'gmr' );
					$sr	= tic_mysql_result($SQL_Result, $i, 'gsr' );
					$aj	= tic_mysql_result($SQL_Result, $i, 'ga' );
					break;
				default:
					echo '????huh?!??? - Ohooooh';
					break;
			}

			if ( $rpnext != $rp ) {
?>
	<table width="100%">
		<tr>
			<td colspan="12" class="datatablehead"><?php echo $rg.':'.$rp.' - '.$rname.' ('.getscannames($rscans).')'; ?></td>
		</tr>
		<tr>
			<td class="fieldnormaldark"><b>Punkte</b></td>
			<td class="fieldnormaldark"><b>MetExen</b></td>
			<td class="fieldnormaldark"><b>KrisExen</b></td>
			<td class="fieldnormaldark"><b>Schiffe</b></td>
			<td class="fieldnormaldark"><b>Deffensiv</b></td>
			<td class="fieldnormaldark"><b>Astros</b></td>
			<td>&nbsp;</td>
			<td colspan="2" bgcolor="#dbdbbb"><b>Copy for IRC</b></td>
			<td>&nbsp;</td>
			<td class="fieldnormaldark"><b>Genauigkeit</b></td>
			<td class="fieldnormaldark"><b>Datum</b></td>
		</tr>
		<tr>
			<td class="fieldnormallight"><?php echo number_format($pts, 0, ',', '.'); ?></td>
			<td class="fieldnormallight"><?php echo $me; ?></td>
			<td class="fieldnormallight"><?php echo $ke; ?></td>
			<td class="fieldnormallight"><?php echo $s; ?></td>
			<td class="fieldnormallight"><?php echo $d; ?></td>
			<td class="fieldnormallight"><?php echo $a; ?></td>
<?php
	$sektor =		'Ab hier Kopieren:&lt;br /&gt;';
	$sektor = $sektor.	'00,10Sektorscan (01,10 '.$sgen.'%00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)&lt;br /&gt;';
	$sektor = $sektor.	'00,01Punkte: 07,01'.number_format($pts, 0, ',', '.').' 00,01Astros: 07,01'.$a.'&lt;br /&gt;';
	$sektor = $sektor.	'00,01Schiffe: 07,01'.$s.' 00,01Gesch&uuml;tze: 07,01'.$d.'&lt;br /&gt;';
	$sektor = $sektor.	'00,01Metall-Exen: 07,01'.$me.' 00,01Kristall-Exen: 07,01'.$ke.'&lt;br /&gt;';
	$sektor = $sektor.	'00,01Datum: 07,01'.$szeit.'';
?>
			<td>&nbsp;</td>
			<td bgcolor="#fdfddd" colspan="2"><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$sektor.'\', STICKY, CAPTION,\'Sektorscan\', CENTER);" onmouseout="nd();">Sektorscan</a>';?></td>
			<td>&nbsp;</td>
			<td class="fieldnormallight"><?=$sgen;?></td>
			<td class="fieldnormallight"><?=$szeit;?></td>
		</tr>
		<tr>
			<td class="fieldnormaldark"><b>LO</b></td>
			<td class="fieldnormaldark"><b>LR</b></td>
			<td class="fieldnormaldark"><b>MR</b></td>
			<td class="fieldnormaldark"><b>SR</b></td>
			<td class="fieldnormaldark"><b>AJ</b></td>
			<td colspan="2">&nbsp;</td>
<?php
	$gscan = 		'Ab hier Kopieren:&lt;br /&gt;';
	$gscan = $gscan.	'00,10Gesch&uuml;tzscan (01,10 '.$ggen.'%00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)&lt;br /&gt;';
	$gscan = $gscan.	'00,01Rubium: 07,01'.$lo.' 00,01Pulsar: 07,01'.$lr.' 00,01Coon: 07,01'.$mr.'&lt;br /&gt;';
	$gscan = $gscan.	'00,01Centurion: 07,01'.$sr.' 00,01Horus: 07,01'.$aj.'&lt;br /&gt;';
	$gscan = $gscan.	'00,01Datum: 07,01'.$gzeit.'';
?>
			<td bgcolor="#fdfddd" colspan="2"><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$gscan.'\', STICKY, CAPTION,\'Geschützscan\', CENTER);" onmouseout="nd();">Gesch&uuml;tzscan</a>';?></td>
			<td>&nbsp;</td>
			<td class="fieldnormaldark"><b>Genauigkeit</b></td>
			<td class="fieldnormaldark"><b>Datum</b></td>
		</tr>
		<tr>
			<td class="fieldnormallight"><?php echo $lo; ?></td>
			<td class="fieldnormallight"><?php echo $lr; ?></td>
			<td class="fieldnormallight"><?php echo $mr; ?></td>
			<td class="fieldnormallight"><?php echo $sr; ?></td>
			<td class="fieldnormallight"><?php echo $aj; ?></td>
			<td colspan="2">&nbsp;</td>
<?php
	$MiliH = 		'Ab hier Kopieren:&lt;br /&gt;';
	$MiliH = $MiliH.	'00,10Milit&auml;rscan (01,10 '.$mgen.'%00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)&lt;br /&gt;';
	$Orbit = 		'00,1Orbit: 07,01'.$ja0.' 00,1Leo 07,01'.$bo0.' 00,1Aquilae 07,01'.$fr0.' 00,1Fornax 07,01'.$ze0.' 00,1Draco 07,01'.$kr0.' 00,1Goron 07,01'.$sl0.' 00,1Pentalin 07,01'.$tr0.' 00,1Zenit 07,01'.$ka0.' 00,1Cleptor 07,01'.$ca0.' 00,1Cancri &lt;br /&gt;';
	$Flotte1 = 		'00,01Flotte1: 07,01'.$ja1.' 00,01Leo 07,01'.$bo1.' 00,01Aquilae 07,01'.$fr1.' 00,01Fornax 07,01'.$ze1.' 00,01Draco 07,01'.$kr1.' 00,01Goron 07,01'.$sl1.' 00,01Pentalin 07,01'.$tr1.' 00,01Zenit 07,01'.$ka1.' 00,01Cleptor 07,01'.$ca1.' 00,01Cancri &lt;br /&gt;';
	$Flotte2 = 		'00,01Flotte2: 07,01'.$ja2.' 00,01Leo 07,01'.$bo2.' 00,01Aquilae 07,01'.$fr2.' 00,01Fornax 07,01'.$ze2.' 00,01Draco 07,01'.$kr2.' 00,01Goron 07,01'.$sl2.' 00,01Pentalin 07,01'.$tr2.' 00,01Zenit 07,01'.$ka2.' 00,01Cleptor 07,01'.$ca2.' 00,01Cancri &lt;br /&gt;';
	$MiliF = 		'00,01Datum: 07,01'.$mzeit.'';
?>
			<td colspan="2" bgcolor="#fdfddd"><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$MiliH.$Orbit.$Flotte1.$Flotte2.$MiliF.'\', STICKY, CAPTION,\'Militärscan\', CENTER);" onmouseout="nd();">Kompleter Milit&auml;rscan</a>';?></td>
			<td>&nbsp;</td>
			<td class="fieldnormallight"><?php echo $ggen; ?></td>
			<td class="fieldnormallight"><?php echo $gzeit; ?></td>
		</tr>
		<tr>
			<td bgcolor="#dbdbbb"><b>Copy</b></td>
			<td class="fieldnormaldark"><b>J&auml;ger</b></td>
			<td class="fieldnormaldark"><b>Bomber</b></td>
			<td class="fieldnormaldark"><b>Fregs</b></td>
			<td class="fieldnormaldark"><b>Zerries</b></td>
			<td class="fieldnormaldark"><b>Kreuzer</b></td>
			<td class="fieldnormaldark"><b>Schlachter</b></td>
			<td class="fieldnormaldark"><b>Tr&auml;ger</b></td>
			<td class="fieldnormaldark"><b>Kleps</b></td>
			<td class="fieldnormaldark"><b>Schutzies</b></td>
			<td class="fieldnormaldark"><b>Genauigkeit</b></td>
			<td class="fieldnormaldark"><b>Datum</b></td>
		</tr>
		<tr bgcolor="#ddddfd">
<?php
	$unit = 	'Ab hier Kopieren:&lt;br /&gt;';
	$unit = $unit.	'00,10Einheitenscan (01,10 '.$ugen.'%00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)&lt;br /&gt;';
	$unit = $unit.	'00,01Leo: 07,01'.$ja.' 00,01Aquilae: 07,01'.$bo.' 00,01Fronax: 07,01'.$fr.' 00,01Draco: 07,01'.$ze.' 00,01Goron: 07,01'.$kr.'&lt;br /&gt;';
	$unit = $unit.	'00,01Pentalin: 07,01'.$sl.' 00,01Zenit: 07,01'.$tr.' 00,01Cleptor: 07,01'.$ka.' 00,01Cancri: 07,01'.$ca.'&lt;br /&gt;';
	$unit = $unit.	'00,01Datum: 07,01'.$uzeit.'';
?>
			<td bgcolor="#fdfddd"><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$unit.'\', STICKY, CAPTION,\'Einheitenscan\', CENTER);" onmouseout="nd();">Summe</a>';?></td>
			<td><b><?php echo $ja; ?></b></td>
			<td><b><?php echo $bo; ?></b></td>
			<td><b><?php echo $fr; ?></b></td>
			<td><b><?php echo $ze; ?></b></td>
			<td><b><?php echo $kr; ?></b></td>
			<td><b><?php echo $sl; ?></b></td>
			<td><b><?php echo $tr; ?></b></td>
			<td><b><?php echo $ka; ?></b></td>
			<td><b><?php echo $ca; ?></b></td>
			<td><b><?php echo $ugen; ?></b></td>
			<td><b><?php echo $uzeit; ?></b></td>
		</tr>
		<tr class="fieldnormallight">
			<td bgcolor="#fdfddd"><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$MiliH.$Orbit.$MiliF.'\', STICKY, CAPTION,\'Militärscan Orbit\', CENTER);" onmouseout="nd();">Orbit</a>';?></td>
			<td><?php echo $ja0; ?></td>
			<td><?php echo $bo0; ?></td>
			<td><?php echo $fr0; ?></td>
			<td><?php echo $ze0; ?></td>
			<td><?php echo $kr0; ?></td>
			<td><?php echo $sl0; ?></td>
			<td><?php echo $tr0; ?></td>
			<td><?php echo $ka0; ?></td>
			<td><?php echo $ca0; ?></td>
			<td rowspan="3"><?php echo $mgen; ?></td>
			<td rowspan="3"><?php echo $mzeit; ?></td>
		</tr>
		<tr class="fieldnormallight">
			<td bgcolor="#fdfddd"><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$MiliH.$Flotte1.$MiliF.'\', STICKY, CAPTION,\'Militärscan Flotte1\', CENTER);" onmouseout="nd();">Flotte1</a>';?></td>
			<td><?php echo $ja1; ?></td>
			<td><?php echo $bo1; ?></td>
			<td><?php echo $fr1; ?></td>
			<td><?php echo $ze1; ?></td>
			<td><?php echo $kr1; ?></td>
			<td><?php echo $sl1; ?></td>
			<td><?php echo $tr1; ?></td>
			<td><?php echo $ka1; ?></td>
			<td><?php echo $ca1; ?></td>
		</tr>
		<tr class="fieldnormallight">
			<td bgcolor="#fdfddd"><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$MiliH.$Flotte2.$MiliF.'\', STICKY, CAPTION,\'Militärscan Flotte2\', CENTER);" onmouseout="nd();">Flotte2</a>';?></td>
			<td><?php echo $ja2; ?></td>
			<td><?php echo $bo2; ?></td>
			<td><?php echo $fr2; ?></td>
			<td><?php echo $ze2; ?></td>
			<td><?php echo $kr2; ?></td>
			<td><?php echo $sl2; ?></td>
			<td><?php echo $tr2; ?></td>
			<td><?php echo $ka2; ?></td>
			<td><?php echo $ca2; ?></td>
		</tr>
	</table>
<?php
				// all
				// sektor
				$pts = 0; $me  = 0; $ke  = 0; $sgen=0; $szeit='-'; $s=0; $d=0; $a=0;
				// unit init
				$ja   = 0; $bo   = 0; $fr   = 0; $ze   = 0; $kr   = 0; $sl   = 0; $tr   = 0; $ka   = 0; $ca   = 0; $ugen=0; $uzeit='-';
				// mili init
				$ja0  = 0; $bo0  = 0; $fr0  = 0; $ze0  = 0; $kr0  = 0; $sl0  = 0; $tr0  = 0; $ka0  = 0; $ca0  = 0; $mgen=0; $mzeit='-';
				$ja1  = 0; $bo1  = 0; $fr1  = 0; $ze1  = 0; $kr1  = 0; $sl1  = 0; $tr1  = 0; $ka1  = 0; $ca1  = 0;
				$ja2  = 0; $bo2  = 0; $fr2  = 0; $ze2  = 0; $kr2  = 0; $sl2  = 0; $tr2  = 0; $ka2  = 0; $ca2  = 0;
				// gscan
				$lo = 0; $ro = 0; $mr = 0; $sr = 0; $aj = 0; $ggen=0; $gzeit='-';
				$rscans = '';
			} // end of if (rp != rpnext)
		} // end of for
	} // end of else
?>
