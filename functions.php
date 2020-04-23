<?PHP
    function GetScans($SQL_DBConn, $galaxie, $planet) {
        $scan_type[0] = 'S';
        $scan_type[1] = 'E';
        $scan_type[2] = 'M';
        $scan_type[3] = 'G';
     		$scan_type[4] = 'N';

        $datumx = date('d.m.Y');

        $SQL_Result = tic_mysql_query('SELECT * FROM `gn4scans` WHERE rg="'.$galaxie.'" AND rp="'.$planet.'" ORDER BY type;') or die(tic_mysql_error(__FILE__,__LINE__));
        //echo "Scan: ".'SELECT * FROM `gn4scans` WHERE rg="'.$galaxie.'" AND rp="'.$planet.'" ORDER BY type;<br />';
        $SQL_Num = mysql_num_rows($SQL_Result);
        if ($SQL_Num == 0)
            return '[-]';
        else {
            $tmp_result = '[';
            for ($n = 0; $n < $SQL_Num; $n++)
            {
                $tmp_result = $tmp_result.$scan_type[mysql_result($SQL_Result, $n, 'type')];
            }
            $tmp_result = $tmp_result.']';
        //    echo "Scan=>$tmp_result<br />";
            return $tmp_result;
        }
        return null;
    }

    function GetScans2($SQL_DBConn, $galaxie, $planet) {
        $scan_type[0] = 'S';
        $scan_type[1] = 'E';
        $scan_type[2] = 'M';
        $scan_type[3] = 'G';
     		$scan_type[4] = 'N';

        $datumx = date('d.m.Y');

        $SQL_Result = tic_mysql_query('SELECT * FROM `gn4scans` WHERE rg="'.$galaxie.'" AND rp="'.$planet.'" ORDER BY type;') or die(tic_mysql_error(__FILE__,__LINE__));
        //echo "Scan: ".'SELECT * FROM `gn4scans` WHERE rg="'.$galaxie.'" AND rp="'.$planet.'" ORDER BY type;<br />';
        $SQL_Num = mysql_num_rows($SQL_Result);
        if ($SQL_Num == 0)
            return '[-]';
        else {
            $tmp_result = '[';
            for ($n = 0; $n < $SQL_Num; $n++)
            {
               if ($datumx == substr(mysql_result($SQL_Result, $n, 'zeit'),-10)) {
                  $fc1 = "";
                  $fc2 = "";
               } else {
                  $fc1 = "<FONT COLOR=#FF887F>";
                  $fc2 = "</FONT>";
               }

               $tmp_result = $tmp_result.$fc1.$scan_type[mysql_result($SQL_Result, $n, 'type')].$fc2;
            }
            $tmp_result = $tmp_result.']';
        //    echo "Scan=>$tmp_result<br />";
            return $tmp_result;
        }
        return null;
    }

function GetUserInfos($id) {
      global $SQL_DBConn;
      $SQL = 'SELECT * FROM `gn4accounts` WHERE id ="'.$id.'";';
		  $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
		  $SQL_Num = mysql_num_rows($SQL_Result);
		  if ($SQL_Num == 0)
			  return '???';
		  else {
			  $tmp_result = mysql_result($SQL_Result, 0, 'galaxie').':'.mysql_result($SQL_Result, 0, 'planet').' '.mysql_result($SQL_Result, 0, 'name');
			  return $tmp_result;
		  }
}

  	function GetUserPts($id) {
      global $SQL_DBConn;
		  $SQL= 'SELECT * FROM `gn4accounts` WHERE id ="'.$id.'";';
		  $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
		  $SQL_Num = mysql_num_rows($SQL_Result);
		  if ($SQL_Num == 0) {
         return 0;
      } else {
         $SQL2 = "SELECT pts FROM `gn4scans` WHERE rg='".mysql_result($SQL_Result, 0, 'galaxie')."' AND rp='".mysql_result($SQL_Result, 0, 'planet')."' AND type='0';";
    	   $SQL_Result2 = tic_mysql_query($SQL2) or die(tic_mysql_error(__FILE__,__LINE__));
         if (mysql_num_rows($SQL_Result2) != 1)
         {
           return 0;
         } else  {
           return mysql_result($SQL_Result2, 0, 'pts');
         }
      }
	  }

    function AttPlanerRights($Allianz, $Meta, $Super, $Rechte, $UserMeta, $UserAllianz) {

      if ($Super == 1 && $Rechte == 3) {
          return  true;
      } else {
         if ($Meta = $UserMeta && $Rechte >= 2) {
           return  true;
         } else {
            if($Allianz == $UserAllianz && $Rechte >= 1) {
               return  true;
            } else {
               return  false;
            }
         }
      }
      // end
    }

    function LogAction($text, $type = LOG_SYSTEM)
    {
        global $Benutzer;
        global $_SERVER;
        tic_mysql_query("INSERT INTO `gn4log` (type, ticid, name, accid, rang, allianz, zeit, aktion, ip) VALUES (".$type.", '".$Benutzer['ticid']."', '".$Benutzer['name']."', '".$Benutzer['id']."', '".$Benutzer['rang']."', '".$Benutzer['allianz']."', '".date("d.m.Y H:i")."', '".addcslashes($text, "\000\x00\n\r'\"\x1a")."', '".$_SERVER['REMOTE_ADDR']."')") or die(tic_mysql_error(__FILE__,_LINE__,false));
    }

    function ZahlZuText($zahl)
    {
        $x = 0;
        $text = '';
        for ($n = strlen($zahl); $n >= 0; $n--)
        {
            $text = substr($zahl, $n, 1).$text;
            if ($x >= 3 && $n > 0) {
                $x = 0;
                $text = '.'.$text;
            }
            $x++;
        }
        return $text;
    }

    function TextZuZahl($text)
    {
        $zahl = str_replace(',', '', $text);
        $zahl = str_replace('.', '', $zahl);
        return intval($zahl);
    }

    function CountScans($id)
    {
        $SQL_Result = tic_mysql_query('SELECT COUNT(id) FROM `gn4accounts` WHERE id="'.$id.'"') or die(tic_mysql_error(__FILE__,__LINE__));
        $count = mysql_fetch_row($SQL_Result);
        if($count[0])
        {
            tic_mysql_query('UPDATE `gn4accounts` SET scans = scans+1 WHERE id="'.$id.'"') or die(tic_mysql_error(__FILE__,__LINE__));
        }
    }

    function getime4display( $time_in_min )
    {
        global $Benutzer;
        global $displayflag;
        global $Ticks;
        if ($time_in_min < 0)
            $time_in_min=0;
        if (!isset($displayflag))
        {
            $displayflag=0;
            $SQL_Result3 = tic_mysql_query('SELECT zeitformat FROM `gn4accounts` WHERE id="'.$Benutzer['id'].'"') or die(tic_mysql_error(__FILE__,__LINE__));
            $displayflag =  mysql_result($SQL_Result3, 0, 'zeitformat' );
        }
        switch( $displayflag )
        {

            case 1:     // std:min
                $result_std = sprintf("%02d", intval($time_in_min / 60));
                $result_min = sprintf("%02d", intval($time_in_min % 60));
                $result = $result_std.':'.$result_min;
                break;
            case 2:     // ticks
                $result = (int)($time_in_min / $Ticks['lange']);
                break;
           default:
                $result=$time_in_min;
           break;


        }
        return $result;
    }

    function addgnuser($gala, $planet, $name, $kommentare="")
    {
        if ($name != "" && is_numeric($planet) && $planet != '' && is_numeric($gala)&& $gala != '')
        {
            tic_mysql_query("DELETE FROM gn4gnuser WHERE name='".$name."'") or die(tic_mysql_error(__FILE__,__LINE__));
            tic_mysql_query("DELETE FROM gn4gnuser WHERE gala='".$gala."' AND planet='".$planet."'") or die(tic_mysql_error(__FILE__,__LINE__));
            tic_mysql_query("INSERT INTO gn4gnuser (gala, planet, name, kommentare, erfasst) VALUES ('".$gala."', '".$planet."', '".$name."', '".$kommentare."', '".time()."')") or die(tic_mysql_error(__FILE__,__LINE__));
        }
    }

    function gnuser($gala, $planet)
    {
        if($gala != "" && $planet != "" && is_numeric($planet)&& is_numeric($gala))
        {
            $SQL_Result = tic_mysql_query('SELECT name FROM `gn4gnuser` WHERE gala="'.$gala.'" AND planet="'.$planet.'"') or die(tic_mysql_error(__FILE__,__LINE__));
            if($user = mysql_fetch_row($SQL_Result))
                return $user[0];
            else
            {
                $SQL_Result = tic_mysql_query('SELECT name FROM `gn4accounts` WHERE galaxie="'.$gala.'" AND planet="'.$planet.'"') or die(tic_mysql_error(__FILE__,__LINE__));
                if($user = mysql_fetch_row($SQL_Result))
                    return $user[0];
            }
        }
        return "Unknown?";
    }

    function eta($time1, $time2 = null)
    {
        global $Ticks;
        if($time2 === null)
        {
            $time2 = $time1;
            $time1 = time();
        }
        $eta = ceil((($time2-$time1)/60)/$Ticks['lange']);
        if($eta < 0)
            $eta = 0;
        return $eta;
    }

    function count_querys($inc = true)
    {
        static $querys = 0;
        if($inc)
            $querys++;
        return $querys;
    }

    function tic_mysql_query($query, $file = null, $line = null)
    {
        $GLOBALS['last_sql_query'] = $query;
        $query_result = mysql_query($query, $GLOBALS['SQL_DBConn']);
        if(!$query_result && $file != null)
        {
            die(tic_mysql_error($file, $line));
        }
        count_querys();
        return $query_result;
    }

    function tic_mysql_error($file = null, $line = null, $log = true)
    {
        $re = "<div style=\"text-align:left\"><ul><b>Mysql Fehler".($file != "" ? " in ".$file."(".$line.")" : "").":</b>".($GLOBALS['last_sql_query'] ? "\n<li><b>Query:</b> ".$GLOBALS['last_sql_query']."</li>\n" : "")."<li><b>Fehlermeldung:</b> ".mysql_errno()." - ".mysql_error()."</li>\n</ul></div></body></html>";
        if($log)
            LogAction("<div style=\"text-align:left\"><ul><b>Mysql Fehler".($file != "" ? " in ".$file."(".$line.")" : "").":</b>".($GLOBALS['last_sql_query'] ? "\n<li><b>Query:</b> ".$GLOBALS['last_sql_query']."</li>\n" : "")."<li><b>Fehlermeldung:</b> ".mysql_errno()." - ".mysql_error()."</li>\n</ul></div>", LOG_ERROR);
        return $re;

    }

  function ConvertDatumToDB($Text) {
     if (strlen($Text) == 10) {
        $ret = substr($Text,6,4)."-".substr($Text,3,2)."-".substr($Text,0,2);
     } else {
        $ret = substr($Text,6,2)."-".substr($Text,3,2)."-".substr($Text,0,2);
     }
     return $ret;
  }

  function ConvertDatumToText($Text) {
      $ret=substr($Text,8,2).".".substr($Text,5,2).".".substr($Text,0,4);
      return $ret;
  }

  function printselect($nr) {
// ausgabe der Funktion im der Suchseite fuer Ziele
    echo '<td><center><select name=fkt'.$nr.'><option>=</option><option><=</option><option>>=</option></select></center></td>';
  }

  function OnMouseFlotte($galaxie, $planet, $punkte, $stype) {

    include './globalvars2.php';

    $SQL = "SELECT * FROM gn4scans WHERE rg=".$galaxie." and rp=".$planet." order by type;";
    $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
    $SQL_Num = mysql_num_rows($SQL_Result);
    for ($i=0;$i<15;$i++) {
        $d[$i]="?";
        $sx[$i] = "?";
        $xzeit[$i] = "?";
    }
    $uzeit="";
    $ugen="";
    $gzeit="";
    $ggen ="";


    for ($i = 0; $i < $SQL_Num; $i++) {
         $type = mysql_result($SQL_Result, $i, 'type' );
         if ($punkte >= 0) {
            switch( $type ) {   // scan-type
                case 0:
                    $uzeit  = mysql_result($SQL_Result, $i, 'zeit' );
                    $ugen   = mysql_result($SQL_Result, $i, 'gen' );
                    $xzeit[0] = "<b>S-Scan: ".$uzeit." ".$ugen."%:</b><br>";
                    $sx[0] = mysql_result($SQL_Result, $i, 'me' );
                    $sx[1] = mysql_result($SQL_Result, $i, 'ke' );
                    $sx[2] = round (mysql_result($SQL_Result, $i, 'pts' ) / 1000000,3)." M";

                    if ($punkte != 0) {
                       if ((mysql_result($SQL_Result, $i, 'pts' ) * $ATTOVERALL) >= $punkte ) {
                            $sx[2] .= "  <= Ziel angreifbar";
                       } else {
                            $sx[2] .= "  (Ziel nicht angreibar; MIN=".(round($punkte / $ATTOVERALL/ 1000000,3))." M)";
                       }
                    }

                    $sx[3] = mysql_result($SQL_Result, $i, 's' );
                    $sx[4] = mysql_result($SQL_Result, $i, 'd');

                case 1: // Einheiten
                    if ($stype == "") {
                      $uzeit  = mysql_result($SQL_Result, $i, 'zeit' );
                      $ugen   = mysql_result($SQL_Result, $i, 'gen' );
                      $xzeit[1] = "<b>E-Scan: ".$uzeit." ".$ugen."%:</b><br>";
                      $d[0]     = mysql_result($SQL_Result, $i, 'sfj' );
                      $d[1]     = mysql_result($SQL_Result, $i, 'sfb' );
                      $d[2]     = mysql_result($SQL_Result, $i, 'sff' );
                      $d[3]     = mysql_result($SQL_Result, $i, 'sfz' );
                      $d[4]     = mysql_result($SQL_Result, $i, 'sfkr' );
                      $d[5]     = mysql_result($SQL_Result, $i, 'sfsa' );
                      $d[6]     = mysql_result($SQL_Result, $i, 'sft' );
                      $d[8]     = mysql_result($SQL_Result, $i, 'sfka' );
                      $d[9]     = mysql_result($SQL_Result, $i, 'sfsu' );
                    }

                case 2: // MilitaerScan
                    if ($stype == "M") {
                      $uzeit  = mysql_result($SQL_Result, $i, 'zeit' );
                      $ugen   = mysql_result($SQL_Result, $i, 'gen' );
                      $xzeit[1] = "<b>M-Scan: ".$uzeit." ".$ugen."%:</b><br>";
                      $d[0]     = mysql_result($SQL_Result, $i, 'sf1j' )." : ".mysql_result($SQL_Result, $i, 'sf2j' ) ;
                      $d[1]     = mysql_result($SQL_Result, $i, 'sf1b' )." : ".mysql_result($SQL_Result, $i, 'sf2b' ) ;
                      $d[2]     = mysql_result($SQL_Result, $i, 'sf1f' )." : ".mysql_result($SQL_Result, $i, 'sf2f' ) ;
                      $d[3]     = mysql_result($SQL_Result, $i, 'sf1z' )." : ".mysql_result($SQL_Result, $i, 'sf2z') ;
                      $d[4]     = mysql_result($SQL_Result, $i, 'sf1kr' )." : ".mysql_result($SQL_Result, $i, 'sf2kr' ) ;
                      $d[5]     = mysql_result($SQL_Result, $i, 'sf1sa' )." : ".mysql_result($SQL_Result, $i, 'sf2sa' ) ;
                      $d[6]     = mysql_result($SQL_Result, $i, 'sf1t' )." : ".mysql_result($SQL_Result, $i, 'sf2t' ) ;
                      $d[8]     = mysql_result($SQL_Result, $i, 'sf1ka' )." : ".mysql_result($SQL_Result, $i, 'sf2ka' ) ;
                      $d[9]     = mysql_result($SQL_Result, $i, 'sf1su' )." : ".mysql_result($SQL_Result, $i, 'sf2su' ) ;
                    } elseif ($stype == "1" or $stype=="2") {
                        $uzeit  = mysql_result($SQL_Result, $i, 'zeit' );
                        $ugen   = mysql_result($SQL_Result, $i, 'gen' );
                        $xzeit[1] = "<b>Flotte Nr.".$stype.": ".$uzeit." ".$ugen."%:</b><br>";
                        $d[0]     = mysql_result($SQL_Result, $i, 'sf'.$stype.'j' );
                        $d[1]     = mysql_result($SQL_Result, $i, 'sf'.$stype.'b' );
                        $d[2]     = mysql_result($SQL_Result, $i, 'sf'.$stype.'f' );
                        $d[3]     = mysql_result($SQL_Result, $i, 'sf'.$stype.'z' );
                        $d[4]     = mysql_result($SQL_Result, $i, 'sf'.$stype.'kr' );
                        $d[5]     = mysql_result($SQL_Result, $i, 'sf'.$stype.'sa' );
                        $d[6]     = mysql_result($SQL_Result, $i, 'sf'.$stype.'t' );
                        $d[8]     = mysql_result($SQL_Result, $i, 'sf'.$stype.'ka' );
                        $d[9]     = mysql_result($SQL_Result, $i, 'sf'.$stype.'su' );
                    }

                case 3: // geschuetz
                    $uzeit  = mysql_result($SQL_Result, $i, 'zeit' );
                    $ugen   = mysql_result($SQL_Result, $i, 'gen' );
                    $xzeit[3] = "<b>G-Scan: ".$uzeit." ".$ugen."%:</b><br>";
                    $d[10]     = mysql_result($SQL_Result, $i, 'glo' );
                    $d[11]     = mysql_result($SQL_Result, $i, 'glr' );
                    $d[12]     = mysql_result($SQL_Result, $i, 'gmr' );
                    $d[13]     = mysql_result($SQL_Result, $i, 'gsr' );
                    $d[14]     = mysql_result($SQL_Result, $i, 'ga' );
            }
        } else {
            if ($type == 2 and ($punkte == -1 or $punkte == -2)) {
               // Flottenstatus 1 anzeigen:
               $flnr = $punkte * -1;
               $uzeit  = mysql_result($SQL_Result, $i, 'zeit' );
               $ugen   = mysql_result($SQL_Result, $i, 'gen' );
               $xzeit[1] = "<b>Flotte Nr.".$flnr.": ".$uzeit." ".$ugen."%:</b><br>";
               $d[0]     = mysql_result($SQL_Result, $i, 'sf'.$flnr.'j' );
               $d[1]     = mysql_result($SQL_Result, $i, 'sf'.$flnr.'b' );
               $d[2]     = mysql_result($SQL_Result, $i, 'sf'.$flnr.'f' );
               $d[3]     = mysql_result($SQL_Result, $i, 'sf'.$flnr.'z' );
               $d[4]     = mysql_result($SQL_Result, $i, 'sf'.$flnr.'kr' );
               $d[5]     = mysql_result($SQL_Result, $i, 'sf'.$flnr.'sa' );
               $d[6]     = mysql_result($SQL_Result, $i, 'sf'.$flnr.'t' );
               $d[8]     = mysql_result($SQL_Result, $i, 'sf'.$flnr.'ka' );
               $d[9]     = mysql_result($SQL_Result, $i, 'sf'.$flnr.'su' );

            }
        }
   }

   $output = "";
   if  ($xzeit[0] != '?') {
     $output .= $xzeit[0];
     for ($i=0; $i<5; $i++) {
       $output = $output.$EF[$i].": ".$sx[$i]." <br>";
     }
   }

   if  ($xzeit[1] != '?') {
     $output .= $xzeit[1];
     for ($i=0; $i<10; $i++) {
         if ($i != 7) {
           $output = $output.$SF[$i].": ".$d[$i]." <br>";
         }
     }
   }

   if  ($xzeit[3] != '?') {
     $output .= $xzeit[3];
     for ($i=10 ;$i<15; $i++) {
        $r = $i-10;
        $output = $output.$DF[$r].": ".$d[$i]." <br>";
     }
   }
   if ($output != '') {
     $output .= '<br>';
   } else {
     $output .= 'No Scans!';
   }
   return $output;
 }


function check_attflottenstatus($id,$flnr,$rg,$rp,$AttStatus,$lfd) {
      // 0 Vorbereitung gelb
      // 1 abgeflogen / gestartert gruen
      // 2 rueckflug / Abbruch rot
      global $ATTETA;
      global $tick_abzug;

      $ret =0;
      $SQL = 'SELECT * FROM `gn4accounts` WHERE id ="'.$id.'";';
		  $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
		  $SQL_Num = mysql_num_rows($SQL_Result);

		  if ($SQL_Num != 0) {
			  $ug = mysql_result($SQL_Result, 0, 'galaxie');
        $up = mysql_result($SQL_Result, 0, 'planet');

        $SQL = 'SELECT * FROM gn4flottenbewegungen WHERE angreifer_galaxie='.$ug.' AND angreifer_planet='.$up.' AND verteidiger_galaxie='.$rg.' AND verteidiger_planet='.$rp.' and (flottennr = '.$flnr.' or flottennr =0);';
        $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
        $SQL_Num = mysql_num_rows($SQL_Result);
  		  if ($SQL_Num != 0) {
            // 1 angriff
            // 2 deff rueckflug
            // 3 rueckflug att
            // 4 rueckflug deff
            $modus = mysql_result($SQL_Result, 0, 'modus');
            $flottennr = mysql_result($SQL_Result, 0, 'flottennr');
            if ($modus == 1 ) {
// Eta ermitteln
         			 $time1 = mysql_result($SQL_Result, 0, 'ankunft');
			         $time2 = mysql_result($SQL_Result, 0, 'flugzeit_ende');
			         $time3 = mysql_result($SQL_Result, 0, 'ruckflug_ende');
					     $ATTETA = getime4display(eta($time1) * $Ticks['lange'] - $tick_abzug);

               $ret = 1;
               if ($AttStatus < 2) {
                   // eine flotte ist gestaret .. Status wird geaendert
                  $SQL = 'UPDATE gn4attplanung set attstatus = 2 where lfd='.$lfd.';';
                  $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
               }

            } else if ($modus == 3) {
               $ret = 2;
            }
        }
     }
     return $ret;
   }

  function del_attplanlfd($lfd) {
        $SQL = 'DELETE FROM gn4attflotten WHERE lfd ='.$lfd.';';
        $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));

        $SQL = 'DELETE FROM gn4attplanung WHERE lfd ='.$lfd.';';
        $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
        // echo 'ATT-Ziel Nr. '.$lfd.' geloescht!';
  }

function AttAnzahl($Ally,$Meta,$type) {
  if ($type == 0) {
     $SQL = "SELECT count(lfd) as Anzahl FROM gn4attplanung WHERE (freigabe = 1) and (forall = 1 or formeta = ".$Meta." or forallianz = ".$Ally.");";
  } else {
     $SQL = "SELECT count(lfd) as Anzahl FROM gn4attplanung WHERE (freigabe = 1) and (forall = 1 or formeta = ".$Meta." or forallianz = ".$Ally.") and attstatus >2;";
  }
  $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
  $SQL_Num = mysql_num_rows($SQL_Result);
  if ($SQL_Num != 0) {
     return  mysql_result($SQL_Result, 0, "Anzahl");
  } else {
     return  0;
  }
}

function InfoText($Text) {
  $txt = ' onmouseover="return overlib(\''.$Text.'\');" onmouseout="return nd();" ';
  return  $txt;
}

function Get_Scan3($SQL_DBConn,$v_gala,$v_plan, $help, $punkte) {
       $output = OnMouseFlotte($v_gala, $v_plan,$punkte,"");
    //   $output="<a href=\"./main.php?modul=showgalascans&xgala=".$v_gala."&xplanet=".$v_plan."\"".($help?" onmouseover=\"return overlib('".$output."');\" onmouseout=\"return nd();\"":"").">".GetScans2($SQL_DBConn, $v_gala, $v_plan)."</a>";
       $refa ='<a href="./main.php?modul=showgalascans&xgala='.$v_gala.'&xplanet='.$v_plan.'" ';
       $output = $refa.InfoText($output).">".GetScans2($SQL_DBConn, $v_gala, $v_plan)."</a>";
       return $output;
}
function Get_Scan4($SQL_DBConn,$v_gala,$v_plan, $help, $punkte,$flnr) {
       $output = OnMouseFlotte($v_gala, $v_plan,$punkte,$flnr);
    //   $output="<a href=\"./main.php?modul=showgalascans&xgala=".$v_gala."&xplanet=".$v_plan."\"".($help?" onmouseover=\"return overlib('".$output."');\" onmouseout=\"return nd();\"":"").">".GetScans2($SQL_DBConn, $v_gala, $v_plan)."</a>";
       $refa ='<a href="./main.php?modul=showgalascans&xgala='.$v_gala.'&xplanet='.$v_plan.'" ';
       $output = $refa.InfoText($output).">".GetScans2($SQL_DBConn, $v_gala, $v_plan)."</a>";
       return $output;
}


function Get_ScanID($id, $help, $punkte) {

      global $SQL_DBConn;
      $SQL = 'SELECT * FROM `gn4accounts` WHERE id ="'.$id.'";';
		  $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
		  $SQL_Num = mysql_num_rows($SQL_Result);
		  if ($SQL_Num == 0)
			  return '???';
		  else {
       $v_gala = mysql_result($SQL_Result, 0, 'galaxie');
       $v_plan = mysql_result($SQL_Result, 0, 'planet');
 		   $tmp_result = $v_gala.':'.$v_plan.' '.mysql_result($SQL_Result, 0, 'name');

       $output = OnMouseFlotte($v_gala, $v_plan, $punkte,"");
       $refa ='<a href="./main.php?modul=showgalascans&xgala='.$v_gala.'&xplanet='.$v_plan.'" ';
       $output = $refa.InfoText($output).">".GetScans2($SQL_DBConn, $v_gala, $v_plan)."</a>";
       return $tmp_result.$output;
		  }
}

function Get_FlottenNr($id, $help, $flnr) {
      global $SQL_DBConn;
      $SQL = 'SELECT * FROM `gn4accounts` WHERE id ="'.$id.'";';
		  $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
		  $SQL_Num = mysql_num_rows($SQL_Result);
		  if ($SQL_Num == 0)
			  return '???';
		  else {
       $v_gala = mysql_result($SQL_Result, 0, 'galaxie');
       $v_plan = mysql_result($SQL_Result, 0, 'planet');

       $output = OnMouseFlotte($v_gala, $v_plan, $flnr*-1,"");
       $refa ='<a href="./main.php?modul=showgalascans&xgala='.$v_gala.'&xplanet='.$v_plan.'" ';
       $output = $refa.InfoText($output).">FL#".$flnr."</a>";
       return $output;
		  }
}



function GetAllianzName($id) {
      global $SQL_DBConn;
      $SQL = 'SELECT * FROM `gn4accounts` WHERE id ="'.$id.'";';
		  $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
		  $SQL_Num = mysql_num_rows($SQL_Result);
		  if ($SQL_Num == 0) {
			  return '';
		  } else {
         $SQL = 'SELECT * FROM `gn4allianzen` WHERE ticid ='.mysql_result($SQL_Result, 0, "ticid").';';
	    	 $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));
		     $SQL_Num = mysql_num_rows($SQL_Result);
		     if ($SQL_Num == 0)
			      return '';
		     else {
			      return mysql_result($SQL_Result, 0, "tag");
		     }
      }
}

include('functions2.php');

?>
