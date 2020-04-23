<?php
// System-/Allianzeinstellungen ändern
    if ($_POST['action'] == 'systemeinstellung') {
        if ($Benutzer['rang'] <= $Rang_GC)
            $error_code = 5;
        else {
            if (!isset($_POST['lstCode'])) $_POST['lstCode'] = 0;
            $SQL_Result = tic_mysql_query('UPDATE `gn4allianzen` SET code="'.$_POST['lstCode'].'" WHERE id="'.$Benutzer['allianz'].'";', $SQL_DBConn) or $error_code = 7;
            if (isset($_POST['txtBNDs'])){
			$SQL_Result = tic_mysql_query('UPDATE `gn4allianzen` SET info_bnds="'.$_POST['txtBNDs'].'" WHERE id="'.$Benutzer['allianz'].'";', $SQL_DBConn) or $error_code = 7;
			}
            if (isset($_POST['txtNAPs'])){
			$SQL_Result = tic_mysql_query('UPDATE `gn4allianzen` SET info_naps="'.$_POST['txtNAPs'].'" WHERE id="'.$Benutzer['allianz'].'";', $SQL_DBConn) or $error_code = 7;
	}
            if (isset($_POST['txtInoffizielleNAPs'])){
            $SQL_Result = tic_mysql_query('UPDATE `gn4allianzen` SET info_inoffizielle_naps="'.$_POST['txtInoffizielleNAPs'].'" WHERE id="'.$Benutzer['allianz'].'";', $SQL_DBConn) or $error_code = 7;
          }
            if (isset($_POST['txtKriege'])){
            $SQL_Result = tic_mysql_query('UPDATE `gn4allianzen` SET info_kriege="'.$_POST['txtKriege'].'" WHERE id="'.$Benutzer['allianz'].'";', $SQL_DBConn) or $error_code = 7;
          }
          if (isset($_POST['txtGalas'])){
            $SQL_Result = tic_mysql_query('UPDATE `gn4allianzen` SET galalist="'.$_POST['txtGalas'].'" WHERE id="'.$Benutzer['allianz'].'";', $SQL_DBConn) or $error_code = 7;
          }

            $AllianzInfo[$Benutzer['allianz']]['code'] = $_POST['lstCode'];
            $AllianzInfo[$Benutzer['allianz']]['info_bnds'] = $_POST['txtBNDs'];
            $AllianzInfo[$Benutzer['allianz']]['info_naps'] = $_POST['txtNAPs'];
            $AllianzInfo[$Benutzer['allianz']]['info_inoffizielle_naps'] = $_POST['txtInoffizielleNAPs'];
            $AllianzInfo[$Benutzer['allianz']]['info_kriege'] = $_POST['txtKriege'];
            $AllianzInfo[$Benutzer['allianz']]['galalist'] = $_POST['txtGalas'];
            if ($error_code == 0) LogAction("Systemeinstellungen geändert: Code=".$AllianzInfo[$Benutzer['allianz']]['code']."; BNDs='".$AllianzInfo[$Benutzer['allianz']]['info_bnds']."'; NAPs='".$AllianzInfo[$Benutzer['allianz']]['info_naps']."'; inoff. NAPs='".$AllianzInfo[$Benutzer['allianz']]['info_inoffizielle_naps']."'; Kriege='".$AllianzInfo[$Benutzer['allianz']]['info_kriege']."';");
        }
    }
?>
