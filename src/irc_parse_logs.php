<?php

include('sessionhelpers.inc.php');
include('./accdata.php' );
include('functions.php');

$query = "SELECT * FROM gn4accounts WHERE name='irc'";
$Benutzer = array(
	"ticid" => 1,
	"name" => "IRC-Bot",
	"galaxie" => 0,
	"planet" => 1);

include('irc-scans.inc.php');

?>