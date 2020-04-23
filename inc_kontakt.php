<center>
	<h2>Übersicht der Kontakte</h2>
	<table>
		<tr><td>
			<table width="100%">
				<tr class="datatablehead" align="center"><td>Administratoren des Tactical Information Center</td></tr>
				<tr class="fieldnormallight" style="font-weight:bold;" align="center"><td>
<?php
	$SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE rang="'.$Rang_STechniker.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
	while ($userdata = mysql_fetch_assoc($SQL_Result)) {
		echo $userdata['galaxie'].':'.$userdata['planet'].' ['.$AllianzTag[$userdata['allianz']].'] '.$userdata['name']."<br />\n";
	}
?>
				</td></tr>
			</table>
		</td></tr>
		<tr><td>
			<table width="100%">
				<tr class="datatablehead" align="center"><td>Techniker des Tactical Information Center</td></tr>
				<tr class="fieldnormallight" style="font-weight:bold;" align="center"><td>
<?php
	$SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE rang="'.$Rang_Techniker.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
	while ($userdata = mysql_fetch_assoc($SQL_Result)) {
		echo $userdata['galaxie'].':'.$userdata['planet'].' ['.$AllianzTag[$userdata['allianz']].'] '.$userdata['name']."<br />\n";
	}
?>
				</td></tr>
			</table>
		</td></tr>
<?php
	foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
?>
		<tr><td>
			<table width="100%">
				<tr><td colspan="2" class="datatablehead" align="center"><?= "[".$AllianzTag[$AllianzNummer]."] ".$AllianzNummerName ?></td></tr>
				<tr class="fieldnormaldark" style="font-weight:bold;" align="center"><td>Rang</td><td>Name</td></tr>
<?php
		$color = false;
	
		$SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE allianz="'.$AllianzNummer.'" AND (rang >= "'.$Rang_GC.'" AND rang <= "'.$Rang_Admiral.'") ORDER BY rang DESC, galaxie, planet;', $SQL_DBConn) or $error_code = 4;
		while ($userdata = mysql_fetch_assoc($SQL_Result)) {
			$color = !$color;
			echo '	<tr class="fieldnormal'.($color ? 'light' : 'dark').'"><td>'.$RangName[$userdata['rang']].'</td><td>'.$userdata['galaxie'].':'.$userdata['planet'].' '.$userdata['name'].'</td></tr>';
		}
?>
			</table>
		</td></tr>
<?php
	}
?>
	</table>
</center>
