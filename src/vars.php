<?php
	$SQL_Result = tic_mysql_query('SELECT * FROM `gn4vars` ORDER BY id;');
	for ($n = 0; $n < mysqli_num_rows($SQL_Result); $n++) {
		$var = tic_mysql_result($SQL_Result, $n, 'name');
		$$var = tic_mysql_result($SQL_Result, $n, 'value');
	}

	$SQL_Result = tic_mysql_query("SELECT * FROM gn4meta WHERE id = '".$Benutzer['ticid']."'") or die(tic_mysql_error(__FILE__,__LINE__));
	$MetaInfo = mysqli_fetch_assoc($SQL_Result);

	// Allianzen
	$SQL_Result = tic_mysql_query("SELECT a.*, b.name as metaname FROM `gn4allianzen` as a LEFT JOIN gn4meta as b ON(a.ticid = b.id) ORDER BY tag;", $SQL_DBConn) or $error_code = 4;
	$SQL_Num = mysqli_num_rows($SQL_Result);
	if ($SQL_Num == 0)
		$error_code = 12;
	else {
		for ($n = 0; $n < $SQL_Num; $n++) {
			$AllianzName[tic_mysql_result($SQL_Result, $n, 'id')] = tic_mysql_result($SQL_Result, $n, 'name');
			$AllianzTag[tic_mysql_result($SQL_Result, $n, 'id')] = tic_mysql_result($SQL_Result, $n, 'tag');
			$AllianzInfo[tic_mysql_result($SQL_Result, $n, 'id')]['name']			= tic_mysql_result($SQL_Result, $n, 'name');
			$AllianzInfo[tic_mysql_result($SQL_Result, $n, 'id')]['tag']			= tic_mysql_result($SQL_Result, $n, 'tag');
			$AllianzInfo[tic_mysql_result($SQL_Result, $n, 'id')]['meta']			= tic_mysql_result($SQL_Result, $n, 'ticid');
			$AllianzInfo[tic_mysql_result($SQL_Result, $n, 'id')]['metaname']			= tic_mysql_result($SQL_Result, $n, 'metaname');
			$AllianzInfo[tic_mysql_result($SQL_Result, $n, 'id')]['info_bnds']			= tic_mysql_result($SQL_Result, $n, 'info_bnds');
			$AllianzInfo[tic_mysql_result($SQL_Result, $n, 'id')]['info_naps']			= tic_mysql_result($SQL_Result, $n, 'info_naps');
			$AllianzInfo[tic_mysql_result($SQL_Result, $n, 'id')]['info_inoffizielle_naps']	= tic_mysql_result($SQL_Result, $n, 'info_inoffizielle_naps');
			$AllianzInfo[tic_mysql_result($SQL_Result, $n, 'id')]['info_kriege']		= tic_mysql_result($SQL_Result, $n, 'info_kriege');
			$AllianzInfo[tic_mysql_result($SQL_Result, $n, 'id')]['code']			= tic_mysql_result($SQL_Result, $n, 'code');
			$AllianzInfo[tic_mysql_result($SQL_Result, $n, 'id')]['blind']			= tic_mysql_result($SQL_Result, $n, 'blind');
		}
	}


	$tick_abzug = intval(date('i') / $Ticks['lange']);
	$tick_abzug = date('i') - $tick_abzug * $Ticks['lange'];
?>
