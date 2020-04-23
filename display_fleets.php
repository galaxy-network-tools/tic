<?php
    include('./accdata.php');
    include('./functions.php');
    include('./globalvars.php');


    // Verbindung zur Datenbank aufbauen
    $SQL_DBConn = mysql_connect($db_info['host'], $db_info['user'], $db_info['password']) or $error_code = 1;
    mysql_select_db($db_info['dbname'], $SQL_DBConn) or $error_code = 2;

    $fleets = explode( '|', $fleet );

?>
<HTML>
    <HEAD>
        <TITLE>T.I.C. Fleet Summary - <?php $HTTP_HOST ?> - <?php $SCRIPT_NAME ?></TITLE>
        <link rel="stylesheet" href="tic.css" type="text/css">
    </HEAD>
    <BODY bgcolor="#B3B1C2" LINK=#0000ff VLINK=#0000ff ALINK=#ff0000>
        <table align="center">
            <tr>
                <td><font size="4">
                    Fleet Summary - <?php echo $fleet; ?>
                </font></td>
            </tr>
        </table>
        <table align="center">
        <tr>
        <td bgcolor="#666666">
        <table width="100%" cellspacing="3">
            <?php
                $sumja     = 0;
                $sumbo     = 0;
                $sumfr     = 0;
                $sumze     = 0;
                $sumkr     = 0;
                $sumsl     = 0;
                $sumtr     = 0;
                $sumka     = 0;
                $sumca     = 0;
                $count = count( $fleets );
                for ( $i=0; $i<$count; $i++ ) {
                    echo '<tr>';
                    echo '<td bgcolor="#dddddd"><font size="-1">';
                    $ef = explode( '@', $fleets[$i] );   // koords + fleetnumber  123:34@2
                    if ( $ef[1] == 1 ) echo $ef[0].' Flotte&nbsp;1:';
                    else               echo $ef[0].' Flotte&nbsp;2:';

                    echo '</font></td>';

                    echo '<td bgcolor="#ffffff"><font size="-1">';
                    $eg = explode( ':', $ef[0] );

                    $sql = 'SELECT sf'.$ef[1].'j ,sf'.$ef[1].'b ,sf'.$ef[1].'f ,sf'.$ef[1].'z ,sf'.$ef[1].'kr ,sf'.$ef[1].'sa ,sf'.$ef[1].'t ,sf'.$ef[1].'ka ,sf'.$ef[1].'su FROM `gn4scans` WHERE rg="'.$eg[0].'" and rp="'.$eg[1].'" and type=2';
                    $SQL_Result2 = tic_mysql_query( $sql, $SQL_DBConn );
                    if ( mysql_num_rows($SQL_Result2) == 0 ) {
                        $ja     = 0;
                        $bo     = 0;
                        $fr     = 0;
                        $ze     = 0;
                        $kr     = 0;
                        $sl     = 0;
                        $tr     = 0;
                        $ka     = 0;
                        $ca     = 0;
                    } else {

                        $ja     = mysql_result($SQL_Result2, 0, 'sf'.$ef[1].'j' );
                        $bo     = mysql_result($SQL_Result2, 0, 'sf'.$ef[1].'b' );
                        $fr     = mysql_result($SQL_Result2, 0, 'sf'.$ef[1].'f' );
                        $ze     = mysql_result($SQL_Result2, 0, 'sf'.$ef[1].'z' );
                        $kr     = mysql_result($SQL_Result2, 0, 'sf'.$ef[1].'kr' );
                        $sl     = mysql_result($SQL_Result2, 0, 'sf'.$ef[1].'sa' );
                        $tr     = mysql_result($SQL_Result2, 0, 'sf'.$ef[1].'t' );
                        $ka     = mysql_result($SQL_Result2, 0, 'sf'.$ef[1].'ka' );
                        $ca     = mysql_result($SQL_Result2, 0, 'sf'.$ef[1].'su' );
                    }

                    $sumja += $ja;
                    $sumbo += $bo;
                    $sumfr += $fr;
                    $sumze += $ze;
                    $sumkr += $kr;
                    $sumsl += $sl;
                    $sumtr += $tr;
                    $sumka += $ka;
                    $sumca += $ca;

                    /*
                    printf( "%5d J&auml; &nbsp;-&nbsp; %5d Bo &nbsp;-&nbsp; %5d Fr &nbsp;-&nbsp; %5d Ze &nbsp;-&nbsp; %5d Kr &nbsp;-&nbsp; %5d Schl &nbsp;-&nbsp; %5d Tr &nbsp;-&nbsp; %5d Schutz &nbsp;-&nbsp; %5d Kaper\n",
                    $ja ,$bo ,$fr ,$ze ,$kr ,$sl ,$tr ,$ca, $ka );
                    */
                    echo '<table width="100%">';


                    echo '<tr>';

                    echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1">';
                    echo $ja.' J&auml;';
                    echo '</font></td>';
                    echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1">';
                    echo $bo.' Bo';
                    echo '</font></td>';
                    echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1">';
                    echo $fr.' Fr';
                    echo '</font></td>';
                    echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1">';
                    echo $ze.' Ze';
                    echo '</font></td>';
                    echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1">';
                    echo $kr.' Kr';
                    echo '</font></td>';
                    echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1">';
                    echo $sl.' Schl';
                    echo '</font></td>';
                    echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1">';
                    echo $tr.' Tr';
                    echo '</font></td>';
                    echo '<td bgcolor="#eeeeee" width="120" align="right"><font size="-1">';
                    echo $ca.' Schutz';
                    echo '</font></td>';
                    echo '<td bgcolor="#eeeeee" width="120" align="right"><font size="-1">';
                    echo $ka.' Kaper';
                    echo '</font></td>';

                    echo '</tr>';
                    echo '</table>';
                    echo '</font></td>';
                    echo '</tr>';
                }

                echo '<tr>';
                echo '<td bgcolor="#aaaaaa"><font size="-1"><b>';
                echo 'Summe:';
                echo '</b></font></td>';
                echo '<td bgcolor="#ffffff"><font size="-1"><b>';

//                printf( "%5d J&auml; &nbsp;-&nbsp; %5d Bo &nbsp;-&nbsp; %5d Fr &nbsp;-&nbsp; %5d Ze &nbsp;-&nbsp; %5d Kr &nbsp;-&nbsp; %5d Schl &nbsp;-&nbsp; %5d Tr &nbsp;-&nbsp; %5d Schutz &nbsp;-&nbsp; %5d Kaper\n", $sumja ,$sumbo ,$sumfr ,$sumze ,$sumkr ,$sumsl ,$sumtr ,$sumca, $sumka );
                echo '<table width="100%">';


                echo '<tr>';

                echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1"><b>';
                echo $sumja.' J&auml;';
                echo '</b></font></td>';
                echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1"><b>';
                echo $sumbo.' Bo';
                echo '</b></font></td>';
                echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1"><b>';
                echo $sumfr.' Fr';
                echo '</b></font></td>';
                echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1"><b>';
                echo $sumze.' Ze';
                echo '</b></font></td>';
                echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1"><b>';
                echo $sumkr.' Kr';
                echo '</b></font></td>';
                echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1"><b>';
                echo $sumsl.' Schl';
                echo '</b></font></td>';
                echo '<td bgcolor="#eeeeee" width="80" align="right"><font size="-1"><b>';
                echo $sumtr.' Tr';
                echo '</b></font></td>';
                echo '<td bgcolor="#eeeeee" width="120" align="right"><font size="-1"><b>';
                echo $sumca.' Schutz';
                echo '</b></font></td>';
                echo '<td bgcolor="#eeeeee" width="120" align="right"><font size="-1"><b>';
                echo $sumka.' Kaper';
                echo '</b></font></td>';

                echo '</tr>';
                echo '</table>';
                echo '</font></td>';
                echo '</tr>';

                echo '</b></font></td>';
                echo '</tr>';
            ?>
        </table>
        </td>
        </tr>
        </table>
        <br>
        <form>
          <div align="center">
            <input type=button value="       Fenster schliessen      " onClick="self.close()">
          </div>
        </form>
    </BODY>
</HTML>
