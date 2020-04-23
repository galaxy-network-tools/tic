<?
	$_scanmaxalter = 72; // Stunden

	//include('./accdata.php');

	// Verbindung zur Datenbank aufbauen
	// $SQL_DBConn = mysql_connect($db_info['host'], $db_info['user'], $db_info['password']);
	// mysql_select_db($db_info['dbname'], $SQL_DBConn);

	$SQL_Result = tic_mysql_query('SELECT galaxie FROM `gn4accounts` GROUP BY galaxie;',__FILE__,__LINE__);

	while ($z=mysql_fetch_row($SQL_Result)) {
		if(!isset($wherenot) || $wherenot == "") $wherenot = "rg=$z[0]";
		else $wherenot.= " OR rg=$z[0]";
	}

	$SQL_Result = tic_mysql_query("SELECT id, zeit FROM `gn4scans` WHERE NOT ($wherenot);", $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());

	$scancounter = 0;
	for ($n = 0; $n < mysql_num_rows($SQL_Result); $n++) {
		$zeit = mysql_result($SQL_Result, $n, 'zeit');

		$tmp = explode(" ",$zeit);
		$tmp1 = explode(".",$tmp[1]);
		$tmp2 = explode(":",$tmp[0]);
		$d = "$tmp1[2]/$tmp1[1]/$tmp1[0] $tmp[0]:00";

		$time = strtotime($d) + $_scanmaxalter * 60 *60;

		if($time < time()) {
			$SQL_Result2 = tic_mysql_query('DELETE FROM `gn4scans` WHERE id="'.mysql_result($SQL_Result, $n, 'id').'";', $SQL_DBConn);
			//echo 'DELETE FROM `gn4scans` WHERE id="'.mysql_result($SQL_Result, $n, 'id').'"<br />';
		}
	}
	$time = $null_ticks * (60*15);
	$SQL_Result = tic_mysql_query('UPDATE `gn4vars` SET value="'.date("H",$time).':'.date("i",$time).' '.date("d",$time).'.'.date("m",$time).'.'.date("Y",$time).'" WHERE name="lastscanclean" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn) OR die('ERROR 1');


?>
