<?php
// ATT-Planer Administration / Configuration
    if (!isset($_POST['fkt'])) $_POST['fkt'] = '';

    if (!isset($_POST['id'])) $_POST['id'] = '';
    if (!isset($_POST['lfd'])) $_POST['lfd'] = '';

    if (!isset($_POST['attplaner'])) $_POST['attplaner'] = '';

    if (!isset($_POST['galaxie'])) $_POST['galaxie'] = '';
    if (!isset($_POST['planet'])) $_POST['planet'] = '';
    if (!isset($_POST['attdatum'])) $_POST['attdatum'] = '';
    if (!isset($_POST['attzeit'])) $_POST['attzeit'] = '';
    if (!isset($_POST['attfor'])) $_POST['attfor'] = '';
    if (!isset($_POST['freigabe'])) $_POST['freigabe'] = '';
    if (!isset($_POST['info'])) $_POST['info'] = '';

    if (!isset($_POST['flotte'])) $_POST['flotte'] = '';
    if (!isset($_POST['attstatus'])) $_POST['attstatus'] = '';


    // Benutzer als Attplaner entfernen
    if ($_POST['fkt'] == 'remove') {
        if ($_POST['id'] != '') {
           $SQL='UPDATE `gn4accounts` SET attplaner=0 WHERE id = '.$_POST['id'].';';
 		       $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));
        }
    }

    // Info Text wird geändert
    if ($_POST['fkt'] == 'changeinfo') {
        if ($_POST['lfd'] != '') {
           $SQL='UPDATE `gn4attplanung` SET info="'.$_POST['info'].'" WHERE lfd = '.$_POST['lfd'].';';
		       $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));
        }
    }

    // Neuer Benutzer als Attplaner anlegen
    if ($_POST['fkt'] == 'newattplaner') {
        if ($_POST['id'] != '' && $_POST['attplaner'] != '') {
           $SQL='UPDATE `gn4accounts` SET attplaner='.$_POST['attplaner'].' WHERE id = '.$_POST['id'].';';
           $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));
        }
    }

    // Neuer Att Einplanen
    if ($_POST['fkt'] == 'attadd') {
// einplanen für:
         if ($_POST['attfor'] == "Allianz") {
           $ForAll = 0;
           $ForMeta = 0;
           $ForAllianz = $Benutzer['allianz'];
         }
         if ($_POST['attfor'] == "Meta") {
           $ForAll = 0;
           $ForMeta = $Benutzer['ticid'];
           $ForAllianz = 0;
         }
         if ($_POST['attfor'] == "Alle") {
           $ForAll = 1;
           $ForMeta = 0;
           $ForAllianz = 0;
         }

         $_POST['attdatum'] = ConvertDatumToDB($_POST['attdatum']);

         if ($_POST['galaxie'] != '' && $_POST['attdatum'] != '' && $_POST['attzeit'] != '') {

         if ($_POST['planet'] == '') {
            // Alle Planeten hinzufügen, wo von es einen Scan gibt!

            $SQL2="Select rp from gn4scans WHERE rg =".$_POST['galaxie']." GROUP BY rp;";
          
       		  $SQL2_Result = tic_mysql_query($SQL2) or die(tic_mysqli_error(__FILE__,__LINE__));
            $SQL2_Num = mysqli_num_rows($SQL2_Result);
              for ( $i=0; $i<$SQL2_Num; $i++ )  {
                 $_POST['planet'] = tic_mysql_result($SQL2_Result, $i, 'rp');

                 $SQL = 'INSERT INTO gn4attplanung (id,galaxie,planet,attdatum,attzeit,freigabe,info,forall,formeta,forallianz)
                      Values ("'.$_POST['id'].'","'.$_POST['galaxie'].'","'.$_POST['planet'].'","'.$_POST['attdatum'].'","'.$_POST['attzeit'].'","'.$_POST['freigabe'].'","'.$_POST['info'].'","'.$ForAll.'","'.$ForMeta.'","'.$ForAllianz.'");';
             		 $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));
 //               echo $SQL;
              }
         } else {
           if ($_POST['lfd'] == '') {
              $SQL = 'INSERT INTO gn4attplanung (id,galaxie,planet,AttDatum,AttZeit,Freigabe,Info,ForAll,ForMeta,ForAllianz)
                    Values ("'.$_POST['id'].'","'.$_POST['galaxie'].'","'.$_POST['planet'].'","'.$_POST['attdatum'].'","'.$_POST['attzeit'].'","'.$_POST['freigabe'].'","'.$_POST['info'].'","'.$ForAll.'","'.$ForMeta.'","'.$ForAllianz.'");';
           } else {
              $SQL = 'UPDATE gn4attplanung
                      Set id = '.$_POST['id'].',
                       galaxie = '.$_POST['galaxie'].',
                       planet = '.$_POST['planet'].',
                       AttDatum = "'.$_POST['attdatum'].'",
                       AttZeit = "'.$_POST['attzeit'].'",
                       Freigabe = "'.$_POST['freigabe'].'",
                       Info = "'.$_POST['info'].'",
                       ForAll = "'.$ForAll.'",
                       ForMeta = "'.$ForMeta.'",
                       ForAllianz = "'.$ForAllianz.'" WHERE lfd = '.$_POST['lfd'].';';
           }
           $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));
         }
         echo $_POST['lfd'].': Att erfolgreich eingeplant!';
        }
    }

    if ($_POST['fkt'] == 'delattflotte') {
        $SQL = 'DELETE FROM gn4attflotten WHERE lfd ='.$_POST['lfd'].' AND id ='.$_POST['id'].' AND flottenr = '.$_POST['flotte'].';';
		    $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));
        echo 'Att Flotte ausgeplant!';
    }

    if ($_POST['fkt'] == 'addattflotte') {
        $SQL = 'INSERT INTO gn4attflotten (lfd, id, flottenr) VALUES ("'.$_POST['lfd'].'","'.$_POST['id'].'","'.$_POST['flotte'].'");';
        $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));
        echo 'Att Flotte Eingeplant!';
    }

//    if ($_POST['fkt'] == 'attloeschung') {
//        $SQL = 'DELETE FROM gn4attflotten WHERE lfd ='.$_POST['lfd'].';';
//        $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));
//
//        $SQL = 'DELETE FROM gn4attplanung WHERE lfd ='.$_POST['lfd'].';';
//        $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));
//        echo 'ATT-Ziel gelöscht!';
//    }


//    if ($_POST['fkt'] == 'attfreigabe') {
//        $SQL = 'UPDATE gn4attplanung SET freigabe = 1 WHERE lfd ='.$_POST['lfd'].';';
//        $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));
//        echo 'Att-Ziel freigegeben!';
//    }

    if ($_POST['fkt'] == 'attstatuschange') {
        $SQL = 'UPDATE gn4attplanung SET attstatus = '.$_POST['attstatus'].' WHERE lfd ='.$_POST['lfd'].';';
        $SQL_Result = tic_mysql_query($SQL) or die(tic_mysqli_error(__FILE__,__LINE__));
        echo 'Att-Status geändert!';
    }
?>
