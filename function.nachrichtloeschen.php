<?php
// Nachricht löschen
    if ($_POST['action'] == 'nachrichtloeschen') {
        if ($Benutzer['rang'] < $Rang_GC)
            $error_code = 5;
        else {
            if (!isset($_POST['id'])) $_POST['id'] = '';
            if ($_POST['id'] == '')
                $error_code = 6;
            else
                $SQL_Result = tic_mysql_query('DELETE FROM `gn4nachrichten` WHERE id='.$_POST['id'].' ;', $SQL_DBConn) or $error_code = 7;
                if ($error_code == 0) LogAction("Nachricht gelöscht: ID=".$_POST['id'].";");
        }
    }
?>
