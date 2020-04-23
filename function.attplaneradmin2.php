<?
// ATT-Planer Administration / Configuration Methode GET
    if (!isset($_GET['fkt'])) $_GET['fkt'] = '';

    if (!isset($_GET['id'])) $_GET['id'] = '';
    if (!isset($_GET['lfd'])) $_GET['lfd'] = '';

    if (!isset($_GET['attplaner'])) $_GET['attplaner'] = '';

    if (!isset($_GET['galaxie'])) $_GET['galaxie'] = '';
    if (!isset($_GET['planet'])) $_GET['planet'] = '';
    if (!isset($_GET['attdatum'])) $_GET['attdatum'] = '';
    if (!isset($_GET['attzeit'])) $_GET['attzeit'] = '';
    if (!isset($_GET['attfor'])) $_GET['attfor'] = '';
    if (!isset($_GET['freigabe'])) $_GET['freigabe'] = '';
    if (!isset($_GET['info'])) $_GET['info'] = '';

    if (!isset($_GET['flotte'])) $_GET['flotte'] = '';
    if (!isset($_GET['attstatus'])) $_GET['attstatus'] = '';

    if ($_GET['fkt'] == 'attfreigabe') {
        $SQL = 'UPDATE gn4attplanung SET freigabe = 1 WHERE lfd ='.$_GET['lfd'].';';
        $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
        echo 'Att-Ziel freigegeben!'.$_GET['lfd'];
    }

    if ($_GET['fkt'] == 'attloeschung' && $_GET['lfd'] != '') {
       $ret =del_attplanlfd($_GET['lfd']);
    }


?>

