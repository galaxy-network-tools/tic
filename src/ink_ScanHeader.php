<?php
// File: ink_ScanHeader.php
// by Bytehoppers from CCBLOCK

echo '<CENTER>
	<B>Manuelle Scan-Datenbank Auflistung!</B><BR>
	<BR>
	<A HREF="./main.php?modul=scans">Scan in die Datenbank hinzuf&uuml;gen</A><BR>
	<BR>

	<TABLE>
		<TR>
				<td bgcolor="#6490BB" class="menutop"><B>
      	<A HREF="./main.php?modul=scanlist2'.$SSQL.'&order=rg'.$DESC2.'">
        ZielSektor</a></B></FONT></TD>
				<td bgcolor="#6490BB" class="menutop"><B>
      	<A HREF="./main.php?modul=scanlist2'.$SSQL.'&order=zeit'.$DESC2.'">
        ScanZeit</a></B></FONT></TD>
				<td bgcolor="#6490BB" class="menutop"><B>-Typs</B></FONT></TD>
				<td bgcolor="#6490BB" class="menutop"><B>G%</B></FONT></TD>
				<td bgcolor="#6490BB" class="menutop"><B>
      	<A HREF="./main.php?modul=scanlist2'.$SSQL.'&order=pts'.$DESC2.'">
        PKT</a></B></FONT></TD>
				<td bgcolor="#6490BB" class="menutop"><B>
      	<A HREF="./main.php?modul=scanlist2'.$SSQL.'&order=s'.$DESC2.'">
        Schiffe</a></B></FONT></TD>
				<td bgcolor="#6490BB" class="menutop"><B>
      	<A HREF="./main.php?modul=scanlist2'.$SSQL.'&order=d'.$DESC2.'">
        Def-Einheiten</a></B></FONT></TD>
				<td bgcolor="#6490BB" class="menutop"><B>
      	<A HREF="./main.php?modul=scanlist2'.$SSQL.'&order=me'.$DESC2.'">
        Metall Ex</a></B></FONT></TD>
				<td bgcolor="#6490BB" class="menutop"><B>
      	<A HREF="./main.php?modul=scanlist2'.$SSQL.'&order=ke'.$DESC2.'">
        Kristall Ex</a></B></FONT></TD>
				<td bgcolor="#6490BB" class="menutop"><B>
        Ex-Sum</B></FONT></TD>
		</TR>';
?>

