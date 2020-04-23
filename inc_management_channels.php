<center>
<?php

if(isset($metaerror))
   echo "<div class=\"error\">".$metaerror."</div>";

$Result_Chans = tic_mysql_query('SELECT * from gn4channels;',__FILE__,__LINE__);
$Result_Allies = tic_mysql_query('SELECT * from gn4allianzen;',__FILE__,__LINE__);
$SQL_Num=mysql_num_rows($Result_Chans);
if (isset($_REQUEST['selectChan'])) {
	$selChan = $_REQUEST['selectChan'];
} else {
	$selChan = 1;
};
?>
<form action="./main.php?modul=management_channels" method="post">
	<input type="hidden" name="action" value="management_channels" />
	<table cellpadding="3">
		<tr class="datatablehead" align="center"><td colspan="4">Channel-Management</td></tr>
		<tr>
			<td align="center" class="fieldnormallight" colspan="4">Channel: 
				<select onchange="this.form.submit();" name="selectChan">
<?
$i = 0;
$joinCommand = "";
$pass = "";
$ally = 0;
$voicefor = 0;
$channame = "";
$accessfor = 0;
$opfor = 2;
$invitefor = 2;
$opcontrol = 1;
$answer = 0;
while ($row = mysql_fetch_object($Result_Chans)) {
	printf("<option %s value=\"%d\">%s</option>\n", $row->id == $selChan ? "selected" : "", $row->id, $row->channame);
	$channel[$row->id] = $i++;
	if ($row->id == $selChan) {
		$joinCommand = $row->joincommand;
		$pass = $row->pass;
		$ally = $row->ally;
		$voicefor = $row->voicerang;
		$channame = $row->channame;
		$accessfor = $row->accessrang;
		$opfor = $row->oprang;
		$invitefor = $row->inviterang;
		$opcontrol = $row->opcontrol;
		$answer = $row -> answer;
	};
};
mysql_data_seek($Result_Chans, 0);
?>
				</select>
				<noscript> <input type="submit" value="Ausw&auml;hlen" /></noscript>
			</td>
		</tr>
		<tr class="fieldnormaldark">
			<td>Name: </td>
			<td><input type="text" name="channame" value="<?  print($channame); ?>" /> </td>
		</tr>
		<tr class="fieldnormallight">
			<td>Join-Kommando:</td>
			<td><select name="joincommand">
<?
foreach (array("join", "privmsg L invite", "privmsg Q invite") as $jn => $jc) {
	printf("<option %s value=\"%d\">%s</option>\n", $joinCommand == $jc ? "selected" : "", $jn, $jc);
};

?>
			</select></td>
		</tr>
		<tr class="fieldnormaldark">
			<td>Passwort:</td>
			<td><input type="text" name="pass" value="<?  print($pass); ?>" /> </td>
		</tr>
		<tr class="fieldnormallight">
			<td>Allianz:</td>
			<td>
				<select name="ally">
					<option value="0">--keine--</option>
<?
while ($allyrow = mysql_fetch_object($Result_Allies)) {
	printf("<option %s value=\"%d\">%s</option>\n", $ally == $allyrow->id ? "selected" : "", $allyrow->id, $allyrow->tag);
};
?>
				</select>
			</td>
		</tr>
		<tr class="fieldnormaldark">
			<td>Zugang ab Rang:</td>
			<td><select name="accessfor">
<?
printf("<option %s value=\"%d\">%s</option>\n", $accessfor == -1 ? "selected" : "", -1, "unbeschr&auml;nkt");
foreach ($RangName as $afrang => $afname) {
	printf("<option %s value=\"%d\">%s</option>\n", $accessfor == $afrang ? "selected" : "", $afrang, $afname);
};

?>
			</select></td>
		</tr>
		<tr class="fieldnormallight">
			<td>Invite ab Rang:</td>
			<td><select name="invitefor">
<?
printf("<option %s value=\"%d\">%s</option>\n", $invitefor == -1 ? "selected" : "", -1, "gar nicht");
foreach ($RangName as $ifrang => $ifname) {
	printf("<option %s value=\"%d\">%s</option>\n", $invitefor == $ifrang ? "selected" : "", $ifrang, $ifname);
};

?>
			</select></td>
		</tr>
		<tr class="fieldnormaldark">
			<td>Op vergeben ab Rang:</td>
			<td><select name="opfor">
<?
printf("<option %s value=\"%d\">%s</option>\n", $opfor == -1 ? "selected" : "", -1, "gar nicht");
foreach ($RangName as $ofrang => $ofname) {
	printf("<option %s value=\"%d\">%s</option>\n", $opfor == $ofrang ? "selected" : "", $ofrang, $ofname);
};

?>
			</select></td>
		</tr>
			
		<tr class="fieldnormallight">
			<td>Voice vergeben ab Rang:</td>
			<td><select name="voicefor">
<?
printf("<option %s value=\"%d\">%s</option>\n", $voicefor == -1 ? "selected" : "", -1, "gar nicht");
foreach ($RangName as $vfrang => $vfname) {
	printf("<option %s value=\"%d\">%s</option>\n", $voicefor == $vfrang ? "selected" : "", $vfrang, $vfname);
};

?>
			</select></td>
		</tr>
			
		<tr class="fieldnormaldark">
			<td>OP-Control (Bitch Mode):</td>
			<td><input type="checkbox" <? if ($opcontrol == 1) print("checked "); ?>name="opcontrol" value="1" /></td>
		</tr>
		<tr class="fieldnormallight">
			<td>Auf Kommandos antworten:</td>
			<td><input type="checkbox" <? if ($answer == 1) print("checked "); ?>name="answer" value="1" /></td>
		</tr>
		<tr class="fieldnormaldark">
			<td colspan="2">
				<input name="chanspeichern" value="Channel speichern" type="submit" />
				<input name="newchannel" value="Neuen Channel anlegen" type="submit" />
				<input name="delchannel" value="Channel l&ouml;schen" type="submit" />
			</td>
		</tr>
	</table>
</form>
		
</center>
