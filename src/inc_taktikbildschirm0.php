<!-- START: inc_taktikbildschirm0 -->
<center>
	<br>
	<table border="0" cellspacing="2" cellpadding="1" width="100%" class="datatable">
		<colgroup>
			<col width="20">
			<col width="55">
			<col width="*">
			<col width="150">
			<col width="30">
			<col width="150">
			<col width="30">
			<col width="150">
			<col width="30">
			<col width="150">
			<col width="30">
		</colgroup>
		<tr>
			<th class="datatablehead">Nav</th>
			<th class="datatablehead">
				<a href="<?=$scripturl?>&md_orderby=sektor&md_orderdir=<?=($md_orderby=="sektor"?"$md_orderdir_new":"$md_orderdir")?>"><span class="datatablesort<?=($md_orderby=="sektor"?"selected":"")?>">Sektor</span></a>
			</th>
			<th class="datatablehead">
				<a href="<?=$scripturl?>&md_orderby=rang&md_orderdir=<?=($md_orderby=="rang"?"$md_orderdir_new":"$md_orderdir")?>"><span class="datatablesort<?=($md_orderby=="rang"?"selected":"")?>">Rang</span></a> /
				<a href="<?=$scripturl?>&md_orderby=name&md_orderdir=<?=($md_orderby=="name"?"$md_orderdir_new":"$md_orderdir")?>"><span class="datatablesort<?=($md_orderby=="name"?"selected":"")?>">Name</span></a>
			</th>
			<th class="datatablehead" colspan="2">Greift an</th>
			<th class="datatablehead" colspan="2">Verteidigt</th>
			<th class="datatablehead" colspan="2">Wird angegriffen von</th>
			<th class="datatablehead" colspan="2">Wird verteidigt von</th>
		</tr>
<?php
	for ($n = 0; $n < $SQL_Num_user; $n++) {
		$farb_zusatz = '';

		if (tic_mysql_result($SQL_Result_user, $n, 'umod') != '') $farb_zusatz = '_blau';
		if (tic_mysql_result($SQL_Result_user, $n, 'id') == $Benutzer['id']) $farb_zusatz = '_gruen';

		if (tic_mysql_result($SQL_Result_user, $n, 'lastlogin') == "" || tic_mysql_result($SQL_Result_user, $n, 'lastlogin') == "0000-00-00" || tic_mysql_result($SQL_Result_user, $n, 'lastlogin') == 0 || tic_mysql_result($SQL_Result_user, $n, 'lastlogin') < (time() - (3 * 24 * 3600)) ) {
		  	$farb_zusatz2 = 'ffaaaa';
		} else {
		  	$farb_zusatz2 = 'eeeeee';
    }

		$dspsave = '0';
		$display_line=0;
		$dsp="";
		$dsp .= "		<tr>\n";

		$koord_g = tic_mysql_result($SQL_Result_user, $n, 'galaxie');
		$koord_p = tic_mysql_result($SQL_Result_user, $n, 'planet');

// ------------------
		$dsp .= "			<td bgcolor=".$farb_zusatz2."><a href=\"./main.php?modul=vergleich&xgala=".$koord_g."&xplanet=".$koord_p."\"><img src=\"./bilder/default/swords.gif\" width=\"20\" height=\"20\" border=\"0\" alt=\"Flottengegenüberstellung anzeigen\" title=\"Flottengegenüberstellung anzeigen\"></a></td>\n";
		$dsp .= "			<td bgcolor=\"".$htmlstyle['dunkel'.$farb_zusatz]."\" align=\"center\"><font size=\"-1\">".$koord_g.":".$koord_p."</font></td>\n";
		$dsp .= "			<td bgcolor=\"".$htmlstyle['hell'.$farb_zusatz]."\"><font size=\"-1\">\n";
		$dsp .= "				<a href=\"./main.php?modul=anzeigen&id=".tic_mysql_result($SQL_Result_user, $n, 'id')."\"".($Benutzer['help']?" onmouseover=\"return overlib('Klick hier rauf um inc,deff usw. einzutragen ');\" onmouseout=\"return nd();\"":"").">".tic_mysql_result($SQL_Result_user, $n, 'name')."</a>\n";
//		$dsp .= "				<a href=\"./main.php?modul=showgalascans&xgala=".$koord_g."&xplanet=".$koord_p."\">".GetScans2($SQL_DBConn, $koord_g, $koord_p)."</a>\n";
        $dsp .= Get_Scan3($SQL_DBConn,$koord_g,$koord_p,$Benutzer['help'],0);
		$dsp .= "				".$RangName[tic_mysql_result($SQL_Result_user, $n, 'rang')]."\n";
		$dsp .= "			</font></td>\n";
// ------------------

		$f1_liste_namen = "";
		$f1_liste_eta = "";
		$f2_liste_namen = "";
		$f2_liste_eta = "";
		$f3_liste_namen = "";
		$f3_liste_eta = "";
		$f4_liste_namen = "";
		$f4_liste_eta = "";
		$incsunsafe = 0;

		$SQL_Query = "SELECT * FROM gn4flottenbewegungen WHERE (angreifer_galaxie = '".$koord_g."' AND angreifer_planet='".$koord_p."') OR (verteidiger_galaxie='".$koord_g."' AND verteidiger_planet='".$koord_p."') ORDER BY eta;";
		$SQL_Result_fleets = tic_mysql_query($SQL_Query, $SQL_DBConn); // or error("Error while bilding 'taktik' (step 2).", ERROR_SQL, false);
		$SQL_Num_fleets = mysqli_num_rows($SQL_Result_fleets);

		for ($x = 0; $x < $SQL_Num_fleets; $x++) {
			$f_mode = tic_mysql_result($SQL_Result_fleets, $x, 'modus');
			$a_gala = tic_mysql_result($SQL_Result_fleets, $x, 'angreifer_galaxie');
			$a_plan = tic_mysql_result($SQL_Result_fleets, $x, 'angreifer_planet');
			$v_gala = tic_mysql_result($SQL_Result_fleets, $x, 'verteidiger_galaxie');
			$v_plan = tic_mysql_result($SQL_Result_fleets, $x, 'verteidiger_planet');
			$time1 = tic_mysql_result($SQL_Result_fleets, $x, 'ankunft');
			$time2 = tic_mysql_result($SQL_Result_fleets, $x, 'flugzeit_ende');
			$time3 = tic_mysql_result($SQL_Result_fleets, $x, 'ruckflug_ende');

// Angriff ->
			if (( $f_mode == 1 || $f_mode == 3 ) && ( $a_gala == $koord_g ) && ( $a_plan == $koord_p )) {

 //      $output = OnMouseFlotte($v_gala, $v_plan, $Benutzer['punkte']);
 //      $scan="<a href=\"./main.php?modul=showgalascans&xgala=".$v_gala."&xplanet=".$v_plan."\"".($Benutzer['help']?" onmouseover=\"return overlib('".$output."');\" onmouseout=\"return nd();\"":"").">".GetScans2($SQL_DBConn, $v_gala, $v_plan)."</a>";
         $scan = Get_Scan4($SQL_DBConn,$v_gala,$v_plan,$Benutzer['help'],$Benutzer['punkte'],"");
//				$scan = "<a href=\"./main.php?modul=showgalascans&xgala=".$v_gala."&xplanet=".$v_plan."\">".GetScans2($SQL_DBConn, $v_gala, $v_plan)."</a>";

				$flonr = tic_mysql_result($SQL_Result_fleets, $x, 'flottennr');
				$fbid = tic_mysql_result($SQL_Result_fleets, $x, 'id');
				$lnk = "<a href=\"".$scripturl."&action=flonrchange&fbid=".$fbid."&flonr=".$flonr."\"".($Benutzer['help']?" onmouseover=\"return overlib('Hier kannst du die Flott Nr. ändern. ');\" onmouseout=\"return nd();\"":"").">#".($flonr == 0?"?":$flonr)."</a>";
				if ($f_mode == 1) {
					$f1_liste_namen = $f1_liste_namen."				".$v_gala.":".$v_plan." <a href=\"./main.php?modul=flotteaendern&id=".tic_mysql_result($SQL_Result_user, $n, 'id')."&flottenid=".$fbid."\" onmouseover=\"return overlib('Eingetragen von ".tic_mysql_result($SQL_Result_fleets, $x, 'erfasser')." um ".tic_mysql_result($SQL_Result_fleets, $x, 'erfasst_am')." ');\" onmouseout=\"return nd();\">".gnuser($v_gala, $v_plan)."</a> ".$lnk." ".$scan."<br>\n";
					$f1_liste_eta = $f1_liste_eta ."				".getime4display(eta($time1) * $Ticks['lange'] - $tick_abzug)."<br>\n";
				} else {
					$f1_liste_namen = $f1_liste_namen."				Rückflug (".$v_gala.":".$v_plan." ".gnuser($v_gala, $v_plan).")<br>\n";
					$f1_liste_eta = $f1_liste_eta ."				".getime4display(eta($time3) * $Ticks['lange'] - $tick_abzug)."<br>\n";
				}
				if ($_GET['mode'] == 2) $display_line=1;
			}

// Verteidigen ->
			if (( $f_mode == 2 || $f_mode == 4 ) && ( $a_gala == $koord_g ) && ( $a_plan == $koord_p )) {
        $scan = Get_Scan3($SQL_DBConn,$v_gala,$v_plan,$Benutzer['help'],0);
//				$scan = "<a href=\"./main.php?modul=showgalascans&xgala=".$v_gala."&xplanet=".$v_plan."\">".GetScans2($SQL_DBConn, $v_gala, $v_plan)."</a>";
				$flonr = tic_mysql_result($SQL_Result_fleets, $x, 'flottennr');
				$fbid = tic_mysql_result($SQL_Result_fleets, $x, 'id');
				$lnk = "<a href=\"".$scripturl."&action=flonrchange&fbid=".$fbid."&flonr=".$flonr."\"".($Benutzer['help']?" onmouseover=\"return overlib('Hier kannst du die Flott Nr. ändern. ');\" onmouseout=\"return nd();\"":"").">#".($flonr == 0?"?":$flonr)."</a>";
				if ($f_mode == 2) {
					$f2_liste_namen = $f2_liste_namen."				".$v_gala.":".$v_plan." <a href=\"./main.php?modul=flotteaendern&id=".tic_mysql_result($SQL_Result_user, $n, 'id')."&flottenid=".$fbid."\" onmouseover=\"return overlib('Eingetragen von ".tic_mysql_result($SQL_Result_fleets, $x, 'erfasser')." um ".tic_mysql_result($SQL_Result_fleets, $x, 'erfasst_am')." ');\" onmouseout=\"return nd();\">".gnuser($v_gala, $v_plan)."</a> ".$lnk." ".$scan."<br>\n";
					$f2_liste_eta = $f2_liste_eta ."				".getime4display(eta($time1) * $Ticks['lange'] - $tick_abzug)."<br>\n";
				} else {
					$f2_liste_namen = $f2_liste_namen."				Rückflug (".$v_gala.":".$v_plan." ".gnuser($v_gala, $v_plan).")<br>\n";
					$f2_liste_eta = $f2_liste_eta ."				".getime4display(eta($time3) * $Ticks['lange'] - $tick_abzug)."<br>\n";
				}
				if ($_GET['mode'] == 2) $display_line=1;
			}

// Angriff <-
			if (( $f_mode == 1 ) && ( $v_gala == $koord_g ) && ( $v_plan == $koord_p )) {
//				$scan = "<a href=\"./main.php?modul=showgalascans&xgala=".$a_gala."&xplanet=".$a_plan."\">".GetScans2($SQL_DBConn, $a_gala, $a_plan)."</a>";
				$flonr = tic_mysql_result($SQL_Result_fleets, $x, 'flottennr');
				$fbid = tic_mysql_result($SQL_Result_fleets, $x, 'id');
				$incunsave = tic_mysql_result($SQL_Result_fleets, $x, 'save');
        if ($flonr ==0) {
          $scan = Get_Scan3($SQL_DBConn,$a_gala,$a_plan,$Benutzer['help'],$Benutzer['punkte']);
        } else {
          $scan = Get_Scan4($SQL_DBConn,$a_gala,$a_plan,$Benutzer['help'],$Benutzer['punkte'],$flonr);
        }
				$lnk = "<a href=\"".$scripturl."&action=flonrchange&fbid=".$fbid."&flonr=".$flonr."\"".($Benutzer['help']?" onmouseover=\"return overlib('Hier kannst du die Flott Nr. ändern. ');\" onmouseout=\"return nd();\"":"").">#".($flonr == 0?"?":$flonr)."</a>";
				$f3_liste_namen = $f3_liste_namen."				".($incunsave?"<font color=\"#FF0000\">":"").$a_gala.":".$a_plan.($incunsave?"</font>":"");
				$f3_liste_namen = $f3_liste_namen." <a href=\"./main.php?modul=flotteaendern&id=".tic_mysql_result($SQL_Result_user, $n, 'id')."&flottenid=".$fbid."\" onmouseover=\"return overlib('Eingetragen von ".tic_mysql_result($SQL_Result_fleets, $x, 'erfasser')." um ".tic_mysql_result($SQL_Result_fleets, $x, 'erfasst_am')." ');\" onmouseout=\"return nd();\">".gnuser($a_gala, $a_plan)."</a> ";
				$f3_liste_namen = $f3_liste_namen.$lnk." ".$scan."<br>\n";
				$f3_liste_eta = $f3_liste_eta ."				<a href=\"".$scripturl."&action=savechange&fbid=".$fbid."&incsave=".$incunsave."\"".($Benutzer['help']?" onmouseover=\"return overlib('Koords rot = Unsave<br>Koords schwarz = Save<br>Klick hier um zu ändern');\" onmouseout=\"return nd();\"":"").">".getime4display(eta($time1) * $Ticks['lange'] - $tick_abzug)."</a><br>\n";
				$display_line=1;
				$incsunsafe += $incunsave;
			}

// Verteidigen <-
			if (( $f_mode == 2 ) && ( $v_gala == $koord_g ) && ( $v_plan == $koord_p )) {
//				$scan = "<a href=\"./main.php?modul=showgalascans&xgala=".$a_gala."&xplanet=".$a_plan."\">".GetScans2($SQL_DBConn, $a_gala, $a_plan)."</a>";
        $scan = Get_Scan3($SQL_DBConn,$a_gala,$a_plan,$Benutzer['help'],0);
				$flonr = tic_mysql_result($SQL_Result_fleets, $x, 'flottennr');
				$fbid = tic_mysql_result($SQL_Result_fleets, $x, 'id');
				$lnk = "<a href=\"".$scripturl."&action=flonrchange&fbid=".$fbid."&flonr=".$flonr."\"".($Benutzer['help']?" onmouseover=\"return overlib('Hier kannst du die Flott Nr. ändern. ');\" onmouseout=\"return nd();\"":"").">#".($flonr == 0?"?":$flonr)."</a>";
				$f4_liste_namen = $f4_liste_namen."				".$a_gala.":".$a_plan." <a href=\"./main.php?modul=flotteaendern&id=".tic_mysql_result($SQL_Result_user, $n, 'id')."&flottenid=".$fbid."\" onmouseover=\"return overlib('Eingetragen von ".tic_mysql_result($SQL_Result_fleets, $x, 'erfasser')." um ".tic_mysql_result($SQL_Result_fleets, $x, 'erfasst_am')." ');\" onmouseout=\"return nd();\">".gnuser($a_gala, $a_plan)."</a> ";
				$f4_liste_namen = $f4_liste_namen.$lnk." ".$scan."<br>\n";
				$f4_liste_eta = $f4_liste_eta ."				".getime4display(eta($time1) * $Ticks['lange'] - $tick_abzug)."<br>\n";
				$display_line=1;
			}
		}

// ------------------

		mysqli_free_result($SQL_Result_fleets);

		$dsp .= "			<td bgcolor=\"".$htmlstyle['hell'.$farb_zusatz]."\"><font size=\"-2\">\n";
		$dsp .= $f1_liste_namen;
		$dsp .= "			</font></td>\n";
		$dsp .= "			<td bgcolor=\"".$htmlstyle['dunkel'.$farb_zusatz]."\" align=\"right\"><font size=\"-2\">\n";
		$dsp .= $f1_liste_eta;
		$dsp .= "			</font></td>\n";

		$dsp .= "			<td bgcolor=\"".$htmlstyle['hell'.$farb_zusatz]."\"><font size=\"-2\">\n";
		$dsp .= $f2_liste_namen;
		$dsp .= "			</font></td>\n";
		$dsp .= "			<td bgcolor=\"".$htmlstyle['dunkel'.$farb_zusatz]."\" align=\"right\"><font size=\"-2\">\n";
		$dsp .= $f2_liste_eta;
		$dsp .= "			</font></td>\n";

		if ($f3_liste_namen == "") {
			$dsp .= "			<td bgcolor=\"".$htmlstyle['hell'.$farb_zusatz]."\"><font size=\"-2\">\n";
			$dsp .= "				&nbsp;";
			$dsp .= "			</font></td>\n";
			$dsp .= "			<td bgcolor=\"".$htmlstyle['dunkel'.$farb_zusatz]."\" align=\"right\"><font size=\"-2\">\n";
			$dsp .= "				&nbsp;";
			$dsp .= "			</font></td>\n";
		} elseif ($incsunsafe > 0) {
			$dsp .= "			<td bgcolor=\"".$htmlstyle['hell_rot']."\"><font size=\"-2\">\n";
			$dsp .= $f3_liste_namen;
			$dsp .= "			</font></td>\n";
			$dsp .= "			<td bgcolor=\"".$htmlstyle['dunkel_rot']."\" align=\"right\"><font size=\"-2\">\n";
			$dsp .= $f3_liste_eta;
			$dsp .= "			</font></td>\n";
		} else {
			$dsp .= "			<td bgcolor=\"".$htmlstyle['hell_gruen']."\"><font size=\"-2\">\n";
			$dsp .= $f3_liste_namen;
			$dsp .= "			</font></td>\n";
			$dsp .= "			<td bgcolor=\"".$htmlstyle['dunkel_gruen']."\" align=\"right\"><font size=\"-2\">\n";
			$dsp .= $f3_liste_eta;
			$dsp .= "			</font></td>\n";
		}

		$dsp .= "			<td bgcolor=\"".$htmlstyle['hell'.$farb_zusatz]."\"><font size=\"-2\">\n";
		$dsp .= $f4_liste_namen;
		$dsp .= "			</font></td>\n";
		$dsp .= "			<td bgcolor=\"".$htmlstyle['dunkel'.$farb_zusatz]."\" align=\"right\"><font size=\"-2\">\n";
		$dsp .= $f4_liste_eta;
		$dsp .= "			</font></td>\n";

		$dsp .= "		</tr>\n";

// Anzeige
		if ( ($_GET['mode'] != 1 && $_GET['mode'] != 2) || $display_line == 1 ){
			echo $dsp;
		}
	}
?>
	</table>
	<br>
	<font size="-1"><b>(<u>Blau</u> makierte Spieler sind im Urlaubs-Modus)</b></font>
	<br>
	<font size="-1"><b>(<u>NAV-Rot</u> makierte Spieler waren seit 3 Tagen nicht mehr im TIC)</b></font>
  <br>
	<a href="<?=$scripturl?>&action=tcchange&tc=<?=$Benutzer['tcausw']?>&id=<?=$Benutzer['id']?>">Taktikscreen wechseln</a>
</center>
<!-- ENDE: inc_taktikbildschirm0 -->
