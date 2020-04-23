<?PHP
    // Raenge
    $RangName[0] = 'Mitglied';
    $RangName[1] = 'Kommodore';
    $RangName[2] = 'Vize Admiral';
    $RangName[3] = 'Admiral';
    $RangName[4] = 'Techniker';
    $RangName[5] = 'Administrator';

    $RangImage[0] = 'bilder/rang/member.gif';
    $RangImage[1] = 'bilder/rang/stern-bronze.gif';
    $RangImage[2] = 'bilder/rang/stern-silber.gif';
    $RangImage[3] = 'bilder/rang/stern-gold.gif';
    $RangImage[4] = 'bilder/rang/schluessel-silber.gif';
    $RangImage[5] = 'bilder/rang/schluessel-gold.gif';

    $Rang_Mitglied = 0;
    $Rang_GC = 1;
    $Rang_VizeAdmiral = 2;
    $Rang_Admiral = 3;
    $Rang_Techniker = 4;
    $Rang_STechniker = 5;

    define("RANG_MITGLIED", 0);
    define("RANG_GC", 1);
    define("RANG_VIZEADMIRAL", 2);
    define("RANG_ADMIRAL", 3);
    define("RANG_TECHNIKER", 4);
    define("RANG_STECHNIKER", 5);

    // Scantypen
    $ScanTyp[0] = 'Unbekannt';
    $ScanTyp[1] = 'Militaerscan';
    $ScanTyp[2] = 'Newsscan';

    // Attplaner Typen von Bytehoppers 20.07.05
    $PlanerTyps[0] = "AllyScanner";
    $PlanerTyps[1] = "AllyPlaner";
    $PlanerTyps[2] = "MetaPlaner";
    $PlanerTyps[3] = "SuperGauPlaner";

   // gruen
    $ATTSTATUSINFO[0] = 'Planung';
    $ATTSTATUSHTML[0] = 'aaffaa';
   // gelb
    $ATTSTATUSINFO[1] = 'VOLL';
    $ATTSTATUSHTML[1] = 'FCB60E';
   // gruen
    $ATTSTATUSINFO[2] = 'GESTARTET';
    $ATTSTATUSHTML[2] = '0EBC1A';
  // dunkles rot
    $ATTSTATUSINFO[3] = 'STOP';
    $ATTSTATUSHTML[3] = 'F4DFAC';
 // pink
    $ATTSTATUSINFO[4] = 'WARN DEFFER!';
    $ATTSTATUSHTML[4] = 'E85AD6';
 // ROT
    $ATTSTATUSINFO[5] = 'ABBRUCH/RECALL!';
    $ATTSTATUSHTML[5] = 'FF1B1B';


    // Allianzcodes
    $AllianzCode[0] = "<FONT COLOR=#0000FF><B>DEFCON-0</B></FONT>";
    $AllianzCode[1] = "<FONT COLOR=#FFA500><B>DEFCON-1</B></FONT>";
    $AllianzCode[2] = "<FONT COLOR=#FF0000><B>DEFCON-2</B></FONT>";

    // Ticks
    $Ticks['angriffsflug'] = 30;
    $Ticks['angriff'] = 5;
    $Ticks['verteidigungsflug'] = 24;
    $Ticks['verteidigen'] = 20;

    //Zeitformat
    $Zeitformat[0] = 'Minuten';
    $Zeitformat[1] = 'Stunden:Minuten';
    $Zeitformat[2] = 'Ticks';

    define("LOG_SYSTEM", 0);
    define("LOG_ERROR", 1);
    define("LOG_SETSAFE", 2);

    $Ticks['lange'] = 15;
	$SQL_Result = mysql_query('SELECT value FROM gn4vars WHERE name="tickdauer";', $SQL_DBConn);
	if(mysql_num_rows($SQL_Result) == 1) {
		$Ticks['lange'] = mysql_result($SQL_Result,0,'value');
	}

$vag_faktor = 2; // Faktor fuer den Verlustausgleich

$db_format['sektor'] = array('pts', 's', 'd', 'me', 'ke', 'a');
$db_format['deff'] = array('lo', 'lr', 'mr', 'sr', 'a');
$db_format['schiffe'] = array('j', 'b', 'f', 'z', 'kr', 'sa', 't', 'ka', 'su');

$alg_format['sektor'] = array('punkte', 'schiffe', 'deff', 'me', 'ke', 'ast');
$alg_format['deff'] = array('lo', 'lr', 'mr', 'sr', 'aj');
$alg_format['schiffe'] = array('j', 'b', 'f', 'z', 'kr', 'sl', 'tr', 'ka', 'ss');

$html_format['sektor'] = array('Punkte', 'Schiffe', 'Defensiveinheiten',
				'Metall Exen', 'Kristall Exen', 'Asteroiden'
);
$html_format['deff'] = array('Leichtes Orbitalgesch&uuml;tz &quot;Rubium&quot;', 'Leichtes Raumgesch&uuml;tz &quot;Pulsar&quot;',
				'Mittleres Raumgesch&uuml;tz &quot;Coon&quot;', 'Schweres Raumgesch&uuml;tz &quot;Centurion&quot;',
				'Abfangj&auml;ger &quot;Horus&quot;'
);
$html_format['schiffe'] = array('J&auml;ger &quot;Leo&quot;', 'Bomber &quot;Aquilae&quot;', 'Fregatten &quot;Fornax&quot;',
				'Zerst&ouml;rer &quot;Draco&quot;', 'Kreuzer &quot;Goron&quot;', 'Schlachtschiffe &quot;Pentalin&quot;',
				'Tr&auml;ger &quot;Zenit&quot;', 'Kaperschiffe &quot;Kleptor&quot;&quot;', 'Schutzschiffe &quot;Cancri&quot;'
);

$kosten['m']['j'] = 4000; $kosten['k']['j'] = 6000;
$kosten['m']['b'] = 2000; $kosten['k']['b'] = 8000;
$kosten['m']['f'] = 15000; $kosten['k']['f'] = 7500;
$kosten['m']['z'] = 40000; $kosten['k']['z'] = 30000;
$kosten['m']['kr'] = 65000; $kosten['k']['kr'] = 85000;
$kosten['m']['sl'] = 250000; $kosten['k']['sl'] = 150000;
$kosten['m']['tr'] = 200000; $kosten['k']['tr'] = 50000;
$kosten['m']['ka'] = 1500; $kosten['k']['ka'] = 1000;
$kosten['m']['ss'] = 1000; $kosten['k']['ss'] = 1500;
$kosten['m']['lo'] = 6000; $kosten['k']['lo'] = 2000;
$kosten['m']['lr'] = 20000; $kosten['k']['lr'] = 10000;
$kosten['m']['mr'] = 60000; $kosten['k']['mr'] = 100000;
$kosten['m']['sr'] = 200000; $kosten['k']['sr'] = 300000;
$kosten['m']['aj'] = 1000; $kosten['k']['aj'] = 1000;

$scan_teil = array(
	"sek" => array("punkte", "schiffe", "deff", "me", "ke", "ast"),
	"unit" => array("j", "b", "f", "z", "kr", "sl", "tr", "ka", "ss"),
	"g" => array("lo", "lr", "mr", "sr", "aj"),
);
$scan_teil['mili'] = $scan_teil['unit'];

include './globalvars2.php';
?>
