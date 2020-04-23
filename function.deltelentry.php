<?php
if ( strcmp( $_GET['action'], 'deltelentry' ) == 0 ) {
    $SQL_Result = tic_mysql_query('DELETE FROM `gn4accountadds` WHERE id="'.$_GET['teleid'].'" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn) or $error_code = 7;
}

?>
