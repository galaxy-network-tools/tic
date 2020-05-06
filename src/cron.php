<?php
	$tsec = $Ticks['lange']*60;

	// Diverenzberechnung ohne Cron-JOB!!!
	// Dazu gefgt von Mojah 2004
	$res = tic_mysql_query('SELECT time, count FROM `gn4cron` ;', $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());

	$null_ticks = (int)(time() / ($tsec));

	$alt_ticks = 0;
	if(mysqli_affected_rows()==0)
	{
		tic_mysql_query("INSERT INTO gn4cron (time,count) VALUES (".$null_ticks.",0);", $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());
		$alt_ticks = $null_ticks;
	}
	else
	{
		$alt_ticks = tic_mysql_result($res,0);
	}

	$alt_count = mt_rand();

	if ($alt_ticks< $null_ticks)
	{
		tic_mysql_query("UPDATE gn4cron set time=$null_ticks, count=$alt_count ;", $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());
	}

	$div_ticks = $null_ticks - $alt_ticks;

	//echo "div = $null_ticks - $alt_ticks = $div_ticks<br>";
	// Ende Vorbereitung!

    /* Tick Test (keine Ahnung was da gemacht wird, da lasttick_minute nicht gesetzt ist! Mojah
    $minute_jetzt = date('i') + 60;
    $minute_vergangen = bcmod(($minute_jetzt - $lasttick_minute), 60);
    if ($minute_vergangen < 15) $div_ticks=0;
    $minute_naechste = $lasttick_minute + 15;
    if ($minute_naechste == 60) $minute_naechste = 0;
    */

    // cron Berechnungen
if($div_ticks > 0)
{
    $SQL_Result = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen`  ORDER BY id;', $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());
    $SQL_Num = mysqli_num_rows($SQL_Result);

	if ($SQL_Num != 0)
	{

		for ($n = 0; $n < $SQL_Num; $n++) {

		$eintrag_id = tic_mysql_result($SQL_Result, $n, 'id');
		$eintrag_modus = tic_mysql_result($SQL_Result, $n, 'modus');
		$eintrag_angreifer_galaxie = tic_mysql_result($SQL_Result, $n, 'angreifer_galaxie');
		$eintrag_angreifer_planet = tic_mysql_result($SQL_Result, $n, 'angreifer_planet');
		$eintrag_verteidiger_galaxie = tic_mysql_result($SQL_Result, $n, 'verteidiger_galaxie');
		$eintrag_verteidiger_planet = tic_mysql_result($SQL_Result, $n, 'verteidiger_planet');
		$eintrag_eta = tic_mysql_result($SQL_Result, $n, 'eta');
		$eintrag_flugzeit = tic_mysql_result($SQL_Result, $n, 'flugzeit');

		$ankunft = tic_mysql_result($SQL_Result, $n, 'ankunft');
		$flugzeit_ende = tic_mysql_result($SQL_Result, $n, 'flugzeit_ende');
		$ruckflug_ende = tic_mysql_result($SQL_Result, $n, 'ruckflug_ende');

		if ($ruckflug_ende == 0) {
			echo "Alte Daten! Cronjob ausgeführt<br />";
			continue;
		}

		// Debug Infos!
		//echo "$eintrag_angreifer_name => $eintrag_verteidiger_name<br />";
		//echo "ank=".date("d.m.y H:i",$ankunft)."; fz=".date("d.m.y H:i",$flugzeit_ende)."; rfz=".date("d.m.y H:i",$ruckflug_ende)."<br />";


		$akt_time = ((int)(time()/($tsec)))*($tsec);

		// Noch auf Hinflug???
		if($ankunft > $akt_time)
		{
			$eintrag_eta = (int)(($ankunft - $akt_time)/($tsec));
			$SQL_Result2 = tic_mysql_query('UPDATE `gn4flottenbewegungen` SET eta="'.$eintrag_eta.'" WHERE id="'.$eintrag_id.'" ;', $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());
			//echo "Hin: UPDATE `gn4flottenbewegungen` SET eta='$eintrag_eta' WHERE id='$eintrag_id';<br />";
		}
		// Angriff oder Verteidigung ??
		elseif($flugzeit_ende > $akt_time)
		{
			$eintrag_flugzeit = (int)(($flugzeit_ende - $akt_time)/($tsec));
			$SQL_Result2 = tic_mysql_query('UPDATE `gn4flottenbewegungen` SET flugzeit="'.$eintrag_flugzeit.'", eta=0 WHERE id="'.$eintrag_id.'" ;', $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());
			//echo "Ang/Vert: UPDATE `gn4flottenbewegungen` SET flugzeit='$eintrag_flugzeit', eta=0 WHERE id='.$eintrag_id.';'<br />";
		}
		// Schon Zurück??
		elseif($ruckflug_ende <= $akt_time)
		{

			$SQL_Result2 = tic_mysql_query('DELETE FROM `gn4flottenbewegungen` WHERE id='.$eintrag_id, $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());
			//echo "ENDE: DELETE FROM `gn4flottenbewegungen` WHERE id=$eintrag_id<br />";
		}
		// Auf Rückflug ??
 		elseif($ruckflug_ende > $akt_time)
		{
			if($eintrag_modus==1) {
				$eintrag_eta = (int)(($ruckflug_ende - $akt_time)/($tsec));
				$SQL_Result2 = tic_mysql_query('UPDATE `gn4flottenbewegungen` SET modus="3", flugzeit="0", eta="'.$eintrag_eta.'" WHERE id="'.$eintrag_id.'";', $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());
				//echo "Rück: UPDATE `gn4flottenbewegungen` SET modus='0', flugzeit='0', eta='$eintrag_eta' WHERE id='$eintrag_id';'<br />";
			}
			if($eintrag_modus==2) {
				$eintrag_eta = (int)(($ruckflug_ende - $akt_time)/($tsec));
				$SQL_Result2 = tic_mysql_query('UPDATE `gn4flottenbewegungen` SET modus="4", flugzeit="0", eta="'.$eintrag_eta.'" WHERE id="'.$eintrag_id.'";', $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());
				//echo "Rück: UPDATE `gn4flottenbewegungen` SET modus='0', flugzeit='0', eta='$eintrag_eta' WHERE id='$eintrag_id';'<br />";
			}
		}
	}
 }
	//echo 'UPDATE `gn4vars` SET value="'.$minute_naechste.'" WHERE name="lasttick_minute";<br>';
//	echo 'UPDATE `gn4vars` SET value="'.date('H').':'.date('i').':'.date('s').'" WHERE name="lasttick";';
//	$SQL_Result = tic_mysql_query('UPDATE `gn4vars` SET value="'.$minute_naechste.'" WHERE name="lasttick_minute";', $SQL_DBConn) or $error_code = 7;
	$time = $null_ticks * ($tsec);
	$SQL_Result = tic_mysql_query('UPDATE `gn4vars` SET value="'.date("H:i:s", $time).'" WHERE name="lasttick";', $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());

	include "cleanscans.php";
}
?>
