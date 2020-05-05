<CENTER>
<TABLE WIDTH=70%>
<?php
if($Benutzer['rang']!='5')die('Keine Rechte um eine neue Ally an zulegen Kontaktieren Sie ihren nächsten Admin');
                echo '<TR>';
                echo '<TD><BR></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '  <TD BGCOLOR=#333333><FONT COLOR=#FFFFFF><B><font>Allianzeinstellungen</font></B></FONT></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD>';
                echo '<FORM ACTION="./main.php" METHOD="POST">';
                echo '<INPUT TYPE="hidden" NAME="modul" VALUE="ally_add">';
                echo '<INPUT TYPE="hidden" NAME="action" VALUE="ally_add">';
                echo '<TABLE>';
                echo '<TR>';
                echo '<TD><font>*Name:</font></TD><TD><INPUT TYPE="text" NAME="txtNick"  SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>*Tag:</font></TD><TD><INPUT TYPE="text" NAME="txtTag" SIZE="10"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Bündnisse:</font></TD><TD><INPUT TYPE="text" NAME="txtBNDs" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Offizielle NAPs:</font></TD><TD><INPUT TYPE="text" NAME="txtNAPs" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Inoffizielle NAPs:</font></TD><TD><INPUT TYPE="text" NAME="txtInoffizielleNAPs" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Kriege:</font></TD><TD><INPUT TYPE="text" NAME="txtKriege" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><BR></TD><TD><INPUT TYPE="submit" VALUE="Allianz Erstellen"></TD>';
                echo '</TR>';
                echo '</TABLE>';
                echo '</FORM>';
                echo '</TD>';
                echo '</TR>';
                echo 'Die Felder mit dem * Makiert sind, sind pflicht Felder!';
?>
</table>
</CENTER>