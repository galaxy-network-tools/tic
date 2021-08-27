<?php
//////////////////////////////////////////////////////
//
// Version 0.1
//
// Copyright (C) 2005  Lars-Peter 'laprican' Clausen
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
/////////////////////////////////////////////////////

    function callstep($step, $data = array())
    {
        switch($step)
        {
            case 5:
                return step5($data);
			case 4:
                return step4($data);
            case 3:
                return step3($data);
            case 2:
                return step2($data);
           default:
                return step1($data);
         }
    }

	function step1($data)
	{
          echo "<div style=\"border:1px #000000 solid;width:400px;padding:3px;\"><b>Schritt 1: Vorbereitung der Installation</b>
                   <br />
                   <div style=\"text-align:left;\">Damit die Installation erfolgreich ausgef&uuml;hrt werden kann ben&ouml;tigt PHP Schreibrechte auf das tic Verzeichniss. Dieses kann am einfach durch das Setzen der Rechte auf 0777 erreicht werden. Nach der Installation k&ouml;nnen die Rechte wieder auf ihren urspr&uuml;nglichen Wert zur&uuml;ckgesetzt werden.</div><form action=\"\" method=\"post\"><input type=\"hidden\" name=\"step\" value=\"2\" /><input type=\"submit\" value=\"Weiter\" /></form></div>";

	}

    function step2($data)
    {
           unset($_SESSION['install']);
            $_SESSION['install'] = array();
           echo "<div style=\"border:1px #000000 solid;width:400px;padding:3px;\"><b>Schritt 2: Mysql Account Daten + Addresse des Tics</b>
              <form action=\"\" method=\"post\"><input type=\"hidden\" name=\"step\" value=\"3\" \><table style=\"text-align:left;\">
                <tr><td>Host:</td><td><input type=\"text\" name=\"host\" value=\"".(isset($_POST['host']) ? $_POST['host'] : "localhost")."\" /></td></tr>
                <tr><td>Benutzername:</td><td><input type=\"text\" name=\"username\" value=\"".(isset($_POST['username']) ? $_POST['username'] : "")."\" /></td></tr>
                <tr><td>Passwort:</td><td><input type=\"password\" name=\"password\" value=\"\" /></td></tr>
                <tr><td>Datenbank:</td><td><input type=\"text\" name=\"database\" value=\"".(isset($_POST['database']) ? $_POST['database'] : "")."\" /></td></tr>
                <tr><td>Addresse des Tics:</td><td><input type=\"text\" name=\"adress\" value=\"".(isset($_POST['adress']) ? $_POST['adress'] : "http://".$_SERVER['HTTP_HOST'].str_replace("index.php", "", str_replace("installer/", "", $_SERVER['REQUEST_URI'])))."\" /></td></tr>
                <tr><td colspan=\"2\" style=\"text-align:center\"><input type=\"submit\" value=\"Weiter\" /></td></tr>
              </table>";

            if(isset($data['errormsg']) && $data['errormsg'] != "")
               echo "<div style=\"color:#800000;text-align:left;\">".$data['errormsg']."</div>";
            echo "</form>
            </div>";
            return "";
    }

    function step3($data)
    {
        if(!isset($_POST['host']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['database']) || !isset($_POST['adress']))
            return "Es trat ein Fehler bei der ï¿½rmittlung der Daten auf.";

        $mysql = new mysql(array());
        if(!@$mysql->connect($_POST['host'], $_POST['username'], $_POST['password']))
            die("Es konnte keine Verbindung zum Server hergestellt werden.<br />&Uuml;berpr&uuml;fe bitte deine Angaben auf ihre Richtigkeit.<br />".mysqli_error($mysql));

        if(!@$mysql->select_db($_POST['database']))
            return "Die gew&uuml;nschte Datenbank konnte nicht ausgew&auml;hlt werden.<br />Entweder existiert diese nicht oder der gew&auml;hlte Benutzer hat keine Zugriffsrechte.<br />".mysqli_error($mysql);

        $_SESSION['install']['mysql']['host'] = $_POST['host'];
        $_SESSION['install']['mysql']['username'] = $_POST['username'];
        $_SESSION['install']['mysql']['password'] = $_POST['password'];
        $_SESSION['install']['mysql']['database'] = $_POST['database'];
        $_SESSION['install']['tic_adress'] = $_POST['adress'];

        $tables = array("gn4accounts",
                                  "gn4allianzen",
                                  "gn4cron",
                                  "gn4flottenbewegungen",
                                  "gn4forum",
                                  "gn4gnuser",
                                  "gn4incplanets",
                                  "gn4log",
                                  "gn4nachrichten",
                                  "gn4nachtwache",
                                  "gn4meta",
                                  "gn4scans",
                                  "gn4attflotten",
                                  "gn4attplanung",
                                  "gn4vars",
                                  "gn4channels");

        $mysql->query("SHOW TABLE STATUS") or die($mysql->error(__FILE__,__LINE__));

        $tableexits = array();

        while($row = $mysql->fetch())
        {
            $tableexits[$row['Name']] = 1;
        }
        echo "<div style=\"border:1px #000000 solid;width:400px;padding:3px;\"><b>Schritt 3: &Uuml;berpr&uuml;fe Tabellen</b>
          <form action=\"\" method=\"post\"><input type=\"hidden\" name=\"step\" value=\"4\" \>
          <table style=\"text-align:left;\" cellspacing=\"0\" cellpadding=\"0\">
            <tr style=\"font-weight:bold;\"><td>Tabellenname</td><td>Installieren</td></tr>";

        $showwarning = 0;
        foreach($tables as $table)
        {
            if($tableexits[$table])
            {
                $color = "#800000";
                $showwarning = 1;
                $input = "<input type=\"checkbox\" name=\"check[]\" value=\"".$table."\" checked=\"checked\"/>";
            }
            else
            {
                $color = "#008000";
                $input = "<input type=\"checkbox\" checked=\"checked\" disabled = \"disabled\" /><input type=\"hidden\"  name=\"check[]\" value=\"".$table."\" />";
            }
            echo "<tr style=\"color:".$color."\"><td>$table</td><td style=\"text-align:center\">".$input."</td></tr>";
        }

        if($showwarning)
            echo "</table><br /><div style=\"text-align:left\"><b>ACHTUNG:</b> Rot makierte Tabellen existieren bereits und werden, sofern die Installation fortgesetzt wird, inklusive ihrer Daten berschrieben.</div>";
        echo "<input type=\"submit\" value=\"Weiter\" /></form>
            </div>";

        return "";
    }

    function step4($data)
    {
        unset($_SESSION['install']['tables']);
        if(isset($_POST['check']))
        {
            foreach($_POST['check'] as $check)
            {
                $_SESSION['install']['tables'][] = $check;
            }
        }

        echo "<div style=\"border:1px #000000 solid;width:400px;padding:3px;\"><b>Schritt 4: Meta-, Allianz- und Benutzerdaten</b>
          <form action=\"\" method=\"post\"><input type=\"hidden\" name=\"step\" value=\"5\" \>
          <table style=\"text-align:left;\">
             <tr><td>Metaname:</td><td><input type=\"text\" name=\"meta_name\" value=\"".(isset($_POST['meta_name']) ? $_POST['meta_name'] : "")."\" /></td></tr>
             <tr><td colspan=\"2\"><hr /></td></tr>
             <tr><td>Allianztag:</td><td><input type=\"text\" name=\"alli_tag\" value=\"".(isset($_POST['alli_tag']) ? $_POST['alli_tag'] : "")."\" /></td></tr>
             <tr><td>Allianzname:</td><td><input type=\"text\" name=\"alli_name\" value=\"".(isset($_POST['alli_name']) ? $_POST['alli_name'] : "")."\" /></td></tr>
             <tr><td colspan=\"2\"><hr /></td></tr>
             <tr><td>Benutzername:</td><td><input type=\"text\" name=\"user_name\" value=\"".(isset($_POST['user_name']) ? $_POST['user_name'] : "")."\" /></td></tr>
             <tr><td>Benutzerpasswort:</td><td><input type=\"password\" name=\"user_password\" value=\"".(isset($_POST['user_password']) ? $_POST['user_pasword'] : "")."\" /></td></tr>
             <tr><td>Benutzercoords:</td><td><input type=\"text\" name=\"user_coords1\" value=\"".(isset($_POST['user_coords1']) ? $_POST['user_coords1'] : "")."\" size=\"3\" maxlength=\"4\" />:<input type=\"text\" name=\"user_coords2\" value=\"".(isset($_POST['user_coords2']) ? $_POST['user_coords2'] : "")."\" size=\"2\" maxlength=\"2\" /></td></tr>
             <tr><td colspan=\"2\" style=\"text-align:center\"><input type=\"submit\" value=\"Weiter\" /></td></tr>";
         if(isset($data['errormsg']) && $data['errormsg'] != "")
             echo "<tr><td colspan=\"2\" style=\"color:#800000\">".$data['errormsg']."</td></tr>";
         echo "</table>
         </div>";
    }

    function step5($data)
    {
        if($_POST['meta_name'] == "" || $_POST['alli_tag'] == "" || $_POST['alli_name'] == "" || $_POST['user_name'] == "" || $_POST['user_password'] == ""  || $_POST['user_coords1'] == ""  || $_POST['user_coords2'] == "" )
            return "Du musst alle Felder ausf&uuml;llen.";

        echo "<div style=\"border:1px #000000 solid;width:400px;padding:3px;\"><b>Schritt 5: Abschlu&szlig; der Installation</b><div style=\"text-align:left\">";

        $t = &$_SESSION['install']['mysql'];

        $file = @fopen("../accdata.php", "w");
        if(!$file)
        {
            echo "<div style=\"color:#800000\">Die Datei accdata.php konnte nicht zum schreiben &ouml;ffnen. Trage die Mysqldaten bitte nachtr&auml;glich ein.</div>";
        }
        else
        {
            fwrite($file, "<?php
        // Account Daten
        \$db_info['host'] = '".$t['host']."';
        \$db_info['user'] = '".$t['username']."';
        \$db_info['password'] = '".$t['password']."';
        \$db_info['dbname'] = '".$t['database']."';
        \$pfadzumtick = '".$_SESSION['install']['tic_adress']."'; // Der Schrägstrich am Ende ist wichtig!

        \$tic_status = array('".implode("', '", $GLOBALS['status'])."');
        \$tic_version = array(".implode(", ",$GLOBALS ['version']).");
    ?>");
            fclose($file);
			@chmod("../accdata.php", 0777);
        }

        $mysql = new mysql(array('host' => $t['host'], 'user' => $t['username'], 'password' => $t['password'], 'dbname' => $t['database']));
        unset($t);

        if(isset($_SESSION['install']['tables']))
        {
            include("sql.php");
            foreach($_SESSION['install']['tables'] as $table)
            {
                $mysql->multiquery($sqlquery[$table]) or die($mysql->error(__FILE__,__LINE__));
            }
        }
        $mysql->insert("gn4meta", "name, sysmsg", "'".$_POST['meta_name']."', 'Wenn dieser Text zu lesen ist dann ist leuft der TIC diese Nachricht kann unter Managment geändert werden.'") or die($mysql->error(__FILE__,__LINE__));
        $ticid = $mysql->insert_id();


        $mysql->insert("gn4vars", "name, value, ticid", "'lastscanclean', '".date("H:n d.m.Y.")."', '".$ticid."'") or die($mysql->error(__FILE__,__LINE__));
        $mysql->insert("gn4vars", "name, value, ticid", "'forumpriority', '0', '".$ticid."'") or die($mysql->error(__FILE__,__LINE__));
        $mysql->insert("gn4vars", "name, value, ticid", "'lasttick', '".date("H:n:s")."', '".$ticid."'") or die($mysql->error(__FILE__,__LINE__));
        $mysql->insert("gn4vars", "name, value, ticid", "'style', '../gnstyle', '".$ticid."'") or die($mysql->error(__FILE__,__LINE__));
        $mysql->insert("gn4vars", "name, value, ticid", "'attplaner', 'aktiv', '".$ticid."'") or die($mysql->error(__FILE__,__LINE__));
        $mysql->insert("gn4vars", "name, value, ticid", "'botpw', '', '".$ticid."'") or die($mysql->error(__FILE__,__LINE__));
        $mysql->insert("gn4vars", "name, value, ticid", "'tickdauer', '15', '".$ticid."'") or die(mysql_error(__FILE__,__LINE__));


        $mysql->insert("gn4allianzen", "ticid, name , tag", "'".$ticid."', '".$_POST['alli_name']."', '".$_POST['alli_tag']."'") or die($mysql->error(__FILE__,__LINE__));
        $alli_id = $mysql->insert_id();
        $mysql->insert("gn4accounts", "ticid, name, passwort, galaxie, planet, rang, allianz", "'".$ticid."', '".$_POST['user_name']."', '".md5($_POST['user_password'])."', '".$_POST['user_coords1']."', '".$_POST['user_coords2']."', '5', '".$alli_id."'") or die($mysql->error(__FILE__,__LINE__));

        echo "Die Installation wurde erfolgreich abgeschlossen.<br />Bitte l&ouml;sche den Ordner installer vom Server.<br />Danach kannst du dich <a href=\"../index.php\">hier</a> mit deinen Accountdaten einloggen.</div>";

        return "";
    }

?>
