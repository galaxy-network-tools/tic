<?php
/***************************************************************************
 *   Copyright (C) 2006 by                                                 *
 *      Pascal Gollor <pascal@gollor1.de> -- irc://irc.quakenet.org/Hugch  *
 *      Andreas Hemel <dai.shan@gmx.net>                                   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA              *
 ***************************************************************************/

$scan_typ = array("sek" => 0, "unit" => 1, "g" => 3, "mili" => 2);
$scan_teil = array(
    "sek" => array("punkte", "schiffe", "deff", "me", "ke", "ast"),
    "unit" => array("j", "b", "f", "z", "kr", "sl", "tr", "ka", "ss"),
    "g" => array("lo", "lr", "mr", "sr", "aj"),
);
$scan_teil['mili'] = $scan_teil['unit'];
$scan_array['sek'] = array(
    "punkte" => array("punkte", "pkt"),
    "schiffe" => array("schiffe", "schiffsanzahl"),
    "deff" => array("gesch.*tze", "deff", "deffensiv", "verteidigung", "gesch.*zanzahl"),
    "me" => array("metall-extrakoren", "metall-exen", "m-extraktoren", "m-exxen", "me", "mextrakoren", "mexxen", "m-exen", "metallextraktoren"),
    "ke" => array("kristall-extrakoren", "kristall-exen", "k-extraktoren", "k-exxen", "ke", "kextrakoren", "kexxen", "k-exen", "kristallextraktoren"),
    "ast" => array("asteroiden", "ast", "astr", "astros")
);
$scan_array['unit'] = array (
    "j" => array("j.*?ger", "leo", "j.*?g"),
    "b" => array("bomber", "aquilae", "bom"),
    "f" => array("fregatten", "fornax", "freggs", "fregs", "freg", "fre"),
    "z" => array("zerst.*rer", "draco", "zerris", "zer"),
    "kr" => array("kreuzer", "goron", "kreu", "kre"),
    "sl" => array("schlachtschiffe", "pentalin", "schlachter", "schl"),
    "tr" => array("tr.*gerschiffe", "zenit", "tr.*ger", "tr.*"),
    "ka" => array("kaperschiffe", "cleptor", "cleptoren", "kleptoren", "kap", "cleps", "kleps"),
    "ss" => array("schutzschiffe", "cancri", "cancris", "sch.*tzis", "schu", "cancs")
);
$scan_array['mili'] = $scan_array['unit'];
$scan_array['g'] = array(
    "lo" => array("rubium", "lo"),
    "lr" => array("pulsar", "lr"),
    "mr" => array("coon", "mr"),
    "sr" => array("centurion", "sr"),
    "aj" => array("horus", "abfangj.*ger", "aj")
);
$scan_fehlermeldung = 'Dieser IRC-Scan konnte <font color="#FF0000">nicht</font> eindeutig identifiziert werden!!!'.
    '<br>Wenn es dennoch einer ist, dann wende dich bitte an einen der TIC-Technicker!!!';

$scan_speichern = new scan_speichern;
$scan_speichern->debug = $scan_debug;

function irc_scan_array($scan, $typ) {
    global $scan_start;

    $muster = array(0 => '/'.chr(3).'[0-9]{2},[0-9]{2}/', 1 => '/\(/', 2 => '/\)/', 3 => '/\[/', 4 => '/\]/', 5 => '/\{/',
        6 => '/\}/', 7 => '/</', 8 => '/>/', 9 => '/\@/', 10 => '/\|/',
        11 => '/'.chr(32).'+/', 12 => '/(Orbit:)/', 13 => '/(Im Orbit:)/', 14 => '/(Im Orbit)/', 15 => '/(Flotte 1:)/',
        16 => '/(Flotte 2:)/', 17 => '/(Flotte1:)/', 18 => '/(Flotte2:)/', 19 => '/(flug'.chr(32).')/', 20 => '/(:'.chr(32).')/', 21 => '/'.chr(3).'/');
    $ersetzen = array(0 => '', 1 => '', 2 => '', 3 => '', 4 => '', 5 => '',
        6 => '', 7 => '', 8 => '', 9 => '', 10 => '',
        11 => chr(32), 12 => 'im_orbit', 13 => 'im_orbit', 14 => 'im-orbit', 15 => 'flotte_1',
        16 => 'flotte_2', 17 => 'flotte_1', 18 => 'flotte_2', 19 => 'flug_', 20 => ':_', 21 => '');
    $scan = preg_replace($muster, $ersetzen, $scan);

    $array = preg_split('/'.chr(13).chr(10).'/', $scan);
    $len0 = count($array);
    for ($i0 = 0; $i0 < $len0; $i0++) {
        if (preg_match('/('.$typ.')/', strtolower($array[$i0])) && preg_match('/%/', $array[$i0])) {
            $scan_start = $i0;
            break;
        }
    }
    if (!isset($scan_start)) {
        if ($GLOBALS['scan_debug'] == 1) {
            echo $GLOBALS['scan_fehlermeldung'];
        } else {
            die($GLOBALS['scan_fehlermeldung']);
        }
    }

    $array = preg_array_split('/'.chr(32).'/', $array);
    return $array;
}

function irc_scan_kopf($kopf, $zeit = 0, $scanner = 0) {
    global $Benutzer;

    $len0 = count($kopf);
    for ($i0 = 0; $i0 < $len0; $i0++) {
        $teil = preg_replace('/[^0-9:]/', '', $kopf[$i0]);
        if (koord_check($teil)) {
            $split = preg_split('/:/', $teil);
            $scan['gala'] = $split[0];
            $scan['planet'] = $split[1];
            $scan['name'] = $kopf[$i0 - 1];
        }
        $teil = preg_replace('/[^0-9%]/', '', $kopf[$i0]);
        if (preg_match('/[0-9]{1,3}(\%)/', $teil)) {
            $scan['gen'] = preg_replace('/\%/', '', $teil);
        }
    }
    if (is_array($scan) === false) {
        die($GLOBALS['scan_fehlermeldung']);
    }
    $scan['scanner']['name'] = $Benutzer['name'];
    $scan['scanner']['gala'] = $Benutzer['galaxie'];
    $scan['scanner']['planet'] = $Benutzer['planet'];
    $scan['datum'] = date("H:i d.m.Y");
    if ($zeit != 0) { $scan['datum'] = $zeit; }
    if ($scanner != 0) {
        $scan['scanner']['name'] = "IRC-Bot";
        $scan['scanner']['gala'] = "0";
        $scan['scanner']['planet'] = "1";
    }
    return $scan;
}

function irc_scan_flotten_suche($array) {
    $len0 = count($array);
    $zu[1] = $len0;
    for ($i0 = 0; $i0 < $len0; $i0++) {
        if (stristr($array[$i0], "im_orbit")) {
            $zu[0] = 0;
            return $zu;
        } elseif (stristr($array[$i0], "flotte_1")) {
            $zu[0] = 1;
            return $zu;
        } elseif (stristr($array[$i0], "flotte_2")) {
            $zu[0] = 2;
            return $zu;
        }
    }
    $zu[0] = 3;
    return $zu;
}

function scan_zeichen_entfernen($string) {
    //$muster[0] = '/^(.*\_)/';
    //$muster[1] = '/\./';
    //$ersetzen[0] = '';
    //$ersetzen[1] = '';
    $muster[0] = '/[^0-9]/';
    $ersetzen[0] = '';
    return preg_replace($muster, $ersetzen, $string);
}

function scan_string_suche($array, $scan_start, $typ) {
    global $scan_array, $scan_teil;

    settype($scan_start, "integer");
    $len0 = count($array);
    for ($i0 = 0 + $scan_start; $i0 < $len0; $i0++) {
        $len1 = count($array[$i0]);
        for ($i1 = 0; $i1 < $len1; $i1++) {
            $len2 = count($scan_teil[$typ]);
            for ($i2 = 0; $i2 < $len2; $i2++) {
                $art = $scan_teil[$typ][$i2];
                $len3 = count($scan_array[$typ][$art]);
                for ($i3 = 0; $i3 < $len3; $i3++) {
                    if ($typ != "mili") {
                        if (preg_match('/^('.$scan_array[$typ][$art][$i3].':)/', strtolower($array[$i0][$i1])) && !isset($rueck[$art])) {
                            $rueck[$art] = scan_zeichen_entfernen($array[$i0][$i1]);
                        } elseif (preg_match('/^('.$scan_array[$typ][$art][$i3].'$)/', strtolower($array[$i0][$i1])) && !isset($rueck[$art])) {
                            $rueck[$art] = scan_zeichen_entfernen($array[$i0][$i1 + 1]);
                        }
                    } else {
                        $flotte = irc_scan_flotten_suche($array[$i0]);
                        if (preg_match('/('.$scan_array[$typ][$art][$i3].')/', strtolower($array[$i0][$i1])) && !isset($rueck[$flotte[0]][$art])) {
                            $schiff = scan_zeichen_entfernen($array[$i0][$i1 - 1]);
                            if ($schiff != "") { $rueck[$flotte[0]][$art] = $schiff; }
                        }
                    }
                }
            }
        }
        if ($typ == "mili") {
            $flotte = irc_scan_flotten_suche($array[$i0]);
            $rueck[$flotte[0]]['status'] = $array[$i0][$flotte[1] - 1];
        }
    }
    return $rueck;
}

function irc_sek_scan($scan, $zeit = 0, $scanner = 0) {
    global $scan_start, $scan_speichern;

    $array = irc_scan_array($scan, "sektorscan");
    unset($scan);
    $scan = irc_scan_kopf($array[$scan_start], $zeit, $scanner);
    $sek = scan_string_suche($array, $scan_start, "sek");
    $scan_speichern ->sek($scan, $sek);
}

function irc_unit_scan($scan, $zeit = 0, $scanner = 0) {
    global $scan_start, $scan_speichern;

    $array = irc_scan_array($scan, "einheitenscan");
    unset($scan);
    $scan = irc_scan_kopf($array[$scan_start], $zeit, $scanner);
    $unit = scan_string_suche($array, $scan_start, "unit");
    $scan_speichern ->unit($scan, $unit);
}

function irc_g_scan($scan, $zeit = 0, $scanner = 0) {
    global $scan_start, $scan_speichern;

    $array = irc_scan_array($scan, "gesch.*zscan");
    unset($scan);
    $scan = irc_scan_kopf($array[$scan_start], $zeit, $scanner);
    $g = scan_string_suche($array, $scan_start, "g");
    $scan_speichern ->g($scan, $g);
}

function irc_mili_scan($scan, $zeit = 0, $scanner = 0) {
    global $scan_start, $scan_speichern;

    $array = irc_scan_array($scan, "mili.*scan");
    unset($scan);
    $scan = irc_scan_kopf($array[$scan_start], $zeit, $scanner);
    $mili = scan_string_suche($array, $scan_start, "mili");
    unset($mili[3]);
    unset($mili[0]['status']);

    for ($i0 = 1; $i0 <= 2; $i0++) {
        unset($split);
        $split = preg_split('/^(.*?_)/', $mili[$i0]['status'], -1, PREG_SPLIT_DELIM_CAPTURE);
        if (!stristr($split[1], "flug")) {
            $split = preg_split('/^(.*?_)/', $split[2], -1, PREG_SPLIT_DELIM_CAPTURE);
        }
        $mili[$i0]['ziel'] = $split[2];
        if (preg_match('/(r.*kflug\_)/', strtolower($mili[$i0]['status']))) {
            $mili[$i0]['status'] = 3;
        } elseif (stristr($mili[$i0]['status'], "verteidigungsflug_")) {
            $mili[$i0]['status'] = 2;
        } elseif (stristr($mili[$i0]['status'], "angriffsflug_")) {
            $mili[$i0]['status'] = 1;
        } else {
            $mili[$i0]['status'] = 0;
            $mili[$i0]['ziel'] = "";
        }
    }
    $scan_speichern->mili($scan, $mili);
}

function irc_flotte ($flotte)
{
    //echo '<pre>';
    global $scan_speichern, $scan_teil, $scan_array;

    $flotte = preg_replace('/'.chr(3).'\d{2},\d{2}/', ' ', $flotte);
    preg_match('/flotte (\d)/i', $flotte, $match);
    $fleet = $match[1];
    if (!is_numeric($fleet) || $fleet < 0 || $fleet > 2) { $fleet  = 0; }

    $flotte = preg_replace('/\s/', ' ', $flotte);
    $flotte = preg_split('/ /', $flotte);
    foreach ($flotte as $key => $value) {
        $koord = preg_replace('/[^:\d]/', '', $value);
        if ($koord[strlen($koord) - 1] = ":") { $koord = substr($koord, 0, -1); }
        if (!isset($scan['koord']) && preg_match('/^\d+:\d+$/', $koord)) {
            $scan['koord'] = $koord;
            $scan['gala'] = preg_replace('/(\d+):(\d+)/', '$1', $scan['koord']);
            $scan['planet'] = preg_replace('/(\d+):(\d+)/', '$2', $scan['koord']);
            $scan['gen'] = 100;
            $scan['datum'] = date("H:i d.m.Y");
            $scan['scanner']['name'] = "IRC-Bot";
            $scan['scanner']['gala'] = "0";
            $scan['scanner']['planet'] = "1";
        }
        if ($key > 4) {
            foreach ($scan_array['mili'] as $key1 => $value1) {
                foreach ($value1 as $value2) {
                    if (preg_match('/'.$value2.'/', strtolower($value))) {
                        $mili[$fleet][$key1] = preg_replace('/\D/', '', $flotte[$key - 1]);
                        break 1;
                    }
                }
                if (!isset($mili[$fleet][$key1])) { $mili[$fleet][$key1] = 0; }
            }
        }
    }

    foreach ($scan_teil['unit'] as $value) {
        if (!isset($mili[0][$value])) { $mili[0][$value] = 0; }
        if (!isset($mili[1][$value])) { $mili[1][$value] = 0; }
        if (!isset($mili[2][$value])) { $mili[2][$value] = 0; }
    }

    if (isset($scan) && isset($mili)) { $scan_speichern->mili($scan, $mili); } else { echo '<font>Fehler!!!</font>'; }
    //print_r($scan);
    //print_r($mili);
    //echo '</pre>';
}

function scan_pruefen($scan) {
    global $scan_speichern, $scan_debug;

    $scan_speichern->echo = 1;
    if (stristr($scan, "sektorscan")) {
        irc_sek_scan($_POST['irc_scan']);
    }
    if (stristr($scan, "einheitenscan")) {
        irc_unit_scan($_POST['irc_scan']);
    }
    if (preg_match('/(gesch.*zscan)/', strtolower($scan))) {
        irc_g_scan($_POST['irc_scan']);
    }
    if (preg_match('/(mili.*scan)/', strtolower($scan))) {
        irc_mili_scan($_POST['irc_scan']);
    }
    if (preg_match('/flotte.*von/iU', $scan)) {
        irc_flotte($scan);
    }
}

?>
