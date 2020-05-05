<?php
// Passwort ändern
	if ($_POST['action'] == 'passwortaendern') {
		if (!isset($_POST['txtChPasswort'])) $_POST['txtChPasswort'] = '';
		if ($_POST['txtChPasswort'] != '' && $_POST['txtChPasswort'] == $_POST['txtChPasswort_p']) {
			$SQL_Result = tic_mysql_query('SELECT passwort FROM `gn4accounts` WHERE id="'.$Benutzer['id'].'"', $SQL_DBConn) or $error_code = 4;
			if ( md5($_POST['txtChPasswort']) != mysql_result($SQL_Result, 0, 'passwort') ) {
				$SQL_Result = tic_mysql_query('UPDATE `gn4accounts` SET passwort="'.md5($_POST['txtChPasswort']).'", pwdandern="0" WHERE id="'.$Benutzer['id'].'"', $SQL_DBConn) or $error_code = 7;
				$Benutzer['pwdandern'] = 0;
			}
		}
	}
?>
