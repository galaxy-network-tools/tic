<?php


    function GetFirstKoords( $planetlist ) {
        $arr = explode( '|', $planetlist );
        return $arr[0];
    }

    function GetAllKoordsExceptFirst( $planetlist ) {

        $galas = '';
        $count = 0;
        if ( strpos( $planetlist, '|' ) > 0 ) {
            $gal = explode( '|', $planetlist );
            $count = count($gal);
        }
        for ( $idx=1; $idx < $count; $idx++ ) {
            if ( $idx == 1 ) {
                $galas = $gal[$idx];
            } else {
                $galas .= '|'.$gal[$idx];
            }
        }
        return $galas;
    }
    function RemoveKoordsFrom( $remove, $planetlist ) {

        $galas = '';
        $count = 0;
        if ( strpos( $planetlist, '|' ) > 0 ) {
            $gal = explode( '|', $planetlist );
            $count = count($gal);
        }
        for ( $idx=0; $idx < $count; $idx++ ) {
            if ( strpos(  $gal[$idx], $remove ) === false ) {
                if ( strcmp( $galas, '' ) == 0 ) {
echo 'add first!<br>';
                    $galas = $gal[$idx];
                } else {
                    $galas .= '|'.$gal[$idx];
echo 'add next!<br>';
                }
            }
        }
        return $galas;
    }
// end of functions -----------------------------------------------------------


if( strcmp( $_POST['subaction'], "incadd" ) == 0 ) {
    $s = $_POST['playergala'] .':'. $_POST['playerplanet'].'@'.$_POST['fleet'];
    $sql  = 'select vorgemerkt from `gn4incplanets` wHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
    $abort = 0;
    $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
    if ( mysql_num_rows($SQL_Result) == 1 ) {
        $sins  = mysql_result( $SQL_Result, 0, 'vorgemerkt' );
        if ( strstr( $sins, $s ) <> "" )
            $abort = 1;
    } else {
        $sins = "";
    }
    if ( strcmp( $sins, "" ) == 0 ) {
        $sins = $s;
    } else {
        $sins .= '|'.$s;
    }


    if ( $abort == 0 ) {
        $sql2 = 'UPDATE `gn4incplanets` SET vorgemerkt="'.$sins.'" WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
        $SQL_Result2 = tic_mysql_query( $sql2, $SQL_DBConn );
    }
} else if( strcmp( $_POST['subaction'], "removevormerkung" ) == 0 ) {
    $s = $_POST['gala'] .':'. $_POST['plant'];
    $sql  = 'select vorgemerkt from `gn4incplanets` wHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';

    $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
    if ( mysql_num_rows($SQL_Result) > 0 ) {
        $sins  = mysql_result( $SQL_Result, 0, 'vorgemerkt' );
        if ( strcmp( $sins,  "" ) <> 0 ) {
            $sins = GetAllKoordsExceptFirst( $sins );
            $sql2 = 'UPDATE `gn4incplanets` SET vorgemerkt="'.$sins.'" WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
            $SQL_Result2 = tic_mysql_query( $sql2, $SQL_DBConn );
        }
    }



} else if( strcmp( $_POST['subaction'], "addvormerkung" ) == 0 ) {
    // add a
    $sql  = 'SELECT vorgemerkt, bestaetigt FROM `gn4incplanets` WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
    $addgala = '';
    $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
    if ( mysql_num_rows($SQL_Result) == 1 ) {
        $sins  = mysql_result( $SQL_Result, 0, 'vorgemerkt' );
        $addgala = GetFirstKoords( $sins );
        $sins = GetAllKoordsExceptFirst( $sins );
        $sql2 = 'UPDATE `gn4incplanets` SET vorgemerkt="'.$sins.'" WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
        $SQL_Result2 = tic_mysql_query( $sql2, $SQL_DBConn );
    }
    if ( $addgala <> '' ) {
        $bestaetigt = mysql_result( $SQL_Result, 0, 'bestaetigt' );
        if ( strpos( $bestaetigt, $addgala ) === false  ) {
            if ( $bestaetigt == "" ) {
                $bestaetigt = $addgala;
            } else {
                $bestaetigt .= '|'.$addgala;
            }

            $sql2 = 'UPDATE `gn4incplanets` SET bestaetigt="'.$bestaetigt.'" WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
            $SQL_Result2 = tic_mysql_query( $sql2, $SQL_DBConn );
        }
    }
} else if( strcmp( $_POST['subaction'], "removebestaetigt" ) == 0 ) {
    // lösche von bestätigt
    $sql  = 'SELECT bestaetigt, vorgemerkt FROM `gn4incplanets` WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
    $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
    if ( mysql_num_rows($SQL_Result) > 0 ) {
        $sins  = mysql_result( $SQL_Result, 0, 'bestaetigt' );
        if ( strcmp( $sins, "" ) <> 0 ) {
            $_POST['fleet'] = GetFirstKoords( $sins );
            $sins = GetAllKoordsExceptFirst( $sins );
            $sql2 = 'UPDATE `gn4incplanets` SET bestaetigt="'.$sins.'" WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
            $SQL_Result2 = tic_mysql_query( $sql2, $SQL_DBConn );
        }
    }

    $abort = 0;
    $sins  = mysql_result( $SQL_Result, 0, 'vorgemerkt' );
    if ( strstr( $sins, $_POST['fleet'] ) <> "" )
        $abort = 1;

    if ( strcmp( $sins, "" ) == 0 ) {
        $sins = $_POST['fleet'];
    } else {
        $sins .= '|'.$_POST['fleet'];
    }


    if ( $abort == 0 ) {
        $sql2 = 'UPDATE `gn4incplanets` SET vorgemerkt="'.$sins.'" WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
        $SQL_Result2 = tic_mysql_query( $sql2, $SQL_DBConn );
    }

} else if( strcmp( $_POST['subaction'], "removespecial" ) == 0 ) {
    // remove a specila gala/planet from vorgemerkt
    $sql  = 'SELECT bestaetigt, vorgemerkt FROM `gn4incplanets` WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
    $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
    if ( mysql_num_rows($SQL_Result) == 1 ) {
        $sins  = mysql_result( $SQL_Result, 0, 'vorgemerkt' );
        $s = $_POST['playergala'] .':'. $_POST['playerplanet'].'@'.$_POST['fleet'];
        $sins = RemoveKoordsFrom( $s, $sins );

        $sql2 = 'UPDATE `gn4incplanets` SET vorgemerkt="'.$sins.'" WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
        $SQL_Result2 = tic_mysql_query( $sql2, $SQL_DBConn );
    }
} else if( strcmp( $_POST['subaction'], "removeplanet" ) == 0 ) {
    // remove a specila planet from list
    $sql  = 'SELECT bestaetigt, vorgemerkt FROM `gn4incplanets` WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
    $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
    if ( mysql_num_rows($SQL_Result) == 1 ) {
        $sins  = mysql_result( $SQL_Result, 0, 'vorgemerkt' );
        $s = $_POST['playergala'] .':'. $_POST['playerplanet'].'@'.$_POST['fleet'];
        $sins = RemoveKoordsFrom( $s, $sins );

        $sql2 = 'DELETE FROM `gn4incplanets` WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
        $SQL_Result2 = tic_mysql_query( $sql2, $SQL_DBConn );
    }
} else if( strcmp( $_POST['subaction'], "close" ) == 0 ) {
    $sql2 = 'UPDATE `gn4incplanets` SET frei="'.$offen.'" WHERE gala="'.$_POST['gala'].'" and planet="'.$_POST['plant'].'"';
    $SQL_Result2 = tic_mysql_query( $sql2, $SQL_DBConn );
}


?>
