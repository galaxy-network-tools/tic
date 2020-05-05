<?php
// Informationen ändern
	if ($_POST['action'] == 'infoaendern') {
		if (!isset($_POST['txtSVs']))		$_POST['txtSVs'] = 0;
		if (!isset($_POST['lstScanTyp']))	$_POST['lstScanTyp'] = '';
		if (!isset($_POST['txtPunkte']))	$_POST['txtPunkte'] = 0;
		if (!isset($_POST['txtSchiffe']))	$_POST['txtSchiffe'] = 0;
		if (!isset($_POST['txtDefensiv']))	$_POST['txtDefensiv'] = 0;
		if (!isset($_POST['txtExen_m']))	$_POST['txtExen_m'] = 0;
		if (!isset($_POST['txtExen_k']))	$_POST['txtExen_k'] = 0;
		$_POST['txtSVs']	= TextZuZahl($_POST['txtSVs']);
		$_POST['txtSBs']	= TextZuZahl($_POST['txtSBs']);
		$_POST['txtPunkte']	= TextZuZahl($_POST['txtPunkte']);
		$_POST['txtSchiffe']	= TextZuZahl($_POST['txtSchiffe']);
		$_POST['txtDefensiv']	= TextZuZahl($_POST['txtDefensiv']);
		$_POST['txtExen_m']	= TextZuZahl($_POST['txtExen_m']);
		$_POST['txtExen_k']	= TextZuZahl($_POST['txtExen_k']);
		if ($_POST['lstScanTyp'] != '') {
			$SQL_Result = tic_mysql_query('UPDATE `gn4accounts` SET svs="'.$_POST['txtSVs'].'", sbs="'.$_POST['txtSBs'].'", scantyp="'.$_POST['lstScanTyp'].'" WHERE id="'.$Benutzer['id'].'";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
			$SQL_Result = tic_mysql_query('delete FROM `gn4scans` where rg="'.$Benutzer['galaxie'].'" and rp="'.$Benutzer['planet'].'" and type="0";', $SQL_DBConn);
			$SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, pts, s, d, me, ke) VALUES ("0", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "100", "'.$_POST['txtPunkte'].'", "'.$_POST['txtSchiffe'].'", "'.$_POST['txtDefensiv'].'", "'.$_POST['txtExen_m'].'", "'.$_POST['txtExen_k'].'")');
			if ($error_code == 0) {
				$Benutzer['scantyp']	= $_POST['lstScanTyp'];
				$Benutzer['svs']	= $_POST['txtSVs'];
				$Benutzer['sbs']	= $_POST['txtSBs'];
				$Benutzer['punkte']	= $_POST['txtPunkte'];
				$Benutzer['schiffe']	= $_POST['txtSchiffe'];
				$Benutzer['defensiv']	= $_POST['txtDefensiv'];
				$Benutzer['exen_m']	= $_POST['txtExen_m'];
				$Benutzer['exen_k']	= $_POST['txtExen_k'];
			}
		} else $error_code = 6;
	}

?>
