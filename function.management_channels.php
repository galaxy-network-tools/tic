<?php
if($Benutzer['rang']>=RANG_TECHNIKER and (isset($_POST['newchannel']) or isset($_POST['chanspeichern']) or isset($_POST['delchannel']))){
	$channame = $_POST['channame'];
	//$joincommand = $_POST['joincommand'];
	$pass = $_POST['pass'];
	$ally = $_POST['ally'];
	#$metachan = (isset($_POST['metachan']) and $_POST['metachan']>0) ? 1 : 0;
	$accessrang = $_POST['accessfor'];
	$inviterang = $_POST['invitefor'];
	$oprang = $_POST['opfor'];
	$voicerang = $_POST['voicefor'];
	$opcontrol = (isset($_POST['opcontrol']) and $_POST['opcontrol']>0) ? 1 : 0;
	$answer = (isset($_POST['answer']) and $_POST['answer']>0) ? 1 : 0;
	$id = $_POST['selectChan'];
	switch ($_POST['joincommand']) {
		case 0:
			$joincommand = "join";
			break;
		case 1:
			$joincommand = "privmsg L invite";
			break;
		case 2:
			$joincommand = "privmsg Q invite";
			break;

	};
	if(isset($_POST['newchannel'])) {
		$qry = sprintf ("INSERT INTO `gn4channels` (channame, joincommand, pass, ally, accessrang, inviterang, oprang, opcontrol, voicerang, answer) VALUES ('%s', '%s', '%s', %d, %d, %d, %d, %d, %d, %d);",
			$channame, $joincommand, $pass, $ally, $accessrang, $inviterang, $oprang, $opcontrol, $voicerang, $answer
			);
	} elseif(isset($_POST['chanspeichern'])) {
		$qry = sprintf("Update `gn4channels` set channame='%s', joincommand='%s', pass='%s', ally=%d, accessrang=%d, inviterang=%d, oprang=%d, opcontrol=%d, voicerang=%d, answer=%d where id=%d;", 
			$channame, $joincommand, $pass, $ally,
			$accessrang, $inviterang, $oprang, $opcontrol, $voicerang, $answer,
			$id
			);
	} elseif(isset($_POST['delchannel'])) {
		$qry = sprintf("delete from `gn4channels` where id=$id");
	};
	//print ("<!--\n");
	//print_r($_POST);
	//print("\n -->");
	//print("<!-- ".$qry." -->\n");
	tic_mysql_query($qry,__FILE__,__LINE__);
}
?>
