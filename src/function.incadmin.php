<?php
    if ( strcmp( $_POST['subaction'], 'adminchange') == 0 ){
         $sql = 'delete from `gn4vars` where name="assi"';
         $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);

        if ( $_POST['organisator'] <> '' and strcmp( $_POST['organisator'], '0:0') <> 0 ) {
            $sql = 'delete from `gn4vars` where name="admin" ';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);


            $sql = 'INSERT INTO `gn4vars` (ticid, value, name ) ';
            $sql .= 'VALUES("'.$Benutzer['ticid'].'", "'.$_POST['organisator'].'","admin" )';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);
        }
        if ( $_POST['vertreter1'] <> '' and strcmp( $_POST['vertreter1'], '0:0') <> 0 ) {
            $sql = 'INSERT INTO `gn4vars` (ticid, value, name) ';
            $sql .= 'VALUES("'.$Benutzer['ticid'].'", "'.$_POST['vertreter1'].'","assi" )';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);
        }
        if ( $_POST['vertreter2'] <> '' and strcmp( $_POST['vertreter2'], '0:0') <> 0 ) {
            $sql = 'INSERT INTO `gn4vars` (ticid, value, name) ';
            $sql .= 'VALUES("'.$Benutzer['ticid'].'", "'.$_POST['vertreter2'].'","assi" )';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);
        }
        if ( $_POST['vertreter3'] <> '' and strcmp( $_POST['vertreter3'], '0:0') <> 0 ) {
            $exp = explode( ':', $_POST['vertreter3'] );
            $sql = 'INSERT INTO `gn4vars` (ticid, value, name) ';
            $sql .= 'VALUES("'.$Benutzer['ticid'].'", "'.$_POST['vertreter3'].'","assi" )';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);
        }
    } else if ( strcmp( $_POST['subaction'], 'scannerchange') == 0 ){
        $sql = 'delete from `gn4vars` where name="scanner" ';
        $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);

        if ( $_POST['scanner1'] <> '' and strcmp( $_POST['scanner1'], '0:0') <> 0 ) {
            $sql = 'INSERT INTO `gn4vars` (ticid, value, name) ';
            $sql .= 'VALUES("'.$Benutzer['ticid'].'", "'.$_POST['scanner1'].'","scanner" )';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);
        }
        if ( $_POST['scanner2'] <> '' and strcmp( $_POST['scanner2'], '0:0') <> 0 ) {
            $sql = 'INSERT INTO `gn4vars` (ticid, value, name) ';
            $sql .= 'VALUES("'.$Benutzer['ticid'].'", "'.$_POST['scanner2'].'","scanner" )';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);
        }
        if ( $_POST['scanner3'] <> '' and strcmp( $_POST['scanner3'], '0:0') <> 0 ) {
            $sql = 'INSERT INTO `gn4vars` (ticid, value, name) ';
            $sql .= 'VALUES("'.$Benutzer['ticid'].'", "'.$_POST['scanner3'].'","scanner" )';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);
        }
        if ( $_POST['scanner4'] <> '' and strcmp( $_POST['scanner4'], '0:0') <> 0 ) {
            $sql = 'INSERT INTO `gn4vars` (ticid, value, name) ';
            $sql .= 'VALUES("'.$Benutzer['ticid'].'", "'.$_POST['scanner4'].'","scanner" )';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);
        }
        if ( $_POST['scanner5'] <> '' and strcmp( $_POST['scanner5'], '0:0') <> 0 ) {
            $sql = 'INSERT INTO `gn4vars` (ticid, value, name) ';
            $sql .= 'VALUES("'.$Benutzer['ticid'].'", "'.$scaner5.'","scanner" )';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);
        }

    } else if ( strcmp( $_POST['subaction'], 'galachange') == ' ' ){
        $sql = 'delete from `gn4vars` where name="galainc" ';
        $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);

        for ( $i=0; $i<10; $i++ ){
            $sql = 'INSERT INTO `gn4vars` ( name, value ) ';
            $sql .= 'VALUES("galainc", "'.$_POST['ziel'.$i].'|'.$_POST['zieldesc'.$i].'" )';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);

            if ( $_POST['ziel'.$i] <> 0 ){
                $sql = 'select gala, planet from `gn4incplanets`  where gala='.$_POST['ziel'.$i].' and planet=1 ';
                $SQL_Result2 = tic_mysql_query( $sql, $SQL_DBConn);
                $count = mysqli_num_rows($SQL_Result2) or $count=0;
                if ( $count == 0 ){
                    for ( $j=0; $j<12; $j++ ) {
                        $nr = $j+1;
                        $sql2 = 'INSERT INTO `gn4incplanets` (planet, gala, bestaetigt, vorgemerkt ) VALUES("'.$nr.'","'.$_POST['ziel'.$i].'", "", "" )';
                        $SQL_Result2 = tic_mysql_query( $sql2, $SQL_DBConn );
                    }
                }
            }
        }



    } else if ( strcmp( $_POST['subaction'], 'activechange') == 0 ){
        $incfreigabe=$_POST['check'];
        if ( $incfreigabe=='' ) {
            $incfreigabe = 0;
        } else {
            $incfreigabe = 1;
        }

        $sql = 'SELECT value from `gn4vars` where name="incfreigabe" ';
        $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
        $count = mysqli_num_rows($SQL_Result) or $count=0;
        if ( $count > 0 ){
            $sql = 'UPDATE `gn4vars` SET value="'.$incfreigabe.'" WHERE name="incfreigabe"';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);
        } else {
            $sql = 'INSERT INTO `gn4vars` (name, value ) VALUES( "incfreigabe","'.$incfreigabe.'" )';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn);
        }

        if ( $incfreigabe == 1 ){
            // lösche alle incplanets
            $sql = 'DELETE FROM `gn4incplanets` ';
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
            /* erzeuge alle planeten neu */
            $sql = 'SELECT name, value from `gn4vars` where name = "galainc" and value !="|" order by id';

            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
            $count = mysqli_num_rows($SQL_Result) or $count=0;
            for ( $i=0; $i<$count; $i++ ) {
                $galatr = tic_mysql_result($SQL_Result, $i, 'value' );
                $galatr2= explode("|", $galatr);
		        $gala   = $galatr2[0];
                for ( $j=0; $j<12; $j++ ) {
                    $nr = $j+1;
                    $sql2 = 'INSERT INTO `gn4incplanets` (planet, gala, bestaetigt, vorgemerkt, frei ) VALUES( "'.$nr.'","'.$gala.'", "", "", "1" )';
                    $SQL_Result2 = tic_mysql_query( $sql2, $SQL_DBConn );
                }
            }

        }


    }


?>
