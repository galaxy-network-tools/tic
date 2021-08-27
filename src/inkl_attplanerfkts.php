<?php

// Ziele Umplanen und löschen
  echo '<a href="./main.php?modul=atteinplanen&lfd='.tic_mysql_result($SQL_Result, $i, "lfd").'&fkt=attumplanung&action=attplaneradmin2">';
  echo '<img src="./bilder/attplaner/Ziel_umplanen.gif" width="128" height="25" border="0">';
  echo '</a><br>';

  echo '<a href="./main.php?modul=attplanerlist&lfd='.tic_mysql_result($SQL_Result, $i, "lfd").'&fkt=attloeschung&action=attplaneradmin2">';
  echo '<img src="./bilder/attplaner/Ziel_loeschen.gif" width="128" height="25" border="0">';
  echo '</a>';


?>

