<?php
if($Benutzer['rang'] >= RANG_VIZEADMIRAL){
	if (isset($_POST['Allispeichern']) && isset($_POST['selectid'])) {
		if($Benutzer['rang']== RANG_VIZEADMIRAL || $Benutzer['rang']== RANG_ADMIRAL) $_POST['selectid']=$Benutzer['allianz'];
        $query1 = $query = "";
		if($Benutzer['rang'] < RANG_STECHNIKER){ $query=' and ticid="'.$Benutzer['ticid'].'"';
		}else{ $query1=',ticid="'.$_POST['selectMeta'].'"';}
		tic_mysql_query('update `gn4allianzen` set name="'.$_POST['Alliname'].'",tag="'.$_POST['Allitag'].'",info_bnds="'.$_POST['Allibnds'].'",info_naps="'.$_POST['Allinaps'].'",info_inoffizielle_naps="'.$_POST['Alliinnaps'].'",info_kriege="'.$_POST['Allikriege'].'"'.$query1.' where id="'.$_POST['selectid'].'"'.$query.';',__FILE__,__LINE__);
	if($Benutzer['rang'] == RANG_STECHNIKER){
		tic_mysql_query('update `gn4accounts` set ticid="'.$_POST['selectMeta'].'" where allianz="'.$_POST['selectid'].'";',__FILE__,__LINE__);
	}
	}
	if (isset($_POST['Allidelet'])&& $Benutzer['rang']==RANG_STECHNIKER&&isset($_POST['selectid'])){
		$SQL_Result = tic_mysql_query('DELETE FROM `gn4allianzen`WHERE id="'.$_POST['selectid'].'" ;',__FILE__,__LINE__);
		$SQL_Result = tic_mysql_query('DELETE FROM `gn4accounts` WHERE allianz="'.$_POST['selectid'].'" ;',__FILE__,__LINE__);
      LogAction("Alli und Accounts gel&ouml;scht. ID=".$_POST['selectid'].";");
	}
	if (isset($_POST['Allineu'])&& $Benutzer['rang']==RANG_STECHNIKER){
		$SQL_Result = tic_mysql_query('Insert into `gn4allianzen` (`ticid` , `name` , `tag` , `info_bnds` , `info_naps` , `info_inoffizielle_naps` , `info_kriege`)VALUES ("'.$_POST['selectMeta'].'", "'.$_POST['Alliname'].'", "'.$_POST['Allitag'].'", "'.$_POST['Allibnds'].'", "'.$_POST['Allinaps'].'", "'.$_POST['Alliinnaps'].'", "'.$_POST['Allikriege'].'");',__FILE__,__LINE__);
      LogAction("Neue Allianz mit den namen ".$_POST['Alliname']." angelegt!;");
	}
}
?>