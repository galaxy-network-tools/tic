<?php

echo "<html><head><title>Parser</title></head>";
if(isset($parse))
{
     $pos = 0;
    $keywords[0][0] = "Jäger";
    $keywords[0][1] = 1;
    $keywords[1][0] = "Bomber";
    $keywords[1][1] = 2;
    $keywords[2][0] = "Fregatte";
    $keywords[2][1] = 3;
    $keywords[3][0] = "Zerstörer";
    $keywords[3][1] = 4;
    $keywords[4][0] = "Kreuzer";
    $keywords[4][1] = 5;
    $keywords[5][0] = "Schlachtschiff";
    $keywords[5][1] = 6;
    $keywords[6][0] = "Trägerschiff";
    $keywords[6][1] = 7;
    $keywords[7][0] = "Kaperschiff";
    $keywords[7][1] = 8;
    $keywords[8][0] = "Schutzschiff";
    $keywords[8][1] = 9;
    $keywords[9][0] = "LO";
    $keywords[9][1] = 10;
    $keywords[10][0] = "LR";
    $keywords[10][1] = 11;
    $keywords[11][0] = "MR";
    $keywords[11][1] = 12;
    $keywords[12][0] = "SR";
    $keywords[12][1] = 13;
    $keywords[13][0] = "AJ";
    $keywords[13][1] = 14;
    $keywords[14][0] = "Leichtes Orbitalgeschütz";
    $keywords[14][1] = 10;
    $keywords[15][0] = "Leichtes Raumgeschütz";
    $keywords[15][1] = 11;
    $keywords[16][0] = "Mittleres Raumgeschütz";
    $keywords[16][1] = 12;
    $keywords[17][0] = "Schweres Raumgeschütz";
    $keywords[17][1] = 13;
    $keywords[18][0] = "Abfangjäger";
    $keywords[18][1] = 14;
    $keywords[19][0] = "Rubium";
    $keywords[19][1] = 10;
    $keywords[20][0] = "Pulsar";
    $keywords[20][1] = 11;
    $keywords[21][0] = "Coon";
    $keywords[21][1] = 12;
    $keywords[22][0] = "Centurion";
    $keywords[22][1] = 13;
    $keywords[23][0] = "Horus";
    $keywords[23][1] = 14;
    $keywords[24][0] = "Fregatten";
    $keywords[24][1] = 3;
    $keywords[25][0] = "Metall-Extraktoren";
    $keywords[25][1] = 15;
    $keywords[26][0] = "Kristall-Extraktoren";
    $keywords[26][1] = 16;
	for($i=0;$i<count($keywords);$i++)
	{
	    $pos = strpos($parse, $keywords[$i][0]);
	    while($pos!==false)
	    {
		$pos+=strlen($keywords[$i][0]);
		while($parse[$pos]==" " || $parse[$pos]==":")
		    $pos++;
		$end = min(strpos($parse, "\r\n", $pos), strpos($parse, " ", $pos), strpos($parse, "	", $pos));
		if($end===false)
		    $end=strlen($parse);

		$numbers[$keywords[$i][1]]+=substr($parse, $pos, $end-$pos);
		$pos = strpos($parse, $keywords[$i][0], $pos);
	    }
	}
    echo "<script>
	var oldwindow=0;
	function SetVars()
	{";
    for($i=0;$i<15;$i++)
    {
	if(isset($numbers[$i]))
	{
	    if($for==0)
		echo "\r\n			window.opener.form1.a".$i.".value = ".$numbers[$i].";";
	    else
 		echo "\r\n			window.opener.form1.v".$i.".value = ".$numbers[$i].";";
	}
   }
	if(isset($numbers[15]))
            echo "window.opener.form1.me.value = ".$numbers[15].";";
	if(isset($numbers[16]))
            echo "window.opener.form1.ke.value = ".$numbers[16].";";

    echo "\r\n	}
	SetVars();";
}
else
echo "<script>";

echo"
	</script>";
echo "<body bgcolor=#999999 text=#000000>
<form type='get'name='form1' action='$PHP_SELF'><textarea name='parse' cols=32 rows=10>$parse</textarea><br>für: <select name='for'>";
	if($for==0)
	    echo "<option value='0' selected>Angreifer</option><option value='1'>Verdeidiger</option></select>";
	else
	    echo "<option value='0'>Angreifer</option><option value='1' selected>Verdeidiger</option></select>";
echo " <input type='submit' value='Parse' onClick=\"exit=0;\"></form>
</body>
</html>";

?>