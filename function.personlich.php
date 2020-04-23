<?php
$handy = htmlentities($_POST['handy']);
$messangerID = htmlentities($_POST['icq']);
$ticscreen = htmlentities($_POST['ticscreen']);
$infotext = htmlentities($_POST['infotext']);
$authnick = htmlentities($_POST['authnick']);
$incfreigabe=htmlentities($_POST['check']);
$lstZeitformat = htmlentities($_POST['lstZeitformat']);
        if ( $incfreigabe=='' ) {
            $incfreigabe = 0;
        } else {
            $incfreigabe = 1;
        }

$sql = "Update gn4accounts set handy='$handy', infotext='$infotext', authnick='$authnick', tcausw='$ticscreen', zeitformat='$lstZeitformat', messangerID='$messangerID', help='$incfreigabe' where id=".$Benutzer["id"].";";
$SQL_Result = tic_mysql_query($sql);
$Benutzer["zeitformat"]=$lstZeitformat;
$Benutzer["help"]= $incfreigabe;
$Benutzer['tcausw']= $ticscreen;
//echo $sql;
?>
