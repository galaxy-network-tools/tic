<?php
	if (!isset($top_counter)) $top_counter = 10;

	function print_table($query) {
		global $Benutzer;
		global $AllianzTag;

		$SQL_Result = tic_mysql_query($query, $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
		$SQL_Num = mysql_num_rows($SQL_Result);
		if ($SQL_Num == 0) {
			echo '<tr><td colspan="4"><i>Keine Eintr&auml;ge gefunden</i></td></tr>';
		} else {
			for ($n = 0; $n < $SQL_Num; $n++) {
				$ftype = 'normal';
				if (mysql_result($SQL_Result, $n, 'umod') != '') {
					$ftype = 'umode';
				}
				if (mysql_result($SQL_Result, $n, 'id') == $Benutzer['id']) {
					$ftype = 'myself';
				}
				if (mysql_result($SQL_Result, $n, 'value') > 0) {
					if ($n < 9 ) {
						$s = "0".($n + 1);
					} else {
						$s = $n + 1;
					}
					$gala = mysql_result($SQL_Result, $n, 'gala');
					$planet = mysql_result($SQL_Result, $n, 'planet');
					if (strlen($planet) == 1) {
						$planet = "0".$planet;
					}
					echo '<tr>';
					echo '<td class="field'.$ftype.'light">'.$s.'.</td>';
					echo '<td class="field'.$ftype.'dark" align="center">['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].']</td>';
					echo '<td class="field'.$ftype.'light" align="right">'.$gala.':'.$planet.'</td>';
					echo '<td  class="field'.$ftype.'dark">'.
						'<a href="./main.php?modul=anzeigen&amp;id='.mysql_result($SQL_Result, $n, 'id').'">'.
						mysql_result($SQL_Result, $n, 'name').'</a></td>';
					echo '<td class="field'.$ftype.'light" align="right">'.
						ZahlZuText(mysql_result($SQL_Result, $n, 'value')).'</td>';
					echo '</tr>';
				}
			}
		}

	}
?>
<center>
<h2>T.I.C. Rankings</h2>
<a href="./main.php?modul=rankings&amp;top_counter=10">Top 10</a> | <a href="./main.php?modul=rankings&amp;top_counter=20">Top 20</a> | <a href="./main.php?modul=rankings&amp;top_counter=50">Top 50</a>

<table width="100%">
<tr><td width="50%">
	<table align="center">
	<tr><td class="datatablehead" colspan="5">Punkte(Top <?=$top_counter?>)</td></tr>
	<?php
		$query = 'SELECT gn4accounts.id, pts as value, gn4accounts.name, allianz, '.
				'umod, gn4accounts.galaxie as gala, gn4accounts.planet '.
			'FROM gn4scans, gn4accounts '.
			'WHERE rg=galaxie AND rp=planet AND type="0" '.
			'ORDER BY pts DESC, galaxie ASC, planet ASC LIMIT '.$top_counter;
		print_table($query);
	?>
	</table>
</td>
<td width="50%">
	<table align="center">
	<tr><td class="datatablehead" colspan="5">Extraktoren(Top <?=$top_counter?>)</td></tr>
	<?php
		$query = 'SELECT gn4accounts.id, gn4accounts.name, allianz, me + ke as value, umod, '.
				'gn4accounts.galaxie as gala, gn4accounts.planet '.
			'FROM gn4scans, gn4accounts '.
			'WHERE rg=galaxie and rp=planet AND type="0" '.
			'ORDER BY (me + ke) DESC, galaxie ASC, planet ASC LIMIT '.$top_counter;
		print_table($query);
	?>
	</table>
</td></tr>
<tr><td></td></tr>
<tr><td width="50%">
	<table align="center">
	<tr><td class="datatablehead" colspan="5">Flotten(Top <?=$top_counter?>)</td></tr>
	<?php
		$query = 'SELECT gn4accounts.id, s as value, gn4accounts.name, allianz, umod, '.
				'gn4accounts.galaxie as gala, gn4accounts.planet '.
			'FROM gn4scans, gn4accounts '.
			'WHERE rg=galaxie AND rp=planet AND type="0" '.
			'ORDER BY s DESC, galaxie ASC, planet ASC LIMIT '.$top_counter;
		print_table($query);
	?>
	</table>
</td>
<td width="50%">
	<table align="center">
	<tr><td class="datatablehead" colspan="5">Gesch&uuml;tze(Top <?=$top_counter?>)</td></tr>
	<?php
		$query = 'SELECT gn4accounts.id, d as value, gn4accounts.name, allianz, umod, '.
				'gn4accounts.galaxie as gala, gn4accounts.planet '.
			'FROM gn4scans, gn4accounts '.
			'WHERE rg=galaxie AND rp=planet AND type="0" '.
			'ORDER BY d DESC, galaxie ASC, planet ASC LIMIT '.$top_counter;
		print_table($query);
	?>
	</table>
</td></tr>
</table>
<b>(<u>Blau</u> makierte Spieler sind im Urlaubs-Modus)</b>
</center>
