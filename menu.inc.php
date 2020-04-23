<!-- START: menu.inc.php -->
<div class="navi">
<table>
	<tr>
		<td class="menutop">Taktik</td>
	</tr>
	<tr><td>
		<table cellspacing="1" cellpadding="0" style="background:#000000;width:100%">
			<tr>
				<td class="menu"><a href="./main.php?modul=taktikbildschirm&amp;mode=1"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Incomings</a></td>
			</tr>
			<tr>
				<td class="menu"><a href="./main.php?modul=taktikbildschirm&amp;mode=2"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Flotten</a></td>
			</tr>
<?php
	if ($Benutzer['rang']>=$Rang_GC) {
		echo "			<tr>\n";
		echo "				<td class=\"menu\"><a href=\"./main.php?modul=taktikbildschirm&amp;mode=3\"><img src=\"bilder/skin/menu_item_icon.bmp\" alt=\"\" style=\"padding:0px 5px 0px 5px;\" />Alles</a></td>\n";
		echo "		</tr>\n";
	}
	echo "			<tr>\n";
	echo "				<td class=\"menu\"><a href=\"./main.php?modul=taktikbildschirm&amp;mode=4&amp;allianz=".$Benutzer['allianz']."\"><img src=\"bilder/skin/menu_item_icon.bmp\" alt=\"\" style=\"padding:0px 5px 0px 5px;\" />Ally</a></td>\n";
	echo "			</tr>\n";
?>
			<tr>
				<td class="menu"><a href="./main.php?modul=taktikbildschirm&amp;mode=5"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Galaxie <?=$Benutzer['galaxie']?></a></td>
			</tr>
		</table>
	</td></tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="menutop">Tic-Intern</td>
	</tr>
	<tr><td>
		<table cellspacing="1" style="width:100%;background:#000000;">
			<tr>
				<td class="menu"><a href="./main.php?modul=NWshow"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Nachtwache</a></td>
			</tr>
			<tr>
				<td class="menu"><a href="./main.php?modul=scans"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Scan-Erfassen</a></td>
			</tr>
			<tr>
				<td class="menu"><a href="./main.php?modul=ircpaste"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />IRC-Paste</a></td>
			</tr>
			<tr>
				<td class="menu"><a href="./main.php?modul=showgalascans&amp;displaytype=0&amp;xgala=<?=$Benutzer['galaxie']?>&amp;xplanet=<?=$Benutzer['planet']?>"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Scans-Anzeige</a></td>
			</tr>
			<tr>
				<td class="menu"><a href="./main.php?modul=forum&amp;faction=show&amp;falli=0&amp;ftopic=0"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Forum</a></td>
			</tr>
			<tr>
				<td class="menu"><a href="./main.php?modul=profil"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Profil</a></td>
			</tr>
			<tr>

<?
	$attanzahl = AttAnzahl($Benutzer['allianz'],$Benutzer['ticid'],0);
	echo '			<td class="menu"><a href="./main.php?modul=attplanung"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Att-Planung (';
	echo '<font color="#'.$ATTSTATUSHTML[1].'">'.$attanzahl.'</font>/';
	$attanzahl = AttAnzahl($Benutzer['allianz'],$Benutzer['ticid'],1);
	echo '<font color="#'.$ATTSTATUSHTML[5].'">'.$attanzahl.'</font>)</a></td>';
?>
			</tr>
			<tr>
				<td class="menu"><a href="help/help.html" target="tic-hilfe"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Hilfe</a></td>
			</tr>
		</table>
	</td></tr>
<?php
	if ($Benutzer['rang'] >= $Rang_GC) {
		echo "	<tr>\n";
		echo "		<td>&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"menutop\">Admin</td>\n";
		echo "	</tr>\n";
		echo "	<tr><td>\n";
		echo "		<table cellspacing=\"1\" style=\"width:100%;background:#000000;\">\n";
		echo "			<tr>\n";
		echo "				<td class=\"menu\"><a href=\"./main.php?modul=management_alli\"><img src=\"bilder/skin/menu_item_icon.bmp\" alt=\"\" style=\"padding:0px 5px 0px 5px;\" />Allianz-Management</a></td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td class=\"menu\"><a href=\"./main.php?modul=management_meta\"><img src=\"bilder/skin/menu_item_icon.bmp\" alt=\"\" style=\"padding:0px 5px 0px 5px;\" />Meta-Management</a></td>\n";
		echo "			</tr>\n";
		if ($Benutzer['rang'] >= $Rang_Techniker) {
			echo "			<tr>\n";
			echo "				<td class=\"menu\"><a href=\"./main.php?modul=management_channels\"><img src=\"bilder/skin/menu_item_icon.bmp\" alt=\"\" style=\"padding:0px 5px 0px 5px;\" />Channel-Management</a></td>\n";
			echo "			</tr>\n";
		}
		echo "			<tr>\n";
		echo "				<td class=\"menu\"><a href=\"./main.php?modul=userman\"><img src=\"bilder/skin/menu_item_icon.bmp\" alt=\"\" style=\"padding:0px 5px 0px 5px;\" />Benutzerverw.</a></td>\n";
		echo "			</tr>\n";
		if ($Benutzer['rang'] > $Rang_GC) {
			echo "			<tr>\n";
			echo "				<td class=\"menu\"><a href=\"./main.php?modul=nachrichtschreiben\"><img src=\"bilder/skin/menu_item_icon.bmp\" alt=\"\" style=\"padding:0px 5px 0px 5px;\" />Nachricht</a></td>\n";
			echo "			</tr>\n";
		}
		if ($Benutzer['rang'] >= $Rang_Techniker) {
			echo "			<tr>\n";
			echo "				<td class=\"menu\"><a href=\"./main.php?modul=log\"><img src=\"bilder/skin/menu_item_icon.bmp\" alt=\"\" style=\"padding:0px 5px 0px 5px;\" />Log</a></td>\n";
			echo "			</tr>\n";
		}
		echo "		</table>\n";
		echo "	</td></tr>";
	}
?>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="menutop">Sonstiges</td>
	</tr>
	<tr><td>
		<table cellspacing="1" style="width:100%;background:#000000;">
			<tr>
				<td class="menu"><a href="./main.php?modul=vag-rechner"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Vag-Rechner</a></td>
			</tr>
			<tr>
				<td class="menu"><a href="./main.php?modul=kampf"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Kampf-Simu</a></td>
			</tr>
			<tr>
				<td class="menu"><a href="./main.php?modul=statistic"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Statistic</a></td>
			</tr>
			<tr>
				<td class="menu"><a href="./main.php?modul=listen"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Listen</a></td>
			</tr>
			<tr>
				<td class="menu"><a href="./main.php?modul=impressum"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Impressum</a></td>
			</tr>
			<tr>
				<td class="menu"><a href="./main.php?modul=logout"><img src="bilder/skin/menu_item_icon.bmp" alt="" style="padding:0px 5px 0px 5px;" />Logout</a></td>
			</tr>
		</table>
	</td></tr>
</table>
</div>
<!-- END: menu.inc.php -->
