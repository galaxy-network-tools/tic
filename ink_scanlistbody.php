<?php
   // File: ink_scanlistbody.php
   // by Bytehoppers form CCBLOCK

   $SQL_Num = mysql_num_rows($SQL_Result);

	 if ($SQL_Num == 0)  echo '<B>Keine Scans gefunden</B><br>';
   	else
	  {
	  // Max anzahl an Scans anzeigen!
      if ($SQL_Num > 50) $SQL_Num = 50;

      for ($n = 0; $n < $SQL_Num; $n++)
      {
        echo '<TR>';
        $gala = mysql_result($SQL_Result, $n, 'rg');
        $planet = mysql_result($SQL_Result, $n, 'rp');
		    echo '<TD><P CLASS="hell"><font size=1>'.$gala.':'.$planet.' ';
        echo gnuser($gala, $planet).'</font></TD>';

      	$scanzeit = mysql_result($SQL_Result, $n, 'zeit');
	  	  if (substr($scanzeit, 6, 2) == date('d'))
	  							$farbe = $htmlstyle['hell'];
	  						else
	  							$farbe = $htmlstyle['hell_rot'];

		    echo '	<TD BGCOLOR=#'.$farbe.'><font size=1>'.$scanzeit.'</font></TD>';
	    	//scans&txtScanGalaxie=5&txtScanPlanet=7

	    	$stypes = GetScans2($SQL_DBConn, mysql_result($SQL_Result, $n, 'rg'), mysql_result($SQL_Result, $n, 'rp'));
        $output =OnMouseFlotte($gala, $planet, $Benutzer['punkte'],"");

        echo "<TD onmouseover=\"return overlib('".$output."');\"
             onmouseout=\"return nd();\">";

		    echo '<P CLASS="hell">';
        echo '<A HREF="./main.php?modul=showgalascans&displaytype=0&xgala='.$gala.'&xplanet='.$planet.'">';
        echo '<font size=2>'.$stypes.'</font></A></p></TD>';
        // Sektor gescannt
		    // Genauigkeit
		    $gen = mysql_result($SQL_Result, $n, 'gen');

		    if ($gen == 100 or $gen==99) $farbe = $htmlstyle['hell'];
		    if ($gen < 99) $farbe = $htmlstyle['hell_rot'];

		    echo '	<TD BGCOLOR=#'.$farbe.'><font size=1>'.$gen.'%</font></TD>';

        $stk = mysql_result($SQL_Result, $n, 'pts');
        if ($Benutzer['punkte'] / $ATTOVERALL   <= $stk ) {
            // Angreifbar
         		echo '	<TD ALIGN="right" BGCOLOR=#29D202><font size=1><b>'.ZahlZuText($stk).'</font></TD>';
        } else {
  		      echo '	<TD ALIGN="right"><P CLASS="hell"><font size=1><b>'.ZahlZuText($stk).'</font</TD>';
        }

		    echo '	<TD ALIGN="right"><P CLASS="hell">'.ZahlZuText(mysql_result($SQL_Result, $n, 's')).'</TD>';

		    echo '	<TD ALIGN="right"><P CLASS="hell">'.ZahlZuText(mysql_result($SQL_Result, $n, 'd')).'</TD>';

		    echo '	<TD ALIGN="right"><P CLASS="hell">'.ZahlZuText(mysql_result($SQL_Result, $n, 'me')).'</TD>';

		    echo '	<TD ALIGN="right"><P CLASS="hell">'.ZahlZuText(mysql_result($SQL_Result, $n, 'ke')).'</TD>';

        $IstEx = (mysql_result($SQL_Result, $n, 'ke')+mysql_result($SQL_Result, $n, 'me'));
 		    echo '	<TD ALIGN="right"><P CLASS="hell">'.$IstEx.'</P></TD>';

        echo '</TR>';
	  }
		echo '</TABLE>';
    echo '<br><center><font size=2 color=#29D202>(Angreifbar aufgrund der Punkte 1/'.$ATTOVERALL.')</font>';
	}
?>
