<?php
if ($_POST['action'] == 'settaktiksort') {
    $SQL_command = 'UPDATE `gn4accounts` SET taktiksort="'.$_POST['taktik_sort'].'" WHERE id="'.$Benutzer['id'].'";';
    $SQL_Result = tic_mysql_query( $SQL_command, $SQL_DBConn) or $error_code = 7;
    $Benutzer['taktiksort'] = $_POST['taktik_sort'];
    }

?>
