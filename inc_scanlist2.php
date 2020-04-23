<?
  // File: inc_scanlist2.php
  // by Bytehoppers from CCBLOCK

  $SQL = '';
  $SQLAND = '';
  $SSQL = '';
  $research = '';

  if (!isset($_GET["order"])) {
      $order = 'zeit';
      $order = 'SUBSTRING(zeit,13,4) DESC , SUBSTRING(zeit,10,2) DESC, SUBSTRING(zeit,7,2) DESC, SUBSTRING(zeit, 1,5) DESC';
  } else {
      $order = $_GET["order"];
//      $order = 'SUBSTRING(zeit,13,4), SUBSTRING(zeit,10,2), SUBSTRING(zeit,7,2), SUBSTRING(zeit, 1,5) ';
  }

  $DESC2 = "&DESC=1";
  if (!isset($_GET["DESC"])) {
      $DESC = '';
      $DESC2 = "&DESC=1";
  } else {
      $DESC = "DESC";
      $DESC2 = "";
  }

   for ($n = 0; $n < 19; $n++) {
     $r = "d".$n;
     if (!isset($_GET[$r])) {
         $d[$n] = 0;
     } else {
         $d[$n] =$_GET[$r];
         if ($_GET[$r]!=''){
            $SSQL = $SSQL."&".$r."=".$_GET[$r];
            $research = $research . '&'.$r.'='.$_GET[$r];
         }
     }
     $r = "fkt".$n;
     if (!isset($_GET[$r])) {
         $fkt[$n] = '';
     } else {
         $fkt[$n] =$_GET[$r];
         if ($d[$n]!='' and $d[$n] != 0){
             $SSQL = $SSQL."&".$r."=".$_GET[$r];
             $research = $research . '&'.$r.'='.$_GET[$r];
         }
     }

//     echo $r." d:".$d[$n]." FKT:".$fkt[$n]."<br>";

     if ($d[$n] != 0 AND $d[$n] != '' AND $fkt[$n] != '') {
       // SQLQuery Aufbau
          if ($n <10) {
             // schiffe
             if ($n != 7) {
                $SQL = $SQL.$SQLAND." sf".$Schiffe[$n]."  ".$fkt[$n]." ".$d[$n]." ";
             }
          } else {
            if ($n <16) {
               // Defensiveinheiten
               $nx = $n - 10;
               $SQL = $SQL.$SQLAND." g".$Defensiv[$nx]."  ".$fkt[$n]." ".$d[$n]." ";
             } else {
               if ($n == 16) {
                  $SQL = $SQL.$SQLAND." me ".$fkt[$n]." ".$d[$n]." ";
               } else if ($n == 17) {
                  $SQL = $SQL.$SQLAND." ke ".$fkt[$n]." ".$d[$n]." ";
               } else if ($n == 18) {
                  $SQL = $SQL.$SQLAND." pts ".$fkt[$n]." ".$d[$n]." ";
               }
             }
          }
          $SQLAND = " AND ";
     }
   }

   $stk = $Benutzer['punkte'] / $ATTOVERALL;
   if (!isset($_GET['angreifbar'])) {
   } else {
     $SQL = $SQL.$SQLAND." pts >= ".$stk." ";
   }

   $NOTICUSER = "SELECT galaxie FROM gn4accounts GROUP BY galaxie;";
   $SQL_Result = tic_mysql_query($NOTICUSER) or die(tic_mysql_error(__FILE__,__LINE__));
   $NOTIC = "";
   $TIC_Num = mysql_num_rows($SQL_Result);
   for ($n = 0; $n < $TIC_Num; $n++) {
       $galaxie = mysql_result($SQL_Result, $n, 'galaxie');
       if ($n == 0 ) {
          $NOTIC = " g<>".$galaxie;
       } else {
          $NOTIC = $NOTIC." AND rg<>".$galaxie;
       }
   }
   if ($SQL !='') {
      $SQL = $SQL." AND (".$NOTIC.")";
   } else {
      $SQL = "(".$NOTIC.")";
   }

//   echo  "Search-Sql: ".$SQL."<br>";
   include './ink_ScanHeader.php';

   $SQL = "SELECT * FROM `gn4scans` WHERE ".$SQL." ORDER BY ".$order." ".$DESC.";";
//   $SQL_Result = mysql_query($SQL, $SQL_DBConn);
   $SQL_Result = tic_mysql_query($SQL) or die(tic_mysql_error(__FILE__,__LINE__));

   include './ink_scanlistbody.php';

   echo '<CENTER>	   <BR>
       	<TABLE><TR><td bgcolor="#6F94AA" class="menutop"><B>
      	<A HREF="./main.php?modul=zielsuche'.$research.'".>Zielsuche überarbeiten:</A>
        </B></FONT></TD></TR></TABLE>
        </CENTER>';
?>

