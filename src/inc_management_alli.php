<center>
    <table width="70%">
    <tr><td>
        <?php
      		if($Benutzer['rang']>='2'){
      			if(isset($_POST['selectid']) && !isset($_POST['Allidelet'])) $selectid=$_POST['selectid'];
      			else $selectid=$Benutzer['allianz'];
      			$SQL_Result=tic_mysql_query('Select name,ticid,tag,info_bnds,info_naps,info_inoffizielle_naps,info_kriege FROM `gn4allianzen` where id="'.$selectid.'" limit 1; ',__FILE__,__LINE__);
      			$alliname=mysql_result($SQL_Result,'0','name');
      			$allitag=mysql_result($SQL_Result,'0','tag');
      			$allibnds=mysql_result($SQL_Result,'0','info_bnds');
  				$allinaps=mysql_result($SQL_Result,'0','info_naps');
				$alliinnaps=mysql_result($SQL_Result,'0','info_inoffizielle_naps');
				$allikriege=mysql_result($SQL_Result,'0','info_kriege');
      			if($Benutzer['rang']=='5'){
      				$alliticid=mysql_result($SQL_Result,'0','ticid');
      				$metaselect='<td align="center" class="fieldnormallight"><Select name="selectMeta">';
      				$SQL_Result=tic_mysql_query('select id,name FROM `gn4meta`;',__FILE__,__LINE__);
					$SQL_Num=mysql_num_rows($SQL_Result);
      				for ($x='0';$x<$SQL_Num;$x++){
      				$meta=mysql_result($SQL_Result,$x,'name');
					$idmeta=mysql_result($SQL_Result,$x,'id');
					$selected='';
					if($idmeta==$alliticid) $selected='selected';
					$metaselect .="<option value=\"".$idmeta."\" ".$selected.">".$meta."</option>\n";
      				}
      				$metaselect .='</select></td>';
      			}
				echo '<form method="post" action="./main.php?modul=management_alli">
      			<input type="hidden" name="action" value="management_alli" />
      			<input type="hidden" name="selectid" value="'.$selectid.'" />
      			<table width="100%">
      			<tr class="datatablehead" align="center"><td colspan="4">Allianz Einstellungen</td></tr>
      				 <tr><td class="fieldnormallight">Name:</td><td class="fieldnormallight">
      				 <input name="Alliname" value="'.$alliname.'" size="30" /></td><td class="fieldnormallight">Tag:
      				 <input name="Allitag" value="'.$allitag.'" size="8" /></td>'.$metaselect.'</tr>
      				 <tr class="fieldnormaldark"><td>BNDs:</td><td colspan="3"><input name="Allibnds"  style="width:100%;" value="'.$allibnds.'" /></td></tr>
      				 <tr><td class="fieldnormallight">NAPs:</td><td colspan="3" class="fieldnormallight"><input name="Allinaps" style="width:100%;" value="'.$allinaps.'" /></td></tr>
      				 <tr class="fieldnormaldark"><td>Inoffiziel.NAPs:</td><td colspan="3"><input name="Alliinnaps" value="'.$alliinnaps.'"  style="width:100%;" /></td></tr>
      				 <tr><td class="fieldnormallight">Kriege:</td><td colspan="3" class="fieldnormallight"><input name="Allikriege"  value="'.$allikriege.'" style="width:100%;" /></td></tr><tr class="fieldnormaldark">';
				if($Benutzer['rang']=='5'){
      				echo '<td align="center"><input type="submit" name="Allidelete" value="L&ouml;schen" /></td>';
      			}
      				 echo'<td align="center" colspan="2"><input type="submit" name="Allispeichern" value="Speichern" /></td>';
      				if($Benutzer['rang']=='5'){
      				echo '<td align="center"><input type="submit" name="Allineu" value="Neue Allianz" /></td>';
      			}
      				 echo'</tr>
      				 </table>
      				 </form>';
      		echo '<br/>
      		<br/>';
            $selectMeta = $Benutzer['ticid'];
      		if(isset($_POST['selectMeta'])&&$Benutzer['rang']==RANG_STECHNIKER){
      				$selectMeta = $_POST['selectMeta'];
            }
      		if ($Benutzer['rang']==RANG_STECHNIKER) {
      			echo '<form action="./main.php?modul=management_alli" method="post">
      			Meta:
      			<select onchange="this.form.submit();" name="selectMeta">';
				for ($x='0';$x<$SQL_Num;$x++){
					$meta=mysql_result($SQL_Result,$x,'name');
					$idmeta=mysql_result($SQL_Result,$x,'id');
					$selected='';
					if($idmeta==$selectMeta) $selected='selected';
					echo "<option value=\"".$idmeta."\" ".$selected.">".$meta."</option>\n";
		}
		echo '</select></form><br/>';
    $delet='<input type="submit" name="Allidelet" value="L&ouml;schen" />';
      		}
      		if ($Benutzer['rang']>='4') {
				$SQL_Result=tic_mysql_query('Select name,tag,id FROM `gn4allianzen` where ticid="'.$selectMeta.'";',__FILE__,__LINE__);
      			echo '<table width="432">';
      			$SQL_Num=mysql_num_rows($SQL_Result);
      			for ($x='0';$x<$SQL_Num;$x++){
      				if ( $x%2 == 0 ) {
						$colour='class="fieldnormallight"';
					} else {
						$colour='class="fieldnormaldark"';
					}
      				$alliname=mysql_result($SQL_Result,$x,'name');
      				$allitag=mysql_result($SQL_Result,$x,'tag');
      				$alliid=mysql_result($SQL_Result,$x,'id');
      				echo '<tr '.$colour.'><td width="55%">'.$alliname.'('.$allitag.')</td><td width="45%"><form method="post" action="./main.php?modul=management_alli"><input type="hidden" name="action" value="management_alli" /><input type="hidden" name="selectid" value="'.$alliid.'" /><input type="submit" name="aendern" value="Bearbeiten" /> '.$delet.'</form></td></tr>';

      			}
      				echo'</table>';
      		}
      		}
		?>
	</td></tr>
	</table>
</center>
