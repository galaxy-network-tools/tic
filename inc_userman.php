<center>
    <form action="main.php?modul=userman" method="post">
      <input type="hidden" name="action" value="accounterstellen" />
      <table>
        <tr>
          <td colspan="2" class="datatablehead" align="center">Neuen Benutzer anlegen</td>
        </tr>
        <tr class="fieldnormallight">
          <td>Name:</td>
          <td><input type="text" name="txtAccName" maxlength="50" /></td>
        </tr>
        <tr class="fieldnormaldark">
          <td>Koordinaten:</td>
          <td>
            <?php
                if ($Benutzer['rang'] != $Rang_GC)
                    echo '<input type="text" name="txtAccGalaxie" maxlength="4" size="4" />';
                else
                    echo '<b>'.$Benutzer['galaxie'].'</b>';
            ?>
            <b>:</b>
            <input type="text" name="txtAccPlanet" maxlength="2" size="2" /></td>
        </tr>
        <tr class="fieldnormallight">
          <td>Passwort:</td>
          <td><input type="password" name="txtAccPasswort" maxlength="50" /></td>
        </tr>
        <tr class="fieldnormaldark">
          <td>Allianz:</td>
           <td>
            <?php

                if ($Benutzer['rang'] >= $Rang_Techniker) {
                    echo '<select name="lstAllianz">';
                    foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
                        $zusatz = '';
                        /*
                        if ($AllianzNummer == $Benutzer['allianz'])
                            $zusatz = ' SELECTED';
                        */
                        echo '<option value="'.$AllianzNummer.'"'.$zusatz.'>'.$AllianzTag[$AllianzNummer].' '.$AllianzNummerName.'</option>';
                    }
                    echo '</select>';
                } else {
                    echo '<b>'.$AllianzTag[$Benutzer['allianz']].' '.$AllianzName[$Benutzer['allianz']].'</b>';
                }
            ?>
            </td>
        </tr>
        <tr class="fieldnormaldark">
          <td>Rang:</td>
          <td>
            <select name="lstRang">
              <?php
                  foreach ($RangName as $RangNummer => $RangNummerName) {
                      if ($RangNummer <= $Benutzer['rang']) echo '<option value="'.$RangNummer.'">'.($RangNummer + 1).'. '.$RangNummerName.'</option>';
                  }
              ?>
            </select>
            </td>
            </tr>
            <tr class="datatablefoot">
             <td colspan="2" align="center"><input type="submit" value="Erstellen" /></td>
            </tr>
        </table>
    </form>

<?php

    echo '<table>';

    echo '<tr class="datatablehead" align="center"><td>Benutzer Verwaltung</td></tr>';
    $sql = "SELECT gn4allianzen.id, gn4allianzen.tag, gn4meta.name as meta FROM gn4allianzen LEFT JOIN gn4meta ON(gn4allianzen.ticid = gn4meta.id) ORDER BY gn4allianzen.tag;";

    $SQL_result = tic_mysql_query( $sql ) or print tic_mysql_error();
    $allianzahl = mysql_num_rows( $SQL_result );
    echo '<tr><td class="fieldnormallight"><table cellspacing="3">';
    if ( $allianzahl > 0 ) {
        for ($n = 0; $n < $allianzahl; $n++) {
            $allid = mysql_result( $SQL_result, $n, 'id');
            echo '<tr>';
            echo '<td>'.mysql_result( $SQL_result, $n, 'meta').'</td>';
            echo '<td>'.mysql_result( $SQL_result, $n, 'tag').'</td>';
            $sql2 = "SELECT DISTINCT(galaxie) FROM gn4accounts WHERE allianz='".$allid."' ORDER BY galaxie DESC";
            $SQL_result2 = tic_mysql_query($sql2, $SQL_DBConn);
            $galanzahl = mysql_num_rows( $SQL_result2);
            $galanum = mysql_num_rows( $SQL_result2);
            if ( $galanzahl != '' ) {
                for ($p = 0; $p < $galanzahl; $p++) {
                    $SQL_result2 = tic_mysql_query($sql2, $SQL_DBConn);
                    for ($i = 0;$i < $galanum; $i++) {
                          $gala = mysql_fetch_array($SQL_result2, MYSQL_NUM);
                    }
                    $galanum = $galanum - 1;
                    echo '<td>';
                    echo '<a href="./main.php?modul=userman&amp;selgala='.$gala[0].'">['.$gala[0].']</a>';
                    echo '</td>';
                }
            }

            echo '</tr>';
        }
    }

    echo '</table></td></tr></table>';

    if ( isset( $_GET['selgala'] ) ){

        echo '<br /><table>';
        echo '<tr class="datatablehead" align="center"><td colspan="15">Galaxie '.$_GET['selgala'].'</td></tr>';
        echo '<tr style="font-weight:bold;" class="fieldnormaldark" align="center">';
        echo '<td>';
        echo 'Planet';
        echo '</td>';
        echo '<td>';
        echo 'Name';
        echo '</td>';
        echo '<td>';
        echo 'Rang';
        echo '</td>';
        echo '<td>';
        echo 'Allianz';
        echo '</td>';
        echo '<td>';
        echo 'UMode';
        echo '</td>';
        echo '<td>';
        echo 'LastLogin';
        echo '</td>';
        echo '<td>';
		echo 'Status';
        echo '</td>';
        echo '<td colspan="8">';
        echo 'Bearbeiten';
        echo '</td>';
        echo '</tr>';
        $sql = 'select * from gn4accounts where galaxie='.$_GET['selgala'].' order by planet';
        $SQL_result = tic_mysql_query( $sql, $SQL_DBConn);
		$color = 0;
		while($urow = mysql_fetch_assoc($SQL_result)) {
			$color = !$color;
			echo '<tr style="font-size:8pt;" class="fieldnormal'.($color ? 'light' : 'dark').'">';

			if ($urow['spy'] == 1)
			{
				$status = '<td style="font-color=#cc0000;">Gesperrt';
			} else {
				if($urow['versuche'] >= 3 && $urow['ip'] != "")
				    $status = '<td style="font-color=#cc0000;">IP '.$urow['ip'].' gesperrt';
                else
				    $status = '<td style="font-color=#00cc00;">Entsperrt';
			}

			echo '<td>'.$urow['planet'].'</td>';
			echo '<td>'.$urow['name'].'</td>';
			echo '<td>'.$RangName[$urow['rang']].'</td>';
			echo '<td>'.$AllianzTag[$urow['allianz']].'</td>';
			echo '<td>'.$urow['umod'].'</td>';

			echo '<td style="text-align:center;\">';
			echo ($urow['lastlogin'] ? strftime("%d.%m.%Y %H:%M", $urow['lastlogin']) : "-nie-");
			echo '</td>';

			echo $status;
			echo '</td>';

			// change gala planet pw alliid umode
			echo '<td>';
			if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
				echo  '<a href="./main.php?modul=useredit&amp;change=umode&amp;uid='.$urow['id'].'">UMode</a></td><td><a href="./main.php?modul=useredit&amp;change=pw&amp;uid='.$urow['id'].'">NeuesPW</a>';
				echo '</td><td>';
				echo  '<a href="./main.php?modul=useredit&amp;change=koords&amp;uid='.$urow['id'].'">Koords</a>';
			} else {
				echo 'UMode</td>
						 <td>NeuesPW</td>
						 <td>Koords';
			}
			echo '</td>';

			echo '<td>';
			if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
				echo  '<a href="./main.php?modul=useredit&amp;change=name&amp;uid='.$urow['id'].'">Name</a>';
			} else {
				echo  'Name';
			}

			echo '</td>';

			echo '<td>';
			if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
				echo  '<a href="./main.php?modul=useredit&amp;change=allianz&amp;uid='.$urow['id'].'">Alli</a>';
			} else {
				echo  'Alli';
			}

			echo '</td>';

			echo '<td>';
			if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
				echo  '<a href="./main.php?modul=useredit&amp;change=rang&amp;uid='.$urow['id'].'">Rang</a>';
			} else {
				echo  'Rang';
			}

			echo '<td>';
			if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
			if ( $urow['spy'] == 1 || $urow['versuche'] >= 3 && $urow['ip'] != ""){
			echo  '<a href="./main.php?modul=useredit&amp;change=spy&amp;uid='.$urow['id'].'">Entsperren</a>';
			} else {
			echo  '<a href="./main.php?modul=useredit&amp;change=spy&amp;uid='.$urow['id'].'">Sperren</a>';
			}
			} else {
			echo  'Keine Rechte';
			}

			echo '</td>';


				echo '<td>';
        if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
				echo '<a href="main.php?modul=userman&amp;selgala='.$_GET['selgala'].'&amp;action=accloeschen&amp;id='.$urow['id'].'">L&ouml;schen</a>';
				echo '</td>';
        }else {
				echo  'L&ouml;schen</td>';
			}




			echo '</tr>';

		}
		echo '</table>';
    }

?>
</center>
