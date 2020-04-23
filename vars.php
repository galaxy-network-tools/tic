<?
	$SQL_Result = tic_mysql_query('SELECT * FROM `gn4vars` ORDER BY id;');
	for ($n = 0; $n < mysql_num_rows($SQL_Result); $n++) {
		$var = mysql_result($SQL_Result, $n, 'name');
		$$var = mysql_result($SQL_Result, $n, 'value');
	}

	$SQL_Result = tic_mysql_query("SELECT * FROM gn4meta WHERE id = '".$Benutzer['ticid']."'") or die(tic_mysql_error(__FILE__,__LINE__));
	$MetaInfo = mysql_fetch_assoc($SQL_Result);

	// Allianzen
	$SQL_Result = tic_mysql_query("SELECT a.*, b.name as metaname FROM `gn4allianzen` as a LEFT JOIN gn4meta as b ON(a.ticid = b.id) ORDER BY tag;", $SQL_DBConn) or $error_code = 4;
	$SQL_Num = mysql_num_rows($SQL_Result);
	if ($SQL_Num == 0)
		$error_code = 12;
	else {
		for ($n = 0; $n < $SQL_Num; $n++) {
			$AllianzName[mysql_result($SQL_Result, $n, 'id')] = mysql_result($SQL_Result, $n, 'name');
			$AllianzTag[mysql_result($SQL_Result, $n, 'id')] = mysql_result($SQL_Result, $n, 'tag');
			$AllianzInfo[mysql_result($SQL_Result, $n, 'id')]['name']			= mysql_result($SQL_Result, $n, 'name');
			$AllianzInfo[mysql_result($SQL_Result, $n, 'id')]['tag']			= mysql_result($SQL_Result, $n, 'tag');
			$AllianzInfo[mysql_result($SQL_Result, $n, 'id')]['meta']			= mysql_result($SQL_Result, $n, 'ticid');
			$AllianzInfo[mysql_result($SQL_Result, $n, 'id')]['metaname']			= mysql_result($SQL_Result, $n, 'metaname');
			$AllianzInfo[mysql_result($SQL_Result, $n, 'id')]['info_bnds']			= mysql_result($SQL_Result, $n, 'info_bnds');
			$AllianzInfo[mysql_result($SQL_Result, $n, 'id')]['info_naps']			= mysql_result($SQL_Result, $n, 'info_naps');
			$AllianzInfo[mysql_result($SQL_Result, $n, 'id')]['info_inoffizielle_naps']	= mysql_result($SQL_Result, $n, 'info_inoffizielle_naps');
			$AllianzInfo[mysql_result($SQL_Result, $n, 'id')]['info_kriege']		= mysql_result($SQL_Result, $n, 'info_kriege');
			$AllianzInfo[mysql_result($SQL_Result, $n, 'id')]['code']			= mysql_result($SQL_Result, $n, 'code');
			$AllianzInfo[mysql_result($SQL_Result, $n, 'id')]['blind']			= mysql_result($SQL_Result, $n, 'blind');
		}
	}


	$tick_abzug = intval(date('i') / $Ticks['lange']);
	$tick_abzug = date('i') - $tick_abzug * $Ticks['lange'];
?>
