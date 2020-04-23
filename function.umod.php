<?PHP
// U-Mod einstellen
    if ($_POST['action'] == 'umod') {
        if (!isset($_POST['UModID'])) $_POST['UModID'] = '';
        if (!isset($_POST['txtUModZeit'])) $_POST['txtUModZeit'] = '';
        if ($_POST['UModID'] == '') {
            $error_code = 6;
        } else {
            $SQL_Result = tic_mysql_query('UPDATE `gn4accounts` SET umod="'.$_POST['txtUModZeit'].'" WHERE id="'.$_POST['UModID'].'"', $SQL_DBConn) or $error_code = 7;
            if ($error_code == 0 && $Benutzer['id'] == $_POST['UModID']) $Benutzer['umod'] = $_POST['txtUModZeit'];
        }
    }
?>
