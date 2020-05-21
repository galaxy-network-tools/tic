<?php
/*
	##########################################################
	#                                                        #
	#  T.I.C. | Tactical Information Center                  #
	#                                                        #
	#  Allianzorganisationstool für Galaxy-Network           #
	#  von NataS alias Tobias Sarnowski                      #
	#  von Pomel alias Achim Pomorin                         #
	#  von Abrafax                                           #
	#  vom tic-entwickler.de Team                            #
	#  und mit bytehoppers                                   #
	#                                                        #
	#  2020 upgegraded und upgedated von                     #
	#    ast126                                              #
	#    worp1900                                            #
	#                                                        #
	##########################################################
*/
	// error_reporting(E_ALL); // zu testzwecken einschalten
	ob_start("ob_gzhandler");

	include("sessionhelpers.inc.php");
	$_GET = injsafe($_GET);
	$_POST = injsafe($_POST);
	foreach ($_GET as $key => $val) { $$key = $val; }

	// Session-Registrieren
	session_start();
	if (!isset($_SESSION['is_auth']) || $_SESSION['is_auth']!=1) {
		if ($userid=check_user($_POST['username'], $_POST['userpass'])) {
			$_SESSION['is_auth'] = 1;
			$_SESSION['userid'] = $userid;
		} else {
			$_SESSION['is_auth'] = 0;
            $_SESSION['userid'] = -1;
            header('HTTP/1.1 401 Unauthorized');
            header('Location: ' . '/index.php');
			die("Ihre Anmeldedaten waren nicht korrekt!");
		}
	}



	$mtime = microtime();
	$mtime = explode(" ", $mtime);
	$mtime = $mtime[1] + $mtime[0];
	$start_time = $mtime;

	$version = "1.60";

	include("./accdata.php");
	include("./functions.php");
	include("./globalvars.php");
	//include("./functions.php");

	// Kein Fehler zu Beginn ^^
	$error_code = 0;

	// HTML Style
	$htmlstyle['hell'] = 'eeeeee';
	$htmlstyle['dunkel'] = 'dddddd';
	$htmlstyle['hell_rot'] = 'ffaaaa';
	$htmlstyle['dunkel_rot'] = 'ff8888';
	$htmlstyle['hell_gruen'] = 'aaffaa';
	$htmlstyle['dunkel_gruen'] = '88ff88';
	$htmlstyle['hell_blau'] = 'aaaaff';
	$htmlstyle['dunkel_blau'] = '8888ff';

	$SQL_Result = tic_mysql_query("SELECT * FROM `gn4accounts` WHERE id='".$_SESSION['userid']."'") or die(tic_mysqli_error(__FILE__,__LINE__));
	if (mysqli_num_rows($SQL_Result) == 1)
	{
		// Nameinfos setzen
		$Benutzer['id'] = tic_mysql_result($SQL_Result, 0, 'id');
		$Benutzer['ticid'] = tic_mysql_result($SQL_Result, 0, 'ticid');
		$Benutzer['name'] = tic_mysql_result($SQL_Result, 0, 'name');
		$Benutzer['galaxie'] = tic_mysql_result($SQL_Result, 0, 'galaxie');
		$Benutzer['pwdandern'] = tic_mysql_result($SQL_Result, 0, 'pwdandern');
		$Benutzer['planet'] = tic_mysql_result($SQL_Result, 0, 'planet');
		$Benutzer['rang'] = tic_mysql_result($SQL_Result, 0, 'rang');
		$Benutzer['allianz'] = tic_mysql_result($SQL_Result, 0, 'allianz');
		$Benutzer['scantyp'] = tic_mysql_result($SQL_Result, 0, 'scantyp');
		$Benutzer['zeitformat'] = tic_mysql_result($SQL_Result, 0, 'zeitformat');
		$Benutzer['svs'] = tic_mysql_result($SQL_Result, 0, 'svs');
		$Benutzer['sbs'] = tic_mysql_result($SQL_Result, 0, 'sbs');
		$Benutzer['umod'] = tic_mysql_result($SQL_Result, 0, 'umod');
		$Benutzer['spy'] = tic_mysql_result($SQL_Result, 0, 'spy');
		$Benutzer['help'] = tic_mysql_result($SQL_Result, 0, 'help');
		$Benutzer['tcausw'] = tic_mysql_result($SQL_Result, 0, 'tcausw');

// Erweiterung von Bytehoppers vom 20.07.05 für Attplaner2
		@$Benutzer['attplaner'] = tic_mysql_result($SQL_Result, 0, 'attplaner');
	}
	else
	{
		die("<a href=\"index.php\" target=\"_top\">Neu Einloggen</a>");
	}


	// Variablen laden
	include("./vars.php");
	// Pseudo-Cron
	include("./cron.php");
	//Nachtwache Kontrolle Laden
	include("./NWkontrolle.php");

	// Standardmodul wählen falls nicht angegeben
	if(isset($_POST['modul']) && $_POST['modul'] != "")
		$modul = $_POST['modul'];
	else if(isset($_GET['modul']) && $_GET['modul'] != "")
		$modul = $_GET['modul'];
	else
		$modul = "nachrichten";

        // Get the logged in user
	$SQL_Result2 = tic_mysql_query("SELECT pts, s, d, me, ke FROM `gn4scans` WHERE rg='".$Benutzer['galaxie']."' AND rp='".$Benutzer['planet']."' AND type='0'") or die(tic_mysqli_error(__FILE__,__LINE__));
	if (mysqli_num_rows($SQL_Result2) != 1)
	{
		$Benutzer['punkte'] = 0;
		$Benutzer['schiffe'] = 0;
		$Benutzer['defensiv'] = 0;
		$Benutzer['exen_m'] = 0;
		$Benutzer['exen_k'] = 0;
	}
	else
	{
		$Benutzer['punkte'] = tic_mysql_result($SQL_Result2, 0, 'pts');
		$Benutzer['schiffe'] = tic_mysql_result($SQL_Result2, 0, 's');
		$Benutzer['defensiv'] = tic_mysql_result($SQL_Result2, 0, 'd');
		$Benutzer['exen_m'] = tic_mysql_result($SQL_Result2, 0, 'me');
		$Benutzer['exen_k'] = tic_mysql_result($SQL_Result2, 0, 'ke');
	}
	$SQL_Result2 = tic_mysql_query("SELECT blind FROM `gn4allianzen` WHERE id='".$Benutzer['allianz']."' AND ticid='".$Benutzer['ticid']."'") or die(tic_mysqli_error(__FILE__,__LINE__));
	if (mysqli_num_rows($SQL_Result2) != 1)
	{
		$Benutzer['blind'] = 1;
	}
	else
	{
		$Benutzer['blind'] = tic_mysql_result($SQL_Result2, 0, 'blind');
	}

	//lastlogin setzen
	tic_mysql_query("UPDATE `gn4accounts` SET lastlogin='".time()."' WHERE id='".$Benutzer['id']."' AND ticid='".$Benutzer['ticid']."'") or die(tic_mysqli_error(__FILE__,__LINE__));

	// Spion???
	if($Benutzer['spy'] != 0 && $Benutzer['rang'] != RANG_STECHNIKER)
	{

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de" dir="ltr">
	<head>
		<title>TIC wird gewartet</title>
	</head>
	<body style="background-color:#000000">
		<table height="100%" width="100%">
			<tr height="80%"><td style="text-align:center">
				<font style="font: 36pt bold arial,sans-serif; color:#ffffff;text-align:center">
					Das "Tactical Information Center" ist<br />
					 wegen Wartungsarbeiten nicht erreichbar!<p><br /></p>
				</font>
			</td></tr>
		</table>
	</body>
</html>
<?php
		exit;
	}

	if(isset($_POST['action']) && $_POST['action'] != "")
		$action = $_POST['action'];
	else if(isset($_GET['action']) && $_GET['action'] != "")
		$action = $_GET['action'];
	else
		$action = "";

	// Incoming makieren
	if (isset($_GET['need_planet']) && isset($_GET['need_galaxie']))
	{
		LogAction($_GET['need_galaxie'].":".$_GET['need_planet']." -> Unsafe", LOG_SETSAFE);
		tic_mysql_query("UPDATE `gn4flottenbewegungen` SET save='0' WHERE verteidiger_galaxie='".$_GET['need_galaxie']."' AND verteidiger_planet='".$_GET['need_planet']."'") or die(tic_mysqli_error(__FILE__,__LINE__));
	}
	if (isset($_GET['needno_planet']) && isset($_GET['needno_galaxie']))
	{
		LogAction($_GET['needno_galaxie'].":".$_GET['needno_planet']." -> Safe", LOG_SETSAFE);
		tic_mysql_query("UPDATE `gn4flottenbewegungen` SET save='1' WHERE verteidiger_galaxie='".$_GET['needno_galaxie']."' AND verteidiger_planet='".$_GET['needno_planet']."'") or die(tic_mysqli_error(__FILE__,__LINE__));
	}

	if (isset($irc_log)) {
		if ($irc_log)
			include('irc-scans.inc.php');
	}
	// Funktion einbinden
	if ($action != "")
		include("./function.".$action.".php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de" dir="ltr">
	<head>
		<title>TIC - <?=$MetaInfo['name']?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="refresh" content="900; URL=./main.php?<?=(isset($_GET['auto']) ? "" : "auto").($_SERVER['QUERY_STRING'] != "" ? (isset($_GET['auto']) ? "" : "&amp;").str_replace("&", "&amp;", $_SERVER['QUERY_STRING']) : "")?>" />
		<link rel="stylesheet" href="./tic.css" type="text/css" />
		<script language="javascript" type="text/javascript">
		<!--
			function NeuFenster( link ) {
				MeinFenster = window.open( link, "Artikel", "width=800,height=300,scrollbars=yes,resizable=yes");
				MeinFenster.focus();
			}

//			if ( top.frames.length < 2) {
//				window.open("./frameset.html","_top");
//			}
		//-->
		</script>
		<script type="text/javascript" src="./overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
	</head>
	<body>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
		<div style="position:absolute; z-index:10;background-image:url('bilder/skin/background.bmp');width:100%">
		<!-- <div align="center" style="width:100%"><img src="bilder/skin/banner.jpg" alt="" align="middle" /></div> --> <!-- Banner -->
<?php
	include("./menu.inc.php");
?>
		<div style="position:relative; margin-left:200px; margin-right:30px;">
			<div class="info" align="center">
			<!--<font size="5"><b>T.I.C. | Tactical Information Center der Meta <?=$MetaInfo['name']?></b></font>//-->
<?php
	if ($error_code != 0)
		include("./inc_errors.php");
	else
	{
		include("./inc_accinfo.php");
		echo "			</div>\n";
		$mtime = microtime();
		$mtime = explode(" ", $mtime);
		$mtime = $mtime[1] + $mtime[0];
		$mid_time = $mtime;
		if (isset($_GET['auto']))
			echo "Auto-Refresh...";
		echo "<div class=\"main\" align=\"center\">";
		if ($Benutzer['pwdandern'] != 1)
			include("./inc_".$modul.".php");
		else
			include("./inc_pwdandern.php");

		if ($error_code != 0)
			include("./inc_errors.php");
	}
?>
		<div style="position:relative; width:100%; margin-top:10px;">
			<hr />
			<table width="100%"><tr>
				<td align="left" valign="top">
					<font size="-1">T.I.C. v<?=$version?></font><br />
					<a href="http://www.galaxy-network.de/game/login.php" target="_blank"><img style="border:0px" src="http://www.galaxy-network.de/banner_images/gn-button.gif" alt="Galax-Network" /></a>
				</td>
				<!--
				<td align="center" style="white-space:nowrap;">
					erstellt in
<?php
	$mtime = microtime();
	$mtime = explode(" ", $mtime);
	$mtime = $mtime[1] + $mtime[0];
	$end_time = $mtime;
	echo sprintf("%01.3f", $end_time - $start_time)." sek.";
	if (isset($mid_time) && $mid_time != 0)
	{
		echo " (".sprintf("%01.3f", $mid_time - $start_time)." sek.)<br />\n";
	}
	echo "<br />".count_querys(false)." Datenbankabfragen\n";
?>
				</td>
				<td align="right" valign="top">
					<a href="irc://irc.quakenet.org/tic-progger" target="_blank"><img style="border:0px" src="./bilder/TICELogo.jpg" alt="Tic-Entwickler" /></a>
				</td>//-->
			</tr></table>
		</div></div></div></div>
<?php
	if ($_REQUEST['autoclose'] == "now") {
?>
	<script language="javascript" type="text/javascript">
	<!--
	window.close();
	-->
	</script>
<?php
	}
?>
	</body>
</html>
