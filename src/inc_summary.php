<!-- START: inc_summary -->
<?php
	$SQL_Query = "SELECT id, tag, ticid FROM `gn4allianzen`;";
	$SQL_Result_Alli = tic_mysql_query($SQL_Query) or die(tic_mysql_error(__FILE__,__LINE__));
	$alli_anzahl=mysql_num_rows($SQL_Result_Alli);
	if ($alli_anzahl<8) {
		$out_allis	= "		<th align=\"right\">&nbsp;Allianz:&nbsp;</th>\n";
		$out_incs	= "		<th align=\"right\">&nbsp;Incomings:&nbsp;</th>\n";
		$out_online	= "		<th align=\"right\">&nbsp;Online:&nbsp;</th>\n";
		$tsec = $Ticks['lange']*60;
		$time_now = ((int)(time()/($tsec)))*($tsec);

		for($b=0; $b<$alli_anzahl; $b++) {
			$alliid = mysql_result($SQL_Result_Alli, $b, 'id');
			$metaid = mysql_result($SQL_Result_Alli, $b, 'ticid');
			$incs_offen = 0;
			$incs_overtime = 0;
			$incs_safe = 0;
	
			$SQL_Query = "SELECT ankunft, save FROM gn4accounts as a LEFT JOIN gn4flottenbewegungen as b ON (a.galaxie = b.verteidiger_galaxie) WHERE modus = 1 AND a.allianz = ".$alliid." GROUP BY b.id;";
			$SQL_Result_alli_inc_fleets = tic_mysql_query($SQL_Query) or die(tic_mysql_error(__FILE__,__LINE__));
	
			while($inc = mysql_fetch_assoc($SQL_Result_alli_inc_fleets)) {
				if ( $inc['save'] != 0 )
					if ($inc['ankunft'] - $time_now > ($tsec * 12))
						$incs_offen++;
					else
						$incs_overtime++;
				else
					$incs_safe++;
			}
	
			mysql_free_result($SQL_Result_alli_inc_fleets);
	
			$SQL_Query = "SELECT count(*) FROM gn4accounts WHERE allianz = ".$alliid.";";
			$SQL_Result_alli_user = tic_mysql_query($SQL_Query) or $error_code = 43;
	
			$SQL_Query = "SELECT galaxie, planet, name, allianz, rang FROM gn4accounts WHERE allianz=".$alliid." AND lastlogin > ".(time() - 300)." ORDER BY galaxie, planet;";
			$SQL_Result_alli_user_online = tic_mysql_query($SQL_Query) or $error_code = 44;
	
			$tt_incs = "&lt;b&gt;Incs bei Allianz ".$AllianzInfo[$alliid]['tag']." :&lt;/b&gt;&lt;br /&gt;";
			if ($incs_offen + $incs_overtime + $incs_safe > 0) {
				if ($incs_offen > 0) $tt_incs .= "&lt;span class=textincopen&gt;".$incs_offen." offene&lt;/span&gt;&lt;br /&gt;";
				if ($incs_overtime > 0) $tt_incs .= "&lt;span class=textincovertime&gt;".$incs_overtime." offene (nicht mehr deffbar)&lt;/span&gt;&lt;br /&gt;";
				if ($incs_safe > 0) $tt_incs .= "&lt;span class=textincsafe&gt;".$incs_safe." sichere&lt;/span&gt;&lt;br /&gt;";
			} else
				$tt_incs .= "&lt;i&gt;keine&lt;/i&gt;&lt;br /&gt;";
	
			$out_online_names = "&lt;b&gt;Online bei Allianz ".$AllianzInfo[$alliid]['tag']." :&lt;/b&gt;&lt;br /&gt;";
			if (mysql_num_rows($SQL_Result_alli_user_online) > 0)
				for ($n = 0; $n < mysql_num_rows($SQL_Result_alli_user_online); $n++) {
					$rang = mysql_result($SQL_Result_alli_user_online, $n, 'rang');
					$out_online_names .= mysql_result($SQL_Result_alli_user_online, $n, 'galaxie').":".mysql_result($SQL_Result_alli_user_online, $n, 'planet')." [".$AllianzTag[mysql_result($SQL_Result_alli_user_online, $n, 'allianz')]."] ".($rang == $Rang_STechniker?"&lt;font color=#ff0000&gt;":($rang == $Rang_Techniker?"&lt;font color=#0000ff&gt;":"")).($rang > $Rang_GC?"&lt;b&gt;":"").mysql_result($SQL_Result_alli_user_online, $n, 'name').($rang > $Rang_GC?"&lt;/b&gt;":"").($rang >= $Rang_Techniker?"&lt;/font&gt;":"")."&lt;br&gt;";
				}
			else
				$out_online_names .= "&lt;i&gt;keiner&lt;/i&gt;&lt;br /&gt;";
	
			$out_allis	.= "		<td align=\"center\" width=\"75\" onmouseover=\"return overlib('&lt;b&gt;".$AllianzInfo[$alliid]['name']."&lt;/b&gt;');\" onmouseout=\"return nd();\">&nbsp;".($incs_offen>0?"<span class=textincopen>":"").$AllianzInfo[$alliid]['tag'].($incs_offen>0?"</span>":"")."&nbsp;</td>\n";
			$out_incs	.= "		<td align=\"center\" onmouseover=\"return overlib('".$tt_incs."');\" onmouseout=\"return nd();\">&nbsp;<a href=\"./main.php?modul=taktikbildschirm&amp;mode=1&amp;metanr=".$metaid."\"><span class=\"textinc".($incs_offen>0?"open":"none")."\">".$incs_offen."</span> / <span class=\"textinc".($incs_overtime>0?"overtime":"none")."\">".$incs_overtime."</span> / <span class=\"textinc".($incs_safe>0?"safe":"none")."\">".$incs_safe."</span></a>&nbsp;</td>\n";
			$out_online	 .= "		<td align=\"center\" onmouseover=\"return overlib('".$out_online_names."');\" onmouseout=\"return nd();\">&nbsp;".mysql_num_rows($SQL_Result_alli_user_online)." / ".mysql_result($SQL_Result_alli_user, 0, 'count(*)')."&nbsp;</td>\n";
	
			mysql_free_result($SQL_Result_alli_user);
			mysql_free_result($SQL_Result_alli_user_online);
		}
		mysql_free_result($SQL_Result_Alli);
	}else{
		$out_allis = "		<th align=\"right\">&nbsp;Meta:&nbsp;</th>\n";
		$out_incs = "		<th align=\"right\">&nbsp;Incomings:&nbsp;</th>\n";
		$out_online = "		<th align=\"right\">&nbsp;Online:&nbsp;</th>\n";
	
		$SQL_Query = "SELECT name as value, id as ticid FROM `gn4meta` ORDER BY name;";
		$SQL_Result_Metas = tic_mysql_query($SQL_Query) or die(tic_mysql_error(__FILE__,__LINE__));
		$manzahl = mysql_num_rows($SQL_Result_Metas);
	
		$tsec = $Ticks['lange']*60;
		$time_now = ((int)(time()/($tsec)))*($tsec);
	
		for($b=0; $b<$manzahl; $b++) {
			$mname = mysql_result($SQL_Result_Metas, $b, 'value');
			$mticid = mysql_result($SQL_Result_Metas, $b, 'ticid');
			$incs_offen = 0;
			$incs_overtime = 0;
			$incs_safe = 0;
	
			$SQL_Query = "SELECT ankunft, save FROM gn4accounts as a LEFT JOIN gn4flottenbewegungen as b ON(a.galaxie = b.verteidiger_galaxie AND a.ticid = b.ticid) WHERE modus = 1 AND a.ticid = ".$mticid." GROUP BY b.id;";
			$SQL_Result_alli_inc_fleets = tic_mysql_query($SQL_Query) or $error_code = 42;
	
			while($inc = mysql_fetch_assoc($SQL_Result_alli_inc_fleets))
			{
				if ( $inc['save'] != 0 )
					if ($inc['ankunft'] - $time_now > ($tsec * 12))
						$incs_offen++;
					else
						$incs_overtime++;
				else
					$incs_safe++;
			}
	
			mysql_free_result($SQL_Result_alli_inc_fleets);
	
			$SQL_Query = "SELECT count(*) FROM gn4accounts WHERE ticid = ".$mticid.";";
			$SQL_Result_alli_user = tic_mysql_query($SQL_Query) or $error_code = 43;
	
			$SQL_Query = "SELECT galaxie, planet, name, allianz, rang FROM gn4accounts WHERE ticid=".$mticid." AND lastlogin > ".(time() - 300)." ORDER BY galaxie, planet;";
			$SQL_Result_alli_user_online = tic_mysql_query($SQL_Query) or $error_code = 44;
	
			$tt_incs = "&lt;b&gt;Incs bei Meta ".$mname." :&lt;/b&gt;&lt;br /&gt;";
			if ($incs_offen + $incs_overtime + $incs_safe > 0) {
				if ($incs_offen > 0) $tt_incs .= "<span class=textincopen>".$incs_offen." offene</span><br />";
				if ($incs_overtime > 0) $tt_incs .= "<span class=textincovertime>".$incs_overtime." offene (nicht mehr deffbar)</span><br />";
				if ($incs_safe > 0) $tt_incs .= "<span class=textincsafe>".$incs_safe." sichere</span><br />";
			} else
				$tt_incs .= "&lt;i&gt;keine&lt;/i&gt;&lt;br /&gt;";
	
			$out_online_names = "&lt;b&gt;Online bei Meta ".$mname." :&lt;/b&gt;&lt;br /&gt;";
			if (mysql_num_rows($SQL_Result_alli_user_online) > 0)
						for ($n = 0; $n < mysql_num_rows($SQL_Result_alli_user_online); $n++) {
					$rang = mysql_result($SQL_Result_alli_user_online, $n, 'rang');
					$out_online_names .= mysql_result($SQL_Result_alli_user_online, $n, 'galaxie').":".mysql_result($SQL_Result_alli_user_online, $n, 'planet')." [".$AllianzTag[mysql_result($SQL_Result_alli_user_online, $n, 'allianz')]."] ".($rang == $Rang_STechniker?"&lt;font color=#ff0000&gt;":($rang == $Rang_Techniker?"&lt;font color=#0000ff&gt;":"")).($rang > $Rang_GC?"&lt;b&gt;":"").mysql_result($SQL_Result_alli_user_online, $n, 'name').($rang > $Rang_GC?"&lt;/b&gt;":"").($rang >= $Rang_Techniker?"&lt;/font&gt;":"")."&lt;br /&gt;";
				}
			else
				$out_online_names .= "<i>keiner</i><br />";
	
			$out_allis	.= "		<td align=\"center\" width=\"75\" onmouseover=\"return overlib('<b>".$mname."</b>');\" onmouseout=\"return nd();\">&nbsp;".($incs_offen>0?"<span class=textincopen>":"").$mname.($incs_offen>0?"</span>":"")."&nbsp;</td>\n";
			$out_incs	.= "		<td align=\"center\" onmouseover=\"return overlib('".$tt_incs."');\" onmouseout=\"return nd();\">&nbsp;<a href=\"./main.php?modul=taktikbildschirm&mode=1&metanr=".$mticid."\"><span class=textinc".($incs_offen>0?"open":"none").">".$incs_offen."</span> / <span class=textinc".($incs_overtime>0?"overtime":"none").">".$incs_overtime."</span> / <span class=textinc".($incs_safe>0?"safe":"none").">".$incs_safe."</span></a>&nbsp;</td>\n";
			$out_online	 .= "		<td align=\"center\" onmouseover=\"return overlib('".$out_online_names."');\" onmouseout=\"return nd();\">&nbsp;".mysql_num_rows($SQL_Result_alli_user_online)." / ".mysql_result($SQL_Result_alli_user, 0, 'count(*)')."&nbsp;</td>\n";
	
			mysql_free_result($SQL_Result_alli_user);
			mysql_free_result($SQL_Result_alli_user_online);
		}
		mysql_free_result($SQL_Result_Metas);
	}
?>
<table id="ticsummary">
	<tr>
<?php
	echo $out_allis;
?>
	</tr>
	<tr>
<?php
	echo $out_incs;
?>
	</tr>
	<tr>
<?php
	echo $out_online;
?>
	</tr>
</table>

