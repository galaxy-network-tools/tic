<?php
	function getScanData($scan_galaxie, $scan_planet) {
		global $SQL_DBConn;

		$scan = array(	"scan_sektor" => 0,			"scan_sektor_time" => 0,
				"scan_sektor_prozent" => 0,		"scan_sektor_SVs" => 0,		"scan_sektor_scanner" => "",
				"scan_geschuetze" => 0,			"scan_geschuetze_time" => 0,
				"scan_geschuetze_prozent" => 0,		"scan_geschuetze_SVs" => 0,	"scan_geschuetze_scanner" => "",
				"scan_einheiten" => 0,			"scan_einheiten_time" => 0,
				"scan_einheiten_prozent" => 0,		"scan_einheiten_SVs" => 0,	"scan_einheiten_scanner" => "",
				"scan_militaer" => 0,			"scan_militaer_time" => 0,
				"scan_militaer_prozent" => 0,		"scan_militaer_SVs" => 0,	"scan_militaer_scanner" => "",
				"scan_elokas" => 0,			"scan_elokas_time" => 0,
				"scan_sektor_extraktoren_metall" => 0,	"scan_sektor_extraktoren_kristall" => 0,
				"scan_sektor_asteroiden" => 0,		"scan_sektor_punkte" => 0,
				"scan_sektor_geschuetze" => 0,		"scan_sektor_schiffe" => 0,
				"scan_geschuetze_lorbit" => 0,
				"scan_geschuetze_lraum" => 0,
				"scan_geschuetze_mraum" => 0,
				"scan_geschuetze_sraum" => 0,
				"scan_geschuetze_aj" => 0,
				"scan_einheiten_jaeger" => 0,
				"scan_einheiten_bomber" => 0,
				"scan_einheiten_fregatten" => 0,
				"scan_einheiten_zerstoerer" => 0,
				"scan_einheiten_kreuzer" => 0,
				"scan_einheiten_schlachter" => 0,
				"scan_einheiten_traeger" => 0,
				"scan_einheiten_kleptoren" => 0,
				"scan_einheiten_schildschiffe" => 0,
				"scan_militaer_orbit_jaeger" => 0,
				"scan_militaer_orbit_bomber" => 0,
				"scan_militaer_orbit_fregatten" => 0,
				"scan_militaer_orbit_zerstoerer" => 0,
				"scan_militaer_orbit_kreuzer" => 0,
				"scan_militaer_orbit_schlachter" => 0,
				"scan_militaer_orbit_traeger" => 0,
				"scan_militaer_orbit_kleptoren" => 0,
				"scan_militaer_orbit_schildschiffe" => 0,
				"scan_militaer_flotte1_jaeger" => 0,
				"scan_militaer_flotte1_bomber" => 0,
				"scan_militaer_flotte1_fregatten" => 0,
				"scan_militaer_flotte1_zerstoerer" => 0,
				"scan_militaer_flotte1_kreuzer" => 0,
				"scan_militaer_flotte1_schlachter" => 0,
				"scan_militaer_flotte1_traeger" => 0,
				"scan_militaer_flotte1_kleptoren" => 0,
				"scan_militaer_flotte1_schildschiffe" => 0,
				"scan_militaer_flotte2_jaeger" => 0,
				"scan_militaer_flotte2_bomber" => 0,
				"scan_militaer_flotte2_fregatten" => 0,
				"scan_militaer_flotte2_zerstoerer" => 0,
				"scan_militaer_flotte2_kreuzer" => 0,
				"scan_militaer_flotte2_schlachter" => 0,
				"scan_militaer_flotte2_traeger" => 0,
				"scan_militaer_flotte2_kleptoren" => 0,
				"scan_militaer_flotte2_schildschiffe" => 0,
				);

		$SQL_Query = "SELECT * FROM `gn4scans` WHERE rg='".$scan_galaxie."' AND rp='".$scan_planet."' ORDER BY type;";
		$SQL_Result = tic_mysql_query($SQL_Query, $SQL_DBConn);

		for ($n = 0; $n < mysqli_num_rows($SQL_Result); $n++) {
			$timestamp = 0;
			if (preg_match("(([0-9]+):([0-9]+) ([0-9]+).([0-9]+).([0-9]+))", tic_mysql_result($SQL_Result, $n, 'zeit'), $zeit))
				$timestamp = mktime($zeit[1],$zeit[2],0,$zeit[4],$zeit[3],$zeit[5]);
			switch (tic_mysql_result($SQL_Result, $n, 'type')) {
				case 0: // Sektor
					$scan["scan_sektor"]				= 1;
					$scan["scan_sektor_time"]			= $timestamp;
					$scan["scan_sektor_prozent"]			= tic_mysql_result($SQL_Result, $n, 'gen');
					$scan["scan_sektor_SVs"]			= 0;
					$scan["scan_sektor_scanner"]			= "";

					$scan["scan_sektor_extraktoren_metall"]		= tic_mysql_result($SQL_Result, $n, 'me');
					$scan["scan_sektor_extraktoren_kristall"]	= tic_mysql_result($SQL_Result, $n, 'ke');
					$scan["scan_sektor_asteroiden"]			= tic_mysql_result($SQL_Result, $n, 'a');
					$scan["scan_sektor_punkte"]			= tic_mysql_result($SQL_Result, $n, 'pts');
					$scan["scan_sektor_geschuetze"]			= tic_mysql_result($SQL_Result, $n, 'd');
					$scan["scan_sektor_schiffe"]			= tic_mysql_result($SQL_Result, $n, 's');

					break;
				case 1: // Einheiten
					$scan["scan_einheiten"]			= 1;
					$scan["scan_einheiten_time"]		= $timestamp;
					$scan["scan_einheiten_prozent"]		= tic_mysql_result($SQL_Result, $n, 'gen');
					$scan["scan_einheiten_SVs"]		= 0;
					$scan["scan_einheiten_scanner"]		= "";

					$scan["scan_einheiten_jaeger"]		= tic_mysql_result($SQL_Result, $n, 'sfj');
					$scan["scan_einheiten_bomber"]		= tic_mysql_result($SQL_Result, $n, 'sfb');
					$scan["scan_einheiten_fregatten"]	= tic_mysql_result($SQL_Result, $n, 'sff');
					$scan["scan_einheiten_zerstoerer"]	= tic_mysql_result($SQL_Result, $n, 'sfz');
					$scan["scan_einheiten_kreuzer"]		= tic_mysql_result($SQL_Result, $n, 'sfkr');
					$scan["scan_einheiten_schlachter"]	= tic_mysql_result($SQL_Result, $n, 'sfsa');
					$scan["scan_einheiten_traeger"]		= tic_mysql_result($SQL_Result, $n, 'sft');
					$scan["scan_einheiten_kleptoren"]	= tic_mysql_result($SQL_Result, $n, 'sfka');
					$scan["scan_einheiten_schildschiffe"]	= tic_mysql_result($SQL_Result, $n, 'sfsu');

					break;
				case 2: // Militaer
					$scan["scan_militaer"]				= 1;
					$scan["scan_militaer_time"]			= $timestamp;
					$scan["scan_militaer_prozent"]			= tic_mysql_result($SQL_Result, $n, 'gen');
					$scan["scan_militaer_SVs"]			= 0;
					$scan["scan_militaer_scanner"]			= "";

					$scan["scan_militaer_orbit_jaeger"]		= tic_mysql_result($SQL_Result, $n, 'sf0j');
					$scan["scan_militaer_orbit_bomber"]		= tic_mysql_result($SQL_Result, $n, 'sf0b');
					$scan["scan_militaer_orbit_fregatten"]		= tic_mysql_result($SQL_Result, $n, 'sf0f');
					$scan["scan_militaer_orbit_zerstoerer"]		= tic_mysql_result($SQL_Result, $n, 'sf0z');
					$scan["scan_militaer_orbit_kreuzer"]		= tic_mysql_result($SQL_Result, $n, 'sf0kr');
					$scan["scan_militaer_orbit_schlachter"]		= tic_mysql_result($SQL_Result, $n, 'sf0sa');
					$scan["scan_militaer_orbit_traeger"]		= tic_mysql_result($SQL_Result, $n, 'sf0t');
					$scan["scan_militaer_orbit_kleptoren"]		= tic_mysql_result($SQL_Result, $n, 'sf0ka');
					$scan["scan_militaer_orbit_schildschiffe"]	= tic_mysql_result($SQL_Result, $n, 'sf0su');

					$scan["scan_militaer_flotte1_jaeger"]		= tic_mysql_result($SQL_Result, $n, 'sf1j');
					$scan["scan_militaer_flotte1_bomber"]		= tic_mysql_result($SQL_Result, $n, 'sf1b');
					$scan["scan_militaer_flotte1_fregatten"]	= tic_mysql_result($SQL_Result, $n, 'sf1f');
					$scan["scan_militaer_flotte1_zerstoerer"]	= tic_mysql_result($SQL_Result, $n, 'sf1z');
					$scan["scan_militaer_flotte1_kreuzer"]		= tic_mysql_result($SQL_Result, $n, 'sf1kr');
					$scan["scan_militaer_flotte1_schlachter"]	= tic_mysql_result($SQL_Result, $n, 'sf1sa');
					$scan["scan_militaer_flotte1_traeger"]		= tic_mysql_result($SQL_Result, $n, 'sf1t');
					$scan["scan_militaer_flotte1_kleptoren"]	= tic_mysql_result($SQL_Result, $n, 'sf1ka');
					$scan["scan_militaer_flotte1_schildschiffe"]	= tic_mysql_result($SQL_Result, $n, 'sf1su');

					$scan["scan_militaer_flotte2_jaeger"]		= tic_mysql_result($SQL_Result, $n, 'sf2j');
					$scan["scan_militaer_flotte2_bomber"]		= tic_mysql_result($SQL_Result, $n, 'sf2b');
					$scan["scan_militaer_flotte2_fregatten"]	= tic_mysql_result($SQL_Result, $n, 'sf2f');
					$scan["scan_militaer_flotte2_zerstoerer"]	= tic_mysql_result($SQL_Result, $n, 'sf2z');
					$scan["scan_militaer_flotte2_kreuzer"]		= tic_mysql_result($SQL_Result, $n, 'sf2kr');
					$scan["scan_militaer_flotte2_schlachter"]	= tic_mysql_result($SQL_Result, $n, 'sf2sa');
					$scan["scan_militaer_flotte2_traeger"]		= tic_mysql_result($SQL_Result, $n, 'sf2t');
					$scan["scan_militaer_flotte2_kleptoren"]	= tic_mysql_result($SQL_Result, $n, 'sf2ka');
					$scan["scan_militaer_flotte2_schildschiffe"]	= tic_mysql_result($SQL_Result, $n, 'sf2su');

					break;
				case 3: // Geschuetze
					$scan["scan_geschuetze"]		= 1;
					$scan["scan_geschuetze_time"]		= $timestamp;
					$scan["scan_geschuetze_prozent"]	= tic_mysql_result($SQL_Result, $n, 'gen');
					$scan["scan_geschuetze_SVs"]		= 0;
					$scan["scan_geschuetze_scanner"]	= "";

					$scan["scan_geschuetze_lorbit"]		= tic_mysql_result($SQL_Result, $n, 'glo');
					$scan["scan_geschuetze_lraum"]		= tic_mysql_result($SQL_Result, $n, 'glr');
					$scan["scan_geschuetze_mraum"]		= tic_mysql_result($SQL_Result, $n, 'gmr');
					$scan["scan_geschuetze_sraum"]		= tic_mysql_result($SQL_Result, $n, 'gsr');
					$scan["scan_geschuetze_aj"]		= tic_mysql_result($SQL_Result, $n, 'ga');

					break;
			}
		}

		mysqli_free_result($SQL_Result);
		return $scan;
	}

	function getScanAge($scan_time, $eta_time) {
		global $Ticks;
		$tsec = $Ticks['lange']*60;
		if (time() - $scan_time > 96 * $tsec) {
			return "scanold";
		} elseif ($eta_time - $scan_time > 30 * $tsec) {
			return "scanwarn";
		} else {
			return "scanok";
		}
	}
?>
