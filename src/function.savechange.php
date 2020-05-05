<?php
    if ( !isset( $_GET['fbid'] ) ){
        echo 'internal parameter-error #1';
        return;
    }
    if ( !isset( $_GET['incsave'] ) ){
        echo 'internal parameter-error #2';
        return;
    }
    
    $SQL_Result = tic_mysql_query("SELECT verteidiger_galaxie, verteidiger_planet FROM `gn4flottenbewegungen` WHERE id ='".$_GET['fbid']."'") or die(tic_mysql_error(__FILE__,__LINE__));
    
    if(!$row = mysql_fetch_row($SQL_Result))
        return;

    if ( $_GET['incsave'] == 1 )
    {
        $newsave = 0;
        LogAction($row[0].":".$row[1]." -> Safe", LOG_SETSAFE);
    }
    else
    {
        $newsave = 1;
        LogAction($row[0].":".$row[1]." -> Unsafe", LOG_SETSAFE);
    }
    
    tic_mysql_query("UPDATE `gn4flottenbewegungen` SET save='".$newsave."' WHERE id='".$_GET['fbid']."'") or die(tic_mysql_error(__FILE__,__LINE__));


?>
