<?php
/***************************************************************************
 *   Copyright (C) 2006 by                                                 *
 *      Pascal Gollor <pascal@gollor1.de> -- irc://irc.quakenet.org/Hugch  *
 *      Andreas Hemel <dai.shan@gmx.net>                                   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA              *
 ***************************************************************************/

?>

<table width="90%" align="center">
	<tr><td class="datatablehead">Scans aus dem IRC einf&uuml;gen(Clipboard)</td></tr>
	<tr><td bgcolor="#ccccec">
			<font color="#b22222">Bitte pro Scantyp nur einen Scan einf&uuml;gen!!!</font>
	</td></tr>
	<tr><td class="fieldnormallight">
		<form action="main.php?modul=ircpaste" method="post">
			<textarea cols="80" rows="20" name="irc_scan"><? if ($scan_debug == 1) { echo $_POST['irc_scan']; } ?></textarea><br />
			<!-- <input type="hidden" name="action" value="irc_paste"> -->
			<!-- <input type="checkbox" name="scan_debug">Debug<br /> -->
			<input type="submit" value="Speichern" />
		</form>
	</td></tr>
</table>

<?php

if ($scan_debug == 1) { echo '<table><tr><td align="left"><pre>'; }
if ($_POST['irc_scan'] != "") { scan_pruefen($_POST['irc_scan']); }
if ($scan_debug == 1) { echo '</pre></td></tr></table>'; }

?>
