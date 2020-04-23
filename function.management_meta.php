<?php
if($Benutzer['rang']>=RANG_TECHNIKER){
	if(isset($_POST['newmeta'])&& $Benutzer['rang']== RANG_STECHNIKER){
		tic_mysql_query('insert INTO `gn4meta` (name,duell,wars,naps,bnds,sysmsg) VALUES ("'.$_POST['meta'].'", "'.$_POST['duell'].'", "'.$_POST['wars'].'", "'.$_POST['naps'].'","'.$_POST['bnds'].'" , "'.$_POST['sysmsg'].'");',__FILE__,__LINE__);
        $newmetaid = mysql_insert_id();
//		echo $_POST['meta'].' angelegt!';
}
if(isset($_POST['metaspeichern'])) {
  if($Benutzer['rang']== RANG_TECHNIKER) $_POST['metaid']==$Benutzer['ticid'];
	tic_mysql_query('Update `gn4meta` set name="'.$_POST['meta'].'", duell="'.$_POST['duell'].'", naps="'.$_POST['naps'].'", bnds="'.$_POST['bnds'].'", wars="'.$_POST['wars'].'", sysmsg="'.$_POST['sysmsg'].'" where id="'.$_POST['metaid'].'" ;',__FILE__,__LINE__);
//	echo 'ï¿½derung gespeichert';
}
if(isset($_POST['metadelet'])&& $Benutzer['rang']== RANG_STECHNIKER){
	$SQL_Result=tic_mysql_query('Select id FROM `gn4allianzen` where ticid="'.$_POST['metaid'].'";',__FILE__,__LINE__);
	if(mysql_num_rows($SQL_Result)!='0'){
		LogAction($Benutzer['name'].' hat Versucht den Meta mit der id '.$_POST['metaid'].' zul&ouml;schen ohne die Allianzen vorher zu l&ouml;schen !');
		$metaerror="Es m&uuml;ssen erst die Allianzen gel&ouml;scht werden bevor das Meta gel&ouml;scht werdne kann!";
	}else {
		tic_mysql_query('DELETE FROM `gn4meta` WHERE id="'.$_POST['metaid'].'"',__FILE__,__LINE__);
        unset($_POST['metaid']);
	}
}
}
?>