<?PHP
// Flottenbewegung löschen
    if ($_POST['action'] == 'flotteloeschen') {
        if (!isset($_POST['flottenid'])) $_POST['flottenid'] = '';
        if ($_POST['flottenid'] != '') {
            $SQL_Result = tic_mysql_query('DELETE FROM `gn4flottenbewegungen` WHERE id='.$_POST['flottenid'].'', $SQL_DBConn) or $error_code = 7;
        } else $error_code = 6;
    }
?>
