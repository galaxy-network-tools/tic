<table align="center">
	<tr>
		<td colspan="2" class="datatablehead">T.I.C. Statistik</td>
	</tr>
	<?php
		$SQL_Result = tic_mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
		$SQL_Row = mysql_fetch_row($SQL_Result);
		echo '<tr><td align="left" class="fieldnormaldark">Anzahl Flottenbewegungen:</td><td class="fieldnormallight" align="right">'.ZahlZuText($SQL_Row[0]).'</td></tr>';

		$SQL_Result = tic_mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where modus=1 and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
		$SQL_Row = mysql_fetch_row($SQL_Result);
		echo '<tr><td align="left" class="fieldnormaldark">Anzahl Verteidingungsfl&uuml;ge:</td><td class="fieldnormallight" align="right">'.ZahlZuText($SQL_Row[0]).'</td></tr>';
		$SQL_Result = tic_mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where modus=2 and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
		$SQL_Row = mysql_fetch_row($SQL_Result);
		echo '<tr><td align="left" class="fieldnormaldark">Anzahl Angriffsfl&uuml;ge:</td><td class="fieldnormallight" align="right">'.ZahlZuText($SQL_Row[0]).'</td></tr>';
		$SQL_Result = tic_mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where modus>2 and ticid="'.$Benutzer['ticid'].'" or modus=0 and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
		$SQL_Row = mysql_fetch_row($SQL_Result);
		echo '<tr><td align="left" class="fieldnormaldark">Anzahl R&uuml;ckfl&uuml;ge:</td><td class="fieldnormallight" align="right">'.ZahlZuText($SQL_Row[0]).'</td></tr>';

		$SQL_Result = tic_mysql_query('SELECT COUNT(*) FROM `gn4accounts`', $SQL_DBConn);
		$SQL_Row = mysql_fetch_row($SQL_Result);
		echo '<tr><td align="left" class="fieldnormaldark">Alle T.I.C. Accounts</td><td class="fieldnormallight" align="right">'.ZahlZuText($SQL_Row[0]).'</td></tr>';
		echo '<tr><td align="left" class="fieldnormaldark" colspan="2"><b>Accounts pro Allianz:</b></td></tr>';
		$SQL_Result = tic_mysql_query('SELECT id, tag, name FROM `gn4allianzen` where ticid="'.$Benutzer['ticid'].'" ORDER BY id', $SQL_DBConn);
		for ($n = 0; $n < mysql_num_rows($SQL_Result); $n++) {
			$SQL_Result2 = tic_mysql_query('SELECT COUNT(*) FROM `gn4accounts` WHERE allianz="'.mysql_result($SQL_Result, $n, 'id').'" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
			$SQL_Row = mysql_fetch_row($SQL_Result2);
			echo '<tr><td align="left" class="fieldnormaldark">['.mysql_result($SQL_Result, $n, 'tag').'] '.mysql_result($SQL_Result, $n, 'name').'</td><td class="fieldnormallight" align="right">'.$SQL_Row[0].'</td></tr>';
		}
		echo '<tr><td class="fieldnormaldark" colspan="2"><b>Forenstatistik</b></td></tr>';
		$SQL_Result = tic_mysql_query('SELECT COUNT(*) FROM `gn4forum` WHERE belongsto="0" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
		$SQL_Row = mysql_fetch_row($SQL_Result);
		echo '<tr><td align="left" class="fieldnormaldark">Themen:</td><td class="fieldnormallight" align="right">'.ZahlZuText($SQL_Row[0]).'</td></tr>';
		$SQL_Result = tic_mysql_query('SELECT COUNT(*) FROM `gn4forum` WHERE NOT belongsto="0" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
		$SQL_Row = mysql_fetch_row($SQL_Result);
		echo '<tr><td align="left" class="fieldnormaldark">Antworten:</td><td class="fieldnormallight" align="right">'.ZahlZuText($SQL_Row[0]).'</td></tr>';
		echo '<tr><td align="left" class="fieldnormaldark" colspan="2"><b>Scan Datenbank</b></td></tr>';
		//$SQL_Result = tic_mysql_query('SELECT COUNT(*) FROM `gn4scans` where ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
		$SQL_Result = tic_mysql_query('SELECT COUNT(*) FROM `gn4scans`', $SQL_DBConn);
		$SQL_Row = mysql_fetch_row($SQL_Result);
		echo '<tr><td align="left" class="fieldnormaldark">Anzahl Scans:</td><td class="fieldnormallight" align="right">'.ZahlZuText($SQL_Row[0]).'</td></tr>';
		echo '<tr><td align="left" class="fieldnormaldark">Letzte Scans&auml;uberung:</td><td class="fieldnormallight">'.$lastscanclean.'</td></tr>';
	?>
</table>
