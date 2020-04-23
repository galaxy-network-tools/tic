<?

$title = "GN-Kampfsimulator";
//echo "<body bgcolor=#333333 text=#ffffff>";
//include("navi.php");
include("GNSimuclass.php");
// $ticks = $ticks;
// $me = $me;
// $ke = $ke;
$summgunbefore = 0;
if($action == 'compute')
{
    $a[0] = $a1;
    $a[1] = $a2;
    $a[2] = $a3;
    $a[3] = $a4;
    $a[4] = $a5;
    $a[5] = $a6;
    $a[6] = $a7;
    $a[7] = $a8;
    $a[8] = $a9;

    $v[0] = $v1;
    $v[1] = $v2;
    $v[2] = $v3;
    $v[3] = $v4;
    $v[4] = $v5;
    $v[5] = $v6;
    $v[6] = $v7;
    $v[7] = $v8;
    $v[8] = $v9;
    $v[9] = $v10;
    $v[10]= $v11;
    $v[11]= $v12;
    $v[12]= $v13;
    $v[13]= $v14;

    $summgunbefore = $v10+$v11+$v12;


    $gnsimu = new GNSimu();
    for($i=0;$i<14;$i++)
    {
    if($a[$i])
        $gnsimu->attaking[$i] = $a[$i];
    if($v[$i])
        $gnsimu->deffending[$i] = $v[$i];
    }
    $gnsimu->mexen = $me;
    $gnsimu->kexen = $ke;
}
else
{
    for($i=0;$i<15;$i++)
    {
    if($i<10)
        $a[$i]=0;
    $v[$i]=0;
    }
    $me = 0;
    $ke = 0;
    $ticks=1;
}

if($ticks<1)
    $ticks=1;
if($ticks>5)
    $ticks=5;
if($action == 'compute')
{
    if ( $summgunbefore > 0 ){
        $gnsimu->ComputeTwoTickBefore();
        $gnsimu->PrintStatesGun();
        $gnsimu->ComputeOneTickBefore();
        $gnsimu->PrintStatesGun();
    }

    for($i=0;$i<$ticks;$i++)
    {
    $gnsimu->Compute($i==$ticks-1);
    $gnsimu->PrintStates();
    }
    $gnsimu->PrintOverView();
    echo "<br>";
}
//include("foot.php");

?>

