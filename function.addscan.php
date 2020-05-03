<?php
class bewegung {
	var $ag, $ap, $vg, $vp, $modus, $safe, $eta, $flottennr, $erfasser, $erfasst_am, $aname, $vname;
};
//error_reporting (E_ALL);

?>
<!--
<?php
//print_r($_POST);
?>
-->
<?php

function addGalaxieSekScans($galaxiemitglieder) {
	global $Benutzer;
	global $SQL_DBConn;

	for ($i = 0; $i < sizeof($galaxiemitglieder); $i++) {
		echo $galaxiemitglieder[$i]["galaxie"].":".$galaxiemitglieder[$i]["planet"]." ".$galaxiemitglieder[$i]["name"]." -> ".$galaxiemitglieder[$i]["punkte"]."<br>\n";
		addgnuser($galaxiemitglieder[$i]["galaxie"], $galaxiemitglieder[$i]["planet"], $galaxiemitglieder[$i]["name"]);

		$delcommand = "DELETE FROM `gn4scans` WHERE rg='".$galaxiemitglieder[$i]["galaxie"]."' AND rp='".$galaxiemitglieder[$i]["planet"]."' AND type='0';";
		$SQL_Result = tic_mysql_query( $delcommand, $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());

		$addcommand = "INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, pts, s, d, me, ke, a)
		VALUES ('0', '".date("H:i d.m.Y")."', '".$Benutzer['galaxie']."', '".$Benutzer['planet']."', '".$galaxiemitglieder[$i]["galaxie"]."', '".$galaxiemitglieder[$i]["planet"]."', '99', '".$galaxiemitglieder[$i]["punkte"]."', '".$galaxiemitglieder[$i]["flotte"]."', '".$galaxiemitglieder[$i]["geschuetze"]."', '".$galaxiemitglieder[$i]["mextraktoren"]."', '".$galaxiemitglieder[$i]["kextraktoren"]."', '".$galaxiemitglieder[$i]["asteroiden"]."');";
		$SQL_Result = tic_mysql_query( $addcommand, $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
	}
}

function parseBewegung($in, $modus, $bew_html, $eta_html, $gala, $pos, $myname, &$flottenbewegungen) {
	global $Ticks;

	preg_match_all('/(-)*([0-9]+):([0-9]+) (.*?)<br>/', $bew_html, $mao, PREG_SET_ORDER);
	preg_match_all('/<nobr>([^<]+)<\/nobr>/', $eta_html, $maoe, PREG_SET_ORDER);

	/*echo $eta_html."\n";
	print_r($mao); echo "\n";
	print_r($maoe); echo "\n";*/

	foreach($mao as $num=>$att) {
		$bew = new bewegung();

		//entferne evtl. scanlinks
		$mao[$num][4] = preg_replace('/<a.*$/', '', $mao[$num][4]);

		//beim deffen in der gala taucht die fleet 2 mal in der taktik auf
		// --> einmal ignorieren
		if ($in && $modus == 2 && $gala == $mao[$num][2]) {
			continue;
		}

		if ($in) {
			$bew->vg = $gala;
			$bew->vp = $pos;
			$bew->ag = $mao[$num][2];
			$bew->ap = $mao[$num][3];
			$bew->vname = $myname;
			$bew->aname = $mao[$num][4];
		} else {
			$bew->ag = $gala;
			$bew->ap = $pos;
			$bew->vg = $mao[$num][2];
			$bew->vp = $mao[$num][3];
			$bew->aname = $myname;
			$bew->vname = $mao[$num][4];
		}
		$bew->modus = $modus;
		$bew->safe = 0;


		if (preg_match('/^([0-9]+):([0-9]+)/', $maoe[$num][1], $mtm)) {
			$bew->eta = (int)(($mtm[1]*60 + $mtm[2]) / $Ticks['lange']) + 1;
		} elseif (preg_match('/([0-9]+) Min/i', $maoe[$num][1], $mtm)) {
			$bew->eta = (int)($mtm[1] / $Ticks['lange']) + 1;
		} elseif (preg_match('/([0-9]+)/', $maoe[$num][1], $mtm)) {
			$bew->eta = (int)$mtm[1];
		}
		if (isset($mao[$num][1]) and preg_match('/-/', $mao[$num][1]) ) {
			$bew->modus += 2;
		}

		array_push($flottenbewegungen, array(
			"modus" => $bew->modus,
			"start_galaxie" => $bew->ag,
			"start_planet" => $bew->ap,
			"start_name" => $bew->aname,
			"ziel_galaxie" => $bew->vg,
			"ziel_planet" => $bew->vp,
			"ziel_name" => $bew->vname,
			"eta" => $bew->eta,
			"fleet" => 0,
			"safe" => 0,
			"mod" => 0
		));
	}
}


function parseLine( $line_in) {
	$templine = str_replace( chr(9), ' ',  $line_in );
	$templine = str_replace( '  ', ' ', $templine );
	$templine = str_replace( '.', '', $templine );

	return explode( ' ', trim( $templine ));
}

function grabShipData($data) {
    if (empty($data)) {
        return 0;
    } else {
        return trim($data);
    }
}

// Uebergebene Werte setzen
	$action = isset($_POST['action'])?$_POST['action']:(isset($_GET['action'])?$_GET['action']:"");
	$txtScan = isset($_POST['txtScan'])?$_POST['txtScan']:"";

// Scan hinzufuegen
	if ($action == 'addscan') { // 1
		if ($txtScan != '') { // 2
			$txtScanOrg = $txtScan;
			$txtScan = str_replace( " \t", ' ', $txtScan );
			$txtScan = str_replace( "\t", ' ', $txtScan );
			$zeilen = explode("\n", trim($txtScan)); // Zeilenumbrueche
			$zeilen = explode("\x0d", trim($txtScan)); // Zeilenumbrueche
			$zeilen = explode("\x0a", trim($txtScan)); // Zeilenumbrueche
			#$zeilen[0] = str_replace(':', ' ', $zeilen[0]);
			$zeilen[0] = preg_replace('/([0-9]+):([0-9]+)/', '$1 $2', $zeilen[0]);
			$daten = explode(' ', trim($zeilen[0]));		// Sektorscan Ergebnis (Genauigkeit:100%)
			$scan_typ = trim($daten[0]);

			if (ereg("(Flottenbewegungen[^·]*·  Nachricht an die gesamte Galaxie senden ··»)", $txtScanOrg, $ereg_tmp) or preg_match('/DC.Publisher/', urldecode($txtScanOrg))) { // 3


				$html = urldecode($txtScanOrg);
				$flottenbewegungen = array();
				if (preg_match('/DC.Publisher/', $html)) { // 4: Wir haben HTML-Code bekommen!
					$html = preg_replace('/[\n\r]+/mi', "\r", $html);
					$html = preg_replace('/&amp;/', '&', $html);
					$html = preg_replace('/\\"/', '"', $html);
					$html = preg_replace('/"/', '\"', $html);
					$html = preg_replace('/[ \t|]*<a href=."(http:\/\/www.galaxy-network.de\/game\/)*waves.php\?action=Scannen&c1=[0-9]*&c2=[0-9]*&typ=[a-z]*.">[a-z]*<\/a>[ \t|]*/i', "", $html);
					$html = preg_replace('/\r\s*/i', "\r", $html);
					$html = preg_replace('/(<span class=."gc_color.">)|(<\/span>)/i', "", $html);
					$html = preg_replace('/&nbsp;/i', " ", $html);
					$html = preg_replace('/<a href=."http:\/\/www.galaxy-network.de\/game\/uni.php\?gala=[0-9]+(&sid=[a-z0-9]+){0,1}.">([0-9]+)<\/a>:([[:digit:]]+) (.*?) *<br>/i', "$1:$2 $3<br>", $html);
					$html = preg_replace('/<a href=."uni.php\?gala=[0-9]+(&sid=[a-z0-9]+){0,1}.">([0-9]+)<\/a>:([[:digit:]]+) (.*?) *<br>/i', "$2:$3 $4<br>", $html);
					$html = preg_replace('/<a href=."http:\/\/www.galaxy-network.de\/game\/comsys.php\?action=sendmsg&toid1=([0-9]+)&toid2=([0-9]+)(&sid=[a-z0-9]+){0,1}.">(.*?)<\/a>/i', "$4", $html);
					$html = preg_replace('/<a href=."comsys.php\?action=sendmsg&toid1=([0-9]+)&toid2=([0-9]+)(&sid=[a-z0-9]+){0,1}.">(.*?)<\/a>/i', "$4", $html);
					$html = preg_replace('/R.{0,3}ckflug *<br>\((.*?) *\)/i', "-$1", $html);


					$members = array();

					$i = preg_match_all('/.*?tr class=."R.">(.*?)<\/tr>/i', $html, $matches, PREG_PATTERN_ORDER);

					foreach ($matches[1] as $member) { // 5
						array_push($members, $member);
					}; // 5

					preg_match('/<td class=."welcometext.">Willkommen.*?([0-9]+):[0-9]+\)\!<\/td>/i', $html, $mm);
					$this_galaxy = $mm[1];


					foreach ($members as $member) { // 5
						if (preg_match('/<td class=."r.">(.*?):(.*?)<\/td>.*<td class=."r2.">(.*?)[ *]*<\/td>.*?<td>(.*?)<\/td>.*?<td class=."r2.">(.*?)<\/td>.*?<td>(.*?)<\/td>.*?<td class=."r2.">(.*?)<\/td>.*?<td>(.*?)<\/td>.*?<td class=."r2.">(.*?)<\/td>.*?<td>(.*?)<\/td>.*?<td class=."r2.">(.*?)<\/td>/i',
								$member, $matches) == 1) { // 6
							$gala = $matches[1];
							$pos = $matches[2];
							$myname = $matches[3];
							$at_out = $matches[4];
							$at_out_etas = $matches[5];
							$de_out = $matches[6];
							$de_out_etas = $matches[7];
							$at_in = $matches[8];
							$at_in_etas = $matches[9];
							$de_in = $matches[10];
							$de_in_etas = $matches[11];

							/*if ($Benutzer['name'] == 'daishan') {
								echo "at out\n";
								print_r($at_out);
								echo "\n";
								print_r($at_out_etas);
								echo "\n";
								echo "de out\n";
								print_r($de_out);
								echo "\n";
								print_r($de_out_etas);
								echo "\n";
								echo "at in\n";
								print_r($at_in);
								echo "\n";
								print_r($at_in_etas);
								echo "\n";
								echo "de in\n";
								print_r($de_in);
								echo "\n";
								print_r($de_in_etas);
								echo "\n";
							}*/
							parseBewegung(false, 1, $at_out, $at_out_etas, $gala, $pos, $myname, $flottenbewegungen);
							parseBewegung(false, 2, $de_out, $de_out_etas, $gala, $pos, $myname, $flottenbewegungen);
							parseBewegung(true, 1, $at_in, $at_in_etas, $gala, $pos, $myname, $flottenbewegungen);
							parseBewegung(true, 2, $de_in, $de_in_etas, $gala, $pos, $myname, $flottenbewegungen);

						}; // 6
					} // 5
				} else { // 4


					$text_in = $ereg_tmp[1];

					$from_opera = false;
					if (ereg(chr(9).chr(9).chr(13).chr(10)."Sektor".chr(9), $text_in)) { // 5
						$from_opera = true;
						echo "Browser: Opera<BR>\n";
					} // 5

	// Umwandeln der Eingabe auf ein einheitliches Format
					$text_in = ereg_replace( "Flottenbewegungen(.*)Sektor", "Flottenbewegungen".chr(13).chr(10)."Sektor", $text_in );
					$text_in = ereg_replace( "Sektor(.*)Kommandant", "Sektor-Kommandant",$text_in );
					$text_in = str_replace( "Greift an", "Greift_an", $text_in );
					$text_in = str_replace( "Wird angegriffen von", "Wird_angegriffen_von", $text_in );
					$text_in = str_replace( "Wird verteidigt von", "Wird_verteidigt_von", $text_in );
					$text_in = str_replace( "·  Nachricht an die gesamte Galaxie senden ··»", "·__Nachricht_an_die_gesamte_Galaxie_senden_··»", $text_in );
					$text_in = str_replace( " *", "", $text_in );
					$text_in = str_replace( " Min", "m", $text_in );
					$text_in = str_replace( " Std", "s", $text_in );
					$text_in = preg_replace( "/ (\|[SEMGN])+\|/", "", $text_in );
					$text_in = preg_replace( "/\|/", "", $text_in );
					$text_in = str_replace(chr(32).chr(9), chr(9), $text_in );
					$text_in = str_replace(chr(32).chr(13).chr(10).chr(32).chr(13).chr(10), chr(32).chr(13).chr(10), $text_in );
					$text_in = str_replace(chr(32).chr(13).chr(10), chr(13).chr(10), $text_in );
					$text_in = preg_replace( "/(\d+\:\d+)[ ".chr(9)."]([^".chr(10).chr(13)."])/", "$1-$2", $text_in );
					$text_in = preg_replace( "/Rückflug".chr(13).chr(10)."\((\d+\:\d+)-([^".chr(10).chr(13)."]*)\)/", "Rückflug-$1-$2", $text_in );
					$text_in = str_replace(chr(32), chr(9), $text_in );
					$text_in = str_replace(chr(13).chr(10).chr(9), chr(9), $text_in );
					$text_in = str_replace("-".chr(9), chr(9), $text_in );
					if ($from_opera)
						$text_in = str_replace(chr(9).chr(13).chr(10), chr(13).chr(10), $text_in );


	// Zerlegen der Eingabe in die Tabellen-Zellen
					$text_reg = $text_in;
					$taktik = array();
					$break_it = 0;
					do { // 5
						$break = true;
						if ( ereg ( "([^".chr(9).chr(13).chr(10)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*)".chr(13).chr(10), $text_reg, $line_reg) ) { // 6
							if ( ereg ( "([^".chr(9).chr(10).chr(13)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)", $line_reg[1], $cells) and sizeof($cells) == 10) { // 7
								$temparray = $cells;
								array_shift($temparray);
								array_push($taktik, $temparray);
							} // 7
							$text_reg = ereg_replace( quotemeta($line_reg[1]).chr(13).chr(10), "", $text_reg);
							$break = false;
						} // 6
						$break_it++;
					} while (($break == false) && ($break_it < 25)); // 5

	// Erstellen der einzelnen Flottenbewegungen
					$this_galaxy = 0;
					for ($i = 0; $i < sizeof($taktik); $i++) { // 5
						if ($taktik[$i][0] == "Sektor-Kommandant") continue;
						if ( ereg ( "([^:]*):([^-]*)-(.*)", $taktik[$i][0], $temp) ) { // 6
							$local_galaxy = $temp[1];
							$local_planet = $temp[2];
							$local_name = $temp[3];
							if ($this_galaxy == 0) $this_galaxy = $local_galaxy;
							if ( $taktik[$i][1] != "" ) { // --> Angriff // 7
								$flotten = explode(chr(13).chr(10), $taktik[$i][1]);
								$etas = explode(chr(13).chr(10), $taktik[$i][2]);
								for ($ii = 0; $ii < sizeof($etas); $ii++) { // 8
									if (strpos($etas[$ii], ":")>0) { // 9
										if (ereg("00:00", $etas[$ii])) { // 10
											$etas[$ii] = 0;
										} else { // 10
											$etas[$ii] = (int)((substr($etas[$ii],0,2)*60 + substr($etas[$ii],3,2))/$Ticks['lange'])+1;
										} // 10
									} elseif (strpos($etas[$ii], "s")>0) { // 9
										$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "s"))*60/$Ticks['lange'])+1;
									} elseif (strpos($etas[$ii], "m")>0) { // 9
										$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "m"))/$Ticks['lange'])+1;
									} // 9
								} // 8
								for ($ii = 0; $ii < sizeof($flotten); $ii++) { // 8
									$modus = 1;
									if ( ereg ( "Rückflug-", $flotten[$ii]) ) { // 9
										$flotten[$ii] = str_replace("Rückflug-", "", $flotten[$ii]);
										$modus += 2;
									} // 9
									if ( ereg ( "([^:]*):([^-]*)-(.*)", $flotten[$ii], $ftemp) ) { // 9
										$flotte_galaxy = $ftemp[1];
										$flotte_planet = $ftemp[2];
										$flotte_name = $ftemp[3];
										array_push($flottenbewegungen, array("modus" => $modus, "start_galaxie" => $local_galaxy, "start_planet" => $local_planet, "start_name" => $local_name, "ziel_galaxie" => $flotte_galaxy, "ziel_planet" => $flotte_planet, "ziel_name" => $flotte_name, "eta" => $etas[$ii], "fleet" => 0, "safe" => 0, "mod" => 0));
									} // 9
								} // 8
							} // 7
							if ( $taktik[$i][3] != "" ) { // --> Verteidigung // 7
								$flotten = explode(chr(13).chr(10), $taktik[$i][3]);
								$etas = explode(chr(13).chr(10), $taktik[$i][4]);
								for ($ii = 0; $ii < sizeof($etas); $ii++) { // 8
									if (strpos($etas[$ii], ":")>0) { // 9
										if (ereg("00:00", $etas[$ii])) { // 10
											$etas[$ii] = 0;
										} else { // 10
											$etas[$ii] = (int)((substr($etas[$ii],0,2)*60 + substr($etas[$ii],3,2))/$Ticks['lange'])+1;
										} // 10
									} elseif (strpos($etas[$ii], "s")>0) { // 9
										$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "s"))*60/$Ticks['lange'])+1;
									} elseif (strpos($etas[$ii], "m")>0) { // 9
										$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "m"))/$Ticks['lange'])+1;
									} // 9
								} // 8
								for ($ii = 0; $ii < sizeof($flotten); $ii++) { // 8
									$modus = 2;
									if ( ereg ( "Rückflug-", $flotten[$ii]) ) { // 9
										$flotten[$ii] = str_replace("Rückflug-", "", $flotten[$ii]);
										$modus += 2;
									} // 9
									if ( ereg ( "([^:]*):([^-]*)-(.*)", $flotten[$ii], $ftemp) ) { // 9
										$flotte_galaxy = $ftemp[1];
										$flotte_planet = $ftemp[2];
										$flotte_name = $ftemp[3];
										array_push($flottenbewegungen, array("modus" => $modus, "start_galaxie" => $local_galaxy, "start_planet" => $local_planet, "start_name" => $local_name, "ziel_galaxie" => $flotte_galaxy, "ziel_planet" => $flotte_planet, "ziel_name" => $flotte_name, "eta" => $etas[$ii], "fleet" => 0, "safe" => 0));
									} // 9
								} // 8
							} // 7
							if ( $taktik[$i][5] != "" ) { // <-- Angriff // 7
								$flotten = explode(chr(13).chr(10), $taktik[$i][5]);
								$etas = explode(chr(13).chr(10), $taktik[$i][6]);
								for ($ii = 0; $ii < sizeof($etas); $ii++) { // 8
									if (strpos($etas[$ii], ":")>0) { // 9
										if (ereg("00:00", $etas[$ii])) { // 10
											$etas[$ii] = 0;
										} else { // 10
											$etas[$ii] = (int)((substr($etas[$ii],0,2)*60 + substr($etas[$ii],3,2))/$Ticks['lange'])+1;
										} // 10
									} elseif (strpos($etas[$ii], "s")>0) { // 9
										$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "s"))*60/$Ticks['lange'])+1;
									} elseif (strpos($etas[$ii], "m")>0) { // 9
										$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "m"))/$Ticks['lange'])+1;
									} // 9
								} // 8
								for ($ii = 0; $ii < sizeof($flotten); $ii++) { // 8
									$modus = 1;
									if ( ereg ( "([^:]*):([^-]*)-(.*)", $flotten[$ii], $ftemp) ) { // 9
										$flotte_galaxy = $ftemp[1];
										$flotte_planet = $ftemp[2];
										$flotte_name = $ftemp[3];
										array_push($flottenbewegungen, array("modus" => $modus, "start_galaxie" => $flotte_galaxy, "start_planet" => $flotte_planet, "start_name" => $flotte_name, "ziel_galaxie" => $local_galaxy, "ziel_planet" => $local_planet, "ziel_name" => $local_name, "eta" => $etas[$ii], "fleet" => 0, "safe" => 0));
									} // 9
								} // 8
							} // 7
							if ( $taktik[$i][7] != "" ) { // <-- Verteidigung // 7
								$flotten = explode(chr(13).chr(10), $taktik[$i][7]);
								$etas = explode(chr(13).chr(10), $taktik[$i][8]);
								for ($ii = 0; $ii < sizeof($etas); $ii++) { // 8
									if (strpos($etas[$ii], ":")>0) { // 9
										if (ereg("00:00", $etas[$ii])) { // 10
											$etas[$ii] = 0;
										} else { // 10
											$etas[$ii] = (int)((substr($etas[$ii],0,2)*60 + substr($etas[$ii],3,2))/$Ticks['lange'])+1;
										} // 10
									} elseif (strpos($etas[$ii], "s")>0) { // 9
										$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "s"))*60/$Ticks['lange'])+1;
									} elseif (strpos($etas[$ii], "m")>0) { // 9
										$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "m"))/$Ticks['lange'])+1;
									} // 9
								} // 8
								for ($ii = 0; $ii < sizeof($flotten); $ii++) { // 8
									$modus = 2;
									if ( ereg ( "([^:]*):([^-]*)-(.*)", $flotten[$ii], $ftemp) ) { // 9
										$flotte_galaxy = $ftemp[1];
										$flotte_planet = $ftemp[2];
										$flotte_name = $ftemp[3];
										if ($flotte_galaxy != $this_galaxy)
											array_push($flottenbewegungen, array("modus" => $modus, "start_galaxie" => $flotte_galaxy, "start_planet" => $flotte_planet, "start_name" => $flotte_name, "ziel_galaxie" => $local_galaxy, "ziel_planet" => $local_planet, "ziel_name" => $local_name, "eta" => $etas[$ii], "fleet" => 0, "safe" => 0, "mod" => 0));
									} // 9
								} // 8
							} // 7
						} // 6
					} // 5
				}; // 4
				$SQL_Query = 'SELECT * FROM `gn4flottenbewegungen` WHERE (angreifer_galaxie='.$this_galaxy.' OR verteidiger_galaxie='.$this_galaxy.') ORDER BY eta;';
				$SQL_Result = tic_mysql_query( $SQL_Query, $SQL_DBConn) or die('<br>mist - n db-error!!!');

				for ($i=0; $i < mysql_num_rows($SQL_Result); $i++){ // 4
					$start_galaxie = mysql_result($SQL_Result, $i, 'angreifer_galaxie');
					$start_planet = mysql_result($SQL_Result, $i, 'angreifer_planet');
					$ziel_galaxie = mysql_result($SQL_Result, $i, 'verteidiger_galaxie');
					$ziel_planet = mysql_result($SQL_Result, $i, 'verteidiger_planet');
					for ($ii = 0; $ii < sizeof($flottenbewegungen); $ii++) { // 5
						if ($flottenbewegungen[$ii]["mod"] == 0 && $flottenbewegungen[$ii]["start_galaxie"] == $start_galaxie && $flottenbewegungen[$ii]["start_planet"] == $start_planet && $flottenbewegungen[$ii]["ziel_galaxie"] == $ziel_galaxie && $flottenbewegungen[$ii]["ziel_planet"] == $ziel_planet) { // 6
//							echo "DB-&Uuml;bernahme: ".$start_galaxie.":".$start_planet." -> ".$ziel_galaxie.":".$ziel_planet."<br>\n";
							$flottenbewegungen[$ii]["mod"] = 1;
							$flottenbewegungen[$ii]["fleet"] = mysql_result($SQL_Result, $i, 'flottennr');
							$flottenbewegungen[$ii]["safe"] = 1 - mysql_result($SQL_Result, $i, 'save');
							break;
						} // 6
					} // 5
				} // 4

				$delcommand = 'DELETE FROM `gn4flottenbewegungen` WHERE (angreifer_galaxie='.$this_galaxy.' or verteidiger_galaxie='.$this_galaxy.');';
				$SQL_Result = tic_mysql_query( $delcommand, $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
				$action = "flottenbewegung";
				for ($i = 0; $i < sizeof($flottenbewegungen); $i++) { // 4
					switch ($flottenbewegungen[$i]["modus"]) { // 5
						case 1:
							echo "Angriff: ";
							break;
						case 2:
							echo "Verteidigung: ";
							if ($flottenbewegungen[$i]["fleet"] == 0) $flottenbewegungen[$i]["fleet"] = 1;
							break;
						case 3:
							echo "Angriff (R&uuml;ckflug): ";
							break;
						case 4:
							echo "Verteidigung (R&uuml;ckflug): ";
							if ($flottenbewegungen[$i]["fleet"] == 0) $flottenbewegungen[$i]["fleet"] = 1;
							break;
					} // 5
					echo $flottenbewegungen[$i]["start_galaxie"].":".$flottenbewegungen[$i]["start_planet"]." ".$flottenbewegungen[$i]["start_name"]." -> ".$flottenbewegungen[$i]["ziel_galaxie"].":".$flottenbewegungen[$i]["ziel_planet"]." ".$flottenbewegungen[$i]["ziel_name"]." ETA: ".$flottenbewegungen[$i]["eta"]." (".date("d.M H:i", ((int)(time() / 900) + $flottenbewegungen[$i]["eta"]) * 900).") / Flotte: ".$flottenbewegungen[$i]["fleet"]." / safe: ".$flottenbewegungen[$i]["safe"]."<br>\n";

					$txt_Angreifer_Galaxie		= $flottenbewegungen[$i]["start_galaxie"];
					$txt_Angreifer_Planet		= $flottenbewegungen[$i]["start_planet"];
					$txt_Angreifer_Name		= $flottenbewegungen[$i]["start_name"];
					$txt_Verteidiger_Galaxie	= $flottenbewegungen[$i]["ziel_galaxie"];
					$txt_Verteidiger_Planet		= $flottenbewegungen[$i]["ziel_planet"];
					$txt_Verteidiger_Name		= $flottenbewegungen[$i]["ziel_name"];
					$txt_not_safe			= 1 - $flottenbewegungen[$i]["safe"];
					$lst_ETA			= $flottenbewegungen[$i]["eta"];
					$lst_Flotte			= $flottenbewegungen[$i]["fleet"];
					$modus				= $flottenbewegungen[$i]["modus"];
					include("function.flottenbewegung.php");

				} // 4

			} // 3

			if (ereg("(Galaxiemitglieder[^·]*·  Nachricht an die gesamte Galaxie senden ··»)", $txtScanOrg, $ereg_tmp) ||
					ereg("Galaxiemitglieder.*Nachricht an die gesamte Galaxie senden", urldecode($txtScanOrg), $throwaway)) { // 3
				$text_in = $ereg_tmp[1];
				$html = urldecode($txtScanOrg);
				if (preg_match('/DC.Publisher/', $html)) { // 4: Wir haben HTML-Code bekommen!
					preg_match('/<td class="welcometext">Willkommen.*?([0-9]+):[0-9]+\)\!<\/td>/i', $html, $mm);
					$this_galaxy = $mm[1];

					$html = preg_replace('/[\n\r]+/mi', "", $html);
					$html = preg_replace('/.*Galaxiemitglieder/', '', $html);
					$html = preg_replace('/Nachricht an die gesamte Galaxie senden.*/', '', $html);
					$html = preg_replace('/ class=.?"[a-zA-Z0-9_]*.?"/', '', $html);
					$html = preg_replace('/ align=.?"[a-zA-Z0-9_]*.?"/', '', $html);
					$html = preg_replace('/ nowrap=.?"[a-zA-Z0-9_]*.?"/', '', $html);
					$html = preg_replace('/&nbsp;/', '', $html);
					$html = preg_replace('/<a href="[^"]*">/', '', $html);
					$html = preg_replace('/<\/a>/', '', $html);
					$html = preg_replace('/<span[^>]*>/', '', $html);
					$html = preg_replace('/<\/span>/', '', $html);
					$html = preg_replace('/\s+/', '', $html);


					$i = preg_match_all('/<tr>(.*?)<\/tr>/i', $html, $matches, PREG_PATTERN_ORDER);
					$members_html = array();
					foreach ($matches[1] as $member) { // 5
						array_push($members_html, $member);
					}; // 5

					$regex = '/<td>([0-9]+):([0-9]+)<\/td>'; // koords
					$regex .= '<td>([^<\*]*)\*?<\/td>'; // nick
					$regex .= '<td>([^<]*)<\/td>'; // punkte
					$regex .= '<td>([^<]*)<\/td>'; // fleet
					$regex .= '<td>([^<]*)<\/td>'; // deff
					$regex .= '<td>([^\/]*)\/([^<]*)<\/td>'; // exen
					$regex .= '<td>([^<]*)<\/td>/'; // astros

					$members = array();
					foreach ($members_html as $member) { // 5
						if (preg_match($regex, $member, $match)) {
							$tmp = array();
							$tmp['galaxie'] = $match[1];
							$tmp['planet'] = $match[2];
							$tmp['name'] = $match[3];
							$tmp['punkte'] = str_replace('.', '', $match[4]);
							$tmp['flotte'] = $match[5];
							$tmp['geschuetze'] = $match[6];
							$tmp['mextraktoren'] = $match[7];
							$tmp['kextraktoren'] = $match[8];
							$tmp['asteroiden'] = $match[9];
							array_push($members, $tmp);
						}
					}

					//echo "<!-- "; print_r($members); echo " -->\n";
					addGalaxieSekScans($members);
				} else { // 3

					// Umwandeln der Eingabe auf ein einheitliches Format
					$text_in = ereg_replace( "Galaxiemitglieder(.*)Sektor", "Galaxiemitglieder".chr(13).chr(10)."Sektor", $text_in );
					$text_in = ereg_replace( "Sektor(.*)Kommandant", "Sektor-Kommandant",$text_in );
					$text_in = str_replace( "Extraktoren [Metall/Kristall]", "Extraktoren", $text_in );
					$text_in = str_replace( " / ", "/", $text_in );
					$text_in = str_replace( " *", "", $text_in );
					$text_in = str_replace( "·  Nachricht an die gesamte Galaxie senden ··»", "·__Nachricht_an_die_gesamte_Galaxie_senden_··»", $text_in );
					$text_in = str_replace(chr(32).chr(9), chr(9), $text_in );
					$text_in = str_replace(chr(32).chr(13).chr(10).chr(32).chr(13).chr(10), chr(32).chr(13).chr(10), $text_in );
					$text_in = str_replace(chr(32).chr(13).chr(10), chr(13).chr(10), $text_in );
					$text_in = preg_replace( "|(\:\d+)[ ".chr(9)."]([^".chr(10).chr(13)."])|", "$1-$2", $text_in );
					$text_in = str_replace(chr(32), chr(9), $text_in );
					$text_in = str_replace(chr(9).chr(9), chr(9), $text_in );
					$text_in = str_replace(chr(13).chr(10).chr(9), chr(9), $text_in );
					$text_in = str_replace(chr(9).chr(13).chr(10), chr(13).chr(10), $text_in );
					$text_in = str_replace("-".chr(9), chr(9), $text_in );

					// Zerlegen der Eingabe in die Tabellen-Zellen
					$text_reg = $text_in;
					$galaxie = array();
					$break_it = 0;
					do { // 5
						$break = true;
						if ( ereg ( "([^".chr(9).chr(13).chr(10)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*)".chr(13).chr(10), $text_reg, $line_reg) ) { // 6
							if ( ereg ( "([^".chr(9).chr(10).chr(13)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)", $line_reg[1], $cells) and sizeof($cells) == 7) { // 7
								$temparray = $cells;
								array_shift($temparray);
								array_push($galaxie, $temparray);
							} // 7
							$text_reg = ereg_replace( quotemeta($line_reg[1]).chr(13).chr(10), "", $text_reg);
							$break = false;
						} // 6
						$break_it++;
					} while (($break == false) && ($break_it < 25)); // 5

					// Daten-Array erstellen
					$galaxiemitglieder = array();
					for ($i = 0; $i < sizeof($galaxie); $i++) { // 5
						if ($galaxie[$i][0] == "Sektor-Kommandant") continue;
						if ($galaxie[$i][0] == "Gesamt:") continue;
						if ($galaxie[$i][0] == "Durchschnitt:") continue;
						if ( ereg ( "([^:]*):([^-]*)-(.*)", $galaxie[$i][0], $temp) ) { // 6
							$local_galaxy = $temp[1];
							$local_planet = $temp[2];
							$local_name = $temp[3];
							ereg ( "([^/]*)/(.*)", $galaxie[$i][4], $temp);
							$mex = $temp[1];
							$kex = $temp[2];
							array_push($galaxiemitglieder, array("galaxie" => $local_galaxy, "planet" => $local_planet, "name" => $local_name, "punkte" => str_replace(".", "", $galaxie[$i][1]), "flotte" => $galaxie[$i][2], "geschuetze" => $galaxie[$i][3], "mextraktoren" => $mex, "kextraktoren" => $kex, "asteroiden" => $galaxie[$i][5]));
						} // 6
					} // 5
					addGalaxieSekScans($galaxiemitglieder);
				} // 4
			} // 3

            if ( $scan_typ == 'Flottenzusammensetzung' ) { // 3
                $scan_gen = 99;
                $daten = parseLine( $zeilen[0] );
                $scan_rn = trim( $daten[2] );
                $scan_rg = trim( $daten[3] );                       // Koordinaten: 233:20
                $scan_rg = substr( $scan_rg, 1 );                       // Koordinaten: 233:xx
                $scan_rp = trim( $daten[4] );                      // Koordinaten: 233:20
                $rp_len = strlen( $scan_rp );
                $scan_rp = substr( $scan_rp, 0, $pr_len-1  );                      // Koordinaten: xxx:20

                $scan_type = 2;
                for($n = 2; $n <= 50; $n++) { // 4
                    if (!isset($zeilen[$n])) $zeilen[$n] = '0';
                    $zeilen[$n] = str_replace(',', '', $zeilen[$n]);
                } // 4
                $daten = parseLine( $zeilen[1] );
                if ( $daten[0] == "" ){ // 4
                    $idx = 3;   // ie, mozilla
                } else { // 4
                    $idx = 2;   // opera
                } // 4

                $daten = parseLine( $zeilen[$idx] );            // Jäger
                $scan_sf0j = grabShipData($daten[1]);
                $scan_sf1j = grabShipData($daten[2]);
                $scan_sf2j = grabShipData($daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // bomber
                $scan_sf0b = grabShipData($daten[1]);
                $scan_sf1b = grabShipData($daten[2]);
                $scan_sf2b = grabShipData($daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // fregs
                $scan_sf0f = grabShipData($daten[1]);
                $scan_sf1f = grabShipData($daten[2]);
                $scan_sf2f = grabShipData($daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // zerries
                $scan_sf0z = grabShipData($daten[1]);
                $scan_sf1z = grabShipData($daten[2]);
                $scan_sf2z = grabShipData($daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx]);            // kreuzer
                $scan_sf0kr = grabShipData($daten[1]);
                $scan_sf1kr = grabShipData($daten[2]);
                $scan_sf2kr = grabShipData($daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // schlachter
                $scan_sf0sa = grabShipData($daten[1]);
                $scan_sf1sa = grabShipData($daten[2]);
                $scan_sf2sa = grabShipData($daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // träger
                $scan_sf0t = grabShipData($daten[1]);
                $scan_sf1t = grabShipData($daten[2]);
                $scan_sf2t = grabShipData($daten[3]);

                $sf0ko = $scan_sf0ko = 0;
                $sf1ko = $scan_sf1ko = 0;
                $sf2ko = $scan_sf2ko = 0;

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // Kaper
                $scan_sf0ka = grabShipData($daten[1]);
                $scan_sf1ka = grabShipData($daten[2]);
                $scan_sf2ka = grabShipData($daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // schutzies
                $scan_sf0su = grabShipData($daten[1]);
                $scan_sf1su = grabShipData($daten[2]);
                $scan_sf2su = grabShipData($daten[3]);


                $scan_status0 = 4;
                $scan_status1 = 4;
                $scan_status2 = 4;
                // insert mili  ............................................
                $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" AND rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
                $insert_names = 'sf0j, sf0b, sf0f, sf0z, sf0kr, sf0sa, sf0t, sf0ko, sf0ka, sf0su';
                $insert_names = $insert_names.', sf1j, sf1b, sf1f, sf1z, sf1kr, sf1sa, sf1t, sf1ko, sf1ka, sf1su, status1';
                $insert_names = $insert_names.', sf2j, sf2b, sf2f, sf2z, sf2kr, sf2sa, sf2t, sf2ko, sf2ka, sf2su, status2';
                $insert_values = '"'.$scan_sf0j.'", "'.$scan_sf0b.'", "'.$scan_sf0f.'", "'.$scan_sf0z.'", "'.$scan_sf0kr.'", "'.$scan_sf0sa.'", "'.$scan_sf0t.'", "'.$scan_sf0ko.'", "'.$scan_sf0ka.'", "'.$scan_sf0su.'"';
                $insert_values = $insert_values.', "'.$scan_sf1j.'", "'.$scan_sf1b.'", "'.$scan_sf1f.'", "'.$scan_sf1z.'", "'.$scan_sf1kr.'", "'.$scan_sf1sa.'", "'.$scan_sf1t.'", "'.$scan_sf1ko.'", "'.$scan_sf1ka.'", "'.$scan_sf1su.'", "'.$scan_status1.'"';
                $insert_values = $insert_values.', "'.$scan_sf2j.'", "'.$scan_sf2b.'", "'.$scan_sf2f.'", "'.$scan_sf2z.'", "'.$scan_sf2kr.'", "'.$scan_sf2sa.'", "'.$scan_sf2t.'", "'.$scan_sf2ko.'", "'.$scan_sf2ka.'", "'.$scan_sf2su.'", "'.$scan_status2.'"';
                addgnuser($scan_rg, $scan_rp, $scan_rn);
                $SQL_Result = tic_mysql_query(
                    'INSERT INTO `gn4scans` (
                            type,
                            zeit,
                            g,
                            p,
                            rg,
                            rp,
                            gen,
                            '.$insert_names.'
                        ) VALUES (
                            "'.$scan_type.'",
                            "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'",
                            "'.$Benutzer['galaxie'].'",
                            "'.$Benutzer['planet'].'",
                            "'.$scan_rg.'",
                            "'.$scan_rp.'",
                            "'.$scan_gen.'",
                            '.$insert_values.'
                        );',
                        $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben'
                );

                // insert unit  ............................................
                $scan_type = 1;
                // jäger
                $scan_sfj = $scan_sf0j + $scan_sf1j +$scan_sf2j;

                // bomber
                $scan_sfb = $scan_sf0b + $scan_sf1b + $scan_sf2b;

                // fregs
                $scan_sff = $scan_sf0f + $scan_sf1f + $scan_sf2f;

                // zerries
                $scan_sfz = $scan_sf0z + $scan_sf1z + $scan_sf2z;

                // kreuzer
                $scan_sfkr = $scan_sf0kr + $scan_sf1kr + $scan_sf2kr;

                // schlachter
                $scan_sfsa = $scan_sf0sa + $scan_sf1sa + $scan_sf2sa;

                // träger
                $scan_sft  = $scan_sf0t  + $scan_sf1t + $scan_sf2t;

                // komisches ding
                $sfko = $scan_sfko = 0;

                // Kaper
                $scan_sfka = $scan_sf0ka + $scan_sf1ka + $scan_sf2ka;

                // schutzies
                $scan_sfsu = $scan_sf0su + $scan_sf1su +$scan_sf2su;

                $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" AND rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
                $insert_names = 'sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfko, sfka, sfsu';
                $insert_values = '"'.$scan_sfj.'", "'.$scan_sfb.'", "'.$scan_sff.'", "'.$scan_sfz.'", "'.$scan_sfkr.'", "'.$scan_sfsa.'", "'.$scan_sft.'", "'.$scan_sfko.'", "'.$scan_sfka.'", "'.$scan_sfsu.'"';
                $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
                addgnuser($scan_rg, $scan_rp, $scan_rn);
//print( $insert_values."<br>" );

                // insert gscan ............................................
                $daten = parseLine( $zeilen[1] );
                if ( $daten[0] == "" ){ // 4
                    $daten = parseLine( $zeilen[14] );
                    if ( $daten[0] == 'Verteidigungseinheiten') { // 5
                        $idx2 = 14; // mozilla
                        $idx = 17;
                    } else { // 5
                        $daten = parseLine( $zeilen[17] );
                        if ( $daten[0] == 'Verteidigungseinheiten') { // 6
                            $idx2 = 17; // ie neu
                            $idx = 20;
                        } else { // 6
                            $idx2 = 19; // ie alt
                            $idx = 22;
                        } // 6
                    } // 5
                } else { // 4
                    $idx2 = 12; // opera
                    $idx = 14;
                } // 4
                if ( strstr( $zeilen[$idx2], 'Verteidigungseinheiten') ){ // Verteidigungseinheiten sind vorhanden // 4
                    $scan_type = 3;
                    $daten = strchr( $zeilen[$idx] , ':' );            // Leichtes Orbitalgeschütz 400
                    $daten = substr( $daten, 1 );
                    $scan_glo = trim($daten);
                    $idx++;
                    $daten = strchr( $zeilen[$idx] , ':' );            // Leichtes Raumgeschütz 0
                    $daten = substr( $daten, 1 );
                    $scan_glr = trim($daten);
                    $idx++;
                    $daten = strchr( $zeilen[$idx] , ':' );            // Mittleres Raumgeschütz 0
                    $daten = substr( $daten, 1 );
                    $scan_gmr = trim($daten);
                    $idx++;
                    $daten = strchr( $zeilen[$idx] , ':' );            // Schweres Raumgeschütz 0
                    $daten = substr( $daten, 1 );
                    $scan_gsr = trim($daten);
                    $idx++;
                    $daten = strchr( $zeilen[$idx] , ':' );            // Abfangjäger 1000
                    $daten = substr( $daten, 1 );
                    $scan_ga = trim($daten);
                    $scan_gr = 0;                                 // raumbasis

                    addgnuser($scan_rg, $scan_rp, $scan_rn);
                    $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" AND rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
                    $insert_names = 'glo, glr, gmr, gsr, ga, gr';
                    $insert_values = '"'.$scan_glo.'", "'.$scan_glr.'", "'.$scan_gmr.'", "'.$scan_gsr.'", "'.$scan_ga.'", "'.$scan_gr.'"';
                    $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H:i d.m.Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');

                } // 4
            } else {    // sec, mili, unit, news, gscan // 3
                $scan_gen = trim($daten[count($daten) - 1]);
		$scan_gen = preg_replace('/[^0-9]/', '', $scan_gen);
                $daten = parseLine( $zeilen[1]);            // Name: FedEx
                $scan_rn = trim($daten[1]);
                $daten = parseLine( $zeilen[2]);            // Koordinaten: 233:20
                $scan_koord = trim($daten[1]);
                $scan_koord = explode(':', $scan_koord);
                $scan_rg = trim($scan_koord[0]);
                $scan_rp = trim($scan_koord[1]);
                addgnuser($scan_rg, $scan_rp, $scan_rn);
            } // 3
            if ($scan_typ == 'Sektorscan') { // 3
                $scan_type = 0;
                $daten = parseLine( $zeilen[3]);            // Punktzahl: 3.998.150
                $scan_pts = trim($daten[1]);
				        $daten = parseLine( $zeilen[4]);            // Schiffe: 200
                $scan_s = trim($daten[1]);
                $daten = parseLine( $zeilen[5]);            // Defensiveinheiten: 1150
                $scan_d = trim($daten[1]);
                $daten = parseLine( $zeilen[6]);            // Metall-Extraktoren: 92
                $scan_me = trim($daten[1]);
                $daten = parseLine( $zeilen[7]);            // Kristall-Extraktoren: 0
                $scan_ke = trim($daten[1]);
                $daten = parseLine( $zeilen[8]);            // Asteroiden: 20
                $scan_a = trim($daten[1]);
                addgnuser($scan_rg, $scan_rp, $scan_rn);
                $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" AND rp="'.$scan_rp.'" and type="'.$scan_type.'";', $SQL_DBConn);
                $insert_names = 'pts, s, d, me, ke, a';
                $insert_values = '"'.$scan_pts.'", "'.$scan_s.'", "'.$scan_d.'", "'.$scan_me.'", "'.$scan_ke.'", "'.$scan_a.'"';
                $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H:i d.m.Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.')', $SQL_DBConn)  or $error_code = 7;
            } // 3
            if ($scan_typ == 'Einheitenscan') { // 3
                $scan_type = 1;
                $daten = parseLine( $zeilen[3]);            // Jäger 0
                $scan_sfj = trim($daten[3]);
                $daten = parseLine( $zeilen[4]);            // Bomber 0
                $scan_sfb = trim($daten[3]);
                $daten = parseLine( $zeilen[5]);            // Fregatte 0
                $scan_sff = trim($daten[3]);
                $daten = parseLine( $zeilen[6]);            // Zerstörer 0
                $scan_sfz = trim($daten[3]);
                $daten = parseLine( $zeilen[7]);            // Kreuzer 0
                $scan_sfkr = trim($daten[3]);
                $daten = parseLine( $zeilen[8]);            // Schlachtschiff 0
                $scan_sfsa = trim($daten[3]);
                $daten = parseLine( $zeilen[9]);            // Trägerschiff 0
                $scan_sft = trim($daten[3]);
//                $daten = explode(' ', trim($zeilen[10]));           // Kommandoschiff 0
                $scan_sfko = 0;
                $daten = parseLine( $zeilen[10]);           // Kaperschiff 1000
                $scan_sfka = trim($daten[3]);
                $daten = parseLine( $zeilen[11]);           // Schutzschiff 500
                $scan_sfsu = trim($daten[3]);
                addgnuser($scan_rg, $scan_rp, $scan_rn);
                $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" and rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
                $insert_names = 'sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfko, sfka, sfsu';
                $insert_values = '"'.$scan_sfj.'", "'.$scan_sfb.'", "'.$scan_sff.'", "'.$scan_sfz.'", "'.$scan_sfkr.'", "'.$scan_sfsa.'", "'.$scan_sft.'", "'.$scan_sfko.'", "'.$scan_sfka.'", "'.$scan_sfsu.'"';
                $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H:i d.m.Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
            } // 3

			if ($scan_typ == 'Militärscan') { // 3
				$scan_type = 2;
					for($n = 0; $n <= 50; $n++) { // 4
						if (!isset($zeilen[$n])) $zeilen[$n] = '0';
						$zeilen[$n] = str_replace(',', '', $zeilen[$n]);
					} // 4

					$daten = parseLine( $zeilen[4]);            // Jäger
                    $scan_sf0j = grabShipData($daten[3]);
                    $scan_sf1j = grabShipData($daten[4]);
                    $scan_sf2j = grabShipData($daten[5]);

					$daten = parseLine( $zeilen[5]);            // bomber
                    $scan_sf0b = grabShipData($daten[3]);
                    $scan_sf1b = grabShipData($daten[4]);
                    $scan_sf2b = grabShipData($daten[5]);

					$daten = parseLine( $zeilen[6]);            // fregs
                    $scan_sf0f = grabShipData($daten[3]);
                    $scan_sf1f = grabShipData($daten[4]);
                    $scan_sf2f = grabShipData($daten[5]);

					$daten = parseLine( $zeilen[7]);            // zerries
                    $scan_sf0z = grabShipData($daten[3]);
                    $scan_sf1z = grabShipData($daten[4]);
                    $scan_sf2z = grabShipData($daten[5]);

					$daten = parseLine( $zeilen[8]);            // kreuzer
                    $scan_sf0kr = grabShipData($daten[3]);
                    $scan_sf1kr = grabShipData($daten[4]);
                    $scan_sf2kr = grabShipData($daten[5]);

					$daten = parseLine( $zeilen[9]);            // schlachter
                    $scan_sf0sa = grabShipData($daten[3]);
                    $scan_sf1sa = grabShipData($daten[4]);
                    $scan_sf2sa = grabShipData($daten[5]);

					$daten = parseLine( $zeilen[10]);            // träger
                    $scan_sf0t = grabShipData($daten[3]);
                    $scan_sf1t = grabShipData($daten[4]);
                    $scan_sf2t = grabShipData($daten[5]);

					$sf0ko = $scan_sf0ko = 0;
					$sf1ko = $scan_sf1ko = 0;
					$sf2ko = $scan_sf2ko = 0;

					$daten = parseLine( $zeilen[11]);            // Kaper
                    $scan_sf0ka = grabShipData($daten[3]);
                    $scan_sf1ka = grabShipData($daten[4]);
                    $scan_sf2ka = grabShipData($daten[5]);

					$daten = parseLine( $zeilen[12]);            // schutzies
                    $scan_sf0su = grabShipData($daten[3]);
                    $scan_sf1su = grabShipData($daten[4]);
                    $scan_sf2su = grabShipData($daten[5]);

					$ipos = parseLine( $zeilen[13]);


					$next_word = trim( $ipos[5] );
					$scan_ziel1 = trim( $ipos[6] );
					switch ( $next_word ) { // 4
						case 'Im': // orbit
							$scan_status1 = 0;
							break;
						case 'Rückflug':
							$scan_status1 = 3 ;
							break;
						case 'Angriffsflug':
							$scan_status1 = 1;
							break;
						case 'Verteidigungsflug':
							$scan_status1 = 2;
							break;
					} // 4

					$next_word = trim( $ipos[7] );
					$scan_ziel2 = trim( $ipos[8] );
					switch ( $next_word ) { // 4
						case 'Im': // orbit
							$scan_status2 = 0;
							break;
						case 'Rückflug':
							$scan_status2 = 3;
							break;
						case 'Angriffsflug':
							$scan_status2 = 1;
							break;
						case 'Verteidigungsflug':
							$scan_status2 = 2;
							break;
					} // 4

					if (!isset($scan_status2)) $scan_status2 = 4;

					$SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" and rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
					$insert_names = 'sf0j, sf0b, sf0f, sf0z, sf0kr, sf0sa, sf0t, sf0ko, sf0ka, sf0su';
					$insert_names = $insert_names.', sf1j, sf1b, sf1f, sf1z, sf1kr, sf1sa, sf1t, sf1ko, sf1ka, sf1su, status1, ziel1';
					$insert_names = $insert_names.', sf2j, sf2b, sf2f, sf2z, sf2kr, sf2sa, sf2t, sf2ko, sf2ka, sf2su, status2, ziel2';
					$insert_values = '"'.$scan_sf0j.'", "'.$scan_sf0b.'", "'.$scan_sf0f.'", "'.$scan_sf0z.'", "'.$scan_sf0kr.'", "'.$scan_sf0sa.'", "'.$scan_sf0t.'", "'.$scan_sf0ko.'", "'.$scan_sf0ka.'", "'.$scan_sf0su.'"';
					$insert_values = $insert_values.', "'.$scan_sf1j.'", "'.$scan_sf1b.'", "'.$scan_sf1f.'", "'.$scan_sf1z.'", "'.$scan_sf1kr.'", "'.$scan_sf1sa.'", "'.$scan_sf1t.'", "'.$scan_sf1ko.'", "'.$scan_sf1ka.'", "'.$scan_sf1su.'", "'.$scan_status1.'","'.$scan_ziel1.'"';
					$insert_values = $insert_values.', "'.$scan_sf2j.'", "'.$scan_sf2b.'", "'.$scan_sf2f.'", "'.$scan_sf2z.'", "'.$scan_sf2kr.'", "'.$scan_sf2sa.'", "'.$scan_sf2t.'", "'.$scan_sf2ko.'", "'.$scan_sf2ka.'", "'.$scan_sf2su.'", "'.$scan_status2.'","'.$scan_ziel2.'"';
					$SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H:i d.m.Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
					addgnuser($scan_rg, $scan_rp, $scan_rn);
					if ($scan_ziel1!='Orbit') { // 4
						if ($scan_status1==1 || $scan_status1==2) { // 5
							$SQL_Result = tic_mysql_query('SELECT gala,planet FROM `gn4gnuser` WHERE name="'.$scan_ziel1.'";') or die(mysql_errno()." - ".mysql_error());
							if (mysql_num_rows($SQL_Result)==1) { // 6
								$ziel1_gala = mysql_result($SQL_Result,0,'gala');
								$ziel1_planet=mysql_result($SQL_Result,0,'planet');
								$SQL_Result = tic_mysql_query('SELECT eta FROM `gn4flottenbewegungen` WHERE angreifer_galaxie="'.$scan_rg.'" and angreifer_planet="'.$scan_rp.'" and verteidiger_galaxie="'.$ziel1_gala.'" and verteidiger_planet="'.$ziel1_planet.'";') or die(mysql_errno()." - ".mysql_error());
								if (mysql_num_rows($SQL_Result) == 1) { // 7
									tic_mysql_query('UPDATE `gn4flottenbewegungen` SET flottennr="1" WHERE angreifer_galaxie="'.$scan_rg.'" and angreifer_planet="'.$scan_rp.'" and verteidiger_galaxie="'.$ziel1_gala.'" and verteidiger_planet="'.$ziel1_planet.'";')or die(mysql_errno()." - ".mysql_error());
								} // 7
							} // 6
						} // 5
					} // 4
					if ($scan_ziel2!='Orbit') { // 4
						if ($scan_status2==1 || $scan_status2==2) { // 5
							$SQL_Result = tic_mysql_query('SELECT gala,planet FROM `gn4gnuser` WHERE name="'.$scan_ziel2.'";') or die(mysql_errno()." - ".mysql_error());
							$SQL_Num=mysql_num_rows($SQL_Result);
							if ($SQL_Num==1) { // 5
								$ziel2_gala = mysql_result($SQL_Result,0,'gala');
								$ziel2_planet=mysql_result($SQL_Result,0,'planet');
								$SQL_Result = tic_mysql_query('SELECT eta FROM `gn4flottenbewegungen` WHERE angreifer_galaxie="'.$scan_rg.'" and angreifer_planet="'.$scan_rp.'" and verteidiger_galaxie="'.$ziel2_gala.'" and verteidiger_planet="'.$ziel2_planet.'";') or die(mysql_errno()." - ".mysql_error());
								if (mysql_num_rows($SQL_Result) == 1) { // 6
									tic_mysql_query('UPDATE `gn4flottenbewegungen` SET flottennr="2" WHERE angreifer_galaxie="'.$scan_rg.'" and angreifer_planet="'.$scan_rp.'" and verteidiger_galaxie="'.$ziel2_gala.'" and verteidiger_planet="'.$ziel2_planet.'";')or die(mysql_errno()." - ".mysql_error());
								} // 6
							} // 5
						} // 4
					} // 3

					// insert unit  ............................................
					$scan_type = 1;
					$scan_sfj = $scan_sf0j + $scan_sf1j + $scan_sf2j; // jäger
					$scan_sfb = $scan_sf0b + $scan_sf1b + $scan_sf2b; // bomber
					$scan_sff = $scan_sf0f + $scan_sf1f + $scan_sf2f; // fregs
					$scan_sfz = $scan_sf0z + $scan_sf1z + $scan_sf2z; // zerries
					$scan_sfkr = $scan_sf0kr + $scan_sf1kr + $scan_sf2kr; // kreuzer
					$scan_sfsa = $scan_sf0sa + $scan_sf1sa + $scan_sf2sa; // schlachter
					$scan_sft  = $scan_sf0t  + $scan_sf1t + $scan_sf2t; // träger
					$sfko = $scan_sfko = 0; // komisches ding
					$scan_sfka = $scan_sf0ka + $scan_sf1ka + $scan_sf2ka; // Kaper
					$scan_sfsu = $scan_sf0su + $scan_sf1su +$scan_sf2su; // schutzies

					$SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" AND rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
					$insert_names = 'sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfko, sfka, sfsu';
					$insert_values = '"'.$scan_sfj.'", "'.$scan_sfb.'", "'.$scan_sff.'", "'.$scan_sfz.'", "'.$scan_sfkr.'", "'.$scan_sfsa.'", "'.$scan_sft.'", "'.$scan_sfko.'", "'.$scan_sfka.'", "'.$scan_sfsu.'"';
					$SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H:i d.m.Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
				} // 2
            if ($scan_typ == 'Geschützscan') { // 2
                $scan_type = 3;
                $daten = parseLine( $zeilen[3]);            // Leichtes Orbitalgeschütz 400
                $scan_glo = trim($daten[4]);
                $daten = parseLine( $zeilen[4]);            // Leichtes Raumgeschütz 0
                $scan_glr = trim($daten[4]);
                $daten = parseLine( $zeilen[5]);            // Mittleres Raumgeschütz 0
                $scan_gmr = trim($daten[4]);
                $daten = parseLine( $zeilen[6]);            // Schweres Raumgeschütz 0
                $scan_gsr = trim($daten[4]);
                $daten = parseLine( $zeilen[7]);            // Abfangjäger 1000
                $scan_ga = trim($daten[3]);
                addgnuser($scan_rg, $scan_rp, $scan_rn);
                $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" and rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
                $insert_names = 'glo, glr, gmr, gsr, ga';
                $insert_values = '"'.$scan_glo.'", "'.$scan_glr.'", "'.$scan_gmr.'", "'.$scan_gsr.'", "'.$scan_ga.'"';
                $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H:i d.m.Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
            } // 2
            CountScans($Benutzer['id']);
            $modul = 'scans';
            $txtScanGalaxie = $scan_rg;
            $txtScanPlanet = $scan_rp;
        } else { // 1
		$error_code = 6;
	}; // 1

    // Abrafax:
    if (strlen($tmpGala)>0)
    { // 1
        // Flottenbewegungen wurden gescannt,
        // Anzeige auf Taktikbildschirm umleiten
        $modul = 'taktikbildschirm';
    } // 1
    } // 0

//error_reporting (E_ALL ^ E_NOTICE);

?>
