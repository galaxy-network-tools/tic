<CENTER>
 	<TABLE WIDTH=70%>
		<TR><TD BGCOLOR=#333333>
			<font color="#FFFFFF"><B>Neues Passwort</B></font><br>
		</TD></TR>
		<TR><TD>
			<P CLASS="hell">
				Sie m&uuml;ssen sich ein neues, <b>anderes</b> Passwort geben, um das TIC benutzen zu k&ouml;nnen.<br>
<?php
	if ($action == 'passwortaendern') {
		echo "				<font color=\"#FF0000\"><B>Fehlerhaftes Passwort - bitte erneut versuchen!</B></font><br>\n";
	}
?>
			        <FORM ACTION="./main.php" METHOD="POST">
					<INPUT TYPE="hidden" NAME="modul" VALUE="profil" />
					<INPUT TYPE="hidden" NAME="action" VALUE="passwortaendern" />
					<TABLE>
						<TR>
							<TD VALIGN="top">neues Passwort (2x):</TD>
							<TD><INPUT TYPE="password" NAME="txtChPasswort" MAXLENGTH=50 /><br><INPUT TYPE="password" NAME="txtChPasswort_p" MAXLENGTH=50 /></TD>
						</TR>
						<TR>
							<TD></TD>
							<TD><INPUT TYPE="submit" VALUE="Passwort &auml;ndern" /></TD>
            					</TR>
					</TABLE>
				</FORM>
			</P>
		</TD></TR>
	</TABLE>
</CENTER>