<!-- START: inc_taktikbildschirm1 -->
<center>
	<br>
	<table border="0" cellspacing="2" cellpadding="1" width="100%" class="datatable">
		<colgroup>
			<col width="20">
			<col width="20">
			<col width="20">
			<col width="20">
			<col width="55">
			<col width="*">
			<col width="20%">
			<col width="30">
			<col width="20%">
			<col width="30">
			<col width="20%">
			<col width="30">
		</colgroup>
		<tr>
			<th class="datatablehead" colspan="4">Navigation</th>
			<th class="datatablehead">
				<a href="<?=$scripturl?>&md_orderby=sektor&md_orderdir=<?=($md_orderby=="sektor"?"$md_orderdir_new":"$md_orderdir")?>"><span class="datatablesort<?=($md_orderby=="sektor"?"selected":"")?>">Sektor</span></a>
			</th>
			<th class="datatablehead">
				<a href="<?=$scripturl?>&md_orderby=rang&md_orderdir=<?=($md_orderby=="rang"?"$md_orderdir_new":"$md_orderdir")?>"><span class="datatablesort<?=($md_orderby=="rang"?"selected":"")?>">Rang</span></a> /
				<a href="<?=$scripturl?>&md_orderby=name&md_orderdir=<?=($md_orderby=="name"?"$md_orderdir_new":"$md_orderdir")?>"><span class="datatablesort<?=($md_orderby=="name"?"selected":"")?>">Name</span></a>
			</th>
			<th class="datatablehead" colspan="2">Spielerflotte</th>
			<th class="datatablehead" colspan="2">Wird angegriffen von</th>
			<th class="datatablehead" colspan="2">Wird verteidigt von</th>
		</tr>
<?php
	$help_scan = "Hier klicken um die Scans einzusehen.<br /><br />";
	$help_fleet = "Hier klicken um die Flottennummer zu ändern.<br /><br />";
	$help_safe = "Hier klicken um den Status zu ändern.<br /><br />";

	// Flottenbewegungen
	define ("FLEET_MOVEMENT_UNKNOWN", 0);
	define ("FLEET_MOVEMENT_ATTACK", 1);
	define ("FLEET_MOVEMENT_DEFEND", 2);
	define ("FLEET_MOVEMENT_ATTACK_RETURN", 3);
	define ("FLEET_MOVEMENT_DEFEND_RETURN", 4);

	$tsec = $Ticks['lange']*60;
	$time_now = ((int)(time()/($tsec)))*($tsec);

	while ($SQL_Row_user = mysql_fetch_assoc($SQL_Result_user)) {
		$farb_zusatz = "normal";
		if ($SQL_Row_user['umod'] != '') $farb_zusatz = 'umode';
		if ($SQL_Row_user['id'] == $Benutzer['id']) $farb_zusatz = 'own';

		$display_line=0;
		$dsp="";

		$dsp .= "		<tr>\n";

		$user_g = $SQL_Row_user['galaxie'];
		$user_p = $SQL_Row_user['planet'];
		$user_n = $SQL_Row_user['name'];
		$user_r = $SQL_Row_user['rang'];
		$user_ll = $SQL_Row_user['lastlogin'];

		$login_warn = $user_ll == "" || $user_ll == "0000-00-00" || $user_ll == 0 || $user_ll < (time() - (3 * 24 * 3600));

// ------------------
		$dsp .= "			<td class=\"field".$farb_zusatz."dark\"><a href=\"./main.php?modul=scans&txtScanGalaxie=".$user_g."&txtScanPlanet=".$user_p."\"><img src=\"./bilder/default/scan.gif\" width=\"20\" height=\"20\" border=\"0\" alt=\"Scans erfassen\" title=\"Scans erfassen\"></a></td>\n";
		$dsp .= "			<td class=\"field".$farb_zusatz."dark\"><a href=\"./main.php?modul=showgalascans&xgala=".$user_g."&xplanet=".$user_p."&displaymode=0\"><img src=\"./bilder/default/ship.gif\" width=\"20\" height=\"20\" border=\"0\" alt=\"Schiffe anzeigen\" title=\"Schiffe anzeigen\"></a></td>\n";
		$dsp .= "			<td class=\"field".$farb_zusatz."dark\"><a href=\"./main.php?modul=anzeigen&id=".$SQL_Row_user['id']."\"><img src=\"./bilder/default/move.gif\" width=\"20\" height=\"20\" border=\"0\" alt=\"Schiffsbewegungen ändern\" title=\"Schiffsbewegungen ändern\"></a></td>\n";
		$dsp .= "			<td class=\"field".$farb_zusatz."dark\"><a href=\"./main.php?modul=vergleich&xgala=".$user_g."&xplanet=".$user_p."\"><img src=\"./bilder/default/swords.gif\" width=\"20\" height=\"20\" border=\"0\" alt=\"Flottengegenüberstellung anzeigen\" title=\"Flottengegenüberstellung anzeigen\"></a></td>\n";

		$tooltip_scan = ($Benutzer['help']?$help_scan:"")."<b>Scans von ".$user_g.":".$user_p." ".$user_n."</b><br />";
		$scan = getScanData($user_g, $user_p);
		if ($scan["scan_elokas_time"] > 0) $tooltip_scan.= "EloKas: min. ".$scan["scan_elokas"]." (Stand: ".date("d.M H:i", $scan["scan_elokas_time"]).")<br /><br />";
		if ($scan["scan_sektor"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_sektor_time"], time()).">Sektor vom ".date("d.M H:i", $scan["scan_sektor_time"])." (".$scan["scan_sektor_prozent"]."%)</span><br />";
		if ($scan["scan_geschuetze"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_geschuetze_time"], time()).">Gesch&uuml;tze vom ".date("d.M H:i", $scan["scan_geschuetze_time"])." (".$scan["scan_geschuetze_prozent"]."%)</span><br />";
		if ($scan["scan_einheiten"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_einheiten_time"], time()).">Einheiten vom ".date("d.M H:i", $scan["scan_einheiten_time"])." (".$scan["scan_einheiten_prozent"]."%)</span><br />";
		if ($scan["scan_militaer"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_militaer_time"], time()).">Milit&auml;r vom ".date("d.M H:i", $scan["scan_militaer_time"])." (".$scan["scan_militaer_prozent"]."%)</span><br />";
		if (!($scan["scan_sektor"] || $scan["scan_geschuetze"] || $scan["scan_einheiten"] || $scan["scan_militaer"])) $tooltip_scan.= "<i>keine</i><br />";
		$tooltip_scan .= "<br /><b>Letzter Login von ".$user_g.":".$user_p." ".$user_n."</b><br />".($user_ll?date("d.m.Y H:i", $user_ll):"<i>nie</i>");
		$link_scan = "<a href=\"./main.php?modul=showgalascans&displaymode=0&xgala=".$user_g."&xplanet=".$user_p."\" onmouseover=\"return overlib('".$tooltip_scan."');\" onmouseout=\"return nd();\">";

		$dsp .= "			<td class=\"field".$farb_zusatz."light\" align=\"center\">".$user_g.":".$user_p."</td>\n";
		$dsp .= "			<td class=\"field".$farb_zusatz."dark\" align=\"left\"><img src=\"".$RangImage[$user_r]."\" width=\"20\" height=\"20\" border=\"0\" alt=\"".$RangName[$user_r]."\" title=\"".$RangName[$user_r]."\" align=\"middle\"> ".$link_scan."<span class=\"texttaktik\">[ ".$AllianzTag[$SQL_Row_user['allianz']]." ] ".($login_warn?"<span class=\"loginwarn\">":"").$user_n.($login_warn?"</span>":"")." <img src=\"./bilder/scans/".getScanAge($scan["scan_militaer_time"], time()).".gif\" width=\"15\" height=\"15\" border=\"0\" align=\"middle\"></span></a>".($user_ll > $time_online?" *":"")."</td>\n";
// ----------
		$f1_liste_namen = "";
		$f1_liste_eta = "";
		$f2_liste_namen = "";
		$f2_liste_eta = "";
		$f3_liste_namen = "";
		$f3_liste_eta = "";
		$incs_not_safe = 0;

		$SQL_Query = "SELECT * FROM gn4flottenbewegungen WHERE (angreifer_galaxie = '".$user_g."' AND angreifer_planet='".$user_p."') OR (verteidiger_galaxie='".$user_g."' AND verteidiger_planet='".$user_p."') ORDER BY eta;";
		$SQL_Result_fleets = tic_mysql_query($SQL_Query, $SQL_DBConn); // or error("Error while bilding 'taktik' (step 2).", ERROR_SQL, false);

		while ($SQL_Row_fleets = mysql_fetch_assoc($SQL_Result_fleets)) {
			$f_id		= $SQL_Row_fleets['id'];
			$f_mode		= $SQL_Row_fleets['modus'];
			$f_nummer	= $SQL_Row_fleets['flottennr'];
			$f_eta		= $SQL_Row_fleets['ankunft'];
			$f_eta_ab	= $SQL_Row_fleets['flugzeit_ende'];
			$f_eta_rueck	= $SQL_Row_fleets['ruckflug_ende'];
			$start_g	= $SQL_Row_fleets['angreifer_galaxie'];
			$start_p	= $SQL_Row_fleets['angreifer_planet'];
			$start_n	= gnuser($start_g, $start_p);
			$ziel_g		= $SQL_Row_fleets['verteidiger_galaxie'];
			$ziel_p		= $SQL_Row_fleets['verteidiger_planet'];
			$ziel_n		= gnuser($ziel_g, $ziel_p);

// Spielerflotten ->
			if (( $start_g == $user_g ) && ( $start_p == $user_p )) {
				$tooltip_scan = ($Benutzer['help']?$help_scan:"")."<b>Scans von ".$ziel_g.":".$ziel_p." ".$ziel_n."</b><br />";
				$scan = getScanData($ziel_g, $ziel_p);
				if ($scan["scan_elokas_time"] > 0) $tooltip_scan.= "EloKas: min. ".$scan["scan_elokas"]." (Stand: ".date("d.M H:i", $scan["scan_elokas_time"]).")<br /><br />";
				if ($scan["scan_sektor"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_sektor_time"], $f_eta).">Sektor vom ".date("d.M H:i", $scan["scan_sektor_time"])." (".$scan["scan_sektor_prozent"]."%)</span><br />";
				if ($scan["scan_geschuetze"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_geschuetze_time"], $f_eta).">Gesch&uuml;tze vom ".date("d.M H:i", $scan["scan_geschuetze_time"])." (".$scan["scan_geschuetze_prozent"]."%)</span><br />";
				if ($scan["scan_einheiten"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_einheiten_time"], $f_eta).">Einheiten vom ".date("d.M H:i", $scan["scan_einheiten_time"])." (".$scan["scan_einheiten_prozent"]."%)</span><br />";
				if ($scan["scan_militaer"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_militaer_time"], $f_eta).">Milit&auml;r vom ".date("d.M H:i", $scan["scan_militaer_time"])." (".$scan["scan_militaer_prozent"]."%)</span><br />";
				if (!($scan["scan_sektor"] || $scan["scan_geschuetze"] || $scan["scan_einheiten"] || $scan["scan_militaer"])) $tooltip_scan.= "<i>keine</i>";
				$scan_militaer_time = $scan["scan_militaer_time"];
				$link_scan = "<a href=\"./main.php?modul=showgalascans&displaymode=0&xgala=".$ziel_g."&xplanet=".$ziel_p."\" onmouseover=\"return overlib('".$tooltip_scan."');\" onmouseout=\"return nd();\">";
				$scan = getScanData($start_g, $start_p);
				$tooltip_fleet = ($Benutzer['help']?$help_fleet:"")."<b>".($f_nummer == 0?"unbekannte Flotte":"Flotte ".$f_nummer)." von ".$start_g.":".$start_p." ".$start_n."</b><br />";
				if ($scan["scan_militaer"] && $f_nummer > 0) {
					$tooltip_fleet .= "<span class=".getScanAge($scan["scan_militaer_time"], $f_eta).">";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_jaeger"]		?$scan["scan_militaer_flotte".$f_nummer."_jaeger"]		." J&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_bomber"]		?$scan["scan_militaer_flotte".$f_nummer."_bomber"]		." Bomber<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_fregatten"]		?$scan["scan_militaer_flotte".$f_nummer."_fregatten"]		." Fregatten<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_zerstoerer"]		?$scan["scan_militaer_flotte".$f_nummer."_zerstoerer"]		." Zerst&ouml;rer<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_kreuzer"]		?$scan["scan_militaer_flotte".$f_nummer."_kreuzer"]		." Kreuzer<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_schlachter"]		?$scan["scan_militaer_flotte".$f_nummer."_schlachter"]		." Schlachtschiffe<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_traeger"]		?$scan["scan_militaer_flotte".$f_nummer."_traeger"]		." Tr&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_kleptoren"]		?$scan["scan_militaer_flotte".$f_nummer."_kleptoren"]		." Kleptoren<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_schildschiffe"]	?$scan["scan_militaer_flotte".$f_nummer."_schildschiffe"]	." Schildschiffe<br />":"";
					$tooltip_fleet .= "<i>(".$scan["scan_militaer_prozent"]."% Milit&auml;rscan vom ".date("d.M H:i", $scan["scan_militaer_time"]).")</i><br />";
					$tooltip_fleet .= "</span>";
				}
				if ($scan["scan_einheiten"] && (!($scan["scan_militaer"] && $f_nummer > 0) || ((getScanAge($scan["scan_militaer_time"], $f_eta) != "scanok") && (getScanAge($scan["scan_einheiten_time"], $f_eta) == "scanok")))) {
					$tooltip_fleet .= "<br /><b>Einheiten von ".$start_g.":".$start_p." ".$start_n."</b><br />";
					$tooltip_fleet .= "<span class=".getScanAge($scan["scan_einheiten_time"], $f_eta).">";
					$tooltip_fleet .= $scan["scan_einheiten_jaeger"]	?$scan["scan_einheiten_jaeger"]		." J&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_bomber"]	?$scan["scan_einheiten_bomber"]		." Bomber<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_fregatten"]	?$scan["scan_einheiten_fregatten"]	." Fregatten<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_zerstoerer"]	?$scan["scan_einheiten_zerstoerer"]	." Zerst&ouml;rer<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_kreuzer"]	?$scan["scan_einheiten_kreuzer"]	." Kreuzer<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_schlachter"]	?$scan["scan_einheiten_schlachter"]	." Schlachtschiffe<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_traeger"]	?$scan["scan_einheiten_traeger"]	." Tr&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_kleptoren"]	?$scan["scan_einheiten_kleptoren"]	." Kleptoren<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_schildschiffe"]	?$scan["scan_einheiten_schildschiffe"]	." Schildschiffe<br />":"";
					$tooltip_fleet .= "<i>(".$scan["scan_einheiten_prozent"]."% Einheitenscan vom ".date("d.M H:i", $scan["scan_einheiten_time"]).")</i><br />";
					$tooltip_fleet .= "</span>";
				}
				$link_fleet = "<a href=\"".$scripturl."&action=flonrchange&fbid=".$f_id."&flonr=".$f_nummer."\" onmouseover=\"return overlib('".$tooltip_fleet."');\" onmouseout=\"return nd();\">#".($f_nummer == 0?"?":$f_nummer)."</a>";

				$f1_liste_namen = $f1_liste_namen."			".($f_mode == FLEET_MOVEMENT_ATTACK_RETURN || $f_mode == FLEET_MOVEMENT_DEFEND_RETURN?"RF: ":"").$link_scan."<span class=\"".($f_mode == FLEET_MOVEMENT_ATTACK || $f_mode == FLEET_MOVEMENT_ATTACK_RETURN?"fleetatt":"fleetdeff")."\">".$ziel_g.":".$ziel_p." ".trimname($ziel_n)." <img src=\"./bilder/scans/".getScanAge($scan_militaer_time, $f_eta).".gif\" width=\"15\" height=\"15\" border=\"0\" align=\"middle\"></span></a> ".$link_fleet."<br />\n";
				$f1_liste_eta = $f1_liste_eta ."			".($f_mode != FLEET_MOVEMENT_ATTACK_RETURN && $f_mode != FLEET_MOVEMENT_DEFEND_RETURN && $f_eta < $time_now?"-":"").getime4display(eta($f_mode == FLEET_MOVEMENT_ATTACK_RETURN || $f_mode == FLEET_MOVEMENT_DEFEND_RETURN?$f_eta_rueck:($f_eta >= $time_now?$f_eta:$f_eta_ab)) * $Ticks['lange'] - $tick_abzug)."<br />\n";
				if ($mode == 2) $display_line = 1;
			}

// Angriff <-
			if (( $f_mode == FLEET_MOVEMENT_ATTACK ) && ( $ziel_g == $user_g ) && ( $ziel_p == $user_p )) {
				$inc_safe = 1 - $SQL_Row_fleets['save'];
//				$inc_safe_name = ""; //$SQL_Row_fleets['safe_name'];
//				$inc_safe_time = 0; // $SQL_Row_fleets['safe_time'];

				$tooltip_scan = ($Benutzer['help']?$help_scan:"")."<b>Scans von ".$start_g.":".$start_p." ".$start_n."</b><br />";
				$scan = getScanData($start_g, $start_p);
				if ($scan["scan_elokas_time"] > 0) $tooltip_scan.= "EloKas: min. ".$scan["scan_elokas"]." (Stand: ".date("d.M H:i", $scan["scan_elokas_time"]).")<br /><br />";
				if ($scan["scan_sektor"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_sektor_time"], $f_eta).">Sektor vom ".date("d.M H:i", $scan["scan_sektor_time"])." (".$scan["scan_sektor_prozent"]."%)</span><br />";
				if ($scan["scan_geschuetze"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_geschuetze_time"], $f_eta).">Gesch&uuml;tze vom ".date("d.M H:i", $scan["scan_geschuetze_time"])." (".$scan["scan_geschuetze_prozent"]."%)</span><br />";
				if ($scan["scan_einheiten"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_einheiten_time"], $f_eta).">Einheiten vom ".date("d.M H:i", $scan["scan_einheiten_time"])." (".$scan["scan_einheiten_prozent"]."%)</span><br />";
				if ($scan["scan_militaer"])	$tooltip_scan.= "<span class=".getScanage($scan["scan_militaer_time"], $f_eta).">Milit&auml;r vom ".date("d.M H:i", $scan["scan_militaer_time"])." (".$scan["scan_militaer_prozent"]."%)</span><br />";
				if (!($scan["scan_sektor"] || $scan["scan_geschuetze"] || $scan["scan_einheiten"] || $scan["scan_militaer"])) $tooltip_scan.= "<i>keine</i>";
				$link_scan = "<a href=\"./main.php?modul=showgalascans&displaymode=0&xgala=".$start_g."&xplanet=".$start_p."\" onmouseover=\"return overlib('".$tooltip_scan."');\" onmouseout=\"return nd();\">";
				$tooltip_fleet = ($Benutzer['help']?$help_fleet:"")."<b>".($f_nummer == 0?"unbekannte Flotte":"Flotte ".$f_nummer)." von ".$start_g.":".$start_p." ".$start_n."</b><br />";
				if ($scan["scan_militaer"] && $f_nummer > 0) {
					$tooltip_fleet .= "<span class=".getScanAge($scan["scan_militaer_time"], $f_eta).">";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_jaeger"]		?$scan["scan_militaer_flotte".$f_nummer."_jaeger"]		." J&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_bomber"]		?$scan["scan_militaer_flotte".$f_nummer."_bomber"]		." Bomber<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_fregatten"]		?$scan["scan_militaer_flotte".$f_nummer."_fregatten"]		." Fregatten<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_zerstoerer"]		?$scan["scan_militaer_flotte".$f_nummer."_zerstoerer"]		." Zerst&ouml;rer<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_kreuzer"]		?$scan["scan_militaer_flotte".$f_nummer."_kreuzer"]		." Kreuzer<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_schlachter"]		?$scan["scan_militaer_flotte".$f_nummer."_schlachter"]		." Schlachtschiffe<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_traeger"]		?$scan["scan_militaer_flotte".$f_nummer."_traeger"]		." Tr&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_kleptoren"]		?$scan["scan_militaer_flotte".$f_nummer."_kleptoren"]		." Kleptoren<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_schildschiffe"]	?$scan["scan_militaer_flotte".$f_nummer."_schildschiffe"]	." Schildschiffe<br />":"";
					$tooltip_fleet .= "<i>(".$scan["scan_militaer_prozent"]."% Milit&auml;rscan vom ".date("d.M H:i", $scan["scan_militaer_time"]).")</i><br />";
					$tooltip_fleet .= "</span>";
				}
				if ($scan["scan_einheiten"] && (!($scan["scan_militaer"] && $f_nummer > 0) || ((getScanAge($scan["scan_militaer_time"], $f_eta) != "scanok") && (getScanAge($scan["scan_einheiten_time"], $f_eta) == "scanok")))) {
					$tooltip_fleet .= "<br /><b>Einheiten von ".$start_g.":".$start_p." ".$start_n."</b><br />";
					$tooltip_fleet .= "<span class=".getScanAge($scan["scan_einheiten_time"], $f_eta).">";
					$tooltip_fleet .= $scan["scan_einheiten_jaeger"]	?$scan["scan_einheiten_jaeger"]		." J&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_bomber"]	?$scan["scan_einheiten_bomber"]		." Bomber<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_fregatten"]	?$scan["scan_einheiten_fregatten"]	." Fregatten<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_zerstoerer"]	?$scan["scan_einheiten_zerstoerer"]	." Zerst&ouml;rer<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_kreuzer"]	?$scan["scan_einheiten_kreuzer"]	." Kreuzer<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_schlachter"]	?$scan["scan_einheiten_schlachter"]	." Schlachtschiffe<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_traeger"]	?$scan["scan_einheiten_traeger"]	." Tr&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_kleptoren"]	?$scan["scan_einheiten_kleptoren"]	." Kleptoren<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_schildschiffe"]	?$scan["scan_einheiten_schildschiffe"]	." Schildschiffe<br />":"";
					$tooltip_fleet .= "<i>(".$scan["scan_einheiten_prozent"]."% Einheitenscan vom ".date("d.M H:i", $scan["scan_einheiten_time"]).")</i><br />";
					$tooltip_fleet .= "</span>";
				}
				$link_fleet = "<a href=\"".$scripturl."&action=flonrchange&fbid=".$f_id."&flonr=".$f_nummer."\" onmouseover=\"return overlib('".$tooltip_fleet."');\" onmouseout=\"return nd();\">#".($f_nummer == 0?"?":$f_nummer)."</a>";
				$tooltip_safe = ($Benutzer['help']?$help_safe:"")."<b>Status: <span class=".($inc_safe?"textincsafe>sicher":"textincopen>offen")."</span></b>";
				if (($inc_safe_name != "") && ($inc_safe_time != 0)) {
					$tooltip_safe .= "<br />wer: ".$inc_safe_name."<br />wann: ".date("d.M H:i", $inc_safe_time);
				}
				$link_safe = "<a href=\"".$scripturl."&action=savechange&fbid=".$f_id."&&incsave=".(1 - $inc_safe)."\" onmouseover=\"return overlib('".$tooltip_safe."');\" onmouseout=\"return nd();\">";

				$f2_liste_namen = $f2_liste_namen."			".$link_scan."<span class=\"".($inc_safe?"textincsafe":(($f_eta - $time_now <= ($tsec * 12))?"textincovertime":"textincopen"))."\">".$start_g.":".$start_p." ".trimname($start_n)." <img src=\"./bilder/scans/".getScanAge($scan["scan_militaer_time"], $f_eta).".gif\" width=\"15\" height=\"15\" border=\"0\" align=\"middle\"></span></a> ".$link_fleet."<br />\n";
				$f2_liste_eta = $f2_liste_eta ."			".$link_safe."<span class=\"".($inc_safe?"textincsafe":(($f_eta - $time_now <= ($tsec * 12))?"textincovertime":"textincopen"))."\">".($f_eta < $time_now?"-":"").getime4display(eta($f_eta >= $time_now?$f_eta:$f_eta_ab) * $Ticks['lange'] - $tick_abzug)."</span></a><br />\n";
				$display_line = 1;
				$incs += 1;
				$incs_not_safe += (1 - $inc_safe);
			}

// Verteidigen <-
			if (( $f_mode == FLEET_MOVEMENT_DEFEND ) && ( $ziel_g == $user_g ) && ( $ziel_p == $user_p )) {
				$tooltip_scan = ($Benutzer['help']?$help_scan:"")."<b>Scans von ".$start_g.":".$start_p." ".$start_n."</b><br />";
				$scan = getScanData($start_g, $start_p);
				if ($scan["scan_elokas_time"] > 0) $tooltip_scan.= "EloKas: min. ".$scan["scan_elokas"]." (Stand: ".date("d.M H:i", $scan["scan_elokas_time"]).")<br /><br />";
				if ($scan["scan_sektor"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_sektor_time"], $f_eta).">Sektor vom ".date("d.M H:i", $scan["scan_sektor_time"])." (".$scan["scan_sektor_prozent"]."%)</span><br />";
				if ($scan["scan_geschuetze"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_geschuetze_time"], $f_eta).">Gesch&uuml;tze vom ".date("d.M H:i", $scan["scan_geschuetze_time"])." (".$scan["scan_geschuetze_prozent"]."%)</span><br />";
				if ($scan["scan_einheiten"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_einheiten_time"], $f_eta).">Einheiten vom ".date("d.M H:i", $scan["scan_einheiten_time"])." (".$scan["scan_einheiten_prozent"]."%)</span><br />";
				if ($scan["scan_militaer"])	$tooltip_scan.= "<span class=".getScanAge($scan["scan_militaer_time"], $f_eta).">Milit&auml;r vom ".date("d.M H:i", $scan["scan_militaer_time"])." (".$scan["scan_militaer_prozent"]."%)</span><br />";
				if (!($scan["scan_sektor"] || $scan["scan_geschuetze"] || $scan["scan_einheiten"] || $scan["scan_militaer"])) $tooltip_scan.= "<i>keine</i>";
				$link_scan = "<a href=\"./main.php?modul=showgalascans&displaymode=0&xgala=".$start_g."&xplanet=".$start_p."\" onmouseover=\"return overlib('".$tooltip_scan."');\" onmouseout=\"return nd();\">";
				$tooltip_fleet = ($Benutzer['help']?$help_fleet:"")."<b>".($f_nummer == 0?"unbekannte Flotte":"Flotte ".$f_nummer)." von ".$start_g.":".$start_p." ".$start_n."</b><br />";
				if ($scan["scan_militaer"] && $f_nummer > 0) {
					$tooltip_fleet .= "<span class=".getScanAge($scan["scan_militaer_time"], $f_eta).">";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_jaeger"]		?$scan["scan_militaer_flotte".$f_nummer."_jaeger"]		." J&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_bomber"]		?$scan["scan_militaer_flotte".$f_nummer."_bomber"]		." Bomber<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_fregatten"]		?$scan["scan_militaer_flotte".$f_nummer."_fregatten"]		." Fregatten<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_zerstoerer"]		?$scan["scan_militaer_flotte".$f_nummer."_zerstoerer"]		." Zerst&ouml;rer<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_kreuzer"]		?$scan["scan_militaer_flotte".$f_nummer."_kreuzer"]		." Kreuzer<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_schlachter"]		?$scan["scan_militaer_flotte".$f_nummer."_schlachter"]		." Schlachtschiffe<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_traeger"]		?$scan["scan_militaer_flotte".$f_nummer."_traeger"]		." Tr&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_kleptoren"]		?$scan["scan_militaer_flotte".$f_nummer."_kleptoren"]		." Kleptoren<br />":"";
					$tooltip_fleet .= $scan["scan_militaer_flotte".$f_nummer."_schildschiffe"]	?$scan["scan_militaer_flotte".$f_nummer."_schildschiffe"]	." Schildschiffe<br />":"";
					$tooltip_fleet .= "<i>(".$scan["scan_militaer_prozent"]."% Milit&auml;rscan vom ".date("d.M H:i", $scan["scan_militaer_time"]).")</i><br />";
					$tooltip_fleet .= "</span>";
				}
				if ($scan["scan_einheiten"] && (!($scan["scan_militaer"] && $f_nummer > 0) || ((getScanAge($scan["scan_militaer_time"], $f_eta) != "scanok") && (getScanAge($scan["scan_einheiten_time"], $f_eta) == "scanok")))) {
					$tooltip_fleet .= "<br /><b>Einheiten von ".$start_g.":".$start_p." ".$start_n."</b><br />";
					$tooltip_fleet .= "<span class=".getScanAge($scan["scan_einheiten_time"], $f_eta).">";
					$tooltip_fleet .= $scan["scan_einheiten_jaeger"]	?$scan["scan_einheiten_jaeger"]		." J&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_bomber"]	?$scan["scan_einheiten_bomber"]		." Bomber<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_fregatten"]	?$scan["scan_einheiten_fregatten"]	." Fregatten<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_zerstoerer"]	?$scan["scan_einheiten_zerstoerer"]	." Zerst&ouml;rer<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_kreuzer"]	?$scan["scan_einheiten_kreuzer"]	." Kreuzer<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_schlachter"]	?$scan["scan_einheiten_schlachter"]	." Schlachtschiffe<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_traeger"]	?$scan["scan_einheiten_traeger"]	." Tr&auml;ger<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_kleptoren"]	?$scan["scan_einheiten_kleptoren"]	." Kleptoren<br />":"";
					$tooltip_fleet .= $scan["scan_einheiten_schildschiffe"]	?$scan["scan_einheiten_schildschiffe"]	." Schildschiffe<br />":"";
					$tooltip_fleet .= "<i>(".$scan["scan_einheiten_prozent"]."% Einheitenscan vom ".date("d.M H:i", $scan["scan_einheiten_time"]).")</i><br />";
					$tooltip_fleet .= "</span>";
				}
				$link_fleet = "<a href=\"".$scripturl."&action=flonrchange&fbid=".$f_id."&flonr=".$f_nummer."\" onmouseover=\"return overlib('".$tooltip_fleet."');\" onmouseout=\"return nd();\">#".($f_nummer == 0?"?":$f_nummer)."</a>";

				$f3_liste_namen = $f3_liste_namen."			".$link_scan."<span class=\"texttaktik\">".$start_g.":".$start_p." ".trimname($start_n)." <img src=\"./bilder/scans/".getScanAge($scan["scan_militaer_time"], $f_eta).".gif\" width=\"15\" height=\"15\" border=\"0\" align=\"middle\"></span></a> ".$link_fleet."<br />\n";
				$f3_liste_eta = $f3_liste_eta ."			".($f_eta < $time_now?"-":"").getime4display(eta($f_eta >= $time_now?$f_eta:$f_eta_ab) * $Ticks['lange'] - $tick_abzug)."<br />\n";
				$display_line=1;
			}
		}

		mysql_free_result($SQL_Result_fleets);

		$dsp .= "			<td class=\"field".$farb_zusatz."light\" align=\"left\">\n";
		$dsp .= $f1_liste_namen;
		$dsp .= "			</td>\n";
		$dsp .= "			<td class=\"field".$farb_zusatz."dark\" align=\"right\">\n";
		$dsp .= $f1_liste_eta;
		$dsp .= "			</td>\n";

		if ($f2_liste_namen == "") {
			$dsp .= "			<td class=\"field".$farb_zusatz."light\" align=\"left\">\n";
			$dsp .= "			</td>\n";
			$dsp .= "			<td class=\"field".$farb_zusatz."dark\" align=\"right\">\n";
			$dsp .= "			</td>\n";
		} elseif ($incs_not_safe > 0) {
			$dsp .= "			<td class=\"fieldinclight\" align=\"left\">\n";
			$dsp .= $f2_liste_namen;
			$dsp .= "			</td>\n";
			$dsp .= "			<td class=\"fieldincdark\" align=\"right\">\n";
			$dsp .= $f2_liste_eta;
			$dsp .= "			</td>\n";
		} else {
			$dsp .= "			<td class=\"fieldincsafelight\" align=\"left\">\n";
			$dsp .= $f2_liste_namen;
			$dsp .= "			</td>\n";
			$dsp .= "			<td class=\"fieldincsafedark\" align=\"right\">\n";
			$dsp .= $f2_liste_eta;
			$dsp .= "			</td>\n";
		}

		$dsp .= "			<td class=\"field".$farb_zusatz."light\" align=\"left\">\n";
		$dsp .= $f3_liste_namen;
		$dsp .= "			</td>\n";
		$dsp .= "			<td class=\"field".$farb_zusatz."dark\" align=\"right\">\n";
		$dsp .= $f3_liste_eta;
		$dsp .= "			</td>\n";

		$dsp .= "		</tr>\n";
// Anzeige
		if ( ($mode != 1 && $mode != 2) || $display_line == 1 ){
			echo $dsp;
		}
	}
?>
	</table>
	<br>
	<font size="-1"><b>(<u>Blau</u> makierte Spieler sind im Urlaubs-Modus)</b></font>
	<br>
	<a href="<?=$scripturl?>&action=tcchange&tc=<?=$Benutzer['tcausw']?>&id=<?=$Benutzer['id']?>">Taktikscreen wechseln</a>
</center>
<!-- ENDE: inc_taktikbildschirm1 -->
