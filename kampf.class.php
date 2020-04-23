<?

/***********************************************************
***  Name: kampf.php                                     ***
***                                                      ***
***  Klasse zur Berechnung der Kampfergebnisse von       ***
***  Galaxy Network.                                     ***
***                                                      ***
***  Rückgabewert: Feld mit Endflotten des Angreifers    ***
***                und Verteidigers. Sowie erbeutete(!!) ***
***                Exis (nicht übrige Exis!)             ***
***                                                      ***
***********************************************************/

class kampf
{
 var
 //Variablen für Basis-Kampfwerte
 $werte = array('jaebom'=>0.392,'jaecoo'=>0.0246,'jaekre'=>0.0263,'jae1'=>0.35,'jae2'=>0.3,'jae3'=>0.35,
                'bomcen'=>0.008,'bompen'=>0.01,'bomtra'=>0.0075,'bom1'=>0.35,'bom2'=>0.35,'bom3'=>0.3,
                'freabf'=>4.5,'frejae'=>0.85,'fre1'=>0.6,'fre2'=>0.4,
                'zerrub'=>3.5,'zerfre'=>1.2444,'zer1'=>0.6,'zer2'=>0.4,
                'krepul'=>2.0,'krezer'=>0.8571,'krecan'=>10.0,'kre1'=>0.35,'kre2'=>0.3,'kre3'=>0.35,
                'pencoo'=>1.0,'penkre'=>1.0666,'penpen'=>0.4,'pentra'=>0.3019,'pencan'=>26.6667,'pen1'=>0.2,'pen2'=>0.2,'pen3'=>0.2,'pen4'=>0.2,'pen5'=>0.2,
                'tracle'=>25.0,'tracan'=>14.0,'tra1'=>0.5,'tra2'=>0.5,
                'rubjae'=>0.3,'rubcle'=>1.28,'rub1'=>0.6,'rub2'=>0.4,
                'pulbom'=>1.2,'pulfre'=>0.5334,'pul1'=>0.4,'pul2'=>0.6,'pulfern1'=>0.5,
                'coozer'=>0.9143,'cookre'=>0.4267,'coo1'=>0.4,'coo2'=>0.6,'coofern1'=>0.5,
                'cenpen'=>0.5,'centra'=>0.3774,'cen1'=>0.5,'cen2'=>0.5,'cenfern1'=>0.6,'cenfern2'=>0.2,
                'abfzer'=>0.0114,'abfcle'=>0.32,'abf1'=>0.4,'abf2'=>0.6);

function berechne($ang,$ver,$roids,$tick)
 {
 //$ang = array (0->jäger,1->bomber,2->fregatten,3->zerstörer,4->kreuzer,5->schlachtschiffe,6->träger,7->cleptoren,
 //              8->cancri)
 //$ver = array (0->jäger,1->bomber,2->fregatten,3->zerstörer,4->kreuzer,5->schlachtschiffe,6->träger,7->cleptoren,
 //              8->cancri,9->rubium,10->pulsar,11->coon,12->centurion,13->abfangjäger)
 //$roids = array (0->metall,1->kristall)
 //$tick = integer (-15, 0, 15, 30, 45, 60, 75)

  $erg = array ($ang['jae'],$ang['bom'],$ang['fre'],$ang['zer'],$ang['kre'],$ang['pen'],$ang['tra'],$ang['cle'],
                $ang['can'],
                $ver['jae'],$ver['bom'],$ver['fre'],$ver['zer'],$ver['kre'],$ver['pen'],$ver['tra'],$ver['cle'],
                $ver['can'],$ver['rub'],$ver['pul'],$ver['coo'],$ver['cen'],$ver['abf'],
                $roids['metall'],$roids['kristall']);
  //Ergebniswerte mit Startflotte initialisieren
  $j=0;
  for ($i=0;$i<9;$i++) //zuerst angreifer
  {
   $erg[$i]=$ang[$j];
   $j++;
  }
  $j=0;
  for ($i=9;$i<23;$i++) //verteidiger
  {
   $erg[$i]=$ver[$j];
   $j++;
  }
  $erg[24]=$roids['metall'];
  $erg[25]=$roids['kristall'];

  //Berechnung 2. Ticks vor Ankunft
  if ($tick==(-$Ticks['lang']))
  {
    //Centurion
    if (($ang[5]==0) or ($ang[6]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[5]>0) {$erg[5]=($erg[5]-(floor($ver[12]*$this->werte['cenpen']*$this->werte['cenfern2'])));}
     if ($ang[6]>0) {$erg[6]=($erg[6]-(floor($ver[12]*$this->werte['centra']*$this->werte['cenfern2'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[5]=($erg[5]-(floor($ver[12]*$this->werte['cenpen']*$this->werte['cen1']*$this->werte['cenfern2'])));
     $erg[6]=($erg[6]-(floor($ver[12]*$this->werte['centra']*$this->werte['cen2']*$this->werte['cenfern2'])));
    }
  }
  if (($tick>-$Ticks['lang']) and ($tick<$Ticks['lang'])) //berechnung 1 tick vor kampf
  {
    //Centurion
    if (($ang[5]==0) or ($ang[6]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[5]>0) {$erg[5]=($erg[5]-(floor($ver[12]*$this->werte['cenpen']*$this->werte['cenfern1'])));}
     if ($ang[6]>0) {$erg[6]=($erg[6]-(floor($ver[12]*$this->werte['centra']*$this->werte['cenfern1'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[5]=($erg[5]-(floor($ver[12]*$this->werte['cenpen']*$this->werte['cen1']*$this->werte['cenfern1'])));
     $erg[6]=($erg[6]-(floor($ver[12]*$this->werte['centra']*$this->werte['cen2']*$this->werte['cenfern1'])));
    }

    //Coon
    if (($ang[3]==0) or ($ang[4]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[3]>0) {$erg[3]=($erg[3]-(floor($ver[11]*$this->werte['coozer']*$this->werte['coofern1'])));}
     if ($ang[4]>0) {$erg[4]=($erg[4]-(floor($ver[11]*$this->werte['cookre']*$this->werte['coofern1'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[3]=($erg[3]-(floor($ver[11]*$this->werte['coozer']*$this->werte['coo1']*$this->werte['coofern1'])));
     $erg[4]=($erg[4]-(floor($ver[11]*$this->werte['cookre']*$this->werte['coo2']*$this->werte['coofern1'])));
    }

    //Pulsar (wenn Bomber vorhanden trotzdem Feuerteilung?)
    if ($ang[2]>0) //Bomber in Träger und daher unrelevant?
    {
     $erg[2]=($erg[2]-(floor($ver[10]*$this->werte['pulfre']*$this->werte['pulfern1'])));
    }
  }
  if ($tick>0)
  {
    //Rubium
    if (($ang[0]==0) or ($ang[7]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[0]>0) {$erg[0]=($erg[0]-(floor($ver[9]*$this->werte['rubjae'])));}
     if ($ang[7]>0) {$erg[7]=($erg[7]-(floor($ver[9]*$this->werte['rubcle'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[0]=($erg[0]-(floor($ver[9]*$this->werte['rubjae']*$this->werte['rub1'])));
     $erg[7]=($erg[7]-(floor($ver[9]*$this->werte['rubcle']*$this->werte['rub2'])));
    }

    //Pulsar
    if (($ang[1]==0) or ($ang[2]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[1]>0) {$erg[1]=($erg[1]-(floor($ver[10]*$this->werte['pulbom'])));}
     if ($ang[2]>0) {$erg[2]=($erg[2]-(floor($ver[10]*$this->werte['pulfre'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[1]=($erg[1]-(floor($ver[10]*$this->werte['pulbom']*$this->werte['pul1'])));
     $erg[2]=($erg[2]-(floor($ver[10]*$this->werte['pulfre']*$this->werte['pul2'])));
    }

    //Coon
    if (($ang[3]==0) or ($ang[4]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[3]>0) {$erg[3]=($erg[3]-(floor($ver[11]*$this->werte['coozer'])));}
     if ($ang[4]>0) {$erg[4]=($erg[4]-(floor($ver[11]*$this->werte['cookre'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[3]=($erg[3]-(floor($ver[11]*$this->werte['coozer']*$this->werte['coo1'])));
     $erg[4]=($erg[4]-(floor($ver[11]*$this->werte['cookre']*$this->werte['coo2'])));
    }

    //Centurion
    if (($ang[5]==0) or ($ang[6]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[5]>0) {$erg[5]=($erg[5]-(floor($ver[12]*$this->werte['cenpen'])));}
     if ($ang[6]>0) {$erg[6]=($erg[6]-(floor($ver[12]*$this->werte['centra'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[5]=($erg[5]-(floor($ver[12]*$this->werte['cenpen']*$this->werte['cen1'])));
     $erg[6]=($erg[6]-(floor($ver[12]*$this->werte['centra']*$this->werte['cen2'])));
    }

    //Abfangjaeger
    if (($ang[3]==0) or ($ang[7]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[3]>0) {$erg[3]=($erg[3]-(floor($ver[13]*$this->werte['abfzer'])));}
     if ($ang[7]>0) {$erg[7]=($erg[7]-(floor($ver[13]*$this->werte['abfcle'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[3]=($erg[3]-(floor($ver[13]*$this->werte['abfzer']*$this->werte['abf1'])));
     $erg[7]=($erg[7]-(floor($ver[13]*$this->werte['abfcle']*$this->werte['abf2'])));
    }

    //Jäger Verteidiger (Verteidiger-Jäger können nie gegen Coon kämpfen!)
    if (($ang[1]==0) or ($ang[4]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[1]>0) {$erg[1]=($erg[1]-(floor($ver[0]*$this->werte['jaebom'])));}
     if ($ang[4]>0) {$erg[4]=($erg[4]-(floor($ver[0]*$this->werte['jaekre'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[1]=($erg[1]-(floor($ver[0]*$this->werte['jaebom']*($this->werte['jae2']+($this->werte['jae1']/2))))); //Feuerkraft die nicht für Coon benötigt wird, wird aufgeteilt
     $erg[4]=($erg[4]-(floor($ver[0]*$this->werte['jaekre']*($this->werte['jae2']+($this->werte['jae1']/2)))));
    }

    //Bomber Verteidiger (Verteidiger-Bomber können nie gegen Centurion kämpfen!)
    if (($ang[5]==0) or ($ang[6]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[5]>0) {$erg[5]=($erg[6]-(floor($ver[1]*$this->werte['bompen'])));}
     if ($ang[6]>0) {$erg[6]=($erg[5]-(floor($ver[1]*$this->werte['bomtra'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[5]=($erg[5]-(floor($ver[1]*$this->werte['bompen']*($this->werte['bom2']+($this->werte['bom1']/2)))));
     $erg[6]=($erg[6]-(floor($ver[1]*$this->werte['bomtra']*($this->werte['bom2']+($this->werte['bom1']/2)))));
    }

    //Fregatten Verteidiger (Verteidiger-Fregatten können nie gegen AJs kämpfen!)
     if ($ang[0]>0) {$erg[0]=($erg[0]-(floor($ver[2]*$this->werte['frejae'])));}

    //Zerstörer Verteidiger (Verteidiger-Zerstörer können nie gegen Rubium kämpfen!)
     if ($ang[2]>0) {$erg[2]=($erg[2]-(floor($ver[3]*$this->werte['zerfre'])));}

    //Kreuzer Verteidiger (Verteidiger-Kreuzer können nie gegen Pulsar und Cancri kämpfen!)
    if (($ang[3]==0) or ($ang[8]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[3]>0) {$erg[3]=($erg[3]-(floor($ver[4]*$this->werte['krezer'])));}
     if ($ang[8]>0) {$erg[8]=($erg[8]-(floor($ver[4]*$this->werte['krecan'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[3]=($erg[3]-(floor($ver[4]*$this->werte['krezer']*($this->werte['kre2']+($this->werte['kre1']/2)))));
     $erg[8]=($erg[8]-(floor($ver[4]*$this->werte['krecan']*($this->werte['kre3']+($this->werte['kre1']/2)))));
    }

    //Schlachtschiff Verteidiger (Verteidiger-Schlachties können nie gegen Coon kämpfen!)
    //zusätzliches (freies) Feuer ermitteln
    $free=0.2; $anzfeuer=4;
    if ($ang[4]<=0) {$free=$free+$this->werte['pen2']; $anzfeuer--;} //keine Kreuzer auf die gefeuert werden muss
    if ($ang[5]<=0) {$free=$free+$this->werte['pen3']; $anzfeuer--;} //keine Schlachties auf die gefeuert werden muss
    if ($ang[6]<=0) {$free=$free+$this->werte['pen4']; $anzfeuer--;} //keine Traeger auf die gefeuert werden muss
    if ($ang[8]<=0) {$free=$free+$this->werte['pen5']; $anzfeuer--;} //keine Cancri auf die gefeuert werden muss
    if ($anzfeuer==0) {$anzfeuer=1;$free=0;} //gar keine Schiffe da

    if ($ang[4]>0) {$erg[4]=($erg[4]-(floor($ver[5]*(($free/$anzfeuer)+$this->werte['pen2'])*$this->werte['penkre'])));}
    if ($ang[5]>0) {$erg[5]=($erg[5]-(floor($ver[5]*(($free/$anzfeuer)+$this->werte['pen3'])*$this->werte['penpen'])));}
    if ($ang[6]>0) {$erg[6]=($erg[6]-(floor($ver[5]*(($free/$anzfeuer)+$this->werte['pen4'])*$this->werte['pentra'])));}
    if ($ang[8]>0) {$erg[8]=($erg[8]-(floor($ver[5]*(($free/$anzfeuer)+$this->werte['pen5'])*$this->werte['pencan'])));}

    //Traeger Verteidiger
    if (($ang[7]==0) or ($ang[8]==0)) //Nur einer der beiden Schiffstypen vorhanden
    {
     if ($ang[7]>0) {$erg[7]=($erg[7]-(floor($ver[6]*$this->werte['tracle'])));}
     if ($ang[8]>0) {$erg[8]=($erg[8]-(floor($ver[6]*$this->werte['tracan'])));}
    }
    else //Beide Schiffstypen vorhanden
    {
     $erg[7]=($erg[7]-(floor($ver[6]*$this->werte['tracle']*$this->werte['tra1'])));
     $erg[8]=($erg[8]-(floor($ver[6]*$this->werte['tracan']*$this->werte['tra2'])));
    }

    //Jaeger Angreifer
    //zusätzliches (freies) Feuer ermitteln
    $free=0;$anzfeuer=3; //alle möglichen Gegner da
    if ($ver[11]<=0) {$free=$free+$this->werte['jae1']; $anzfeuer--;} //keine Coon auf die gefeuert werden muss
    if ($ver[1]<=0) {$free=$free+$this->werte['jae2']; $anzfeuer--;} //keine Bomber auf die gefeuert werden muss
    if ($ver[4]<=0) {$free=$free+$this->werte['jae3']; $anzfeuer--;} //keine kreuzer auf die gefeuert werden muss
    if ($anzfeuer==0) {$anzfeuer=1;$free=0;} //gar keine Schiffe da

    if ($ver[11]>0) {$erg[20]=($erg[20]-(floor($ang[0]*(($free/$anzfeuer)+$this->werte['jae2'])*$this->werte['jaecoo'])));}
    if ($ver[1]>0) {$erg[10]=($erg[10]-(floor($ang[0]*(($free/$anzfeuer)+$this->werte['jae1'])*$this->werte['jaebom'])));}
    if ($ver[4]>0) {$erg[13]=($erg[13]-(floor($ang[0]*(($free/$anzfeuer)+$this->werte['jae3'])*$this->werte['jaekre'])));}

    //Bomber Angreifer
    //zusätzliches (freies) Feuer ermitteln
    $free=0;$anzfeuer=3; //alle möglichen Gegner da
    if ($ver[12]<=0) {$free=$free+$this->werte['bom1']; $anzfeuer--;} //keine Centurion auf die gefeuert werden muss
    if ($ver[5]<=0) {$free=$free+$this->werte['bom2']; $anzfeuer--;} //keine Schlachties auf die gefeuert werden muss
    if ($ver[6]<=0) {$free=$free+$this->werte['bom3']; $anzfeuer--;} //keine Traeger auf die gefeuert werden muss
    if ($anzfeuer==0) {$anzfeuer=1;$free=0;} //gar keine Schiffe da

    if ($ver[12]>0) {$erg[21]=($erg[21]-(floor($ang[1]*(($free/$anzfeuer)+$this->werte['bom1'])*$this->werte['bomcen'])));}
    if ($ver[5]>0) {$erg[14]=($erg[14]-(floor($ang[1]*(($free/$anzfeuer)+$this->werte['bom2'])*$this->werte['bompen'])));}
    if ($ver[6]>0) {$erg[15]=($erg[15]-(floor($ang[1]*(($free/$anzfeuer)+$this->werte['bom3'])*$this->werte['bomtra'])));}

    //Fregatten Angreifer
    //zusätzliches (freies) Feuer ermitteln
    $free=0;$anzfeuer=2; //alle möglichen Gegner da
    if ($ver[13]<=0) {$free=$free+$this->werte['fre1']; $anzfeuer--;} //keine AJs auf die gefeuert werden muss
    if ($ver[0]<=0) {$free=$free+$this->werte['fre2']; $anzfeuer--;} //keine Jaeger auf die gefeuert werden muss
    if ($anzfeuer==0) {$anzfeuer=1;$free=0;} //gar keine Schiffe da

    if ($ver[13]>0) {$erg[22]=($erg[22]-(floor($ang[2]*(($free/$anzfeuer)+$this->werte['fre1'])*$this->werte['freabf'])));}
    if ($ver[0]>0) {$erg[9]=($erg[9]-(floor($ang[2]*(($free/$anzfeuer)+$this->werte['fre2'])*$this->werte['frejae'])));}

    //Zerstoerer Angreifer
    //zusätzliches (freies) Feuer ermitteln
    $free=0;$anzfeuer=2; //alle möglichen Gegner da
    if ($ver[9]<=0) {$free=$free+$this->werte['zer1']; $anzfeuer--;} //keine Rubium auf die gefeuert werden muss
    if ($ver[2]<=0) {$free=$free+$this->werte['zer2']; $anzfeuer--;} //keine Fregatten auf die gefeuert werden muss
    if ($anzfeuer==0) {$anzfeuer=1;$free=0;} //gar keine Schiffe da

    if ($ver[9]>0) {$erg[18]=($erg[18]-(floor($ang[3]*(($free/$anzfeuer)+$this->werte['zer1'])*$this->werte['zerrub'])));}
    if ($ver[2]>0) {$erg[11]=($erg[11]-(floor($ang[3]*(($free/$anzfeuer)+$this->werte['zer2'])*$this->werte['zerfre'])));}

    //Kreuzer Angreifer
    //zusätzliches (freies) Feuer ermitteln
    $free=0;$anzfeuer=3; //alle möglichen Gegner da
    if ($ver[10]<=0) {$free=$free+$this->werte['kre1']; $anzfeuer--;} //keine Pulsar auf die gefeuert werden muss
    if ($ver[3]<=0) {$free=$free+$this->werte['kre2']; $anzfeuer--;} //keine Zerstoerer auf die gefeuert werden muss
    if ($ver[8]<=0) {$free=$free+$this->werte['kre3']; $anzfeuer--;} //keine Cancri auf die gefeuert werden muss
    if ($anzfeuer==0) {$anzfeuer=1;$free=0;} //gar keine Schiffe da

    if ($ver[10]>0) {$erg[19]=($erg[19]-(floor($ang[4]*(($free/$anzfeuer)+$this->werte['kre1'])*$this->werte['krepul'])));}
    if ($ver[3]>0) {$erg[12]=($erg[12]-(floor($ang[4]*(($free/$anzfeuer)+$this->werte['kre2'])*$this->werte['krezer'])));}
    if ($ver[8]>0) {$erg[17]=($erg[17]-(floor($ang[4]*(($free/$anzfeuer)+$this->werte['kre3'])*$this->werte['krecan'])));}

    //Schlachtschiffe Angreifer
    //zusätzliches (freies) Feuer ermitteln
    $free=0;$anzfeuer=5; //alle möglichen Gegner da
    if ($ver[11]<=0) {$free=$free+$this->werte['pen1']; $anzfeuer--;} //keine Coon auf die gefeuert werden muss
    if ($ver[4]<=0) {$free=$free+$this->werte['pen2']; $anzfeuer--;} //keine Kreuzer auf die gefeuert werden muss
    if ($ver[5]<=0) {$free=$free+$this->werte['pen3']; $anzfeuer--;} //keine Schlachties auf die gefeuert werden muss
    if ($ver[6]<=0) {$free=$free+$this->werte['pen4']; $anzfeuer--;} //keine Traeger auf die gefeuert werden muss
    if ($ver[8]<=0) {$free=$free+$this->werte['pen5']; $anzfeuer--;} //keine Cancri auf die gefeuert werden muss
    if ($anzfeuer==0) {$anzfeuer=1;$free=0;} //gar keine Schiffe da

    if ($ver[11]>0) {$erg[20]=($erg[20]-(floor($ang[5]*(($free/$anzfeuer)+$this->werte['pen1'])*$this->werte['pencoo'])));}
    if ($ver[4]>0) {$erg[13]=($erg[13]-(floor($ang[5]*(($free/$anzfeuer)+$this->werte['pen2'])*$this->werte['penkre'])));}
    if ($ver[5]>0) {$erg[14]=($erg[14]-(floor($ang[5]*(($free/$anzfeuer)+$this->werte['pen3'])*$this->werte['penpen'])));}
    if ($ver[6]>0) {$erg[15]=($erg[15]-(floor($ang[5]*(($free/$anzfeuer)+$this->werte['pen4'])*$this->werte['pentra'])));}
    if ($ver[8]>0) {$erg[17]=($erg[17]-(floor($ang[5]*(($free/$anzfeuer)+$this->werte['pen5'])*$this->werte['pencan'])));}

    //Traeger Angreifer
    //zusätzliches (freies) Feuer ermitteln
    $free=0;$anzfeuer=2; //alle möglichen Gegner da
    if ($ver[7]<=0) {$free=$free+$this->werte['tra1']; $anzfeuer--;} //keine Cleptoren auf die gefeuert werden muss
    if ($ver[8]<=0) {$free=$free+$this->werte['tra2']; $anzfeuer--;} //keine Cancri auf die gefeuert werden muss
    if ($anzfeuer==0) {$anzfeuer=1;$free=0;} //gar keine Schiffe da

    if ($ver[7]>0) {$erg[16]=($erg[16]-(floor($ang[6]*(($free/$anzfeuer)+$this->werte['tra1'])*$this->werte['tracle'])));}
    if ($ver[8]>0) {$erg[17]=($erg[17]-(floor($ang[6]*(($free/$anzfeuer)+$this->werte['tra2'])*$this->werte['tracan'])));}

    //Verteidiger-Cancri blocken nun Angreifer-Cleps
    if ($erg[7]<0) {$erg[7]=0;}
    if ($erg[17]<0) {$erg[17]=0;}
    $freetoroid=$erg[7]-$erg[17]; //ergibt noch freie Cleps, die nun roiden können
    if ($freetoroid<0) {$freetoroid=0;}

    //übrige freie Cleps roiden nun :)
    $roidmetall=floor($roids[0]*0.1); //maximale ausbeute metall-exis
    $metall=$roidmetall;
    $roidkristall=floor($roids[1]*0.1); //maximale ausbeute kristall-exis
    if ((($roidmetall+$roidkristall)>$freetoroid) and ($freetoroid>0)) //cleps reichen nicht, um maximal ausbeute zu erzielen
    {
     $metall=floor($freetoroid*($roidmetall/($roidmetall+$roidkristall))); //cleps werden anhand des verhältnisses von metall- und kristallexis verteilt
     $roidkristall=floor($freetoroid*($roidkristall/($roidmetall+$roidkristall)));

    }
    if ($freetoroid<=0)
    {
     $metall=0; $roidkristall=0;
    }
    $erg[23]=$metall;
    $erg[24]=$roidkristall;
    $erg[7]=$erg[7]-$roidmetall-$roidkristall; //cleptoren die exis kapern gehen verloren!
  }

  //negative Werte auf Null setzen
  for ($i=0;$i<25;$i++)
  {
   if ($erg[$i]<0) {$erg[$i]=0;}
  }

  return $erg;
 } //Ende Funktion berechne

} //Ende der Klasse Kampf



?>
