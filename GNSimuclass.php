<?
//////////////////////////////////////////////////////
//
// Version 0.1
//
// Copyright (C) 2005  Lars-Peter 'laprican' Clausen(laprican@laprican.de)
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
    class Fleet
    {
        var $OldShips;      // Schiffe, die im letzten Tick in der Flotte waren
        var $Ships;         // Schiffe, die diesen Tick in der Flotte sind
        var $LostShips;     // Schiffe, die diese Flotte verloren hat
        var $StolenExenM;   // Von Dieser Flotte gestohlene Kristallextraktoren
        var $StolenExenK;   // Von Dieser Flotte gestohlene Kristallextraktoren
        var $TicksToWait;   // Dauer in Ticks, bis die Flotte angreift/verteidgt
        var $TicksToStay;   // Wieviele Ticks die Flotte angreift/verteidgt
    }
    class GNSimu_Multi
    {
        var $AttFleets;
        var $DeffFleets;
        var $Deff;      // Gesch&uuml;tze des Verteidigers
        var $Exen_M;    // Metall-Extarktoren des Verteidigers
        var $Exen_K;    // Kristall-Extarktoren des Verteidigers
        var $shipdata;
        function GNSimu_Multi()
        {
            // Daten für Jäger Nr. 0
            $this->shipdata[0]['name'] = "Jäger";
            $this->shipdata[0]['attakpower']  = array(0.0246, 0.392, 0.0263); // Wie viele Schiffe ein Schiff mit 100% Feuerkrafft zerstören wrde
            $this->shipdata[0]['shiptoattak'] = array(11,1,4); // Welche Schiffe/Gesch&uuml;tze angegriffen werden
            $this->shipdata[0]['percent']     = array(0.35,0.30,0.35); // Die Verteilung der Prozente, mit der auf die Schiffe geschoßen wird.
            $this->shipdata[0]['cost'] = array(4000, 6000);
            // Daten für Bomber Nr. 1
            $this->shipdata[1]['attakpower']  = array(0.0080,0.0100,0.0075);
            $this->shipdata[1]['shiptoattak'] = array(12,5,6);
            $this->shipdata[1]['percent']     = array(0.35,0.35,0.30);
            $this->shipdata[1]['name'] = "Bomber";
            $this->shipdata[1]['cost'] = array(2000, 8000);
            // Daten für Fregatte Nr. 2
            $this->shipdata[2]['attakpower']  = array(4.5,0.85);
            $this->shipdata[2]['shiptoattak'] = array(13,0);
            $this->shipdata[2]['percent']     = array(0.6,0.4);
            $this->shipdata[2]['name'] = "Fregatte";
            $this->shipdata[2]['cost'] = array(15000, 7500);
            // Daten für Zerstörer Nr. 3
            $this->shipdata[3]['attakpower']  = array(3.5,1.2444);
            $this->shipdata[3]['shiptoattak'] = array(9,2);
            $this->shipdata[3]['percent']     = array(0.6,0.4);
            $this->shipdata[3]['name'] = "Zerstörer";
            $this->shipdata[3]['cost'] = array(40000, 30000);
            // Daten für Kreuzer Nr. 4
            $this->shipdata[4]['attakpower']  = array(2.0,0.8571,10.0);
            $this->shipdata[4]['shiptoattak'] = array(10,3,8);
            $this->shipdata[4]['percent']     = array(0.35,0.30,0.35);
            $this->shipdata[4]['name'] = "Kreuzer";
            $this->shipdata[4]['cost'] = array(65000, 85000);
            // Daten für Schalchtschiff Nr. 5
            $this->shipdata[5]['attakpower']  = array(1.0,1.0666,0.4,0.3019,26.6667);
            $this->shipdata[5]['shiptoattak'] = array(11,4,5,6,8);
            $this->shipdata[5]['percent']     = array(0.2,0.2,0.2,0.2,0.2);
            $this->shipdata[5]['name'] = "Schlachtschiff";
            $this->shipdata[5]['cost'] = array(250000,  150000);
            // Daten für Trägerschiff Nr. 6
            $this->shipdata[6]['attakpower']  = array(25.0,14.0);
            $this->shipdata[6]['shiptoattak'] = array(7,8);
            $this->shipdata[6]['percent']     = array(0.5,0.5);
            $this->shipdata[6]['cost'] = array(200000, 50000);
            $this->shipdata[6]['name'] = "Trägerschiff";
            // Daten für Kaperschiff
            $this->shipdata[7]['cost'] = array(1500, 1000);
            $this->shipdata[3]['name'] = "Kaperschiff";
            // Daten fr Schutzschiff
            $this->shipdata[8]['cost'] = array(1000, 1500);
            $this->shipdata[8]['name'] = "Schutzschiff";
            // Daten für Leichtes Obligtalgeschütz Nr. 9
            $this->shipdata[9]['attakpower']  = array(0.3,1.28);
            $this->shipdata[9]['shiptoattak'] = array(0,7);
            $this->shipdata[9]['percent']     = array(0.6,0.4);
            $this->shipdata[9]['cost'] = array(6000, 2000);
            $this->shipdata[9]['name'] = "Leichtes Obligtalgeschütz";
            // Daten für Leichtes Raumgeschütz Nr. 10
            $this->shipdata[10]['attakpower']  = array(1.2,0.5334);
            $this->shipdata[10]['shiptoattak'] = array(1,2);
            $this->shipdata[10]['percent']     = array(0.4,0.6);
            $this->shipdata[10]['cost'] = array(20000, 10000);
            $this->shipdata[10]['name'] = "Leichtes Raumgeschütz";
            // Daten für Mittleres Raumgeschütz Nr. 11
            $this->shipdata[11]['attakpower']  = array(0.9143,0.4267);
            $this->shipdata[11]['shiptoatta'] = array(3,4);
            $this->shipdata[11]['percent']     = array(0.4,0.6);
            $this->shipdata[11]['cost'] =  array(60000, 100000);
            $this->shipdata[11]['name'] = "Mittleres Raumgeschütz";
            // Daten für Schweres Raumgeschütz Nr. 12
            $this->shipdata[12]['attakpower']  = array(0.5,0.3774);
            $this->shipdata[12]['shiptoattak'] = array(5,6);
            $this->shipdata[12]['percent']     = array(0.5,0.5);
            $this->shipdata[12]['cost'] = array(200000, 300000);
            $this->shipdata[12]['name'] = "Schweres Raumgeschütz";
            // Daten für  Abfangjäger Nr. 13
            $this->shipdata[10]['attakpower']  = array(0.0114,0.32);
            $this->shipdata[10]['shiptoattak'] = array(3,7);
            $this->shipdata[10]['percent']     = array(0.4,0.6);
            $this->shipdata[10]['cost'] = array(1000, 1000);
            $this->shipdata[10]['name'] = "Abfangjäger";
        }
        function Tick($debug)
        {
            for($i = 0;$i < count($this->AttFleets);$i++)
            {
                if($this->AttFleets[$i]->TicksToWait == 0)
                {
                    $this->AttFleets[$i]->TicksToStay--;
                    if($this->AttFleets[$i]->TicksToStay >= 0)
                    {
                        for($j = 0;$j < 9;$j++)
                            $TotalAtt[$j] += $this->AttFleets[$i]->Ships[$j];
                    }
                }
                else
                {
                    $this->AttFleets[$i]->TicksToWait--;
                }
                $this->AttFleets[$i]->OldShips = $this->AttFleets[$i]->Ships;
            }
            for($i = 0;$i < count($this->DeffFleets);$i++)
            {
                if($this->DeffFleets[$i]->TicksToWait == 0)
                {
                    $this->DeffFleets[$i]->TicksToStay--;
                    if($this->DeffFleets[$i]->TicksToStay >= 0)
                    {
                        for($j = 0;$j < 9;$j++)
                            $TotalDeff[$j] += $this->DeffFleets[$i]->Ships[$j];
                    }
                }
                else
                {
                    $this->DeffFleets[$i]->TicksToWait--;
                }
                $this->DeffFleets[$i]->OldShips = $this->DeffFleets[$i]->Ships;
            }
            for($i = 0;$i < 5;$i++)
            {
                $TotalDeff[$i+9] = $this->Deff[$i];
            }
            //Schleife ber alle Schiffe
            for($i = 0;$i < 14;$i++)
            {
                //Variablen fr das nächste Schiff "nullen"
                $RestPercentatt = 0;
                $Restpoweratt = $TotalAtt[$i]; //Die Power ist gleich der Anzahl der Schiffe die angreifen
                $OldRestpoweratt = 0;
                $RestPercentdeff = 0;
                $Restpowerdeff = $TotalDeff[$i];
                $OldRestpowerdeff = 0;
                $strike=0;
                //Berechnen wie viele Strikes der aktuelle Schiffstyp hat(eins geteilet durch den kleinsten Prozentwert, mit dem das Schiff feuert und das ganz aufrunden und noch +3)
                if($this->shipdata[$i]['percent'])
                    $curstrikes = ceil(1 / min($this->shipdata[$i]['percent'])) + 3;
                else
                    $curstrikes = 0;
                while($strike < $curstrikes )
                {
                    if($debug)
                        echo "Strike".($strike-$curstrikes)."<br />";
                    $OldRestpoweratt = $Restpoweratt;
                    $OldRestpowerdeff = $Restpowerdeff;
                    // Schleife ber alle Schiffe die angeriffen werden sollen
                    for($j = 0;$j < count($this->shipdata[$i]['attakpower']);$j++)
                    {
                        if($debug)
                            echo $this->shipdata[$i]['name']." gegen ".$this->shipdata[$this->shipdata[$i]['shiptoattak'][$j]]['name']."<br />";
                        // Angreifer
                        if($Restpoweratt > 0)
                        {
                            $del = 0;
                            // Dafr sorgen, dass nicht mit einem Prozentsatz von größer als 100% angerifen wird
                            if($RestPercentatt + $this->shipdata[$i]['percent'][$j] > 1)
                                $RestPercentatt = 1.0 - $this->shipdata[$i]['percent'][$j];
                            // Maximale Zerstörung die Angerichtet werden kann. Die Power der Prozentsatz mal die Power der Schiffe mal wie viele Schiffe vom andern typ von einem zerstört werden
                            $val = ($RestPercentatt + $this->shipdata[$i]['percent'][$j]) * $OldRestpoweratt * $this->shipdata[$i]['attakpower'][$j];
                            if(($val - intval($val)) > 0.99)
                                $MaxDestruction = ceil($val);
                            else
                                $MaxDestruction = floor($val);
                            if($debug)
                            {
                                echo "<font color=#ff0000>- Angreifende Schiffe: ".$TotalAtt[$i]." Verteidigende Schiffe:".($TotalDeff[$this->shipdata[$i]['shiptoattak'][$j]]-$ToDestroyDeff[$this->shipdata[$i]['shiptoattak'][$j]])."<br />";
                                echo "<font color=#ff0000>- Maximale Zerstörung: floor(($RestPercentatt+".$this->shipdata[$i]['percent'][$j].") * $OldRestpoweratt * ".$this->shipdata[$i]['attakpower'][$j].")=$MaxDestruction<br />";
                            }
                            // Wie viele Schiffe dann zerstört werden, nich mehr als die maximale Zerstörung und nich mehr als mit 100%(was oben eigentlich schon geprft wird) und nich mehr als Schiffe noch ber sind.
                            $del= floor(max(min($MaxDestruction, $Restpoweratt * $this->shipdata[$i]['attakpower'][$j], $TotalDeff[$this->shipdata[$i]['shiptoattak'][$j]]-$ToDestroyDeff[$this->shipdata[$i]['shiptoattak'][$j]]), 0));
                            // Im 4ten Strike wird unter bestimmten Umständen(s.u) der Prozentsatz, der beim feuern nicht zum Einsatz gekommen ist zu einer Variable addiert, die zum normalen Prozentsatz dazugerechnet wird.
                            if($strike == 3)
                            {
                                // Wenn es das letzte Schiff im Tick ist oder keine Schiffe zerstört wurden wird Rest-Prozent um den Prozentsatz, der nich verbraucht wird erhöht.
                                // Alles könnte schön und gut sein, wenn da nicht die Schlachter waren, die flogen der Regel nämlich nur wenn sie auf sich selbst oder Kreuzer schießen, sonnst wird immer der Prozentsatz der nicht gebraucht wurde dazugerechnet, warum auch immer...
                                if( $j == count($this->shipdata[$i]['attakpower']) -1 || $del == 0 )
                                {
                                    $RestPercentatt += $this->shipdata[$i]['percent'][$j] - ($del / $OldRestpoweratt / $this->shipdata[$i]['attakpower'][$j]);
                                }
                            }
                            // Benutze Feuerkraft berechnen und subtrahiren
                            $Firepower = $del / $this->shipdata[$i]['attakpower'][$j];
                            $Restpoweratt -= $Firepower;
                            // Schiffe zerstören
                            $ToDestroyDeff[$this->shipdata[$i]['shiptoattak'][$j]] += $del;
                            if($debug)
                            {
                                echo "<font color=#ff0000>- Zerstörte Schiffe: $del<br />
                                        <font color=#ff0000>- Benutzte Firepower = $del/".$this->shipdata[$i]['attakpower'][$j]." = $Firepower; Restpower = $Restpoweratt<br />";
                            }
                        }
                        // Nochmal genau das selbe nur mit Angreifer/Verteidiger vertauschten Variablen.
                        if($Restpowerdeff > 0)
                        {
                            $del = 0;
                            if($RestPercentdeff + $this->shipdata[$i]['percent'][$j] > 1)
                                $RestPercentdeff = 1.0 - $this->shipdata[$i]['percent'][$j];
                            $val = ($RestPercentdeff + $this->shipdata[$i]['percent'][$j]) * $OldRestpowerdeff * $this->shipdata[$i]['attakpower'][$j];
                            if(($val - intval($val)) > 0.99)
                                $MaxDestruction = ceil($val);
                            else
                                $MaxDestruction = floor($val);
                            if($debug)
                            {
                                echo "<font color=#00ff00>- Angreifende Schiffe: ".$TotalDeff[$i]." Verteidigende Schiffe:".($TotalAtt[$this->shipdata[$i]['shiptoattak'][$j]]-$ToDestroyAtt[$this->shipdata[$i]['shiptoattak'][$j]])."<br />";
                                echo "<font color=#00ff00>- Maximale Zerstörung: floor(($RestPercentdeff+".$this->shipdata[$i]['percent'][$j].") * $OldRestpowerdeff * ".$this->shipdata[$i]['attakpower'][$j].")=$MaxDestruction<br />";
                            }
                            $del= floor(max(min($MaxDestruction, $Restpowerdeff * $this->shipdata[$i]['attakpower'][$j], $TotalAtt[$this->shipdata[$i]['shiptoattak'][$j]]-$ToDestroyAtt[$this->shipdata[$i]['shiptoattak'][$j]]), 0));
                            if($strike==3)
                            {
                                if ( $j == count($this->shipdata[$i]['attakpower'])-1 || $del == 0 )
                                {
                                    $RestPercentdeff += $this->shipdata[$i]['percent'][$j] - ($del / $OldRestpowerdeff / $this->shipdata[$i]['attakpower'][$j]);
                                }
                            }
                            $Firepower = $del / $this->shipdata[$i]['attakpower'][$j];
                            $Restpowerdeff -= $Firepower;
                            $ToDestroyAtt[$this->shipdata[$i]['shiptoattak'][$j]] += $del;
                            if($debug)
                            {
                                echo "<font color=#00ff00>- Zerstörte Schiffe: $del<br />
                                    <font color=#00ff00>- Benutzte Firepower = $del/".$this->shipdata[$i]['attakpower'][$j]." = $Firepower; Restpower = $Restpowerdeff<br />";
                            }
                        }
                    }
                    $strike++;
                }
            }
            //Todel verrechnen
            for($i = 0;$i < 9;$i++)
            {
                if($TotalAtt[$i] > 0)
                {
                    for($j = 0;$j < count($this->AttFleets);$j++)
                    {
                        if($this->AttFleets[$j]->TicksToWait > 0)
                            continue;
                        $t = round($TotalAtt[$i] / $this->AttFleets[$j]->Ships[$i] * $ToDestroyAtt[$i]);
                        $this->AttFleets[$j]->LostShips[$i] += $t;
                        $this->AttFleets[$j]->Ships[$i] -= $t;
                    }
                }
                if($TotalDeff[$i] > 0)
                {
                    for($j = 0;$j < count($this->DeffFleets);$j++)
                    {
                        if($this->DeffFleets[$j]->TicksToWait > 0)
                            continue;
                        $t = round($TotalDeff[$i] / $this->DeffFleets[$j]->Ships[$i] * $ToDestroyDeff[$i]);
                        $this->DeffFleets[$j]->LostShips[$i] += $t;
                        $this->DeffFleets[$j]->Ships[$i] -= $t;
                    }
                }
            }
            for($j = 0;$j < count($this->AttFleets) && $TotalAtt[8] > 0;$j++)
            {
                if($this->AttFleets[$j]->TicksToWait > 0)
                    continue;
                $t = (max($this->AttFleets->Ships[7]-$this->AttFleets->Ships[8],0))/2;
                $maxmexen = ceil($t)*($TotalAtt[8]/$this->AttFleets->Ships[8]);
                $maxkexen = floor($t)*($TotalAtt[8]/$this->AttFleets->Ships[8]);
                $rmexen = min($maxmexen, floor($this->Exen_M*0.1));
                if($rmexen != $maxmexen)
                    $maxkexen += $maxmexen-$rmexen;
                $rkexen = min($maxkexen, floor($this->Exen_K*0.1));
                if($rmexen != $maxmexen)
                {
                    $maxmexen += $maxkexen-$rkexen;
                    $rmexen = min($maxmexen, floor($this->Exen_M*0.1));
                }
                $this->Exen_M -= $rmexen;
                $this->Exen_K -= $rkexen;
                $this->AttFleets->Ships[8] -= $rmexen+$rkexen;
                $this->AttFleets->LostShips[8] += $rmexen+$rkexen;
                $this->AttFleets->StolenExenM += $this->stolenmexen = $rmexen;
                $this->AttFleets->StolrnExenK += $this->stolenkexen = $rkexen;
            }
        }
        function AddAttFleet(&$fleet)
        {
            $this->AttFleets[] = &$fleet;
        }
        function AddDeffFleet(&$fleet)
        {
            $this->DeffFleets[] = &$fleet;
        }
        function PrintOverview()
        {
            $vklost = $vmlost = $aklost = $amlost = 0;
            for($i = 0;$i < count($this->AttFleets);$i++)
            {
                for($j = 0;$j < 9;$j++)
                {
                $geslostshipsa[$j] = $this->AttFleets[$i]->LostShips[$j];
                if($geslostshipsa[$j]=="")
                    $geslostshipsa[$j] = 0;
                }
                $gesstolenexenm+=$this->AttFleets[$i]->StolnenExenM;
                $gesstolenexenk+=$this->AttFleets[$i]->StolnenExenK;
            }
            for($i=0;$i<count($this->DeffFleets);$i++)
            {
                for($j = 0;$j < 9;$j++)
                {
                $geslostshipsv[$j] = $this->DeffFleets[$i]->LostShips[$j];
                if($geslostshipsv[$j]=="")
                    $geslostshipsv[$j] = 0;
                }
            }
            for($i=0;$i<15;$i++)
            {
                $vklost  += $this->shipdata[$i]['cost'][1]*$geslostshipsv[$i];
                $vmlost  += $this->shipdata[$i]['cost'][0]*$geslostshipsv[$i];
                $aklost  += $this->shipdata[$i]['cost'][1]*$geslostshipsa[$i];
                $amlost  += $this->shipdata[$i]['cost'][0]*$geslostshipsa[$i];
            }
            echo "<br /><center><table bgcolor=#555555 cellspacing=1>";
            echo "<tr><td colspan=\"3\" class=\"datatablehead\" align=\"center\">Übersicht</td></tr>";
            echo "<tr bgcolor=#666666><td colspan=\"3\">Verlorene Schiffe/Gesch&uuml;tze</td></tr>";
            echo "<tr bgcolor=#777777><td>Typ</td><td>Verteidiger</td><td align='center'>Angreifer</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Jäger</td><td>          ".$geslostshipsv[0]."</td><td>".$geslostshipsa[0]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Bomber</td><td>         ".$geslostshipsv[1]."</td><td>".$geslostshipsa[1]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Fregatte</td><td>       ".$geslostshipsv[2]."</td><td>".$geslostshipsa[2]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Zerstörer</td><td>      ".$geslostshipsv[3]."</td><td>".$geslostshipsa[3]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Kreuzer</td><td>        ".$geslostshipsv[4]."</td><td>".$geslostshipsa[4]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Schlachtschiff</td><td>     ".$geslostshipsv[5]."</td><td>".$geslostshipsa[5]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Trägerschiff</td><td>       ".$geslostshipsv[6]."</td><td>".$geslostshipsa[6]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Kaperschiff</td><td>        ".$geslostshipsv[7]."</td><td>".$geslostshipsa[7]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Schutzschiff</td><td>       ".$geslostshipsv[8]."</td><td>".$geslostshipsa[8]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Leichtes Orbitalgsch&uuml;tz</td><td> ".$geslostshipsv[9]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Leichtes Raumgesch&uuml;tz</td><td>   ".$geslostshipsv[10]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Mittleres Raumgesch&uuml;tz</td><td>  ".$geslostshipsv[11]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Schweres Raumgesch&uuml;tz</td><td>  ".$geslostshipsv[12]."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Abfangjäger</td><td>    ".$geslostshipsv[13]."</td></tr>";
            echo "<tr class=\"fieldnormaldark\">><td align='center' colspan=3>Kosten f&uuml;r Neubau</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Metall</td><td>".ZahlZuText($vmlost)."</td><td>".ZahlZuText($amlost)."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Kristall</td><td>".ZahlZuText($vklost)."</td><td>".ZahlZuText($aklost)."</td></tr>";
            echo "<tr class=\"fieldnormaldark\">><td colspan=\"3\" align='center'>Gestohlene Extraktoren</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Metallextraktoren:</td><td> ".$gesstolenexenm."</td></tr>";
            echo "<tr class=\"fieldnormallight\"><td>Kristallextraktoren:</td><td>   ".$gesstolenexenk."</td></tr>";
            echo "</table></center>";
        }
    }
    class GNSimu
    {
        var $attaking;   // Angreifende Schiffe(array von 0-8)
        var $deffending; // Verteidigende Schiffe(array von 0-13)
        var $Oldatt;     //Schiffe die am Anfang des Ticks da waren
        var $Olddeff;
        var $mexen;      // Exen die der Angegriffene hat
        var $kexen;
        var $stolenmexen;  // Geklauteexen
        var $stolenkexen;
        var $gesstolenexenm; // Gesammtgeklaute exen
        var $gesstolenexenk;
        var $geslostshipsatt; // Schiffe die seit erstellung der Klasse zerstört wurden
        var $geslostshipsdeff;
        var $mcost;            // Wie viel ein Schiff Kostet
        var $kcost;
        var $name;
        var $attakpower;
        var $shiptoattak;
        var $percent;
        function GNSimu() // Variablen mit Kampfwerten fllen
        {
            $this->geslostshipsatt = array(0,0,0,0,0,0,0,0,0,0);
            $this->geslostshipsdeff = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            $this->attaking = array(0,0,0,0,0,0,0,0,0);
            $this->deffending = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            // Daten Fr Jäger Nr. 0
            $this->name[0] = "J&auml;ger";
            $this->attakpower[0]  = array(0.0246, 0.392, 0.0263); // Wie viele Schiffe ein Schiff mit 100% Feuerkrafft zerstören wrde
            $this->shiptoattak[0] = array(11,1,4); // Welche Schiffe/Gesch&uuml;tze angegriffen werden
            $this->percent[0]     = array(0.35,0.30,0.35); // Die Verteilung der Prozente, mit der auf die Schiffe geschoßen wird.
            $this->mcost[0] = 4000;
            $this->kcost[0] = 6000;
            // Daten Fr Bomber Nr. 1
            $this->attakpower[1]  = array(0.0080,0.0100,0.0075);
            $this->shiptoattak[1] = array(12,5,6);
            $this->percent[1]     = array(0.35,0.35,0.30);
            $this->name[1] = "Bomber";
            $this->mcost[1] = 2000;
            $this->kcost[1] = 8000;
            // Daten Fr Fregatte Nr. 2
            $this->attakpower[2]  = array(4.5,0.85);
            $this->shiptoattak[2] = array(13,0);
            $this->percent[2]     = array(0.6,0.4);
            $this->name[2] = "Fregatte";
            $this->mcost[2] = 15000;
            $this->kcost[2] = 7500;
            // Daten Fr Zerstörer Nr. 3
            $this->attakpower[3]  = array(3.5,1.2444);
            $this->shiptoattak[3] = array(9,2);
            $this->percent[3]     = array(0.6,0.4);
            $this->name[3]  = "Zerst&ouml;rer";
            $this->mcost[3] = 40000;
            $this->kcost[3] = 30000;
            // Daten Fr Kreuzer Nr. 4
            $this->attakpower[4]  = array(2.0,0.8571,10.0);
            $this->shiptoattak[4] = array(10,3,8);
            $this->percent[4]     = array(0.35,0.30,0.35);
            $this->name[4]       = "Kreuzer";
            $this->mcost[4] = 65000;
            $this->kcost[4] = 85000;
            // Daten Fr Schalchtschiff Nr. 5
            $this->attakpower[5]  = array(1.0,1.0666,0.4,0.3019,26.6667);
            $this->shiptoattak[5] = array(11,4,5,6,8);
            $this->percent[5]     = array(0.2,0.2,0.2,0.2,0.2);
            $this->name[5]  = "Schlachtschiff";
            $this->mcost[5] = 250000;
            $this->kcost[5] = 150000;
            // Daten Fr Trägerschiff Nr. 6
            $this->attakpower[6]  = array(25.0,14.0);
            $this->shiptoattak[6] = array(7,8);
            $this->percent[6]     = array(0.5,0.5);
            $this->mcost[6] = 200000;
            $this->kcost[6] =  50000;
            $this->name[6]  = "Tr&auml;gerschiff";
            // Daten fr Kaperschiff
            $this->mcost[7] = 1500;
            $this->kcost[7] = 1000;
            $this->name[7] = "Kaperschiff";
            // Daten fr Schutzschiff
            $this->mcost[8] = 1000;
            $this->kcost[8] = 1500;
            $this->name[8]  = "Schutzschiff";
            // Daten Fr Leichtes Obligtalgesch&uuml;tz Nr. 9
            $this->attakpower[9]  = array(0.3,1.28);
            $this->shiptoattak[9] = array(0,7);
            $this->percent[9]     = array(0.6,0.4);
            $this->mcost[9] = 6000;
            $this->kcost[9] = 2000;
            $this->name[9]  = "Leichtes Orbligtalgesch&uuml;tz";
            // Daten Fr Leichtes Raumgesch&uuml;tz Nr. 10
            $this->attakpower[10]  = array(1.2,0.5334);
            $this->shiptoattak[10] = array(1,2);
            $this->percent[10]     = array(0.4,0.6);
            $this->mcost[10] = 20000;
            $this->kcost[10] = 10000;
            $this->name[10]  = "Leichtes Raumgesch&uuml;tz";
            // Daten Fr Mittleres Raumgesch&uuml;tz Nr. 11
            $this->attakpower[11]  = array(0.9143,0.4267);
            $this->shiptoattak[11] = array(3,4);
            $this->percent[11]     = array(0.4,0.6);
            $this->mcost[11] =  60000;
            $this->kcost[11] = 100000;
            $this->name[11]  = "Mittleres Raumgesch&uuml;tz";
                // Daten Fr Schweres Raumgesch&uuml;tz Nr. 12
            $this->attakpower[12]  = array(0.5,0.3774);
            $this->shiptoattak[12] = array(5,6);
            $this->percent[12]     = array(0.5,0.5);
            $this->mcost[12] = 200000;
            $this->kcost[12] = 300000;
            $this->name[12]  = "Schweres Raumgesch&uuml;tz";
            // Daten Fr  Abfangjäger Nr. 13
            $this->attakpower[13]  = array(0.0114,0.32);
            $this->shiptoattak[13] = array(3,7);
            $this->percent[13]     = array(0.4,0.6);
            $this->mcost[13] = 1000;
            $this->kcost[13] = 1000;
            $this->name[13]  = "Abfangj&auml;ger";
        }
        function Compute($lasttick,$debug=0) // $lasttick dient dazu im letzten tick die Jäger und Bomber zu zerstören, die über sind.
        {
            // "Sicherheitskopie" der Anzahl der Schiffe machen
            for($i=0;$i<14;$i++)
            {
                $this->Olddeff[$i] = $this->deffending[$i];
                if($i < 9)
                    $this->Oldatt[$i] = $this->attaking[$i];
            }
            //Schleife ber alle Schiffe
            for($i=0;$i<14;$i++)
            {
                //Variablen fr das n?hste Schiff "nullen"
                $RestPercentatt = 0;
                if(isset($this->Oldatt[$i]))
                    $Restpoweratt = $this->Oldatt[$i]; //Die Power ist gleich der Anzahl der Schiffe die angreifen
                else
                    $Restpoweratt = 0;
                $OldRestpoweratt = 0;
                $RestPercentdeff = 0;
                $Restpowerdeff = $this->Olddeff[$i];
                $OldRestpowerdeff = 0;
                $strike=0;
                //Berechnen wie viele Strikes der aktuelle Schiffstyp hat(eins geteilet durch den kleinsten Prozentwert, mit dem das Schiff feuert und das ganz aufrunden und noch +3)
                if(isset($this->percent[$i]))
                    $curstrikes = ceil(1/min($this->percent[$i]))+3;
                else
                    $curstrikes = 0;
                while($strike < $curstrikes )
                {
                    if($debug)
                        echo "Strike".($strike-$curstrikes)."<br />";
                    $OldRestpoweratt = $Restpoweratt;
                    $OldRestpowerdeff = $Restpowerdeff;
                    // Schleife ber alle Schiffe die angeriffen werden sollen
                    for($j=0;$j<count($this->attakpower[$i]);$j++)
                    {
                        if($debug)
                            echo $this->name[$i]." gegen ".$this->name[$this->shiptoattak[$i][$j]]."<br />";
                        // Angreifer
                        if($Restpoweratt>0)
                        {
                            $del = 0;
                            // Dafr sorgen, dass nicht mit einem Prozentsatz von gr?er als 100% angerifen wird
                            if($RestPercentatt+$this->percent[$i][$j] > 1)
                                $RestPercentatt = 1.0 - $this->percent[$i][$j];
                            // Maximale Zerst?ung die Angerichtet werden kann. Die Power der Prozentsatz mal die Power der Schiffe mal wie viele Schiffe vom andern typ von einem zerst?t werden
                            $val = ($RestPercentatt+$this->percent[$i][$j]) * $OldRestpoweratt * $this->attakpower[$i][$j];
                            if(($val - intval($val)) > 0.99)
                                $MaxDestruction = ceil($val);
                            else
                                $MaxDestruction = floor($val);
                            if($debug)
                            {
                                echo "<font color=#ff0000>- Angreifende Schiffe: ".$this->Oldatt[$i]." Verteidigende Schiffe:".($this->deffending[$this->shiptoattak[$i][$j]])."<br />";
                                echo "<font color=#ff0000>- Maximale Zerst?ung: floor(($RestPercentatt+".$this->percent[$i][$j].") * $OldRestpoweratt * ".$this->attakpower[$i][$j].")=$MaxDestruction<br />";
                            }
                            // Wie viele Schiffe dann zerst?t werden, nich mehr als die maximale zerst?ung und nich mehr als mit 100%(was oben eigentlich schon geprft wird) und nich mehr als Schiffe noch ber sind.
                            $del= floor(max(min($MaxDestruction, $Restpoweratt * $this->attakpower[$i][$j], $this->deffending[$this->shiptoattak[$i][$j]]), 0));
                            // Im 4ten Strike wird unter bestimmten Umst?den(s.u) der Prozentsatz, der beim feuern nicht zum Einsatz gekommen ist zu einer Variable addiert, die zum normalen Prozentsatz dazugerechnet wird.
                            if($strike==3)
                            {
                                // Wenn es das letzte Schiff im Tick ist oder keine Schiffe zerst?t wurden wird Rest-Prozent um den Prozentsatz, der nich verbraucht wird erh?t.
                                // Alles k?nte sch? und gut sein, wenn da nicht die Schlachter waren, die flogen der Regel n?lich nur wenn sie auf sich selbst oder Kreuzer schie?n, sonnst wird immer der Prozentsatz der nicht gebraucht wurde dazugerechnet, warum auch immer...
                                if( $j == count($this->attakpower[$i]) -1 || $del == 0 )
                                {
                                    $RestPercentatt += $this->percent[$i][$j] - ($del / $OldRestpoweratt / $this->attakpower[$i][$j]);
                                }
                            }
                            // Benutze Feuerkraft berechnen und subtrahiren
                            $Firepower = $del/$this->attakpower[$i][$j];
                            $Restpoweratt -= $Firepower;
                            // Schiffe zerst?en
                            $this->deffending[$this->shiptoattak[$i][$j]] -=$del;
                            $this->geslostshipsdeff[$this->shiptoattak[$i][$j]] += $del;
                            if($debug)
                            {
                                echo "<font color=#ff0000>- Zerst?te Schiffe: $del<br />
                                        <font color=#ff0000>- Benutzte Firepower = $del/".$this->attakpower[$i][$j]." = $Firepower; Restpower = $Restpoweratt<br />";
                            }
                        }
                        // Nochmal genau das selbe nur mit Angreifer/Verteidiger vertauschten Variablen.
                        if($Restpowerdeff > 0)
                        {
                            $del = 0;
                            if($RestPercentdeff+$this->percent[$i][$j] > 1)
                            $RestPercentdeff = 1.0 - $this->percent[$i][$j];
                            $val = ($RestPercentdeff+$this->percent[$i][$j]) * $OldRestpowerdeff * $this->attakpower[$i][$j];
                            if(($val - intval($val)) > 0.99)
                                $MaxDestruction = ceil($val);
                            else
                                $MaxDestruction = floor($val);
                            if($debug)
                            {
                                echo "<font color=#00ff00>- Angreifende Schiffe: ".$this->Olddeff[$i]." Verteidigende Schiffe:".($this->attaking[$this->shiptoattak[$i][$j]])."<br />";
                                echo "<font color=#00ff00>- Maximale Zerst?ung: floor(($RestPercentdeff+".$this->percent[$i][$j].") * $OldRestpowerdeff * ".$this->attakpower[$i][$j].")=$MaxDestruction<br />";
                            }
                            if($this->shiptoattak[$i][$j] < 9)
                            {
                                $del = floor(max(min($MaxDestruction, $Restpowerdeff * $this->attakpower[$i][$j], $this->attaking[$this->shiptoattak[$i][$j]]), 0));
    
                                if($strike==3)
                                {
                                    if ( $j == count($this->attakpower[$i])-1 || $del == 0 )
                                    {
                                        $RestPercentdeff += $this->percent[$i][$j] - ($del / $OldRestpowerdeff / $this->attakpower[$i][$j]);
                                    }
                                }
                                $Firepower = $del/$this->attakpower[$i][$j];
                                $Restpowerdeff -= $Firepower;
                                $this->attaking[$this->shiptoattak[$i][$j]] -= $del;
                                $this->geslostshipsatt[$this->shiptoattak[$i][$j]] += $del;
                                if($debug)
                                {
                                    echo "<font color=#00ff00>- Zerst?te Schiffe: $del<br />
                                        <font color=#00ff00>- Benutzte Firepower = $del/".$this->attakpower[$i][$j]." = $Firepower; Restpower = $Restpowerdeff<br />";
                                }
                            }
                        }
                    }
                    $strike++;
                }
            }
            //Wenn wir im letzen Tick sind wird geprft ob auch alle J?er und Bomber mit nach hause fliegn drfen
            if($lasttick)
            {
                $jaeger =  $this->attaking[0];
                $bomber =  $this->attaking[1];
                $traeger = $this->attaking[6];
                if ( $bomber + $jaeger > $traeger*100)
                {
                $todel = $jaeger + $bomber - $traeger*100;
                $tmp = round($todel*($jaeger/($jaeger + $bomber)));
                        $this->attaking[0] -= $tmp;
                        $this->geslostshipsatt[0] += $tmp;
                $tmp = round($todel*($bomber/($jaeger + $bomber)));
                        $this->attaking[1] -= $tmp;
                        $this->geslostshipsatt[1] += $tmp;
                }
            }
            //Dann noch mal eben schnell paar exen klauen
            //Erstmall ausrechnen, wie viele maximal mitgenommen werden k?nen, bin der Meinung mal Iregndwo im Forum gelesen zu haben, dass Metall- auf- und Kristallexen abgerundet werden
            $maxmexen = ceil((max($this->attaking[7]-$this->deffending[8],0))/2);
            $maxkexen = floor((max($this->attaking[7]-$this->deffending[8],0))/2);
            //Dann wie viele Metallexen in den mei?en F?len geklaut werden
            $rmexen = min($maxmexen, floor($this->mexen*0.1));
            //Wenn nich alle Schiffe, die fr Metallexenlau bereitgestellt waren benutz werden, drfen diese zum Kristallexen klauen Benutzt werden
            if($rmexen != $maxmexen)
                $maxkexen += $maxmexen-$rmexen;
            //Kristallexen in den mei?en F?len
            $rkexen = min($maxkexen, floor($this->kexen*0.1));
            // Wenn nich alle zum Kristallexen bereitgestellten Cleps benutzt wurden, rechnen wir nochmal Metallexen ob nich evtl mehr mit genommen werden k?nen.
            if($rkexen != $maxkexen)
            {
                $maxmexen += $maxkexen-$rkexen;
                $rmexen = min($maxmexen, floor($this->mexen*0.1));
            }
            // Exen vom bestand abziehen und auch die benutzen Cleps "zerst?en"
            $this->mexen -=$rmexen;
            $this->kexen -=$rkexen;
            $this->attaking[7] -= $rmexen+$rkexen;
            $this->geslostshipsatt[7]+=$rmexen+$rkexen;
            //Fr die Statistik, wie viele Exen insgesammt gestohlen wurden.
            $this->gesstolenexenm+=$this->stolenmexen = $rmexen;
            $this->gesstolenexenk+=$this->stolenkexen = $rkexen;
       }
       function ComputeOneTickBefore()
       {
            $debug = false;
            $todela = array(0,0,0,0,0,0,0,0,0);
            $todelv = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            for($i=0;$i<14;$i++)
            {
                $this->Olddeff[$i] = $this->deffending[$i];
                if($i < 9)
                    $this->Oldatt[$i] = $this->attaking[$i];
            }
        /// Leichtes Raumgesch&uuml;tz
        $RestPercentdeff = 0;
        $Restpowerdeff = $this->deffending[10];
        $OldRestpowerdeff = 0;
        $first = 0;
        while($first<6 && ($Restpowerdeff>0))
        {
            $OldRestpowerdeff = $Restpowerdeff;
            // Leichtes Raumgesch&uuml;tz  gegen Fregatte
            // Verteidiger
            if($Restpowerdeff>0)
            {
            $del = 0;
            $MaxDestruction = floor(($RestPercentdeff+1.0) * $OldRestpowerdeff * 0.5334*0.5);
            if($first==3)
                $RestPercentdeff+=0.6;
            $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.5334*0.5, $this->attaking[2]+$todela[2]), 0));
            if($first==3)
                 $RestPercentdeff-= ($del / $OldRestpowerdeff /  (0.5334*0.5));
            $Firepower = $del/(0.5334*0.5);
            $Restpowerdeff -= $Firepower;
            $todela[2]-=$del;
            }
            $first++;
        }
        $RestPercentdeff = 0;
        $Restpowerdeff = $this->deffending[11];
        $OldRestpowerdeff = 0;
        $first = 0;
        while($first<6 && ($Restpowerdeff>0))
        {
            $OldRestpowerdeff = $Restpowerdeff;
            // Mittlers Raumgesch&uuml;tz  gegen Zerst?er
            // Verteidiger
            if($Restpowerdeff>0)
            {
            $del = 0;
            $MaxDestruction = floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.9143*0.5);
            if($first==3)
                $RestPercentdeff+=0.4;
            $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.9143*0.5, $this->attaking[3]+$todela[3]), 0));
            if($first==3)
                 $RestPercentdeff-= ($del / $OldRestpowerdeff / (0.9143*0.5));
            $Firepower = $del/(0.9143*0.5);
            $Restpowerdeff -= $Firepower;
            $todela[3]-=$del;
            }
            // Mittlers Raumgesch&uuml;tz  gegen Kreuzer
            // Verteidiger
            if($Restpowerdeff>0)
            {
            $del = 0;
            $MaxDestruction = floor(($RestPercentdeff+0.6) * $OldRestpowerdeff * 0.4267*0.5);
            if($first==3)           $RestPercentdeff+=0.6;
            $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.4267*0.5, $this->attaking[4]+$todela[4]), 0));
            if($first==3)
                 $RestPercentdeff-= ($del / $OldRestpowerdeff /  (0.4267*0.5));
            $Firepower = $del/(0.4267*0.5);     $Restpowerdeff -= $Firepower;
            $todela[4]-=$del;
            }
            $first++;
        }
    //Mittlers Raumgesch&uuml;tz
        // Brechnungen fr Schweres Raumgesch&uuml;tz
        $RestPercentdeff = 0;
        $Restpowerdeff = $this->deffending[12];
        $OldRestpowerdeff = 0;
        $first = 0;
        if($debug)
            echo "Berechnungen fr Schweres Raumgesch&uuml;tz<br />";
        while($first<8 && ($Restpowerdeff>0))
        {
            if($debug)
            echo "Strike".(-5+$first)."<br />";
            $OldRestpowerdeff = $Restpowerdeff;
            // Schweres Raumgesch&uuml;tz  gegen Schlachtschiff
            if($debug)
            echo "<font color=#00ff00>- Schweres Raumgesch&uuml;tz  gegen Schlachtschiff<br />";
            // Verteidiger
            if($Restpowerdeff>0)
            {
            $del = 0;
                    if($RestPercentdeff+0.4 > 1.0)
                        $RestPercentdeff = 1.0-0.4;
            $MaxDestruction = floor(($RestPercentdeff+0.5) * $OldRestpowerdeff * 0.5*0.6);
            if($debug)
            {
                echo "<font color=#00ff00>- Angreifende Schiffe: ".$this->deffending[12]." Verteidigende Schiffe:".($this->attaking[5]+$todela[5])."<br />";
                echo "<font color=#00ff00>- Maximale Zerst?ung: floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.5)=$MaxDestruction<br />";
            }       if($first==3)
                $RestPercentdeff+=0.5;
            $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.5*0.6, $this->attaking[5]+$todela[5]), 0));
            if($first==3)
                 $RestPercentdeff-= ($del / $OldRestpowerdeff / (0.5*0.6));
            $Firepower = $del/(0.5*0.6);
            $Restpowerdeff -= $Firepower;
            $todela[5]-=$del;
            }
            // Schweres Raumgesch&uuml;tz  gegen Tr?erschiff
            if($debug)
            echo "<font color=#00ff00>- Schweres Raumgesch&uuml;tz  gegen Tr?erschiff<br />";
            // Verteidiger
            if($Restpowerdeff>0)        {
            $del = 0;
            $MaxDestruction = floor(($RestPercentdeff+0.5) * $OldRestpowerdeff * 0.3774*0.6);
            if($debug)
            {
                echo "<font color=#00ff00>- Angreifende Schiffe: ".$this->deffending[13]." Verteidigende Schiffe:".($this->attaking[6]+$todela[6])."<br />";
                echo "<font color=#00ff00>- Maximale Zerst?ung: floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.3774*0.6)=$MaxDestruction<br />";
            }       if($first==3)
                $RestPercentdeff+=0.5;
            $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.3774*0.6, $this->attaking[6]+$todela[6]), 0));
            if($first==3)
                 $RestPercentdeff-= ($del / $OldRestpowerdeff /  (0.3774*0.6));
            $Firepower = $del/(0.3774*0.6);
            $Restpowerdeff -= $Firepower;
            $todela[6]-=$del;
            }
            if($first==3)
                $RestPercentdeff+=0.2;
            $first++;
        }//Schweres Raumgesch&uuml;tz
        // ?rige Bomber und J?er zerst?en...
        $jaeger =  $this->attaking[0] + $todela[0];
        $bomber =  $this->attaking[1] + $todela[1];
        $traeger = $this->attaking[6] + $todela[6];
        if ( $bomber + $jaeger > $traeger*100)
        {
            $todel = $jaeger + $bomber - $traeger*100;
            $todela[0] = -round($todel*($jaeger/($jaeger + $bomber)));
            $todela[1] = -round($todel*($bomber/($jaeger + $bomber)));
        }
        //Todel verrechnen
        for($i=0;$i<14;$i++)
        {
            if($i < 9)
            {
                $this->geslostshipsatt[$i]-=$todela[$i];
                $this->attaking[$i]+=$todela[$i];
            }
            $this->geslostshipsdeff[$i]-=$todelv[$i];
            $this->deffending[$i]+=$todelv[$i];
        }
       }
        function ComputeTwoTickBefore()
        {
            $debug = false;
            $todela = array(0,0,0,0,0,0,0,0,0);
            $todelv = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            for($i=0;$i<14;$i++)
            {
                $this->Olddeff[$i] = $this->deffending[$i];
                if($i < 9)
                    $this->Oldatt[$i] = $this->attaking[$i];
            }
        // Brechnungen fr Schweres Raumgesch&uuml;tz
        $RestPercentdeff = 0;
        $Restpowerdeff = $this->deffending[12];
        $OldRestpowerdeff = 0;
        $first = 0;
        if($debug)
            echo "Berechnungen fr Schweres Raumgesch&uuml;tz<br />";
        while($first<8 && ($Restpowerdeff>0))
        {
            if($debug)
            echo "Strike".(-5+$first)."<br />";
            $OldRestpowerdeff = $Restpowerdeff;
            // Schweres Raumgesch&uuml;tz  gegen Schlachtschiff
            if($debug)
            echo "<font color=#00ff00>- Schweres Raumgesch&uuml;tz  gegen Schlachtschiff<br />";
            // Verteidiger
            if($Restpowerdeff>0)
            {
            $del = 0;
            $MaxDestruction = floor(($RestPercentdeff+0.5) * $OldRestpowerdeff * 0.5*0.2);  if($debug)
            {
                echo "<font color=#00ff00>- Angreifende Schiffe: ".$this->deffending[12]." Verteidigende Schiffe:".($this->attaking[5]+$todela[5])."<br />";
                echo "<font color=#00ff00>- Maximale Zerst?ung: floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.5*0.2)=$MaxDestruction<br />";
            }
            if($first==3)
                $RestPercentdeff+=0.5;
            $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.5*0.2, $this->attaking[5]+$todela[5]), 0));
            if($first==3)
                 $RestPercentdeff-= ($del / $OldRestpowerdeff / (0.5*0.2));
            $Firepower = $del/(0.5*0.2);
            $Restpowerdeff -= $Firepower;
            $todela[5]-=$del;
            }
            // Schweres Raumgesch&uuml;tz  gegen Tr?erschiff
            if($debug)
            echo "<font color=#00ff00>- Schweres Raumgesch&uuml;tz  gegen Tr?erschiff<br />";
            // Verteidiger
            if($Restpowerdeff>0)
            {
            $del = 0;
            $MaxDestruction = floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.3774*0.2);
            if($debug)
            {
                echo "<font color=#00ff00>- Angreifende Schiffe: ".$this->deffending[12]." Verteidigende Schiffe:".($this->attaking[6]+$todela[6])."<br />";
                echo "<font color=#00ff00>- Maximale Zerst?ung: floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.3774*0.2)=$MaxDestruction<br />";
            }       if($first==3)
                $RestPercentdeff+=0.4;
            $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.3774*0.2, $this->attaking[6]+$todela[6]), 0));
            if($first==3)
                 $RestPercentdeff-= ($del / $OldRestpowerdeff /  (0.3774*0.2));
            $Firepower = $del/(0.3774*0.2);
            $Restpowerdeff -= $Firepower;
            $todela[6]-=$del;
            }
            if($first==3)
                $RestPercentdeff+=0.2;
            $first++;
        }//Schweres Raumgesch&uuml;tz
        // ?rige Bomber und J?er zerst?en...
        $jaeger =  $this->attaking[0] + $todela[0];
        $bomber =  $this->attaking[1] + $todela[1];
        $traeger = $this->attaking[6] + $todela[6];
        if ( $bomber + $jaeger > $traeger*100)
        {
            $todel = $jaeger + $bomber - $traeger*100;
            $todela[0] = -round($todel*($jaeger/($jaeger + $bomber)));
            $todela[1] = -round($todel*($bomber/($jaeger + $bomber)));
        }
        //Todel verrechnen
        for($i=0;$i<14;$i++)
        {
            if($i < 9)
            {
                $this->geslostshipsatt[$i] -= $todela[$i];
                $this->attaking[$i] += $todela[$i];
            }
            $this->geslostshipsdeff[$i] -= $todelv[$i];
            $this->deffending[$i] += $todelv[$i];
        }
       }
       function PrintStates()
       {
        echo "<table align=\"center\" class=\"datatable\" cellspacing=\"1\" style=\"padding:5px;\">";
        echo "<tr class=\"datatablehead\"><td></td><td colspan=\"2\">Verteidigende Flotte</td><td colspan=\"2\">Angreifende Flotte</td></tr>";
        echo "<tr style=\"font-weight:bold\" class=\"fieldnormaldark\"><td>Typ</td><td>Vorher</td><td>Nachher</td><td>Vorher</td><td>Nachher</td></tr>";
        $color = 0;
        for($i = 0; $i < 14;$i++)
        {
            $color = !$color;
            echo "<tr class=\"fieldnormal".($color ? "light" : "dark")."\"><td>".$this->name[$i]."</td><td>          ".$this->Olddeff[$i]."</td><td>".$this->deffending[$i]."</td>";
            if($i < 9)
                echo "<td>".$this->Oldatt[$i]."</td><td>".$this->attaking[$i]."</td>";
            echo "</tr>";
        }
        echo "<tr class=\"fieldnormallight\"><td>Metallexen geklaut:</td><td>    ".$this->stolenmexen."</td></tr>";
        echo "<tr class=\"fieldnormaldark\"><td>Kristallexen geklaut:</td><td>  ".$this->stolenkexen."</td></tr>";
        echo "</table>";
       }
       function PrintStatesGun()
       {
        echo "<table class=\"datatable\" cellspacing=\"1\" align=\"center\" style=\"padding:5px;\">";
        echo "<tr class=\"datatablehead\"><td></td><td colspan=\"2\">Verteidigende Flotte</td><td colspan=\"2\">Angreifende Flotte</td></tr>";
        echo "<tr style=\"font-weight:bold\" bgcolor=\"#cccc88\"><td>Typ</td><td>Vorher</td><td>Nachher</td><td>Vorher</td><td>Nachher</td></tr>";
        $color = 0;
        for($i = 0; $i < 14;$i++)
        {
            $color = !$color;
            echo "<tr style=\"background-color:".($color ? "#dddd99" : "#cccc88").";\"><td>".$this->name[$i]."</td><td>          ".$this->Olddeff[$i]."</td><td>".$this->deffending[$i]."</td>";
            if($i < 9)
                echo "<td>".$this->Oldatt[$i]."</td><td>".$this->attaking[$i]."</td>";
            echo "</tr>";
        }
        echo "</table>";
       }
       function PrintStates_ACE()
       {
        echo "<table align=\"center\" cellspacing=\"1\">";
        echo "<tr bgcolor=#666666><td></td><td colspan=\"2\">Angreifende Flotte</td><td colspan=\"2\">Verteidigende Flotte</td></tr>";
        echo "<tr bgcolor=#777777><td>Typ<td>Vorher</td><td>Nachher</td><td>Vorher</td><td>Nachher</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>J&auml;ger</td><td>          ".$this->Oldatt[0]."</td><td>".$this->attaking[0]."</td><td>".$this->Olddeff[0]."</td><td>".$this->deffending[0]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Bomber</td><td>         ".$this->Oldatt[1]."</td><td>".$this->attaking[1]."</td><td>".$this->Olddeff[1]."</td><td>".$this->deffending[1]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Fregatte</td><td>       ".$this->Oldatt[2]."</td><td>".$this->attaking[2]."</td><td>".$this->Olddeff[2]."</td><td>".$this->deffending[2]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Zerst&ouml;rer</td><td>      ".$this->Oldatt[3]."</td><td>".$this->attaking[3]."</td><td>".$this->Olddeff[3]."</td><td>".$this->deffending[3]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Kreuzer</td><td>        ".$this->Oldatt[4]."</td><td>".$this->attaking[4]."</td><td>".$this->Olddeff[4]."</td><td>".$this->deffending[4]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Schlachtschiff</td><td>     ".$this->Oldatt[5]."</td><td>".$this->attaking[5]."</td><td>".$this->Olddeff[5]."</td><td>".$this->deffending[5]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Tr&auml;gerschiff</td><td>       ".$this->Oldatt[6]."</td><td>".$this->attaking[6]."</td><td>".$this->Olddeff[6]."</td><td>".$this->deffending[6]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Kaperschiff</td><td>        ".$this->Oldatt[7]."</td><td>".$this->attaking[7]."</td><td>".$this->Olddeff[7]."</td><td>".$this->deffending[7]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Schutzschiff</td><td>       ".$this->Oldatt[8]."</td><td>".$this->attaking[8]."</td><td>".$this->Olddeff[8]."</td><td>".$this->deffending[8]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Leichtes Orbitalgsch&uuml;tz</td><td></td><td></td><td>   ".$this->Olddeff[9]."</td><td>".$this->deffending[9]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Leichtes Raumgesch&uuml;tz</td><td></td><td></td><td> ".$this->Olddeff[19]."</td><td>".$this->deffending[10]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Mittleres Raumgesch&uuml;tz</td><td></td><td></td><td>    ".$this->Olddeff[11]."</td><td>".$this->deffending[11]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Schweres Raumgesch&uuml;tz</td><td></td><td></td><td>    ".$this->Olddeff[12]."</td><td>".$this->deffending[12]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Abfangj&auml;ger</td><td></td><td></td><td>  ".$this->Olddeff[13]."</td><td>".$this->deffending[13]."</td></tr>";
        echo "<tr bgcolor=#555555><td colspan=5></td>     <tr class=\"fieldnormallight\"><td>Metallexen geklaut:</td><td>    ".$this->stolenmexen."</td>";
        echo "<tr class=\"fieldnormallight\"><td>Kristallexen geklaut:</td><td>  ".$this->stolenkexen."</td>";
        echo "</tr></table>";
       }
       function PrintOverView()
       {
        $vklost = $vmlost = $aklost = $amlost = 0;
        for($i=0;$i<14;$i++)
        {
            $vklost  += $this->kcost[$i]*$this->geslostshipsdeff[$i];
            $vmlost  += $this->mcost[$i]*$this->geslostshipsdeff[$i];
            if($i < 9)
            {
                $aklost  += $this->kcost[$i]*$this->geslostshipsatt[$i];
                $amlost  += $this->mcost[$i]*$this->geslostshipsatt[$i];
            }
        }
        echo "<table class=\"datatable\" cellspacing=\"1\" align=\"center\" style=\"padding:5px;\">";
        echo "<tr><td colspan=\"3\" class=\"datatablehead\">&Uuml;bersicht</td></tr>";
        echo "<tr class=\"fieldnormaldark\" style=\"font-weight:bold\"><td colspan=\"3\">Verlorene Schiffe/Gesch&uuml;tze</td></tr>";
        echo "<tr class=\"fieldnormaldark\" style=\"font-weight:bold\"><td>Typ</td><td>Verteidiger</td><td>Angreifer</td></tr>";
        $color = 0;
        for($i = 0; $i < 14;$i++)
        {
            $color = !$color;
            echo "<tr class=\"fieldnormal".($color ? "light" : "dark")."\"><td>".$this->name[$i]."</td><td>          ".$this->geslostshipsdeff[$i]."</td>";
            if($i < 9)
                echo "<td>".$this->geslostshipsatt[$i]."</td>";
            echo "</tr>";
        }
        echo "<tr class=\"fieldnormaldark\"><td colspan=\"3\" style=\"font-weight:bold\">Kosten f&uuml;r Neubau</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Metall</td><td>".ZahlZuText($vmlost)."</td><td>".ZahlZuText($amlost)."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Kristall</td><td>".ZahlZuText($vklost)."</td><td>".ZahlZuText($aklost)."</td></tr>";
        echo "<tr class=\"fieldnormaldark\"><td colspan=\"3\" style=\"font-weight:bold\">Gestohlene Extraktoren</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Metallextraktoren:</td><td> ".$this->gesstolenexenm."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Kristallextraktoren:</td><td>   ".$this->gesstolenexenk."</td></tr>";
        echo "</table>";
       }
       function PrintOverView_ACE()
       {
        $vklost = $vmlost =$aklost = $amlost = 0;
        for($i=0;$i<15;$i++)
        {
            $vklost  += $this->kcost[$i]*$this->geslostshipsdeff[$i];
            $vmlost  += $this->mcost[$i]*$this->geslostshipsdeff[$i];
            $aklost  += $this->kcost[$i]*$this->geslostshipsatt[$i];
            $amlost  += $this->mcost[$i]*$this->geslostshipsatt[$i];
        }
        echo "<table bgcolor=#555555 cellspacing=1>";
        echo "<tr><td colspan=3 align='center'>&Uuml;bersicht</td></tr>";
        echo "<tr bgcolor=#666666><td colspan=3 align='center'>Verlorene Schiffe/Gesch&uuml;tze</td></tr>";
        echo "<tr bgcolor=#777777><td>Typ</td><td align='center'>Angreifer</td><td align='center'>Verteidiger</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>J&auml;ger</td><td>           ".$this->geslostshipsatt[0]."</td><td>".$this->geslostshipsdeff[0]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Bomber</td><td>          ".$this->geslostshipsatt[1]."</td><td>".$this->geslostshipsdeff[1]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Fregatte</td><td>        ".$this->geslostshipsatt[2]."</td><td>".$this->geslostshipsdeff[2]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Zerst&ouml;rer</td><td>       ".$this->geslostshipsatt[3]."</td><td>".$this->geslostshipsdeff[3]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Kreuzer</td><td>     ".$this->geslostshipsatt[4]."</td><td>".$this->geslostshipsdeff[4]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Schlachtschiff</td><td>      ".$this->geslostshipsatt[5]."</td><td>".$this->geslostshipsdeff[5]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Tr&auml;gerschiff</td><td>        ".$this->geslostshipsatt[6]."</td><td>".$this->geslostshipsdeff[6]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Kaperschiff</td><td>     ".$this->geslostshipsatt[7]."</td><td>".$this->geslostshipsdeff[7]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Schutzschiff</td><td>        ".$this->geslostshipsatt[8]."x</td><td>".$this->geslostshipsdeff[8]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Leichtes Orbitalgsch&uuml;tz</td><td></td><td>".$this->geslostshipsdeff[9]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Leichtes Raumgesch&uuml;tz</td><td></td><td>   ".$this->geslostshipsdeff[10]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Mittleres Raumgesch&uuml;tz</td><td></td><td>  ".$this->geslostshipsdeff[11]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Schweres Raumgesch&uuml;tz</td><td></td><td>  ".$this->geslostshipsdeff[12]."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Abfangj&auml;ger</td><td></td><td>            ".$this->geslostshipsdeff[13]."</td></tr>";
        echo "<tr bgcolor=#777777><td align='center' colspan=3>Kosten fr Neubau</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Metall</td><td>$amlost</td><td>$amlost</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Kristall</td><td>$aklost</td><td>$aklost</td></tr>";
        echo "<tr bgcolor=#666666><td colspan=3 align='center'>Gestohlene Extraktoren</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Metallextraktoren:</td><td>    ".$this->gesstolenexenm."</td></tr>";
        echo "<tr class=\"fieldnormallight\"><td>Kristallextraktoren:</td><td>  ".$this->gesstolenexenk."</td></tr>";
        echo "</table></center>";
       }
    }
?>
