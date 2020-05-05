<?php
    // Filename: inc_zielsuche.php
    // by Bytehoppers from CCBLOCK
    // Variable d[] enthält die Werte
    // Vairable fkt[] enthält die Funktion

    for ($i=0;$i<20;$i++) {
         if (!isset($_GET['d'.$i]))  {
           $d[$i] = '';
         } else {
           $d[$i] = $_GET['d'.$i];
         }
         if (!isset($_GET['fkt'.$i]))  {
           $fkt[$i] = '';
         } else {
           $fkt[$i] = $_GET['fkt'.$i];
         }
    }
    ?>
    <form action="./main.php" method="get">
      <input type="hidden" name="modul" value="scanlist2" />
      <table align="center">
      <tr><td class="datatablehead" colspan="3">Zielsuchsystem</td></tr>
      <tr style="font-weight:bold;" class="fieldnormaldark"><td>Typ</td><td>Funktion</td><td>Wert</td></tr>
    <?php
    $fktopt[0]="=";
    $fktopt[1]="<=";
    $fktopt[2]=">=";

    $color = 0;
    // Schiffe
    for ($i = 0; $i < 10; $i++) {
      if ($i != 7) {
        $color = !$color;
        echo '<tr align="left" class="fieldnormal'.($color ? 'dark' : 'light').'"><td>'.$SF[$i].':</td>';
        echo '<td align="center"><select name="fkt'.$i.'">';
        for ($j=0; $j<3;$j++) {
            echo '<option ';
            if ($fktopt[$j] == $fkt[$i] ) echo 'selected';
            echo '>'.$fktopt[$j].'</option>';
        }
        echo '</select></td>';
        echo '<td><input type="text" name="d'.$i.'" value="'.$d[$i].'" /></td></tr>';
      }
    }
    // Deff
    for ($ix = 0; $ix < 5; $ix++) {
      $i = $ix + 10;
      $color = !$color;
      echo '<tr align="left" class="fieldnormal'.($color ? 'dark' : 'light').'"><td>'.$DF[$ix].':</td>';
      echo '<td align="center"><select name="fkt'.$i.'">';
        for ($j=0; $j<3;$j++) {
            echo '<option ';
            if ($fktopt[$j] == $fkt[$i] ) echo 'selected';
            echo '>'.$fktopt[$j].'</option>';
        }
      echo '</select></td>';
      echo '<td><input type="text" name="d'.$i.'" value="'.$d[$i].'" /></td></tr>';
    }

    // others
    for ($ix = 0; $ix < 3; $ix++) {
      $i = $ix + 16;
      $color = !$color;
      echo '<tr align="left" class="fieldnormal'.($color ? 'dark' : 'light').'"><td>'.$EF[$ix].':</td>';
      echo '<td align="center"><select name="fkt'.$i.'">';
        for ($j=0; $j<3;$j++) {
            echo '<option ';
            if ($fktopt[$j] == $fkt[$i] ) echo 'selected';
            echo '>'.$fktopt[$j].'</option>';
        }
      echo '</select></td>';
      echo '<td><input type="text" name="d'.$i.'" value="'.$d[$i].'" /></td></tr>';
    }
    $color = !$color;
    echo '<tr align="left" class="fieldnormal'.($color ? 'dark' : 'light').'"><td>Ziel angreifbar:</td><td colspan="2"><input type="checkbox" checked="checked"  name="angreifbar" value="1" /></td></tr>
         <tr class="datatablefoot"><td colspan="3" align="center"><input type="submit" name="submit" value="Suchen" /></td></tr>
  </table>
</form>';
?>
