<?php
//////////////////////////////////////////////////////
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

    $status = array("Vollerversion");
    $version = array(1,36,3);

    include("mysql.php");
    include("steps.php");

    session_start();

    $step = (isset($_POST['step']) ? intval($_POST['step']) : 1);
    if($step < 1 || $step > 5)
        $step = 1;

    echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html>
  <head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
    <title>Tic Installation v".implode(".", $version).$status[0].$status[1]." - Schritt ".$step."</title>
  </head>
  <body>
      <div align=\"center\"><h2>Willkommen zur Installation des Tics v".implode(".", $version).$status[0].$status[1]."</h2>";

    $r = callstep($step);
    if($r != "")
        callstep($step-1, array("errormsg" => $r));

    echo "    </div>\n  </body>\n</html>";

?>
