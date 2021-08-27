<center>
    <h1>Nachrichten</h1>
    (neues aus den Allies und dem T.I.C.)<br />
    <?php
        $ccode = "333333";
        if ($Benutzer['rang'] == $Rang_Mitglied) {
           $SQL_Result = tic_mysql_query('SELECT * FROM `gn4nachrichten` where ticid="'.$Benutzer['ticid'].'" OR ticid="alle" ORDER BY id DESC;', $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());
        } else {
           if ($Benutzer['rang'] >= $Rang_VizeAdmiral) {
              // Auch SuperGau news anzeigen
              $SQL_Result = tic_mysql_query('SELECT * FROM `gn4nachrichten` where ticid="'.$Benutzer['ticid'].'" or ticid="H'.$Benutzer['ticid'].'"  or ticid="SHC" OR ticid="alle" ORDER BY id DESC;', $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());
           } else {
              $SQL_Result = tic_mysql_query('SELECT * FROM `gn4nachrichten` where ticid="'.$Benutzer['ticid'].'" or ticid="H'.$Benutzer['ticid'].'" OR ticid="alle" ORDER BY id DESC;', $SQL_DBConn) or die(mysqli_errno()." - ".mysqli_error());
           }
        }
        $SQL_Num = mysqli_num_rows($SQL_Result);
        if ($SQL_Num == 0)
            echo '<b>Es sind keine Nachrichten vorhanden.</b>';
        else {
            echo '<table width="80%">';
            for ($n = 0; $n < $SQL_Num; $n++) {
                $ntype = substr(tic_mysql_result($SQL_Result, $n, 'ticid'),0,1);
                if ($ntype=='H') {
                   $ccode = "aa3333";
                } else if ($ntype=='S') {
                   $ccode = "ff000";
                } else {
                  $ccode = "333333";
                }

                echo '  <tr class="datatablehead">';
                echo '      <td width="50%">'.tic_mysql_result($SQL_Result, $n, 'titel').'</td><td width="15%">'.tic_mysql_result($SQL_Result, $n, 'zeit').'</td><td width="35%" >'.tic_mysql_result($SQL_Result, $n, 'name').'</td>';
                echo '  </tr>';
                echo '  <tr>';
                echo '  <td colspan="3" class="fieldnormallight" style="background-color:'.$ccode.'">'.tic_mysql_result($SQL_Result, $n, 'text').'</td>';
                echo '  </tr>';
                if ($Benutzer['rang'] > $Rang_GC) {
                    echo '<tr class="fieldnormaldark"><td colspan="3">';
                    echo '<form action="./main.php?modul=nachrichten" method="post">';
                            echo '<input type="hidden" name="id" value="'.tic_mysql_result($SQL_Result, $n, 'id').'" />';
                            echo '<input type="hidden" name="action" value="nachrichtloeschen" />';
                            echo '<Input type="submit" value="L&ouml;schen" />';
                    echo '</form></td></tr>';
                }
                echo '  <tr>';
                echo '      <td colspan="2"><br /></td>';
                echo '  </tr>';
            }
            echo '</TABLE>';
        }
    ?>
</CENTER>
