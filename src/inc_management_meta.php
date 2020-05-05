<center>
<?php

if($Benutzer['rang']>= RANG_TECHNIKER){
    if(isset($metaerror))
	   echo "<div class=\"error\">".$metaerror."</div>";
echo'<form action="./main.php?modul=management_meta" method="post">
<input type="hidden" name="action" value="management_meta" />
<table cellpadding="3">

	<tr class="datatablehead" align="center"><td colspan="4">Meta-Managment</td></tr>
<tr>
	<td class="fieldnormallight" colspan="4">Meta: <select onchange="this.form.submit();" name="selectMeta">';
    $query='';
    if($Benutzer['rang']== RANG_TECHNIKER) $query='where id="'.$Benutzer['ticid'].'"';
		$SQL_Result=tic_mysql_query('select id,name,sysmsg,duell,wars,naps,bnds FROM `gn4meta` '.$query.' order by id asc;',__FILE__,__LINE__);
		$SQL_Num=mysql_num_rows($SQL_Result);
        if(isset($newmetaid)) $selectMeta=$newmetaid;
        else if(isset($_POST['selectMeta'])) $selectMeta=$_POST['selectMeta'];
        if(!isset($selectMeta) || $selectMeta < 1) $selectMeta=$Benutzer['ticid'];

        for ($x=0;$x<$SQL_Num;$x++){
			$meta=mysql_result($SQL_Result,$x,'name');
			$selected='';
			if(mysql_result($SQL_Result,$x,'id') == $selectMeta)
            {
                $selected='selected';
                $metaid=mysql_result($SQL_Result,$x,'id');
                $metaname=mysql_result($SQL_Result,$x,'name');
                $duell=mysql_result($SQL_Result,$x,'duell');
                $sysmsg=mysql_result($SQL_Result,$x,'sysmsg');
                $wars=mysql_result($SQL_Result,$x,'wars');
                $bnds=mysql_result($SQL_Result,$x,'bnds');
                $naps=mysql_result($SQL_Result,$x,'naps');
            }
			echo "<option value=\"".mysql_result($SQL_Result,$x,'id')."\" ".$selected.">".$meta."</option>\n";
		}

        if(!isset($metaname))
        {
                $metaid=mysql_result($SQL_Result,0,'id');
                $metaname=mysql_result($SQL_Result,0,'name');
                $duell=mysql_result($SQL_Result,0,'duell');
                $sysmsg=mysql_result($SQL_Result,0,'sysmsg');
                $wars=mysql_result($SQL_Result,0,'wars');
                $bnds=mysql_result($SQL_Result,0,'bnds');
                $naps=mysql_result($SQL_Result,0,'naps');
        }
        if($Benutzer['rang']==RANG_TECHNIKER) $schaltflaeche='<td align="center">&nbsp;</td><td colspan="2" align="center"><input name="metaspeichern" value="Speichern" type="submit" /></td>
	  <td align="center">&nbsp;</td>';
        else $schaltflaeche='<td align="center"><input name="metadelet" value="L&ouml;schen" type="submit" /></td><td colspan="2" align="center"><input name="metaspeichern" value="Speichern" type="submit" /></td>
	  <td align="center"><input align="right" type="submit" name="newmeta" value="Neuer Meta" /></td>';

		echo '</select><noscript> <input type="submit" value="Ausw&auml;hlen" /></noscript></td></tr>	<tr class="fieldnormaldark"><td>Name:</td>
	<td align="left"><input size="16" type="text" name="meta" value="'.$metaname.'" /></td>
	<td >Duell:</td>

	<td>
	    <input name="duell" value="'.$duell.'"  type="text" /> <input type="hidden" name="metaid" value="'.$metaid.'" /></td>
	</tr>
	<tr class="fieldnormallight"><td rowspan="3">Systemnachricht:</td><td rowspan="3" class="fieldnormallight"><textarea name="sysmsg" cols="50" rows="5">'.$sysmsg.'</textarea></td>
	  <td>Bnd:</td>
	  <td><input name="bnds" value="'.$bnds.'" /></td>
	</tr>

	<tr class="fieldnormaldark">
	  <td>Naps:</td>
	  <td><input name="naps" value="'.$naps.'" /></td>
	  </tr>
	  <tr class="fieldnormallight">
	  <td>Wars:</td>
	  <td><input name="wars" value="'.$wars.'" /></td>
	  </tr>

	<tr class="fieldnormaldark">'.$schaltflaeche.'
	</tr></table>
	</form>';

}else {
	echo 'Zu wenig Rechte !';
}
?>
</center>