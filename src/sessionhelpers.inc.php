<?php
function injsafe($value) {
	if (get_magic_quotes_gpc())
		return $value;
	if(is_array($value)) {
		foreach($value as $key => $null)
			$value[$key] = injsafe($value[$key]);
	} else {
//		return addcslashes($value, "\000\x00\n\r\\'\"\x1a");
		return addslashes($value);
	}
	return $value;
}


function connect() {
    include('./accdata.php' );
    $SQL_DBConn = mysqli_connect($db_info['host'], $db_info['user'], $db_info['password']);
    mysqli_select_db($SQL_DBConn, $db_info['dbname'] );

    $SQL_Query = "SET CHARACTER SET latin1";
    @mysqli_query($SQL_DBConn, $SQL_Query );
    return $SQL_DBConn;
}

function check_user($name, $pass) {
	global $SQL_DBConn;
	$SQL_Query = "SELECT ip, versuche FROM `gn4accounts` WHERE name='".$name."' LIMIT 1;";
	$SQL_Result_iplock = mysqli_query($SQL_DBConn, $SQL_Query) or die(mysqli_errno()." - ".mysqli_error());
	$iplock = mysqli_fetch_assoc($SQL_Result_iplock);
	if (!$iplock)
		return false;

	if($iplock['ip'] == $_SERVER['REMOTE_ADDR'] && $iplock['versuche'] >= 3)
		die ('Dieser Account ist gesperrt, wenden sie sich an Ihren Adminstrator');
	mysqli_free_result($SQL_Result_iplock);

	$SQL_Query = "SELECT id FROM gn4accounts WHERE name='".$name."' AND passwort=MD5('".$pass."') LIMIT 1;";
	$SQL_Result_login = mysqli_query($SQL_DBConn, $SQL_Query) or die(mysqli_errno()." - ".mysqli_error());
	if ($user = mysqli_fetch_assoc($SQL_Result_login)) {
		mysqli_free_result($SQL_Result_login);
		$SQL_Query = "UPDATE gn4accounts SET versuche=0, ip='' WHERE name='".$name."';";
		mysqli_query($SQL_DBConn, $SQL_Query) or die(mysqli_errno()." - ".mysqli_error());
		return $user['id'];
	}

	$SQL_Query = "UPDATE gn4accounts SET versuche=versuche + 1, ip='".$_SERVER['REMOTE_ADDR']."' WHERE name='".$name."';";
	mysqli_query($SQL_DBConn, $SQL_Query) or die(mysqli_errno()." - ".mysqli_error());
	return false;
}

/* werden derzeit nicht gebraucht
function login($userid)
{
    $sql="UPDATE gn4accounts
    SET session='".session_id()."'
    WHERE id=".$userid." and ticid=".$ticid;
     mysql_query($sql);
}

function logged_in()
{
    $sql="SELECT id
    FROM gn4accounts
    WHERE session='".session_id()."'
    LIMIT 1";
    $result= mysql_query($sql);
      return ( mysqli_num_rows($result)==1);
}

function logout()
{
    $sql="UPDATE gn4accounts
    SET session=NULL
    WHERE session='".session_id()."'";
     mysql_query($sql);
}*/

$SQL_DBConn = connect();
?>
