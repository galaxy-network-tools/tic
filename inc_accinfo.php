<!-- START: inc_accountinfo -->
<table width="100%" cellspacing="6" border="0" cellpadding="1" style="border:1px #000000 solid;background-color:#ffffff;">
	<colgroup>
		<col width="85%" />
		<col width="15%" />
	</colgroup>
	<tr>
		<td valign="middle" rowspan="2" align="center">
<?php
	include( "./inc_summary.php" );
?>
		</td>
		<td align="right" valign="top" style="white-space:nowrap;">
			<font size="-1">
				[ <?=$AllianzTag[$Benutzer['allianz']]?> ] <?=$Benutzer['name']?><br />
				<img src="<?=$RangImage[$Benutzer['rang']]?>" width="20" height="20" border="0" alt="<?=$RangName[$Benutzer['rang']]?>" title="<?=$RangName[$Benutzer['rang']]?>" align="middle" /> <?=$Benutzer['galaxie']?>:<?=$Benutzer['planet']?>
<?
	if ($Benutzer['umod'] != '') {
		echo "				<br /><font size=\"-2\" color=\"#".$htmlstyle['dunkel_blau']."\"><b>".$Benutzer['umod']."</b></font>\n";
	}
?>
			</font>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top">
			<div id="ticktime">
				Serverzeit: <span id="Uhr"><?=date("H:i:s")?></span><br />
				Letzter Tick: <?=$lasttick."\n"?>
			</div>
		</td>
	</tr>
</table>
<script type="text/javascript">
<!--
	var Uhr = document.getElementById('Uhr');
	var TimeServer = new Date("<?=date("M, d Y H:i:s")?>");
	var TimeLocal = new Date();
	var offset = TimeServer.getTime() - TimeLocal.getTime();

	function serverzeit_anzeigen() {
		var jetzt = new Date();
		jetzt.setTime(jetzt.getTime() + offset);
		var Std = jetzt.getHours();
		var Min = jetzt.getMinutes();
		var Sec = jetzt.getSeconds();
		var StdAusgabe  = ((Std < 10) ? "0" + Std : Std);
		var MinAusgabe  = ((Min < 10) ? "0" + Min : Min);
		var SecAusgabe  = ((Sec < 10) ? "0" + Sec : Sec);
		Uhr.innerHTML = StdAusgabe + ':' + MinAusgabe + ':' + SecAusgabe;
		window.setTimeout('serverzeit_anzeigen();', 999);
	}
	window.onload = serverzeit_anzeigen;
//-->
</script>
<?
	if (@$MetaInfo['sysmsg'] !="") {
		echo "<br /><div class=\"sysmessage\">\n";
		echo nl2br(htmlentities($MetaInfo['sysmsg'] ))."\n";
		echo "</div>\n";
	}
?>
<br />
<!-- ENDE: inc_accountinfo -->
