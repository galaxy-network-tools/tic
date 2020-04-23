<CENTER>
<TABLE WIDTH=70%>
<?php
if($Benutzer['rang']!='5')die('Keine Rechte, Kontaktieren Sie ihren nächsten Admin');
                $SQL_Result=tic_mysql_query('SELECT name, tag, info_bnds, info_naps, info_inoffizielle_naps, info_kriege, code FROM `gn4allianzen` WHERE id="'.$allid.'";', $SQL_DBConn);
                if (mysql_num_rows($SQL_Result) == 0) die('Alli ID nicht vorhanden');
                $tag = mysql_result($SQL_Result, 0, 'tag');
		            $name = mysql_result($SQL_Result, 0, 'name');
		            $bnd = mysql_result($SQL_Result, 0, 'info_bnds');
		            $nap = mysql_result($SQL_Result, 0, 'info_naps');
                $innap = mysql_result($SQL_Result, 0, 'info_inoffizielle_naps');
		            $krieg = mysql_result($SQL_Result, 0, 'info_kriege');
		            $code = mysql_result($SQL_Result, 0, 'code');
                echo '<TR>';
                echo '<TD><BR></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '  <TD BGCOLOR=#333333><FONT COLOR=#FFFFFF><B><font>Allianzeinstellungen</font></B></FONT></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD>';
                echo '<FORM ACTION="./main.php" METHOD="POST">';
                echo '<INPUT TYPE="hidden" NAME="modul" VALUE="ally_bear">';
                echo '<INPUT TYPE="hidden" NAME="action" VALUE="ally_bear">';
                echo '<INPUT TYPE="hidden" NAME="allid" VALUE="'.$allid.'">';
                echo '<TABLE>';
                echo '<TR>';
                echo '<TD><font>*Name:</font></TD><TD><INPUT TYPE="text" NAME="txtNick" VALUE="'.$name.'"  SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>*Tag:</font></TD><TD><INPUT TYPE="text" NAME="txtTag" VALUE="'.$tag.'" SIZE="10"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Allianzcode:</font></TD>';
                echo '<TD>';
                echo '<SELECT NAME="lstCode" SIZE=SMALL>';
                foreach ($AllianzCode as $CodeNummer => $CodeNummerName) {
                    if ($CodeNummer != $code)
                        echo '<OPTION VALUE="'.$CodeNummer.'">'.$CodeNummerName.'</OPTION>';
                    else
                        echo '<OPTION VALUE="'.$CodeNummer.'" SELECTED>'.$CodeNummerName.'</OPTION>';
                }
                echo '</SELECT>';
                echo '</TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Bündnisse:</font></TD><TD><INPUT TYPE="text" NAME="txtBNDs" VALUE="'.$bnd.'" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Offizielle NAPs:</font></TD><TD><INPUT TYPE="text" NAME="txtNAPs" VALUE="'.$nap.'" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Inoffizielle NAPs:</font></TD><TD><INPUT TYPE="text" NAME="txtInoffizielleNAPs" VALUE="'.$innap.'" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Kriege:</font></TD><TD><INPUT TYPE="text" NAME="txtKriege" VALUE="'.$krieg.'" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><BR></TD><TD><INPUT TYPE="submit" VALUE="Speichern"></TD>';
                echo '</TR>';
                echo '</TABLE>';
                echo '</FORM>';
                echo '</TD>';
                echo '</TR>';
                echo 'Die Felder mit dem * Makiert sind, sind pflicht Felder!';
?>
</table>
</CENTER>