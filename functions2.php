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

function mysql_multi_query($senden, $typ = 0, $array = -1) {
	global $SQL_DBConn;

	if (is_array($senden)) {
		for ($i0 = 0; $i0 < count($senden); $i0++) {
			$query[$i0] = mysql_query($senden[$i0], $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
			if ($typ == 1) {
				$query[$i0] = mysql_fetch_assoc($query[$i0]);
			} elseif ($typ == 2) {
				$query[$i0] = mysql_num_rows($query[$i0]);
			}
		}
		if ($array == -1) {
			return $query;
		} else {
			return $query[$array];
		}
	} else {
		die('<font color="#FF0000">Es wurde kein Array an die Funktion &uuml;bergeben!!!</font>');
	}
}

function koord_check($koord) {
	if(preg_match('/^[0-9]{1,4}:[1-9]{1}[0-9]{0,1}$/', $koord)) {
		$split = preg_split('/:/', $koord);
		$gala = $split[0];
		$planet = $split[1];
		$zu = 0;

		if (strlen($planet) == 1) {
			$zu = 1;
		} elseif (strlen($planet) == 2) {
			if (preg_match('/^[12]{1}[0-9]{1}$/', $planet)) {
				$zu = 1;
			}
		}
		if (!preg_match('/^[1-9]{1}/', $gala)) {
			$zu = 0;
		}
	} else {
		$zu = 0;
	}
	return $zu;
}

function preg_array_split($muster, $array) {
	$len0 = count($array);
	for ($i0 = 0; $i0 < $len0; $i0++) {
		$array[$i0] = preg_split($muster, $array[$i0]);
	}
	return $array;
}

function debug($string, $farbe = 0) {
	switch ($farbe) {
	case 0:
		$farbe = "red";
		break;
	case 1:
		$farbe = "yellow";
		break;
	case 2:
		$farbe = "green";
		break;
	case 3:
		$farbe = "blue";
		break;
	}
	echo '<font color="'.$farbe.'">'.$string.'</font>';
}

class scan_speichern {
	var $debug = 0;
	var $echo = 0;
	var $fehler = 0;
	var $log_datei = "./scan_log.txt";

	function sek($scan, $sek) {
		$this->sek_check($sek);
		if ($this->debug == 1) {
			echo "sek_scan:";
			//print_r($scan);
			echo $scan['name'].'('.$scan['gala'].':'.$scan['planet'].') Genauigkeit: '.$scan['gen'].'%';;
			echo "\nsek:";
			print_r($sek);
			echo "\n";
		} else {
			$this->add_nick_to_db($scan['gala'], $scan['planet'], $scan['name']);
			$this->add_nick_to_db($scan['scanner']['gala'], $scan['scanner']['planet'], $scan['scanner']['name']);
			$query[0] = 'DELETE FROM gn4scans '.
				'WHERE rg="'.$scan['gala'].'" AND rp="'.$scan['planet'].'" AND type="'.$GLOBALS['scan_typ']['sek'].'"';
			$query[1] = 'INSERT INTO gn4scans '.
					'(type, zeit, g, p, rg, rp, gen, pts, s, d, me, ke, a) '.
				'VALUES ("'.$GLOBALS['scan_typ']['sek'].'", "'.$scan['datum'].'", '.
					'"'.$scan['scanner']['gala'].'", "'.$scan['scanner']['planet'].'", '.
					'"'.$scan['gala'].'", "'.$scan['planet'].'", "'.$scan['gen'].'", '.
					'"'.$sek['punkte'].'", "'.$sek['schiffe'].'", "'.$sek['deff'].'", '.
					'"'.$sek['me'].'", "'.$sek['ke'].'", "'.$sek['ast'].'")';
			if ($this->fehler == 0) { $sql_result = mysql_multi_query($query); }
			if ($this->echo == 1) {
				echo 'Sektorscan von <u><a href="main.php?modul=showgalascans&displaytype=0&xgala='.$scan['gala'].'&xplanet='.$scan['planet'].'">'.
					$scan['name'].'('.$scan['gala'].':'.$scan['planet'].')'.
					'</a></u> wurde erfolgreich eingetragen.<br />';
			}
		}
		if ($this->fehler == 0) {
			if ($sek['deff'] == 0) { $this ->g($scan, 0); }
			if ($sek['schiffe'] == 0) { $this ->mili($scan, 0); }
		}
	}
	function unit($scan, $unit, $pruefen = 1) {
		if ($pruefen == 1) { $this->unit_check($unit); }
		if ($this->debug == 1) {
			echo "unit_scan:";
			//print_r($scan);
			echo $scan['name'].'('.$scan['gala'].':'.$scan['planet'].') Genauigkeit: '.$scan['gen'].'%';;
			echo "\nunit:";
			print_r($unit);
			echo "\n";
		} else {
			$this->add_nick_to_db($scan['gala'], $scan['planet'], $scan['name']);
			$this->add_nick_to_db($scan['scanner']['gala'], $scan['scanner']['planet'], $scan['scanner']['name']);
			$query[0] = 'DELETE FROM gn4scans '.
				'WHERE rg="'.$scan['gala'].'" AND rp="'.$scan['planet'].'" AND type="'.$GLOBALS['scan_typ']['unit'].'"';
			$query[1] = 'INSERT INTO gn4scans '.
					'(type, zeit, g, p, rg, rp, gen, sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfka, sfsu) '.
				'VALUES ("'.$GLOBALS['scan_typ']['unit'].'", "'.$scan['datum'].'", '.
					'"'.$scan['scanner']['gala'].'", "'.$scan['scanner']['planet'].'", '.
					'"'.$scan['gala'].'", "'.$scan['planet'].'", "'.$scan['gen'].'", '.
					'"'.$unit['j'].'", "'.$unit['b'].'", "'.$unit['f'].'", "'.$unit['z'].'", "'.$unit['kr'].'", '.
					'"'.$unit['sl'].'", "'.$unit['tr'].'", "'.$unit['ka'].'", "'.$unit['ss'].'")';
			if ($this->fehler == 0) { $sql_result = mysql_multi_query($query); }
			if ($this->echo == 1) {
				echo 'Einheitenscan von <u><a href="main.php?modul=showgalascans&displaytype=0&xgala='.$scan['gala'].'&xplanet='.$scan['planet'].'">'.
					$scan['name'].'('.$scan['gala'].':'.$scan['planet'].')'.
					'</a></u> wurde erfolgreich eingetragen.<br />';
			}
		}
	}
	function g($scan, $g) {
		$this->g_check($g);
		if ($this->debug == 1) {
			echo "g_scan:";
			//print_r($scan);
			echo $scan['name'].'('.$scan['gala'].':'.$scan['planet'].') Genauigkeit: '.$scan['gen'].'%';;
			echo "\ng:";
			print_r($g);
			echo "\n";
		} else {
			$debug = 0;
			$this->add_nick_to_db($scan['gala'], $scan['planet'], $scan['name']);
			$this->add_nick_to_db($scan['scanner']['gala'], $scan['scanner']['planet'], $scan['scanner']['name']);
			$query[0] = 'DELETE FROM gn4scans '.
				'WHERE rg="'.$scan['gala'].'" AND rp="'.$scan['planet'].'" AND type="'.$GLOBALS['scan_typ']['g'].'"';
			$query[1] = 'INSERT INTO gn4scans '.
					'(type, zeit, g, p, rg, rp, gen, glo, glr, gmr, gsr, ga) '.
				'VALUES ("'.$GLOBALS['scan_typ']['g'].'", "'.$scan['datum'].'", '.
					'"'.$scan['scanner']['gala'].'", "'.$scan['scanner']['planet'].'", '.
					'"'.$scan['gala'].'", "'.$scan['planet'].'", "'.$scan['gen'].'", '.
					'"'.$g['lo'].'", "'.$g['lr'].'", "'.$g['mr'].'", "'.$g['sr'].'", "'.$g['aj'].'")';
			if ($this->fehler == 0) { $sql_result = mysql_multi_query($query); }
			if ($this->echo == 1) {
				echo 'Gesch&uuml;tzscan von <u><a href="main.php?modul=showgalascans&displaytype=0&xgala='.$scan['gala'].'&xplanet='.$scan['planet'].'">'.
					$scan['name'].'('.$scan['gala'].':'.$scan['planet'].')'.
					'</a></u> wurde erfolgreich eingetragen.<br />';
			}
		}
	}
	function mili($scan, $mili) {
		global $scan_teil;

		$len0 = count($scan_teil['unit']);
		for ($i0 = 0; $i0 < $len0; $i0++) {
			$art = $scan_teil['unit'][$i0];
			$unit[$art] = ($mili[0][$art] + $mili[1][$art] + $mili[2][$art]);
		}
		if ($this->fehler == 0) { $this->unit($scan, $unit); }

		if ($this->debug == 1) {
			echo "mili_scan:";
			//print_r($scan);
			echo $scan['name'].'('.$scan['gala'].':'.$scan['planet'].') Genauigkeit: '.$scan['gen'].'%';;
			echo "\nmili:";
			print_r($mili);
			echo "\n";
		} else {
			$this->add_nick_to_db($scan['gala'], $scan['planet'], $scan['name']);
			$this->add_nick_to_db($scan['scanner']['gala'], $scan['scanner']['planet'], $scan['scanner']['name']);
			$query[0] = 'DELETE FROM gn4scans '.
				'WHERE rg="'.$scan['gala'].'" AND rp="'.$scan['planet'].'" AND type="'.$GLOBALS['scan_typ']['mili'].'"';
			$query[1] = 'INSERT INTO gn4scans '.
					'(type, zeit, g, p, rg, rp, gen, '.
					'sf0j, sf0b, sf0f, sf0z, sf0kr, sf0sa, sf0t, sf0ka, sf0su, '.
					'sf1j, sf1b, sf1f, sf1z, sf1kr, sf1sa, sf1t, sf1ka, sf1su, '.
					'sf2j, sf2b, sf2f, sf2z, sf2kr, sf2sa, sf2t, sf2ka, sf2su, '.
					'status1, ziel1, status2, ziel2) '.
				'VALUES ("'.$GLOBALS['scan_typ']['mili'].'", "'.$scan['datum'].'", '.
					'"'.$scan['scanner']['gala'].'", "'.$scan['scanner']['planet'].'", '.
					'"'.$scan['gala'].'", "'.$scan['planet'].'", "'.$scan['gen'].'", '.
					'"'.$mili[0]['j'].'", "'.$mili[0]['b'].'", "'.$mili[0]['f'].'", "'.$mili[0]['z'].'", "'.$mili[0]['kr'].'", '.
						'"'.$mili[0]['sl'].'", "'.$mili[0]['tr'].'", "'.$mili[0]['ka'].'", "'.$mili[0]['ss'].'", '.
					'"'.$mili[1]['j'].'", "'.$mili[1]['b'].'", "'.$mili[1]['f'].'", "'.$mili[1]['z'].'", "'.$mili[1]['kr'].'", '.
						'"'.$mili[1]['sl'].'", "'.$mili[1]['tr'].'", "'.$mili[1]['ka'].'", "'.$mili[1]['ss'].'", '.
					'"'.$mili[2]['j'].'", "'.$mili[2]['b'].'", "'.$mili[2]['f'].'", "'.$mili[2]['z'].'", "'.$mili[2]['kr'].'", '.
						'"'.$mili[2]['sl'].'", "'.$mili[2]['tr'].'", "'.$mili[2]['ka'].'", "'.$mili[2]['ss'].'", '.
					'"'.$mili[1]['status'].'", "'.$mili[1]['ziel'].'", "'.$mili[2]['status'].'", "'.$mili[2]['ziel'].'")';
			if ($this->fehler == 0) { $sql_result = mysql_multi_query($query); }
			if ($this->echo == 1) {
				echo 'Milit&auml;rscan von <u><a href="main.php?modul=showgalascans&displaytype=0&xgala='.$scan['gala'].'&xplanet='.$scan['planet'].'">'.
					$scan['name'].'('.$scan['gala'].':'.$scan['planet'].')'.
					'</a></u> wurde erfolgreich eingetragen.<br />';
			}
		}
	}
	function sek_check($sek) {
		global $scan_teil;

		$fehler = 0;
		$len0 = count($scan_teil['sek']);
		for ($i0 = 0; $i0 < $len0; $i0++) {
			$art = $scan_teil['sek'][$i0];
			if (!isset($sek[$art])) { $fehler = 1; }
			if (!preg_match('/^[0-9]+$/', $sek[$art])) { $fehler = 1; }
		}
		if ($fehler == 0) { $this->is_scan = 1; }
		$this->fehler_aus($fehler);
	}
	function unit_check($unit) {
		global $scan_teil;

		$fehler = 0;
		$len0 = count($scan_teil['unit']);
		for ($i0 = 0; $i0 < $len0; $i0++) {
			$art = $scan_teil['unit'][$i0];
			if (!isset($unit[$art])) { $fehler = 2; }
			if (!preg_match('/^[0-9]+$/', $unit[$art])) { $fehler = 2; }
		}
		if ($fehler == 0) { $this->is_scan = 1; }
		$this->fehler_aus($fehler);
	}
	function g_check($g) {
		global $scan_teil;

		$fehler = 0;
		$len0 = count($scan_teil['g']);
		for ($i0 = 0; $i0 < $len0; $i0++) {
			$art = $scan_teil['g'][$i0];
			if (isset($g[$art]) && !preg_match('/^[0-9]+$/', $g[$art])) { $fehler = 3; }
		}
		if ($fehler == 0) { $this->is_scan = 1; }
		$this->fehler_aus($fehler);
	}
	function mili_check($mili) {
		global $scan_teil;

		$fehler = 0;
		$len0 = count($scan_teil['mili']);
		for ($i0 = 0; $i0 < $len0; $i0++) {
			$art = $scan_teil['mili'][$i0];
			if (isset($mili[$art]) && !preg_match('/^[0-9]+$/', $mili[$art])) { $fehler = 4; }
		}
		if ($fehler == 0) { $this->is_scan = 1; }
		$this->fehler_aus($fehler);
	}
	function fehler_aus($fehler) {
		if ($fehler != 0) {
			$typ = array("", "Sektorscan", "Einheitenscan", "Gesch&uuml;tzscan", "Milit&auml;rscan");
			$m0 = '<font color="#FF0000">';
			if ($fehler <= 4 && $fehler >= 1) {
				$m1 = 'Der '.$typ[$fehler].' ist fehlerhaft oder unvollst&auml;ndig!!!';
			} else {
				$m1 = 'FEHLER!!!';
			}
			if ($this->debug == 1) {
				echo $m0.$m1."</font>\n";
			} elseif ($this->echo == 1) {
				die($m0.$m1.'</font>');
			}
			$this->log($m1);
		}
		$this->fehler = $fehler;
	}
	function add_nick_to_db($gala, $planet, $name) {
		if ($this ->debug == 1) {
			print_r($scan);
			print_r($sek);
		} else {
			$query[0] = 'DELETE FROM gn4gnuser WHERE gala="'.$gala.'" AND planet="'.$planet.'"';
			$query[1] = 'INSERT INTO gn4gnuser (gala, planet, name, erfasst) '.
				'VALUES ("'.$gala.'", "'.$planet.'", "'.$name.'", "'.time().'")';
			mysql_multi_query($query);
		}
	}
	function log($fehler) {
		$handle = fopen ($this->log_datei, "a");
		if ($handle !== false) {
			fputs ($handle, date("U").": ".$fehler."\n");
			fclose ($handle);
		}
	}
}

?>
