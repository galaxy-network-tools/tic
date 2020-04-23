<?php
    if ( !isset( $_GET['fbid'] ) ){
        echo 'internal parameter-error #1';
        return;
    }
    if ( !isset( $_GET['flonr'] ) ){
        echo 'internal parameter-error #2';
        return;
    }

    if ( $_GET['flonr'] == 1 ){
        $newflonr=2;
        $anr=1;
    }else{
        $anr=2;
        $newflonr=1;
        }

    $sql = 'UPDATE `gn4flottenbewegungen` SET flottennr="'.$newflonr.'" WHERE id='.$_GET['fbid'].';';
    if ( $sql != '' ){
        $SQL_result = tic_mysql_query( $sql, $SQL_DBConn);
    }
    $SQL_Result= tic_mysql_query('SELECT angreifer_galaxie, angreifer_planet FROM `gn4flottenbewegungen` WHERE id="'.$_GET['fbid'].'";')or die(mysql_errno()." - ".mysql_error());
    if(mysql_num_rows($SQL_Result)=='1'){
    $von_gala= mysql_result($SQL_Result,0,'angreifer_galaxie');
    $von_planet= mysql_result($SQL_Result,0,'angreifer_planet');
    $SQL_Result= tic_mysql_query('SELECT id FROM `gn4flottenbewegungen` WHERE id!="'.$_GET['fbid'].'" and angreifer_galaxie="'.$von_gala.'" and angreifer_planet="'.$von_planet.'";');
    $NUM_ROS=mysql_num_rows($SQL_Result);
    if($NUM_ROS==1){
    $id2=mysql_result($SQL_Result,0,'id');
    tic_mysql_query('UPDATE `gn4flottenbewegungen` SET flottennr="'.$anr.'" WHERE id="'.$id2.'";');
    }
    }


?>
