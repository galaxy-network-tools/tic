<?php
/***************************************************************************
 *   Copyright (C) 2006 by                                                 *
 *      Pascal Gollor <pascal@gollor1.de> -- irc://irc.quakenet.org/Hugch  *
 *      Andreas Hemel <dai.shan@gmx.net>                                   *
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

include('function.irc_paste.php');
 
if ($scan_speichern->debug == 1) { echo '<table><tr><td align="left"><pre>'; }

function irc_scan_log_auslesen($pfad, $datei, $datum) {
	$scan_typ1 = array("sektorscan", "einheitenscan", "gesch.*zscan", "mili.*scan");
	$scan_typ2 = array("sek", "unit", "g", "mili");
	$scan_timeout = 6; // Nach wie vielen Zeilen ein unvolständiger Scan übersprungen wird

	$chan = preg_replace('/(_'.$datum.'\.log)/', '', $datei);
	$query[0] = 'SELECT tag, zeile FROM gn4irc_scans WHERE chan="'.$chan.'"';
	$eintrag = mysql_multi_query($query, 1, 0);
	$last = $eintrag['zeile'];
	if ($last == "") {
		$last = 0;
	} else {
		if ($eintrag['tag'] != date("d")) {
			$last = 0;
		}
	}
	settype($lsat, "int");
	$last++;

	$z0 = 0; $z1 = 0;
	$handle = fopen ($pfad.$datei, "r");
	while (!feof($handle)) {
		$z0++;
		$buffer = trim(fgets($handle, 400));
		if ($z0 >= $last) {
			$muster[0] = '/'.chr(3).'[0-9]{2}\,[0-9]{2}/';
			$muster[1] = '/'.chr(3).'/';
			$muster[2] = '/'.chr(32).'+/';
			$muster[3] = '/</';
			$muster[4] = '/>/';
			$ersetzen[0] = '';
			$ersetzen[1] = '';
			$ersetzen[2] = chr(32);
			$ersetzen[3] = '';
			$ersetzen[4] = '';
			$buffer = preg_replace($muster, $ersetzen, $buffer);
			$log[$z1] = $buffer;
			$z1++;
		}
	}
	fclose ($handle);

	$len0 = count($log);
	for ($i0 = 0; $i0 < $len0; $i0++) {
		$len1 = count($scan_typ1);
		for ($i1 = 0; $i1 < $len1; $i1++) {
			if (preg_match('/('.$scan_typ1[$i1].')/', strtolower($log[$i0])) && preg_match('/%/', $log[$i0])) {
				preg_match('/[0-9]{2}\|[0-9]{2}\|[0-9]{2}/', $log[$i0], $scan_datum, PREG_OFFSET_CAPTURE);
				$a_typ = $scan_typ2[$i1];
				$scan_start = $i0 + 1; // Zeilen angabe(Immer eins mehr als aktuells Array)
				for ($i2 = 1; $i2 <= $scan_timeout; $i2++) {
					$zeile = $log[$i0 + $i2];
					if (!isset($log[$i0 + $i2])) { break 1; }
					$split = preg_split('/'.chr(32).'/', $log[$i0 + $i2]);
					$len3 = count($split);
					for ($i3 = 1; $i3 <= $len3; $i3++) {
						$split[$i3] = preg_replace('/[^0-9:]/', '', $split[$i3]);
						if (koord_check($split[$i3]) || stristr($zeile, "gn-scr1pt by wurst - #wursts.scr1pt1ng-ecke")) {
							$scan_ende = $scan_start + $i2;
							$last = $last + $scan_ende - 1;
							break 4;
						}
					}
				}
				if (!isset($scan_ende) && $i2 == 6) { unset($scan_start); }
			}
		}
	}
	if(!isset($scan_ende)) {
		$last--;
		if (!isset($scan_start)) {
			$last = count($log) + $last - 1;
		}
	}

	$query[0] = 'DELETE FROM gn4irc_scans WHERE chan="'.$chan.'"';
	$query[1] = 'INSERT INTO gn4irc_scans (tag, zeile, chan) VALUES ("'.date("d").'", "'.$last.'", "'.$chan.'")';
	mysql_multi_query($query);

	if(isset($scan_ende)) {
		// mindestens ein scan gefunden
		for ($i0 = $scan_start; $i0 <= $scan_ende; $i0++) {
			$log[$i0 - 1] = trim($log[$i0 - 1]);
			$scan .= $log[$i0 - 1].chr(13).chr(10);
		}
		//eval('irc_'.$a_typ.'_scan('.$scan.');');
		$s_datum = preg_replace('/\|/', ':', $scan_datum[0][0]);
		$s_datum .= " ".date("d.m.Y");
		$s_datum = substr($s_datum, 0, 5).substr($s_datum, 8);
		if ($a_typ == "sek") {
			irc_sek_scan($scan, $s_datum, 1);
		} elseif ($a_typ == "unit") {
			irc_unit_scan($scan, $s_datum, 1);
		} elseif ($a_typ == "g") {
			irc_g_scan($scan, $s_datum, 1);
		} elseif ($a_typ == "mili") {
			irc_mili_scan($scan, $s_datum, 1);
		}
		unset($scan_start);
		unset($scan_ende);
		irc_scan_log_auslesen($pfad, $datei, $datum);
	}
}

function irc_scan_ordner_auslesen($pfad) {
	global $scan_speichern, $log_channel;

	$scan_speichern->echo = 0;
	if (!($handle = opendir($pfad))) {
		echo '<font color="#FF0000">Der Ordner konnte nicht ge&ouml;ffnet werden!!!</font>';
	}

	$datum_format = "Y-m-d";
	$datum = date($datum_format);

	while (false !== ($datei = readdir($handle))) {
		if (preg_match('/(.*_'.$datum.'\.log)/', $datei)) {
			//if (preg_match('/(farg.int)/', $datei)) {
				irc_scan_log_auslesen($pfad, $datei, $datum);
			//}
		}
	}
}

irc_scan_ordner_auslesen($log_pfad);
if ($scan_speichern->debug == 1) { echo '</pre></td></tr></table>'; }

?>
