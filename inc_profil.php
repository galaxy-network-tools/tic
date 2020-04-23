<table align="center" width="70%">
	<tr>
		<td class="datatablehead">Spieler Informationen</td>
	</tr>
	<tr align="left">
		<td>
			<form action="./main.php?modul=profil" method="post">
				<input type="hidden" name="action" value="infoaendern" />
				<table width="100%" class="fieldnormallight">
					<tr>
						<td>Scantyp:</td>
						<td colspan="3">
							<select name="lstScanTyp">
<?php
	foreach ($ScanTyp as $ScanTypNummer => $ScanTypname) {
		echo "								<option value=\"".$ScanTypNummer."\"".(($ScanTypNummer == $Benutzer['scantyp'])?" selected":"").">".$ScanTypname."</option>";
	}
?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Scanverst&auml;rker:</td>
						<td>
							<input type="text" name="txtSVs" maxlength="10" size="10" value="<?=ZahlZuText($Benutzer['svs'])?>" />
						</td>
						<td>Scanblocker:</td>
						<td>
							<input type="text" name="txtSBs" maxlength="10" size="10" value="<?=ZahlZuText($Benutzer['sbs'])?>" />
						</td>
					</tr>
					<tr>
						<td>Punkte:</td>
						<td>
							<input type="text" name="txtPunkte" maxlength="20" size="10" value="<?=ZahlZuText($Benutzer['punkte'])?>" />
						</td>
					</tr>
					<tr>
						<td>Schiffe:</td>
						<td>
							<input type="text" name="txtSchiffe" maxlength="10" size="10" value="<?=ZahlZuText($Benutzer['schiffe'])?>" />
						</td>
						<td>Defensiveinheiten:</td>
						<td>
							<input type="text" name="txtDefensiv" maxlength="10" size="10" value="<?=ZahlZuText($Benutzer['defensiv'])?>" />
						</td>
					</tr>
					<tr>
						<td>Metallextraktoren:</td>
						<td>
							<input type="text" name="txtExen_m" maxlength="10" size="10" value="<?=ZahlZuText($Benutzer['exen_m'])?>" />
						</td>
						<td>Kristallextraktoren:</td>
						<td>
							<input type="text" name="txtExen_k" maxlength="10" size="10" value="<?=ZahlZuText($Benutzer['exen_k'])?>" />
						</td>
					</tr>
					<tr>
						<td align="center" colspan="4">
							<input type="submit" value="Informationen &auml;ndern" />
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr>
		<td class="datatablehead">Passwort &auml;ndern</td>
	</tr>
	<tr>
	  <td align="left">
		<form action="./main.php?modul=profil" method="post">
		  <input type="hidden" name="action" value="passwortaendern" />
		  <table class="fieldnormallight" width="100%">
			<tr>
			  <td>Neues Passwort:</td>
			  <td>
				<input type="password" name="txtChPasswort" maxlength="50" />
				</td>
			</tr>
			<tr>
			  <td>Wiederholen:</td>
			  <td>
				<input type="password" name="txtChPasswort_p" maxlength="50" />
				</td>
			</tr>
			<tr>
			  <td><br />
				</td>
			  <td>
				<input type="submit" value="Passwort &auml;ndern" />
				</td>
			</tr>
		  </table>
		</form>
		</td>
	</tr>
		<tr>
			<td class="datatablehead">Pers&ouml;nliche Daten</td>
		</tr>
		<tr>
		<td align="left">
			<?
				$sql = "select authnick, handy, messangerID, infotext from gn4accounts where id=".$Benutzer["id"].";";
				$SQL_Result = tic_mysql_query($sql, $SQL_DBConn);
				$pdaten = mysql_fetch_array($SQL_Result);
				//echo mysql_error()."<br />".$sql;
			?>
			<form action="./main.php?module=profil" method="post">
			<input type="hidden" name="action" value="personlich" />
			<table class="fieldnormallight" width="100%">
			<tr><td>Handy-Nummer:</td><td><input name="handy" value="<?=$pdaten[1]?>" /></td></tr>
			<tr><td>Messanger:</td><td><input name="icq" value="<?=$pdaten[2]?>" /></td><td>zB.: ICQ : 123 456 678</td></tr>
			<tr><td>Zusatzinfos:</td><td><input name="infotext" value="<?=$pdaten[3]?>" /></td><td>
	  Authnick:</td><td><input name="authnick" value="<?=$pdaten[0]?>" /></td></tr>
			<tr>
			  <td>Zeitformat:</td>
			  <td>
				<select name="lstZeitformat">
				  <?php
					foreach ($Zeitformat as $ZeitformatNummer => $Zeitformatname) {
							$zusatz = '';
							if ($ZeitformatNummer == $Benutzer["zeitformat"]) $zusatz = ' selected="selected"';
							echo '<option value="'.$ZeitformatNummer.'"'.$zusatz.'>'.$Zeitformatname.'</option>';
					}
				?>
				</select>
				</td>
			<td>Taktikscreen:</td>
			<td>
			<?php
					echo '<select name="ticscreen" size="1">';
					$freisel='';
					$sperrsel='';
					if ( $Benutzer['tcausw'] == 1 ) $freisel=' selected="selected"';
					else $sperrsel=' selected="selected"';

					echo '<option value="0"'.$sperrsel.'>Taktikscreen 1</option>';
					echo '<option value="1"'.$freisel.'>Taktikscreen 2</option>';
					echo '</select>';
			?>
			</td>
			</tr>
			<tr>
			<?php
			$selected='';
			if ( $Benutzer['help'] == 1 ){
				 $selected = 'checked="checked"';
				 }

			?>
			<td width="36%" height="38">Hilfe
			</td>
			<td width="5%" height="38"><input type="checkbox" name="check" <?php echo $selected; ?>/></td>
			</tr>
			<tr><td><br /></td><td><input type="submit" value="Pers&ouml;nliche Daten speichern" /></td></tr>
		</table>
	</form>

		</td>
	</tr>
	<tr>
	  <td class="datatablehead">Urlaubs-Modus</td>
	</tr>
	<tr>
	  <td align="left">
		<form action="./main.php?module=profil" method="post">
		  <input type="hidden" name="action" value="umod" />
		  <input type="hidden" name="UModID" value="<?=$Benutzer['id']?>" />
		  <table class="fieldnormallight" width="100%">
			<?php
			  if ($Benutzer['umod'] == '') {
				  echo '<tr>';
				   echo '  <td>Zeitraum des Urlaubs:</td><td><input type="text" name="txtUModZeit" value="'.date("d").'.'.date("m").'.'.date("Y").'-XX.XX.XXXX" maxlength="21" size="21" /> (Falls unbekannt einfach so lassen)</td>';
				  echo '</tr>';
				  echo '<tr>';
				  echo '  <td><br /></td><td><input type="submit" value="Urlaubs-Modus aktivieren" /></td>';
				  echo '</tr>';
			  } else {
				  echo '<tr>';
				  echo '  <td>Zeitraum des Urlaubs:</td><td><B>'.$Benutzer['umod'].'</B><input type="hidden" name="txtUModZeit" value="" /></td>';
				  echo '</tr>';
				  echo '<tr>';
				  echo '  <td><br /></td><td><input type="submit" value="Urlaubs-Modus deaktivieren" /></td>';
				  echo '</tr>';
			  }
		  ?>
		  </table>
		</form>
	  </td>
	</tr>
</table>
