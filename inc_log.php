<?php
	DEFINE('LOG_PER_PAGE', 15);
	DEFINE('LOG_PAGES_CONTIGUOUS', 9);

	function constructPageIndex($base_url, $start, $max_value, $num_per_page) {
		// Save whether $start was less than 0 or not.
		$start_invalid = $start < 0;
		// Make sure $start is a proper variable - not less than 0.
		if ($start_invalid)
			$start = 0;
		// Not greater than the upper bound.
		elseif ($start >= $max_value)
			$start = (int) $max_value - (((int) $max_value % (int) $num_per_page) == 0 ? $num_per_page : ((int) $max_value % (int) $num_per_page));
		// And it has to be a multiple of $num_per_page!
		else
			$start = (int) $start - ((int) $start % (int) $num_per_page);
		// Somehow $start ended up zero through the subtractions... fix it.
		if ($start < 0)
			$start = 0;
		$base_link = '<a href="'.$base_url.'&amp;start=';
		// If they didn't enter an odd value, pretend they did.
		$PageContiguous = (int) (LOG_PAGES_CONTIGUOUS - (LOG_PAGES_CONTIGUOUS % 2)) / 2;
		// Show the first page. (>1< ... 6 7 [8] 9 10 ... 15)
		if ($start > $num_per_page * $PageContiguous)
			$pageindex = $base_link . '0">1</a> ';
		else
			$pageindex = '';

		// Show the ... after the first page.  (1 >...< 6 7 [8] 9 10 ... 15)
		if ($start > $num_per_page * ($PageContiguous + 1))
			$pageindex .= '<b> ... </b>';

		// Show the pages before the current one. (1 ... >6 7< [8] 9 10 ... 15)
		for ($nCont = $PageContiguous; $nCont >= 1; $nCont--)
			if ($start >= $num_per_page * $nCont) {
				$tmpStart = $start - $num_per_page * $nCont;
				$pageindex.= $base_link . $tmpStart . '">' . ($tmpStart / $num_per_page + 1) . '</a> ';
			}
	
		// Show the current page. (1 ... 6 7 >[8]< 9 10 ... 15)
		if (!$start_invalid)
			$pageindex .= '[<b>' . ($start / $num_per_page + 1) . '</b>] ';
		else
			$pageindex .= $base_link . $start . '">' . ($start / $num_per_page + 1) . '</a> ';
	
		// Show the pages after the current one... (1 ... 6 7 [8] >9 10< ... 15)
		$tmpMaxPages = (int) (($max_value - 1) / $num_per_page) * $num_per_page;
		for ($nCont = 1; $nCont <= $PageContiguous; $nCont++)
			if ($start + $num_per_page * $nCont <= $tmpMaxPages) {
				$tmpStart = $start + $num_per_page * $nCont;
				$pageindex .= $base_link . $tmpStart . '">' . ($tmpStart / $num_per_page + 1) . '</a> ';
			}
	
		// Show the '...' part near the end. (1 ... 6 7 [8] 9 10 >...< 15)
		if ($start + $num_per_page * ($PageContiguous + 1) < $tmpMaxPages)
			$pageindex .= '<b> ... </b>';
	
		// Show the last number in the list. (1 ... 6 7 [8] 9 10 ... >15<)
		if ($start + $num_per_page * $PageContiguous < $tmpMaxPages)
			$pageindex .= $base_link . $tmpMaxPages . '">' . ($tmpMaxPages / $num_per_page + 1) . '</a> ';
		return $pageindex;
	}

	if($Benutzer['rang'] < RANG_TECHNIKER)
	{
		 $error_code = 5;
	}
	else
	{
		$typetostring = array(LOG_SYSTEM => "System", LOG_ERROR => "Scriptfehler", LOG_SETSAFE => "Safe/Unsafe setzen");

		if($Benutzer['rang'] < RANG_STECHNIKER)
			$SQL_where = " ticid = ".$Benutzer['ticid'];
		else
			$SQL_where = " 1";

		if(isset($_GET['type']) && array_key_exists($_GET['type'], $typetostring))
		{
			$style = array("dark", "light");
			
			$start = (isset($_GET['start']) ? $_GET['start'] : 0);
			$SQL_Result = tic_mysql_query("SELECT COUNT(id) FROM gn4log WHERE".$SQL_where." AND type='".$_GET['type']."'") or die(tic_mysql_error(__FILE__,__LINE__));
			$count = mysql_fetch_row($SQL_Result);
			if($count[0] < $start)
				$start = 0;
			
			$SQL_Result = tic_mysql_query("SELECT name, accid, rang, allianz, zeit, aktion, ip FROM gn4log WHERE".$SQL_where." AND type='".$_GET['type']."' ORDER BY id DESC LIMIT ".$start.",".LOG_PER_PAGE) or die(tic_mysql_error(__FILE__,__LINE__));
			
			echo "<div style=\"font-size:9pt;font-weight:bold;\">T.I.C Log - ".$typetostring[$_GET['type']]."</div><table class=\"datatable\" align=\"center\"><tr class=\"datatablehead\"><th>Zeit</th><th>Meta</th><th>Benutzer</th><th>Meldung</th><th>IP</th></tr>";
			$i = 0;
			while($row = mysql_fetch_assoc($SQL_Result))
			{
				echo "<tr class=\"fieldnormal".$style[$i%2]."\">
						<td>".$row['zeit']."</td>
						<td>".(isset($AllianzInfo[$row['allianz']]['metaname']) ? $AllianzInfo[$row['allianz']]['metaname'] : "")."</td><td>".(isset($RangImage[$row['rang']]) ? "<img src=\"".$RangImage[$row['rang']]."\" alt=\"".$RangName[$row['rang']]."\" width=\"20\" height=\"20\" />" : $row['rang'])."<a href=\"?modul=anzeigen&amp;id=".$row['accid']."\">".(isset($AllianzTag[$row['allianz']]) ? "[".$AllianzTag[$row['allianz']]."]" : "").$row['name']."</a></td><td>".$row['aktion']."</td><td>".$row['ip']."</td></tr>";
				$i++;
			}
			if($i == 0)
			{
				echo "<tr class=\"fieldnormallight\"><td colspan=\"4\">Es sind keine Logeinträge fr diesen Typ vorhanden.</td></tr>";
			}
					echo "<tr><td colspan=\"4\">Seite: ".constructPageIndex("?modul=log&amp;type=".$_GET['type'], $start, $count[0], LOG_PER_PAGE)."</td></tr>\n";
			echo "</table>[<a href=\"?modul=log\">Zurück</a>]";
		}
		else
		{
			$SQL_Result = tic_mysql_query("SELECT type, COUNT(type) FROM gn4log WHERE".$SQL_where." GROUP BY type") or die(tic_mysql_error(__FILE__,__LINE__));
			while($row = mysql_fetch_row($SQL_Result))
			{
				$count[$row[0]] = $row[1];
			}
			$i = 0;
			echo "<table cellpadding=\"5\" align=\"center\"><tr><th colspan=\"3\">T.I.C Log</th></tr>";
			foreach($typetostring as $type => $string)
			{
				if($i == 0)
				   echo "<tr>";
				$i++;
				echo "<td><a href=\"?modul=log&amp;type=".$type."\"><img src=\"bilder/icons/folder.gif\" alt=\"\" border=\"0\" /> ".$string."(".(isset($count[$type]) ? $count[$type] : "0").")</a></td>";
				if($i == 3)
				{
					echo "</tr>";
					$i = 0;
				}
			}
			if($i != 0)
				echo "</tr>";
			echo "</table>";
		}
	}
?>

