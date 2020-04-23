<center>
<?php

    if ( !isset( $_GET['uid'] )){
        echo '<font color="#800000" size="-1"><b>internal error - no uid set</b></font>';
        return;
    }
    if ( !isset( $Benutzer['rang'])) $Benutzer['rang'] = '0';
    if ( $Benutzer['rang']<'1') die('Rang zu Niedrig');

    $sql = "select * from gn4accounts where id='".$_GET['uid']."';";
    $SQL_result = tic_mysql_query( $sql, $SQL_DBConn);
    $SQL_Num = mysql_num_rows( $SQL_result );
    if ( $SQL_Num == 0 ) {
        echo '<font color="#800000" size="-1"><b>internal error - db access failed</b></font>';
        return;
    }
    $selgala = mysql_result( $SQL_result, 0, 'galaxie');
    $planet  = mysql_result( $SQL_result, 0, 'planet');
    $rang    = mysql_result( $SQL_result, 0, 'rang');
    $name    = mysql_result( $SQL_result, 0, 'name');
    $alli    = mysql_result( $SQL_result, 0, 'allianz');
    $umode   = mysql_result( $SQL_result, 0, 'umod');
    $lastlog = mysql_result( $SQL_result, 0, 'lastlogin');
    $spy     = mysql_result( $SQL_result, 0, 'spy');
    $SQL_Result1 = tic_mysql_query("select tag from gn4allianzen where id='".$alli."';",$SQL_DBConn);
    $allitag = mysql_result( $SQL_Result1, 0, 'tag');
    if( $Benutzer['rang'] == '1') {
    if($selgala != $Benutzer['galaxie']) die('Du hast nur recht in deiner Gala');
    }
    // parm-vars: change gala planet pw alliid umode name rang spy
    if ( !isset( $_GET['change'] )){
        echo '<font color="#800000" size="-1"><b>no access - invalid function</b></font>';
        return;
    }



    echo '<form action="./main.php?modul=userman" method="post">';
    echo '<input type="hidden" name="action" value="userman" />';
    echo '<input type="hidden" name="change" value="'.$_GET['change'].'" />';
    echo '<input type="hidden" name="selgala" value="'.$selgala.'" />';
    echo '<input type="hidden" name="selplanet" value="'.$planet.'" />';
    echo '<input type="hidden" name="uid" value="'.$_GET['uid'].'" />';

    echo '<table>';
    echo '<tr align="center"><td class="datatablehead" colspan="2">Benutzerdaten</td></tr>';

    // galaxie
    echo '<tr class="fieldnormallight">';
    echo '<td>Galaxie:</td>';
    echo '<td>';
    if ( $_GET['change']=='koords' ) {
        echo '<input type="text" name="selgala" maxlength="4" value="'.$selgala.'" />';
        echo '<input type="hidden" name="selname" value="'.$name.'" />';
    } else {
        echo $selgala;
    }
    echo '</td>';
    echo '</tr>';

    // planet
    echo '<tr class="fieldnormaldark">';
    echo '<td>Planet:</td>';
    echo '<td>';
    if ( $_GET['change']=='koords' ) {
        echo '<input type="text" name="planet" maxlength="2" value="'.$planet.'" />';
    } else {
        echo $planet;
    }
    echo '</td>';
    echo '</tr>';

    // name
    echo '<tr class="fieldnormallight">';
    echo '<td>Name:</td>';
    echo '<td>';
    if ( $_GET['change']=='name' ) {
        echo '<input type="text" name="name" maxlength="50" value="'.$name.'" />';
    } else {
        echo $name;
    }
    echo '</td>';
    echo '</tr>';

    // rang
    echo '<tr class="fieldnormaldark">';
    echo '<td>Rang:</td>';
    echo '<td>';
    if ( $_GET['change']=='rang' ) {
        if ($Benutzer['rang'] >= $Rang_GC && $rang <= $Benutzer['rang'] ) {
            echo '<select name="rang" size="1">';
            for ( $n=0; $n<count( $RangName ); $n++ ) {
                $zusatz = '';
                if ($n == $rang )
                    $zusatz = ' selected="selected"';

                // man kann leute nur max. auf das eigene level "befördern"
                if ( $Benutzer['rang'] >= $n )
                    echo '<option value="'.$n.'"'.$zusatz.'>'.$RangName[$n].'</option>';
                else
                    break;
            }
            echo '</select>';
        }
    } else {
        echo $RangName[$rang];
    }
    echo '</td>';
    echo '</tr>';

    // pw
    echo '<tr class="fieldnormallight">';
    echo '<td>Passwort:</td>';
    echo '<td>';
    if ( $_GET['change']=='pw' ) {
        echo '<input type="text" name="pw" maxlength="20" />';
    } else {
        echo 'xxxxxxxxx';
    }
    echo '</td>';
    echo '</tr>';

    // umode
    echo '<tr class="fieldnormaldark">';
    echo '<td>UMode:</td>';
    echo '<td>';
    if ( $_GET['change']=='umode' ) {
        $add = '';
        if ( $umode!='' )
            $add=' checked="checked"';
        echo '<input type="checkbox" name="umode"'.$add.' />';
        echo '<input type="text" value="tt.mm.jjjj" name="umodedate" maxlength="10" />';
    } else {
        echo $umode;
    }
    echo '</td>';
    echo '</tr>';

    // allianz
    echo '<tr class="fieldnormallight">';
    echo '<td>Allianz:</td>';
    echo '<td>';
    if ( $_GET['change']=='allianz' ) {

        echo '<select name="allianz" size="1">';
        foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
            if ( $AllianzNummer == $alli )
                $zusatz = ' selected="selected"';
            echo '<option value="'.$AllianzNummer.'"'.$zusatz.'>'.$AllianzTag[$AllianzNummer].' '.$AllianzNummerName.'</option>';
        }
        echo '</select>';

    } else {
        echo $allitag;
    }
    echo '</td>';
    echo '</tr>';

    // sperren
	    echo '<tr class="fieldnormaldark">';
	    echo '<td>';
	    echo 'Sperren:';
	    echo '</td>';
	    echo '<td>';
	    if ( $_GET['change']=='spy' ) {
	        $add = '';
	        if ( $spy!='0' )
	            $add=' checked';
	        echo '<input type="checkbox" name="spy"'.$add.' value="gesperrt" />';
	    } else {

	        //status-anzeige

	        if ($spy == 1)
                {
                $status = '<font color="#ff0000">Gesperrt</font>';
                } else {
					if(mysql_result( $SQL_result, 0, 'versuche') >= 3 && mysql_result( $SQL_result, 0, 'ip') != "")
					    $status = '<font color="#cc0000">IP '.mysql_result( $SQL_result, 0, 'ip').' gesperrt</font>';
					else
                        $status = '<font color="#00cc00">Entsperrt</font>';
                }
	        echo $status;
	    }
	    echo '</td>';
    echo '</tr>';

    echo '<tr class="datatablefoot" align="center">';
    echo '<td colspan="2"><input type="submit" value="&Auml;ndern" /></td>';
    echo '</tr>';
    echo '</table>';
    echo '</form>';





?>
</center>
