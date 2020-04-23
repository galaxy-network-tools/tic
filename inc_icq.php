<?
/***************************************************************************
 *   Copyright (C) 2006 by Andreas Hemel <dai.shan@gmx.net>, Pascal Gollor *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA              *
 ***************************************************************************/
?>

<table align="center">
<tr class="datatablehead">
<td align="middle">Status</td>
<td align="middle">ICQ-Nummer</td>
<td align="middle">Ally</td>
<td align="middle">Gala</td>
<td align="middle">Pos</td>
<td align="middle">Nick</td>
<td align="middle">Rang</td>
</tr>

<?

include ('globalvars.php');

icq_auslesen();

function icq_auslesen() {
	global $RangName;
	global $Benutzer;

	$icq_lnk_about = "http://www.icq.com/people/about_me.php?uin=";
	$icq_lnk_online = "http://web.icq.com/whitepages/online?icq=";

	$bild = 5;
	$breite = 18;
	$hoehe = 18;
	
	$qry = "SELECT gn4accounts.name AS name, ".
			"messangerID AS icq, ".
			"gn4allianzen.name AS allianz, ".
			"galaxie, ".
			"planet, ".
			"rang ".
		"FROM gn4accounts JOIN gn4allianzen ON gn4accounts.allianz = gn4allianzen.id ".
		"WHERE gn4accounts.ticid = '{$Benutzer['ticid']}' AND messangerID != '' ".
		"ORDER BY gn4accounts.allianz,galaxie,planet;";
	$result = tic_mysql_query($qry);
	$row = mysql_fetch_array($result);
	for ($i=0; $row != FALSE; $row = mysql_fetch_array($result), $i++) {

		$row['icq'] = preg_replace("/[^0-9]+/", '', $row['icq']);
		if (!preg_match('/^[0-9]{6,9}$/', $row['icq'])) {
			$i--;
			continue;
		}
		if ($i%2==0) {
			echo "<tr class=\"fieldnormallight\">\n";
		} else {
			echo "<tr class=\"fieldnormaldark\">\n";
		}
		
		echo '<td align="middle">';
		echo "<img src=\"$icq_lnk_online{$row['icq']}&img={$bild}\" width=\"$breite\" height=\"$hoehe\" border=\"0\"></a>";
		echo "</td>\n";
		
		echo '<td align="middle">';
		echo "<a href=\"$icq_lnk_about{$row['icq']}\" target=\"_blank\" class=\"nlink\">".number_format($row['icq'], 0, ',', '-');
		echo "</td>\n";

		echo "<td align=\"middle\">{$row['allianz']}</td>\n";
		echo "<td align=\"middle\">{$row['galaxie']}</td>\n";
		echo "<td align=\"middle\">{$row['planet']}</td>\n";
		echo "<td align=\"middle\">{$row['name']}</td>\n";
		echo "<td align=\"middle\">{$RangName[$row['rang']]}</td>\n";
		echo "</tr>\n";
	}
}

?>
</table>
<br />
Legende: <img src="http://status.icq.com/5/online1.gif"> = Online | <img src="http://status.icq.com/5/online0.gif"> = Offline | <img src="http://status.icq.com/5/online2.gif"> = Status nicht erfasst
</font>
</center>
</body>
</html>

