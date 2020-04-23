<?
	include('./accdata.php');
	include('./functions.php');
	include('./globalvars.php');
	
	// Kein Fehler zu Beginn ^^
	$error_code = 0;
	
	// Verbindung zur Datenbank aufbauen
	$SQL_DBConn = mysql_connect($db_info['host'], $db_info['user'], $db_info['password']  ) or $error_code = 1;
    mysql_select_db($db_info['dbname'], $SQL_DBConn) or $error_code = 2;
    

    // Variablen laden
    include('./vars.php');
	
	// Ticks
	$res = tic_mysql_query('SELECT time, count FROM `gn4cron`;');

	$null_ticks = (int)(time() / ( 60*15));
	$alt_ticks = mysql_result($res,0);

?>
<html>
<body>
<form action="cron-tst.php"><br>
Ticks Jetzt: <input name="null_ticks" value="<?=$null_ticks?>"/><br>
Ticks DB: <input name="alt_ticks" value="<?=$alt_ticks?>"/><br>
<input type="submit" />

</body>
</html>
