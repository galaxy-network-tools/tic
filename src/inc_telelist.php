  <table align="center" width="80%">
    <tr class="datatablehead">
      <td>Gala</td>
      <td>Name</td>
      <td>Telefon</td>
      <td>ICQ</td>
      <td>Zusatzinfos</td>
      <?php
        if ($Benutzer['rang'] > $Rang_GC) { // vize admiral oder mehr
            echo '<td>Aktion</td>';
        }
      ?>
    </tr>
        <?php
            $sql = 'SELECT name, galaxie, planet, handy, ';
            $sql .= 'messangerID, infotext, id ';
            $sql .= 'FROM `gn4accounts`';
            $sql .= 'where ticid="'.$Benutzer['ticid'].'" ORDER BY galaxie, planet';
            $color = 0;
            $SQL_Result = tic_mysql_query( $sql, $SQL_DBConn );
            for ( $i=0; $i<mysqli_num_rows($SQL_Result); $i++ ) {
                $color = !$color;
                $name = tic_mysql_result($SQL_Result, $i, 'name' );
                $gala =  tic_mysql_result($SQL_Result, $i, 'galaxie' );
                $gala = $gala .':'. tic_mysql_result($SQL_Result, $i, 'planet' );

                $telno = tic_mysql_result($SQL_Result, $i,   'handy' );
                $icq = tic_mysql_result($SQL_Result, $i,     'messangerID' );
                $infotext = tic_mysql_result($SQL_Result, $i, 'infotext' );
                $teleid = tic_mysql_result($SQL_Result, $i,  'id' ); // used later, to delete the record

                echo '<tr align="left" class="fieldnormal'.($color ? 'light' : 'dark').'">';
                echo '<td align="center">'.$gala.'</td>';
                echo '<td>'.$name.'</td>';
                echo '<td>'.$telno.'</td>';
                echo '<td>'.$icq.'</td>';
                echo '<td>'.$infotext.'</td>';
                if ($Benutzer['rang'] > $Rang_GC) { // vize admiral oder mehr

                    echo '<td align="center"><a href="./main.php?modul=telelist&amp;action=deltelentry&amp;teleid='.$teleid.'">löschen</a></td>';
                }
                echo '</tr>';

            }
        ?>
  </table>
