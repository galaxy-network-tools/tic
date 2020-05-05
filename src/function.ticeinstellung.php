<?php
$SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE id="'.$_SESSION['userid'].'" and ticid="'.$Benutzer['ticid'].'";', $SQL_DBConn);
$rang = mysql_result($SQL_Result, 0,'rang');
if ($rang == $Rang_STechniker){
$SQL_Result = tic_mysql_query('UPDATE `gn4vars` SET value="'.$_POST['newmeta'].'" WHERE name="ticeb" and ticid="'.$Benutzer['ticid'].'";', $SQL_DBConn);
$SQL_Result = tic_mysql_query('UPDATE `gn4vars` SET value="'.$_POST['newsystemnachricht'].'" WHERE name="systemnachricht" and ticid="'.$Benutzer['ticid'].'";', $SQL_DBConn);
$SQL_Result = tic_mysql_query('UPDATE `gn4vars` SET value="'.$_POST['newbotpw'].'" WHERE name="botpw" and ticid="'.$Benutzer['ticid'].'";', $SQL_DBConn);
$systemnachricht = $_POST['newsystemnachricht'];
}
?>
