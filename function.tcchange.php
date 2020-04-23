<?php
    if ( !isset($_GET['tc']) ){
        echo 'internal parameter-error #1';
        return;
    }
    if ( !isset($_GET['id']) ){
        echo 'internal parameter-error #1';
        return;
    }


    if ( $_GET['tc'] == 1 )
        $newtc=0;
    else
        $newtc=1;

    $sql = 'UPDATE `gn4accounts` SET tcausw="'.$newtc.'" WHERE id='.$_GET['id'];
    if ( $sql != '' ){
        $SQL_result = tic_mysql_query( $sql, $SQL_DBConn);
    }
    $Benutzer['tcausw']=$newtc;

?>
